<?php
if ( !session_id() ) {
    session_start();
}
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 11/12/2016
 * Time: 2:03 AM
 */

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])){

    $where = "";

    if(!empty($_GET["bank"]) && isset($_GET["bank"]) && $_GET["bank"]!="" && $_GET["bank"]!="undefined" && $_GET["bank"]!="null"){
        $where = "lc.`lcissuerbank` = ".$_GET["bank"];
    }
    if(!empty($_GET["supplier"]) && isset($_GET["supplier"]) && $_GET["supplier"]!="" && $_GET["supplier"]!="undefined" && $_GET["supplier"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`supplier` = ".$_GET["supplier"];
    }
    if(!empty($_GET["lcno"]) && isset($_GET["lcno"]) && $_GET["lcno"]!="" && $_GET["lcno"]!="undefined" && $_GET["lcno"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "lc.`lcno` = ".$_GET["lcno"];
    }
    if(!empty($_GET["pono"]) && isset($_GET["pono"]) && $_GET["pono"]!="" && $_GET["pono"]!="undefined" && $_GET["pono"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`lcno` = ".$_GET["pono"];
    }
    if(!empty($_GET["cur"]) && isset($_GET["cur"]) && $_GET["cur"]!="" && $_GET["cur"]!="undefined" && $_GET["cur"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`currency` = ".$_GET["cur"];
    }
    if(!empty($_GET["chargeby"]) && isset($_GET["chargeby"]) && $_GET["chargeby"]!="" && $_GET["chargeby"]!="undefined" && $_GET["chargeby"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "am.`chargeBorneBy` = ".$_GET["chargeby"];
    }

    if(!empty($_GET["start"]) && isset($_GET["start"]) && $_GET["start"]!="" && $_GET["start"]!="undefined" && $_GET["start"]!="null"){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = "lc.`lcissuedate` BETWEEN '".$start."' AND '".$end."'";
    }

    /*if(!empty($_GET["sumBy"]) && isset($_GET["sumBy"]) && $_GET["sumBy"]!="" && $_GET["sumBy"]!="undefined" && $_GET["sumBy"]!="null"){
        if($_GET["sumBy"]=='bank'){
            $sumByCol = 'bi.`name`';
        } else if($_GET["sumBy"]=='supplier'){
            $sumByCol = 'co.`name`';
        }
    }*/

    if($where!=""){ $where = ' WHERE '.$where; }

    $query = getSQL($_GET['action'], $where);

    $objdal = new dal();
    $objdal->read(trim($query));
    //echo $query;
    $rows = array();
    if(!empty($objdal->data)){
        /*foreach($objdal->data as $row){
            $rows[] = $row;
        }*/
        $json = json_encode($objdal->data);
    }
    unset($objdal);
    //$json = json_encode($rows);
    $table_data = '{"data": ['.$json.']}';
    echo $table_data;

    //echo getReportTableData($query);

    /*$objdal = new dal();

    $query = getSQL($_GET['action'], $where);

    $objdal->read(trim($query));
    $rows = array();

    if(!empty($objdal->data)){
        // generating column array in JSON format
        $columnArray = '';
        $rowArray = '';
        $dataArray = '';
        $cols = $objdal->data[0];
        foreach ($cols as $key => $value){
            if($columnArray!=""){ $columnArray .= ','; }
            $columnArray .= '["'.$key.'"]';
        }
        $columnArray = '"columns": ['.$columnArray.']';

        // generating Data
        foreach($objdal->data as $row){
            if($rowArray!=""){ $rowArray .= ','; }
            $dataArray = '';
            foreach ($row as $val){
                if($dataArray!=""){ $dataArray .= ','; }
                $dataArray .= '"'.$val.'"';
            }
            $rowArray .= '['.$dataArray.']';
        }
        $rowArray = '"data": ['.$rowArray.']';

        $json = '{'.$columnArray.','.$rowArray.'}';
    } else{
        $json = '{"data":[]}';
    }
    unset($objdal);

    echo $json;*/

}

function getSQL($action, $where){

    $sql = "";

    switch ($action){

        case 1:
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
                '' AS `Description of Goods`,
                cat1.`name` AS `Currency`,
                FORMAT(`lcvalue`,2) AS `LC Value`,
                FORMAT((`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT((`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`,
                `pono` AS `PONo`,
                (SELECT `createdon` FROM `wc_t_pi` AS p WHERE p.`poid`=lc.`pono`) AS `SourcingApprovalDate`,
                (SELECT `ActionOn` FROM `wc_t_action_log` WHERE `PO` = po.`poid` AND `ActionByRole` = ".role_LC_Approvar_5." AND `Status` = 1) AS `TradeFinanceApprovalDate`,
                null AS `QueryResolveDate`,
                null AS `Remarks`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id`
                LEFT JOIN `wc_t_insurance_charge` insC ON lc.`pono` = insC.`ponum` $where
            ) t,
            (SELECT @rownum := 0) r;";
            break;

        case 2:
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
                LEFT JOIN `wc_t_pi` po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_category` cat2 ON cat2.`id` = sh.`docType`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`lcissuerbank` = bi1.`id`
                $where) t,
                (SELECT @rownum := 0) r;";
            break;

        case 3:
            $sql = "SELECT @rownum:=@rownum + 1 as `SL`, 
            t.*, datediff(`TradeFinanceApprovalDate`, `SourcingApprovalDate`) AS `GrossDay`,
            null `WeekendHoliday`,
            null `ActualDayRequired`
            FROM (
            SELECT 
                lc.`lcno` AS `LCNo`,
                po.`poid` AS `PONo`,
                am.amendNo AS `AmendmentNo`,
                DATE_FORMAT(am.`submitOn`,'%d-%M-%Y') AS `AmendmentDate`,
                bi2.`name` AS `Bank`,
                co1.`name` AS `Supplier`,
                am.`amendReason` AS `description`,
                cat1.`name` AS `FCY`,
                FORMAT(lc.`lcvalue`,2) AS `LCValue`,
                FORMAT((lc.`lcvalue` * lc.`xeUSD`),2) AS `LCValueinUSD`,
                FORMAT((lc.`lcvalue` * lc.`xeBDT`),2) AS `LCValueinBDT`,
                FORMAT(lc.`xeBDT`,2) AS `ExRate`,
                FORMAT(am.`charge`,2) AS `AmendmentCost`,
                am.`chargeBorneBy`,
                (SELECT `createdon` FROM `wc_t_pi` AS p WHERE p.`poid`=lc.`pono`) AS `SourcingApprovalDate`,
                (SELECT `ActionOn` FROM `wc_t_action_log` WHERE `PO` = po.`poid` AND `ActionByRole` = 10 AND `Status` = 1) AS `TradeFinanceApprovalDate`,
                null AS `QueryResolveDate`
            FROM `wc_t_amendment` am 
                LEFT JOIN `wc_t_lc` lc ON am.`poNo` = lc.`pono`
                LEFT JOIN `wc_t_pi` po ON am.`poNo` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id` $where
            ) t,
            (SELECT @rownum := 0) r";
            break;

        case 4:
            $sql = "SELECT 
                co1.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat1.`name` AS `Currency`,
                FORMAT(SUM(lc.`lcvalue`),2) AS `LC Value`,
                FORMAT(SUM(lc.`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT(SUM(lc.`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`  
            GROUP BY co1.`name`, cat1.`name`;";
            break;

        case 5:
            $sql = "SELECT 
                bi.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat1.`name` AS `Currency`,
                FORMAT(SUM(lc.`lcvalue`),2) AS `LC Value`,
                FORMAT(SUM(lc.`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT(SUM(lc.`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`
            FROM `wc_t_lc` AS lc 
                LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_bank_insurance` AS bi ON lc.`lcissuerbank` = bi.`id`
                LEFT JOIN `wc_t_category` AS cat1 ON cat1.`id` = po.`currency`  
            GROUP BY bi.`name`, cat1.`name`;";
            break;

        case 6:
            $sql = "SELECT 
                bi.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat.`name` AS `Currency`,
                FORMAT(SUM(sh.`ciAmount`),2) AS `EndAmount`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeUSD`)),2) AS `EndAmountInUSD`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeBDT`)),2) AS `EndAmountInBDT`
            FROM `wc_t_shipment` AS sh 
                LEFT JOIN `wc_t_lc` AS lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_pi` AS po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_category` AS cat ON cat.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` AS bi ON lc.`lcissuerbank` = bi.`id`
			GROUP BY bi.`name`, cat.`name`;";
            break;

        case 7:
            $sql = "SELECT 
                co.`name` AS `sumByColumn`,
                COUNT(lc.`lcno`) `lcCount`,
                cat.`name` AS `Currency`,
                FORMAT(SUM(sh.`ciAmount`),2) AS `EndAmount`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeUSD`)),2) AS `EndAmountInUSD`,
                FORMAT((SUM(sh.`ciAmount` * lc.`xeBDT`)),2) AS `EndAmountInBDT`
            FROM `wc_t_shipment` AS sh 
                LEFT JOIN `wc_t_lc` AS lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_pi` AS po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_category` AS cat ON cat.`id` = po.`currency`
                LEFT JOIN `wc_t_company` AS co ON po.`supplier` = co.`id`
			GROUP BY co.`name`, cat.`name`;";
            break;
    }

    /*$sql = "SELECT @rownum:=@rownum + 1 as `SL`,
                   t.*
            FROM (".$sql.") t,
            (SELECT @rownum := 0) r;";*/
    return $sql;

}

?>