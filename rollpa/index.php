<?php
	// mysql
	class MySQL
	{
		private $host;
		private $user;
		private $pass;
		private $database;
		
		function __construct()
		{
			$this->host = "wm11.wedos.net";
			$this->user = "a8403_kultira";
			$this->pass = "";
			$this->database = "d8403_kultira";
		}
		
		function Connect()
		{
			mysql_connect($this->host, $this->user, $this->pass);
			mysql_select_db($this->database);
			
			mysql_query("SET NAMES utf8");
		}
		
		function Close()
		{
			mysql_close();
		}
	}

	//
	$arguments = explode("/", $_GET["arg"]);

	$param[1] = $arguments[0];
	$param[2] = $arguments[1];
	$param[3] = $arguments[2];
	
	if(empty($param[2]) && empty($param[3]))
	{
		switch($param[1])
		{
			default:
			case "":
				{
					$stranka = 'pages/main.php';
					$menu = 'homepage';
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
						$menu = 'homepage';
						$title = "404";
					}
				}
				break;
		}
	}
	
	function menu()
	{
		global $menu;
		
		switch($menu)
		{
			case "homepage":
				{
					echo '<li class="current"><a href="/">Úvod</a></li>';
                    echo '<li><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li><a href="/katalog/">Katalog</a></li>';
                    echo '<li><a href="/cenik/">Ceník</a></li>';
                    echo '<li><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
				
			case "cojeto":
				{
					echo '<li><a href="/">Úvod</a></li>';
                    echo '<li class="current"><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li><a href="/katalog/">Katalog</a></li>';
                    echo '<li><a href="/cenik/">Ceník</a></li>';
                    echo '<li><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
				
			case "katalog":
				{
					echo '<li><a href="/">Úvod</a></li>';
                    echo '<li><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li class="current"><a href="/katalog/">Katalog</a></li>';
                    echo '<li><a href="/cenik/">Ceník</a></li>';
                    echo '<li><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
				
			case "cenik":
				{
					echo '<li><a href="/">Úvod</a></li>';
                    echo '<li><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li><a href="/katalog/">Katalog</a></li>';
                    echo '<li class="current"><a href="/cenik/">Ceník</a></li>';
                    echo '<li><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
				
			case "navrh":
				{
					echo '<li><a href="/">Úvod</a></li>';
                    echo '<li><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li><a href="/katalog/">Katalog</a></li>';
                    echo '<li><a href="/cenik/">Ceník</a></li>';
                    echo '<li class="current"><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
				
			case "ostatni":
				{
					echo '<li><a href="/">Úvod</a></li>';
                    echo '<li><a href="/cojeto/">Co je to?</a></li>';
                    echo '<li><a href="/katalog/">Katalog</a></li>';
                    echo '<li><a href="/cenik/">Ceník</a></li>';
                    echo '<li><a href="/navrh/">Návrh konstrukce</a></li>';
                    echo '<li class="current"><a href="/ostatni/">Ostatní produkty</a></li>';
				}
				break;
		}
	}
	
	function title()
	{
		global $title;
		
		switch($title)
		{
			case "homepage":
				echo "Hlavní stránka";
				break;
				
			case "cojeto":
				echo "Co je to?";
				break;
				
			case "katalog":
				echo "Katalog";
				break;
				
			case "cenik":
				echo "Ceník";
				break;
				
			case "navrh":
				echo "Návrh konstrukce";
				break;
				
			case "ostatni":
				echo "Ostatní produkty";
				break;
				
			case "404":
				echo "404 Error";
				break;
				
			default:
				echo $title;
				break;
		}
	}
	
	function menu_border()
	{
		global $title;
		
		if($title == "homepage")
			echo "header_wrapper_no-border";
		else
			echo "header_wrapper";
	}
	
	function keywords()
	{
		echo "rollpa";
	}
	
	function description()
	{
		echo "rollpa";
	}
	
	function slider()
	{
		$mysql = new MySQL();
		
		$mysql->Connect();
		
		$slider_count_query = mysql_query("SELECT COUNT(*) FROM slider");
		$slider_count = mysql_result($slider_count_query, 0);
		
		for($i = 0; $i < $slider_count; $i++)
		{
			$slider_query = mysql_query("SELECT * FROM slider WHERE id = ".$i);
			
			while($row = mysql_fetch_array($slider_query))
			{
				// some div with opacity
				echo "<div class='info'>";
					echo "<h3>".$row['title']."</h3>";
					echo "<p>".$row['text']."</p>";
					
					echo "<a href='".$row['link']."' class='slider_arrow'>Více informací</a>";
				echo "</div>";
				
				echo "<div class='f_right'>";
					echo "<div class='img'>";
						echo "<img src='".$row['image']."' alt='".$row['title']."'>";
					echo "</div>";
				echo "</div>";
			}
		}
		
		$mysql->Close();
	}
	
	function short_news()
	{
		$mysql = new MySQL();
		
		$mysql->Connect();
		
		$news_query = mysql_query("SELECT * FROM short_news ORDER BY id DESC LIMIT 2");
		
		while($row = mysql_fetch_array($news_query))
		{
			echo "<div>";
				echo "<div class='date'>".$row['date']."</div>";
				echo "<p>".$row['text']."</p>";
			echo "</div>";
		}
		
		$mysql->Close();
	}

?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta name="keywords" content="<?php keywords(); ?>">
	<meta name="description" content="<?php description(); ?>">
	<meta name="author" content="Lukas Veteška">
	<meta name="robots" content="index, follow">
    
    <title>Rollpa - <?php title(); ?></title>

	<base href="http://open-source.veteska.cz/rollpa/">
    
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="css/video.css">
    
    <script src="js/video.js"></script>
<meta charset="utf-8">
</head>
<body>
	<div class="<?php menu_border(); ?>">
        <div class="header">
            <div class="logo">
                <h1>Rollpa</h1>
                <h2>svět stavebnicových konstrukcí</h2>
            </div>
                
            <div class="menu">
                <ul>
                    <?php menu(); ?>
                </ul>
            </div>
        </div>
    </div>
	
	<?php include_once($stranka); ?>
    
    <div class="footer">
    	<div class="content">
        	<div>
                <div class="left">
                    <div class="logo">
                        <div class="title">Rollpa</div>
                        <div class="subtitle">svět stavebnicových konstrukcí</div>
                    </div>
                    
                    <div class="site-links">
                        <a href="#"><div class="facebook"></div></a>
                        <a href="#"><div class="twitter"></div></a>
                        <a href="#"><div class="rss"></div></a>
                    </div>
                </div>
                
                <div class="right">
                	<div class="m_left_75 f_left">
                		<h3>Rollpa System Europe s.r.o.</h3>
                        
                        <div class="f_left">
                        	Na Skalkách 1558
                            <br>
                            Brandýs nad Labem
                            <br>
                            250 01
                        </div>
                        
                        <div class="m_left_40 f_right">
                        	Tel: +420 776 024 707
                            <br>
                            e-mail: rollpa@atlas.cz
                            <br>
                            IČO: 283 43 981
                        </div>
                    </div>
                    
                    <div class="f_right">
                    	<div class="f_left">
                        	<div class="f_left">
                            	<a href="#">Úvod</a>
                                <br>
                                <a href="#">Co je to?</a>
                                <br>
                                <a href="#">Typy</a>
                            </div>
                            
                            <div class="m_left_15 f_right">
                            	<a href="#">Ceník</a>
                                <br>
                                <a href="#">Návrh konstrukce</a>
                                <br>
                                <a href="#">Ostatní produkty</a>
                            </div>
                        </div>
                        
                        <div class="m_left_65 m_right_20 f_right">
                        	<a href="#">Napište nám</a>
                            <br>
                            <a href="#">Návštěvní kniha</a>
                            <br>
                            <a href="#">Partneři</a>
                        </div>
                    </div>
                </div>
                
                <div class="clear"></div>
            </div>
            
            <div class="copyright">© 2013 - Lukáš Veteška</div>
        </div>
    </div>
</body>
</html>