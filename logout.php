<?php
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/application/config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

if(isset($_SESSION[session_prefix.'wclogin_userid'])) {
    //Add info to activity log table
    $module = requestUri . '; User-Agent: ' . userAgent;
    addActivityLog($module, 'User logged out thyself.', $user_id, 1);
    $_SESSION[session_prefix . 'wclogin_logout'] = 1;
    if (session_id()) {
        session_destroy(); // Destroying All Sessions
        // Unset all of the session variables
        $_SESSION = array();
    }
}
header("Location: login"); // Redirecting To login Page
?>