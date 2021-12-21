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
$user_company = $_SESSION[session_prefix.'wclogin_company'];

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// get all fx request info
            echo GetFxDetail($_GET["fxrFqRowId"]);
            break;
        case 2:	// get all fx request info
            break;
        default:
            break;
    }
}

function GetFxDetail($fxrFqRowId)
{
    global $user_id, $user_company;
    $objdal = new dal();
    $query = "SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            cat.`name` as `CurName`,
            fxr.`CuttsOffTime`,
            fxr.`FxRate`,
            fxr.`OfferedVolumeAmount`,
            fxr.`remarks`
        FROM
            `fx_rfq_request` fxr
        INNER JOIN `fx_request` fx ON
            fxr.`FxRequestId` = `fx`.`id`
        INNER JOIN `wc_t_category` cat ON 
            fx.`currency` = cat.`Id`
        WHERE
            `fx`.`Id` = $fxrFqRowId  and fxr.`BankId` = $user_company
        ORDER BY `fxr`.`FxRequestId`  ASC;";
    //echo $query;
    $objdal->read($query);
    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);

    return json_encode($res);

}


if (!empty($_POST)) {
    if (!empty($_POST["fxrFqRowId"]) && !empty($_POST["FxRate"]) && !empty($_POST["OfferedVolumeAmount"]) && !empty($_POST["remarks"])) {
        echo submitBankRate($_POST["fxrFqRowId"], $_POST["FxRate"],$_POST["OfferedVolumeAmount"],$_POST["remarks"]);
    }
}
//Insert BankRate
function submitBankRate($fxrFqRowId, $bankRate,$ownBankOffervol,$remarks){

    $refId = decryptId($_POST["refId"]);
    $poid = $_POST["poid"];
    $OfferedVolumeAmount = str_replace(",", "", $ownBankOffervol);

    $objdal = new dal();

    //$bankrate = $objdal->sanitizeInput($_POST['FxRate']);
    $query = "UPDATE
                    `fx_rfq_request`
                SET
                    `FxRate` = $bankRate,
                    `OfferedVolumeAmount` = $OfferedVolumeAmount,
                    `remarks`= '$remarks'
                WHERE
                    `fx_rfq_request`.`Id` = $fxrFqRowId;";
    //echo $query;
//    die();
    $objdal->update($query, "Failed to submit Bank FX Rate");

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_fx_rfq_rate_given_by_bank,
        'status' => 1,
        'newstatus' => 1,
        'msg' => "'FX RFQ Offer submitted against Request# ".$poid."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);

}
?>
