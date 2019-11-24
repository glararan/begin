<?php
    require_once("config.php");

    require_once("php/database.php");
    //require_once("php/user.php");
    require_once("php/view.php");

    ob_start();

    session_start();

    $arguments = empty($_GET["arg"]) ? null : explode("/", $_GET["arg"]);
	
	$param[1] = empty($arguments[0]) ? null : $arguments[0];
	$param[2] = empty($arguments[1]) ? null : $arguments[1];
	$param[3] = empty($arguments[2]) ? null : $arguments[2];

    if($param[1] == null)
        $param[1] = "home";

    /*if(User::existsInstance())
        User::getInstance()->refresh();*/

    // DELETE THIS
    $db = new Database();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Itheria</title>

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <h1>Itheria</h1>
        <h2>RolePlay server</h2>
        
        <div class="container">
            <div id="head">
                <div class="row">
                    <div class="col-md-4">
                        <a href="#"><img src="images/logo.png" height="70"></a>
                    </div>

                    <div class="col-md-8">
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

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav">
                                        <li class="active"><a href="#">Novinky <span class="sr-only">(current)</span></a></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Server <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Jak se připojit?</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#">Stav</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#">Tým</a></li>
                                        <li><a href="#">Wiki</a></li>
                                        <li><a href="#">Forum</a></li>
                                    </ul>

                                    <!--<ul class="nav navbar-nav navbar-right">
                                        <li><a href="#">Itheria</a></li>
                                    </ul>-->
                                </div>
                                <!-- /.navbar-collapse -->
                            </div>
                            <!-- /.container-fluid -->
                        </nav>
                    </div>
                </div>
            </div>
            
            <div id="main">
                <!-- <img src="images/lichking.png" id="lichking"> -->
                <img src="images/bear.png" id="bear">
                <!-- <img src="images/logo.png" id="logo">  -->
            </div>
            
            <div id="content">
                <div class="row">
                    <?php
                        View::show($param[1]);
                    ?>
                </div>
            </div>
            
            <footer>
                <div class="col-md-12">&copy; 2016 Itheria</div>
            </footer>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>