<?php
    require_once("../config.php");

    require_once("../php/database.php");
    require_once("../php/user.php");
    require_once("../php/view.php");

    ob_start();

    session_start();

    $arguments = empty($_GET["arg"]) ? null : explode("/", $_GET["arg"]);
	
	$param[1] = empty($arguments[0]) ? null : $arguments[0];
	$param[2] = empty($arguments[1]) ? null : $arguments[1];
	$param[3] = empty($arguments[2]) ? null : $arguments[2];

    if($param[1] == null)
        $param[1] = "home";

    if(User::existsInstance() && (in_array(User::getInstance()->getType(), array(UserType::GM, UserType::Admin))))
        User::getInstance()->refresh();
    else
        header("location: http://itheria.cz");

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

    // DELETE THIS
    $db = new Database(DB_WEB);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>Itheria - Administration</title>

	<base href="http://open-source.veteska.cz/itheria/admin">

    <!-- Tell the browser to be responsive to screen width -->

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="admin/plugins/datatables/dataTables.bootstrap.css">
    <!-- Froala editor -->
    <link href="admin/dist/css/froala/froala_editor.min.css" rel="stylesheet" type="text/css">
    <link href="admin/dist/css/froala/froala_style.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/char_counter.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/code_view.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/colors.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/emoticons.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/file.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/fullscreen.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/image.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/image_manager.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/line_breaker.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/quick_insert.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/table.css">
    <link rel="stylesheet" href="admin/dist/css/froala/plugins/video.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="admin/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="admin/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="admin/dist/css/main.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- jQuery 2.1.4 -->
    <script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="admin/plugins/fastclick/fastclick.min.js"></script>
    <!-- DataTables -->
    <script src="admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Froala editor -->
    <script src="admin/plugins/froala/froala_editor.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/align.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/char_counter.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/code_beautifier.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/code_view.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/colors.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/emoticons.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/entities.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/file.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/font_family.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/font_size.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/fullscreen.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/image.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/image_manager.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/inline_style.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/line_breaker.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/link.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/lists.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/paragraph_format.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/paragraph_style.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/quick_insert.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/quote.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/table.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/save.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/url.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/plugins/video.min.js"></script>
    <script type="text/javascript" src="admin/plugins/froala/languages/cs.js"></script>
    <!-- AdminLTE App -->
    <script src="admin/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="admin/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="admin/plugins/chartjs/Chart.min.js"></script>
    <script src="admin/dist/js/demo.js"></script>
    <!-- panzoom !-->
    <script src="admin/plugins/panzoom/jquery.panzoom.js.min"></script>
</head>
<body class="hold-transition skin-black sidebar-mini fixed">
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="admin/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>I</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Itheria</b> Admin</span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="images/medivh.jpg" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo User::getInstance()->getProfileName(); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="images/medivh.jpg" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo User::getInstance()->getProfileName(); ?> - <?php echo User::getInstance()->getTypeName(); ?>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="admin/profil" class="btn btn-default btn-flat">Profil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="admin/logout" class="btn btn-default btn-flat">Odhlásit se</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">HLAVNÍ MENU</li>
                    <li class="<?php echo isActive("home"); ?>"><a href="admin/"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li class="<?php echo isActive("clanky"); ?>"><a href="admin/clanky"><i class="fa fa-file-text"></i> <span>Články</span></a></li>
                    <li class="<?php echo isActive("uzivatele"); ?>"><a href="admin/uzivatele"><i class="fa fa-users"></i> <span>Uživatelé</span></a></li>
                    <li class="<?php echo isActive("postavy"); ?>"><a href="admin/postavy"><i class="fa fa-gamepad"></i> <span>Postavy</span></a></li>
                    <li class="<?php echo isActive("changelog"); ?>"><a href="admin/changelog"><i class="fa fa-code-fork"></i> <span>Changelog</span></a></li>
                    <li class="<?php echo isActive("ukoly"); ?>"><a href="admin/ukoly"><i class="fa fa-bug"></i> <span>Úkoly</span></a></li>
                    <li class="<?php echo isActive("patchlist"); ?>"><a href="admin/patchlist"><i class="fa fa-download"></i> <span>Patchlist</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-laptop"></i>
                            <span>TEMPLATE</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        
                        <ul class="treeview-menu">
                            <li>
                                <a href="pages/widgets.html">
                                    <i class="fa fa-th"></i> <span>Widgets</span> <small class="label pull-right bg-green">new</small>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-pie-chart"></i>
                                    <span>Charts</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="pages/charts/chartjs.html"><i class="fa fa-circle-o"></i> ChartJS</a></li>
                                    <li><a href="pages/charts/morris.html"><i class="fa fa-circle-o"></i> Morris</a></li>
                                    <li><a href="pages/charts/flot.html"><i class="fa fa-circle-o"></i> Flot</a></li>
                                    <li><a href="pages/charts/inline.html"><i class="fa fa-circle-o"></i> Inline charts</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-laptop"></i>
                                    <span>UI Elements</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> General</a></li>
                                    <li><a href="pages/UI/icons.html"><i class="fa fa-circle-o"></i> Icons</a></li>
                                    <li><a href="pages/UI/buttons.html"><i class="fa fa-circle-o"></i> Buttons</a></li>
                                    <li><a href="pages/UI/sliders.html"><i class="fa fa-circle-o"></i> Sliders</a></li>
                                    <li><a href="pages/UI/timeline.html"><i class="fa fa-circle-o"></i> Timeline</a></li>
                                    <li><a href="pages/UI/modals.html"><i class="fa fa-circle-o"></i> Modals</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-edit"></i> <span>Forms</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="pages/forms/general.html"><i class="fa fa-circle-o"></i> General Elements</a></li>
                                    <li><a href="pages/forms/advanced.html"><i class="fa fa-circle-o"></i> Advanced Elements</a></li>
                                    <li><a href="pages/forms/editors.html"><i class="fa fa-circle-o"></i> Editors</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-table"></i> <span>Tables</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="pages/tables/simple.html"><i class="fa fa-circle-o"></i> Simple tables</a></li>
                                    <li><a href="pages/tables/data.html"><i class="fa fa-circle-o"></i> Data tables</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="pages/calendar.html">
                                    <i class="fa fa-calendar"></i> <span>Calendar</span>
                                    <small class="label pull-right bg-red">3</small>
                                </a>
                            </li>
                            <li>
                                <a href="pages/mailbox/mailbox.html">
                                    <i class="fa fa-envelope"></i> <span>Mailbox</span>
                                    <small class="label pull-right bg-yellow">12</small>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-folder"></i> <span>Examples</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li>
                                    <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Profile</a></li>
                                    <li><a href="pages/examples/login.html"><i class="fa fa-circle-o"></i> Login</a></li>
                                    <li><a href="pages/examples/register.html"><i class="fa fa-circle-o"></i> Register</a></li>
                                    <li><a href="pages/examples/lockscreen.html"><i class="fa fa-circle-o"></i> Lockscreen</a></li>
                                    <li><a href="pages/examples/404.html"><i class="fa fa-circle-o"></i> 404 Error</a></li>
                                    <li><a href="pages/examples/500.html"><i class="fa fa-circle-o"></i> 500 Error</a></li>
                                    <li><a href="pages/examples/blank.html"><i class="fa fa-circle-o"></i> Blank Page</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="fa fa-share"></i> <span>Multilevel</span>
                                    <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                                    <li>
                                        <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                                            <li>
                                                <a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                                                <ul class="treeview-menu">
                                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                                </ul>
                            </li>
                            <li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>
                        </ul>
                    </li>
                    <li class="header">ODKAZY</li>
                    <li><a href=""><i class="fa fa-circle-o text-red"></i> <span>Web</span></a></li>
                    <li><a href="forum/"><i class="fa fa-circle-o text-yellow"></i> <span>Forum</span></a></li>
                    <li><a href="wiki/"><i class="fa fa-circle-o text-aqua"></i> <span>Wiki</span></a></li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php
                View::showAdmin($param[1]);
            ?>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Verze</b> 0.9b | Systém naprogramoval <b>Lukáš Veteška (glararan)</b>
            </div>
            
            <strong>Template v2.3.0 Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->
</body>
</html>