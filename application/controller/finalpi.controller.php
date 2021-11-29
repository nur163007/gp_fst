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

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo json_encode(SubmitFinalPIBOQ());
    }
}

function SubmitFinalPIBOQ()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }else{
        //$res["status"] = 0;
    	//$res["message"] = 'Valid reference code.'.$refId;
    	//return json_encode($res);
    }

    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $pinum = replaceTextRegex($_POST['pinum']);
    $pivalue = htmlspecialchars($_POST['pivalue'],ENT_QUOTES, "ISO-8859-1");
    $shipmode = htmlspecialchars($_POST['shipmode'],ENT_QUOTES, "ISO-8859-1");
    $hscsea = htmlspecialchars($_POST['hscsea'],ENT_QUOTES, "ISO-8859-1");
    $hscode = htmlspecialchars($_POST['hscode'],ENT_QUOTES, "ISO-8859-1");
    
    $pidate = htmlspecialchars($_POST['pidate'],ENT_QUOTES, "ISO-8859-1");    
    $pidate = date('Y-m-d', strtotime($pidate));
    
    $basevalue = htmlspecialchars($_POST['basevalue'],ENT_QUOTES, "ISO-8859-1");
    
    $origin = '';
    foreach($_POST['origin'] as $val) {
        if(strlen($origin)>0){$origin .= ',';}
        $origin.= htmlspecialchars($val,ENT_QUOTES, "ISO-8859-1");
    }
    
    $negobank = htmlspecialchars($_POST['negobank'],ENT_QUOTES, "ISO-8859-1");
    $shipport = htmlspecialchars($_POST['shipport'],ENT_QUOTES, "ISO-8859-1");
    $lcbankaddress = htmlspecialchars($_POST['lcbankaddress'],ENT_QUOTES, "ISO-8859-1");
    $productiondays = htmlspecialchars($_POST['productiondays'],ENT_QUOTES, "ISO-8859-1");
    $buyercontact = htmlspecialchars($_POST['buyercontact'],ENT_QUOTES, "ISO-8859-1");
    $techcontact = htmlspecialchars($_POST['techcontact'],ENT_QUOTES, "ISO-8859-1");
    
    if(!isset($_POST['messageUserYes'])){ $messageUser = 'NULL'; } else{ $messageUser = "'".htmlspecialchars($_POST['messageUser'],ENT_QUOTES, "ISO-8859-1")."'"; };
    
    // attachment data in an 3D array
    $attachFinalPI = htmlspecialchars($_POST['attachFinalPI'],ENT_QUOTES, "ISO-8859-1");
    $attachFinalBOQ = htmlspecialchars($_POST['attachFinalBOQ'],ENT_QUOTES, "ISO-8859-1");
    $attachFinalCatelog = htmlspecialchars($_POST['attachFinalCatelog'],ENT_QUOTES, "ISO-8859-1");
    
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
    $pivalue = stripslashes($pivalue);
    $shipmode = stripslashes($shipmode);
    $hscsea = stripslashes($hscsea);
    $hscode = stripslashes($hscode);    
    $pidate = stripslashes($pidate);
    $basevalue = stripslashes($basevalue);    
    $origin = stripslashes($origin);
    $negobank = stripslashes($negobank);
    $shipport = stripslashes($shipport);
    $lcbankaddress = stripslashes($lcbankaddress);
    $productiondays = stripslashes($productiondays);
    $buyercontact = stripslashes($buyercontact);
    $techcontact = stripslashes($techcontact);
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
    $pivalue = $objdal->real_escape_string($pivalue);
    $shipmode = $objdal->real_escape_string($shipmode);
    $hscsea = $objdal->real_escape_string($hscsea);
    $hscode = $objdal->real_escape_string($hscode);    
    $pidate = $objdal->real_escape_string($pidate);
    $basevalue = $objdal->real_escape_string($basevalue);    
    $origin = $objdal->real_escape_string($origin);
    $negobank = $objdal->real_escape_string($negobank);
    $shipport = $objdal->real_escape_string($shipport);
    $lcbankaddress = $objdal->real_escape_string($lcbankaddress);
    $productiondays = $objdal->real_escape_string($productiondays);
    $buyercontact = $objdal->real_escape_string($buyercontact);
    $techcontact = $objdal->real_escape_string($techcontact);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	// update new po
    $query = "UPDATE `wc_t_po` SET 
		`pinum` = '$pinum',
        `pivalue` = $pivalue,
        `hscode` = '$hscode',
        `hscsea` = '$hscsea',
        `shipmode` = '$shipmode',
        `pidate` = '$pidate',
        `basevalue` = '$basevalue',
        `origin` = '$origin',
        `negobank` = '$negobank',
        `shipport` = '$shipport',
        `lcbankaddress` = '$lcbankaddress',
        `productiondays` = $productiondays,
        `buyercontact` = '$buyercontact',
        `techcontact` = '$techcontact',
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip'
        WHERE `poid` = '$poid';";
	$objdal->insert($query);
	//echo($query);

    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_Final_PI_Submitted,
        'status' => 1,
        'msg' => $messageUser,
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$poid', 'Final PI', '$attachFinalPI', $user_id, '$ip', $loginRole),
        ('$poid', 'Final BOQ', '$attachFinalBOQ', $user_id, '$ip', $loginRole),
        ('$poid', 'FinalCatalog', '$attachFinalCatelog', $user_id, '$ip', $loginRole);";
    
	$objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($poid);
    
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'Final PI BOQ Catelog submitted.';
	return $res;
}


?>