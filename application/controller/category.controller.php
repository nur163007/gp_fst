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
		case 1:	// get single Navigation
			echo json_encode(GetCat($_GET["id"]));
			break;
		case 2:	// get all Navigation
			echo GetAllCats();
			break;
		case 3:	// delete Navigation
			if(!empty($_GET["id"])) { echo DeleteCat($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// get Navigation List
            if(!empty($_GET["id"])) {
                if(!empty($_GET["tag"])) {
                    echo GetCatList($_GET["id"],true);
                } else {
                    echo GetCatList($_GET["id"]);
                }
            } else {
                echo GetCatList();
            }
			break;
		case 5:	// get Navigation List 
			echo GetNavList();
			break;
		case 6:	// get Navigation List
            if(!empty($_GET["id"])) {
                if(!empty($_GET["tag"])) {
                    echo GetOptionHtml($_GET["id"],true);
                } else {
                    echo GetOptionHtml($_GET["id"]);
                }
            } else {
                echo GetOptionHtml();
            }			
			break;
		case 7:	// get Meta Text
			echo GetMeta($_GET["id"]);
			break;
		case 8:	// get Iconned list
			echo GetIconnedList($_GET["id"]);
			break;
		case 9:	// get company wise option list
			echo GetOptionListByCompany($_GET["cid"]);
			break;
	}
}

// Case for Insert or Update
if (!empty($_POST)){
	if (!empty($_POST["catId"]) || isset($_POST["catId"])){
        echo SaveCat();
	}
	if (!empty($_POST["lookupSetName"]) || isset($_POST["lookupSetName"])){
        echo createLookupSet();
	}
}

function createLookupSet(){

	global $user_id;

	$name = htmlspecialchars($_POST['lookupSetName'],ENT_QUOTES, "ISO-8859-1");

	$objdal = new dal();

	$query = "INSERT INTO `wc_t_navs` SET 
		`name` = '$name', 
		`category` = b'1',
		`createdby` = $user_id;";
	$objdal->insert($query);
	$lastNavId = $objdal->LastInsertId();
	addActivityLog(requestUri, 'Navigation created', $user_id, 1);
	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	$res["lookupid"] = $lastNavId;
	return json_encode($res);
}

// Insert or Update
function SaveCat()
{
	global $user_id;
	$id = htmlspecialchars($_POST['catId'],ENT_QUOTES, "ISO-8859-1");
	$name = htmlspecialchars($_POST['name'],ENT_QUOTES, "ISO-8859-1");
	if($_POST['category']!=""){
        $category = htmlspecialchars($_POST['category'],ENT_QUOTES, "ISO-8859-1");
	} else{ $category = 'NULL'; }
	if(!isset($_POST['active'])){ $active = 0; } else{ $active = 1; };
	if(!isset($_POST['moderation'])){ $moderation = 0; } else{ $moderation = 1; };
	if($_POST['parent']!=""){
        $parent = htmlspecialchars($_POST['parent'],ENT_QUOTES, "ISO-8859-1");
	} else{ $parent = 'NULL'; }
	$tag = htmlspecialchars($_POST['tag'],ENT_QUOTES, "ISO-8859-1");
	$metatext = htmlspecialchars($_POST['metatext'],ENT_QUOTES, "ISO-8859-1");
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$name = stripslashes($name);
	$category = stripslashes($category);
	$active = stripslashes($active);
	$moderation = stripslashes($moderation);
	$parent = stripslashes($parent);
	$tag = stripslashes($tag);
	$metatext = stripslashes($metatext);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$name = $objdal->real_escape_string($name);
	$category = $objdal->real_escape_string($category);
	$active = $objdal->real_escape_string($active);
	$moderation = $objdal->real_escape_string($moderation);
	$parent = $objdal->real_escape_string($parent);
	$tag = $objdal->real_escape_string($tag);
	$metatext = $objdal->real_escape_string($metatext);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($id == 0){
		$taskMessage = 'Insert new data.';
        $query = "INSERT INTO `wc_t_category` SET 
		`name` = '$name', 
		`menu` = $category, 
		`active` = b'$active', 
		`moderation` = b'$moderation',
		`parent` = $parent, 
		`tag` = '$tag',
		`metatext` = '$metatext',
		`createdby` = $user_id;";
		$objdal->insert($query);
	
    } else {
		$taskMessage = 'Update old data.';
        $query = "UPDATE `wc_t_category` SET 
		`name` = '$name', 
		`menu` = '$category', 
		`active` = b'$active', 
		`moderation` = b'$moderation',
		`parent` = $parent,
		`tag` = '$tag',
		`metatext` = '$metatext'
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
function DeleteCat($id)
{
	global $user_id;
	global $loginRole;
	if ($loginRole == 1) {
		$objdal = new dal();
		$query = "DELETE FROM `wc_t_category` WHERE `id` = $id;";
		addActivityLog(requestUri, 'Category deleted', $user_id, 1);
		$objdal->delete($query);
		unset($objdal);
		return 1;
	}else{
		return 'Invalid request';
	}
}
// Get 1
function GetCat($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_category` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
function GetAllCats()
{
	$objdal=new dal();
	$strQuery="SELECT c.`id`, c.`name`, n.`name` `menu`, c.`active`, c.`moderation`, c.`parent`, c1.`name` parentname, c.`tag`, c.`metatext`
        FROM `wc_t_category` c 
            LEFT JOIN `wc_t_category` c1 ON c.`parent` = c1.`id`
            LEFT JOIN `wc_t_navs` n ON c.`menu` = n.`id`;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				$table_data = '{"id": "'.$id.'", "name": "'.$name.'", "menu": "'.$menu.'", "active": "'.$active.'", "moderation": "'.$moderation.'", "parent": "'.$parentname.'", "tag": "'.$tag.'"}';
			else
				$table_data .= ',{"id": "'.$id.'", "name": "'.$name.'", "menu": "'.$menu.'", "active": "'.$active.'", "moderation": "'.$moderation.'", "parent": "'.$parentname.'", "tag": "'.$tag.'"}';
		}
	}
	else
	{
		$table_data = '{"id": "&nbsp;", "name": "&nbsp;", "menu": "&nbsp;", "active": "&nbsp;", "moderation": "&nbsp;", "parent": "&nbsp;", "tag": "&nbsp;"}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}

function GetCatList($id = 0, $tag = false)
{
    
    $objdal=new dal();
    if($id > 0){
        $where = " WHERE `active` = 1 AND `menu` = $id";
    } else{
        $where = " WHERE `active` = 1";
    }
    if($tag==true){
        $sql = "SELECT `tag` AS `id`, `name` FROM `wc_t_category` $where;";
    } else {
	   $sql = "SELECT `id`, `name` FROM `wc_t_category` $where;";
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

function GetOptionListByCompany($id)
{
    $objdal=new dal();
    
    $sql = "SELECT `contractRef` FROM `wc_t_company` WHERE `id` = $id";
    $objdal->read($sql);
    
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }
    
    unset($objdal->data);
    
    //$sql = "SELECT `id`, `name` FROM `wc_t_category` WHERE `id` IN ($contractRef);";
    $sql = "SELECT `id`, `contractName` FROM `wc_t_contract` WHERE `id` IN ($contractRef);";

	$objdal->read($sql); 
	
    // json
	$jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $jsondata .= ', {"id": "'.$id.'", "text": "'.$contractName.'"}';
		}
	}
    $jsondata .= ']';
	unset($objdal);
	return $jsondata;
}

function GetOptionHtml($id = 0, $tag = false)
{
    
    $objdal=new dal();
    if($id > 0){
        $where = " WHERE `menu` = $id";
    } else{
        $where = "";
    }
    $sql = "SELECT `id`, `name`, tag FROM `wc_t_category` $where;";
    
    $objdal->read($sql); 
	
    // html
    if($tag==true){
    	$htmldata = '<option value="" data-tag=""></option>';
    	//$htmldata = '';
        if(!empty($objdal->data)){
    		foreach($objdal->data as $val){
    			extract($val);
                $htmldata .= '<option value="'.$id.'" data-tag="'.htmlspecialchars_decode($tag,ENT_QUOTES).'">'.htmlspecialchars_decode($name,ENT_QUOTES).'</option>';		
    		}
    	}
    } else{
        $htmldata = '<option value=""></option>';
        //$htmldata = '';
        if(!empty($objdal->data)){
    		foreach($objdal->data as $val){
    			extract($val);
                $htmldata .= '<option value="'.htmlspecialchars_decode($id,ENT_QUOTES).'">'.htmlspecialchars_decode($name,ENT_QUOTES).'</option>';		
    		}
    	}
    }
    unset($objdal);
	return $htmldata;
}

function GetIconnedList($id)
{
    
    $objdal=new dal();

    $sql = "SELECT `id`, `name`, tag FROM `wc_t_category` WHERE `menu` = $id;";
    
    $objdal->read($sql); 
	//<option value="5" data-icon="flag-icon flag-icon-gb">Pound</option>
    $htmldata = '<option value="" data-icon="">Select</option>';
    if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
            $htmldata .= '<option value="'.$id.'" data-icon="'.htmlspecialchars_decode($tag,ENT_QUOTES).'">'.htmlspecialchars_decode($name,ENT_QUOTES).'</option>';		
		}
	}
    unset($objdal);
	return $htmldata;
}

function GetNavList()
{
    
    $objdal=new dal();
	$sql = "SELECT `id`, `name` FROM `wc_t_navs`;";
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

function GetMeta($id)
{
	$objdal = new dal();
	$query = "SELECT `metatext` FROM `wc_t_category` WHERE `id` = $id;";
	$objdal->read($query);
    $meta = '';
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $meta = $metatext;
	}
	unset($objdal);
	return $meta;
}

?>

