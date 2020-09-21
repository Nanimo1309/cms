<?php
	class Controller
	{
		private static $page = null;
		private static $pagesCount = 0;
		
		public static function init()
		{
			if(preg_match('~/{2,}~', Request::url())) // Deletes two slashes in url
				self::location(preg_replace('~/{2,}~', '/', Request::url()), 301);
			
			ob_start();
			
			Connection::init();
			
			if(!self::load(Request::url()))
				self::noPage();
			
			if(error_reporting())
				ob_end_flush();
			else
				ob_end_clean();
			
			self::$page->write();
			
			Connection::close();
		}
		
		public static function load($url)
		{
			$find = Connection::prepare('SELECT url, file, class FROM page WHERE POSITION(CONCAT(url, ?) IN CONCAT(?, ?)) = 1 ORDER BY LENGTH(url) DESC LIMIT 1');
			
			$page = $find->execute('/', $url, '/');
			
			if(!$page)
			{
				$page = $find->execute('', '/', '');
				
				if(!$page)
					return false;
			}
			
			$page = $page[0];
			
			$urlLen = strlen($page['url']);
			
			$args = explode('/', substr($url, $urlLen > 1 ? $urlLen + 1: 1));
			$url = explode('/', substr($page['url'], 1));
			
			if(!$url[0])
				$url = [];
			
			if(!$args[0])
				$args = [];
			
			++self::$pagesCount;
			$pagesCount = self::$pagesCount;
			
			include_once($page['file']);
			self::$page = new $page['class']($url, $args);
			self::$page->construct();
			
			if((!empty($_POST) || !empty($_GET)) && self::$pagesCount == $pagesCount)
			{
				Form::init();
				if(!self::$page->processForms() && Request::method() == 'POST')
					Controller::reload();
			}
			
			if(self::$pagesCount == $pagesCount)
				self::$page->load();
			
			return true;
		}
		
		public static function location($location, $code)
		{
			header("Location: $location", true, $code);
			exit;
		}
		
		public static function reload()
		{
			self::location(Request::wholeUrl(), 303);
		}
		
		public static function goTo($location)
		{
			self::location($location, 303);
		}
		
		public static function notFound()
		{
			self::location('/404', 404);
		}
		
		public static function forbidden()
		{
			self::location('/403', 403);
		}
		
		public static function updateGet()
		{
			$uri = preg_replace("~\\?(.*)$~", "", $_SERVER['REQUEST_URI']);
			$get = "";
			
			foreach($_GET as $key => $value)
				$get .= $key . "=" . $value . "&";
			
			if($get != "")
				$uri .= "?" . substr($get, 0, -1);
			
			self::location($uri, 303);
		}
		
		public static function setCookie($name, $value, $lifetime = 0, $path = '/')
		{
			setcookie($name, $value, $lifetime + time(), $path, Request::domain(), Request::https());
		}
		
		public static function deleteCookie($name, $path = '/')
		{
			self::setCookie($name, '', -1, $path);
		}
		
		private static function noPage()
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 418 I\'m a teapot');
			echo '<h1 style="height:70%;display:flex;justify-content:center;align-items:center;text-align:center">I don\'t have any page, but I can make you some tea.</h1>';
			exit;
		}
	}
?>