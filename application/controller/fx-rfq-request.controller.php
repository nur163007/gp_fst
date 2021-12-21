<?php
if (!session_id()) {
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

if (!empty($_GET["action"]) || isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case 1:
            if (isset($_GET["fxReqid"]) && !empty($_GET["fxReqid"])) {
                echo FXRFQClose($_GET["fxReqid"], $_GET['ref']);
            }
            break;
        case 2:    // get all fx request info
            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                echo GetAllFxReq($_GET["id"]);
            }
            break;
        case 3:
            echo AutoCloseFXRFQ();
            break;

        default:
            break;
    }
}

function AutoCloseFXRFQ(){

    global $user_id;
    global $loginRole;

    $objdal = new dal();

    $sql = "SELECT `ID`, `PO`, SUBSTRING(`PO`, 6) `FXId` FROM `wc_t_action_log` 
            WHERE `PO` IN 
            (SELECT CONCAT('FXRFP', Id) AS `PO` FROM `fx_request` 
                WHERE `CuttsOffTime` < DATE_ADD(UTC_TIMESTAMP, INTERVAL 6 HOUR) ) 
            AND `ActionID` = 203 
            AND `Status` = 0";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $id = $row['ID'];
            $pono = $row['PO'];
            $fxId = $row['FXId'];

            $udate_query = "UPDATE `fx_request` SET
    		`status` = 2
    		 where `id`= $fxId;";
            echo $udate_query;
            $objdal->update($udate_query, "Could not update FX Request status");

            FXAction($fxId, action_fx_rfq_end);

            // Action Log --------------------------------//
            $action = array(
                'refid' => $id,
                'pono' => "'".$pono."'",
                'actionid' => action_fx_rfq_end,
                'status' => 1,
                'msg' => "'FX RFQ # ".$pono." closed by system'",
            );
            //var_dump($action);
            //exit();
            UpdateAction($action);
            // End Action Log -----------------------------
        }
    }

    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'FX RFQ closed Successfully';
    return json_encode($res);

}

function GetAllFxReq($id)
{
    global $loginRole;
    if ($loginRole == role_foreign_strategy) {
        $objdal = new dal();
        $query = "SELECT fx.`id`,r.`name` AS `requisition_type`,curr.`name` AS `currency`,
                    FORMAT(fx.`value`, 2) AS `value`
                     FROM `fx_request` fx 
                     LEFT JOIN `wc_t_category` r ON fx.`requisition_type` = r.`id`
                     LEFT JOIN `wc_t_category` curr ON fx.`currency` = curr.`id`
                     where fx.`id`= $id
                     ORDER BY fx.`id` ASC;";

        $objdal->read($query);

        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }

        unset($objdal);

        return json_encode($res);
    }
}

//Submit Fx-Rfq-Request

if (!empty($_POST)) {
//    echo json_encode($_POST);
    echo submitFXRfqRequest();
}
function submitFXRfqRequest()
{
    global $user_id;
    global $loginRole;

//    return $_POST['rfqBank'][1];

    $post_data = $_POST;

    $refId = decryptId($post_data["refId"]);
    $objdal = new dal();
    $rfqId = $post_data['id'];
    $cutoff_date = $post_data['cutoff_date'];

    if ($rfqId != "") {
        // insert new fx
        for ($i = 0; $i < count($_POST['rfqBank']); $i++) {
            $query = "INSERT INTO `fx_rfq_request` SET
            `FxRequestId` = $rfqId,
            `CuttsOffTime` = '$cutoff_date',
            `BankId` = ".$_POST['rfqBank'][$i].";";

            $objdal->insert($query, "Could not submit FX RFQ Request data");

            // Action Log --------------------------------//
            $action = array(
                'refid' => $refId,
                'pono' => "'".$post_data['poid']."'",
                'actionid' => action_fx_rfq_invited_by_GP,
                'pendingtoco' => $_POST['rfqBank'][$i],
                'msg' => "'FX RFQ invited against Request# ".$post_data['poid']."'",
            );
            UpdateAction($action);
            // End Action Log -----------------------------

        }
        //Update status
        $udate_query = "UPDATE `fx_request` SET
    		`status` = 1,
            `CuttsOffTime` = '$cutoff_date'
    		 where `id`= $rfqId;";
        $objdal->update($udate_query, "Could not submit FX Request data");
    }

    FXAction($rfqId,action_fx_rfq_float_done);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$post_data['poid']."'",
        'actionid' => action_fx_rfq_float_done,
        'status' => 1,
        'msg' => "'FX RFQ floated against Request# ".$post_data['poid']."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'FX Requisition Submitted Successfully';
    return json_encode($res);
}

function FXRFQClose($FXReqId, $refIdEncrypted){

    $refId = decryptId($refIdEncrypted);
    $pono = 'FXRFP'.$FXReqId;

    $objdal = new dal();
    $udate_query = "UPDATE `fx_request` SET
    		`status` = 2
    		 where `id`= $FXReqId;";
    $objdal->update($udate_query, "Could not update FX Request status");

    FXAction($FXReqId, action_fx_rfq_end);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_fx_rfq_end,
        'status' => 1,
        'msg' => "'FX RFQ # ".$pono." closed by system'",
    );
    //var_dump($action);
    //exit();
    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'FX Request status updated';
}

?>