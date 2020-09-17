<?php
	class Connection
	{
		private static $host = 'nazmetplgxmysql.mysql.db';
		private static $username = 'nazmetplgxmysql';
		private static $passwd = 'Nazar007';
		private static $dbname = 'nazmetplgxmysql';
		private static $port = 3306;

		private static $con = null;

		public static function init()
		{
			$con = new mysqli(self::$host, self::$username, self::$passwd, self::$dbname, self::$port);

			if($con->connect_errno)
				throw new SQLException('Cannot connect to database', $con->connect_errno);

			$con->set_charset('utf8');

			self::$con = &$con;
		}

		public static function close()
		{
			self::$con->close();
			self::$con = null;
		}

		public static function prepare($query)
		{
			if($stmt = self::$con->prepare($query))
				return new Stmt($stmt);
			else
				throw new SQLException(self::$con->error, self::$con->errno);
		}

		public static function lastId()
		{
			return self::prepare('SELECT LAST_INSERT_ID() as id')->execute()[0]['id'];
		}

		public static function transaction()
		{
			self::$con->autocommit(false);
		}

		public static function commit()
		{
			self::$con->commit();
			self::$con->autocommit(true);
		}

		public static function rollBack()
		{
			self::$con->rollback();
			self::$con->autocommit(true);
		}

		public static function escape($string)
		{
			return self::$con->escape_string($string);
		}

		public static function entities($string)
		{
			return htmlentities($strnig, ENT_HTML5 | ENT_NOQUOTES, 'UTF-8');
		}
	}
?>