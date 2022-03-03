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
    
    if(!empty($_GET["start"]) && isset($_GET["start"]) && $_GET["start"]!="" && $_GET["start"]!="undefined" && $_GET["start"]!="null"){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $start .= " 00:00:00";
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));
        $end .= " 23:59:59";

        $where = "c.`createdon` BETWEEN '".$start."' AND '".$end."'";
    }

    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT 
            sh.`eaRefNo` AS `GP Ref. No.`,
            c.`lcno` AS `LC/LCA No.`,
            DATE_FORMAT(c.`RequisitionDate`, '%d-%M-%Y') AS `Requisition Date`,
            replace(replace(po.`podesc`,'\\t',' '),'\\r\\n','') AS `PO Description`,
            FORMAT(sh.`ciAmount`, 2) AS `Invoice Value_right`,
            c.`poid` AS `PO No.`,
            c.shipNo AS `Consignment_center`,
            FORMAT(c.`CdPayAmount`, 2) AS `Total Duty_right`,
            FORMAT(c.`vat`, 2) AS `VAT_right`,
            FORMAT(c.`ait`, 2) AS `AIT_right`,
            FORMAT(c.`atv`, 2) AS `ATV_right`,
            FORMAT(c.`advanceTax`, 2) AS `AT_right`,
            FORMAT(c.`vatOnCnFC`, 2) AS `VAT On C&FC_right`,
            FORMAT(c.`RebateAmount`, 2) AS `VAT On C&FC Rebate_right`,
            FORMAT(c.`customDuty`, 2) AS `C. Duty (Act)_right`
        FROM
            `wc_t_custom_duty` AS c
                LEFT JOIN
            `wc_t_pi` AS po ON c.`poid` = po.`poid`
                LEFT JOIN
            `wc_t_shipment` AS sh ON c.`poid` = sh.`pono` AND c.`shipNo` = sh.`shipNo` 
        $where
        ORDER BY c.`RequisitionDate` ASC;";

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
            foreach ($row as $val) {
                if ($dataArray != "") {
                    $dataArray .= ',';
                }
                if (strpos($val, 'ref=') > 0) {
                    $refval = substr($val, strripos($val, 'ref=') + 4, strripos($val, 'target=') - (strripos($val, 'ref=') + 6));
                    $refvalenc = encryptId($refval);
                    $newval = str_replace('ref=' . $refval, 'ref=' . $refvalenc, $val);
                } else {
                    $newval = $val;
                }
                $dataArray .= '"' . $newval . '"';
            }
            $rowArray .= '['.$dataArray.']';
        }
        $rowArray = '"data": ['.$rowArray.']';

        $json = '{'.$columnArray.','.$rowArray.'}';
    } else{
        $json = '{"columns": [], "data":[]}';
    }
    unset($objdal);

    echo $json;
    
}


?>

