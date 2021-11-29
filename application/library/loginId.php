<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 21-Jul-19
 * Time: 6:04 PM
 */
//Check if the user is logged in. If not then return to login page.
if(isset($_SESSION[session_prefix.'wclogin_userid'])){
    $user_id = $_SESSION[session_prefix.'wclogin_userid'];
    $loginRole = $_SESSION[session_prefix . 'wclogin_role'];
    $companyId = $_SESSION[session_prefix . 'wclogin_company'];
}else{
    header("location: /fst/login");
    exit();
}