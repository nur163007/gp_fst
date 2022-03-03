<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 07-Feb-19
 * Time: 4:25 PM
 */

if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    if(isset($_GET['startDate'])) {
        $startDate = $_GET['startDate'];
    } else {
        $startDate = 'null';
    }

    if(isset($_GET['endDate'])) {
        $endDate = $_GET['endDate'];
    } else {
        $endDate = 'null';
    }

    if(isset($_GET['poNo'])) {
        $poNo = $_GET['poNo'];
    } else {
        $poNo = 'null';
    }
    switch($_GET["action"])
    {
        case 1:
            echo getPoList();
            break;
        case 2:
            echo getPayHistory($_GET["poNo"]);
            break;
        case 3:
            echo getPayMaturity($startDate, $endDate, $poNo);
            break;

    }
}

/*********GET PO LIST**********
 **********CREATED BY: HASAN MASUD******/
function getPoList()
{
    $objdal=new dal();
    $sql = "SELECT `poid` FROM `wc_t_pi` GROUP BY `poid`;";
    $objdal->read($sql);
    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$poid.'", "text": "'.$poid.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;
}

/*GET PAYMENT HISTORY STATUS - ACTION - 2
*********CREATED BY: HASAN MASUD*********/
function getPayHistory($poNo)
{
    $objdal = new dal();
    $query = "SELECT 
                pt.`pono`,
                c.`name` AS `payDocName`,
                pt.`percentage` AS `paymentPercent`,
                lc.`lcno`,
                sh.`ciNo`,
                (SELECT `amount` FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) `payAmount`,
                (SELECT DATE_FORMAT(`payDate`,'%d-%M-%Y') FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) `payDate`
            FROM
                `wc_t_payment_terms` pt
                INNER JOIN `wc_t_lc` lc ON pt.`pono` = lc.`pono`
                INNER JOIN `wc_t_category` c ON c.`id` = pt.`partname`
                INNER JOIN `wc_t_shipment` sh ON lc.`lcno` = sh.`lcNo`
            WHERE
                pt.`pono` = '$poNo'
            ORDER BY sh.`ciNo`, pt.`partname`;";
    //echo $query;
    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    return json_encode($rows);
}

/*GET PAYMENT HISTORY STATUS - ACTION - 3
*********CREATED BY: HASAN MASUD*********/
function getPayMaturity($startDate, $endDate, $poNo)
{
    $where = '';
    if(!empty($startDate) && $startDate!="" && $startDate!="undefined" && $startDate!="null"){
        $start = htmlspecialchars($startDate,ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($endDate,ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));
        $where = " DATE_ADD(sh.`awbOrBlDate`, INTERVAL pt.`dayofmaturity` DAY ) BETWEEN '".$start.' 00:00:00'."' AND '".$end.' 23:59:59'."'";
    }

    if($poNo!="" && $poNo!="undefined" && $poNo!="null") {
        if ($where != '') {
            $where .= ' AND ';
        }
        $where .= "pt.`pono` = " . "'$poNo'";
    }
    if($where!='') {
        $where = ' WHERE ' . $where;
    }
    $objdal = new dal();
    $query = "SELECT 
                pt.`pono`,
                c.`name` AS `payDocName`,
                pt.`percentage` AS `paymentPercent`,
                lc.`lcno`,
                sh.`ciNo`,
                sh.`ciAmount`,
                c1.`name` AS `currencyName`,
                (SELECT `amount` FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) `paidAmount`,
                (SELECT DATE_FORMAT(`payDate`,'%d-%M-%Y') FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) `paidDate`,
                sh.`awbOrBlDate`,
                pt.`dayofmaturity`,
                ((sh.`ciAmount`*pt.`percentage`)/100) AS `payableAmount`,
                DATE_FORMAT(DATE_ADD(sh.`awbOrBlDate`, INTERVAL pt.`dayofmaturity` DAY ),'%d-%M-%Y') AS `payableDate`
            FROM
                `wc_t_payment_terms` pt
                INNER JOIN `wc_t_lc` lc ON pt.`pono` = lc.`pono`
                INNER JOIN `wc_t_category` c ON c.`id` = pt.`partname`
                INNER JOIN `wc_t_pi` po ON po.`poid` = pt.`pono`
                INNER JOIN `wc_t_category` c1 ON c1.`id` = po.`currency`
                INNER JOIN `wc_t_shipment` sh ON lc.`lcno` = sh.`lcNo`
            $where
          
            ORDER BY sh.`ciNo`, pt.`partname`;";
    //echo $query;
    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    return json_encode($rows);
}
