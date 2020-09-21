<?php
	class LoadConfig extends Page
	{
		private $exceptions = [];
		
		public function construct()
		{
			//Controller::load('/403'); // Comment to disable/enable
		}
		
		public function load()
		{	
			$args = $this->args();
			
			if($args)
			{
				$parsed = [];
				
				foreach($args as $arg)
				{
					switch($arg)
					{
					case 'tables':
						$temp =
						[
							'tDrop',
							'tPage',
							'tFile',
							'tGallery',
							'tDictionary',
							'tOffer'
						];
						
						$parsed = array_merge($parsed, $temp);
						break;
					case 'configs':
						$temp =
						[
							'pages',
							'files',
							'galleries',
							'dictionary',
							'offers'
						];
						
						$parsed = array_merge($parsed, $temp);
						break;
					default:
						$parsed[] = $arg;
						break;
					}
				}
				
				foreach($parsed as $arg)
				{
					switch($arg)
					{
					case 'tDrop':
						$this->sql('Config/Base/dropTables.sql');
						break;
					case 'tPage':
						$this->sql('Config/Base/page.sql');
						break;
					case 'tFile':
						$this->sql('Config/Base/file.sql');
						break;
					case 'tDictionary':
						$this->sql('Config/Base/dictionary.sql');
						break;
					case 'tOffer':
						$this->sql('Config/Base/offer.sql');
						break;
					case 'tGallery':
						$this->sql('Config/Base/gallery.sql');
						break;
						
						
					case 'pages':
						$this->loadConfigFile(null, 'Page', 'Config/pages.json');
						break;
					case 'files':
						$this->loadConfigFile('Tool/File.php', 'File', 'Config/files.json');
						break;
					case 'dictionary':
						$this->loadConfigFile('Tool/Lang.php', 'Lang', 'Config/dictionary.json');
						break;
					case 'offers':
						$this->loadConfigFile('Tool/Offer.php', 'Offer', 'Config/offers.json');
						break;
					case 'galleries':
						$this->loadConfigFile('Tool/Gallery.php', 'Gallery', 'Config/galleries.json');
						break;
						
						
					default:
						$this->exceptions[] = 'No config with name: ' . $arg;
						break;
					}
				}
			}
			else
				$this->exceptions[] = 'No args';
			
			
			if(!$this->exceptions && !empty($_GET))
				Controller::load(str_replace('_', ' ', array_values(array_flip($_GET))[0]));
		}
		
		public function write()
		{
			if($this->exceptions)
			{
				foreach($this->exceptions as $e)
					Debug::desc($e);
			}
			else
				echo 'Complete!';
		}
		
		private function sql($file)
		{
			try
			{
				if(!file_exists($file))
					throw new ConfigException('Cannot find file: ' . $file);
				
				$queries = file_get_contents($file);
					
				foreach(explode(';', $queries, -1) as $q)
					Connection::prepare($q)->execute();
			}
			catch(Throwable $e)
			{
				$this->exceptions[] = $e;
			}
		}
		
		private function loadConfigFile($file, $class, $json)
		{
			try
			{
				if($file)
				{
					if(file_exists($file))
						include_once $file;
					else
						throw new ConfigException('Cannot find file: ' . $file);
				}
				
				if(!file_exists($json))
					throw new ConfigException('No config file');
				
				if(!($config = json_decode(file_get_contents($json), true)))
					throw new ConfigException('Cannot decode JSON');
				
				$class::config($config);
			}
			catch(Throwable $e)
			{
				$this->exceptions[] = $e;
			}
		}
		
		public static function loadAll()
		{
			Connection::init();
			
			$load = new LoadConfig(null, ['tables', 'configs']);
			$load->load();
			$load->write();
			
			Connection::close();
		}
	}
?>