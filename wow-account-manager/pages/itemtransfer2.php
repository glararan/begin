<?php 
	$guid = mysql_real_escape_string(addslashes($_POST['char']));
	
	if(!empty($guid) || !isset($_POST['char']))
	{
		$get_chars = mysql_query("SELECT * FROM `".$db_char."`.`characters` WHERE `guid` = '".$guid."'") or die(mysql_error());
		
		if(mysql_num_rows($get_chars) == 1)
		{
			$f_chars = mysql_fetch_array($get_chars);
			$g_items = mysql_query("SELECT * FROM `".$db_char."`.`item_instance` WHERE `owner_guid` = '".$f_chars['guid']."'") or die(mysql_error());

			echo '<form action="'.$url.'?page=itemtransfer3" method="post">Vyberte item / stack<br>(bude odeslán celý stack!):<br><select name="item">';
							
			while($f_items = mysql_fetch_array($g_items))
			{
				$rozdel = explode(" ", $f_items['data']);
				$itementry = $rozdel['3']; // item Entry
				$itemcount = $rozdel['14']; // Počet itemů v stacků
								
				$item = mysql_query("SELECT `name` FROM `".$db_core."`.`item_template` WHERE `entry` = '".$itementry."'") or die(mysql_error());
				$item = mysql_fetch_array($item);
				$itemname = $item['name'];
				
				if($itemcount == 1)
					$count = "";
				else
					$count = " (".$itemcount.")";
					
				echo '<option value="'.$itementry.'">'.$itemname.''.$count.'</option>';
			}
			echo '</select><br><br><input type="submit" name="submit" value="3. Krok -->"></form>';
		}
		else
			header('location: index.php?page=transfer');
	}
	else
		header('location: index.php?page=transfer');
?>