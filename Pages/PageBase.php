<?php
	abstract class PageBase extends Page
	{
		protected $title = null;
		protected $lang = null;

		private $css = [];
		private $js = [];
		private $bodyJs = [];
		private $meta = [];

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

		public function addBodyJS($path)
		{
			$this->bodyJs[] = $path;
		}

		public function addMeta($name, $content)
		{
			$this->meta[] = [$name, $content];
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
					<meta http-equiv="content-type" content="text/html; charset=UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">

					<?php
						foreach($this->meta as $meta)
							echo '<meta name="' . $meta[0] . '" content="' . $meta[1] . '">';

						foreach($this->css as $css)
							if(is_array($css))
								echo '<link rel="stylesheet" media="' . $css[1] . '" href="' . $css[0] . '">';
							else
								echo '<link rel="stylesheet" href="' . $css . '">';

						foreach($this->js as $js)
							echo '<script type="application/javascript" src="' . $js . '"></script>';
					?>
				</head>
				<body>
					<?php
						$this->body();

						foreach($this->bodyJs as $js)
							echo '<script type="application/javascript" src="' . $js . '"></script>';
					?>
				</body>
			</html>
			<?php
		}

		abstract protected function body();
	}
?>