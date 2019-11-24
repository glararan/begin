<?php
	require 'common.php';
	
	function ExChange($acc, $pass)
	{
		$ldap     = ldap_connect("10.1.1.50");
		$ldapuser = $acc;
		$ldappass = $pass;
		$ldaptree = "OU=skola,DC=ssakhk,DC=cz";
		$retezec  = explode("@", $ldapuser);
		$user     = $retezec[0];

		if(@$bind = ldap_bind($ldap, $ldapuser, $ldappass))
			return true;
		else
			return false;
	}
	
	if(!isset($_POST['email']))
	{
		echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Přihlašovací email je prazdný!"));
		
		return;
	}
	else
	{
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Přihlašovací email neodpovídá filtru pro šablonu emailu!"));
			return;
		}
	}
	
	if (!isset($_POST['password']))
	{
		echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Heslo je prázdné!"));
		
		return;
	}
	
	if (strlen($_POST['email']) == 0 || strlen($_POST['password']) == 0)
	{
		echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Nějaká kolonka neni vyplněna!"));
		return;
	}
	
	// Připojení k databázi
	$mysqli = new mysqli($hostnameServer, $usernameServer, $passwordServer, $dbnameServer);
	
	if (mysqli_connect_errno()) 
	{
		echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Nelze se připojit k databázi!"));
		return;
	}

	$query = $mysqli->stmt_init();
	
	if($query = $mysqli->prepare("SELECT id FROM admins WHERE email = ?"))
	{
		$query->bind_param("s", $_POST['email']);
		$query->execute();
		$query->store_result();
		$query->fetch();

		if ($query->num_rows > 0)
		{
			if(ExChange($_POST['email'], $_POST['password']))
			{
				$_SESSION['user_email']  = $_POST['email'];
				$_SESSION['rank']        = 'admin';
				$_SESSION['user_status'] = 0;
				
				$logged = true;
			}
		}
		
		$query->close();

		if($logged)
		{
			echo json_encode(array("bar-color" => "alert-success", "alert-info" => "Uspěšné přihlášení:", "alert-text" => 'Do 2 sekund budete přesměrováni na hlavní stránku!<meta http-equiv="refresh" content="2;URL=\"/\"">'));
			return;
		}
	}
	
	$query = $mysqli->stmt_init();
	
	if($query = $mysqli->prepare("SELECT status FROM users WHERE email = ?"))
	{
		$query->bind_param("s", $_POST['email']);
		$query->execute();
		$query->store_result();
		$query->bind_result($uStatus);
		$query->fetch();
		
		if ($query->num_rows > 0)
		{
			if(ExChange($_POST['email'], $_POST['password']))
			{
				$_SESSION['user_email']  = $_POST['email'];
				$_SESSION['rank']        = 'user';
				$_SESSION['user_status'] = $uStatus;
				
				$logged = true;
			}
		}
		else
		{
			$query2 = $mysqli->stmt_init();
			
			if(ExChange($_POST['email'], $_POST['password']))
			{
				if($query2 = $mysqli->prepare("INSERT INTO users (email, status) VALUES(?, 0)"))
				{
					$query->bind_param("s", $_POST['email']);
					
					if($query->execute())
					{
						$_SESSION['user_email']  = $_POST['email'];
						$_SESSION['rank']        = 'user';
						$_SESSION['user_status'] = 0;
						
						$logged = true;
					}
					else
						$errorI = true;
				}
				else
					$errorI = true;
			}
		}
		
		$query->close();
		
		if($logged)
		{
			echo json_encode(array("bar-color" => "alert-success", "alert-info" => "Uspěšné přihlášení:", "alert-text" => 'Do 2 sekund budete přesměrováni na hlavní stránku!<meta http-equiv="refresh" content="2;URL=\"/\"">'));
			return;
		}
		
		if($errorI)
		{
			echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Nepovedlo se přihlášení, nepovedlo se vložit do databáze záznam!"));
			return;
		}
	}
	
	echo json_encode(array("bar-color" => "alert-error", "alert-info" => "Neúspešné přihlašení:", "alert-text" => "Přihlašovací jméno nebo heslo je špatné!"));
?>