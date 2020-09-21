<?php
	trait CookieReminder
	{
		private function cookieReminder()
		{
			$this->addCSS('https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css');
			$this->addJS('https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js', ['data-cfasync' => 'false']);
			
			$json =
			[
				'palette' =>
				[
					'popup' =>
					[
						'background' => '#aaa',
						'text' =>  '#000'
					],
					
					'button' =>
					[
						'background' => '#da1',
						'text' => '#fff'
					]
				],
				
				'theme' => 'classic',
				
				'content' =>
				[
					'message' => Lang::get('cookieRemindText'),
					'dismiss' => Lang::get('cookieRemindGotIt'),
					'link' => Lang::get('cookieRemindLearnMore')
				]
			];
			
			$this->addRawJS('$(function()
			{
				window.cookieconsent.initialise(' . json_encode($json) . ');
			});');
		}
	}
?>