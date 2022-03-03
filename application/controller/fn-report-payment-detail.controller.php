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

    if((isset($_GET["po"]) && !empty($_GET["po"])) && $_GET["po"]!="" && $_GET["po"]!="undefined" && $_GET["po"]!="null"){
        $where = "lc.`pono` = ".$_GET["po"];
    }

    if((isset($_GET["lc"]) && !empty($_GET["lc"])) && $_GET["lc"]!="" && $_GET["lc"]!="undefined" && $_GET["lc"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "p.`LcNo` = ".$_GET["lc"];
    }

    if((isset($_GET["lcbank"]) && !empty($_GET["lcbank"])) && $_GET["lcbank"]!="" && $_GET["lcbank"]!="undefined" && $_GET["lcbank"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "lc.`lcissuerbank` = ".$_GET["lcbank"];
    }

    if((isset($_GET["sourcebank"]) && !empty($_GET["sourcebank"])) && $_GET["sourcebank"]!="" && $_GET["sourcebank"]!="undefined" && $_GET["sourcebank"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "p.`fundCollectFrom` = ".$_GET["sourcebank"];
    }

    if((isset($_GET["supplier"]) && !empty($_GET["supplier"])) && $_GET["supplier"]!="" && $_GET["supplier"]!="undefined" && $_GET["supplier"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`supplier` = ".$_GET["supplier"];
    }

    if((isset($_GET["currency"]) && !empty($_GET["currency"])) && $_GET["currency"]!="" && $_GET["currency"]!="undefined" && $_GET["currency"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`currency` = ".$_GET["currency"];
    }

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        if($where!=""){ $where.=' AND '; }
        $where .= "p.`payDate` BETWEEN '".$start."' AND '".$end."'";
    }
    
    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT @rownum:=@rownum + 1 as `SL`, 
               t.*, datediff(`bankNotifyDate`,`docDeliveredByFin`) AS `GrossDay`,
               null `WeekendHoliday`,
               null `ActualDayRequired`
        FROM ( 
        SELECT 
            DATE_FORMAT(p.`bankNotifyDate`, '%d-%M-%Y') AS `BankNotificationDate`,
            DATE_FORMAT(p.`payDate`, '%d-%M-%Y') AS `PaymentDate`,
            lc.`pono` AS `PONo`,
            p.`LcNo` AS `LCNo`,
            bi1.`name` AS `LCissuingBank`,
            bi2.`name` AS `SourcingBank`,
            co.`name` AS `Supplier`,
            cat1.`name` AS `Currency`,
            `p`.`ciNo`,
            sh.`GERPVoucherNo` AS `GERPInvoice`,
            FORMAT(sh.`ciAmount`, 2) AS `InvoiceValue`,
            FORMAT(p.`amount`, 2) AS `PaymentAmount`,
            FORMAT((p.`amount` * lc.`xeUSD`),2) AS `PaymentAmountUSD`,
            FORMAT((p.`amount` * p.`exchangeRate`),2) AS `PaymentAmountBDT`,
            FORMAT(p.`exchangeRate`, 2) AS `FXRate`,
            cat2.`name` AS `BasisOfPayment`,
            p.`paymentPercent` AS `InvoicePortion`,
            FORMAT(p.`bcSellingRate`,2) AS `BCSellingRate`,
            FORMAT(sh.`GERPExchangeRate`,2) AS `InvoiceBookingRate`,
            FORMAT((p.`bcSellingRate`-p.`exchangeRate`),2) AS `GrossSavingFxRate`,
            FORMAT((sh.`GERPExchangeRate`-p.`exchangeRate`),2) AS `NetSavingFxRate`,
            FORMAT((p.`amount`*(p.`bcSellingRate`-p.`exchangeRate`)),2) AS `GrossCostSavingsAgainstLCPaymentBDT`,
            FORMAT((p.`amount`*(sh.`GERPExchangeRate`-p.`exchangeRate`)),2) AS `NetCostSavingsAgainstLCPaymentBDT`,
            p.`bankNotifyDate`,
            sh.`docDeliveredByFin`,
            DATE_FORMAT(sh.`docDeliveredByFin`,'%d-%M-%Y') AS `DocDelivered`
        FROM
            `wc_t_payment` AS p
                INNER JOIN `wc_t_lc` AS lc ON p.`LcNo` = lc.`lcno`
                INNER JOIN `wc_t_pi` AS po ON lc.`pono` = po.`poid`
                INNER JOIN `wc_t_shipment` AS sh ON p.`LcNo` = sh.`lcNo`
                INNER JOIN `wc_t_bank_insurance` AS bi1 ON lc.`lcissuerbank` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` AS bi2 ON p.`fundCollectFrom` = bi2.`id`
                INNER JOIN `wc_t_company` AS co ON po.`supplier` = co.`id`
                INNER JOIN `wc_t_category` AS cat1 ON po.`currency` = cat1.`id`
                INNER JOIN `wc_t_category` AS cat2 ON p.`docName` = cat2.`id` $where
        ) t,
        (SELECT @rownum := 0) r;";
    //echo $query;
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

}


?>

