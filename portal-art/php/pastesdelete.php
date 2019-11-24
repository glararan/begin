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
	
	if (isset($_SESSION['rank']) && $_SESSION['rank'] == "admin")
	{
		$pastesID = $_POST['ids'];

		if(!isset($pastesID))
		{
			echo json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			for($i = 0; $i < count($pastesID); $i++)
			{
				if(!is_numeric($pastesID[$i]))
				{
					echo json_encode($lang['error_missingParameter']);
					return;
				}
			}
		}
		
		for($i = 0; $i < count($pastesID); $i++)
		{
			$query_check = $mysqli->stmt_init();
			
			if($query_check = $mysqli->prepare("SELECT author FROM sharecode WHERE id = ?"))
			{
				$query_check->bind_param("i", $pastesID[$i]);
				$query_check->execute();
				$query_check->store_result();
				$query_check->bind_result($author);
				
				while($query_check->fetch())
				{
					if(!empty($author))
					{
						$query = $mysqli->stmt_init();
						
						if($query = $mysqli->prepare("DELETE FROM sharecode WHERE id = ?"))
						{
							$query->bind_param("i", $pastesID[$i]);
							$query->execute();
						}
					}
				}
			}
		}
			
		echo json_encode(array("success" => 1));
	}
	else
	{
		echo json_encode($lang['dontHaveAccess']);
		return;
	}
?>