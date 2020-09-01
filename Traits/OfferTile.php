<?php
	trait OfferTile
	{
		protected function writeOffer($offer)
		{
			?>
			<section class="offerTile">
				<img src="<?php echo $offer->image; ?>" />
				<div>
					<p><?php echo $offer->price; ?>zł</p>
				</div>
				<p><?php echo $offer->hours; ?>h</p>
				<h1><?php Lang::echo($offer->title); ?></h1>
				<a href="<?php echo $offer->?>"><?php Lang::echo('readMore'); ?></a>
			</section>
			<?php
		}
	}
?>