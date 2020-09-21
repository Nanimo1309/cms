<?php
	include_once 'Tool/File.php';
	
	class FilePage extends Page
	{
		private $file;
		private $attachment;
		
		public function load()
		{
			$this->file = File::load(Request::wholeUrl());
			
			if(!$this->file)
			{
				Controller::load('/404');
				return;
			}
			
			header('Content-Disposition: filename="' . $this->file->name . '.' . $this->file->extension . '"');
			header('Cache-Control: max-age=3600, no-cache');
			header('ETag: "' . $this->file->hash . '"');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $this->file->lastModified) . ' GMT');
			
			if($this->notModified())
			{
				http_response_code(304);
				$this->file = null;
				return;
			}
			
			$this->encode();
			
			header('Content-Type: ' . $this->file->mime . '; charset=utf-8');
			header('Content-Length: ' . $this->file->size);
		}
		
		public function write()
		{
			if($this->file)
				echo $this->file->data;
		}
		
		protected function notModified()
		{
			$modifiedSince = Request::ifModifiedSince();
			return Request::ifNoneMatch() == $this->file->hash && $modifiedSince && strtotime($modifiedSince) == $this->file->lastModified;
		}
		
		protected function encode()
		{
			foreach(Request::encoding() as $encode)
			{
				if($this->file->encode($encode))
				{
					header('Content-Encoding: ' . $encode);
					return;
				}
			}
			
			header('Content-Encoding: identity');
		}
	}
?>