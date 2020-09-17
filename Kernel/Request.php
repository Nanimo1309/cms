<?php
	class Request
	{
		public static function url()
		{
			return explode('?', $_SERVER['REQUEST_URI'], 2)[0];
		}

		public static function wholeUrl()
		{
			return $_SERVER['REQUEST_URI'];
		}

		public static function method()
		{
			return $_SERVER['REQUEST_METHOD'];
		}

		public static function https()
		{
			return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
		}

		public static function time()
		{
			return $_SERVER['REQUEST_TIME_FLOAT'];
		}

		public static function client()
		{
			return $_SERVER['REMOTE_ADDR'];
		}

		public static function domain()
		{
			return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
		}

		public static function domains()
		{
			return explode('.', self::domain());
		}

		public static function ifNoneMatch()
		{
			return isset($_SERVER['HTTP_IF_NONE_MATCH']) ? substr($_SERVER['HTTP_IF_NONE_MATCH'], 1, -1) : null;
		}

		public static function ifModifiedSince()
		{
			return isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : null;
		}

		public static function lang()
		{
			if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
				return false;

			$lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

			foreach($lang as &$l)
				$l = explode(';', explode('-', $l, 2)[0], 2)[0];

			return array_unique($lang);
		}

		public static function accept()
		{
			if(!isset($_SERVER['HTTP_ACCEPT']))
				return false;

			$accept = explode(',', $_SERVER['HTTP_ACCEPT']);

			foreach($accept as &$a)
				$a = explode(';', $a, 2)[0];

			return $accept;
		}

		public static function encoding()
		{
			if(!isset($_SERVER['HTTP_ACCEPT_ENCODING']))
				return false;

			return explode(', ', $_SERVER['HTTP_ACCEPT_ENCODING']);
		}
	}
?>