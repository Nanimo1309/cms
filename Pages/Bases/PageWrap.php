<?php
	include_once 'PageBase.php';
	include_once 'Tool/Lang.php';

	abstract class PageWrap extends PageBase
	{
		public function load()
		{
			parent::load();
			
			Lang::init();
			
			if(!Lang::setLang(Request::domains()[0]))
			{
				if(Lang::detectLang() || Lang::setLang('en'))
					Lang::goToLangDomain();
				
				Controller::load('/cannotDefineLanguage');
				return;
			}
			
			header('Content-Language: ' . Lang::getLang());
			
			$this->title = Lang::get('cracowTrips');
			
			$this->addCSS('/CSS/base');
			$this->addCSS('/CSS/skin');
			$this->addCSS('/CSS/pageWrap');
			$this->addCSS('/CSS/pageWrapMobile', '(max-width: 600px)');
			
			$this->addJS('https://code.jquery.com/jquery-3.4.1.slim.min.js');
			$this->addJS('/JS/pageWrap');
		}
		
		protected function body()
		{
			?>
			<p id="error"></p>
			<header><?php $this->header(); ?></header>
			<nav><?php $this->nav(); ?></nav>
			<main><?php $this->main(); ?></main>
			<footer><?php $this->footer(); ?></footer>
			<?php
		}
		
		protected function header()
		{
			$url = substr(Request::domain(), strlen(Request::domains()[0]) + 1) . Request::wholeUrl();
			
			?>
			<img src="/image/logo" />
			<div id="flags">
				<a href="//en.<?php echo $url; ?>"><img src="/image/gb-flag" alt="English" /></a>
				<a href="//pl.<?php echo $url; ?>"><img src="/image/pl-flag" alt="Polski" /></a>
			</div>
			<?php
		}
		
		protected function nav()
		{
			?>
			<label for="navMobileSwitch">
					<div></div>
					<div></div>
					<div></div>
			</label>
			<input id="navMobileSwitch" type="checkbox" hidden />
			<ul>
				<li>
					<a href='/'><?php Lang::echo('mainPage'); ?></a>
				</li>
				<li>
					<a href='/Tours'><?php Lang::echo('tours'); ?></a>
				</li>
				<li>
					<a href='/#aboutUs'><?php Lang::echo('aboutUs'); ?></a>
				</li>
				<li>
					<a href='/#map'><?php Lang::echo('agencies'); ?></a>
				</li>
				<li>
					<a href='/#contact'><?php Lang::echo('contact'); ?></a>
				</li>
			</ul>
			<?php
		}
		
		protected function footer()
		{
			
		}
		
		abstract protected function main();
	}
?>