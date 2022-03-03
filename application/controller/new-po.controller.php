<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author      : A'qa Technology
    Created By  : Shohel Iqbal
    Created on  : 30.2021
    Purpose     : New PO creation
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

/*
 * GET Calls
 */
if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// Get PO detail from PO Dump
            echo GetPODetailFromDump($_GET["id"]);
            break;
        case 2:
            echo GetSuppliersPOList();
            break;
        case 3:
            if(isset($_GET["pono"]) || !empty($_GET["pono"])) {
                echo GetIssuesPODetail($_GET["pono"]);
            }
            break;
        case 4:
            if(isset($_GET["pono"]) || !empty($_GET["pono"])) {
                echo GetIssuedPOLines($_GET["pono"]);
            }
            break;
        case 5:
            if(isset($_GET["pono"]) || !empty($_GET["pono"])) {
                echo GetStatusBaaedPOLines($_GET["pono"]);
            }
            break;
        case 6:
            if(isset($_GET["pono"]) || !empty($_GET["pono"])) {
                echo GetSubmittedPIDetail($_GET["pono"]);
            }
            break;
        default:
            break;
    }
}

/*
 * POST calls
 */
if (!empty($_POST)){
    switch($_POST["action"])
    {
        case 1:
            if(!empty($_POST["poNo"]) || isset($_POST["poNo"])){
                echo SubmitPO();
            }
            break;
        default:
            break;
    }

}

/*--------------------------------------------------------------
 * Get PO detail from PO Dump
 */
function GetPODetailFromDump($id)
{
    global $user_id;
    global $loginRole;

    $objdal = new dal();

    $sql = "SELECT p.`poNo`, p.`POAmount`, p.`poDesc`, p.`itemDesc`, p.`currency`, ct.`id` AS `currencyId`,
            DATE_FORMAT(p.`poDate`, '%M %d, %Y') AS `poDate`, DATE_FORMAT(p.`needByDate`, '%M %d, %Y') AS `needByDate`,
            u1.`firstname` AS `PRUser`, u1.`id` AS `PRUserId`, p.`prNo`,
            p.`PRUserDept`,p.`supplierId`, p.`supplier`, p.`ContractNo`, cn.id `contractId`
            FROM `wc_t_po_dump` p 
                LEFT JOIN `wc_t_users` u1 ON p.`PRUser` = u1.`CoupaMatch`
                LEFT JOIN `wc_t_contract` cn ON p.ContractNo = cn.contractName 
                LEFT JOIN `wc_t_category` ct ON p.`currency` = ct.`name`
            WHERE p.`poNo` = '$id' LIMIT 1;";
//    wc_t_contract
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $podetail[0] = $objdal->data[0];
    }

    unset($objdal);

    return json_encode(array($podetail));

}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Get PO detail from issued PO Table
 */
function GetIssuesPODetail($id)
{
    global $user_id;
    global $loginRole;

    /*
     * TODO
     * need to check vendors unauthorized access through URL
     */
    $objdal = new dal();

    $sql = "SELECT p.`poNo`, p.`prNo`, p.`poValue`, (p.`poValue`-IFNULL((SELECT SUM(`pivalue`) FROM wc_t_pi WHERE PONum = p.`poNo`),0))  AS `validPIVal`, 
			(SELECT count(*)+1 `PIReqNo` FROM wc_t_pi where PONum = p.`poNo`) `piReqNo`,
            p.`poDesc`, p.`currency`, ct.`name` AS `currencyName`, DATE_FORMAT(p.`actualPoDate`, '%M %d, %Y') AS `poDate`, 
            DATE_FORMAT(p.`deliveryDate`, '%M %d, %Y') AS `deliveryDate`, u1.`firstname` AS `PRUser`, u1.`id` AS `PRUserId`, 
            p.`department`, p.`createdBy`, u2.`firstname` AS `buyersName`, p.`supplier`, p.`supplierAddress`, co.`name` as `supplierName`, 
            p.`contractRef`, cn.`contractName`, p.`installBy`, p.`nofLcIssue`, p.`nofShipAllow`            
            FROM `po` p 
                LEFT JOIN `wc_t_users` u1 ON p.`prUser` = u1.`id`
                LEFT JOIN `wc_t_users` u2 ON p.`createdBy` = u2.`id`
                LEFT JOIN `wc_t_contract` cn ON p.`contractRef` = cn.`id`
                LEFT JOIN `wc_t_category` ct ON p.`currency` = ct.`id`
                LEFT JOIN `wc_t_company` co ON p.`supplier` = co.`id`
            WHERE p.`poNo` = '$id' LIMIT 1;";
//    wc_t_contract
    $objdal->getRow($sql);

    if(!empty($objdal->data)){
        $podetail = $objdal->data;
    }

    unset($objdal);

    return json_encode(array($podetail));

}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Get PO detail from issued PO Table
 */
function GetSubmittedPIDetail($id)
{
    global $user_id;
    global $loginRole;

    /*
     * TODO
     * need to check vendors unauthorized access through URL
     */
    $objdal = new dal();

    $sql = "SELECT p.`poid` `poNo`, p.`pr_no` `prNo`, p.`poValue`, (p.`poValue`-IFNULL((SELECT SUM(`pivalue`) FROM wc_t_pi WHERE PONum = p.`poid`),0))  AS `validPIVal`, 
			p.`piReqNo`,
            p.`poDesc`, p.`currency`, ct.`name` AS `currencyName`, DATE_FORMAT(p.`actualPoDate`, '%M %d, %Y') AS `poDate`, 
            DATE_FORMAT(p.`deliveryDate`, '%M %d, %Y') AS `deliveryDate`, u1.`firstname` AS `PRUser`, u1.`id` AS `PRUserId`, 
            p.`department`, p.`createdBy`, u2.`firstname` AS `buyersName`, p.`supplier`, p.`supplier_address` `supplierAddress`, co.`name` as `supplierName`, 
            p.`contractRef`, cn.`contractName`, p.`installbysupplier` `installBy`, p.`noflcissue` `nofLcIssue`, p.`nofshipallow` `nofShipAllow`,
            p.`pinum`, p.`pivalue`, p.`pi_description` `pidesc`, p.`shipmode`, p.`hscode`, p.`negobank`, p.`shipport`, p.`lcbankaddress`, p.`productiondays`,
            p.`pidate`, p.`basevalue`, p.`producttype`, p.`origin`, p.`importAs`
            FROM `wc_t_pi` p 
                LEFT JOIN `wc_t_users` u1 ON p.`pruserto` = u1.`id`
                LEFT JOIN `wc_t_users` u2 ON p.`createdBy` = u2.`id`
                LEFT JOIN `wc_t_contract` cn ON p.`contractRef` = cn.`id`
                LEFT JOIN `wc_t_category` ct ON p.`currency` = ct.`id`
                LEFT JOIN `wc_t_company` co ON p.`supplier` = co.`id`
            WHERE p.`poid` = '$id' LIMIT 1;";
//    wc_t_contract
    $objdal->getRow($sql);

    if(!empty($objdal->data)){
        $podetail = $objdal->data;
    }

    // message log
    $i=0;
    unset($objdal->data);

    $sql = "SELECT pl.`PO` AS `poid`, pl.`Msg` AS `title`, pl.`UserMsg` AS `msg`, pl.`ActionBy` `msgby`, 
            u.`username`, r.`name` AS `rolename`, pl.`ActionOn` AS `msgon`, pl.`ActionID` AS `status`,  
            pl.`ActionByRole` AS `fromgroup`, a.`ActionPendingTo` AS `togroup`, r1.`name` AS `torole`, a.`stage` 
            FROM `wc_t_action_log` as pl 
                INNER JOIN `wc_t_users` as u ON pl.`ActionBy` = u.`id` 
                INNER JOIN `wc_t_roles` as r ON u.`role` = r.`id`
                INNER JOIN `wc_t_action` as a ON pl.`ActionID` = a.`ID`
                LEFT JOIN `wc_t_roles` as r1 ON a.`ActionPendingTo` = r1.`id` 
            WHERE `PO` = '$id';";
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
    $sql = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` AS `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title` AND (a2.`grouponly` IS null OR a2.`grouponly` = $loginRole) AND a2.`poid` = '$id'
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

    return json_encode(array($podetail,$msg,$attach));

}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Submit new PO by Buyer
 */
function SubmitPO()
{
    global $user_id;
    global $loginRole;
    $objdal = new dal();

    /*$refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }*/
    //return $refId;

    /*echo '<pre>';
        var_dump($_POST);
        exit();
    echo '</pre>';*/

    $poNo = $objdal->sanitizeInput($_POST['poNo']);

    $poValue = $objdal->sanitizeInput($_POST['poValue']);
    $poValue = str_replace(",","", $poValue);

    $poDesc = $objdal->sanitizeInput($_POST['poDesc']);
    $poDesc = replaceTextRegex($poDesc);
    $supplier = $objdal->sanitizeInput($_POST['supplier']);
    $currency = $objdal->sanitizeInput($_POST['currency']);
    $contractRef = $objdal->sanitizeInput($_POST['contractRef']);

    $deliveryDate = $objdal->sanitizeInput($_POST['deliveryDate']);
    $deliveryDate = date('Y-m-d', strtotime($deliveryDate));

    $draftSendBy = $objdal->sanitizeInput($_POST['draftSendBy']);
    $draftSendBy = date('Y-m-d', strtotime($draftSendBy));

    $actualPoDate = $objdal->sanitizeInput($_POST['actualPoDate']);
    $actualPoDate = date('Y-m-d', strtotime($actualPoDate));

    $prUser = $objdal->sanitizeInput($_POST['prUser']);

    $prUserCc = '';
    if(isset($_POST['prUserCc'])){
        foreach($_POST['prUserCc'] as $val) {
            if(strlen($prUserCc)>0){$prUserCc .= ',';}
            $prUserCc.= $objdal->sanitizeInput($val);
        }
    }

    $supplierEmailTo = $objdal->sanitizeInput($_POST['supplierEmailTo']);
    $supplierEmailCc = $objdal->sanitizeInput($_POST['supplieremailCc']);
    $supplierAddress = $objdal->sanitizeInput($_POST['supplierAddress']);
    //$nofLcIssue = $objdal->sanitizeInput($_POST['nofLcIssue']);
    //$nofShipAllow = $objdal->sanitizeInput($_POST['nofShipAllow']);
    //$installBy = $objdal->sanitizeInput($_POST['installBy']);

    $buyersMessage = $objdal->sanitizeInput($_POST['buyersMessage']);

    // attachment data in an 3D array
    /*$attachpo = $objdal->sanitizeInput($_POST['attachpo']);
    $attachboq = $objdal->sanitizeInput($_POST['attachboq']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);*/

    $prNo = $objdal->sanitizeInput($_POST['prNo']);
    $department = $objdal->sanitizeInput($_POST['department']);
    $ip = $_SERVER['REMOTE_ADDR'];

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed create new PO';
    //------------------------------------------------------------------------------

    $query = "INSERT INTO `po` SET 
    		`poNo` = '$poNo',
            `povalue` = $poValue, 
            `poDesc` = '$poDesc',
            `supplier` = $supplier, 
            `currency` = $currency, 
            `contractRef` = '$contractRef', 
            `deliveryDate` = '$deliveryDate', 
            `draftSendBy` = '$draftSendBy', 
            `actualPoDate` = '$actualPoDate', 
            `supplierEmailTo` = '$supplierEmailTo', 
            `supplierEmailCc` = '$supplierEmailCc',
    		`supplierAddress` = '$supplierAddress',
    		`prNo` = $prNo,
            `prUser` = '$prUser', 
            `prUserCc` = '$prUserCc',
    		`department` = '$department',  
            `createdBy` = $user_id, 
    		`createdFrom` = '$ip';";
//        echo $query;
//        die();
    $objdal->insert($query);

    /*-------------------------------------------------------------
     * PO Line insert
     *------------------------------------------------------------*/

    $sqlLines = "INSERT INTO `po_lines` (
        `id`, `poNo`, `lineNo`, `itemCode`, `itemDesc`, `poDate`, `uom`, `unitPrice`, `poQty`, `poTotal`
        )
        SELECT 
        null, `poNo`, `lineNo`, `itemCode`, `itemDesc`, `poDate`, `uom`, `unitPrice`, `poQty`, `poTotal` 
        FROM `wc_t_po_dump` 
        WHERE `poNo` = '$poNo'";

    //echo $sqlLines;
    $objdal->insert($sqlLines);
    //----------------------------End PO Line insert-------------------/

    // Getting PR user's cc email address
    $emails = '';
    $prcc = explode(',', $prUserCc);
    for($i=0; $i<count($prcc); $i++){
        $query = "SELECT `email` FROM `wc_t_users` WHERE `id` IN ($prUserCc);";
//        echo $query;
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
    if($supplierEmailCc!=""){
        $allemails = $supplierEmailCc.','.$emails;
    }else{
        $allemails = $emails;
    }

    unset($objdal);

    $action = array(
        'pono' => "'".$poNo."'",
        'actionid' => action_New_PO_Issued,
        'msg' => "'New PO issued. PO# ".$poNo."'",
        'usermsg' => "'".$buyersMessage."'",
        'mailcc' => $allemails,
    );

    UpdateAction($action);
    // End Action Log -----------------------------

    $res["status"] = 1;
    $res["message"] = 'PO Submitted Successfully';
    return json_encode($res);
}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Supplier wise PO list with action log reference
 * In front end it has to split to take the PO number
*/
function GetSuppliersPOList(){

    global $companyId;

    $objdal = new dal();
    $sql = "SELECT p1.`poNo`, al.`ID` `refId` 
            FROM `po` as p1 INNER JOIN `wc_t_action_log` al ON p1.`poNo` = al.`PO` AND al.`ActionId` = 1
            WHERE p1.supplier = $companyId and (select count(pl.`poNo`) from `po_lines` as pl where pl.poNo = p1.poNo and pl.`Status`=0)>1;";
    $objdal->read($sql);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$poNo.'|'.encryptId($refId).'", "text": "'.$poNo.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;

}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Original PO lines from po-lines table
*/
function GetIssuedPOLines($pono)
{
    $objdal = new dal();

    $sql = "SELECT `id`, `poNo`, `lineNo`, `itemCode`, `itemDesc`, `poDate`, `needByDate`, `uom`, 
		    `unitPrice`, `poQty`, `poTotal`, `delivQty`, `delivTotal`, `status`, `closeStatus`
            FROM `po_lines` p 
            WHERE p.`poNo` = '$pono';";
    //echo $sql;
    $objdal->read($sql);

    $poLines = [];
    if(!empty($objdal->data)){
        $poLines[0] = $objdal->data[0];
    }
    unset($objdal);

    return json_encode(array($poLines));

}
/*-------------------------------------------------------------*/
/*--------------------------------------------------------------
 * Delivered and Non-Delivered PO lines
*/
function GetStatusBaaedPOLines($pono){
//var_dump("ok");
    $objdal = new dal();
    if(strpos($pono, 'PI', 1)>1){
        $strCriteria = "pil.`poNo`";
    } else {
        $strCriteria = "pol.`poNo`";
    }
    /*!
     * Query for Delivered PO Lines
     * **********************************/
    $sql = "SELECT 
            pol.`id`,
            pol.`poNo`,
            DATE_FORMAT(po.`deliveryDate`, '%Y-%m-%d') `deliveryDate`,
            pol.`itemCode`,
            REPLACE(pol.`itemDesc`, CHAR(194), '') AS `itemDesc`,
            po.`currency`,
            pol.`lineNo`,
            pol.`uom`,
            pol.`unitPrice`,
            pol.`poQty`,
            pol.`poTotal`,
            pol.`status`,
            IFNULL(SUM(pil.`delivQty`),0) AS `delivQty`,
            IFNULL(SUM(pil.`delivTotal`),0) AS `delivTotal`,
            pol.`poQty` - IFNULL(SUM(pil.`delivQty`),0) AS `delivQtyValid`,
            ROUND(pol.`poTotal` - IFNULL(SUM(pil.`delivTotal`),0), 2) AS `delivAmountValid`,
            concat('PI', pil.`PIReqNo`) `PIReqNo`            
        FROM
            `po` AS po
                INNER JOIN
            `po_lines` AS pol ON po.poNo = pol.poNo
                LEFT JOIN
            `pi_lines` pil ON (pol.`poNo` = pil.`buyersPo`
                AND pol.`lineNo` = pil.`lineNo`)
        WHERE
            ".$strCriteria." = '$pono' AND (pol.`status` = 1 OR pol.`poQty` > pil.`delivQty`)
        GROUP BY pol.`id`, pol.`poNo`, po.`deliveryDate`, pol.`itemCode`, pol.`itemDesc`, po.`currency`, 
            pol.`lineNo`, pol.`uom`, pol.`unitPrice`, pol.`poQty`, pol.`poTotal`, pol.`status`, pil.`PIReqNo`;";
    //echo $sql;
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $delivered = $objdal->data;
    } else {
        $delivered = array();
    }
    unset($objdal);

    /*!
     * Query for non Delivered PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            pol.`id`,
            pol.`poNo`,
            DATE_FORMAT(po.`deliveryDate`, '%Y-%m-%d') `deliveryDate`,
            pol.`itemCode`,
            REPLACE(pol.`itemDesc`, CHAR(194), '') AS `itemDesc`,
            po.`currency`,
            pol.`lineNo`,
            pol.`uom`,
            pol.`unitPrice`,
            pol.`poQty`,
            pol.`poTotal`,
            pol.`status`,
            IFNULL(SUM(pil.`delivQty`),0) AS `delivQty`,
            IFNULL(SUM(pil.`delivTotal`),0) AS `delivTotal`,
            pol.`poQty` - IFNULL(SUM(pil.`delivQty`),0) AS `delivQtyValid`,
            ROUND(pol.`poTotal` - IFNULL(SUM(pil.`delivTotal`),0), 2) AS `delivAmountValid`
        FROM
            `po` AS po
                INNER JOIN
            `po_lines` AS pol ON po.poNo = pol.poNo
                LEFT JOIN
            `pi_lines` pil ON (pol.`poNo` = pil.`buyersPo`
                AND pol.`lineNo` = pil.`lineNo`)
        WHERE
            ".$strCriteria." = '$pono' AND pol.`status` = 0
        GROUP BY pol.`id`, pol.`poNo`, po.`deliveryDate`, pol.`itemCode`, pol.`itemDesc`, po.`currency`, 
            pol.`lineNo`, pol.`uom`, pol.`unitPrice`, pol.`poQty`, pol.`poTotal`, pol.`status`;";
//    echo $sql;
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $nondelivered = $objdal->data;
    } else {
        $nondelivered = array();
    }

    unset($objdal);

    /*!
     * Query for rejected PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            `poNo`, GROUP_CONCAT(`lineNo`) AS `rejectedlines`
        FROM
            `pi_lines`
        WHERE
            `poNo` = '$pono' AND `status` = 1
        GROUP BY `poNo`";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $rej = $objdal->data;
    } else {
        $rej = array();
    }

    $json = json_encode(array($delivered, $nondelivered, $rej));
    /*if (!empty($objdal->data)) {
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    $json = json_encode($res);*/
    return $json;

}
/*-------------------------------------------------------------*/