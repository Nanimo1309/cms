<?php
	include_once 'Tool/Gallery.php';
	include_once 'Tool/Offer.php';
	
	trait OfferGallery
	{
		protected function loadGallery()
		{
			$this->addCSS('/CSS/gallery');
			$this->addJS('/JS/gallery');
			
			$this->offer->gallery = Gallery::load($this->offer->gallery);
		}
		
		protected function writeGallery()
		{
			if($this->offer->gallery)
			{
				?>
				<div class="gallery row wrap center">
					<?php foreach($this->offer->gallery as $image): ?>
						<figure>
							<img src="<?php echo $image; ?>">
						</figure>
					<?php endforeach; ?>
				</div>
				<?php
			}
		}
		
		protected function writeGalleryShow()
		{
			if($this->offer->gallery)
			{
				?>
				<div id="galleryShow" class="fixed center" style="display: none;">
					<div class="fixed column">
						<div></div>
						<div class="center">
							<button>&#x276E;</button>
						</div>
					</div>
					<img>
					<div class="fixed column">
						<div class="centerH">
							<button>&#x2716;</button>
						</div>
						<div class="center">
							<button>&#x276F;</button>
						</div>
					</div>
				</div>
				<?php
			}
		}
	}
?>