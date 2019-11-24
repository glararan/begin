<?php
	$query_info = mysql_query("SELECT `email`, `expansion`, `last_ip` FROM `".$db_realmd."`.`account` WHERE `id` = '".$data['id']."'") or die(mysql_error());
	$acc_info = mysql_fetch_array($query_info);
	
	if(empty($acc_info['email']))
		$acc_info['email'] = "Neni vyplněn";
?>

ACCOUNT NAME:<br>
<div class="home_margin"><?php echo $data['username']; ?></div>
                
EMAIL:<br>
<div class="home_margin"><?php echo $acc_info['email']; ?></div>
                
EXPANSION:<br>
<div class="home_margin"><?php echo printExpansion($acc_info); ?></div>
                
LAST IP:<br>
<div class="home_margin"><?php echo $acc_info['last_ip']; ?></div>
               