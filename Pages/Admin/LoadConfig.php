<?php
	include_once 'Tool/User.php';
	
	class LoadConfig extends Page
	{
		private $exceptions = [];
		
		public function construct()
		{
			/*User::init();
			
			if(!User::checkRights(2))
				Controller::load('/403');*/
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
							'tAccount',
							'tFile',
							'tDictionary',
							'tOffer'
						];
						
						$parsed = array_merge($parsed, $temp);
						break;
					case 'configs':
						$temp =
						[
							'pages',
							'users',
							'files',
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
					case 'tAccount':
						$this->sql('Config/Base/account.sql');
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
						
						
					case 'pages':
						$this->loadConfigFile(null, 'Page', 'Config/pages.json');
						break;
					case 'users':
						$this->loadConfigFile('Tool/User.php', 'User', 'Config/users.json');
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
						
						
					default:
						$this->exceptions[] = 'No config with name: ' . $arg;
						break;
					}
				}
			}
			else
				$this->exceptions[] = 'No args';
			
		}
		
		public function write()
		{
			if($this->exceptions)
			{
				
				foreach($this->exceptions as $e)
					if($e instanceof Throwable)
						Debug::desc($e);
					else
						echo $e . '<br />';
			}
			else
				echo 'Completed!<br />';
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
				
				$class::config($json);
			}
			catch(Throwable $e)
			{
				$this->exceptions[] = $e;
			}
		}
		
		public static function initLoad()
		{
			Connection::init();
			
			$load = new LoadConfig(null, ['tables', 'configs']);
			$load->load();
			$load->write();
			
			Connection::close();
		}
	}
?>