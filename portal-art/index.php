<?php
	session_start();
	
	/* IMPORTANT!!!
		xfunkci
		
		zkontrolovat ostatní prohlížeče - MELO BY BYT DONE
	*/
	
	require_once("php/common.php");
	require_once("php/lang.php");

	// pak smazat!!!!!!!!!!!!!!!!!
	$_SESSION['rank'] = "admin";
	$_SESSION['user_email'] = "prvni@ssakhk.cz";
	$_SESSION['user_status'] = 0;
	
	// arguments
	$arguments = explode("/", $_GET["arg"]);
	
	$param[1] = $arguments[0]; // norm 0, beta 4
	$param[2] = $arguments[1]; // norm 1, beta 5
	$param[3] = $arguments[2]; // norm 2, beta 6
	
	switch($param[1])
	{
		case "":
		default:
			{
				$stranka = 'pages/home.php';
				$menu = 'home';
				$title = $menu;
			}
			break;
		
		case $param[1]:
			{
				if(file_exists('pages/'.$param[1].'.php'))
				{
					$stranka = 'pages/'.$param[1].'.php';
					$menu = $param[1];
					$title = $menu;
				}
				else
				{
					$stranka = 'pages/404.php';
					$menu = 'home';
					$title = "404";
				}
			}
			break;
	}
	
	function getMenu()
	{
		global $menu;
		
		switch($menu)
		{
			case "home":
			default:
				{
					echo '<li class="active"><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
				
			case "devices":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li class="active"><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
				
			case "transfers":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li class="active"><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
				
			/*case "gallery":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						echo '<li class="active"><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;*/
				
			case "pastecode":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li class="active"><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
				
			case "devices_allow":
			case "devices_return":
			case "devices_settings":
			case "categories_settings":
			case "projects_settings":
			case "users":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown active"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
			
			case "login":
			case "myaccount":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
				
			case "projects":
				{
					echo '<li><a href="">DOMŮ</a></li>';
                    echo '<li><a href="devices/">ZAŘÍZENÍ</a></li>';
					
					if(isset($_SESSION['rank']))
					{
						echo '<li><a href="transfers/">VYPŮJČENÍ</a></li>';
						//echo '<li><a href="gallery/">GALERIE</a></li>';
						echo '<li><a href="pastecode/">SDÍLENÍ KODU</a></li>';
					}
					
					echo '<li class="active"><a href="projects/">PROJEKTY</a></li>';
						
					if(isset($_SESSION['rank']))
					{
						if($_SESSION['rank'] == "admin")
						{
							echo '<li class="dropdown"><a href="#" class="dropdown-toggle">ADMINISTRACE <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu">';
									echo '<li><a href="devices_allow/">SCHVÁLIT VYPŮJČENÍ</a></li>';
									echo '<li><a href="devices_return/">ZÁPIS VRÁCENÉHO ZAŘÍZENÍ</a></li>';
									echo '<li><a href="devices_settings/">ZAŘÍZENÍ</a></li>';
									echo '<li><a href="categories_settings/">KATEGORIE PRO ZAŘÍZENÍ</a></li>';
									echo '<li class="divider"></li>';
									echo '<li><a href="projects_settings/">PROJEKTY</a></li>';
									echo '<li><a href="users/">UŽIVATELÉ</a></li>';
								echo '</ul>';
							echo '</li>';
						}
					}
				}
				break;
		}
	}
	
	function getTitle()
	{
		global $title;
		
		switch($title)
		{
			case "home":
				echo "Hlavní stránka";
				break;
				
			case "devices":
				echo "Zařízení";
				break;
				
			case "transfers":
				echo "Vypučejní";
				break;
				
			/*case "gallery":
				echo "Galerie";
				break;*/
				
			case "pastecode":
				echo "Sdílení kodu";
				break;
				
			case "users":
				echo "Uživatelé";
				break;
			
			case "myaccount":
				echo "Můj účet";
				break;
				
			case "logout":
				echo "Odhlášení";
				break;
				
			case "login":
				echo "Přihlášení";
				break;
				
			case "projects":
				echo "Projekty";
				break;
				
			case "devices_allow":
				echo "Schválení vypůjčení";
				break;
				
			case "devices_return":
				echo "Vrácení zařízení";
				break;
				
			case "devices_settings":
				echo "Nastavení zařízení";
				break;
				
			case "categories_settings":
				echo "Kategorie zařízení";
				break;
				
			case "projects_settings":
				echo "Nastavení projektů";
				break;
				
			case "404":
				echo "404 Error";
				break;
				
			default:
				echo $title;
				break;
		}
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Projekt Portal Art na ŠŠAKHK">
		<meta name="keywords" content="Portal Art, Střední škola aplikované kybernetiky, kyberna">
		<meta name="author" content="Projekt 21 - Portál Art (Matěj Pokorný, Lukáš Veteška, Antonín Sůva, Jakub Matoušek, Jan Matoušek)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="index, follow">
		
		<title>Portal Art - <?php getTitle(); ?></title>

		<base href="http://open-source.veteska.cz/portal-art/">
		
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<?php
			if($title == "pastecode")
				echo '<link type="text/css" rel="stylesheet" href="css/github.css">';
				
			if($title == "pastecode" && !empty($param[2]))
				echo '<link type="text/css" rel="stylesheet" href="css/jquery-linedtextarea.css">';
		?>
		
		<script src="js/jquery.js"></script>
		<script src="js/jquery-ui.js"></script>
		<script src="js/main.js"></script>
		<?php
			if($title == "pastecode" && !empty($param[2]))
				echo '<script src="js/highlight.pack.js"></script>';
		?>
		
		<script type="application/javascript">
			$(document).ready(function()
			{
				$('a[href=#top]').click(function()
				{
					$('html, body').animate({scrollTop:0}, 'slow');
				});
			});
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div class="header">
				<h1>Portal Art</h1>
				<h2 class="lead">Vítejte</h2>
			</div>
			
			<div class="navbar navbar-inverse">
				<div class="navbar-inner">
					<div class="container">
						<div class="navbar-collapse">
							<ul class="nav">
								<?php getMenu(); ?>
							</ul>
							
							<ul class="nav pull-right">
								<?php
									if(!isset($_SESSION['rank']))
									{
										if($title == "login")
											echo '<li class="active"><a href="login/">PŘIHLÁSIT SE</a></li>';
										else
											echo '<li><a href="login/">PŘIHLÁSIT SE</a></li>';
									}
									else
									{
										if($title == "myaccount")
											echo '<li class="active"><a href="myaccount/">MŮJ ÚČET</a></li>';
										else
											echo '<li><a href="myaccount/">MŮJ ÚČET</a></li>';
										
										echo '<li><a href="logout/">ODHLÁSIT SE</a></li>';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			
			<div class="content" id="content">
				<?php 
					include_once($stranka);
				?>
			</div>
			
			<hr>
		
			<div class="footer">
				<p class="pull-right"><a href="#top">Zpět nahoru</a></p>
				
				<div class="links">
					<a href="">Domů</a>
					<a href="devices/">Zařízení</a>
					
					<?php
						if(!isset($_SESSION['rank']))
							echo '<a href="login/">Přihlásit se</a>';
						else
						{
							/*<a href="gallery/">Galerie</a>*/
							echo '<a href="transfers/">Vypůjčení</a><a href="pastecode/">Sdílení kodu</a><a href="projects/">Projekty</a>';
							
							if($_SESSION['rank'] == "admin")
								echo '<a href="users/">Uživatelé</a>';
							
							echo '<a href="myaccount/">Můj účet</a>';
							echo '<a href="logout/">Odhlásit se</a>';
						}
					?>
				</div>
				
				Vytvořili studenti Střední školy aplikované kybernetiky s.r.o.
				<br>
				Matěj Pokorný, Lukáš Veteška, Antonín Sůva, Jakub Matoušek, Jan Matoušek.
				<br><br>
				© <?php echo date("Y"); ?> <a href="http://ssakhk.cz">SSAKHK</a>.
			</div>
		</div>
	</body>
</html>