<?php
/**
 * Created by Shohel Iqbal.
 * User: Aaqa
 * Date: 4/5/2017
 * Time: 3:29 AM
 */

if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");


if(isset($_POST['inputUserEmail']) && isset($_POST['inputUserName']) && !empty($_POST['inputUserEmail']) && !empty($_POST['inputUserName']))
{
    $user = htmlspecialchars($_POST['inputUserName'],ENT_QUOTES, "ISO-8859-1");
    $email = htmlspecialchars($_POST['inputUserEmail'],ENT_QUOTES, "ISO-8859-1");

    $pass = randomKey(7);
    $passMd5 = md5($pass);

    $objdal = new dal();

    // To protect MySQL injection for Security purpose
    $user = stripslashes($user);
    //$pass = stripslashes($pass);
    $user = $objdal->real_escape_string($user);
    //$pass = $objdal->real_escape_string($pass);

    $res['success'] = 0;
    $res['msg'] = '';

    $query = "UPDATE `wc_t_users` SET `password`='$passMd5' 
		WHERE `username` = '$user' AND `email` = '$email';";
//    echo $pass;
//    echo $query;
    $rows = $objdal->update($query);

    unset($objdal);
    $res['success'] = 1;
    $res['msg'] = 'Your password reset successfully.<br/>Please check your email.<a href="'.const_wcadmin_path.'login">Signin</a>';

    // code for email
    $subject = "FST password reset successfully";
    $message = "Your new password is <b>$pass</b><br/>We recommend to change your password immediately from Profile->Security tab.";
    wcMailFunction($email, $subject, $message, '');

    echo json_encode($res);
}

?>