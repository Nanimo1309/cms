<?php
	include_once 'Pages/Bases/PageWrap.php';
	include_once 'Traits/OfferTile.php';
	
	class MainPage extends PageWrap
	{
		use OfferTile;
		
		private $offers;
		
		public function construct()
		{
			parent::construct();
			
			if(!empty($this->args()))
			{
				Controller::load('/404');
				return;
			}
			
			$this->addForm(new Form('MainPage::contactAction', 'contactForm', ['class' => 'row wrap']))
			->add('email', 'email', Lang::get('yourEmail'), ['placeholder' => Lang::get('emailPlaceholder'), 'pattern' => '[^@]+@[a-zA-Z][a-zA-Z0-9]*\.[a-zA-Z][a-zA-Z0-9]+', 'required'])
			->add('phone', 'tel', Lang::get('formPhone'), ['placeholder' => Lang::get('formPhonePlaceholder'), 'pattern' => '\+?[0-9\s\-]*', 'required'])
			->add('title', 'text', Lang::get('title'), ['placeholder' => Lang::get('titlePlaceholder'), 'required'])
			->add('message', 'textarea', Lang::get('message'), ['placeholder' => Lang::get('messagePlaceholder'), 'required'])
			->add('submit', 'submit', Lang::get('send'), ['class' => 'colorBtn']);
		}
		
		public static function contactAction($data)
		{
			if(filter_var($data['email'], FILTER_VALIDATE_EMAIL))
			{
				$data['phone'] = htmlentities($data['phone'], ENT_SUBSTITUTE);
				$data['title'] = htmlentities($data['title'], ENT_SUBSTITUTE);
				$data['message'] = htmlentities($data['message'], ENT_SUBSTITUTE);
				
				require 'Tool/PHPMailer.php';
				
				$mail = new PHPMailer();
				
				$mail->setFrom($data['email']);
				$mail->addAddress(Lang::get('contactFormEmail'));
				
				$mail->isHTML();
				$mail->Subject = $data['title'] . ' - ' . Request::domain();
				
				$mail->Body =
				'<h2><b>Tytuł: ' . $data['title'] . '</b></h2>' .
				'<h3>Telefon: ' . $data['phone'] . '<br />Email: ' . $data['email'] . '</h3>' .
				'<p>' . $data['message'] . '</p>'.
				'<p>Wysłano: ' . date('H:i:s') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . date('d-m-Y') . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . Request::domain() . '</p>';
				
				$mail->AltBody = $data['phone'] . ' - ' . $data['message'];
				
				$mail->send();
				
				Controller::setCookie(mailCookie, 'ok', 60);
			}
			
			Controller::reload();
		}
		
		public function load()
		{
			parent::load();
			
			$this->offerTileInit();
			
			$this->addCSS('/CSS/mainPage');
			$this->addCSS('/CSS/mainPageMobile', ['media' => '(max-width: 800px)']);
			
			$this->addJS(facebookIframeJS, ['async', 'defer', 'crossorigin' => 'anonymus']);
			$this->addJS('/JS/mainPage');
			
			$this->offers = Offer::loadTile(theBestOffers);
			
			if(isset($_COOKIE[mailCookie]))
			{
				$this->addAlert(Lang::get('mailSendSuccessfully'));
				
				Controller::deleteCookie(mailCookie);
			}
		}
		
		protected function main()
		{
			?>
			<div id="fb-root"></div>
			<article><?php $this->offer(); ?></article>
			<p id="AboutUs"></p>
			<article><?php $this->aboutUs(); ?></article>
			<p id="Map"></p>
			<article class="column"><?php $this->map(); ?></article>
			<p id="Contact"></p>
			<article><?php $this->contact(); ?></article>
			<?php
		}
		
		private function offer()
		{
			$this->writeOffers();
			?>
			<div class="center wrap">
				<a class="button colorBtn" href="/Offers/Trips"><?php Lang::echo('seeMoreTrips'); ?></a>
				<a class="button colorBtn" href="/Offers/Transfers"><?php Lang::echo('seeMoreTransfers'); ?></a>
				<a class="button colorBtn" href="/Offers/Activities"><?php Lang::echo('seeMoreActivities'); ?></a>
			</div>
			<?php
		}
		
		private function aboutUs()
		{
			?>
			<h1 class="center colorDark"><?php Lang::echo('aboutUs'); ?></h1>
			<p><?php Lang::echo('aboutUsText'); ?></p>
			<?php
		}
		
		private function map()
		{
			?>
			<h1 class="center colorDark"><?php Lang::echo('agencies'); ?></h1>
			<div class="column center">
				<h3 class="center"><?php Lang::echo('agencyAddress'); ?></h3>
				<iframe src="<?php echo agencyMapIframe; ?>" allowfullscreen></iframe>
			</div>
			<?php
		}
		
		private function contact()
		{
			?>
			<h1 class="center colorDark"><?php Lang::echo('contact'); ?></h1>
			<div class="row wrap center">
				<address class="column">
					<h1 class="center"><?php Lang::echo('cracowTrips'); ?></h1>
					<h3 class="center"><?php Lang::echo('phoneNumber'); ?></h3>
					<h3 class="center"><?php Lang::echo('contactEmail'); ?></h3>
				</address>
				<div class="fb-page" data-href="<?php echo facebookIframe; ?>" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"></div>
				<?php
				$this->getForm('contactForm')
				->start()
					->write('email')
					->write('phone')
					->write('title')
					->write('message')
					->html('<div></div>')
					->write('submit')
				->end();
				?>
			</div>
			<?php
		}
	}
?>