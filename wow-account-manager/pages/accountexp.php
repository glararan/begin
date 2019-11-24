<?php
	$gettype = mysql_query("SELECT `expansion` FROM `".$db_realmd."`.`account` WHERE `id` = '".$data['id']."'") or die(mysql_error());
	$ftype = mysql_fetch_array($gettype);
	
	if(isset($_POST['submit_ex']))
	{
		$exp = mysql_real_escape_string(addslashes($_POST['exp']));
		
		if(!empty($exp))
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
				
				mysql_query("UPDATE `".$db_realmd."`.`account` SET `expansion` = '".$dat."' WHERE `id` = ".$data['id']) or die(mysql_error());
				
				$error = "";
				$end = true;
			}
			else
				die("O to se nepokoušejte!");
		}
		else
			$error = "ERROR: Špatné pole!";
	}
	else
		$error = "";
	
	if($end != true)
	{
?>
    <form action="" method="post">
    	<select name="exp">
        	<option<?php selectChacked($ftype['expansion'], 0); ?> value="classic">World of Warcraft: Classic</option>
        	<option<?php selectChacked($ftype['expansion'], 1); ?> value="tbc">World of Warcraft: The Burning Crusade</option>
        	<option<?php selectChacked($ftype['expansion'], 2); ?> value="wotlk">World of Warcraft: Wrath of The Lich King</option>
        </select>
        <input type="submit" name="submit_ex" value="Změnit typ"><br><br>
       
    </form>
<?php
		echo $error;
	}
	else
		echo "Typ accountu byl úspěšně změněn!";
?>
