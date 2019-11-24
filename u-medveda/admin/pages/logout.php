<h2>Odhlášení</h2>
<p>Naschledanou...</p>

<?php
	session_start();
	session_destroy();
	
	echo '<meta http-equiv="refresh" content="0; url=/admin/">';
?>