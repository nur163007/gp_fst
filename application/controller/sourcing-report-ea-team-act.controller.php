<?php
/**
 * Created by PhpStorm.
 * User: Shohel
 * Date: 4/18/2017
 * Time: 12:41 PM
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

    if((!empty($_GET["start1"]) && isset($_GET["start1"])) && (!empty($_GET["end1"]) && isset($_GET["end1"]))){

        $start = htmlspecialchars($_GET['start1'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end1'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        //if($where!=""){ $where.=' AND '; }
        $where = "sh.`whReceiveDate` BETWEEN '".$start."' AND '".$end."'";
    }

    if((!empty($_GET["start2"]) && isset($_GET["start2"])) && (!empty($_GET["end2"]) && isset($_GET["end2"]))){

        $start = htmlspecialchars($_GET['start2'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end2'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        if($where!=""){ $where.=' AND '; }
        $where = "sh.`billOfEntryNo` BETWEEN '".$start."' AND '".$end."'";
    }

    if($where!=""){ $where = ' WHERE '.$where.' '; }

    $objdal = new dal();

    $query = "SELECT 
            sh.eaRefNo AS `EA Ref. Number`,
            sh.pono AS `PO No.`,
            '' AS `BTRC NOC No.`,
            '' AS `NOC Date`,
            lc.lcno AS `LC No.`,
            DATE_FORMAT(lc.lcissuedate, '%d-%M-%Y') AS `LC Date`,
            c.name AS `Currency_center`,
            FORMAT(lc.lcvalue,2) AS `LC value_right`,
            sh.ciNo AS `C. Invoice No.`,
            DATE_FORMAT(sh.ciDate, '%d-%M-%Y') AS `Invoice Date`,
            FORMAT(sh.ciAmount,2) AS `Invoice Value_right`,
            sh.shipNo AS `Shipment No._center`,
            sh.hawbNo AS `HAWB Number`,
            sh.mawbNo AS `MAWB Number`,
            sh.blNo AS `BL Number`,
            po.lcdesc AS `Equipments`,
            sh.shipmode AS `Mode of Shipment_center`,
            DATE_FORMAT(sh.docReceiveByEA, '%d-%M-%Y') AS `Document Received Date`,
            sh.billOfEntryNo AS `Bill Of Entry No.`,
            DATE_FORMAT(sh.billOfEntryDate, '%d-%M-%Y') AS `Bill of Entry Date`,
            FORMAT(sh.CDAmount,2) AS `Total CDVAT Amount_right`,
            DATE_FORMAT(sh.actualArrivalAtPort, '%d-%M-%Y') AS `Goods Arrival at Port`,
            DATE_FORMAT(sh.releaseFromPort, '%d-%M-%Y') AS `Port released Date`,
            DATE_FORMAT(sh.whReceiveDate, '%d-%M-%Y') AS `At Warehouse Date`,
            DATEDIFF(sh.whReceiveDate, IF(sh.docReceiveByEA > sh.actualArrivalAtPort, sh.docReceiveByEA, sh.actualArrivalAtPort)) AS `KPI (days)_center`,
            sh.ChargeableWeight AS `Chargeable Weight`,
            FORMAT(sh.demurrageAmount,2) AS `Only Demurrage Amount_right`
        FROM
            wc_t_shipment AS sh
                LEFT JOIN
            wc_t_pi AS po ON sh.pono = po.poid
                LEFT JOIN
            wc_t_lc AS lc ON sh.pono = lc.pono
                LEFT JOIN
            wc_t_category AS c ON po.currency = c.id
        $where
        ORDER BY sh.`eaRefNo` , sh.pono , sh.`shipNo`;";

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