<?
	session_start();

	$param[1] = $_GET['newmsg'];
	$param[2] = $_GET['text'];

	// Připojení k MySQL
    @mysql_connect("wm27.wedos.net", "a8403_palermo", "qwedsayxc789") or die("Nelze se připojit do databáze.");
    @mysql_select_db("d8403_palermo") or die("Databáze nebyla nalezena.");
    // Konec
	
    if(isset($param[1]))
	{
    	if(isset($param[2]))
		{
        	if(!empty($param[2])) // Sice jsme tohle již ověřovali v JS, ale ten může kdokoli změnit
            	mysql_query("INSERT INTO `chat` (`user`, `text`, `date`) VALUES('".$_SESSION['username']."', '".mysql_real_escape_string(addslashes($param[2]))."', ".Date("U").")");
		}
    }
   
    if(isset($_POST['show'])) // Provede se v případě, pokud AJAX odeslal na tento soubor dotaz pomocí POST s atributem/proměnnou "show"
	{
    	$getmsgs = mysql_query("SELECT * FROM `chat` ORDER BY `date` DESC LIMIT 20");
		
        while($msg = mysql_fetch_array($getmsgs))
		{
        	echo "<div class='message'><p><span>[".Date("d.m v H:i", $msg['date'])."] <div class='user'>".$msg['user']."</div>:</span>\n";
            echo $msg['text']."\n";
            echo "</p></div>\n";
		}
    }
?>