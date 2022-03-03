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
			echo json_encode(GetPOTargetGroup($_GET["poid"]));
			break;
		case 2:	// get all user info
			echo GetAllStatus();
			break;
		default:
			break;
	}
}

// Get 1
function GetPOTargetGroup($id)
{
	$objdal = new dal();
	$query = "SELECT p.`poid`, p.`status`, s.`targetform`, s.`targetrole` 
            FROM `wc_t_pi` p INNER JOIN `wc_t_status` s ON p.`status`=s.`id`
            WHERE p.`poid` = '$id';";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
        //$res = $objdal->data[0]['targetrole'];
		//extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
//function GetAllUsers()
//{
//	$objdal=new dal();
//	$strQuery="SELECT u.`id`, u.`username`, u.`firstname`, u.`lastname`, u.`password`, u.`role`, r.`name` `roleName`, 
//				c1.`name` `company`, u.`email`, u.`mobile`, u.`active`, u.`manager`
//			FROM `wc_t_users` u INNER JOIN `wc_t_roles` r ON r.`id`=u.`role`
//                LEFT JOIN `wc_t_category` c1 ON u.`company` = c1.`id`
//            ORDER BY u.`id` ASC;";
//	$objdal->read($strQuery); 
//	
//	$table_data = '';
//	if(!empty($objdal->data)){
//		foreach($objdal->data as $val){
//			extract($val);
//			if(empty($table_data))
//				$table_data = '{"id": "'.$id.'", "username": "'.$username.'", "fullname": "'.trim($firstname.' '.$lastname).'", "company": "'.$company.'", "mobile": "'.$mobile.'", "email": "'.$email.'", "role": "'.$roleName.'", "manager": "'.$manager.'", "active": "'.$active.'"}';
//			else
//				$table_data .= ',{"id": "'.$id.'", "username": "'.$username.'", "fullname": "'.trim($firstname.' '.$lastname).'", "company": "'.$company.'", "mobile": "'.$mobile.'", "email": "'.$email.'", "role": "'.$roleName.'", "manager": "'.$manager.'", "active": "'.$active.'"}';
//		}
//	}
//	else
//	{
//		$table_data = '{"id": "&nbsp;", "username": "&nbsp;", "fullname": "&nbsp;", "company": "&nbsp;", "mobile": "&nbsp;", "email": "&nbsp;", "role": "&nbsp;", "groupleader": "&nbsp;", "active": "&nbsp;"}';
//	}
//	$table_data = '{"data": ['.$table_data.']}';
//	unset($objdal);
//	return $table_data;
//}
//
//function GetUserList($role){
//    $objdal=new dal();
//    if($role==0){
//        $sql = "SELECT `id`, `username` FROM `wc_t_users`;";
//    } else {
//        $sql = "SELECT `id`, `username` FROM `wc_t_users` WHERE `role` = $role;";
//    }
//	
//	$objdal->read($sql); 
//	
//    // json
//	$jsondata = '[';
//    $jsondata .= '{"id": "", "text": ""}';
//	if(!empty($objdal->data)){
//		foreach($objdal->data as $val){
//			extract($val);
//            $jsondata .= ', {"id": "'.$id.'", "text": "'.$username.'"}';		
//		}
//	}
//    $jsondata .= ']';
//	unset($objdal);
//	return $jsondata;
//}
?>

