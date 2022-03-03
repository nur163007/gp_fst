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

        $where = "ic.`entryOn` BETWEEN '".$start."' AND '".$end."'";
    }
    
    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    if($_GET["action"]==1) {
        $query = "SELECT lc.`lcno` AS `LC#`, DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Date`, bi2.`name` AS `Issuer Bank`, '' `RC`, 
                bi1.`name` AS `Insurance Compnay`, ic.`coverNoteNo` AS `Cover Note #`, c.`name` AS `Currency`, 
                po.`basevalue` AS `Insurance / Base Value`, ic.`exchangeRate` `FX Rate`, FORMAT(ic.`assuredAmount`,2) AS `Assured Amount`, 
                FORMAT(ic.`marine`,2) AS `Marine`, FORMAT(ic.`war`,2) AS `War`, FORMAT(ic.`netPremium`,2) AS `Net Premium`, 
                FORMAT(ic.`vat`,2) AS `Total VAT`, FORMAT(ic.`stampDuty`,2) AS `Stamp Duty`, FORMAT(ic.`otherCharges`,2) AS `Other Charges`, 
                FORMAT(ic.`total`,2) AS `Total`, FORMAT(ic.`capex`,2) AS `Capex`, FORMAT(ic.`vatRebateAmount`,2) AS `VAT Rebate`, FORMAT(ic.`vatPayable`,2) AS `VAT Payable`
            FROM `wc_t_insurance_charge` AS ic 
                INNER JOIN `wc_t_lc` AS lc ON ic.`ponum` = lc.`pono`
                INNER JOIN `wc_t_pi` AS po ON lc.`pono` = po.`poid`
                INNER JOIN `wc_t_bank_insurance` bi1 ON bi1.`id` = lc.`insurance` 
                INNER JOIN `wc_t_bank_insurance` bi2 ON bi2.`id` = lc.`lcissuerbank`
                INNER JOIN `wc_t_category` AS c ON po.`currency` = c.`id` $where;";
    }elseif($_GET["action"]==2){
        $query = "SELECT bi1.`name` AS `Insurance Compnay_left`, 
                format(sum(po.`basevalue`),2) AS `Insurance / Base Value_right`, 
                format(sum(ic.`assuredAmount`),2) AS `Assured Amount_right`, 
                format(sum(ic.`netPremium`),2) AS `Net Premium_right`,
                c.`name` AS `Currency_center`
            FROM `wc_t_insurance_charge` AS ic 
                INNER JOIN `wc_t_lc` AS lc ON ic.`ponum` = lc.`pono`
                INNER JOIN `wc_t_pi` AS po ON lc.`pono` = po.`poid`
                INNER JOIN `wc_t_bank_insurance` bi1 ON bi1.`id` = lc.`insurance` 
                INNER JOIN `wc_t_category` AS c ON po.`currency` = c.`id` $where
            GROUP BY bi1.`name`, c.`name` 
            ORDER BY bi1.`name`;";
    }
//    echo $query;
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
            if(substr($key, 0, strripos($key,'_'))!="") {
                $columnArray .= '{"title":"' . substr($key, 0, strripos($key, '_')) . '", "class":"text-' . substr($key, strripos($key, '_') + 1) . '"}';
            }else {
                $columnArray .= '{"title":"' . $key . '"}';
            }
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

