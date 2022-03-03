<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1: //Get PO stage list
            echo GetDumpPO();
            break;
        case 2:	// get pending action at user end
            echo GetAllPO($_GET["id"]);
            break;
        case 3:	// delete action log
            if(!empty($_GET["id"])) { echo DeleteAction($_GET["id"]); } else { echo 0; };
            break;
        case 4:	// Get PO detail from PO Dump
            echo GetActionDeleteInfo($_GET["id"]);
            break;
        default:
            break;
    }
}

function GetDumpPO()
{
    $objdal = new dal();
    $strQuery="SELECT
            DISTINCT `PO`
        FROM
             `wc_t_action_log` WHERE NOT `RefID` IS NULL";
    $objdal->read($strQuery);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$PO.'", "text": "'.$PO.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;

}

function GetAllPO($poid)
{
//    var_dump($poid);
//    exit();
    global $loginRole;
    $objdal = new dal();

    $sql = "SELECT al.`ID`, al.`PO`,al.`ActionID`,al.`ActionOn`,al.`RefID`,al.`Status`, a.`ActionDone`,rd.`name` as `ActionDoneBy`, rp.`name` as `ActionPendingTo`
             FROM `wc_t_action_log` AS al 
        	LEFT JOIN `wc_t_action` a ON al.`ActionID` = a.`ID`
            LEFT JOIN `wc_t_roles` rd ON a.`ActionDoneBy` = rd.`id`
            LEFT JOIN `wc_t_roles` rp ON a.`ActionPendingTo` = rp.`id`
            WHERE al.`PO` = '$poid'
            ORDER BY al.`ID` DESC;";
    $objdal->read($sql);

    $table_data = '';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);

            $table_data .= '{
                "ID": "' . $ID . '", 
                "ActionDone": "' . $ActionDone . '", 
                "ActionOn": "' . $ActionOn . '", 
                "ActionDoneBy": "' . $ActionDoneBy . '", 
                "ActionPendingTo": "' . $ActionPendingTo . '",
                "Status": "' . $Status . '",
                "RefID": "' . $RefID . '"
                }';
        }
    }
//    $table_data = '{"data": ['.$table_data.']}';

    $table_data = '';
    if(!empty($objdal->data)){
        $table_data = json_encode($objdal->data);
    }
    $table_data = '{"data": '.$table_data.'}';

    unset($objdal);
    return $table_data;
}

function DeleteAction($id)
{
    global $loginRole;
    $messageUser = htmlspecialchars($_POST['remarks'],ENT_QUOTES, "ISO-8859-1");
    $date = date("Y-m-d h:i:s");
//    var_dump($date);
//    exit();
    $objdal = new dal();

    $query = "UPDATE `wc_t_action_log` set 
             `Status` = 3,
             `isDeleted` = 1,
             `deletedBy` = $loginRole,
             `deletedOn` = '$date',
             `deleteRemarks` = '$messageUser'
            WHERE `ID` = $id;";
    $objdal->update($query);

    $refId = "SELECT `RefID` from `wc_t_action_log` where `id`=$id;";
    $objdal->read($refId);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal->data);
    $referID = $res["RefID"];

    if ($referID != ""){
        $update = "UPDATE `wc_t_action_log` SET
               `Status` = 0
               WHERE `ID` = $referID";
        $objdal->update($update);
    }

//    else{
//        $update = "UPDATE `wc_t_action_log` SET
//               `Status` = 0
//               WHERE `ID` = $id";
//        $objdal->update($update);
//    }


    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Deleted Successfully';
    echo json_encode($res);
}

function GetActionDeleteInfo($id)
{
    global $user_id;
    global $loginRole;

    $objdal = new dal();

    $sql = "SELECT al.`PO`,al.`isDeleted`,DATE_FORMAT(al.`deletedOn`, '%M %d, %Y') AS `deletedOn`,al.`deleteRemarks`, u.`firstname`
            FROM `wc_t_action_log` al 
                LEFT JOIN `wc_t_users` u ON al.`deletedBy` = u.`role`
            WHERE al.`ID` = $id LIMIT 1;";
//    wc_t_contract
    $objdal->read($sql);

    if(!empty($objdal->data)){
        $podetail[0] = $objdal->data[0];
    }

    unset($objdal);

    return json_encode(array($podetail));

}
?>
