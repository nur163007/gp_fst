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

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	
			echo GetLCOCapex($_GET['lc']);
			break;
        case 2:	
			echo GetLCO($_GET['lc']);
			break;
        case 3:	
			echo getChargeStatus($_GET['lc']);
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["LcNo"]) || isset($_POST["LcNo"])){
		echo SaveBankCharge();
	}
}

// Insert or update
function SaveBankCharge()
{
	global $user_id;
	global $loginRole;
	
	$LcNo = htmlspecialchars($_POST['LcNo'],ENT_QUOTES, "ISO-8859-1");
	$pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
	
    $commission = htmlspecialchars($_POST['commission'],ENT_QUOTES, "ISO-8859-1");
    $commission = str_replace(",", "", $commission);
	
    $LcCommissionRate = htmlspecialchars($_POST['LcCommissionRate'],ENT_QUOTES, "ISO-8859-1");
	$exchangeRate = htmlspecialchars($_POST['exchangeRate'],ENT_QUOTES, "ISO-8859-1");
	
    $comissionBDT = htmlspecialchars($_POST['comissionBDT'],ENT_QUOTES, "ISO-8859-1");
    $comissionBDT = str_replace(",", "", $comissionBDT);
	
    $cableCharge = htmlspecialchars($_POST['cableCharge'],ENT_QUOTES, "ISO-8859-1");
    $cableCharge = str_replace(",", "", $cableCharge);  
	
    $otherCharge = htmlspecialchars($_POST['otherCharge'],ENT_QUOTES, "ISO-8859-1"); 
    $otherCharge = str_replace(",", "", $otherCharge);  
	
    $nonVAtOtherCharge = htmlspecialchars($_POST['nonVAtOtherCharge'],ENT_QUOTES, "ISO-8859-1"); 
    $nonVAtOtherCharge = str_replace(",", "", $nonVAtOtherCharge);  
	
    $chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");  
	
    $lcCommAddVAT = htmlspecialchars($_POST['lcCommAddVAT'],ENT_QUOTES, "ISO-8859-1"); 
    $lcCommAddVAT = str_replace(",", "", $lcCommAddVAT);  
	
    $vatOnComm = htmlspecialchars($_POST['vatOnComm'],ENT_QUOTES, "ISO-8859-1");
    $vatOnComm = str_replace(",", "", $vatOnComm);  
	
    $vatOnOtherCharge = htmlspecialchars($_POST['vatOnOtherCharge'],ENT_QUOTES, "ISO-8859-1"); 
    $vatOnOtherCharge = str_replace(",", "", $vatOnOtherCharge); 
	
    $totalVAT = htmlspecialchars($_POST['totalVAT'],ENT_QUOTES, "ISO-8859-1"); 
    $totalVAT = str_replace(",", "", $totalVAT); 
	
    $totalCharge = htmlspecialchars($_POST['totalCharge'],ENT_QUOTES, "ISO-8859-1"); 
    $totalCharge = str_replace(",", "", $totalCharge);
	
    $capex = htmlspecialchars($_POST['capex'],ENT_QUOTES, "ISO-8859-1");  
    $capex = str_replace(",", "", $capex);
	
    $vatRebateOnLcComm = htmlspecialchars($_POST['vatRebateOnLcComm'],ENT_QUOTES, "ISO-8859-1"); 
    $vatRebateOnLcComm = str_replace(",", "", $vatRebateOnLcComm); 
    $vatRebateOnLcCommRate = htmlspecialchars($_POST['vatRebateOnLcCommRate'],ENT_QUOTES, "ISO-8859-1"); 
    $vatRebateOnLcCommRate = str_replace(",", "", $vatRebateOnLcCommRate);
	
    $vatRebateOnOtherCharges = htmlspecialchars($_POST['vatRebateOnOtherCharges'],ENT_QUOTES, "ISO-8859-1");  
    $vatRebateOnOtherCharges = str_replace(",", "", $vatRebateOnOtherCharges);
    $vatRebateOnOtherChargesRate = htmlspecialchars($_POST['vatRebateOnOtherChargesRate'],ENT_QUOTES, "ISO-8859-1");  
    $vatRebateOnOtherChargesRate = str_replace(",", "", $vatRebateOnOtherChargesRate);
	
    $totalRebate = htmlspecialchars($_POST['totalRebate'],ENT_QUOTES, "ISO-8859-1"); 
    $totalRebate = str_replace(",", "", $totalRebate); 
	
    $chargeBearer = htmlspecialchars($_POST['chargeBearer'],ENT_QUOTES, "ISO-8859-1");  
	
    $payOrderIssueCharge = htmlspecialchars($_POST['payOrderIssueCharge'],ENT_QUOTES, "ISO-8859-1");  
	$payOrderIssueCharge = str_replace(",", "", $payOrderIssueCharge);
    
    $vatPayOrderIssueCharge = htmlspecialchars($_POST['vatPayOrderIssueCharge'],ENT_QUOTES, "ISO-8859-1");
    $vatPayOrderIssueCharge = str_replace(",", "", $vatPayOrderIssueCharge);  
    
	$vatRebateOnPayOrderCharge = htmlspecialchars($_POST['vatRebateOnPayOrderCharge'],ENT_QUOTES, "ISO-8859-1"); 
    $vatRebateOnPayOrderCharge = str_replace(",", "", $vatRebateOnPayOrderCharge); 
	$vatRebateOnPayOrderChargeRate = htmlspecialchars($_POST['vatRebateOnPayOrderChargeRate'],ENT_QUOTES, "ISO-8859-1"); 
    $vatRebateOnPayOrderChargeRate = str_replace(",", "", $vatRebateOnPayOrderChargeRate); 
	
    $totalChargePayOrder = htmlspecialchars($_POST['totalChargePayOrder'],ENT_QUOTES, "ISO-8859-1"); 
    $totalChargePayOrder = str_replace(",", "", $totalChargePayOrder);
    
    $payorderChargeType = htmlspecialchars($_POST['payOrderChargeType'],ENT_QUOTES, "ISO-8859-1");
    
	// attachment data in an 3D array
    $attachBankCharge = htmlspecialchars($_POST['attachBankCharge'],ENT_QUOTES, "ISO-8859-1");
    $attachIssueCharge = htmlspecialchars($_POST['attachIssueCharge'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
	$LcNo = stripslashes($LcNo);
	$commission = stripslashes($commission);
	$LcCommissionRate = stripslashes($LcCommissionRate);
	$exchangeRate = stripslashes($exchangeRate);
	$comissionBDT = stripslashes($comissionBDT);
	$cableCharge = stripslashes($cableCharge);
	$otherCharge = stripslashes($otherCharge);
	$nonVAtOtherCharge = stripslashes($nonVAtOtherCharge);
	$chargeType = stripslashes($chargeType);
	$lcCommAddVAT = stripslashes($lcCommAddVAT);
	$vatOnComm = stripslashes($vatOnComm);
	$vatOnOtherCharge = stripslashes($vatOnOtherCharge);
	$totalVAT = stripslashes($totalVAT);
	$totalCharge = stripslashes($totalCharge);
	$capex = stripslashes($capex);
	$vatRebateOnLcComm = stripslashes($vatRebateOnLcComm);
	$vatRebateOnLcCommRate = stripslashes($vatRebateOnLcCommRate);
	$vatRebateOnOtherCharges = stripslashes($vatRebateOnOtherCharges);
	$vatRebateOnOtherChargesRate = stripslashes($vatRebateOnOtherChargesRate);
	$totalRebate = stripslashes($totalRebate);
	$chargeBearer = stripslashes($chargeBearer);
	$payOrderIssueCharge = stripslashes($payOrderIssueCharge);
	$vatPayOrderIssueCharge = stripslashes($vatPayOrderIssueCharge);
	$vatRebateOnPayOrderCharge = stripslashes($vatRebateOnPayOrderCharge);
	$vatRebateOnPayOrderChargeRate = stripslashes($vatRebateOnPayOrderChargeRate);
	$totalChargePayOrder = stripslashes($totalChargePayOrder);
	$payorderChargeType = stripslashes($payorderChargeType);
	
	$objdal = new dal();
	
	$LcNo = $objdal->real_escape_string($LcNo);
	$commission = $objdal->real_escape_string($commission);
	$LcCommissionRate = $objdal->real_escape_string($LcCommissionRate);
	$exchangeRate = $objdal->real_escape_string($exchangeRate);
	$comissionBDT = $objdal->real_escape_string($comissionBDT);
	$cableCharge = $objdal->real_escape_string($cableCharge);
	$otherCharge = $objdal->real_escape_string($otherCharge);
	$nonVAtOtherCharge = $objdal->real_escape_string($nonVAtOtherCharge);
	$chargeType = $objdal->real_escape_string($chargeType);
	$lcCommAddVAT = $objdal->real_escape_string($lcCommAddVAT);
	$vatOnComm = $objdal->real_escape_string($vatOnComm);
	$vatOnOtherCharge = $objdal->real_escape_string($vatOnOtherCharge);
	$totalVAT = $objdal->real_escape_string($totalVAT);
	$totalCharge = $objdal->real_escape_string($totalCharge);
	$capex = $objdal->real_escape_string($capex);
	$vatRebateOnLcComm = $objdal->real_escape_string($vatRebateOnLcComm);
	$vatRebateOnLcCommRate = $objdal->real_escape_string($vatRebateOnLcCommRate);
	$vatRebateOnOtherCharges = $objdal->real_escape_string($vatRebateOnOtherCharges);
	$vatRebateOnOtherChargesRate = $objdal->real_escape_string($vatRebateOnOtherChargesRate);
	$totalRebate = $objdal->real_escape_string($totalRebate);
	$chargeBearer = $objdal->real_escape_string($chargeBearer);
	$payOrderIssueCharge = $objdal->real_escape_string($payOrderIssueCharge);
	$vatPayOrderIssueCharge = $objdal->real_escape_string($vatPayOrderIssueCharge);
	$vatRebateOnPayOrderCharge = $objdal->real_escape_string($vatRebateOnPayOrderCharge);
	$vatRebateOnPayOrderChargeRate = $objdal->real_escape_string($vatRebateOnPayOrderChargeRate);
	$totalChargePayOrder = $objdal->real_escape_string($totalChargePayOrder);
	$payorderChargeType = $objdal->real_escape_string($payorderChargeType);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

    // Checking if this data is already exist
    $query = "SELECT COUNT(*) AS `exist` FROM `wc_t_lc_opening_bank_charge` WHERE `LcNo` = '$LcNo';";
    $objdal->read($query);

    $obcExist = 0;
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $obcExist = $res['exist'];
    }

    if($obcExist==0) {
		$taskMessage = 'Insert new data';
    	$query = "INSERT INTO `wc_t_lc_opening_bank_charge` SET 
			`LcNo` = '$LcNo', 
			`commission` = $commission, 
			`LcCommissionRate` = $LcCommissionRate, 
			`exchangeRate` = $exchangeRate, 
			`comissionBDT` = $comissionBDT, 
			`cableCharge` = $cableCharge,  
			`otherCharge` = $otherCharge, 
			`nonVAtOtherCharge` = $nonVAtOtherCharge, 
			`chargeType` = $chargeType, 
			`lcCommAddVAT` = $lcCommAddVAT, 
			`vatOnComm` = $vatOnComm, 
			`vatOnOtherCharge` = $vatOnOtherCharge, 
			`totalVAT` = $totalVAT, 
			`totalCharge` = $totalCharge,
			`capex` = $capex,  
			`vatRebateOnLcComm` = $vatRebateOnLcComm, 
			`vatRebateOnLcCommRate` = $vatRebateOnLcCommRate, 
			`vatRebateOnOtherCharges` = $vatRebateOnOtherCharges, 
			`vatRebateOnOtherChargesRate` = $vatRebateOnOtherChargesRate, 
			`totalRebate` = $totalRebate, 
			`chargeBearer` = b'$chargeBearer', 
			`payOrderIssueCharge` = $payOrderIssueCharge, 
			`vatPayOrderIssueCharge` = $vatPayOrderIssueCharge,
			`vatRebateOnPayOrderCharge` = $vatRebateOnPayOrderCharge,  
			`vatRebateOnPayOrderChargeRate` = $vatRebateOnPayOrderChargeRate,  
			`totalChargePayOrder` = $totalChargePayOrder,
			`payorderChargeType` = $payorderChargeType;";

    	$objdal->insert($query);

    } else {
    	$taskMessage = 'Update new data';
        $query = "UPDATE `wc_t_lc_opening_bank_charge` SET 
			`commission` = $commission, 
			`LcCommissionRate` = $LcCommissionRate, 
			`exchangeRate` = $exchangeRate, 
			`comissionBDT` = $comissionBDT, 
			`cableCharge` = $cableCharge,  
			`otherCharge` = $otherCharge, 
			`nonVAtOtherCharge` = $nonVAtOtherCharge, 
			`chargeType` = $chargeType, 
			`lcCommAddVAT` = $lcCommAddVAT, 
			`vatOnComm` = $vatOnComm, 
			`vatOnOtherCharge` = $vatOnOtherCharge, 
			`totalVAT` = $totalVAT, 
			`totalCharge` = $totalCharge,
			`capex` = $capex,  
			`vatRebateOnLcComm` = $vatRebateOnLcComm, 
			`vatRebateOnLcCommRate` = $vatRebateOnLcCommRate, 
			`vatRebateOnOtherCharges` = $vatRebateOnOtherCharges, 
			`vatRebateOnOtherChargesRate` = $vatRebateOnOtherChargesRate, 
			`totalRebate` = $totalRebate, 
			`chargeBearer` = b'$chargeBearer', 
			`payOrderIssueCharge` = $payOrderIssueCharge, 
			`vatPayOrderIssueCharge` = $vatPayOrderIssueCharge,
			`vatRebateOnPayOrderCharge` = $vatRebateOnPayOrderCharge,  
			`vatRebateOnPayOrderChargeRate` = $vatRebateOnPayOrderChargeRate,  
			`totalChargePayOrder` = $totalChargePayOrder,
			`payorderChargeType` = $payorderChargeType
			WHERE `LcNo` = '$LcNo';";

        $objdal->update($query);
    }

    /*for debug*/
    //echo($query);
    
 // insert attachment
	if($attachBankCharge!="") {
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `LcNo`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$pono', '$LcNo', 'Bank Charge Advice', '$attachBankCharge', $user_id, '$ip', $loginRole)";
        $objdal->insert($query);
		//echo($query);
		//Transfer file from 'temp' directory to respective 'docs' directory
		fileTransferTempToDocs($pono);
    }
    if($attachIssueCharge!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `LcNo`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES
        ('$pono', '$LcNo', 'Pay Order Issue Charge', '$attachIssueCharge', $user_id, '$ip', $loginRole)";
        $objdal->insert($query);
		//echo($query);
		//Transfer file from 'temp' directory to respective 'docs' directory
		fileTransferTempToDocs($pono);
    }

    /*for debug*/
    /*echo($query);*/
	//Add info to activity log table
	addActivityLog(requestUri, $taskMessage, $user_id, 1);
	
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

function GetLCOCapex($lcno)
{
    $objdal = new dal();
    $query = "SELECT `capex`
        FROM `wc_t_lc_opening_bank_charge` 
        WHERE `LcNo` = '$lcno';";
    $objdal->read($query);
    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
        //$ciVal = $ciAmount;
    }
    unset($objdal);
    return json_encode($res);
    //echo $query;
}

function GetLCO($lcno){
    $objdal = new dal();
	$query = "SELECT *,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `lcno`='$lcno' AND `title`='Bank Charge Advice' order by attachedon desc limit 1) `attachBankCharge`,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `lcno`='$lcno' AND `title`='Pay Order Issue Charge' order by attachedon desc limit 1) `attachIssueCharge`
        FROM `wc_t_lc_opening_bank_charge` 
        WHERE `LcNo` = '$lcno';";
	$objdal->read($query);
	$result = "";
    if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $result = json_encode($res);
	}
	unset($objdal);
	return $result;
}

function getChargeStatus($lcno)
{
	$objdal = new dal();
	$query = "SELECT COUNT(*) `status` FROM `wc_t_lc_opening_bank_charge` WHERE `LcNo` = '$lcno';";
	$objdal->read($query);
	$result = 0;
    if(!empty($objdal->data)){
		$result = $objdal->data[0]['status'];
	}
	unset($objdal);
	return $result;
}

?>

