<?php
	session_start();

	require 'common.php';
	
	function isEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
			return true;
		else
			return false;
	}
		
	if(!isEmail($_SESSION['user_email']))
		exit($lang['invalidEmail']);
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
	{
		echo json_encode('database error');
		return;
	}
	
	if (isset($_SESSION['rank']) && ($_SESSION['rank'] == "user" || $_SESSION['rank'] == "admin"))
	{
		$title = $_POST['title'];
		$publication = $_POST['publication'];
		$syntax = $_POST['syntax'];
		$source = $_POST['code'];
		$author = $_SESSION['user_email'];
		
		if(!isset($title) && !isset($publication) && !isset($syntax) && !isset($source) && !isset($author))
		{
			echo json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if(strlen($title) < 4)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Název vložení:", "alert-text" => "Obsahuje pod 5 znaků!", "success" => 0));
				return;
			}
			else if(strlen($title) == 0)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Název vložení:", "alert-text" => "Je prázdný!", "success" => 0));
				return;
			}
			
			$syntax_ext = array("bash", "aps", "acs", "vbs", "cs", "cpp", "java", "glsl", "css", "htmlxml", "php", "js", "sql", "json");
			
			$found = false;
			
			foreach($syntax_ext as $extension)
			{
				if($syntax == $extension)
				{
					$found = true;
					break;
				}
			}
			
			if(!$found)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Syntax:", "alert-text" => "Nepovolený syntax!", "success" => 0));
				return;
			}
			else
			{
				switch($syntax)
				{
					case "js":
						$syntax = "javascript";
						break;
						
					case "htmlxml":
						$syntax = "xml";
						break;
						
					case "acs":
						$syntax = "actionscript";
						break;
						
					case "vbs":
						$syntax = "vbscript";
						break;
						
					case "aps":
						$syntax = "applescript";
						break;
				}
			}
			
			if($publication != 0 && $publication != 1 && $publication != 2)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Nastavení sdílení:", "alert-text" => "Nepovolený typ!", "success" => 0));
				return;
			}
			
			if(strlen($source) < 10)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Obsah zdrojového kódu:", "alert-text" => "Obsahuje pod 10 znaků!", "success" => 0));
				return;
			}
			else if(strlen($source) == 0)
			{
				echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Obsah zdrojového kódu:", "alert-text" => "Je prázdný!", "success" => 0));
				return;
			}
		}
		
		$data = array();
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("INSERT INTO sharecode (name, date, syntax, author, public, code) VALUES(?, ?, ?, ?, ?, ?)"))
		{
			$currDate = date("Y-m-d H:i:s");
			$query->bind_param("ssssis", $title, $currDate, $syntax, $author, $publication, $source);
			$query->execute();
			$query->store_result();
			
			$query_id = $mysqli->stmt_init();
			
			if($query_id = $mysqli->prepare("SELECT id FROM sharecode WHERE name = ? and date = ? and syntax = ? and author = ? and public = ?"))
			{
				$query_id->bind_param("ssssi", $title, $currDate, $syntax, $author, $publication);
				$query_id->execute();
				$query_id->store_result();
				$query_id->bind_result($sharecodeID);
				
				while($query_id->fetch())
					$data = array("success" => 1, "success_data" => sprintf('<meta http-equiv="refresh" content="0;URL=\'http://portalart.glararan.eu/pastecode/%s/\'">', $sharecodeID));
				
				$query_id->close();
			}
			else
				$data = array("bar-color" => "alert-error", "alert-info" => "Neuspešné přesměrování:", "alert-text" => "Něco se někde neprovedlo! Ale vložili jsme do databáze kód.", "success" => 0);
			
			$query->close();
		}
		else
			$data = array("bar-color" => "alert-error", "alert-info" => "Neuspešné vložení do databáze:", "alert-text" => "Něco se někde neprovedlo!", "success" => 0);
		
		echo json_encode($data);
	}
	else
	{
		echo json_encode($lang['dontHaveAccess']);
		return;
	}
?>