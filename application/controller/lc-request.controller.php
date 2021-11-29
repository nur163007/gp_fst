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
    if(!empty($_POST["pono"]) || isset($_POST["pono"])){       
       echo SubmitLCApproval();
    }
}

// Insert
function SubmitLCApproval()
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
    $approversComment = htmlspecialchars($_POST['approversComment'],ENT_QUOTES, "ISO-8859-1");
    
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    //if(in_array($loginRole, array(role_LC_Approvar_4,role_LC_Approvar_5))){
    if($loginRole==role_LC_Approvar_4){
        
        $bank = htmlspecialchars($_POST['bank'],ENT_QUOTES, "ISO-8859-1");
        $insurance = htmlspecialchars($_POST['insurance'],ENT_QUOTES, "ISO-8859-1");
        
        /*if($loginRole == role_LC_Approvar_5){
            $lcissuerbankOld = htmlspecialchars($_POST['lcissuerbankOld'],ENT_QUOTES, "ISO-8859-1");
            $lcissuerbankNew = htmlspecialchars($_POST['lcissuerbankNew'],ENT_QUOTES, "ISO-8859-1");
            $insuranceOld = htmlspecialchars($_POST['insuranceOld'],ENT_QUOTES, "ISO-8859-1");
            $insuranceNew = htmlspecialchars($_POST['insuranceNew'],ENT_QUOTES, "ISO-8859-1");
        }*/
    }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
    $pono = stripslashes($pono);
    $approversComment = stripslashes($approversComment);
	
    //if(in_array($loginRole, array(role_LC_Approvar_4,role_LC_Approvar_5))){
	if($loginRole==role_LC_Approvar_4){
        $bank = stripslashes($bank);
        $insurance = stripslashes($insurance);
    }
    
	$objdal = new dal();
	
    $pono = $objdal->real_escape_string($pono);
    $approversComment = $objdal->real_escape_string($approversComment);
    
    //if(in_array($loginRole, array(role_LC_Approvar_4,role_LC_Approvar_5))){
    if($loginRole==role_LC_Approvar_4){
        $bank = $objdal->real_escape_string($bank);
        $insurance = $objdal->real_escape_string($insurance);
    }
    
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'Failed to PO!';
	//------------------------------------------------------------------------------
    
    if($loginRole==role_LC_Approvar_1){
        $level = '1st';
        if($userAction==1){$newStatus = 16;} elseif($userAction==2) {$newStatus = 17;}
        if($userAction==1){$toGroup = 7;} elseif($userAction==2) {$toGroup = 2;}
        if($userAction==1){ $actionId = action_Approved_by_1st_Level; } 
            elseif($userAction==2){ $actionId = action_Rejected_by_1st_Level; }
    } elseif($loginRole==role_LC_Approvar_2){
        $level = '2nd';
        if($userAction==1){$newStatus = 18;} elseif($userAction==2) {$newStatus = 19;}
        if($userAction==1){$toGroup = 8;} elseif($userAction==2) {$toGroup = 2;}
        if($userAction==1){ $actionId = action_Approved_by_2nd_Level; } 
            elseif($userAction==2){ $actionId = action_Rejected_by_2nd_Level; }
    } elseif($loginRole==role_LC_Approvar_3){
        $level = '3rd';
        if($userAction==1){$newStatus = 20;} elseif($userAction==2) {$newStatus = 21;}
        if($userAction==1){$toGroup = 9;} elseif($userAction==2) {$toGroup = 2;}
        if($userAction==1){ $actionId = action_Approved_by_3rd_Level; } 
            elseif($userAction==2){ $actionId = action_Rejected_by_3rd_Level; }
    } elseif($loginRole==role_LC_Approvar_4){
        $level = '4th';
        if($userAction==1){$newStatus = 22;} elseif($userAction==2) {$newStatus = 23;}
        if($userAction==1){$toGroup = 10;} elseif($userAction==2) {$toGroup = 2;}
        if($userAction==1){ $actionId = action_Approved_by_4th_Level; } 
            elseif($userAction==2){ $actionId = action_Rejected_by_4th_Level; }
    } elseif($loginRole==role_LC_Approvar_5){
        $level = '5th';
        if($userAction==1){$newStatus = 24;} elseif($userAction==2) {$newStatus = 25;}
        if($userAction==1){$toGroup = 11;} elseif($userAction==2) {$toGroup = 2;}
        if($userAction==1){ $actionId = action_Approved_by_5th_Level; } 
            elseif($userAction==2){ $actionId = action_Rejected_by_5th_Level; }
    }
    
    if($userAction==1){
        $msgTitle = 'Accepted LC request by '.$level.' level approver against PO# '.$pono;
    } elseif($userAction==2){
        $msgTitle = 'Rejected LC request by '.$level.' level approver against PO# '.$pono;
    }
    
    //if(in_array($loginRole, array(role_LC_Approvar_4,role_LC_Approvar_5)) && $userAction!=2){
    if($loginRole==role_LC_Approvar_4 && $userAction!=2){
        $query = "UPDATE `wc_t_lc` SET `lcissuerbank` = $bank, `insurance` = $insurance WHERE `pono` = '$pono';";
        $objdal->update($query);
    }
    
    /*if($loginRole == role_LC_Approvar_5){
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
    }*/
    if($userAction==1){
        // Action Log --------------------------------//    
        $action = array(
            'refid' => $refId,
            'pono' => "'".$pono."'",
            'actionid' => $actionId,
            'status' => 1,
            'msg' => "'".$msgTitle."'",
            'usermsg' => "'".$approversComment."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    if($userAction==2){
        // Action Log --------------------------------//    
        $action = array(
            'refid' => $refId,
            'pono' => "'".$pono."'",
            'actionid' => $actionId,
            'status' => -1,
            'msg' => "'".$msgTitle."'",
            'usermsg' => "'".$approversComment."'",
        );
        UpdateAction($action);
        // End Action Log -----------------------------
    }
    
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = "Feedback sent to next level";
	return json_encode($res);
}


?>

