<?php
	include_once 'Pages/Bases/PageWrap.php';
	
	class Page403 extends PageWrap
	{
		public function load()
		{
			parent::load();
			
			$this->title = Lang::get('forbidden');
			
			http_response_code(403);
		}
		
		protected function main()
		{
			?>
			<div class=".centerH .centerV">
				<h1><?php Lang::echo('forbidden'); ?></h1>
				<h3><?php Lang::echo('forbiddenInfo'); ?></h3>
			</div>
			<?php
		}
	}
?>