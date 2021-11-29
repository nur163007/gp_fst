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
		case 1:	// get single info
			echo json_encode(GetCreditReort($_GET["id"]));
			break;
		case 2:	// get all info
			echo GetAllCreditReort();
			break;
		case 3:	// delete 
			if(!empty($_GET["id"])) { echo DeleteCreditReort($_GET["id"]); } else { echo 0; };
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["reportID"]) || isset($_POST["reportID"])){
		echo json_encode(SaveCreditReport());
	}
}

// Insert or update
function SaveCreditReport()
{
	global $user_id;
	$id = htmlspecialchars($_POST['reportID'],ENT_QUOTES, "ISO-8859-1");
	$supplier = htmlspecialchars($_POST['supplier'],ENT_QUOTES, "ISO-8859-1");
	$creditReportDate = htmlspecialchars($_POST['creditReportDate'],ENT_QUOTES, "ISO-8859-1");
    $creditReportDate = date('Y-m-d', strtotime($creditReportDate));
	$reportExpiryDate = htmlspecialchars($_POST['reportExpiryDate'],ENT_QUOTES, "ISO-8859-1");
    $reportExpiryDate = date('Y-m-d', strtotime($reportExpiryDate));
	$creditReportCharge = htmlspecialchars($_POST['creditReportCharge'],ENT_QUOTES, "ISO-8859-1");
    $creditReportCharge = str_replace(",", "", $creditReportCharge);
	$vatOnCharges = htmlspecialchars($_POST['vatOnCharges'],ENT_QUOTES, "ISO-8859-1");
    $vatOnCharges = str_replace(",", "", $vatOnCharges);
	$rebate = htmlspecialchars($_POST['rebate'],ENT_QUOTES, "ISO-8859-1");
    $rebate = str_replace(",", "", $rebate);
	$vatRebate = htmlspecialchars($_POST['vatRebate'],ENT_QUOTES, "ISO-8859-1");
    $vatRebate = str_replace(",", "", $vatRebate);
	$chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");
	
	/*$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");*/
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$supplier = stripslashes($supplier);
	$creditReportDate = stripslashes($creditReportDate);
	$reportExpiryDate = stripslashes($reportExpiryDate);
	$creditReportCharge = stripslashes($creditReportCharge);
	$vatOnCharges = stripslashes($vatOnCharges);
	$rebate = stripslashes($rebate);
	$vatRebate = stripslashes($vatRebate);
	$chargeType = stripslashes($chargeType);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$supplier = $objdal->real_escape_string($supplier);
	$creditReportDate = $objdal->real_escape_string($creditReportDate);
	$reportExpiryDate = $objdal->real_escape_string($reportExpiryDate);
	$creditReportCharge = $objdal->real_escape_string($creditReportCharge);
	$vatOnCharges = $objdal->real_escape_string($vatOnCharges);
	$rebate = $objdal->real_escape_string($rebate);
	$vatRebate = $objdal->real_escape_string($vatRebate);
	$chargeType = $objdal->real_escape_string($chargeType);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($id == 0){
    $query = "INSERT INTO `wc_t_credit_report` SET 
        `supplier` = $supplier, 
        `creditReportDate` = '$creditReportDate', 
        `reportExpiryDate` = '$reportExpiryDate', 
        `creditReportCharge` = $creditReportCharge, 
        `vatOnCharges` = $vatOnCharges, 
        `rebate` = $rebate, 
        `vatRebate` = $vatRebate, 
        `chargeType` = $chargeType;";
    $objdal->insert($query);
    } else {

    $query = "UPDATE `wc_t_credit_report` SET 
        `supplier` = $supplier, 
        `creditReportDate` = '$creditReportDate', 
        `reportExpiryDate` = '$reportExpiryDate', 
        `creditReportCharge` = $creditReportCharge, 
        `vatOnCharges` = $vatOnCharges, 
        `rebate` = $rebate, 
        `vatRebate` = $vatRebate, 
        `chargeType` = $chargeType
        WHERE `id` = $id;";
    $objdal->update($query);
	}
    
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return $res;
    
    /*echo $query;*/
    
}

//Delete
function DeleteCreditReort($id)
{
	$objdal=new dal();
	$query="DELETE FROM `wc_t_credit_report` WHERE `id` = $id;";	
	$objdal->delete($query);
	unset($objdal);
	return 1;
}
// Get 1
function GetCreditReort($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_credit_report` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
function GetAllCreditReort()
{
	$objdal=new dal();
	$strQuery="SELECT `id`, `supplier`, `creditReportDate`, `reportExpiryDate`, `creditReportCharge`, `vatOnCharges`, `rebate`, `vatRebate`, `chargeType`  FROM `wc_t_credit_report`
            ORDER BY `id` ASC;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				$table_data = '{"id": "'.$id.'", "supplier": "'.$supplier.'", "creditReportDate": "'.$creditReportDate.'", "reportExpiryDate": "'.$reportExpiryDate.'", "creditReportCharge": "'.$creditReportCharge.'", "vatOnCharges": "'.$vatOnCharges.'", "rebate": "'.$rebate.'", "vatRebate": "'.$vatRebate.'", "chargeType": "'.$chargeType.'"}';
			else
				$table_data .= ',{"id": "'.$id.'", "supplier": "'.$supplier.'", "creditReportDate": "'.$creditReportDate.'", "reportExpiryDate": "'.$reportExpiryDate.'", "creditReportCharge": "'.$creditReportCharge.'", "vatOnCharges": "'.$vatOnCharges.'", "rebate": "'.$rebate.'", "vatRebate": "'.$vatRebate.'", "chargeType": "'.$chargeType.'"}';
		}
	}
	else
	{
		$table_data = '{"id": "&nbsp;", "supplier": "&nbsp;", "creditReportDate": "&nbsp;", "reportExpiryDate": "&nbsp;", "creditReportCharge": "&nbsp;", "vatOnCharges": "&nbsp;", "vatOnCharges": "&nbsp;", "rebate": "&nbsp;","vatRebate": "&nbsp;", "chargeType": "&nbsp;"}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}
?>

