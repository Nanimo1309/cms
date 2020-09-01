<?php
	error_reporting(E_ALL);

	try
	{
		require 'Kernel/Kernel.php';
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