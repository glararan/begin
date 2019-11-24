<?php
	session_start();
	
	if(!isset($_SESSION['user']))
		$title = "Přihlášení";
	else
	{
		$title = "Administrace";
		
	}
?>

<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex, nofollow">
	
	<title>Restarauce U Medvěda - <?php $title; ?></title>
	
	<?php
		if(!isset($_SESSION['user']))
		{
	?>
	<link type="text/css" rel="stylesheet" href="/admin/css/login.css">
	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<?php
		}
		else
		{
	?>
	<link type="text/css" rel="stylesheet" href="/css/fonts.css">
	<link type="text/css" rel="stylesheet" href="/css/style.css">
	<?php
		}
	?>
</head>
<body>
	<?php
		if(!isset($_SESSION['user']))
		{
			if(file_exists("login.php"))
				include("login.php");
			else								
				include("../pages/404.php");
		}
		else
		{
?>
	<div class="wrapper">
		<div class="navigation-bar">
			<div class="menu">
				<ul class="left">
					<li><a href="">Úvod</a></li>
					<li><a href="">Menu</a></li>
					<li><a href="">Jídelní lístek</a></li>
					<li><a href="">Rezervace</a></li>
					<li><a href="">Galerie</a></li>
					<li><a href="">Odhlásit se</a></li>
				</ul>
			</div>
		</div>
		
		<div class="container content">
			<?php
				switch($param[1])
				{
					case "":
					default:
						include("admin/pages/home.php");
						break;
					
					case $param[1]:
						{
							if(file_exists("admin/pages/".$param[1].".php"))
								include("admin/pages/".$param[1].".php");
							else								
								include("../pages/404.php");
						}
						break;
				}
			?>
		</div>
		
		<div class="footer">
			<div class="container">
				<ul class="sub-menu">
					<li><a href="">Úvod</a></li>
					<li><a href="">Menu</a></li>
					<li><a href="">Jídelní lístek</a></li>
					<li><a href="">Rezervace</a></li>
					<li><a href="">Galerie</a></li>
					<li><a href="">Kontakt</a></li>
				</ul>
				
				<div class="copyright">
					© <?php echo date("Y"); ?> U Medvěda. Všechna práva vyhrazena.
				</div>
			</div>
		</div>
	</div>
	<?php
		}
	?>
</body>
</html>