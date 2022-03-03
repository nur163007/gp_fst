<?php

$maintenance = 0;

if($maintenance==1) {

    header('location:maintenance.php');

} else {

    if (isset($_REQUEST['q'])) {
        $page = $_REQUEST['q'];
    } else {
        $page = 'dashboard';
    }

    if (!ini_get('date.timezone')) {
        date_default_timezone_set('Asia/Dhaka');
    }
    require_once(realpath(dirname(__FILE__) . "/application/config.php"));


    if ($page == 'downloadAttachment') {

        require_once(LIBRARY_PATH . "/" . $page . ".php");

    } else {

        require_once(LIBRARY_PATH . "/_layout.php");

        /*
            Now you can handle all your php logic outside of the template
            file which makes for very clean code!
        */


        //$pageTitle = $page;
        $pageTitle = 'FST : Finance-Sourcing Tool';
        $adminUrl = '/fstV3/';

        // Must pass in variables (as an array) to use in template
        $variables = array(
            'pageTitle' => $pageTitle,
            'adminUrl' => $adminUrl
        );
        /*!
         * If password reset is required,
         * Redirect user to profile page->password change tab
         * **************************************************/
        if ($_SESSION['fst_wclogin_isPassResetRequired'] == true && $page != 'profile') {
            //$page = 'profile';
            header('location:profile');
        }

        renderLayoutWithContentFile($page, $variables);
    }
}
?>
