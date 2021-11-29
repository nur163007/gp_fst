<?php
if ( !session_id() ) {
    session_start();
}
/*
    @Author: Hasan Masud
    Created: 2020-05-25
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

// 100 % Payment complete
$pono = ['60000246PI1'];
//$actionId = action_Payment_Complete;
$actionId = action_FAC_Payment;
$shipno = [1];
$length = count($pono);
//for ($i = 0; $i<$length; $i++){
//    echo 'PO: '.$pono[$i]. ' Ship: '.$shipno[$i].'</br>';
//}
$objdal = new dal();
for ($i = 0; $i<$length; $i++) {
    if (checkStepOver($pono[$i], $actionId, $shipno[$i]) == 0) {
        $action = array(
            'pono' => "'" . $pono[$i] . "'",
            'shipno' => $shipno[$i],
            'actionid' => $actionId,
            'newstatus' => 1,
            //'msg' => "'Payment made 100 %'",
            'msg' => "'CAC done(Manual Input)'",
        );
        UpdateAction($action);
    }
}
//$importedLines = $objdal->getRow("SELECT ROW_COUNT() AS `importedLines`;");

//
////FAC Pending
//$pono = ['300049659PI1',
//    '300047409PI1',
//    '300046010PI1',
//    '300042918PI1',
//    '300040062PI1',
//    '300040045PI1',
//    '300039996PI1',
//    '300039864PI1',
//    '300039864PI1',
//    '300038989PI1',
//    '300038103PI1',
//    '300027394PI1',
//    '300023696PI1',
//    '300023059PI2',
//    '300019601PI1',
//    '300019601PI1'];
//$actionId = action_FAC_Payment;
//$shipno = [1,
//    1,
//    1,
//    1,
//    1,
//    1,
//    1,
//    1,
//    2,
//    1,
//    1,
//    1,
//    1,
//    1,
//    1,
//    2];
//$length = count($pono);
//$objdal = new dal();
//for ($i = 0; $i<$length; $i++) {
//    if (checkStepOver($pono[$i], $actionId, $shipno[$i]) == 0) {
//        $action = array(
//            'pono' => "'" . $pono[$i] . "'",
//            'shipno' => $shipno[$i],
//            'actionid' => $actionId,
//            'newstatus' => 1,
//            'msg' => "'CAC Payment Done (Manual Input)'",
//        );
//        UpdateAction($action);
//    }
//}

//CAC Pending
//$pono = ['60000246PI1'];
//$actionId = action_CAC_Payment;
//$shipno = [1];
//$length = count($pono);
//$objdal = new dal();
//for ($i = 0; $i<$length; $i++) {
//    if (checkStepOver($pono[$i], $actionId, $shipno[$i]) == 0) {
//        $action = array(
//            'pono' => "'" . $pono[$i] . "'",
//            'shipno' => $shipno[$i],
//            'actionid' => $actionId,
//            'newstatus' => 1,
//            'msg' => "'Arrived at Warehouse - Sight Payment Done(Manual input)'",
//        );
//        UpdateAction($action);
//    }
//}







echo "Queries executed successfully";
unset($objdal);
die();