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
	
	if (isset($_SESSION['rank']) && $_SESSION['rank'] == 'admin')
	{
		// Připojení k databázi
		$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
		$mysqli->set_charset('utf8');
		
		if (mysqli_connect_errno()) 
		{
			echo json_encode('database error');
			return;
		}

		$email = $_POST['email'];
		$stat = $_POST['status'];
		
		if(!isset($email) && !isset($stat))
		{
			json_encode($lang['error_missingParameter']);
			return;
		}
		else
		{
			if($stat != "0" && $stat != "1" && $stat != "2")
			{
				json_encode($lang['error_missingParameter']);
				return;
			}
		}
		
		if(!isEmail($email))
		{
			echo json_encode($lang['invalidEmail']);
			return;
		}
		
		$query_check = $mysqli->stmt_init();
		
		if($query_check = $mysqli->prepare("SELECT status FROM users WHERE email = ?"))
		{
			$query_check->bind_param("s", $email);
			$query_check->execute();
			$query_check->store_result();
			$query_check->bind_result($statusID);
			
			while($query_check->fetch())
			{
				if($statusID != $stat)
				{
					$query = $mysqli->stmt_init();
		
					if($query = $mysqli->prepare("UPDATE users SET status = ? WHERE email = ?"))
					{
						$query->bind_param("ss", $stat, $email);
						
						$data = array();
						
						if($query->execute())
							$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšná změna:", "alert-text" => "Gratulujeme, právě jste změnili status uživatele!");
						else
							$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešná změna:", "alert-text" => "Něco se pokazilo s databází!");

						$query->close();
					}
					else
					{
						json_encode($lang['error_cantDoStatement']);
						return;
					}
				}
				else
					$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšná změna:", "alert-text" => "Gratulujeme, právě jste změnili status uživatele!");
			}
			
			$query_check->close();
		}
		else
		{
			json_encode($lang['error_cantDoStatement']);
			return;
		}
		
		echo json_encode($data);
	}
	else
	{
		json_encode($lang['dontHaveAccess']);
		return;
	}
?>