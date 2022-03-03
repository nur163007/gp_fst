<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 2020-10-28
 *   i. Partial Payment =  FAC will be 100%
    ii. When payment is 100% in sight =  WH Arrival date will be close date
 * Aging Calculation:
        0.1 PO Stage: PO Approve Date || PO & BOQ Sent to Vendor not blank, but Draft PI Date is blank
    1. PI Ageing = PI Sent to CA || Today - PO Approve Date || PO & BOQ Sent to Vendor
        1.1 PI Stage: Draft PI Date not blank, but PI Sent to CA is blank
    2. CA Ageing = Request for BTRC Permission || Today - PI Sent to CA
        2.1 CA Stage: PI Sent to CA not blank, but Request for BTRC Permission is blank
    3. BTRC Ageing = BTRC Permission Received || Today - Request for BTRC Permission
        3.1 BTRC Stage: Request for BTRC Permission not blank, but BTRC Permission Received is blank
    4. LC Ageing = LC Accept Date || Today - BTRC Permission Received
        4.1 LC Stage: BTRC Permission Received not blank, but LC Accept Date is blank
    5. Production = Air Way Bill/BL Date || Scan Copy Receive Date(For e-delivery) || Today - LC Accept Date
        5.1 Production Stage: LC Issue Date not blank, but Air Way Bill/BL Date || Scan Copy Receive Date(For e-delivery) is blank
    6. Shipment = Doc Receive By EA || Today - Air Way Bill/BL Date(Shipment Docs Share - 59) || Actual Arrival Date
        6.1 Shipment Stage: Scan Copy Receive Date not blank, but Doc Receive By EA is blank
    7. Origin to Destination = Bill of Entry Date || Today - Air Way Bill/BL Date
    8. Custom Clearance = Release From Port || Today - Doc Receive By EA
        8.1 Custom Clearance Stage: Doc Receive By EA not blank, but Release From Port is blank
    9. Warehouse Physical Receiving = WH Physical Receiving Date || Today - Release From Port
        9.1 Warehouse Physical Receiving Stage: Release From Port not blank, but WH Physical Receiving Date is blank
   10. CAC Ageing: CAC Payment Date || PAC Payment Date || DFS Payment Date || Today - Sight Payment Date
        10.1 CAC Stage: WH Physical Receiving Date not blank, but CAC Payment Date is blank
   11. FAC Ageing: Today || FAC Payment Date - CAC Payment Date || PAC Payment Date || DFS Payment Date
   11.1 FAC Stage: CAC Payment Date || PAC Payment Date not blank, but FAC Payment Date is blank
   12.1 GLC Ageing: GLC Payment Date || Today - FAC Payment Date

    * Note 1: For some PO in certificate stage WH Physical Receiving Date will be missing as the user did not put the date.
 */

if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");


if (!empty($_GET["action"]) || isset($_GET["action"])){
    $objdal = new dal();

    $where = "";

    $poNo = $objdal->sanitizeInput($_GET["poNo"]);
    $stage = $objdal->sanitizeInput($_GET["stage"]);
    $dtStart = $objdal->sanitizeInput($_GET["dtStart"]);
    $dtEnd = $objdal->sanitizeInput($_GET["dtEnd"]);
    $supplier = $objdal->sanitizeInput($_GET["supplier"]);

    /*if((empty($poNo) || $poNo =="null") || (empty($stage) || $stage =="null") || (empty($dtStart) || $dtStart =="null")
        || (empty($dtEnd) || $dtEnd =="null") || (empty($supplier) || $supplier =="null")){
        $startDate = date('Y-m-d 00:00:00', strtotime('-180 days'));
        $endDate = date('Y-m-d 23:59:59');
        if($where!=""){ $where.=' AND '; }
        $where .= " log.`ActionOn` BETWEEN '".$startDate."' AND '".$endDate."'";
    }*/

    if((isset($poNo) && !empty($poNo)) && $poNo!="" && $poNo!="undefined" && $poNo!="null"){
        if($where!=""){ $where.=' AND '; }
        $where .= "x.`poid` = '$poNo'";
    }

    if((isset($stage) && !empty($stage)) && $stage!="" && $stage!="undefined" && $stage!="null"){
        if ($stage!="All") {
            if ($where != "") { $where .= ' AND '; }
            $where .= "x.`Stage` = '$stage'";
        }
    }

    if((isset($dtStart) && !empty($dtStart)) && $dtStart!="" && $dtStart!="undefined" && $dtStart!="null" &&
        (isset($dtEnd) && !empty($dtEnd)) && $dtEnd!="" && $dtEnd!="undefined" && $dtEnd!="null"){
        $start = date('Y-m-d', strtotime($dtStart));
        $start .= " 00:00:00";
        $end = date('Y-m-d', strtotime($dtEnd));
        $end .= " 23:59:59";
        if($where!=""){ $where.=' AND '; }
        $where .= "x.`createdon` BETWEEN '".$start."' AND '".$end."'";
    }

    if((isset($supplier) && !empty($supplier)) && $supplier!="" && $supplier!="undefined" && $supplier!="null"){
        if($where!=""){ $where.=' AND '; }
        $where .= "x.`supplier` = $supplier";
    }


    if($where!=""){ $where = ' WHERE '.$where; }

    $query = "SELECT x.`Stage`, x.`poid` AS `PO#`, IF(x.`shipNo` = 0, '', x.`shipNo`) AS `Shipment`, TRIM(CONCAT(u.`firstname`,' ', IFNULL(u.`lastname`,'')))
            AS `PR Requester Name`, u.`department` AS `Dept. Name`, u.`mobile` AS `User Phone Number`, u.`email` AS `User Email address`,
            x.`podesc` AS `PO Description`, TRIM(CONCAT(ub.`firstname`,' ', IFNULL(ub.`lastname`,''))) AS `PO Buyer Name`,
            DATE_FORMAT(x.`actualPoDate`, '%d-%M-%Y') AS `PO Approve Date`, 
            DATE_FORMAT(x.`deliverydate`, '%d-%M-%Y') AS `PO Need by Date`, c.`name` AS `Supplier Name`, ct.`contractName`
            AS `Contact Ref`,(SELECT GROUP_CONCAT(pt.`percentage` SEPARATOR '-') FROM `wc_t_payment_terms` pt WHERE pt.`pono` = x.`poid`) 
            AS `Payments Term`, cr.`name` AS `Currency`, FORMAT(x.`povalue`,2) AS `PO Value`, FORMAT(x.`basevalue`,2) 
            AS `Base/Insurance Value`, DATE_FORMAT(x.`createdon`, '%d-%M-%Y') AS `PO & BOQ Sent to Vendor`, 
            SUBSTRING_INDEX(x.`poid`, 'PI', 1) AS `PO`, SUBSTRING_INDEX(x.`poid`, 'PI', -1) AS `PI`, UPPER(x.`shipmode`) 
            AS `Shipment Mode`, DATE_FORMAT(x.`draftPIDate`, '%d-%M-%Y') AS `Draft PI Date`, x.`pinum` AS `PI Number`,
            DATE_FORMAT(x.`finalPIDate`, '%d-%M-%Y') AS `Final PI Date`,
            DATE_FORMAT(x.`preNocApproveDate`, '%d-%M-%Y') AS `PI Sent to CA`,
            DATE_FORMAT(x.`btrcNOCReqDate`, '%d-%M-%Y') AS `Request for BTRC Permission`,
            DATE_FORMAT(x.`btrcAcceptDate`, '%d-%M-%Y') AS `BTRC Permission Received`,
            x.`btrcNocNo` AS `BTRC NOC Number`, DATE_FORMAT(x.`btrcNocDate`, '%d-%M-%Y') AS `BTRC NOC Date`,
            (SELECT DATE_FORMAT(al6.`ActionOn`, '%d-%M-%Y') FROM `wc_t_action_log` al6 WHERE al6.`PO` = x.`poid` AND al6.`ActionID` =".action_LC_Request_Sent." ORDER BY al6.`ID` DESC LIMIT 1) AS `Apply for LC Date`,
            bi.`name` AS `LC Issuing Bank`, lc.`lcno` AS `LC Number`, DATE_FORMAT(lc.`lcissuedate`, '%d-%M-%Y') AS `LC Issue Date`,
            FORMAT(lc.`lcvalue`, 2) AS `LC Value`, DATE_FORMAT(x.`lcAcceptDate`, '%d-%M-%Y') AS `LC Accept Date`, DATE_FORMAT(x.`scheduleETD`, '%d-%M-%Y') AS `ETD`,
            CASE
                WHEN x.`shipmode` = 'sea' THEN x.`blNo`
                WHEN x.`shipmode` = 'air' THEN CONCAT('MAWB: ',x.`mawbNo`, ' | ', 'HAWB: ', x.`hawbNo`)
                WHEN x.`shipmode` = 'E-Delivery' THEN ''
                ELSE ''
            END AS `Air Way Bill/BL Number`,
            DATE_FORMAT(x.`awbOrBlDate`, '%d-%M-%Y') AS `Air Way Bill/BL Date`,
            DATE_FORMAT(x.`shipDocShareDate`, '%d-%M-%Y') AS `Scan Copy Receive Date`,
            (SELECT DATE_FORMAT(al8.`ActionOn`, '%d-%M-%Y') FROM `wc_t_action_log` al8 WHERE al8.`PO` = x.`poid` AND al8.`ActionID` =".action_Requested_for_EA_Inputs." AND al8.`shipNo` = x.`shipNo` ORDER BY al8.`ID` DESC LIMIT 1) AS `Pre-Alert Date`,
            DATE_FORMAT(x.`gitReceiveDate`, '%d-%M-%Y') AS `GIT Received Date`,
            (SELECT DATE_FORMAT(al9.`ActionOn`, '%d-%M-%Y') FROM `wc_t_action_log` al9 WHERE al9.`PO` = x.`poid` AND al9.`ActionID` =".action_Endorsed_Document_Delivered." AND al9.`shipNo` = x.`shipNo` ORDER BY al9.`ID` DESC LIMIT 1) AS `Doc Endorse Mail Date`,
            x.`ipcNo` AS `GIT (IPC ) Number`, x.`ciNo` AS `CI Number`, DATE_FORMAT(x.`ciDate`, '%d-%M-%Y') AS `CI Date`,
            FORMAT(x.`ciAmount`,2) AS `CI Amount`, CONCAT('No. of Boxes: ',x.`noOfBoxes`,'<br>', ' Chargeable Weight: ', x.`ChargeableWeight`) 
            AS `Shipment Description`, x.`GERPVoucherNo` AS `Voucher No`, DATE_FORMAT(x.`GERPVoucherDate`, '%d-%M-%Y') 
            AS `Voucher Creation Date`, x.`dhlTrackNo` AS `DHL Tracking Number`,
            (SELECT DATE_FORMAT(e.`endDate`, '%d-%M-%Y') FROM `wc_t_endorsement` e WHERE e.`ciNo` = x.`ciNo` LIMIT 1) AS `Endorsement Date`,
            x.`eaRefNo` AS `GP Ref: No`, DATE_FORMAT(x.`actualArrivalAtPort`, '%d-%M-%Y') AS `Actual Arrival Date`, 
            x.`billOfEntryNo` AS `Bill of Entry Number`, DATE_FORMAT(x.`billOfEntryDate`, '%d-%M-%Y') AS `Bill of Entry Date`,  
            DATE_FORMAT(x.`releaseFromPort`, '%d-%M-%Y') AS `Release from Port`,DATE_FORMAT(x.`docReceiveByEA`, '%d-%M-%Y') AS `Doc Receive By EA`, 
            FORMAT(cd.`CdPayAmount`, 2) AS `Customs Payment Amount (Total Duty)`, DATE_FORMAT(x.`whReceiveDate`, '%d-%M-%Y') AS `Actual Arrival at WH (PGRD Date)`, 
                DATE_FORMAT(x.`whArrivalDate`, '%d-%M-%Y') AS `WH Physical Receiving Date`,
                (SELECT FORMAT(py1.`amount`, 2) FROM `wc_t_payment` py1 WHERE py1.`docName` = 6 AND py1.`ciNo` = x.`ciNo` ORDER BY py1.`id` LIMIT 1) AS `Sight Value`,            
                DATE_FORMAT(x.`sightPayDate`, '%d-%M-%Y') AS `Sight Payment Date`,
                (SELECT FORMAT(py3.`amount`, 2) FROM `wc_t_payment` py3 WHERE py3.`docName` = 7 AND py3.`ciNo` = x.`ciNo` ORDER BY py3.`id` LIMIT 1) AS `CAC Value`,            
                DATE_FORMAT(IFNULL(x.`cacPayDate`, IFNULL(x.`pacPayDate`, x.`dfsPayDate`)), '%d-%M-%Y') AS `CAC Payment Date`,
                (SELECT FORMAT(py5.`amount`, 2) FROM `wc_t_payment` py5 WHERE py5.`docName` = 8 AND py5.`ciNo` = x.`ciNo` ORDER BY py5.`id` LIMIT 1) AS `FAC Value`,            
                DATE_FORMAT(x.`facPayDate`, '%d-%M-%Y') AS `FAC Payment Date`,
                (SELECT FORMAT(py7.`amount`, 2) FROM `wc_t_payment` py7 WHERE py7.`docName` = 81 AND py7.`ciNo` = x.`ciNo` ORDER BY py7.`id` LIMIT 1) AS `Go Live Value`,            
                DATE_FORMAT(x.`glcPayDate`, '%d-%M-%Y') AS `Go Live Payment Date`,
                                
                CEILING( TIMESTAMPDIFF( DAY, IFNULL(x.`actualPoDate`, x.`createdon`), IFNULL(x.`preNocApproveDate`, CURDATE()))) AS `PI Ageing`,
                CEILING( TIMESTAMPDIFF( DAY, x.`preNocApproveDate`, IFNULL(x.`btrcNOCReqDate`, CURDATE()))) AS `CA Ageing`,
                CEILING( TIMESTAMPDIFF( DAY, x.`btrcNOCReqDate`, IFNULL(x.`btrcAcceptDate`, CURDATE()))) AS `BTRC Ageing`,
                CEILING( TIMESTAMPDIFF( DAY, x.`btrcAcceptDate`, IFNULL(x.`lcAcceptDate`, CURDATE()))) AS `LC Ageing`,  
                CASE
                    WHEN x.`shipmode` = 'E-Delivery' THEN CEILING( TIMESTAMPDIFF( DAY, x.`lcAcceptDate`, IFNULL(x.`shipDocShareDate`, CURDATE())))
                    ELSE CEILING( TIMESTAMPDIFF( DAY, x.`lcAcceptDate`, IFNULL(x.`awbOrBlDate`, CURDATE())))
                END AS `Production`, 
                CEILING( TIMESTAMPDIFF( DAY, IFNULL(x.`awbOrBlDate`, x.`actualArrivalAtPort`), IFNULL(x.`docReceiveByEA`, CURDATE()))) AS `LC to Shipment`,                
                CEILING( TIMESTAMPDIFF( DAY, x.`docReceiveByEA`, IFNULL(x.`releaseFromPort`, CURDATE()))) AS `Custom Clearance`,                 
                CEILING( TIMESTAMPDIFF( DAY, x.`releaseFromPort`, IFNULL(x.`whArrivalDate`, CURDATE()))) AS `WH Physical Receiving`, 
                (
                    CEILING( TIMESTAMPDIFF( DAY, IFNULL(x.`actualPoDate`, x.`createdon`), IFNULL(x.`preNocApproveDate`, CURDATE()))) +
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, x.`preNocApproveDate`, IFNULL(x.`btrcNOCReqDate`, CURDATE()))),0) +
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, x.`btrcNOCReqDate`, IFNULL(x.`btrcAcceptDate`, CURDATE()))),0) +
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, x.`btrcAcceptDate`, IFNULL(x.`lcAcceptDate`, CURDATE()))), 0) + 
                    IFNULL(CASE
                        WHEN x.`shipmode` = 'E-Delivery' THEN CEILING( TIMESTAMPDIFF( DAY, x.`lcAcceptDate`, IFNULL(x.`shipDocShareDate`, CURDATE())))
                        ELSE CEILING( TIMESTAMPDIFF( DAY, x.`lcAcceptDate`, IFNULL(x.`awbOrBlDate`, CURDATE())))
                    END, 0) +                
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, IFNULL(x.`awbOrBlDate`, x.`actualArrivalAtPort`), IFNULL(x.`docReceiveByEA`, CURDATE()))), 0) +                 
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, x.`docReceiveByEA`, IFNULL(x.`releaseFromPort`, CURDATE()))), 0) +                 
                    IFNULL(CEILING( TIMESTAMPDIFF( DAY, x.`releaseFromPort`, IFNULL(x.`whArrivalDate`, CURDATE()))), 0) 
                ) AS `Total Lead Time`,
                CEILING( TIMESTAMPDIFF( DAY, x.`sightPayDate`, IFNULL(IFNULL(x.`cacPayDate`, IFNULL(x.`pacPayDate`, x.`dfsPayDate`)), CURDATE()))) AS `CAC Ageing`,                 
                CEILING( TIMESTAMPDIFF( DAY, IFNULL(x.`cacPayDate`, IFNULL(x.`pacPayDate`, x.`dfsPayDate`)), IFNULL(x.`facPayDate`, CURDATE()))) AS `FAC Ageing`,                 
                CEILING( TIMESTAMPDIFF( DAY, x.`facPayDate`, IFNULL(x.`glcPayDate`, CURDATE()))) AS `GLC Ageing`
            FROM
         (SELECT
                (SELECT al0.`ActionOn` FROM `wc_t_action_log` al0 WHERE al0.`PO` = p.`poid` AND al0.`ActionID` =".action_Draft_PI_Submitted." ORDER BY al0.`ID` DESC LIMIT 1) AS `draftPIDate`,
                (SELECT al2.`ActionOn` FROM `wc_t_action_log` al2 WHERE al2.`PO` = p.`poid` AND al2.`ActionID` =".action_Final_PI_Submitted." ORDER BY al2.`ID` DESC LIMIT 1) AS `finalPIDate`,
                (SELECT al3.`ActionOn` FROM `wc_t_action_log` al3 WHERE al3.`PO` = p.`poid` AND al3.`ActionID` =".action_BTRC_Process_Approved_by_3rd_Level." ORDER BY al3.`ID` DESC LIMIT 1) AS `preNocApproveDate`,
                (SELECT al4.`ActionOn` FROM `wc_t_action_log` al4 WHERE al4.`PO` = p.`poid` AND al4.`ActionID` =".action_Sent_to_BTRC_for_NOC." ORDER BY al4.`ID` DESC LIMIT 1) AS `btrcNOCReqDate`,
                (SELECT al5.`ActionOn` FROM `wc_t_action_log` al5 WHERE al5.`PO` = p.`poid` AND al5.`ActionID` =".action_Accepted_by_BTRC." ORDER BY al5.`ID` DESC LIMIT 1) AS `btrcAcceptDate`,
                (SELECT al7.`ActionOn` FROM `wc_t_action_log` al7 WHERE al7.`PO` = p.`poid` AND al7.`ActionID` =" . action_Shared_Shipment_Document . " AND al7.`shipNo` = IFNULL(s.`shipNo`, 0) ORDER BY al7.`ID` DESC LIMIT 1) AS `shipDocShareDate`,
                (SELECT al10.`ActionOn` FROM `wc_t_action_log` al10 WHERE al10.`PO` = p.`poid` AND al10.`ActionID` =" . action_LC_Accepted . " ORDER BY al10.`ID` DESC LIMIT 1) AS `lcAcceptDate`,
                (SELECT py2.`payDate` FROM `wc_t_payment` py2 WHERE py2.`docName` = " . payment_Sight . " AND py2.`ciNo` = s.`ciNo` ORDER BY py2.`id` LIMIT 1) AS `sightPayDate`, 
                (SELECT py3.`payDate` FROM `wc_t_payment` py3 WHERE py3.`docName` = " . payment_CAC . " AND py3.`ciNo` = s.`ciNo` ORDER BY py3.`id` LIMIT 1) AS `cacPayDate`, 
                (SELECT py4.`payDate` FROM `wc_t_payment` py4 WHERE py4.`docName` = " . payment_FAC . " AND py4.`ciNo` = s.`ciNo` ORDER BY py4.`id` LIMIT 1) AS `facPayDate`, 
                (SELECT py8.`payDate` FROM `wc_t_payment` py8 WHERE py8.`docName` = " . payment_GLC . " AND py8.`ciNo` = s.`ciNo` ORDER BY py8.`id` LIMIT 1) AS `glcPayDate`, 
                (SELECT py9.`payDate` FROM `wc_t_payment` py9 WHERE py9.`docName` = " . payment_PAC . " AND py9.`ciNo` = s.`ciNo` ORDER BY py9.`id` LIMIT 1) AS `pacPayDate`,  
                (SELECT py10.`payDate` FROM `wc_t_payment` py10 WHERE py10.`docName` = " . payment_DFS . " AND py10.`ciNo` = s.`ciNo` ORDER BY py10.`id` LIMIT 1) AS `dfsPayDate`, 
                p.`poid`, p.`pruserto`, IFNULL(s.`shipNo`, 0) AS `shipNo`, MAX(a.`serialNo`) AS `StageSN`, p.`podesc`,
                p.`createdby`, p.`deliverydate`, p.`supplier`, p.`contractref`, p.`currency`, p.`povalue`, p.`basevalue`,
                p.`createdon`, s.`shipmode`, p.`pidate`, p.`pinum`, s.`btrcNocNo`, s.`btrcNocDate`, s.`scheduleETD`, s.`blNo`,
                s.`mawbNo`, s.`hawbNo`, s.`awbOrBlDate`, s.`gitReceiveDate`, s.`ipcNo`, s.`ciNo`, s.`ciDate`, s.`ciAmount`,
                s.`noOfBoxes`, s.`ChargeableWeight`, s.`GERPVoucherNo`, s.`GERPVoucherDate`, s.`dhlTrackNo`, s.`eaRefNo`,
                s.`actualArrivalAtPort`, s.`billOfEntryNo`, s.`billOfEntryDate`, s.`whReceiveDate`, s.`inserton`, s.`whArrivalDate`,
                s.`docReceiveByEA`, s.`releaseFromPort`, p.`actualPoDate`,
            (SELECT a2.`stage` FROM `wc_t_action_log` al1
                INNER JOIN `wc_t_action` a2 ON al1.`ActionID` = a2.`ID`
                WHERE al1.`PO` = al.`PO` AND IFNULL(al1.`shipNo`, 0) = IFNULL(s.`shipNo`, 0)
                ORDER BY a2.`serialNo` DESC, al1.`ActionOn` DESC LIMIT 1) AS `Stage`
     FROM `wc_t_pi` p
        LEFT JOIN `wc_t_shipment` s ON p.`poid` = s.`pono`
        INNER JOIN `wc_t_action_log` al ON p.`poid` = al.`PO` AND IFNULL(s.`shipNo`, 0) = IFNULL(al.`shipNo`, 0)
        INNER JOIN `wc_t_action` a ON al.`ActionID` = a.`ID` GROUP BY p.`poid`, s.`shipNo` ORDER BY p.`poid` , s.`shipNo`) x
        LEFT JOIN `wc_t_users` u ON x.`pruserto` = u.`id`
        LEFT JOIN `wc_t_users` ub ON x.`createdby` = ub.`id`
        LEFT JOIN `wc_t_company` c ON x.`supplier` = c.`id`
        LEFT JOIN `wc_t_contract` ct ON x.`contractref` = ct.`id`
        LEFT JOIN `wc_t_category` cr ON x.`currency` = cr.`id`
        LEFT JOIN `wc_t_lc` lc ON x.`poid` = lc.`pono`
        LEFT JOIN `wc_t_bank_insurance` bi ON lc.`lcissuerbank` = bi.`id`
        LEFT JOIN `wc_t_custom_duty` cd ON x.`poid` = cd.`poid` AND x.`shipNo` = cd.`shipNo`
        $where;";

//    echo $query;
//    die();

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
                if (strpos($val, '&amp;') > 0) {
                    $newval = str_replace('&amp;', '&', $val);
                } else {
                    $newval = $val;
                }
                $dataArray .= '"' . replaceTextRegex($newval) . '"';
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
    die();

}