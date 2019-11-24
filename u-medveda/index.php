<?php
	$arguments = explode("/", $_GET["arg"]);
	
	$param[1] = $arguments[0];
	$param[2] = $arguments[1];
	$param[3] = $arguments[2];
	
	$root = $_SERVER['DOCUMENT_ROOT'];
	$dir  = "";
	
	switch($param[1])
	{
		case "":
		default:
			$title = "Úvodní stránka";
			break;
			
		case "menu":
			$title = "Obědové menu";
			break;
			
		case "jidelni-listek":
			$title = "Jídelní lístek";
			break;
		
		case "rezervace":
			$title = "Rezervace";
			break;
		
		case "galerie":
			$title = "Galerie";
			break;
		
		case "kontakt":
			$title = "Kontakt";
			break;
			
		case "404":
			$title = "404 Error";
			break;
	}
?>

<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Restaurace U Medvěda">
	<meta name="keywords" content="Restaurace U Medvěda, restaurace, medvěd, restarauce u">
	<meta name="author" content="">
	<meta name="robots" content="index, follow">
	
	<title>Restaurace U Medvěda - <?php echo $title; ?></title>

	<base href="http://open-source.veteska.cz/u-medveda/">
	
	<link type="text/css" rel="stylesheet" href="css/fonts.css">
	<link type="text/css" rel="stylesheet" href="css/style.css">
	<link type="text/css" rel="stylesheet" href="css/jquery-ui-1.10.3.custom.css">
	
	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
</head>
<body>
	<div class="wrapper">
		<div class="navigation-bar">
			<div class="menu">
				<ul class="left">
					<li><a href="/">Úvod</a></li>
					<li><a href="menu/">Menu</a></li>
					<li><a href="jidelni-listek/">Jídelní lístek</a></li>
				</ul>
				
				<div class="logo">
					<img src="images/logo.png" alt="U Medvěda">
				</div>
				
				<ul class="right">
					<li><a href="rezervace/">Rezervace</a></li>
					<li><a href="galerie/">Galerie</a></li>
					<li><a href="kontakt/">Kontakt</a></li>
				</ul>
			</div>
		</div>
		
		<div class="background">
		</div>
	
		<div class="container content <?php if($param[1] == ""){ echo "content2"; } ?>">
			<?php
				switch($param[1])
				{
					case "":
					default:
						include("pages/home.php");
						break;
					
					case $param[1]:
						{
							if(file_exists("pages/".$param[1].".php"))
								include("pages/".$param[1].".php");
							else								
								include("pages/404.php");
						}
						break;
				}
			 ?>
		</div>
		
		<div class="footer">
			<div class="container">
				<ul class="sub-menu">
					<li><a href="/">Úvod</a></li>
					<li><a href="menu/">Menu</a></li>
					<li><a href="jidelni-listek/">Jídelní lístek</a></li>
					<li><a href="rezervace/">Rezervace</a></li>
					<li><a href="galerie/">Galerie</a></li>
					<li><a href="kontakt/">Kontakt</a></li>
				</ul>
				
				<div class="copyright">
					© <?php echo date("Y"); ?> U Medvěda. Všechna práva vyhrazena.
				</div>
			</div>
		</div>
	</div>
</body>
</html>