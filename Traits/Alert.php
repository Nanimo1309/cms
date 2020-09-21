<?php
	trait Alert
	{
		private $alerts = [];
		
		protected function addAlert($message, $button = 'OK')
		{
			if(!$this->alerts)
			{
				$this->addCSS('/CSS/alert');
				$this->addRawJS('$(function(){$("html").css("overflow", "hidden");});');
			}
			
			$this->alerts[] = [$message, $button];
		}
		
		protected function writeAlerts()
		{
			for($i = 0, $n = count($this->alerts); $i < $n; ++$i):
				list($message, $button) = $this->alerts[$i];
				?>
				<div class="alert fixed center">
					<div class="column center">
						<p class="center"><?php echo $message; ?></p>
						<button class="colorBtn" onclick='$(this).parent().parent().hide()<?php echo !$i ? '; $("html").css("overflow", "unset")' : ''; ?>'><?php echo $button; ?></button>
					</div>
				</div>
				<?php
			endfor;
		}
	}
?>