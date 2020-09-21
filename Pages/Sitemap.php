<?php
	class Sitemap extends Page
	{
		private $page;
		
		public function construct()
		{
			header('Content-Type: text/xml; charset=utf-8');
		}
		
		public function load()
		{
			$this->page = (Request::https() ? 'https' : 'http') . '://' . Request::domain() . '/';
		}
		
		public function write()
		{
			echo '<?xml version="1.0" encoding="UTF-8" ?>';
			?>
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
				<url>
					<loc><?php echo $this->page; ?></loc>
					<changefreq>never</changefreq>
					<priority>1.0</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offers/Trips</loc>
					<changefreq>never</changefreq>
					<priority>0.7</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offers/Transfers</loc>
					<changefreq>never</changefreq>
					<priority>0.7</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offers/Activities</loc>
					<changefreq>never</changefreq>
					<priority>0.7</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>#AboutUs</loc>
					<changefreq>never</changefreq>
					<priority>0.8</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>#Map</loc>
					<changefreq>never</changefreq>
					<priority>0.8</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>#Contact</loc>
					<changefreq>never</changefreq>
					<priority>0.9</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Auschwitz - Birkenau</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Wieliczka Salt Mine</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Oskar Schindler Factory</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Zakopane</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/John Paul II</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Częstochowa</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Ojców National Park</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Balice</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Pyrzowice</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Okęcie</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Balice Zakopane</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Berlin</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Budapeszt</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Kraków - Praga</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Go-Karts</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Termy</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Energylandia</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Dunajec - River Rafting</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
				<url>
					<loc><?php echo $this->page; ?>Offer/Shooting Range</loc>
					<changefreq>never</changefreq>
					<priority>0.5</priority>
				</url>
			</urlset>
			<?php
		}
	}
?>