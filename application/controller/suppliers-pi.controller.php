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
    $objdal = new dal();

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $postatus = $objdal->sanitizeInput($_POST['postatus']);

    $poid = $objdal->sanitizeInput($_POST['pono']);
    $pinum = replaceTextRegex($_POST['pinum']);

    $pivalue = $objdal->sanitizeInput($_POST['pivalue']);
    $pivalue = str_replace(",", "", $pivalue);

    $pidesc = $objdal->sanitizeInput($_POST['pi_description']);

    $producttype = $objdal->sanitizeInput($_POST['producttype']);

    $shipmode = $objdal->sanitizeInput($_POST['shipmode']);
    $hscode = $objdal->sanitizeInput(replaceTextRegex($_POST['hscode']));
    $importAs = $objdal->sanitizeInput($_POST['importAs']);

    if ($postatus >= action_Requested_for_Final_PI) {
        $pidate = $objdal->sanitizeInput($_POST['pidate']);
        $pidate = date('Y-m-d', strtotime($pidate));

        $basevalue = $objdal->sanitizeInput($_POST['basevalue']);
        $basevalue = str_replace(",", "", $basevalue);
    }

    $origin = '';
    foreach ($_POST['origin'] as $val) {
        if (strlen($origin) > 0) {
            $origin .= ',';
        }
        $origin .= $objdal->sanitizeInput($val);
    }

    $negobank = $objdal->sanitizeInput($_POST['negobank']);
    $shipport = $objdal->sanitizeInput(replaceTextRegex($_POST['shipport']));
    $lcbankaddress = $objdal->sanitizeInput($_POST['lcbankaddress']);
    $productiondays = $objdal->sanitizeInput($_POST['productiondays']);

    $nofLcIssue = $objdal->sanitizeInput($_POST['nofLcIssue']);
    $nofShipAllow = $objdal->sanitizeInput($_POST['nofShipAllow']);
    $installBy = $objdal->sanitizeInput($_POST['installBy']);

    if (!isset($_POST['messageyes'])) {
        $suppliersmessage = 'NULL';
    } else {
        $suppliersmessage = "'" . $objdal->sanitizeInput($_POST['suppliersmessage']) . "'";
    }

    $ip = $objdal->sanitizeInput($_SERVER['REMOTE_ADDR']);
    /*
	//---To protect MySQL injection for Security purpose----------------------------
    $poid = stripslashes($poid);
    $pivalue = stripslashes($pivalue);
    $producttype = stripslashes($producttype);
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
	
//	$objdal = new dal();
	
    $poid = $objdal->real_escape_string($poid);
    $pivalue = $objdal->real_escape_string($pivalue);
    $producttype = $objdal->real_escape_string($producttype);
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
    $productiondays = $objdal->real_escape_string($productiondays);*/

    // attachment data in an 3D array
    if ($postatus < action_Draft_PI_Submitted) {
        $attachpo = $objdal->sanitizeInput($_POST['attachpo']);
        $attachboq = $objdal->sanitizeInput($_POST['attachboq']);
        $attachother = $objdal->sanitizeInput($_POST['attachother']);
    }
    $attachDraftPI = $objdal->sanitizeInput($_POST['attachDraftPI']);
    $attachDraftBOQ = $objdal->sanitizeInput($_POST['attachDraftBOQ']);
    $attachCatelog = $objdal->sanitizeInput($_POST['attachCatelog']);

    //------------------------------------------------------------------------------
    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------
    $forFinal = "";
    if ($postatus >= action_Requested_for_Final_PI) {
        $forFinal = "
        `pidate` = '$pidate',
        `basevalue` = '$basevalue',";
    }

    if ($postatus != action_Requested_for_Final_PI) {
        // Getting PI sequence number
        $query = "SELECT count(*)+1 `PIReqNo` FROM wc_t_pi where PONum = '$poid';";
        $PIReqNo = $objdal->getScalar($query);

        $systemPINo = $poid . 'PI' . $PIReqNo;
        //echo $systemPINo;
        $query = "INSERT INTO `wc_t_pi` (`poid`,`PONum`,`PIReqNo`,`povalue`,`podesc`,`importAs`,`supplier`,`currency`,`contractref`,`deliverydate`, 
                    `draftsendby`,`actualPoDate`,`emailto`,`emailcc`,`pruserto`,`prusercc`,`noflcissue`,`nofshipallow`, 
                    `installbysupplier`,`createdby`,`createdfrom`,`pr_no`,`department`,`supplier_address`)
                SELECT '$systemPINo', p.poNo, $PIReqNo, p.poValue, p.poDesc, p.importAs, p.supplier, p.currency, p.contractRef, p.deliveryDate, 
                    p.draftSendBy, p.actualPoDate, p.supplierEmailTo, p.supplierEmailCc, p.prUser, p.prUserCc, p.nofLcIssue, p.nofShipAllow,
                    p.installBy, p.createdBy, p.createdFrom, p.prNo, p.department, p.supplierAddress
                FROM `po` p
                WHERE p.poNo = '$poid';";

        $objdal->insert($query, "Failed to insert PO information");
    } else {
        $systemPINo = $poid;
    }
    // update new po
    $query = "UPDATE `wc_t_pi` SET 
		`pinum` = '$pinum',
        `pivalue` = $pivalue,
        `hscode` = '$hscode',
        `importAs` = $importAs,
        `shipmode` = '$shipmode',
        `origin` = '$origin', $forFinal
        `negobank` = '$negobank',
        `shipport` = '$shipport',
        `lcbankaddress` = '$lcbankaddress',
        `productiondays` = $productiondays,
        `noflcissue` = $nofLcIssue, 
        `nofshipallow` = $nofShipAllow, 
        `installbysupplier` = $installBy, 
        `modifiedby` = $user_id,
        `modifiedfrom` = '$ip',
        `pi_description` = '$pidesc',
        `producttype` = $producttype
        WHERE `poid` = '$systemPINo';";
//    echo($query);
    //exit();
    $objdal->update($query, "Failed to update PO data");

    // PI attachment naming
    if ($postatus < action_Draft_PI_Submitted) {

        $piAttachmentName = 'Suppliers Draft PI';
        // insert attachment
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$systemPINo', 'Buyers PO', '$attachpo', $user_id, '$ip', $loginRole),
        ('$systemPINo', 'Buyers BOQ', '$attachboq', $user_id, '$ip', $loginRole),
        ('$systemPINo', '$piAttachmentName', '$attachDraftPI', $user_id, '$ip', $loginRole),
        ('$systemPINo', 'BTRC BOQ', '$attachDraftBOQ', $user_id, '$ip', $loginRole),
        ('$systemPINo', 'Suppliers Catalog', '$attachCatelog', $user_id, '$ip', $loginRole)";
        if ($attachother != '') {

            $query .= ",('$systemPINo', 'Other PO Doc', '$attachother', $user_id, '$ip', $loginRole)";
        }
    } else{
        $piAttachmentName = 'Suppliers Final PI';
        // insert attachment
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$systemPINo', '$piAttachmentName', '$attachDraftPI', $user_id, '$ip', $loginRole),
        ('$systemPINo', 'BTRC BOQ', '$attachDraftBOQ', $user_id, '$ip', $loginRole),
        ('$systemPINo', 'Suppliers Catalog', '$attachCatelog', $user_id, '$ip', $loginRole);";
    }


    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    //fileTransferTempToDocs($poid);
    if ($postatus != action_Requested_for_Final_PI) {

        /*
        // Deleting old po lines
        $delete = "DELETE FROM `pi_lines` WHERE `poNo` = '$poid'";
        $objdal->delete($delete);*/

        $sqlHeader = "INSERT INTO `pi_lines`(
                `buyersPo`,
                `poNo`, 
                `lineNo`, 
                `PIReqNo`,
                `itemCode`, 
                `itemDesc`, 
                `deliveryDate`, 
                `uom`, 
                `unitPrice`, 
                `poQty`, 
                `poTotal`, 
                `delivQty`, 
                `delivTotal`) VALUES ";
        $sqlRows = '';
        $sqlUpdateLineStatus = "UPDATE `po_lines` SET `status`= 1 WHERE `poNo` = '$poid' AND `lineNo` IN (XXXXX);";
        $lineNOs = "";
        $j = 0;
        /*!
         * Replace REPEATED |(PIPE) sign from string
         * to protect empty row on partial line submission $_POST variable
         * **********************************************************************/
        $regex = "/\|+/";
        $trimmedPoLines = rtrim(preg_replace($regex, '|', $_POST["consolidatedPoLines"]), '|');

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
            $delivQtyValid = $objdal->sanitizeInput($separateCol[9]);
            $delivQtyValid = str_replace(",", "", $delivQtyValid);
            $delivTotal = str_replace(",", "", $separateCol[10]);
            $delivTotal = $objdal->sanitizeInput($delivTotal);
            /*$ldAmount = $objdal->sanitizeInput($_POST['ldAmnt'][$i], ENT_QUOTES, "ISO-8859-1");
            $ldAmount = str_replace(",", "", $ldAmount);*/
            // insert new again

            if ($sqlRows != '') {
                $sqlRows .= ',';
            }

            $sqlRows .= "(
            '$poid',
            '$systemPINo', 
            $lineNo, 
            $PIReqNo,
            '$itemCode', 
            '$itemDesc', 
            '$poDate', 
            '$uom', 
            $unitPrice, 
            $poQty, 
            $poTotal, 
            $delivQty, 
            $delivTotal)";

            if ($delivQtyValid == $delivQty) {
                if ($lineNOs == "")
                {
                    $lineNOs = $lineNo;
                }
                else{
                    $lineNOs .= ',' . $lineNo;
                }
            }

            $j++;
            if ($j == 300) {
                $sql = $sqlHeader . $sqlRows . ';';
//            echo $sql;
                $objdal->insert($sql, "Failed to save PO Lines");
                $sqlRows = '';
                if ($lineNOs != "") {
                    $sqlUpdateLineStatus = str_ireplace('XXXXX', $lineNOs, $sqlUpdateLineStatus);
//            echo $sqlUpdateLineStatus;
                    $objdal->update($sqlUpdateLineStatus);
                }
                $j = 0;
            }
        }
        // finally if any rest rows to insert
        if ($sqlRows != "") {
            //echo $sqlHeader . $sqlRows;
            $objdal->insert($sqlHeader . $sqlRows . ';', "Failed to save PO Lines");
            $sqlRows = '';
            if ($lineNOs != "") {
                $sqlUpdateLineStatus = str_ireplace('XXXXX', $lineNOs, $sqlUpdateLineStatus);
//        echo $sqlUpdateLineStatus;
                $objdal->update($sqlUpdateLineStatus);
                $sqlUpdateLineStatus = '';
            }
            $j = 0;
        }
    }
    unset($objdal);

    // Action Log --------------------------------//
    if ($postatus >= action_Requested_for_Final_PI) {
        $newActionID = action_Final_PI_Submitted;
        $msg = "'Final PI submitted against PO# " . $systemPINo . "'";
    } else {
        $newActionID = action_Draft_PI_Submitted;
        $msg = "'Draft PI submitted against PO# " . $systemPINo . "'";
    }
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $systemPINo . "'",
        'actionid' => $newActionID,
        'status' => 1,
        'msg' => $msg,
        'usermsg' => $suppliersmessage,
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'PI submitted successfully';
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

