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
	
	if (isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin')
	{
		$data = array();
		
		if(isset($_POST['all']) && $_POST['all'] == true)
		{
			$query = $mysqli->stmt_init();
			
			if($query = $mysqli->prepare("SELECT id, name FROM categories"))
			{
				$query->execute();
				$query->store_result();
				$query->bind_result($Cid, $Cname);
				
				while($query->fetch())
					array_push($data, array($Cid, $Cname));
				
				$query->close();
			}
			else
				$data = array("error" => $lang['error_missingParameter']);
		}
		else if(isset($_POST['catid']))
		{
			if(is_numeric($_POST['catid']))
			{
				$query = $mysqli->stmt_init();
				
				if($query = $mysqli->prepare("SELECT category FROM devices WHERE id = ?"))
				{
					$query->bind_param("i", $_POST['catid']);
					$query->execute();
					$query->store_result();
					$query->bind_result($Cid);
					$query->fetch();
					
					$data = array("category" => $Cid);
					
					$query->close();
				}
				else
					$data = array("category" => 0);
			}
			else
				$data = array("category" => 0);
		}
		
		echo json_encode($data);
	}
	else
	{
		echo json_encode($lang['dontHaveAccess']);
		return;
	}
?>