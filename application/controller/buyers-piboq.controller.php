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

//if (!empty($_GET["action"]) || isset($_GET["action"]))
//{
//	switch($_GET["action"])
//	{
//		case 1:	// get single user info
//			if(!empty($_GET["xpo"])) { echo GetNewPONumber($_GET["xpo"]); } else { echo GetNewPONumber(); };
//			break;
//		case 2:	// get a purchase order
//			echo GetPODetail($_GET["id"]);
//			break;
//		default:
//			break;
//	}
//}

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo SubmitPIBOQ();
    }
}

function SubmitPIBOQ()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $postatus = htmlspecialchars($_POST['postatus'],ENT_QUOTES, "ISO-8859-1");
    
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $lcdesc = htmlspecialchars($_POST['lcdesc'],ENT_QUOTES, "ISO-8859-1");
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    if($userAction<>2){
        $messagetopr = htmlspecialchars($_POST['messageToPRUser'],ENT_QUOTES, "ISO-8859-1");
        $messagetoea = htmlspecialchars($_POST['messageToEATeam'],ENT_QUOTES, "ISO-8859-1");    
    }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
    $lcdesc = stripslashes($lcdesc);
    
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
    $lcdesc = $objdal->real_escape_string($lcdesc);
    
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    
	// update po
    $query = "UPDATE `wc_t_po` SET 
		`lcdesc` = '$lcdesc',
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip'
        WHERE `poid` = '$poid';";
	$objdal->insert($query);
	//echo($query);
    
    if($userAction==1){
        // Action Log --------------------------------//
        
        if($postatus>=action_Final_PI_Submitted){
            $newActionID_PR = action_Final_PI_Sent_for_PR_Feedback;
            $newActionID_EA = action_Final_PI_Sent_for_EA_Feedback;
            $piType = 'Final';
        } else{
            $newActionID_PR = action_Draft_PI_Sent_for_PR_Feedback;
            $newActionID_EA = action_Draft_PI_Sent_for_EA_Feedback;
            $piType = 'Draft';
        }
        
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => $newActionID_PR,
            'status' => 1,
            'msg' => "'Requested for feedback on suppliers $piType PI against PO# ".$poid."'",
            'usermsg' => "'".$messagetopr."'",
        );
        UpdateAction($action);
        
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => $newActionID_EA,
            'status' => 1,
            'msg' => "'Requested for feedback on suppliers $piType PI against PO# ".$poid."'",
            'usermsg' => "'".$messagetoea."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }

    // Final PI accepted
    if($userAction==2){
        // Action Log --------------------------------//    
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Accepted,
            'status' => 1,
            'msg' => "'Final PI accepted and process started for BTRC permission against PO# ".$poid.".'",
        );
        $lastAction = UpdateAction($action);
        // End Action Log -----------------------------
    }

    // Send PR & EA to recheck
    if($userAction==3){
        // Action Log --------------------------------//
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Sent_for_PR_Feedback,
            'status' => 1,
            'msg' => "'Requested for feedback on suppliers Final PI against PO# ".$poid."'",
            'usermsg' => "'".$messagetopr."'",
        );
        UpdateAction($action);
        
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Final_PI_Sent_for_EA_Feedback,
            'status' => 1,
            'msg' => "'Requested for feedback on suppliers Final PI against PO# ".$poid."'",
            'usermsg' => "'".$messagetoea."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
	unset($objdal);
	
	$res["status"] = 1;
	
    if($userAction==1){
        $res["message"] = 'Feedback request send to PR User and EA Team.';
    } elseif($userAction==2){
        $res["message"] = 'Final PI Acceptance feedback sent to supplier.';
        $res["lastaction"] = encryptId($lastAction);
    } elseif($userAction==3){
        $res["message"] = 'Re Check request send to PR User and EA Team.';
    }   
    
	return json_encode($res);
}

?>

