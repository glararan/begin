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
		$pasteID = $_POST['id'];

		if(!isset($pasteID))
		{
			echo json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if(!is_numeric($pasteID))
			{
				echo json_encode($lang['error_missingParameter']);
				return;
			}
		}
		
		$data = array();
		
		$query_check = $mysqli->stmt_init();
		
		if($query_check = $mysqli->prepare("SELECT author FROM sharecode WHERE id = ?"))
		{
			$query_check->bind_param("i", $pasteID);
			$query_check->execute();
			$query_check->store_result();
			$query_check->bind_result($author);
			
			while($query_check->fetch())
			{
				if($author == $_SESSION['user_email'])
				{
					$query = $mysqli->stmt_init();
					
					if($query = $mysqli->prepare("DELETE FROM sharecode WHERE id = ?"))
					{
						$query->bind_param("i", $pasteID);

						if($query->execute())
							$data = array("success" => 1, "result" => "<meta http-equiv='refresh' content='0; url=/pastecode/'>");
						else
							$data = array("success" => 0);
					}
					else
						$data = array("success" => 0);
				}
				else
					$data = array("success" => 0);
			}
		}
		else
			$data = array("success" => 0);
			
		echo json_encode($data);
	}
	else
	{
		echo json_encode($lang['dontHaveAccess']);
		return;
	}
?>