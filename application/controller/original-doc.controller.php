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
			echo GetDocInfo($_GET['po'], $_GET['shipno']);
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

//	var_dump($_POST);
//	exit();
	if (!empty($_POST["LcNo1"]) || isset($_POST["LcNo1"])){
	   echo SubmitODocAcceptenceRequest();
	}
    if (!empty($_POST["EATeamAction"]) || isset($_POST["EATeamAction"])){
        echo SubmitEAFeedback();
    }
    if (!empty($_POST["attachOriginalDoc"]) || isset($_POST["attachOriginalDoc"])){
        echo OriginalDocDeliver();
    }
	if (!empty($_POST["userAction"])){
		echo InsPolicyRequest();
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
                WHERE `poid` = '$pono' AND `title`='Original Bank Document' AND `filename` = '$attachOriginalDocOld'";
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

		// LC Payment FX rate settlement dump
		SightPaymentForFXSettlement($PoNo, $shipNo, 6);

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

function SightPaymentForFXSettlement($pono, $shipno, $doc)
{
	$objdal = new dal();

	$sql = "INSERT INTO fx_settelment_pending_fn(`pono`, `shipment`, `partname`, `percentage`, `amount`, `ciamount`, `lcno`, `duedate`, `cino`)
		SELECT s.pono, s.shipNo, p.partname, p.percentage, (s.ciAmount/100)*p.percentage, s.ciAmount, s.lcNo, 
		(SELECT a1.ActionOn FROM `wc_t_action_log` a1 
            WHERE PO = '$pono' AND shipNo = $shipno AND ActionID = " . action_Sent_for_Original_Document_Accpetance . " 
            ORDER BY Id DESC LIMIT 1), 
		s.ciNo
		FROM wc_t_shipment s inner join wc_t_payment_terms p on s.pono = p.pono 
		where s.pono = '$pono' and shipNo = $shipno and p.partname = $doc;";

	try {
		$objdal->insert($sql);
		unset($objdal);
		return 1;
	} catch (Exception $e) {
		unset($objdal);
		return 0;
	}
}

function GetODocBankNotification($pono, $ship){
	$objdal = new dal();
	$query = "SELECT a1.ActionOn FROM `wc_t_action_log` a1 
            WHERE PO = '$pono' and shipNo = $ship and ActionID = ".action_Sent_for_Original_Document_Accpetance." 
            ORDER BY Id DESC LIMIT 1;";
	$res = "";
	$res = $objdal->getScalar($query);
	unset($objdal);
	return $res;
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
    }

	$LcNo = htmlspecialchars($_POST['LcNo1'],ENT_QUOTES, "ISO-8859-1");
	$PoNo = htmlspecialchars($_POST['PoNo'],ENT_QUOTES, "ISO-8859-1");
	$discStatus = htmlspecialchars($_POST['discStatus'],ENT_QUOTES, "ISO-8859-1");
	$discrepancyList = htmlspecialchars($_POST['discrepancyList'],ENT_QUOTES, "ISO-8859-1");

    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

	//	$objdal = new dal();
	//------------------------------------------------------------------------------

	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	/*
    $query = "INSERT INTO `wc_t_original_doc` SET
        `lcno` = '$LcNo',
        `shipno` = '$shipNo',
        `status` = $discStatus,
        `discrepancy` = '$discrepancyList',
        `banknotifydate` = NOW(),
        `submittedby` = $user_id,
        `submittedfrom` = '$ip'";
    $objdal->insert($query);
	*/
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

	$res["status"] = 1;
	$res["message"] = 'Request submitted successfully!';
	return json_encode($res);
    
}

function GetDocInfo($pono, $ship){

	$objdal = new dal();

	$query = "SELECT l.pono, o.*,
			(SELECT a.filename FROM wc_t_attachments a WHERE a.poid = l.pono AND a.shipno = o.shipno AND a.title = 'Original Bank Document' ORDER BY id DESC LIMIT 1) `attachOriginalDoc`
		FROM wc_t_original_doc AS o
			INNER JOIN wc_t_lc AS l ON o.lcno = l.lcno
		WHERE l.pono = '$pono' AND o.shipno = $ship;";

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
         (SELECT `name` FROM `wc_t_category` WHERE `id`=(SELECT `currency` FROM `wc_t_pi` where `poid` = '$po')) `currency`,
         (SELECT `name` FROM `wc_t_company` WHERE `id`=(select `supplier` from `wc_t_pi` where `poid` = '$po')) `coname`,
         (SELECT `address` FROM `wc_t_company` WHERE `id`=(select `supplier` from `wc_t_pi` where `poid` = '$po')) `coaddress`,
         (SELECT `name` FROM `wc_t_bank_insurance` WHERE `id`=(select `bankaccount` from `wc_t_lc` where `pono` = '$po')) `account`
        FROM `wc_t_company` WHERE `id` = $bankId;";
	//echo $query;
    $objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function InsPolicyRequest(){

	$refId = decryptId($_POST["refId"]);

	global $user_id;
	global $loginRole;
	$objdal = new dal();
	$po = $objdal->sanitizeInput($_POST['pono']);
	$shipno = $objdal->sanitizeInput($_POST['shipno']);
	$insurance = $objdal->sanitizeInput($_POST['insurance']);

		$action = array(
			'refid' => $refId,
			'pono' => "'".$po."'",
			'shipno' => $shipno,
			'actionid' => action_Requested_for_Ins_Policy_by_TFO,
			'pendingtoco' => $insurance,
			'msg' => "'Insurance policy request sent against  PO# ".$po."'",
		);
		UpdateAction($action);

	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'Insurance Policy Request sent Successfully';
	return json_encode($res);
}
?>

