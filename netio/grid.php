<?php
	$page = $_REQUEST['page'];
	
	switch($page)
	{
		default:
		case "":
			include_once('./includes/pages/main.php');
			break;
			
		case $page:
			$page = str_replace("/","", $page);
		
			if(file_exists("./includes/pages/".$page.".php"))
				include("./includes/pages/".$page.".php");
			else
				include("./includes/pages/404.php"); 
		
			break;
	}
?>