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
        case 1:	// get all fx settlement Report
            echo GetFxSettelmentReport();
            break;
        default:
            break;
    }
}



/*-------Fetch Fx Settelement Data----*/
function GetFxSettelmentReport()
{
    global $loginRole;
    $company = $_SESSION[session_prefix.'wclogin_company'];

    if ($loginRole == role_foreign_strategy || $loginRole == role_head_of_treasury) {
        $where = 'fx.`status`= 4';
    }elseif ($loginRole == role_bank_fx){
        $where = 'fx.`status`= 4 AND fxr.`BankId` = '.$company;
    }else {
        $json = "[]";
        $table_data = '{"data": ' . $json . '}';
        //return $table_data;
        return $table_data;
    }

    $objdal = new dal();

    $query = "SELECT
                    fx.`id`,
                    fxr.`RfqDate`,
                    req.`name` AS req_type,
                    wc.`name` AS currency,
                    FORMAT(fx.`value`, 2) AS fx_value,
                    co.`name`,
                    fxr.`FxRate`,
                    fxr.`DealAmount`,
                    ROUND (((fxr.`FxRate` - (SELECT MIN(fxr1.`FxRate`) FROM `fx_rfq_request` fxr1 WHERE fxr1.`FxRequestId`=fxr.`FxRequestId`))*fxr.`OfferedVolumeAmount`),2) `PotentialLoss`,
                    fxr.`remarks`
                FROM
                    `fx_request` fx
                LEFT JOIN `wc_t_category` wc ON
                    fx.`currency` = wc.`id`
                LEFT JOIN `wc_t_category` req ON
                    fx.`requisition_type` = req.`id`
                LEFT JOIN `fx_rfq_request` fxr ON
                    fx.`Id` = fxr.`FxRequestId`
                LEFT JOIN `wc_t_company` co ON
                    fxr.`BankId` = co.`id`
                WHERE $where;";
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
?>