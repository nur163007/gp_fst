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
    if(!empty($_GET["cur"]) && isset($_GET["cur"]) && $_GET["cur"]!="" && $_GET["cur"]!="undefined" && $_GET["cur"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`currency` = ".$_GET["cur"];
    }

    if($where!=""){ $where = ' WHERE '.$where; }

    $query = getSQL($_GET['action'], $where);

    echo getReportTableData($query);

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
            $sql = "SELECT 
                lc.`lcno` AS `LC #`,
                bi1.`name` AS `Ins. Company`,
                insC.`coverNoteNo` AS `Ins. Cover Note#`,
                bi2.`name` AS `Bank`,
                DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Opening Date`,
                DATE_FORMAT(lc.`lastdateofship`,'%d-%M-%Y') AS `Last Date of Shipment`,
                DATE_FORMAT(lc.`lcexpirydate`,'%d-%M-%Y') AS `Expiry Date`,
                co1.`name` AS `Supplier`,
                po.`lcdesc` AS `Description of Goods`,
                cat1.`name` AS `Currency`,
                `lcvalue` AS `LC Value`,
                `lcvalue` * `xeUSD` AS `LC Value in USD`,
                `lcvalue` * `xeBDT` AS `LC Value in BDT`,
                `pono` AS `PO No.`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id`
                LEFT JOIN `wc_t_insurance_charge` insC ON lc.`pono` = insC.`ponum` $where;";
            break;
        case 2:
            $sql = "SELECT 
                sh.`docDeliveredByFin` AS `Endorsement Date`,
                lc.`lcno` AS `LC No.`,
                bi2.`name` AS `Bank`,
                sh.`docType` AS `Document Type`,
                po.`poid` AS `PO No.`,
                bi1.`name` AS `Ins. Company`,
                co1.`name` AS `Supplier`,
                po.`lcdesc` AS `Description`,
                cat1.`name` AS `Currency`,
                lc.`lcvalue` AS `LC Value`,
                sh.`ciAmount` AS `Endorsed Amount`,
                sh.`ciAmount` * lc.`xeUSD` AS `LC Value in USD`,
                sh.`ciAmount` * lc.`xeBDT` AS `LC Value in BDT`,
                lc.`xeBDT` AS `Ex. Rate`,
                sh.`ciNo` AS `CI No.`,
                sh.`ciDate` AS `CI Date`,
                sh.`awbOrBlDate` AS `AWB Date`,
                sh.`shipNo` AS `Shipment/ Endorsement No.`            
            FROM `wc_t_shipment` sh 
                LEFT JOIN `wc_t_lc` lc ON sh.`pono` = lc.`pono`
                LEFT JOIN `wc_t_pi` po ON sh.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id` $where;";
            break;
        case 3:
            $sql = "SELECT 
                lc.`lcno` AS `LC No.`,
                po.`poid` AS `PO No.`,
                am.amendNo AS `Amendment No.`,
                am.submitOn AS `Amendment Date`,
                bi2.`name` AS `Bank`,
                co1.`name` AS `Supplier`,
                po.`lcdesc` AS `Description`,
                cat1.`name` AS `LCY`,
                lc.`lcvalue` AS `LC Value`,
                lc.`lcvalue` * lc.`xeUSD` AS `LC Value in USD`,
                lc.`lcvalue` * lc.`xeBDT` AS `LC Value in BDT`,
                lc.`xeBDT` AS `Ex. Rate`,
                am.`charge` AS `Amendment Cost`,
                am.`chargeBorneBy` AS `Amendment Cost Borne by`,
                'qq' AS `Any Adjustment`
            FROM `wc_t_amendment` am 
                LEFT JOIN `wc_t_lc` lc ON am.`poNo` = lc.`pono`
                LEFT JOIN `wc_t_pi` po ON am.`poNo` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id` $where;";
            break;
        case 4:
            $sql = "SELECT 
                co1.`name` AS `Supplier`,
                cat1.`name` AS `Currency`,
                SUM(lc.`lcvalue`) AS `LC Value`,
                SUM(lc.`lcvalue` * `xeUSD`) AS `LC Value in USD`,
                SUM(lc.`lcvalue` * `xeBDT`) AS `LC Value in BDT`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_pi` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency` $where 
                GROUP BY co1.`name`, cat1.`name`;";
            break;
        case 5:
            $sql = "";
            break;
    }

    return $sql;

}

?>