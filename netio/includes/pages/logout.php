<div class="content_left">
	<?php echo $lang['logout_img']; ?>
</div>

<div class="content_right">
	<?php echo $lang['logout_title']; ?>
    <br>
    <?php
		session_start();
		session_destroy();
		
		echo $lang['logout_text'];
	?>
</div>

<div class="clear">
</div>