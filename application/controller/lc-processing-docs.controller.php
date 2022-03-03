<?php
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"]) {
        case 1:
            echo getLCDocs();
            break;
        case 2:
            echo getAllLCDocs($_GET['id']);
            break;
        default:
            break;
    }
}

if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
    switch($_POST["userAction"]){
        case 1:
            if($_POST["id"]){
                echo submitLCDocs();
            }
    }

}

function submitLCDocs(){
    global $loginRole;
    $objdal = new dal();

    $id = $objdal->sanitizeInput($_POST['id']);
    $attahment = $objdal->sanitizeInput($_POST['replaceDocAttachNew']);

    $query = "SELECT `filename`,`docName` from `lc_processing_docs` where `id`=$id;";
    $objdal->read($query);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal->data);
    $docName = $res["docName"];
    $file = $res["filename"];

    if ($file == NULL){
        $sql = "UPDATE `lc_processing_docs` set `filename` = '$attahment' where `id` = $id;";
        $objdal->update($sql);

        $lastLCId = $id;

        fileTransferLCDocs($lastLCId);
    }
    else{
        $sql="INSERT INTO `lc_processing_docs` (docName,filename) VALUES ('$docName','$attahment');";
        $objdal->insert($sql);

        $lastLCId = $objdal->LastInsertId();

        fileTransferLCDocs($lastLCId);
    }



    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'File store successfully';
    return json_encode($res);

}

function getLCDocs(){
    $objdal = new dal();
    $query="SELECT * from `lc_processing_docs`
    WHERE `docName` IN('IRS Documents','Membership Certificates','TIN Certificates','Trade License')
    LIMIT 4;";
    $objdal->read($query);

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

function getAllLCDocs($id){
    global $loginRole;
    $objdal = new dal();
    $query="SELECT `docName` from `lc_processing_docs` WHERE `id` = $id;";
    $objdal->read($query);

    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    unset($objdal->data);
    $docName = $res["docName"];

    if ($loginRole == role_LC_Operation){
        $sql = "SELECT * from `lc_processing_docs`             
        WHERE `docName` = '$docName' order by `id` DESC ;";
        $objdal->read($sql);
    }
    elseif ($loginRole == role_bank_lc){
        $sql = "SELECT * from `lc_processing_docs`             
        WHERE `docName` = '$docName' order by `id` DESC LIMIT 1;";
        $objdal->read($sql);
    }

    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $rows[] = $row;
        }
    }

    unset($objdal->data);

    $json = json_encode($rows);
    echo $json;
}
?>