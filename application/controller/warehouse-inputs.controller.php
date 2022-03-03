<?php
if ( !session_id() ) {
    session_start();
}
/*
    @Author: Shohel Iqbal
    Created: 12.Mar.2016
    Code fridged on: 
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"]) {
        case 1:
            //echo getUpdateState($_GET["po"], $_GET["shipno"], $_GET["col"]);
            echo checkStepOver($_GET["po"], action_GIT_Receiving_Date_updated, $_GET["shipno"]);
            break;
        default:
            break;
    }
}

if (!empty($_POST)){
    //print_r($_POST);
    
    if(!empty($_POST["userAction"]) || isset($_POST["userAction"])){
        
        switch($_POST["userAction"]){
            case 1:
                echo reject();
                break;
            case 2:
                echo notifyToBuyer();
                break;
            case 3:
                echo updateShipment();
                break;
            case 4:
                echo updateShipment();
                break;
            case 5:
                echo updateShipment();
                break;
            case 6:
                echo importCSV($_POST["csv"],$_POST["po"], $_POST["shipno"]);
                break;
            case 7:
                echo notifyToFinance($_POST["pono"], $_POST["shipno"]);
                break;
            case 8:
                echo mailTemplateTest($_POST["pono"], $_POST["shipno"]);
                break;
            default:
                break;
        }
    }
}

function reject(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    $message = htmlspecialchars($_POST['rejectMessage'],ENT_QUOTES, "ISO-8859-1");
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'actionid' => action_Ship_Doc_Rejected_Warehouse,
        'status' => -1,
        'msg' => "'Average cost updated against PO# ".$pono." and Shipment# ".$shipNo."'",
        'usermsg' => "'".$message."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    $res["status"] = 1;
    $res["message"] = 'Notification sent SUCCESSFULLY.';
    return json_encode($res);

}

function notifyToBuyer(){

    global $user_id;
    global $loginRole;

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipNo = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'actionid' => action_GIT_Receiving_Date_updated,
        'newstatus' => 1,
        'msg' => "'Acknowledgement'",
    );
    UpdateAction($action);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'actionid' => action_Warehouse_Input_Updated_Pending_FN,
        'status' => 1,
        'msg' => "'GIT receiving updated for PO# ".$pono." and Ship# ".$shipNo." to Buyer'",
    );
    UpdateAction($action);

    $action = array(
        'refid' => $refId,
        'pono' => "'".$pono."'",
        'shipno' => $shipNo,
        'actionid' => action_Warehouse_Input_Updated_Pending_Avg_Cost,
        'status' => 1,
        'msg' => "'GIT receiving updated for PO# ".$pono." and Ship# ".$shipNo." to Warehouse'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------

    // insert po lines in avg. cost table
    $sql = "INSERT INTO `wc_t_average_cost` (`pono`, `shipno`, `ipcno`, `poline`, `item`, `desc`, `qty`, `uom`, `price`, `amount`, `curr`) 
            SELECT sl.poNo, sl.shipNo, 
                (select ipcNo from wc_t_shipment where pono = sl.poNo and shipNo = $shipNo), 
                sl.lineNo, sl.itemCode, sl.itemDesc, sl.delivQty, sl.uom, sl.unitPrice, sl.delivTotal,
                (select c.name from wc_t_pi p inner join wc_t_category c on p.currency = c.id where p.poid = sl.`poNo`) curr
            FROM shipment_lines sl where sl.`poNo`='$pono' and sl.`shipNo` = $shipNo;";
    $objdal = new dal();
    $objdal->insert($sql);

    $res["status"] = 1;
    $res["message"] = 'Notification sent SUCCESSFULLY.';
    return json_encode($res);

}

function notifyToFinance($poNo, $shipNo){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    // Action Log --------------------------------//
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poNo."'",
        'shipno' => $shipNo,
        'actionid' => action_Avg_Cost_Data_Updated,
        'status' => 1,
        'msg' => "'Average cost updated against PO# ".$poNo." and Shipment# ".$shipNo."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    $res["status"] = 1;
	$res["message"] = 'Notification sent SUCCESSFULLY.';
	return json_encode($res);
}

function getUpdateState($poNo, $shipNo, $colName){
    
    $objdal = new dal();
    $query = "SELECT $colName field1 FROM `wc_t_shipment` WHERE `pono` = $poNo AND `shipNo` = $shipNo;";
    $objdal->read($query);
    $null = 0;
	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        if($field1 != null && $field1 != ""){
            $null = 1;
        }
	}
	unset($objdal);
	return $null;
    
}

// Insert
function updateShipment()
{
	global $user_id;
	global $loginRole;

    $pono = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
    $shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    if($_POST["userAction"]==3){
        $column = 'ipcNo';
        $value = htmlspecialchars($_POST['ipcNo'],ENT_QUOTES, "ISO-8859-1");
        $field = "IPC number";
    }
    elseif($_POST["userAction"]==4){
        $column = 'gitReceiveDate';
        $value = htmlspecialchars($_POST['gitReceiveDate'],ENT_QUOTES, "ISO-8859-1");
        $value = date('Y-m-d', strtotime($value));
        $field = "GIT receive date";
    }
    elseif($_POST["userAction"]==5) {

        $column = 'whArrivalDate';
        $value = htmlspecialchars($_POST['whArrivalDate'], ENT_QUOTES, "ISO-8859-1");
        $value = date('Y-m-d', strtotime($value));
        $field = "Date of Actual arrival at warehouse";

        //Get certificate payment info of this PO
        $objdal = new dal();
        $query = "SELECT p.`docName`, p.`paymentPercent`, pt.`id` AS `termId`
                    FROM `wc_t_shipment` s
                    INNER JOIN `wc_t_payment` p ON s.`lcNo` = p.`LcNo`
                    INNER JOIN `wc_t_payment_terms` pt ON pt.`pono` = s.`pono`
                    WHERE s.`pono` = '$pono' AND s.`shipNo` = $shipno LIMIT 1;";
//        var_dump($query);
//        exit();
        $paymentInfo = $objdal->getRow($query);

        if ($paymentInfo['docName'] == payment_Sight && $paymentInfo['paymentPercent'] == 100) {

//            var_dump('if');
//            exit();
            if (checkStepOver($pono, payment_Sight, $shipno) == 0) {
                $ip = $_SERVER['REMOTE_ADDR'];
                /*$q = "INSERT INTO `wc_t_action_log`(`PO`, `ActionID`, `Status`, `Msg`, `shipNo`, `ActionBy`, `ActionByRole`, `ActionFrom`)
                                        VALUES ('$pono'," . action_Payment_Complete . ",1,'Payment 100% completed & arrived at Warehouse', $shipno, $user_id, $loginRole, '$ip')";
                $objdal->insert($q, "Failed to close the PO");*/

                if (checkStepOver($pono, action_Payment_Complete, $shipno) == 0) {
                    $action = array(
                        'pono' => "'" . $pono . "'",
                        'shipno' => $shipno,
                        'actionid' => action_Payment_Complete,
                        'status' => 0,
                        'newstatus' => 1,
                        'msg' => "'Item arrived at Warehouse.'"
                    );
                    UpdateAction($action);
                }

            }
        }
        else {

//            var_dump('else');
//            exit();
            $queryPt = "SELECT `partname` FROM `wc_t_payment_terms` WHERE `pono` = '$pono' AND `id` > ".$paymentInfo['termId'].";";
            $termInfo = $objdal->getRow($queryPt);
            //var_dump($termInfo);

            switch ($termInfo['partname']) {
                case payment_CAC:
                    $actionId = action_CAC_Payment;
                    break;
                case payment_FAC:
                    $actionId = action_FAC_Payment;
                    break;
                case payment_PAC:
                    $actionId = action_CAC_Payment;
                    break;
                case payment_GLC:
                    $actionId = action_GLC_Payment;
                    break;
                case payment_SIC:
                    $actionId = action_SIC_Payment;
                    break;
                case payment_SIAC:
                    $actionId = action_SIAC_Payment;
                    break;
                case payment_DFS:
                    $actionId = action_DFS_Payment;
                    break;
                default:
                    $actionId = action_CAC_Payment;
                    break;
            }
            if (checkStepOver($pono, $actionId, $shipno) == 0) {
                $action = array(
                    'pono' => "'" . $pono . "'",
                    'shipno' => $shipno,
                    'actionid' => $actionId,
                    'status' => 0,
                    'newstatus' => 1,
                    'msg' => "'Item arrived at Warehouse.'",
                );
                UpdateAction($action);
            }
        }


        unset($objdal);

        // Send PGRD Notification emails
        // 1. Get mail template from template file
//        $contents=file_get_contents("abc.txt");

        // 2. Get data

        // 3. Replace tags with data from template

        // 4. Call mail function

    }
        
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    
    $objdal = new dal();
	
	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'FAILED!';
	//------------------------------------------------------------------------------

	// Update shipment table
    $query = "UPDATE `wc_t_shipment` SET 
		      `$column` = '$value'
            WHERE `pono` = '$pono' AND `shipNo` = $shipno;";
	$objdal->update(trim($query), "Could not update shipment data");
	//echo($query);
    
    unset($objdal);
	
	$res["status"] = 1;
	$res["message"] = $field.' updated SUCCESSFULLY';
	return json_encode($res);
}

function importCSV($csvfile, $pono, $shipno){
    
    global $user_id;
    
    $file = realpath(dirname(__FILE__) . "/../../temp/".$csvfile);
    //$csv = file_get_contents($file);
    //$array = array_map("str_getcsv", explode("\n", $csv));
    //$json = json_encode($array);
    
    //get the csv file
    //$file = $_FILES[csv][tmp_name];
    $handle = fopen($file,"r");
    $sql = "";
    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");	
    //loop through the csv file and insert into database
    $objdal = new dal();
    
    // Deleting OLD data
    $sql = "DELETE FROM `wc_t_average_cost` WHERE `pono`='$pono' AND `shipno`=$shipno;";
    $objdal->delete($sql);
    $row = 1;
    
    while ($data = fgetcsv($handle,1000,",","\"")){
        if($row>1){
            if ($data[0]) {
                $sql = "INSERT INTO `wc_t_average_cost` SET 
                    `pono` = '".replaceRegex($pono)."',
                    `shipno` = ".replaceRegex($shipno).",
                    `ipcno` = ".addslashes(replaceRegex($data[1])).",
                    `poline` = ".addslashes(replaceRegex($data[2])).",
                    `item` = ".addslashes(replaceRegex($data[3])).",
                    `desc` = '".addslashes(replaceRegex($data[4]))."',
                    `qty` = ".addslashes(replaceRegex($data[5])).",
                    `uom` = '".addslashes(replaceRegex($data[6]))."',
                    `price` = ".addslashes(str_replace(",","",replaceRegex($data[7]))).",
                    `amount` = ".addslashes(str_replace(",","",replaceRegex($data[8]))).",
                    `curr` = '".addslashes(replaceRegex($data[9]))."',
                    `insertedby` = $user_id,
                    `insertedfrom` = '$ip';";
                $objdal->insert($sql, "Could not store average cost data");
            }
        }
        $row++;
    } 
    
	unset($objdal);
    //return $sql;
	$res["status"] = 1;
	$res["message"] = 'CSV imported SUCCESSFULLY';
	return json_encode($res);
}

function mailTemplateTest($pono, $shipno)
{
    // Send PGRD Notification emails
    // 1. Get mail template from template file
    try {
        $contents = file_get_contents("../templates/letter_template/PGRD_Notification.html");
    } catch (Exception $err){
        return $err;
    }
    // 2. Get data
    $sql = "select u1.firstname `pruser`, u2.firstname `buyer`, s.ipcNo, poid, s.whArrivalDate, s.noOfBoxes, '' `remarks`,
            c1.name `supplier`, '' `prno`, s.mawbNo, s.hawbNo, s.blNo, s.invoiceQty, 
            concat(u1.email, ',', u2.email, ',', c1.emailTo) `emailTo`, c1.emailCc
            from wc_t_pi p 
				inner join wc_t_shipment s on (p.poid = s.pono and s.shipNo=$shipno)
                inner join wc_t_users u1 on p.pruserto = u1.id
                inner join wc_t_users u2 on p.createdby = u2.id
                inner join wc_t_company c1 on p.supplier = c1.id
            WHERE p.poid = '$pono';";
//    echo $sql;
//    exit();
    $objdal = new dal();

    $data = $objdal->getRow($sql);

    unset($objdal);
    // 3. Replace tags with data from template
    $contents = str_ireplace('##PRUSER##', $data['pruser'], $contents);
    $contents = str_ireplace('##BUYER##', $data['buyer'], $contents);
    $contents = str_ireplace('##IPCNO##', $data['ipcNo'], $contents);
    $contents = str_ireplace('##PONO##', $data['poid'], $contents);
    $contents = str_ireplace('##RECEIVINGDATE##', $data['whArrivalDate'], $contents);
    $contents = str_ireplace('##BOXQTY##', $data['noOfBoxes'], $contents);
    $contents = str_ireplace('##REMARKS##', $data['remarks'], $contents);
    $contents = str_ireplace('##SUPPLIER##', $data['supplier'], $contents);
    $contents = str_ireplace('##PRNO##', $data['prno'], $contents);
    $contents = str_ireplace('##MAWBILL##', $data['mawbNo'].'/'.$data['hawbNo'], $contents);
    $contents = str_ireplace('##BILLLAD##', $data['blNo'], $contents);
    $contents = str_ireplace('##INVQTY##', $data['invoiceQty'], $contents);
    // 4. Call mail function

//    $res = wcMailFunctionWH()

    return $contents;
}


?>

