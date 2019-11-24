<?php
	session_start();
	//Include cofiguration
	include('config.php');
	
	if(!session_is_registered(esusername) and !session_is_registered(espassword))
	{		
		$error = "<br>";
		
		mysql_connect($db_ip, $db_acc, $db_pass);
		
		$account = mysql_real_escape_string($_POST['account']);
		$password = mysql_real_escape_string($_POST['password']);
		$account = stripslashes($account);
		$password = stripslashes($password);
		
		if(isset($_POST['submit']))
		{
			$password1 = sha1(strtoupper($account). ':' .strtoupper($password));
			
			if(!empty($account) and !empty($password))
			{
				$vyber = mysql_query("SELECT * FROM `$db_realmd`.`account` WHERE `username` = '".$account."' AND `sha_pass_hash` = '".$password1."'") or die(mysql_error());
				$count = mysql_num_rows($vyber);
				
				if($count == 1)
				{
					$data = mysql_fetch_array($vyber);
					
					$_SESSION["esusername"] = $account;
					$_SESSION["espassword"] = $password;
					
					header("location:index.php");
				}
				else
					$error = 'Špatné jméno nebo heslo';
			}
			else
				$error = 'Musíte vyplnit všechna pole';
		}
		else
			$error = '';
		
		mysql_close();
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Account Management - Login</title>
    <link rel="stylesheet" type="text/css" href="css/css.css">
</head>
<body>
	<div class="page-wrapper">
    	<img src="images/logo.png" class="logo" alt="logo">
        
        <div id="main">
        	<div id="loginform">
            	<div class="login">
                    <h3>Přihlášení</h3>
                    
                    <form method="post" id="form" action="">
                        <p><input placeholder="Přhilašovací jméno..." name="account" autocomplete="off" type="text" tabindex="1" class="input_user"></p>
                        <p><input placeholder="Heslo..." name="password" type="password" tabindex="2" autocomplete="off" class="input_pass"></p>
                        <p><input name="submit" type="submit" tabindex="3" class="input_btn_small" value="Přihlásit se"></p>
                    </form><br>
                    
                    <?php echo $error; ?>
                    
                    <ul>
                        <li>Nemáte svůj účet? <a href="./register.php">Registrujte se</a></li>
                        <li>Zapomněli jste heslo? <a href="#">Klikněte zde</a></li>
                    </ul>
                </div>
                
                <div class="cara"></div>
            </div>   
                     
            <div id="registrace">
            	<h3>Registrace</h3>
                <a href="./register.php" class="registrace">Registruj se</a>
            </div>
        </div>
    </div>
</body>
</html>

<?php
	}
	else
		header('location: index.php');
?>