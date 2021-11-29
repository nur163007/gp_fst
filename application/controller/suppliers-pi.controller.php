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

// Submit new PO
if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
    if($_POST["userAction"]==1){
        echo submitPI();
    } elseif($_POST["userAction"]==2){
        echo rejectPO();
    }
}

function submitPI()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $postatus = htmlspecialchars($_POST['postatus'],ENT_QUOTES, "ISO-8859-1");
    
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    $pinum = replaceTextRegex($_POST['pinum']);
    $pivalue = htmlspecialchars($_POST['pivalue'],ENT_QUOTES, "ISO-8859-1");
    $pivalue = str_replace(",", "", $pivalue);
    $shipmode = htmlspecialchars($_POST['shipmode'],ENT_QUOTES, "ISO-8859-1");
    $hscode = htmlspecialchars(replaceTextRegex($_POST['hscode']),ENT_QUOTES, "ISO-8859-1");
    
    if($postatus>=action_Requested_for_Final_PI){
        $pidate = htmlspecialchars($_POST['pidate'],ENT_QUOTES, "ISO-8859-1");    
        $pidate = date('Y-m-d', strtotime($pidate));

        $basevalue = htmlspecialchars($_POST['basevalue'],ENT_QUOTES, "ISO-8859-1");
        $basevalue = str_replace(",", "", $basevalue);
    }
    
    $origin = '';
    foreach($_POST['origin'] as $val) {
        if(strlen($origin)>0){$origin .= ',';}
        $origin.= htmlspecialchars($val,ENT_QUOTES, "ISO-8859-1");
    }
    
    $negobank = htmlspecialchars($_POST['negobank'],ENT_QUOTES, "ISO-8859-1");
    $shipport = htmlspecialchars(replaceTextRegex($_POST['shipport']),ENT_QUOTES, "ISO-8859-1");
    $lcbankaddress = htmlspecialchars($_POST['lcbankaddress'],ENT_QUOTES, "ISO-8859-1");
    $productiondays = htmlspecialchars($_POST['productiondays'],ENT_QUOTES, "ISO-8859-1");
    
    if(!isset($_POST['messageyes'])){ $suppliersmessage = 'NULL'; } else{ $suppliersmessage = "'".htmlspecialchars($_POST['suppliersmessage'],ENT_QUOTES, "ISO-8859-1")."'"; };
    
    // attachment data in an 3D array
    $attachDraftPI = htmlspecialchars($_POST['attachDraftPI'],ENT_QUOTES, "ISO-8859-1");
    $attachDraftBOQ = htmlspecialchars($_POST['attachDraftBOQ'],ENT_QUOTES, "ISO-8859-1");
    $attachCatelog = htmlspecialchars($_POST['attachCatelog'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
    $pivalue = stripslashes($pivalue);
    $shipmode = stripslashes($shipmode);
    $hscode = stripslashes($hscode);
    if($postatus>=action_Requested_for_Final_PI){
        $pidate = stripslashes($pidate);
        $basevalue = stripslashes($basevalue);
    }
    $origin = stripslashes($origin);
    $negobank = stripslashes($negobank);
    $shipport = stripslashes($shipport);
    $lcbankaddress = stripslashes($lcbankaddress);
    $productiondays = stripslashes($productiondays);
	
	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
    $pivalue = $objdal->real_escape_string($pivalue);
    $pidesc = $objdal->real_escape_string($_POST['pi_description']);
    $shipmode = $objdal->real_escape_string($shipmode);
    $hscode = $objdal->real_escape_string($hscode);    
    if($postatus>=action_Requested_for_Final_PI){
        $pidate = $objdal->real_escape_string($pidate);
        $basevalue = $objdal->real_escape_string($basevalue);
    }
    $origin = $objdal->real_escape_string($origin);
    $negobank = $objdal->real_escape_string($negobank);
    $negobank = replaceTextRegex($negobank);
    $shipport = $objdal->real_escape_string($shipport);
    $lcbankaddress = $objdal->real_escape_string($lcbankaddress);
    $lcbankaddress = replaceTextRegex($lcbankaddress);
    $productiondays = $objdal->real_escape_string($productiondays);
	//------------------------------------------------------------------------------
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    $forFinal = "";
    if($postatus>=action_Requested_for_Final_PI){
        $forFinal = "
        `pidate` = '$pidate',
        `basevalue` = '$basevalue',";
    }
	// update new po
    $query = "UPDATE `wc_t_po` SET 
		`pinum` = '$pinum',
        `pivalue` = $pivalue,
        `hscode` = '$hscode',
        `shipmode` = '$shipmode',
        `origin` = '$origin', $forFinal
        `negobank` = '$negobank',
        `shipport` = '$shipport',
        `lcbankaddress` = '$lcbankaddress',
        `productiondays` = $productiondays,
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip',
        `pi_description` = '$pidesc'
        WHERE `poid` = '$poid';";
    //echo($query);
    $objdal->update($query, "Failed to update PO data");

    // Action Log --------------------------------//
    if($postatus>=action_Requested_for_Final_PI){
        $newActionID = action_Final_PI_Submitted;
        $msg = "'Final PI submitted against PO# ".$poid."'";
    } else{
        $newActionID = action_Draft_PI_Submitted;
        $msg = "'Draft PI submitted against PO# ".$poid."'";
    }
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => $newActionID,
        'status' => 1,
        'msg' => $msg,
        'usermsg' => $suppliersmessage,
    );

    UpdateAction($action);
    // End Action Log -----------------------------

    // insert attachment
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$poid', 'Suppliers PI', '$attachDraftPI', $user_id, '$ip', $loginRole),
        ('$poid', 'Suppliers BOQ', '$attachDraftBOQ', $user_id, '$ip', $loginRole),
        ('$poid', 'Suppliers Catalog', '$attachCatelog', $user_id, '$ip', $loginRole);";
    
	$objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($poid);

    // Deleting old po lines
    $delete = "DELETE FROM `wc_t_po_line` WHERE `poNo` = '$poid'";
    $objdal->delete($delete);

    $sqlHeader = "INSERT INTO `wc_t_po_line`(
                `poNo`, 
                `lineNo`, 
                `itemCode`, 
                `itemDesc`, 
                `poDate`, 
                `UOM`, 
                `unitPrice`, 
                `poQty`, 
                `poTotal`, 
                `delivQty`, 
                `delivTotal`) VALUES ";
    $sqlRows='';
    $j = 0;
    /*!
     * Replace REPEATED |(PIPE) sign from string
     * to protect empty row on partial line submission $_POST variable
     * **********************************************************************/
    $regex = "/\|+/";
    $trimmedPoLines = rtrim(preg_replace($regex, '|', $_POST["consolidatedPoLines"]),'|');

    foreach (explode('|', $trimmedPoLines) as $separateRow) {

        $separateCol = explode(';', $separateRow);
        $lineNo = $objdal->sanitizeInput($separateCol[0]);

        $itemCode = $objdal->sanitizeInput($separateCol[1]);

        $itemDesc = $objdal->sanitizeInput($separateCol[2]);
        //$itemDesc = ($separateCol[2]) ? $objdal->sanitizeInput($separateCol[2]) : 'NA';
        $poDate = $objdal->sanitizeInput($separateCol[3]);
        $uom = $objdal->sanitizeInput($separateCol[4]);
        $unitPrice = str_replace(",", "", $separateCol[5]);
        $unitPrice = $objdal->sanitizeInput($unitPrice);
        $poQty = str_replace(",", "", $separateCol[6]);
        $poQty = $objdal->sanitizeInput($poQty);
        $poTotal = str_replace(",", "", $separateCol[7]);
        $poTotal = $objdal->sanitizeInput($poTotal);
        $delivQty = $objdal->sanitizeInput($separateCol[8]);
        $delivQty = str_replace(",", "", $delivQty);
        $delivTotal = str_replace(",", "", $separateCol[9]);
        $delivTotal = $objdal->sanitizeInput($delivTotal);
        /*$ldAmount = $objdal->sanitizeInput($_POST['ldAmnt'][$i], ENT_QUOTES, "ISO-8859-1");
        $ldAmount = str_replace(",", "", $ldAmount);*/
        // insert new again

        if ($sqlRows != '') {
            $sqlRows .= ',';
        }

        $sqlRows .= "(
            
            '$poid', 
            $lineNo, 
            '$itemCode', 
            '$itemDesc', 
            '$poDate', 
            '$uom', 
            $unitPrice, 
            $poQty, 
            $poTotal, 
            $delivQty, 
            $delivTotal)";

        $j++;
        if ($j == 300) {
            $sql = $sqlHeader . $sqlRows . ';';
//            echo $sql;
            $objdal->insert($sql, "Failed to save PO Lines");
            $sqlRows = '';
            $j = 0;
        }
    }
    // finally if any rest rows to insert
    if($sqlRows!="") {
//        echo $sqlHeader . $sqlRows;
        $objdal->insert($sqlHeader . $sqlRows . ';', "Failed to save PO Lines");
        $sqlRows = '';
        $j = 0;
    }

    unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'PI, BOQ &amp; Catalog submitted.';
	return json_encode($res);
}

function rejectPO()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    $poid = htmlspecialchars($_POST['poid'],ENT_QUOTES, "ISO-8859-1");
    
    $suppliersmessage = htmlspecialchars($_POST['suppliersmessage'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");    
    
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'actionid' => action_PO_Rejected_by_Supplier,
        'status' => -1,
        'msg' => "'PO# ".$poid." rejected by supplier'",
        'usermsg' => "'".$suppliersmessage."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

	$res["status"] = 1;
	$res["message"] = 'PO rejected feedback send to buyer.';
	return json_encode($res);
}

?>

