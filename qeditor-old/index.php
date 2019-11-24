<!doctype html class="csstransforms csstransforms3d csstransitions">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="Projekt QEditor">
	<meta name="keywords" content="QEditor, QEditor - 3D teréní editor, QEditor - 3D terrain editor, terrain, Qt, editor">
	<meta name="author" content="Lukáš Veteška">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>QEditor - 3D editor terénu</title>

	<base href="http://open-source.veteska.cz/qeditor-old/">
	
	<!-- css -->
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/fonts.css" rel="stylesheet">
	<link href="css/jquery.fancybox.css" rel="stylesheet">
	<!-- Optionally add helpers - button, thumbnail and/or media -->
	<link rel="stylesheet" href="css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen">
	<link rel="stylesheet" href="css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen">
	
	<!-- skin color -->
	<link href="css/default.css" rel="stylesheet">
	
	<!--[if lt IE 7]>
				<link href="css/font-awesome-ie7.css" type="text/css" rel="stylesheet">  
	<![endif]-->
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
			  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<!-- navbar -->
	<div class="navbar-wrapper">
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<!-- Responsive navbar -->
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
					</a>
					<h1 class="brand"><a href="index.php">QEditor</a></h1>
					<!-- navigation -->
					<nav class="pull-right nav-collapse collapse">
					<ul id="menu-main" class="nav">
						<li><a title="team" href="#about">O nás</a></li>
						<li><a title="services" href="#services">Technologie</a></li>
						<li><a title="works" href="#works">Galerie</a></li>
						<li><a title="blog" href="#blog">O aplikaci</a></li>
						<li><a title="contact" href="#contact">Kontakt</a></li>
					</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
	<!-- Header area -->
	<div id="header-wrapper" class="header-slider">
		<header class="clearfix">
		<div class="container">
			<div class="row">
				<div class="span12">
					<div id="main-flexslider" class="flexslider">
						<ul class="slides">
							<li>
							<p class="home-slide-content">
								vytvořte si <strong>virtuální svět</strong>
							</p>
							</li>
							<li>
							<p class="home-slide-content">
								nebo <strong>simulaci světa</strong>
							</p>
							</li>
							<li>
							<p class="home-slide-content">
								snadno a lehce s naší aplikací
							</p>
							</li>
						</ul>
					</div>
					<!-- end slider -->
				</div>
			</div>
		</div>
		</header>
	</div>
	<!-- spacer section -->
	<section class="spacer green">
	<div class="container">
		<div class="row">
			<div class="span6 alignright flyLeft">
				<blockquote class="large">
					 Je zde obrovský prostor mezi představou a kreativitou <cite>Mark Simmons, Nett Media</cite>
				</blockquote>
			</div>
			<div class="span6 aligncenter flyRight">
				<i class="icon-file-text icon-10x"></i>
			</div>
		</div>
	</div>
	</section>
	<!-- end spacer section -->
	<!-- section: team -->
	<section id="about" class="section">
	<div class="container">
		<h4>Kdo jsme</h4>
		<div class="row">
			<div class="span4 offset1">
				<div>
					<h2>Studenti královéhradecké <strong>kybernetiky</strong></h2>
					<p>
						Jsme 3 studenti druhého ročníku Střední školy a vyšší odborné školy aplikované kybernetiky s.r.o. v Hradci Králové. Vedoucí projektu a hlavní vývojař aplikace Lukáš Veteška z Bystřice nad Pernštejnem, mobilní vývojář Martin Hruška z Chrastavy a copywriter Jindřich Jadrný z Liberce.<br><span class="pull-right">(K datu 22.3.2014)</span>
					</p>
				</div>
			</div>
			<div class="span6">
				<div class="aligncenter">
					<img src="images/kyberna.png" alt="Střední škola a vyšší odborná škola aplikované kybernetiky s.r.o.">
				</div>
			</div>
		</div>
		<div class="row" id="centerPeople">
			<div class="span2 flyIn">
				<div class="people">
					<img class="team-thumb img-circle" src="images/img-1.jpg" alt="Avatar 1">
					<h3>Lukáš Veteška</h3>
					<p>
						Vývojář
					</p>
				</div>
			</div>
			<div class="span2 flyIn">
				<div class="people">
					<img class="team-thumb img-circle" src="images/img-2.jpg" alt="Avatar 2">
					<h3>Martin Hruška</h3>
					<p>
						Mobilní vývojář
					</p>
				</div>
			</div>
			<div class="span2 flyIn">
				<div class="people">
					<img class="team-thumb img-circle" src="images/img-3.jpg" alt="Avatar 3">
					<h3>Jindřich Jadrný</h3>
					<p>
						Copywriter
					</p>
				</div>
			</div>
		</div>
	</div>
	<!-- /.container -->
	</section>
	<!-- end section: team -->
	<!-- section: services -->
	<section id="services" class="section orange">
	<div class="container">
		<h4>Technologie</h4>
		<!-- Four columns -->
		<div class="row">
			<div class="span3 animated-fast flyIn">
				<div class="service-box">
					<img src="images/c++.png" alt="Cplusplus">
					<h2>C++</h2>
					<p>
						 Jádro celé aplikace je napsáno v programovacím jazyce C++.
					</p>
				</div>
			</div>
			<div class="span3 animated flyIn">
				<div class="service-box">
					<img src="images/qt.png" alt="Qt Framework">
					<h2>Qt Framework</h2>
					<p>
						 Pro lehčí implementaci na různé operační systémy využíváme Qt Framework 5.2.
					</p>
				</div>
			</div>
			<div class="span3 animated-fast flyIn">
				<div class="service-box">
					<img src="images/opengl.png" alt="OpenGL">
					<h2>OpenGL</h2>
					<p>
						 K vykreslení scény v aplikaci využíváme grafickou knihovnu OpenGL 4.2.
					</p>
				</div>
			</div>
			<div class="span3 animated-slow flyIn">
				<div class="service-box">
					<img src="images/assimp.png" alt="Assimp">
					<h2>Assimp</h2>
					<p>
						 Aplikace pracuje s knihovnou Assimp, aby bylo možné využít modelů z obecně známých aplikací.
					</p>
				</div>
			</div>
		</div>
	</div>
	</section>
	<!-- end section: services -->
	<!-- section: works -->
	<section id="works" class="section">
	<div class="container clearfix">
		<h4>Galerie</h4>
		<div class="row">
		<div id="filters" class="span12">
			<ul class="clearfix">
				<li><a href="#" data-filter="*" class="active">
				<h5>Vše</h5>
				</a></li>
				<li><a href="#" data-filter=".screenshots">
				<h5>Screenshoty</h5>
				</a></li>
				<li><a href="#" data-filter=".widgets">
				<h5>Widgety</h5>
				</a></li>
				<li><a href="#" data-filter=".windowsphone">
				<h5>Windows Phone</h5>
				</a></li>
				<li><a href="#" data-filter=".others">
				<h5>Ostatní</h5>
				</a></li>
			</ul>
		</div>
		<!-- END PORTFOLIO FILTERING -->
		<div class="row">
			<div class="span12">
				<div id="portfolio-wrap">
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor.jpg" alt="Screenshot 1">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_1a.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_1a.jpg" alt="Screenshot 2">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_1b.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_1b.jpg" alt="Screenshot 3">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_1c.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_1c.jpg" alt="Screenshot 4">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_1d.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_1d.jpg" alt="Screenshot 5">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_2a.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_2a.jpg" alt="Screenshot 6">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_2b.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_2b.jpg" alt="Screenshot 7">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_2c.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_2c.jpg" alt="Screenshot 8">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_2c2.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_2c2.jpg" alt="Screenshot 9">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_fotka_1.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_fotka_1.jpg" alt="Screenshot 10">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_painting.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_painting.jpg" alt="Screenshot 11">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_painting2.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_painting2.jpg" alt="Screenshot 12">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor_painting3.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor_painting3.jpg" alt="Screenshot 13">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor3.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor3.jpg" alt="Screenshot 14">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/qeditor4.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/qeditor4.jpg" alt="Screenshot 15">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/test.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/test.jpg" alt="Screenshot 16">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/test1.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/test1.jpg" alt="Screenshot 17">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/test2.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/test2.jpg" alt="Screenshot 18">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/vertexShading.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/vertexShading.jpg" alt="Screenshot 19">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/screenshoty/vertexShadingWithout.png" class="fancybox screenshots" data-fancybox-group="gallery">
							<img src="images/galerie/screenshoty/vertexShadingWithout.jpg" alt="Screenshot 20">
							</a>
					<!-- end portfolio item -->
					
					<!-- portfolio item -->
							<a href="images/galerie/_big/widgety/qeditor_fotka_5.png" class="fancybox widgets" data-fancybox-group="gallery">
							<img src="images/galerie/widgety/qeditor_fotka_5.jpg" alt="Widget 1">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/widgety/qeditor_intro.png" class="fancybox widgets" data-fancybox-group="gallery">
							<img src="images/galerie/widgety/qeditor_intro.jpg" alt="Widget 2">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/widgety/qeditor_work.png" class="fancybox widgets" data-fancybox-group="gallery">
							<img src="images/galerie/widgety/qeditor_work.jpg" alt="Widget 3">
					<!-- end portfolio item -->
					
					<!-- portfolio item -->
							<a href="images/galerie/_big/windowsphone/qeditor_fotka_4.png" class="fancybox windowsphone" data-fancybox-group="gallery">
							<img src="images/galerie/windowsphone/qeditor_fotka_4.jpg" alt="Windows Phone 1">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/windowsphone/wp_ss_20140310_0001.png" class="fancybox windowsphone" data-fancybox-group="gallery">
							<img src="images/galerie/windowsphone/wp_ss_20140310_0001.jpg" alt="Windows Phone 2">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/windowsphone/wp_ss_20140310_0002.png" class="fancybox windowsphone" data-fancybox-group="gallery">
							<img src="images/galerie/windowsphone/wp_ss_20140310_0002.jpg" alt="Windows Phone 3">
							</a>
					<!-- end portfolio item -->
					
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/7Le0AiX.jpg" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/7Le0AiX.jpg" alt="Ostatní 1">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/example2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/example2.jpg" alt="Ostatní 2">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain.jpg" alt="Ostatní 3">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain2.jpg" alt="Ostatní 4">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain3.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain3.jpg" alt="Ostatní 5">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain5.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain5.jpg" alt="Ostatní 6">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain6.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain6.jpg" alt="Ostatní 7">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/explain7.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/explain7.jpg" alt="Ostatní 8">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_2d.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_2d.jpg" alt="Ostatní 9">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_2dbug.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_2dbug.jpg" alt="Ostatní 10">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_fotka_2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_fotka_2.jpg" alt="Ostatní 11">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_fotka_3.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_fotka_3.jpg" alt="Ostatní 12">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_vertexShading.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_vertexShading.jpg" alt="Ostatní 13">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_vertexShading2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_vertexShading2.jpg" alt="Ostatní 14">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_water_alfa.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_water_alfa.jpg" alt="Ostatní 15">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor_water_alfa2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor_water_alfa2.jpg" alt="Ostatní 16">
							</a>
					<!-- end portfolio item -->
					<!-- portfolio item -->
							<a href="images/galerie/_big/ostatni/qeditor2.png" class="fancybox others" data-fancybox-group="gallery">
							<img src="images/galerie/ostatni/qeditor2.jpg" alt="Ostatní 17">
							</a>
					<!-- end portfolio item -->
				</div>
			</div>
		</div>
	</div>
	</section>
	<!-- section: blog -->
	<section id="blog" class="section">
	<div class="container">
		<h4>O Aplikaci</h4>
		<!-- Three columns -->
		<div class="row">
			<div class="span12">
				<div class="home-post">
					<div class="post-meta">
						<i class="icon-file icon-2x"></i>
						<span class="date">K datu 23. března 2014</span>
						<span class="tags">Abstrakt</span>
					</div>
					<div class="entry-content">
						<p>
							&emsp;QEditor je aplikace s rozsáhlou sadou nástrojů, které mají společný cíl – pomáhat při vytváření virtuálního světa či simulace světa.<br>
&emsp;Dnes lze najít spoustu řešení pro vytvoření virtuálního světa, na kterých lze postavit videohru či simulaci. Většina z nich však obsahuje zbytečné funkce a nástroje, které při tvorbě nelze upotřebit.<br>
&emsp;Výše uvedené důvody nás motivovaly k tomu, abychom se pokusili navrhnout a posléze i realizovat vlastní projekt, který by tuto aplikaci vytvořil a zbavil uživatele přebytečně bezvýznamných funkcí.<br>
&emsp;Aplikace využívá nejmodernější hardwarové a softwarové technologie. QEditor je možné spustit na operačních systémech Windows, kde má plnou podporu, probíhá interní testování operačního systému Linux Debian, do budoucna plánujeme i podporu operačního systému Mac OS X.<br>
&emsp;Celý program je napsán v programovacím jazyce C++ s frameworkem Qt. Využíváme OpenGL knihovnu pro vykreslení celé scény. Dále používáme nově shaderovací jazyk GLSL, který umožňuje přímou komunikaci s grafickou kartou v počítači.<br>
&emsp;Mezi hlavními nástroji jsou momentálně dostupné tyto: ukazatel, nástroj k upravování terénu, nástroj na kreslení textur, nástroj Vertex shading, nástroj voda a nástroj 3D modelů.<br>
&emsp;Po několikaměsíčním vývoji můžeme konstatovat, že byla vytvořena spolehlivá aplikace, která je použitelná k již zmíněným účelům.<br>
&emsp;Cílem této práce bylo navrhnout a implementovat terén, vytvořit základní funkce jako uložit, načíst a podobné, implementovat základní nástroje pro upravování virtuálního světa. Zároveň po dobu celé práce byla aplikace koncipována tak, aby výsledný terén mohl být použit i v jiném programu.
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="blankdivider30"></div>
	</div>
	</section>
	<!-- spacer section -->
	<section class="spacer bg2">
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="aligncenter">
					<div id="tweet">
					</div>
				</div>
			</div>
		</div>
	</div>
	</section>
	<!-- end spacer section -->
	<!-- section: contact -->
	<section id="contact" class="section green">
	<div class="container">
		<h4>Zeptejte se nás</h4>
		<p>
			 Využijte formulář, který najdete níže a neváhejte se zeptat na cokoliv.
		</p>
		<div class="blankdivider30">
		</div>
		<div class="row">
			<div class="span12">
				<div class="cform" id="contact-form">
					<div id="sendmessage">
						 Vaše zpráva byla odeslána. Děkujeme!
					</div>
					<form action="contact.php" method="post" class="contactForm">
						<div class="row">
							<div class="span6">
								<div class="field your-name">
									<input type="text" name="your-name" placeholder="Jméno" class="cform-text" size="40" data-rule="maxlen:4" data-msg="Vložte alespoň 4 znaky" />
									<div class="validation">
									</div>
								</div>
								<div class="field your-email">
									<input type="text" name="your-email" placeholder="Email" class="cform-text" size="40" data-rule="email" data-msg="Vložte validní email" />
									<div class="validation">
									</div>
								</div>
								<div class="field subject">
									<input type="text" name="subject" placeholder="Předmět" class="cform-text" size="40" data-rule="maxlen:4" data-msg="Vložte alespoň 8 znaků" />
									<div class="validation">
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="field message">
									<textarea name="message" class="cform-textarea" cols="40" rows="10" data-rule="required" data-msg="Napište nám něco prosím"></textarea>
									<div class="validation">
									</div>
								</div>
								<input type="submit" value="Odeslat zprávu" class="btn btn-theme pull-left">
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- ./span12 -->
		</div>
	</div>
	</section>
	<footer>
	<div class="container">
		<div class="row">
			<div class="span6 offset3">
				<ul class="social-networks">
					<li><a href="https://www.youtube.com/watch?v=ajP2bfSsHzI&list=PLs3aLd2a8EWPx3bzOMpV0TdFlZ_KFisLF"><i class="icon-circled icon-bgdark icon-youtube-play icon-2x"></i></a></li>
					<li><a href="https://twitter.com/TheQEditor"><i class="icon-circled icon-bgdark icon-twitter icon-2x"></i></a></li>
					<li><a href="QEditor.pdf"><i class="icon-circled icon-bgdark icon-print icon-2x"></i></a></li>
					<li><a href="https://github.com/glararan/QEditor"><i class="icon-circled icon-bgdark icon-terminal icon-2x"></i></a></li>
				</ul>
				<p class="copyright">
					&copy; 2014 QEditor. Všechna práva vyhrazena.
				</p>
			</div>
		</div>
	</div>
	<!-- ./container -->
	</footer>
	<a href="#" class="scrollup"><i class="icon-angle-up icon-square icon-bgdark icon-2x"></i></a>
	<!-- jQuery -->
	<script src="js/jquery.js"></script>
	<!-- nav -->
	<script src="js/jquery.scrollTo.js"></script>
	<script src="js/jquery.nav.js"></script>
	<!-- localScroll -->
	<script src="js/jquery.localscroll-1.2.7-min.js"></script>
	<!-- bootstrap -->
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
	<script type="text/javascript" src="js/jquery.fancybox-buttons.js?v=1.0.5"></script>
	<script type="text/javascript" src="js/jquery.fancybox-media.js?v=1.0.6"></script>
	<!-- Works scripts -->
	<script src="js/isotope.js"></script>
	<!-- flexslider -->
	<script src="js/jquery.flexslider.js"></script>
	<!-- inview -->
	<script src="js/inview.js"></script>
	<!-- animation -->
	<script src="js/animate.js"></script>
	<!-- twitter -->
	<script src="js/jquery.tweet.js"></script>
	<!-- contact form -->
	<script src="js/validate.js"></script>
	<!-- custom functions -->
	<script src="js/custom.js"></script>
</body>
</html>