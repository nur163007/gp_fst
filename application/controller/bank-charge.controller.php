<?php
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");


if (!empty($_POST)){
//    var_dump($_POST);
//    exit();
        echo SaveChargeBank();
}

function SaveChargeBank(){
    global $loginRole;
    $objdal = new dal();

    $lcissuerbank = htmlspecialchars($_POST['LcIssuingBank'],ENT_QUOTES, "ISO-8859-1");
    $cableCharge = htmlspecialchars($_POST['cableCharge'],ENT_QUOTES, "ISO-8859-1");
    $cableCharge = str_replace(",", "", $cableCharge);
    $stampCharge = htmlspecialchars($_POST['stampCharge'],ENT_QUOTES, "ISO-8859-1");
    $stampCharge = str_replace(",", "", $stampCharge);
    $nonVatOtherCharge = htmlspecialchars($_POST['nonVatOtherCharge'],ENT_QUOTES, "ISO-8859-1");
    $nonVatOtherCharge = str_replace(",", "", $nonVatOtherCharge);
    $otherCharge = htmlspecialchars($_POST['otherCharge'],ENT_QUOTES, "ISO-8859-1");
    $otherCharge = str_replace(",", "", $otherCharge);

    $query = "INSERT INTO `bank_charges` (`BankId`,`CableCharge`,`OtherCharge`,`StampCharge`,`NonVatOtherCharge`)
                VALUES ($lcissuerbank,$cableCharge,$otherCharge,$stampCharge,$nonVatOtherCharge);";
    $objdal->insert($query);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Data saved successfully.';
    return json_encode($res);

}

?>
