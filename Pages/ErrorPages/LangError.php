<?php
	include_once 'Pages/Bases/PageBase.php';
	
	class LangError extends PageBase
	{
		public function load()
		{
			parent::load();
			
			http_response_code(501);
			
			$this->title = 'Cannot define language';
		}
		
		protected function body()
		{
			?>
			<div class=".centerH .centerV">
				<h1>Cannot define any language for page</h1>
			</div>
			<?php
		}
	}
?>