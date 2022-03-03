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
			echo GetLCInfoByLC($_GET["lc"]);
			break;
		case 2:	
			echo GetLCList();
			break;
        case 3:	
			echo GetLCInfoByPO($_GET["po"]);
			break;
        case 4:	
			echo GetBankCo($_GET["bank"], $_GET["po"]);
			break;
        case 5:	
			echo getLCDetailInfo($_GET["po"]);
			break;
		default:
			break;
	}
}


if (!empty($_POST)){
    
    switch($_POST["userAction"]){
        case 1:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
               echo SubmitLCOpening();
            }
            break;
        case 2:
            if(!empty($_POST["pono2"]) || isset($_POST["pono2"])){
               echo SendFinalLCCopy();
            }
            break;
        case 3:
            if(!empty($_POST["confChargeId"]) || isset($_POST["confChargeId"])){
               echo SubmitConfCharge();
            }
            break;
        case 4:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
               echo SubmiteEditedTerms();
            }
            break;
        case 5:
            if(!empty($_POST["lcno"]) || isset($_POST["lcno"])){
               echo SubmiteEditedDates();
            }
            break;
        case 6:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo SubmitCN();
            }
            break;
        case 7:
            if(!empty($_POST["ponum1"]) || isset($_POST["ponum1"])) {
                echo AcceptCN();
            }
            break;
        case 8:
            if(!empty($_POST["ponum1"]) || isset($_POST["ponum1"])) {
                echo RejectCN();
            }
            break;
        case 9:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo submitLCRequestToBank();
            }
            break;
        case 10:
            echo submitBCSEx();
            break;
        default:
            break;
    }

}

function SubmiteEditedDates(){
    
    global $user_id;
	global $loginRole;
    
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    
    $lastdateofship = htmlspecialchars($_POST['lastdateofship'],ENT_QUOTES, "ISO-8859-1");
    $lastdateofship = date('Y-m-d', strtotime($lastdateofship));
    
    $lcexpirydate = htmlspecialchars($_POST['lcexpirydate'],ENT_QUOTES, "ISO-8859-1");
    $lcexpirydate = date('Y-m-d', strtotime($lcexpirydate));
    
    $objdal = new dal();
        
    $query = "UPDATE `wc_t_lc` SET 
            `lastdateofship` = '$lastdateofship', 
            `lcexpirydate` = '$lcexpirydate' 
        WHERE `lcno` = '$lcno';";
    //echo $query;
    $objdal->update($query);
    
    unset($objdal);
	
	$res["status"] = 1;
    $res["message"] = 'Change(s) updated successfully.';
	return json_encode($res);
}

function SubmiteEditedTerms(){
    
    global $user_id;
	global $loginRole;
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");    
    $paymentTermsTextEditable = htmlspecialchars($_POST['paymentTermsTextEditable'],ENT_QUOTES, "ISO-8859-1");    
    
    $objdal = new dal();
    
    /**
    * If LC request already exist then clear the terms first
    * then insert again.
    */
    $query = "DELETE FROM `wc_t_payment_terms` WHERE `pono` = '$pono'";
    $objdal->delete($query);
    
    /**
    * Update terms text in LC table
    */
    $query = "UPDATE `wc_t_lc` SET `paymentterms` = '$paymentTermsTextEditable' WHERE `pono` = '$pono';";
    //echo $query;
    $objdal->update($query);
    
    $query = '';
    for($i=0; $i<count($_POST['ppPercentage']); $i++){
        $query = "INSERT INTO `wc_t_payment_terms` SET 
            `pono` = '$pono',
            `partname` = ".$_POST['ppPartName'][$i].",
            `percentage` = ".$_POST['ppPercentage'][$i].",
            `dayofmaturity` = ".$_POST['ppMaturityDay'][$i].",
            `maturityterms` = ".$_POST['ppMaturityTerm'][$i].";";
        
        $objdal->insert($query);
    }
    unset($objdal);
	
	$res["status"] = 1;
    $res["message"] = 'Payment terms updated successfully.';
	return json_encode($res);
}

function GetLCInfoByLC($lcno)
{
	$objdal = new dal();
	$query = "SELECT lc.`id`, lc.`pono`, lc.`lcno`, lc.`lcvalue`, lc.`xeUSD`, lc.`xeBDT`, lc.`lcafno`, lc.`lctype`, lc.`lcissuedate`, lc.`lcexpirydate`, 
        `daysofexpiry`, lc.`lastdateofship`, lc.`lcissuerbank`, lc.`bankaccount`, lc.`bankservice`, lc.`serviceremark`, 
        `insurance`, lc.`producttype`, lc.`cocorigin`, lc.`iplbltolcbank`, lc.`delivcertify`, lc.`qualitycertify`, 
        `advshipdoc`, lc.`advshipdocwithbl`, lc.`addconfirmation`, lc.`preshipinspection`, lc.`transshipment`, 
        `partship`, lc.`confchargeatapp`, lc.`ircno`, lc.`imppermitno`, lc.`tinno`, lc.`vatregno`, lc.`customername`, 
        lc.`customeraddress`, co.`name` AS `lcBank`, (SELECT po.`currency` FROM `wc_t_pi` po WHERE po.`poid` = lc.`pono`) `currency`
        FROM `wc_t_lc` lc 
        LEFT JOIN `wc_t_company` co ON lc.`lcissuerbank` = co.`id`
        WHERE lc.`lcno` = '$lcno';";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function GetLCInfoByPO($pono)
{
	$objdal = new dal();
	$query = "SELECT lc.`id`, lc.`pono`, lc.`lcno`, lc.`lcvalue`, lc.`xeUSD`, lc.`xeBDT`, lc.`lcafno`, lc.`lctype`, lc.`lcissuedate`, lc.`lcexpirydate`, 
        `daysofexpiry`, lc.`lastdateofship`, lc.`lcissuerbank`, lc.`bankaccount`, lc.`bankservice`, lc.`serviceremark`, 
        `insurance`, lc.`producttype`, lc.`cocorigin`, lc.`iplbltolcbank`, lc.`delivcertify`, lc.`qualitycertify`, 
        `advshipdoc`, lc.`advshipdocwithbl`, lc.`addconfirmation`, lc.`preshipinspection`, lc.`transshipment`, 
        `partship`, lc.`confchargeatapp`, lc.`ircno`, lc.`imppermitno`, lc.`tinno`, lc.`vatregno`, lc.`customername`, 
        `customeraddress`, po.`currency`, c.`name` `curname`, ic.`coverNoteNo`
        FROM `wc_t_lc` lc 
            LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
			LEFT JOIN `wc_t_category` c ON c.`id` = po.`currency`
            LEFT JOIN `wc_t_insurance_charge` ic ON lc.`pono` = ic.`ponum`
        WHERE `pono` = '$pono';";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function getLCDetailInfo($id){
    
    $objdal = new dal();
    $sql = "SELECT lc.`pono`, lc.`lcno`, lc.`lcvalue`, lc.`xeUSD`, lc.`xeBDT`, lc.`lcdesc`, lc.`lctype`, lc.`lcissuedate`, 
            lc.`lcexpirydate`, lc.`lastdateofship`, lc.`lcissuerbank`, c1.`name` AS `lcissuerbankname`, lc.`bankaccount`, 
            c2.`name` AS `bankaccountname`, lc.`bankservice`, lc.`serviceremark`, lc.`insurance`, c3.`name` AS `insurancename`, 
            lc.`producttype`, lc.`cocorigin`, lc.`iplbltolcbank`, lc.`delivcertify`, lc.`qualitycertify`, lc.`advshipdoc`, 
            lc.`advshipdocwithbl`, lc.`addconfirmation`, lc.`preshipinspection`, lc.`transshipment`, lc.`partship`, 
            lc.`confchargeatapp`, lc.`ircno`, lc.`imppermitno`, lc.`tinno`, lc.`vatregno`, lc.`customername`, lc.`customeraddress`, 
            lc.`paymentterms`, lc.`createdby`, lc.`createdon`, lc.`lcafno`, lc.`advBank`,lc.`contactPSI`,lc.`psiClauseA`,
            lc.`psiClauseB`,lc.`insNotification1`,lc.`insNotification2`,lc.`insNotification3`, lc.`forAirShipment1`,
            lc.`forAirShipment2`,lc.`forSeaShipment1`,lc.`forSeaShipment2`,lc.`shippingRemarks`, cc.`id` `conf_id`, 
            cc.`chargetype` AS `conf_chargetype`, cc.`exchangerate` AS `conf_exchangerate`, cc.`amount` `conf_amount`,
            cc.`vat` `conf_vat`, cc.`othercharge` AS `conf_othercharge`, cc.`total` `conf_total`, cc.`currency` `conf_currency`,
        (SELECT a1.`filename` FROM `wc_t_attachments` a1 WHERE a1.`poid`='$id' AND a1.`title`='LC Opening Request' ORDER BY a1.`id` DESC LIMIT 1) AS `attachLCORequest`,
        (SELECT a2.`filename` FROM `wc_t_attachments` a2 WHERE a2.`poid`='$id' AND a2.`title`='Bank Received Copy' ORDER BY a2.`id` DESC LIMIT 1) AS `attachBankReceiveCopy`,
        (SELECT a3.`filename` FROM `wc_t_attachments` a3 WHERE a3.`poid`='$id' AND a3.`title`='LC Opening Other Files' ORDER BY a3.`id` DESC LIMIT 1) AS `attachLCOOther`,
        (SELECT a4.`filename` FROM `wc_t_attachments` a4 WHERE a4.`poid`='$id' AND a4.`title`='Confirmation Charge Advice' ORDER BY a4.`id` DESC LIMIT 1) AS `attachConfChargeAdv`,
        (SELECT a5.`filename` FROM `wc_t_attachments` a5 WHERE a5.`poid`='$id' AND a5.`title`='Final LC Copy' ORDER BY a5.`id` DESC LIMIT 1) AS `attachFinalLC`
        FROM `wc_t_lc` lc 
            LEFT JOIN `wc_t_bank_insurance` c1 ON lc.`lcissuerbank` = c1.`id` 
            LEFT JOIN `wc_t_bank_insurance` c2 ON lc.`bankaccount` = c2.`id` 
            LEFT JOIN `wc_t_bank_insurance` c3 ON lc.`insurance` = c3.`id` 
            LEFT JOIN `wc_t_confirmation_charge` cc ON lc.`lcno` = cc.`lcno` 
        WHERE lc.`pono` = '$id';";        
        //echo $sql;
    $objdal->read($sql);
	
    if(!empty($objdal->data)){
		$lcrequest[0] = $objdal->data[0];
	}
    
    $i=0;
    unset($objdal->data);
    
    $sql = "SELECT t.`id`, t.`pono`, c1.`name` `partname`, t.`percentage`, 
                t.`dayofmaturity`, c2.`name` `maturityterms` 
            FROM `wc_t_payment_terms` t 
                INNER JOIN `wc_t_category` c1 ON c1.`id` = t.`partname` 
                INNER JOIN `wc_t_category` c2 ON c2.`id` = t.`maturityterms` 
            WHERE t.`pono` = '$id'";
    $objdal->read($sql);
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
    		$pterms[$i] = $val;
            $i++;
        }
	}
    unset($objdal);
    return json_encode(array($lcrequest,$pterms));
}


// Insert
function SubmitConfCharge()
{
	global $user_id;
	global $loginRole;
    
    $pono = htmlspecialchars($_POST['pono1'],ENT_QUOTES, "ISO-8859-1");
    $confChargeId = htmlspecialchars($_POST['confChargeId'],ENT_QUOTES, "ISO-8859-1");    
    $lcno1 = htmlspecialchars($_POST['lcno1'],ENT_QUOTES, "ISO-8859-1");
    $chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");
    $confChargeAmount = htmlspecialchars($_POST['confChargeAmount'],ENT_QUOTES, "ISO-8859-1");
    $confChargeAmount = str_replace(",", "", $confChargeAmount);
    $currency = htmlspecialchars($_POST['currency'],ENT_QUOTES, "ISO-8859-1");
    $exchangeRate = htmlspecialchars($_POST['exchangeRate'],ENT_QUOTES, "ISO-8859-1");
    $exchangeRate = str_replace(",", "", $exchangeRate);
    $vatOnConfCharge = htmlspecialchars($_POST['vatOnConfCharge'],ENT_QUOTES, "ISO-8859-1");
    $vatOnConfCharge = str_replace(",", "", $vatOnConfCharge);
    $otherCharge = htmlspecialchars($_POST['otherCharge'],ENT_QUOTES, "ISO-8859-1");
    $otherCharge = str_replace(",", "", $otherCharge);
    $totalCharge = htmlspecialchars($_POST['totalCharge'],ENT_QUOTES, "ISO-8859-1");
    $totalCharge = str_replace(",", "", $totalCharge);
    
    $attachConfChargeAdvice = htmlspecialchars($_POST['attachConfChargeAdvice'],ENT_QUOTES, "ISO-8859-1");
    $attachConfChargeAdviceOld = htmlspecialchars($_POST['attachConfChargeAdviceOld'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $objdal = new dal();
    if($confChargeId > 0){
        $query = "UPDATE `wc_t_confirmation_charge` SET 
            `lcno` = '$lcno1',
            `chargetype` = $chargeType,
            `amount` = $confChargeAmount,
            `currency` = $currency,
            `exchangerate` = $exchangeRate,
            `vat` = $vatOnConfCharge,
            `othercharge` = $otherCharge,
            `total` = $totalCharge,
            `entryby` = $user_id,
            `entryfrom` = '$ip'
            WHERE `id` = '$confChargeId';";
        $objdal->update($query);
        //echo $query;
        if($attachConfChargeAdviceOld!=""){
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachConfChargeAdvice',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Confirmation Charge Advice' AND `filename` = '$attachConfChargeAdviceOld'";
           	$objdal->update($query);
            //Transfer file from 'temp' directory to respective 'docs' directory
            fileTransferTempToDocs($pono);
        }
    } else {
        $query = "INSERT INTO `wc_t_confirmation_charge` SET 
    		`lcno` = '$lcno1',
            `chargetype` = $chargeType,
            `amount` = $confChargeAmount,
            `currency` = $currency,
            `exchangerate` = $exchangeRate,
            `vat` = $vatOnConfCharge,
            `othercharge` = $otherCharge,
            `total` = $totalCharge,
            `entryby` = $user_id,
            `entryfrom` = '$ip';";
    	$objdal->insert($query);
        //echo $query;
        
        $query = "INSERT INTO `wc_t_attachments`
            (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `grouponly`) VALUES 
            ('$pono', 'Confirmation Charge Advice', '$attachConfChargeAdvice', $user_id, '$ip', $loginRole, '$lcno1', '$loginRole')";
       	$objdal->insert($query);
        //echo $query;
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($pono);
    }
	unset($objdal);
	
	$res["status"] = 1;
    $res["message"] = 'Confirmation charge Submitted.';
	return json_encode($res);
}

function SubmitLCOpening()
{
	global $user_id;
	global $loginRole;
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");    
    $lctype = htmlspecialchars($_POST['lctype'],ENT_QUOTES, "ISO-8859-1");
    
    $lcissuerbank = htmlspecialchars($_POST['lcissuerbank'],ENT_QUOTES, "ISO-8859-1");
    $lcissuerbankOld = htmlspecialchars($_POST['lcissuerbankOld'],ENT_QUOTES, "ISO-8859-1");
    $lcissuerbankNew = htmlspecialchars($_POST['lcissuerbankNew'],ENT_QUOTES, "ISO-8859-1");
    
    $insurance = htmlspecialchars($_POST['insurance'],ENT_QUOTES, "ISO-8859-1");
    $insuranceOld = htmlspecialchars($_POST['insuranceOld'],ENT_QUOTES, "ISO-8859-1");
    $insuranceNew = htmlspecialchars($_POST['insuranceNew'],ENT_QUOTES, "ISO-8859-1");
    
    $bankaccount = htmlspecialchars($_POST['bankaccount'],ENT_QUOTES, "ISO-8859-1");
    $bankservice = htmlspecialchars($_POST['bankservice'],ENT_QUOTES, "ISO-8859-1");
    
    $serviceremark = htmlspecialchars($_POST['serviceremark'],ENT_QUOTES, "ISO-8859-1");
    if($bankservice==""){ $bankservice = 'NULL'; }
    
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    
    if($_POST['lcissuedate']!=""){
        $lcissuedate = htmlspecialchars($_POST['lcissuedate'],ENT_QUOTES, "ISO-8859-1");
        $lcissuedate = date('Y-m-d', strtotime($lcissuedate));
        $lcissuedate = "`lcissuedate` = '".$lcissuedate."',";
    }else{
        $lcissuedate = '';
    }
    
    $producttype = htmlspecialchars($_POST['producttype'],ENT_QUOTES, "ISO-8859-1");
    
    if($_POST['lcissuedate']!=""){
        $daysofexpiry = htmlspecialchars($_POST['daysofexpiry'],ENT_QUOTES, "ISO-8859-1");
        $daysofexpiry = date('Y-m-d', strtotime($daysofexpiry));
        $daysofexpiry = "`daysofexpiry` = '".$daysofexpiry."',";
    }else{
        $daysofexpiry = '';
    }
    
    
    $lcvalue = htmlspecialchars($_POST['lcvalue'],ENT_QUOTES, "ISO-8859-1");
    $lcvalue = str_replace(",", "", $lcvalue);
    
    $xr1 = htmlspecialchars($_POST['xr1'],ENT_QUOTES, "ISO-8859-1");
    $xr1 = str_replace(",", "", $xr1);
    
    $xr2 = htmlspecialchars($_POST['xr2'],ENT_QUOTES, "ISO-8859-1");
    $xr2 = str_replace(",", "", $xr2);
    
    $lcafno = htmlspecialchars($_POST['lcafno'],ENT_QUOTES, "ISO-8859-1");
    
    $attachLCOpenRequest = htmlspecialchars($_POST['attachLCOpenRequest'],ENT_QUOTES, "ISO-8859-1");
    $attachLCOpenRequestOld = htmlspecialchars($_POST['attachLCOpenRequestOld'],ENT_QUOTES, "ISO-8859-1");
    
    $attachBankReceiveCopy = htmlspecialchars($_POST['attachBankReceiveCopy'],ENT_QUOTES, "ISO-8859-1");
    $attachBankReceiveCopyOld = htmlspecialchars($_POST['attachBankReceiveCopyOld'],ENT_QUOTES, "ISO-8859-1");
    
    $attachLCOther = htmlspecialchars($_POST['attachLCOther'],ENT_QUOTES, "ISO-8859-1");
    $attachLCOtherOld = htmlspecialchars($_POST['attachLCOtherOld'],ENT_QUOTES, "ISO-8859-1");
    
    //$attachFinalLCCopy = htmlspecialchars($_POST['attachFinalLCCopy'],ENT_QUOTES, "ISO-8859-1");
    //$attachFinalLCCopyOld = htmlspecialchars($_POST['attachFinalLCCopyOld'],ENT_QUOTES, "ISO-8859-1");
    
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $objdal=new dal();
    
    $query = "UPDATE `wc_t_lc` SET 
        `lctype` = $lctype,
        `lcissuerbank` = $lcissuerbank,
        `insurance` = $insurance,
        `bankaccount` = $bankaccount,
        `bankservice` = $bankservice,
        `serviceremark` = '$serviceremark',
        `lcno` = '$lcno', $lcissuedate
        `producttype` = $producttype, $daysofexpiry
        `lcvalue` = $lcvalue,
        `xeUSD` = $xr1,
        `xeBDT` = $xr2,
        `lcafno` = '$lcafno'
        WHERE `pono` = '$pono';";
    //echo $query;
    $objdal->update($query);
    
    if($lcissuerbankOld!=$lcissuerbankNew || $insuranceOld!=$insuranceNew){
        // send the notification
        $changemsg = 'PO # '.$pono."\n";
        if($lcissuerbankOld!=$lcissuerbankNew){
            $changemsg .= "Previous bank: ".$lcissuerbankOld."\n";
            $changemsg .= "New bank: ".$lcissuerbankNew."\n";
        }
        if($insuranceOld!=$insuranceNew){
            $changemsg .= "Previous insurance: ".$insuranceOld."\n";
            $changemsg .= "New insurance: ".$insuranceNew."\n";
        }
        //mailToUser('lca04', 'Bank/Insurance changed by Operation', $changemsg);
    }
    
    if($attachLCOpenRequest!=''){
        if($attachLCOpenRequestOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `grouponly`) VALUES 
                ('$pono', 'LC Opening Request', '$attachLCOpenRequest', $user_id, '$ip', $loginRole, '$lcno', '$loginRole');";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachLCOpenRequest',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='LC Opening Request' AND `filename` = '$attachLCOpenRequestOld'";
           	$objdal->update($query);
        }
    }
    
    if($attachBankReceiveCopy!=''){
        if($attachBankReceiveCopyOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `grouponly`) VALUES 
                ('$pono', 'Bank Received Copy', '$attachBankReceiveCopy', $user_id, '$ip', $loginRole, '$lcno', '$loginRole');";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachBankReceiveCopy',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Bank Received Copy' AND `filename` = '$attachBankReceiveCopyOld'";
           	$objdal->update($query);
        }
    }
    
    if($attachLCOther!=''){
        if($attachLCOtherOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `grouponly`) VALUES 
                ('$pono', 'LC Opening Other Files', '$attachLCOther', $user_id, '$ip', $loginRole, '$lcno', '$loginRole');";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachLCOther',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='LC Opening Other Files' AND `filename` = '$attachLCOtherOld'";
           	$objdal->update($query);
        }
        //echo $query;
    }
    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);
    //Add info to activity log table
    addActivityLog(requestUri, 'Saved LC opening information', $user_id, 1);
    
	unset($objdal);
	
	$res["status"] = 1;
    $res["message"] = 'LC opening information Saved SUCCESSFULLY';
    
	return json_encode($res);
}

function SendFinalLCCopy()
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
    
    $pono = htmlspecialchars($_POST['pono2'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno2'],ENT_QUOTES, "ISO-8859-1");
    
    $attachFinalLCCopy = htmlspecialchars($_POST['attachFinalLCCopy'],ENT_QUOTES, "ISO-8859-1");
    $attachFinalLCCopyOld = htmlspecialchars($_POST['attachFinalLCCopyOld'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $objdal = new dal();
    
    if($attachFinalLCCopy!='') {
        if ($attachFinalLCCopyOld == '') {
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
                ('$pono', 'Final LC Copy', '$attachFinalLCCopy', $user_id, '$ip', $loginRole, '$lcno');";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachFinalLCCopy',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `lcno` = '$lcno' AND `title`='Final LC Copy' AND `filename` = '$attachFinalLCCopyOld'";
            $objdal->update($query);
        }
    }
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Final_LC_Copy_Sent,
        'status' => 1,
        'msg' => "'Final LC copy sent from Finance against PO# ".$pono."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);
    
	unset($objdal);
	
	$res["status"] = 1;
    $res["message"] = 'LC Copy Sent SUCCESSFULLY';
	return json_encode($res);
}


function GetLCList()
{
    
    $objdal=new dal();
	$sql = "SELECT `lcno`, `pono` FROM `wc_t_lc` WHERE NOT `lcno` = '';";
	$objdal->read($sql); 
	
    // json
	$jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$lcno.'", "text": "'.$lcno.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function GetBankCo($bankId, $po)
{
	$objdal = new dal();
	$query = "select co2.`name`, co2.`address`, co1.`name` as `coname`, co1.`address` as `coaddress` 
                from `wc_t_pi` po 
                    inner join `wc_t_company` co1 on po.`supplier` = co1.`id`
                    inner join `wc_t_lc` lc on lc.`pono` = po.`poid`
                    inner join `wc_t_company` co2 on co2.`id` = lc.`lcissuerbank`
                where po.`poid` = '$po';";
	//echo $query;
    $objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

//COVER NOTE SUBMIT

function SubmitCN()
{
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId1"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }else{

    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");

    $insurance = htmlspecialchars($_POST['insurance'],ENT_QUOTES, "ISO-8859-1");

    $objdal=new dal();

    $query = "UPDATE `wc_t_lc` set `xeBDT` = ".$_POST["xr2"].", `xeUSD` = ".$_POST["xr1"]." WHERE `pono` = '$pono';";
    $objdal->update($query);

    $query = "INSERT INTO `cn_request` (`po_no`,`ic_id`) VALUES ('$pono',$insurance)";
    //echo $query;
    $objdal->insert($query);

// Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Request_for_CN_To_IC,
        'pendingtoco' => $insurance,
        'msg' => "'Cover Note Request sent against PO# ".$pono."'",
    );
    UpdateAction($action);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Cover Note request sent SUCCESSFULLY';

    return json_encode($res);
}

//COVER NOTE ACCEPTED BY TFO

function AcceptCN()
{
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId2"]);
    $pono = htmlspecialchars($_POST['ponum1'],ENT_QUOTES, "ISO-8859-1");

    $cnNo = htmlspecialchars($_POST['cn_number'],ENT_QUOTES, "ISO-8859-1");

    $cnDate = htmlspecialchars($_POST['cn_date'],ENT_QUOTES, "ISO-8859-1");
    $cnDate = date('Y-m-d', strtotime($cnDate));
    $poc = htmlspecialchars($_POST['pay_order_charge'],ENT_QUOTES, "ISO-8859-1");
    $poc = str_replace(",","", $poc);

    $objdal=new dal();

    $sql = "UPDATE `cn_request` SET `status` = 1 WHERE `po_no` = '$pono';";
    //echo $query;
    $objdal->update($sql);
    unset($objdal->data);

    $selectID = "SELECT count(*) `Exist` from `wc_t_insurance_charge` where `ponum` = '$pono';";
    $exist = $objdal->getScalar($selectID);

//    $res = '';
//    if (!empty($objdal->data)) {
//        $res = $objdal->data[0];
//        extract($res);
//    }
    unset($objdal->data);
    //$pid = $res["id"];

    if ($exist > 0){
        $query = "UPDATE `wc_t_insurance_charge` SET
                `ponum` = '$pono',
                `coverNoteNo` = '$cnNo',
                `coverNoteDate` = '$cnDate',
                `stampDuty` = $poc
                WHERE `ponum` = '$pono';";
//        var_dump($query) ;
        $objdal->update($query);
    }
    else{
        $insert = "INSERT INTO `wc_t_insurance_charge` (`ponum`,`coverNoteNo`,`coverNoteDate`,`stampDuty`) VALUES ('$pono','$cnNo','$cnDate',$poc);";
        $objdal->insert($insert);

    }

// Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'status' => 1,
        'actionid' => action_CN_Accepted_by_TFO,
        'newstatus' => 1,
        'msg' => "'Acknowledgement'",
    );
    UpdateAction($action);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Cover Note Accepted SUCCESSFULLY';

    return json_encode($res);
}

//COVER NOTE REJECT BY TFO
function RejectCN(){
    global $user_id;
    global $loginRole;
    $objdal=new dal();
    $refId = decryptId($_POST["refId2"]);
    $pono = $objdal->sanitizeInput($_POST['ponum1']);
    $remarks = $objdal->sanitizeInput($_POST['remarks']);

    $query = "UPDATE `cn_request` SET `status` = -1 WHERE `po_no` = '$pono';";
    //echo $query;
    $objdal->update($query);

// Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'status' => -1,
        'actionid' => action_CN_Rejected_by_TFO,
        'msg' => "'Cover Note Rejected against PO# ".$pono."'",
        'usermsg' => "'".$remarks."'",
    );
    UpdateAction($action);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Cover Note Rejectd';

    return json_encode($res);
}

//LC SENT TO BANK

function submitLCRequestToBank()
{
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId1"]);
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcRequestType = htmlspecialchars($_POST['lcRequestType'],ENT_QUOTES, "ISO-8859-1");
    $attachLCOpenRequest = htmlspecialchars($_POST['attachLCOpenRequest'],ENT_QUOTES, "ISO-8859-1");
    $attachLCOpenRequestOld = htmlspecialchars($_POST['attachLCOpenRequestOld'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $lcissuerbank = htmlspecialchars($_POST['lcissuerbank'],ENT_QUOTES, "ISO-8859-1");
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    $objdal=new dal();

// Action Log --------------------------------//

    if ($lcRequestType == 0){
        // Draft LC request
        $action = array(
            'refid' => $refId,
            'pono' => "'".$pono."'",
            'actionid' => action_Draft_LC_Request_sent_to_Bank,
            'pendingtoco' => $lcissuerbank,
            'msg' => "'Draft LC request sent to Bank against PO# ".$pono."'",
        );
    }
    elseif ($lcRequestType==1){
        // Final LC request
        $action = array(
            'refid' => $refId,
            'pono' => "'".$pono."'",
            'status' => 1,
            'actionid' => action_Final_LC_Request_sent_to_Bank,
            'pendingtoco' => $lcissuerbank,
            'msg' => "'Final LC request sent to Bank against PO# ".$pono."'",
        );
    }
    UpdateAction($action);

    if($attachLCOpenRequest!=''){
        if($attachLCOpenRequestOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `grouponly`) VALUES 
                ('$pono', 'LC Opening Request', '$attachLCOpenRequest', $user_id, '$ip', $loginRole, '$lcno', '$loginRole');";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachLCOpenRequest',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='LC Opening Request' AND `filename` = '$attachLCOpenRequestOld'";
            $objdal->update($query);
        }
    }
    fileTransferTempToDocs($pono);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'LC request sent to Bank SUCCESSFULLY';

    return json_encode($res);
}

//BCS EX RATE TO FSO

function SubmitBCSEx()
{
    global $user_id;
    global $loginRole;

    $ids = $_POST['chkLine'];
    $valueDates = $_POST['valueDate'];
    //$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();
    $i = 0;

    foreach ($ids as $value) {

        $id = $value;

        $valueDate = htmlspecialchars($valueDates[$i], ENT_QUOTES, "ISO-8859-1");
        $valueDate = date('Y-m-d', strtotime($valueDate));

        $query = "SELECT l.`lcno`,l.`lcvalue`,'$valueDate' `valueDate`,l.`lcissuerbank`,ct.`id` as `currency`, p.`poid`
              from `wc_t_lc` l
              LEFT JOIN `wc_t_pi` p ON l.`pono` = p.`poid`
              LEFT JOIN `wc_t_category` ct ON p.`currency` = ct.`id`
              where l.`pono` = (SELECT pono FROM fx_settelment_pending_fn where id = $id);";

        $objdal->read($query);

        $row = '';
        if (!empty($objdal->data)) {
            $row = $objdal->data[0];
            extract($row);
        }
        unset($objdal->data);

        $req_type = requisition_type;
        $lcno = $row['lcno'];
        $bankid = $row['lcissuerbank'];
        $lcval = $row['lcvalue'];
        $lcdate = $row['valueDate'];
        $lcdate = date('Y-m-d', strtotime($lcdate));
        $cur = $row['currency'];
        $pono = $row['poid'];

        $sql = "INSERT INTO `fx_request_primary` (`requisition_type`,`currency`,`value`,`value_date`,`lcno`,`bankID`) 
        VALUES ('$req_type','$cur','$lcval','$lcdate','$lcno','$bankid');";

        $objdal->insert($sql);

        // Action Log --------------------------------//
        $action = array(
            'pono' => "'" . $pono . "'",
            'actionid' => action_fx_request_for_lc,
            'msg' => "'BCS Ex rate settlement request sent for LC #" . $lcno . "'",
        );
        UpdateAction($action);

        // update fs settlement pending table
        $sql = "UPDATE `fx_settelment_pending_fn` SET `status` = 1 WHERE `id` = $id;";
        $objdal->update($sql);

        $i++;
    }
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'FX Rate Settlement Requests sent SUCCESSFULLY';

    return json_encode($res);
}
?>

