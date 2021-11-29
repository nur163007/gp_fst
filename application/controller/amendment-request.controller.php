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
    
    $attachAmendmentCopy = htmlspecialchars($_POST['attachAmendmentCopy'],ENT_QUOTES, "ISO-8859-1");  
    $attachAdvice = htmlspecialchars($_POST['attachAdviceNote'],ENT_QUOTES, "ISO-8859-1");
    
	$objdal = new dal();
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    // insert attachment
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
?>

