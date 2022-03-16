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
		case 1:	// get single user info
			echo GetBank($_GET["id"]);
			break;
		case 2:	// get all user info
			echo GetAllBanks();
			break;
		case 3:	// delete user
			if(!empty($_GET["id"])) { echo DeleteBank($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// get user list
            if(!empty($_GET["type"])) {
                if(!empty($_GET["id"])){
                    echo GetBankList($_GET["type"], $_GET["id"]);
                } else {
                    echo GetBankList($_GET["type"]);
                }
            };
			break;

		case 5:
			echo GetBankListForRFQ();
			break;

		default:
			break;
	}
}


// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["id"]) || isset($_POST["id"])){
		echo SaveBank();
	}
}

// Insert or update
function SaveBank()
{
	global $user_id;
	$id = htmlspecialchars($_POST['id'],ENT_QUOTES, "ISO-8859-1");
	$name = htmlspecialchars($_POST['name'],ENT_QUOTES, "ISO-8859-1");
	$address = htmlspecialchars($_POST['address'],ENT_QUOTES, "ISO-8859-1");
	$manager = htmlspecialchars($_POST['manager'],ENT_QUOTES, "ISO-8859-1");
	$telephone = htmlspecialchars($_POST['telephone'],ENT_QUOTES, "ISO-8859-1");
	$mobile = htmlspecialchars($_POST['mobile'],ENT_QUOTES, "ISO-8859-1");
	$email = htmlspecialchars($_POST['email'],ENT_QUOTES, "ISO-8859-1");
	$website = htmlspecialchars($_POST['website'],ENT_QUOTES, "ISO-8859-1");	
	$type = htmlspecialchars($_POST['type'],ENT_QUOTES, "ISO-8859-1");
	$bank = htmlspecialchars($_POST['bank'],ENT_QUOTES, "ISO-8859-1");
    if($bank==""){$bank = 'NULL';}
	//$servicerank = htmlspecialchars($_POST['servicerank'],ENT_QUOTES, "ISO-8859-1");
	$tag = htmlspecialchars($_POST['tag'],ENT_QUOTES, "ISO-8859-1");
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$name = stripslashes($name);
	$address = stripslashes($address);
	$manager = stripslashes($manager);
	$telephone = stripslashes($telephone);
	$mobile = stripslashes($mobile);
	$email = stripslashes($email);
	$website = stripslashes($website);
	$type = stripslashes($type);
	$bank = stripslashes($bank);
	//$servicerank = stripslashes($servicerank);
	$tag = stripslashes($tag);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$name = $objdal->real_escape_string($name);
	$address = $objdal->real_escape_string($address);
	$manager = $objdal->real_escape_string($manager);
	$telephone = $objdal->real_escape_string($telephone);
	$mobile = $objdal->real_escape_string($mobile);
	$email = $objdal->real_escape_string($email);
	$website = $objdal->real_escape_string($website);
	$type = $objdal->real_escape_string($type);
	$bank = $objdal->real_escape_string($bank);
	//$servicerank = $objdal->real_escape_string($servicerank);
	$tag = $objdal->real_escape_string($tag);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($id <= 0){
		$query = "INSERT INTO `wc_t_bank_insurance` SET 
    		`name` = '$name', 
    		`address` = '$address', 
    		`manager` = '$manager', 
    		`telephone` = '$telephone', 
    		`mobile` = '$mobile', 
    		`email` = '$email', 
    		`website` = '$website', 
    		`type` = '$type', 
    		`bank` = $bank,
    		`tag` = '$tag';";
		$objdal->insert($query);
	} else {
        $query = "UPDATE `wc_t_bank_insurance` SET 
    		`name` = '$name', 
    		`address` = '$address', 
    		`manager` = '$manager', 
    		`telephone` = '$telephone', 
    		`mobile` = '$mobile', 
    		`email` = '$email', 
    		`website` = '$website', 
    		`type` = '$type', 
    		`bank` = $bank,
    		`tag` = '$tag'
    		WHERE `id` = $id;";
		$objdal->update($query);
	}
	
	unset($objdal);
	//echo $query;
    
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
    
}

//Delete
function DeleteBank($id)
{
	$objdal=new dal();
	$query="DELETE FROM `wc_t_bank_insurance` WHERE `id` = $id;";	
	$objdal->delete($query);
	unset($objdal);
	return 1;
}
// Get 1
function GetBank($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_bank_insurance` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return json_encode($res);
}
// Get All
function GetAllBanks()
{
	$objdal=new dal();
	$strQuery="SELECT b.`id`, b.`name`, b.`address`, b.`email`, b.`type`, b.`bank`, b.`servicerank`, b1.`name` AS `bankname`, 
            IFNULL(b.`bank`, b.`id`) `bankorder`
        FROM `wc_t_bank_insurance` b LEFT JOIN `wc_t_bank_insurance` b1 ON b.`bank` = b1.`id`
        ORDER BY b.`id` ASC;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				//$table_data = '{"id": "'.$id.'", "name": "'.$name.'", "address": "'.$address.'", "manager": "'.$manager.'", "telephone": "'.$telephone.'", "mobile": "'.$mobile.'", "email": "'.$email.'", "website": "'.$website.'", "type": "'.$type.'", "bank": "'.$bank.'", "servicerank": "'.$servicerank.'","tag": "'.$tag.'"}';
				$table_data = '{"id": "'.$id.'", "bankorder": "'.$bankorder.'", "name": "'.$name.'", "address": "'.str_replace(array("\n", "\t", "\r"), '', $address).'", "email": "'.$email.'", "type": "'.$type.'", "bank": "'.$bankname.'"}';
			else
				//$table_data .= ',{"id": "'.$id.'", "name": "'.$name.'", "address": "'.$address.'", "manager": "'.$manager.'", "telephone": "'.$telephone.'", "mobile": "'.$mobile.'", "email": "'.$email.'", "website": "'.$website.'", "type": "'.$type.'", "bank": "'.$bank.'",  "servicerank": "'.$servicerank.'", "tag": "'.$tag.'"}';
				$table_data .= ',{"id": "'.$id.'", "bankorder": "'.$bankorder.'", "name": "'.$name.'", "address": "'.str_replace(array("\n", "\t", "\r"), '', $address).'", "email": "'.$email.'", "type": "'.$type.'", "bank": "'.$bankname.'"}';
		}
	}
	else
	{
		$table_data = '{"id": "&nbsp;", "name": "&nbsp;", "address": "&nbsp;", "email": "&nbsp;", "type": "&nbsp;", "bank": "&nbsp;", "servicerank": "&nbsp;"}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}

function GetBankList($type, $bankId=0){
    $objdal=new dal();
    if($bankId==0){
        $sql = "SELECT `id`, `name` FROM `wc_t_company` WHERE `type`='$type';";
    } else {
        $sql = "SELECT `id`, `name` FROM `wc_t_company` WHERE `bank` = $bankId;";
    }
	
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

function GetBankListForRFQ(){

	$objdal=new dal();
	$sql = "SELECT co.id, bi.name FROM wc_t_bank_insurance bi INNER JOIN wc_t_company co ON bi.name = co.name WHERE bi.type = 'bank';";

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
?>

