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
		case 1:	 //get terms info
			echo GetTermInfo($_GET["lc"], $_GET["term"]);
			break;
		case 2:	 //get a purchase order
			echo GetPODetail($_GET["id"]);
			break;
		case 3:	 //get payment part name
			echo GetPPName();
			break;
		case 4:	 //get Certificate Tag
			echo GetCertTag($_GET["name"]);
			break;
		default:
			break;
	}
}

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["pono"]) || isset($_POST["pono"])){
	   //echo "from class";
       echo SubmitLCRequest();
        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";
    }
}

// Insert
function SubmitLCRequest()
{
	global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }else{
        //$res["status"] = 0;
    	//$res["message"] = 'Valid reference code.'.$refId;
    	//return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    $lcafno = htmlspecialchars($_POST['lcANo'],ENT_QUOTES, "ISO-8859-1");
    $lcvalue = htmlspecialchars($_POST['lcvalue'],ENT_QUOTES, "ISO-8859-1");
    $lcdesc = htmlspecialchars($_POST['lcdesc1'],ENT_QUOTES, "ISO-8859-1");
    $lcvalue = str_replace(",", "", $lcvalue );
    $lcRequestType = htmlspecialchars($_POST['lcRequestType'],ENT_QUOTES, "ISO-8859-1");
    //$lctype = htmlspecialchars($_POST['lctype'],ENT_QUOTES, "ISO-8859-1");
    //$lcissuedate = htmlspecialchars($_POST['lcissuedate'],ENT_QUOTES, "ISO-8859-1");
    
    $lcexpirydate = htmlspecialchars($_POST['lcexpirydate'],ENT_QUOTES, "ISO-8859-1");
    $lcexpirydate = date('Y-m-d', strtotime($lcexpirydate));
    
    $lastdateofship = htmlspecialchars($_POST['lastdateofship'],ENT_QUOTES, "ISO-8859-1");
    $lastdateofship = date('Y-m-d', strtotime($lastdateofship));
    //$lcissuerbank = htmlspecialchars($_POST['lcissuerbank'],ENT_QUOTES, "ISO-8859-1");
    //$bankaccount = htmlspecialchars($_POST['bankaccount'],ENT_QUOTES, "ISO-8859-1");
    //$bankservice = htmlspecialchars($_POST['bankservice'],ENT_QUOTES, "ISO-8859-1");
    //$serviceremark = htmlspecialchars($_POST['serviceremark'],ENT_QUOTES, "ISO-8859-1");
    //$insurance = htmlspecialchars($_POST['insurance'],ENT_QUOTES, "ISO-8859-1");
    //$producttype = htmlspecialchars($_POST['producttype'],ENT_QUOTES, "ISO-8859-1");
    
    if(!isset($_POST['cocorigin'])){ $cocorigin = 0; } else{ $cocorigin = 1; };
    if(!isset($_POST['iplbltolcbank'])){ $iplbltolcbank = 0; } else{ $iplbltolcbank = 1; };
    if(!isset($_POST['delivcertify'])){ $delivcertify = 0; } else{ $delivcertify = 1; };
    if(!isset($_POST['qualitycertify'])){ $qualitycertify = 0; } else{ $qualitycertify = 1; };
    if(!isset($_POST['qualitycertify1'])){ $qualitycertify1 = 0; } else{ $qualitycertify1 = 1; };
    if(!isset($_POST['advshipdoc'])){ $advshipdoc = 0; } else{ $advshipdoc = 1; };
    if(!isset($_POST['advshipdocwithbl'])){ $advshipdocwithbl = 0; } else{ $advshipdocwithbl = 1; };
    if(!isset($_POST['addconfirmation'])){ $addconfirmation = 0; } else{ $addconfirmation = 1; };
    if(!isset($_POST['preshipinspection'])){ $preshipinspection = 0; } else{ $preshipinspection = 1; };
    if(!isset($_POST['transshipment'])){ $transshipment = 0; } else{ $transshipment = 1; };
    if(!isset($_POST['partship'])){ $partship = 0; } else{ $partship = 1; };
    if(!isset($_POST['confchargeatapp'])){ $confchargeatapp = 0; } else{ $confchargeatapp = 1; };
       
    $otherTerms = htmlspecialchars($_POST['otherTerms'],ENT_QUOTES, "ISO-8859-1");

    $ircno = htmlspecialchars($_POST['ircno'],ENT_QUOTES, "ISO-8859-1");
    $imppermitno = htmlspecialchars($_POST['imppermitno'],ENT_QUOTES, "ISO-8859-1");
    $tinno = htmlspecialchars($_POST['tinno'],ENT_QUOTES, "ISO-8859-1");
    $vatregno = htmlspecialchars($_POST['vatregno'],ENT_QUOTES, "ISO-8859-1");
    $customername = htmlspecialchars($_POST['customername'],ENT_QUOTES, "ISO-8859-1");
    $customeraddress = htmlspecialchars($_POST['customeraddress'],ENT_QUOTES, "ISO-8859-1");
    $paymentTermsText = htmlspecialchars(replaceTextRegex($_POST['paymentTermsText']),ENT_QUOTES, "ISO-8859-1");
    
    $advBank = htmlspecialchars($_POST['advBank'],ENT_QUOTES, "ISO-8859-1");
    $contactPSI = htmlspecialchars($_POST['contactPSI'],ENT_QUOTES, "ISO-8859-1");
    $psiClauseA = htmlspecialchars($_POST['psiClauseA'],ENT_QUOTES, "ISO-8859-1");
    $psiClauseB = htmlspecialchars($_POST['psiClauseB'],ENT_QUOTES, "ISO-8859-1");
    $insNotification1 = htmlspecialchars($_POST['insNotification1'],ENT_QUOTES, "ISO-8859-1");
    $insNotification2 = htmlspecialchars($_POST['insNotification2'],ENT_QUOTES, "ISO-8859-1");
    $insNotification3 = htmlspecialchars($_POST['insNotification3'],ENT_QUOTES, "ISO-8859-1");
    $forAirShipment1 = htmlspecialchars($_POST['forAirShipment1'],ENT_QUOTES, "ISO-8859-1");
    $forAirShipment2 = htmlspecialchars($_POST['forAirShipment2'],ENT_QUOTES, "ISO-8859-1");
    $forSeaShipment1 = htmlspecialchars($_POST['forSeaShipment1'],ENT_QUOTES, "ISO-8859-1");
    $forSeaShipment2 = htmlspecialchars($_POST['forSeaShipment2'],ENT_QUOTES, "ISO-8859-1");
    $shippingRemarks = htmlspecialchars($_POST['shippingRemarks'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $pono = stripslashes($pono);
    $lcno = stripslashes($lcno);
    $lcafno = stripslashes($lcafno);
    $lcvalue = stripslashes($lcvalue);
    $lcdesc = stripslashes($lcdesc);
    $lcRequestType = stripslashes($lcRequestType);
    //$lctype = stripslashes($lctype);
    //$lcissuedate = stripslashes($lcissuedate);
    $lcexpirydate = stripslashes($lcexpirydate);
    $lastdateofship = stripslashes($lastdateofship);
    //$lcissuerbank = stripslashes($lcissuerbank);
    //$bankaccount = stripslashes($bankaccount);
    //$bankservice = stripslashes($bankservice);
    //$serviceremark = stripslashes($serviceremark);
    //$insurance = stripslashes($insurance);
    //$producttype = stripslashes($producttype);
    $cocorigin = stripslashes($cocorigin);
    $iplbltolcbank = stripslashes($iplbltolcbank);
    $delivcertify = stripslashes($delivcertify);
    $qualitycertify = stripslashes($qualitycertify);
    $qualitycertify1 = stripslashes($qualitycertify1);
    $advshipdoc = stripslashes($advshipdoc);
    $advshipdocwithbl = stripslashes($advshipdocwithbl);
    $addconfirmation = stripslashes($addconfirmation);
    $preshipinspection = stripslashes($preshipinspection);
    $transshipment = stripslashes($transshipment);
    $partship = stripslashes($partship);
    $confchargeatapp = stripslashes($confchargeatapp);
    $otherTerms = stripslashes($otherTerms);
    $ircno = stripslashes($ircno);
    $imppermitno = stripslashes($imppermitno);
    $tinno = stripslashes($tinno);
    $vatregno = stripslashes($vatregno);
    $customername = stripslashes($customername);
    $customeraddress = stripslashes($customeraddress);
    $paymentTermsText = stripslashes($paymentTermsText);
    
    $advBank = stripslashes($advBank);
    $contactPSI = stripslashes($contactPSI);
    $psiClauseA = stripslashes($psiClauseA);
    $psiClauseB = stripslashes($psiClauseB);
    $insNotification1 = stripslashes($insNotification1);
    $insNotification2 = stripslashes($insNotification2);
    $insNotification3 = stripslashes($insNotification3);
    $forAirShipment1 = stripslashes($forAirShipment1);
    $forAirShipment2 = stripslashes($forAirShipment2);
    $forSeaShipment1 = stripslashes($forSeaShipment1);
    $forSeaShipment2 = stripslashes($forSeaShipment2);
    $shippingRemarks = stripslashes($shippingRemarks);
	
	$objdal = new dal();
	
    $pono = $objdal->real_escape_string($pono);
    $lcno = $objdal->real_escape_string($lcno);
    $lcafno = $objdal->real_escape_string($lcafno);
    $lcvalue = $objdal->real_escape_string($lcvalue);
    $lcdesc = $objdal->real_escape_string($lcdesc);
    $lcRequestType = $objdal->real_escape_string($lcRequestType);
    //$lctype = $objdal->real_escape_string($lctype);
    //$lcissuedate = $objdal->real_escape_string($lcissuedate);
    $lcexpirydate = $objdal->real_escape_string($lcexpirydate);
    $lastdateofship = $objdal->real_escape_string($lastdateofship);
    //$lcissuerbank = $objdal->real_escape_string($lcissuerbank);
    //$bankaccount = $objdal->real_escape_string($bankaccount);
    //$bankservice = $objdal->real_escape_string($bankservice);
    //$serviceremark = $objdal->real_escape_string($serviceremark);
    //$insurance = $objdal->real_escape_string($insurance);
    //$producttype = $objdal->real_escape_string($producttype);
    $cocorigin = $objdal->real_escape_string($cocorigin);
    $iplbltolcbank = $objdal->real_escape_string($iplbltolcbank);
    $delivcertify = $objdal->real_escape_string($delivcertify);
    $qualitycertify = $objdal->real_escape_string($qualitycertify);
    $qualitycertify1 = $objdal->real_escape_string($qualitycertify1);
    $advshipdoc = $objdal->real_escape_string($advshipdoc);
    $advshipdocwithbl = $objdal->real_escape_string($advshipdocwithbl);
    $addconfirmation = $objdal->real_escape_string($addconfirmation);
    $preshipinspection = $objdal->real_escape_string($preshipinspection);
    $transshipment = $objdal->real_escape_string($transshipment);
    $partship = $objdal->real_escape_string($partship);
    $confchargeatapp = $objdal->real_escape_string($confchargeatapp);
    $otherTerms = $objdal->real_escape_string($otherTerms);
    $ircno = $objdal->real_escape_string($ircno);
    $imppermitno = $objdal->real_escape_string($imppermitno);
    $tinno = $objdal->real_escape_string($tinno);
    $vatregno = $objdal->real_escape_string($vatregno);
    $customername = $objdal->real_escape_string($customername);
    $customeraddress = $objdal->real_escape_string($customeraddress);
    $paymentTermsText = $objdal->real_escape_string($paymentTermsText);
    
    $advBank = $objdal->real_escape_string($advBank);
    $contactPSI = $objdal->real_escape_string($contactPSI);
    $psiClauseA = $objdal->real_escape_string($psiClauseA);
    $psiClauseB = $objdal->real_escape_string($psiClauseB);
    $insNotification1 = $objdal->real_escape_string($insNotification1);
    $insNotification2 = $objdal->real_escape_string($insNotification2);
    $insNotification3 = $objdal->real_escape_string($insNotification3);
    $forAirShipment1 = $objdal->real_escape_string($forAirShipment1);
    $forAirShipment2 = $objdal->real_escape_string($forAirShipment2);
    $forSeaShipment1 = $objdal->real_escape_string($forSeaShipment1);
    $forSeaShipment2 = $objdal->real_escape_string($forSeaShipment2);
    $shippingRemarks = $objdal->real_escape_string($shippingRemarks);
    
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'Failed to PO!';
	//------------------------------------------------------------------------------
    
/**
 *  Checking that, if LC request already been sent and it is has rejected back for changes
 */
    $query = "SELECT COUNT(*) `exist` FROM `wc_t_lc` WHERE `pono` = '$pono';";
    $objdal->read($query);
    $res = $objdal->data[0];
    extract($res);
    
    if($exist==0){
        $taskMessage = "Insert new data.";
    	// insert new po
        $query = "INSERT INTO `wc_t_lc` SET
    		`pono` = '$pono',
            `lcno` = '$lcno',
            `lcvalue` = $lcvalue,
            `lcdesc` = '$lcdesc',
            `lcafno` = '$lcafno',
            `lcexpirydate` = '$lcexpirydate',
            `lastdateofship` = '$lastdateofship',
            `cocorigin` = b'$cocorigin',
            `iplbltolcbank` = b'$iplbltolcbank',
            `delivcertify` = b'$delivcertify',
            `qualitycertify` = b'$qualitycertify',
            `qualitycertify1` = b'$qualitycertify1',
            `advshipdoc` = b'$advshipdoc',
            `advshipdocwithbl` = b'$advshipdocwithbl',
            `addconfirmation` = b'$addconfirmation',
            `preshipinspection` = b'$preshipinspection',
            `transshipment` = b'$transshipment',
            `partship` = b'$partship',
            `confchargeatapp` = b'$confchargeatapp',
            `lca` = b'$lcRequestType',
            `otherTerms` = '$otherTerms',
            `ircno` = '$ircno',
            `imppermitno` = '$imppermitno',
            `tinno` = '$tinno',
            `vatregno` = '$vatregno',
            `customername` = '$customername',
            `customeraddress` = '$customeraddress',
            `paymentterms` = '$paymentTermsText',
            `advBank` = '$advBank',
            `contactPSI` = '$contactPSI',
            `psiClauseA` = '$psiClauseA',
            `psiClauseB` = '$psiClauseB',
            `insNotification1` = '$insNotification1',
            `insNotification2` = '$insNotification2',
            `insNotification3` = '$insNotification3',
            `forAirShipment1` = '$forAirShipment1',
            `forAirShipment2` = '$forAirShipment2',
            `forSeaShipment1` = '$forSeaShipment1',
            `forSeaShipment2` = '$forSeaShipment2',
            `shippingRemarks` = '$shippingRemarks',
            `createdby` = $user_id, 
    		`createdfrom` = '$ip';";
        //echo $query;*/
//        return json_encode($res);
    	$objdal->insert($query);
    } else{
        $taskMessage = "Update new data.";
        // insert new po
        $query = "UPDATE `wc_t_lc` SET 
    		`lcno` = '$lcno',
            `lcvalue` = $lcvalue,
            `lcdesc` = '$lcdesc',
            `lcafno` = '$lcafno',
            `lcexpirydate` = '$lcexpirydate',
            `lastdateofship` = '$lastdateofship',
            `cocorigin` = b'$cocorigin',
            `iplbltolcbank` = b'$iplbltolcbank',
            `delivcertify` = b'$delivcertify',
            `qualitycertify` = b'$qualitycertify',
            `qualitycertify1` = b'$qualitycertify1',
            `advshipdoc` = b'$advshipdoc',
            `advshipdocwithbl` = b'$advshipdocwithbl',
            `addconfirmation` = b'$addconfirmation',
            `preshipinspection` = b'$preshipinspection',
            `transshipment` = b'$transshipment',
            `partship` = b'$partship',
            `confchargeatapp` = b'$confchargeatapp',
            `lca` = b'$lcRequestType',
            `otherTerms` = '$otherTerms',
            `ircno` = '$ircno',
            `imppermitno` = '$imppermitno',
            `tinno` = '$tinno',
            `vatregno` = '$vatregno',
            `customername` = '$customername',
            `customeraddress` = '$customeraddress',
            `paymentterms` = '$paymentTermsText',
            `advBank` = '$advBank',
            `contactPSI` = '$contactPSI',
            `psiClauseA` = '$psiClauseA',
            `psiClauseB` = '$psiClauseB',
            `insNotification1` = '$insNotification1',
            `insNotification2` = '$insNotification2',
            `insNotification3` = '$insNotification3',
            `forAirShipment1` = '$forAirShipment1',
            `forAirShipment2` = '$forAirShipment2',
            `forSeaShipment1` = '$forSeaShipment1',
            `forSeaShipment2` = '$forSeaShipment2',
            `shippingRemarks` = '$shippingRemarks',
            `modifiedby` = $user_id, 
    		`modifiedfrom` = '$ip'
            WHERE `pono` = '$pono';";
    	$objdal->update($query);
    }
    
    /**
    * check if the installed by option has been changed 
    */
    
    if($_POST["installBy"]!=$_POST["installByOld"]){
        $query = "UPDATE `wc_t_pi` SET `installbysupplier` = ".$_POST["installBy"]." WHERE `poid`='$pono';";
        $objdal->update($query);
    }
    
    /**
    * If LC request already exist then clear the terms first
    * then insert again.
    */
    if($exist>0){
        $query = "DELETE FROM `wc_t_payment_terms` WHERE `pono` = '$pono'";
        $objdal->delete($query);
    }
    $query = '';
    for($i=0; $i<count($_POST['ppPercentage']); $i++){
        //echo $_POST['ppPercentage'][$i].'<br />';
        $query = "INSERT INTO `wc_t_payment_terms` SET 
            `pono` = '$pono',
            `partname` = ".$_POST['ppPartName'][$i].",
            `percentage` = ".$_POST['ppPercentage'][$i].",
            `dayofmaturity` = '".$_POST['ppMaturityDay'][$i]."',
            `maturityterms` = '".$_POST['ppMaturityTerm'][$i]."',
            `cacFacDay` = '".$_POST['cacFacDay'][$i]."',
            `cacFacText` = '".$_POST['cacFacText'][$i]."';";
        //echo $query;
        
        $objdal->insert($query);
    }
    
    // Action Log --------------------------------//
    if($exist==0){
        $msg = "'New LC request has been sent from sourcing against PO# ".$pono."'";
    }else{
        $msg = "'Changed LC request has been sent from sourcing against PO# ".$pono."'";
    }
    
    $actionId = getActionID($refId);
    if($actionId == action_Rejected_by_1st_Level){ $newActionId = action_Sent_Revised_LC_Request_1; }
    elseif($actionId == action_Rejected_by_2nd_Level){ $newActionId = action_Sent_Revised_LC_Request_2; }
    elseif($actionId == action_Rejected_by_3rd_Level){ $newActionId = action_Sent_Revised_LC_Request_3; }
    elseif($actionId == action_Rejected_by_4th_Level){ $newActionId = action_Sent_Revised_LC_Request_4; }
    elseif($actionId == action_Rejected_by_5th_Level){ $newActionId = action_Sent_Revised_LC_Request_5; }
    else{ $newActionId = action_LC_Request_Sent; }
    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => $newActionId,
        'status' => 1,
        'msg' => $msg,
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'LC request has been sent SUCCESSFULLY';
	return json_encode($res);
}

function GetTermInfo($lcno, $term)
{
	$objdal = new dal();
	$query = "SELECT *
            FROM `wc_t_payment_terms` 
            WHERE `pono` = (SELECT `pono` FROM `wc_t_lc` WHERE `lcno`='$lcno') AND `partname`=$term;";
	$objdal->read($query);
    
    $res = null;
    
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

/*Get payment part name Added by - Hasan Masud
**********************************************/
function GetPPName(){
    $objdal=new dal();

    $strQuery = "SELECT `id`, `name` FROM `wc_t_category` WHERE `menu`= 25;";
    /*echo '<pre>';
    	print_r($strQuery);
    echo '</pre>';*/
    $objdal->read($strQuery);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$id.'", "text": "'.$name.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;
}

/*Get Certificate TAG name, Added by - Hasan Masud
**************************************************/
function GetCertTag($name){
    $objdal=new dal();
    $query = "SELECT `tag` FROM `wc_t_category` WHERE `id` = $name;";
    /*echo '<pre>';
    	print_r($query);
    echo '</pre>';*/
    $objdal->read($query);
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    return json_encode($res);
}
?>

