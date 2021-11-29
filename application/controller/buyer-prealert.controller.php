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
$group_name = $_SESSION[session_prefix.'wclogin_rolename'];

if (isset($_GET["po"]) && isset($_GET["step"])){

    if(isset($_GET["info"])){
        echo checkStepOver($_GET["po"], $_GET["step"], $_GET["shipno"], $_GET["info"]);
    } else {
        echo checkStepOver($_GET["po"], $_GET["step"], $_GET["shipno"]);
    }
}

// Case for Insert and update
if (!empty($_POST)){

	if (!empty($_POST["pono"]) && isset($_POST["pono"])){

	    switch ($_POST['userAction']){
            case 1:
                echo accept();
                break;
            case 2:
                echo rejectedByBuyer();
                break;
            case 3:
                echo mailToWarehouse();
                break;
            case 4:
                echo mailToEATeam();
                break;
            case 5:
                echo mailToFinance();
                break;
        }
	}
}

function accept(){
    
    global $user_id;
	global $loginRole;
    global $group_name;

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
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $message = htmlspecialchars($_POST['userMessage'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Ship_Doc_Accepted_Buyer_Pending_WH,
        'status' => 1,
        'msg' => "'Shipment document accepted by buyer against PO# ".$pono." Shipment# ".$shipno."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Ship_Doc_Accepted_Buyer_Pending_EA,
        'status' => 1,
        'msg' => "'Shipment document accepted by buyer against PO# ".$pono." Shipment# ".$shipno."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
    $res["message"] = 'Shipment doc. accepted';
	return json_encode($res);
}

function mailToWarehouse()
{

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    } else {
        //$res["status"] = 0;
        //$res["message"] = 'Valid reference code.'.$refId;
        //return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'], ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'], ENT_QUOTES, "ISO-8859-1");
    $shipmode = htmlspecialchars($_POST['shipmode'], ENT_QUOTES, "ISO-8859-1");

    $message = htmlspecialchars($_POST['buyersMsgToWareHouse'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------

    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'shipno' => $shipno,
        'actionid' => action_Requested_for_Warehouse_Inputs,
        'status' => 1,
        'msg' => "'Requested for warehouse inputs against PO# " . $pono . " and Shipment# " . $shipno . "'",
        'usermsg' => "'" . $message . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    // Email notification to Trade Finance
    $objdal = new dal();
    $sql = "select email from wc_t_users where role=" . role_LC_Operation . ";";
    $objdal->read($sql);

    $to = array();
    $cc = $_SESSION[session_prefix.'wclogin_email'];

    if(!empty($objdal->data)){
        foreach ($objdal->data as $val) {
            extract($val);
            array_push($to, $email);
        }
    }
    if($shipmode=="air"){
        $doc = "endorsed";
    }elseif($shipmode=="sea") {
        $doc = "original";
    }else{
        $doc = "original";
    }

    $subject = "Pre-Alert to finance against PO# " . $pono . " and Shipment# " . $shipno . "";
    $message = "Appreciate if you go for necessary pre-works to collect $doc shipping document from respective bank, so that you can share doc after getting the voucher number. Your earliest cooperation is required for this issue.";


    if($_SERVER['SERVER_NAME']!='localhost') {
        wcMailFunction($to, $subject, $message, $cc);
    }
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Notification sent to Warehouse';
    return json_encode($res);
}

function mailToEATeam(){
    
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
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $message = htmlspecialchars($_POST['buyersMsgToEA'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Requested_for_EA_Inputs,
        'status' => 1,
        'msg' => "'Requested for EA team inputs against PO# ".$pono." and Shipment # ".$shipno."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
    $res["message"] = 'Message sent to EA Team group.';
	return json_encode($res);
}

function mailToFinance(){
    
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
    $objdal = new dal();
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $docType = htmlspecialchars($_POST['docType'],ENT_QUOTES, "ISO-8859-1");
    
    $voucherCreateDate = htmlspecialchars($_POST['voucherCreateDate'],ENT_QUOTES, "ISO-8859-1");
    $voucherCreateDate = date('Y-m-d', strtotime($voucherCreateDate));
    $voucherNo = htmlspecialchars($_POST['voucherNo'],ENT_QUOTES, "ISO-8859-1");
    $exchangeRate = htmlspecialchars($_POST['exchangeRate'],ENT_QUOTES, "ISO-8859-1");
    
    $message = htmlspecialchars($_POST['buyersMsgToFinance'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Shared_Voucher_info_to_Fin,
        'newstatus' => 1,
        'msg' => "'Acknowledgement'",
    );
    UpdateAction($action);
    if($docType=="endorse"){
        $newAction = action_Sent_for_Document_Endorsement;
        $msg = "'Request for endorsed document against PO# ".$pono." and Shipment # ".$shipno."'";
    } elseif($docType=="original"){
        $newAction = action_Requested_to_Collect_Original_Doc;
        $msg = "'Request for original document against PO# ".$pono." and Shipment # ".$shipno."'";
    }
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => $newAction,
        'status' => 1,
        'msg' => $msg,
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
    $query = "UPDATE `wc_t_shipment` SET 
        `GERPVoucherDate`='$voucherCreateDate', 
        `GERPVoucherNo`='$voucherNo', 
        `GERPExchangeRate`='$exchangeRate' 
        WHERE `pono`='$pono' AND `shipNo` = $shipno;";
    $objdal->update($query);
    
    unset($objdal);
    
	$res["status"] = 1;
    $res["message"] = 'Message sent to Finance group';
	return json_encode($res);
}

function rejectedByBuyer(){
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $message = htmlspecialchars($_POST['rejectMessage'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Shipment_Document_Rejected,
        'status' => -1,
        'msg' => "'Shipment document rejected by buyer against PO# ".$pono." and Shipment # ".$shipno."'",
        'usermsg' => "'".$message."'",
    );

    UpdateAction($action);
    // End Action Log -----------------------------
	$res["status"] = 1;
    $res["message"] = 'Feedback sent supplier.';
	return json_encode($res);
}
?>

