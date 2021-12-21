<?php
if ( !session_id() ) {
    session_start();
}
/*
    @Author: Shohel Iqbal
    Created: 12.Mar.2016
    Code fridged on:
    Updated on: 2020-08-23 (Hasan Masud)
    1. Added Advance Tax(advanceTax) calculation
    2. Added remarks(eaRemarksOnBasic) field in basic inputs field
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

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
        default:
            break;
    }
}

if (!empty($_POST)){
    //print_r($_POST);

    if(!empty($_POST["userAction"]) || isset($_POST["userAction"])){

        switch($_POST["userAction"]) {
            case 1:
                echo cnfCDVATInputUpdate();
                break;
            case 2:
                echo cnfCDVATtoGP();
                break;
            case 3:
                echo eaCDVATInputAcceptance();
                break;
            case 4:
                echo eaCDVATInputRejection();
                break;
            default:
                break;
        }
    }
}

function cnfCDVATInputUpdate(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $attachBillOfEntry = htmlspecialchars($_POST['attachBillOfEntry'], ENT_QUOTES, "ISO-8859-1");
    $attachOtherCustomDoc = htmlspecialchars($_POST['attachOtherCustomDoc'], ENT_QUOTES, "ISO-8859-1");
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");
    if($_POST['billOfEntryDate']!="") {
        $billOfEntryDate = htmlspecialchars($_POST['billOfEntryDate'],ENT_QUOTES, "ISO-8859-1");
        $billOfEntryDate = date('Y-m-d', strtotime($billOfEntryDate));
        $billOfEntryDate = "`billOfEntryDate` = '$billOfEntryDate', ";
    }else{ $billOfEntryDate = ""; };

    if($_POST['billOfEntryNo']!="") {
        $billOfEntryNo = htmlspecialchars($_POST['billOfEntryNo'],ENT_QUOTES, "ISO-8859-1");
        $billOfEntryNo = "`billOfEntryNo` = '$billOfEntryNo', ";
    }else{ $billOfEntryNo = ""; };

    if($_POST['ddlBeneficiary']!="") {
        $ddlBeneficiary = htmlspecialchars($_POST['ddlBeneficiary'],ENT_QUOTES, "ISO-8859-1");
        $ddlBeneficiary = "`beneficiary` = $ddlBeneficiary, ";
    }else{ $ddlBeneficiary = ""; };

    // --------- TAXES ------------------------
    if($_POST['itOnCnFComm']!="") {
        $itOnCnFComm = htmlspecialchars($_POST['itOnCnFComm'], ENT_QUOTES, "ISO-8859-1");
        $itOnCnFComm = str_replace(",", "", $itOnCnFComm);
        $itOnCnFComm = "`itOnCnFComm` = $itOnCnFComm, ";
    }else{ $itOnCnFComm = ""; };

    if($_POST['vatOnCnFComm']!="") {
        $vatOnCnFComm = htmlspecialchars($_POST['vatOnCnFComm'], ENT_QUOTES, "ISO-8859-1");
        $vatOnCnFComm = str_replace(",", "", $vatOnCnFComm);
        $vatOnCnFComm = "`vatOnCnFComm` = $vatOnCnFComm, ";
    }else{ $vatOnCnFComm = ""; };

    if($_POST['docProcessFee']!="") {
        $docProcessFee = htmlspecialchars($_POST['docProcessFee'], ENT_QUOTES, "ISO-8859-1");
        $docProcessFee = str_replace(",", "", $docProcessFee);
        $docProcessFee = "`docProcessFee` = $docProcessFee, ";
    }else{ $docProcessFee = ""; };

    if($_POST['finePenalties']!="") {
        $finePenalties = htmlspecialchars($_POST['finePenalties'], ENT_QUOTES, "ISO-8859-1");
        $finePenalties = str_replace(",", "", $finePenalties);
        $finePenalties = "`finePenalties` = $finePenalties, ";
    }else{ $finePenalties = ""; };

    if($_POST['contScanningFee']!="") {
        $contScanningFee = htmlspecialchars($_POST['contScanningFee'], ENT_QUOTES, "ISO-8859-1");
        $contScanningFee = str_replace(",", "", $contScanningFee);
        $contScanningFee = "`contScanningFee` = $contScanningFee, ";
    }else{ $contScanningFee = ""; };

    if($_POST['customDuty']!="") {
        $customDuty = htmlspecialchars($_POST['customDuty'], ENT_QUOTES, "ISO-8859-1");
        $customDuty = str_replace(",", "", $customDuty);
        $customDuty = "`customDuty` = $customDuty, ";
    }else{ $customDuty = ""; };

    if($_POST['regulatoryDuty']!="") {
        $regulatoryDuty = htmlspecialchars($_POST['regulatoryDuty'], ENT_QUOTES, "ISO-8859-1");
        $regulatoryDuty = str_replace(",", "", $regulatoryDuty);
        $regulatoryDuty = "`regulatoryDuty` = $regulatoryDuty, ";
    }else{ $regulatoryDuty = ""; };

    if($_POST['supplementaryDuty']!="") {
        $supplementaryDuty = htmlspecialchars($_POST['supplementaryDuty'], ENT_QUOTES, "ISO-8859-1");
        $supplementaryDuty = str_replace(",", "", $supplementaryDuty);
        $supplementaryDuty = "`supplementaryDuty` = $supplementaryDuty, ";
    }else{ $supplementaryDuty = ""; };

    if($_POST['valueAddedTax']!="") {
        $valueAddedTax = htmlspecialchars($_POST['valueAddedTax'], ENT_QUOTES, "ISO-8859-1");
        $valueAddedTax = str_replace(",", "", $valueAddedTax);
        $valueAddedTax = "`valueAddedTax` = $valueAddedTax, ";
    }else{ $valueAddedTax = ""; };

    if($_POST['advanceIncomeTax']!="") {
        $advanceIncomeTax = htmlspecialchars($_POST['advanceIncomeTax'], ENT_QUOTES, "ISO-8859-1");
        $advanceIncomeTax = str_replace(",", "", $advanceIncomeTax);
        $advanceIncomeTax = "`advanceIncomeTax` = $advanceIncomeTax, ";
    }else{ $advanceIncomeTax = ""; };

    if($_POST['advanceTradeVat']!="") {
        $advanceTradeVat = htmlspecialchars($_POST['advanceTradeVat'], ENT_QUOTES, "ISO-8859-1");
        $advanceTradeVat = str_replace(",", "", $advanceTradeVat);
        $advanceTradeVat = "`advanceTradeVat` = $advanceTradeVat, ";
    }else{ $advanceTradeVat = ""; };

    if($_POST['advanceTax']!="") {
        $advanceTax = htmlspecialchars($_POST['advanceTax'], ENT_QUOTES, "ISO-8859-1");
        $advanceTax = str_replace(",", "", $advanceTax);
        $advanceTax = "`advanceTax` = $advanceTax, ";
    }else{ $advanceTax = ""; };

    if($_POST['totalCDVATAmount']!="") {
        $totalCDVATAmount = htmlspecialchars($_POST['totalCDVATAmount'], ENT_QUOTES, "ISO-8859-1");
        $totalCDVATAmount = str_replace(",", "", $totalCDVATAmount);
        $totalCDVATAmount = "`totalCDVATAmount` = $totalCDVATAmount, ";
    }else{ $totalCDVATAmount = ""; };

    $objdal = new dal();

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to save the data';
    //------------------------------------------------------------------------------

    // Update shipment table CD/VAT inputs
    $query = "UPDATE `cnf_inputs` SET 
		      $billOfEntryDate $billOfEntryNo $ddlBeneficiary $itOnCnFComm $vatOnCnFComm $docProcessFee 
		      $finePenalties $contScanningFee $customDuty $regulatoryDuty $supplementaryDuty $valueAddedTax 
		      $advanceIncomeTax $advanceTradeVat $advanceTax $totalCDVATAmount
		      `insertby` = $user_id
            WHERE `poid` = '$pono' AND `shipno` = '$shipno';";
    //echo($query);
    $objdal->update(trim($query), "Failed to update shipment info for CDV-AT inputs");
    //Add info to activity log table
//    addActivityLog(requestUri, 'Update old data', $user_id, 1);

    $queryInsert = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `shipno`) VALUES ";

    if ($attachBillOfEntry != '') {
        $query = $queryInsert . "('$pono', 'Bill of Entry Copy', '$attachBillOfEntry', $user_id, '$ip', $loginRole, $shipno)";
        $objdal->insert($query);
        //echo($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($pono);
    }

    if ($attachOtherCustomDoc != '') {
        $query = $queryInsert . "('$pono', 'Other Customs Doc', '$attachOtherCustomDoc', $user_id, '$ip', $loginRole, $shipno)";
        $objdal->insert($query);
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($pono);
    }
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Updated SUCCESSFULLY';
    return json_encode($res);

}

function GetShipmentInfo($pono, $shipno=0)
{
    $objdal = new dal();
    $shipVal = "";
    if ($shipno > 0) {
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
       
        $attach
        
        FROM `cnf_inputs` ";
    //echo $query;

    if ($shipno > 0) {
        $query .= "WHERE `poid` = '$pono' AND `shipno` = $shipno";
    } else {
        $query .= "WHERE `poid` = '$pono'";
    }
//    echo $query;

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

function cnfCDVATtoGP(){

    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $usermsg = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'status' => 1,
        'shipno' => $shipno,
        'actionid' => action_CNF_Input_Given,
        'usermsg' => "'".$usermsg."'",
        'msg' => "'C&F inputs sent to GP for PO# " . $pono . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Share to GP successfully';
    return json_encode($res);

}

function eaCDVATInputAcceptance(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $usermsg = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");

    if($_POST['billOfEntryDate']!="") {
        $billOfEntryDate = htmlspecialchars($_POST['billOfEntryDate'],ENT_QUOTES, "ISO-8859-1");
        $billOfEntryDate = date('Y-m-d', strtotime($billOfEntryDate));
        $billOfEntryDate = "`billOfEntryDate` = '$billOfEntryDate', ";
    }else{ $billOfEntryDate = ""; };

    if($_POST['billOfEntryNo']!="") {
        $billOfEntryNo = htmlspecialchars($_POST['billOfEntryNo'],ENT_QUOTES, "ISO-8859-1");
        $billOfEntryNo = "`billOfEntryNo` = '$billOfEntryNo', ";
    }else{ $billOfEntryNo = ""; };

    if($_POST['ddlBeneficiary']!="") {
        $ddlBeneficiary = htmlspecialchars($_POST['ddlBeneficiary'],ENT_QUOTES, "ISO-8859-1");
        $ddlBeneficiary = "`beneficiary` = $ddlBeneficiary, ";
    }else{ $ddlBeneficiary = ""; };

    // --------- TAXES ------------------------
    if($_POST['itOnCnFComm']!="") {
        $itOnCnFComm = htmlspecialchars($_POST['itOnCnFComm'], ENT_QUOTES, "ISO-8859-1");
        $itOnCnFComm = str_replace(",", "", $itOnCnFComm);
        $itOnCnFComm = "`itOnCnFComm` = $itOnCnFComm, ";
    }else{ $itOnCnFComm = ""; };

    if($_POST['vatOnCnFComm']!="") {
        $vatOnCnFComm = htmlspecialchars($_POST['vatOnCnFComm'], ENT_QUOTES, "ISO-8859-1");
        $vatOnCnFComm = str_replace(",", "", $vatOnCnFComm);
        $vatOnCnFComm = "`vatOnCnFComm` = $vatOnCnFComm, ";
    }else{ $vatOnCnFComm = ""; };

    if($_POST['docProcessFee']!="") {
        $docProcessFee = htmlspecialchars($_POST['docProcessFee'], ENT_QUOTES, "ISO-8859-1");
        $docProcessFee = str_replace(",", "", $docProcessFee);
        $docProcessFee = "`docProcessFee` = $docProcessFee, ";
    }else{ $docProcessFee = ""; };

    if($_POST['finePenalties']!="") {
        $finePenalties = htmlspecialchars($_POST['finePenalties'], ENT_QUOTES, "ISO-8859-1");
        $finePenalties = str_replace(",", "", $finePenalties);
        $finePenalties = "`finePenalties` = $finePenalties, ";
    }else{ $finePenalties = ""; };

    if($_POST['contScanningFee']!="") {
        $contScanningFee = htmlspecialchars($_POST['contScanningFee'], ENT_QUOTES, "ISO-8859-1");
        $contScanningFee = str_replace(",", "", $contScanningFee);
        $contScanningFee = "`contScanningFee` = $contScanningFee, ";
    }else{ $contScanningFee = ""; };

    if($_POST['customDuty']!="") {
        $customDuty = htmlspecialchars($_POST['customDuty'], ENT_QUOTES, "ISO-8859-1");
        $customDuty = str_replace(",", "", $customDuty);
        $customDuty = "`customDuty` = $customDuty, ";
    }else{ $customDuty = ""; };

    if($_POST['regulatoryDuty']!="") {
        $regulatoryDuty = htmlspecialchars($_POST['regulatoryDuty'], ENT_QUOTES, "ISO-8859-1");
        $regulatoryDuty = str_replace(",", "", $regulatoryDuty);
        $regulatoryDuty = "`regulatoryDuty` = $regulatoryDuty, ";
    }else{ $regulatoryDuty = ""; };

    if($_POST['supplementaryDuty']!="") {
        $supplementaryDuty = htmlspecialchars($_POST['supplementaryDuty'], ENT_QUOTES, "ISO-8859-1");
        $supplementaryDuty = str_replace(",", "", $supplementaryDuty);
        $supplementaryDuty = "`supplementaryDuty` = $supplementaryDuty, ";
    }else{ $supplementaryDuty = ""; };

    if($_POST['valueAddedTax']!="") {
        $valueAddedTax = htmlspecialchars($_POST['valueAddedTax'], ENT_QUOTES, "ISO-8859-1");
        $valueAddedTax = str_replace(",", "", $valueAddedTax);
        $valueAddedTax = "`valueAddedTax` = $valueAddedTax, ";
    }else{ $valueAddedTax = ""; };

    if($_POST['advanceIncomeTax']!="") {
        $advanceIncomeTax = htmlspecialchars($_POST['advanceIncomeTax'], ENT_QUOTES, "ISO-8859-1");
        $advanceIncomeTax = str_replace(",", "", $advanceIncomeTax);
        $advanceIncomeTax = "`advanceIncomeTax` = $advanceIncomeTax, ";
    }else{ $advanceIncomeTax = ""; };

    if($_POST['advanceTradeVat']!="") {
        $advanceTradeVat = htmlspecialchars($_POST['advanceTradeVat'], ENT_QUOTES, "ISO-8859-1");
        $advanceTradeVat = str_replace(",", "", $advanceTradeVat);
        $advanceTradeVat = "`advanceTradeVat` = $advanceTradeVat, ";
    }else{ $advanceTradeVat = ""; };

    if($_POST['advanceTax']!="") {
        $advanceTax = htmlspecialchars($_POST['advanceTax'], ENT_QUOTES, "ISO-8859-1");
        $advanceTax = str_replace(",", "", $advanceTax);
        $advanceTax = "`advanceTax` = $advanceTax, ";
    }else{ $advanceTax = ""; };

    if($_POST['totalCDVATAmount']!="") {
        $totalCDVATAmount = htmlspecialchars($_POST['totalCDVATAmount'], ENT_QUOTES, "ISO-8859-1");
        $totalCDVATAmount = str_replace(",", "", $totalCDVATAmount);
        $totalCDVATAmount = "`totalCDVATAmount` = $totalCDVATAmount, ";
    }else{ $totalCDVATAmount = ""; };

    $objdal = new dal();

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to save the data';
    //------------------------------------------------------------------------------

    // Update shipment table CD/VAT inputs
    $query = "UPDATE `wc_t_shipment` SET 
		      $billOfEntryDate $billOfEntryNo $ddlBeneficiary $itOnCnFComm $vatOnCnFComm $docProcessFee 
		      $finePenalties $contScanningFee $customDuty $regulatoryDuty $supplementaryDuty $valueAddedTax 
		      $advanceIncomeTax $advanceTradeVat $advanceTax $totalCDVATAmount
		      `insertby` = $user_id
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
    //echo($query);
    $objdal->update(trim($query), "Failed to update shipment info for CDV-AT inputs");
    //Add info to activity log table
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'status' => 1,
        'shipno' => $shipno,
        'actionid' => action_Accept_CNF_Inputs,
        'newstatus' => 1,
        'usermsg' => "'".$usermsg."'",
        'msg' => "'Acknowledgement'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------


    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Updated SUCCESSFULLY';
    return json_encode($res);

}

function eaCDVATInputRejection(){

    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $usermsg = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'status' => -1,
        'shipno' => $shipno,
        'actionid' => action_Reject_CNF_Inputs,
        'usermsg' => "'".$usermsg."'",
        'msg' => "'C&F inputs rejected for PO# " . $pono . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Rejeted inputs';
    return json_encode($res);

}
?>

