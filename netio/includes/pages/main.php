<div class="content_left">
	<?php echo $lang['main_img']; ?>
</div>

<div class="content_right">
	<?php
		# UPDATE system
		include_once('includes/parser_lib.php');
		
		$update_url = "http://netio4-update.glararan.eu/update.php?version=".preg_replace('/\s+/', "", $xml->read('Version', 0, true));

		$html = file_get_html($update_url);
		
		foreach($html->find('body') as $row)
			$update_content = $row->find('pre', 0)->plaintext;
		
		if(!strstr("IS_UP_TO_DATE", $update_content))
			echo "<div class='extra_content'><div class='text_center'><strong>Je k dispozici AKTUALIZACE!</strong></div><br>Stáhnout jí můžete <a href='".$update_content."'>kliknutím zde</a>.</div><br>";
	?>

	<?php echo $lang['main_title']; ?>
    <br>
    <?php echo $lang['main_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# Info system
		$system['lang'] = $xml->read('Lang', 0, true);
		$system['version'] = $xml->read('Version', 0, true);
		
		echo "<table width='70%'><tbody><tr><td><b>".$lang['main_table_name']."</b></td><td><b>".$lang['main_table_value']."</b></td></tr><tr><td>".$lang['main_table_language']."</td><td>".$system['lang']."</td></tr><tr><td>".$lang['main_table_version']."</td><td>".$system['version']."</td></tr></tbody></table>";
	?>
</div>

<div class="clear">
</div>