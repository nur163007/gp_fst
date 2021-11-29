<?php
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
if(isset($_GET["v"]) && !empty($_GET["v"]))
{
	ValidateSession();
}

//Extend session
if(isset($_GET["sess_ext"]) && !empty($_GET["sess_ext"]))
{
    $currentTime = time();
    $_SESSION[session_prefix . 'wclogin_expire'] = $currentTime + 3600;    //1 hour in seconds
}

function ValidateSession()
{
	$now = time();
	if(!isset($_SESSION[session_prefix.'wclogin_userid']) || $now > $_SESSION[session_prefix.'wclogin_expire']){
		
        if(isset($_SESSION['wclogin_logout']) && $_SESSION['wclogin_logout'] == 1){
            echo 1;
		} else {
            session_destroy(); // Destroying All Sessions
            // Unset all of the session variables
            $_SESSION = array();
            echo 0;
        }
	}
	else{
		echo 1;
	}
}
?>