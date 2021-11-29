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
    $lcvalue = htmlspecialchars($_POST['lcvalue'],ENT_QUOTES, "ISO-8859-1");
    $lcvalue = str_replace(",", "", $lcvalue );
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
    if(!isset($_POST['advshipdoc'])){ $advshipdoc = 0; } else{ $advshipdoc = 1; };
    if(!isset($_POST['advshipdocwithbl'])){ $advshipdocwithbl = 0; } else{ $advshipdocwithbl = 1; };
    if(!isset($_POST['addconfirmation'])){ $addconfirmation = 0; } else{ $addconfirmation = 1; };
    if(!isset($_POST['preshipinspection'])){ $preshipinspection = 0; } else{ $preshipinspection = 1; };
    if(!isset($_POST['transshipment'])){ $transshipment = 0; } else{ $transshipment = 1; };
    if(!isset($_POST['partship'])){ $partship = 0; } else{ $partship = 1; };
    if(!isset($_POST['confchargeatapp'])){ $confchargeatapp = 0; } else{ $confchargeatapp = 1; };
       
    $ircno = htmlspecialchars($_POST['ircno'],ENT_QUOTES, "ISO-8859-1");
    $imppermitno = htmlspecialchars($_POST['imppermitno'],ENT_QUOTES, "ISO-8859-1");
    $tinno = htmlspecialchars($_POST['tinno'],ENT_QUOTES, "ISO-8859-1");
    $vatregno = htmlspecialchars($_POST['vatregno'],ENT_QUOTES, "ISO-8859-1");
    $customername = htmlspecialchars($_POST['customername'],ENT_QUOTES, "ISO-8859-1");
    $customeraddress = htmlspecialchars($_POST['customeraddress'],ENT_QUOTES, "ISO-8859-1");
    $paymentTermsText = htmlspecialchars($_POST['paymentTermsText'],ENT_QUOTES, "ISO-8859-1");
    
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    
	//---To protect MySQL injection for Security purpose----------------------------
    $pono = stripslashes($pono);
    $lcno = stripslashes($lcno);
    $lcvalue = stripslashes($lcvalue);
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
    $advshipdoc = stripslashes($advshipdoc);
    $advshipdocwithbl = stripslashes($advshipdocwithbl);
    $addconfirmation = stripslashes($addconfirmation);
    $preshipinspection = stripslashes($preshipinspection);
    $transshipment = stripslashes($transshipment);
    $partship = stripslashes($partship);
    $confchargeatapp = stripslashes($confchargeatapp);
    $ircno = stripslashes($ircno);
    $imppermitno = stripslashes($imppermitno);
    $tinno = stripslashes($tinno);
    $vatregno = stripslashes($vatregno);
    $customername = stripslashes($customername);
    $customeraddress = stripslashes($customeraddress);
    $paymentTermsText = stripslashes($paymentTermsText);
	
	$objdal = new dal();
	
    $pono = $objdal->real_escape_string($pono);
    $lcno = $objdal->real_escape_string($lcno);
    $lcvalue = $objdal->real_escape_string($lcvalue);
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
    $advshipdoc = $objdal->real_escape_string($advshipdoc);
    $advshipdocwithbl = $objdal->real_escape_string($advshipdocwithbl);
    $addconfirmation = $objdal->real_escape_string($addconfirmation);
    $preshipinspection = $objdal->real_escape_string($preshipinspection);
    $transshipment = $objdal->real_escape_string($transshipment);
    $partship = $objdal->real_escape_string($partship);
    $confchargeatapp = $objdal->real_escape_string($confchargeatapp);
    $ircno = $objdal->real_escape_string($ircno);
    $imppermitno = $objdal->real_escape_string($imppermitno);
    $tinno = $objdal->real_escape_string($tinno);
    $vatregno = $objdal->real_escape_string($vatregno);
    $customername = $objdal->real_escape_string($customername);
    $customeraddress = $objdal->real_escape_string($customeraddress);
    $paymentTermsText = $objdal->real_escape_string($paymentTermsText);
    
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'Failed to PO!';
	//------------------------------------------------------------------------------
	// insert new po
    $query = "INSERT INTO `wc_t_lc` SET 
		`pono` = '$pono',
        `lcno` = '$lcno',
        `lcvalue` = $lcvalue,
        `lcexpirydate` = '$lcexpirydate',
        `lastdateofship` = '$lastdateofship',
        `cocorigin` = b'$cocorigin',
        `iplbltolcbank` = b'$iplbltolcbank',
        `delivcertify` = b'$delivcertify',
        `qualitycertify` = b'$qualitycertify',
        `advshipdoc` = b'$advshipdoc',
        `advshipdocwithbl` = b'$advshipdocwithbl',
        `addconfirmation` = b'$addconfirmation',
        `preshipinspection` = b'$preshipinspection',
        `transshipment` = b'$transshipment',
        `partship` = b'$partship',
        `confchargeatapp` = b'$confchargeatapp',
        `ircno` = '$ircno',
        `imppermitno` = '$imppermitno',
        `tinno` = '$tinno',
        `vatregno` = '$vatregno',
        `customername` = '$customername',
        `customeraddress` = '$customeraddress',
        `paymentterms` = '$paymentTermsText',
        `createdby` = $user_id, 
		`createdfrom` = '$ip';";
	$objdal->insert($query);
	//echo($query);
    
    $query = '';
    for($i=0; $i<count($_POST['ppPercentage']); $i++){
        //echo $_POST['ppPercentage'][$i].'<br />';
        $query = "INSERT INTO `wc_t_payment_terms` SET 
            `pono` = '$pono',
            `partname` = ".$_POST['ppPartName'][$i].",
            `percentage` = ".$_POST['ppPercentage'][$i].",
            `dayofmaturity` = ".$_POST['ppMaturityDay'][$i].",
            `maturityterms` = ".$_POST['ppMaturityTerm'][$i].";";
        
        $objdal->insert($query);
    }
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'actionid' => action_LC_Request_Sent,
        'status' => 1,
        'msg' => "'New LC request has been sent from sourcing against PO# ".$pono."'",
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
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}

?>

