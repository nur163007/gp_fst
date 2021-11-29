<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 3/19/2017
 * Time: 5:06 AM
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
    $whereStatus = "";

    if((isset($_GET["buyer"]) && !empty($_GET["buyer"])) && $_GET["buyer"]!="" && $_GET["buyer"]!="undefined" && $_GET["buyer"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`createdby` = ".$_GET["buyer"];
    }

    if((isset($_GET["supplier"]) && !empty($_GET["supplier"])) && $_GET["supplier"]!="" && $_GET["supplier"]!="undefined" && $_GET["supplier"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where .= "po.`supplier` = ".$_GET["supplier"];
    }

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $start .= " 00:00:00";
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));
        $end .= " 23:59:59";

        if($where!=""){ $where.=' AND '; }
        $where = "po.`createdon` BETWEEN '".$start."' AND '".$end."'";
    }

    if((isset($_GET["currentStatus"]) && !empty($_GET["currentStatus"])) && $_GET["currentStatus"]!="" && $_GET["currentStatus"]!="undefined" && $_GET["currentStatus"]!="null"){
        if($where!=""){ $where.=' AND '; }
        $where = "po.`poid` in (SELECT `PO` FROM `wc_t_action_log` WHERE `ActionID` = ".$_GET["currentStatus"]." AND `Status` = 0)";
    }

    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    $query = "SELECT 
        CASE WHEN sh.`shipNo` IS NULL THEN 
			CONCAT('<a href=''view-po?po=', po.`poid`, '&ref=', (SELECT MAX(al.`ID`) FROM `wc_t_action_log` as al INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID`  WHERE PO = po.`poid` AND a.`ActionPending` != 'Acknowledgement'),''' target=''_blank''>', po.`poid`, '</a>')
        ELSE 
			CONCAT('<a href=''view-po?po=', po.`poid`, '&ship=', sh.`shipNo`,'&ref=',(SELECT MAX(al.`ID`) FROM `wc_t_action_log` as al INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID`  WHERE PO = po.`poid` AND a.`ActionPending` != 'Acknowledgement'),''' target=''_blank''>', po.`poid`, '</a>') END AS `PO Number`,
        sh.`shipNo` AS `Shipment#_center`,
        (SELECT `username` FROM `wc_t_users` WHERE id = po.`createdby`) AS `PO Buyer`,
        (SELECT `name` FROM `wc_t_company` WHERE id = po.`supplier`) AS `Supplier`,
        (SELECT `username` FROM `wc_t_users` WHERE id = po.`pruserto`) AS `PR User`,
        (SELECT `contractName` FROM `wc_t_contract` WHERE id = po.`contractref`) AS `Contact Ref`,
        (SELECT `name` FROM `wc_t_category` WHERE id = po.`currency`) AS `Currency_center`,
        FORMAT(po.`povalue`,2) AS `PO Value_right`,
        replace(replace(`podesc`,'\\t',' '),'\\r\\n','') AS `PO Description`,
        DATE_FORMAT(po.`createdOn`,'%d-%M-%Y') AS `PO & BOQ Sent to Vendor`,
        DATE_FORMAT(`deliverydate`,'%d-%M-%Y') AS `PO Need by Date`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Draft_PI_Submitted.") AS `PI & BOQ Receive Date`,
        `productiondays` AS `Days required for shipment after getting LC_center`,
        po.`pinum` AS `PI Number`, DATE_FORMAT(po.`pidate`,'%d-%M-%Y') AS `PI Date`, 
        FORMAT(po.`pivalue`,2) AS `PI Value_right`, UPPER(po.`shipmode`) AS `Shipment Mode_center`,
        FORMAT(`basevalue`,2) AS `Insurance Value_right`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Sent_for_BTRC_Permission.") AS `Request for BTRC Permission`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Accepted_by_BTRC.") AS `BTRC Permission Received`,
        (SELECT GROUP_CONCAT(`percentage` SEPARATOR ',') FROM `wc_t_payment_terms` pt WHERE pt.`pono` = po.`poid` GROUP BY pt.`pono`)  AS `Payment Terms`,
        (SELECT GROUP_CONCAT(`amount` SEPARATOR ',') FROM `wc_t_payment` pm WHERE sh.`ciNo` = pm.`ciNo`)  AS `Sight/CAC/FAC Value`,
        (SELECT GROUP_CONCAT(DATE_FORMAT(`cfacIssueDate`,'%d-%M-%Y') SEPARATOR ',') FROM `wc_t_cacfac_request` cr WHERE sh.`ciNo` = cr.`ciNo`)  AS `Cert. Issue Date`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_LC_Request_Sent.") AS `Apply for LC`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Final_LC_Copy_Sent.") AS `LC Receive Date`,
        (SELECT concat(trim(lc.`lcno`), if(trim(ifnull(lc.`lcno`,'')) ='', (case when lc.`lca` = 0 then 'LC' else 'LCA' end), (case when lc.`lca` = 0 then '<br>LC' else '<br>LCA' end))) FROM `wc_t_lc` AS lc WHERE lc.`pono` = po.`poid`) AS `LC Number`,
        (SELECT FORMAT(lc.`lcvalue`,2) FROM `wc_t_lc` AS lc WHERE lc.`pono` = po.`poid`) AS `LC Value_right`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`shipNo` = sh.`shipno` AND log1.`ActionID`=".action_Original_Document_Delivered.") AS `Scan Copy Receive Date`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`shipNo` = sh.`shipno` AND log1.`ActionID`=".action_Shared_Voucher_info_to_Fin.") AS `Pre-Alert & GIT receiving & Doc Endorse Mail`,
        sh.`ipcNo` AS `IPC Number`,
        DATE_FORMAT(sh.`gitReceiveDate`,'%d-%M-%Y') AS `GIT Received Date`,
        sh.`ciNo` AS `Commercial Invoice Number`,
        DATE_FORMAT(sh.`ciDate`,'%d-%M-%Y') AS `Commercial Invoice Date`,
        sh.`ciAmount` AS `C.Invoice Amount_right`,
        sh.`mawbNo` as `MAWB No.`,
        sh.`hawbNo` as `HAWB No.`,
        sh.`blNo` as `BL No.`,
        sh.eaRefNo AS `GP Ref: no`,
        DATE_FORMAT(sh.`awbOrBlDate`,'%d-%M-%Y') AS `AWB/BL Date`,
        '-' AS `Description (For partial shipment only)`,
        sh.`GERPVoucherNo` AS `Voucher No`,
        DATE_FORMAT(sh.`GERPVoucherDate`,'%d-%M-%Y') AS `Voucher Creation Date`,
        DATE_FORMAT(sh.`scheduleETA`,'%d-%M-%Y') AS `ETA`,
        sh.`dhlTrackNo` AS `DHL`,
        DATE_FORMAT(`whArrivalDate`,'%d-%M-%Y') AS `Actual Arrival at WH`,
        (SELECT GROUP_CONCAT(CONCAT('<span class=''custome-db-column01''>',a.ActionPending,'<span>') SEPARATOR ',<br/>')
            FROM wc_t_action_log as l INNER JOIN wc_t_action AS a ON l.ActionID = a.ID
            where l.PO = po.`poid` and l.`shipNo` = sh.`shipNo` and l.Status = 0 and a.ActionPending != 'Acknowledgement'
            order by l.ID desc) AS `Status (pending for)`
        FROM `wc_t_po` po 
        LEFT JOIN `wc_t_shipment` AS sh ON po.`poid` = sh.`pono`
        $where        
        ORDER BY po.`poid`, sh.`shipNo`;";

//    echo $where;
    //echo $query;

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


