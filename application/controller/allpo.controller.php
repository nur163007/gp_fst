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

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// get pending action at user end
			echo GetAllPO();
			break;
		default:
			break;
	}
}

// Get pending action at user end
function GetAllPO()
{
    global $loginRole;
	$objdal = new dal();
    
    $sql = "SELECT p1.`poid`, sh.`shipNo`, format(p1.`povalue`,2) as `povalue`, c2.name as currency, c1.`name` `supplier`, 
            p1.`podesc`, al1.`ID`, a1.`ActionDone` as statusname, 
            a1.`ActionPending` as pending, a1.`ActionPendingTo`, r1.name as targetrole, u1.`username` as buyer,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=37 AND al.`PO`=al1.`PO`) `lcRequest`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=37 AND al.`PO`=al1.`PO`) `lcOpening`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=50 AND al.`PO`=al1.`PO`) `lcAmendment`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=72 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `originalDoc`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=71 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `endorsedDoc`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=75 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `customDuty`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=74 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `eaInputs`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=76 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `avgCostUpdate`,
            sh.`eaRefNo`, sh.`lcNo`, 
            (SELECT `al2`.`Msg` FROM `wc_t_action_log` as `al2` WHERE `al2`.`PO` = p1.`poid` AND `al2`.`ActionID` IN(150,161) AND IFNULL(`al2`.`shipNo`, 0) = IFNULL(sh.`shipNo`, 0) LIMIT 1) as `remarks`
        FROM wc_t_pi p1 
        	INNER JOIN `wc_t_action_log` `al1` ON p1.`poid` = al1.`PO` AND al1.`ID` = (SELECT al.`ID` FROM `wc_t_action_log` as al 
        	    INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID` AND a.`ActionPending` !='Acknowledgement' WHERE al.`PO` = al1.`PO` ORDER BY al.`ID` DESC LIMIT 1)
            INNER JOIN `wc_t_action` a1 ON al1.`ActionID` = a1.ID
            INNER JOIN `wc_t_roles` r1 ON a1.`ActionPendingTo` = r1.id
            INNER JOIN `wc_t_users` u1 ON p1.`createdby` = u1.id
            INNER JOIN `wc_t_company` c1 ON p1.`supplier` = c1.`id`
            INNER JOIN `wc_t_category` c2 ON p1.`currency` = c2.`id`
            LEFT JOIN `wc_t_shipment` as sh ON p1.`poid` = sh.`pono`
        ORDER BY p1.`poid`, sh.`shipNo`;";
    $objdal->read($sql); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);

            if(!empty($table_data)) {
                $table_data .= ',';
            }
            if($loginRole!=role_LC_Approvar_4) {
                $lcRequest = 0;
                $lcAmendment = 0;
            }
            if($loginRole!=role_LC_Operation) {
                $lcOpening = 0;
                $lcAmendment = 0;
                $originalDoc = 0;
                $endorsedDoc = 0;
                $customDuty = 0;
                $avgCostUpdate = 0;
            }
            if($loginRole!=role_External_Approval) {
                $eaInputs = 0;
            }
            if($loginRole!=role_Admin) {
                $remarks = '';
            }
            $table_data .= '{
                "poid": "' . $poid . '", 
                "shipNo": "' . $shipNo . '", 
                "povalue": "' . $povalue . '", 
                "currency": "' . $currency . '", 
                "buyer": "' . $buyer . '", 
                "supplier": "' . $supplier . '", 
                "gprefno": "' . $eaRefNo . '", 
                "podesc": "' . htmlspecialchars_decode(str_replace(array("\n", "\t", "\r"), '', $podesc), ENT_QUOTES) . '", 
                "lcNo": "' . $lcNo . '", 
                "refId": "' . encryptId($ID) . '", 
                "status": "' . $statusname . '", 
                "targetrole": "' . $targetrole . '", 
                "pendingto": "' . $pending . '", 
                "lcRequest": "' . $lcRequest . '", 
                "lcOpening": "' . $lcOpening . '", 
                "lcAmendment": "' . $lcAmendment . '",
                "originalDoc": "' . $originalDoc . '",
                "endorsedDoc": "' . $endorsedDoc . '",
                "customDuty": "' . $customDuty . '",
                "avgCostUpdate": "' . $avgCostUpdate . '",
                "eaInputs": "' . $eaInputs . '",
                "remarks": "' . replaceTextRegex($remarks). '"
                }';
        }
	}
	$table_data = '{"data": ['.$table_data.']}';

    /*$table_data = '';
    if(!empty($objdal->data)){
        $table_data = json_encode($objdal->data);
    }
    $table_data = '{"data": '.$table_data.'}';*/

	unset($objdal);
	return $table_data;
}


function GetPendingInfo($poid, $topStage){
    
    $dal = new dal();
    
    $prAccepted = 0;
    $prRejected = 0;
    $eaAccepted = 0;
    $eaRejected = 0;
    
    // for status 4.5.6.7
    if($topStage<10){
        $sql = "SELECT `id`, `poid`, `title`, `status` FROM `wc_t_polog` 
            WHERE `poid` = '$poid' AND `status` IN (4,5,6,7)
            ORDER BY `msgon` ASC;";
    } else {
        $sql = "SELECT `id`, `poid`, `title`, `status` FROM `wc_t_polog` 
            WHERE `poid` = '$poid' AND `status` IN (4,5,6,7) 
            AND `msgon` >(SELECT `msgon` FROM `wc_t_polog` WHERE `poid` = '$poid' AND `status` = (SELECT MAX(`status`) FROM `wc_t_polog` WHERE `poid` = '$poid'))
            ORDER BY `msgon` ASC;";        
    }
    $dal->read($sql);
    if(!empty($dal->data)){
        foreach($dal->data as $row){
            extract($row);
            if($row['status']==4){          // PR Approved
                $prAccepted = 1;
                $prRejected = 0;
            } elseif($row['status']==5){    // Rejected
                $prAccepted = 0;
                $prRejected = 1;
            } elseif($row['status']==6){    // EA Approved
                $eaAccepted = 1;
                $eaRejected = 0;
            } elseif($row['status']==7){    // Rejected
                $eaAccepted = 0;
                $eaRejected = 1;
            }
        }
    }
    
    unset($dal);
    
    $prStatus = "Pending to PR";
    $eaStatus = "Pending to EA";

    if($prAccepted == 1){ $prStatus = 'Accepted by PR'; }
    if($prRejected == 1){ $prStatus = 'Rejected by PR'; }
    
    if($eaAccepted == 1){ $eaStatus = 'Accepted by EA'; }
    if($eaRejected == 1){ $eaStatus = 'Rejected by EA'; }
    
    
    $res["status"] = '';
    $res["pendingto"] = '';
    $res["targetform"] = '';

    $res["status"] = $prStatus.'<br />'.$eaStatus;
    if(($eaAccepted==1 || $eaRejected==1) && ($prAccepted==1 || $prRejected==1)){
        $res["pendingto"] = 'Buyer';
        if($eaAccepted==1 && $prAccepted==1){
            if($topStage<10){
                $res["targetform"] = 'pr-ea-interface';
            } else{
                $res["targetform"] = 'buyers-piboq';
            }            
        } else {
            $res["targetform"] = 'pr-ea-interface';
        }
    } else {
        if($eaRejected==0 && $eaAccepted==0){
            $res["pendingto"] = 'EA Team';
        $res["targetform"] = 'pr-ea-interface';
        } elseif($prRejected==0 && $prAccepted==0){
            $res["pendingto"] = 'PR User';
        $res["targetform"] = 'pr-ea-interface';
        }
    }
    
    //return $prStatus.'<br />'.$eaStatus;
    return $res;
}

function GetPendingToText($ids){
    $objdal = new dal();
    $sql = 'SELECT r.`name` FROM `wc_t_roles` r WHERE r.`id` IN ('.$ids.');';
    $objdal->read($sql);
    $res = '';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            if(strlen($res)>0){$res .= ', ';}
            $res .= $val['name'];
        }
    }
    unset($objdal);
    return $res;
}

/**************CLOSE PO*****************
 ******CREATED BY: HASAN MASUD************/
if (!empty($_POST)){
    if(!empty($_POST["poNo"]) || isset($_POST["poNo"])){
        echo closePO();
    }
}
function closePO(){
    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";*/
    $objdal = new dal();
    $poNo = $objdal->sanitizeInput($_POST['poNo']);
    $action_type = $objdal->sanitizeInput($_POST['action_type']);
    $closeJstifctn = $objdal->sanitizeInput($_POST['closeJstifctn']);
    $shipNo = $objdal->sanitizeInput($_POST['shipNo']);

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to close PO!';
    //------------------------------------------------------------------------------
    $shipCol = ($shipNo) ? " AND `shipNo` = $shipNo " : "";

    $query = "UPDATE `wc_t_action_log` SET `Status` = 1 WHERE `PO` = '$poNo' $shipCol AND `Status` = 0;";
    $objdal->update($query, "Failed to update close/complete data");

    //unset($objdal);
    $message = "";
    if ($action_type == action_Close_PO){
        $message = "Closed";
    }elseif ($action_type == action_PO_Cancel){
        $message = "Cancelled";
    }
    $action = array(
        'pono' => "'".$poNo."'",
        'actionid' => $action_type,
        'newstatus' => 1,
        'shipno' => ($shipNo) ? $shipNo : 'null',
        'msg' => "'$message PO# ".$poNo." by admin '",
        'usermsg' => "'".$closeJstifctn."'",
    );
    UpdateAction($action);

    $res["status"] = 1;
    $res["message"] = "PO $message Successfully";
    return json_encode($res);
}

?>

