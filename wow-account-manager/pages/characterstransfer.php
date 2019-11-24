<?php
	if(isset($_POST['submit_char']))
	{
		$char = mysql_real_escape_string(addslashes($_POST['char']));
		$cil = mysql_real_escape_string(addslashes($_POST['cil']));
		
		if(!empty($char) and !empty($cil))
		{
			$getcil = mysql_query("SELECT * FROM `".$db_realmd."`.`account` WHERE `username` = '".$cil."'") or die(mysql_error());
			
			if(mysql_num_rows($getcil) == 1)
			{
				$getchar = mysql_query("SELECT * FROM `".$db_char."`.`characters` WHERE `guid` = '".$char."'") or die(mysql_error());
				
				if(mysql_num_rows($getchar) == 1)
				{	
					if($data['kredity'] >= 100)
					{
						$fchar = mysql_fetch_array($getchar);
						$fcil = mysql_fetch_array($getcil);
						$kredityminus = $data['kredity'] - 100;
						
						mysql_query("UPDATE `".$db_char."`.`characters` SET `account` = '".$fcil['id']."' WHERE `guid` = '".$fchar['guid']."'") or die(mysql_error());
						mysql_query("UPDATE `".$db_realmd."`.`account` SET `kredity` = '".$kredityminus."' WHERE `id` = '".$data['id']."'") or die(mysql_error());
						
						$error = "cool";
					}
					else
						$error = "Nemáte dostatek kredtiů!";
				}
				else
					$error = "Váš character neexistuje!";
			}
			else
				$error = "Cílový account neexistuje!";
		}
		else
			$error = "Musíte vyplnit všechna pole!";
	}
	else
		$error = "";
	
	$getchar = mysql_query("SELECT * FROM `".$db_char."`.`characters` WHERE `account` = '".$data['id']."'") or die(mysql_error());
	
	if(mysql_num_rows($getchar) == 0)
		$ischar = false;
	else
		$ischar = true;
?>
    Převod characteru stojí 100 kreditů.<br><br>
    <form action="" method="post">
    	Vyberte character:<br>
        <?php
			if($ischar == true)
			{
				echo '<select name="char">';
				
				while($charactery = mysql_fetch_array($getchar))
					echo '<option value="'.$charactery['guid'].'">'.$charactery['name'].'</option>';
	
				echo '</select>';
			}
			else
				echo 'Nemáte na accountu žádný character!';
		?>
        <br><br>
    	Cílový account:<br>
    	<input type="text" name="cil" style="width:230px;"><br><br>
        <input type="submit" name="submit_char" value="Převést"><br><br>
        <?php echo $error; ?>
    </form>