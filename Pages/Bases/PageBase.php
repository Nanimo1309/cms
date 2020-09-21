<?php
	abstract class PageBase extends Page
	{
		protected $title = null;
		protected $lang = null;
		protected $keywords = null;
		protected $description = null;
		
		private $css = [];
		private $cssScript = [];
		private $js = [];
		private $jsScript = [];
		
		public function addCSS($path, $attr = [])
		{
			$this->css[] = [$path, $attr];
		}
		
		public function addRawCSS($script, $attr)
		{
			$this->cssScript[] = [$script, $attr];
		}
		
		public function addJS($path, $attr = [])
		{
			$this->js[] = [$path, $attr];
		}
		
		public function addRawJS($script, $attr = [])
		{
			$this->jsScript[] = [$script, $attr];
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
					<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<meta name="author" content="Jan Kot">
					<meta name="contact" content="email:jasiek1309@wp.pl">
					<meta name="keywords" content="<?php echo $this->keywords; ?>">
					<meta name="description" content="<?php echo $this->description; ?>">
					
					<?php
						foreach($this->css as list($path, $attr))
							echo "<link rel='stylesheet' href='$path'" . self::writeAttrib($attr) . '>';
						
						foreach($this->cssScript as list($script, $attr))
							echo '<style' . self::writeAttrib($attr) . ">$script</style>";
						
						foreach($this->js as list($path, $attr))
							echo "<script type='application/javascript' src='$path'" . self::writeAttrib($attr) . '></script>';
						
						foreach($this->jsScript as list($script, $attr))
							echo '<script type="application/javascript"' . self::writeAttrib($attr) . ">$script</script>";
					?>
				</head>
				<body>
					<?php $this->body(); ?>
				</body>
			</html>
			<?php
		}
		
		private static function writeAttrib($attr)
		{
			$temp = '';
			
			foreach($attr as $name => $value)
				if(is_int($name))
					$temp .= ' ' . $value;
				else
					$temp .= " $name='$value'";
			
			return $temp;
		}
		
		abstract protected function body();
	}
?>