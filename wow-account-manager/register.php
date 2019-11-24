<?php
	include('config.php');

	function IsValidEmail($email)
	{
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}

	if(isset($_POST['submit_reg']))
	{
		$acc   = mysql_real_escape_string(addslashes($_POST['account_name']));
		$mail  = mysql_real_escape_string(addslashes($_POST['account_email']));
		$pass  = mysql_real_escape_string(addslashes($_POST['account_password']));
		$pass2 = mysql_real_escape_string(addslashes($_POST['account_password2']));
		$exp   = mysql_real_escape_string(addslashes($_POST['account_expansion']));
		$rules = $_POST['rules'];
		
		$passed = true;
		
		if(empty($acc) || empty($mail) || empty($pass) || empty($pass2) || empty($exp))
		{
			$error = "<font color='red'>Nevyplnili jste všechny pole! Zkuste to znovu.</font>";
			$passed = false;
		}
		else
		{
			if($rules != 'ON')
			{
				$error = "<font color='red'>Musíte souhlasit s pravidly serveru!</font>";
				$passed = false;
			}
			else
			{
				$isAccFree = mysql_query("SELECT Count(1) as count FROM account WHERE UPPER(`username`) = UPPER('$_POST[account_name]');");
				$accountFree = mysql_fetch_array($isAccFree);
				
				if($accountFree['count'] != 0)
				{
					$error = "<font color='red'>Název účtu je již zabraný! Zkuste jiný.</font>";
					$passed = false;
				}
				else
				{
					if(!IsValidEmail($_REQUEST['account_email']))
					{
						$error = "<font color='red'>Email neni validní! Zkuste to znovu.</font>";
						$passed = false;
					}
					else
					{
						$getMail = mysql_query("SELECT Count(1) as count FROM account WHERE email = '$_REQUEST[mail]';");
						$mail = mysql_fetch_array($getMail);
						
						if($mail['count'] != 0)
						{
							$error = "<font color='red'>Email je již použitý! Zkuste jiný.</font>";
							$passed = false;
						}
						else
						{
							if(strlen($POST_['account_password']) >= 4)
							{
								if($_POST['account_password'] != $_POST['account_password2'])
								{
									$error = "<font color='red'>Hesla se neshodují! Zksute to znovu.</font>";
									$passed = false;
								}
								else
								{								
									switch($exp)
									{
										case "classic":
											$dat = "a";
											break;
											
										case "tbc":
											$dat = 1;
											break;
											
										case "wotlk":
											$dat = 2;
											break;
											
										default:
											$dat = '';
											break;
									}
									
									if(!empty($dat))
									{
										if($dat == "a")
											$dat = 0;
										
										mysql_query("INSERT INTO `account` (`username`,`sha_pass_hash`, `email`, `expansion`) VALUES(UPPER('$acc'), SHA1(CONCAT(UPPER('$acc'),':',UPPER('$pass'))), '$mail', '$dat');");
										
										$error = "";
										$passed = true;
									}
									else
									{
										$error = "<font color='red'>Datadisk je špatný! Zkuste to znovu.</font>";
										$passed = false;
									}
								}
							}
							else
							{
								$error = "<font color='red'>Heslo je přiliš krátké! Nejméně 4 znaky. Zkuste to znovu.</font>";
								$passed = false;
							}
						}
					}
				}
			}
		}
	}
	else
		$error = "";
	
	if($passed != true)
	{
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Account Management - Registrace</title>
    <link rel="stylesheet" type="text/css" href="css/css.css">
</head>
<body>
	<div class="page-wrapper">
    	<img src="images/logo.png" class="logo" alt="logo">
        
        <div id="main">
        	<div id="loginform">
            	<div class="login">
                    <h3>Registrace</h3>
                        <form action="" method="post">
                            Zadejte název účtu:<br>
                            <input type="text" name="account_name" placeholder="Název účtu" tabindex="1" style="width:268px;"><br><br>
                            Zadejte email:<br>
                            <input type="text" name="account_email" placeholder="Název emailu" tabindex="2" style="width:268px;"><br><br>
                            Zadejte heslo:<br>
                            <input type="password" name="account_password" placeholder="Heslo" tabindex="3" style="width:268px;"><br><br>
                            Zopakujte heslo:<br>
                            <input type="password" name="account_password2" placeholder="Heslo" tabindex="4" style="width:268px;"><br><br>
                            Zvolte datadisk:<br>
                            <select name="account_expansion">
                                <option value="classic">World of Warcraft: Classic</option>
                                <option value="tbc">World of Warcraft: The Burning Crusade</option>
                                <option selected value="wotlk">World of Warcraft: Wrath of The Lich King</option>
                            </select><br><br>
                            <input type="checkbox" name="rules">Souhlasím s pravidly serveru<br><br>
                            <input type="submit" name="submit_reg" value="Zaregistrovat"><br><br>
                        </form>
						<?php
                                echo $error;
                            }
                            else
                                echo "Účet byl úspešně vytvořen! Nyní se můžete přihlásit";
                        ?>
                </div>
                <div class="cara"></div>
            </div>
        </div>
    </div>
</body>
</html>