<?php
	session_start();

	require 'common.php';
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	$mysqli->set_charset('utf8');
	
	if (mysqli_connect_errno()) 
	{
		echo json_encode('database error');
		return;
	}
	
	if (isset($_SESSION['rank']) && ($_SESSION['rank'] == 'user' || $_SESSION['rank'] == "admin"))
	{
		$deviceID = $_POST['deviceID'];
		
		if(!isset($deviceID))
		{	
			json_encode($lang['error_missingParameter']);
			return;
		}
		
		$data = array();
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT name, nick, description, status, category FROM devices WHERE id = ?"))
		{
			$query->bind_param("i", $deviceID);
			$query->execute();
			$query->store_result();
			$query->bind_result($name, $nick, $description, $status, $categoryID);
		
			while($query->fetch())
			{				
				switch($status)
				{
					case 0:
						$status = "K vypůjčení";
						break;
						
					case 1:
						$status = "Vypůjčené";
						break;
						
					case 2:
						$status = "Nelze vypůjčit";
						break;
						
					case 3:
						$status = "Poškozené";
						break;
				}
				
				$query_c = $mysqli->stmt_init();
				
				if($query_c = $mysqli->prepare("SELECT name FROM categories WHERE id = ?"))
				{
					$query_c->bind_param("i", $categoryID);
					$query_c->execute();
					$query_c->store_result();
					$query_c->bind_result($category);
				
					while($query_c->fetch())
						$data = array("deviceStatus" => 1, "name" => $name, "nick" => $nick, "description" => $description, "status" => $status, "category" => $category);
					
					$query_c->close(); 
				}
				else
					$data = array("deviceStatus" => 0);
			}
			
			$query->close();
		}
		else
			$data = array("deviceStatus" => 0);
		
		echo json_encode($data);
	}
	else
	{
		json_encode(array("deviceStatus" => 0));
		return;
	}
?>