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
    
    if(!empty($_GET["ci"]) && isset($_GET["ci"]) && $_GET["ci"]!="" && $_GET["ci"]!="undefined" && $_GET["ci"]!="null"){
        $where = "l.`lcissuerbank` = ".$_GET["bank"];
    }
    if(!empty($_GET["supplier"]) && isset($_GET["supplier"]) && $_GET["supplier"]!="" && $_GET["supplier"]!="undefined" && $_GET["supplier"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`supplier` = ".$_GET["supplier"];
    }
    if(!empty($_GET["expiry"]) && isset($_GET["expiry"]) && $_GET["expiry"]!="" && $_GET["expiry"]!="undefined" && $_GET["expiry"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "l.`lcissuerbank` = ".$_GET["bank"];
    }
    if(!empty($_GET["sumBy"]) && isset($_GET["sumBy"]) && $_GET["sumBy"]!="" && $_GET["sumBy"]!="undefined" && $_GET["sumBy"]!="null"){
        if($_GET["sumBy"]=='bank'){
            $sumByCol = 'bi.`name`';
        } else if($_GET["sumBy"]=='supplier'){
            $sumByCol = 'co.`name`';
        }
    }
    
    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT 
            (SELECT SUM(IF(DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)>0 AND DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)<31,1,0)) FROM `wc_t_lc` lc1 ) AS `0-30 Days`,
            (SELECT SUM(IF(DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)>30 AND DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)<61,1,0)) FROM `wc_t_lc` lc1 ) AS `30-60 Days`,
            (SELECT SUM(IF(DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)>60 AND DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)<91,1,0)) FROM `wc_t_lc` lc1 ) AS `61-90 Days`,
            (SELECT SUM(IF(DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)>90 AND DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)<181,1,0)) FROM `wc_t_lc` lc1 ) AS `91-180 Days`,
            (SELECT SUM(IF(DATEDIFF(lc1.`daysofexpiry`, CURRENT_DATE)>180,1,0)) FROM `wc_t_lc` lc1 ) AS `180+ Days`;";

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

