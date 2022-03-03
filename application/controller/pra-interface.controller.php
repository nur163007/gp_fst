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
            echo GetIndex($_GET["my"]);
            break;
        case 2:	// delete pra request
            if(!empty($_GET["id"])) { echo DeleteCA($_GET["id"]); } else { echo 0; };
            break;
        case 3:
            echo GetAllPRA();
            break;
        case 4:	// delete btrc request
            if(!empty($_GET["id"])) { echo DeleteBtrc($_GET["id"]); } else { echo 0; };
            break;
        case 5:
            echo BtrcSubmit();
            break;
        case 6:
            echo GetAttach($_GET["cref"]);
            break;
        case 7:
            echo GetPolineData($_GET["ref"]);
            break;
        case 8:
            echo GetLetterSL($_GET["ref"]);
            break;
        default:
            break;
    }
}


/**
 * @param $my
 */
function GetIndex($my){
    $objdal = new dal();
        if($my=='true') {
            $query = "SELECT ca.`id`,ca.`ca_ref` AS `pra_ref`, 
                        (select count(ca1.po_no) from ca_activity_table ca1 where ca1.ca_ref = ca.`ca_ref` and status = 1) poCount,
                        ca.`po_no` AS `pono`,ca.`action_log_ref` AS `actionRef`,ca.`btrc_division`,
                        (select wc.name from wc_t_category wc where wc.id = ca.btrc_division) division,
                        pi.`pinum`, DATE_FORMAT(pi.`pidate`, '%d-%b-%Y') `pidate`, format(pi.`pivalue`,2) `pivalue`,
                        (select concat(a.id,',',a.filename) from `wc_t_attachments` a where a.poid = ca.po_no and a.title = 'Buyers PO' order by id desc limit 1) `PO`,
                        (select LOWER(SUBSTRING(a1.`filename`, LENGTH(a1.`filename`)-(INSTR(REVERSE(a1.`filename`), '.')-2))) from `wc_t_attachments` a1 where a1.poid = ca.po_no and a1.title = 'Buyers PO' order by id desc limit 1) `extpo`,
                        (select concat(a2.id,',',a2.filename) from `wc_t_attachments` a2 where a2.poid = ca.po_no and a2.title = 'Buyers BOQ' order by id desc limit 1) `BOQ`,
                        (select LOWER(SUBSTRING(a3.`filename`, LENGTH(a3.`filename`)-(INSTR(REVERSE(a3.`filename`), '.')-2))) from `wc_t_attachments` a3 where a3.poid = ca.po_no and a3.title = 'Buyers BOQ' order by id desc limit 1) `extboq`,
                        (select concat(a4.id,',',a4.filename) from `wc_t_attachments` a4 where a4.poid = ca.po_no and a4.title = 'Justification' order by id desc limit 1) `Justification`,
                        (select LOWER(SUBSTRING(a5.`filename`, LENGTH(a5.`filename`)-(INSTR(REVERSE(a5.`filename`), '.')-2))) from `wc_t_attachments` a5 where a5.poid = ca.po_no and a5.title = 'Justification' order by id desc limit 1) `extjust`,
                        (select concat(a6.id,',',a6.filename) from `wc_t_attachments` a6 where a6.poid = ca.po_no and a6.title = 'Suppliers Catalog' order by id desc limit 1) `Catalog`,
                        (select LOWER(SUBSTRING(a7.`filename`, LENGTH(a7.`filename`)-(INSTR(REVERSE(a7.`filename`), '.')-2))) from `wc_t_attachments` a7 where a7.poid = ca.po_no and a7.title = 'Suppliers Catalog' order by id desc limit 1) `extcat`
                        from `ca_activity_table` ca inner join wc_t_pi pi on ca.po_no = pi.poid
                        where ca.status = 1;";
        }
            else {
                $query = "SELECT ca.`id`,ca.`ca_ref` AS `pra_ref`,(select count(ca1.po_no) from ca_activity_table ca1 where ca1.ca_ref = ca.`ca_ref` and status = 0) poCount,
                        ca.`po_no` AS `pono`,ca.`action_log_ref` AS `actionRef`,ca.`btrc_division`,
                        (select wc.name from wc_t_category wc where wc.id = ca.btrc_division) division
                        from `ca_activity_table` ca inner join wc_t_pi pi on ca.po_no = pi.poid
                        where ca.status = 0;";

            }

    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $rows[] = $row;
        }
    }

    unset($objdal->data);

    $json = json_encode($rows);
    echo $json;
/*    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    $table_data = '{"data": ' . $json . '}';

            echo $table_data;*/

}

//get attachment
//function GetAttach($po){
//    var_dump($po);
//}
//reject CA

function DeleteCA($id)
{
    global $loginRole;
    $messageUser = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");
//    var_dump($messageUser);
//    exit();
        $objdal = new dal();
        $query = "UPDATE `ca_activity_table` SET `status`=-1  WHERE `id` = $id;";
        $objdal->update($query);
        unset($objdal->data);

        $refId = "SELECT `action_log_ref` from `ca_activity_table` where `id`=$id;";
        $objdal->read($refId);

        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }
         unset($objdal->data);
         $actionID = $res["action_log_ref"];

        $allInfo = "SELECT 
                      `ID`, `ActionID`,`PO`
                        FROM `wc_t_action_log`
                       /* LEFT JOIN `wc_t_action` a ON w.`ActionID` = a.`ID` */
                     WHERE 
                        `RefID` = $actionID;";
        $objdal->read($allInfo);

        $row = '';
        if (!empty($objdal->data)) {
            $row = $objdal->data[0];
            extract($row);
        }

//    var_dump($res);
    unset($objdal->data);

    $refID = $row["ID"];
    $pID = $row["PO"];

    if($loginRole==role_public_regulatory_affairs) {
    $action = array(
        'refid' => $refID,
        'pono' => "'" . $pID . "'",
        'actionid' => action_Rejected_by_PRA,
        'status' => -1,
        'msg' => "'PRA rejected against PO #" . $pID . "'",
        'usermsg' => "'".$messageUser."'",
    );
    }
    UpdateAction($action);

        unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Rejected Successfully';
    echo json_encode($res);
}

function DeleteBtrc($id)
{
    global $loginRole;
    $messageUser = htmlspecialchars($_POST['remarks1'],ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();
    $query = "UPDATE `ca_activity_table` SET `status`= -2  WHERE `id` = $id;";
    $objdal->update($query);
    unset($objdal->data);

    $refId = "SELECT `action_log_ref` from `ca_activity_table` where `id`=$id;";
    $objdal->read($refId);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal->data);
    $actionID = $res["action_log_ref"];


    $allInfo = "SELECT 
                      `ID`, `ActionID`,`PO`
                        FROM `wc_t_action_log`
                       /* LEFT JOIN `wc_t_action` a ON w.`ActionID` = a.`ID` */
                     WHERE 
                        `RefID` = $actionID;";
    $objdal->read($allInfo);

    $row = '';
    if (!empty($objdal->data)) {
        $row = $objdal->data[0];
        extract($row);
    }

//    var_dump($res);
    unset($objdal->data);

    $refID = $row["ID"];
    $pID = $row["PO"];

    if($loginRole==role_public_regulatory_affairs) {
        $action = array(
            'refid' => $refID,
            'pono' => "'" . $pID . "'",
            'actionid' => action_Rejected_by_BTRC,
            'status' => -1,
            'msg' => "'BTRC rejected against PO #" . $pID . "'",
            'usermsg' => "'".$messageUser."'",
        );
    }
    UpdateAction($action);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Rejected Successfully';
    echo json_encode($res);
}

function GetAllPRA(){

    /*var_dump($_POST);
    exit();*/

    $post_data = $_POST;
    $info = $post_data["val"];

    if (isset($info)){

        foreach ($info as $taRefData){
            PRAStore($taRefData);
        }

        $res["status"] = 1;
        $res["message"] = 'Request sent to BTRC.';

        echo json_encode($res,true);
    }

}
function PRAStore($taRefData)
{
    global $user_id;
    global $loginRole;

    $vals = explode("|", $taRefData);

    $refId = $vals[0];
    $attachDoc = $vals[1];
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();

    $allInfo = "SELECT `ID`, `ActionID`,`PO` FROM `wc_t_action_log` WHERE `RefID` = $refId;";
    $objdal->read($allInfo);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal->data);

    $cID = $res["ID"];
    $pID = $res["PO"];

    $checkdata = "SELECT `ca_ref`,`btrc_division`,`action_log_ref` from `ca_activity_table` where `action_log_ref`=$refId and `status` =1;";
    $objdal->read($checkdata);

    $row = '';
    if (!empty($objdal->data)) {
        $row = $objdal->data[0];
        extract($row);
    }
    unset($objdal->data);

    $cref = $row["ca_ref"];
    $division = $row["btrc_division"];
    $actionId = $row["action_log_ref"];

    $sql = "INSERT INTO `ca_activity_table` SET 
            `ca_ref` = $cref, 
            `po_no` = '$pID',
            `btrc_division` = $division,
            `action_log_ref` = $cID,
            `status` = 0;";
    $objdal->insert($sql, "Could not submit CA data");

    $update = "UPDATE `ca_activity_table` 
            SET `status` = 2
            WHERE `action_log_ref` = $refId;";
    $objdal->update($update, "Could not submit CA data");
/*
    if($division==btrc_division_ENO) {
        $doc = explode(",", $attachDoc);
        $sqlAttach = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$pID', 'Application E&O', '$doc[0]', $user_id, '$ip', $loginRole),
            ('$pID', 'Equipment List and Description', '$doc[1]', $user_id, '$ip', $loginRole)";
    }
    if($division==btrc_division_SM) {
        $doc = explode(",", $attachDoc);
        $sqlAttach = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$pID', 'Application Spectrum File', '$doc[0]', $user_id, '$ip', $loginRole),
            ('$pID', 'Equipment List and Description', '$doc[1]', $user_id, '$ip', $loginRole),
            ('$pID', 'Equipments Quantity', '$doc[2]', $user_id, '$ip', $loginRole)";
    }
    $objdal->insert($sqlAttach, "Failed to save attachments");
    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pID, 1);
*/
    unset($objdal);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $cID,
        'pono' => "'" . $pID . "'",
        'actionid' => action_Sent_to_BTRC_for_NOC,
        'status' => 1,
        'msg' => "'Request Sent to BTRC for NOC against PO#" . $pID . "'",
    );
    UpdateAction($action);

}

function BtrcSubmit(){

    $post_data = $_POST;
    $info = $post_data["val"];
    $attach = $post_data["attachment"];
    if (isset($info)){
        foreach ($info as $i){
            BtrcStore($i,$attach);
        }

        $res["status"] = 1;
        $res["message"] = 'BTRC NOC Received.';

        echo json_encode($res,true);
    }
}

function BtrcStore($id,$attach){

    global $loginRole;
    $user_id = $_SESSION[session_prefix.'wclogin_userid'];
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();
    $allInfo = "SELECT al.`ID`, al.`ActionID`,al.`PO`, pi.`shipmode`
                FROM `wc_t_action_log` al INNER JOIN `wc_t_pi` pi ON al.`PO` = pi.`poid`
			    WHERE al.`RefID` = $id;";
    $objdal->read($allInfo);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }

    unset($objdal->data);
    $cID = $res["ID"];
    $pID = $res["PO"];

    // Action Log --------------------------------//
    $action = array(
        'refid' => $cID,
        'pono' => "'".$pID."'",
        'actionid' => action_Accepted_by_BTRC,
        'status' => 1,
        'msg' => "'BTRC NOC received for PO#".$pID."'",
    );
    UpdateAction($action);

    if($loginRole==role_public_regulatory_affairs && $attach!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES
            ('$pID', 'BTRC NOC', '$attach', $user_id, '$ip', $loginRole);";

        $objdal->insert($query);
        fileTransferTempToDocs($pID, 1);
    }
    unset($objdal->data);
    // End Action Log -----------------------------

    $checkdata = "SELECT `ca_ref`,`btrc_division`,`action_log_ref` from `ca_activity_table` where `action_log_ref`=$id and `status` =0;";
    $objdal->read($checkdata);

    $row = '';
    if (!empty($objdal->data)) {
        $row = $objdal->data[0];
        extract($row);
    }
    unset($objdal->data);
    $cref = $row["ca_ref"];
    $division = $row["btrc_division"];
    $actionId = $row["action_log_ref"];


    $sql = "INSERT INTO `ca_activity_table` SET 
            `ca_ref` = $cref, 
            `po_no` = '$pID',
            `btrc_division` = $division,
            `action_log_ref` = $cID,
            `status` = 3;";
    $objdal->insert($sql, "Could not submit CA data");

    unset($objdal->data);
//    echo $query;
//    die();

    $update = "UPDATE `ca_activity_table` SET
            `status` = -3
            where `action_log_ref` = $id;";
    $objdal->update($update, "Could not submit CA data");


    unset($objdal);
}

function GetAttach($cref){
//    var_dump($status);
    $objdal = new dal();
    if ($cref){
        $query = "SELECT ca.`id`,ca.`ca_ref` AS `pra_ref`, (select count(ca1.po_no) from ca_activity_table ca1 where ca1.ca_ref = ca.`ca_ref`) poCount,
                        ca.`po_no` AS `pono`,ca.`action_log_ref` AS `actionRef`,ca.`btrc_division`,
                        (select wc.name from wc_t_category wc where wc.id = ca.btrc_division) division,
                        (select concat(a.id,',',a.filename) from `wc_t_attachments` a where a.poid = ca.po_no and a.title = 'PO' order by id desc limit 1) `PO`,
                        (select a8.title from `wc_t_attachments` a8 where a8.poid = ca.po_no and a8.title = 'PO' order by id desc limit 1) `titlePO`,
                        (select LOWER(SUBSTRING(a1.`filename`, LENGTH(a1.`filename`)-(INSTR(REVERSE(a1.`filename`), '.')-2))) from `wc_t_attachments` a1 where a1.poid = ca.po_no and a1.title = 'PO' order by id desc limit 1) `extpo`,
                        (select concat(a2.id,',',a2.filename) from `wc_t_attachments` a2 where a2.poid = ca.po_no and a2.title = 'BOQ' order by id desc limit 1) `BOQ`,
                        (select a9.title from `wc_t_attachments` a9 where a9.poid = ca.po_no and a9.title = 'BOQ' order by id desc limit 1) `titleBOQ`,
                        (select LOWER(SUBSTRING(a3.`filename`, LENGTH(a3.`filename`)-(INSTR(REVERSE(a3.`filename`), '.')-2))) from `wc_t_attachments` a3 where a3.poid = ca.po_no and a3.title = 'BOQ' order by id desc limit 1) `extboq`,
                        (select concat(a4.id,',',a4.filename) from `wc_t_attachments` a4 where a4.poid = ca.po_no and a4.title = 'Justification' order by id desc limit 1) `Justification`,
                        (select a10.title from `wc_t_attachments` a10 where a10.poid = ca.po_no and a10.title = 'Justification' order by id desc limit 1) `titleJust`,
                        (select LOWER(SUBSTRING(a5.`filename`, LENGTH(a5.`filename`)-(INSTR(REVERSE(a5.`filename`), '.')-2))) from `wc_t_attachments` a5 where a5.poid = ca.po_no and a5.title = 'Justification' order by id desc limit 1) `extjust`,
                        (select concat(a6.id,',',a6.filename) from `wc_t_attachments` a6 where a6.poid = ca.po_no and a6.title = 'Suppliers Catalog' order by id desc limit 1) `Catalog`,
                        (select a11.title from `wc_t_attachments` a11 where a11.poid = ca.po_no and a11.title = 'Suppliers Catalog' order by id desc limit 1) `titleCat`,
                        (select LOWER(SUBSTRING(a7.`filename`, LENGTH(a7.`filename`)-(INSTR(REVERSE(a7.`filename`), '.')-2))) from `wc_t_attachments` a7 where a7.poid = ca.po_no and a7.title = 'Suppliers Catalog' order by id desc limit 1) `extcat`
                        from `ca_activity_table` ca
                        where ca.ca_ref = $cref;";
        $objdal->read($query);

        $rows = array();
        if (!empty($objdal->data)) {
            foreach ($objdal->data as $row) {
                $rows[] = $row;
            }
        }

        unset($objdal->data);

        $json = json_encode($rows);
        echo $json;
    }
}

function GetPolineData($ref){
//    var_dump($ref);
//    exit();
    $objdal = new dal();
    if ($ref){
        $query = "SELECT pl.`poNo`, pl.`itemDesc`,pl.`poQty`,pl.`poTotal`,po.`user_justification` as `justification`,
                    po.`pinum`,DATE_FORMAT(po.`pidate`, '%d-%b, %Y') `pidate`,co.`name` as `supplier`,cu.`name` as `currency`
                FROM `ca_activity_table` ca
                LEFT JOIN `pi_lines` pl ON ca.`po_no` = pl.`poNo` 
                LEFT JOIN `wc_t_pi` po ON pl.`poNo` = po.`poid` 
                LEFT JOIN `wc_t_company` co ON po.`supplier` = co.`id` 
                LEFT JOIN `wc_t_category` cu ON po.`currency` = cu.`id` 
                WHERE ca.btrc_division = $ref AND ca.`status` = 1;";
        $objdal->read($query);

        $rows = array();
        if (!empty($objdal->data)) {
            foreach ($objdal->data as $row) {
                $rows[] = $row;
            }
        }

        unset($objdal->data);

        $json = json_encode($rows);
        echo $json;
    }
}

function GetLetterSL($ref){
//    var_dump($ref);
//    exit();
    $objdal = new dal();
    if ($ref){
        $query = "SELECT COUNT(`ca_ref`) as `SL` FROM ca_activity_table
                WHERE
                `btrc_division` = $ref and status=1;";
        $objdal->read($query);

        $serial = 1;
        $objdal->read($query);
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
            $serial = $SL;
        }
        return $serial;
    }
}
?>
