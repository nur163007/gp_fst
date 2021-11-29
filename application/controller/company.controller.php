<?php
if ( !session_id() ) {
	session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// get single Company data
			echo GetCompany($_GET["id"]);
			break;
		case 2:	// get all company data for datatables
			echo GetAllCompany();
			break;
		case 3:	// delete Company
			if(!empty($_GET["id"])) { echo DeleteCompany($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// get Role List 
			echo GetCompanyList();
			break;
		case 5:	// get Role List 
			echo GetEmails($_GET["id"]);
			break;
	}
}

// Case for Insert or Update
if (!empty($_POST)){
    
	if (!empty($_POST["companyid"]) || isset($_POST["companyid"])){
        echo SaveCompany();
	}
    
}

// Insert
function SaveCompany()
{
	global $user_id;
	global $loginRole;
	if ($loginRole == 1) {
		$objdal = new dal();
		$id = $objdal->sanitizeInput($_POST['companyid']);
		$name = $objdal->sanitizeInput($_POST['name']);
		$address = $objdal->sanitizeInput($_POST['address']);
		$phone = $objdal->sanitizeInput($_POST['phone']);
		$fax = $objdal->sanitizeInput($_POST['fax']);
		$emailTo = $objdal->sanitizeInput($_POST['emailTo']);
		$emailCc = $objdal->sanitizeInput($_POST['emailCc']);
		$concernPerson = $objdal->sanitizeInput($_POST['concernPerson']);
		$designation = $objdal->sanitizeInput($_POST['designation']);
		$contractRef = '';
		foreach ($_POST['contractRef'] as $val) {
			if (strlen($contractRef) > 0) {
				$contractRef .= ',';
			}
			$contractRef .= $objdal->sanitizeInput($val);
		}

		//---return array---------------------------------------------------------------
		$res["status"] = 0;    // 0 = Failed, 1 = Success
		$res["message"] = 'Failed to process the request';
		//------------------------------------------------------------------------------

		if ($id == 0) {
			$taskMessage = 'Insert new data.';
			$query = "INSERT INTO `wc_t_company` SET 
						`name` = '$name', 
						`address` = '$address', 
						`phone` = '$phone', 
						`fax` = '$fax', 
						`emailTo` = '$emailTo', 
						`emailCc` = '$emailCc', 
						`concernPerson` = '$concernPerson', 
						`designation` = '$designation',
						`contractRef` = '$contractRef';";
			$objdal->insert($query);

		} else {
			$taskMessage = 'Update old data.';
			$query = "UPDATE `wc_t_company` SET 
						`name` = '$name', 
						`address` = '$address', 
						`phone` = '$phone', 
						`fax` = '$fax', 
						`emailTo` = '$emailTo', 
						`emailCc` = '$emailCc', 
						`concernPerson` = '$concernPerson', 
						`designation` = '$designation',
						`contractRef` = '$contractRef'
						WHERE `id` = $id;";
			$objdal->update($query);

		}
		//Add info to activity log table
		addActivityLog(requestUri, $taskMessage, $user_id, 1);
		unset($objdal);

		$res["status"] = 1;
		$res["message"] = 'Data saved successfully.';
		return json_encode($res);
	} else {
		$res["status"] = 0;
		$res["message"] = 'Invalid request.';
		return json_encode($res);
	}
}

//Delete
function DeleteCompany($id)
{
	global $loginRole;
	global $user_id;
	if ($loginRole == 1) {
		$objdal = new dal();
		$query = "DELETE FROM `wc_t_company` WHERE `id` = $id;";
		$objdal->delete($query);
		addActivityLog(requestUri, 'Company deleted', $user_id, 1);
		unset($objdal);
		return 1;
	} else {
		return "Invalid request";
	}
}
// Get 1
function GetCompany($id)
{
	global $loginRole;
	if ($loginRole == 1) {
		$objdal = new dal();
		$query = "SELECT * FROM `wc_t_company` WHERE `id` = $id;";
		//echo $query;
		$objdal->read($query);
		if (!empty($objdal->data)) {
			$res = $objdal->data[0];
			extract($res);
		}else{
			$res = '';
		}
		unset($objdal);
		return json_encode($res);
	} else {
		return 'Invalid request';
	}
}
// Get All for DataTables
function GetAllCompany()
{
	global $loginRole;
	if ($loginRole == 1) {
		$objdal = new dal();
		$strQuery = "SELECT 
						`id`, `name`, `address`, `phone`, `fax`, `emailTo`, `emailCc`, `concernPerson`, `designation` 
        			FROM `wc_t_company`;";
		$objdal->read($strQuery);

		$rows = array();
		if (!empty($objdal->data)) {
			foreach ($objdal->data as $row) {
				$rows[] = $row;
			}
		}
		unset($objdal);
		$json = json_encode($rows);
		if ($json == "" || $json == 'null') {
			$json = "[]";
		}
		$table_data = '{"data": ' . $json . '}';
		//return $table_data;
		return $table_data;
	} else {
		return 'Invalid request';
	}
}

function GetCompanyList()
{
	$objdal = new dal();
	$strQuery="SELECT `id`, upper(`name`) as `name` FROM `wc_t_company` ORDER BY `name`;";
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


function GetEmails($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_company` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}
?>

