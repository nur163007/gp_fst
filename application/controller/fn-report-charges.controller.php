<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 10/1/2016
 * Time: 12:35 PM
 */

if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])){

    $where = "";

    if (!empty($_GET["start"]) && isset($_GET["start"]) && $_GET["start"] != "" && $_GET["start"] != "undefined" && $_GET["start"] != "null") {

        $start = htmlspecialchars($_GET['start'], ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'], ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = "lc.`lcissuedate` BETWEEN '" . $start . "' AND '" . $end . "'";
    }

    if($where!=""){ $where = ' WHERE '.$where.' '; }

    $objdal = new dal();

    if($_GET["action"]==1){
        $query = "SELECT 
                bc.LcNo AS `LC#`,
                DATE_FORMAT(lc.lcissuedate, '%d-%M-%Y') AS `LC Date`,
                bi.name AS `Bank`,
                '' AS `Bank GL`,
                c.name AS `Currency`,
                FORMAT(lc.lcvalue,2) AS `LC Amount_right`,
                FORMAT(bc.exchangeRate,2) AS `FX rate_right`,
                FORMAT(bc.commission,2) AS `Commission_right`,
                FORMAT(bc.comissionBDT,2) AS `Commission in BDT_right`,
                FORMAT(bc.vatOnComm,2) AS `VAT ON LC Comission_right`,
                FORMAT(bc.vatOnOtherCharge,2) AS `VAT on Other Charges_right`,
                FORMAT(bc.vatOnComm + bc.vatOnOtherCharge,2) AS `Total VAT Charges_right`,
                FORMAT(bc.cableCharge,2) AS `Cable Charge_right`,
                FORMAT(bc.otherCharge,2) AS `Other Charges_right`,
                FORMAT(bc.nonVAtOtherCharge,2) AS `Stamp Charge_right`,
                FORMAT(bc.totalCharge,2) AS `Total_right`,
                FORMAT(bc.capex,2) AS `Capex_right`,
                FORMAT(((bc.vatOnComm + bc.vatOnOtherCharge) / 100) * bc.vatRebateOnOtherChargesRate,
                    2) AS `VAT Rebate_right`
            FROM
                wc_t_lc_opening_bank_charge AS bc
                INNER JOIN wc_t_lc AS lc ON bc.LcNo = lc.lcno
                INNER JOIN wc_t_po AS po ON lc.pono = po.poid
                INNER JOIN wc_t_category AS c ON po.currency = c.id
                INNER JOIN wc_t_bank_insurance AS bi ON lc.lcissuerbank = bi.id $where;";
    }
    if($_GET["action"]==2){
        $query = "SELECT 
            lc.lcno AS `LC#`,
            DATE_FORMAT(lc.lcissuedate, '%d-%M-%Y') AS `LC Date`,
            bi1.name AS `Bank`,
            '' AS `Bank GL`,
            bi2.name AS `Insurance Compnay`,
            ic.coverNoteNo as `Cover Note No.`,
            c.name AS `Currency`,
            FORMAT(lc.lcvalue,2) AS `LC Amount_right`,
            FORMAT(ic.insuranceValue,2) as `Insurance Amount`,
            FORMAT(ic.exchangeRate,2) AS `FX rate_right`,
            FORMAT(ic.assuredAmount,2) AS `Assured amount BDT_right`,
            FORMAT(ic.marine,2) AS `Marine_right`,
            FORMAT(ic.war,2) AS `War_right`,
            FORMAT(ic.netPremium,2) AS `Net premium_right`,
            FORMAT(ic.vat,2) AS `Total VAT_right`,
            FORMAT(ic.stampDuty,2) AS `Stamp charge_right`,
            FORMAT(ic.otherCharges,2) AS `Other charges_right`,
            FORMAT(ic.total,2) AS ` Total_right`,
            FORMAT(ic.capex,2) AS `Capex_right`,
            FORMAT(ic.vatRebateAmount,2) as `VAT Rebate(80%)_right`,
            FORMAT(ic.vatPayable,2) AS `Vat Payable_right`
        FROM
            wc_t_insurance_charge AS ic
            INNER JOIN wc_t_po AS po ON ic.ponum = po.poid
            INNER JOIN wc_t_lc AS lc ON po.poid = lc.pono
            INNER JOIN wc_t_category AS c ON po.currency = c.id
            INNER JOIN wc_t_bank_insurance AS bi1 ON lc.lcissuerbank = bi1.id
            INNER JOIN wc_t_bank_insurance AS bi2 ON lc.insurance = bi2.id $where";
    }

//    echo $where;
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