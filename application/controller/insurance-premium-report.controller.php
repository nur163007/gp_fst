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
    $query = "SELECT lc.`lcno` AS `LC#`, DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Date`, bi2.`name` AS `Issuer Bank`, '' `RC`, bi1.`name` AS `Insurance Compnay`, 
                ic.`coverNoteNo` AS `Cover Note #`, po.`currency` AS `Currency`, lc.`lcvalue` AS `LC Amount`, ic.`exchangeRate` `FX Rate`, ic.`assuredAmount` AS `Assured Amount`, 
                ic.`marine` AS `Marine`, ic.`war` AS `War`, ic.`netPremium` AS `Net Premium`, ic.`vat` AS `Total VAT`, ic.`stampDuty` AS `Stamp Duty`, ic.`otherCharges` AS `Other Charges`, 
                ic.`total` AS `Total`, ic.`capex` AS `Capex`, ic.`vatRebate` AS `VAT Rebate`, ic.`vatPayable` AS `VAT Payable`
            FROM `wc_t_insurance_charge` AS ic 
                INNER JOIN `wc_t_lc` AS lc ON ic.`ponum` = lc.`pono`
                INNER JOIN `wc_t_po` AS po ON lc.`pono` = po.`poid`
                INNER JOIN `wc_t_bank_insurance` bi1 ON bi1.`id` = lc.`insurance` 
                INNER JOIN `wc_t_bank_insurance` bi2 ON bi2.`id` = lc.`lcissuerbank`  $where;";

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

    echo $json;
    
}


?>

