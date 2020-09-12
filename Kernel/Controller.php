<?php
	class Controller
	{
		private static $page = null;

		public static function init()
		{
			if(preg_match('~/{2,}~', Request::url())) // Deletes two backslashes in url
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
			$stmt = Connection::prepare('SELECT file, class FROM page WHERE url = ?');
			$url = strrev($url);
			$args = [];

			while(!($page = $stmt->execute($url)))
			{
				$e = explode('/', $url, 2);
				$args[] = strrev($e[0]);
				$url = $e[1];

				if(!$url)
				{
					if($page = $stmt->execute('/'))
						break;
					else
						return false;
				}
			}

			$page = $page[0];
			require_once($page['file']);
			self::$page = new $page['class'](explode('/', substr(strrev($url), 1)), array_reverse($args));
			self::$page->construct();

			if(!empty($_POST) || !empty($_GET))
			{
				Form::init();
				if(!self::$page->processForms() && Request::method() == 'POST')
					Controller::reload();
			}

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

		private static function noPage()
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 418 I\'m a teapot');
			echo '<h1 style="height:70%;display:flex;justify-content:center;align-items:center;text-align:center">I don\'t have any page, but I can make you some tea.</h1>';
			exit;
		}
	}
?>