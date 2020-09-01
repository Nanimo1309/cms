<?php
	include_once 'Pages/Bases/PageWrap.php';
	
	class Page404 extends PageWrap
	{
		public function load()
		{
			parent::load();
			
			$this->title = Lang::get('notFound');
			
			http_response_code(404);
		}
		
		protected function main()
		{
			?>
			<div class=".centerH .centerV">
				<h1><?php Lang::echo('notFound'); ?></h1>
				<h3><?php LAng::echo('notFoundInfo'); ?></h3>
			</div>
			<?php
		}
	}
?>