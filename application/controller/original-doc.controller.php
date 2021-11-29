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
			echo GetDiscrepancy($_GET['po'], $_GET['shipno']);
			break;
        
        case 2:	
			echo GetEAAcceptanceMessage($_GET['po'], $_GET['shipno']);
			break;
        
        case 3:	
			echo GetLetterInfo($_GET["bank"], $_GET["po"]);
			break;
            
		default:
			break;
	}
}

if (!empty($_POST)){
	if (!empty($_POST["LcNo1"]) || isset($_POST["LcNo1"])){
	   echo SubmitODocAcceptenceRequest();        
	}
    if (!empty($_POST["EATeamAction"]) || isset($_POST["EATeamAction"])){
        echo SubmitEAFeedback();
    }
    if (!empty($_POST["attachOriginalDoc"]) || isset($_POST["attachOriginalDoc"])){
        echo OriginalDocDeliver();
    }
}

function OriginalDocDeliver()
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
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $attachOriginalDoc = htmlspecialchars($_POST['attachOriginalDoc'],ENT_QUOTES, "ISO-8859-1");
    $attachOriginalDocOld = htmlspecialchars($_POST['attachOriginalDocOld'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
	
    $objdal = new dal();
    
    // Update document info in shipment table
    $query = "UPDATE `wc_t_shipment` SET `docType` = ".doctype_original.", 
		      `docDeliveredByFin` = current_timestamp()
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
	$objdal->update(trim($query));
    
    if($attachOriginalDoc!=''){
        if($attachOriginalDocOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`) VALUES 
                ('$pono', 'Original Bank Document', '$attachOriginalDoc', $user_id, '$ip', $loginRole, '$lcno', $shipno);";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachOriginalDoc',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Original Bank Document' AND `filename` = '$attachLCOpenRequestOld'";
           	$objdal->update($query);
        }
		//Transfer file from 'temp' directory to respective 'docs' directory
		fileTransferTempToDocs($pono);
    }
    
	unset($objdal);
	
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Original_Document_Delivered,
        'status' => 1,
        'msg' => "'Original document delivered against PO# " . $pono . " and Ship# " . $shipno . "'",
        'usermsg' => "'Please be notified that the original document has been delivered by Finance'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
    $res["message"] = 'Original document delivered.';
	return json_encode($res);
}

function SubmitEAFeedback()
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
	
	//$LcNo = htmlspecialchars($_POST['LcNo'],ENT_QUOTES, "ISO-8859-1");
	$PoNo = htmlspecialchars($_POST['PoNo'],ENT_QUOTES, "ISO-8859-1");
	$message = htmlspecialchars($_POST['rejectMessage'],ENT_QUOTES, "ISO-8859-1");
    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
	//------------------------------------------------------------------------------
    if($_POST["EATeamAction"]==1){
        // Action Log --------------------------------//    
//        $action = array(
//            'refid' => $refId,
//            'pono' => "'".$PoNo."'",
//            'actionid' => action_Original_Document_Accepted_For_Document_Delivery,
//            'status' => 1,
//            'msg' => "'".$message."'",
//        );
//        UpdateAction($action);
        
        $action = array(
            'refid' => $refId,
            'pono' => "'".$PoNo."'",
            'actionid' => action_Original_Document_Accepted_For_Document_Delivery,
            'status' => 1,
            'shipno' => $shipNo,
			'msg' => "'Original document accepted against PO# " . $PoNo . " and Ship# " . $shipNo . "'",
            'usermsg' => "'".$message."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    } elseif($_POST["EATeamAction"]==-1){
        // Action Log --------------------------------//    
        $action = array(
            'refid' => $refId,
            'pono' => "'".$PoNo."'",
            'actionid' => action_Original_Document_Rejected,
            'status' => -1,
            'shipno' => $shipNo,
			'msg' => "'Original document rejected against PO# " . $PoNo . " and Ship# " . $shipNo . "'",
			'usermsg' => "'".$message."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
	
	$res["status"] = 1;
	$res["message"] = 'Request submitted successfully!';
	return json_encode($res);
    
}

function SubmitODocAcceptenceRequest()
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
	
	$LcNo = htmlspecialchars($_POST['LcNo1'],ENT_QUOTES, "ISO-8859-1");
	$PoNo = htmlspecialchars($_POST['PoNo'],ENT_QUOTES, "ISO-8859-1");
	$discStatus = htmlspecialchars($_POST['discStatus'],ENT_QUOTES, "ISO-8859-1");    
	$discrepancyList = htmlspecialchars($_POST['discrepancyList'],ENT_QUOTES, "ISO-8859-1");
    $bankNotifyDate = htmlspecialchars($_POST['bankNotifyDate'],ENT_QUOTES, "ISO-8859-1");  
    $bankNotifyDate = date('Y-m-d', strtotime($bankNotifyDate));
    
    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
	
	$objdal = new dal();
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
    $query = "INSERT INTO `wc_t_original_doc` SET 
        `lcno` = '$LcNo',
        `shipno` = '$shipNo',
        `status` = $discStatus,
        `discrepancy` = '$discrepancyList',
        `banknotifydate` = '$bankNotifyDate',
        `submittedby` = $user_id,
        `submittedfrom` = '$ip'";
    $objdal->insert($query);
	
    //$objdal->LastInsertId();
	if($discStatus==1) {
		$userMsg = "Discrepancy Status: YES\n";
	}else{
		$userMsg = "Discrepancy Status: NO\n";
	}
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$PoNo."'",
        'actionid' => action_Sent_for_Original_Document_Accpetance,
        'status' => 1,
        'shipno' => $shipNo,
        'msg' => "'Request for Original Document acceptance against PO#".$PoNo." and Ship# " . $shipNo . "'",
        'usermsg' => "'".$userMsg." ".$discrepancyList."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'Request submitted successfully!';
	return json_encode($res);
    
}

function GetDiscrepancy($pono, $ship){

	$objdal = new dal();

	$query = "SELECT 
			l.pono, o.*
		FROM
			wc_t_original_doc AS o
				INNER JOIN
			wc_t_lc AS l ON o.lcno = l.lcno
			where l.pono = '$pono' and o.shipno = $ship;";

	$objdal->read($query);
    $res = "";
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $res = json_encode($res);
	}
	unset($objdal);
	return $res;
}

function GetEAAcceptanceMessage($pono, $ship){
    $objdal = new dal();
	$query = "SELECT a1.* FROM `wc_t_action_log` a1 
            WHERE a1.`RefID` = (SELECT a2.`ID` FROM `wc_t_action_log` a2 
                WHERE `ActionID` = ".action_Sent_for_Original_Document_Accpetance." AND `PO` = '$pono' AND `shipNo` = $ship
                ORDER BY `ActionOn` DESC LIMIT 1);";
	$objdal->read($query);
    $res = "";
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $res = json_encode($res);
	}
	unset($objdal);
	return $res;
}

function GetLetterInfo($bankId, $po)
{
	$objdal = new dal();
	$query = "SELECT `name`, `address`,
         (SELECT `name` FROM `wc_t_category` WHERE `id`=(SELECT `currency` FROM `wc_t_po` where `poid` = '$po')) `currency`,
         (SELECT `name` FROM `wc_t_company` WHERE `id`=(select `supplier` from `wc_t_po` where `poid` = '$po')) `coname`,
         (SELECT `address` FROM `wc_t_company` WHERE `id`=(select `supplier` from `wc_t_po` where `poid` = '$po')) `coaddress`,
         (SELECT `name` FROM `wc_t_bank_insurance` WHERE `id`=(select `bankaccount` from `wc_t_lc` where `pono` = '$po')) `account`
        FROM `wc_t_bank_insurance` WHERE `id` = $bankId;";
	//echo $query;
    $objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

?>

