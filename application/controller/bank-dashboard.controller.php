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
                echo GetFx(0);
            break;
        case 2:	// get all fx request info
                echo GetFxDetail($_GET["fxrFqRowId"]);
            break;
        case 3:
            echo GetFx(1);
        default:
            break;
    }
}

function GetFx($offerfxstatus)
{
    $coId = $_SESSION[session_prefix . 'wclogin_company'];

    $objdal = new dal();
    $query = "SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            fx.`value` AS `FxValue`,
            fx.`value_date` AS `FxDate`,
            cat.`name` as `CurName`,
            fxr.`CuttsOffTime`,
            fxr.`FxRate`,
            IF(fxr.FxRate IS null, 0, 1) FxRateStatus,
            fx.`status`
        FROM
            `fx_rfq_request` fxr
        INNER JOIN `fx_request` fx ON
            fxr.`FxRequestId` = `fx`.`id`
        INNER JOIN `wc_t_category` cat ON 
            fx.`currency` = cat.`Id`
        WHERE
            `fxr`.`BankId` = $coId 
            AND (fx.`status` = 1 OR fx.`open`=1) 
            AND IF(fxr.FxRate IS null, 0, 1) = $offerfxstatus
        ORDER BY `fxr`.`FxRequestId`  ASC;";
    //echo $query;
    $objdal->read($query);
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
function GetFxDetail($fxrFqRowId)
{
    $objdal = new dal();
    $query = "SELECT
            fxr.`Id`,
            fxr.`FxRequestId`,
            fx.`value` AS `FxValue`,
            fx.`value_date` AS `FxDate`,
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
            `fxr`.`Id` = $fxrFqRowId 
        ORDER BY `fxr`.`FxRequestId`  ASC;";

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

    $objdal = new dal();

    //$bankrate = $objdal->sanitizeInput($_POST['FxRate']);
    $query = "UPDATE
                    `fx_rfq_request`
                SET
                    `FxRate` = $bankRate,
                    `OfferedVolumeAmount` = $ownBankOffervol,
                    `remarks`= '$remarks'
                WHERE
                    `fx_rfq_request`.`Id` = $fxrFqRowId;";
    //echo $query;
//    die();
    $objdal->update($query, "Failed to submit Bank FX Rate");

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Bank FX Rate Submitted Successfully';
    return json_encode($res);

}

?>

