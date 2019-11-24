<div class="content_left">
	<?php echo $lang['files_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['files_title']; ?>
    <br>
    <?php echo $lang['files_text']; ?>
    <?php echo $lang['highlighter']; ?>
    
    <?php
		# uploading system
		
		// FILE LIST
		echo "<table width='100%'><tbody><tr><td><b>".$lang['files_table_name']."</b></td><td><b>".$lang['files_table_size']."</b></td></tr>";
		
		$files = glob("./data/files/*.*");
		
		foreach($files as $file)
		{
			$_file = pathinfo($file);
			$size = bytesToSize(filesize($file));
			$file_url = "http://".$_SERVER['SERVER_NAME'].preg_replace('/\s+/', "", $xml->read('Dir', 0, true))."/data/files/".$_file['basename'];
			
			echo "<tr><td><a href='".$file_url."'>".$_file['basename']."</a></td><td>".$size."</td></tr>";
		}
		
		echo "</tbody></table>";
		
		// UPLOAD
		echo "<form method='post' action='/includes/plupload_upload.php'><div id='upload_container'></div></form>";
	?>
</div>

<div class="clear">
</div>