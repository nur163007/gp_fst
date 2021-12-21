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
        case 2:	// get a purchase order
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetCNDetail($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetCNDetail($_GET["id"]);
            }
            break;
        default:
            break;
    }
}

//SUBMIT CN REQUEST
if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
    switch($_POST["userAction"]) {
        case 1:
            echo SendCNToGP();
            break;
        case 2:
            echo submitCN();
            break;
        case 3:
            echo submitInsPolicy();
            break;
    }

}

function SendCNToGP(){

    $refId = decryptId($_POST["refId1"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();

    $po = $objdal->sanitizeInput($_POST['po']);
    $cn_no = $objdal->sanitizeInput($_POST['cn_number']);
    $cn_date = $objdal->sanitizeInput($_POST['cn_date']);
    $cn_date = date('Y-m-d', strtotime($cn_date));
    $pay_order_amount = $objdal->sanitizeInput($_POST['pay_order_amount']);
    $pay_order_amount = str_replace(",","", $pay_order_amount);

    // attachment data in an 3D array
    $attachcn = $objdal->sanitizeInput($_POST['attachcn']);
    $attachcnOld = $objdal->sanitizeInput($_POST['attachcnOld']);
    $attachporc = $objdal->sanitizeInput($_POST['attachporc']);
    $attachporcOLD = $objdal->sanitizeInput($_POST['attachporcOLD']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);
    $attachotherOLD = $objdal->sanitizeInput($_POST['attachotherOLD']);

    $ip = $_SERVER['REMOTE_ADDR'];

        // updated exist cn
        $query = "UPDATE `cn_request` SET
            `cn_no` = '$cn_no',
            `cn_date` = '$cn_date',
            `pay_order_amount` = $pay_order_amount
            where `po_no`='$po';";
//    echo $query;
//    die();
        $objdal->update($query, "Could not updated CN Request data");

    // insert attachment
    $res["message"] = 'Failed to save attachments!';


    if($attachcn!=''){
        if($attachcnOld==''){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Insurance Cover Note', '$attachcn', $user_id, '$ip', $loginRole)";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachcn',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$po' AND `title`='Insurance Cover Note' AND `filename` = '$attachcnOld'";
            $objdal->update($query);
        }
    }

    if($attachporc!=''){
        if($attachporcOLD==''){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Pay Order Receive Copy', '$attachporc', $user_id, '$ip', $loginRole)";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachporc',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$po' AND `title`='Pay Order Receive Copy' AND `filename` = '$attachporcOLD'";
            $objdal->update($query);
        }
    }

    if($attachother!=''){
        if($attachotherOLD==''){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Insurance Other Doc', '$attachother', $user_id, '$ip', $loginRole)";
            $objdal->insert($query);
        } else {
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachother',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$po' AND `title`='Insurance Other Doc' AND `filename` = '$attachotherOLD'";
            $objdal->update($query);
        }
    }

//
//    if($attachporc!=''){
//        $query .= ",('$po', 'Pay Order Receive Copy', '$attachporc', $user_id, '$ip', $loginRole);";
//    }
//    if($attachother!=''){
//        $query .= ",('$po', 'Insurance Other Doc', '$attachother', $user_id, '$ip', $loginRole);";
//    }
//    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($po);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'CN Submitted Successfully';
    return json_encode($res);
}

function submitCN(){
    $refId = decryptId($_POST["refId1"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['po']);
    $cn_no = $objdal->sanitizeInput($_POST['cn_number']);
    $cn_date = $objdal->sanitizeInput($_POST['cn_date']);
    $cn_date = date('Y-m-d', strtotime($cn_date));
    $pay_order_amount = $objdal->sanitizeInput($_POST['pay_order_amount']);
    $pay_order_amount = str_replace(",","", $pay_order_amount);
    $pay_order_charge = $objdal->sanitizeInput($_POST['pay_order_charge']);
    $pay_order_charge = str_replace(",","", $pay_order_charge);

    // attachment data in an 3D array
    $attachcn = $objdal->sanitizeInput($_POST['attachcn']);
    $attachporc = $objdal->sanitizeInput($_POST['attachporc']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);

    $ip = $_SERVER['REMOTE_ADDR'];

        // updated exist cn
        $query = "UPDATE `cn_request` SET
            `cn_no` = '$cn_no',
            `cn_date` = '$cn_date',
            `pay_order_amount` = $pay_order_amount,
            `pay_order_charge` = $pay_order_charge,
            `created_by` = $user_id
            where `po_no`='$po';";
//    echo $query;
//    die();
        $objdal->update($query, "Could not updated CN Request data");

    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Insurance Cover Note', '$attachcn', $user_id, '$ip', $loginRole),
        ('$po', 'Pay Order Receive Copy', '$attachporc', $user_id, '$ip', $loginRole)";

    if($attachother!=''){
        $query .= ",('$po', 'Insurance Other Doc', '$attachother', $user_id, '$ip', $loginRole);";
    }
    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($po);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$po."'",
        'actionid' => action_CN_Issued_by_IC,
        'status' => 1,
        'msg' => "'Cover Note Accepted against PO# ".$po."'",
    );
    UpdateAction($action);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'CN Submitted Successfully';
    return json_encode($res);
}

function submitInsPolicy(){
    $refId = decryptId($_POST["refId1"]);
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $po = $objdal->sanitizeInput($_POST['pono']);

    // attachment data in an 3D array
    $attachIcFile = $objdal->sanitizeInput($_POST['attachIcFile']);

    $ip = $_SERVER['REMOTE_ADDR'];

    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po', 'Insurance Policy', '$attachIcFile', $user_id, '$ip', $loginRole)";


    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($po);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$po."'",
        'actionid' => action_Ins_Policy_sent_by_IC,
        'status' => 1,
        'msg' => "'Insurace Policy Accepted against PO# ".$po."'",
    );
    UpdateAction($action);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Accept insurance policy';
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
        $strQuery = $objdal->getRow("SELECT `supplier` FROM `wc_t_po` WHERE `poid` = '$id';");
        $supplier = $strQuery['supplier'];
        if ($supplier != $companyId) {
            $response = ["status" => 0, "message" => "Invalid request"];
            unset($objdal->data);
            //return json_encode($response);
            die();
        }
    }


    if($forPI==1){

        $sql = "SELECT `poid` FROM `wc_t_po` WHERE `poid` LIKE '".$id."%' ORDER BY `createdon` desc LIMIT 1;";
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
            i.`name` AS `insurancebank`, cn.`cn_no`, cn.`cn_date`,cn.`pay_order_amount`,
            (SELECT a1.`filename` FROM `wc_t_attachments` a1 WHERE a1.`poid`='$id' AND a1.`title`='Insurance Cover Note' ORDER BY a1.`id` DESC LIMIT 1) AS `attachCNCopy`,
            (SELECT a1.`filename` FROM `wc_t_attachments` a1 WHERE a1.`poid`='$id' AND a1.`title`='Pay Order Receive Copy' ORDER BY a1.`id` DESC LIMIT 1) AS `attachPORC`,
            (SELECT a1.`filename` FROM `wc_t_attachments` a1 WHERE a1.`poid`='$id' AND a1.`title`='Insurance Other Doc' ORDER BY a1.`id` DESC LIMIT 1) AS `attachIOD`
            FROM `wc_t_po` p 
                INNER JOIN `wc_t_category` c1 ON p.`currency` = c1.`id` 
                INNER JOIN `wc_t_company` c2 ON p.`supplier` = c2.`id`
                LEFT JOIN `wc_t_lc` l ON p.`poid` = l.`pono`
                LEFT JOIN `wc_t_company` i ON l.`insurance` = i.`id`
                LEFT JOIN `cn_request` cn ON cn.`po_no` = p.`poid`
                
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
    if(in_array(action_Final_PI_Sent_for_PR_Feedback, $allStatus)){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` IN('CI Scan Copy','CN Copy','AWB/BL Scan Copy') ";
    } else {
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
             WHERE a2.`title` = a.`title` AND (a2.`grouponly` IS null OR a2.`grouponly` = $loginRole) AND a2.`poid` = '$id' $skipDraft $shipSql
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

    if(in_array(action_LC_Request_Sent, $allStatus) ||  in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
//    if($status >= action_LC_Request_Sent){
        return json_encode(array($podetail,$attach));
    } else {
        return json_encode(array($podetail,$attach));
    }
}

function GetCNDetail($id, $forPI=0, $shipno=0)
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
        $strQuery = $objdal->getRow("SELECT `supplier` FROM `wc_t_po` WHERE `poid` = '$id';");
        $supplier = $strQuery['supplier'];
        if ($supplier != $companyId) {
            $response = ["status" => 0, "message" => "Invalid request"];
            unset($objdal->data);
            //return json_encode($response);
            die();
        }
    }


    if($forPI==1){

        $sql = "SELECT `poid` FROM `wc_t_po` WHERE `poid` LIKE '".$id."%' ORDER BY `createdon` desc LIMIT 1;";
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

    $sql = "SELECT `cn_no`,`cn_date`,`pay_order_amount`,`pay_order_charge` from `cn_request`
            WHERE `po_no` = '$id';";
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
    if(in_array(action_Final_PI_Sent_for_PR_Feedback, $allStatus)){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` IN ('Insurance Cover Note','Pay Order Receive Copy','Insurance Other Doc') ";
    } else {
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
             WHERE a2.`title` = a.`title` AND (a2.`grouponly` IS null OR a2.`grouponly` = $loginRole) AND a2.`poid` = '$id' $skipDraft $shipSql
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

    if(in_array(action_LC_Request_Sent, $allStatus) ||  in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
//    if($status >= action_LC_Request_Sent){
        return json_encode(array($podetail,$attach));
    } else {
        return json_encode(array($podetail,$attach));
    }
}
