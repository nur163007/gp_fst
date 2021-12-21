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

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo SubmitToBTRC();
    }
}

function SubmitToBTRC()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    } else {
        //$res["status"] = 0;
    	//$res["message"] = 'Valid reference code.'.$refId;
    	//return json_encode($res);
    }
    
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    $messageUser = htmlspecialchars($_POST['messageUser'],ENT_QUOTES, "ISO-8859-1");
    //$buyresMessage = htmlspecialchars($_POST['buyersMessage'],ENT_QUOTES, "ISO-8859-1");

    if($userAction==1){
        $attachLCChecklist = htmlspecialchars($_POST['attachLCChecklist'],ENT_QUOTES, "ISO-8859-1");
    } else {
        $attachLCChecklist = '';
    }

    if($userAction==2 || $userAction==4){
        $attachBTRCNOC = htmlspecialchars($_POST['attachBTRCNOC'],ENT_QUOTES, "ISO-8859-1");
    } else {
        $attachBTRCNOC = '';
    }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'Failed to save data.';
	//------------------------------------------------------------------------------
    
    /*if($userAction==1){
        $msgTitle = 'Sent for BTRC NOC';
        $newStatus = 12;
        $toGroup = 5;
    } elseif($userAction==2){
        $msgTitle = 'BTRC NOC Accepted';
        $newStatus = 14;
        $toGroup = 2;
    } elseif($userAction==3){
        $msgTitle = 'Rejected by BTRC';
        $newStatus = 13;
        $toGroup = 2;
    }

    // update new po table
    $query = "UPDATE `wc_t_po` SET 
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip'
        WHERE `poid` = '$poid';";
	$objdal->insert($query);
    */

	//echo($query);
    if($userAction==1){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Sent_for_BTRC_Permission,
            'status' => 1,
            'msg' => "'BTRC NOC process started for PO #".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    if($userAction==2){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Accepted_by_BTRC,
            'status' => 1,
            'msg' => "'BTRC NOC received for PO #".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    if($userAction==3){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_BTRC_Process_Approved_by_3rd_Level,
            'status' => 1,
            'msg' => "'BTRC NOC process approved against PO #".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    // Rejected
    if($userAction==4){
        // Action Log --------------------------------//
        if($loginRole==role_LC_Approvar_3){
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $poid . "'",
                'actionid' => action_BTRC_Process_Rejected_by_3rd_Level,
                'status' => 1,
                'msg' => "'BTRC NOC process rejected against PO #" . $poid . "'",
                'usermsg' => "'".$messageUser."'",
            );
        } elseif($loginRole==const_role_public_regulatory_affairs) {
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $poid . "'",
                'actionid' => action_Rejected_by_BTRC,
                'status' => 1,
                'msg' => "'BTRC rejected against PO #" . $poid . "'",
                'usermsg' => "'".$messageUser."'",
            );
        }
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
    //insert attachment
    if($loginRole==role_Buyer && $attachLCChecklist!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poid', 'LC Checklist', '$attachLCChecklist', $user_id, '$ip', $loginRole);";
        
    	$objdal->insert($query);
        //echo($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($poid);
    }

    if($loginRole==role_Corporate_Affairs && $attachBTRCNOC!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poid', 'BTRC NOC', '$attachBTRCNOC', $user_id, '$ip', $loginRole);";

    	$objdal->insert($query);
        //echo($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($poid);
    }

    // Send back to PR & EA for recheck
    if($userAction==5){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Sent_for_PR_Feedback,
            'status' => -1,
            'msg' => "'Buyer requested for Final PI recheck against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);

        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Sent_for_EA_Feedback,
            'status' => -1,
            'msg' => "'Buyer requested for Final PI recheck against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    // Reject back to supplier for Final PI rectification
    if($userAction==6){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Requested_for_Final_PI_Rectification,
            'status' => -1,
            'msg' => "'Buyer requested for Final PI Rectification against PO# ".$poid.".'",
            'usermsg' => "'".$messageUser."'",
        );
        $lastAction = UpdateAction($action);
        // End Action Log -----------------------------
    }

    if($userAction==7){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Sent_to_BTRC_for_NOC,
            'status' => 1,
            'msg' => "'Request Sent to BTRC for NOC against PO #".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    if($userAction==8){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Ready_For_Submission,
            'status' => 1,
            'msg' => "'Request Sent to PRA against PO #".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

	unset($objdal);
	
	$res["status"] = 1;
    if($userAction==1){
        $res["message"] = 'Request sent for BTRC NOC.';
    } elseif($userAction==2){
        $res["message"] = 'BTRC NOC Received';
    } elseif($userAction==4){
        $res["message"] = 'Rejected by BTRC';
    }else{
        $res["message"] = 'Action done Successfully!';
    }

	return json_encode($res);
}

?>