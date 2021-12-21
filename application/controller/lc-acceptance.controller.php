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

if (!empty($_POST)){
    if($_POST['userAction']==1){
        
        echo AcceptLC();
        
    }elseif($_POST['userAction']==2){    
        
        echo submitSchedule();
    
    }elseif($_POST['userAction']==3){    
        
        echo rejectSchedule();
    
    }elseif($_POST['userAction']==4){    
        
        echo acceptSchedule();
    
    }
}

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	
			echo getShipmentSchedule($_GET["po"]);
			break;
		default:
			break;
	}
}

function acceptSchedule(){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES, "ISO-8859-1");
    if($comments==""){ $comments = 'NULL'; } else { $comments = "'".$comments."'"; }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
    
    $objdal = new dal();
	$query = "SELECT `id`, `pono`, `shipNo` 
        FROM `wc_t_shipment_ETA` WHERE `pono` = '$pono';";
	$objdal->read($query);
	
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
    		extract($val);
            $action = array(
                'refid' => $refId,
                'pono' => "'".$pono."'",
                'shipno' => $shipNo,
                'actionid' => action_Accepted_Shipment_Schedule,
                'status' => 1,
                'msg' => "'Shipment #".$shipNo." schedule accepted by Buyer against PO# ".$pono."'",
                'usermsg' => $comments,
            );
            UpdateAction($action);
        }
	}
	unset($objdal);
    
	$res["status"] = 1;
    $res["message"] = 'Feedback sent to supplier.';
	return json_encode($res);
    
}

function rejectSchedule(){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //------------------------------------------------------------------------------
    
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Rejected_Shipment_Schedule,
        'status' => -1,
        'msg' => "'Shipment schedule rejected by Buyer against PO# ".$pono."'",
        'usermsg' => "'".$comments."'",
    );
    UpdateAction($action);
    
    // End Action Log -----------------------------
    
	$res["status"] = 1;
    $res["message"] = 'Feedback sent to supplier.';
	return json_encode($res);
    
}

function getShipmentSchedule($pono){
    $objdal = new dal();
	$query = "SELECT `id`, `pono`, `shipNo`, `shipmode`, `scheduleETA`, `status` 
        FROM `wc_t_shipment_ETA` WHERE `pono` = '$pono';";
    //echo $query;
	$objdal->read($query);
	$res = "";
    if(!empty($objdal->data)){
		$res = $objdal->data;
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function AcceptLC()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_LC_Accepted,
        'status' => 1,
        'msg' => "'LC (".$lcno.") accepted by supplier against PO# ".$pono."'",
    );
    $lastAction = UpdateAction($action);
    // End Action Log -----------------------------
    
	$res["status"] = 1;
    $res["message"] = 'LC acceptance feedback sent to buyer.';
    $res["lastaction"] = encryptId($lastAction);
	return json_encode($res);
}

function submitSchedule(){

    /*echo '<pre>';
    var_dump($_POST);
    echo '</pre>';*/

    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $shipMode = htmlspecialchars($_POST['shippingMode'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
    //------------------------------------------------------------------------------
    
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $objdal = new dal();
    //echo 1;
    // First clear all previous schedule if any under this po
    $query = "DELETE FROM wc_t_shipment_ETA WHERE pono = '$pono' AND lcNo = '$lcno';";

    $objdal->delete($query);
    //echo 2;
    $shipmentNumber = 0;
    for($i=0; $i<count($_POST['shipmentSchedule']); $i++){
        
        $shipmentSchedule = htmlspecialchars($_POST['shipmentSchedule'][$i],ENT_QUOTES, "ISO-8859-1");
        $shipmentSchedule = date('Y-m-d', strtotime($shipmentSchedule));
        
        $shipmentNumber = $i + 1;
        
        $query = "INSERT INTO `wc_t_shipment_ETA` SET 
            `pono` = '".$pono."',
            `lcNo` = '".$lcno."',
            `shipNo` = ".$shipmentNumber.",
            `shipmode` = '".$shipMode."',
            `scheduleETA` = '".$shipmentSchedule."',
            `insertby` = ".$user_id.",
            `insertfrom` = '".$ip."';";
        //echo $query;
        $objdal->insert($query, "Could not save shipment ETA data");
    
    }
    
    unset($objdal);
    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Shared_Shipment_Schedule,
        'status' => 1,
        'msg' => "'Supplier scheduled for ".$shipmentNumber." shipment against PO# ".$pono."'",
    );
    UpdateAction($action);
    
    
    
    
	$res["status"] = 1;
    $res["message"] = 'LC accepted feedback sent.';
    //$res["lastaction"] = encryptId($lastAction);
	return json_encode($res);
}

?>

