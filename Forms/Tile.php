<?php
	class Tile
	{
		private $offer;
		private $form;
		
		public function __construct($offerName)
		{
			
			$this->offer = new Offer($offerName);
			
			$this
			->add('offer', 'submit', 'showOffer', ['value': $offerName]);
		}
		
		public static function toOffer($data)
		{
			$offer
			Controller::goTo($data['offer']);
		}
		
		public function writeForm()
		{
			?>
			<div class="tile">
				<img src="<?php echo $offer->image; ?>" />
				<p><?php echo $offer->price; ?></p>
				<p><?php echo $offer->description; ?></p>
				<?php
				$this->start()
					->write('offer')
				->end();
				?>
			</div>
			<?php
		}
	}
?>