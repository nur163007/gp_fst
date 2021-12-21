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


if (!empty($_POST)){
    //print_r($_POST);
    
    if(!empty($_POST["userAction"]) || isset($_POST["userAction"])){
        
        switch($_POST["userAction"]) {
            case 1:
                echo eaBasicInputUpdate();
                break;
            case 2:
                echo eaCDVATInputUpdate();
                break;
            case 3:
                echo requestSendToFinance();
                break;
            case 4:
                echo reject();
                break;
            case 5:
                break;
            case 6:
                echo eaIPCInputUpdate();
                break;
            case 7:
                echo mailForIPCNumber();
                break;
            case 8:
                echo preAlertToCNF();
                break;
            default:
                break;
        }
    }
}


function reject(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $message = htmlspecialchars($_POST['rejectMessage'],ENT_QUOTES, "ISO-8859-1");
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'actionid' => action_Ship_Doc_Rejected_EATeam,
        'status' => -1,
        'msg' => "'Shipment document rejected by External Approval against PO# ".$pono." and Shipment# ".$shipNo."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    $res["status"] = 1;
    $res["message"] = 'Notification sent SUCCESSFULLY.';
    return json_encode($res);

}

function eaBasicInputUpdate(){

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

    // Date type Field
    if($_POST['docReceiveByEA']!="") {
        $docReceiveByEA = htmlspecialchars($_POST['docReceiveByEA'], ENT_QUOTES, "ISO-8859-1");
        $docReceiveByEA = date('Y-m-d', strtotime($docReceiveByEA));
        $docReceiveByEA = "`docReceiveByEA` = '$docReceiveByEA', ";
    }else{ $docReceiveByEA = ""; };

    if($_POST['actualArrivalAtPort']!="") {
        $actualArrivalAtPort = htmlspecialchars($_POST['actualArrivalAtPort'], ENT_QUOTES, "ISO-8859-1");
        $actualArrivalAtPort = date('Y-m-d', strtotime($actualArrivalAtPort));
        $actualArrivalAtPort = "`actualArrivalAtPort` = '$actualArrivalAtPort', ";
    }else{ $actualArrivalAtPort = ""; };

    if($_POST['whReceiveDate']!="") {
        $whReceiveDate = htmlspecialchars($_POST['whReceiveDate'], ENT_QUOTES, "ISO-8859-1");
        $whReceiveDate = date('Y-m-d', strtotime($whReceiveDate));
        $whReceiveDate = "`whReceiveDate` = '$whReceiveDate', ";
    }else{ $whReceiveDate = ""; };

    if($_POST['releaseFromPort']!="") {
        $releaseFromPort = htmlspecialchars($_POST['releaseFromPort'], ENT_QUOTES, "ISO-8859-1");
        $releaseFromPort = date('Y-m-d', strtotime($releaseFromPort));
        $releaseFromPort = "`releaseFromPort` = '$releaseFromPort', ";
    }else{ $releaseFromPort = ""; };

    if($_POST['eaRemarksOnBasic']!="") {
        $eaRemarksOnBasic = htmlspecialchars($_POST['eaRemarksOnBasic'], ENT_QUOTES, "ISO-8859-1");
        $eaRemarksOnBasic = "`eaRemarksOnBasic` = '$eaRemarksOnBasic', ";
    }else{ $eaRemarksOnBasic = ""; };

    if($_POST['btrcNocNo']!="") {
        $btrcNocNo = htmlspecialchars($_POST['btrcNocNo'], ENT_QUOTES, "ISO-8859-1");
        $btrcNocNo = "`btrcNocNo` = '$btrcNocNo', ";
    }else{ $btrcNocNo = ""; };

    if($_POST['btrcNocDate']!="") {
        $btrcNocDatet = htmlspecialchars($_POST['btrcNocDate'], ENT_QUOTES, "ISO-8859-1");
        $btrcNocDatet = date('Y-m-d', strtotime($btrcNocDatet));
        $btrcNocDatet = "`btrcNocDate` = '$btrcNocDatet', ";
    }else{ $btrcNocDatet = ""; };

    /*if($_POST['billOfEntryDate']!="") {
        $billOfEntryDate = htmlspecialchars($_POST['billOfEntryDate'],ENT_QUOTES, "ISO-8859-1");
        $billOfEntryDate = date('Y-m-d', strtotime($billOfEntryDate));
        $billOfEntryDate = "`billOfEntryDate` = '$billOfEntryDate', ";
    }else{ $billOfEntryDate = ""; };

    if($_POST['tentativeDelivDate']!="") {
        $tentativeDelivDate = htmlspecialchars($_POST['tentativeDelivDate'],ENT_QUOTES, "ISO-8859-1");
        $tentativeDelivDate = date('Y-m-d', strtotime($tentativeDelivDate));
        $tentativeDelivDate= "`tentativeDelivDate` = '$tentativeDelivDate', ";
    }else{ $tentativeDelivDate = ""; };*/

    // Numeric type Field
    if($_POST['cnfNetPayment']!="") {
        $cnfNetPayment = htmlspecialchars($_POST['cnfNetPayment'], ENT_QUOTES, "ISO-8859-1");
        $cnfNetPayment = str_replace(",", "", $cnfNetPayment);
        $cnfNetPayment = "`cnfNetPayment` = $cnfNetPayment, ";
    }else{ $cnfNetPayment = ""; };

    if($_POST['demurrageAmount']!="") {
        $demurrageAmount = htmlspecialchars($_POST['demurrageAmount'], ENT_QUOTES, "ISO-8859-1");
        $demurrageAmount = str_replace(",", "", $demurrageAmount);
        $demurrageAmount = "`demurrageAmount` = $demurrageAmount, ";
    }else{ $demurrageAmount = ""; };

    if($_POST['cNfAgentName']!="") {
        $cNfAgentName = htmlspecialchars($_POST['cNfAgentName'],ENT_QUOTES, "ISO-8859-1");
        $cNfAgentName = "`cNfAgent` = $cNfAgentName, ";
    }else{ $cNfAgentName = ""; };

    /*if($_POST['inputCDAmount']!="") {
        $inputCDAmount = htmlspecialchars($_POST['inputCDAmount'], ENT_QUOTES, "ISO-8859-1");
        $inputCDAmount = str_replace(",", "", $inputCDAmount);
        $inputCDAmount = "`CDAmount` = $inputCDAmount, ";
    }else{ $inputCDAmount = ""; };

    if($_POST['ddlBeneficiary']!="") {
        $ddlBeneficiary = htmlspecialchars($_POST['ddlBeneficiary'],ENT_QUOTES, "ISO-8859-1");
        $ddlBeneficiary = "`beneficiary` = $ddlBeneficiary, ";
    }else{ $ddlBeneficiary = ""; };*/

    $eaRefNo = htmlspecialchars($_POST['eaRefNo'],ENT_QUOTES, "ISO-8859-1");
    //$billOfEntryNo = htmlspecialchars($_POST['billOfEntryNo'],ENT_QUOTES, "ISO-8859-1");

    //$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to save data!';
    //------------------------------------------------------------------------------

    // Update shipment table Basic inputs
    $query = "UPDATE `wc_t_shipment` SET 
		      $docReceiveByEA $actualArrivalAtPort $whReceiveDate $releaseFromPort $eaRemarksOnBasic 
		      $cnfNetPayment $demurrageAmount $cNfAgentName $btrcNocNo $btrcNocDatet
		      `eaRefNo` = '$eaRefNo'
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
    //echo($query);
    $objdal->update(trim($query), "Failed to update shipment info for basic inputs");
    //Add info to activity log table
    addActivityLog(requestUri, 'Update old data', $user_id, 1);

    unset($objdal);

    $res["stepover"] = 0;
    // If Warehouse Received Date mentioned then a EA inputs will close pending status
    if($whReceiveDate!="") {
        if (checkStepOver($pono, action_EA_Inputs_Completed, $shipno) == 0) {
            // Action Log --------------------------------//
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $pono . "'",
                'shipno' => $shipno,
                'actionid' => action_EA_Inputs_Completed,
                'status' => 1,
                'newstatus' => 1,
                'msg' => "'EA Inputs closed by Warehouse Receiving date input.'",
            );
            UpdateAction($action);
            // End Action Log -----------------------------

            $res["stepover"] = 1;
        }
    }

    // If Documents Received by EA then, add the event in action log
    if ($docReceiveByEA !="") {
        if (checkStepOver($pono, action_Docs_Received_by_EA, $shipno) == 0) {
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $pono . "'",
                'shipno' => $shipno,
                'actionid' => action_Docs_Received_by_EA,
                'status' => 0,
                'newstatus' => 1,
                'msg' => "'Documents received by EA.'",
            );
            UpdateAction($action);
        }
    }

    // Add the event in action log
    if ($actualArrivalAtPort !="") {
        if (checkStepOver($pono, action_actual_arrival_at_port, $shipno) == 0) {
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $pono . "'",
                'shipno' => $shipno,
                'actionid' => action_actual_arrival_at_port,
                'newstatus' => 1,
                'msg' => "'Items arrived at port.'",
            );
            UpdateAction($action);
        }
    }

    // If EA gets the release from port date then, add the event in action log
    if ($releaseFromPort !="") {
        if (checkStepOver($pono, action_Release_from_Port, $shipno) == 0) {
            $action = array(
                'refid' => $refId,
                'pono' => "'" . $pono . "'",
                'shipno' => $shipno,
                'actionid' => action_Release_from_Port,
                'newstatus' => 1,
                'msg' => "'Items released from port.'",
            );
            UpdateAction($action);
        }
    }

    $res["status"] = 1;
    $res["message"] = 'Updated SUCCESSFULLY';
    return json_encode($res);

}

function eaCDVATInputUpdate(){

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

    // Related IPC inputs

    if($_POST['ipcServiceValue']!="") {
        $ipcServiceValue = htmlspecialchars($_POST['ipcServiceValue'], ENT_QUOTES, "ISO-8859-1");
        $ipcServiceValue = str_replace(",", "", $ipcServiceValue);
        $ipcServiceValue = "`ipcServiceValue` = '$ipcServiceValue', ";
    }else{ $ipcServiceValue = ""; };

    if($_POST['ipcReceivedQty']!="") {
        $ipcReceivedQty = htmlspecialchars($_POST['ipcReceivedQty'], ENT_QUOTES, "ISO-8859-1");
        $ipcReceivedQty = "`ipcReceivedQty` = '$ipcReceivedQty', ";
    }else{ $ipcReceivedQty = ""; };


    $objdal = new dal();

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to save the data';
    //------------------------------------------------------------------------------

    // Update shipment table CD/VAT inputs
    $query = "UPDATE `wc_t_shipment` SET 
		      $billOfEntryDate $billOfEntryNo $ddlBeneficiary $itOnCnFComm $vatOnCnFComm $docProcessFee 
		      $finePenalties $contScanningFee $customDuty $regulatoryDuty $supplementaryDuty $valueAddedTax 
		      $advanceIncomeTax $advanceTradeVat $advanceTax $ipcServiceValue $ipcReceivedQty $totalCDVATAmount
		      `insertby` = $user_id
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
    //echo($query);
    $objdal->update(trim($query), "Failed to update shipment info for CDV-AT inputs");
    //Add info to activity log table
    addActivityLog(requestUri, 'Update old data', $user_id, 1);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Updated SUCCESSFULLY';
    return json_encode($res);

}

function requestSendToFinance(){
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'], ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'], ENT_QUOTES, "ISO-8859-1");
    $eaRemarksOnCD = htmlspecialchars($_POST['eaRemarksOnCD'], ENT_QUOTES, "ISO-8859-1");
    $eaRefNo = htmlspecialchars($_POST['eaRefNo'], ENT_QUOTES, "ISO-8859-1");

    $attachBillOfEntry = htmlspecialchars($_POST['attachBillOfEntry'], ENT_QUOTES, "ISO-8859-1");
    $attachOtherCustomDoc = htmlspecialchars($_POST['attachOtherCustomDoc'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();

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

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'shipno' => $shipno,
        'actionid' => action_CD_BE_Copy_updated,
        'usermsg' => "'" . $eaRemarksOnCD . "'",
        'msg' => "'Pay-Order Requisition for PO# " . $pono . " of Shipment# " . $shipno . " GP Ref# " . $eaRefNo . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Request sent SUCCESSFULLY.';
    return json_encode($res);
}

function eaIPCInputUpdate(){

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

    // Date type Field
    if($_POST['ipcPONO']!="") {
        $ipcPONO = htmlspecialchars($_POST['ipcPONO'], ENT_QUOTES, "ISO-8859-1");
        $ipcPONO = "`ipcPONO` = '$ipcPONO', ";
    }else{ $ipcPONO = ""; };

    if($_POST['ipcPOAmount']!="") {
        $ipcPOAmount = htmlspecialchars($_POST['ipcPOAmount'], ENT_QUOTES, "ISO-8859-1");
        $ipcPOAmount = str_replace(",", "", $ipcPOAmount);
        $ipcPOAmount = "`ipcPOAmount` = '$ipcPOAmount', ";
    }else{ $ipcPOAmount = ""; };

    if($_POST['ipcItemCode']!="") {
        $ipcItemCode = htmlspecialchars($_POST['ipcItemCode'], ENT_QUOTES, "ISO-8859-1");
        $ipcItemCode = "`ipcItemCode` = '$ipcItemCode', ";
    }else{ $ipcItemCode = ""; };

    if($_POST['ipcServiceValue']!="") {
        $ipcServiceValue = htmlspecialchars($_POST['ipcServiceValue'], ENT_QUOTES, "ISO-8859-1");
        $ipcServiceValue = str_replace(",", "", $ipcServiceValue);
        $ipcServiceValue = "`ipcServiceValue` = '$ipcServiceValue', ";
    }else{ $ipcServiceValue = ""; };

    if($_POST['ipcReceivedQty']!="") {
        $ipcReceivedQty = htmlspecialchars($_POST['ipcReceivedQty'], ENT_QUOTES, "ISO-8859-1");
        $ipcReceivedQty = "`ipcReceivedQty` = '$ipcReceivedQty', ";
    }else{ $ipcReceivedQty = ""; };

    if($_POST['ipcReceivedQtyStatus']!="") {
        $ipcReceivedQtyStatus = htmlspecialchars($_POST['ipcReceivedQtyStatus'], ENT_QUOTES, "ISO-8859-1");
        $ipcReceivedQtyStatus = "`ipcReceivedQtyStatus` = '$ipcReceivedQtyStatus', ";
    }else{ $ipcReceivedQtyStatus = ""; };

    if($_POST['ipcPONeedByDate']!="") {
        $ipcPONeedByDate = htmlspecialchars($_POST['ipcPONeedByDate'], ENT_QUOTES, "ISO-8859-1");
        $ipcPONeedByDate = date('Y-m-d', strtotime($ipcPONeedByDate));
        $ipcPONeedByDate = "`ipcPONeedByDate` = '$ipcPONeedByDate', ";
    }else{ $ipcPONeedByDate = ""; };

    if($_POST['ipcReceivedDate']!="") {
        $ipcReceivedDate = htmlspecialchars($_POST['ipcReceivedDate'], ENT_QUOTES, "ISO-8859-1");
        $ipcReceivedDate = date('Y-m-d', strtotime($ipcReceivedDate));
        $ipcReceivedDate = "`ipcReceivedDate` = '$ipcReceivedDate', ";
    }else{ $ipcReceivedDate = ""; };

    if($_POST['tentativeDelivDate']!="") {
        $tentativeDelivDate = htmlspecialchars($_POST['tentativeDelivDate'],ENT_QUOTES, "ISO-8859-1");
        $tentativeDelivDate = date('Y-m-d', strtotime($tentativeDelivDate));
        $tentativeDelivDate= "`tentativeDelivDate` = '$tentativeDelivDate', ";
    }else{ $tentativeDelivDate = ""; };

    $eaRefNo = htmlspecialchars($_POST['eaRefNo'],ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to save data!';
    //------------------------------------------------------------------------------

    // Update shipment table IPC inputs
    $query = "UPDATE `wc_t_shipment` SET 
		      $ipcPONO $ipcPOAmount $ipcItemCode $ipcServiceValue $ipcReceivedQty  
		      $ipcReceivedQtyStatus $ipcPONeedByDate $ipcReceivedDate $tentativeDelivDate
		      `eaRefNo` = '$eaRefNo'
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
    //echo($query);
    $objdal->update(trim($query), "Failed to update shipment info for IPC inputs");
    //Add info to activity log table
    addActivityLog(requestUri, 'Update old data', $user_id, 1);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Updated SUCCESSFULLY';
    return json_encode($res);

}

function mailForIPCNumber(){

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

    $eaRefNo = htmlspecialchars($_POST['eaRefNo'],ENT_QUOTES, "ISO-8859-1");

    $ipcPONO = htmlspecialchars($_POST['ipcPONO'],ENT_QUOTES, "ISO-8859-1");
    $ipcItemCode = htmlspecialchars($_POST['ipcItemCode'],ENT_QUOTES, "ISO-8859-1");
    $ipcServiceValue = htmlspecialchars($_POST['ipcServiceValue'],ENT_QUOTES, "ISO-8859-1");
    $ipcReceivedQty = htmlspecialchars($_POST['ipcReceivedQty'],ENT_QUOTES, "ISO-8859-1");
    $ipcReceivedQtyStatus = htmlspecialchars($_POST['ipcReceivedQtyStatus'],ENT_QUOTES, "ISO-8859-1");
    $ipcPONeedByDate = htmlspecialchars($_POST['ipcPONeedByDate'],ENT_QUOTES, "ISO-8859-1");
    $ipcReceivedDate = htmlspecialchars($_POST['ipcReceivedDate'],ENT_QUOTES, "ISO-8859-1");

    $mawbNo = htmlspecialchars($_POST['mawbNo'],ENT_QUOTES, "ISO-8859-1");
    $hawbNo = htmlspecialchars($_POST['hawbNo'],ENT_QUOTES, "ISO-8859-1");
    $blNo = htmlspecialchars($_POST['blNo'],ENT_QUOTES, "ISO-8859-1");
    $noOfBoxes = htmlspecialchars($_POST['noOfBoxes'],ENT_QUOTES, "ISO-8859-1");
    $ChargeableWeight = htmlspecialchars($_POST['ChargeableWeight'],ENT_QUOTES, "ISO-8859-1");
    $cNfAgentName = htmlspecialchars($_POST['cNfAgentName'],ENT_QUOTES, "ISO-8859-1");
    $cNfAgentFullName = htmlspecialchars($_POST['cNfAgentFullName'],ENT_QUOTES, "ISO-8859-1");
    $tentativeDelivDate = htmlspecialchars($_POST['tentativeDelivDate'],ENT_QUOTES, "ISO-8859-1");
    $productDesc = htmlspecialchars($_POST['productDesc'],ENT_QUOTES, "ISO-8859-1");

    $message = "<p>Find the bellow information to provide IPC number:</p>";

    $message .= "<table border=\"1\">";
    $message .= "<tr><td>CDVAT PO No.:</td><td>$ipcPONO</td></tr>";
    $message .= "<tr><td>Item Code:</td><td>$ipcItemCode</td></tr>";
    $message .= "<tr><td>Service Value:</td><td>$ipcServiceValue</td></tr>";
    $message .= "<tr><td>Received Qty.:</td><td>$ipcReceivedQty</td></tr>";
    $message .= "<tr><td>Received Qty. Status:</td><td>$ipcReceivedQtyStatus</td></tr>";
    $message .= "<tr><td>PO Need by Date:</td><td>$ipcPONeedByDate</td></tr>";
    $message .= "<tr><td>Received Date:</td><td>$ipcReceivedDate</td></tr>";
    $message .= "</table>";

    $message .= "<p>Also find the delivery information as bellow:</p>";

    $message .= "<table border=\"1\">";
    $message .= "<tr><td>MAWB#:</td><td>$mawbNo</td></tr>";
    $message .= "<tr><td>HAWB#:</td><td>$hawbNo</td></tr>";
    $message .= "<tr><td>BL#:</td><td>$blNo</td></tr>";
    $message .= "<tr><td>No. of Boxes:</td><td>$noOfBoxes</td></tr>";
    $message .= "<tr><td>Weight (KG):</td><td>$ChargeableWeight</td></tr>";
    $message .= "<tr><td>C&F Agent:</td><td>$cNfAgentFullName</td></tr>";
    $message .= "<tr><td>Equipment Product:</td><td>$productDesc</td></tr>";
    $message .= "<tr><td>Tentative Delivery Date:</td><td>$tentativeDelivDate</td></tr>";
    $message .= "</table>";

    $message .= "<p><strong>Please provide IPC Number ASAP accordingly.</strong></p>";

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'shipno' => $shipno,
        'actionid' => action_Tentative_Delivery_Date_Updated,
        'msg' => "'IPC No. required for PO# " . $pono . " of Shipment# " . $shipno . " GP Ref# " . $eaRefNo . "'",
        'usermsg' => "'" . $message . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Notification sent SUCCESSFULLY';
    return json_encode($res);

}

function preAlertToCNF(){

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
    $cNfAgentName = htmlspecialchars($_POST['cNfAgentName'],ENT_QUOTES, "ISO-8859-1");

    $query = "UPDATE `wc_t_shipment` set `cnfAgent` = $cNfAgentName WHERE `pono` = '$pono' and `shipNo` = $shipno;";
    $objdal->update($query);

    $query = "INSERT INTO `cnf_inputs` (`poid`,`shipno`) VALUES ('$pono','$shipno');";
    $objdal->insert($query);
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'" . $pono . "'",
        'shipno' => $shipno,
        'actionid' => action_Request_for_CNF_Input,
        'pendingtoco' => $cNfAgentName,
        'msg' => "'Pre alert sent for PO# " . $pono . "'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'Pre alert sent to CNF successfully';
    return json_encode($res);

}
?>

