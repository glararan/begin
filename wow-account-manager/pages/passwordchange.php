<?php
	if(isset($_POST['submit_pass']))
	{
		$novy = mysql_real_escape_string(stripslashes($_POST['newpass']));
		$confrim = mysql_real_escape_string(stripslashes($_POST['confrim']));
		
		if(!empty($novy) and !empty($confrim))
		{
			if($novy == $confrim)
			{
				$password = sha1(strtoupper($_SESSION['esusername']).':'.strtoupper($novy));
				
				mysql_query("UPDATE `".$db_realmd."`.`account` SET `sha_pass_hash` = '".$password."' WHERE `id` = '".$data['id']."'") or die(mysql_error());
				mysql_query("UPDATE `".$db_realmd."`.`account` SET `v` = '' WHERE `id` = '".$data['id']."'") or die(mysql_error());
				
				$error = "Heslo bylo změněno!";
				$_SESSION["espassword"] = $novy;
			}
			else
				$error = "Hesla se neshodují";
		}
		else
			$error = "Musíe vyplnit všechna pole";
	}
	else
		$error = "";
?>

    <form action="" method="post">
    	Nové heslo:<br>
    	<input type="password" name="newpass" style="width:230px;"><br><br>
    	Potvrdit nové heslo:<br>
    	<input type="password" name="confrim" style="width:230px;"><br> <br>
        <input type="submit" name="submit_pass" value="Odeslat"><br><br>
        <?php echo $error; ?>
    </form>
