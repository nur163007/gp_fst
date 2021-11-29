<?php
session_start();
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 5/2/2017
 * Time: 3:56 PM
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
        $where = "po.`supplier` = " . $_GET["supplier"];
    }
    if (!empty($_GET["pono"]) && isset($_GET["pono"]) && $_GET["pono"] != "" && $_GET["pono"] != "undefined" && $_GET["pono"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where = "po.`poid` = '" . $_GET["pono"] . "'";
    }
    if (!empty($_GET["lcno"]) && isset($_GET["lcno"]) && $_GET["lcno"] != "" && $_GET["lcno"] != "undefined" && $_GET["lcno"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where = "lc.`lcno` = '" . $_GET["lcno"] . "'";
    }
    if (!empty($_GET["cur"]) && isset($_GET["cur"]) && $_GET["cur"] != "" && $_GET["cur"] != "undefined" && $_GET["cur"] != "null") {
        if ($where != "") {
            $where .= ' AND ';
        }
        $where = "po.`currency` = " . $_GET["cur"];
    }

    if (!empty($_GET["start"]) && isset($_GET["start"]) && $_GET["start"] != "" && $_GET["start"] != "undefined" && $_GET["start"] != "null") {

        $start = htmlspecialchars($_GET['start'], ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'], ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = "lc.`lcissuedate` BETWEEN '" . $start . "' AND '" . $end . "'";
    }

    if($where!=""){ $where = ' WHERE '.$where; }

    if($_GET["action"]==1) {
        $sql = "SELECT @rownum:=@rownum + 1 as `SL`, 
            t.*, datediff(`DocDelivered`, `DocRequestBySourcing`) AS `GrossDay`,
            null `WeekendHoliday`,
            null `ActualDayRequired`
            FROM ( SELECT 
                DATE_FORMAT(sh.`docDeliveredByFin`,'%d-%M-%Y') AS `EndorsementDate`,
                lc.`lcno` AS `LCNo`,
                bi1.`name` AS `Bank`,
                sh.`ciNo` AS `CINo`,
                cat2.`name` AS `DocumentType`,
                sh.`GERPVoucherNo`,
                DATE_FORMAT(sh.`GERPVoucherDate`,'%d-%M-%Y') AS `GERPVoucherDate`,
                po.`poid` AS `PONo`,
                co1.`name` AS `Supplier`,
                po.`lcdesc` AS `Description`,
                cat1.`name` AS `Currency`,
                FORMAT(lc.`lcvalue`,2) AS `LCValue`,
                FORMAT(sh.`ciAmount`,2) AS `EndorsedAmount`,
                FORMAT((sh.`ciAmount` * lc.`xeUSD`),2) AS `EndorsedAmountUSD`,
                FORMAT((sh.`ciAmount` * lc.`xeBDT`),2) AS `EndorsedAmountBDT`,
                FORMAT(lc.`xeBDT`,2) AS `ExRate`,
                sh.`shipNo` AS `Shipment/EndorsementNo`,
                (SELECT `ActionOn` FROM `wc_t_action_log` WHERE 
					`PO` = po.`poid` AND 
                    `shipNo` = sh.`shipNo` AND
                    `ActionByRole` = 2 AND 
                    `ActionID` = if(docType=47, 72, 70)) AS `DocRequestBySourcing`,
				DATE_FORMAT(sh.`docDeliveredByFin`,'%d-%M-%Y') AS `DocDelivered`,
                null `QueryResolveDate`
            FROM `wc_t_shipment` sh 
                LEFT JOIN `wc_t_lc` lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_po` po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_category` cat2 ON cat2.`id` = sh.`docType`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`lcissuerbank` = bi1.`id` $where) t,
                (SELECT @rownum := 0) r;";
    }
    if($_GET["action"]==2){
        $sql = "SELECT 
                bi.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat.`name` AS `Currency`,
                FORMAT(SUM(sh.`ciAmount`),2) AS `EndAmount`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeUSD`)),2) AS `EndAmountInUSD`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeBDT`)),2) AS `EndAmountInBDT`
            FROM `wc_t_shipment` AS sh 
                LEFT JOIN `wc_t_lc` AS lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_po` AS po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_category` AS cat ON cat.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` AS bi ON lc.`lcissuerbank` = bi.`id`
			GROUP BY bi.`name`, cat.`name`;";
    }
    if($_GET["action"]==3){
        $sql = "SELECT 
                co.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat.`name` AS `Currency`,
                FORMAT(SUM(sh.`ciAmount`),2) AS `EndAmount`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeUSD`)),2) AS `EndAmountInUSD`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeBDT`)),2) AS `EndAmountInBDT`
            FROM `wc_t_shipment` AS sh 
                LEFT JOIN `wc_t_lc` AS lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_po` AS po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_category` AS cat ON cat.`id` = po.`currency`
                LEFT JOIN `wc_t_company` AS co ON po.`supplier` = co.`id`
			GROUP BY co.`name`, cat.`name`;";
    }
//    echo $sql;
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