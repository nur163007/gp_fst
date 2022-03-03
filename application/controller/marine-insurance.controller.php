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
			echo GetInsuranceInfo($_GET["po"]);
			break;
		case 2:	
			echo GetInsuranceCapex($_GET["po"]);
			break;
		case 3:
			echo getChargeStatus($_GET["po"]);
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["ponum"]) || isset($_POST["ponum"])){
		echo SaveInsurance();
	}
}

// Insert or update
function SaveInsurance()
{
    global $user_id;
    global $loginRole;

    $ponum = htmlspecialchars($_POST['ponum'], ENT_QUOTES, "ISO-8859-1");
    $insuranceValue = htmlspecialchars($_POST['insuranceValue'], ENT_QUOTES, "ISO-8859-1");
    $insuranceValue = str_replace(",", "", $insuranceValue);
    $coverNoteNo = htmlspecialchars($_POST['coverNoteNo'], ENT_QUOTES, "ISO-8859-1");
    $coverNoteDate = htmlspecialchars($_POST['coverNoteDate'], ENT_QUOTES, "ISO-8859-1");
    //$coverNoteDate = str_replace('/', '-', $coverNoteDate);
    $coverNoteDate = date('Y-m-d', strtotime($coverNoteDate));
    $exchangeRate = htmlspecialchars($_POST['exchangeRate'], ENT_QUOTES, "ISO-8859-1");
    $exchangeRate = str_replace(",", "", $exchangeRate);
    $stampDuty = htmlspecialchars($_POST['stampDuty'], ENT_QUOTES, "ISO-8859-1");
    $stampDuty = str_replace(",", "", $stampDuty);
    $otherCharges = htmlspecialchars($_POST['otherCharges'], ENT_QUOTES, "ISO-8859-1");
    $otherCharges = str_replace(",", "", $otherCharges);
    $assuredAmount = htmlspecialchars($_POST['assuredAmount'], ENT_QUOTES, "ISO-8859-1");
    $assuredAmount = str_replace(",", "", $assuredAmount);
    $marine = htmlspecialchars($_POST['marine'], ENT_QUOTES, "ISO-8859-1");
    $marine = str_replace(",", "", $marine);
    $war = htmlspecialchars($_POST['war'], ENT_QUOTES, "ISO-8859-1");
    $war = str_replace(",", "", $war);
    $netPremium = htmlspecialchars($_POST['netPremium'], ENT_QUOTES, "ISO-8859-1");
    $netPremium = str_replace(",", "", $netPremium);
    $vat = htmlspecialchars($_POST['vat'], ENT_QUOTES, "ISO-8859-1");
    $vat = str_replace(",", "", $vat);
    $total = htmlspecialchars($_POST['total'], ENT_QUOTES, "ISO-8859-1");
    $total = str_replace(",", "", $total);
    $chargeType = htmlspecialchars($_POST['chargeType'], ENT_QUOTES, "ISO-8859-1");
    $vatRebate = htmlspecialchars($_POST['vatRebate'], ENT_QUOTES, "ISO-8859-1");
    $vatRebateAmount = htmlspecialchars($_POST['vatRebateAmount'], ENT_QUOTES, "ISO-8859-1");
    $vatRebateAmount = str_replace(",", "", $vatRebateAmount);
    $capex = htmlspecialchars($_POST['capex'], ENT_QUOTES, "ISO-8859-1");
    $capex = str_replace(",", "", $capex);
    $vatPayable = htmlspecialchars($_POST['vatPayable'], ENT_QUOTES, "ISO-8859-1");
    $vatPayable = str_replace(",", "", $vatPayable);
    $premiumBorneBy = htmlspecialchars($_POST['premiumBorneBy'], ENT_QUOTES, "ISO-8859-1");
    $chargeRemarks = htmlspecialchars($_POST['chargeRemarks'], ENT_QUOTES, "ISO-8859-1");
    $servicePerformance = htmlspecialchars($_POST['servicePerformance'], ENT_QUOTES, "ISO-8859-1");
    $serviceRemarks = htmlspecialchars($_POST['serviceRemarks'], ENT_QUOTES, "ISO-8859-1");

    // attachment data in an 3D array
    $attachInsCoverNote = htmlspecialchars($_POST['attachInsCoverNote'], ENT_QUOTES, "ISO-8859-1");
    $attachPayOrderReceivedCopy = htmlspecialchars($_POST['attachPayOrderReceivedCopy'], ENT_QUOTES, "ISO-8859-1");
    $attachInsChargeOther = htmlspecialchars($_POST['attachInsChargeOther'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $ponum = stripslashes($ponum);
    $insuranceValue = stripslashes($insuranceValue);
    $coverNoteNo = stripslashes($coverNoteNo);
    $coverNoteDate = stripslashes($coverNoteDate);
    $exchangeRate = stripslashes($exchangeRate);
    $stampDuty = stripslashes($stampDuty);
    $otherCharges = stripslashes($otherCharges);
    $assuredAmount = stripslashes($assuredAmount);
    $marine = stripslashes($marine);
    $war = stripslashes($war);
    $netPremium = stripslashes($netPremium);
    $vat = stripslashes($vat);
    $total = stripslashes($total);
    $chargeType = stripslashes($chargeType);
    $vatRebate = stripslashes($vatRebate);
    $vatRebateAmount = stripslashes($vatRebateAmount);
    $capex = stripslashes($capex);
    $vatPayable = stripslashes($vatPayable);
    $premiumBorneBy = stripslashes($premiumBorneBy);
    $chargeRemarks = stripslashes($chargeRemarks);
    $servicePerformance = stripslashes($servicePerformance);

    $objdal = new dal();

    $ponum = $objdal->real_escape_string($ponum);
    $insuranceValue = $objdal->real_escape_string($insuranceValue);
    $coverNoteNo = $objdal->real_escape_string($coverNoteNo);
    $coverNoteDate = $objdal->real_escape_string($coverNoteDate);
    $exchangeRate = $objdal->real_escape_string($exchangeRate);
    $stampDuty = $objdal->real_escape_string($stampDuty);
    $otherCharges = $objdal->real_escape_string($otherCharges);
    $assuredAmount = $objdal->real_escape_string($assuredAmount);
    $marine = $objdal->real_escape_string($marine);
    $war = $objdal->real_escape_string($war);
    $netPremium = $objdal->real_escape_string($netPremium);
    $vat = $objdal->real_escape_string($vat);
    $total = $objdal->real_escape_string($total);
    $chargeType = $objdal->real_escape_string($chargeType);
    $vatRebate = $objdal->real_escape_string($vatRebate);
    $vatRebateAmount = $objdal->real_escape_string($vatRebateAmount);
    $capex = $objdal->real_escape_string($capex);
    $vatPayable = $objdal->real_escape_string($vatPayable);
    $premiumBorneBy = $objdal->real_escape_string($premiumBorneBy);
    $chargeRemarks = $objdal->real_escape_string($chargeRemarks);
    $servicePerformance = $objdal->real_escape_string($servicePerformance);
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------

    // Checking if this data is already exist
    $query = "SELECT COUNT(*) AS `exist` FROM `wc_t_insurance_charge` WHERE `ponum` = '$ponum';";
    $objdal->read($query);
    $oicExist = 0;
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $oicExist = $res['exist'];
    }

    if($oicExist==0) {

        $taskMessage = 'Insert new data';
        $query = "INSERT INTO `wc_t_insurance_charge` SET 
			`ponum` = '$ponum', 
			`insuranceValue` = $insuranceValue, 
			`coverNoteNo` = '$coverNoteNo', 
			`coverNoteDate` = '$coverNoteDate', 
			`exchangeRate` = $exchangeRate, 
			`stampDuty` = $stampDuty, 
			`otherCharges` = $otherCharges, 
			`assuredAmount` = $assuredAmount, 
			`marine` = $marine, 
			`war` = $war, 
			`netPremium` = $netPremium, 
			`vat` = $vat, 
			`total` = $total, 
			`chargeType` = $chargeType, 
			`vatRebate` = $vatRebate, 
			`vatRebateAmount` = $vatRebateAmount, 
			`capex` = $capex, 
			`vatPayable` = $vatPayable, 
			`premiumBorneBy` = b'$premiumBorneBy', 
			`chargeRemarks` = '$chargeRemarks', 
			`servicePerformance` = $servicePerformance, 
			`serviceRemarks` = '$serviceRemarks';";
        $objdal->insert($query);

    } else{
        $taskMessage = 'Update new data';
        $query = "UPDATE `wc_t_insurance_charge` SET 
			`insuranceValue` = $insuranceValue, 
			`coverNoteNo` = '$coverNoteNo', 
			`coverNoteDate` = '$coverNoteDate', 
			`exchangeRate` = $exchangeRate, 
			`stampDuty` = $stampDuty, 
			`otherCharges` = $otherCharges, 
			`assuredAmount` = $assuredAmount, 
			`marine` = $marine, 
			`war` = $war, 
			`netPremium` = $netPremium, 
			`vat` = $vat, 
			`total` = $total, 
			`chargeType` = $chargeType, 
			`vatRebate` = $vatRebate, 
			`vatRebateAmount` = $vatRebateAmount, 
			`capex` = $capex, 
			`vatPayable` = $vatPayable, 
			`premiumBorneBy` = b'$premiumBorneBy', 
			`chargeRemarks` = '$chargeRemarks', 
			`servicePerformance` = $servicePerformance, 
			`serviceRemarks` = '$serviceRemarks'
			WHERE `ponum` = '$ponum';";
        $objdal->update($query);

	}
    // insert attachment
    if ($attachInsCoverNote != "") {
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$ponum', 'Insurance Cover Note', '$attachInsCoverNote', $user_id, '$ip', $loginRole)";
        $objdal->insert($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($ponum);
    }
    if($attachPayOrderReceivedCopy!=''){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$ponum', 'Pay Order Receive Copy', '$attachPayOrderReceivedCopy', $user_id, '$ip', $loginRole)";
        $objdal->insert($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($ponum);
    }
    if($attachInsChargeOther!=''){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$ponum', 'Insurance Other Doc', '$attachInsChargeOther', $user_id, '$ip', $loginRole)";
        $objdal->insert($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($ponum);
    }

    // Updating CN request
    $sql = "UPDATE `cn_request` SET `status` = 1 WHERE `po_no` = '$ponum';";
    $objdal->update($sql);

    //echo($query);
    //Add info to activity log table
    addActivityLog(requestUri, $taskMessage, $user_id, 1);
	
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

function GetInsuranceInfo($pono)
{

	$objdal = new dal();
	$query = "SELECT *,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `title`='Insurance Cover Note' ORDER BY ID DESC limit 1) `attachInsCoverNote`,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `title`='Pay Order Receive Copy' ORDER BY ID DESC limit 1) `attachPayOrderReceivedCopy`,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `title`='Insurance Other Doc' ORDER BY ID DESC limit 1) `attachInsChargeOther`
        FROM `wc_t_insurance_charge` WHERE `ponum` = '$pono' ORDER BY `id` DESC Limit 1;";
//echo $query;
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

function GetInsuranceCapex($pono)
{
    $objdal = new dal();
    $query = "SELECT *
        FROM `wc_t_insurance_charge` 
        WHERE `ponum` = '$pono';";
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

function getChargeStatus($pono)
{
	$objdal = new dal();
	$query = "SELECT COUNT(*) `status` FROM `wc_t_insurance_charge` WHERE `ponum` = '$pono';";
	$objdal->read($query);
	$result = 0;
    if(!empty($objdal->data)){
		$result = $objdal->data[0]['status'];
	}
	unset($objdal);
	return $result;
}

?>

