<?php
if ( !session_id() ) {
    session_start();
}
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 12/11/2016
 * Time: 9:35 PM
 */

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])) {

    $where = "";

    if (!empty($_GET["bank"]) && isset($_GET["bank"]) && $_GET["bank"] != "" && $_GET["bank"] != "undefined" && $_GET["bank"] != "null") {
        $where = "lc.`lcissuerbank` = " . $_GET["bank"];
    }
    if (!empty($_GET["supplier"]) && isset($_GET["supplier"]) && $_GET["supplier"] != "" && $_GET["supplier"] != "undefined" && $_GET["supplier"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where .= "po.`supplier` = " . $_GET["supplier"];
    }
    if (!empty($_GET["pono"]) && isset($_GET["pono"]) && $_GET["pono"] != "" && $_GET["pono"] != "undefined" && $_GET["pono"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where .= "po.`poid` = '" . $_GET["pono"] . "'";
    }
    if (!empty($_GET["lcno"]) && isset($_GET["lcno"]) && $_GET["lcno"] != "" && $_GET["lcno"] != "undefined" && $_GET["lcno"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where .= "lc.`lcno` = '" . $_GET["lcno"] . "'";
    }
    if (!empty($_GET["cur"]) && isset($_GET["cur"]) && $_GET["cur"] != "" && $_GET["cur"] != "undefined" && $_GET["cur"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where .= "po.`currency` = " . $_GET["cur"];
    }

    if (!empty($_GET["start"]) && isset($_GET["start"]) && $_GET["start"] != "" && $_GET["start"] != "undefined" && $_GET["start"] != "null") {

        $start = htmlspecialchars($_GET['start'], ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'], ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));
        if ($where != "") {
            $where .= ' AND ';
        }
        $where .= "lc.`lcissuedate` BETWEEN '" . $start . "' AND '" . $end . "'";
    }

    if($where!=""){ $where = ' WHERE '.$where; }

    if($_GET["action"]==1) {
        $sql = "SELECT @rownum:=@rownum + 1 as `SL`, 
            t.*, datediff(`TradeFinanceApprovalDate`, `SourcingApprovalDate`) AS `GrossDay`,
            null `WeekendHoliday`,
            null `ActualDayRequired`
            FROM ( SELECT 
                lc.`lcno` AS `LCNo`,
                bi1.`name` AS `InsCompany`,
                insC.`coverNoteNo` AS `InsCoverNote`,
                bi2.`name` AS `Bank`,
                DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Opening Date`,
                DATE_FORMAT(lc.`lastdateofship`,'%d-%M-%Y') AS `Last Date of Shipment`,
                DATE_FORMAT(lc.`lcexpirydate`,'%d-%M-%Y') AS `Expiry Date`,
                co1.`name` AS `Supplier`,
                lc.`lcdesc` AS `Description of Goods`,
                cat1.`name` AS `Currency`,
                FORMAT(`lcvalue`,2) AS `LC Value`,
                FORMAT((`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT((`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`,
                FORMAT(insC.`insuranceValue`,2) AS `Insurance Value`,
                (SELECT GROUP_CONCAT(CONCAT(c.`name`, ' : ', pt.`percentage`, '%') SEPARATOR ', ') FROM `wc_t_payment_terms` pt LEFT JOIN `wc_t_category` c ON pt.partname = c.id WHERE pt.`pono` = po.`poid` GROUP BY pt.`pono`)  AS `Payment Terms`,
                `pono` AS `PONo`,
                right(`pono`, 1) `PINo`,
                (SELECT `createdon` FROM `wc_t_po` AS p WHERE p.`poid`=lc.`pono`) AS `SourcingApprovalDate`,
                (SELECT `ActionOn` FROM `wc_t_action_log` WHERE `PO` = po.`poid` AND `ActionByRole` = " . role_LC_Approvar_5 . " AND `Status` = 1) AS `TradeFinanceApprovalDate`,
                null AS `QueryResolveDate`,
                null AS `Remarks`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_po` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id`
                LEFT JOIN `wc_t_insurance_charge` insC ON lc.`pono` = insC.`ponum` $where
            ) t,
            (SELECT @rownum := 0) r;";
    }
    if($_GET["action"]==2){
        $sql = "SELECT 
                bi.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat1.`name` AS `Currency`,
                FORMAT(SUM(lc.`lcvalue`),2) AS `LC Value`,
                FORMAT(SUM(lc.`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT(SUM(lc.`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`
            FROM `wc_t_lc` AS lc 
                LEFT JOIN `wc_t_po` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_bank_insurance` AS bi ON lc.`lcissuerbank` = bi.`id`
                LEFT JOIN `wc_t_category` AS cat1 ON cat1.`id` = po.`currency`  $where
            GROUP BY bi.`name`, cat1.`name`;";
    }
    if($_GET["action"]==3){
        $sql = "SELECT 
                co1.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat1.`name` AS `Currency`,
                FORMAT(SUM(lc.`lcvalue`),2) AS `LC Value`,
                FORMAT(SUM(lc.`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT(SUM(lc.`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_po` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`  $where
            GROUP BY co1.`name`, cat1.`name`;";
    }
    //echo $sql;
    $objdal = new dal();
    $rows = $objdal->read($sql);
    //$json = array();

    if(!empty($rows)){
        $json = json_encode($rows);
        /*if (!$json)
            echo json_last_error_msg();*/
        $table_data = '{"data": '.$json.'}';
    }else{
        $table_data = '{"data": []}';
    }

    echo $table_data;
    unset($objdal);

}

?>