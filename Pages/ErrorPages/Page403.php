<?php
	include_once 'Pages/Bases/PageWrap.php';
	
	class Page403 extends PageWrap
	{
		public function load()
		{
			parent::load();
			
			http_response_code(403);
			
			$this->title = Lang::get('forbidden');
			$this->addCSS('/CSS/errorPage');
		}
		
		protected function main()
		{
			?>
			<div>
				<h1 class="center"><?php Lang::echo('forbidden'); ?></h1>
				<h3 class="center"><?php Lang::echo('forbiddenInfo'); ?></h3>
			</div>
			<?php
		}
	}
?>