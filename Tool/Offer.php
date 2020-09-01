<?php
	class Offer
	{
		public $name;
		public $image;
		public $title;
		public $description;
		public $price;
		public $hours;
		
		/*
		{name}:
		{
			"title": {Dictionary name},
			"description": {Dictionary name},
			"price": {Price (6, 2)},
			"hours": {Hours (5, 2)}
		}
		*/
		
		public static function config($file)
		{
			if(!file_exists($file))
				throw new ConfigException('No config file');
			
			if(!($config = json_decode(file_get_contents($file), true)))
				throw new ConfigException('Cannot decode JSON');
			
			foreach($config as $name => list('image' => $image, 'title' => $title, 'description' => $description))
			{
				foreach([$title, $description] as $index)
					if(!Connection::prepare('SELECT NULL FROM dictIndex WHERE name = ?')->execute($index))
						throw new ConfigException('No dictionary index: ' . $index);
				
				if(!Connection::prepare('SELECT NULL FROM file WHERE url = ?')->execute($image))
					throw new ConfigException('no image with url: ' . $image);
			}
			
			Connection::prepare('DELETE FROM offer');
			
			$getIndex = '(SELECT id FROM dictIndex WHERE name = ?)';
			$getImage = '(SELECT id FROM file WHERE url = ?)';
			
			foreach($config as $name => list('image' => $image, 'title' => $title, 'description' => $description, 'price' => $price, 'hours' => $hours))
				Connection::prepare("INSERT INTO offer (name, ifImage, idTitle, idDescription, price, hours) VALUE (?, $getImage, $getIndex, $getIndex, ?, ?)")
				->execute($name, $image, $title, $description, $price, $hours);
		}
		
		public static function load($name)
		{
			$offer = Connection::prepare(
			'SELECT file.url, title.name, description.name, offer.price, offer.hours FROM file, dictIndex as title, dictIndex as description, offer ' .
			'WHERE file.id = offer.idImage AND title.id = offer.idTitle AND description.id = offer.idDescription AND offer.name = ?')
			->execute($name);
			
			if(!$offer)
				return false;
			
			$offer = $offer[0];
			return new Offer($name, ...$offer);
		}
		
		public static function loadId($id)
		{
			$offer = Connection::prepare(
			'SELECT title.name, description.name, offer.price, offer.hours FROM dictIndex as title, dictIndex as description, offer ' .
			'WHERE title.id = offer.title AND description.id = offer.description AND offer.name = ?')
			->execute($name);
			
			if(!$offer)
				return false;
			
			$offer = $offer[0];
			return new Offer($name, ...$offer);
		}
		
		public function __construct($name, $image, $title, $description, $price, $hours)
		{
			$this->name = $name;
			$this->image = $image;
			$this->title = $title;
			$this->description = $description;
			$this->price = $price;
			$this->hours = $hours;
		}
	}
?>