<?php
	class Lang
	{
		private static $lang = null;
		private static $stmt;
		
		/*
		"languages": {Array with languages},
		"dictionary":
		{
			{index}: {Array with translations (sequence as in languages field)}
		}
		*/
		
		public static function config($config, $delete = true)
		{
			$langCount = count($config['languages']);
			foreach($config['dictionary'] as $index => $d)
				if(is_array($d) && count($d) != $langCount)
					throw new ConfigException('Wrong number of translations on index: ' . $index);
			
			$addLang = Connection::prepare('INSERT INTO lang (name) VALUE (?)');
			$addIndex = Connection::prepare('INSERT INTO dictIndex (name) VALUE (?)');
			$addWord = Connection::prepare('INSERT INTO dictWord (idLang, idIndex, val) VALUE (?, ?, ?)');
			$addText = Connection::prepare('INSERT INTO dictText (idLang, idIndex, val) VALUE (?, ?, ?)');
			
			$getLang = Connection::prepare('SELECT id FROM lang WHERE name = ?');
			$getIndex = Connection::prepare('SELECT id FROM dictIndex WHERE name = ?');
			
			if($delete)
			{
				Connection::prepare('DELETE FROM lang')->execute();
				Connection::prepare('DELETE FROM dictIndex')->execute();
			}
			
			$langId = [];
			
			foreach($config['languages'] as $lang)
			{
				$addLang->execute($lang);
				$langId[] = $getLang->execute($lang)[0]['id'];
			}
			
			foreach($config['dictionary'] as $index => $trans)
			{
				if(!is_array($trans))
					continue;
				
				$addIndex->execute($index);
				$indexId = $getIndex->execute($index)[0]['id'];
				
				foreach($trans as $langNum => $t)
				{
					if(strlen($t) <= 255)
						$addWord->execute($langId[$langNum], $indexId, $t);
					else
						$addText->execute($langId[$langNum], $indexId, $t);
				}
			}
		}
		
		public static function init()
		{
			self::$stmt = Connection::prepare
			(
			'SELECT w.val FROM dictIndex i INNER JOIN dictWord w ON i.id = w.idIndex INNER JOIN lang l ON w.idLang = l.id ' .
			'WHERE i.name = ? AND l.name = ? UNION ' .
			'SELECT w.val FROM dictIndex i INNER JOIN dictText w ON i.id = w.idIndex INNER JOIN lang l ON w.idLang = l.id ' .
			'WHERE i.name = ? AND l.name = ?'
			);
		}
		
		public static function detectLang()
		{
			$lang = Request::lang();
			
			foreach($lang as &$l)
				$l = substr($l, 0, 2);
			
			array_unique($lang);
			
			$check = Connection::prepare('SELECT NULL FROM lang WHERE name = ?');
			
			foreach($lang as $l)
			{
				if($check->execute($l))
				{
					self::$lang = $l;
					
					return true;
				}
			}
			
			return false;
		}
		
		public static function setLang($lang)
		{
			if(Lang::checkLang($lang))
			{
				self::$lang = $lang;
				return true;
			}
			
			return false;
		}
		
		public static function checkLang($lang)
		{
			return Connection::prepare('SELECT NULL FROM lang WHERE name = ?')->execute($lang) == true;
		}
		
		public static function getLang()
		{
			return self::$lang;
		}
		
		public static function get($name)
		{
			if($res = self::$stmt->execute($name, self::$lang, $name, self::$lang))
				return $res[0]['val'];
			else
				throw new ConfigException('No text with this name');
		}
		
		public static function echo($name)
		{
			echo self::get($name);
		}
	}
?>