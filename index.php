<?php
	error_reporting(E_ALL);

	try
	{
		require 'Kernel/Kernel.php';
		
		require 'settings.php';
		
		Controller::init();
	}
	catch(Throwable $e)
	{
		if(class_exists('Debug'))
			Debug::desc($e);
		else
			throw $e;
	}
?>