<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2021
    Code fridged on:
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

//NotifyFXRToSelectedBank(20);
//exit();

if (!empty($_POST)){
    if (!empty($_POST["postAction"])){
        switch ($_POST["postAction"]){
            case 1:
                echo SubmitDealAmount();
                break;
            case 2:
                echo SubmitHOTAccept();
                break;
            case 3:
                echo SubmitHOTReject();
                break;
            case 4:
                echo SubmitMessage();
                break;
            default:
                break;
        }

    }
}

function SubmitDealAmount(){

    $fxReqId = $_POST['hdnFxRequestId'];
    $refId = decryptId($_POST["refId"]);
    $poid = $_POST['poid'];
    $objdal = new dal();

    for ($i = 0; $i < count($_POST['hdnFxRfqRowId']); $i++) {
        $id = $_POST['hdnFxRfqRowId'][$i];
        $query = "UPDATE
                    `fx_rfq_request`
                SET
                    `DealAmount` = " . $_POST['DealAmount'][$i] . ",
                    `Selected` = " . $_POST['SelectCheckbox_'.$id] . "
                WHERE
                    `fx_rfq_request`.`Id` = " . $_POST['hdnFxRfqRowId'][$i];
        $objdal->update($query, "Failed to submit Bank FX Rate");
    }
    $statusUpadte = "UPDATE
                    `fx_request`
                SET
                    `status` = 3
                WHERE
                    `id` = $fxReqId;";
    $objdal->update($statusUpadte, "Failed to submit Bank FX Rate");

    FXAction($fxReqId, action_fx_rfq_sent_for_HOT_approval);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_fx_rfq_sent_for_HOT_approval,
        'status' => 1,
        'msg' => "'FX Request # ".$poid." sent for HOT approval'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}

function SubmitHOTAccept() {

    $refId = decryptId($_POST["refId"]);
    $poid = $_POST['poid'];

    $objdal = new dal();
    $fxReqId = $_POST['hdnFxRequestId'];

    $statusUpadte = "UPDATE
                    `fx_request`
                SET
                    `status` = 4
                WHERE
                    `id` = $fxReqId;";
    $objdal->update($statusUpadte, "Failed to submit Bank FX Rate");

    FXAction($fxReqId, action_fx_rfq_accepted_by_hot);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_fx_rfq_accepted_by_hot,
        'status' => 1,
        'msg' => "'FX Request # ".$poid." approved by HOT'",
        'newstatus' => 1,
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    // TODO ---- Send confirmation email to each bank---
    NotifyFXRToSelectedBank($fxReqId);
    //---------------------------------------------------
    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}

function NotifyFXRToSelectedBank($fxReq)
{

    $objdal = new dal();

    $sqlquery = "SELECT
                    CONCAT(wcs.`firstname`, ' ', wcs.`lastname`) AS `Contact_Person`,
                    wcs.`email`,
                    fxr.`FxRate`,
                    fx.`id`,
                    co.`name` AS `BankName`,
                    fxr.`DealAmount`,
                    cat.`name` AS `Currency`,
                    fx.`value_date`,
                    caat.`name` AS `ReqType`
                FROM
                    `fx_request` fx
                LEFT JOIN `wc_t_company` wc ON
                    wc.`id` = fx.`supplier_id`
                LEFT JOIN `fx_rfq_request` fxr ON
                    fxr.`FxRequestId` = fx.`id`
                LEFT JOIN `wc_t_company` co ON
                    fxr.`BankId` = co.`Id`
                LEFT JOIN `wc_t_category` cat ON
                    fx.`currency` = cat.`Id`
                LEFT JOIN `wc_t_category` caat ON
                    fx.`requisition_type` = caat.`Id`
                LEFT JOIN `wc_t_users` wcs ON
                    fxr.`BankId` = wcs.`company` AND wcs.`role` = 23
                LEFT JOIN `wc_t_bank_insurance` wcb ON
                    co.`name` = wcb.`name`
                WHERE
                    fxr.`Selected` = 1 AND fx.`id` = $fxReq;";

//        echo $sqlquery;
    $objdal->read($sqlquery);

    $mailMessageTemp = '<div>
        <br>
        <br>
        <p style="font-size: 15px;font-family:Telenor;"><b>##NAME##</b></p>
        <br>
        <p style="font-size:15px;font-family:Telenor;">We are confirming the below ##PTYPE## payment at ##CURR## ##FxRATE##</p>
        <br>
        <br>
        <div>
            <table class="tablebank" style="text-align: left; width: 100%;border-collapse: collapse;">
                <thead>
                <tr style="border: 1px solid #cfcfcf; text-align: left;">
                    <th style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">Request #</th>
                    <th style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">Bank Name</th>
                    <th style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">Amount</th>
                    <th style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">Currency</th>
                    <th style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">Value Date</th>
                </tr>
                <tr>
                    <td style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">##FXREQID##</td>
                    <td style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">##BANKNAME##</td>
                    <td style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">##AMOUNT##</td>
                    <td style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">##CUR##</td>
                    <td style="width: 800px;padding: 20px;margin: 0 auto;font-size: 15px;border: 2px solid #cfcfcf;">##VALDATE##</td>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <br>
        <br>
        <p style="margin-bottom:12.0pt;font-size:15px;font-family:Telenor;">Thanks & Best Regards,</p>
        <br>
        <p style="margin-bottom:12.0pt;font-size:15px;font-family:Telenor;">Syed Nafees Ahmed</p>
        <p style="margin-bottom:12.0pt;font-size:15px;font-family:Telenor;">+8801711505626</p>
        <br>
        <br>
    </div>';


    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            //echo json_encode($row);
            // todo for email
            echo $row["Contact_Person"];
            echo $row["email"];
            $subject = "";
            $mailMessage = $mailMessageTemp;
            $mailMessage = str_replace("##NAME##", $row["Contact_Person"], $mailMessage);
            $mailMessage = str_replace("##PTYPE##", $row["ReqType"], $mailMessage);
            $mailMessage = str_replace("##CURR##", $row["Currency"], $mailMessage);
            $mailMessage = str_replace("##FxRATE##", $row["FxRate"], $mailMessage);
            $mailMessage = str_replace("##FXREQID##", $row["id"], $mailMessage);
            $mailMessage = str_replace("##BANKNAME##", $row["BankName"], $mailMessage);
            $mailMessage = str_replace("##AMOUNT##", $row["DealAmount"], $mailMessage);
            $mailMessage = str_replace("##CUR##", $row["Currency"], $mailMessage);
            $mailMessage = str_replace("##VALDATE##", $row["value_date"], $mailMessage);

            echo $mailMessage;
            //wcMailFunction($row["email"], $subject, $mailMessage);
        }
    }
    unset($objdal);

    return 1;

}
/*-------submit status = 1--------*/
function SubmitHOTReject() {

    $refId = decryptId($_POST["refId"]);
    $poid = $_POST['poid'];

    $objdal = new dal();
    $fxReqId = $id = $_POST['hdnFxRequestId'];
    $statusUpadte = "UPDATE
                    `fx_request`
                SET
                    `status` = 5
                WHERE
                    `id` = $fxReqId;";
    $objdal->update($statusUpadte, "Failed to submit Bank FX Rate");

    FXAction($fxReqId, action_fx_rfq_rejected_by_hot, $_POST['reject_note']);

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_fx_rfq_rejected_by_hot,
        'status' => -1,
        'msg' => "'FX Request # ".$poid." rejected by HOT'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'Rejected by HOT';
    return json_encode($res);
}

/*-------Submit message to FSO--------*/
function SubmitMessage(){

    $objdal = new dal();
    $fxLastMsgId = $objdal->sanitizeInput($_POST['fxLastMsgId']);

    $fxReqIdMsg = $objdal->sanitizeInput($_POST['fxReqIdMsg']);
    $FxConvMsg = $objdal->sanitizeInput($_POST['FxConvMsg']);
    $msgTitle = $objdal->sanitizeInput($_POST['msgTitle']);

    $userId = $_SESSION[session_prefix.'wclogin_userid'];

    $query = "INSERT INTO `fx_req_message` SET 
            `UserId` = $userId, 
            `Req_Id` = $fxReqIdMsg,
            `MsgText` = '$FxConvMsg',
            `Title` = '$msgTitle',
            `Msg_Ref`=$fxLastMsgId;";

//    echo $query;
    $objdal->insert($query, "Could not submit FSO Message");
    unset($objdal);
    //return $query;
    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}
?>
