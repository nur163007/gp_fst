<?php
if ( !session_id() ) {
    session_start();
}
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 1/25/2017
 * Time: 8:06 PM
 */

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])){

    $where = "";

    if((isset($_GET["buyerList"]) && !empty($_GET["buyerList"])) && $_GET["buyerList"]!="" && $_GET["buyerList"]!="undefined" && $_GET["buyerList"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`createdby` = ".$_GET["buyerList"];
    }

    if((isset($_GET["supplier"]) && !empty($_GET["supplier"])) && $_GET["supplier"]!="" && $_GET["supplier"]!="undefined" && $_GET["supplier"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`supplier` = ".$_GET["supplier"];
    }


    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        if($where!=""){ $where.=' AND '; }
        $where = "po.`createdon` BETWEEN '".$start."' AND '".$end."'";
    }

    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT 
                po.`poid` AS `PO Number`,
                (SELECT `username` FROM `wc_t_users` WHERE id = po.`createdby`) AS `PO Buyer`,
                (SELECT `name` FROM `wc_t_company` WHERE id = po.`supplier`) AS `Supplier`,
                `podesc` AS `PO Description`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_New_PO_Initiated.") AS `PO & BOQ Sent to Vendor`,
                DATE_FORMAT(`deliverydate`,'%d-%M-%Y') AS `PO Need by Date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Draft_PI_Submitted.") AS `PI & BOQ Receive Date`,                
                `productiondays` AS `Lead Time`,
                `basevalue` AS `Discount`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Sent_for_BTRC_Permission.") AS `Request for BTRC Permission`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Accepted_by_BTRC.") AS `BTRC Permission Received`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_LC_Request_Sent.") AS `Apply for LC`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Final_LC_Copy_Sent.") AS `LC Receive Date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Original_Document_Delivered.") AS `Scan Copy Receive Date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Shared_Voucher_info_to_Fin.") AS `Pre-Alert & GIT receiving & Doc Endorse Mail`,
                (SELECT DATE_FORMAT(`gitReceiveDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `GIT Received Date`,
                (SELECT DATE_FORMAT(`awbOrBlDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `AWB No / BL No`,
                (SELECT `ciNo` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `CInvoice Number`,
                (SELECT DATE_FORMAT(`ciDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `CInvoice Date`,
                (SELECT `ciAmount` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `CInvoice Amount`,
                '-' AS `Description (For partial shipment only)`,
                (SELECT `GERPVoucherNo` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `Voucher No`,
                (SELECT DATE_FORMAT(`GERPVoucherDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `V Creation Date`,
                (SELECT DATE_FORMAT(`scheduleETA`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `ETA`,
                (SELECT DATE_FORMAT(`whArrivalDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `Actual Arrival at WH`
            FROM `wc_t_po` po $where;";
//    echo $query;
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
    /*$objdal->read(trim($query));
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

    echo $json;*/

}


?>

