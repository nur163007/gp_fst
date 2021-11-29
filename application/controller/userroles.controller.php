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
		case 1:	// get single Role data
			echo json_encode(GetUserRole($_GET["id"]));            
			break;
		case 2:	// get all role data for datatables
			echo GetAllUserRoles();
			break;
		case 3:	// delete user role
			if(!empty($_GET["id"])) { echo DeleteUserRoles($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// get Role List 
			echo GetRoleList();
			break;
	}
}

// Case for Insert or Update
if (!empty($_POST)){
    
	if (!empty($_POST["userroleid"]) || isset($_POST["userroleid"])){
        echo json_encode(SaveUserRoles());
	}
    
}

// Insert
function SaveUserRoles()
{
	global $user_id;
	$id = htmlspecialchars($_POST['userroleid'], ENT_QUOTES, "ISO-8859-1");
	$name = htmlspecialchars($_POST['name'], ENT_QUOTES, "ISO-8859-1");
	if ($_POST['parent'] != "") {
		$parent = htmlspecialchars($_POST['parent'], ENT_QUOTES, "ISO-8859-1");
	} else {
		$parent = 'NULL';
	}
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES, "ISO-8859-1");
	$tag = htmlspecialchars($_POST['tag'], ENT_QUOTES, "ISO-8859-1");

	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$name = stripslashes($name);
	$parent = stripslashes($parent);
	$description = stripslashes($description);
	$tag = stripslashes($tag);

	$objdal = new dal();

	$id = $objdal->real_escape_string($id);
	$name = $objdal->real_escape_string($name);
	$parent = $objdal->real_escape_string($parent);
	$description = $objdal->real_escape_string($description);
	$tag = $objdal->real_escape_string($tag);
	//------------------------------------------------------------------------------

	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED';
	//------------------------------------------------------------------------------

	if ($id == 0) {
		$taskMessage = 'Insert new data.';
		$query = "INSERT INTO `wc_t_roles` SET 
		`name` = '$name', 
		`parent` = $parent, 
		`description` = '$description', 
		`tag` = '$tag';";
		$objdal->insert($query);

	} else {
		$taskMessage = 'Update old data.';
		$query = "UPDATE `wc_t_roles` SET 
		`name` = '$name', 
		`parent` = $parent, 
		`description` = '$description', 
		`tag` = '$tag'
		WHERE `id` = $id;";
		$objdal->update($query);

	}
	//Add info to activity log table
	addActivityLog(requestUri, $taskMessage, $user_id, 1);
	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'SUCCESS';
	return $res;
}

//Delete
function DeleteUserRoles($id)
{
	global $loginRole;
	global $user_id;
	if ($loginRole == 1) {
		$objdal = new dal();
		$query = "DELETE FROM `wc_t_roles` WHERE `id` = $id;";
		$objdal->delete($query);
		addActivityLog(requestUri, 'User role deleted', $user_id, 1);
		unset($objdal);
		return 1;
	} else {
		return "Invalid request";
	}
}
// Get 1
function GetUserRole($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_roles` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All for DataTables
function GetAllUserRoles()
{
    global $loginRole;
	$objdal=new dal();
	$strQuery = "SELECT r.`id`, r.`name`, r.`parent`, r1.`name` parentname, r.`description`, r.`tag` 
        FROM `wc_t_roles` r LEFT JOIN `wc_t_roles` r1 ON r.`parent` = r1.`id`;";
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
    if ($loginRole == role_Admin) {
        return $table_data;
    }else{
        return 'Invalid request';
    }
}
// Generating JSON data for select2 list control
function GetRoleList()
{
	$objdal=new dal();
	$strQuery="SELECT * FROM `wc_t_roles`;";
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
    
//  html
//  $htmldata = '<option value=""></option>';
//	if(!empty($objdal->data)){
//		foreach($objdal->data as $val){
//			extract($val);
//            $htmldata .= '<option value="'.$id.'">'.$name.'</option>';		
//		}
//	}
//	unset($objdal);
//	return $htmldata;
    
}

?>

