<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 2020-08-24
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
            echo tacPdfGenerate($_GET["poNo"], $_GET["shipNo"], $_GET["partName"]);
            break;
        case 2:
            echo cfacPdfGenerate($_GET["po"], $_GET["ship"], $_GET["partName"]);
            break;


    }
}

/*TECHNICAL ACCEPTANCE CERTIFICATE (TAC) PDF Generate
*****************************************************/
function tacPdfGenerate($poNo,$shipNo,$partName)
{

    $objdal = new dal();
    //$poNo = $_GET['po'];
    $query = "SELECT 
                s.`pono`, 
                s.`lcNo`, 
                s.`ciNo`, 
                s.`ciAmount`,
                p.`podesc`,
                c.`name` AS `currencyName`,
                co.`name` AS `suppName`
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_po` p ON p.`poid` = s.`pono` 
                INNER JOIN `wc_t_category` c ON c.`id` = p.`currency` 
                INNER JOIN `wc_t_company`co ON co.`id` = p.`supplier`
            WHERE 
                s.`pono` = '$poNo';";
    $tac_info = $objdal->read($query)[0];
    unset($objdal);

    $objdal = new dal();

    $ciNo = $tac_info['ciNo'];
    $query = "SELECT 
                p.*, c.`name` as `docName` ,
                c.`tag` AS `docFullName`
            FROM 
                `wc_t_payment_terms` p LEFT JOIN `wc_t_category` c ON p.partname = c.id
            WHERE 
                p.`partname` = $partName
                 AND p.pono = '$poNo' 
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

    $ci_info = $objdal->read($query)[0];
    unset($objdal);

    $objdal = new dal();
    $ciValue = $tac_info['ciAmount'];
    $valueOfDoc = ($ciValue*$ci_info['percentage'])/100;

    //$shipNo = $_GET['ship'];
    /*$tacQuery = "SELECT * FROM `wc_t_cacfac_request` WHERE `poNo` = '$poNo' AND shipNo='$shipNo';";*/
    $tacQuery = "SELECT 
                    `id`, 
                    `poNo`, 
                    `lcNo`, 
                    `shipNo`, 
                    `ciNo`, 
                    FORMAT(`ciValue`,2) AS `ciValue`, 
                    FORMAT(`partValue`,2) AS `partValue`, 
                    `partName`, 
                    `submittedBy`, 
                    `submittedOn`, 
                    `submittedFrom`, 
                    `issueDate`, 
                    `issuedBy`, 
                    `issuedFrom`, 
                    `letterBody`, 
                    `cfacLetterBody`, 
                    `cfacIssueDate`, 
                    `paymentStatus` 
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

    //$html = '<link rel="stylesheet" href="pdf-bootstrap.min.css">';
    //$html .= '<div>'.$_POST['content'].'</div>';

    //$template = require_once(TEMPLATES_PATH . "/letter_template/tac-pdf-format.php");

    /*CHECK IF THE CERTIFICATE IS READY TO DOWNLOAD & PREVENT USERS FROM TRESPASSING
    *******************************************************************************/
    if($tacData['issueDate']!=null){
        $html = '
		<style>
			table {
				margin-bottom: 25pt;
			}
		</style>
		<table style="width: 100%; border-collapse: collapse;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle; text-align: right">
					<img src="../../assets/images/gp-logo.jpg" alt="" style="max-width: 100%">
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<h2 style="font-weight: 700; margin: 20px 0px 0px 0px; font-size: 18px; text-align: center; color: #0000FF;">GP Project</h2>
		<h2 style="font-weight: 700; margin: 0px 0px 0px 15px;   font-size: 18px; text-align: center; color: #0000FF;">Technical Acceptance Certificate</h2>
		
		<table style="width: 100%; border-collapse: collapse; margin-bottom: 15pt;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle">
				<h2 style="font-weight: 700; margin: 15px 0px 0px 0px; font-size: 14px; color: #0000FF;">Technical Information:</h2>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		<table style="width: 100%; border-collapse: collapse; float: right" cellpadding="6">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">TAC Issue Date:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">'.date('F j, Y', strtotime($tacData['issueDate'])).'</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Project Name:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['podesc'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%; border-collapse: collapse;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle">
					<h2 style="font-weight: 700; margin: 15px 0px 0px 0px; font-size: 14px; color: #0000FF;">Commercial Information:</h2>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		<table style="width: 100%; border-collapse: collapse;" cellpadding="6" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Supplier :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['suppName'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">PO Number :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $suffLessPoNo . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C No:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tacData['lcNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tacData['ciNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $tacData['ciValue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $ci_info['cacFacText'] . ' Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' '. $tacData['partValue'] .'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">'.nl2br($tacData['letterBody']).'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;">
			<!--<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"><b>TAC effective from: </b></td>
				<td style="width: 10%;"> </td>
			</tr>-->
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<!--
		* Signature area of supplier and GP related person has been removed.
		* Instructed by Tanvir Bhai(GP)
		* Edited by: Hasan Masud
		* Edit Date: 23-02-2019
		-->
		<!--<table style="width: 100%;">
			<tr>
				<td style="width: 55%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Project Manager (Supplier)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Project Owner (GP)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Head of PMO (Supplier)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>-->';
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

/*CFAC PDF Generate, Action - 8
********************************/
function cfacPdfGenerate($poNo,$ship,$partName)
{
    $objdal = new dal();
    /*$poNo = $_GET['po'];
    $ship = $_GET['ship'];*/

    //$returnedLetterBody = getLetterText($poNo,$ship,$partName, 0);
    $letterInfo = json_decode(getLetterText($poNo,$ship,$partName, 0));
    $returnedLetterBody = $letterInfo->cfacLetterBody;
    $approverId = $letterInfo->certFinalApprover;
    //echo $returnedLetterBody;

    //Prepare final approver information
    $strQuery ="SELECT CONCAT(u.`firstname`, ' ', u.`lastname`) AS `fullName`
                FROM `wc_t_users` u 
                INNER JOIN `wc_t_cacfac_request` cr ON cr.`certFinalApprover` = u.`id`
                WHERE u.`id` = '$approverId';";
    $approverInfo = $objdal->read($strQuery)[0];
    unset($objdal);

    /* Here 137 is ID of CPO Alamin Bhai
     * At initial phase GP requirement was CPO will be final approver
     * As GP changed approval flow, to maintain previous certificates we have to hardcode ID 137
     */
    if(!empty($approverId) && $approverId != 137){
        $approverSign = $approverId;
        $finalAppName = $approverInfo['fullName'];
        $finalAppDesignation = 'General Manager';
    }else{
        $approverSign = 'cpo_signature';
        $finalAppName = 'A.K.M. Al-Amin';
        $finalAppDesignation = 'Chief Procurement Officer';
    }

    //Prepare Technical Acceptance Certificate info
    $objdal = new dal();
    $query = "SELECT 
                s.`pono`, 
                s.`lcNo`, 
                s.`ciNo`, 
                s.`ciAmount`,
                p.`podesc`,
                c.`name` AS `currencyName`,
                co.`name` AS `suppName`
            FROM 
                `wc_t_shipment` s 
                INNER JOIN `wc_t_po` p ON p.`poid` = s.`pono` 
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
                p.*, 
                c.`name` AS `docName`,
                p.`cacFacText`
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
    $valueOfDoc = ($ciValue*$ci_info['percentage'])/100;

    $objdal = new dal();
    $query = "SELECT 
                lcvalue,
				(SELECT lcbankaddress FROM wc_t_po WHERE poid = '$poNo') as lcbeneficiary,
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
                    `id`, 
                    `poNo`, 
                    `lcNo`, 
                    `shipNo`, 
                    `ciNo`, 
                    FORMAT(`ciValue`,2) AS `ciValue`, 
                    FORMAT(`partValue`,2) AS `partValue`, 
                    `partName`, 
                    `submittedBy`, 
                    `submittedOn`, 
                    `submittedFrom`, 
                    `issueDate`, 
                    `issuedBy`, 
                    `issuedFrom`, 
                    `letterBody`, 
                    `cfacLetterBody`, 
                    `cfacIssueDate`, 
                    `paymentStatus` 
                FROM 
                    `wc_t_cacfac_request` 
                WHERE  `poNo` = '$poNo' AND `shipNo`='$ship' AND `partName` = $partName;";
    $cfacData = $objdal->read($cfacQuery)[0];
    unset($objdal);

    /*CUSTOMIZE/ REMOVE SUFFIX FROM PO NUMBER. EXAMPLE 300012345PI1 -> 300012345*/
    $suffPoNo = $cfacData['poNo'];
    $suffLessPoNo =  strstr($suffPoNo, 'PI', true) ?: $suffPoNo;

    $valueOfDocinWord = ucwords(convertNumberToWord($cfacData['partValue']));

    require_once(LIBRARY_PATH . "/tcpdf/tcpdf.php");

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);

    $pdf->SetAuthor('Shohel Iqbal');
    $pdf->SetTitle('CFAC PDF');
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

    $pdf->SetFont('helvetica', '', '10', '', true);

    //$html = '<link rel="stylesheet" href="pdf-bootstrap.min.css">';
    //$html .= '<div>'.$_POST['content'].'</div>';

    //$template = require_once(TEMPLATES_PATH . "/letter_template/tac-pdf-format.php");
    /*CHECK IF THE CERTIFICATE IS READY TO DOWNLOAD & PREVENT USERS FROM TRESPASSING
	*******************************************************************************/
    if($cfacData['cfacIssueDate']!=null){
        $html = '
		<style>
			table {
				margin-bottom: 25pt;
			}
		</style>
		<table style="width: 100%; border-collapse: collapse;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle; text-align: right">
					<img src="../../assets/images/gp-logo.jpg" alt="" style="max-width: 100%">
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<h2 style="font-weight: 700; margin: 20px 0px 0px 0px; font-size: 18px; text-align: center; color: #0000FF;">GP Project</h2>
		<h2 style="font-weight: 700; margin: 0px 0px 0px 15px;   font-size: 18px; text-align: center; color: #0000FF;">' . $ci_info['cacFacText'] . '</h2>
		<table style="width: 100%; border-collapse: collapse;" cellpadding="6" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Supplier :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['suppName'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">PO Number :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $suffLessPoNo . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Description:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['podesc'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C No:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $cfacData['lcNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $lc_info['lcvalue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C Beneficiary:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['suppName'] . '</td>
				<!-- <td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $lc_info['lcbeneficiary'] . '</td>-->
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $cfacData['ciNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">AWB/ BL Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $lc_info['blNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $cfacData['ciValue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $ci_info['cacFacText'] . ' Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' '. $cfacData['partValue'] .'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">' . nl2br($returnedLetterBody) . '</td>
				<td style="width: 10%;"></td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">' . $tac_info['currencyName'] . ' '.$cfacData['partValue'].' (' . $tac_info['currencyName'] . ' '.$valueOfDocinWord.')</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">This represents '.$ci_info['percentage'].'% of the Commercial Invoice value of the Finally Accepted Equipment.</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 50px;"> </div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;"><br/><br/><br/><br/>Authorized Signature</td>
				<td style="width: 25%;">
				    <img src="../../assets/images/'.$approverSign.'.png" alt="" style="max-width: 100%">
				   : ----------------------------------
				   </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 55%;"> </td>
				<td style="width: 25%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Name</td>
				<td style="width: 25%;">: <b> '.$finalAppName.'</b></td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Designation</td>
				<td style="width: 25%; line-height: 1.4;">
					: '.$finalAppDesignation.'<br/>
					   Global Sourcing<br/>
					   Grameenphone Ltd.
				</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Date</td>
				<td style="width: 25%;">: ' . date("F j, Y",strtotime($cfacData['cfacIssueDate'])) . '</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>';}else {
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
    $pdfName = $ci_info['cacFacText'].'PO_'.$poNo.'_CI_'.$cfacData['ciNo'].'.pdf';
    $pdf->Output($pdfName, 'I');

    die();
}
