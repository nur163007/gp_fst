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
		case 1:	// get all info
			echo GetEvents();
			break;
		default:
			break;
	}
}



// Case for Insert and update
if (!empty($_POST)){
	if (!empty($_POST["EventId"]) || isset($_POST["EventId"])){
		echo json_encode(SaveEvent());
	}
}

// Insert or update
function SaveEvent()
{
	global $user_id;
	$eventid = htmlspecialchars($_POST['EventId'],ENT_QUOTES, "ISO-8859-1");
	
	$title = htmlspecialchars($_POST['title'],ENT_QUOTES, "ISO-8859-1");
	$description = htmlspecialchars($_POST['description'],ENT_QUOTES, "ISO-8859-1");
    $start = htmlspecialchars($_POST['start'],ENT_QUOTES, "ISO-8859-1");    
    //$start = str_replace('/', '-', $start);
    $start = date('Y-m-d', strtotime($start));
    
    $end = htmlspecialchars($_POST['end'],ENT_QUOTES, "ISO-8859-1");
    //$end = str_replace('/', '-', $end);
	$end = date('Y-m-d', strtotime($end));
	
	$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
	
	//---To protect MySQL injection for Security purpose----------------------------
	$eventid = stripslashes($eventid);
	$title = stripslashes($title);
	$description = stripslashes($description);
	$start = stripslashes($start);
	$end = stripslashes($end);
	
	$objdal = new dal();
	
	$eventid = $objdal->real_escape_string($eventid);
	$title = $objdal->real_escape_string($title);
	$description = $objdal->real_escape_string($description);
	$start = $objdal->real_escape_string($start);
	$end = $objdal->real_escape_string($end);
	//------------------------------------------------------------------------------
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = Failed, 1 = Success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------
	
	if($userid == 0){
		$query = "INSERT INTO `wc_t_events` SET 
    		`title` = '$title', 
    		`description` = '$description', 
    		`start` = '$start', 
    		`end` = '$end', 
    		`createdBy` = '$user_id', 
    		`createdFrom` = '$ip';";
		$objdal->insert($query);
	} else {
		
	}
	
	unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = 'SUCCESS!';
	return $res;
    
}


function GetEvents()
{
	$objdal = new dal();
	$query = "SELECT `title`, `start`, `end` FROM `wc_t_events`;";
	$objdal->read($query);
	
	$event_data = '';
	if(!empty($objdal->data)){
		foreach($objdal->data as $val){
			extract($val);
			if(empty($event_data))
				$event_data = '{"title": "'.$title.'", "start": "'.$start.'", "end": "'.$end.'"}';
			else
				$event_data .= ',{"title": "'.$title.'", "start": "'.$start.'", "end": "'.$start.'"}';
		}
	}
	else
	{
		$event_data = '{"title": "&nbsp;", "start": "&nbsp;", "end": "&nbsp;"}';
	}
	$event_data = '['.$event_data.']';
	unset($objdal);
	return $event_data;
}

?>

