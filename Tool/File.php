<?php
	class File
	{
		private $id;
		private $url;
		private $data;
		private $name;
		private $extension;
		private $mime;
		private $size;
		private $hash;
		private $lastModified;
		
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
		
		public static function config($file)
		{
			if(!file_exists($file))
				throw new ConfigException('No config file');
			
			if(!($config = json_decode(file_get_contents($file), true)))
				throw new ConfigException('Cannot decode JSON');
			
			foreach($config as $files)
				foreach($files as list('file' => $file))
					if(!file_exists($file))
						throw new ConfigException('Cannot find file: ' . $file);
			
			$addFile = Connection::prepare('INSERT INTO file (url, data, name, extension, mime, size, hash) VALUE (?, ?, ?, ?, ?, ?, ?)');
			
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
			$res = Connection::prepare('SELECT url, data, name, extension, mime, size, hash, UNIX_TIMESTAMP(lastModified) as lM FROM file WHERE id = ?')->execute($id);
			
			if($res)
			{
				$res = $res[0];
				
				$new = new File();
				
				$new->id = $id;
				$new->url = $res['url'];
				$new->data = $res['data'];
				$new->name = $res['name'];
				$new->extension = $res['extension'];
				$new->mime = $res['mime'];
				$new->size = $res['size'];
				$new->hash = $res['hash'];
				$new->lastModified = $res['lM'];
				
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
				
				$new = new File();
				
				$new->url = $url;
				$new->id = $res['id'];
				$new->data = $res['data'];
				$new->name = $res['name'];
				$new->extension = $res['extension'];
				$new->mime = $res['mime'];
				$new->size = $res['size'];
				$new->hash = $res['hash'];
				$new->lastModified = $res['lM'];
				
				return $new;
			}
			else
				return false;
		}
		
		private function __construct() {}
		
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
		
		public function id()
		{
			return $this->id;
		}
		
		public function url()
		{
			return $this->url;
		}
		
		public function data()
		{
			return $this->data;
		}
		
		public function name()
		{
			return $this->name;
		}
		
		public function extension()
		{
			return $this->extension;
		}
		
		public function mime()
		{
			return $this->mime;
		}
		
		public function size()
		{
			return $this->size;
		}
		
		public function hash()
		{
			return $this->hash;
		}
		
		public function lastModified()
		{
			return $this->lastModified;
		}
	}
?>