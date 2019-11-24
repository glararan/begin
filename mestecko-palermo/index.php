<?php
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$start = $time;

	session_start();
	
	// CONFIG
	$db['host'] = 'wm27.wedos.net';
	$db['user'] = 'a8403_palermo';
	$db['pass'] = 'qwedsayxc789';
	$db['db']   = 'd8403_palermo';
	
	function connectToMySQL()
	{
		@mysql_connect('wm27.wedos.net', 'a8403_palermo', 'qwedsayxc789') or die("Nelze se připojit k DB!");
		@mysql_select_db('d8403_palermo') or die("Nelze vybrat DB!");
	}
	
	//connectToMySQL();
	
	// URLčka
	// /room/IDRoom/
	$arguments = explode("/", $_GET["param1"]);

	$param[1] = $arguments[0];
	$param[2] = $arguments[1];
	$param[3] = $arguments[2];
	
	switch($param[1])
	{
		case "chat":
			$title = "Chat";
			$stranka = 'chat.php';
			$chat = true;
			$js_path = "../";
			break;
			
		case "join":
			$title = "Připojení k místnosti";
			$stranka = 'join.php';
			$join = true;
			break;
			
		case "contact":
			$title = "Kontakt";
			$stranka = 'contact.php';
			break;
			
		case "404":
			$title = "404 Error";
			$stranka = '404.php';
			break;
			
		case $param[1]:
			{
				if($param[1] == "room")
					break;
				else
				{
					if($param[1] == "")
					{
						$title = "Hlavní stránka";
						$stranka = 'main.php';
					}
					else
					{
						$title = "404 Error";
						$stranka = '404.php';
					}
				}
			}
			break;
	}
	
	if(!empty($param[1]) && !empty($param[2]))
	{		
		if($param[1] == "room" && is_numeric($param[2]))
		{
			$title = "Místnost " + $param[2];
			
			$room = true;
			$js_path = "../../";
		}
		else
			$echo404 = true;
	}
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
    
    <?php
		//if($echo404)
			//echo '<meta http-equiv="refresh" content="0;URL=\'/404/\'">';
	?>
    
	<title>Městečko Palermo - <?php echo $title; ?></title>

	<base href="http://open-source.veteska.cz/mestecko-palermo/">
    
    <link href="css/style.css" type="text/css" rel="stylesheet">
    
    <?php	
		if($chat)
			echo '<script type="text/javascript" src="/core/chat/chat.js"></script>';
		if($join)
			echo '<script type="text/javascript" src="/core/join/join.js"></script>';
		if($room)
		{
			echo '<script type="text/javascript" src="/core/game/game.js"></script>';
	?>
    <script type="text/javascript">
		var doUnload = function()
		{
			var xmlHttp = null;
			
			xmlHttp = new XMLHttpRequest;
			xmlHttp.open("GET", "/do.php", false);
			xmlHttp.send(null);
		};
		
		if(window.addEventListener)
			window.addEventListener("unload", doUnload, false);
		else if(window.attachEvent)
			window.attachEvent("onunload", doUnload);
	</script>
                  
	<?php
		}
		
		if($chat || $room || $join)
		{
	?>
	<script type="text/javascript">
		var checkJS = function()
		{
			var xmlHttp = null;
			
			xmlHttp = new XMLHttpRequest();
			xmlHttp.open("GET", "/core/javascript.php", false);
			xmlHttp.send(null);
		};
					
		if(window.addEventListener)
			window.addEventListener("load", checkJS, false);
		else if(window.attachEvent)
			window.attachEvent("onload", checkJS);
		</script>
	<?php
		}
	?>
       
	<!--[if gte IE 9]>
		<style type="text/css">
			.gradient {filter: none;}
		</style>
	<![endif]-->
</head>
<body<?php if($chat) echo " onload='showChat(); setInterval(\"showChat()\", 1000);'"; ?><?php if($join) echo " onload='showRooms(); setInterval(\"showRooms()\", 3000);'"; ?><?php if($room) echo " onload='buttonStart(); showPlayers(); autoEndGame(); gameText(); showChat(); setInterval(\"buttonStart()\", 3500); setInterval(\"showPlayers()\", 3000); setInterval(\"autoEndGame()\", 1500); setInterval(\"gameText()\", 2000); setInterval(\"showChat()\", 1250);'"; ?>>
	<div class="header">
        <h1>Městečko Palermo</h1>
        <h2>Online hra pro více hráčů</h2>
            
    	<img src="images/logo.png" width="294" height="43" alt="Logo" title="">
    </div>

	<div class="wrapper">
    	<div class="menu">
        	<ul>
            	<li><a href="/">Domů</a></li>
                <li><a href="join/">Připojit</a></li>
                <li><a href="chat/">Chat</a></li>
                <li><a href="contact/">Kontakt</a></li>
            </ul>
        </div>
        
        <div class="menu_underline"></div>
    
        <div class="content">
        	<div class="text">
				<?php
                    if(!isset($_SESSION['username']))
                    {
                        if(!isset($_POST['login']))
                        {
                ?>
                    <div class="login">
                        <form action="" method="post">
                            <label for="login_name">Uživatelské jméno</label>
                            <br>
                            <input type="text" tabindex="0" autofocus="autofocus" placeholder="Zadejte uživ. jméno" name="login_name" class="login_box">
                            <br>
                            <input type="submit" tabindex="1" name="login" class="login_button" value="Přihlásit">
                        </form>
                    </div>
                <?php
                        }
                        else
                        {
							if(addslashes($_POST['login_name']) != "")
							{
								if(ereg("^[(a-zA-Z)]+$", addslashes($_POST['login_name'])))
								{
									$_SESSION['username'] = addslashes($_POST['login_name']);
									
									echo '<meta http-equiv="refresh" content="0;URL=\'/\'">';
								}
								else
									echo '<font color="red">Vaše uživatelské jméno obsahuje nepovolené znaky!</font><meta http-equiv="refresh" content="3;URL=\'/\'">';
							}
							else
								echo '<font color="red">Vaše uživatelské jméno je prázdné!</font><meta http-equiv="refresh" content="3;URL=\'/\'">';
                        }
                    }
                    else
                    {				
                        if($room)
                        	include('pages/room.php');
                        else
                            include('pages/'.$stranka);
                    }
                ?>
			</div>
        </div>
        
        <?php
			$time = microtime();
			$time = explode(' ', $time);
			$time = $time[1] + $time[0];
			
			$finish = $time;
			$total_time = round(($finish - $start), 4);
		?>
        
        <div class="footer">
        	<div class="text">© 2013 <a href="http://veteska.cz">Veteška.cz</a>. Všechna práva vyhrazena. Pozadí použito z her Mafia II & GTA IV.<br>Stránka byla vygenerována za <?php echo $total_time * 1000; ?>ms.</div>
        </div>
	</div>
</body>
</html>