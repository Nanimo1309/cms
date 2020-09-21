<?php
	include_once 'Tool/Lang.php';
	include_once 'PageBase.php';
	include_once 'Traits/Alert.php';
	include_once 'Traits/CookieReminder.php';

	abstract class PageWrap extends PageBase
	{
		use Alert;
		use CookieReminder;
		
		public function construct()
		{
			Lang::init();
			
			if(isset(langDomain[Request::domain()]))
				Lang::setLang(langDomain[Request::domain()]);
			else
			{
				if(!(Lang::detectLang() && isset(array_flip(langDomain)[Lang::getLang()])) && !(defined('defaultLang') && Lang::setLang(defaultLang)))
				{
					Controller::load('/cannotDefineLanguage');
					return;
				}
				
				Controller::goTo('//' . array_flip(langDomain)[Lang::getLang()]);
			}
			
			header('Content-Language: ' . Lang::getLang());
		}
		
		public function load()
		{
			parent::load();
			
			$this->title = Lang::get('cracowTrips');
			
			$this->addCSS('/CSS/base');
			$this->addCSS('/CSS/skin');
			$this->addCSS('/CSS/pageWrap');
			$this->addCSS('/CSS/pageWrapMobile', ['media' => '(max-width: 800px)']);
			
			$this->addJS('https://code.jquery.com/jquery-3.4.1.min.js');
			$this->addJS('/JS/pageWrap');
			$this->addRawJS('$("#contactForm-email").on("input", function()
			{
				if(this.validity.patternMismatch)
					this.setCustomValidity(emailValidityError);
				else
					this.setCustomValidity("' . Lang::get('emailPatternError') . '");
			});');
			
			$this->cookieReminder();
		}
		
		public function body()
		{
			?>
			<header><?php $this->header(); ?></header>
			<nav class="sticky"><?php $this->nav(); ?></nav>
			<main><?php $this->main(); ?></main>
			<footer><?php $this->footer(); ?></footer>
			
			<a id="toBegin" class="button center fixed" href="<?php echo Request::wholeUrl() . '#'?>">
				<div></div>
			</a>
			<?php
			
			$this->writeAlerts();
		}
		
		private function header()
		{
			$domains = array_flip(langDomain);
			
			?>
			<div style="background-image: url('/image/logo')"></div>
			<div class="row" id="flags">
				<a href="//<?php echo $domains['en']; ?>"><img src="/image/gb-flag" alt="English" /></a>
				<a href="//<?php echo $domains['pl']; ?>"><img src="/image/pl-flag" alt="Polski" /></a>
			</div>
			<?php
		}
		
		private function nav()
		{
			?>
			<label class="button" for="navMobileSwitch">
					<div></div>
					<div></div>
					<div></div>
			</label>
			<input id="navMobileSwitch" type="checkbox" hidden />
			<ul>
				<li>
					<a class="button center colorBtn" href='/#'><?php Lang::echo('mainPage'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/Offers/Trips#'><?php Lang::echo('trips'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/Offers/Transfers#'><?php Lang::echo('transfers'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/Offers/Activities#'><?php Lang::echo('activities'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/#AboutUs'><?php Lang::echo('aboutUs'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/#Map'><?php Lang::echo('agencies'); ?></a>
				</li>
				<li>
					<a class="button center colorBtn" href='/#Contact'><?php Lang::echo('contact'); ?></a>
				</li>
			</ul>
			<?php
		}
		
		private function footer()
		{
			?>
			<p class="center colorDark"></p>
			<?php
		}
		
		abstract protected function main();
	}
?>