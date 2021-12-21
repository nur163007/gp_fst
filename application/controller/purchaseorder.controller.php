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
        case 1:	// get single user info
            if(!empty($_GET["xpo"])) { echo GetNewPONumber($_GET["xpo"]); } else { echo GetNewPONumber(); };
            break;
        case 2:	// get a purchase order
            if(isset($_GET["shipno"]) && !empty($_GET["shipno"])) {
                echo GetPODetail($_GET["id"], 0, $_GET["shipno"]);
            }else{
                echo GetPODetail($_GET["id"]);
            }
            break;
        case 3:
            echo PoList();
            break;
        case 4:
            echo checkStepOver($_GET["po"], $_GET["step"]);
            break;
        case 5:
            echo checkValidNewPO($_GET['po']);
            break;
        case 6:
            echo PoList($_GET['onlypo']);
            break;
        case 7:	// get a PO for new PI
            echo GetPODetail($_GET["id"], $_GET['forpi']);
            break;
        case 8:	// get active PO buyer's list       for Drop down
            echo GetPOBuyersList();
            break;
        case 9:	// get active PO buyer's list       for Select 2
            echo GetPOBuyersSelectList();
            break;
        case 10: //Get PO stage list
            echo stageList();
            break;
        case 11: //Get PO stage list
            echo GetDumpPO();
            break;
        case 12:	// get a PO for new PI
            echo GetPODumpDetails($_GET["id"]);
            break;
        case 13:	// get a purchase order
                echo getPODumpInfo($_GET["id"]);

            break;
        default:
            break;
    }
}

function GetPOBuyersList(){

    $objdal=new dal();

    $sql = "SELECT 
            tab1.createdby AS userid,
            tab1.username, tab1.fullname,
            IFNULL(tab2.poCount, 0) AS poCount
        FROM
            (SELECT 
                p.createdby, u.username, trim(concat(u.firstname,' ' , u.lastname)) as fullname
            FROM
                wc_t_po AS p
            INNER JOIN wc_t_users AS u ON p.createdby = u.id
            GROUP BY p.createdby
            ORDER BY u.username) AS tab1
                LEFT JOIN
            (SELECT 
                username, COUNT(poid) AS poCount
            FROM
                (SELECT 
                u.username, p.poid
            FROM
                wc_t_po AS p
            INNER JOIN wc_t_action_log AS l ON p.poid = l.PO
            INNER JOIN wc_t_action AS a ON l.ActionId = a.ID
            INNER JOIN wc_t_users AS u ON p.createdby = u.id
            WHERE
                l.Status = 0 AND a.ActionPendingTo = 2
            GROUP BY u.username , p.poid) AS x
            GROUP BY x.username) AS tab2 ON tab1.username = tab2.username;";

    $objdal->read(trim($sql));

    if(!empty($objdal->data)){
        $i = 0;
        foreach($objdal->data as $val){
            $jsondata[$i] = $val;
            $i++;
        }
        $jsondata = json_encode($jsondata);
    }

    unset($objdal);
    return $jsondata;
}

/*!
 * Get specific PO details
 * */
function GetPODetail($id, $forPI=0, $shipno=0)
{
    global $user_id;
    global $loginRole;
    global $companyId;

    $objdal = new dal();
    $status = 0;
    $allStatus = array();
    $shipSql = "";

    //$response = ["status" => 0, "message" => "Invalid request access-denied"];
    if ($loginRole == role_Supplier) {
        $strQuery = $objdal->getRow("SELECT `supplier` FROM `wc_t_po` WHERE `poid` = '$id';");
        $supplier = $strQuery['supplier'];
        if ($supplier != $companyId) {
            $response = ["status" => 0, "message" => "Invalid request"];
            unset($objdal->data);
            //return json_encode($response);
            die();
        }
    }


    if($forPI==1){

        $sql = "SELECT `poid` FROM `wc_t_po` WHERE `poid` LIKE '".$id."%' ORDER BY `createdon` desc LIMIT 1;";
        $objdal->read($sql);

        if(!empty($objdal->data)){
            $id = $objdal->data[0]['poid'];
        }
        unset($objdal->data);
    }

    unset($objdal);
    $objdal = new dal();
    // get PO status collection
    $sql = "SELECT distinct `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id';";
    $objdal->read($sql);

    if(!empty($objdal->data)){

        $i = 0;
        foreach ($objdal->data as $val){
            extract($val);
            $allStatus[$i] = $ActionID;
            $i++;
        }
        //echo json_encode($allStatus);
    }

    unset($objdal->data);

    $extraWhere = ($loginRole == role_Supplier) ? " AND c2.`id` = $companyId" : "" ;

    $sql = "SELECT p.`poid`, p.`povalue`, p.`podesc`, p.`lcdesc`, p.`importAs`, c4.`name` `importAsName`, p.`supplier`, c2.`name` `supname`, p.`supplier_address` `supadd`, 
            p.`currency`, c1.`name` `curname`, p.`contractref`,p.`pr_no`,p.`department`, p.`pi_description` `pidesc`,p.`exp_type`,p.`producttype`,p.`user_justification` `user_just`, c3.`contractName` AS `contractrefName`, p.`deliverydate`, 
            p.`draftsendby`, p.`actualPoDate`, p.`emailto`, p.`emailcc`, p.`pruserto`, p.`prusercc`, p.`noflcissue`, p.`nofshipallow`, 
            p.`installbysupplier`, p.`pinum`, p.`pivalue`, p.`hscode`, p.`shipmode`, p.`pidate`, p.`basevalue`, p.`origin`, 
            p.`negobank`, p.`shipport`, p.`lcbankaddress`, p.`productiondays`, p.`buyercontact`, p.`techcontact`, 
            (SELECT `ActionID` FROM `wc_t_action_log` WHERE `PO` = '$id' ORDER BY `ID` DESC Limit 1) `status`, 
            p.`createdby`, CONCAT(u1.`firstname`,' ',u1.`lastname`) `buyersName`, u1.`email` `buyersEmail`, u1.`mobile` `buyersMobile`,
            CONCAT(u2.`firstname`,' ',u2.`lastname`) `prName`, u2.`email` `prEmail`, u2.`mobile` `prMobile`
            FROM `wc_t_po` p 
                INNER JOIN `wc_t_category` c1 ON p.`currency` = c1.`id` 
                INNER JOIN `wc_t_company` c2 ON p.`supplier` = c2.`id`
                LEFT JOIN `wc_t_users` u1 ON p.`createdby` = u1.`id`
                LEFT JOIN `wc_t_users` u2 ON p.`pruserto` = u2.`id`
                LEFT JOIN `wc_t_contract` c3 ON p.`contractref` = c3.`id`
                LEFT JOIN `wc_t_category` c4 ON p.`importAs` = c4.`id`
            WHERE p.`poid` = '$id';";
    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $podetail[0] = $objdal->data[0];
        //extract($podetail);
        //$status = $podetail[0]['status'];
    }
    //echo $status;
    // message log
    $i=0;
    unset($objdal->data);

    if($shipno!=0){
        $shipSql = " AND (pl.`shipNo` is null OR pl.`shipNo` = $shipno) ";
    }
    $sql = "SELECT pl.`PO` AS `poid`, pl.`Msg` AS `title`, pl.`UserMsg` AS `msg`, pl.`ActionBy` `msgby`, 
            u.`username`, r.`name` AS `rolename`, pl.`ActionOn` AS `msgon`, pl.`ActionID` AS `status`,  
            pl.`ActionByRole` AS `fromgroup`, a.`ActionPendingTo` AS `togroup`, r1.`name` AS `torole`, a.`stage` 
            FROM `wc_t_action_log` as pl 
                INNER JOIN `wc_t_users` as u ON pl.`ActionBy` = u.`id` 
                INNER JOIN `wc_t_roles` as r ON u.`role` = r.`id`
                INNER JOIN `wc_t_action` as a ON pl.`ActionID` = a.`ID`
                LEFT JOIN `wc_t_roles` as r1 ON a.`ActionPendingTo` = r1.`id` 
            WHERE `PO` = '$id' $shipSql;";
    //echo $sql;
    $objdal->read($sql);
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            $msg[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }
    $i=0;
    unset($objdal->data);

    // attachments
    if(in_array(action_Final_PI_Sent_for_PR_Feedback, $allStatus)){
//    if($status > action_Final_PI_Sent_for_PR_Feedback){
        $skipDraft = "  AND a2.`title` NOT LIKE 'Draft%' ";
    } else {
        $skipDraft = "";
    }
    if($shipno!=0){
        $shipSql = " AND (a2.`shipno` is null OR a2.`shipno` = $shipno) ";
    }

    $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` AS `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title` AND (a2.`grouponly` IS null OR a2.`grouponly` = $loginRole) AND a2.`poid` = '$id' $skipDraft $shipSql
             ORDER BY a2.`attachedon` DESC
             LIMIT 1)
        ORDER BY a.`attachedby`, a.`id`;";
    //echo $sql;
    $objdal->read($sql);
    if(!empty($objdal->data)) {
        foreach ($objdal->data as $val) {
            //extract($val);
            array_push($val, encryptId($val['id']));
            $attach[$i] = $val;
            $i++;
            //extract($res[1]);
        }
    }

    // LC Info and Payment Terms
    if(in_array(action_LC_Request_Sent, $allStatus) || in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
        //if($status >= action_LC_Request_Sent){

        $i=0;
        unset($objdal->data);

        $sql = "SELECT lc.`pono`, lc.`lcno`, lc.`lcvalue`, lc.`xeUSD`, lc.`xeBDT`, lc.`lcdesc`, lc.`lctype`, c4.`name` `lctypename`, 
            lc.`lcissuedate`, lc.`lcexpirydate`, lc.`lastdateofship`, 
            lc.`lcissuerbank`, c1.`name` `lcissuerbankname`, lc.`bankaccount`, c2.`name` `bankaccountname`, lc.`bankservice`, 
            lc.`serviceremark`, lc.`insurance`, c3.`name` `insurancename`, lc.`producttype`, c5.`name` `producttypename`, lc.`cocorigin`, lc.`iplbltolcbank`, lc.`delivcertify`, 
            lc.`qualitycertify`, lc.`qualitycertify1`, lc.`advshipdoc`, lc.`advshipdocwithbl`, lc.`addconfirmation`, lc.`preshipinspection`, lc.`transshipment`, 
            lc.`partship`, lc.`confchargeatapp`, lc.`otherTerms`, lc.`ircno`, lc.`imppermitno`, lc.`tinno`, lc.`vatregno`, lc.`customername`, 
            lc.`customeraddress`, lc.`paymentterms`, lc.`createdby`, lc.`createdon`, lc.`lcafno`, lc.`lca`,
            lc.`advBank`,lc.`contactPSI`,lc.`psiClauseA`,lc.`psiClauseB`,lc.`insNotification1`,lc.`insNotification2`,lc.`insNotification3`,
            lc.`forAirShipment1`,lc.`forAirShipment2`,lc.`forSeaShipment1`,lc.`forSeaShipment2`,lc.`shippingRemarks`, lc.`daysofexpiry`,
            cc.`id` `conf_id`, cc.`chargetype` `conf_chargetype`, cc.`exchangerate` `conf_exchangerate`, cc.`amount` `conf_amount`,
            cc.`vat` `conf_vat`, cc.`othercharge` `conf_othercharge`, cc.`total` `conf_total`, cc.`currency` `conf_currency`,
            (SELECT a1.`filename` FROM `wc_t_attachments` a1 WHERE a1.`poid`='$id' AND a1.`title`='LC Opening Request' ORDER BY a1.`id` DESC LIMIT 1) AS `attachLCORequest`,
            (SELECT a2.`filename` FROM `wc_t_attachments` a2 WHERE a2.`poid`='$id' AND a2.`title`='Bank Received Copy' ORDER BY a2.`id` DESC LIMIT 1) AS `attachBankReceiveCopy`,
            (SELECT a3.`filename` FROM `wc_t_attachments` a3 WHERE a3.`poid`='$id' AND a3.`title`='LC Opening Other Files' ORDER BY a3.`id` DESC LIMIT 1) AS `attachLCOOther`,
            (SELECT a4.`filename` FROM `wc_t_attachments` a4 WHERE a4.`poid`='$id' AND a4.`title`='Confirmation Charge Advice' ORDER BY a4.`id` DESC LIMIT 1) AS `attachConfChargeAdv`,
            (SELECT a5.`filename` FROM `wc_t_attachments` a5 WHERE a5.`poid`='$id' AND a5.`title`='Final LC Copy' ORDER BY a5.`id` DESC LIMIT 1) AS `attachFinalLC`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_bank_insurance` c1 ON lc.`lcissuerbank` = c1.`id` 
                LEFT JOIN `wc_t_bank_insurance` c2 ON lc.`bankaccount` = c2.`id` 
                LEFT JOIN `wc_t_bank_insurance` c3 ON lc.`insurance` = c3.`id` 
                LEFT JOIN `wc_t_confirmation_charge` cc ON lc.`lcno` = cc.`lcno` 
                LEFT JOIN `wc_t_category` c4 ON lc.`lctype` = c4.`id`
                LEFT JOIN `wc_t_category` c5 ON lc.`producttype` = c5.`id`
            WHERE lc.`pono` = '$id';";
        //echo $sql;
        $objdal->read($sql);

        if(!empty($objdal->data)){
            $lcrequest[0] = $objdal->data[0];
        }

        $i=0;
        unset($objdal->data);

        /*!
         * Query for payment terms
         * ***************************************/
        $sql = "SELECT 
                      t.`id`, t.`pono`, t.`partname` AS `ccId`, t.`maturityterms` AS `termsText`, c1.`name` `partname`, t.`percentage`, 
                      t.`dayofmaturity`, c2.`name` AS `maturityterms`, t.`cacFacDay`, t.`cacFacText`
                FROM `wc_t_payment_terms` t 
                    INNER JOIN `wc_t_category` c1 ON c1.`id` = t.`partname` 
                    INNER JOIN `wc_t_category` c2 ON c2.`id` = t.`maturityterms` 
                WHERE t.`pono` = '$id';";
        //echo $sql;
        $objdal->read($sql);
        if(!empty($objdal->data)){
            foreach($objdal->data as $val){
                $pterms[$i] = $val;
                $i++;
            }
        }
    }

    unset($objdal);

    if(in_array(action_LC_Request_Sent, $allStatus) ||  in_array(action_Sent_Revised_LC_Request_1, $allStatus)){
//    if($status >= action_LC_Request_Sent){
        return json_encode(array($podetail,$msg,$attach,$lcrequest,$pterms));
    } else {
        return json_encode(array($podetail,$msg,$attach));
    }
}

function GetNewPONumber($expo=''){

    $objdal = new dal();
    if($expo==''){
        //$sql = "SELECT CONCAT(CONVERT((MAX(CONVERT(SUBSTR(`poid`,1,INSTR(poid, 'P')-1),UNSIGNED INTEGER))+1), 
//            CHAR(9)), 'P01') AS num FROM `wc_t_po`;";
        $sql = "SELECT CONVERT(IFNULL(MAX(`poid`),300004000),UNSIGNED INTEGER)+1 AS `num` FROM `wc_t_po` ;";
        $objdal->read($sql);
        $res = $objdal->data[0]['num'];
    } else {
        $sql = "SELECT MAX(CONVERT(SUBSTR(`poid`,INSTR(`poid`,'P')+1), UNSIGNED INTEGER))+1 ship 
            FROM `wc_t_po` WHERE poid like '".$expo."%'";
        $objdal->read($sql);
        $lastShip = $objdal->data[0]['ship'];
        if(strlen($lastShip)<2){
            $res = $expo.'S0'.$lastShip;
        } else{
            $res = $expo.'S'.$lastShip;
        }
    }

    unset($objdal);
    return $res;
}

function checkValidNewPO($po){

    $objdal = new dal();
    $query = "SELECT COUNT(poid) valid FROM wc_t_po WHERE SUBSTR(`poid`,1,INSTR(poid, 'P')-1)='$po';";

    $objdal->read($query);
    $res = $objdal->data[0]['valid'];

    unset($objdal);
    return $res;
}

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo SubmitPO();
    }
}

// Insert
function SubmitPO()
{
    global $user_id;
    global $loginRole;
    $objdal = new dal();

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    //return $refId;

    $oldPoid = $objdal->sanitizeInput($_POST['poid']);
    $originalPOnum = $objdal->sanitizeInput($_POST['poid1']);
    $poid = $objdal->sanitizeInput($_POST['poid1'].$_POST['pino']);
    $povalue = $objdal->sanitizeInput($_POST['povalue']);
    $povalue = str_replace(",","", $povalue);

    $podesc = $objdal->sanitizeInput($_POST['podesc']);
    $podesc = replaceTextRegex($podesc);

    $importAs = $objdal->sanitizeInput($_POST['importAs']);
    $supplier = $objdal->sanitizeInput($_POST['supplier']);
    $currency = $objdal->sanitizeInput($_POST['currency']);
    $contractref = $objdal->sanitizeInput($_POST['contractref']);
    $deliverydate = $objdal->sanitizeInput($_POST['deliverydate']);
    $deliverydate = date('Y-m-d', strtotime($deliverydate));
    $draftsendby = $objdal->sanitizeInput($_POST['draftsendby']);
    $draftsendby = date('Y-m-d', strtotime($draftsendby));
    $actualPoDate = $objdal->sanitizeInput($_POST['actualPoDate']);
    $actualPoDate = date('Y-m-d', strtotime($actualPoDate));

    $pruserto = $objdal->sanitizeInput($_POST['prUserEmailTo']);

    $prusercc = '';
    if(isset($_POST['prUserEmailCC'])){
        foreach($_POST['prUserEmailCC'] as $val) {
            if(strlen($prusercc)>0){$prusercc .= ',';}
            $prusercc.= $objdal->sanitizeInput($val);
        }
    }

    $emailto = $objdal->sanitizeInput($_POST['emailto']);
    $emailcc = $objdal->sanitizeInput($_POST['emailcc']);
    $noflcissue = $objdal->sanitizeInput($_POST['noflcissue']);
    $nofshipallow = $objdal->sanitizeInput($_POST['nofshipallow']);
    //if(!isset($_POST['installbysupplier'])){ $installbysupplier = 0; } else{ $installbysupplier = 1; };
    $installbysupplier = $objdal->sanitizeInput($_POST['installBy']);

    $buyersmessage = $objdal->sanitizeInput($_POST['buyersmessage']);

    // attachment data in an 3D array
    $attachpo = $objdal->sanitizeInput($_POST['attachpo']);
    $attachboq = $objdal->sanitizeInput($_POST['attachboq']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);

    $pr_no = $objdal->sanitizeInput($_POST['prno']);
    $department = $objdal->sanitizeInput($_POST['dept']);
    $sup_address = $objdal->sanitizeInput($_POST['supplier_address']);
    $ip = $_SERVER['REMOTE_ADDR'];

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed create new PO';
    //------------------------------------------------------------------------------
    /*echo '<pre>';
        var_dump($_POST);
    echo '</pre>';*/
    if($oldPoid==""){
        // insert new po
        $query = "INSERT INTO `wc_t_po` SET 
    		`poid` = '".replaceRegex($poid)."',
    		`originalPONum` = $originalPOnum,
            `povalue` = $povalue, 
            `podesc` = '$podesc',
            `importAs` = $importAs,
            `supplier` = $supplier, 
            `currency` = $currency, 
            `contractref` = '$contractref', 
            `deliverydate` = '$deliverydate', 
            `draftsendby` = '$draftsendby', 
            `actualPoDate` = '$actualPoDate', 
            `emailto` = '$emailto', 
            `emailcc` = '$emailcc', 
            `pruserto` = '$pruserto', 
            `prusercc` = '$prusercc', 
            `noflcissue` = $noflcissue, 
            `nofshipallow` = $nofshipallow, 
            `installbysupplier` = $installbysupplier, 
            `createdby` = $user_id, 
    		`createdfrom` = '$ip',
    		`pr_no` = $pr_no,
    		`department` = '$department',
    		`supplier_address` = '$sup_address';";
        //echo $query;
        //die();
        $objdal->insert($query);

    } else{
        // Update existing PO
        $query = "UPDATE `wc_t_po` SET 
    		`povalue` = $povalue, 
            `podesc` = '$podesc',
            `importAs` = $importAs, 
            `supplier` = $supplier, 
            `currency` = $currency, 
            `contractref` = '$contractref', 
            `deliverydate` = '$deliverydate', 
            `draftsendby` = '$draftsendby', 
            `actualPoDate` = '$actualPoDate', 
            `emailto` = '$emailto', 
            `emailcc` = '$emailcc', 
            `pruserto` = '$pruserto', 
            `prusercc` = '$prusercc', 
            `noflcissue` = $noflcissue, 
            `nofshipallow` = $nofshipallow, 
            `installbysupplier` = $installbysupplier, 
            `modifiedby` = $user_id, 
    		`modifiedfrom` = '$ip',
    		`pr_no` = $pr_no,
    		`department` = '$department',
    		`supplier_address` = '$sup_address'
            WHERE `poid` = '$poid';";
        $objdal->update($query);
        //echo($query);
    }

    // Getting PR user's cc email address
    $emails = '';
    $prcc = explode(',', $prusercc);
    for($i=0; $i<count($prcc); $i++){
        $query = "SELECT `email` FROM `wc_t_users` WHERE `id` IN ($prusercc);";
        $objdal->read($query);
        if(!empty($objdal->data)){
            foreach($objdal->data as $val){
                extract($val);
                if($emails!="") { $emails .= ','; }
                $emails .= $email;
            }
        }
    }

    // Action Log --------------------------------//

    if($emailcc!=""){
        $allemails = $emailcc.','.$emails;
    }else{
        $allemails = $emails;
    }

    if($oldPoid==""){
        $action = array(
            'pono' => "'".$poid."'",
            'actionid' => action_New_PO_Initiated,
            'msg' => "'New PO initiated. PO# ".$poid."'",
            'usermsg' => "'".$buyersmessage."'",
            'mailcc' => $allemails,
        );
    } else {
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Revised_PO_Sent,
            'status' => 1,
            'msg' => "'Revised PO# ".$poid." Sent'",
            'usermsg' => "'".$buyersmessage."'",
            'mailcc' => $allemails,
        );
    }

    $res["message"] = 'Failed to create action log';
    UpdateAction($action);
    // End Action Log -----------------------------

    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$poid', 'PO', '$attachpo', $user_id, '$ip', $loginRole),
        ('$poid', 'BOQ', '$attachboq', $user_id, '$ip', $loginRole)";

    if($attachother!=''){
        $query .= ",('$poid', 'Other PO Doc', '$attachother', $user_id, '$ip', $loginRole);";
    }
    $objdal->insert($query);
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to move attachments';
    fileTransferTempToDocs($poid);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'PO Submitted Successfully';
    return json_encode($res);
}

function PoList($onlyPO=0)
{

    $objdal=new dal();
    if($onlyPO==0){
        $sql = "SELECT `poid` FROM `wc_t_po` ORDER BY `poid`;";
    } else {
        $sql = "SELECT DISTINCT SUBSTR(`poid`,1,INSTR(poid, 'P')-1) `poid` 
            FROM `wc_t_po` 
            GROUP BY SUBSTR(`poid`,1,INSTR(poid, 'P')-1)
            ORDER BY SUBSTR(`poid`,1,INSTR(poid, 'P')-1);";
    }
    $objdal->read($sql);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$poid.'", "text": "'.$poid.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;
}

/*!
 * Get PO status list
 * Added by: Hasan Masud
 * Added on: 2020-11-03
 * ********************************/
function stageList()
{
    $objdal = new dal();

    $sql = "SELECT DISTINCT `stage`, `serialNo` FROM `wc_t_action` WHERE NOT `stage` IS NULL ORDER BY `serialNo`;";
    $objdal->read(trim($sql));
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $jsondata .= ', {"id": "' . $row["stage"] . '", "text": "' . $row["stage"] . '"}';
        }
        $jsondata .= ', {"id": "' . 0 . '", "text": "' . 'All' . '"}';
    }
    $jsondata .= ']';

    unset($objdal);
    return $jsondata;
}

function GetDumpPO()
{
    $objdal = new dal();
    $strQuery="SELECT
            DISTINCT pd.poNo
        FROM
            `wc_t_po` po
        RIGHT JOIN wc_t_po_dump pd ON
            po.`originalPONum` = pd.poNo
            WHERE po.`originalPONum` IS NULL;";
    $objdal->read($strQuery);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$poNo.'", "text": "'.$poNo.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;

}

function GetPODumpDetails($id, $shipno=0)
{
    $objdal=new dal();

    $sql = "SELECT `supplierId` FROM `wc_t_po_dump` WHERE `poNo` = $id";
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }

    unset($objdal->data);

    //$sql = "SELECT `id`, `name` FROM `wc_t_category` WHERE `id` IN ($contractRef);";
    $sql = "SELECT `id`, `name` FROM `wc_t_company` WHERE `id` IN ($supplierId);";

    $objdal->read($sql);

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

/*!
 * Get specific PO details
 * */
function getPODumpInfo($id)
{
    global $user_id;
    global $loginRole;

    $objdal = new dal();

    $sql = "SELECT p.`poNo`, p.`POAmount`, p.`poDesc`, p.`itemDesc`, p.`currency`,DATE_FORMAT(p.`poDate`, '%M %d, %Y') as `poDate`, DATE_FORMAT(p.`needByDate`, '%M %d, %Y') as`needByDate`,p.`PRUserDept`,p.`supplierId`,p.`ContractNo`
            FROM `wc_t_po_dump` p 
            WHERE p.`poNo` = '$id' LIMIT 1;";
    //echo $sql;
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $podetail[0] = $objdal->data[0];
        //extract($podetail);
        //$status = $podetail[0]['status'];
    }
    //echo $status;
    // message log

    unset($objdal);

        return json_encode(array($podetail));

}
?>

