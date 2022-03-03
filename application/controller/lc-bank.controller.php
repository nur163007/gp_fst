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
        case 1:	// get a purchase order
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetPODetail($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetPODetail($_GET["id"]);
            }
            break;
        case 2:
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetLCattach($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetLCattach($_GET["id"]);
            }
            break;
        case 3:
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetLCComments($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetLCComments($_GET["id"]);
            }
            break;

        case 4:
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetLCTFOComments($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetLCTFOComments($_GET["id"]);
            }
            break;
        case 5:
            echo GetBankNotification();
            break;
        case 6:
            echo GetFinFXSettlePending();
            break;
        default:
            break;
    }
}

if (!empty($_POST)){

    switch($_POST["userAction1"]){
        case 1:
            if(!empty($_POST["po"]) || isset($_POST["po"])){
                echo submitLCToTFO();
            }
            break;
        case 2:
            if(!empty($_POST["po"]) || isset($_POST["po"])){
                echo ShareLCBuyerSupplier();
            }
            break;
        case 3:
            if(!empty($_POST["po"]) || isset($_POST["po"])){
                echo feedbackBuyer();
            }
            break;
        case 4:
            if(!empty($_POST["po"]) || isset($_POST["po"])){
                echo feedbackSupplier();
            }
            break;

        case 5:
            if(!empty($_POST["po"]) || isset($_POST["po"])){
                echo AcceptFeedback();
            }
            break;

        case 6:
            if(!empty($_POST["LcNo1"]) || isset($_POST["LcNo1"])){
                echo SubmitODocReceiptNotification();
            }
            break;

        default:
            break;
    }

}

//SUBMIT LC COPY TO TFO
function submitLCToTFO()
{

    $refId = decryptId($_POST["refId2"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);
    $actionID = $objdal->sanitizeInput($_POST['actionID']);

    // attachment data in an 3D array

    if ($actionID == 401) {
        $attachLC = $objdal->sanitizeInput($_POST['attachLC']);
//        $attachBRC = $objdal->sanitizeInput($_POST['attachBRC']);
//        $attachBCA = $objdal->sanitizeInput($_POST['attachBCA']);
    }

    if ($actionID == 402) {
        $lcno = htmlspecialchars($_POST['lcno'], ENT_QUOTES, "ISO-8859-1");

        if ($_POST['lcissuedate'] != "") {
            $lcissuedate = htmlspecialchars($_POST['lcissuedate'], ENT_QUOTES, "ISO-8859-1");
            $lcissuedate = date('Y-m-d', strtotime($lcissuedate));
            $lcissuedate = "`lcissuedate` = '" . $lcissuedate . "'";
        } else {
            $lcissuedate = '';
        }

        $lcexpirydate = htmlspecialchars($_POST['lcexpirydate'], ENT_QUOTES, "ISO-8859-1");
        $lcexpirydate = date('Y-m-d', strtotime($lcexpirydate));
        $attachLC = $objdal->sanitizeInput($_POST['attachLC']);
        $attachBRC = $objdal->sanitizeInput($_POST['attachBRC']);
        $attachBCA = $objdal->sanitizeInput($_POST['attachBCA']);

        $query = "UPDATE `wc_t_lc` SET 
        `lcno` = '$lcno', 
         $lcissuedate,
        `lcexpirydate` = '$lcexpirydate' 
        WHERE `pono` = '$po';";

        $objdal->update($query);

        // Checking if this data is already exist
        $query = "SELECT COUNT(*) AS `exist` FROM `wc_t_lc_opening_bank_charge` WHERE `LcNo` = '$lcno';";
        $objdal->read($query);

        $obcExist = 0;
        if(!empty($objdal->data)){
            $res = $objdal->data[0];
            extract($res);
            $obcExist = $res['exist'];
        }

        if($obcExist==0) {
            $taskMessage = 'Insert new data';
            $query = "INSERT INTO `wc_t_lc_opening_bank_charge` SET 
			`LcNo` = '$lcno';";

            $objdal->insert($query);

        }
    }

    $ip = $_SERVER['REMOTE_ADDR'];


    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    if ($actionID == 401) {
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Draft LC Copy', '$attachLC', $user_id, '$ip', $loginRole)";
    } elseif ($actionID == 402) {
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `lcno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po','$lcno', 'Final LC Copy', '$attachLC', $user_id, '$ip', $loginRole),
        ('$po','$lcno', 'Bank Received Copy', '$attachBRC', $user_id, '$ip', $loginRole),
        ('$po','$lcno', 'Bank Charge Advice', '$attachBCA', $user_id, '$ip', $loginRole)";
    }
    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($po);


    if ($actionID == 401) {
        $action = array(
            'refid' => $refId,
            'pono' => "'" . $po . "'",
            'actionid' => action_Draft_LC_Copy_Sent_to_GP,
            'status' => 1,
            'msg' => "'Draft LC Copy Sent to GP against PO# " . $po . "'",
        );
    } elseif ($actionID == 402) {
        $action = array(
            'refid' => $refId,
            'pono' => "'" . $po . "'",
            'actionid' => action_Final_LC_Copy_Sent_to_GP,
            'status' => 1,
            'msg' => "'Final LC Copy Sent to GP against PO# " . $po . "'",
        );
    }

    UpdateAction($action);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'LC copy sent to TFO Successfully';
    return json_encode($res);
}

//SHARE LC COPY TO BUYER AND SUPPLIER
function ShareLCBuyerSupplier(){

    $refId = decryptId($_POST["refId2"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);

    $actionID = $objdal->sanitizeInput($_POST['actionID']);

if($actionID == 403){
    $action = array(
        'refid' => $refId,
        'pono' => "'".$po."'",
        'actionid' => action_Draft_LC_shared_to_buyer,
        'status' => 1,
        'msg' => "'Share Draft LC Copy to Buyer against  PO# ".$po."'",
    );
    UpdateAction($action);
    $action = array(
        'refid' => $refId,
        'pono' => "'".$po."'",
        'actionid' => action_Draft_LC_shared_to_supplier,
        'status' => 1,
        'msg' => "'Share Draft LC Copy to Supplier against PO# ".$po."'",
    );
    UpdateAction($action);
}

/*    elseif($actionID == 404){
        $action = array(
            'refid' => $refId,
            'pono' => "'".$po."'",
            'actionid' => action_Final_LC_shared_to_buyer,
            'status' => 1,
            'msg' => "'Share Final LC Copy to Supplier against PO# ".$po."'",
        );
        UpdateAction($action);
        $action = array(
            'refid' => $refId,
            'pono' => "'".$po."'",
            'actionid' => action_Final_LC_shared_to_supplier,
            'status' => 1,
            'msg' => "'Share Final LC Copy to Supplier against PO# ".$po."'",
        );
        UpdateAction($action);
    }*/


    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Share LC copy to buyers & suppliers Successfully';
    return json_encode($res);
}

//SEND FEEDBACK BY BUYER TO TFO
function feedbackBuyer(){

    $refId = decryptId($_POST["refId2"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);
    $messagebuyer =$objdal->sanitizeInput($_POST['feedbackmessage']);
    $actionID = $objdal->sanitizeInput($_POST['actionID']);

    $xref = getXRefID($refId);

//    'xrefid' => $xref,
    if($actionID == 405){
        $action = array(
            'refid' => $refId,
            'pono' => "'".$po."'",
            'xrefid' => $xref,
            'actionid' => action_Draft_LC_feedback_given_by_buyer,
            'status' => 1,
            'msg' => "'Feedback sent by buyers against  PO# ".$po."'",
            'usermsg' => "'".$messagebuyer."'",
        );
        UpdateAction($action);
    }

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Feedback sent by buyers Successfully';
    return json_encode($res);
}

//SEND FEEDBACK BY BUYER TO TFO
function feedbackSupplier(){

    $refId = decryptId($_POST["refId2"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);
    $messagebuyer =$objdal->sanitizeInput($_POST['feedbackmessage']);
    $actionID = $objdal->sanitizeInput($_POST['actionID']);
    $xref = getXRefID($refId);
    if($actionID == 406){
        $action = array(
            'refid' => $refId,
            'pono' => "'".$po."'",
            'xrefid' => $xref,
            'actionid' => action_Draft_LC_feedback_given_by_supplier,
            'status' => 1,
            'msg' => "'Feedback sent by Suppliers against  PO# ".$po."'",
            'usermsg' => "'".$messagebuyer."'",
        );
        UpdateAction($action);
    }

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Feedback sent by supplier Successfully';
    return json_encode($res);
}

//SUBMIT LC COPY TO TFO
function AcceptFeedback(){

    $refId = decryptId($_POST["refId2"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$po."'",
        'actionid' => action_Buyer_Supplier_feedback_accepted,
        'status' => 1,
        'msg' => "'Buyer & Supplier Feedback Accepted against PO# ".$po."'",
    );
    UpdateAction($action);

    $query = "UPDATE `wc_t_action_log` SET 
              `Status` = 1
              where `PO`= '$po' AND `ActionID` IN(407,408);";
    $objdal->update($query);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Accept feedback Successfully';
    return json_encode($res);
}

function GetPODetail($id, $forPI=0, $shipno=0)
{
    global $user_id;
    global $loginRole;
    global $companyId;

    $objdal = new dal();
    $status = 0;
    $allStatus = array();
    $shipSql = "";

    //$response = ["status" => 0, "message" => "Invalid request access-denied"];
    if ($loginRole == role_Supplier) {
        $strQuery = $objdal->getRow("SELECT `supplier` FROM `wc_t_pi` WHERE `poid` = '$id';");
        $supplier = $strQuery['supplier'];
        if ($supplier != $companyId) {
            $response = ["status" => 0, "message" => "Invalid request"];
            unset($objdal->data);
            //return json_encode($response);
            die();
        }
    }


    if($forPI==1){

        $sql = "SELECT `poid` FROM `wc_t_pi` WHERE `poid` LIKE '".$id."%' ORDER BY `createdon` desc LIMIT 1;";
        $objdal->read($sql);

        if(!empty($objdal->data)){
            $id = $objdal->data[0]['poid'];
        }
        unset($objdal->data);
    }

    unset($objdal);
    $objdal = new dal();
    // get PO status collection
    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    unset($objdal->data);

    $extraWhere = ($loginRole == role_Supplier) ? " AND c2.`id` = $companyId" : "" ;

    $sql = "SELECT p.`poid`, p.`povalue`, p.`lcdesc`, c2.`name` `supname`,c1.`name` `curname`,p.`pinum`, p.`shipmode`,
            (SELECT `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id' ORDER BY `ID` DESC Limit 1) `status`,
            i.`name` AS `insurancebank`
            FROM `wc_t_pi` p 
                INNER JOIN `wc_t_category` c1 ON p.`currency` = c1.`id` 
                INNER JOIN `wc_t_company` c2 ON p.`supplier` = c2.`id`
                LEFT JOIN `wc_t_lc` l ON p.`poid` = l.`pono`
                LEFT JOIN `wc_t_company` i ON l.`insurance` = i.`id`
                
            WHERE p.`poid` = '$id';";
    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $podetail[0] = $objdal->data[0];
        //extract($podetail);
        //$status = $podetail[0]['status'];
    }
    //echo $status;
    // message log
    $i=0;
    unset($objdal->data);

    // attachments
    if(in_array(action_Draft_LC_Request_sent_to_Bank, $allStatus) || in_array(action_Final_LC_Request_sent_to_Bank, $allStatus) ){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` IN ('Insurance Cover Note','LC Opening Request') ";
    }
    else {
        $skipDraft = "";
    }
    if($shipno!=0){
        $shipSql = " AND (a2.`shipno` is null OR a2.`shipno` = $shipno) ";
    }

    $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` AS `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title`  AND a2.`poid` = '$id' $skipDraft $shipSql
             ORDER BY a2.`attachedon` DESC
             LIMIT 1)
        ORDER BY a.`attachedby`, a.`id`;";

    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data[0])) {
        foreach ($objdal->data as $val) {
            //extract($val);
            array_push($val, encryptId($val['id']));
            $attach[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }

    unset($objdal);

    if(in_array(action_Draft_LC_Request_sent_to_Bank, $allStatus) ||  in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
//    if($status >= action_LC_Request_Sent){
        return json_encode(array($podetail,$attach));
    } else {
        return json_encode(array($podetail,$attach));
    }
}

function GetLCattach($id, $forPI=0, $shipno=0)
{
    global $user_id;
    global $loginRole;
    global $companyId;

    $objdal = new dal();
    $status = 0;
    $allStatus = array();
    $shipSql = "";

    //$response = ["status" => 0, "message" => "Invalid request access-denied"];
    // get PO status collection
    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    unset($objdal->data);

    //echo $status;
    // message log
    $i=0;
    unset($objdal->data);

    // attachments
    if(in_array(action_Draft_LC_Copy_Sent_to_GP, $allStatus)){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` = 'Draft LC Copy' ";
    }
    elseif(in_array(action_Final_LC_Copy_Sent_to_GP, $allStatus) ){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` = 'Final LC Copy' ";
    }
    else {
        $skipDraft = "";
    }
    if($shipno!=0){
        $shipSql = " AND (a2.`shipno` is null OR a2.`shipno` = $shipno) ";
    }

    $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` AS `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title` AND (a2.`grouponly` IS null OR a2.`grouponly` = $loginRole)  AND a2.`poid` = '$id' $skipDraft $shipSql
             ORDER BY a2.`attachedon` DESC
             LIMIT 1)
        ORDER BY a.`attachedby`, a.`id`;";
    //echo $sql;
    $objdal->read($sql);
    if(!empty($objdal->data)) {
        foreach ($objdal->data as $val) {
            //extract($val);
            array_push($val, encryptId($val['id']));
            $attach[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }

    unset($objdal);

    if(in_array(action_Draft_LC_Request_sent_to_Bank, $allStatus) ||  in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
//    if($status >= action_LC_Request_Sent){
        return json_encode(array($attach));
    } else {
        return json_encode(array($attach));
    }
}

//FEEDBACK COMMENTS FOR BANK MODULE
function GetLCComments($id, $forPI=0, $shipno=0)
{
    global $user_id;
    global $loginRole;
    global $companyId;

    $objdal = new dal();
    $status = 0;
    $allStatus = array();
    $shipSql = "";

    //$response = ["status" => 0, "message" => "Invalid request access-denied"];
    // get PO status collection
    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    //echo $status;
    // message log
    $i=0;
    unset($objdal->data);
    if($shipno!=0){
        $shipSql = " AND (pl.`shipNo` is null OR pl.`shipNo` = $shipno) ";
    }
    $sql = "SELECT pl.`PO` AS `poid`, pl.`Msg` AS `title`, pl.`UserMsg` AS `msg`, pl.`ActionBy` `msgby`, 
            u.`username`, r.`name` AS `rolename`, pl.`ActionOn` AS `msgon`, pl.`ActionID` AS `status`,  
            pl.`ActionByRole` AS `fromgroup`, a.`ActionPendingTo` AS `togroup`, r1.`name` AS `torole`, a.`stage` 
            FROM `wc_t_action_log` as pl 
                INNER JOIN `wc_t_users` as u ON pl.`ActionBy` = u.`id` 
                INNER JOIN `wc_t_roles` as r ON u.`role` = r.`id`
                INNER JOIN `wc_t_action` as a ON pl.`ActionID` = a.`ID`
                LEFT JOIN `wc_t_roles` as r1 ON a.`ActionPendingTo` = r1.`id` 
            WHERE `PO` = '$id' $shipSql AND pl.`ActionID` IN(407,408) AND a.`stage`='LC' ORDER BY pl.`ID` DESC;";

    $objdal->getRow($sql);

    // echo is_null($objdal->data);
    $msg = (array) null;

    if(is_null($objdal->data)!=1){
        //echo 1;
        foreach($objdal->data as $val){
            $msg[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }
    unset($objdal);

    return json_encode($msg);
}

//FEEDBACK COMMENTS FOR BANK MODULE
function GetLCTFOComments($id, $forPI=0, $shipno=0)
{
    global $user_id;
    global $loginRole;
    global $companyId;

    $objdal = new dal();
    $status = 0;
    $allStatus = array();
    $shipSql = "";

    //$response = ["status" => 0, "message" => "Invalid request access-denied"];
    // get PO status collection
    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    //echo $status;
    // message log
    $i=0;
    unset($objdal->data);
    if($shipno!=0){
        $shipSql = " AND (pl.`shipNo` is null OR pl.`shipNo` = $shipno) ";
    }
    $sql = "SELECT pl.`PO` AS `poid`, pl.`Msg` AS `title`, pl.`UserMsg` AS `msg`, pl.`ActionBy` `msgby`, 
            u.`username`, r.`name` AS `rolename`, pl.`ActionOn` AS `msgon`, pl.`ActionID` AS `status`,  
            pl.`ActionByRole` AS `fromgroup`, a.`ActionPendingTo` AS `togroup`, r1.`name` AS `torole`, a.`stage` 
            FROM `wc_t_action_log` as pl 
                INNER JOIN `wc_t_users` as u ON pl.`ActionBy` = u.`id` 
                INNER JOIN `wc_t_roles` as r ON u.`role` = r.`id`
                INNER JOIN `wc_t_action` as a ON pl.`ActionID` = a.`ID`
                LEFT JOIN `wc_t_roles` as r1 ON a.`ActionPendingTo` = r1.`id` 
            WHERE `PO` = '$id' $shipSql AND pl.`ActionID` IN(407,408) AND a.`stage`='LC' ORDER BY pl.`ID` DESC;";
//    echo $sql;
//    ECHO 2;
    $objdal->read($sql);
    if(!empty($objdal->data)){
//        echo 1;
        foreach($objdal->data as $val){
            $msg[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }
    unset($objdal);

    return json_encode(array($msg));
}

function GetBankNotification(){

    $user_company = $_SESSION[session_prefix.'wclogin_company'];
    //$loginRole = $_SESSION[session_prefix.'wclogin_role'];
    $objdal = new dal();
    $query = "SELECT al.`ID`, al.`PO`, al.`shipNo`, al.`ActionID`, po.`povalue`, po.`podesc`, 
                ct.name as `Cur`, lc.`lcno`, lc.`lcdesc`, format(sh.`ciAmount`,2) `ciAmount`, sh.`ciDate`, sh.`ciNo`,
                format(lc.lcvalue, 2) `lcvalue`, date_format(lc.lcissuedate,'%d-%b-%Y') `lcdate`
            FROM wc_t_action_log al 
                INNER JOIN wc_t_pi po ON al.PO = po.poid 
                INNER JOIN wc_t_category ct ON po.currency = ct.id
                INNER JOIN wc_t_lc lc ON po.poid = lc.pono
                INNER JOIN wc_t_shipment sh ON (al.PO = sh.pono and al.shipNo = sh.shipNo)
            WHERE al.ActionID = ".action_Pre_Alert_To_Bank_for_Org_Doc." AND al.PendingToCo = $user_company AND al.`Status` = 0;";
//    echo $query;
    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $row['ID'] = encryptId($row['ID']);
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    $table_data = '{"data": ' . $json . '}';
    //return $table_data;
    return $table_data;

}

function SubmitODocReceiptNotification()
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
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $discStatus = htmlspecialchars($_POST['discStatus'],ENT_QUOTES, "ISO-8859-1");
    $discrepancyList = htmlspecialchars($_POST['discrepancyList'],ENT_QUOTES, "ISO-8859-1");
//    $bankNotifyDate = htmlspecialchars($_POST['bankNotifyDate'],ENT_QUOTES, "ISO-8859-1");
//    $bankNotifyDate = date('Y-m-d', strtotime($bankNotifyDate));

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
        `banknotifydate` = NOW(),
        `submittedby` = $user_id,
        `submittedfrom` = '$ip'";

    $objdal->insert($query);

    $attachOriginalDoc = htmlspecialchars($_POST['attachOriginalDoc'],ENT_QUOTES, "ISO-8859-1");
    $attachOriginalDocOld = htmlspecialchars($_POST['attachOriginalDocOld'],ENT_QUOTES, "ISO-8859-1");

    if($attachOriginalDoc!=''){
        if($attachOriginalDocOld==''){
            $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`) VALUES 
                ('$PoNo', 'Original Bank Document', '$attachOriginalDoc', $user_id, '$ip', $loginRole, '$LcNo', $shipno);";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachOriginalDoc',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$PoNo' AND `title`='Original Bank Document' AND `filename` = '$attachOriginalDocOld'";
            $objdal->update($query);
        }
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($PoNo);
    }
    //echo('success 1');
    //$objdal->LastInsertId();
    if($discStatus==1) {
        $userMsg = "Discrepancy Status: YES\n";
    }else{
        $userMsg = "Discrepancy Status: NO\n";
    }

    /*if($docType=="endorse"){
        $newAction = action_Sent_for_Document_Endorsement;
        $msg = "'Request for endorsed document against PO# ".$pono." and Shipment # ".$shipno."'";
    } elseif($docType=="original"){
        $newAction = action_Requested_to_Collect_Original_Doc;
        $msg = "'Request for original document against PO# ".$pono." and Shipment # ".$shipno."'";
    }*/
    $msg = "'Notification of original document receive against PO# ".$PoNo." and Shipment # ".$shipNo."'";
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$PoNo."'",
        'shipno' => $shipNo,
        'actionid' => action_Requested_to_Collect_Original_Doc,
        'status' => 1,
        'msg' => $msg,
        'usermsg' => "'".$userMsg." ".$discrepancyList."'",
    );
//    var_dump($action);
    UpdateAction($action);
//    echo("success 2");
    // End Action Log -----------------------------

    /*// Action Log --------------------------------//
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
    // End Action Log -----------------------------*/

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Request submitted successfully!';
    return json_encode($res);

}

function GetFinFXSettlePending() {

    $objdal = new dal();
    $strQuery = "SELECT 
            f.`id`,
            f.`pono`,
            f.`shipment`,
            c1.name AS `docname`,
            CONCAT(f.`percentage`, '%') `percentage`,
            FORMAT(f.`amount`, 2) `amount`,
            FORMAT(f.`ciamount`, 2) `ciamount`,
            f.`lcno`,
            f.`status`,
            c2.`name` AS `currency`,
            co.`name` `lcbankaddress`
        FROM
            `fx_settelment_pending_fn` f
                INNER JOIN
            `wc_t_pi` po ON f.`pono` = po.`poid`
                INNER JOIN
            `wc_t_category` c1 ON f.`partname` = c1.id
                INNER JOIN
            `wc_t_category` c2 ON po.`currency` = c2.`id`
                INNER JOIN
            `wc_t_lc` lc ON po.`poid` = lc.`pono`
                INNER JOIN
            `wc_t_company` co ON lc.`lcissuerbank` = co.`id`
        WHERE f.`status` = 0;";

    $objdal->read($strQuery);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    $table_data = '{"data": ' . $json . '}';
    //return $table_data;
    return $table_data;

}

