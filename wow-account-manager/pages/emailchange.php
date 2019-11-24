<?php
	if(isset($_POST['submit_email']))
	{
		$novy = mysql_real_escape_string(stripslashes($_POST['newemail']));
		$confrim = mysql_real_escape_string(stripslashes($_POST['confrim']));
		
		if(!empty($novy) and !empty($confrim))
		{
			if($novy == $confrim)
			{
				if($data['email'] != $novy)
				{
					mysql_query("UPDATE `".$db_realmd."`.`account` SET `email` = '".$novy."' WHERE `id` = '".$data['id']."'") or die(mysql_error());
					
					$error = "Email byl změněný na '".$novy."'";
				}
				else
					$error = "Tento e-mail již máte použitý";
			}
			else
				$error = "Emaily se neshodují";
		}
		else
			$error = "Musíe vyplnit všechna pole";
	}
	else
		$error = "";
?>
    <form action="" method="post">
    	Nový e-mail:<br>
    	<input type="text" name="newemail" style="width:230px;"><br><br>
    	Potvrdit nový e-mail:<br>
    	<input type="text" name="confrim" style="width:230px;"><br> <b>
        <input type="submit" name="submit_email" value="Odeslat"><br><br>
        <?php echo $error; ?>
    </form>
