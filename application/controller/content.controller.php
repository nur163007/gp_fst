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

$user_id = $_SESSION[session_prefix.'wclogin_userid'];

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// get single user info
			echo json_encode(GetContent($_GET["id"]));
			break;
		case 2:	// get all user info
			echo GetAllContent();
			break;
		case 3:	// delete user
			if(!empty($_GET["id"])) { echo DeleteContent($_GET["id"]); } else { echo 0; };
			break;
	}
}


// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["contentId"]) || isset($_POST["contentId"])){
		echo json_encode(SaveContent());
	}
}

// Insert or update
function SaveContent()
{
	global $user_id;
	$id = htmlspecialchars($_POST['contentId'],ENT_QUOTES, "ISO-8859-1");
	$metaTitle = htmlspecialchars($_POST['metaTitle'],ENT_QUOTES, "ISO-8859-1");
	$mainTitle = htmlspecialchars($_POST['mainTitle'],ENT_QUOTES, "ISO-8859-1");
	$subTitle = htmlspecialchars($_POST['subTitle'],ENT_QUOTES, "ISO-8859-1");
	$content = htmlspecialchars($_POST['content'],ENT_QUOTES, "ISO-8859-1");
	$tag = htmlspecialchars($_POST['tag'],ENT_QUOTES, "ISO-8859-1");
	if($_POST['category']!=""){
		$category = htmlspecialchars($_POST['category'],ENT_QUOTES, "ISO-8859-1");
	} else{ $category = 'NULL'; }
	
	$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
	
	//---To protect MySQL injection for Security purpose----------------------------
	$id = stripslashes($id);
	$metaTitle = stripslashes($metaTitle);
	$mainTitle = stripslashes($mainTitle);
	$subTitle = stripslashes($subTitle);
	$content = stripslashes($content);
	$tag = stripslashes($tag);
	$category = stripslashes($category);
	
	$objdal = new dal();
	
	$id = $objdal->real_escape_string($id);
	$metaTitle = $objdal->real_escape_string($metaTitle);
	$mainTitle = $objdal->real_escape_string($mainTitle);
	$subTitle = $objdal->real_escape_string($subTitle);
	$content = $objdal->real_escape_string($content);
	$tag = $objdal->real_escape_string($tag);
	$category = $objdal->real_escape_string($category);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($id == 0){
		$query = "INSERT INTO `wc_t_content` SET 
    		`metaTitle` = '$metaTitle', 
    		`mainTitle` = '$mainTitle', 
    		`subTitle` = '$subTitle', 
    		`content` = '$content', 
    		`tag` = '$tag', 
    		`category` = $category, 
    		`createdBy` = '$user_id', 
    		`createdFrom` = '$ip';";
		$objdal->insert($query);
	} else {
		// leaving password blank during update will keep password unchanged
        if($password == ''){ $pass = ''; } else { $pass = "`password` = '$password', "; }
		
        $query = "UPDATE `wc_t_content` SET 
    		`metaTitle` = '$metaTitle', 
    		`mainTitle` = '$mainTitle', 
    		`subTitle` = '$subTitle', 
    		`content` = '$content', 
    		`tag` = '$tag', 
    		`category` = $category
    		WHERE `id` = $id;";
		$objdal->update($query);
	}
	
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return $res;
    
}

//Delete
function DeleteContent($id)
{
	$objdal=new dal();
	$query="DELETE FROM `wc_t_content` WHERE `id` = $id;";	
	$objdal->delete($query);
	unset($objdal);
	return 1;
}
// Get 1
function GetContent($id)
{
	$objdal = new dal();
	$query = "SELECT * FROM `wc_t_content` WHERE `id` = $id;";
	$objdal->read($query);
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
	}
	unset($objdal);
	return $res;
}
// Get All
function GetAllContent()
{
	$objdal=new dal();
	$strQuery="SELECT u.`id`, u.`metaTitle`, u.`mainTitle`, u.`subTitle`, u.`content`, u.`tag`, c1.`name` `category`
			FROM `wc_t_content` u LEFT JOIN `wc_t_category` c1 ON u.`category` = c1.`id`
            ORDER BY u.`id` ASC;";
	$objdal->read($strQuery); 
	
	$table_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($table_data))
				$table_data = '{"id": "'.$id.'", "metaTitle": "'.$metaTitle.'", "mainTitle": "'.$mainTitle.'", "subTitle": "'.$subTitle.'", "content": "'.$content.'", "tag": "'.$tag.'", "category": "'.$category.'"}';
			else
				$table_data .= ',{"id": "'.$id.'", "metaTitle": "'.$metaTitle.'", "mainTitle": "'.$mainTitle.'", "subTitle": "'.$subTitle.'", "content": "'.$content.'", "tag": "'.$tag.'", "category": "'.$category.'"}';
		}
	}
	else
	{
		$table_data = '{"id": "&nbsp;", "metaTitle": "&nbsp;", "mainTitle": "&nbsp;", "subTitle": "&nbsp;", "content": "&nbsp;", "tag": "&nbsp;", "category": "&nbsp;"}';
	}
	$table_data = '{"data": ['.$table_data.']}';
	unset($objdal);
	return $table_data;
}
?>

