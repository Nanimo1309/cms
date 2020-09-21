<?php
	class Gallery
	{
		/*
		{Gallery name}: [Image URL's]
		*/
		
		public static function config($config, $delete = true)
		{
			$checkGallery = Connection::prepare('SELECT NULL FROM gallery WHERE name = ?');
			$checkImage = Connection::prepare('SELECT NULL FROM file WHERE url = ?');
			
			foreach($config as $gallery => $images)
				foreach($images as $image)
					if(!$checkImage->execute($image))
						throw new ConfigException("Image with url: $image does not exist");
			
			if($delete)
				Connection::prepare('DELETE FROM gallery')->execute();
			
			$addGallery = Connection::prepare('INSERT INTO gallery (name) VALUE (?)');
			$addImage = Connection::prepare('INSERT INTO galleryElem (idGallery, idFile) VALUE (?, (SELECT id FROM file WHERE url = ?))');
			
			foreach($config as $gallery => $images)
			{
				$addGallery->execute($gallery);
				
				$galleryId = Connection::lastId();
				
				foreach($images as $image)
					$addImage->execute($galleryId, $image);
			}
		}
		
		public static function load($name)
		{
			$res = Connection::prepare('SELECT f.url FROM gallery g INNER JOIN galleryElem ge ON g.id = ge.idGallery INNER JOIN file f ON ge.idFile = f.id WHERE g.name = ?')->execute($name);
			
			if($res)
				return array_column($res, 'url');
			else
				return [];
		}
	}
?>