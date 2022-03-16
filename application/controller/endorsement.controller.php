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
		case 1:	
			echo GetEndorsementInfo($_GET["po"], $_GET["shipno"]);
			break;
		case 2:	
			echo DocDelivered($_GET["po"], $_GET["endno"], $_GET["refId"], $_GET["shipno"]);
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["pono"]) || isset($_POST["pono"])){
		//echo json_encode($_POST);
        switch ($_POST["useraction"])
        {
            case 1:
                echo SendEndorsementToGP();
                break;
            case 2:
                echo goForOrgDocCollectionProcess();
                break;
            case 3:
                echo sendEndorsementRequest();
                break;
            case 4:
                echo SaveEndorsement();
                break;


        }
	}
}

function goForOrgDocCollectionProcess(){

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

    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Requested_to_Collect_Original_Doc,
        'status' => -1,
        'msg' => "'Document collection process changed against PO# ".$pono." and Shipment# ".$shipno."'",
        'usermsg' => "'This is to notify that we have been changed the document collection process due to logical requirements.'",
    );
    $lastAction = UpdateAction($action);
    // End Action Log -----------------------------
    $res["status"] = 1;
    $res["message"] = 'Document collection process changed!';
    $res["lastaction"] = encryptId($lastAction);

    return json_encode($res);
}

// Insert or update
function SendEndorsementToGP()
{
	global $user_id;
	global $loginRole;
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    //$policyNum = htmlspecialchars($_POST['policyNum'],ENT_QUOTES, "ISO-8859-1");
    //$policyValue = htmlspecialchars($_POST['policyValue'],ENT_QUOTES, "ISO-8859-1");
    //$policyValue = str_replace(",", "", $policyValue);
    $ciValue = htmlspecialchars($_POST['ciValue'],ENT_QUOTES, "ISO-8859-1");
    $ciValue = str_replace(",", "", $ciValue);
    $gerpVNo = htmlspecialchars($_POST['gerpVNo'],ENT_QUOTES, "ISO-8859-1");
    //$gerpInvNo = htmlspecialchars($_POST['gerpInvNo'],ENT_QUOTES, "ISO-8859-1");
    $ciNo = htmlspecialchars($_POST['ciNo'],ENT_QUOTES, "ISO-8859-1");
    $ciDate = htmlspecialchars($_POST['ciDate'],ENT_QUOTES, "ISO-8859-1");
    $ciDate = date('Y-m-d', strtotime($ciDate));
    if($_POST['endDate']!=""){
        $endDate = htmlspecialchars($_POST['endDate'],ENT_QUOTES, "ISO-8859-1");
        $endDate = date('Y-m-d', strtotime($endDate));
    } else{
        $endDate = date('Y-m-d');
    }
    $endCharge = htmlspecialchars($_POST['endCharge'],ENT_QUOTES, "ISO-8859-1");
    $vatOnCharge = htmlspecialchars($_POST['vatOnCharge'],ENT_QUOTES, "ISO-8859-1");
    $chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");
    //$docDelivered = htmlspecialchars($_POST['docDelivered'],ENT_QUOTES, "ISO-8859-1");
    if(!isset($_POST['docDelivered'])){ 
        $docDelivered = 0;
        $docDeliveredOn = 'NULL';
    } else{ 
        $docDelivered = 1;
        $docDeliveredOn = 'CURRENT_DATE'; 
    };
    $attachEndorsementCopy = htmlspecialchars($_POST['attachEndorsementCopy'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementAdvice = htmlspecialchars($_POST['attachEndorsementAdvice'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementOtherDoc = htmlspecialchars($_POST['attachEndorsementOtherDoc'],ENT_QUOTES, "ISO-8859-1");
    
    //$createdby = htmlspecialchars($_POST['createdby'],ENT_QUOTES, "ISO-8859-1");
    
	$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    
	//---To protect MySQL injection for Security purpose----------------------------
    $pono = stripslashes($pono);
    $lcno = stripslashes($lcno);
    $shipno = stripslashes($shipno);
    //$policyNum = stripslashes($policyNum);
    //$policyValue = stripslashes($policyValue);
    $ciValue = stripslashes($ciValue);
    $gerpVNo = stripslashes($gerpVNo);
    //$gerpInvNo = stripslashes($gerpInvNo);
    $ciNo = stripslashes($ciNo);
    $ciDate = stripslashes($ciDate);
    $endDate = stripslashes($endDate);
    $endCharge = stripslashes($endCharge);
    $vatOnCharge = stripslashes($vatOnCharge);
    $chargeType = stripslashes($chargeType);
    //$docDelivered = stripslashes($docDelivered);
    //$docDeliveredOn = stripslashes($docDeliveredOn);
    //$createdby = stripslashes($createdby);
	
	$objdal = new dal();
	
	$pono = $objdal->real_escape_string($pono);
    $lcno = $objdal->real_escape_string($lcno);
    $shipno = $objdal->real_escape_string($shipno);
    //$policyNum = $objdal->real_escape_string($policyNum);
    //$policyValue = $objdal->real_escape_string($policyValue);
    $ciValue = $objdal->real_escape_string($ciValue);
    $gerpVNo = $objdal->real_escape_string($gerpVNo);
    //$gerpInvNo = $objdal->real_escape_string($gerpInvNo);
    $ciNo = $objdal->real_escape_string($ciNo);
    $ciDate = $objdal->real_escape_string($ciDate);
    $endDate = $objdal->real_escape_string($endDate);
    $endCharge = $objdal->real_escape_string($endCharge);
    $vatOnCharge = $objdal->real_escape_string($vatOnCharge);
    $chargeType = $objdal->real_escape_string($chargeType);
    //$docDelivered = $objdal->real_escape_string($docDelivered);
    //$docDeliveredOn = $objdal->real_escape_string($docDeliveredOn);
    //$createdby = $objdal->real_escape_string($createdby);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

    $sql = "INSERT INTO `wc_t_endorsement` SET
        `endNo` = $shipno,
        `pono` = '$pono',
        `lcno` = '$lcno',
        `ciValue` = $ciValue,
        `gerpVNo` = '$gerpVNo',
        `gerpInvNo` = NULL,
        `ciNo` = '$ciNo',
        `ciDate` = '$ciDate',
        `endDate` = '$endDate',
        `endCharge` = $endCharge,
        `vatOnCharge` = $vatOnCharge,
        `chargeType` = $chargeType,
        `createdby` = $user_id,
        `createdfrom` = '$ip';";
//    echo($query);

    $objdal->insert($sql);

    $lastEndId =  $objdal->LastInsertId();
    
    //insert attachment
    if ($attachEndorsementCopy!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Copy', '$attachEndorsementCopy', $user_id, '$ip', $loginRole, '$lcno')";
        $objdal->insert($query);
    }
    if($attachEndorsementAdvice!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Advice', '$attachEndorsementAdvice', $user_id, '$ip', $loginRole, '$lcno')";
        $objdal->insert($query);
    }
    if($attachEndorsementOtherDoc!=""){
        $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Other Docs', '$attachEndorsementOtherDoc', $user_id, '$ip', $loginRole, '$lcno')";
        $objdal->insert($query);
    }

    //echo($query);

    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Endorsement_Doc_Send_By_Bank,
        'status' => 1,
        'msg' => "'Endorsement doc share to GP against PO# ".$pono." and Shipment# ".$shipno."'",
    );
    UpdateAction($action);

	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}
function SaveEndorsement()
{
    global $user_id;
    global $loginRole;
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $hiddenEndId = htmlspecialchars($_POST['hiddenEndId'],ENT_QUOTES, "ISO-8859-1");
    //$policyNum = htmlspecialchars($_POST['policyNum'],ENT_QUOTES, "ISO-8859-1");
    //$policyValue = htmlspecialchars($_POST['policyValue'],ENT_QUOTES, "ISO-8859-1");
    //$policyValue = str_replace(",", "", $policyValue);
    $ciValue = htmlspecialchars($_POST['ciValue'],ENT_QUOTES, "ISO-8859-1");
    $ciValue = str_replace(",", "", $ciValue);
    $gerpVNo = htmlspecialchars($_POST['gerpVNo'],ENT_QUOTES, "ISO-8859-1");
    //$gerpInvNo = htmlspecialchars($_POST['gerpInvNo'],ENT_QUOTES, "ISO-8859-1");
    $ciNo = htmlspecialchars($_POST['ciNo'],ENT_QUOTES, "ISO-8859-1");
    $ciDate = htmlspecialchars($_POST['ciDate'],ENT_QUOTES, "ISO-8859-1");
    $ciDate = date('Y-m-d', strtotime($ciDate));
    if($_POST['endDate']!=""){
        $endDate = htmlspecialchars($_POST['endDate'],ENT_QUOTES, "ISO-8859-1");
        $endDate = date('Y-m-d', strtotime($endDate));
    } else{
        $endDate = date('Y-m-d');
    }
    $endCharge = htmlspecialchars($_POST['endCharge'],ENT_QUOTES, "ISO-8859-1");
    $endCharge = str_replace(",", "", $endCharge);
    $vatOnCharge = htmlspecialchars($_POST['vatOnCharge'],ENT_QUOTES, "ISO-8859-1");
    $chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");
    //$docDelivered = htmlspecialchars($_POST['docDelivered'],ENT_QUOTES, "ISO-8859-1");
    if(!isset($_POST['docDelivered'])){
        $docDelivered = 0;
        $docDeliveredOn = 'NULL';
    } else{
        $docDelivered = 1;
        $docDeliveredOn = 'CURRENT_DATE';
    };

    $attachEndorsementCopy = htmlspecialchars($_POST['attachEndorsementCopy'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementAdvice = htmlspecialchars($_POST['attachEndorsementAdvice'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementOtherDoc = htmlspecialchars($_POST['attachEndorsementOtherDoc'],ENT_QUOTES, "ISO-8859-1");


    $attachEndorsementCopyOld = htmlspecialchars($_POST['attachEndorsementCopyOld'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementAdviceOld = htmlspecialchars($_POST['attachEndorsementAdviceOld'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementOtherDocOld = htmlspecialchars($_POST['attachEndorsementOtherDocOld'],ENT_QUOTES, "ISO-8859-1");


    //$createdby = htmlspecialchars($_POST['createdby'],ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------
    $pono = stripslashes($pono);
    $lcno = stripslashes($lcno);
    $shipno = stripslashes($shipno);
    $hiddenEndId = stripslashes($hiddenEndId);
    //$policyNum = stripslashes($policyNum);
    //$policyValue = stripslashes($policyValue);
    $ciValue = stripslashes($ciValue);
    $gerpVNo = stripslashes($gerpVNo);
    //$gerpInvNo = stripslashes($gerpInvNo);
    $ciNo = stripslashes($ciNo);
    $ciDate = stripslashes($ciDate);
    $endDate = stripslashes($endDate);
    $endCharge = stripslashes($endCharge);
    $vatOnCharge = stripslashes($vatOnCharge);
    $chargeType = stripslashes($chargeType);
    //$docDelivered = stripslashes($docDelivered);
    //$docDeliveredOn = stripslashes($docDeliveredOn);
    //$createdby = stripslashes($createdby);

    $objdal = new dal();

    $pono = $objdal->real_escape_string($pono);
    $lcno = $objdal->real_escape_string($lcno);
    $shipno = $objdal->real_escape_string($shipno);
    $hiddenEndId = $objdal->real_escape_string($hiddenEndId);
    //$policyNum = $objdal->real_escape_string($policyNum);
    //$policyValue = $objdal->real_escape_string($policyValue);
    $ciValue = $objdal->real_escape_string($ciValue);
    $gerpVNo = $objdal->real_escape_string($gerpVNo);
    //$gerpInvNo = $objdal->real_escape_string($gerpInvNo);
    $ciNo = $objdal->real_escape_string($ciNo);
    $ciDate = $objdal->real_escape_string($ciDate);
    $endDate = $objdal->real_escape_string($endDate);
    $endCharge = $objdal->real_escape_string($endCharge);
    $vatOnCharge = $objdal->real_escape_string($vatOnCharge);
    $chargeType = $objdal->real_escape_string($chargeType);
    //$docDelivered = $objdal->real_escape_string($docDelivered);
    //$docDeliveredOn = $objdal->real_escape_string($docDeliveredOn);
    //$createdby = $objdal->real_escape_string($createdby);
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------

    if ($hiddenEndId != ""){
        $sql = "UPDATE `wc_t_endorsement` SET
        `endNo` = $shipno,
        `pono` = '$pono',
        `lcno` = '$lcno',
        `ciValue` = $ciValue,
        `gerpVNo` = '$gerpVNo',
        `gerpInvNo` = NULL,
        `ciNo` = '$ciNo',
        `ciDate` = '$ciDate',
        `endDate` = '$endDate',
        `endCharge` = $endCharge,
        `vatOnCharge` = $vatOnCharge,
        `chargeType` = $chargeType,
        `createdby` = $user_id,
        `createdfrom` = '$ip'
        WHERE `id` = $hiddenEndId;";
//    echo($query);

        $objdal->update($sql);
        $lastEndId =  $hiddenEndId;
    }
    else{
        $sql = "INSERT INTO `wc_t_endorsement` SET
        `endNo` = $shipno,
        `pono` = '$pono',
        `lcno` = '$lcno',
        `ciValue` = $ciValue,
        `gerpVNo` = '$gerpVNo',
        `gerpInvNo` = NULL,
        `ciNo` = '$ciNo',
        `ciDate` = '$ciDate',
        `endDate` = '$endDate',
        `endCharge` = $endCharge,
        `vatOnCharge` = $vatOnCharge,
        `chargeType` = $chargeType,
        `createdby` = $user_id,
        `createdfrom` = '$ip';";
//    echo($query);

        $objdal->insert($sql);

        $lastEndId =  $objdal->LastInsertId();
    }


    //insert attachment
    if ($attachEndorsementCopy!=""){
        if ($attachEndorsementCopyOld == ""){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Copy', '$attachEndorsementCopy', $user_id, '$ip', $loginRole, '$lcno')";
            $objdal->insert($query);
        }
        else{
            fileDeleteFromDocs($pono);
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachEndorsementCopy',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Endorsement Copy' AND `filename` = '$attachEndorsementCopyOld'";
            $objdal->update($query);
        }
    }
    if($attachEndorsementAdvice!=""){
        if ($attachEndorsementAdviceOld == ""){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Advice', '$attachEndorsementAdvice', $user_id, '$ip', $loginRole, '$lcno')";
            $objdal->insert($query);
        }
        else{
            fileDeleteFromDocs($pono);
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachEndorsementAdvice',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Endorsement Advice' AND `filename` = '$attachEndorsementAdviceOld'";
            $objdal->update($query);
        }
    }
    if($attachEndorsementOtherDoc!=""){
        if ($attachEndorsementOtherDocOld == ""){
            $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
        ('$pono', $shipno, 'Endorsement Other Docs', '$attachEndorsementOtherDoc', $user_id, '$ip', $loginRole, '$lcno')";
            $objdal->insert($query);
        }
        else{
            fileDeleteFromDocs($pono);
            $query = "UPDATE `wc_t_attachments` SET
                `filename`='$attachEndorsementOtherDoc',
                `replacedby`=$user_id,
                `replacedfrom`='$ip'
                WHERE `poid` = '$pono' AND `title`='Endorsement Other Docs' AND `filename` = '$attachEndorsementOtherDocOld'";
            $objdal->update($query);
        }
    }

    //Transfer file from 'temp' directory to respective 'docs' directory
    fileTransferTempToDocs($pono);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);

}
function sendEndorsementRequest(){
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $lcissuerbank = htmlspecialchars($_POST['hiddenlcissuerbank'],ENT_QUOTES, "ISO-8859-1");
    $attachEndorsementLetter = htmlspecialchars($_POST['attachEndorsementLetter'],ENT_QUOTES, "ISO-8859-1");
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    $pono = stripslashes($pono);
    $lcno = stripslashes($lcno);
    $shipno = stripslashes($shipno);
    $lcissuerbank = stripslashes($lcissuerbank);

    $objdal = new dal();

    $pono = $objdal->real_escape_string($pono);
    $lcno = $objdal->real_escape_string($lcno);
    $shipno = $objdal->real_escape_string($shipno);
    $lcissuerbank = $objdal->real_escape_string($lcissuerbank);

if($attachEndorsementLetter!="") {
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `shipno`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`, `lcno`) VALUES 
    ('$pono', $shipno, 'Endorsement Request', '$attachEndorsementLetter', $user_id, '$ip', $loginRole, '$lcno')";
    $objdal->insert($query);
}

    fileTransferTempToDocs($pono);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Request_For_Doc_Endorsement_Send_By_GP,
        'status' => 1,
        'pendingtoco' => $lcissuerbank,
        'msg' => "'Endorsement doc requested against PO# ".$pono." and Shipment# ".$shipno."'",
    );
    UpdateAction($action);

    $res["status"] = 1;
    $res["message"] = 'Endorsement Doc requested successfully!';
    return json_encode($res);

}

function GetEndorsementInfo($pono, $shipno)
{
	$objdal = new dal();
	$query = "SELECT *,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno` = $shipno AND `title`='Endorsement Copy' ORDER BY id DESC LIMIT 1) `attachEndorsementCopy`,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno` = $shipno AND `title`='Endorsement Advice' ORDER BY id DESC LIMIT 1) `attachEndorsementAdvice`,
        (SELECT `filename` FROM `wc_t_attachments` WHERE `poid`='$pono' AND `shipno` = $shipno AND `title`='Endorsement Other Docs' ORDER BY id DESC LIMIT 1) `attachEndorsementOtherDoc`
        FROM `wc_t_endorsement` WHERE `pono` = '$pono' AND `endNo` = $shipno ORDER BY `id` DESC Limit 1;";
//	echo $query;
    $res = '';
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

function DocDelivered($pono, $endno, $refId1, $shipno){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($refId1);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }else{
        //$res["status"] = 0;
    	//$res["message"] = 'Valid reference code.'.$refId;
    	//return json_encode($res);
    }
    
    //$shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $objdal = new dal();
//	$query = "UPDATE `wc_t_endorsement` SET 
//            `docDelivered` = b'1',
//            `docDeliveredOn` = CURRENT_DATE
//            WHERE `pono` = '$pono' AND `endNo` = $endno;";
//	$objdal->update($query);

    // Update document info in shipment table
    $query = "UPDATE `wc_t_shipment` SET `docType` = ".doctype_endorsed.", 
		      `docDeliveredByFin` = current_timestamp()
            WHERE `pono` = '$pono' AND `shipNo` = '$shipno';";
	$objdal->update(trim($query));

    //echo $query;

    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipno,
        'actionid' => action_Endorsed_Document_Delivered,
        'status' => 1,
        'msg' => "'Endorsed document delivered to sourcing.'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    unset($objdal);
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
}

?>

