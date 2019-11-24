<h3><a href="/">Chat</a></h3>

<noscript>
	<font color='red'>JavaScript je vypnut! Zapněte si ho pokuď chcete používat funkci Chat.</font>
    
    <?php
		if(!isset($_SESSION['javascript']))
			$java_is_offline = true;
	?>
</noscript>

<?php
	if(!$java_is_offline)
	{
?>

<div class='chat'>
	<div id='ChatArea' class="box">Loading...</div>
	<div class='AddMessage'>
        <input type='text' class="chat_message" id='ChatNewMessage'>
		<input type='button' class='send' value='Odeslat' tabindex="0" onclick='sendMsg()'>
	</div>
</div>

<?php
	}
?>