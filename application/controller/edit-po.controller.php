<?php
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");
$user_role_name = $_SESSION[session_prefix.'wclogin_rolename'];
$user_name = $_SESSION[session_prefix.'wclogin_username'];

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo UpdatePO($_POST['postatus']);
    }
}

function UpdatePO($postatus)
{
    global $user_id;
    global $user_name;

    $refId = decryptId($_POST["refId"]);
    if (!is_numeric($refId)) {
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    $objdal = new dal();

    // PO --
    $poid = htmlspecialchars($_POST['poid'], ENT_QUOTES, "ISO-8859-1");
    $povalue = htmlspecialchars($_POST['povalue'], ENT_QUOTES, "ISO-8859-1");
    $povalue = str_replace(",", "", $povalue);
    $podesc = htmlspecialchars($_POST['podesc'], ENT_QUOTES, "ISO-8859-1");
    $podesc = str_replace("\r\n", "", str_replace("\t", " ", $podesc));

    if(isset($_POST["lcdesc"])) {
        $lcdesc = htmlspecialchars($_POST['lcdesc'], ENT_QUOTES, "ISO-8859-1");
    }

    $deliverydate = htmlspecialchars($_POST['deliverydate'], ENT_QUOTES, "ISO-8859-1");
    $deliverydate = date('Y-m-d', strtotime($deliverydate));
    $actualPoDate = $objdal->sanitizeInput($_POST['actualPoDate']);
    $actualPoDate = date('Y-m-d', strtotime($actualPoDate));

    $noflcissue = htmlspecialchars($_POST['noflcissue'], ENT_QUOTES, "ISO-8859-1");
    $nofshipallow = htmlspecialchars($_POST['nofshipallow'], ENT_QUOTES, "ISO-8859-1");

    $installbysupplier = htmlspecialchars($_POST['installBy'],ENT_QUOTES, "ISO-8859-1");

    $pruserto = htmlspecialchars($_POST['prUserEmailTo'],ENT_QUOTES, "ISO-8859-1");
    $prusercc = '';
    if(isset($_POST['prUserEmailCC'])){
        foreach($_POST['prUserEmailCC'] as $val) {
            if(strlen($prusercc)>0){$prusercc .= ',';}
            $prusercc.= htmlspecialchars($val,ENT_QUOTES, "ISO-8859-1");
        }
    }

    $supplier = htmlspecialchars($_POST['supplier'], ENT_QUOTES, "ISO-8859-1");
    $contractref = htmlspecialchars($_POST['contractref'], ENT_QUOTES, "ISO-8859-1");
    $emailto = htmlspecialchars($_POST['emailto'],ENT_QUOTES, "ISO-8859-1");
    $emailcc = htmlspecialchars($_POST['emailcc'],ENT_QUOTES, "ISO-8859-1");

    // PI --
    if($_POST['postatus'] >= action_Draft_PI_Submitted) {

        $pinum = replaceTextRegex($_POST['pinum']);
        $pivalue = htmlspecialchars($_POST['pivalue'], ENT_QUOTES, "ISO-8859-1");
        $pivalue = str_replace(",", "", $pivalue);

        $shipmode = htmlspecialchars($_POST['shipmode'], ENT_QUOTES, "ISO-8859-1");
        $hscode = htmlspecialchars($_POST['hscode'], ENT_QUOTES, "ISO-8859-1");

        if (isset($_POST["pidate"])) {
            $pidate = htmlspecialchars($_POST['pidate'], ENT_QUOTES, "ISO-8859-1");
            $pidate = date('Y-m-d', strtotime($pidate));
        }
        if (isset($_POST["basevalue"])) {
            $basevalue = htmlspecialchars($_POST['basevalue'], ENT_QUOTES, "ISO-8859-1");
            $basevalue = str_replace(",", "", $basevalue);
        }

        $origin = '';
        foreach($_POST['origin'] as $val) {
            if(strlen($origin)>0){$origin .= ',';}
            $origin.= htmlspecialchars($val,ENT_QUOTES, "ISO-8859-1");
        }
        $negobank = htmlspecialchars($_POST['negobank'], ENT_QUOTES, "ISO-8859-1");
        $shipport = htmlspecialchars($_POST['shipport'], ENT_QUOTES, "ISO-8859-1");
        $lcbankaddress = htmlspecialchars($_POST['lcbankaddress'], ENT_QUOTES, "ISO-8859-1");
        $productiondays = htmlspecialchars($_POST['productiondays'], ENT_QUOTES, "ISO-8859-1");

    }

    // LC --
    if (isset($_POST["lcno"]) && !empty($_POST["lcno"])) {
        $lctype = htmlspecialchars($_POST['lctype'], ENT_QUOTES, "ISO-8859-1");

        $lcvalue = htmlspecialchars($_POST['lcvalue'], ENT_QUOTES, "ISO-8859-1");
        $lcvalue = str_replace(",", "", $lcvalue);

        $producttype = htmlspecialchars($_POST['producttype'], ENT_QUOTES, "ISO-8859-1");

        $lcissuerbank = htmlspecialchars($_POST['lcissuerbank'], ENT_QUOTES, "ISO-8859-1");
        $bankaccount = htmlspecialchars($_POST['bankaccount'], ENT_QUOTES, "ISO-8859-1");
        $insurance = htmlspecialchars($_POST['insurance'], ENT_QUOTES, "ISO-8859-1");

        $lcno = htmlspecialchars($_POST['lcno'], ENT_QUOTES, "ISO-8859-1");
        $lcafno = htmlspecialchars($_POST['lcafno'], ENT_QUOTES, "ISO-8859-1");

        $lcissuedate = htmlspecialchars($_POST['lcissuedate'], ENT_QUOTES, "ISO-8859-1");
        $lcissuedate = date('Y-m-d', strtotime($lcissuedate));

        $daysofexpiry = htmlspecialchars($_POST['daysofexpiry'], ENT_QUOTES, "ISO-8859-1");
        $daysofexpiry = date('Y-m-d', strtotime($daysofexpiry));

        $lastdateofship = htmlspecialchars($_POST['lastdateofship'], ENT_QUOTES, "ISO-8859-1");
        $lastdateofship = date('Y-m-d', strtotime($lastdateofship));

        $lcexpirydate = htmlspecialchars($_POST['lcexpirydate'], ENT_QUOTES, "ISO-8859-1");
        $lcexpirydate = date('Y-m-d', strtotime($lcexpirydate));


    }

    // Shipment --
    if (isset($_POST["shipno"]) && !empty($_POST["shipno"])) {
        $shipno = $_POST["shipno"];
        if (checkStepOver($poid, action_Shared_Shipment_Document, $shipno) > 0) {

            $shipmode1 = htmlspecialchars($_POST['shipmode1'], ENT_QUOTES, "ISO-8859-1");

            $scheduleETA = htmlspecialchars($_POST['scheduleETA'], ENT_QUOTES, "ISO-8859-1");
            $scheduleETA = date('Y-m-d', strtotime($scheduleETA));

            if($_POST['scheduleETD']!="") {
                $scheduleETD = htmlspecialchars($_POST['scheduleETD'], ENT_QUOTES, "ISO-8859-1");
                $scheduleETD = date('Y-m-d', strtotime($scheduleETD));
            }
            $mawbNo = htmlspecialchars($_POST['mawbNo'], ENT_QUOTES, "ISO-8859-1");
            $hawbNo = htmlspecialchars($_POST['hawbNo'], ENT_QUOTES, "ISO-8859-1");
            $blNo = htmlspecialchars($_POST['blNo'], ENT_QUOTES, "ISO-8859-1");

            $awbOrBlDate = htmlspecialchars($_POST['awbOrBlDate'], ENT_QUOTES, "ISO-8859-1");
            $awbOrBlDate = date('Y-m-d', strtotime($awbOrBlDate));

            $dhlTrackNo = htmlspecialchars($_POST['dhlTrackNo'],ENT_QUOTES, "ISO-8859-1");

            $GERPVoucherNo = htmlspecialchars($_POST['GERPVoucherNo'],ENT_QUOTES, "ISO-8859-1");

            $ciNo = htmlspecialchars($_POST['ciNo'], ENT_QUOTES, "ISO-8859-1");

            $ciDate = htmlspecialchars($_POST['ciDate'], ENT_QUOTES, "ISO-8859-1");
            $ciDate = date('Y-m-d', strtotime($ciDate));

            $ciAmount = htmlspecialchars($_POST['ciAmount'], ENT_QUOTES, "ISO-8859-1");
            $ciAmount = str_replace(",", "", $ciAmount);

            $invoiceQty = htmlspecialchars($_POST['invoiceQty'], ENT_QUOTES, "ISO-8859-1");
            $noOfcontainer = htmlspecialchars($_POST['noOfcontainer'], ENT_QUOTES, "ISO-8859-1");
            $noOfBoxes = htmlspecialchars($_POST['noOfBoxes'], ENT_QUOTES, "ISO-8859-1");
            $ChargeableWeight = htmlspecialchars($_POST['ChargeableWeight'], ENT_QUOTES, "ISO-8859-1");
        }
    }
    $buyersEditComment = htmlspecialchars($_POST['buyersEditComment'], ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");

    //---To protect MySQL injection for Security purpose----------------------------

    // PO --
    $poid = stripslashes($poid);
    $povalue = stripslashes($povalue);
    $podesc = stripslashes($podesc);
    if(isset($lcdesc)) {
        $lcdesc = stripslashes($lcdesc);
        $lcdesc = replaceTextRegex($lcdesc);
    }
    $deliverydate = stripslashes($deliverydate);
    $deliverydate = stripslashes($deliverydate);
    $noflcissue = stripslashes($noflcissue);
    $nofshipallow = stripslashes($nofshipallow);
    $installbysupplier = stripslashes($installbysupplier);
    $pruserto = stripslashes($pruserto);
    $prusercc = stripslashes($prusercc);
    $supplier = stripslashes($supplier);
    $contractref = stripslashes($contractref);
    $emailto = stripslashes($emailto);
    $emailcc = stripslashes($emailcc);

    // PI --
    if($postatus >= action_Draft_PI_Submitted) {

        $pivalue = stripslashes($pivalue);
        $pivalue = stripslashes($pivalue);
        $shipmode = stripslashes($shipmode);
        $hscode = stripslashes($hscode);
        if(isset($pidate)) {
            $pidate = stripslashes($pidate);
        }
        if(isset($basevalue)) {
            $basevalue = stripslashes($basevalue);
        }
        $origin = stripslashes($origin);
        $negobank = stripslashes($negobank);
        $shipport = stripslashes($shipport);
        $lcbankaddress = stripslashes($lcbankaddress);
        $productiondays = stripslashes($productiondays);
        $buyersEditComment = stripslashes($buyersEditComment);
    }

    // LC --
    if(isset($lctype)) {
        $lctype = stripslashes($lctype);
        $lcvalue = stripslashes($lcvalue);
        $producttype = stripslashes($producttype);
        $lcissuerbank = stripslashes($lcissuerbank);
        $bankaccount = stripslashes($bankaccount);
        $insurance = stripslashes($insurance);
        $lcno = stripslashes($lcno);
        $lcafno = stripslashes($lcafno);
        $lcissuedate = stripslashes($lcissuedate);
        $daysofexpiry = stripslashes($daysofexpiry);
        $lastdateofship = stripslashes($lastdateofship);
        $lcexpirydate = stripslashes($lcexpirydate);
    }
    // Shipment --
    if(isset($shipno)) {
        $shipmode1 = stripslashes($shipmode1);
        $scheduleETA = stripslashes($scheduleETA);
        if(isset($scheduleETD)) {
            $scheduleETD = stripslashes($scheduleETD);
        }
        $mawbNo = stripslashes($mawbNo);
        $hawbNo = stripslashes($hawbNo);
        $blNo = stripslashes($blNo);
        $awbOrBlDate = stripslashes($awbOrBlDate);
        $dhlTrackNo = stripslashes($dhlTrackNo);
        $GERPVoucherNo = stripslashes($GERPVoucherNo);
        $ciNo = stripslashes($ciNo);
        $ciDate = stripslashes($ciDate);
        $ciAmount = stripslashes($ciAmount);
        $invoiceQty = stripslashes($invoiceQty);
        $noOfcontainer = stripslashes($noOfcontainer);
        $noOfBoxes = stripslashes($noOfBoxes);
        $ChargeableWeight = stripslashes($ChargeableWeight);
    }


    // PO --
    $poid = $objdal->real_escape_string($poid);
    $povalue = $objdal->real_escape_string($povalue);
    $podesc = $objdal->real_escape_string($podesc);
    $podesc = replaceTextRegex($podesc);
    if(isset($_POST["lcdesc"])) {
        $lcdesc = $objdal->real_escape_string($lcdesc);
        $lcdesc = replaceTextRegex($lcdesc);
    }
    $deliverydate = $objdal->real_escape_string($deliverydate);
    $deliverydate = $objdal->real_escape_string($deliverydate);
    $noflcissue = $objdal->real_escape_string($noflcissue);
    $nofshipallow = $objdal->real_escape_string($nofshipallow);
    $installbysupplier = $objdal->real_escape_string($installbysupplier);
    $pruserto = $objdal->real_escape_string($pruserto);
    $prusercc = $objdal->real_escape_string($prusercc);
    $supplier = $objdal->real_escape_string($supplier);
    $contractref = $objdal->real_escape_string($contractref);
    $emailto = $objdal->real_escape_string($emailto);
    $emailcc = $objdal->real_escape_string($emailcc);

    // PI --
    if($postatus >= action_Draft_PI_Submitted) {
        $pivalue = $objdal->real_escape_string($pivalue);
        $pivalue = $objdal->real_escape_string($pivalue);
        $shipmode = $objdal->real_escape_string($shipmode);
        $hscode = $objdal->real_escape_string($hscode);
        $hscode = replaceTextRegex($hscode);
        if(isset($pidate)) {
            $pidate = $objdal->real_escape_string($pidate);
        }
        if(isset($basevalue)) {
            $basevalue = $objdal->real_escape_string($basevalue);
        }
        $origin = $objdal->real_escape_string($origin);
        $negobank = $objdal->real_escape_string($negobank);
        $negobank = replaceTextRegex($negobank);
        $shipport = $objdal->real_escape_string($shipport);
        $shipport = replaceTextRegex($shipport);
        $lcbankaddress = $objdal->real_escape_string($lcbankaddress);
        $lcbankaddress = replaceTextRegex($lcbankaddress);
        $productiondays = $objdal->real_escape_string($productiondays);
        $buyersEditComment = $objdal->real_escape_string($buyersEditComment);
        $buyersEditComment = replaceTextRegex($buyersEditComment);
    }


    // LC --
    if(isset($lctype)) {
        $lctype = $objdal->real_escape_string($lctype);
        $lcvalue = $objdal->real_escape_string($lcvalue);
        $producttype = $objdal->real_escape_string($producttype);
        $lcissuerbank = $objdal->real_escape_string($lcissuerbank);
        $bankaccount = $objdal->real_escape_string($bankaccount);
        $insurance = $objdal->real_escape_string($insurance);
        $lcno = $objdal->real_escape_string($lcno);
        $lcafno = $objdal->real_escape_string($lcafno);
        $lcissuedate = $objdal->real_escape_string($lcissuedate);
        $daysofexpiry = $objdal->real_escape_string($daysofexpiry);
        $lastdateofship = $objdal->real_escape_string($lastdateofship);
        $lcexpirydate = $objdal->real_escape_string($lcexpirydate);
    }

    // Shipment --
    if(isset($shipno)) {
        $shipmode1 = $objdal->real_escape_string($shipmode1);
        $scheduleETA = $objdal->real_escape_string($scheduleETA);
        if(isset($scheduleETD)) {
            $scheduleETD = $objdal->real_escape_string($scheduleETD);
        }
        $mawbNo = $objdal->real_escape_string($mawbNo);
        $hawbNo = $objdal->real_escape_string($hawbNo);
        $blNo = $objdal->real_escape_string($blNo);
        $awbOrBlDate = $objdal->real_escape_string($awbOrBlDate);
        $dhlTrackNo = $objdal->real_escape_string($dhlTrackNo);
        $GERPVoucherNo = $objdal->real_escape_string($GERPVoucherNo);
        $ciNo = $objdal->real_escape_string($ciNo);
        $ciDate = $objdal->real_escape_string($ciDate);
        $ciAmount = $objdal->real_escape_string($ciAmount);
        $invoiceQty = $objdal->real_escape_string($invoiceQty);
        $noOfcontainer = $objdal->real_escape_string($noOfcontainer);
        $noOfBoxes = $objdal->real_escape_string($noOfBoxes);
        $ChargeableWeight = $objdal->real_escape_string($ChargeableWeight);
    }

    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to PO!';
    //------------------------------------------------------------------------------

    // Updating PO table
    $query = "UPDATE `wc_t_pi` SET 
            `povalue` = $povalue, 
            `podesc` = '$podesc',";

    if(isset($lcdesc)) {
        $query .= "`lcdesc` = '$lcdesc',";
    }

    $query .= "`supplier` = $supplier, 
            `contractref` = '$contractref', 
            `deliverydate` = '$deliverydate', 
            `actualPoDate` = '$actualPoDate', 
            `noflcissue` = $noflcissue, 
            `nofshipallow` = $nofshipallow, 
            `installbysupplier` = $installbysupplier,
            `emailto` = '$emailto', 
            `emailcc` = '$emailcc', 
            `pruserto` = '$pruserto', 
            `prusercc` = '$prusercc', ";

    if($postatus >= action_Draft_PI_Submitted) {
        $query .= "`pinum` = '$pinum',
            `pivalue` = $pivalue,";

        if(isset($pidate)) {
            $query .= "`pidate` = '$pidate',";
        }
        if(isset($basevalue)) {
            $query .= "`basevalue` = $basevalue,";
        }

        $query .= "`hscode` = '$hscode',
            `shipmode` = '$shipmode',
            `origin` = '$origin',
            `negobank` = '$negobank',
            `shipport` = '$shipport',
            `lcbankaddress` = '$lcbankaddress',
            `productiondays` = $productiondays,";
    }

    $query .= " 
            `modifiedby` = $user_id, 
            `modifiedfrom` = '$ip'
        WHERE `poid` = '$poid';";
    //echo $query;
    $objdal->update($query,"Could not update PO data");

    // Updating LC Table
    if(isset($lctype)) {
        $query = "UPDATE `wc_t_lc` SET 
        `lctype` = $lctype,
        `lcissuerbank` = $lcissuerbank,
        `insurance` = $insurance,
        `bankaccount` = $bankaccount,
        `lcno` = '$lcno', 
        `lcissuedate` = '$lcissuedate', 
        `daysofexpiry` = '$daysofexpiry', 
        `lastdateofship` = '$lastdateofship', 
        `lcexpirydate` = '$lcexpirydate', 
        `producttype` = $producttype,
        `lcvalue` = $lcvalue,
        `lcafno` = '$lcafno'
        WHERE `pono` = '$poid';";

        //echo $query;
        $objdal->update($query, "Could not update LC data");

    }

    // LC terms update
    if(isset($_POST['ppPercentage']) && !empty($_POST['ppPercentage'])) {
        /**
         * If LC request already exist then clear the terms first
         * then insert again.
         */
        $query = "DELETE FROM `wc_t_payment_terms` WHERE `pono` = '$poid'";
        $objdal->delete($query, "Could not delete PT old data");

        for ($i = 0; $i < count($_POST['ppPercentage']); $i++) {

            $termId = htmlspecialchars($_POST['termId'][$i], ENT_QUOTES, "ISO-8859-1");
            $ppPartName = htmlspecialchars($_POST['ppPartName'][$i], ENT_QUOTES, "ISO-8859-1");
            $ppPercentage = htmlspecialchars($_POST['ppPercentage'][$i], ENT_QUOTES, "ISO-8859-1");
            $ppMaturityDay = htmlspecialchars($_POST['ppMaturityDay'][$i], ENT_QUOTES, "ISO-8859-1");
            $ppMaturityTerm = htmlspecialchars($_POST['ppMaturityTerm'][$i], ENT_QUOTES, "ISO-8859-1");
            $cacFacDay = htmlspecialchars($_POST['cacFacDay'][$i], ENT_QUOTES, "ISO-8859-1");
            $cacFacText = htmlspecialchars($_POST['cacFacText'][$i], ENT_QUOTES, "ISO-8859-1");
            //$cacFacDay = 0;
            //$cacFacText = 0;

            $termId = stripslashes($termId);
            $ppPartName = stripslashes($ppPartName);
            $ppPercentage = stripslashes($ppPercentage);
            $ppMaturityDay = stripslashes($ppMaturityDay);
            $ppMaturityTerm = stripslashes($ppMaturityTerm);
            $cacFacDay = stripslashes($cacFacDay);
            $cacFacText = stripslashes($cacFacText);

            $termId = $objdal->real_escape_string($termId);
            $ppPartName = $objdal->real_escape_string($ppPartName);
            $ppPercentage = $objdal->real_escape_string($ppPercentage);
            $ppMaturityDay = $objdal->real_escape_string($ppMaturityDay);
            $ppMaturityTerm = $objdal->real_escape_string($ppMaturityTerm);
            $cacFacDay = $objdal->real_escape_string($cacFacDay);
            $cacFacText = $objdal->real_escape_string($cacFacText);

            /*$query = "UPDATE `wc_t_payment_terms` SET
                `partname` = $ppPartName,
                `percentage` = $ppPercentage,
                `dayofmaturity` = $ppMaturityDay,
                `maturityterms` = $ppMaturityTerm
                WHERE `pono` = '$poid' AND `id` = $termId;";

            $objdal->update($query);*/

            $query = "INSERT INTO `wc_t_payment_terms` SET 
                `pono` = '$poid',
                `partname` = $ppPartName,
                `percentage` = $ppPercentage,
                `dayofmaturity` = $ppMaturityDay,
                `maturityterms` = $ppMaturityTerm,
                `cacFacDay` = '$cacFacDay',
                `cacFacText` = '$cacFacText';";

            $objdal->insert($query, "Could not add payment terms data");
        }

        /*UPDATE LC TERMS TEXT*/
        $paymentTermsText = htmlspecialchars($_POST['paymentTermsText'],ENT_QUOTES, "ISO-8859-1");
        $paymentTermsText = stripslashes($paymentTermsText);
        $paymentTermsText = $objdal->real_escape_string($paymentTermsText);
        $query = "UPDATE `wc_t_lc` SET `paymentterms` = '$paymentTermsText' WHERE `pono` = '$poid';";
        $objdal->update($query,"Could not update LC data of payment terms");
    }
    // Updating Shipment Table
    if(isset($shipno)) {
        $query = "UPDATE `wc_t_shipment` SET 
			`lcNo` = '$lcno', 
			`shipmode` = '$shipmode', 
			`scheduleETA` = '$scheduleETA', ";
		if(isset($scheduleETD)) {
            $query .= "`scheduleETD` = '$scheduleETD',";
        }
        $query .= "`mawbNo` = '$mawbNo', 
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
			`dhlTrackNo` = '$dhlTrackNo',
			`GERPVoucherNo` = '$GERPVoucherNo'
			WHERE `pono` = '$poid' AND `shipNo` = $shipno;";
        $objdal->update($query, "Could not update shipment data");
    }

    unset($objdal);

    $action = array(
        'refid' => $refId,
        'pono' => "'" . $poid . "'",
        'actionid' => action_PO_Edited_by_Buyer,
        'msg' => "'PO# " . $poid . " edited by buyer " . $user_name . "'",
        'usermsg' => "'" . $buyersEditComment . "'",
        'newstatus' => 1,
    );

    $lastAction = UpdateAction($action);

    $res["status"] = 1;
    $res["message"] = 'PO Information UPDATED';

    if ($_POST['userAction'] == 'pi_rejection_edit') {
        $res["lastaction"] = encryptId($lastAction);
    }

    return json_encode($res);
}


?>