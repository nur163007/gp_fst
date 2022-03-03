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
    switch($_GET["action"]) {
        case 1:
            if (isset($_GET["reject"])) {
                echo checkStepOver($_GET["po"], $_GET["status"], $_GET["shipno"], null, $_GET["reject"]);
            } else {
                echo checkStepOver($_GET["po"], $_GET["status"], $_GET["shipno"]);
            }
            break;
        case 2:
            echo getPOLines($_GET["id"]);
            break;
        case 3:
            echo calculateData($_GET["id"]);
            break;
        case 4:
            echo calculateDraftData($_GET["id"]);
            break;
        default:
            break;
    }
}

// Submit new PO
if (!empty($_POST)){
    if($_POST["userAction"]==1){
        echo updateStatus();
    }
}

function updateStatus(){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    if($_POST['shipno']!="") {
        $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    }else{
        $shipNo= "'NULL'";
    }
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'status' => 1,
        'msg' => "'Acknowledged'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

	$res["status"] = 1;
    $res["message"] = 'Status updated';
	return json_encode($res);
    
}
/*
function SubmitFeedback()
{
	global $user_id;
	global $loginRole;
    
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $userAction = htmlspecialchars($_POST['userAction'],ENT_QUOTES, "ISO-8859-1");
    
    if(!isset($_POST['messageUserYes'])){ $messageUser = 'NULL'; } else{ $messageUser = "'".htmlspecialchars($_POST['messageUser'],ENT_QUOTES, "ISO-8859-1")."'"; };
    if($loginRole==14){
        $attachJustification = htmlspecialchars($_POST['attachJustification'],ENT_QUOTES, "ISO-8859-1");
    } else {
        $attachJustification = '';
    }
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $lastStatus = GetLastStatus($poid);
    // new status
    if($userAction == '1'){
        if($loginRole==14){
            $newStatus = 5;
        } elseif($loginRole == 4){
            $newStatus = 7;
        }        
    } elseif($userAction == '2'){
        if($loginRole==14){
            $newStatus = 4;
        } elseif($loginRole == 4){
            $newStatus = 6;
        }
    } elseif($userAction == '3'){
        $newStatus = 8;
    } elseif($userAction == '4'){
         $newStatus = 9;
    }
    // update new po table
    $query = "UPDATE `wc_t_pi` SET
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip'
        WHERE `poid` = '$poid';";
	$objdal->insert($query);
	//echo($query);

    // polog message title
    if($userAction == '1'){
        if($loginRole==14){
            $msgTitle ='Rejected by PR User';
        } elseif($loginRole == 4){
            $msgTitle ='Rejected by EA Team';
        }        
    } elseif($userAction == '2'){
        if($loginRole==14){
            $msgTitle ='Accepted by PR User';
        } elseif($loginRole == 4){
            $msgTitle ='Accepted by EA Team';
        }
    } elseif($userAction == '3'){
        $msgTitle ='Sent supplier for Rectification';
    } elseif($userAction == '4'){
        $msgTitle ='Approved by PR & EA and requested for final PI';
    }
    
    // polog message title
    if($userAction == '1'){
        $toGroup = 2;      
    } elseif($userAction == '2'){
        $toGroup = 2;
    } elseif($userAction == '3'){
        $toGroup = 3;
    } elseif($userAction == '4'){
        $toGroup = 3;
    }
    
    // inserting log
    $query = "INSERT INTO `wc_t_polog` SET 
		`poid` = '$poid', 
        `title` = '$msgTitle', 
        `msg` = $messageUser, 
        `msgby` = $user_id,
        `msgfrom` = '$ip', 
        `fromgroup` = $loginRole, 
        `togroup` = $toGroup;";
	$objdal->insert($query);    
    //echo($query);
    
    //insert attachment
    if($loginRole==14){
        $res["message"] = 'Failed to save attachments!';
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
            ('$poid', 'Justification', '$attachJustification', $user_id, '$ip', $loginRole);";
        
    	$objdal->insert($query);
        //echo($query);
    }
    
//    if(($lastStatus==4 && $newStatus==6) || ($lastStatus==6 && $newStatus==4)){
//        $finalStatus = 9;
//    } elseif($lastStatus==6 && $newStatus==4){
//        $newStatus = 9;
//    }
    
	unset($objdal);
	
	$res["status"] = 1;
    if($userAction == '1'){
        $res["message"] = 'REJECT Feedback send to Buyer.';
    } elseif($userAction == '2'){
        $res["message"] = 'ACCEPT Feedback send to Buyer.';
    } elseif($userAction == '3'){
        $res["message"] = 'Feedback send to Supplier.';
    } elseif($userAction == '4'){
        $res["message"] = 'Feedback send to Supplier.';
    }
	
	return json_encode($res);
}

function GetLastStatus($poid){
    $dal = new dal();
    $sql = "SELECT `status` FROM `wc_t_pi` WHERE `poid` = '$poid' ORDER BY `msgon` DESC LIMIT 1";
    $dal->read($sql);
    $res = '';
    if(!empty($dal->data)){
        $row = $dal->data[0];
        extract($row);
        $res = $status;
    }
    unset($dal);
    return $res;
}*/


function getPOLines($pono){
//var_dump("ok");
    $objdal = new dal();

    /*!
     * Query for Delivered PO Lines
     * **********************************/
    $sql = "SELECT 
            pil.`id`,
            pil.`poNo`,
            DATE_FORMAT(pil.`deliveryDate`, '%b %d, %Y') AS `deliveryDate`,
            pil.`itemCode`,
            REPLACE(pil.`itemDesc`, CHAR(194), '') AS `itemDesc`,
            pi.`currency`,
            pil.`lineNo`,
            pil.`uom`,
            pil.`unitPrice`,
            pil.`poQty`,
            pil.`poTotal`,
            pil.`status`,
            pil.`delivQty`,
            pil.`delivTotal`
        FROM
            `wc_t_pi` AS pi
                INNER JOIN
            `pi_lines` pil ON pi.`poid` = pil.`poNo`
        WHERE
            pil.`poNo` = '$pono';";
    //echo $sql;
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $delivered = $objdal->data;
    } else {
        $delivered = array();
    }

      unset($objdal);

    /*!
     * Query for rejected PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            `poNo`, GROUP_CONCAT(`lineNo`) AS `rejectedlines`
        FROM
            `pi_lines`
        WHERE
            `poNo` = '$pono' AND `status` = 1
        GROUP BY `poNo`";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $rej = $objdal->data;
    } else {
        $rej = array();
    }

    $json = json_encode(array($delivered, $rej));
    /*if (!empty($objdal->data)) {
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    $json = json_encode($res);*/
    return $json;

}

function calculateData($id){
//var_dump("ok");
    $objdal = new dal();

    /*!
     * Query for Delivered PO Lines
     * **********************************/
    $sql = "SELECT 
            SUM(l.poQty) as grandpoQty, 
            SUM(l.poTotal) as grandPoTotal, 
            SUM(l.delivQty) as grandDelivQty, 
            SUM(l.delivTotal) as grandDelivTotal 
        FROM
            `pi_lines` l 
        WHERE
            l.`poNo` = '$id'
        GROUP BY l.`poNo`;";
    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data)) {
        $podetail[0] = $objdal->data[0];
    }
    unset($objdal);

    $json = json_encode(array($podetail));

    return $json;

}

function calculateDraftData($id){
//var_dump($id);
//exit();
    $objdal = new dal();

    if(strpos($id, 'PI', 1)>1){
        $strCriteria = "l.`poNo`";
    } else {
        $strCriteria = "pol.`poNo`";
    }
    /*!
     * Query for Delivered PO Lines
     * **********************************/
    $sql = "SELECT 
            SUM(l.poQty) as grandpoQty, 
            SUM(l.poTotal) as grandPoTotal, 
            SUM(l.delivQty) as grandDelivQty, 
            SUM(l.delivTotal) as grandDelivTotal 
        FROM
            `pi_lines` l
             LEFT JOIN
            `po_lines` pol ON (pol.`poNo` = l.`buyersPo`
                AND pol.`lineNo` = l.`lineNo`) 
        WHERE
            ".$strCriteria." = '$id'
        GROUP BY l.`poNo`;";
    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data)) {
        $podetail[0] = $objdal->data[0];
    }
    unset($objdal);

    $json = json_encode(array($podetail));

    return $json;

}
?>