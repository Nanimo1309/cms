<?php
	abstract class PageBase extends Page
	{
		protected $title = null;
		protected $lang = null;
		
		private $css = [];
		private $js = [];
		
		public function addCSS($path, $media = null)
		{
			if($media)
				$this->css[] = [$path, $media];
			else
				$this->css[] = $path;
		}
		
		public function addJS($path)
		{
			$this->js[] = $path;
		}
		
		public function load()
		{
			header('Content-Type: text/html; charset=utf-8');
		}
		
		public function write()
		{
			?>
			<!DOCTYPE html>
			<html <?php echo $this->lang ? 'lang="' . $this->lang . '"' : ''; ?>>
				<head>
					<?php if($this->title) echo "<title>$this->title</title>"; ?>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<meta name="author" content="Jan Kot">
					<meta name="contact" content="email:jasiek1309@wp.pl">
					
					<?php
						foreach($this->css as $css)
							if(is_array($css))
								echo "<link rel='stylesheet' media='{$css[1]}' href='{$css[0]}'>";
							else
								echo "<link rel='stylesheet' href='$css'>";
						
						foreach($this->js as $js)
							echo "<script type='application/javascript' src='$js'></script>";
					?>
				</head>
				<body>
					<?php $this->body(); ?>
				</body>
			</html>
			<?php
		}
		
		abstract protected function body();
	}
?>