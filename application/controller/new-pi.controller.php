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

// Submit new PO
if (!empty($_POST)){
    if(!empty($_POST["poid"]) || isset($_POST["poid"])){
        echo SubmitPO();
    }
}

// Insert
function SubmitPO()
{
    global $user_id;
    global $loginRole;
    $objdal = new dal();

    $refId = decryptId($_POST["refId"]);
    if(!is_numeric($refId)){
        $res["status"] = 0;
        $res["message"] = 'Invalid reference code.';
        return json_encode($res);
    }
    //return $refId;

    $source = $objdal->sanitizeInput($_POST['selectPO'].'PI1');
    $oldPoid = $objdal->sanitizeInput($_POST['poid']);
    $poid = $objdal->sanitizeInput($_POST['poid1'].$_POST['pino']);
    $povalue = $objdal->sanitizeInput($_POST['povalue']);
    $povalue = str_replace(",","", $povalue);

    $podesc = $objdal->sanitizeInput($_POST['podesc']);
    $podesc = str_replace("\r\n", "", str_replace("\t", " ", $podesc));

    $importAs = $objdal->sanitizeInput($_POST['importAs']);
    $supplier = $objdal->sanitizeInput($_POST['supplier']);
    $currency = $objdal->sanitizeInput($_POST['currency']);
    $contractref = $objdal->sanitizeInput($_POST['contractref']);
    $deliverydate = $objdal->sanitizeInput($_POST['deliverydate']);
    $deliverydate = date('Y-m-d', strtotime($deliverydate));
    $draftsendby = $objdal->sanitizeInput($_POST['draftsendby']);
    $draftsendby = date('Y-m-d', strtotime($draftsendby));

    $pruserto = $objdal->sanitizeInput($_POST['prUserEmailTo']);

    $prusercc = '';
    if(isset($_POST['prUserEmailCC'])){
        foreach($_POST['prUserEmailCC'] as $val) {
            if(strlen($prusercc)>0){$prusercc .= ',';}
            $prusercc.= $objdal->sanitizeInput($val);
        }
    }

    $emailto = $objdal->sanitizeInput($_POST['emailto']);
    $emailcc = $objdal->sanitizeInput($_POST['emailcc']);
    $noflcissue = $objdal->sanitizeInput($_POST['noflcissue']);
    $nofshipallow = $objdal->sanitizeInput($_POST['nofshipallow']);
    //if(!isset($_POST['installbysupplier'])){ $installbysupplier = 0; } else{ $installbysupplier = 1; };
    $installbysupplier = $objdal->sanitizeInput($_POST['installBy']);

    $buyersmessage = $objdal->sanitizeInput($_POST['buyersmessage']);

    // attachment data in an 3D array
    $attachpo = $objdal->sanitizeInput($_POST['attachpo']);
    $attachboq = $objdal->sanitizeInput($_POST['attachboq']);
    $attachother = $objdal->sanitizeInput($_POST['attachother']);

    $ip = $_SERVER['REMOTE_ADDR'];

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = failed, 1 = success
    $res["message"] = 'Failed to create new PI';
    //------------------------------------------------------------------------------
    /*echo '<pre>';
        var_dump($_POST);
    echo '</pre>';
    die();*/
    if($oldPoid==""){
        // insert new po
        $query = "INSERT INTO `wc_t_pi` SET 
            `poid` = '".replaceRegex($poid)."', 
            `povalue` = $povalue, 
            `podesc` = '$podesc',
            `importAs` = $importAs,
            `supplier` = $supplier, 
            `currency` = $currency, 
            `contractref` = '$contractref', 
            `deliverydate` = '$deliverydate', 
            `draftsendby` = '$draftsendby', 
            `emailto` = '$emailto', 
            `emailcc` = '$emailcc', 
            `pruserto` = '$pruserto', 
            `prusercc` = '$prusercc', 
            `noflcissue` = $noflcissue, 
            `nofshipallow` = $nofshipallow, 
            `installbysupplier` = $installbysupplier, 
            `createdby` = $user_id, 
            `createdfrom` = '$ip';";
        //echo $query;
        //die();
        $objdal->insert($query, "Failed to create new PI");

    } else{
        // Update existing PO
        $query = "UPDATE `wc_t_pi` SET 
    		`povalue` = $povalue, 
            `podesc` = '$podesc',
            `importAs` = $importAs, 
            `supplier` = $supplier, 
            `currency` = $currency, 
            `contractref` = '$contractref', 
            `deliverydate` = '$deliverydate', 
            `draftsendby` = '$draftsendby', 
            `emailto` = '$emailto', 
            `emailcc` = '$emailcc', 
            `pruserto` = '$pruserto', 
            `prusercc` = '$prusercc', 
            `noflcissue` = $noflcissue, 
            `nofshipallow` = $nofshipallow, 
            `installbysupplier` = $installbysupplier, 
            `modifiedby` = $user_id, 
    		`modifiedfrom` = '$ip'
            WHERE `poid` = '$poid';";
        $objdal->update($query, "Failed to update PO data");
        //echo($query);
    }

    // Getting PR user's cc email address
    $emails = '';
    $prcc = explode(',', $prusercc);
    for($i=0; $i<count($prcc); $i++){
        $query = "SELECT `email` FROM `wc_t_users` WHERE `id` IN ($prusercc);";
        $objdal->read($query);
        if(!empty($objdal->data)){
            foreach($objdal->data as $val){
                extract($val);
                if($emails!="") { $emails .= ','; }
                $emails .= $email;
            }
        }
    }

    // Action Log --------------------------------//

    if($emailcc!=""){
        $allemails = $emailcc.','.$emails;
    }else{
        $allemails = $emails;
    }

    if($oldPoid==""){
        $action = array(
            'pono' => "'".$poid."'",
            'actionid' => action_New_PO_Issued,
            'msg' => "'New PO initiated. PO# ".$poid."'",
            'usermsg' => "'".$buyersmessage."'",
            'mailcc' => $allemails,
        );
    } else {
        $action = array(
            'refid' => $refId,
            'pono' => "'".$poid."'",
            'actionid' => action_Revised_PO_Sent,
            'status' => 1,
            'msg' => "'Revised PO# ".$poid." Sent'",
            'usermsg' => "'".$buyersmessage."'",
            'mailcc' => $allemails,
        );
    }

    $res["message"] = 'Failed to create action log';
    UpdateAction($action);
    // End Action Log -----------------------------

    // insert attachment
    $res["message"] = 'Failed to save attachments!';
    $query = "INSERT INTO `wc_t_attachments`(`poid`, `title`, `filename`, `attachedby`, `attachedfrom`, `groupid`) VALUES 
        ('$poid', 'PO', '$attachpo', $user_id, '$ip', $loginRole),
        ('$poid', 'BOQ', '$attachboq', $user_id, '$ip', $loginRole)";

    if($attachother!=''){
        $query .= ",('$poid', 'Other PO Doc', '$attachother', $user_id, '$ip', $loginRole);";
    }
    $objdal->insert($query, "Failed to save attachments!");
    //echo($query);
    //Transfer file from 'temp' directory to respective 'docs' directory
    $res["message"] = 'Failed to copy attachments';
    cp_POtoPI($source, $poid);

    unset($objdal);

    $res["status"] = 1;
    $res["message"] = 'PO Submitted Successfully';
    return json_encode($res);
}


?>

