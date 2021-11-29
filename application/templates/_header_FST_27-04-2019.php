<!DOCTYPE html>
<html class="no-js before-run" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content="WebCase7" />
    <meta name="author" content="A'qa technology" />

    <title><?php echo $pageTitle;?></title>

    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png" />
    <link rel="shortcut icon" href="assets/images/favicon1.ico" />

    <!-- Stylesheets -->
    <link rel="Stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-extend.min.css" />

    <link rel="stylesheet" href="assets/vendor/animsition/animsition.css" />
    <link rel="stylesheet" href="assets/vendor/asscrollable/asScrollable.css" />
    <link rel="stylesheet" href="assets/vendor/switchery/switchery.css" />
    <link rel="stylesheet" href="assets/vendor/intro-js/introjs.css" />
    <link rel="stylesheet" href="assets/vendor/slidepanel/slidePanel.css" />
    <link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css" />
    <link rel="stylesheet" href="assets/vendor/alertify-js/themes/bootstrap.css" />
    <link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css" />
   
    <!-- Plugin -->
	<?php
	if (is_array($page_specific_css)) {
		foreach( $page_specific_css as $script ) {
			 // Render a script tag
			 echo $script;
		}
	}
	?>
	
    <link rel="stylesheet" href="assets/css/site.css" />
    <link rel="stylesheet" href="assets/css/custom.css" />
    
    <!-- Page -->
    <link rel="stylesheet" href="assets/css/dashboard/v2.css" />

    <!-- Fonts -->
    <link rel="stylesheet" href="assets/fonts/web-icons/web-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/brand-icons/brand-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/font-awesome/font-awesome.min.css" />

    <!--link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic" /-->

    <!--[if lt IE 9]>
    <script src="../assets/vendor/html5shiv/html5shiv.min.js")"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="../assets/vendor/media-match/media.match.min.js")"></script>
    <script src="../assets/vendor/respond/respond.min.js")"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="assets/vendor/modernizr/modernizr.js"></script>
    <script src="assets/vendor/breakpoints/breakpoints.js"></script>
    <script>
        Breakpoints();
        var _adminURL = "<?php echo const_wcadmin_path; ?>";
        var _dashboardURL = "<?php echo const_wcadmin_path; ?>";
    </script>
</head>
<!--body class="dashboard site-menubar-fold" data-auto-menubar="false"-->
<body class="dashboard" style="overflow: hidden;">
<!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">

    <div class="navbar-header">
        <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided"
            data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse"
            data-toggle="collapse">
            <i class="icon wb-more-horizontal" aria-hidden="true"></i>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-search"
            data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon wb-search" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <img class="navbar-brand-logo" src="assets/images/logo.png" title="FST" />
            <span class="navbar-brand-text">FS Tool <span class="comment-meta" style="color: white;">1.1</span></span>
        </div>
    </div>

    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <ul class="nav navbar-toolbar">
                <li class="hidden-float" id="toggleMenubar">
                    <a data-toggle="menubar" href="#" role="button">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">Toggle menubar</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                <li class="hidden-xs" id="toggleFullscreen">
                    <a class="icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                        <span class="sr-only">Toggle fullscreen</span>
                    </a>
                </li>
                <li class="hidden-float">
                    <a class="icon wb-search" data-toggle="collapse" href="#site-navbar-search" role="button">
                        <span class="sr-only">Toggle Search</span>
                    </a>
                </li>

            </ul>
            <!-- End Navbar Toolbar -->

            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">

                <li style="margin-top:20px;">
                    <span><?php echo $_SESSION[session_prefix.'wclogin_fullname']; ?></span>
                </li>
                <li class="dropdown">
                    <a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false"
                        data-animation="slide-bottom" role="button">
                        <span class="avatar avatar-online">
                            <img src="assets/portraits/1.jpg" alt="...">
                            <i></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li role="presentation">
                            <a href="profile" role="menuitem"><i class="icon wb-user" aria-hidden="true"></i>Profile</a>
                        </li>
                        <li role="presentation">
                            <a href="logout" role="menuitem"><i class="icon wb-power" aria-hidden="true"></i>Logout</a>
                        </li>
                    </ul>
                </li>
                <?php if(1==2){ ?>
                <li class="dropdown">
                    <a data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false"
                        data-animation="slide-bottom" role="button">
                        <i class="icon wb-bell" aria-hidden="true"></i>
                        <span class="badge badge-danger up" id="notificationCount1">0</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                        <li class="dropdown-menu-header" role="presentation">
                            <h5>NOTIFICATIONS</h5>
                            <span class="label label-round label-danger" id="notificationCount2">0</span>
                        </li>

                        <li class="list-group" role="presentation">
                            <div data-role="container">
                                <div data-role="content" id="userNotification">

                                </div>
                            </div>
                        </li>
                        <li class="dropdown-menu-footer" role="presentation">
                            <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
                                <i class="icon wb-settings" aria-hidden="true"></i>
                            </a>
                            <a href="javascript:void(0)" role="menuitem">All notifications</a>
                        </li>
                    </ul>
                </li>
                <?php }?>
                <li class="margin-left-45">&nbsp;</li>
            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->

        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon wb-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search"
                            data-toggle="collapse" aria-label="Close">
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Site Navbar Seach -->
    </div>
</nav>
