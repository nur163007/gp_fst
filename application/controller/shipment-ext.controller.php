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
    switch($_GET["action"])
    {
        case 1:
            echo getShipList($_GET["po"]);
            break;
    }
}
/*For Insert and Update*/
if (!empty($_POST)){

    if (!empty($_POST["noship"]) || isset($_POST["noship"])){
        echo executeQuery($_POST["poList"], $_POST["noship"] );
    }
}

function executeQuery($po, $noship){

    $objdal = new dal();
    $query = "update wc_t_pi set nofshipallow = $noship where poid='$po';";
    $objdal->update($query);

    $query_1 = "insert into wc_t_shipment_ETA (pono, lcNo, shipNo, shipmode, scheduleETA, insertby, inserton, insertfrom, status)
                SELECT pono, lcNo, $noship, shipmode, curdate(), insertby, now(), insertfrom, 0 
                FROM wc_t_shipment_ETA where pono = '$po' Limit 1;";
    //echo $query_1;
    $objdal->insert($query_1);

    $query_2 = "insert into wc_t_action_log (`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `UserMsg`, `XRefID`, `PI`, `shipNo`, `TargetForm`, 
                `ActionBy`, `ActionByRole`, `ActionOn`, `BaseActionOn`, `ActionFrom`, `SLADate`) select `RefID`, `PO`, `ActionID`, 0, 
                concat('Shipment #',$noship,' schedule accepted by Buyer against PO# ', '$po'), `UserMsg`, `XRefID`, `PI`, $noship, 
                `TargetForm`, `ActionBy`, `ActionByRole`, `ActionOn`, `BaseActionOn`, `ActionFrom`, `SLADate` 
                from wc_t_action_log where ActionID=58 and PO = '$po' Limit 1;";
    $objdal->insert($query_2);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'SUCCESS!';
    return json_encode($res);
}



/*GET SHIPMENT NUMBER LIST BASED ON PO NUMBER*/
function getShipList($pono)
{
    $objdal=new dal();
    //$sql = "SELECT `shipNo`, `ciNo` FROM `wc_t_shipment` WHERE `pono` = '$pono' ORDER BY `shipNo`;";
    $sql = "SELECT `shipNo` FROM `wc_t_shipment_ETA` WHERE `pono` = '$pono';";
    //echo $sql;
    $objdal->read($sql);

    // json
    $shipNo = '';
    $pos = 0;
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){ $pos++;
            if($pos<count($objdal->data)) {
                $shipNo .= $val['shipNo'].',';
            } else {
                $shipNo .= $val['shipNo'];
            }


        }
    }

    //echo json_encode($shipNo);

    unset($objdal);
    return json_encode($shipNo);
}

?>