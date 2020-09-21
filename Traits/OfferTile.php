<?php
	include_once 'Tool/Offer.php';
	
	trait OfferTile
	{
		private function offerTileInit()
		{
			$this->addCSS('/CSS/offerTile');
		}
		
		private function writeOffers()
		{
			?>
			<div id="offersContainer" class="row centerH wrap">
				<?php foreach($this->offers as $offer): ?>
					<section>
						<a class="button" href="<?php echo $offer->url; ?>">
							<div>
								<img src="<?php echo $offer->image; ?>">
							</div>
							<div class="color"></div>
							<p class="center"><?php echo min($offer->priceDC, $offer->pricePC, $offer->priceDV, $offer->pricePV); ?>zł</p>
							<p class="center colorDark"><?php echo floatval($offer->hours); ?>h</p>
							<h1><?php Lang::echo($offer->title); ?></h1>
							<p><?php Lang::echo($offer->shortDescription); ?></p>
						</a>
					</section>
				<?php endforeach; ?>
			</div>
			<?php
		}
	}
?>