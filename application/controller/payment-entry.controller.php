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
        /*Get Draft Payment Entry*/
        case 1:
            echo getDraftPayment($_GET["doc"],$_GET["lc"],$_GET["ci"]);
            break;
    }
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["LcNo"]) || isset($_POST["LcNo"])){
        echo SavePaymentEntry();
	}
}

// Insert or update
function SavePaymentEntry()
{
    global $user_id;
    global $loginRole;

    $pono = htmlspecialchars($_POST['pono'], ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'], ENT_QUOTES, "ISO-8859-1");

    $LcNo = htmlspecialchars($_POST['LcNo'], ENT_QUOTES, "ISO-8859-1");
    $ciNo = htmlspecialchars($_POST['ciNo'], ENT_QUOTES, "ISO-8859-1");
    //$docArrivalDate = htmlspecialchars($_POST['docArrivalDate'],ENT_QUOTES, "ISO-8859-1");
    //$docArrivalDate = date('Y-m-d', strtotime($docArrivalDate));
    $bankNotifyDate = htmlspecialchars($_POST['bankNotifyDate'], ENT_QUOTES, "ISO-8859-1");
    $bankNotifyDate = date('Y-m-d', strtotime($bankNotifyDate));
    $docName = htmlspecialchars($_POST['docName'], ENT_QUOTES, "ISO-8859-1");
    $paymentPercent = htmlspecialchars($_POST['paymentPercent'], ENT_QUOTES, "ISO-8859-1");
    $amount = htmlspecialchars($_POST['amount'], ENT_QUOTES, "ISO-8859-1");
    $amount = str_replace(",", "", $amount);
    $docReceiveDate = htmlspecialchars($_POST['docReceiveDate'], ENT_QUOTES, "ISO-8859-1");
    $docReceiveDate = date('Y-m-d', strtotime($docReceiveDate));
    $payDueDate = htmlspecialchars($_POST['payDueDate'], ENT_QUOTES, "ISO-8859-1");
    $payDueDate = date('Y-m-d', strtotime($payDueDate));
    $payDate = htmlspecialchars($_POST['payDate'], ENT_QUOTES, "ISO-8859-1");
    $payDate = date('Y-m-d', strtotime($payDate));
    $payMatureDate = htmlspecialchars($_POST['payMatureDate'], ENT_QUOTES, "ISO-8859-1");
    $payMatureDate = date('Y-m-d', strtotime($payMatureDate));
    $exchangeRate = htmlspecialchars($_POST['exchangeRate'], ENT_QUOTES, "ISO-8859-1");
    $fundCollectFrom = htmlspecialchars($_POST['fundCollectFrom'], ENT_QUOTES, "ISO-8859-1");
    $bcSellingRate = htmlspecialchars($_POST['bcSellingRate'], ENT_QUOTES, "ISO-8859-1");
    $bcSellingRate = str_replace(",", "", $bcSellingRate);
    $stlmntCharge = htmlspecialchars($_POST['stlmntCharge'], ENT_QUOTES, "ISO-8859-1");
    $stlmntCharge = str_replace(",", "", $stlmntCharge);
    $vatOnStlmntCharge = htmlspecialchars($_POST['vatOnStlmntCharge'], ENT_QUOTES, "ISO-8859-1");
    $vatOnStlmntCharge = str_replace(",", "", $vatOnStlmntCharge);
    $vatRebate = htmlspecialchars($_POST['vatRebate'], ENT_QUOTES, "ISO-8859-1");
    $vatRebate = str_replace(",", "", $vatRebate);
    $bankCharge = htmlspecialchars($_POST['bankCharge'], ENT_QUOTES, "ISO-8859-1");
    $bankCharge = str_replace(",", "", $bankCharge);
    $totalCharge = htmlspecialchars($_POST['totalCharge'], ENT_QUOTES, "ISO-8859-1");
    $totalCharge = str_replace(",", "", $totalCharge);
    $BBRefNo = htmlspecialchars($_POST['BBRefNo'], ENT_QUOTES, "ISO-8859-1");

    if ($_POST['BBRefDate'] != "") {
        $BBRefDate = htmlspecialchars($_POST['BBRefDate'], ENT_QUOTES, "ISO-8859-1");
        $BBRefDate = date('Y-m-d', strtotime($BBRefDate));
    }

    if (!isset($_POST['maturityPayment'])) {
        $maturityPayment = 0;
    } else {
        $maturityPayment = 1;
    };

    $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, "ISO-8859-1");

    $attachLCPaymentAdvice = htmlspecialchars($_POST['attachLCPaymentAdvice'], ENT_QUOTES, "ISO-8859-1");
    $attachLCPaymentAdviceOld = htmlspecialchars($_POST['attachLCPaymentAdviceOld'], ENT_QUOTES, "ISO-8859-1");

    $attachLCPayAcceptCertificate = htmlspecialchars($_POST['attachLCPayAcceptCertificate'], ENT_QUOTES, "ISO-8859-1");
    $attachLCPayAcceptCertificateOld = htmlspecialchars($_POST['attachLCPayAcceptCertificateOld'], ENT_QUOTES, "ISO-8859-1");

    $attachPaymentInstructionLetter = htmlspecialchars($_POST['attachPaymentInstructionLetter'], ENT_QUOTES, "ISO-8859-1");
    $attachPaymentInstructionLetterOld = htmlspecialchars($_POST['attachPaymentInstructionLetterOld'], ENT_QUOTES, "ISO-8859-1");

    $attachBankReceivedLetter = htmlspecialchars($_POST['attachBankReceivedLetter'], ENT_QUOTES, "ISO-8859-1");
    $attachBankReceivedLetterOld = htmlspecialchars($_POST['attachBankReceivedLetterOld'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $LcNo = stripslashes($LcNo);
    $ciNo = stripslashes($ciNo);
    //$docArrivalDate = stripslashes($docArrivalDate);
    $bankNotifyDate = stripslashes($bankNotifyDate);
    $docName = stripslashes($docName);
    $amount = stripslashes($amount);
    $paymentPercent = stripslashes($paymentPercent);
    $docReceiveDate = stripslashes($docReceiveDate);
    $payDueDate = stripslashes($payDueDate);
    $payDate = stripslashes($payDate);
    $payMatureDate = stripslashes($payMatureDate);
    $exchangeRate = stripslashes($exchangeRate);
    $fundCollectFrom = stripslashes($fundCollectFrom);
    $bcSellingRate = stripslashes($bcSellingRate);
    $stlmntCharge = stripslashes($stlmntCharge);
    $vatOnStlmntCharge = stripslashes($vatOnStlmntCharge);
    $vatRebate = stripslashes($vatRebate);
    $bankCharge = stripslashes($bankCharge);
    $totalCharge = stripslashes($totalCharge);
    $BBRefNo = stripslashes($BBRefNo);
//	$BBRefDate = stripslashes($BBRefDate);
    $remarks = stripslashes($remarks);

    $objdal = new dal();

    $LcNo = $objdal->real_escape_string($LcNo);
    $ciNo = $objdal->real_escape_string($ciNo);
    //$docArrivalDate = $objdal->real_escape_string($docArrivalDate);
    $bankNotifyDate = $objdal->real_escape_string($bankNotifyDate);
    $docName = $objdal->real_escape_string($docName);
    $amount = $objdal->real_escape_string($amount);
    $paymentPercent = $objdal->real_escape_string($paymentPercent);
    $docReceiveDate = $objdal->real_escape_string($docReceiveDate);
    $payDueDate = $objdal->real_escape_string($payDueDate);
    $payDate = $objdal->real_escape_string($payDate);
    $payMatureDate = $objdal->real_escape_string($payMatureDate);
    $exchangeRate = $objdal->real_escape_string($exchangeRate);
    $fundCollectFrom = $objdal->real_escape_string($fundCollectFrom);
    $bcSellingRate = $objdal->real_escape_string($bcSellingRate);
    $stlmntCharge = $objdal->real_escape_string($stlmntCharge);
    $vatOnStlmntCharge = $objdal->real_escape_string($vatOnStlmntCharge);
    $vatRebate = $objdal->real_escape_string($vatRebate);
    $bankCharge = $objdal->real_escape_string($bankCharge);
    $totalCharge = $objdal->real_escape_string($totalCharge);
    $BBRefNo = $objdal->real_escape_string($BBRefNo);
//	$BBRefDate = $objdal->real_escape_string($BBRefDate);
    $remarks = $objdal->real_escape_string($remarks);
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'Failed to save data!';
    //------------------------------------------------------------------------------
    $table = '';
    if ($_POST['action'] == 1) {

        $table = 'wc_t_payment';
        $query = "DELETE FROM `wc_t_payment_draft` WHERE `LcNo` = '$LcNo' AND `ciNo` = '$ciNo' AND `docName` = $docName;";
        //echo $query;
        $objdal->delete($query);

        //Create new status after payment is 100% completed
        $totalPercent = $_POST['ppPercentage'] + $_POST['paymentPercent'];

        if ($totalPercent === 100 && $docName != payment_Sight){
            $ip = $_SERVER['REMOTE_ADDR'];
            if (checkStepOver($pono, action_Payment_Complete, $shipno) == 0) {
                $q = "INSERT INTO `wc_t_action_log`(`PO`, `ActionID`, `Status`, `Msg`, `shipNo`, `ActionBy`, `ActionByRole`, `ActionFrom`) 
                                        VALUES ('$pono'," . action_Payment_Complete . ",1,'Payment 100% completed', $shipno, $user_id, $loginRole, '$ip')";
                $objdal->insert($q, "Failed to update payment completion status");
            }
        }

        if ($totalPercent != 100 && $docName != payment_Sight){
            $queryPt = "SELECT `id`, `partname`, `cacFacText` FROM `wc_t_payment_terms` WHERE `pono` = '$pono' AND `partname` = $docName;";
            $termInfo = $objdal->getRow($queryPt);

            $nextCert = "SELECT `id`, `partname`, `cacFacText` FROM `wc_t_payment_terms` WHERE `pono` = '$pono' AND `id` > ".$termInfo['id'].";";
            $nextCertInfo = $objdal->getRow($nextCert);

            if ($nextCertInfo) {
                $actionId = getPaymentAction($nextCertInfo['partname']);
            }else{
                $actionId = getPaymentAction($termInfo['partname']);
                if (checkStepOver($pono, $actionId, $shipno) == 0) {
                    $action = array(
                        'pono' => "'" . $pono . "'",
                        'shipno' => $shipno,
                        'actionid' => $actionId,
                        'newstatus' => 1,
                        'msg' => "'Payment made for ".$termInfo['cacFacText']."'",
                    );
                    UpdateAction($action);
                }
                $actionId = action_Payment_Complete;
            }
            if (checkStepOver($pono, $actionId, $shipno) == 0) {
                $action = array(
                    'pono' => "'" . $pono . "'",
                    'shipno' => $shipno,
                    'actionid' => $actionId,
                    'newstatus' => 1,
                    'msg' => "'Payment made for ".$termInfo['cacFacText']."'",
                );
                UpdateAction($action);
            }
        }

    } elseif ($_POST['action'] == 2) {
        $table = 'wc_t_payment_draft';

    }

    $query = "SELECT COUNT(*) `exist` FROM $table WHERE `LcNo` = '$LcNo' AND `ciNo` = '$ciNo' AND `docName` = $docName;";
    $objdal->read($query);
    $res = $objdal->data[0];
    extract($res);

    if($exist > 0) {
        $taskMessage = 'Update old data';
        $query = "UPDATE $table SET 
            `bankNotifyDate` = '$bankNotifyDate', 
            `docName` = $docName, 
            `paymentPercent` = $paymentPercent, 
            `amount` = $amount, 
            `docReceiveDate` = '$docReceiveDate',  
            `payDueDate` = '$payDueDate', 
            `payDate` = '$payDate', 
            `payMatureDate` = '$payMatureDate', 
            `exchangeRate` = $exchangeRate, 
            `fundCollectFrom` = $fundCollectFrom, 
            `bcSellingRate` = $bcSellingRate, 
            `stlmntCharge` = $stlmntCharge, 
            `vatOnStlmntCharge` = $vatOnStlmntCharge, 
            `vatRebate` = $vatRebate, 
            `bankCharge` = $bankCharge, 
            `totalCharge` = $totalCharge, 
            `BBRefNo` = '$BBRefNo',";
            if(isset($BBRefDate)){
                $query .= "`BBRefDate` = '$BBRefDate', ";
            } else {
                $query .= "`BBRefDate` = null, ";
            }
            $query .="`maturityPayment` = b'$maturityPayment', 
            `remarks` = '$remarks'
            WHERE `LcNo` = '$LcNo' AND `ciNo` = '$ciNo' AND `docName` = $docName;";
//        echo $query;
        $objdal->update($query);

    } else {
        $taskMessage = 'Insert new data';
        $query = "INSERT INTO $table SET 
            `LcNo` = '$LcNo', 
            `ciNo` = '$ciNo', 
            `bankNotifyDate` = '$bankNotifyDate', 
            `docName` = $docName, 
            `paymentPercent` = $paymentPercent, 
            `amount` = $amount, 
            `docReceiveDate` = '$docReceiveDate',  
            `payDueDate` = '$payDueDate', 
            `payDate` = '$payDate', 
            `payMatureDate` = '$payMatureDate', 
            `exchangeRate` = $exchangeRate, 
            `fundCollectFrom` = $fundCollectFrom, 
            `bcSellingRate` = $bcSellingRate, 
            `stlmntCharge` = $stlmntCharge, 
            `vatOnStlmntCharge` = $vatOnStlmntCharge, 
            `vatRebate` = $vatRebate, 
            `bankCharge` = $bankCharge, 
            `totalCharge` = $totalCharge, 
            `BBRefNo` = '$BBRefNo', ";
            if(isset($BBRefDate)){
                $query .= "`BBRefDate` = '$BBRefDate', ";
            } else {
                $query .= "`BBRefDate` = null, ";
            }
            $query .="`maturityPayment` = b'$maturityPayment', 
            `remarks` = '$remarks', 
            `createdby` = $user_id, 
            `createdfrom` = '$ip';";
        $objdal->insert($query);

        $query = "UPDATE `wc_t_cacfac_request` SET `paymentStatus` = 1 WHERE `poNo` = '$pono' AND `ciNo` = '$ciNo' AND `partName`=$docName;";
        $objdal->update($query);
    }

    // attachment will only save when final submit
    if ($_POST['action'] == 1) {

        if ($attachLCPaymentAdvice != '') {
            if ($attachLCPaymentAdviceOld == '') {
                $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`, `grouponly`) VALUES 
                ('$pono', 'LC Payment Advice', '$attachLCPaymentAdvice', $user_id, '$ip', $loginRole, '$LcNo', $shipno, $loginRole);";
                $objdal->insert($query);
            } else {
                $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachLCPaymentAdvice',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `shipno` = $shipno AND `title`='LC Payment Advice' AND `filename` = '$attachLCPaymentAdviceOld'";
                $objdal->update($query);
            }
            //Transfer file from 'temp' directory to respective 'docs' directory
            //fileTransferTempToDocs($pono);
        }

        if ($attachLCPayAcceptCertificate != '') {
            if ($attachLCPayAcceptCertificateOld == '') {
                $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`, `grouponly`) VALUES 
                ('$pono', 'Acceptance Certificate', '$attachLCPayAcceptCertificate', $user_id, '$ip', $loginRole, '$LcNo', $shipno, $loginRole);";
                $objdal->insert($query);
            } else {
                $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachLCPayAcceptCertificate',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `shipno` = $shipno AND `title`='Acceptance Certificate' AND `filename` = '$attachLCPayAcceptCertificateOld'";
                $objdal->update($query);
            }
            //Transfer file from 'temp' directory to respective 'docs' directory
            //fileTransferTempToDocs($pono);
        }

        if ($attachPaymentInstructionLetter != '') {
            if ($attachPaymentInstructionLetterOld == '') {
                $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`, `grouponly`) VALUES 
                ('$pono', 'Payment Instruction Letter', '$attachPaymentInstructionLetter', $user_id, '$ip', $loginRole, '$LcNo', $shipno, $loginRole);";
                $objdal->insert($query);
            } else {
                $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachPaymentInstructionLetter',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `shipno` = $shipno AND `title`='Payment Instruction Letter' AND `filename` = '$attachPaymentInstructionLetterOld'";
                $objdal->update($query);
            }
            //Transfer file from 'temp' directory to respective 'docs' directory
            //fileTransferTempToDocs($pono);
        }

        if ($attachBankReceivedLetter != '') {
            if ($attachBankReceivedLetterOld == '') {
                $query = "INSERT INTO `wc_t_attachments`
                (`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`, `shipno`, `grouponly`) VALUES 
                ('$pono', 'Bank Received Letter', '$attachBankReceivedLetter', $user_id, '$ip', $loginRole, '$LcNo', $shipno, $loginRole);";
                $objdal->insert($query);
            } else {
                $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachBankReceivedLetter',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `shipno` = $shipno AND `title`='Bank Received Letter' AND `filename` = '$attachBankReceivedLetterOld'";
                $objdal->update($query);
            }
        }
        //Transfer file from 'temp' directory to respective 'docs' directory
        fileTransferTempToDocs($pono);
    }

    //Add info to activity log table
    addActivityLog(requestUri, $taskMessage, $user_id, 1);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}

//Get Draft Payment Entry*/
function getDraftPayment($docName, $LcNo, $ciNo) {
    $objdal = new dal();

    $query = "SELECT COUNT(*) `exist` FROM `wc_t_payment` 
              WHERE `docName` = $docName AND `LcNo`='$LcNo' AND `ciNo`='$ciNo';";
    //echo $query;
    $objdal->read($query);
    $res = $objdal->data[0];
    extract($res);

    $table = '';
    if($exist>0){
        $table = 'wc_t_payment';
    }
    else{
        $table = 'wc_t_payment_draft';
    }
    unset($objdal);
    $objdal = new dal();

    $query = "SELECT *
            FROM `$table` 
            WHERE `docName` = $docName AND `LcNo`='$LcNo' AND `ciNo`='$ciNo';";
    //echo $query;
    $objdal->read($query);

    $res = null;

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    return json_encode($res);
}

/*!
* Get action ID for chosen certificate
*/
function getPaymentAction($certificate){
    switch ($certificate) {
        case payment_CAC:
            $actionId = action_CAC_Payment;
            break;
        case payment_PAC:
            $actionId = action_CAC_Payment;
            break;
        case payment_GLC:
            $actionId = action_GLC_Payment;
            break;
        case payment_SIC:
            $actionId = action_SIC_Payment;
            break;
        case payment_SIAC:
            $actionId = action_SIAC_Payment;
            break;
        case payment_DFS:
            $actionId = action_DFS_Payment;
            break;
        default:
            $actionId = action_FAC_Payment;
            break;
    }
    return $actionId;
}

?>

