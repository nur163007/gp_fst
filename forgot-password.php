<?php
/**
 * Created by Shohel Iqbal.
 * User: aaqa
 * Date: 4/5/2017
 * Time: 12:45 AM
 */

require_once(realpath(dirname(__FILE__) . "/application/config.php")); ?>
<!DOCTYPE html>
<html class="no-js before-run" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="description" content="WebCase7" />
    <meta name="author" content="A'qa technology" />

    <title>Forgot Password | FST</title>

    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png" />
    <link rel="shortcut icon" href="assets/images/favicon1.ico" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-extend.min.css" />
    <link rel="stylesheet" href="assets/css/site.min.css" />

    <!--link rel="stylesheet" href="assets/vendor/animsition/animsition.css"-->
    <link rel="stylesheet" href="assets/vendor/asscrollable/asScrollable.css" />
    <link rel="stylesheet" href="assets/vendor/switchery/switchery.css" />
    <link rel="stylesheet" href="assets/vendor/intro-js/introjs.css" />
    <link rel="stylesheet" href="assets/vendor/slidepanel/slidePanel.css" />
    <link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css" />

    <!-- Page -->
    <link rel="stylesheet" href="assets/css/pages/login.css" />

    <!-- Fonts -->
    <link rel="stylesheet" href="assets/fonts/web-icons/web-icons.min.css" />
    <link rel="stylesheet" href="assets/fonts/brand-icons/brand-icons.min.css" />
    <!--link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic' /-->


    <!--[if lt IE 9]>
    <script src="assets/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="assets/vendor/media-match/media.match.min.js"></script>
    <script src="assets/vendor/respond/respond.min.js"></script>
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
<body class="page-login layout-full">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->


<!-- Page -->
<div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">

    <div class="page-content vertical-align-middle">
        <div class="brand">
            <img class="brand-img" src="assets/images/logo@2x.png" alt="..." />
            <h2 style="color:lightgray">Forgot Your Password ?</h2>
            <p>Input your registered email to reset your password</p>
        </div>

        <p id="login_error" style="display:none; color: #ffcc00;"></p>
        <form method="post" action="" id="forgotpass-form">
            <div class="form-group">
                <label class="sr-only" for="inputUserName">User Name</label>
                <input type="text" class="form-control" id="inputUserName" name="inputUserName" placeholder="username" required />
            </div>
            <div class="form-group">
                <label class="sr-only" for="inputUserName">User Email</label>
                <input type="text" class="form-control" id="inputUserEmail" name="inputUserEmail" placeholder="email address" required />
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="btnResetPassword">Reset Password</button>
        </form>

        <footer class="page-copyright">
            <p>&copy; <?php echo date("Y");?>. grameenphone Ltd.</p>
        </footer>
        <div class="site-footer-right small" style="position: fixed; bottom: 5px; right: 10px; opacity: .7;">
            <i class="wb wb-edit"></i> Powered by <a target="_blank" href="http://aqa.technology">Aqa technology</a>
        </div>
    </div>
</div>
<!-- End Page -->


<!-- Core  -->
<script src="assets/vendor/jquery/jquery.js"></script>
<script src="assets/vendor/bootstrap/bootstrap.js"></script>
<script src="assets/vendor/animsition/jquery.animsition.js"></script>
<script src="assets/vendor/asscroll/jquery-asScroll.js"></script>
<script src="assets/vendor/mousewheel/jquery.mousewheel.js"></script>
<script src="assets/vendor/asscrollable/jquery.asScrollable.all.js"></script>
<script src="assets/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>

<!-- Plugins -->
<script src="assets/vendor/switchery/switchery.min.js"></script>
<script src="assets/vendor/intro-js/intro.js"></script>
<script src="assets/vendor/screenfull/screenfull.js"></script>
<script src="assets/vendor/slidepanel/jquery-slidePanel.js"></script>

<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Scripts -->
<script src="assets/js/site.config.js"></script>
<script src="assets/js/core.js"></script>
<script src="assets/js/site.js"></script>

<script src="application/handler/forgot-password.handler.js"></script>

<script src="assets/js/components/asscrollable.js"></script>
<script src="assets/js/components/animsition.js"></script>
<script src="assets/js/components/slidepanel.js"></script>
<script src="assets/js/components/switchery.js"></script>
<script src="assets/js/components/jquery-placeholder.js"></script>

<script>
    (function (document, window, $) {
        'use strict';

        var Site = window.Site;
        $(document).ready(function () {
            Site.run();
        });
    })(document, window, jQuery);
    $("#inputUserName").focus();
</script>
<script src="assets/js/aqa.script.lib.js"></script>
</body>

</html>
