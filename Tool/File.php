<?php
	class File
	{
		public $id;
		public $url;
		public $data;
		public $name;
		public $extension;
		public $mime;
		public $size;
		public $hash;
		public $lastModified;
		
		/*
		{mime}:
		{
			{URL}:
			{
				"file": {File},
				"name": {Name in base},
				"extension": {Extension in base}
			}
		}
		*/
		
		public static function config($config, $delete = true)
		{
			foreach($config as $files)
				foreach($files as list('file' => $file))
					if(!file_exists($file))
						throw new ConfigException('Cannot find file: ' . $file);
			
			$addFile = Connection::prepare('INSERT INTO file (url, data, name, extension, mime, size, hash) VALUE (?, ?, ?, ?, ?, ?, ?)');
			
			if($delete)
				Connection::prepare('DELETE FROM file')->execute();
			
			foreach($config as $mime => $files)
			{
				foreach($files as $url => list('file' => $file, 'name' => $name, 'extension' => $extension))
				{
					$data = file_get_contents($file);
					$addFile->execute($url, $data, $name, $extension, $mime, strlen($data), hash('sha256', $data));
				}
			}
		}
		
		public static function loadId($id)
		{
			$res = Connection::prepare('SELECT url, data, name, extension, mime, size, hash, UNIX_TIMESTAMP(lastModified) as lM FROM file WHERE id = ?')->num()->execute($id);
			
			if($res)
			{
				$res = $res[0];
				
				$new = new File($id, ...$res);
				
				return $new;
			}
			else
				return false;
		}
		
		public static function load($url)
		{
			$res = Connection::prepare('SELECT id, data, name, extension, mime, size, hash, UNIX_TIMESTAMP(lastModified) as lM FROM file WHERE url = ?')->execute($url);
			
			if($res)
			{
				$res = $res[0];
				
				$id = $res['id'];
				unset($res['id']);
				
				$res = array_values($res);
				
				$new = new File($id, $url, ...$res);
				
				return $new;
			}
			else
				return false;
		}
		
		private function __construct($id, $url, $data, $name, $extension, $mime, $size, $hash, $lastModified)
		{
			$this->id = $id;
			$this->url = $url;
			$this->data = $data;
			$this->name = $name;
			$this->extension = $extension;
			$this->mime = $mime;
			$this->size = $size;
			$this->hash = $hash;
			$this->lastModified = $lastModified;
		}
		
		public function encode($encode)
		{
			switch($encode)
			{
			case 'gzip':
				$this->data = gzencode($this->data);
				$this->size = strlen($this->data);
				break;
			case 'compress':
				$this->data = gzcompress($this->data);
				$this->size = strlen($this->data);
				break;
			case 'deflate':
				$this->data = gzdeflate($this->data);
				$this->size = strlen($this->data);
				break;
			case 'identity':
				break;
			default:
				return false;
			}
			
			return true;
		}
	}
?>