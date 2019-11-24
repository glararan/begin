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
		$id = $_POST['pID'];
		
		if(!isset($id))
		{
			json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if(!is_numeric($id))
			{
				json_encode(array("link" => ""));
				return;
			}
		}
		
		$data = array();
		
		$query = $mysqli->stmt_init();
		
		if($query = $mysqli->prepare("SELECT link FROM projects WHERE id = ?"))
		{
			$query->bind_param("i", $id);
			$query->execute();
			$query->bind_result($link);
			$query->fetch();
				
			$query->close();
			
			$data = array("link" => $link);
		}
		else
			$data = array("link" => "");
		
		echo json_encode($data);
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>