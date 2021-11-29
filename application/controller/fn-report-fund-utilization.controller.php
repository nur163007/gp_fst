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
    $bank = "";

    if((isset($_GET["lcbank"]) && !empty($_GET["lcbank"])) && $_GET["lcbank"]!="" && $_GET["lcbank"]!="undefined" && $_GET["lcbank"]!="null"){
        $bank = "AND bi.`id` = ".$_GET["lcbank"];
    }

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = " WHERE `lcissuedate` BETWEEN '".$start."' AND '".$end."'";
    }

    $query = "SELECT @rownum:=@rownum + 1 as `SL`, t.*
        FROM (
        SELECT 
            bi.`name` AS `Bank`, 
            format(bi.`nonFunded`,2) AS `NonFundedFacilityUSD`,
            format(bi.`nonFunded`*79,2) AS `NonFundedFacilityBDT`,
            format(SUM(ifnull(l.`lcvalue`,0)),2) `CapacityUtilizedUSD`,
            format(SUM(ifnull(l.`lcvalue`,0))*79,2) `CapacityUtilizedBDT`,
            format(bi.`nonFunded` - SUM(ifnull(l.`lcvalue`,0)),2) `SpaceAvailableUSD`,
            format((bi.`nonFunded` - SUM(ifnull(l.`lcvalue`,0)))*79,2) `SpaceAvailableBDT`
        FROM `wc_t_bank_insurance` AS bi 
            LEFT JOIN (SELECT `lcvalue`, `lcissuerbank` FROM `wc_t_lc` $where) l ON bi.`id` = l.`lcissuerbank`
        WHERE bi.`type` = 'bank' $bank
        GROUP BY bi.`id`
        ORDER BY bi.`name`
        ) t,
        (SELECT @rownum := 0) r;";
//    echo $query;
    $objdal = new dal();
    $objdal->read(trim($query));
    $rows = array();
    if(!empty($objdal->data)){
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    $table_data = '{"data": '.$json.'}';
    echo $table_data;

    /*if(!empty($objdal->data)){
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


?>

