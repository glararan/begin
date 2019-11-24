<?php
	include_once('includes/core.php');

	if(file_exists('install.php'))
		include_once('install.php');
	else
	{
		session_start();
		session_cache_expire(30);
	
		if(!isset($_SESSION['account']))
			include_once('login.php');
		else
		{
			include_once('header.php');
			include_once('grid.php');
			include_once('footer.php');
		}
	}
?>