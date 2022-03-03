<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: A'qa Technology
    Code by: Shohel Iqbal
    Date: 13.01.2022
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_POST)){

    switch($_POST["userAction"]){
        case 1:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo SubmitBTRCDocRequest();
            }
            break;
        case 2:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo SendRequestToBank();
            }
            break;
        case 3:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo SendBASISApprovalToTFO();
            }
            break;
        case 4:
            if(!empty($_POST["pono"]) || isset($_POST["pono"])){
                echo ShareBASISApprovalToBuyer();
            }
            break;
        default:
            break;
    }
}

function SubmitBTRCDocRequest(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");

    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';

    // Start Action Log ----------------------------
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Request_for_Bank_Forwarding_Letter,
        'status' => 1,
        'msg' => "'BASIS approval letter requested by Buyer against PO# ".$pono."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Request Sent SUCCESSFULLY';
    return json_encode($res);

}

function SendRequestToBank(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $objdal = new dal();

    $pono = $objdal->sanitizeInput($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $letterIssuerBank = $objdal->sanitizeInput($_POST['letterIssuerBank'],ENT_QUOTES, "ISO-8859-1");
    $attachRequestLetter = $objdal->sanitizeInput($_POST['attachRequestLetter'],ENT_QUOTES, "ISO-8859-1");

    $ip = $objdal->sanitizeInput($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //insert attachment
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$pono', 'Request for BASIS Approval Letter', '$attachRequestLetter', $user_id, '$ip', $loginRole);";

    $objdal->insert($query);
    unset($objdal);

    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);

    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';

    // Start Action Log ----------------------------
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_Request_for_BASIS_Approval_Letter,
        'status' => 1,
        'pendingtoco' => $letterIssuerBank,
        'msg' => "'BASIS approval letter requested by GP against PO# ".$pono."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Request Sent SUCCESSFULLY';
    return json_encode($res);

}

function SendBASISApprovalToTFO(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $objdal = new dal();

    $pono = $objdal->sanitizeInput($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $attachBasisApproval = $objdal->sanitizeInput($_POST['attachBasisApproval'],ENT_QUOTES, "ISO-8859-1");

    $ip = $objdal->sanitizeInput($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //insert attachment
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$pono', 'BASIS Letter for Approval of Import', '$attachBasisApproval', $user_id, '$ip', $loginRole);";

    $objdal->insert($query);
    unset($objdal);

    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);

    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';

    // Start Action Log ----------------------------
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_BASIS_Approval_Letter_Sent_by_Bank,
        'status' => 1,
        'msg' => "'BASIS approval letter sent by Bank against PO# ".$pono."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Request Sent SUCCESSFULLY';
    return json_encode($res);

}

function ShareBASISApprovalToBuyer(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");

    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';

    // Start Action Log ----------------------------
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_BASIS_Approval_Letter_Shared_to_Buyer,
        'status' => 1,
        'newstatus' => 1,
        'msg' => "'BASIS approval letter shared by Buyer against PO# ".$pono."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Request Sent SUCCESSFULLY';
    return json_encode($res);

}
