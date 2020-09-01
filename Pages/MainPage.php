<?php
	include_once 'Pages/Bases/PageWrap.php';
	
	class MainPage extends PageWrap
	{
		protected function main()
		{
			?>
			<section><?php self::offers(); ?></section>
			<section><?php self::aboutUs(); ?></section>
			<section><?php self::map(); ?></section>
			<section><?php self::contact(); ?></section>
			<?php
		}
		
		protected function offers()
		{
			?>
			<?php
		}
		
		protected function aboutUs()
		{
			?>
			<span id="aboutUs"></span>
			<h1><?php Lang::echo('aboutUs') ?></h1>
			<p><?php Lang::echo('loremLong') ?></p>
			<?php
		}
		
		protected function map()
		{
			?>
			<span id="map"></span>
			<h1><?php Lang::echo('agencies') ?></h1>
			<p><?php Lang::echo('loremLong') ?></p>
			<?php
		}
		
		protected function contact()
		{
			?>
			<span id="contact"></span>
			<h1><?php Lang::echo('contact') ?></h1>
			<p><?php Lang::echo('loremLong') ?></p>
			<?php
		}
	}
?>