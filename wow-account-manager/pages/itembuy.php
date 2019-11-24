<?php
	echo '<div class="section account-details"><div style="position:relative; left:30px;width:680px;">';
	
	if(isset($_GET['id']))
	{
		if(!empty($_GET['id']))
		{
			$id = mysql_real_escape_string(addslashes($_GET['id']));
			$getitem = mysql_query("SELECT `id`, `itemid`, `name`, `cost` FROM `".$db_realmd."`.`shop` WHERE `id` = '".$id."'") or die(mysql_error());
			
			if(mysql_num_rows($getitem) == 1)
			{
				$item = mysql_fetch_array($getitem);
				$getdbitem = mysql_query("SELECT * FROM `".$db_core."`.`item_template` WHERE `entry` = '".$item['itemid']."'") or die(mysql_error());
				$getchar = mysql_query("SELECT * FROM `".$db_char."`.`characters` WHERE `account` = '".$data['id']."'") or die(mysql_error());
				
				if(isset($_POST['submit_buy']))
				{
					if($data['kredity'] >= $item['cost'])
					{
						$char = mysql_real_escape_string(addslashes($_POST['char']));
						$getcharr = mysql_query("SELECT `account` FROM `".$db_char."`.`characters` WHERE `guid` = '".$char."'") or die(mysql_error());
						$charr_f = mysql_fetch_array($getcharr);
						
						if($charr_f['account'] == $data['id'])
						{
							// zakoupit item
							$charid = $char;
							$dbitem = mysql_fetch_array($getdbitem);
							$additemid = $dbitem['entry'];
								
							$get_instance_guid = mysql_query ("SELECT MAX(guid) FROM `".$db_char."`.`item_instance`") or die(mysql_error());
							$instance_guid = mysql_fetch_array ($get_instance_guid);
							$instance_guid_plus = $instance_guid[0] + 1;
								
							mysql_query ("INSERT INTO `".$db_char."`.`item_instance` (`guid`, `owner_guid`, `data`) VALUES ('".$instance_guid_plus."', '".$charid."', '".$instance_guid_plus." 1073741824 3 ".$additemid." 1065353216 0 ".$charid." 0 ".$charid." 0 0 0 0 0 1 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 ')") or die(mysql_error());
								
							$get_mail_guid = mysql_query ("SELECT MAX(id) FROM `".$db_char."`.`mail`") or die(mysql_error());
							$mail_guid = mysql_fetch_array($get_mail_guid);
							$mail_guid_plus = $mail_guid[0] + 1;
								
							mysql_query ("INSERT INTO `".$db_char."`.`mail` (`id`, `messageType`, `stationery`, `mailTemplateId`, `sender`, `receiver`, `subject`, `itemTextId`, `has_items`, `expire_time`, `deliver_time`, `money`, `cod`, `checked`) VALUES (".$mail_guid_plus.", 0, 41, 0, 0, ".$charid.", '".$mail['predmet']."', 0, 1, 0, 0, 0, 0, 0)") or die(mysql_error());
								
							mysql_query ("INSERT INTO `".$db_char."`.`mail_items` (`mail_id`, `item_guid`, `item_template`, `receiver`) VALUES ('".$mail_guid_plus."', '".$instance_guid_plus."', '".$additemid."', '".$charid."')") or die(mysql_error());
	
							// odebrat kredity
							$kredity = $data['kredity'] - $item['cost'];
							
							mysql_query("UPDATE `".$db_realmd."`.`account` SET `kredity` = '".$kredity."'") or die(mysql_error());
							
							echo "Item byl zakoupen a odeslán poštou. Vyzvedněte si jej ve hře.";
						}
						else
							echo "Tento character není váš!";
					}
					else
						echo "Nemáte dostatek kreditů";
				}
				else
				{
					if(mysql_num_rows($getchar) == 0)
						$ischar = false;
					else
						$ischar = true;
						
					echo 'Item : <font color="#00B6FF">'.$item['name'] .'</font><br>
						  Cena : <font color="#69BA02">'.$item['cost'].' kreditů</font><br><br>
						  Vyberte character: ';

					if($ischar == true)
					{
						echo '<form action="" method="post"><select name="char">';
						
						while($charactery = mysql_fetch_array($getchar))
							echo '<option value="'.$charactery['guid'].'">'.$charactery['name'].'</option>';

						echo '</select><br><br><input type="submit" name="submit_buy" value="Koupit"></form>';
					}
					else
						echo 'Nemáte na accountu žádný character!';
				}
			}
			else
				die("ERROR: Tento item není v nabídce!");
		}
		else
			die("ERROR: Není určeno ID");
	}
	else
		die("ERROR: Není určeno ID");

	echo '</div></div>';
?>