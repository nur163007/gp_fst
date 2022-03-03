<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 29-Sep-18
 * Time: 12:17 PM
 */
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:
            echo GetFilteredPOList($_GET["supplier"]);
            break;
        case 2:
            echo getShipList($_GET["po"]);
            break;
        case 3:
            echo GetTacInfo($_GET["po"],$_GET["shipNo"]);
            break;
        case 4:
            echo getCIList($_GET["po"]);
            break;
        case 5:
            echo getDocTypevalue($_GET["po"], $_GET["ciNo"]);
            break;
        case 6:
            echo tacPdfGenerate($_GET["poNo"], $_GET["shipNo"], $_GET["partName"]);
            break;
        case 7:
            echo getCFacValueByShip();
            break;
        case 8:
            echo cfacPdfGenerate($_GET["po"], $_GET["ship"], $_GET["partName"]);
            break;
        case 9:
            echo checkCPOApprove();
            break;
        case 10:
            echo getCpoApprovedPO($_GET["user"]);
            break;
        case 11:
            echo getPayHistory($_GET["ciNo"]);
            break;
        case 12:
            echo checkStepOver($_GET["pono"], $_GET["actionName"] ,$_GET["ship"]);
            break;
        case 13:
            echo getLetterText($_GET["pono"],$_GET["ship"], $_GET["partName"], $_GET["lastActId"]);
            break;
        case 14:
            echo getCertName($_GET["poNo"]);
            break;
        case 15:
            echo checkPamntStatus($_GET["poNo"],$_GET["shipNo"]);
            break;
        case 16:
            echo getCertsPayHistory($_GET["poNo"]);
            break;
        case 17:
            echo getRequestData($_GET["poNo"],$_GET["shipNo"], $_GET["partName"]);
            break;

    }
}

/*INSERT REQUEST FORM*/
if (!empty($_POST)){

    if (!empty($_POST["reqId"]) || isset($_POST["reqId"])){
        echo json_encode(saveReqData());
    }

}

// Insert
function saveReqData()
{
    global $user_id;
    /*echo "<pre>";
        print_r($_POST);
    echo "</pre>";*/
    $lastActionId = $_POST["lastActionId"];
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $objdal = new dal();

    $po = $objdal->sanitizeInput($_POST['po']);
    $ciNo = $objdal->sanitizeInput($_POST['ciNo']);
    $LcNo = $objdal->sanitizeInput($_POST['LcNo']);
    $shipNo = $objdal->sanitizeInput($_POST['shipno']);
    if(isset($_POST['certReqId'])){
        $certReqId = $objdal->sanitizeInput($_POST['certReqId']);
    }else{
        $certReqId = 0;
    }
    $ciValue = $objdal->sanitizeInput($_POST['ciValue']);
    $ciValue = str_replace(",", "", $ciValue);
    $ciDesc = $objdal->sanitizeInput($_POST['ciDesc']);
    $ciQty = $objdal->sanitizeInput($_POST["ciQty"]);

    $valueOfDoc = $objdal->sanitizeInput($_POST['valueOfDoc']);
    $valueOfDoc = str_replace(",", "", $valueOfDoc);
    $partName = $objdal->sanitizeInput($_POST['partName']);
    $action = $objdal->sanitizeInput($_POST['action']);
    if(isset($_POST['certFinalApprover'])){
        $certFinalApprover = $objdal->sanitizeInput($_POST['certFinalApprover']);
    }else{
        $certFinalApprover = 'NULL';
    }

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'Failed to process the request';
    //------------------------------------------------------------------------------

    if(isset($_POST['cfacCertificateText'])){
        $cfacCertificateText = htmlspecialchars($_POST['cfacCertificateText'],ENT_QUOTES, "ISO-8859-1");
    }else{
        $cfacCertificateText = '';
    }

    $cfacCertificateText = stripslashes($cfacCertificateText);
    $cfacCertificateText = $objdal->real_escape_string($cfacCertificateText);
    $cfacLetterBody = $cfacCertificateText;

    if($lastActionId==action_TAC_Approved_by_CPO) {
        //Step 4 Supplier ->Buyer

        $newActionID = action_TAC_Request_Send_by_Supplier;
        $msg = "'TAC request forwarded by Buyer against PO# ".$po." and Shipment# ".$shipNo."'";
        $usermsg = 'NULL';
        $status = 1;
        $newstatus = 1;

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else if($lastActionId==action_TAC_Approved_by_Buyer) {
        //Step 4 Supplier ->Buyer

        $certIssueDate = date("Y-m-d H:i:s");
        if($action == 1) {
            $newActionID = action_TAC_Approved_by_CPO;
            $msg = "'TAC request Approved by CPO against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$cfacLetterBody'";
            $status = 1;
            $newstatus = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET `cfacLetterBody` = '$cfacLetterBody', `cfacIssueDate` = '$certIssueDate' 
                      WHERE `poNo` = '$po' AND `shipNo` = '$shipNo' AND `partName` = $partName;";
            $objdal->update($query);

            /*!
             * 1. Get Buyer & Supplier email address
             * 2. Send certificate download mail to them
             * ************************************************/
            $queryE = "SELECT ub.`email` AS `emailCC`, p.`emailto`
                        FROM `wc_t_users` ub
                        INNER JOIN `wc_t_pi` p ON ub.`id` = p.`createdby`
                        INNER JOIN `wc_t_cacfac_request` cr ON p.`poid` = cr.`poNo`
                        WHERE cr.`poNo` = '$po' LIMIT 1;";
            $emails = $objdal->getRow($queryE);

            if($_SERVER['SERVER_NAME']!='localhost') {
                $subject = "Certificates for PO #$po and Shipment #$shipNo are now available to download";
                wcMailFunction($emails["emailto"], $subject, $msg, $emails["emailCC"]);
            }
        }else if($action == 2){
            $newActionID = action_TAC_Rejected_by_CPO;
            $msg = "'TAC request Rejected by CPO against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = -1;
            $query = "UPDATE `wc_t_cacfac_request` SET `cfacLetterBody` = '$cfacLetterBody'
                      WHERE `poNo` = '$po' AND `shipNo` = '$shipNo' AND `partName` = $partName;";
            $objdal->update($query);
            $newstatus = 'NULL';
        }

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else if($lastActionId==action_TAC_Approved_by_PRUser) {
        //Step 3 Supplier ->Buyer

        if($action == 1) {
            $newActionID = action_TAC_Approved_by_Buyer;
            $msg = "'TAC request Approved by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$cfacLetterBody'";
            $status = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET `cfacLetterBody` = '$cfacLetterBody' , `certFinalApprover` = '$certFinalApprover'
                      WHERE `poNo` = '$po' AND `shipNo` = '$shipNo' AND `partName` = $partName ;";
            $objdal->update($query);
        }else if($action == 2){
            $newActionID = action_TAC_Rejected_by_Buyer;
            $msg = "'TAC request Rejected by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = -1;
        }
        $newstatus = 'NULL';

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else if($lastActionId==action_TAC_Rejected_by_CPO) {
        //Step 3 Supplier ->Buyer

        if($action == 1) {
            $newActionID = action_TAC_Approved_by_Buyer;
            $msg = "'TAC request Rectified by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$cfacLetterBody'";
            $status = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET `cfacLetterBody` = '$cfacLetterBody' , `certFinalApprover` = '$certFinalApprover'
                    WHERE `poNo` = '$po' AND `shipNo` = '$shipNo'  AND `partName` = $partName ;";
            $objdal->update($query);
        }else if($action == 2){
            $newActionID = action_TAC_Rejected_by_Buyer;
            $msg = "'TAC request Rejected by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = -1;
        }
        $newstatus = 'NULL';

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else if($lastActionId==action_TAC_Reject_by_PRUser) {
        //Step 3 Supplier ->Buyer

        if($action == 1) {
            $newActionID = action_TAC_Approved_by_Buyer;
            $msg = "'TAC request Approved by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$cfacLetterBody'";
            $status = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET `cfacLetterBody` = '$cfacLetterBody'
                      WHERE `poNo` = '$po' AND `shipNo` = '$shipNo' AND `partName` = $partName ;";
            $objdal->update($query);

        }else if($action == 2){
            $newActionID = action_TAC_Rejected_by_Buyer;
            $msg = "'TAC request Rejected by Buyer against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = -1;
        }
        $newstatus = 'NULL';

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else if($lastActionId==action_TAC_Request_Send_by_Supplier || $lastActionId==action_TAC_Request_Rectified_by_Supplier) {
        //Step 2 // PRUSER->Supplier
        $issueDate = date('Y-m-d H:i:s');
        $issuedBy = $user_id;
        $issuedFrom = $ip;

        $certificateText = htmlspecialchars($_POST['certificateText'],ENT_QUOTES, "ISO-8859-1");
        $certificateText = stripslashes($certificateText);
        $certificateText = $objdal->real_escape_string($certificateText);
        $letterBody = $certificateText;

        if($action == 1) {
            $newActionID = action_TAC_Approved_by_PRUser;
            $msg = "'TAC request Approved by PRUser against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$letterBody'";
            $status = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET 
                `issueDate` = '$issueDate', 
                `issuedBy` = '$issuedBy', 
                `issuedFrom` = '$issuedFrom', 
                `letterBody` = '$letterBody' 
                WHERE `poNo` = '$po' 
                AND `shipNo` = '$shipNo' AND `partName` = $partName ;";
            $objdal->update($query);

        }else if($action == 2){
            $newActionID = action_TAC_Reject_by_PRUser;
            $msg = "'TAC request Rejected by PRUser against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = "'$letterBody'";
            $status = -1;
        }
        $newstatus = 'NULL';

        $refId = decryptId(htmlspecialchars($_POST['refId'],ENT_QUOTES, "ISO-8859-1"));
        $refId = stripslashes($refId);
        $refId = $objdal->real_escape_string($refId);

    } else  if($lastActionId==action_TAC_Rejected_by_Buyer){
        //Step 1 Supplier -> PRUSER
        if($action == 1) {
            $newActionID = action_TAC_Request_Rectified_by_Supplier;
            $msg = "'TAC request rectified by Supplier against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = 1;
            $query = "UPDATE `wc_t_cacfac_request` SET `ciDesc` = '$ciDesc', `ciQty` = '$ciQty'
                    WHERE `poNo` = '$po' AND `shipNo` = '$shipNo' AND `partName` = $partName;";
            //echo $query;
            $objdal->update($query);
        }
        $newstatus = 'NULL';
    } else {
        //Step 1 Supplier -> PRUSER
        if($action == 1) {
            $newActionID = action_TAC_Request_Send_by_Supplier;
            $msg = "'TAC request sent by Supplier against PO# " . $po . " and Shipment# " . $shipNo . "'";
            $usermsg = 'NULL';
            $status = 'NULL';
            $refId = 'NULL';
            $newstatus = 'NULL';

            $query = "INSERT INTO `wc_t_cacfac_request` SET 
            `poNo` = '$po', 
            `ciNo` = '$ciNo', 
            `ciDesc` = '$ciDesc', 
            `ciQty` = '$ciQty', 
            `lcNo` = '$LcNo', 
            `shipNo` = $shipNo,
            `ciValue` = $ciValue,
            `partValue` = $valueOfDoc,
            `partName` = $partName,
            `submittedBy` = $user_id,
            `submittedFrom` = '$ip';";
            //echo $query;
            $objdal->insert($query);
        }
    }
    $lastCertReqId = $objdal->LastInsertId();
    if($certReqId == 0 ){
        $certId = $lastCertReqId;
    }else {
        $certId = $certReqId;
    }
    // Action Log --------------------------------//

    $action = array(
        'pono' => "'".$po."'",
        'shipno' => $shipNo,
        'certReqId' => $certId,
        'actionid' => $newActionID,
        'msg' => $msg,
        'refid' => $refId,
        'status' => $status,
        'newstatus' => $newstatus,
        'usermsg' => $usermsg
    );

    //print_r($action);

    //if($lastActionId == action_TAC_Request_Send_by_Supplier){
    //$action['new_index'] = 23456;
    //}

    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'SUCCESS';
    return $res;
}


function GetFilteredPOList($supplier=0)
{
    $objdal=new dal();

    $sql = "SELECT 
                po.`poid` 
            FROM 
                `wc_t_pi` po 
                INNER JOIN `wc_t_shipment` s ON s.`pono` = po.`poid` 
                INNER JOIN `wc_t_payment` p ON p.`LcNo` = s.`lcNo` 
            WHERE 
                po.`supplier` = $supplier AND p.`docName` >= 6 AND p.`paymentPercent` <> 100
            GROUP BY 
                po.`poid`;";

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

/*GET SHIPMENT AND COMMERCIAL INVOICE (CI) LIST BASED ON PO NUMBER*/
function getShipList($pono = '')
{
    $objdal=new dal();
    $sql = "SELECT `ciNo`, `shipNo` FROM `wc_t_cacfac_request`";
    if($pono!=''){
        $sql .= " WHERE `poNo` = '$pono' ORDER BY `ciNo`;";
    }
    //echo $sql;
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

/*GET TAC INFO BASED ON PO NUMBER*/
function GetTacInfo($po,$shipNo)
{
    $objdal = new dal();
    $query = "SELECT 
                s.`pono`, s.`lcNo`, s.`ciNo`, s.`ciAmount`, p.`podesc`, p.`povalue`, c.`name` AS `currencyName`, co.`name` AS  `supplierName`
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_pi` p ON p.`poid` = s.`pono` 
                INNER JOIN `wc_t_category` c ON c.`id` = p.`currency` 
                INNER JOIN `wc_t_company` co ON co.`id` = p.`supplier` 
            WHERE 
                s.`pono` = '$po' AND `shipNo` = '$shipNo' AND c.`menu` = 17;";
    //echo $query;
    $objdal->read($query);
    //$res = '';
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    return json_encode($res);
}

/*GET CI NUMBER LIST BASED ON PO NUMBER*/
function getCIList($pono)
{
    $objdal=new dal();
    //$sql = "SELECT `shipNo`, `ciNo` FROM `wc_t_shipment` WHERE `pono` = '$pono' ORDER BY `shipNo`;";
    $sql = "SELECT 
                s.`shipNo`, 
                s.`ciNo` 
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_payment` p ON p.`LcNo` = s.`lcNo` 
            WHERE 
                s.`pono` = '$pono' AND p.`docName` = 6 
            GROUP BY 
                s.`ciNo`;";
    //echo $sql;
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

/*GET VALUE OF THE DOCUMENT*/
function getDocTypevalue($po,$ciNo)
{
    $res = array();

    $objdal = new dal();
    $query = "SELECT 
                p.*,
                c.`id` AS  `partName`,
                c.`name` as `docName` ,
                c.`tag` AS `docFullName`
            FROM 
                `wc_t_payment_terms` p LEFT JOIN `wc_t_category` c ON p.partname = c.id
            WHERE 
                p.pono = '$po' 
                AND partname NOT IN (
                    SELECT 
                        p.`docName` 
                    FROM 
                        `wc_t_payment` p 
                        INNER JOIN wc_t_lc l ON p.LcNo = l.lcno 
                    WHERE 
                        l.pono = p.pono
                        AND p.ciNo = '$ciNo'
                ) 
            LIMIT 1;";

    $objdal->read($query);

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }

    unset($objdal);
    return json_encode($res);
}

/*GET VALUE for cfac Form Data*/
function getCFacValueByShip()
{
    $res = array();

    $poNo = $_GET['po'];
    $ship = $_GET['ship'];
    $lcno = $_GET['lcno'];

    $objdal = new dal();
    $query = "SELECT 
                lcvalue,
				(SELECT `lcbankaddress` FROM wc_t_pi WHERE `poid` = '$poNo') as `lcbeneficiary`,
				(SELECT `blNo` FROM wc_t_shipment WHERE `poNo` = '$poNo' AND `shipNo` = '$ship') as `blNo`,
				(SELECT `id` FROM `wc_t_attachments` WHERE `poid` = '$poNo' AND `title` = 'PO' LIMIT 1) AS `attachment`
            FROM 
                `wc_t_lc`
            WHERE 
                pono = '$poNo' AND lcno = '$lcno'
            LIMIT 1;";

    $objdal->read($query);

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        //extract($res);
        $res['attachment'] = encryptId($res['attachment']);
    }

    unset($objdal);
    return json_encode($res);
}

/*TECHNICAL ACCEPTANCE CERTIFICATE (TAC) PDF Generate
*****************************************************/
function tacPdfGenerate($poNo,$shipNo,$partName)
{

    $objdal = new dal();
    //$poNo = $_GET['po'];
    $query = "SELECT 
                s.`pono`, s.`lcNo`, s.`ciNo`, s.`ciAmount`, p.`podesc`, c.`name` AS `currencyName`, co.`name` AS `suppName`
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_pi` p ON p.`poid` = s.`pono` 
                INNER JOIN `wc_t_category` c ON c.`id` = p.`currency` 
                INNER JOIN `wc_t_company`co ON co.`id` = p.`supplier`
            WHERE 
                s.`pono` = '$poNo';";
    $tac_info = $objdal->read($query)[0];
    unset($objdal);

    $objdal = new dal();

    $ciNo = $tac_info['ciNo'];
    $query = "SELECT  p.*, c.`name` as `docName`, c.`tag` AS `docFullName`
            FROM  `wc_t_payment_terms` p LEFT JOIN `wc_t_category` c ON p.`partname` = c.id
                WHERE  p.`partname` = $partName AND p.`pono` = '$poNo' AND `partname` NOT IN (SELECT p.`docName` FROM `wc_t_payment` p 
            INNER JOIN `wc_t_lc` l ON p.`LcNo` = l.`lcno` WHERE l.`pono` = p.`pono` AND p.`ciNo` = '$ciNo') LIMIT 1;";

    $ci_info = $objdal->read($query)[0];
    unset($objdal);

    $objdal = new dal();
    $ciValue = $tac_info['ciAmount'];
    $valueOfDoc = ($ciValue*$ci_info['percentage'])/100;

    $tacQuery = "SELECT 
                    `id`, `poNo`, `lcNo`, `shipNo`, `ciNo`, FORMAT(`ciValue`,2) AS `ciValue`, FORMAT(`partValue`,2) AS `partValue`, 
                    `partName`, `submittedBy`, `submittedOn`, `submittedFrom`, `issueDate`, `issuedBy`, `issuedFrom`, 
                    `letterBody`, `cfacLetterBody`, `cfacIssueDate`, `paymentStatus` 
                FROM 
                    `wc_t_cacfac_request` 
                WHERE  `poNo` = '$poNo' AND `shipNo`='$shipNo' AND `partName` = $partName;";
    $tacData = $objdal->read($tacQuery)[0];

    //$issueDate = date('d-F-Y', strtotime($tacData['issueDate']));
    //$letterBody = $tacData['letterBody'];

    /*CUSTOMIZE/ REMOVE SUFFIX FROM PO NUMBER. EXAMPLE 300012345PI1 -> 300012345*/
    $suffPoNo = $tacData['poNo'];
    $suffLessPoNo =  strstr($suffPoNo, 'PI', true) ?: $suffPoNo;

    require_once(LIBRARY_PATH . "/tcpdf/tcpdf.php");

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);

    $pdf->SetAuthor('Aqa Technology');
    $pdf->SetTitle('Technical Acceptance Certificate');
    $pdf->SetSubject('PDF Invoice');
    $pdf->SetKeywords('TCPDF, PDF Invoice');
    $pdf->setPrintHeader(false);
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage('P', 'A4');

    $today = date("d F, Y");

    $pdf->SetFont('helvetica', '', '10', '', true);

    /*CHECK IF THE CERTIFICATE IS READY TO DOWNLOAD & PREVENT USERS FROM TRESPASSING
    *******************************************************************************/
    if($tacData['issueDate']!=null){
        require_once(TEMPLATES_PATH . "/letter_template/tac-template.php");
    }else{
        $html = '
		<style>
			table {
				margin-bottom: 25pt;
			}
		</style>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 15pt;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle">
				<h2 style="font-weight: 700;  text-align: center; font-size: 14px; color: #0000FF;">THIS CERTIFICATE IS NOT READY YET.</h2>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>';
    }


    $pdf->writeHTML($html, true, false, false, false, '');
    $pdfName = 'Technical Acceptance Certificate'.'PO_'.$poNo.'_CI_'.$tacData['ciNo'].'.pdf';
    $pdf->Output($pdfName, 'I');
}

/*GET LC BENEFICIARY INFO & LETTER BODY TEXT, ACTION - 13
 * Error occurs when supplier enter into the module to submit/rectify certificate request,
 * To handle that error Custom/Dummy JSON has been created in 'IF' condition, as there is only one handler for this module,
**********CREATED BY: HASAN MASUD**********/
function getLetterText($poNo, $ship,$partName, $lastActId)
{

    if ($lastActId == action_TAC_Approved_by_PRUser || $lastActId == action_TAC_Reject_by_PRUser || $lastActId == action_TAC_Rejected_by_Buyer ||
        $lastActId == action_TAC_Request_Send_by_Supplier || $lastActId == action_TAC_Request_Rectified_by_Supplier) {
        $objdal = new dal();
        // Prepare Text using LC Info
        $query = "SELECT 
                    p.`lcbankaddress` AS `lcbeneficiary`, c.`name` AS `suppName`
                FROM `wc_t_pi` p
                    INNER JOIN `wc_t_company` c ON c.`id` = p.`supplier`
                WHERE p.`poid` = '$poNo' LIMIT 1;";
        //echo $query;
        $lcBeneficiary = $objdal->read($query)[0];
        // Prepare full text
        unset($objdal);
        $obj = (object)[
            'cfacLetterBody' => 'This is to certify that ' . $lcBeneficiary["suppName"] . ' beneficiary has completed their deliverable and achieved Acceptance certificate for stated Commercial Invoice and LC number. The following payment due to LC beneficiary should be released, upon presentation of this Original Acceptance Certificate.',
            'certFinalApprover' => ''
        ];
        return json_encode($obj);
    } elseif ($lastActId == action_TAC_Approved_by_CPO || $lastActId == action_TAC_Rejected_by_CPO || $lastActId == 0 || $lastActId == action_TAC_Approved_by_Buyer) {
        // GET DATA FROM CACFAC_REQUEST TABLE IN CASE OF REJECT
        $objdal = new dal();
        $query = "SELECT `ciDesc`, `ciQty`, `cfacLetterBody`, `certFinalApprover` 
                    FROM `wc_t_cacfac_request` 
                  WHERE `poNo` = '$poNo' AND `shipNo` = '$ship' AND `partName` = '$partName';";
        //echo $query.'<br>';
        $objdal->read($query);
        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }
        unset($objdal);
        return json_encode($res);
    }

}

/*GET CERTIFICATE NAME, ACTION - 14
**********CREATED BY: HASAN MASUD**********/
function getCertName($poNo){
    $objdal = new dal();
    $query = "SELECT 
                p.*, c.`tag` AS `cfacName`
            FROM 
                `wc_t_payment_terms` p 
                LEFT JOIN `wc_t_category` c ON p.`partname` = c.`id`
            WHERE 
                p.`pono` = '$poNo' 
                AND `partname` NOT IN 
                (SELECT p.`docName` FROM `wc_t_payment` p 
                INNER JOIN `wc_t_lc` l ON p.`LcNo` = l.`lcno` 
                INNER JOIN `wc_t_cacfac_request` cr ON p.`ciNo` = cr.`ciNo` 
                WHERE l.`pono` = p.`pono` AND p.`ciNo` = cr.`ciNo` ) 
            LIMIT 1;";
    //echo $query;
    $certInfo = $objdal->read($query)[0];
    unset($objdal);
    return json_encode($certInfo);
}

/*CFAC PDF Generate, Action - 8
********************************/
function cfacPdfGenerate($poNo,$ship,$partName)
{
    $objdal = new dal();
    /*$poNo = $_GET['po'];
    $ship = $_GET['ship'];*/

    //$returnedLetterBody = getLetterText($poNo,$ship,$partName, 0);
    $letterInfo = json_decode(getLetterText($poNo, $ship, $partName, 0));
    $returnedLetterBody = $letterInfo->cfacLetterBody;
    $approverId = $letterInfo->certFinalApprover;
    //echo $returnedLetterBody;

    //Prepare final approver information
    $strQuery = "SELECT CONCAT(u.`firstname`, ' ', u.`lastname`) AS `fullName`
                FROM `wc_t_users` u 
                INNER JOIN `wc_t_cacfac_request` cr ON cr.`certFinalApprover` = u.`id`
                WHERE u.`id` = '$approverId';";
    $approverInfo = $objdal->read($strQuery)[0];
    unset($objdal);

    /* Here 137 is ID of CPO Alamin Bhai
     * At initial phase GP requirement was CPO will be final approver
     * As GP changed approval flow, to maintain previous certificates we have to hardcode ID 137
     */
    if (!empty($approverId) && $approverId != 137) {
        $approverSign = $approverId;
        $finalAppName = $approverInfo['fullName'];
        $finalAppDesignation = 'General Manager';
    } else {
        $approverSign = 'cpo_signature';
        $finalAppName = 'A.K.M. Al-Amin';
        $finalAppDesignation = 'Chief Procurement Officer';
    }

    //Prepare Technical Acceptance Certificate info
    $objdal = new dal();
    $query = "SELECT 
                s.`pono`, s.`lcNo`, s.`ciNo`, s.`ciAmount`, p.`podesc`, c.`name` AS `currencyName`, co.`name` AS `suppName`
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_pi` p ON p.`poid` = s.`pono` 
                INNER JOIN `wc_t_category` c ON c.`id` = p.`currency` 
                INNER JOIN `wc_t_company`co ON co.`id` = p.`supplier`
            WHERE 
                s.`pono` = '$poNo';";
    $tac_info = $objdal->read($query)[0];
    unset($objdal);


    $objdal = new dal();

    $ciNo = $tac_info['ciNo'];
    $lcNo = $tac_info['lcNo'];
    $query = "SELECT 
                p.*, c.`name` AS `docName`, p.`cacFacText`
            FROM 
                `wc_t_payment_terms` p LEFT JOIN `wc_t_category` c ON p.`partname` = c.`id`
            WHERE 
                p.`pono` = '$poNo' 
                AND p.`partname` = $partName
                AND `partname` NOT IN (SELECT p.`docName` FROM `wc_t_payment` p INNER JOIN `wc_t_lc` l ON p.`LcNo` = l.`lcno` 
                WHERE l.`pono` = p.`pono` AND p.`ciNo` = '$ciNo' ) 
            LIMIT 1;";

    $ci_info = $objdal->read($query)[0];

    $ciValue = $tac_info['ciAmount'];
    $valueOfDoc = ($ciValue * $ci_info['percentage']) / 100;

    $objdal = new dal();
    $query = "SELECT 
                FORMAT(`lcvalue`,2) AS `lcvalue`,
				(SELECT lcbankaddress FROM wc_t_pi WHERE poid = '$poNo') as lcbeneficiary,
				(SELECT blNo FROM wc_t_shipment WHERE poNo = '$poNo' AND shipNo = '$ship') as blNo
            FROM 
                `wc_t_lc`
            WHERE 
                pono = '$poNo' AND lcno = '$lcNo'
            LIMIT 1;";

    $lc_info = $objdal->read($query)[0];

    //echo "<pre>";
    //print_r($lc_info);
    //echo "</pre>";

    //die();

    $valueOfDocinWord = ucwords(convertNumberToWord($valueOfDoc));

    //die();

    $objdal = new dal();
    $cfacQuery = "SELECT  
                    `id`, `poNo`, `lcNo`, `shipNo`, `ciNo`, FORMAT(`ciValue`,2) AS `ciValue`, FORMAT(`partValue`,2) AS `partValue`, 
                    `partName`, `submittedBy`, `submittedOn`, `submittedFrom`, `issueDate`, `issuedBy`, `issuedFrom`, `letterBody`, 
                    `cfacLetterBody`, `cfacIssueDate`, `paymentStatus` 
                FROM 
                    `wc_t_cacfac_request` 
                WHERE  `poNo` = '$poNo' AND `shipNo`='$ship' AND `partName` = $partName;";
    $cfacData = $objdal->read($cfacQuery)[0];
    unset($objdal);

    /*CUSTOMIZE/ REMOVE SUFFIX FROM PO NUMBER. EXAMPLE 300012345PI1 -> 300012345*/
    $suffPoNo = $cfacData['poNo'];
    $suffLessPoNo = strstr($suffPoNo, 'PI', true) ?: $suffPoNo;

    $valueOfDocinWord = ucwords(convertNumberToWord($cfacData['partValue']));

    require_once(LIBRARY_PATH . "/tcpdf/tcpdf.php");

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);

    $pdf->SetAuthor('Shohel Iqbal');
    $pdf->SetTitle('CFAC PDF');
    $pdf->SetSubject('PDF Invoice');
    $pdf->SetKeywords('TCPDF, PDF Invoice');
    $pdf->setPrintHeader(false);
    $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage('P', 'A4');

    $pdf->SetFont('helvetica', '', '10', '', true);

    //$html = '<link rel="stylesheet" href="pdf-bootstrap.min.css">';
    //$html .= '<div>'.$_POST['content'].'</div>';

    //$template = require_once(TEMPLATES_PATH . "/letter_template/tac-pdf-format.php");
    /*CHECK IF THE CERTIFICATE IS READY TO DOWNLOAD & PREVENT USERS FROM TRESPASSING
	*******************************************************************************/
    if ($cfacData['cfacIssueDate'] != null) {
        require_once(TEMPLATES_PATH . "/letter_template/cfac-template.php");
    } else {
        $html = '
            <style>
                table {
                    margin-bottom: 25pt;
                }
            </style>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15pt;" border="0">
                <tr>
                    <td style="width: 10%;"> </td>
                    <td style="width: 80%; vertical-align: middle">
                    <h2 style="font-weight: 700;  text-align: center; font-size: 14px; color: #0000FF;">THIS CERTIFICATE IS NOT READY YET.</h2>
                    </td>
                    <td style="width: 10%;"> </td>
                </tr>
            </table>';
    }

    $pdf->writeHTML($html, true, false, false, false, '');
    //$pdf->Output('cfac-action.pdf', 'I');
    $pdfName = $ci_info['cacFacText'] . 'PO_' . $poNo . '_CI_' . $cfacData['ciNo'] . '.pdf';
    $pdf->Output($pdfName, 'I');

    die();
}

function checkCPOApprove() {

    $objdal = new dal();
    $poNo = $_GET['po'];
    $ship = $_GET['ship'];
    $query = "SELECT `ActionID`, `status` FROM `wc_t_action_log` WHERE `PO` = '$poNo' AND `shipNo`='$ship' ORDER BY `ID` DESC;";
    $action_log = $objdal->read($query)[0];
    unset($objdal);

    if($action_log['ActionID']==action_TAC_Approved_by_CPO && $action_log['status']==1) {
        return 1;
    } else {
        return 0;
    }
}

/*GET CPO APPROVED PO, ACTION - 10
ADDED BY - HASAN MASUD
*************************************/
function getCpoApprovedPO($user=0)
{
    global $loginRole;
    $objdal=new dal();

    $where = '';
    /*if($supplier>0) {
        $where = " WHERE p.`supplier` = " . $supplier;
    }*/
    if($loginRole == role_Buyer){
        if($user>0) {
            $where = " WHERE p.`createdby` = " . $user;
        }
    }else if($loginRole == role_PR_Users){
        if($user>0) {
            $where = " WHERE p.`pruserto` = " . $user;
        }
    }else if($loginRole == role_Supplier){
        if($user>0) {
            $where = " WHERE c.`submittedBy` = " . $user;
        }
    }else{
        $where ="";
    }

    $sql = "SELECT c.`poNo` FROM `wc_t_cacfac_request` c INNER JOIN `wc_t_pi` p ON p.`poid` = c.`poNo` $where GROUP BY c.`poNo`;";
    //echo $sql .'<br>';
    $objdal->read($sql);

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

/*GET PAYMENT HISTORY STATUS - ACTION - 11
*********CREATED BY: HASAN MASUD*********/
function getPayHistory($ciNo)
{
    $objdal = new dal();
    $query = "SELECT 
                c.`name` AS `docName`, p.`paymentPercent`, p.`amount`, DATE_FORMAT(p.`payDate`, '%M %d, %Y') AS `payDate`, 
                po.`poid`, DATE_FORMAT(po.`createdon`, '%M %d, %Y') AS `poDate`
            FROM 
                `wc_t_payment` p
                INNER JOIN `wc_t_category` c ON c.`id` = p.`docName`
                INNER JOIN `wc_t_lc` lc ON lc.`lcno` = p.`LcNo`
                INNER JOIN `wc_t_pi` po ON po.`poid` = lc.`pono`
            WHERE 
                p.`ciNo` = '$ciNo' AND c.`menu` = 25;";
    //echo $query;
    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    return json_encode($rows);
}

/*CHECK PAYMENT STATUS OF THE REQUEST, ACTION - 15
**********CREATED BY: HASAN MASUD**********/
function checkPamntStatus($poNo,$shipNo){
    $objdal = new dal();
    $query = "SELECT COUNT(*) AS `paymentStatus` FROM `wc_t_cacfac_request` WHERE `poNo` = '$poNo' AND `shipNo` = '$shipNo' AND `paymentStatus` = 0;";
    //echo $query;
    $actionStatus = $objdal->read($query)[0]['paymentStatus'];
    unset($objdal);
    return $actionStatus;
}


/*GET CERTIFICATES & PAYMENT HISTORY STATUS - ACTION - 16
*********CREATED BY: HASAN MASUD*********/
function getCertsPayHistory($poNo)
{
    $objdal = new dal();
    $query = "SELECT 
                pt.`pono`, pt.`cacFacText`,  pt.`percentage` AS `paymentPercent`, lc.`lcno`, sh.`ciNo`, sh.`ciAmount`,
                c.`name` AS `currencyName`, sh.`shipNo`, pt.`partname`,
                (SELECT `amount` FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) AS `payAmount`,
                (SELECT DATE_FORMAT(`payDate`,'%d-%M-%Y') FROM `wc_t_payment` pp WHERE pp.`ciNo` = sh.`ciNo` AND pp.`docName` = pt.`partname`) AS `payDate`,
                (SELECT COUNT(*) FROM `wc_t_cacfac_request` cr WHERE cr.`poNo` = pt.`pono` AND  cr.`shipNo` = sh.`shipNo` AND cr.`partName` = pt.`partname` AND NOT cr.`issuedBy` IS null) AS `tacStatus`,
                (SELECT COUNT(*) FROM `wc_t_cacfac_request` cr WHERE cr.`poNo` = pt.`pono` AND  cr.`shipNo` = sh.`shipNo` AND cr.`partName` = pt.`partname` AND NOT cr.`cfacIssueDate` IS null) AS `cfacStatus`
            FROM
                `wc_t_payment_terms` pt
                INNER JOIN `wc_t_lc` lc ON pt.`pono` = lc.`pono`
                INNER JOIN `wc_t_pi` po ON po.`poid` = pt.`pono`
                INNER JOIN `wc_t_category` c ON c.`id` = po.`currency`
                INNER JOIN `wc_t_shipment` sh ON lc.`lcno` = sh.`lcNo`
            WHERE
                pt.`pono` = '$poNo'
            ORDER BY sh.`ciNo`, pt.`partname`;";
    //echo $query;
    $objdal->read($query);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    return json_encode($rows);
}

//Get certificate request date
function getRequestData($poNo, $shipNo, $partName){
    $objdal = new dal();
    //Get certificate data
    $query = "SELECT `id` AS `certReqId`, `ciDesc`, `ciQty` FROM `wc_t_cacfac_request` 
                WHERE `poNo` = '$poNo' AND `shipNo` = $shipNo AND `partName` = $partName LIMIT 1;";
    $data = $objdal->read($query);
    return json_encode($data[0]);
}