<?php
	class User
	{
		private static $id = 0;
		private static $rights = 1;
		
		const cookieName = 'idKey';
		const expireTime = 60 * 60 * 24 * 7;
		
		public static function config($file)
		{
			if(!file_exists($file))
				throw new ConfigException('No config file');
			
			if(!($config = json_decode(file_get_contents($file), true)))
				throw new ConfigException('Cannot decode JSON');
			
			Connection::prepare('DELETE FROM account');
			
			foreach($config as $login => list('pass' => $password, 'email' => $email, 'name' => $name, 'rights' => $rights))
				self::register($login, $password, $email, $name, $rights);
		}
		
		public static function register($login, $password, $email, $name, $rights)
		{
			return Connection::prepare('INSERT INTO account (login, password, email, name, rights) VALUE (?, SHA2(?, 256), ?, ?, ?)')
			->execute($login, $password, $email, $name, $rights);
		}
		
		public static function unregister($login)
		{
			return Connection::prepare('DELETE FROM account WHERE BINARY login = ?')
			->execute($login);
		}
		
		public static function login($login, $password)
		{
			$idKey = self::newKey();
			
			if(Connection::prepare('UPDATE account SET idKey = ? WHERE BINARY login = ? AND password = SHA2(?, 256)')
			->execute($idKey, $login, $password))
			{
				self::setKey($idKey);
				return true;
			}
			else
				return false;
		}
		
		public static function forceLogin($login)
		{
			$idKey = self::newKey();
			
			if(Connection::prepare('UPDATE account SET idKey = ? WHERE BINARY login = ?')
			->execute($idKey, $login))
			{
				self::setKey($idKey);
				return true;
			}
			else
				return false;
		}
		
		public static function logout()
		{
			if(isset($_COOKIE[self::cookieName]))
				self::deleteKey();
			
			return Connection::prepare('UPDATE account SET idKey = NULL WHERE id = ?')
			->execute(self::$id);
		}
		
		public static function init()
		{
			if(!isset($_COOKIE[self::cookieName]))
				return;
			
			$res = Connection::prepare('SELECT id, rights FROM account WHERE idKey = ?')
			->execute($_COOKIE[self::cookieName]);
			
			if($res)
			{
				$res = $res[0];
				self::$id = $res['id'];
				self::$rights = intval($res['rights']);
				
				$newKey = self::newKey();
				Connection::prepare('UPDATE account SET idKey = ? WHERE id = ?')
				->execute($newKey, self::$id);
				self::setKey($newKey);
			}
		}
		
		private static function newKey()
		{
			$check = Connection::prepare('SELECT NULL FROM account WHERE idKey = ?');
			$idKey = '';
			
			do
			{
				$idKey = md5(time()) . md5(mt_rand()) . md5(mt_rand());
			}while($check->execute($idKey));
			
			return $idKey;
		}
		
		private static function setKey($idKey)
		{
			setcookie(self::cookieName, $idKey, time() + self::expireTime, '/', '', Request::https());
		}
		
		private static function deleteKey()
		{
			setcookie(self::cookieName, '', 1, '/', '', Request::https());
		}
		
		public static function checkRights($rights)
		{
			$rights = intval($rights);
			return ($rights & self::$rights) == $rights;
		}
		
		public static function getId()
		{
			return self::$id;
		}
		
		public static function getRights()
		{
			return self::$rights;
		}
		
		public static function getLogin()
		{
			return ($res = Connection::prepare('SELECT login FROM account WHERE id = ?')
			->execute(self::$id)) ? $res[0]['login'] : false;
		}
		
		public static function getEmail()
		{
			return ($res = Connection::prepare('SELECT email FROM account WHERE id = ?')
			->execute(self::$id)) ? $res[0]['email'] : false;
		}
		
		public static function getName()
		{
			return ($res = Connection::prepare('SELECT name FROM account WHERE id = ?')
			->execute(self::$id)) ? $res[0]['name'] : false;
		}
		
		public static function getLastActive()
		{
			return ($res = Connection::prepare('SELECT lastActive FROM account WHERE id = ?')
			->execute(self::$id)) ? $res[0]['lastActive'] : false;
		}
		
		public static function getRegistrationDate()
		{
			return ($res = Connection::prepare('SELECT registration FROM account WHERE id = ?')
			->execute(self::$id)) ? $res[0]['registration'] : false;
		}
	}
?>