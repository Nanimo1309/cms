<?php
	include_once 'Pages/Bases/PageWrap.php';
	include_once 'Traits/OfferTile.php';
	
	class Offers extends PageWrap
	{
		use OfferTile;
		
		private $offers;
		
		public function construct()
		{
			parent::construct();
			
			if(empty($this->args()) || count($this->args()) > 1)
				Controller::load('/404');
		}
		
		public function load()
		{
			parent::load();
			
			switch(strtolower($this->args()[0]))
			{
			case 'trips':
				$this->title .= ' - ' . Lang::get('trips');
				$this->offers = Offer::loadTile(trips);
				break;
			case 'transfers':
				$this->title .= ' - ' . Lang::get('transfers');
				$this->offers = Offer::loadTile(transfers);
				break;
			case 'activities':
				$this->title .= ' - ' . Lang::get('activities');
				$this->offers = Offer::loadTile(activities);
				break;
			default:
				Controller::load('/404');
				return;
			}
			
			$this->offerTileInit();
		}
		
		protected function main()
		{
			$this->writeOffers();
		}
	}
?>