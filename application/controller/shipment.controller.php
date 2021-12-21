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

//sendActionEmail(964);

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:
            if(!empty($_GET["shipno"])){
	           echo GetShipmentInfo($_GET["po"], $_GET["shipno"]);
            } else{
                echo GetShipmentInfo($_GET["po"]);
            }
			break;
		case 2:
			echo GetCustomDutyDataFromLC($_GET["lc"]);
			break;
		case 3:
			echo GetShipByLC($_GET["lc"],$_GET["col"]);
			break;
		case 4:
			echo GetCiValue($_GET["lc"],$_GET["cino"]);
			break;
		case 5:
			echo GetShipByPo($_GET["po"],$_GET["col"]);
			break;
		case 6:
			echo GetAvgCostInfo($_GET["po"],$_GET["mawb"],$_GET["hawb"],$_GET["bl"]);
			break;
        case 7:
            echo getShareingStatus($_GET["po"], $_GET["shipno"]);
			break;
        case 8:
            echo getPOByLC($_GET["lc"]);
			break;
        case 9:
            if(!empty($_GET["shipno"])){
	           echo getTotalCIValue($_GET["po"], $_GET["shipno"]);
            } else{
                echo getTotalCIValue($_GET["po"]);
            }
			break;
        case 10:
            echo getLCListByPO($_GET["po"], $_GET["shipno"]);
			break;
        case 11:
            echo getCIList($_GET["po"]);
			break;
        case 12:
            echo getTotalEndorsedValue($_GET["po"]);
			break;
		case 13:
            echo checkStepOver($_GET['pono'], $_GET['actionId'], $_GET['shipno']);
            break;
		case 14:
            echo checkStepOver($_GET['pono'], $_GET['actionId'], $_GET['shipno'],null,true);
            break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["pono"]) || isset($_POST["pono"])){
		if($_POST["userAction"]==1){
			echo saveShipmentSchedule();
		}
		if($_POST["userAction"]==2){
			echo updateDHLTrackingNumber();
		}
	}
}

/*!
 * Insert or update shipment data
 *
 */
function saveShipmentSchedule()
{
	/*echo '<pre>';
        var_dump($_POST);
    echo '</pre>';*/
	global $user_id;
	global $loginRole;

	$refId = decryptId($_POST["refId"]);
	if (!is_numeric($refId)) {
		$res["status"] = 0;
		$res["message"] = 'Invalid reference code.';
		return json_encode($res);
	} else {
		//$res["status"] = 0;
		//$res["message"] = 'Valid reference code.'.$refId;
		//return json_encode($res);
	}

	$objdal = new dal();

	$pono = $objdal->sanitizeInput($_POST['pono']);
	$lcno = $objdal->sanitizeInput($_POST['lcno']);
	$shipNo = $objdal->sanitizeInput($_POST['shipNo']);
	$lastAction = $objdal->sanitizeInput($_POST['lastAction']);

	$scheduleETA = $objdal->sanitizeInput($_POST['scheduleETA']);
	$scheduleETA = date('Y-m-d', strtotime($scheduleETA));

	$scheduleETD = $objdal->sanitizeInput($_POST['scheduleETD']);
	$scheduleETD = date('Y-m-d', strtotime($scheduleETD));

	$shipmode = $objdal->sanitizeInput($_POST['shipmode']);
	$mawbNo = $objdal->sanitizeInput($_POST['mawbNo']);
	$hawbNo = $objdal->sanitizeInput($_POST['hawbNo']);
	$blNo = $objdal->sanitizeInput($_POST['blNo']);
	$awbOrBlDate = $objdal->sanitizeInput($_POST['awbOrBlDate']);
	$awbOrBlDate = date('Y-m-d', strtotime($awbOrBlDate));
	$ciNo = $objdal->sanitizeInput($_POST['ciNo']);
	$ciDate = $objdal->sanitizeInput($_POST['ciDate']);
	$ciDate = date('Y-m-d', strtotime($ciDate));
	$ciAmount = $objdal->sanitizeInput($_POST['ciAmount']);
	$ciAmount = str_replace(",", "", $ciAmount);

	$invoiceQty = $objdal->sanitizeInput($_POST['invoiceQty']);
	$noOfcontainer = $objdal->sanitizeInput($_POST['noOfcontainer']);
	$noOfBoxes = $objdal->sanitizeInput($_POST['noOfBoxes']);
	$ChargeableWeight = $objdal->sanitizeInput($_POST['ChargeableWeight']);
	//$dhlTrackNo = htmlspecialchars($_POST['dhlTrackNo']);

	$attachAwbOrBlScanCopy = $objdal->sanitizeInput($_POST['attachAwbOrBlScanCopy']);
	$attachCiScanCopy = $objdal->sanitizeInput($_POST['attachCiScanCopy']);
	$attachPackListScanCopy = $objdal->sanitizeInput($_POST['attachPackListScanCopy']);
	$attachOriginCertificate = $objdal->sanitizeInput($_POST['attachOriginCertificate']);
	$attachFreightCertificate = $objdal->sanitizeInput($_POST['attachFreightCertificate']);
	$attachShipmentOther = $objdal->sanitizeInput($_POST['attachShipmentOther']);

	$attachAwbOrBlScanCopyOld = $objdal->sanitizeInput($_POST['attachAwbOrBlScanCopyOld']);
	$attachCiScanCopyOld = $objdal->sanitizeInput($_POST['attachCiScanCopyOld']);
	$attachPackListScanCopyOld = $objdal->sanitizeInput($_POST['attachPackListScanCopyOld']);
	$attachOriginCertificateOld = $objdal->sanitizeInput($_POST['attachOriginCertificateOld']);
	$attachFreightCertificateOld = $objdal->sanitizeInput($_POST['attachFreightCertificateOld']);
	$attachShipmentOtherOld = $objdal->sanitizeInput($_POST['attachShipmentOtherOld']);

	$ip = $_SERVER['REMOTE_ADDR'];

	//------------------------------------------------------------------------------

	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

	// Checking if EA team already rejected this Shipping Docs
	if ($lastAction != action_Ship_Doc_Rejected_EATeam && $lastAction != action_Shipment_Document_Rejected) {
		$taskMessage = 'Insert new data';
		$query = "INSERT INTO `wc_t_shipment` SET 
			`pono` = '$pono', 
			`lcNo` = '$lcno', 
			`shipNo` = $shipNo, 
			`shipmode` = '$shipmode', 
			`scheduleETA` = '$scheduleETA', 
			`scheduleETD` = '$scheduleETD', 
			`mawbNo` = '$mawbNo', 
			`hawbNo` = '$hawbNo', 
			`blNo` = '$blNo', 
			`awbOrBlDate` = '$awbOrBlDate', 
			`ciNo` = '$ciNo', 
			`ciDate` = '$ciDate', 
			`ciAmount` = '$ciAmount', 
			`invoiceQty` = '$invoiceQty', 
			`noOfcontainer` = '$noOfcontainer', 
			`noOfBoxes` = '$noOfBoxes',  
			`ChargeableWeight` = '$ChargeableWeight',
			`insertby` = $user_id,
			`insertfrom` = '$ip';";
		//echo $query;
		$objdal->insert($query);
	} else {
		$taskMessage = 'Update old data';
		$query = "UPDATE `wc_t_shipment` SET 
			`lcNo` = '$lcno', 
			`shipmode` = '$shipmode', 
			`scheduleETA` = '$scheduleETA', 
			`scheduleETD` = '$scheduleETD', 
			`mawbNo` = '$mawbNo', 
			`hawbNo` = '$hawbNo', 
			`blNo` = '$blNo', 
			`awbOrBlDate` = '$awbOrBlDate', 
			`ciNo` = '$ciNo', 
			`ciDate` = '$ciDate', 
			`ciAmount` = '$ciAmount', 
			`invoiceQty` = '$invoiceQty', 
			`noOfcontainer` = '$noOfcontainer', 
			`noOfBoxes` = '$noOfBoxes',  
			`ChargeableWeight` = '$ChargeableWeight',
			`insertby` = $user_id,
			`insertfrom` = '$ip'
			WHERE `pono` = '$pono' AND `shipNo` = $shipNo;";
		$objdal->update($query);
	}

	//echo($query);
	$lastShipId = $objdal->LastInsertId();

	$query = "UPDATE `wc_t_shipment_ETA` SET `status`=1 WHERE `pono`='$pono' AND `shipNo` = $shipNo;";
	$objdal->update($query);

	// Insert attachment
	if ($lastAction != action_Ship_Doc_Rejected_EATeam && $lastAction != action_Shipment_Document_Rejected) {
		if ($shipmode != 'E-Delivery') {
			$query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`) VALUES 
			('$pono', 'AWB/BL Scan Copy', '$attachAwbOrBlScanCopy', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId)),
			('$pono', 'CI Scan Copy', '$attachCiScanCopy', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId)),
			('$pono', 'Packing List Scan Copy', '$attachPackListScanCopy', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId)),
			('$pono', 'Certificate of Origine Scan Copy', '$attachOriginCertificate', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId)),
			('$pono', 'Freight Certificate', '$attachFreightCertificate', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId))";
		} else {
			$query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`) VALUES 
			('$pono', 'CI Scan Copy', '$attachCiScanCopy', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId))";
		}

		if ($attachShipmentOther != '') {
			$query .= ",('$pono', 'Shipment Other Docs', '$attachShipmentOther', $user_id, '$ip', $loginRole, '$lcno', (SELECT `shipNo` FROM `wc_t_shipment` WHERE `id` = $lastShipId));";
		}
		$objdal->insert($query);
	} else {
		if ($attachAwbOrBlScanCopyOld != $attachAwbOrBlScanCopy && $attachAwbOrBlScanCopy != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachAwbOrBlScanCopy' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachAwbOrBlScanCopyOld';";
			$objdal->update($query);
		}
		if ($attachCiScanCopyOld != $attachCiScanCopy && $attachCiScanCopy != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachCiScanCopy' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachCiScanCopyOld';";
			$objdal->update($query);
		}
		if ($attachPackListScanCopyOld != $attachPackListScanCopy && $attachPackListScanCopy != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachPackListScanCopy' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachPackListScanCopyOld';";
			$objdal->update($query);
		}
		if ($attachOriginCertificateOld != $attachOriginCertificate && $attachOriginCertificate != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachOriginCertificate' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachOriginCertificateOld';";
			$objdal->update($query);
		}
		if ($attachFreightCertificateOld != $attachFreightCertificate && $attachFreightCertificate != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachFreightCertificate' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachFreightCertificateOld';";
			$objdal->update($query);
		}
		if ($attachShipmentOtherOld != $attachShipmentOther && $attachShipmentOther != "") {
			$query = "UPDATE `wc_t_attachments` SET `filename` = '$attachShipmentOther' WHERE `poid` = '$pono' AND `shipno` = $shipNo AND `filename` = '$attachShipmentOtherOld';";
			$objdal->update($query);
		}
	}

	// Action Log --------------------------------//
	if ($lastAction != action_Ship_Doc_Rejected_EATeam && $lastAction != action_Shipment_Document_Rejected) {
		$action = array(
			'refid' => $refId,
			'pono' => "'" . $pono . "'",
			'shipno' => $shipNo,
			'actionid' => action_Shared_Shipment_Document,
			'status' => 1,
			'msg' => "'Documents for Shipment # " . $shipNo . " has been shared by Supplier against PO# " . $pono . "'",
		);
	}else{
		$action = array(
			'refid' => $refId,
			'pono' => "'" . $pono . "'",
			'shipno' => $shipNo,
			'actionid' => action_Shipping_Doc_Rectified_by_Supplier,
			'status' => 1,
			'msg' => "'Shipment documents rectified by supplier against PO# " . $pono . " and Shipment# " . $shipNo . "'",
		);
	}
	UpdateAction($action);

	if ($lastAction != action_Ship_Doc_Rejected_EATeam && $lastAction != action_Shipment_Document_Rejected) {
		$action = array(
			'refid' => $refId,
			'pono' => "'" . $pono . "'",
			'shipno' => $shipNo,
			'actionid' => action_Ship_Doc_Shared_DHL_Track_Pending,
			'msg' => "'Shipment # " . $shipNo . " document shared and pending DHL tracking number against PO# " . $pono . "'",
		);
		UpdateAction($action);
	}
	// End Action Log -----------------------------
	//Transfer file from 'temp' directory to respective 'docs' directory
	fileTransferTempToDocs($pono);

	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'Shipment document submitted SUCCESSFULLY!';
	return json_encode($res);

}

// Updating DHL tracking number
function updateDHLTrackingNumber(){

	global $user_id;
	global $loginRole;

	$refId = decryptId($_POST["refId"]);
	if(!is_numeric($refId)){
		$res["status"] = 0;
		$res["message"] = 'Invalid reference code.';
		return json_encode($res);
	}

	$pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
	$shipNo = htmlspecialchars($_POST['shipNo'],ENT_QUOTES, "ISO-8859-1");
	$dhlTrackNo = htmlspecialchars($_POST['dhlTrackNo'],ENT_QUOTES, "ISO-8859-1");

	$pono = stripslashes($pono);
	$shipNo = stripslashes($shipNo);
	$dhlTrackNo = stripslashes($dhlTrackNo);

	$objdal = new dal();

	$pono = $objdal->real_escape_string($pono);
	$shipNo = $objdal->real_escape_string($shipNo);
	$dhlTrackNo = $objdal->real_escape_string($dhlTrackNo);

	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

	$query = "UPDATE `wc_t_shipment` SET `dhlTrackNo`='$dhlTrackNo' WHERE `pono`='$pono' AND `shipNo` = $shipNo;";
    //echo $query;
	$objdal->update($query);
	//Add info to activity log table
	addActivityLog(requestUri, 'DHL tracking number updated', $user_id, 1);

	unset($objdal);

	// Action Log --------------------------------//
	$action = array(
		'refid' => $refId,
		'pono' => "'".$pono."'",
		'shipno' => $shipNo,
		'actionid' => action_DHL_Track_No_Updated,
		'status' => 1,
		'msg' => "'DHL tracking No. updated for Shipment # ".$shipNo." against PO# ".$pono."'",
	);
	UpdateAction($action);
	// End Action Log -----------------------------

	$res["status"] = 1;
	$res["message"] = 'DHL tracking number updated SUCCESSFULLY!';
	return json_encode($res);

}

/*!
 * Reasons why didn't send & encrypt attachment ID
 * 1. Sub-query returns more than one row(invalid result)
 * 2. File title unavailable
 * 3. 12 attachment id encryption individually
 * 4. Encrypted result appears as numeric index, which is hard to implement in front-end & debug
 * ********************************************************************/
function GetShipmentInfo($pono, $shipno=0)
{
    $objdal = new dal();
    $shipVal = "";
    if ($shipno > 0) {
        $shipVal = "(SELECT sum(`ciAmount`) ciAmount FROM `wc_t_shipment` WHERE `pono`='$pono' AND `shipNo`=$shipno) `totalShipValue`,";

        $attach = "
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='AWB/BL Scan Copy' ORDER BY `id` DESC LIMIT 1) `attachAwbOrBlScanCopy`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='CI Scan Copy' ORDER BY `id` DESC LIMIT 1) `attachCiScanCopy`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Packing List Scan Copy' ORDER BY `id` DESC LIMIT 1) `attachPackListScanCopy`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Certificate of Origine Scan Copy' ORDER BY `id` DESC LIMIT 1) `attachOriginCertificate`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Freight Certificate' ORDER BY `id` DESC LIMIT 1) `attachFreightCertificate`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Shipment Other Docs' ORDER BY `id` DESC LIMIT 1) `attachShipmentOther`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Bill of Entry Copy' ORDER BY `id` DESC LIMIT 1) `attachInsCoverNote`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Custom Duty Copy' ORDER BY `id` DESC LIMIT 1) `attachPayOrderReceivedCopy`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Bill of Entry Copy' ORDER BY `id` DESC LIMIT 1) `attachBillOfEntry`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Other Customs Doc' ORDER BY `id` DESC LIMIT 1) `attachOtherCustomDoc`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Original Bank Document' ORDER BY `id` DESC LIMIT 1) `attachOriginalBankDoc`,
        	(SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno`=$shipno AND `title`='Endorsement Copy' ORDER BY `id` DESC LIMIT 1) `attachEndorsedBankDoc`";
    }
    $query = "SELECT *, 
        (SELECT `lcno` FROM `wc_t_lc` WHERE `pono`='$pono') `lcno`, 
        (SELECT `lcdesc` FROM `wc_t_lc` WHERE `pono`='$pono') `lcdesc`, 
        (SELECT c1.`name` FROM `wc_t_lc` lc LEFT JOIN `wc_t_category` c1 ON lc.`producttype` = c1.`id` WHERE `pono`='$pono') `producttype`, 
        (SELECT `lcvalue` FROM `wc_t_lc` WHERE `pono`='$pono') `lcvalue`, 
        (SELECT `lctype` FROM `wc_t_lc` WHERE `pono`='$pono') `lctype`, 
        (SELECT `lcissuedate` FROM `wc_t_lc` WHERE `pono`='$pono') `lcissuedate`, 
        (SELECT `lcissuerbank` FROM `wc_t_lc` WHERE `pono`='$pono') `lcissuerbank`, 
        (SELECT `currency` FROM `wc_t_po` WHERE `poid`='$pono') `currency`, $shipVal
        (SELECT MAX(`banknotifydate`) FROM `wc_t_original_doc` WHERE `lcno`=(SELECT `lcno` FROM `wc_t_lc` WHERE `pono`='$pono')) `banknotifydate`,
        (SELECT `ActionOn` FROM `wc_t_action_log` WHERE `PO` = '$pono' AND `shipNo` = $shipno AND `ActionID` = " . action_CD_BE_Copy_updated . " AND `Status` in (0,1)) `payOrderReqDate`,
        
        $attach
        
        FROM `wc_t_shipment` ";
    //echo $query;

    if ($shipno > 0) {
        $query .= "WHERE `pono` = '$pono' AND `shipNo` = $shipno";
    } else {
        $query .= "WHERE `pono` = '$pono'";
    }
    //echo $query;

    $res = "";
    $objdal->read($query);
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    if ($res == "") {
        return $res;
    } else {
        return json_encode($res);
    }
    return $query;
}

function GetAvgCostInfo($pono, $mawb, $hawb, $bl)
{
	$objdal = new dal();
	$query = "SELECT `ciAmount`, `bankChargeCapex`, `insuranceCapex`, `cnfNetPayment`, `proportionateCost`
        FROM `wc_t_shipment` 
        WHERE `pono` = '$pono' ";
    
    if($mawb!=""){
        $query .= " AND `mawbNo`='$mawb' ";
    }
    
    if($hawb!=""){
        $query .= " AND `hawbNo`='$hawb' '";
    }
    
    if($bl!=""){
        $query .= " AND `blNo`='$bl'";
    }
        
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        //$ciVal = $ciAmount;
	}
	unset($objdal);
	return json_encode($res);
    //echo $query;
}

function GetCiValue($lcno, $ciNo)
{
	$objdal = new dal();
	$query = "SELECT `ciAmount`,
        (SELECT `lcvalue` FROM `wc_t_lc` WHERE `LcNo`='$lcno') `lcvalue`,
        (SELECT SUM(`docName`) FROM `wc_t_payment` WHERE `LcNo`='$lcno' AND `ciNo` = '$ciNo') `docName`,
        (SELECT SUM(`exchangeRate`) FROM `wc_t_payment` WHERE `LcNo`='$lcno' AND `ciNo` = '$ciNo') `exchangeRate`,
        (SELECT SUM(`paymentPercent`) FROM `wc_t_payment` WHERE `LcNo`='$lcno' AND `ciNo` = '$ciNo') `paidPart`,
        (SELECT SUM(`amount`) `totalAmount` FROM `wc_t_payment` WHERE `LcNo`='$lcno' AND `ciNo` = '$ciNo') `paidAmount`
        FROM `wc_t_shipment` WHERE `pono` = (SELECT `pono` FROM `wc_t_lc` WHERE `lcno`='$lcno') AND `ciNo`='$ciNo';";
    //echo $query;
	$objdal->read($query);
    $returnVal = 0;
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $returnVal = json_encode($res);
	}
	unset($objdal);
	return $returnVal;
    //echo $query;
}

function getShareingStatus($pono, $shipNo)
{
	$objdal = new dal();
	$query = "SELECT `status`
        FROM `wc_t_shipment_ETA` WHERE `pono` = '$pono' AND `shipNo`=$shipNo;";
	$objdal->read($query);
    $returnVal = 0;
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $returnVal = $status;
	}
	unset($objdal);
	return $returnVal;
}

function GetShipByLC($lcno, $col)
{
	$objdal = new dal();
	$query = "SELECT `$col` `col1` 
            FROM `wc_t_shipment` 
        WHERE `pono` = (SELECT `pono` FROM `wc_t_lc` WHERE `lcno`='$lcno');";
	$objdal->read($query);
	
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$col1.'", "text": "'.$col1.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function GetShipByPo($pono, $col)
{
	$objdal = new dal();
	$query = "SELECT `$col` `col1` 
            FROM `wc_t_shipment` 
        WHERE `pono` = '$pono';";
	$objdal->read($query);
	
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$col1.'", "text": "'.$col1.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function GetCustomDutyDataFromLC($lcno){
    
    $objdal = new dal();
	
    $query = "SELECT `pono`, `lcissuedate`, `lcvalue`  FROM `wc_t_lc` WHERE `lcno` = '$lcno';";
	$objdal->read($query);
    //echo $query;
    $pono = "";
    
    if(!empty($objdal->data)){
		$lcinfo[0] = $objdal->data[0];
        $pono = $lcinfo[0]["pono"];
	}
    
    $i=0;
    unset($objdal->data);
    
    $query = "SELECT `ciAmount`, `ciNo`, `mawbNo`, `hawbNo`, `blNo`, `awbOrBlDate`,
            GERPVoucherNo, GERPVoucherDate 
        FROM `wc_t_shipment` WHERE `pono` = '$pono';";
	$objdal->read($query);
    
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
    		$shipinfo[$i] = $val;
            $i++;
        }
	}
    unset($objdal);
	//echo $query;
    return json_encode(array($lcinfo, $shipinfo));
}

function getPOByLC($lcno)
{
	$objdal = new dal();
	$query = "SELECT `pono` 
            FROM `wc_t_shipment` 
        WHERE `pono` = (SELECT `pono` FROM `wc_t_lc` WHERE `lcno`='$lcno');";
	$objdal->read($query);
    $res = 0;
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function getLCListByPO($pono, $shipno)
{
    
    $objdal=new dal();
	$sql = "SELECT `lcno` FROM `wc_t_shipment` WHERE `pono` = '$pono' AND `shipNo` = $shipno;";
	$objdal->read($sql); 
	
    // json
	$jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$lcno.'", "text": "'.$lcno.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function getTotalCIValue($pono, $shipno=0)
{
	$objdal = new dal();
    if($shipno==0){
	   $query = "SELECT ifnull(sum(`ciAmount`),0) `totalCi`
            FROM `wc_t_shipment` 
            WHERE `pono` = '$pono';";
    } else{
        $query = "SELECT ifnull(sum(`ciAmount`),0) `totalCi`
            FROM `wc_t_shipment` 
            WHERE `pono` = '$pono' AND `shipNo`<$shipno;";
    }
    //echo $query;
	$objdal->read($query);
    
    $returnVal = 0;
    
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $returnVal = $res['totalCi'];
	}
	
    unset($objdal);
	return $returnVal;
    //return $query;
}

function getCIList($pono = '')
{
    $objdal=new dal();
    $sql = "SELECT `shipNo`, `ciNo` FROM `wc_t_shipment`";
	if($pono!=''){
		$sql .= " WHERE `pono` = '$pono' ORDER BY `shipNo`;";
	}

	$objdal->read($sql);
	
    // json
	$jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$shipNo.'", "text": "'.$ciNo.'"}';		
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function getTotalEndorsedValue($pono)
{
	$objdal = new dal();
	$query = "SELECT 
			PO, SUM(endValue) AS totalEndVal
		FROM
			(SELECT 
				l.PO,
					l.shipNo,
					(SELECT 
							s.`ciAmount`
						FROM
							`wc_t_shipment` AS s
						WHERE
							s.`pono` = l.PO AND s.shipNo = l.shipNo) AS `endValue`
			FROM
				wc_t_action_log AS l
			WHERE
				l.PO = '$pono'
					AND l.ActionID IN (".action_Original_Document_Delivered." , ".action_Endorsed_Document_Delivered.")) AS t
		GROUP BY PO";
	//echo $query;
	$objdal->read($query);

	$returnVal = 0;

	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
		$returnVal = $res['totalEndVal'];
	}

	unset($objdal);
	return $returnVal;
	//return $query;
}


?>

