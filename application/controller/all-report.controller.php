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
    echo generateReport($_GET["action"]);
/*	switch($_GET["action"])
	{
		case 1:
			echo GetLCOpeningReport();
			break;
		case 2:
			echo GetLCEndorsementReport();
			break;
		case 3:
			echo GetLCAmendmentReport();
			break;
		case 4:
			echo GetSupplierWiseLCOpeningReport();
			break;
		case 5:
			echo GetSupplierWiseLCEndReport();
			break;
		case 6:
			echo GetBuyerWisedReport();
			break;
        
        default:
            break;
	}*/
}

function generateReport($reportId){
    
    switch($reportId){
        case 1:
            $query = "SELECT 
            lc.`lcno` AS `LC #`,
            (SELECT `name` FROM `wc_t_bank_insurance` WHERE id = lc.`insurance`) AS `Ins. Company`,
            (SELECT `coverNoteNo` FROM `wc_t_insurance_charge` WHERE ponum=lc.`pono`) AS `Ins. Cover Note#`,
            (SELECT `name` FROM `wc_t_bank_insurance` WHERE id = lc.`lcissuerbank`) AS `Bank`,
            `lcissuedate` AS `LC Opening Date`,
            `lastdateofship` AS `Last Date of Shipment`,
            `lcexpirydate` AS `Expiry Date`,
            (SELECT `name` FROM `wc_t_company` WHERE id = (SELECT `supplier` FROM `wc_t_pi` WHERE poid = lc.`pono`)) AS `Supplier`,
            (SELECT `lcdesc` FROM `wc_t_pi` WHERE poid = lc.`pono`) AS `Description of Goods`,
            (SELECT `name` FROM `wc_t_category` WHERE id = (SELECT `currency` FROM `wc_t_pi` WHERE poid = lc.`pono`)) AS `Currency`,
            `lcvalue` AS `LC Value`,
            `lcvalue` AS `LC Value in USD`,
            'yyy' AS `LC Value in BDT`,
            `pono` AS `PO No.`
        FROM `wc_t_lc` lc;";
            break;
        case 2:
            $query = "SELECT 
            end.`endDate` AS `Endorsement Date`,
            `lcno` AS `LC No.`,
            (SELECT `name` FROM `wc_t_bank_insurance` WHERE id = end.`id`) AS `Bank`,
            'docType' AS `Document Type`,
            `gerpInvNo` AS `STD Invoice No.`,
            `pono` AS `PO No.`,
            (SELECT `name` FROM `wc_t_company` WHERE id = (SELECT `supplier` FROM `wc_t_pi` WHERE poid = end.`pono`)) AS `Supplier`,
            (SELECT `lcdesc` FROM `wc_t_pi` WHERE poid = end.`pono`) AS `Description`,
            (SELECT `name` FROM `wc_t_category` WHERE id = (SELECT `currency` FROM `wc_t_pi` WHERE poid = end.`pono`)) AS `Currency`,
            (SELECT `lcvalue` FROM `wc_t_lc` WHERE lcno = end.`lcno`) AS `L/C Value`,
            `ciValue` AS `Endorsed Amount `,
            `ciValue` AS `Endorsed Amount in USD `,
            'yyy' AS `Endorsed Amount in BDT `,
            (SELECT `exchangeRate` FROM `wc_t_insurance_charge` WHERE ponum = end.`pono`) AS `Exchange Rate `,
            `ciNo` AS `Commercial Invoice No.`,
            `ciDate` AS `Invoice Date`,
            'yyy' AS `Supplier Site`,
            (SELECT `awbOrBlDate` FROM `wc_t_shipment` WHERE pono = end.`pono`) AS `AWB Date`,
            `endNo` AS `Shipment /Endorsement No.`
        FROM `wc_t_endorsement` end;";
        break;
        
        case 3:
            $query = "SELECT 
                amn.`lcNo` AS `LC No.`,
                `poNo` AS `PO No.`,
                'yyy' AS `Amendment date`,
                (SELECT `name` FROM `wc_t_bank_insurance` WHERE id = amn.`id`) AS `Bank`,
                (SELECT `name` FROM `wc_t_company` WHERE id = (SELECT `supplier` FROM `wc_t_pi` WHERE poid = amn.`poNo`)) AS `Supplier`,
               `amendDetail` AS `Description of Amendment`,
                (SELECT `name` FROM `wc_t_category` WHERE id = (SELECT `currency` FROM `wc_t_pi` WHERE poid = amn.`poNo`)) AS `Currency`,
                (SELECT `lcvalue` FROM `wc_t_lc` WHERE pono = amn.`poNo`) AS `LC Value`,
                (SELECT `lcvalue` FROM `wc_t_lc` WHERE lcno = amn.`poNo`) AS `LC Value in USD`,
                'yyy' AS `LC Value in BDT`,
                'yyy' AS `Amendment cost`,
               `chargeBorneBy` AS `Amendment cost to be borne by`,
                'yyy' AS `Any adjustment `
            FROM `wc_t_amendment` amn;";
        break;
            
        case 4:
            $query = "SELECT 
            (SELECT co1.`name` FROM `wc_t_company` co1 WHERE id = (SELECT po1.`supplier` FROM `wc_t_pi` po1 WHERE poid = lco.`pono`)) AS `Supplier`,
            (SELECT `name` FROM `wc_t_category` WHERE id = (SELECT po2.`currency` FROM `wc_t_pi` po2 WHERE poid = lco.`pono`)) AS `Currency`,
            `lcvalue` AS `LC Value`,
            `lcvalue` AS `LC Value in USD`,
            'yyy' AS `LC Value in BDT`
        FROM `wc_t_lc` lco;";
        break;
            
        case 5:
            $query = "SELECT 
            (SELECT co1.`name` FROM `wc_t_company` co1 WHERE id = (SELECT po1.`supplier` FROM `wc_t_pi` po1 WHERE poid = lco.`pono`)) AS `Supplier`,
            (SELECT `name` FROM `wc_t_category` WHERE id = (SELECT po2.`currency` FROM `wc_t_pi` po2 WHERE poid = lco.`pono`)) AS `Currency`,
            `lcvalue` AS `LC Value`,
            `lcvalue` AS `LC Value in USD`,
            'yyy' AS `LC Value in BDT`
        FROM `wc_t_lc` lco;";
        break;
        case 6:
            $query = "SELECT 
                po.`poid` AS `Foreign PO Number`,
                (SELECT `username` FROM `wc_t_users` WHERE id = po.`createdby`) AS `PO Buyer`,
                (SELECT `name` FROM `wc_t_company` WHERE id = po.`supplier`) AS `Supplier`,
                '-' AS `PR Apporve date`,
                '-' AS `PO Approval Date`,
                `podesc` AS `PO Description`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_New_PO_Issued.") AS `PO & BOQ Sent to Vendor`,
                DATE_FORMAT(`deliverydate`,'%d-%M-%Y') AS `PO need by date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Final_PI_Submitted.") AS `PI & BOQ Receive Date`,
                `basevalue` AS `Discount`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Sent_for_BTRC_Permission.") AS `Request for BTRC Permission`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Accepted_by_BTRC.") AS `BTRC Permission Received`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_LC_Request_Sent.") AS `Apply for LC`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Final_LC_Copy_Sent.") AS `LC Receive Date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Original_Document_Delivered.") AS `Soft Scan Copy DOC Rec.Date`,
                (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Original_Document_Rejected.") AS `Pre-Alert & GIT receiving & Doc Endorse mail`,
                (SELECT DATE_FORMAT(`gitReceiveDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `GIT received Date`,
                (SELECT DATE_FORMAT(`awbOrBlDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `AWB No / BL No`,
                (SELECT `ciNo` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `C. Invoice number`,
                (SELECT DATE_FORMAT(`ciDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `C. Invoice date`,
                (SELECT `ciAmount` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `C. Invoice Amount`,
                '-' AS `Description (For partial shipment only)`,
                (SELECT `GERPVoucherNo` FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS ` Voucher No`,
                (SELECT DATE_FORMAT(`GERPVoucherDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS ` V.Creation Date`,
                (SELECT DATE_FORMAT(`scheduleETA`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS ` ETA`,
                (SELECT DATE_FORMAT(`whArrivalDate`,'%d-%M-%Y') FROM `wc_t_shipment` WHERE pono = po.`poid` limit 1) AS `Actual Arrival at WH`
            FROM `wc_t_pi` po;";
            break;
    }
    
    
    $objdal = new dal();
    $objdal->read($query);
    $rows = array();
	if(!empty($objdal->data)){
		foreach($objdal->data as $row){
            $rows[] = $row;
		}
	}
	unset($objdal);
	$json = json_encode($rows);
    //$table_data = '{"data": '.$json.'}';
    return $json;
    //return $query;
}

?>

