<?php
if ( !session_id() ) {
	session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 21.01.2016
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// get Navigation
			echo GetNavsPrivilege($_GET['id']);
			break;
		case 2:	// get Role List 
			echo GetUserRole();
			break;
		case 3:	// change access. param: roleid, navid, action[add/remove]
			echo ChangeAccess($_GET['roleid'], $_GET['navid'], $_GET['access']);
			break;
		default:
			break;
	}
}


// Get All
function GetNavsPrivilege($roleid)
{
	$objdal=new dal();
	$strQuery="SELECT n.`id`, n.`name`,  n.`url`, n.`category`, 
    	(SELECT COUNT(p.role) FROM `wc_t_privilege` p WHERE p.`navid` = n.`id` AND p.`role` = $roleid) `access` 
        FROM `wc_t_navs` n;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				$table_data = '{"id": "'.$id.'", "name": "'.$name.'", "url": "'.$url.'", "category": "'.$category.'", "access": "'.$access.'", "roleid": "'.$roleid.'"}';
			else
				$table_data .= ',{"id": "'.$id.'", "name": "'.$name.'", "url": "'.$url.'", "category": "'.$category.'", "access": "'.$access.'", "roleid": "'.$roleid.'"}';
		}
	}
	else
	{
		$table_data = '{"id": "&nbsp;", "name": "&nbsp;", "url": "&nbsp;", "category": "&nbsp;", "access": "&nbsp;", "roleid": "&nbsp;"}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}

function GetUserRole()
{
	$objdal=new dal();
	$strQuery="SELECT id, name FROM `wc_t_roles`;";
	$objdal->read($strQuery); 
    
    $htmldata = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            if($id==1){
                $active = ' class="active"';
            } else {
                $active = '';
            }
            $htmldata .= '<li'.$active.'><a data-toggle="tab" href="#" onclick="RefreshPrivilege('.$id.')">'.$name.'</a></li>';
            //$htmldata .= '<li '.$active.'><a data-toggle="tab" id="role_'.$id.'" class="list-group-item" href="javascript:RefreshPrivilege('.$id.',\'role_'.$id.'\')"><i class="icon wb-user-circle" aria-hidden="true"></i>'.$name.'</a></li>';		
            //$htmldata .= '<li><a class="list-group-item '.$active.'" href="#" data-filter="'.$id.'"><i class="icon wb-user-circle" aria-hidden="true"></i>'.$name.'</a></li>';		
		}
	}
	unset($objdal);
	return $htmldata;
    
}

function ChangeAccess($roleid, $navid, $action)
{
	global $loginRole;
	global $user_id;
	if ($loginRole == 1) {
		$objdal = new dal();

		if ($action == 0) {
			$sql = "DELETE FROM `wc_t_privilege` WHERE `role` = $roleid AND `navid` = $navid;";
			$objdal->delete($sql);
		} elseif ($action == 1) {
			$sql = "INSERT INTO `wc_t_privilege`(`role`, `navid`) VALUES($roleid, $navid);";
			$objdal->insert($sql);
		}
		addActivityLog(requestUri, 'Privilege changed', $user_id, 1);
		unset($objdal);
		return 1;
	}else{
		return 'Invalid request';
	}
}

?>

