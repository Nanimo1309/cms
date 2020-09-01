<?php
	include_once 'Tool/File.php';
	
	class FilePage extends Page
	{
		private $file;
		private $disposition;
		
		public function load()
		{
			$this->file = File::load(Request::wholeUrl());
			
			if(!$this->file)
			{
				Controller::load('/404');
				return;
			}
			
			if($accept = Request::accept())
			{
				if($accept[0] == 'text/html')
					$this->disposition(false); // In new tab
				else
					$this->disposition(true); // On page
			}
			else
				$this->disposition(true); // On save
			
			header('Cache-Control: max-age=3600, no-cache');
			
			header('ETag: "' . $this->file->hash() . '"');
			
			if($this->notModified())
			{
				http_response_code(304);
				$this->file = null;
				return;
			}
			
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $this->file->lastModified()) . ' GMT');
			
			$this->encode();
			
			header('Content-Type: ' . $this->file->mime() . '; charset=utf-8');
			header('Content-Length: ' . $this->file->size());
		}
		
		public function write()
		{
			if($this->file)
				echo $this->file->data();
		}
		
		protected function notModified()
		{
			$modifiedSince = Request::ifModifiedSince();
			return Request::ifNoneMatch() == $this->file->hash() && $modifiedSince && strtotime($modifiedSince) == $this->file->lastModified();
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
		
		protected function disposition($attachment)
		{
			header('Content-Disposition: ' . ($attachment ? 'inline' : 'attachment') . '; filename="' . $this->file->name() . '.' . $this->file->extension() . '"');
		}
	}
?>