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
    if(!empty($_GET["lcno"]) && isset($_GET["lcno"])){
        $where = "p.`lcno` = ".$_GET["lcno"];
    }

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        if($where!=""){ $where.=' AND '; }
        $where = "p.`payDate` BETWEEN '".$start."' AND '".$end."'";
    }
    
    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT p.`LcNo` AS `LC No.`, p.`ciNo` AS `CI No.`, format(sh.`ciAmount`,2) AS `Invoice Value`, 
                date_format(p.`payDate`,'%d-%M-%Y') AS `Pay Date`, format(p.`amount`,2) AS `Pay Amount`, 
                format(p.`exchangeRate`,2) AS `FX Rate`, 
                format(round(p.`amount` * p.`exchangeRate`,2),2) AS `Pay Amount In BDT`
            FROM `wc_t_payment` AS p 
              INNER JOIN `wc_t_shipment` AS sh ON p.`LcNo` = sh.`lcNo` $where;";

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

