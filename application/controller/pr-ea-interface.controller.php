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
$user_role_name = $_SESSION[session_prefix.'wclogin_rolename'];

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo SubmitFeedback();
    }
}

function SubmitFeedback()
{
	global $user_id;
	global $loginRole;
    global $user_role_name;
    
    $refId = decryptId($_POST["refId"]);
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
    
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    $messageUser = htmlspecialchars($_POST['messageUser'],ENT_QUOTES, "ISO-8859-1");

    $attachJustification = '';
    if($loginRole == role_PR_Users){
        if($_POST['attachJustification']!="") {
            $attachJustification = htmlspecialchars($_POST['attachJustification'], ENT_QUOTES, "ISO-8859-1");
        }else{
            if($_POST['attachJustificationOld']!="") {
                $attachJustification = htmlspecialchars($_POST['attachJustificationOld'], ENT_QUOTES, "ISO-8859-1");
            }
        }
        $exp_type = htmlspecialchars($_POST['exp_type'],ENT_QUOTES, "ISO-8859-1");
        $user_just = htmlspecialchars($_POST['user_just'],ENT_QUOTES, "ISO-8859-1");
    }

    if($loginRole == role_External_Approval){
        $lcdesc = htmlspecialchars($_POST['lcdesc'],ENT_QUOTES, "ISO-8859-1");
    }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    //---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);

	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    if($loginRole == role_External_Approval){
    // update po
        $query = "UPDATE `wc_t_po` SET 
    		`lcdesc` = '$lcdesc',
            `modifiedby` = $user_id,
            `modifiedfrom` = '$ip'
            WHERE `poid` = '$poid';";
    	$objdal->insert($query);
    }
    elseif($loginRole == role_PR_Users){
        $query = "UPDATE `wc_t_po` SET 
            `exp_type` = $exp_type,
            `user_justification` = '$user_just'
            WHERE `poid` = '$poid';";
        $objdal->insert($query);
    }
    if($userAction==1){
        // Action Log --------------------------------//
        if(getActionID($refId) > action_Final_PI_Submitted){
            if($loginRole==role_PR_Users){
                $actionId = action_Final_PI_Rejected_by_PR;
            } elseif($loginRole == role_External_Approval){
                $actionId = action_Final_PI_Rejected_by_EA;
            }
            
        }else{
            if($loginRole == role_PR_Users){
                $actionId = action_Draft_PI_Rejected_by_PR;
            } elseif($loginRole == role_External_Approval){
                $actionId = action_Draft_PI_Rejected_by_EA;
            }
        }
        $xref = getXRefID($refId);
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => $actionId,
            'status' => -1,
            'xrefid' => $xref,
            'msg' => "'PI rejected by ".$user_role_name." against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
    if($userAction==2){
        // Action Log --------------------------------//
        if(getActionID($refId) > action_Final_PI_Submitted) {
            if ($loginRole == role_PR_Users) {
                $actionId = action_Final_PI_Accepted_by_PR;
            } elseif ($loginRole == role_External_Approval) {
                $actionId = action_Final_PI_Accepted_by_EA;
            }

        } else {
            if($loginRole==role_PR_Users){
                $actionId = action_Draft_PI_Accepted_by_PR;
            } elseif($loginRole == role_External_Approval){
                $actionId = action_Draft_PI_Accepted_by_EA;
            }
        }
        $xref = getXRefID($refId);
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => $actionId,
            'status' => 1,
            'xrefid' => $xref,
            'msg' => "'PI approved by ".$user_role_name." against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------

        //insert attachment
        if($loginRole == role_PR_Users && $attachJustification!=""){

            $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poid', 'Justification', '$attachJustification', $user_id, '$ip', $loginRole);";

            $objdal->insert($query);
            //Transfer file from 'temp' directory to respective 'docs' directory
            fileTransferTempToDocs($poid);
        }

    }

    // Final PI rectification to Supplier
    if($userAction==3){
        // Action Log --------------------------------//
        if(getActionID($refId)>10){
            $actionId = action_Requested_for_Final_PI_Rectification;
        }else{
            $actionId = action_Requested_for_Draft_PI_Rectification;
        }
        
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => $actionId,
            'status' => 1,
            'msg' => "'Requested for PI Rectification against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
    if($userAction==4){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Requested_for_Final_PI,
            'status' => 1,
            'msg' => "'Requested for Final PI against PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    if($userAction==5){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Edit_And_Send_For_Recheck,
            'status' => 1,
            'msg' => "'Inwarded to edit PO# ".$poid."'",
            'usermsg' => "'".$messageUser."'",
        );
        $lastAction = UpdateAction($action);
        // End Action Log -----------------------------
    }

    // Draft PI accepted
    if($userAction==6){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Accepted,
            'status' => 1,
            'msg' => "'Draft PI accepted as final and BTRC permission process started against PO# ".$poid.".'",
            'usermsg' => "'".$messageUser."'",
        );
        $lastAction = UpdateAction($action);
        // End Action Log -----------------------------
    }
	unset($objdal);
	
	$res["status"] = 1;
    if($userAction == '1'){
        $res["message"] = 'REJECT Feedback send to Buyer.';
    } elseif($userAction == '2'){
        $res["message"] = 'ACCEPT Feedback send to Buyer.';
    } elseif($userAction == '3'){
        $res["message"] = 'Feedback send to Supplier.';
    } elseif($userAction == '4'){
        $res["message"] = 'Feedback send to Supplier.';
    } elseif($userAction == '5'){
        $res["message"] = 'Pending for edit PO';
        $res["lastaction"] = encryptId($lastAction);
    } elseif($userAction=='6'){
        $res["message"] = 'PI Acceptance feedback sent to supplier.';
        $res["lastaction"] = encryptId($lastAction);
    }
	
	return json_encode($res);
}

function GetLastStatus($poid){
    $dal = new dal();
    $sql = "SELECT `status` FROM `wc_t_po` WHERE `poid` = '$poid' ORDER BY `msgon` DESC LIMIT 1";
    $dal->read($sql);
    $res = '';
    if(!empty($dal->data)){
        $row = $dal->data[0];
        extract($row);
        $res = $status;
    }
    unset($dal);
    return $res;
}

?>