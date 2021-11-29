<?php
if ( !session_id() ) {
	session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
    Updated on: 2020-08-24 (Hasan Masud)
    1. Added Advance Tax(advanceTax)
    2. Added error message in DB operation
*****************************************************/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	
			echo GetGPRefList();
			break;
		case 2:	
			echo GetCDInfo($_GET["gpref"]);  // OLD
			break;
		case 3:	
			echo GetGPRefNo($_GET["lc"],$_GET["mawb"],$_GET["hawb"],$_GET["bl"]);
			break;
		case 4:	
			echo getCDInformation($_GET["po"],$_GET["shipno"]);
			break;
		case 5:
			echo checkStepOver($_GET['po'],action_CD_Payment_updated_by_Fin,$_GET['shipno']);
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	switch($_POST["userAction"])
	{
		case 1:
			echo reject();
			break;
		case 2:
			echo SaveCustomDuty();
			break;
		case 3:
			echo notifyToSourcing();
			break;
		default:
			break;
	}
}

function reject(){

	$refId = decryptId($_POST["refId"]);
	if(!is_numeric($refId)){
		$res["status"] = 0;
		$res["message"] = 'Invalid reference code.';
		return json_encode($res);
	}

	$poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
	$shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
	$message = htmlspecialchars($_POST['userMessage'],ENT_QUOTES, "ISO-8859-1");
	// Action Log --------------------------------//
	$action = array(
		'refid' => $refId,
		'pono' => "'".$poid."'",
		'shipno' => $shipno,
		'actionid' => action_CD_BE_Rejected_by_Fin,
		'status' => -1,
		'usermsg' => "'".$message."'",
		'msg' => "'CD/BE copy rejected to buyer against PO# ".$poid." Ship# ".$shipno."'",
	);
	UpdateAction($action);
	// End Action Log -----------------------------

	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'Rejected notification has been sent!';
	return json_encode($res);

}
// Insert or update
function SaveCustomDuty()
{
	global $user_id;
	global $loginRole;
	
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }

	$objdal = new dal();

	$poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
	$shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
	$lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
//	$ciValue = htmlspecialchars($_POST['CiValue'],ENT_QUOTES, "ISO-8859-1");
//  $ciValue = str_replace(",", "", $ciValue);
//	$MawbNum = htmlspecialchars($_POST['MawbNum'],ENT_QUOTES, "ISO-8859-1");
//	$HawbNum = htmlspecialchars($_POST['HawbNum'],ENT_QUOTES, "ISO-8859-1");
//	$BlNum = htmlspecialchars($_POST['BlNum'],ENT_QUOTES, "ISO-8859-1");  
//  $gpRefNum = htmlspecialchars($_POST['gpRefNum'],ENT_QUOTES, "ISO-8859-1");
//	$BoENum = htmlspecialchars($_POST['BoENum'],ENT_QUOTES, "ISO-8859-1");   
//	$BoEDate = htmlspecialchars($_POST['BoEDate'],ENT_QUOTES, "ISO-8859-1"); 
//  $BoEDate = date('Y-m-d', strtotime($BoEDate));
	$RequisitionDate = htmlspecialchars($_POST['RequisitionDate'],ENT_QUOTES, "ISO-8859-1"); 
    $RequisitionDate = date('Y-m-d', strtotime($RequisitionDate)); 
	
    $payorderDeliveryTime = htmlspecialchars($_POST['payorderDeliveryTime'],ENT_QUOTES, "ISO-8859-1"); 
    $payorderDeliveryTime = date('Y-m-d H:i:s', strtotime($payorderDeliveryTime));

//	$Beneficiary = htmlspecialchars($_POST['Beneficiary'],ENT_QUOTES, "ISO-8859-1");   
//	$CnFAgent = htmlspecialchars($_POST['CnFAgent'],ENT_QUOTES, "ISO-8859-1");   
	$customDuty = htmlspecialchars($_POST['customDuty'],ENT_QUOTES, "ISO-8859-1");
    $customDuty = str_replace(",", "", $customDuty);
	$Vat = htmlspecialchars($_POST['Vat'],ENT_QUOTES, "ISO-8859-1");
    $Vat = str_replace(",", "", $Vat);
	$vatOnCnFC = htmlspecialchars($_POST['vatOnCnFC'],ENT_QUOTES, "ISO-8859-1");
    $vatOnCnFC = str_replace(",", "", $vatOnCnFC);
	$atv = htmlspecialchars($_POST['atv'],ENT_QUOTES, "ISO-8859-1");
    $atv = str_replace(",", "", $atv);
	$advanceTax = str_replace(",", "", $_POST['advanceTax']);
	$advanceTax = $objdal->sanitizeInput($advanceTax);
	$ait = htmlspecialchars($_POST['ait'],ENT_QUOTES, "ISO-8859-1");
    $ait = str_replace(",", "", $ait);
	$CdPayAmount = htmlspecialchars($_POST['CdPayAmount'],ENT_QUOTES, "ISO-8859-1");
    $CdPayAmount = str_replace(",", "", $CdPayAmount);
	$percentage = htmlspecialchars($_POST['vrPercentage'],ENT_QUOTES, "ISO-8859-1");
    $percentage = str_replace(",", "", $percentage);
	$RebateAmount = htmlspecialchars($_POST['RebateAmount'],ENT_QUOTES, "ISO-8859-1");
    $RebateAmount = str_replace(",", "", $RebateAmount);
	$RemarksFromEA = htmlspecialchars($_POST['RemarksFromEA'],ENT_QUOTES, "ISO-8859-1");
	//$Remarks = htmlspecialchars($_POST['Remarks'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
	$poid = stripslashes($poid);
	$shipno = stripslashes($shipno);
	$lcno = stripslashes($lcno);
//	$ciValue = stripslashes($ciValue);
//	$MawbNum = stripslashes($MawbNum);
//	$HawbNum = stripslashes($HawbNum);
//	$BlNum = stripslashes($BlNum);
//	$gpRefNum = stripslashes($gpRefNum);
//	$BoENum = stripslashes($BoENum);
//	$BoEDate = stripslashes($BoEDate);
	$RequisitionDate = stripslashes($RequisitionDate);
	$payorderDeliveryTime = stripslashes($payorderDeliveryTime);
//	$Beneficiary = stripslashes($Beneficiary);
//	$CnFAgent = stripslashes($CnFAgent);
	$customDuty = stripslashes($customDuty);
	$Vat = stripslashes($Vat);
	$vatOnCnFC = stripslashes($vatOnCnFC);
	$atv = stripslashes($atv);
	$ait = stripslashes($ait);
	$CdPayAmount = stripslashes($CdPayAmount);
	$percentage = stripslashes($percentage);
	$RebateAmount = stripslashes($RebateAmount);
	$RemarksFromEA = stripslashes($RemarksFromEA);
	//$Remarks = stripslashes($Remarks);
	
	$poid = $objdal->real_escape_string($poid);
	$shipno = $objdal->real_escape_string($shipno);
	$lcno = $objdal->real_escape_string($lcno);
//	$ciValue = $objdal->real_escape_string($ciValue);
//	$MawbNum = $objdal->real_escape_string($MawbNum);
//	$HawbNum = $objdal->real_escape_string($HawbNum);
//	$BlNum = $objdal->real_escape_string($BlNum);
//	$gpRefNum = $objdal->real_escape_string($gpRefNum);
//	$BoENum = $objdal->real_escape_string($BoENum);
//	$BoEDate = $objdal->real_escape_string($BoEDate);
	$RequisitionDate = $objdal->real_escape_string($RequisitionDate);
	$payorderDeliveryTime = $objdal->real_escape_string($payorderDeliveryTime);
//	$Beneficiary = $objdal->real_escape_string($Beneficiary);
//	$CnFAgent = $objdal->real_escape_string($CnFAgent);
	$customDuty = $objdal->real_escape_string($customDuty);
	$Vat = $objdal->real_escape_string($Vat);
	$vatOnCnFC = $objdal->real_escape_string($vatOnCnFC);
	$atv = $objdal->real_escape_string($atv);
	$ait = $objdal->real_escape_string($ait);
	$CdPayAmount = $objdal->real_escape_string($CdPayAmount);
	$percentage = $objdal->real_escape_string($percentage);
	$RebateAmount = $objdal->real_escape_string($RebateAmount);
	$RemarksFromEA = $objdal->real_escape_string($RemarksFromEA);
	//$Remarks = $objdal->real_escape_string($Remarks);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    $cdExist = 0;
    
    // Checking if this data is already exist
    $query = "SELECT COUNT(*) AS `exist` FROM `wc_t_custom_duty` WHERE `poid` = '$poid' AND `shipNo` = $shipno AND `lcno` = '$lcno';";
    $objdal->read($query);
    
    if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $cdExist = $res['exist'];
	}
    
    if($payorderDeliveryTime!=""){
	   $payODeliveryTime = "`payorderDeliveryTime` = '$payorderDeliveryTime',";
	}
    
    if($cdExist==0){
        $query = "INSERT INTO `wc_t_custom_duty` SET 
            `poid` = '$poid', 
            `shipNo` = $shipno, 
            `lcno` = '$lcno', 
            `RequisitionDate` = '$RequisitionDate',  
            $payODeliveryTime   
            `customDuty` = $customDuty,  
            `Vat` = $Vat,  
            `vatOnCnFC` = $vatOnCnFC,  
            `atv` = $atv,  
            `advanceTax` = $advanceTax,  
            `ait` = $ait,  
            `CdPayAmount` = $CdPayAmount,  
            `percentage` = $percentage,  
            `RebateAmount` = $RebateAmount,  
            `RemarksFromEA` = '$RemarksFromEA', 
            `createdby` = $user_id, 
    		`createdfrom` = '$ip';";
        $objdal->insert($query, "Could not save custom duty info.");
    } else{
        $query = "UPDATE `wc_t_custom_duty` SET              
            `RequisitionDate` = '$RequisitionDate',  
            $payODeliveryTime   
            `customDuty` = $customDuty,  
            `Vat` = $Vat,  
            `vatOnCnFC` = $vatOnCnFC,  
            `atv` = $atv,  
            `advanceTax` = $advanceTax,  
            `ait` = $ait,  
            `CdPayAmount` = $CdPayAmount,  
            `percentage` = $percentage,  
            `RebateAmount` = $RebateAmount,  
            `RemarksFromEA` = '$RemarksFromEA'
            WHERE `poid` = '$poid' AND `shipNo` = $shipno AND `lcno` = '$lcno';";
        $objdal->update($query, "Could not update custom duty info.");
    }
    /*For Debug*/
    //echo($query);
	
    unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

function notifyToSourcing(){

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'shipno' => $shipno,
        'actionid' => action_CD_Payment_updated_by_Fin,
        'status' => 1,
        'msg' => "'Pay-order requisition updated against PO# ".$poid." and Shipment# ".$shipno."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}

function GetGPRefList()
{
	$objdal = new dal();
	$query = "SELECT `gpRefNum` FROM `wc_t_custom_duty` ";
	$objdal->read($query);
	
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$gpRefNum.'", "text": "'.$gpRefNum.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function GetCDInfo($gpref)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_custom_duty` WHERE `gpRefNum` = '$gpref'";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}


function getCDInformation($pono, $shipno)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_custom_duty` WHERE `poid` = '$pono' AND `shipNo` = $shipno;";
    $res = null;
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
    //return $query;
}

function GetGPRefNo($lcno, $mawb, $hawb, $bl)
{
	$objdal = new dal();
	$query = "SELECT `gpRefNum`, `customDuty`
            FROM `wc_t_custom_duty` 
        WHERE `lcno` = '$lcno' ";
        
    if($mawb!=""){
        $query .= " AND `MawbNum`='$mawb' ";
    }
    
    if($hawb!=""){
        $query .= " AND `HawbNum`='$hawb' '";
    }
    
    if($bl!=""){
        $query .= " AND `BlNum`='$bl'";
    }
    
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}
?>

