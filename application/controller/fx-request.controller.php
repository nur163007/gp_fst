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

/*      Get Methods     */

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// get all fx request info
            echo GetAllFxReq($_GET["status"]);
            break;

        case 2:	// edit fx request info
            if (!empty($_GET["id"])) {
                echo GetFx($_GET["id"]);
            }
            break;
        case 3:	// delete fx request
            if(!empty($_GET["id"])) { echo DeleteFx($_GET["id"]); } else { echo 0; };
            break;

        case 4:
            if(!empty($_GET["id"])) { echo SelectedBank($_GET["id"]); } else { echo 0; };
            break;
        case 5:	// get all fx request info
            echo GetAllFxReq(1);
            break;
        case 7:
            echo GetAllFxReq(2);
            break;
        case 8:
            echo GetLastMSGId($_GET["fxreqid"]);
            break;
        case 9:
            echo GetApprovalLog($_GET["fxreqid"]);
            break;
        case 10:
            echo OpenRFQforEdit($_GET["fxreqid"], 1);
            break;
        case 11:
            echo OpenRFQforEdit($_GET["fxreqid"], 0);
            break;
        case 12:
            echo GenerateRFQ();
            break;
        case 13:
            if(!empty($_GET["id"])) { echo SelectBankData($_GET["id"]); } else { echo 0; };
            break;
        default:
            break;
    }
}

/*       Submit new FX  _Post Method       */

if (!empty($_POST)){
    if (!empty($_POST["postAction"])){
        //echo json_encode($_POST);
        switch($_POST["postAction"])
        {
            case 1:	// get all fx request info
                echo SubmitMessage();
                break;
            default:
                break;
        }
    }
}

/*      DataTable Fetch Query       */

function GetAllFxReq($status=0)
{
/*
 * Param Status 1:      --0
 * FX Status 0 = Rfq float pending
 *  - Bank popup Button Disable
 *
 * Param Status 2:      --1
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
    if ($loginRole == role_foreign_strategy) {
        $objdal = new dal();
        if ($status == 0)
            $where = 'fx.`status` in (0,5)';
        else
            $where = 'fx.`status` = '.$status;

        /*if ($status == 0)
            $where = 'fx.`status` = 0';
        if ($status == 1)
            $where = 'fx.`status` = 1';
        if ($status == 2)
            $where = 'fx.`status` = 2';
        if ($status == 3)
            $where = 'fx.`status` = 2';
        if ($status == 4)
            $where = 'fx.`status` = 3';*/

        /*Datatable query*/

        $strQuery = "SELECT
                    fx.`id`,
                    req.`name` AS req_type,
                    wc.`name` AS currency,
                    FORMAT(fx.`value`, 2) AS fx_value,
                    fx.`CuttsOffTime`,
                    fx.`status`
                FROM
                    `fx_request` fx
                LEFT JOIN `wc_t_category` wc ON
                    fx.`currency` = wc.`id`
                LEFT JOIN `wc_t_users` u ON
                    fx.`created_by` = u.`id`
                LEFT JOIN `wc_t_category` req ON
                    fx.`requisition_type` = req.`id`
                WHERE
                    $where
                ORDER BY
                    fx.`id`
                DESC;";
        //echo $strQuery;
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

/*      edit fx request       */

function GetFx($id)
{

        $objdal = new dal();
        $query = "SELECT
            fx.`id`,
            fx.`supplier_id`,
            fx.`nature_of_service`,
            fx.`requisition_type`,
            fx.`currency`,
            FORMAT(`value`, 2) AS fx_value,
            DATE_FORMAT(`value_date`, '%d-%M-%Y') AS `value_date`,
            fx.`CuttsOffTime`,
            fx.`remarks`,
            fx.`attachment`
        FROM
            `fx_request_primary` fx
        WHERE
            fx.`id` = $id;";

        $objdal->read($query);

        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }

        unset($objdal);

        return json_encode($res);
}

/*---------Get Approval logs----------*/
function GetApprovalLog($fxReqid){

        $objdal = new dal();
        $query = "SELECT
                        fxl.`FxRequestId`,
                        wcu.`username`,
                        wcr.`name` AS Role,
                        fxl.`ActionOn`,
                        wca.`ActionDone`,
                        fxl.`Remarks`
                    FROM
                        `fx_request_log` fxl
                    INNER JOIN `wc_t_users` wcu ON
                        fxl.`ActionBy` = wcu.`id`
                    INNER JOIN `wc_t_roles` wcr ON
                        wcu.`role` = wcr.`id`
                    INNER JOIN `wc_t_action` wca ON
                        wca.`ID` = fxl.`FXAction`
                    WHERE
                        fxl.`FxRequestId` = $fxReqid;";
        //echo $query;

        $objdal->read($query);

        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data;
            extract($res);
        }

        unset($objdal);

        return json_encode($res);
}

/*      Pop up Bank list      */

function SelectedBank($id){
     /*   echo ($id);
        exit();*/
        $object = new dal();
        $bankQuery ="SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            fxr.`RfqDate`,
            fxr.`CuttsOffTime`,
            co.`name` AS `BankName`,
            fxr.`FxRate`,
            fxr.`OfferedVolumeAmount`,
            fx.`value_date`,
            fx.`value`,
            fxr.`remarks`,
            ROUND (((fxr.`FxRate` - (SELECT MIN(fxr1.`FxRate`) FROM `fx_rfq_request` fxr1 WHERE fxr1.`FxRequestId`=fxr.`FxRequestId`))*fxr.`DealAmount`),2) `PotentialLoss`,
            fxr.`DealAmount`,
            fxr.`Selected`,
            (SELECT MIN(fxr2.`FxRate`) FROM `fx_rfq_request` as `fxr2` WHERE fxr2.`FxRequestId`=fxr.`FxRequestId`) `minRate`
        FROM
            `fx_rfq_request` fxr
        LEFT JOIN `wc_t_company` co ON
            fxr.`BankId` = co.`Id`
        LEFT JOIN `fx_request_primary` fx ON fxr.`FxRequestId` = fx.`Id`
        WHERE
            fxr.`FxRequestId` = $id;";

        $object->read($bankQuery);

    $rows = array();
    if (!empty($object->data)) {
        foreach ($object->data as $row) {
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    echo $json;

//    if ($json == "" || $json == 'null') {
//        $json = "[]";
//    }
//    $table_data = '{"data": ' . $json . '}';
//    //return $table_data;
//    return $table_data;

}

/*      submit Deal Amount and select CheckBox data      */



/*  Get message Last ID   */

function GetLastMSGId($fxreqid){
    $objdal = new dal();
    $id = $objdal->getScalar("SELECT MAX(`Id`) FROM `fx_req_message` WHERE `Req_Id`=$fxreqid;");
    if($id=='')
        $id = 'NULL';
    unset($objdal);
    return $id;
}

/*     Submit message to HOT   */

function SubmitMessage(){

    $objdal = new dal();
    $fxLastMsgId = $objdal->sanitizeInput($_POST['fxLastMsgId']);

    $fxReqIdMsg = $objdal->sanitizeInput($_POST['fxReqIdMsg']);
    $HotMsg = $objdal->sanitizeInput($_POST['FxConvMsg']);
    $msgTitle = $objdal->sanitizeInput($_POST['msgTitle']);

    $userId = $_SESSION[session_prefix.'wclogin_userid'];
/*var_dump($userId);
exit();*/
    $query = "INSERT INTO `fx_req_message` SET 
            `UserId` = $userId, 
            `Req_Id` = $fxReqIdMsg,
            `MsgText` = '$HotMsg',
            `Title` = '$msgTitle',
            `Msg_Ref`=$fxLastMsgId";

    //    die();
    $objdal->insert($query, "Could not submit HOT Message");
    unset($objdal);
//    return $query;
    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);
}

/*--------RFQ Modification----------*/
function OpenRFQforEdit($fxreqid, $btnOpenRfqforEdit){

    $objdal = new dal();

    $query = "UPDATE
                `fx_request_primary`
            SET
                `open` = $btnOpenRfqforEdit
            WHERE
                `id` =$fxreqid";

    $objdal->update($query, "Could not submit RFQ Modification");
    unset($objdal);
//    return $query;
    $res["status"] = 1;
    $res["message"] = 'RFQ Edit Opened Successfully';
    return json_encode($res);
}


function GenerateRFQ()
{
    global $loginRole;
    global $user_id;
    if ($loginRole == role_foreign_strategy) {
        $objdal = new dal();

        $strQuery = "SELECT `currency`,`requisition_type`,sum(`value`) as `total`,GROUP_CONCAT(`id`) as `group_id`,
                    `status`,`open` FROM `fx_request_primary` WHERE `status` = 0 GROUP BY `currency`,
                    `requisition_type`,`open`;";
        //echo $strQuery;
        $objdal->read($strQuery);

//        $rows = array();
        if (!empty($objdal->data)) {
            foreach ($objdal->data as $row) {
//                $rows[] = $row;
                $currency = $row['currency'];
                $req_type = $row['requisition_type'];
                $total = $row['total'];
                $status = $row['status'];
                $groupId= $row['group_id'];
                $open= $row['open'];

                $query = "INSERT INTO `fx_request` (`requisition_type`, `currency`,`value`,`status`,`created_by`,`groupId`,`open`)
                VALUES ('$req_type','$currency','$total','$status','$user_id','$groupId','$open')";
//                echo $query;
//                exit();
                $objdal->insert($query);

                $lastFxId = $objdal->LastInsertId();

                $action = array(
                    'pono' => "'FXRFP".$lastFxId."'",
                    'actionid' => action_ready_for_fx_request,
                    'msg' => "'Ready for RFQ request'",
                );
                UpdateAction($action);
            }
        }

        $updateQuery = "UPDATE `fx_request_primary` set `status` = 1";
        $objdal->update($updateQuery);

        unset($objdal);


    }

    $res["status"] = 1;
    $res["message"] = 'RFQ generated Successfully';
    return json_encode($res);
}

function SelectBankData($id){
    /*   echo ($id);
       exit();*/
    $object = new dal();
    $bankQuery ="SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            fxr.`RfqDate`,
            fxr.`CuttsOffTime`,
            co.`name` AS `BankName`,
            fxr.`FxRate`,
            fxr.`OfferedVolumeAmount`,
            fx.`value_date`,
            fx.`value`,
            fxr.`remarks`,
            ROUND (((fxr.`FxRate` - (SELECT MIN(fxr1.`FxRate`) FROM `fx_rfq_request` fxr1 WHERE fxr1.`FxRequestId`=fxr.`FxRequestId`))*fxr.`DealAmount`),2) `PotentialLoss`,
            fxr.`DealAmount`,
            fxr.`Selected`,
            (SELECT MIN(fxr2.`FxRate`) FROM `fx_rfq_request` as `fxr2` WHERE fxr2.`FxRequestId`=fxr.`FxRequestId`) `minRate`
        FROM
            `fx_rfq_request` fxr
        LEFT JOIN `wc_t_company` co ON
            fxr.`BankId` = co.`Id`
        LEFT JOIN `fx_request_primary` fx ON fxr.`FxRequestId` = fx.`Id`
        WHERE
            fxr.`FxRequestId` = $id;";

    $object->read($bankQuery);

    $rows = array();
    if (!empty($object->data)) {
        foreach ($object->data as $row) {
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
//    echo $json;

    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    $table_data = '{"data": ' . $json . '}';
    //return $table_data;
    return $table_data;

}
?>