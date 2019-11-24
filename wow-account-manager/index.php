<?php
	session_start();
	//Include cofiguration
	
	include('config.php');
	require('functions.php');
	
	mysql_connect($db_ip, $db_acc, $db_pass);
	
	if(session_is_registered(esusername) and session_is_registered(espassword))
	{
		$password = sha1(strtoupper($_SESSION['esusername']).':'.strtoupper($_SESSION['espassword']));
		$vyber = mysql_query("SELECT * FROM `$db_realmd`.`account` WHERE `username` = '".$_SESSION['esusername']."' AND `sha_pass_hash` = '".$password."'") or die(mysql_error());
		$count = mysql_num_rows($vyber);
		
		if($count == 1)
		{
			$data = mysql_fetch_array($vyber);
			//web begin
?> 

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $titulek.' - '.$server_name; ?></title>
    <link rel="stylesheet" type="text/css" href="css/css.css">
    
    <script type="text/javascript">
		var timeout	= 500;
		var closetimer	= 0;
		var ddmenuitem	= 0;
		
		// open hidden layer
		function mopen(id)
		{	
			// cancel close timer
			mcancelclosetime();
		
			// close old layer
			if(ddmenuitem)
				ddmenuitem.style.visibility = 'hidden';
		
			// get new layer and show it
			ddmenuitem = document.getElementById(id);
			ddmenuitem.style.visibility = 'visible';
		
		}
		// close showed layer
		function mclose()
		{
			if(ddmenuitem)
				ddmenuitem.style.visibility = 'hidden';
		}
		
		// go close timer
		function mclosetime()
		{
			closetimer = window.setTimeout(mclose, timeout);
		}
		
		// cancel close timer
		function mcancelclosetime()
		{
			if(closetimer)
			{
				window.clearTimeout(closetimer);
				closetimer = null;
			}
		}
		
		// close layer when click-out
		document.onclick = mclose;
    </script>
</head>
<body>
	<div class="page-wrapper">
    	<img src="images/logo.png" class="logo" alt="logo">
        
        <div id="user">
        	Vítej, <?php echo ucfirst($data['username']); ?> <a href="logout.php">Odhlásit</a>
        </div>
        
        <div id="main">
        	<div id="menu">
            	<ul id="menuu">
                	<li class="nazev"><?php echo $server_name; ?></li>
                    <li class="pol"><a href="?page=home">Přehled&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
                    
                    <li class="pol"><a onmouseover="mopen('m1')" onmouseout="mclosetime()" href="#">Obchod&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                        <div id="m1" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        	<a href="?page=shopsummary">Itemy</a>
                            <a href="?page=itemtransfer">Převod itemů</a>
                            <a href="?page=characterstransfer">Převod postav</a>
                        </div>
                    </li>
                    
                    <li class="pol"><a onmouseover="mopen('m2')" onmouseout="mclosetime()" href="#">Nastavení&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                        <div id="m2" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        	<a href="?page=emailchange">Změna e-mailu</a>
                            <a href="?page=passwordchange">Změna hesla</a>
                            <a href="?page=accountexp">Typ účtu</a>
                        </div>
                    </li>
                    
                    <li id="konto">Stav na kontě:<br><a onmouseover="mopen('m3')" onmouseout="mclosetime()" href="#"><?php echo $data['kredity']; ?> Kreditů&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                        <div id="m3" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                            <a href="?page=kredittransfer">Převod kreditů</a>
                            <a href="?page=kreditbuy">Nakoupit kredity</a>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div id="title">
            	<div class="titlecara"></div>
            	<font class="titlet">World of Warcraft account management</font><br>
            	<font class="currentpage"><?php echo printTitle(); ?></font>
            </div>
            
            <div id="content">
            	<div class="topline"></div>
                 <!--ACCOUNT NAME:<br />
                &nbsp;&nbsp;&nbsp;komalarn<br /><br />
                
                EMAIL:<br />
                &nbsp;&nbsp;&nbsp;komalarn@seznam.cz<br /><br />
                
                EXPANSION:<br />
                &nbsp;&nbsp;&nbsp;The burning crusade<br /><br />
                
                LAST IP:<br />
                &nbsp;&nbsp;&nbsp;127.0.0.1
                -->
                <?php
					if (isset($_GET['page']))
					{
						if (truefile($_GET['page']))
							require_once('pages/'.$_GET['page'].'.php');
						else
							require_once('pages/404.php');
					}
					else
						require_once ('pages/home.php');
				?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
		}
		else
			header('location: logout.php');
	}
	else
		header('location: logout.php');

	mysql_close();
?>