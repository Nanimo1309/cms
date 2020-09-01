<?php
	include_once 'Pages/Bases/PageBase.php';
	include_once 'Tool/Lang.php';
	
	class Login extends PageBase
	{
		public function construct()
		{
			$this->addForm(new Form(Login::loginUser, 'login', 'post', ['class' => '.centerV .centerH']))
			->add('login', 'text', 'Login')
			->add('pass', 'password', 'Hasło')
			->add('submit', 'submit', 'Zaloguj');
		}
		
		public static function loginUser($data)
		{
			
		}
		
		public function body()
		{
			$this->getForm('login')->start()->writeForm()->end();
		}
	}
?>