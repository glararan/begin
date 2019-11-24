<?php
	# config
	$title = "Kel'Thuzad";
	$keywords = "World of Warcraft, World of Warcraft server, Kel'Thuzad, wow download, World of Warcraft Download, WotLK, 3.3.5";
	$description = "World of Warcraft server";
	
	$realmlist = "213.211.37.18";
	$patch = "3.3.5a & 1.0";
	
	# ...
	
	if($_GET['home'])	
		$title += " - ...";
		
	# functions
	function onlineStatus()
	{
		$host = $realmlist;
		$port = "8085";
		
		if($server = @fsockopen($host, $port, $ERROR_NO, $ERROR_STR, (float)0.5))
		{
			fclose($server);
			
			echo '<div class="green">ONLINE</div>';
		}
		else
			echo '<div class="red">OFFLINE</div>';
	}
	
	function onlinePlayers()
	{
	}
	
	function uptime()
	{
	}
?>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="author" content="Lukáš Veteška">
    <meta name="robots" content="index, follow">
    
    <title><?php echo $title; ?></title>

	<base href="http://open-source.veteska.cz/kel-thuzad/">
    
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
	<div class="wrapper">
        <div class="logo">
            <h1>Kel'Thuzad</h1>
        </div>
        
        <div class="menu">
        	<ul>
            	<li class="home"><a href="/" class="active"></a></li>
                <li class="register"><a href="registrace/"></a></li>
                <li class="htc"><a href="jaksepripojit/"></a></li>
                <li class="rules"><a href="pravidla/"></a></li>
                <li class="armory"><a href="http://armory.kelthuzad.cz"></a></li>
            </ul>
        </div>
        
        <div class="container">
            <div class="video left">
                <div class="object">
                    <iframe width="622" height="272" src="http://www.youtube.com/embed/BCr7y4SLhck?feature=player_detailpage" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            
            <div class="status right">
                <div class="top"></div>
                <div class="status_container">
                    <div class="content">
                        <div class="serverstatus">Server je <?php onlineStatus(); ?></div>
                        <br>
                        <div class="text">
                        Právě je online:<span><?php onlinePlayers(); ?></span>
                        <br>
                        Server uptime:<span><?php uptime(); ?></span>
                        <br>
                        Realmlist:<span><?php echo $realmlist; ?></span>
                        <br>
                        Patch:<span><?php echo $patch; ?></span>
                        </div>
                    </div>
                </div>
                <div class="bottom"></div>
            </div>
        </div>
        
        <div class="container">
        	<div class="articles left">
                <div class="article">
                    <div class="top"></div>
                    <div class="article_container">
                        <div class="content">
                            <div class="header">
                                <div class="title">Novinka č.1</div>
                                <div class="subtitle">Napsal: glararan | 3.11.2012 9:19 PM</div>
                            </div>
                            <div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur eleifend velit vel est consequat vel ultricies libero viverra. Suspendisse euismod mauris ut nisi imperdiet semper dignissim turpis hendrerit. Nunc vitae enim mi. Phasellus auctor, augue ac volutpat tempor, ligula nisi egestas quam, vel blandit felis sem et leo. Nunc ut lectus sed nibh tincidunt placerat ac sit amet est. Quisque nulla ipsum, dignissim sit amet feugiat sit amet, laoreet vel sem. Ut rutrum convallis dictum. Nulla vel ipsum justo, at varius diam. Aliquam eget aliquet enim. Nulla semper metus a nulla vulputate volutpat.
    <br>
    Phasellus et velit lorem, et aliquet ipsum. Proin volutpat molestie justo ut gravida. Donec ullamcorper risus augue. Nulla tempus massa id lorem interdum ut porta mauris cursus. Suspendisse potenti. Cras fringilla, nulla et sagittis malesuada, augue mauris facilisis massa, id pretium lectus erat eget sapien. Praesent id arcu ac nibh sodales euismod. Fusce suscipit ligula magna, sed laoreet elit. Donec eget lectus sed tellus ultricies sodales ut fringilla sapien. Nam suscipit dolor sed ante luctus sed bibendum lacus euismod. Sed tempus nibh non tellus dignissim adipiscing. Fusce at turpis risus. Pellentesque ante nulla, scelerisque at interdum a, suscipit sit amet dui. Ut lectus lectus, pretium sit amet bibendum id, consequat id nisi. Integer sit amet sem nulla. Donec consequat sapien orci.<br><br><a href="/">Tak toto bude odkaz</a></div>
                        </div>
                    </div>
                    <div class="bottom"></div>
                </div>
            </div>
            
            <div class="right_buttons right">
            	<div class="top"></div>
                <div class="buttons_container">
                	<ul>
                    	<li><a href="#">Změna hesla</a></li>
                        <li><a href="#">Account manager</a></li>
                        <li><a href="#">Soutěž o equip</a></li>
                        <li><a href="#">Stáhnout klienta</a></li>
                        <li><a href="#">Stáhnout patch</a></li>
                        <li><a href="#">Forum</a></li>
                    </ul>
                </div>
                <div class="bottom"></div>
            </div>
        </div>
        
        <div class="footer">
        </div>
    </div>
</body>
</html>