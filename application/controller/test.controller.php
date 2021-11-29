<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 1/22/2017
 * Time: 1:52 PM
 */

if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/mail/mail2.php");

//echo wcSendEMailTest('shohelic@yahoo.com', 'shohelic@outlook.com', 'Test by Shohel', '');

$id = $_GET['id'];
sendActionEmail($id,'', '', 0);


?>