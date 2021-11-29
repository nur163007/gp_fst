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
$user_company = $_SESSION[session_prefix.'wclogin_company'];

/*$dates = getStartAndEndDate(27, date("Y"));
echo $dates[0].'<br>';
echo $dates[1];*/

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:
            echo GetInbox($_GET["my"]);
            break;
        case 2:
            echo GetPiToBTRCProcess();
            break;
        case 3:
            echo GetBarChartData($_GET['start'], $_GET['end']);
            break;
        case 4:
            echo GetBayerWiseActivities($_GET['start'], $_GET['end']);
            break;
        case 5:
            echo GetLCWiseActivities();
            break;
        case 6:
            echo GetBayerWiseActivitiesChartData($_GET['start'], $_GET['end']);
            break;
        default:
            break;
    }
}

/*function GetInbox($my){

    global $user_id, $loginRole, $user_company;
    $sqlWhere = '';
    if($loginRole == role_Supplier){
        $sqlWhere = "  AND p.`supplier` = $user_company ";
    }

    if($loginRole == role_PR_Users){
        $sqlWhere = "  AND (p.`pruserto` = '$user_id' OR p.`prusercc` like '%$user_id%')";
    }

    if($loginRole == role_cert_final_approver){
        $sqlWhere = "  AND (cr.`certFinalApprover` = $user_id)";
    }

    $objdal=new dal();
    if($my=='true'){
        $strQuery = "SELECT l.`ID`, l.`RefID`, l.`PO`, l.`Msg`, l.`XRefID`, l.`PI`, l.`shipNo`, 
            (SELECT COUNT(l1.`XRefID`) FROM `wc_t_action_log` l1 WHERE l1.`XRefID`=l.`XRefID`) `xRefValid`,
            (SELECT CASE WHEN s.`eaRefNo` !='' THEN CONCAT('GP-REF # ', s.`eaRefNo`) ELSE '' END
                FROM `wc_t_shipment` s WHERE l.`PO` = s.`pono` AND l.`shipNo` = s.`shipNo`) AS `eaRefNo`,
            l.`ActionID`, l.`Status`, 
            (SELECT al.`Status` FROM `wc_t_action_log` AS al WHERE al.ID = l.RefID) AS lastStatus,
            a.`ActionDone`, a.`ActionDoneBy`, 
            (SELECT u.`username` from `wc_t_po` p INNER JOIN `wc_t_users` u ON p.`createdby` = u.`id` WHERE p.`poid` = l.`PO` ) `Buyer`,
			a.`ActionPending`, a.`ActionPendingTo`, r.`name` AS `PendingToRoleName`, a.`TargetForm`,
            (SELECT `name` FROM `wc_t_company` c WHERE c.`id` = (SELECT `supplier` FROM `wc_t_po` p WHERE p.`poid` = l.`PO`)) `CoName`,
            DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) as `pendingFor`, 
            if(DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`), 'danger', 'default') as `criticality`, 
            l.`ID` AS `ActionOn`, a.`stage`
            FROM `wc_t_action_log` l 
            	INNER JOIN `wc_t_action` a ON l.`ActionID` = a.`ID`
                INNER JOIN `wc_t_roles` r ON a.`ActionPendingTo` = r.`id`
                INNER JOIN `wc_t_po` p ON p.`poid` = l.`PO`
                LEFT JOIN `wc_t_cacfac_request` cr ON cr.`id` = l.`certReqId`
            WHERE a.`ActionPendingTo` = $loginRole AND l.`Status` = 0 AND a.`ActionPending` != 'Acknowledgement' $sqlWhere
            ORDER BY l.`PO`, l.`shipNo`;";
    } else{
        $strQuery = "SELECT l.`ID`, l.`RefID`, l.`PO`, l.`Msg`, l.`XRefID`, l.`PI`, l.`shipNo`, 
            (SELECT COUNT(l1.`XRefID`) FROM `wc_t_action_log` l1 WHERE l1.`XRefID`=l.`XRefID`) `xRefValid`, 
            (SELECT CASE WHEN s.`eaRefNo` !='' THEN CONCAT('GP-REF # ', s.`eaRefNo`) ELSE '' END
                FROM `wc_t_shipment` s WHERE l.`PO` = s.`pono` AND l.`shipNo` = s.`shipNo`) AS `eaRefNo`,
            l.`ActionID`, l.`Status`, 
            (SELECT al.`Status` FROM `wc_t_action_log` AS al WHERE al.ID = l.RefID) AS lastStatus,
            a.`ActionDone`, a.`ActionDoneBy`, 
            (SELECT u.`username` from `wc_t_po` p INNER JOIN `wc_t_users` u ON p.`createdby` = u.`id` WHERE p.`poid` = l.`PO` ) `Buyer`,
			a.`ActionPending`, a.`ActionPendingTo`, r.`name` AS `PendingToRoleName`, a.`TargetForm`,
            (SELECT `name` FROM `wc_t_company` c WHERE c.`id` = (SELECT `supplier` FROM `wc_t_po` p WHERE p.`poid` = l.`PO`)) `CoName`,
            DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) as `pendingFor`, 
            if(DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`), 'danger', 'default') as `criticality`,
            l.`ID` as `ActionOn`, a.`stage`
            FROM `wc_t_action_log` l 
            	INNER JOIN `wc_t_action` a ON l.`ActionID` = a.`ID`
                INNER JOIN `wc_t_roles` r ON a.`ActionPendingTo` = r.`id`
                INNER JOIN `wc_t_po` p ON p.`poid` = l.`PO`
                LEFT JOIN `wc_t_cacfac_request` cr ON cr.`id` = l.`certReqId`
            WHERE a.`ActionPendingTo` <> $loginRole AND l.`Status` = 0 AND a.`ActionPending` != 'Acknowledgement' $sqlWhere
            ORDER BY l.`PO`, l.`shipNo`;";
    }
	//echo $strQuery;
    $objdal->read($strQuery);

    $table_data = '';
    $xref = null;
    $pooo = "";
    $marge = 0;
    $aRow = array();

    if(!empty($objdal->data)){

        foreach($objdal->data as $val){
            extract($val);
            //$pooo = $PO;

            if($XRefID!=null && $xRefValid > 1){
                // memorize some values from this row
                if($xref==null){
                    $xref = $XRefID;
                    $aRow = array(
                        'id' => $ID,
                        'refid' => $RefID,
                        'po' => $PO,
                        'pi' => $PI,
                        'shipno' => $shipNo,
                        'eaRefNo' => $eaRefNo,
                        'actiondone' => $ActionDone,
                        'actionpending' => $ActionPending,
                        'buyer' => $Buyer,
                        'actionpendingto' => $ActionPendingTo,
                        'targetform' => $TargetForm,
                        'pendingtorolename' => $PendingToRoleName,
                        'coname' => $CoName,
                        'xrefvalid' => $xRefValid,
                        'actionid' => $ActionID,
                        'actionon' => $ActionOn,
                        'lastStatus' => $lastStatus,
                        'pendingfor' => $pendingFor,
                        'criticality' => $criticality,
                        'stage' => $stage,
                    );
                } else{
                    if($xref==$XRefID){
                        // if matched with previous xref then marge the status
                        $ActionDone = $ActionDone.'<br />'.$aRow['actiondone'];

                        if(in_array($aRow['actionid'], array(7,9,16,18))){
                            $TargetForm = $aRow['targetform'];
                            //$TargetForm = 1;
                        }
                        if(in_array($ActionID, array(7,9,16,18))){
                            $TargetForm = $TargetForm;
                            //$TargetForm = 2;
                        }
                        $xref=null;
                    }
                }
            } else {
                if($xref!=null){

                    $ID = $aRow['id'];
                    $RefID = $aRow['refid'];
                    $PO = $aRow['po'];
                    $PI = $aRow['pi'];
                    $shipNo = $aRow['shipno'];
                    $eaRefNo = $aRow['eaRefNo'];
                    $ActionDone = $aRow['actiondone'];
                    $ActionOn = $aRow['actionon'];
                    $ActionPending = $aRow['actionpending'];
                    $Buyer = $aRow['buyer'];
                    $ActionPendingTo = $aRow['actionpendingto'];
                    $TargetForm = $aRow['targetform'];
                    $PendingToRoleName = $aRow['pendingtorolename'];
                    $pendingFor = $aRow['pendingfor'];
                    $criticality = $aRow['criticality'];
                    $CoName = $aRow['coname'];
                    $lastStatus = $aRow['lastStatus'];
                    $xRefValid = 1;
                    $stage = $aRow['stage'];

                    $xref = null;
                }
            }

            if($xref==null){
//                echo $PO;
                //$PO = 0;
                if(empty($table_data))
                    $table_data = '{"ID": "'.encryptId($ID).'", "RefID": "'.$RefID.'", "PO": "'.$PO.'", "PI": "'.$PI.'", 
                    "shipNo": "'.$shipNo.'", "eaRefNo": "'.$eaRefNo.'", "ActionDone": "'.$ActionDone.'", 
                    "ActionPending": "'.$ActionPending.'", "Buyer": "'.$Buyer.'", "ActionPendingTo": "'.$ActionPendingTo.'", 
                    "TargetForm": "'.$TargetForm.'", "PendingToRoleName": "'.$PendingToRoleName.'", "pendingFor": "'.$pendingFor.'", 
                    "criticality": "'.$criticality.'", "CoName": "'.$CoName.'", "marge": "'.$xRefValid.'", "lastStatus": "'.$lastStatus.'", 
                    "ActionOn": "'.$ActionOn.'", "stage": "'.$stage.'"}';
                else
                    $table_data .= ',{"ID": "'.encryptId($ID).'", "RefID": "'.$RefID.'", "PO": "'.$PO.'", "PI": "'.$PI.'", 
                    "shipNo": "'.$shipNo.'",  "eaRefNo": "'.$eaRefNo.'", "ActionDone": "'.$ActionDone.'", 
                    "ActionPending": "'.$ActionPending.'", "Buyer": "'.$Buyer.'", "ActionPendingTo": "'.$ActionPendingTo.'", 
                    "TargetForm": "'.$TargetForm.'", "PendingToRoleName": "'.$PendingToRoleName.'", "pendingFor": "'.$pendingFor.'", 
                    "criticality": "'.$criticality.'", "CoName": "'.$CoName.'", "marge": "'.$xRefValid.'", "lastStatus": "'.$lastStatus.'", 
                    "ActionOn": "'.$ActionOn.'", "stage": "'.$stage.'"}';
            }
        }
    }
    else
    {
        //$table_data = '{"ID": " ", "RefID": " ", "PO": " ", "PI": " ", "shipNo": " ", "ActionDone": " ", "ActionPending": " ", "Buyer": " ", "ActionPendingTo": " ", "TargetForm": " ", "PendingToRoleName": " ", "CoName": " ", "marge": " "}';
        $table_data = '';
    }
    $table_data = '{"data": ['.$table_data.']}';
    unset($objdal);
    return $table_data;
}*/

function GetInbox($my){

    global $user_id, $loginRole, $user_company;
    $sqlWhere = '';
    $join = '';
    $sqlFXFloatBank = '';

    if($loginRole == role_Supplier){
        $sqlWhere = "  AND p.`supplier` = $user_company ";
    }

    if($loginRole == role_PR_Users){
        $sqlWhere = "  AND (p.`pruserto` = '$user_id' OR p.`prusercc` like '%$user_id%')";
    }

    if($loginRole == role_cert_final_approver){
        $sqlWhere = "  AND (cr.`certFinalApprover` = $user_id)";
    }

    if($loginRole == role_foreign_strategy || $loginRole == role_head_of_treasury || role_bank_fx){
        $join = "LEFT ";
    }

    if ($loginRole == role_bank_fx){
        $sqlFXFloatBank = " AND l.PendingToCo = $user_company";
    }

    $objdal=new dal();
    if($my=='true'){
        $strQuery = "SELECT l.`ID`, l.`RefID`, l.`PO`, l.`Msg`, l.`XRefID`, l.`PI`, l.`shipNo`, 
            (SELECT COUNT(l1.`XRefID`) FROM `wc_t_action_log` l1 WHERE l1.`XRefID`=l.`XRefID`) `xRefValid`,
            (SELECT CASE WHEN s.`eaRefNo` !='' THEN CONCAT('GP-REF # ', s.`eaRefNo`) ELSE '' END
                FROM `wc_t_shipment` s WHERE l.`PO` = s.`pono` AND l.`shipNo` = s.`shipNo`) AS `eaRefNo`,
            l.`ActionID`, l.`Status`, 
            (SELECT al.`Status` FROM `wc_t_action_log` AS al WHERE al.ID = l.RefID) AS lastStatus,
            a.`ActionDone`, a.`ActionDoneBy`, 
            (SELECT u.`username` from `wc_t_po` p INNER JOIN `wc_t_users` u ON p.`createdby` = u.`id` WHERE p.`poid` = l.`PO` ) `Buyer`,
			a.`ActionPending`, a.`ActionPendingTo`, r.`name` AS `PendingToRoleName`, a.`TargetForm`,
            (SELECT `name` FROM `wc_t_company` c WHERE c.`id` = (SELECT `supplier` FROM `wc_t_po` p WHERE p.`poid` = l.`PO`)) `CoName`,
            DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) as `pendingFor`, 
            if(DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`), 'danger', 'default') as `criticality`, 
            l.`ID` AS `ActionOn`, a.`stage`
            FROM `wc_t_action_log` l 
            	INNER JOIN `wc_t_action` a ON l.`ActionID` = a.`ID`
                INNER JOIN `wc_t_roles` r ON a.`ActionPendingTo` = r.`id`
                ".$join." JOIN `wc_t_po` p ON p.`poid` = l.`PO`
                LEFT JOIN `wc_t_cacfac_request` cr ON cr.`id` = l.`certReqId`
            WHERE (a.`ActionPendingTo` = $loginRole $sqlFXFloatBank) AND l.`Status` = 0 AND a.`ActionPending` != 'Acknowledgement' $sqlWhere
            ORDER BY l.`PO`, l.`shipNo`;";
    } else{
        $strQuery = "SELECT l.`ID`, l.`RefID`, l.`PO`, l.`Msg`, l.`XRefID`, l.`PI`, l.`shipNo`, 
            (SELECT COUNT(l1.`XRefID`) FROM `wc_t_action_log` l1 WHERE l1.`XRefID`=l.`XRefID`) `xRefValid`, 
            (SELECT CASE WHEN s.`eaRefNo` !='' THEN CONCAT('GP-REF # ', s.`eaRefNo`) ELSE '' END
                FROM `wc_t_shipment` s WHERE l.`PO` = s.`pono` AND l.`shipNo` = s.`shipNo`) AS `eaRefNo`,
            l.`ActionID`, l.`Status`, 
            (SELECT al.`Status` FROM `wc_t_action_log` AS al WHERE al.ID = l.RefID) AS lastStatus,
            a.`ActionDone`, a.`ActionDoneBy`, 
            (SELECT u.`username` from `wc_t_po` p INNER JOIN `wc_t_users` u ON p.`createdby` = u.`id` WHERE p.`poid` = l.`PO` ) `Buyer`,
			a.`ActionPending`, a.`ActionPendingTo`, r.`name` AS `PendingToRoleName`, a.`TargetForm`,
            (SELECT `name` FROM `wc_t_company` c WHERE c.`id` = (SELECT `supplier` FROM `wc_t_po` p WHERE p.`poid` = l.`PO`)) `CoName`,
            DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) as `pendingFor`, 
            if(DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`), 'danger', 'default') as `criticality`,
            l.`ID` as `ActionOn`, a.`stage`
            FROM `wc_t_action_log` l 
            	INNER JOIN `wc_t_action` a ON l.`ActionID` = a.`ID`
                INNER JOIN `wc_t_roles` r ON a.`ActionPendingTo` = r.`id`
                INNER JOIN `wc_t_po` p ON p.`poid` = l.`PO`
                LEFT JOIN `wc_t_cacfac_request` cr ON cr.`id` = l.`certReqId`
            WHERE a.`ActionPendingTo` <> $loginRole AND l.`Status` = 0 AND a.`ActionPending` != 'Acknowledgement' $sqlWhere
            ORDER BY l.`PO`, l.`shipNo`;";
    }
//	echo $strQuery;
    $objdal->read($strQuery);

    $table_data = '';
    $xref = null;
    $pooo = "";
    $marge = 0;
    $aRow = array();

    if(!empty($objdal->data)){

        foreach($objdal->data as $val){
            extract($val);
            //$pooo = $PO;

            if($XRefID!=null && $xRefValid > 1){
                // memorize some values from this row
                if($xref==null){
                    $xref = $XRefID;
                    $aRow = array(
                        'id' => $ID,
                        'refid' => $RefID,
                        'po' => $PO,
                        'pi' => $PI,
                        'shipno' => $shipNo,
                        'eaRefNo' => $eaRefNo,
                        'actiondone' => $ActionDone,
                        'actionpending' => $ActionPending,
                        'buyer' => $Buyer,
                        'actionpendingto' => $ActionPendingTo,
                        'targetform' => $TargetForm,
                        'pendingtorolename' => $PendingToRoleName,
                        'coname' => $CoName,
                        'xrefvalid' => $xRefValid,
                        'actionid' => $ActionID,
                        'actionon' => $ActionOn,
                        'lastStatus' => $lastStatus,
                        'pendingfor' => $pendingFor,
                        'criticality' => $criticality,
                        'stage' => $stage,
                    );
                } else{
                    if($xref==$XRefID){
                        // if matched with previous xref then marge the status
                        $ActionDone = $ActionDone.'<br />'.$aRow['actiondone'];

                        if(in_array($aRow['actionid'], array(7,9,16,18))){
                            $TargetForm = $aRow['targetform'];
                            //$TargetForm = 1;
                        }
                        if(in_array($ActionID, array(7,9,16,18))){
                            $TargetForm = $TargetForm;
                            //$TargetForm = 2;
                        }
                        $xref=null;
                    }
                }
            } else {
                if($xref!=null){

                    $ID = $aRow['id'];
                    $RefID = $aRow['refid'];
                    $PO = $aRow['po'];
                    $PI = $aRow['pi'];
                    $shipNo = $aRow['shipno'];
                    $eaRefNo = $aRow['eaRefNo'];
                    $ActionDone = $aRow['actiondone'];
                    $ActionOn = $aRow['actionon'];
                    $ActionPending = $aRow['actionpending'];
                    $Buyer = $aRow['buyer'];
                    $ActionPendingTo = $aRow['actionpendingto'];
                    $TargetForm = $aRow['targetform'];
                    $PendingToRoleName = $aRow['pendingtorolename'];
                    $pendingFor = $aRow['pendingfor'];
                    $criticality = $aRow['criticality'];
                    $CoName = $aRow['coname'];
                    $lastStatus = $aRow['lastStatus'];
                    $xRefValid = 1;
                    $stage = $aRow['stage'];

                    $xref = null;
                }
            }

            if($xref==null){
//                echo $PO;
                //$PO = 0;
                if(empty($table_data))
                    $table_data = '{"ID": "'.encryptId($ID).'", "RefID": "'.$RefID.'", "PO": "'.$PO.'", "PI": "'.$PI.'", 
                    "shipNo": "'.$shipNo.'", "eaRefNo": "'.$eaRefNo.'", "ActionDone": "'.$ActionDone.'", 
                    "ActionPending": "'.$ActionPending.'", "Buyer": "'.$Buyer.'", "ActionPendingTo": "'.$ActionPendingTo.'", 
                    "TargetForm": "'.$TargetForm.'", "PendingToRoleName": "'.$PendingToRoleName.'", "pendingFor": "'.$pendingFor.'", 
                    "criticality": "'.$criticality.'", "CoName": "'.$CoName.'", "marge": "'.$xRefValid.'", "lastStatus": "'.$lastStatus.'", 
                    "ActionOn": "'.$ActionOn.'", "stage": "'.$stage.'"}';
                else
                    $table_data .= ',{"ID": "'.encryptId($ID).'", "RefID": "'.$RefID.'", "PO": "'.$PO.'", "PI": "'.$PI.'", 
                    "shipNo": "'.$shipNo.'",  "eaRefNo": "'.$eaRefNo.'", "ActionDone": "'.$ActionDone.'", 
                    "ActionPending": "'.$ActionPending.'", "Buyer": "'.$Buyer.'", "ActionPendingTo": "'.$ActionPendingTo.'", 
                    "TargetForm": "'.$TargetForm.'", "PendingToRoleName": "'.$PendingToRoleName.'", "pendingFor": "'.$pendingFor.'", 
                    "criticality": "'.$criticality.'", "CoName": "'.$CoName.'", "marge": "'.$xRefValid.'", "lastStatus": "'.$lastStatus.'", 
                    "ActionOn": "'.$ActionOn.'", "stage": "'.$stage.'"}';
            }
        }
    }
    else
    {
        //$table_data = '{"ID": " ", "RefID": " ", "PO": " ", "PI": " ", "shipNo": " ", "ActionDone": " ", "ActionPending": " ", "Buyer": " ", "ActionPendingTo": " ", "TargetForm": " ", "PendingToRoleName": " ", "CoName": " ", "marge": " "}';
        $table_data = '';
    }
    $table_data = '{"data": ['.$table_data.']}';
    unset($objdal);
    return $table_data;
}


function GetPiToBTRCProcess(){

    $objdal = new dal();
    $query = "SELECT 
            TotalPo, 
            s1, FORMAT((s1/TotalPo)*100,0) AS s1P,
            s2, FORMAT((s2/TotalPo)*100,0) AS s2P, 
            s3, FORMAT((s3/TotalPo)*100,0) AS s3P 
        FROM
        (SELECT TotalPo,
            kpi1 AS `s1`,
            kpi2-kpi3 AS `s2`,
            kpi3 AS `s3`
        FROM
        (SELECT 
        (SELECT COUNT(`poid`) FROM `wc_t_po`) AS TotalPo,
        (SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 1) AS kpi1,
        (SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 4) AS kpi2,
        (SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 22) AS kpi3) AS a) AS b;";
    //echo $query;
    $objdal->read(trim($query));
    $returnVal = 0;
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = json_encode($res);
    }
    unset($objdal);
    return $returnVal;

}

function GetBarChartData($start, $end){

    $dates[0] = date('Y-m-d', strtotime($start));
    $dates[1] = date('Y-m-d', strtotime($end));

    $objdal = new dal();

    $query = "
        SELECT 'DONE' AS `type`,
            kpi1 AS `PO`,
            kpi2 AS `PI`,
            kpi3 AS `BTRC`,
            kpi4 AS `LC`,
            kpi5 AS `Invoice`
        FROM
        (SELECT 
        (SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 1 AND `ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi1,
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
        WHERE lg001.`ActionID` = 4 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi2,
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
        WHERE lg001.`ActionID` = 22 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi3,
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
        WHERE lg001.`ActionID` = 25 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi4,
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, lg1.`shipNo`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`) AS lg001
        WHERE lg001.`ActionID` = 89 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi5) AS a
        
        UNION
        
        SELECT 'KPI' AS `type`,
            kpi1 AS `PO`,
            kpi2 AS `PI`,
            kpi3 AS `BTRC`,
            kpi4 AS `LC`,
            kpi5 AS `Invoice`
        FROM
        (SELECT 
        (SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 1 AND `ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi1,        
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001 
            WHERE lg001.`ActionID` = 4 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]' 
            AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`ActionID` = 1)) AS kpi2,        
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
            WHERE lg001.`ActionID` = 22 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]') AS kpi3,        
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001 
            WHERE lg001.`ActionID` = 25 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]' 
            AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`ActionID` = 24)) AS kpi4,        
        (SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`) AS lg001 
            WHERE lg001.`ActionID` = 89 AND lg001.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]' 
            AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`shipNo` = lg001.`shipNo` AND lg2.`ActionID` = 73 limit 1)) AS kpi5) AS a;";

//    echo $query;
    $objdal->read(trim($query));
    $returnVal = 0;
    if(!empty($objdal->data)){
        $res = $objdal->data;
        extract($res);
        $returnVal = json_encode($res);
    }
    unset($objdal);
    return $returnVal;

}

function GetBayerWiseActivitiesChartData($start, $end){

    $dates[0] = date('Y-m-d', strtotime($start));
    $dates[0] .= " 00:00:00";
    $dates[1] = date('Y-m-d', strtotime($end));
    $dates[1] .= " 23:59:59";

    $objdal = new dal();
    $query="SELECT DISTINCT
        po.createdby,
        u.username,
        trim(concat(u.firstname,' ' , u.lastname)) as `Buyer`,
        (SELECT COUNT(*)
                 FROM `wc_t_po` AS po1
                 WHERE po1.createdby = po.`createdby`
                   AND po1.`createdon` BETWEEN '$dates[0]' AND '$dates[1]') AS `PO`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 4
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `PI`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 22
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `BTRC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 25
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `LC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`shipNo`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`shipNo`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 89
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `GIT`
    FROM wc_t_po AS po 
    INNER JOIN `wc_t_users` AS u ON po.`createdby` = u.`id`
    LEFT JOIN `wc_t_action_log` log ON log.`PO` = po.`poid`
    WHERE log.`ActionOn` BETWEEN '$dates[0]' AND '$dates[1]'
    ORDER BY trim(concat(u.`firstname`,' ' , u.`lastname`));";

    //echo $query;
    $objdal->read(trim($query));
    $returnVal = 0;
    if(!empty($objdal->data)){
        $res = $objdal->data;
        extract($res);
        $returnVal = json_encode($res);
    }
    unset($objdal);
    return $returnVal;

}


function GetBayerWiseActivities($start, $end){

    $dates[0] = date('Y-m-d', strtotime($start));
    $dates[0] .= " 00:00:00";
    $dates[1] = date('Y-m-d', strtotime($end));
    $dates[1] .= " 23:59:59";

    $objdal=new dal();
    $strQuery="SELECT DISTINCT
        po.createdby,
        u.username,
        trim(concat(u.firstname,' ' , u.lastname)) as `Buyer`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 1
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `PO`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 4
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `PI`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 22
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `BTRC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 25
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `LC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`shipNo`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`shipNo`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 89
                    AND lg1.ActionOn BETWEEN '$dates[0]' AND '$dates[1]') AS `GIT`
    FROM
        wc_t_po AS po INNER JOIN
        wc_t_users AS u ON po.createdby = u.id
    ORDER BY trim(concat(u.firstname,' ' , u.lastname));";

//    echo $strQuery;

    $objdal->read($strQuery);

    $table_data = '';
    if(!empty($objdal->data)){
        $table_data = json_encode($objdal->data);
    }
    $table_data = '{"data": '.$table_data.'}';
    unset($objdal);
    return $table_data;

}

function GetLCWiseActivities(){

    //$dates = getStartAndEndDate($week, date("Y"));
    $objdal=new dal();
    $strQuery="SELECT 
        p.PO,
        p.logId,
        l1.ActionId,
        a.ActionDone,
        a.ActionDoneBy,
        DATE_FORMAT(l1.ActionOn,'%d-%M-%Y') as `ActionOn`,
        datediff(current_date(), l1.ActionOn) as `pendingFor`,
        r1.name AS ActionDoneByRole,
        a.ActionPending,
        a.ActionPendingTo,
        r2.name AS ActionPendingToRole
    FROM
        (SELECT DISTINCT
            l.PO, MAX(l.ID) AS logId
        FROM
            wc_t_action_log AS l
        WHERE
            l.PO IN (SELECT 
                    pono
                FROM
                    wc_t_lc) and l.ActionID NOT IN (".action_PO_Edited_by_Buyer.")
        GROUP BY l.PO) AS p
            INNER JOIN
        wc_t_action_log AS l1 ON p.logid = l1.ID
            INNER JOIN
        wc_t_action AS a ON l1.ActionID = a.ID
            INNER JOIN
        wc_t_roles AS r1 ON a.ActionDoneBy = r1.id
            INNER JOIN
        wc_t_roles AS r2 ON a.ActionPendingTo = r2.id;";

//    echo $strQuery;

    $objdal->read($strQuery);

    $table_data = '';
    if(!empty($objdal->data)){
        $table_data = json_encode($objdal->data);
    }
    $table_data = '{"data": '.$table_data.'}';
    unset($objdal);
    return $table_data;

}

?>

