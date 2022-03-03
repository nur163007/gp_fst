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
			echo GetNewAmendNo($_GET["po"], $_GET['lc']);
			break;
		case 2:	
			echo GetAmendInfo($_GET["po"], $_GET['lc']);
			break;
		case 3:
			echo GetAmendAttach($_GET["id"],$_GET['actionId']);
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["poNum"]) || isset($_POST["poNum"])){
        if ($_POST["userAction"]==1){
            echo SendLcAmendmentRequest();
        } elseif ($_POST["userAction"]==2){
            echo AcceptRequest();
        } elseif ($_POST["userAction"]==3){
            echo RejectRequest();
        } elseif ($_POST["userAction"]==4){
            echo SendAmendmentCopy();
        } elseif ($_POST["userAction"]==5){
            echo SaveAmendmentCharge();
        }elseif ($_POST["userAction"]==6){
            echo SendAmendmentRequestToBank();
        }elseif ($_POST["userAction"]==7){
            echo SendAmendmentBankToTFO();
        }
	}
}

// Insert or update
function SendLcAmendmentRequest()
{
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $poNum = htmlspecialchars($_POST['poNum'], ENT_QUOTES, "ISO-8859-1");
    $lcNum = htmlspecialchars($_POST['lcNum'], ENT_QUOTES, "ISO-8859-1");
    //$commission = htmlspecialchars($_POST['commission'],ENT_QUOTES, "ISO-8859-1");
    //$commission = str_replace(",", "", $commission);    
    $amendNo = htmlspecialchars($_POST['amendNo'], ENT_QUOTES, "ISO-8859-1");
    $amendReason = htmlspecialchars($_POST['amendReason'], ENT_QUOTES, "ISO-8859-1");
    //$chargeBorneBy = htmlspecialchars($_POST['chargeBorneBy'],ENT_QUOTES, "ISO-8859-1");
    $attachAmendmentDocs = htmlspecialchars($_POST['attachAmendmentDocs'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $poNum = stripslashes($poNum);
    $lcNum = stripslashes($lcNum);
    //$commission  = stripslashes($commission );
    $amendNo = stripslashes($amendNo);
    $amendReason = stripslashes($amendReason);
    //$chargeBorneBy  = stripslashes($chargeBorneBy );

    $objdal = new dal();

    $poNum = $objdal->real_escape_string($poNum);
    $lcNum = $objdal->real_escape_string($lcNum);
    //$commission  = $objdal->real_escape_string($commission );
    $amendNo = $objdal->real_escape_string($amendNo);
    $amendReason = $objdal->real_escape_string($amendReason);
    //$chargeBorneBy  = $objdal->real_escape_string($chargeBorneBy );

    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------

    $query = "INSERT INTO `wc_t_amendment` SET 
        `poNo` = '$poNum', 
        `lcNo` = '$lcNum', 
        `amendNo` = $amendNo, 
        `amendReason` = '" . str_replace("'", "", $amendReason) . "',
        `submitBy` = $user_id,
        `submitFrom` = '$ip';";
    $objdal->insert($query, "Failed to save Amendment data");

    $lastId = $objdal->LastInsertId();

    $query = '';

    for ($i = 0; $i < count($_POST['clauseTitle']); $i++) {

        $query = "INSERT INTO `wc_t_amendment_clause` SET 
            `amendId` = " . $lastId . ",
            `clauseNumber` = '" . str_replace("'", "", $_POST['clauseNumber'][$i]) . "',
            `clauseTitle` = '" . str_replace("'", "", $_POST['clauseTitle'][$i]) . "',
            `existingClause` = '" . str_replace("'", "", $_POST['existingClause'][$i]) . "',
            `newClause` = '" . str_replace("'", "", $_POST['newClause'][$i]) . "';";

        $objdal->insert($query, "Failed to save Amendment Clause data");
    }

    if ($attachAmendmentDocs != "") {
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poNum', 'Amendment Docs', '$attachAmendmentDocs', $user_id, '$ip', $loginRole)";

        $objdal->insert($query, "Failed to save attachments");
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($poNum);
    }

    unset($objdal);

    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $poNum . "'",
        'actionid' => action_Requested_for_LC_Amendment,
        'status' => -1,
        'msg' => "'Supplier requested for LC amendment against PO# " . $poNum . " LC# " . $lcNum . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}

// Insert or update
function SaveAmendmentCharge()
{
	global $user_id;
	global $loginRole;
    
    $poNum = htmlspecialchars($_POST['poNum'],ENT_QUOTES, "ISO-8859-1");
	$lcNum = htmlspecialchars($_POST['lcNum'],ENT_QUOTES, "ISO-8859-1");
	//$commission = htmlspecialchars($_POST['commission'],ENT_QUOTES, "ISO-8859-1");
    //$commission = str_replace(",", "", $commission);    
	$amendNo = htmlspecialchars($_POST['amendNo'],ENT_QUOTES, "ISO-8859-1");
	$amendReason = htmlspecialchars($_POST['amendReason'],ENT_QUOTES, "ISO-8859-1");  
	$chargeBorneBy = htmlspecialchars($_POST['chargeBorneBy'],ENT_QUOTES, "ISO-8859-1");
    
	$charge = htmlspecialchars($_POST['charge'],ENT_QUOTES, "ISO-8859-1");  
    $charge = str_replace(",", "", $charge);    
	$chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");  
	$otherCharge = htmlspecialchars($_POST['otherCharge'],ENT_QUOTES, "ISO-8859-1");  
    $otherCharge = str_replace(",", "", $otherCharge);    
	$vatRate = htmlspecialchars($_POST['vatRate'],ENT_QUOTES, "ISO-8859-1");  
    $vatRate = str_replace(",", "", $vatRate);    
	$vatOnCharge = htmlspecialchars($_POST['vatOnCharge'],ENT_QUOTES, "ISO-8859-1");  
    $vatOnCharge = str_replace(",", "", $vatOnCharge);    
	$vatRebateRate = htmlspecialchars($_POST['vatRebateRate'],ENT_QUOTES, "ISO-8859-1");      
	$vatRebate = htmlspecialchars($_POST['vatRebate'],ENT_QUOTES, "ISO-8859-1");   
    $vatRebate = str_replace(",", "", $vatRebate);
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
	$poNum  = stripslashes($poNum );
    $lcNum  = stripslashes($lcNum );
    //$commission  = stripslashes($commission );
    $amendNo  = stripslashes($amendNo);
    $amendReason  = stripslashes($amendReason );
    $chargeBorneBy  = stripslashes($chargeBorneBy );
    $charge  = stripslashes($charge );
    $chargeType  = stripslashes($chargeType );
    $otherCharge  = stripslashes($otherCharge );
    $vatRate  = stripslashes($vatRate );
    $vatOnCharge  = stripslashes($vatOnCharge );
    $vatRebateRate  = stripslashes($vatRebateRate );
    $vatRebate  = stripslashes($vatRebate );
    
	$objdal = new dal();
	
	$poNum  = $objdal->real_escape_string($poNum );
    $lcNum  = $objdal->real_escape_string($lcNum );
    //$commission  = $objdal->real_escape_string($commission );
    $amendNo  = $objdal->real_escape_string($amendNo );
    $amendReason  = $objdal->real_escape_string($amendReason );
    $chargeBorneBy  = $objdal->real_escape_string($chargeBorneBy );
    $charge  = $objdal->real_escape_string($charge );
    $chargeType  = $objdal->real_escape_string($chargeType );
    $otherCharge  = $objdal->real_escape_string($otherCharge );
    $vatRate  = $objdal->real_escape_string($vatRate );
    $vatOnCharge  = $objdal->real_escape_string($vatOnCharge );
    $vatRebateRate  = $objdal->real_escape_string($vatRebateRate );
    $vatRebate  = $objdal->real_escape_string($vatRebate );

	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    $query = "UPDATE `wc_t_amendment` SET 
        `amendReason` = '$amendReason',  
        `chargeBorneBy` = $chargeBorneBy, 
        `chargeType` = $chargeType, 
        `charge` = $charge, 
        `otherCharge` = $otherCharge, 
        `vatRate` = $vatRate, 
        `vatOnCharge` = $vatOnCharge,  
        `vatRebateRate` = $vatRebateRate, 
        `vatRebate` = $vatRebate,
        `submitBy` = $user_id,
        `submitFrom` = '$ip'
        WHERE `poNo` = '$poNum' AND `lcNo` = '$lcNum' AND `amendNo` = $amendNo;";
    $objdal->update($query, "Failed to update Amendment data");

	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

//AMENDMENT SENT TO BANK
function SendAmendmentRequestToBank()
{

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $amendNo = htmlspecialchars($_POST['amendNo'], ENT_QUOTES, "ISO-8859-1");
    $poNum = htmlspecialchars($_POST['poNum'], ENT_QUOTES, "ISO-8859-1");
    $lcNum = htmlspecialchars($_POST['lcNum'], ENT_QUOTES, "ISO-8859-1");
    $lcissuerbank = htmlspecialchars($_POST['lcissuerbank'], ENT_QUOTES, "ISO-8859-1");
    $attachAmendmentLetter = htmlspecialchars($_POST['attachAmendmentLetter'],ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $poNum = stripslashes($poNum);
    $lcNum = stripslashes($lcNum);

    $lcissuerbank = stripslashes($lcissuerbank);

    $objdal = new dal();

    $poNum = $objdal->real_escape_string($poNum);
    $lcNum = $objdal->real_escape_string($lcNum);

    $lcissuerbank = $objdal->real_escape_string($lcissuerbank);

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------
    if($attachAmendmentLetter!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poNum', 'Amendment Instruction Letter', '$attachAmendmentLetter', $user_id, '$ip', $loginRole)";

        $objdal->insert($query, "Failed to save Attachments");
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($poNum);
    }

    unset($objdal);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $poNum . "'",
        'actionid' => action_Amendment_Request_By_TFO,
        'pendingtoco' => $lcissuerbank,
        'status' => 1,
        'msg' => "'Bank Proceed LC amendment against PO# " . $poNum . " LC# " . $lcNum . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}
//AMENDMENT SENT TO BANK
function SendAmendmentBankToTFO()
{

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $amendNo = htmlspecialchars($_POST['amendNo'], ENT_QUOTES, "ISO-8859-1");
    $poNum = htmlspecialchars($_POST['poNum'], ENT_QUOTES, "ISO-8859-1");
    $lcNum = htmlspecialchars($_POST['lcNum'], ENT_QUOTES, "ISO-8859-1");
    $bankAmendCharge = htmlspecialchars($_POST['bankAmendCharge'], ENT_QUOTES, "ISO-8859-1");
    $bankAmendCharge = str_replace(",", "", $bankAmendCharge);
    $lcissuerbank = htmlspecialchars($_POST['lcissuerbank'], ENT_QUOTES, "ISO-8859-1");
    $attachAmendmentCopy = htmlspecialchars($_POST['attachAmendmentCopy'],ENT_QUOTES, "ISO-8859-1");
    $attachAdvice = htmlspecialchars($_POST['attachAdviceNote'],ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $poNum = stripslashes($poNum);
    $lcNum = stripslashes($lcNum);
    $bankAmendCharge = stripslashes($bankAmendCharge);
    $lcissuerbank = stripslashes($lcissuerbank);

    $objdal = new dal();

    $poNum = $objdal->real_escape_string($poNum);
    $lcNum = $objdal->real_escape_string($lcNum);
    $bankAmendCharge = $objdal->real_escape_string($bankAmendCharge);
    $lcissuerbank = $objdal->real_escape_string($lcissuerbank);

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------
    $query = "UPDATE `wc_t_amendment` SET 
        `charge` = $bankAmendCharge, 
        `submitBy` = $user_id,
        `submitFrom` = '$ip'
        WHERE `poNo` = '$poNum' AND `lcNo` = '$lcNum' AND `amendNo` = $amendNo;";
    $objdal->update($query, "Failed to update Amendment data");


    if($attachAmendmentCopy!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poNum', 'Amendment LC Copy', '$attachAmendmentCopy', $user_id, '$ip', $loginRole)";

        if($attachAdvice!=""){
            $query .= ",('$poNum', 'Amendment Advice Note', '$attachAdvice', $user_id, '$ip', $loginRole);";
        }

        $objdal->insert($query, "Failed to save Attachments");
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($poNum);
    }

    unset($objdal);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $poNum . "'",
        'actionid' => action_Amendment_Process_Done_By_Bank,
        'status' => 1,
        'msg' => "'Bank Proceed LC amendment against PO# " . $poNum . " LC# " . $lcNum . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}
function AcceptRequest()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
	
	$poNum = htmlspecialchars($_POST['poNum'],ENT_QUOTES, "ISO-8859-1");
	$lcNum = htmlspecialchars($_POST['lcNum'],ENT_QUOTES, "ISO-8859-1");
	$amndId = htmlspecialchars($_POST['amndId'],ENT_QUOTES, "ISO-8859-1");

	$message = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");  
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	$objdal = new dal();
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    
    $sql = "UPDATE wc_t_amendment SET `status` = 1 WHERE `id`=$amndId;";
    $objdal->update($sql, "Failed to update Amendment status");
    unset($objdal);
    
	if($loginRole==role_Buyer){
	   $actionId = action_Accepted_Amendment_Request;
       $msg = "'Amendment request accepted and sent for approval against PO# ".$poNum." LC# ".$lcNum."'";
	} elseif($loginRole==role_LC_Approvar_1){
	   $actionId = action_Amendment_Request_Approved_by_1st_Level;
       $msg = "'Amendment request approved and sent for next level approval against PO# ".$poNum." LC# ".$lcNum."'";
	} elseif($loginRole==role_LC_Approvar_4){
	   $actionId = action_Amendment_Request_Approved_by_4th_Level;
       $msg = "'Amendment request approved and sent for next level approval against PO# ".$poNum." LC# ".$lcNum."'";
	} /*elseif($loginRole==role_LC_Approvar_5){
	   $actionId = action_Amendment_Request_Approved_by_5th_Level;
       $msg = "'Amendment request approved and sent for next level approval against PO# ".$poNum." LC# ".$lcNum."'";
	}*/
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poNum."'",
        'actionid' => $actionId,
        'status' => 1,
        'msg' => $msg,
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

function RejectRequest()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
	
	$poNum = htmlspecialchars($_POST['poNum'],ENT_QUOTES, "ISO-8859-1");
	$lcNum = htmlspecialchars($_POST['lcNum'],ENT_QUOTES, "ISO-8859-1");
	$amndId = htmlspecialchars($_POST['amndId'],ENT_QUOTES, "ISO-8859-1");

	$message = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");  
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	$objdal = new dal();
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    $sql = "UPDATE wc_t_amendment SET `status` = 0 WHERE `id`=$amndId;";
    $objdal->update($sql, "Failed to update Amendment status");
    unset($objdal);
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poNum."'",
        'actionid' => action_Rejected_Amendment_Request,
        'status' => 1,
        'msg' => "'Amendment request rejected and sent for further acceptance against PO# ".$poNum." LC# ".$lcNum."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

function SendAmendmentCopy()
{

	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }

	$poNum = htmlspecialchars($_POST['poNum'],ENT_QUOTES, "ISO-8859-1");
	$lcNum = htmlspecialchars($_POST['lcNum'],ENT_QUOTES, "ISO-8859-1");

	$message = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");  
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

//    $attachAmendmentCopy = htmlspecialchars($_POST['attachAmendmentCopy'],ENT_QUOTES, "ISO-8859-1");
//    $attachAdvice = htmlspecialchars($_POST['attachAdviceNote'],ENT_QUOTES, "ISO-8859-1");
//
	$objdal = new dal();
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    // insert attachment
//    if($attachAmendmentCopy!=""){
//        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES
//            ('$poNum', 'Amendment LC Copy', '$attachAmendmentCopy', $user_id, '$ip', $loginRole)";
//
//        if($attachAdvice!=""){
//            $query .= ",('$poNum', 'Amendment Advice Note', '$attachAdvice', $user_id, '$ip', $loginRole);";
//        }
//
//    	$objdal->insert($query, "Failed to save Attachments");
//        //Transfer file from 'temp' directory to respective 'docs' directory
//        fileTransferTempToDocs($poNum);
//	}

    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poNum."'",
        'actionid' => action_Amendment_Copy_Sent,
        'status' => 1,
        'msg' => "'Amendment copy send against PO# ".$poNum." LC# ".$lcNum."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}
 

function GetNewAmendNo($po, $lc)
{
	$objdal = new dal();
	$query = "SELECT IFNULL(MAX(`amendNo`),0)+1 `amendNo`  FROM `wc_t_amendment` 
        WHERE `poNo` = '$po' AND `lcNo` = '$lc' AND `status` = 1;";
	$objdal->read($query);
	$newAmendNo = 0;
    if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $newAmendNo = $amendNo;
	}
	unset($objdal);
	return $newAmendNo;
}

function GetAmendInfo($po, $lc)
{
	
    $objdal = new dal();
	
    $query = "SELECT *  FROM `wc_t_amendment` 
        WHERE `poNo` = '$po' AND `lcNo` = '$lc'  ORDER BY `Id` DESC LIMIT 1";
	$objdal->read($query);
    //echo $query;
    $pono = "";
    
    if(!empty($objdal->data)){
		$amend[0] = $objdal->data[0];
        $aId = $amend[0]["id"];
	}
    
    $i=0;
    unset($objdal->data);
    
    $query = "SELECT *
        FROM `wc_t_amendment_clause` WHERE `amendId` = $aId;";
	$objdal->read($query);
    
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
    		$clause[$i] = $val;
            $i++;
        }
	}
    unset($objdal);
	//echo $query;
    return json_encode(array($amend, $clause));
    
}

function getAmendAttach($id,$actionId){
        global $user_id;
        global $loginRole;
        global $companyId;
        $allStatus = array();
        $objdal = new dal();

    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    unset($objdal->data);
        $sql = "SELECT p.`poid`, p.`povalue`, p.`lcdesc`, c2.`name` `supname`,c1.`name` `curname`,p.`pinum`, p.`shipmode`,
            (SELECT `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id' ORDER BY `ID` DESC Limit 1) `status`,
            i.`name` AS `insurancebank`
            FROM `wc_t_pi` p 
                INNER JOIN `wc_t_category` c1 ON p.`currency` = c1.`id` 
                INNER JOIN `wc_t_company` c2 ON p.`supplier` = c2.`id`
                LEFT JOIN `wc_t_lc` l ON p.`poid` = l.`pono`
                LEFT JOIN `wc_t_company` i ON l.`insurance` = i.`id`
                
            WHERE p.`poid` = '$id';";
        //echo $sql;
        $objdal->read($sql);

        if(!empty($objdal->data)){
            $podetail[0] = $objdal->data[0];
            //extract($podetail);
            //$status = $podetail[0]['status'];
        }
        //echo $status;
        // message log
        $i=0;
        unset($objdal->data);

        // attachments
        if ($loginRole == role_bank_lc) {
            $skipDraft = "  AND a2.`title` IN ('Amendment Instruction Letter','Amendment Docs') ";
        }  elseif ($loginRole == role_LC_Operation && $actionId > action_Amendment_Request_By_TFO) {
            $skipDraft = "  AND a2.`title` IN ('Amendment LC Copy','Amendment Advice Note') ";
        }
        elseif ($loginRole == role_Buyer || $loginRole == role_LC_Approvar_1 || $loginRole == role_LC_Approvar_4 || $loginRole == role_LC_Operation) {
            $skipDraft = "AND a2.`title` IN ('Amendment Docs','Final LC Copy')";
        }
        else{
            $skipDraft = "AND a2.`title` IN ('Final LC Copy')";
        }

        $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` AS `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title`  AND a2.`poid` = '$id' $skipDraft 
             ORDER BY a2.`attachedon` DESC
             LIMIT 1)
        ORDER BY a.`attachedby`, a.`id`;";

        //echo $sql;
        $objdal->read($sql);

        if(!empty($objdal->data[0])) {
            foreach ($objdal->data as $val) {
                //extract($val);
                array_push($val, encryptId($val['id']));
                $attach[$i] = $val;
                $i++;
                //extract($res[1]);
            }
        }

        unset($objdal);


     return json_encode(array($podetail,$attach));


}

?>

