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
        case 1:	// get all fx request info
            echo GetAllFxReq();
            break;
        case 2:
            echo Submitaccept();
            break;
        case 3:
            echo Submitreject();
            break;
//        case 4:
//            echo SubmitMessage();
//            break;
        case 5:
            echo GetCoversation($_GET["fxreqid"]);
            break;
        default:
            break;
    }
}

if (!empty($_POST)){
    if (!empty($_POST["postAction"])){
        //echo json_encode($_POST);
        switch($_POST["postAction"])
        {
            case 1:	// get all fx request info
                //echo json_encode($_POST);
                echo SubmitMessage();
                break;
            default:
                break;
        }
    }
}
/*      DataTable Query     */

function GetAllFxReq($status=0)
{
    /*
     * Param Status 1:
     * FX Status 0 = Rfq float pending
     *  - Bank popup Button Disable
     *
     * Param Status 2:
     * FX Status 1 & cutsoff date > today = Rfq closed = Rfq float done But still Open For participation
     * - Bank popup Button Disable
     *
     * Param Status 3:
     * FX Status 1 & cutsoff date < today = Rfq closed
     * - Bank list popup enable For FSO input and forword to HOT
     *
     * Param Status 4:
     * FX Status 2 =  Processing stage and Pending to HOT for approval
     * - Banklist popup enable
     *
     * Param Status 5:
     * FX Status 3 = Setteled (Approved by HOT)
     * - Banklist popup enable in read only mode
    */
    global $loginRole;
    if ($loginRole == role_head_of_treasury) {
        $objdal = new dal();

        $strQuery = "SELECT
                    fx.`id`,
                    c.`name` AS supplier_name,
                    cn.`name` AS nature_of_service,
                    req.`name` AS req_type,
                    wc.`name` AS currency,
                    FORMAT(fx.`value`, 2) AS fx_value,
                    DATE_FORMAT(fx.`value_date`, '%d-%M-%Y') AS `value_date`,
                    fx.`CuttsOffTime`,
                    3 as `status`
                FROM
                    `fx_request` fx
                LEFT JOIN `wc_t_category` wc ON
                    fx.`currency` = wc.`id`
                LEFT JOIN `wc_t_users` u ON
                    fx.`created_by` = u.`id`
                LEFT JOIN `wc_t_company` c ON
                    fx.`supplier_id` = c.`id`
                LEFT JOIN `wc_t_category` cn ON
                    fx.`nature_of_service` = cn.`id`
                LEFT JOIN `wc_t_category` req ON
                    fx.`requisition_type` = req.`id`
                WHERE
                    fx.`status` = 3
                ORDER BY
                    fx.`id`
                DESC;";
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

/*      POP UP OF Select bank list with status 2        */

function SelectedBank($id)
{
    $objdal = new dal();
    $Query = "SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            fxr.`RfqDate`,
            fxr.`CuttsOffTime`,
            wcb.`name` AS `BankName`,
            fxr.`FxRate`,
            fxr.`OfferedVolumeAmount`,
            fx.`value_date`,
            fx.`value`,
            fxr.`remarks`,
            ROUND (((fxr.`FxRate` - (SELECT MIN(fxr1.`FxRate`) FROM `fx_rfq_request` fxr1 WHERE fxr1.`FxRequestId`=fxr.`FxRequestId`))*fxr.`OfferedVolumeAmount`),2) `PotentialLoss`,
            fxr.`DealAmount`,
            fxr.`Selected`,
            fx.`status`
        FROM
            `fx_rfq_request` fxr
        LEFT JOIN `wc_t_bank_insurance` wcb ON
            fxr.`BankId` = wcb.`Id`
            LEFT JOIN `fx_request` fx ON fxr.`FxRequestId` = fx.`Id`
        WHERE
            fx.`status` = 2;";

    $objdal->read($Query);

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

/*      submit status = 3       */

function Submitaccept()
{
    $objdal = new dal();
    $fxReqId = $id = $_POST['hdnFxRequestId'];
    $statusUpadte = "UPDATE
                    `fx_request`
                SET
                    `status` = 4
                WHERE
                    `id` = $fxReqId;";
    $objdal->update($statusUpadte, "Failed to submit Bank FX Rate");

    FXAction($fxReqId, action_fx_rfq_accepted_by_hot);

    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}

/*      submit status = 1       */

function Submitreject()
{
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
    
    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}

/*     Submit message to FSO    */

function SubmitMessage(){
//echo 1;
//exit();
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

/*      Get Messages       */

function GetCoversation($fxreqid){
    $objdal = new dal();
    $query = "SELECT * FROM `fx_req_message` WHERE `Req_Id` = $fxreqid";
    //echo $query;
    $objdal->read($query);
    $res = "";
    if(!empty($objdal->data)){
        $res = $objdal->data;
    }
    unset($objdal);
    return json_encode($res);
}
?>