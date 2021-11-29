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
		case 1:	// get single Navigation
			echo json_encode(GetNav($_GET["id"]));
			break;
		case 2:	// get all Navigation
			echo GetAllNavs();
			break;
		case 3:	// delete Navigation
			if(!empty($_GET["id"])) { echo DeleteNav($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// get Navigation List 
			echo GetNavList(1);
			break;
		case 5:	// get Navigation List 
			echo GetNavList(0);
			break;
	}
}

// Case for Insert or Update
if (!empty($_POST)){
	if (!empty($_POST["navId"]) || isset($_POST["navId"])){
        echo SaveNav();
	}
}

// Insert or Update
function SaveNav()
{
	global $user_id;
	$id = htmlspecialchars($_POST['navId'],ENT_QUOTES, "ISO-8859-1");
	$name = htmlspecialchars($_POST['name'],ENT_QUOTES, "ISO-8859-1");
	$url = htmlspecialchars($_POST['url'],ENT_QUOTES, "ISO-8859-1");
	$mask = htmlspecialchars($_POST['mask'],ENT_QUOTES, "ISO-8859-1");
	if(!isset($_POST['category'])){ $category = 0; } else{ $category = 1; };
	if($_POST['parent']!=""){
        $parent = htmlspecialchars($_POST['parent'],ENT_QUOTES, "ISO-8859-1");
	} else{ $parent = 'NULL'; }
	if(!isset($_POST['display'])){ $display = 0; } else{ $display = 1; };
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$name = stripslashes($name);
	$url = stripslashes($url);
	$mask = stripslashes($mask);
	$category = stripslashes($category);
	$parent = stripslashes($parent);
	$display = stripslashes($display);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$name = $objdal->real_escape_string($name);
	$url = $objdal->real_escape_string($url);
	$mask = $objdal->real_escape_string($mask);
	$category = $objdal->real_escape_string($category);
	$parent = $objdal->real_escape_string($parent);
	$display = $objdal->real_escape_string($display);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'Failed to save data!';
	//------------------------------------------------------------------------------
	
	if($id == 0){
		$taskMessage = 'Insert new data.';
        $query = "INSERT INTO `wc_t_navs` SET 
		`name` = '$name', 
		`url` = '$url', 
		`mask` = '$mask',
		`category` = b'$category', 
		`parent` = $parent, 
		`display` = b'$display',
		`createdby` = $user_id;";
		$objdal->insert($query);
        
        $lastNavId = $objdal->LastInsertId();
        
        // by default ADMIN will get the access
        $sql = "INSERT INTO `wc_t_privilege`(`role`, `navid`) VALUES(1, $lastNavId);";
        $objdal->insert($sql);
        
	
    } else {
		$taskMessage = 'Update old data.';
        $query = "UPDATE `wc_t_navs` SET 
		`name` = '$name', 
		`url` = '$url', 
		`mask` = '$mask', 
		`category` = b'$category', 
		`parent` = $parent,
		`display` = b'$display'
		WHERE `id` = $id;";
		$objdal->update($query);
	
    }
	//Add info to activity log table
	addActivityLog(requestUri, $taskMessage, $user_id, 1);
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return json_encode($res);
}

//Delete
function DeleteNav($id)
{
	global $user_id;
	global $loginRole;
	if ($loginRole == 1) {
		$objdal = new dal();
		// Deleting nav entry from navigatiion table
		$query = "DELETE FROM `wc_t_navs` WHERE `id` = $id;";
		$objdal->delete($query);

		// Deleting privilege entries from privilege table
		$query = "DELETE FROM `wc_t_privilege` WHERE `navid` = $id;";
		$objdal->delete($query);
		unset($objdal);
		addActivityLog(requestUri, 'Navigation deleted', $user_id, 1);
		return 1;
	}else{
		return 'Invalid request';
	}
}
// Get 1
function GetNav($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_navs` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
function GetAllNavs()
{
	$objdal = new dal();
	$strQuery="SELECT n.`id`, n.`name`, n.`url`, n.`mask`, n.`category`, n.`parent`, n1.`name` parentname, n.`display`,
        IFNULL(n.`parent`, n.`id`) `navorder` 
        FROM `wc_t_navs` n LEFT JOIN `wc_t_navs` n1 ON n.`parent` = n1.`id`;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				$table_data = '{"id": "'.$id.'", "navorder": "'.$navorder.'", "name": "'.$name.'", "url": "'.$url.'", "mask": "'.$mask.'", "category": "'.$category.'", "parent": "'.$parentname.'", "display": "'.$display.'"}';
			else
				$table_data .= ',{"id": "'.$id.'", "navorder": "'.$navorder.'", "name": "'.$name.'", "url": "'.$url.'", "mask": "'.$mask.'", "category": "'.$category.'", "parent": "'.$parentname.'", "display": "'.$display.'"}';
		}
	}
	else
	{
		$table_data = '{"id": " ", "navorder": " ", "name": " ", "url": " ", "mask": " ", "category": " ", "parent": " ", "display": " "}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}

function GetNavList($nav)
{    
    $objdal=new dal();
    
    if($nav==1){
        $sql = "SELECT `id`, `name` FROM `wc_t_navs` WHERE `parent` is NULL AND `category` = 0;";
    } else {
        $sql = "SELECT `id`, `name` FROM `wc_t_navs` WHERE `parent` is NULL AND `category` = 1;";
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

?>

