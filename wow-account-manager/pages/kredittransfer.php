<?php
	if(isset($_POST['submit_tr']))
	{
		$hodnota = mysql_real_escape_string(stripslashes($_POST['hodnota']));
		$cil = mysql_real_escape_string(stripslashes($_POST['cil']));
		
		if(!empty($hodnota) and !empty($cil))
		{
			if(is_numeric($hodnota))
			{
				if($data['kredity'] >= $hodnota)
				{
					$getcil = mysql_query("SELECT `kredity`, `id` FROM `".$db_realmd."`.`account` WHERE `username` = '".$cil."'") or die(mysql_error());
					
					if(mysql_num_rows($getcil) == 1)
					{
						$fcil = mysql_fetch_array($getcil);
						$kredityplus = $fcil['kredity'] + $hodnota;
						$kredityminus = $data['kredity'] - $hodnota;
						mysql_query("UPDATE `".$db_realmd."`.`account` SET `kredity` = '".$kredityplus."' WHERE `id` = '".$fcil['id']."'") or die(mysql_error());
						mysql_query("UPDATE `".$db_realmd."`.`account` SET `kredity` = '".$kredityminus."' WHERE `id` = '".$data['id']."'") or die(mysql_error());
						$error = "Bylo převedeno ".$hodnota." kreditů na account ".$cil." !";
					}
					else
						$error = "Tento account neexistuje!";
				}
				else
					$error = "Nemáte dostatek kreditů";
			}
			else
				$error = "Počet kreditů musí být číslo!";
		}
		else
			$error = "Musíe vyplnit všechna pole";
	}
	else
		$error = "";
?>

    <form action="" method="post">
    	Počet kreditů:<br>
    	<input type="text" name="hodnota" style="width:230px;"><br><br>
    	Cílový account:<br>
    	<input type="text" name="cil" style="width:230px;"><br><br>
        <input type="submit" name="submit_tr" value="Odeslat"><br><br>
        <?php echo $error; ?>
    </form>
