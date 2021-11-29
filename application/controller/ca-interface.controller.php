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
        case 1:
            echo GetIndex();
            break;
        default:
            break;
    }
}

function GetIndex(){

    $post_data = $_POST;
    $info = $post_data["val"];
    $cref = rand(100,999);
    $division = $post_data["btrc_div"];
//    var_dump($division);
//    exit();
    if (isset($info)){
        foreach ($info as $i){
          CaStore($i,$cref,$division );
        }

        $res["status"] = 1;
        $res["message"] = 'Request sent to PRA.';

        echo json_encode($res,true);
    }
}
function CaStore($id,$cref,$division){
//var_dump($id);
//die();
//    $messageUser = htmlspecialchars($_POST['messageUser'],ENT_QUOTES, "ISO-8859-1");
//    var_dump($messageUser);
//
    $objdal = new dal();
    $allInfo = "SELECT 
			      `ID`, `ActionID`,`PO`
			        FROM `wc_t_action_log`
			       /* LEFT JOIN `wc_t_action` a ON w.`ActionID` = a.`ID` */
                 WHERE 
                    `RefID` = $id;";
    $objdal->read($allInfo);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }

//    var_dump($res);
    unset($objdal->data);
    $cID = $res["ID"];
    $pID = $res["PO"];

    $objdal = new dal();
    // Action Log --------------------------------//
    $action = array(
        'refid' => $cID,
        'pono' => "'".$pID."'",
        'actionid' => action_Ready_For_Submission,
        'status' => 1,
        'msg' => "'Request Sent to PRA against PO #".$pID."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    $sql = "INSERT INTO `ca_activity_table` SET 
            `ca_ref` = $cref, 
            `po_no` = '$pID',
            `btrc_division` = $division,
            `action_log_ref` = $cID,
            `status` = 1;";
//    echo $query;
//    die();
    $objdal->insert($sql, "Could not submit CA data");
    unset($objdal);



}

?>