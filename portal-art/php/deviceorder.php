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
	
	if (isset($_SESSION['rank']) && isset($_SESSION['user_email']) && ($_SESSION['rank'] == 'user' || $_SESSION['rank'] == "admin"))
	{
		$deviceID = $_POST['deviceID'];
		$from     = $_POST['from'];
		$to       = $_POST['to'];
		$comment  = $_POST['comment'];
		
		if(!isset($deviceID) && !isset($from) && !isset($to))
		{	
			json_encode($lang['error_missingParameter']);
			return;
		}
		
		if($comment == "")
			$comment = "Žádný";
			
		$data = array();
		
		$query_u = $mysqli->stmt_init();
		
		if($query_u = $mysqli->prepare("SELECT id FROM users WHERE email = ?"))
		{
			$query_u->bind_param("s", $_SESSION['user_email']);
			$query_u->execute();
			$query_u->store_result();
			$query_u->bind_result($userID);
			
			while($query_u->fetch())
			{
				$query = $mysqli->stmt_init();
				
				if($query = $mysqli->prepare("INSERT INTO transfers (fromDate, toDate, admin, user, userText, adminText, device, status, comment) VALUES(?, ?, -1, ?, ?, '', ?, 0, '')"))
				{
					$query->bind_param("ssisi", $from, $to, $userID, $comment, $deviceID);
	
					if($query->execute())
						$data = array("bar-color" => "alert-success", "alert-info" => "Uspěšné odeslání:", "alert-text" => "Právě jste zažádali o zařízení!");
					else
						$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odeslání:", "alert-text" => "Něco se pokazilo s databází, nejpravděpodobněji jste zadali špatný formát data!");
						
					$query->close();
				}
				else
					$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odeslání:", "alert-text" => "Něco se pokazilo s databází!");
			}
				
			$query_u->close();
		}
		else
			$data = array("bar-color" => "alert-error", "alert-info" => "Neúspešné odeslání:", "alert-text" => "Něco se pokazilo s databází!");
		
		echo json_encode($data);
	}
	else
	{
		json_encode(array("deviceStatus" => 0));
		return;
	}
?>