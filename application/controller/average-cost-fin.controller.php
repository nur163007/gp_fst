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
	switch($_GET["action"])
	{
		case 1:
			echo AvgCostWarehousePreview($_GET['po'],$_GET['shipno'],$_GET['propcost']);    
			break;
		case 2:
			echo getCSV();
			break;
        case 3:
			echo AvgCostWarehouseView($_GET['po'],$_GET['shipno']);    
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
                echo SaveAverageCost();
                break;
            case 2:
                echo notifyWarehouse();
                break;
        }
    }
}

function notifyWarehouse(){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $poid = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
	$shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
	$remark = htmlspecialchars($_POST['Remarks'],ENT_QUOTES, "ISO-8859-1");
    
    // Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'shipno' => $shipno,
        'actionid' => action_Avg_Cost_Cal_Done_by_Fin,
        'status' => 1,
        'usermsg' => "'".$remark."'",
        'msg' => "'Average cost calculation done by Finance against PO# ".$poid."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------
    
    $res["status"] = 1;
	$res["message"] = 'Notification has been sent SUCCESSFULLY';
	return json_encode($res);
    
}


function SaveAverageCost(){
    
    global $user_id;
	global $loginRole;
    
    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
    	$res["message"] = 'Invalid reference code.';
    	return json_encode($res);
    }
    
    $poid = htmlspecialchars($_POST['pono'],ENT_QUOTES, "ISO-8859-1");
	$shipno = htmlspecialchars($_POST['shipno'],ENT_QUOTES, "ISO-8859-1");
    
    $bankChargeCapex = htmlspecialchars($_POST['lcOpenCharge1'],ENT_QUOTES, "ISO-8859-1");
    $bankChargeCapex = str_replace(",","",$bankChargeCapex);
    $insuranceCapex = htmlspecialchars($_POST['insPremium1'],ENT_QUOTES, "ISO-8859-1");
    $insuranceCapex = str_replace(",","",$insuranceCapex);
    $cnfNetPayment = htmlspecialchars($_POST['cnfNetPayment'],ENT_QUOTES, "ISO-8859-1");
    $cnfNetPayment = str_replace(",","",$cnfNetPayment);
    $proportionateCost = htmlspecialchars($_POST['proportionateCost'],ENT_QUOTES, "ISO-8859-1");
    $proportionateCost = str_replace(",","",$proportionateCost);
    
    $objdal = new dal();
    $sql = "UPDATE `wc_t_shipment` SET 
		      `bankChargeCapex` = '$bankChargeCapex',
		      `insuranceCapex` = '$insuranceCapex',
		      `cnfNetPayment` = '$cnfNetPayment',
		      `proportionateCost` = '$proportionateCost'
            WHERE `pono` = '$poid' AND `shipNo`='$shipno';";
    $objdal->update($sql);
    
    unset($objdal);
	
    /*// Action Log --------------------------------//    
    $action = array(
        'refid' => $refId,
        'pono' => "'".$poid."'",
        'shipno' => $shipno,
        'actionid' => action_Avg_Cost_Cal_Done_by_Fin,
        'status' => 1,
        'msg' => "'Average cost calculation done by Finance against PO# ".$poid."'",
    );
    UpdateAction($action);
    // End Action Log -----------------------------*/
    
	$res["status"] = 1;
	$res["message"] = 'Average Cost updated SUCCESSFULLY';
	return json_encode($res);
}

function getCSV()
{
	$file="../../temp/avg_cost_data.csv";
    $csv= file_get_contents($file);
    $array = array_map("str_getcsv", explode("\n", $csv));
    $json = json_encode($array);
    //print_r($json);
    
	$table_data = '{"data": '.$json.'}';
	unset($objdal);
	return $table_data;
}

function AvgCostWarehousePreview($pono, $shipno, $propCost=0)
{
	$objdal = new dal();
    if($propCost<=0){
        $query = "SELECT `pono`,`ipcno`,`poline`,`item`,`desc`,`qty`,`uom`,`price`,`amount`,`curr`
            FROM `wc_t_average_cost` WHERE `pono` = '$pono' AND `shipno` = $shipno;";
    } else {
        $query = "SELECT `pono`,`ipcno`,`poline`,`item`,`desc`,`qty`,`uom`,`price`,`amount`,`curr`,
            ROUND((($propCost/(SELECT SUM(`amount`) FROM `wc_t_average_cost` WHERE `pono` = '$pono' AND `shipno` = $shipno))*`amount`),2) `averagecost`
            FROM `wc_t_average_cost` WHERE `pono` = '$pono' AND `shipno` = $shipno;";
    }
//    echo $query;
	$objdal->read($query);
    $rows = array();
	if(!empty($objdal->data)){
		foreach($objdal->data as $row){
            $rows[] = $row;
		}        
	}
	unset($objdal);
	$json = json_encode($rows);
    $table_data = '{"data": '.$json.'}';
    return $table_data;
}

function AvgCostWarehouseView($pono, $shipno)
{
	$objdal = new dal();
    $query = "SELECT `pono`,`ipcno`,`poline`,`item`,`desc`,`qty`,`uom`,`price`,`amount`,`curr`
            FROM `wc_t_average_cost` WHERE `pono` = '$pono' AND `shipno` = $shipno;";
    
	$objdal->read($query);
    $rows = array();
	if(!empty($objdal->data)){
		foreach($objdal->data as $row){
            $rows[] = $row;
		}        
	}
	unset($objdal);
	$json = json_encode($rows);
    $table_data = '{"data": '.$json.'}';
    return $table_data;
}

?>