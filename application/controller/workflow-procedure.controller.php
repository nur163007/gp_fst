<?php
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

//echo $_GET["action"];

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// get all action
            echo GetAction();
            break;
        case 2:	// get all action
            echo GetDoneActionBy();
            break;
        case 3:	// get all action
            echo GetPendingActionTo();
            break;
        case 4:	// get all action
            echo json_encode(GetActionData($_GET["id"]));
            break;
        default:
            break;
    }
}

if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
    if (!empty($_POST["actionId"]) || isset($_POST["actionId"])){
        echo SaveAction();
    }
}

function SaveAction()
{

    global $user_id;

    $actionid = htmlspecialchars($_POST['actionId'],ENT_QUOTES, "ISO-8859-1");
    $id = htmlspecialchars($_POST['id'],ENT_QUOTES, "ISO-8859-1");
    $actionDone = htmlspecialchars($_POST['actionDone'],ENT_QUOTES, "ISO-8859-1");
    $actionDoneBy = htmlspecialchars($_POST['actionDoneBy'],ENT_QUOTES, "ISO-8859-1");
    $actionPending = htmlspecialchars($_POST['actionPending'],ENT_QUOTES, "ISO-8859-1");
    $actionPendingTo = htmlspecialchars($_POST['actionPendingTo'],ENT_QUOTES, "ISO-8859-1");
    $cc = htmlspecialchars($_POST['cc'],ENT_QUOTES, "ISO-8859-1");
    $targetForm = htmlspecialchars($_POST['targetForm'],ENT_QUOTES, "ISO-8859-1");

    if($_POST['sla']!=""){
        $sla = htmlspecialchars($_POST['sla'],ENT_QUOTES, "ISO-8859-1");
    }else{
        $sla = 0;
    }
    if($_POST['stage']!=""){
        $stage = htmlspecialchars($_POST['stage'],ENT_QUOTES, "ISO-8859-1");
    }else{
        $stage = 0;
    }
    if($_POST['serialNo']!=""){
        $serialNo = htmlspecialchars($_POST['serialNo'],ENT_QUOTES, "ISO-8859-1");
    }else{
        $serialNo = 0;
    }

    if(!isset($_POST['isRejected'])){ $reject = 0; } else{ $reject = 1; }

    //---To protect MySQL injection for Security purpose----------------------------
    $actionid = stripslashes($actionid);
    $id = stripslashes($id);
    $actionDone = stripslashes($actionDone);
    $actionDoneBy = stripslashes($actionDoneBy);
    $actionPending = stripslashes($actionPending);
    $actionPendingTo = stripslashes($actionPendingTo);
    $cc = stripslashes($cc);
    $targetForm = stripslashes($targetForm);
    $sla = stripslashes($sla);
    $stage = stripslashes($stage);
    $serialNo = stripslashes($serialNo);
    $reject = stripslashes($reject);

    $objdal = new dal();

    $actionid = $objdal->real_escape_string($actionid);
    $id = $objdal->real_escape_string($id);
    $actionDone = $objdal->real_escape_string($actionDone);
    $actionDoneBy = $objdal->real_escape_string($actionDoneBy);
    $actionPending = $objdal->real_escape_string($actionPending);
    $actionPendingTo = $objdal->real_escape_string($actionPendingTo);
    $cc = $objdal->real_escape_string($cc);
    $targetForm = $objdal->real_escape_string($targetForm);
    $sla = $objdal->real_escape_string($sla);
    $stage = $objdal->real_escape_string($stage);
    $serialNo = $objdal->real_escape_string($serialNo);
    $reject = $objdal->real_escape_string($reject);

    //------------------------------------------------------------------------------

    if($actionid == 0){
        $taskMessage = 'Insert new data.';
        $query = "INSERT INTO `wc_t_action` SET 
		`ID` = '$id', 
		`ActionDone` = '$actionDone', 
		`ActionDoneBy` = '$actionDoneBy',
		`ActionPending` = '$actionPending',
		`ActionPendingTo` = '$actionPendingTo',
		`cc` = '$cc',
		`TargetForm` = '$targetForm',
		`SLA` = $sla,
		`stage` = '$stage',
		`serialNo` = $serialNo, 
		`isRejected` = $reject;";
        $objdal->insert($query);

    } else {

        $taskMessage = 'Update old data.';
        $query = "UPDATE `wc_t_action` SET 
		`ActionDone` = '$actionDone', 
		`ActionDoneBy` = '$actionDoneBy',
		`ActionPending` = '$actionPending',
		`ActionPendingTo` = '$actionPendingTo',
		`cc` = '$cc',
		`TargetForm` = '$targetForm',
		`SLA` = $sla,
		`stage` = '$stage',
		`serialNo` = $serialNo, 
		`isRejected` = $reject
		WHERE `ID` = $actionid;";
        $objdal->update($query);

    }
    //Add info to activity log table
    addActivityLog(requestUri, $taskMessage, $user_id, 1);
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);
}

function GetAction(){

    $objdal = new dal();

    $strQuery = "SELECT a.`ID`, a.`ActionDone`, r1.`name` AS `ActionDoneBy`, a.`ActionPending`, r2.`name` AS `ActionPendingTo`, a.`TargetForm`, a.`stage`,a.`serialNo` 
        FROM `wc_t_action` AS a 
        LEFT JOIN `wc_t_roles` r1 ON a.`ActionDoneBy` = r1.`id`
        LEFT JOIN `wc_t_roles` r2 ON a.`ActionPendingTo` = r2.`id`;";
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
}

function GetDoneActionBy(){
    $objdal=new dal();
        $sql = "SELECT `id`, `name` FROM `wc_t_roles`;";
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

function GetPendingActionTo(){
    $objdal=new dal();
    $sql = "SELECT `id`, `name` FROM `wc_t_roles`;";
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

function GetActionData($id){
    $objdal = new dal();
    $query = "SELECT a.*,r1.`name` as `ActionBy`,r2.`name` as `PendingTo`
              FROM `wc_t_action` AS a
              LEFT JOIN `wc_t_roles` r1 ON a.`ActionDoneBy` = r1.`id`
              LEFT JOIN `wc_t_roles` r2 ON a.`ActionPendingTo` = r2.`id` 
              WHERE a.`ID` = $id;";
    $objdal->read($query);
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    return $res;
}
?>
