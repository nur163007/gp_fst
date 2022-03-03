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

        $where = "lc.`lcissuedate` BETWEEN '".$start."' AND '".$end."'";
    }

    if($where!=""){ $where = ' WHERE '.$where; }

    $objdal = new dal();

    if($_GET["action"]==1) {
        $query = "SELECT bc.`LcNo` AS `LC No.`, lc.`lcissuedate` AS `LC Issue Date`,
            bc.`totalVAT` AS `VAT Amount_right`, bc.`totalRebate` AS `Rebate Amount_right`
            FROM `wc_t_lc_opening_bank_charge` AS bc INNER JOIN `wc_t_lc` AS lc ON bc.`LcNo` = lc.`lcno` $where;";
    } elseif ($_GET["action"]==2){
        $query = "SELECT 
            bi1.`name` AS `Insurance Company`,
            ic.`coverNoteNo` AS `Cover Note No.`,
            DATE_FORMAT(ic.`coverNoteDate`, '%d-%M-%Y') AS `Cover Note Date`,
            bi2.`name` AS `LC Issuer Bank`,
            DATE_FORMAT(lc.`lcissuedate`, '%d-%M-%Y') AS `LC Issue Date`,
            lc.`lcno` AS `LC No.`,
            FORMAT(ic.`vat`, 2) AS `VAT Amount_right`,
            FORMAT(ic.`vatRebateAmount`, 2) AS `Rebate Amount_right`,
            FORMAT((ic.`vat` - ic.`vatRebateAmount`), 2) AS `20% of Vat Amount_right`,
            0 AS `Challan No.`,
            '' AS `Challan Date.`
        FROM
            `wc_t_insurance_charge` AS ic
                INNER JOIN
            `wc_t_pi` po ON ic.`ponum` = po.`poid`
                INNER JOIN
            `wc_t_lc` lc ON po.`poid` = lc.`pono`
                INNER JOIN
            `wc_t_bank_insurance` bi1 ON bi1.`id` = lc.`insurance`
                INNER JOIN
            `wc_t_bank_insurance` bi2 ON bi2.`id` = lc.`lcissuerbank`  $where;";
    }

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

