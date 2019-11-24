<?php
    require_once("config.php");

    require_once("php/database.php");
    require_once("php/user.php");
    require_once("php/view.php");

    ob_start();

    session_start();

    $arguments = empty($_GET["arg"]) ? null : explode("/", $_GET["arg"]);
	
	$param[1] = empty($arguments[0]) ? null : $arguments[0];
	$param[2] = empty($arguments[1]) ? null : $arguments[1];
	$param[3] = empty($arguments[2]) ? null : $arguments[2];

    if($param[1] == null)
        $param[1] = "home";

    if(User::existsInstance())
        User::getInstance()->refresh();

    // DELETE THIS
    $webDB  = new Database(DB_WEB);
    $authDB = new Database(DB_AUTH);

    function isActive($menu)
    {
        global $param;
        
        if($param[1] == $menu)
            return "active";
            
        return "";
    }

    function isPage()
    {
        global $param;
        
        if($param[1] != "home")
            return true;
        
        return false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Itheria je Cataclysm RP server, který využívá Word of WarCraft lore a zaměřuje se na děj na Northrendu po pádu Lich Kinga.">
    <meta name="keywords" content="Itheria, RP, WoW, World of Warcraft, Roleplay, Cataclysm RP, Cataclysm, Cata">
    <meta name="author" content="Lukáš Veteška">

    <title>Itheria</title>

	<base href="http://open-source.veteska.cz/itheria/">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,300italic,400italic,600italic,700,700italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href="css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="css/main.css" rel="stylesheet">
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
    <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
    <script type="text/javascript">
        window.cookieconsent_options = {"message":"Tento web využívá cookies pro přihlášení na naší webové stránce","dismiss":"OK!","learnMore":"","link":null,"theme":"dark-bottom"};
    </script>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
    <!-- End Cookie Consent plugin -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body <?php if(isPage()) echo 'id="page"'; ?>>
    <div id="wrapper">
        <h1>Itheria</h1>
        <h2>RolePlay server</h2>
        
        <div class="container">
            <div id="head">
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-default">
                            <div class="container-fluid">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>
                                
                                <a href="./" id="logoLink">
                                    <div id="logo"></div>
                                </a>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav">
                                        <li class="<?php echo isActive("home"); ?>"><a href="./">Novinky <span class="sr-only">(current)</span></a></li>
                                        <li class="dropdown <?php echo isActive("jaksepripojit").isActive("stav").isActive("oprojektu").isActive("pravidla"); ?>">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Server <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <!-- <li><a href="./oprojektu">O projektu</a></li>
                                                <li role="separator" class="divider"></li> -->
                                                <li><a href="./pravidla">Pravidla</a></li>
                                                <?php if(!User::isLogged()) { ?><li><a href="./jaksepripojit#registrace">Registrovat</a></li><?php } ?>
                                                <li><a href="./jaksepripojit">Jak se připojit?</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="./stav">Stav</a></li>
                                            </ul>
                                        </li>
                                        <li class="<?php echo isActive("changelog"); ?>"><a href="./changelog">Changelog</a></li>
                                        <li class="<?php echo isActive("tym"); ?>"><a href="./tym">Tým</a></li>
                                    </ul>

                                    <ul class="nav navbar-nav navbar-right">
                                        <?php
                                            if(User::isLogged() && User::getInstance()->getType() == UserType::Admin)
                                                echo '<li><a href="./admin/">Administrace</a></li>';
                                        ?>
                                        <li><a href="./wiki/">Wiki</a></li>
                                        <li><a href="./forum/">Forum</a></li>
                                    </ul>
                                </div>
                                <!-- /.navbar-collapse -->
                            </div>
                            <!-- /.container-fluid -->
                        </nav>
                    </div>
                </div>
            </div>
            
            <div id="main">
                <img src="images/eagle2.png" id="eagle" data-parallaxify-range-x="50" data-parallaxify-range-y="25">
            </div>
            
            <div id="content">
                <div class="row">
                    <?php
                        View::show($param[1]);
                    ?>
                </div>
            </div>
            
            <?php
                View::show("footer-mobile");
            ?>
        </div>
    </div>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/validator.js"></script>
    <script src="js/paralaxify.js"></script>
    <script src="js/parallax.js"></script>
    <script src="js/smooth.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/core.js"></script>
</body>
</html>