<?php
	include_once 'Pages/Bases/PageWrap.php';
	
	class Page404 extends PageWrap
	{
		public function load()
		{
			parent::load();
			
			http_response_code(404);
			
			$this->title = Lang::get('notFound');
			$this->addCSS('/CSS/errorPage');
		}
		
		protected function main()
		{
			?>
			<div>
				<h1 class="center"><?php Lang::echo('notFound'); ?></h1>
				<h3 class="center"><?php Lang::echo('notFoundInfo'); ?></h3>
			</div>
			<?php
		}
	}
?>