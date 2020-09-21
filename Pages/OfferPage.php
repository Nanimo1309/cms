<?php
	include_once 'Pages/Bases/PageWrap.php';
	include_once 'Traits/OfferGallery.php';
	
	class OfferPage extends PageWrap
	{
		use OfferGallery;
		
		private $offer;
		
		public function construct()
		{
			parent::construct();
			
			if(count($this->args()) != 1 || !($this->offer = Offer::load(Request::url())))
			{
				Controller::load('/404');
				Debug::desc($this->args());
				return;
			}
			
			$this->offer = $this->offer[0];
		}
		
		public function load()
		{
			parent::load();
			
			$this->title .= ' - ' . Lang::get($this->offer->title);
			
			$this->addCSS('/CSS/offer');
			
			$this->loadGallery();
		}
		
		public function main()
		{
			?>
			<article class="column center">
				<header class="center">
					<h1 class="center"><?php Lang::echo($this->offer->title); ?></h1>
					<div>
						<img src="<?php echo $this->offer->image; ?>">
					</div>
					<div>
						<div class="color"></div>
						<p class="center"><?php echo min($this->offer->priceDC, $this->offer->pricePC, $this->offer->priceDV, $this->offer->pricePV); ?>zł</p>
					</div>
					<div>
						<p class="center colorDark"><?php echo floatval($this->offer->hours); ?>h</p>
					</div>
				</header>
				<section>
					<div class="column centerH"><?php Lang::echo($this->offer->description); ?></div>
				</section>
				<section class="centerH">
					<table>
							<tr>
								<th></th>
								<th>Car</th>
								<th>Van</th>
							</tr>
							<tr>
								<th>Daily tour</th>
								<td><?php echo $this->offer->priceDC != INF ? $this->offer->priceDC : '-'; ?></td>
								<td><?php echo $this->offer->priceDV != INF ? $this->offer->priceDV : '-'; ?></td>
							</tr>
							<tr>
								<th>Private Tours</th>
								<td><?php echo $this->offer->pricePC != INF ? $this->offer->pricePC : '-'; ?></td>
								<td><?php echo $this->offer->pricePV != INF ? $this->offer->pricePV : '-'; ?></td>
							</tr>
					</table>
				</section>
				<section>
					<?php $this->writeGallery(); ?>
				</section>
				<?php if($this->offer->map): ?>
					<section>
						<iframe src="<?php echo $this->offer->map; ?>"></iframe>
					</section>
				<?php endif; ?>
			</article>
			<?php
			$this->writeGalleryShow();
		}
	}
?>