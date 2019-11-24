<?php 
	$get_chars = mysql_query("SELECT * FROM `".$db_char."`.`characters` WHERE `account` = '".$data['id']."'") or die(mysql_error());
	
	if(mysql_num_rows($get_chars) >= 1)
		$ischar = true;
	else
		$ischar = false;
	
	if($ischar)
	{
		echo '<form action="'.$url.'?page=itemtransfer2" method="post">Vyberte character:<br><select name="char">';
				
		while($f_chars = mysql_fetch_array($get_chars))
			echo '<option value="'.$f_chars['guid'].'">'.$f_chars['name'].'</option>';
					
		echo '</select><br><br><input type="submit" name="submit" value="2. Krok -->"></form>';
	}
	else
		echo "Na Vašem accountu není žádný character";
?>
