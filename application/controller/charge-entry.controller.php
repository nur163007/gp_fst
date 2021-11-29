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
			echo json_encode(getChargeDetail($_GET["id"]));
			break;
		case 2:	// get all info
			echo getAllCharges();
			break;
		case 3:	// delete 
			if(!empty($_GET["id"])) { echo deleteCharge($_GET["id"]); } else { echo 0; };
			break;
		default:
			break;
	}
}

// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["chargeId"]) || isset($_POST["chargeId"])){
		echo submitChargeEntry();
	}
}

// Insert or update
function submitChargeEntry()
{
	global $user_id;
	$id = htmlspecialchars($_POST['chargeId'],ENT_QUOTES, "ISO-8859-1");
	
    $chargeDate = htmlspecialchars($_POST['chargeDate'],ENT_QUOTES, "ISO-8859-1");
    $chargeDate = date('Y-m-d', strtotime($chargeDate));
    
	$chargeType = htmlspecialchars($_POST['chargeType'],ENT_QUOTES, "ISO-8859-1");
	
    $amount = htmlspecialchars($_POST['amount'],ENT_QUOTES, "ISO-8859-1");
    $amount = str_replace(",", "", $amount);
    
	$vatOnCharges = htmlspecialchars($_POST['vatOnCharges'],ENT_QUOTES, "ISO-8859-1");
    $vatOnCharges = str_replace(",", "", $vatOnCharges);
	
	$vatRebate = htmlspecialchars($_POST['vatRebate'],ENT_QUOTES, "ISO-8859-1");
    $vatRebate = str_replace(",", "", $vatRebate);
	
	$totalCharge = htmlspecialchars($_POST['totalCharge'],ENT_QUOTES, "ISO-8859-1");
    $totalCharge = str_replace(",", "", $totalCharge);
	
    $relatedTo = htmlspecialchars($_POST['relatedTo'],ENT_QUOTES, "ISO-8859-1");
	$supplier = htmlspecialchars($_POST['supplier'],ENT_QUOTES, "ISO-8859-1");
	$remarks = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");
    
	$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$chargeDate = stripslashes($chargeDate);
	$chargeType = stripslashes($chargeType);
	$amount = stripslashes($amount);
	$vatOnCharges = stripslashes($vatOnCharges);
	$vatRebate = stripslashes($vatRebate);
	$totalCharge = stripslashes($totalCharge);
	$relatedTo = stripslashes($relatedTo);
    $supplier = stripslashes($supplier);
    $remarks = stripslashes($remarks);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$chargeDate = $objdal->real_escape_string($chargeDate);
	$chargeType = $objdal->real_escape_string($chargeType);
	$amount = $objdal->real_escape_string($amount);
	$vatOnCharges = $objdal->real_escape_string($vatOnCharges);
	$vatRebate = $objdal->real_escape_string($vatRebate);
	$totalCharge = $objdal->real_escape_string($totalCharge);
	$relatedTo = $objdal->real_escape_string($relatedTo);
	$supplier = $objdal->real_escape_string($supplier);
	$remarks = $objdal->real_escape_string($remarks);

	if($supplier==''){
		$supplier = 'NULL';
	}
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($id == 0){
        
        $query = "INSERT INTO `wc_t_charges` SET 
            `chargeDate` = '$chargeDate', 
            `chargeType` = $chargeType, 
            `amount` = $amount,
            `vat` = $vatOnCharges, 
            `vatRebate` = $vatRebate, 
            `totalCharge` = $totalCharge,
            `relatedTo` = $relatedTo,  
            `supplier` = $supplier,
            `remarks` = '$remarks';";
        //echo $query;
		$objdal->insert($query);
    
    } else {

        $query = "UPDATE `wc_t_charges` SET 
            `chargeDate` = '$chargeDate', 
            `chargeType` = $chargeType, 
            `amount` = $amount,
            `vat` = $vatOnCharges, 
            `vatRebate` = $vatRebate, 
            `totalCharge` = $totalCharge,
            `relatedTo` = $relatedTo,  
            `supplier` = $supplier,
            `remarks` = '$remarks'
            WHERE `id` = $id;";
        $objdal->update($query);
	}
    
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
    /*echo $query;*/
    
}

//Delete
function deleteCharge($id)
{
	$objdal=new dal();
	$query="DELETE FROM `wc_t_charges` WHERE `id` = $id;";
	$objdal->delete($query);
	unset($objdal);
	return 1;
}
// Get 1
function getChargeDetail($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_charges` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
function getAllCharges()
{
	$objdal=new dal();
	$query="SELECT `id`, DATE_FORMAT(`chargeDate`,'%d-%M-%Y') AS `chargeDate`, `chargeType`, `amount`, `vat`, `vatRebate`, 
			`totalCharge`, `relatedTo`, `supplier`, `remarks`, `insertedBy` 
		FROM `wc_t_charges` ORDER BY `id` ASC;";

	$objdal->read(trim($query));
    $rows = array();
	if(!empty($objdal->data)){
		foreach($objdal->data as $row){
            $rows[] = $row;
		}
	}
	unset($objdal);
	$json = json_encode($rows);
    $table_data = '{"data": '.$json.'}';
    return $table_data;
}
?>

