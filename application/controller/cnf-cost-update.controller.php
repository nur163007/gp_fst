<?php
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
require_once(LIBRARY_PATH . "/loginId.php");

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["gpRefNum"]) || isset($_POST["gpRefNum"])){
		echo SaveCnfCostUpdate();
	}
}

// Insert or update
function SaveCnfCostUpdate()
{
	global $user_id;
	global $loginRole;
	  
    $gpRefNum = htmlspecialchars($_POST['gpRefNum'],ENT_QUOTES, "ISO-8859-1"); 
	$cNfAmount = htmlspecialchars($_POST['cNfAmount'],ENT_QUOTES, "ISO-8859-1");
	$costUpdateStatus = htmlspecialchars($_POST['costUpdateStatus'],ENT_QUOTES, "ISO-8859-1"); 
    if(!isset($_POST['costUpdateStatus'])){ $costUpdateStatus = 0; } else{ $costUpdateStatus = 1; };  
	$remarks = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");   
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
	$gpRefNum = stripslashes($gpRefNum);
	$cNfAmount = stripslashes($cNfAmount);
	$costUpdateStatus = stripslashes($costUpdateStatus);
	$remarks = stripslashes($remarks);
	
	$objdal = new dal();
	
	$gpRefNum = $objdal->real_escape_string($gpRefNum);
	$cNfAmount = $objdal->real_escape_string($cNfAmount);
	$costUpdateStatus = $objdal->real_escape_string($costUpdateStatus);
	$remarks = $objdal->real_escape_string($remarks);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    $query = "INSERT INTO `wc_t_cnf_cost_update` SET   
        `gpRefNum` = '$gpRefNum',
        `cNfAmount` = $cNfAmount, 
        `costUpdateStatus` = b'$costUpdateStatus',  
        `remarks` = '$remarks', 
        `createdby` = $user_id, 
		`createdfrom` = '$ip';";
    $objdal->insert($query);
    /*For Debug*/
    //echo($query);
	
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

?>

