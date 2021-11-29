<?php
/**
 * Created by Shohel.
 * Date: 9/10/2017
 * Time: 2:12 AM
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

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        //if($where!=""){ $where.=' AND '; }
        $where = "l.`ActionOn` BETWEEN '".$start."' AND '".$end."'";
    }

    if($where!=""){ $where = ' WHERE '.$where.' '; }

    $objdal = new dal();

    $query = "SELECT 
            a.PO,
            DATE_FORMAT(a.PO_Send, '%d-%M-%Y') AS `PO Sent date`,
            DATE_FORMAT(a.PI_Receive, '%d-%M-%Y') AS `PI Receive Date`,
            DATE_FORMAT(a.PI_Rec_SLA_Date, '%d-%M-%Y') AS `PI Receive SLA Date`,
            DATEDIFF(a.PI_Rec_SLA_Date, a.PI_Receive) AS `PI Done_center`,
            DATE_FORMAT(a.BTRC_NOC_Receive, '%d-%M-%Y') AS `BTRC NOC Receive Date`,
            DATE_FORMAT(a.LC_Request_Sent, '%d-%M-%Y') AS `LC Request Sent Date`,
            DATE_FORMAT(a.LC_Req_SLA_Date, '%d-%M-%Y') AS `LC Request Sent SLA Date`,
            DATEDIFF(a.LC_Req_SLA_Date, a.LC_Request_Sent) AS `LC Done_center`,
            DATE_FORMAT(a.GTI_Receive, '%d-%M-%Y') AS `GTI Receive Date`,
            DATE_FORMAT(a.Voucher_Update, '%d-%M-%Y') AS `Voicher Update Date`,
            DATE_FORMAT(a.Invoice_SLA_Date, '%d-%M-%Y') AS `Invoice SLA Date`,
            DATEDIFF(a.Invoice_SLA_Date, a.Voucher_Update) AS `Invoice Done_center`
        FROM
            (SELECT 
                l.PO,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 1 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PO_Send`,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 4 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PI_Receive`,
                (SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 1 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PI_Rec_SLA_Date`,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 24 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `BTRC_NOC_Receive`,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 25 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `LC_Request_Sent`,
                (SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 24 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `LC_Req_SLA_Date`,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 73 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `GTI_Receive`,
                (SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 89 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `Voucher_Update`,
                (SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 73 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `Invoice_SLA_Date`
            FROM
                wc_t_action_log AS l
            $where
            GROUP BY l.PO) a;";

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
                if(is_numeric($val)){
                    if($val<0){
                        $dataArray .= '"<span>' . $val . '</span>"';
                    } else{
                        $dataArray .= '"' . $val . '"';
                    }
                } else {
                    $dataArray .= '"' . $val . '"';
                }
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