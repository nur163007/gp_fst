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

        default:
            break;
    }
}

/*      DataTable Fetch Query       */

function GetAllFxReq($status=0)
{
    global $loginRole;
    if ($loginRole == role_foreign_strategy) {
        $objdal = new dal();
        if ($status == 0)
            $where = 'fx.`status` in (0,5)';
        else
            $where = 'fx.`status` = '.$status;

        $strQuery = "SELECT
                    fx.`id`,
                    c.`name` AS supplier_name,
                    c1.`name` AS lc_bank,
                    cn.`name` AS nature_of_service,
                    req.`name` AS req_type,
                    wc.`name` AS currency,
                    FORMAT(fx.`value`, 2) AS fx_value,
                    DATE_FORMAT(fx.`value_date`, '%d-%b-%Y') AS `value_date`,
                    fx.`status`
                FROM
                    `fx_request_primary` fx
                LEFT JOIN `wc_t_category` wc ON
                    fx.`currency` = wc.`id`
                LEFT JOIN `wc_t_users` u ON
                    fx.`created_by` = u.`id`
                LEFT JOIN `wc_t_company` c ON
                    fx.`supplier_id` = c.`id`
                LEFT JOIN `wc_t_company` c1 ON
                    fx.`bankID` = c1.`id`
                LEFT JOIN `wc_t_category` cn ON
                    fx.`nature_of_service` = cn.`id`
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



?>