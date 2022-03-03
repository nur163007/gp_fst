<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// get all fx request info
            echo GetAllCnReq();
            break;
        case 2:	// edit cn request info
            if (!empty($_GET["id"])) {
                echo GetCnInfoByRequestId($_GET["id"]);
            }
            break;
        case 3:	// delete cn request
            if(!empty($_GET["id"])) {
                echo DeleteCn($_GET["id"]);
            } else { echo 0; };
            break;
        case 4:	// delete cn request
            if(!empty($_GET["poid"])) {
                echo GetCNInfoByPO($_GET["poid"]);
            };
            break;
        default:
            break;
    }
}

//SUBMIT CN REQUEST
if (!empty($_POST)){
    echo submitCN();
}

//CN REQUEST INSERT

function submitCN(){

    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $cnId = $objdal->sanitizeInput($_POST['cnId']);
    $cn_no = $objdal->sanitizeInput($_POST['cn_number']);
    $cn_date = $objdal->sanitizeInput($_POST['cn_date']);
    $cn_date = date('Y-m-d', strtotime($cn_date));
    $pay_order_amount = $objdal->sanitizeInput($_POST['pay_order_amount']);
    $pay_order_amount = str_replace(",","", $pay_order_amount);
    $pay_order_charge = $objdal->sanitizeInput($_POST['pay_order_charge']);
    $pay_order_charge = str_replace(",","", $pay_order_charge);
    $po_no = '300029935PI1';
    $ship_no = 1;

    // attachment data in an 3D array
    $attachcn = $objdal->sanitizeInput($_POST['attachcn']);
    $attachporc = $objdal->sanitizeInput($_POST['attachporc']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);

    $ip = $_SERVER['REMOTE_ADDR'];

    if ($cnId == ""){
        // insert new cn
        $query = "INSERT INTO `cn_request` SET 
            `cn_no` = '$cn_no', 
            `cn_date` = '$cn_date',
            `pay_order_amount` = $pay_order_amount, 
            `pay_order_charge` = $pay_order_charge, 
            `created_by` = $user_id,
            `po_no` ='$po_no',
            `ship_no` =$ship_no;";
//    echo $query;
//    die();
        $objdal->insert($query, "Could not submit CN Request data");
    }
    else{
        // updated exist cn
        $query = "UPDATE `cn_request` SET
            `cn_no` = '$cn_no',
            `cn_date` = '$cn_date',
            `pay_order_amount` = $pay_order_amount,
            `pay_order_charge` = $pay_order_charge,
            `created_by` = $user_id,
            `po_no` ='$po_no',
            `ship_no` =$ship_no
            where `id`=$cnId;";
//    echo $query;
//    die();
        $objdal->update($query, "Could not updated CN Request data");
    }

    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$po_no', 'CN Copy', '$attachcn', $user_id, '$ip', $loginRole),
        ('$po_no', 'Pay Order Receive Copy', '$attachporc', $user_id, '$ip', $loginRole)";

    if($attachother!=''){
        $query .= ",('$po_no', 'CN Other Docs', '$attachother', $user_id, '$ip', $loginRole);";
    }
    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($po_no);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'CN Submitted Successfully';
    return json_encode($res);
}

function GetAllCnReq()
{
    global $loginRole;
    if ($loginRole == role_Admin) {
        $objdal = new dal();
        $strQuery = "SELECT cn.`id`,cn.`po_no`,cn.`cn_no`,DATE_FORMAT(cn.`cn_date`, '%d-%M-%Y')AS `cn_date`,FORMAT(cn.`pay_order_amount`, 2) AS pay_order_amount,FORMAT(cn.`pay_order_charge`, 2) AS pay_order_charge,u.`firstname` as created_by
                     FROM `cn_request` cn 
                     LEFT JOIN `wc_t_users` u ON cn.`created_by` = u.`id` 
                     where cn.`status`= 1
                     ORDER BY cn.`id` ASC;";
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
    } else {
        $json = "[]";
        $table_data = '{"data": ' . $json . '}';
        //return $table_data;
        return $table_data;
    }
}

//edit cn request
function GetCnInfoByRequestId($id){
    $objdal = new dal();
    $query = "SELECT 
			       `id`, `po_no`, `cn_no`,DATE_FORMAT(`cn_date`, '%d-%M-%Y')AS `cn_date`,FORMAT(`pay_order_amount`, 2) AS pay_order_amount, FORMAT(`pay_order_charge`, 2) AS pay_order_charge
			  FROM `cn_request`
              WHERE 
                   `id` = $id;";

    $objdal->read($query);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }

    unset($objdal->data);
    $po_no = $res["po_no"];

    $attachments = [];

    //$cnDocs = 'CN Copy', 'Pay Order Receive Copy', 'CN Other Docs';
    $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`,
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
        WHERE a.`poid` = '$po_no' AND a.`title` IN('CN Copy', 'Pay Order Receive Copy', 'CN Other Docs')
        GROUP BY a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, `ext`
        ORDER BY a.`id` ASC
        limit 3;";
//    echo $sql;
//    die();
    $objdal->read($sql);

    if(!empty($objdal->data)) {
        $i = 0;
        foreach ($objdal->data as $val) {
            //extract($val);
            array_push($val, encryptId($val['id']));
            $attachments[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }
    unset($objdal);
    $cn_copy =$attachments[0]['filename'];
    $porc = $attachments[1]['filename'];
    $cod = $attachments[2]['filename'];
//    echo $cn_copy;
//    echo $porc;
//    echo $cod;
//    die();

    return json_encode([$res, $attachments,$cn_copy,$porc,$cod]);
}

function GetCNInfoByPO($poid)
{
    $objdal = new dal();
    $query = "SELECT 
			       `id`, `po_no`, 
			       `cn_no`,
			       `cn_date`,
			       FORMAT(`pay_order_amount`, 2) AS pay_order_amount, 
			       FORMAT(`pay_order_charge`, 2) AS pay_order_charge,
                   (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$poid' AND `title`='Insurance Cover Note' ORDER BY ID DESC limit 1) `attachInsCoverNote`,
                   (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$poid' AND `title`='Pay Order Receive Copy' ORDER BY ID DESC limit 1) `attachPayOrderReceivedCopy`,
                   (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$poid' AND `title`='Insurance Other Doc' ORDER BY ID DESC limit 1) `attachInsChargeOther`
			  FROM `cn_request`
              WHERE 
                   `po_no` = '$poid';";
//    echo $query;
    $cnInfo = $objdal->read($query);
    return json_encode($cnInfo);
}

//Delete fx request
function DeleteCn($id)
{
    global $loginRole;
    if ($loginRole == 1) {
        $objdal = new dal();
        $query = "UPDATE `cn_request` SET `status`=-1  WHERE `id` = $id;";
        $objdal->update($query);
        unset($objdal);
        return 1;
    } else {
        return "Invalid request";
    }
}
?>
