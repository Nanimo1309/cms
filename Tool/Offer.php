<?php
	include_once 'Lang.php';
	
	class Offer
	{
		public $name;
		public $url;
		public $image;
		public $title;
		public $shortDescription;
		public $description;
		public $map;
		public $gallery;
		public $priceDC; // Daily Car
		public $pricePC; // Private Car
		public $priceDV; // Daily Van
		public $pricePV; // Private Van
		public $hours;
		
		/*
		{name}:
		{
			"url": {URL with offer},
			"image": {File URL},
			"title": {Dictionary name},
			"shortDescription": {Dictionary name},
			"description": {Dictionary name},
			"map": {Map URL},
			"gallery": {Gallery name},
			"priceDC": {Price (6 digits, 2 precision)},
			"pricePC": {Price (6 digits, 2 precision)},
			"priceDV": {Price (6 digits, 2 precision)},
			"pricePV": {Price (6 digits, 2 precision)},
			"hours": {Hours (5 digits, 2 precision)}
		}
		*/
		
		public static function config($config, $delete = true)
		{
			foreach($config as $name => list('url' => $url, 'image' => $image, 'title' => $title, 'shortDescription' => $shortDescription, 'description' => $description, 'gallery' => $gallery))
			{
				if(Connection::prepare('SELECT NULL FROM page WHERE page.url = ?')->execute($url))
					throw new ConfigException("URL \"$url\" is taken");
				
				foreach([$title, $shortDescription, $description] as $index)
					if(!Connection::prepare('SELECT NULL FROM dictIndex WHERE name = ?')->execute($index))
						throw new ConfigException('No dictionary index: ' . $index);
				
				if(!Connection::prepare('SELECT NULL FROM file WHERE url = ?')->execute($image))
					throw new ConfigException('No image with url: ' . $image);
				
				if(!Connection::prepare('SELECT NULL FROM gallery WHERE name = ?')->execute($gallery))
					throw new ConfigException('No gallery with name: ' . $gallery);
			}
			
			if($delete)
				Connection::prepare('DELETE FROM offer')->execute();
			
			$getIndex = '(SELECT id FROM dictIndex WHERE name = ?)';
			$getImage = '(SELECT id FROM file WHERE url = ?)';
			$getGallery = '(SELECT id FROM gallery WHERE name = ?)';
			
			foreach($config as $name => list('url' => $url, 'image' => $image, 'title' => $title, 'shortDescription' => $shortDescription, 'description' => $description, 'map' => $map, 'gallery' => $gallery, 'priceDC' => $priceDC, 'pricePC' => $pricePC, 'priceDV' => $priceDV, 'pricePV' => $pricePV, 'hours' => $hours))
				Connection::prepare("INSERT INTO offer (name, url, idImage, idTitle, idShortDescription, idDescription, map, idGallery, priceDC, pricePC, priceDV, pricePV, hours) VALUE (?, ?, $getImage, $getIndex, $getIndex, $getIndex, ?, $getGallery, ?, ?, ?, ?, ?)")
				->execute($name, $url, $image, $title, $shortDescription, $description, $map, $gallery, $priceDC, $pricePC, $priceDV, $pricePV, $hours);
		}
		
		public static function load($url)
		{
			if(empty($url))
				return [];
			
			$query =
			'SELECT o.name, o.url, f.url AS image, t.name AS title, NULL AS shortDescription, d.name AS description, o.map, g.name AS gallery, o.priceDC, o.pricePC, o.priceDV, o.pricePV, o.hours ' .
			'FROM offer o INNER JOIN dictIndex t ON o.idTitle = t.id INNER JOIN dictIndex d ON o.idDescription = d.id INNER JOIN gallery g ON o.idGallery = g.id LEFT JOIN file f ON o.idImage = f.id ' .
			'WHERE o.url ';
			
			return self::l($url, $query);
		}
		
		public static function loadTile($name)
		{
			if(empty($name))
				return [];
			
			$query =
			'SELECT o.name, o.url, f.url AS image, t.name AS title, sd.name AS shortDescription, NULL AS description, NULL AS map, NULL AS gallery, o.priceDC, o.pricePC, o.priceDV, o.pricePV, o.hours ' .
			'FROM offer o INNER JOIN dictIndex t ON o.idTitle = t.id INNER JOIN dictIndex sd ON o.idShortDescription = sd.id LEFT JOIN file f ON o.idImage = f.id ' .
			'WHERE o.name ';
			
			return self::l($name, $query);
		}
		
		private static function l($name, $query)
		{
			$offers = '';
			
			if(gettype($name) == 'array')
			{
				$placeholders = str_repeat('?, ', count($name) - 1) . '?';
				$query .= "IN($placeholders) ORDER BY FIELD(o.name, $placeholders)";
				$offers = Connection::prepare($query)->execute(...$name, ...$name);
			}
			else
			{
				$query .= '= ?';
				$offers = Connection::prepare($query)->execute($name);
			}
			
			if(!$offers)
				return [];
			
			foreach($offers as &$offer)
				$offer = new Offer($offer['name'], $offer['url'], $offer['image'], $offer['title'], $offer['shortDescription'], $offer['description'], $offer['map'], $offer['gallery'], $offer['priceDC'], $offer['pricePC'], $offer['priceDV'], $offer['pricePV'], $offer['hours']);
			
			return $offers;
		}
		
		public static function getNames()
		{
			$offers = Connection::prepare('SELECT name FROM offer')->execute();
			
			if($offers)
				return array_column($offers, 'name');
			else
				return [];
		}
		
		private function __construct($name, $url, $image, $title, $shortDescription, $description, $map, $gallery, $priceDC, $pricePC, $priceDV, $pricePV, $hours)
		{
			$this->name = $name;
			$this->url = $url;
			$this->image = $image;
			$this->title = $title;
			$this->shortDescription = $shortDescription;
			$this->description = $description;
			$this->map = $map;
			$this->gallery = $gallery;
			$this->priceDC = $priceDC == -1 ? INF : $priceDC;
			$this->pricePC = $pricePC == -1 ? INF : $pricePC;
			$this->priceDV = $priceDV == -1 ? INF : $priceDV;
			$this->pricePV = $pricePV == -1 ? INF : $pricePV;
			$this->hours = $hours;
		}
	}
?>