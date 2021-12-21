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
        case 1:	// get all fx request info
            echo GetAllFxReq();
            break;

        case 2:	// edit fx request info
            if (!empty($_GET["id"])) {
                echo GetFx($_GET["id"]);
            }
            break;
        case 3:	// delete fx request
            if(!empty($_GET["id"])) { echo DeleteFx($_GET["id"]); } else { echo 0; };
            break;
        case 4:	// get fx requisition type
            if(!empty($_GET["id"])) {
                echo GetCatList($_GET["id"]);
            } else {
                echo GetCatList();
            }
            break;
        default:
            break;
    }
}

// Submit new FX
if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
    echo submitFX();
}

// Insert
function submitFX(){
    global $user_id;
    global $loginRole;
    $objdal = new dal();

    $fxId = $objdal->sanitizeInput($_POST['fxId']);
    $supplier = $objdal->sanitizeInput($_POST['supplier_id']);
    $nos = $objdal->sanitizeInput($_POST['nature_of_service']);
    $req_type = $objdal->sanitizeInput($_POST['req_type']);
    $currency = $objdal->sanitizeInput($_POST['currency']);
    $fxvalue = $objdal->sanitizeInput($_POST['value']);
    $fxvalue = str_replace(",","", $fxvalue);
    $fxvaluedate = $objdal->sanitizeInput($_POST['value_date']);
    $fxvaluedate = date('Y-m-d', strtotime($fxvaluedate));
    $remarks = $objdal->sanitizeInput($_POST['remarks']);
    $attachfx = $objdal->sanitizeInput($_POST['attachfx']);
    if ($fxId == ""){

        // insert new fx
        $query = "INSERT INTO `fx_request_primary` SET 
            `supplier_id` = $supplier, 
            `nature_of_service` = $nos,
            `requisition_type` = $req_type,
            `currency` = $currency, 
            `value` = $fxvalue, 
            `value_date` = '$fxvaluedate', 
            `remarks` = '$remarks', 
            `attachment` = '$attachfx', 
            `created_by` = $user_id;";
//    echo $query;
//    die();

        $objdal->insert($query, "Could not submit FX Request data");
//        echo 1;
//        exit();
    }
    else{
        // updated exist fx
        $query = "UPDATE `fx_request_primary` SET 
            `supplier_id` = $supplier, 
            `nature_of_service` = $nos,
            `requisition_type` = $req_type,
            `currency` = $currency, 
            `value` = $fxvalue, 
            `value_date` = '$fxvaluedate', 
            `remarks` = '$remarks', 
            `attachment` = '$attachfx', 
            `created_by` = $user_id
            where `id`=$fxId;";
//    echo $query;
//    die();
        $objdal->update($query, "Could not updated FX Request data");

    }

    $lastFxId = $objdal->LastInsertId();

    fileTransferFxRequest($lastFxId);

    FXAction($lastFxId, action_fx_request_for_fee);

    // Action Log --------------------------------//
    $action = array(
        'pono' => "'FXRFP".$lastFxId."'",
        'actionid' => action_fx_request_for_fee,
        'msg' => "'FX request initiated for Fee payment'",
    );

    UpdateAction($action);
    // End Action Log -----------------------------

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'FX Requisition Submitted Successfully';
    return json_encode($res);

}

function GetAllFxReq()
{
    global $loginRole;
    if ($loginRole == role_foreign_payment_team) {
        $objdal = new dal();
        $strQuery = "SELECT 
                   fx.`id`, c.`name` AS supplier_name, cn.`name` AS nature_of_service, wc.`name` as currency, FORMAT(fx.`value`, 2) AS fx_value,DATE_FORMAT(fx.`value_date`, '%d-%M-%Y')AS `value_date`,u.`firstname` as created_by,fx.`status`
			        FROM `fx_request_primary` fx
			        LEFT JOIN `wc_t_category` wc ON fx.`currency` = wc.`id` 
			        LEFT JOIN `wc_t_users` u ON fx.`created_by` = u.`id`
			        LEFT JOIN `wc_t_company` c ON fx.`supplier_id` = c.`id`
			        LEFT JOIN `wc_t_category` cn ON fx.`nature_of_service` = cn.`id`
			      
                    ORDER BY fx.`id` ASC;";
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
    } else {
        $json = "[]";
        $table_data = '{"data": ' . $json . '}';
        //return $table_data;
        return $table_data;
    }
}
//edit fx request

function GetFx($id)
{

    $objdal = new dal();
    $query = "SELECT 
			      `id`, `supplier_id`,`nature_of_service`,`currency`, FORMAT(`value`, 2) AS fx_value,DATE_FORMAT(`value_date`, '%d-%M-%Y')AS `value_date`,`remarks`,`attachment`
			        FROM `fx_request_primary`
                 WHERE 
                    `id` = $id;";

    $objdal->read($query);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }

    unset($objdal);

    return json_encode($res);
}

//Delete fx request
function DeleteFx($id)
{
    global $loginRole;
    if ($loginRole == 1) {
        $objdal = new dal();
        $query = "UPDATE `fx_request_primary` SET `status`=-1  WHERE `id` = $id;";
        $objdal->update($query);
        unset($objdal);
        return 1;
    } else {
        return "Invalid request";
    }
}
//Ignore
/*function GetCatList($id = 0)
{

    $objdal=new dal();
    if($id > 0){
        $where = " WHERE `active` = 1 AND `id` = $id";
    } else{
        $where = " WHERE `active` = 1";
    }
    $sql = "SELECT `id`, `name` FROM `wc_t_category` $where;";
    $objdal->read($sql);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal);
    return json_encode($res);
}*/
?>