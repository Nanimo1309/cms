<?php
	abstract class Page
	{
		private $forms = [];
		private $url;
		private $args;
		
		/*
		{URL}:
		{
			"file": {File contained page},
			"class": {Name of page class}
		}
		*/
		
		public static function config($file)
		{
			if(!file_exists($file))
				throw new ConfigException('No config file');
			
			if(!($config = json_decode(file_get_contents($file), true)))
				throw new ConfigException('Cannot decode JSON');
			
			foreach($config as list('file' => $file, 'class' => $class))
			{
				if(!file_exists($file))
					throw new ConfigException('Cannot find file: ' . $file);
				
				include_once($file);
				
				if(!class_exists($class))
					throw new ConfigException('No class with name: ' . $class);
				
				if(!is_subclass_of($class, 'Page'))
					throw new ConfigException('Class ' . $class . ' is not instance of Page');
			}
			
			$addPage = Connection::prepare('INSERT INTO page (file, class, url) VALUE (?, ?, ?)');
			
			Connection::prepare('DELETE FROM page')->execute();
			
			foreach($config as $url => list('file' => $file, 'class' => $class))
				$addPage->execute($file, $class, strrev($url));
		}
		
		final public function __construct($url, $args)
		{
			if(!$url && $url[0])
				$this->url = $url;
			else
				$this->url = [];
			
			$this->args = $args;
		}
		
		public function construct() {}
		
		abstract public function load();
		abstract public function write();
		
		final public function addForm($form)
		{
			$this->forms[$form->name()] = $form;
			return $form;
		}
		
		final public function getForm($name)
		{
			return $this->forms[$name];
		}
		
		final public function processForms()
		{
			foreach($this->forms as $form)
				if($form->action())
					return true;
			
			return false;
		}
		
		public function url()
		{
			return $this->url;
		}
		
		public function args()
		{
			return $this->args;
		}
	}
?>