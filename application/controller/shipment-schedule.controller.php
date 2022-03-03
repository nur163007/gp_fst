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
            echo getShipmentLines($_GET["po"]);
            break;
//        case 2:
//            echo deleteShipSchedule($_GET["pono"],$_GET["ship"]);
//            break;
        case 3:
            if(isset($_GET["pono"]) || !empty($_GET["pono"])) {
                echo GetPiLines($_GET["pono"]);
            }
            break;
        case 4:
            echo getShipmentNo($_GET["pono"]);
            break;

        default:
            break;
    }
}

if (!empty($_POST)){

    if($_POST['userAction']==1){
        echo createSchedule();
    }
    elseif($_POST['userAction']==2){
        echo submitSchedule();
    }elseif($_POST['userAction']==3){
        echo acceptSchedule();
    }elseif($_POST['userAction']==4){
        echo deleteShipSchedule();
    }
}
//create shipment schedule
function createSchedule(){

    /*echo '<pre>';
    var_dump($_POST);
    echo '</pre>';*/

//    $lineno = count($_POST['poLine']);
    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcshipmentType = htmlspecialchars($_POST['lcshipmentType'],ENT_QUOTES, "ISO-8859-1");
    if ( $lcshipmentType == 1 ){
        $lcno = htmlspecialchars('N/A');
    }else{
        $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    }
    $bpo = htmlspecialchars($_POST['bpo'],ENT_QUOTES, "ISO-8859-1");
    $piReqNo = htmlspecialchars($_POST['piReqNo'],ENT_QUOTES, "ISO-8859-1");
    $shipMode = htmlspecialchars($_POST['shippingMode'],ENT_QUOTES, "ISO-8859-1");
    $shipNo = htmlspecialchars($_POST['shipmentNo'],ENT_QUOTES, "ISO-8859-1");
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    $shipmentSchedule = htmlspecialchars($_POST['shipmentSchedule'],ENT_QUOTES, "ISO-8859-1");
    $shipmentSchedule = date('Y-m-d', strtotime($shipmentSchedule));
    $margeShipment = htmlspecialchars($_POST['margeShipment'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------
    $objdal = new dal();

if ($margeShipment == 0) {
    $query = "INSERT INTO `wc_t_shipment_eta` SET
        `pono` = '" . $pono . "',
        `lcNo` = '" . $lcno . "',
        `shipNo` = " . $shipNo . ",
        `shipmode` = '" . $shipMode . "',
        `scheduleETA` = '" . $shipmentSchedule . "',
        `insertby` = " . $user_id . ",
        `insertfrom` = '" . $ip . "';";
    //echo $query;
    $objdal->insert($query, "Could not save shipment ETA data");
}

//    STORE SHIPMENT LINES

    $sqlHeader = "INSERT INTO `shipment_lines`(
                `buyersPo`,
                `poNo`, 
                `shipNo`, 
                `lineNo`, 
                `PIReqNo`,
                `itemCode`, 
                `itemDesc`, 
                `deliveryDate`, 
                `uom`, 
                `unitPrice`, 
                `poQty`, 
                `poTotal`, 
                `delivQty`, 
                `delivTotal`) VALUES ";
    $sqlRows = '';
    $sqlUpdateLineStatus = "UPDATE `pi_lines` SET `status`= 1 WHERE `poNo` = '$pono' AND `lineNo` IN (XXXXX);";
    $lineNOs = "";

    $j = 0;
    /*!
     * Replace REPEATED |(PIPE) sign from string
     * to protect empty row on partial line submission $_POST variable
     * **********************************************************************/
    $regex = "/\|+/";
    $trimmedPoLines = rtrim(preg_replace($regex, '|', $_POST["consolidatedPoLines"]), '|');

    foreach (explode('|', $trimmedPoLines) as $separateRow) {
        $separateCol = explode(';', $separateRow);
        $lineNo = $objdal->sanitizeInput($separateCol[0]);
        $itemCode = $objdal->sanitizeInput($separateCol[1]);
        $itemDesc = $objdal->sanitizeInput($separateCol[2]);
        $poDate = $objdal->sanitizeInput($separateCol[3]);
        $uom = $objdal->sanitizeInput($separateCol[4]);
        $unitPrice = str_replace(",", "", $separateCol[5]);
        $unitPrice = $objdal->sanitizeInput($unitPrice);
        $poQty = str_replace(",", "", $separateCol[6]);
        $poQty = $objdal->sanitizeInput($poQty);
        $poTotal = str_replace(",", "", $separateCol[7]);
        $poTotal = $objdal->sanitizeInput($poTotal);
        $delivQty = $objdal->sanitizeInput($separateCol[8]);
        $delivQty = str_replace(",", "", $delivQty);
        $delivQtyValid = $objdal->sanitizeInput($separateCol[9]);
        $delivQtyValid = str_replace(",", "", $delivQtyValid);
        $delivTotal = str_replace(",", "", $separateCol[10]);
        $delivTotal = $objdal->sanitizeInput($delivTotal);
        /*$ldAmount = $objdal->sanitizeInput($_POST['ldAmnt'][$i], ENT_QUOTES, "ISO-8859-1");
        $ldAmount = str_replace(",", "", $ldAmount);*/
        // insert new again

        if ($sqlRows != '') {
            $sqlRows .= ',';
        }

        $sqlRows .= "(
            '$bpo',
            '$pono', 
            $shipNo, 
            $lineNo, 
            $piReqNo,
            '$itemCode', 
            '$itemDesc', 
            '$poDate', 
            '$uom', 
            $unitPrice, 
            $poQty, 
            $poTotal, 
            $delivQty, 
            $delivTotal)";

        if ($delivQtyValid == $delivQty) {
            if ($lineNOs == "")
            {
                $lineNOs = $lineNo;
            }
            else{
                $lineNOs .= ',' . $lineNo;
            }
        }

        $j++;
        if ($j == 300) {
            $sql = $sqlHeader . $sqlRows . ';';
//            echo $sql;
            $objdal->insert($sql, "Failed to save PI Lines");
            $sqlRows = '';
            $sqlUpdateLineStatus = str_ireplace('XXXXX', $lineNOs, $sqlUpdateLineStatus);
//            echo $sqlUpdateLineStatus;
            $objdal->update($sqlUpdateLineStatus);
            $j = 0;
        }
    }
    // finally if any rest rows to insert
    if ($sqlRows != "") {
//        echo $sqlHeader . $sqlRows;
        $objdal->insert($sqlHeader . $sqlRows . ';', "Failed to save PI Lines");
        $sqlRows = '';

        if ($lineNOs != ""){
            $sqlUpdateLineStatus = str_ireplace('XXXXX', $lineNOs, $sqlUpdateLineStatus);
//        echo $sqlUpdateLineStatus;
            $objdal->update($sqlUpdateLineStatus);
            $sqlUpdateLineStatus = '';
        }

        $j = 0;
    }

    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'LC accepted feedback sent.';
    $res["lastaction"] = encryptId($refId);
    //$res["lastaction"] = encryptId($lastAction);
    return json_encode($res);
}

//submit shipment schedule
function submitSchedule(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $lcshipmentType = htmlspecialchars($_POST['lcshipmentType'],ENT_QUOTES, "ISO-8859-1");
    if ( $lcshipmentType == 1 ){
        $lcno = htmlspecialchars('N/A');
    }else{
        $lcno = htmlspecialchars($_POST['lcno'],ENT_QUOTES, "ISO-8859-1");
    }
    $shipMode = htmlspecialchars($_POST['shippingMode'],ENT_QUOTES, "ISO-8859-1");

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'FAILED!';
    //------------------------------------------------------------------------------
    $objdal = new dal();

    $search = "SELECT count(`shipNo`) as `shipNo` from `wc_t_shipment_eta` WHERE pono = '$pono' AND lcNo = '$lcno';";
    $objdal->read($search);
    $res = '';
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
    }
    $shipNo = $res['shipNo'];

        $action = array(
            'refid' => $refId,
            'pono' => "'".$pono."'",
            'actionid' => action_Shared_Shipment_Schedule,
            'status' => 1,
            'msg' => "'Supplier scheduled for ".$shipNo." shipment against PO# ".$pono."'",
        );
        UpdateAction($action);


    unset($objdal);
    $res["status"] = 1;
    $res["message"] = 'Shipment Schedule shared.';
    //$res["lastaction"] = encryptId($lastAction);
    return json_encode($res);
}

//accept shipment schedule

function acceptSchedule(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");

    $comments = htmlspecialchars($_POST['comments'],ENT_QUOTES, "ISO-8859-1");
    if($comments==""){ $comments = 'NULL'; } else { $comments = "'".$comments."'"; }

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

    //------------------------------------------------------------------------------

    $objdal = new dal();
    $query = "SELECT DISTINCT `shipNo` 
        FROM `wc_t_shipment_eta` WHERE `pono` = '$pono';";
    $objdal->read($query);

    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $action = array(
                'refid' => $refId,
                'pono' => "'".$pono."'",
                'shipno' => $shipNo,
                'actionid' => action_Accepted_Shipment_Schedule,
                'status' => 1,
                'msg' => "'Shipment #".$shipNo." schedule accepted by Buyer against PO# ".$pono."'",
                'usermsg' => $comments,
            );
            UpdateAction($action);
        }
    }
    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Feedback sent to supplier.';
    return json_encode($res);

}

function getShipmentLines($pono){
    $objdal = new dal();
    $query = "SELECT  
                DISTINCT sta.`shipNo`,
                sl.`poNo`,  
                sl.`lineNo`, 
                sl.`itemCode`, 
                sl.`itemDesc`,
                DATE_FORMAT(sta.`scheduleETA`, '%d-%M-%Y')AS `scheduleETA`, 
                sl.`UOM`, 
                sl.`unitPrice`,  
                sl.`delivQty`, 
                sl.`delivTotal` 
                FROM `shipment_lines` as sl
                LEFT JOIN `wc_t_shipment_eta` sta ON sl.`poNo` = sta.`pono` 
                WHERE sl.`poNo` = '$pono' AND sta.`shipNo` = sl.`shipNo` order by sta.`shipNo` asc ;";
    //echo $query;
    $objdal->read($query);

    $res = "";
    if(!empty($objdal->data)){
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    return json_encode($res);

/*    $table_data = '';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);

            $table_data .= '{
                "poNo": "' . $poNo . '", 
                "shipNo": "' . $shipNo . '", 
                "lineNo": "' . $lineNo . '", 
                "itemCode": "' . $itemCode . '", 
                "itemDesc": "' . $itemDesc . '",
                "deliveryDate": "' . $deliveryDate . '",
                "UOM": "' . $UOM . '"
                "unitPrice": "' . $unitPrice . '"
                "delivQty": "' . $delivQty . '"
                "delivTotal": "' . $delivTotal . '"
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
    return $table_data;*/
}

function deleteShipSchedule(){

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $ship = htmlspecialchars($_POST['ship'],ENT_QUOTES, "ISO-8859-1");

    $objdal = new dal();

    $searchLine = "SELECT `lineNo` from `shipment_lines` where `poNo`= '$pono' and `shipNo`= $ship;";
    $objdal->read($searchLine);
    if(!empty($objdal->data)){
        foreach ($objdal->data as $row) {
            $lineNo = $row['lineNo'];

            $lineUpdate = "UPDATE `pi_lines` SET `status` = 0 WHERE `poNo`= '$pono' and `lineNo`= $lineNo;";
            $objdal->update($lineUpdate);
        }
    }

    $lineDelete = "DELETE FROM `shipment_lines` WHERE `poNo`= '$pono' and `shipNo`= $ship;";
    $objdal->delete($lineDelete);

    $etaDelete = "DELETE FROM `wc_t_shipment_eta` WHERE `pono`= '$pono' and `shipNo`= $ship;";
    $objdal->delete($etaDelete);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'Deleted Successfully.';
    $res["lastaction"] = encryptId($refId);

    return json_encode($res);

}

function GetPiLines($pono){
//var_dump("ok");
    $objdal = new dal();

    /*!
     * Query for non Delivered PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            pil.`id`,
            pil.`buyersPo`,
            pil.`PIReqNo`,
            pil.`poNo`,
            DATE_FORMAT(po.`deliveryDate`, '%Y-%m-%d') `deliveryDate`,
            pil.`itemCode`,
            REPLACE(pil.`itemDesc`, CHAR(194), '') AS `itemDesc`,
            pil.`lineNo`,
            ct.`name` AS `currencyName`,
            pil.`uom`,
            pil.`unitPrice`,
            pil.`poQty`,
            pil.`poTotal`,
            pil.`status`,
            IFNULL(SUM(sl.`delivQty`),0) AS `delivQty`,
            IFNULL(SUM(sl.`delivTotal`),0) AS `delivTotal`,
            pil.`poQty` - IFNULL(SUM(sl.`delivQty`),0) AS `delivQtyValid`,
            ROUND(pil.`poTotal` - IFNULL(SUM(sl.`delivTotal`),0), 2) AS `delivAmountValid`
        FROM
            `po` AS po
                INNER JOIN
            `pi_lines` AS pil ON po.poNo = pil.`buyersPo`
            	LEFT JOIN 
            `wc_t_category` ct ON po.`currency` = ct.`id`
                LEFT JOIN
            `shipment_lines` sl ON (pil.`poNo` = sl.`poNo`
                AND pil.`lineNo` = sl.`lineNo`)
        WHERE
            pil.`poNo` = '$pono' AND pil.`status` = 0
        GROUP BY pil.`id`, pil.`poNo`, po.`deliveryDate`, pil.`itemCode`, pil.`itemDesc`, po.`currency`, 
            pil.`lineNo`, pil.`uom`, pil.`unitPrice`, pil.`poQty`, pil.`poTotal`, pil.`status`;";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $nondelivered = $objdal->data;
    } else {
        $nondelivered = array();
    }

    unset($objdal);

    /*!
     * Query for rejected PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            `poNo`, GROUP_CONCAT(`lineNo`) AS `rejectedlines`
        FROM
            `shipment_lines`
        WHERE
            `poNo` = '$pono' AND `status` = 1
        GROUP BY `poNo`";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $rej = $objdal->data;
    } else {
        $rej = array();
    }

    $json = json_encode(array($nondelivered, $rej));
    /*if (!empty($objdal->data)) {
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    $json = json_encode($res);*/
    return $json;

}

function getShipmentNo($pono){

    $objdal = new dal();
    $query = "SELECT DISTINCT `shipNo`,DATE_FORMAT(`scheduleETA`, '%M %d,%Y') `scheduleETA`
        FROM
            `wc_t_shipment_eta`
            WHERE `poNo` = '$pono';";
    //echo $query;
    $objdal->read($query);

    $res = "";
    if(!empty($objdal->data)){
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    return json_encode($res);

}
?>