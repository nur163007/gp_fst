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

if (!empty($_GET["action"]) || isset($_GET["action"])){
    
    $where = "";
    
    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = "c.`entryOn` BETWEEN '".$start."' AND '".$end."'";
    }
    
    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT c.`LcNo` AS `LC#`, DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Date`, bn.`name` AS `Bank`, bn.`glCode` AS `Bank GL`, '' AS `RC`, cat.`name` AS `Currency`,
            FORMAT(lc.`lcvalue`,2) AS `LC Amount`, c.`exchangeRate` AS `FX Rate`, FORMAT(c.`comissionBDT`,2) AS `Commission`, FORMAT(c.`totalVAT`,2) AS `Total VAT`,
            FORMAT(c.`cableCharge`,2) AS `Cable Charge`, FORMAT(c.`otherCharge`,2) AS `Other Charges`, FORMAT(c.`totalCharge`,2) AS `Total Charges`, FORMAT(c.`capex`,2) AS `Capex`, FORMAT(c.`totalRebate`,2) AS `VAT Rebate`
        FROM `wc_t_lc_opening_bank_charge` AS c 
            LEFT JOIN `wc_t_lc` AS lc ON c.`LcNo` = lc.`lcno`
            LEFT JOIN `wc_t_po` AS po ON lc.`pono` = po.`poid`
            LEFT JOIN `wc_t_bank_insurance` AS bn ON lc.`lcissuerbank` = bn.`id`
            LEFT JOIN `wc_t_category` AS cat ON po.`currency` = cat.`id` $where;";

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
            $columnArray .= '{"title":"'.$key.'"}';
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

    echo $json;
    
}


?>

