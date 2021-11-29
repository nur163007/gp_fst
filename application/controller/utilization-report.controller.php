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
    
//    $where = "";
    
    /*if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = "c.`createdon` BETWEEN '".$start."' AND '".$end."'";
    }*/
    
//    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    /*$query = "SELECT
            bi.`name` AS `Bank`, format(bi.`nonFunded`,2) AS `Non Funded Facility (USD)`,
            (SELECT SUM(l.`lcvalue`) -  SUM(IFNULL((SELECT SUM(`amount`) FROM `wc_t_payment` pm WHERE pm.`LcNo`=(SELECT lc.`lcno` FROM `wc_t_lc` lc WHERE lc.`pono`=l.`pono`) GROUP BY pm.`LcNo`),0))
        FROM `wc_t_lc` AS l WHERE l.`lcissuerbank` = lc1.`lcissuerbank`) `LC Outstanding`,
            bi.`nonFunded` - (SELECT SUM(l.`lcvalue`) -  SUM(IFNULL((SELECT SUM(`amount`) FROM `wc_t_payment` pm WHERE pm.`LcNo`=(SELECT lc.`lcno` FROM `wc_t_lc` lc WHERE lc.`pono`=l.`pono`) GROUP BY pm.`LcNo`),0))
        FROM `wc_t_lc` AS l WHERE l.`lcissuerbank` = lc1.`lcissuerbank`) `Space Available`
        FROM `wc_t_bank_insurance` AS bi 
            LEFT JOIN `wc_t_lc` lc1 ON bi.`id` = lc1.`lcissuerbank`
        WHERE bi.`type` = 'bank';";*/
    $query = "SELECT 
            bi.`name` AS `Bank`, 
            format(bi.`nonFunded`,2) AS `Non Funded Facility (USD)`,
            format(bi.`nonFunded`*79,2) AS `Non Funded Facility (BDT)`,
            format(SUM(l.`lcvalue`),2) `Capacity Utilized (USD)`,
            format(SUM(l.`lcvalue`)*79,2) `Capacity Utilized (BDT)`,
            format(bi.`nonFunded` - SUM(l.`lcvalue`),2) `Space Available (USD)`,
            format((bi.`nonFunded` - SUM(l.`lcvalue`))*79,2) `Space Available (BDT)`
        FROM `wc_t_bank_insurance` AS bi 
            LEFT JOIN `wc_t_lc` l ON bi.`id` = l.`lcissuerbank`
        WHERE bi.`type` = 'bank'
        GROUP BY bi.`id`
        ORDER BY bi.`name`;";

    $objdal->read(trim($query));
    $rows = array();

    $r = $objdal->dataRaw;
    echo $r;
    while ($row = mysql_fetch_array($r)) {
        echo $row['Field'] . ' ' . $row['Type'];
    }

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

