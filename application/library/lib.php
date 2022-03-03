<?php
require("mail/mail2.php");
require ("csrf_token.php");

if (isset($_SESSION[session_prefix.'wclogin_userid'])){
    $user_id = $_SESSION[session_prefix . 'wclogin_userid'];
    addActivityLog(fullUrl, "Page browse from " . userAgent, $user_id, 1);
    if (empty($user_id)) {
        $res["status"] = 2;    // 0 = Failed, 1 = Success
        $res["message"] = 'Session_Error!';
        return json_encode($res);
    }
} else {
    $res["status"] = 2;    // 0 = Failed, 1 = Success
    $res["message"] = 'Session_Error!';
    return json_encode($res);
}

function html2text($Document) {
    $Rules = array ('@<script[^>]*?>.*?</script>@si',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@([\r\n])[\s]+@',
                    '@&(quot|#34);@i',
                    '@&(amp|#38);@i',
                    '@&(lt|#60);@i',
                    '@&(gt|#62);@i',
                    '@&(nbsp|#160);@i',
                    '@&(iexcl|#161);@i',
                    '@&(cent|#162);@i',
                    '@&(pound|#163);@i',
                    '@&(copy|#169);@i',
                    '@&(reg|#174);@i',
                    '@&#(d+);@e'
             );
    $Replace = array ('',
                      '',
                      '',
                      '',
                      '&',
                      '<',
                      '>',
                      ' ',
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      chr(174),
                      'chr()'
                );
  return preg_replace($Rules, $Replace, $Document);
}

function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
}


define("ENCRYPTION_KEY", "!)(@#$%^&*");
//$string = "shohel iqbal!";

// echo $encrypted = encrypt($string, ENCRYPTION_KEY);
// echo "<br />";
// echo $decrypted = decrypt($encrypted, ENCRYPTION_KEY);

/**
* Returns an encrypted & utf8-encoded
*/
function encrypt($pure_string) {
	$pure_string .= '!*';
	$encryption_key = ENCRYPTION_KEY;
	$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
	return $encrypted_string;
}

/**
* Returns decrypted original string
*/
function decrypt($encrypted_string) {
	$encryption_key = ENCRYPTION_KEY;
	$iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
	return $decrypted_string;
}

function encryptId($id)
{
    $key = md5('My_key-12719', true);
    $id = base_convert($id, 10, 36); // Save some space
    $data = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $id, 'ecb');
    $data = bin2hex($data);

    return $data;
}

function decryptId($encrypted_id)
{
    $key = md5('My_key-12719', true);
    $data = pack('H*', $encrypted_id); // Translate back to binary
    $data = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $data, 'ecb');
    $data = base_convert($data, 36, 10);

    return $data;
}

function UserNotification($params = array()){
    $defaults = array(
        'pono' => 'NULL',
        'lcno' => 'NULL',
        'shipno' => 'NULL',
        'endorseno' => 'NULL',
        'noticeid' => 'NULL',
        'message' => 'NULL',
        'noticeBy' => 'NULL',
        'noticeTo' => 'NULL',
        'noticeOn' => 'NULL',
        'noticeFrom' => 'NULL',
    );
    $params = array_merge($defaults, $params);
    
    $sql = "INSERT INTO `wc_t_notification` SET 
        `pono` = ".$params['pono'].",
        `lcno` = ".$params['lcno'].",
        `shipno` = ".$params['shipno'].",
        `endorseno` = ".$params['endorseno'].",
        `noticeid` = ".$params['noticeid'].",
        `message` = ".$params['message'].",
        `noticeBy` = ".$params['noticeBy'].",
        `noticeTo` = ".$params['noticeTo'].",
        `noticeFrom` = ".$params['noticeFrom'].";";
    //echo $sql;
    $objdal = new dal();
    $objdal->insert($sql);
    unset($objdal);
    return true;
}
// Action functions ----------------------------------------------//
function UpdateAction($params = array()){
    
    $user_id = $_SESSION[session_prefix.'wclogin_userid'];
    $user_group = $_SESSION[session_prefix.'wclogin_role'];
    
    $ip = $_SERVER['REMOTE_ADDR'];
    $defaults = array(
        'refid' => 'NULL',
        'pono' => 'NULL',
        'actionid' => 'NULL',
        'status' => 'NULL',
        'xrefid' => 'NULL',
        'msg' => 'NULL',
        'usermsg' => 'NULL',
        'mailto' => 'NULL',
        'mailcc' => 'NULL',
        'pino' => 'NULL',
        'shipno' => 'NULL',
        'certReqId' => 'NULL',
        'newstatus' => 'NULL',
        'targetform' => 'NULL',
        'pendingto' => 'NULL',
        'pendingtoco' => 'NULL',
    );
    $params = array_merge($defaults, $params);
    
    $objdal = new dal();
    if($params['status']!='NULL'){
        $xRef = getTaggedXRefID($params['refid']);
        if($xRef!=null){
            $sql = "UPDATE `wc_t_action_log` SET 
            `Status` = ".$params['status']."
            WHERE `XRefID` = ".$xRef.";";
        } else{
            $sql = "UPDATE `wc_t_action_log` SET 
            `Status` = ".$params['status']."
            WHERE `ID` = ".$params['refid'].";";
        }
        $objdal->update($sql);
    }

    $newStatus = '';
    $targetForm = '';

    if($params['newstatus']!='NULL'){
        $newStatus = "`Status` = " . $params['newstatus'] . ",";
    }
    if($params['targetform']!='NULL'){
        $targetForm = "`TargetForm` = '" . $params['targetform'] . "'',";
    }

    $newId = 0;
    //echo $sql;
    //`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `XRefID`, `ActionBy`, `ActionByRole`, `ActionOn`, `ActionFrom`
    if($params['actionid']!='NULL'){
        
        $sql = "INSERT INTO `wc_t_action_log` SET 
            `RefID` = ".$params['refid'].",
            `PO` = ".$params['pono'].",
            `ActionID` = ".$params['actionid'].",
            `XRefID` = ".$params['xrefid'].",
            `PI` = ".$params['pino'].",
            `shipNo` = ".$params['shipno'].",
            `certReqId` = ".$params['certReqId'].",
            `Msg` = ".$params['msg'].",
            `UserMsg` = ".$params['usermsg'].",
            `ActionBy` = ".$user_id.",
            `ActionByRole` = ".$user_group.", ".$newStatus." ".$targetForm."
            `ActionFrom` = '".$ip."',
            `pendingTo` = ".$params['pendingto'].",
            `pendingToCo` = ".$params['pendingtoco'].";";
//          echo $sql;
        
        $objdal->insert($sql);
        $newId = $objdal->LastInsertId();

        // Updating SLA datetime
        $sql = "UPDATE `wc_t_action_log` as l SET 
            l.`baseActionOn` = if(l.ActionOn < CAST(CONCAT(YEAR(l.ActionOn), '-', MONTH(l.ActionOn), '-', DAY(l.ActionOn), ' 17:00:00') AS DATETIME), 
                l.ActionOn, 
                CAST(CONCAT(YEAR(date_add(l.ActionOn, INTERVAL 1 DAY)), '-', MONTH(date_add(l.ActionOn, INTERVAL 1 DAY)), '-', DAY(date_add(l.ActionOn, INTERVAL 1 DAY)), ' 10:00:00') AS DATETIME)
            ),
            l.`SLADate` = if(l.ActionOn < CAST(CONCAT(YEAR(l.ActionOn), '-', MONTH(l.ActionOn), '-', DAY(l.ActionOn), ' 17:00:00') AS DATETIME),
                date_add(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY), INTERVAL 
                    if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
                    (if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
                    if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) DAY
                ),
                date_add(date_add(CAST(CONCAT(YEAR(date_add(l.ActionOn, INTERVAL 1 DAY)), '-', MONTH(date_add(l.ActionOn, INTERVAL 1 DAY)), '-', DAY(date_add(l.ActionOn, INTERVAL 1 DAY)), ' 10:00:00') AS DATETIME), INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY), INTERVAL 
                    if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
                    (if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
                    if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) DAY
                )
            )
            WHERE l.`ID` = $newId;";
        //echo $sql;
        //die();
        $objdal->update($sql, "Failed to update action data");

        // Email
        if ($_SERVER['HTTP_HOST'] == 'fst.grameenphone.com') {
            sendActionEmail($newId, $params['mailto'], $params['mailcc']);
        }
        //Add info to activity log table
        addActivityLog(requestUri, $params['msg'], $user_id, 1);
    }

    unset($objdal);
    return $newId;
}

function sendActionEmail($aLogId, $to='', $cc='', $debug=0)
{ // $aLogId = Action Log ID

    $message = "";
    $subject = "";
    $link = "";
    $returnVal = 0;

    $objdal = new dal();

    // get

    $sql = "SELECT 
		l.`PO`, l.`shipNo`, a1.`actionDone`, l.`Msg`, l.`userMsg`, u1.`email` AS `buyersEmail`, l.`ActionID`,
        u2.`email` AS `usersEmail`, c1.`emailTo` AS `suppTo`, c1.`emailCc` AS `suppCc`, a1.`ActionPendingTo`, a1.`targetForm`, 
        if(a1.`cc` is null, 
			(SELECT group_concat(`email` SEPARATOR ',') FROM `wc_t_users` where `role` = a1.`ccGroup`), 
            if((SELECT group_concat(`email` SEPARATOR ',') FROM `wc_t_users` where `role` = a1.`ccGroup`) is null, 
				a1.`cc`, 
                concat(a1.`cc`,',',(SELECT group_concat(`email` SEPARATOR ',') FROM `wc_t_users` where `role` = a1.`ccGroup`)))
            ) AS `additionalCC`,
            cr.`certFinalApprover`,
            u3.`email` AS `cfaEmail`
    FROM `wc_t_action_log` l
    	INNER JOIN `wc_t_pi` po ON l.`PO`=po.`poid`
        INNER JOIN `wc_t_users` u1 ON po.`createdby` = u1.id
        INNER JOIN `wc_t_users` u2 ON po.`pruserto` = u2.id
        INNER JOIN `wc_t_action` a1 ON l.`ActionID` = a1.`ID`
        INNER JOIN `wc_t_company` c1 ON po.`supplier` = c1.`id`
        LEFT JOIN `wc_t_cacfac_request` cr ON cr.`id` = l.`certReqId`
        LEFT JOIN `wc_t_users` u3 ON u3.`id` = cr.`certFinalApprover`
    WHERE l.`ID` = $aLogId;";
    //echo $sql;
    $objdal->read(trim($sql));

    // if already send through parameters------
    if ($to != '' && $to != 'NULL') {
        $to = explode(',', $to);
    } else {
        $to = array();
    }

    if ($cc != '' && $cc != 'NULL') {
        $cc = explode(',', $cc);
    } else {
        $cc = array();
    }
    //-------------------------------------------

    if (!empty($objdal->data)) {

        $res = $objdal->data[0];
        extract($res);

        if ($ActionPendingTo == role_Supplier) {

            $suppTo1 = explode(',', $suppTo);
            $suppCc1 = explode(',', $suppCc);

            if (sizeof($to) > 1) {
                array_merge($to, $suppTo1);
            } else {
                $to = $suppTo1;
            }
            if (sizeof($cc) > 1) {
                array_merge($cc, $suppCc1);
            } else {
                $cc = $suppCc1;
            }
        }

        if ($ActionID == action_TAC_Approved_by_CPO) {

            $suppTo1 = explode(',', $suppTo);
            $suppCc1 = explode(',', $suppCc);

            if (sizeof($cc) > 1) {
                array_merge($cc, $suppTo1);
            } else {
                $cc = $suppTo1;
            }
        }

        if ($ActionPendingTo == role_Buyer) {
            array_push($to, $buyersEmail);
        } else {
            array_push($cc, $buyersEmail);
        }
        if ($ActionPendingTo == role_PR_Users) {
            array_push($to, $usersEmail);
        } else {
            array_push($cc, $usersEmail);
        }

        //Condition for mail to Certificate Final Approver
        if ($ActionPendingTo == role_cert_final_approver) {
            unset($to);
            $to = array($cfaEmail);
        }

        $addiCc1 = explode(',', $additionalCC);
        for ($i = 0; $i < sizeof($addiCc1); $i++) {
            //array_merge($cc, $addiCc1);
            array_push($cc, trim($addiCc1[$i]));
        }

        $subject = $Msg;
        $message = $userMsg;

        if ($shipNo != null) {
            $link = "https://fst.grameenphone.com/" . $targetForm . "?po=" . $PO . "&ship=" . $shipNo . "&ref=" . encryptId($aLogId);
        } else {
            $link = "https://fst.grameenphone.com/" . $targetForm . "?po=" . $PO . "&ref=" . encryptId($aLogId);
        }
        $logref = encryptId($aLogId);
    }

    unset($objdal);
    if ($ActionPendingTo != role_Buyer && $ActionPendingTo != role_Supplier && $ActionPendingTo != role_PR_Users &&
        $ActionPendingTo != role_cert_final_approver) {
        $objdal = new dal();
        $sql = "SELECT `email` FROM `wc_t_users` AS u WHERE u.`role` = $ActionPendingTo";
        $objdal->read($sql);
        if (!empty($objdal->data)) {
            foreach ($objdal->data as $val) {
                extract($val);
                //var_dump($val);
                array_push($to, $email);
                //array_push($to, $val['email']);
            }
        }
        unset($objdal);
    }

    // adding current user and his role's other email
    if ($_SESSION[session_prefix . 'wclogin_role'] != role_Buyer &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_Supplier &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_PR_Users &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_cert_final_approver
    ) {

        $objdal = new dal();
        $sql = "SELECT `email` FROM `wc_t_users` AS u WHERE u.`role` = " . $_SESSION[session_prefix . 'wclogin_role'];
        //unset($objdal->data);
        $objdal->read($sql);

        if (!empty($objdal->data)) {
            foreach ($objdal->data as $val) {
                extract($val);
                if (!in_array($email, $to) && !in_array($email, $cc)) {
                    array_push($cc, trim($email));
                }
            }
        }
    } else {
        $loggedInUserEmail = $_SESSION[session_prefix . 'wclogin_email'];
        if (!in_array($loggedInUserEmail, $to) && !in_array($loggedInUserEmail, $cc)) {
            array_push($cc, trim($loggedInUserEmail));
        }
    }

    unset($objdal);
    $returnVal = 0;

    if ($debug == 1) {
        echo "To: " . json_encode($to) . "<br>CC: " . json_encode($cc);
    } else {
        if ($_SERVER['SERVER_NAME'] != 'localhost') {
        //if ($_SERVER['SERVER_NAME'] == 'https://fst.grameenphone.com') {
            try {
                $returnVal = wcMailFunction($to, $subject, $message, $cc, $link, $logref);
            } catch (Exception $e){
                $returnVal = 0;
            }
        }
    }
    return $returnVal;
}

/*!
 * Updates of 2020-08-13 listed below
 * Removed AND l3.`Status` <> 0
 * As "Accept &amp; Proceed for BTRC Permission" in buyers-piboq.php
 * button not being available to buyer
 * Scenario: 300050196 and 60000128
 * ******************************************************************/
function GetActionRef($ref, $btrcBtn = 0){

    $objdal = new dal();
    $refNum = '';
    if(is_numeric($ref) && !strlen($ref)>4){
        $refNum = $ref;
    } else{
        $refNum = decryptId($ref);
    }
    if ($btrcBtn == 1){
       $adminAction = "AND l2.`ActionID` <> 149";
    }else{
        $adminAction = "";
    }
    //$sql = "SELECT * FROM `wc_t_action_log` WHERE `ID` = ".decryptId($ref);
    $sql = "SELECT l.`ID`,l.`RefID`,l.`Msg`, l.`UserMsg`, l.`ActionID`, a.`ActionDone`,l.`ActionOn`, a.`ActionPendingTo`, 
            IF(a.`ActionPending`='Acknowledgement',IF(l.`Status`=1,'',a.`ActionPending`),a.`ActionPending`) as `ActionPending`,
            l.`Status`, l.`XRefID`, l.`ActionBy`, l.`ActionByRole`, l.`PI`, l.`shipNo`, l.`certReqId`, 
        	(SELECT l2.`ActionID` 
        	    FROM `wc_t_action_log` l2 
        	    WHERE l2.`PO` = l.`PO` 
        	    AND IFNULL(l2.`shipNo`,0) = IFNULL(l.`shipNo`,0)
        	    $adminAction 
        	    ORDER BY l2.`ID` DESC LIMIT 1) AS `1stLastAction`,
            (SELECT l3.`ActionID` 
                FROM `wc_t_action_log` l3 
                WHERE l3.`PO` = l.`PO` 
                AND IFNULL(l3.`shipNo`,0) = IFNULL(l.`shipNo`,0)  
                AND l3.`ID` < 
                    (SELECT l2.`ID` FROM `wc_t_action_log` l2 WHERE l2.`PO` = l.`PO` AND IFNULL(l2.`shipNo`,0) = IFNULL(l.`shipNo`,0) 
                    $adminAction
                    ORDER BY l2.`ID` DESC LIMIT 1) ORDER BY l3.`ID` DESC LIMIT 1) AS `2ndLastAction`,
            (SELECT IFNULL(MAX(s.`shipNo`),0) FROM `wc_t_shipment` s WHERE s.`pono` = l.`PO`) `LastShipment`,
            (SELECT IFNULL(MAX(e.`endNo`),0)  FROM `wc_t_endorsement` e WHERE e.`pono` = l.`PO`) `LastEndorseNo`,
            (SELECT `shipmode` FROM `wc_t_pi` pi WHERE pi.`poid` = l.`PO`) `ShipMode`
        FROM `wc_t_action_log` l LEFT JOIN `wc_t_action` a ON l.`ActionID` = a.`ID`
        WHERE l.`ID` = ".$refNum;
    //echo $sql;
    $objdal->read($sql);
    
    $returnVal = 0;
    
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = $res;
    }
	unset($objdal);
	return $returnVal;
}

function getActionID($refId){
    
    $objdal = new dal();
    
    $sql = "SELECT `ActionID` FROM `wc_t_action_log` WHERE `ID` = $refId";
    
    $objdal->read($sql);
    
    $returnVal = 0;
    
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = $ActionID;
    }
	unset($objdal);
	return $returnVal;
}

function getXRefID($refId){
    
    $objdal = new dal();
    
    $sql = "SELECT `RefID` FROM `wc_t_action_log` WHERE `ID` = $refId";
    
    $objdal->read($sql);
    
    $returnVal = 0;
    
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = $RefID;
    }
	unset($objdal);
	return $returnVal;
}

function getTaggedXRefID($refId){
    
    $objdal = new dal();
    
    $sql = "SELECT `XRefID` FROM `wc_t_action_log` WHERE `ID` = $refId";
    
    $objdal->read($sql);
    
    $returnVal = 0;
    
    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = $XRefID;
    }
	unset($objdal);
	return $returnVal;
}

// Action functions ----------------------------------------------//
function checkStepOver($po, $actionId, $shipNo = null, $info = null, $reject = false){
    // Finding that, is the PO stepped over the action or not
    // return 0 or 1

    $over = 0;
    
    $objdal = new dal();
    if($reject=='false') {
        $query = "SELECT COUNT(`ActionID`) `stepOver`, `Msg`, `UserMsg`
          FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` = $actionId AND `Status` in (0,1)";
    } else {
        $query = "SELECT COUNT(`ActionID`) `stepOver`, `Msg`, `UserMsg`
          FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` = $actionId AND `Status` in (0,1,-1)";
    }
    if($shipNo!=null && $shipNo!=""){
        $query .= " AND `shipNo` = $shipNo";
    }
    $query .= "  GROUP BY `Msg`, `UserMsg`;";

//    echo $query;
    
	$objdal->read($query);

	if(!empty($objdal->data)){
		$res = $objdal->data[0];
		extract($res);
        $over = $stepOver;
	}
	unset($objdal);
    if($info==null){
        return $over;
    } else {
        return json_encode($res);
    }
}

function checkPIFeedbackLevelOver($po){

    // Finding that, is the PO stepped over the action or not
    // return 0 or 1

    $over = 0;

    $objdal = new dal();
    $query = "SELECT SUM(`stepOver`) `stepOver` FROM
        (SELECT COUNT(`ActionID`) `stepOver` FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` in (7,8)
          UNION ALL
        SELECT COUNT(`ActionID`) `stepOver` FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` in (9,10)) a;";
    //echo $query;

    $objdal->read($query);

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $over = $stepOver;
    }
    unset($objdal);

    return $over;
}

function getReportTableData($sql){

    $objdal = new dal();

    $objdal->read(trim($sql));

    if(!empty($objdal->data)){
        // generating column array in JSON format
        $columnArray = '';
        $rowArray = '';
        $dataArray = '';
        $cols = $objdal->data[0];
        foreach ($cols as $key => $value){
            if($columnArray!=""){ $columnArray .= ','; }
            $columnArray .= '["'.$key.'"]';
        }
        $columnArray = '"columns": ['.$columnArray.']';

        // generating Data
        foreach($objdal->data as $row){
            if($rowArray!=""){ $rowArray .= ','; }
            $dataArray = '';
            foreach ($row as $val){
                if($dataArray!=""){ $dataArray .= ','; }
                $dataArray .= '"'.$val.'"';
            }
            $rowArray .= '['.$dataArray.']';
        }
        $rowArray = '"data": ['.$rowArray.']';

        $json = '{'.$columnArray.','.$rowArray.'}';
    } else{
        $json = '{"data":[]}';
    }
    unset($objdal);

    return $json;

}

function normalizeString ($str = '')
{
    $str = strip_tags($str);
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace(' ', '-', $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    $str = str_replace('--', '-', $str);
    return $str;
}

function getStartAndEndDate($week, $year){
    $dates[0] = date("Y-m-d", strtotime($year.'W'.str_pad($week, 2, 0, STR_PAD_LEFT).' -1 days'));
    $dates[1] = date("Y-m-d", strtotime($year.'W'.str_pad($week, 2, 0, STR_PAD_LEFT).' +6 days'));
    return $dates;
}

function getLetterreferenceSerial($po, $ship, $orgType='', $orgId='')
{
    $objdal = new dal();
    if ($orgType == 'bank') {
        if($ship!=0) {
            $sql = "SELECT SL
            FROM (SELECT @rownum:=@rownum + 1 AS `SL`, t.* FROM
                    (SELECT  po.poid, lc.lcissuerbank, sh.shipNo 
                    FROM wc_t_pi AS po 
                      INNER JOIN wc_t_lc AS lc ON po.poid = lc.pono
                      LEFT JOIN wc_t_shipment AS sh ON lc.pono = sh.pono
                    WHERE YEAR(sh.inserton) = YEAR(CURRENT_DATE())
                        AND lc.lcissuerbank = $orgId
                    ORDER BY po.createdon) AS t, (SELECT @rownum:=0) r) AS tt
            WHERE poid = '$po' AND shipNo = $ship;";
        } else{
            $sql = "SELECT SL
            FROM (SELECT @rownum:=@rownum + 1 AS `SL`, t.* FROM
                    (SELECT  po.poid, lc.lcissuerbank 
                    FROM wc_t_pi AS po 
                      INNER JOIN wc_t_lc AS lc ON po.poid = lc.pono
                    WHERE YEAR(lc.createdon) = YEAR(CURRENT_DATE())
                        AND lc.lcissuerbank = $orgId
                    ORDER BY po.createdon) AS t, (SELECT @rownum:=0) r) AS tt
            WHERE poid = '$po';";
        }
    }elseif ($orgType == 'cd') {
        if($ship!=0) {
            $sql = "SELECT SL
            FROM (SELECT @rownum:=@rownum + 1 AS `SL`, t.* FROM
                    (SELECT  po.poid, lc.lcissuerbank, sh.shipNo 
                    FROM wc_t_pi AS po 
                      INNER JOIN wc_t_lc AS lc ON po.poid = lc.pono
                      LEFT JOIN wc_t_shipment AS sh ON lc.pono = sh.pono
                    WHERE YEAR(sh.inserton) = YEAR(CURRENT_DATE())
                        AND sh.Beneficiary = $orgId
                    ORDER BY po.createdon) AS t, (SELECT @rownum:=0) r) AS tt
            WHERE poid = '$po' AND shipNo = $ship;";
        }
    }
    //echo $sql;
    $serial = 1;
    $objdal->read($sql);
    if (!empty($objdal->data)) {
        $res = $objdal->data[0];
        extract($res);
        $serial = $SL;
    }
    return $serial;
}

function getPOContacts($po){

    $objdal = new dal();

    $sql = "SELECT 
        po.poid,
        TRIM(CONCAT(u1.firstname, ' ', u1.lastname)) AS buyerName,
        u1.mobile AS buyerMobile,
        u1.email AS buyerEmail,
        u1.department AS buyerDept,
        TRIM(CONCAT(u2.firstname, ' ', u2.lastname)) AS userName,
        u2.mobile AS userMobile,
        u2.email AS userEmail,
        u2.department AS userDept
    FROM
        wc_t_pi AS po
            INNER JOIN
        wc_t_users AS u1 ON po.createdby = u1.id
            INNER JOIN
        wc_t_users AS u2 ON po.pruserto = u2.id
    WHERE
        po.poid = '$po';";

    $objdal->read($sql);

    $returnVal = 0;

    if(!empty($objdal->data)){
        $res = $objdal->data[0];
        extract($res);
        $returnVal = $res;
    }
    unset($objdal);
    return $returnVal;
}

function randomKey($length) {
    $key = '';
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}

//Copy by Waliul from Stackoverflow
/**
 * modified by Shohel
 * on 10.02.2019
 */
function convertNumberToWord($s){
    $s = strval($s);
    $s = str_replace(",", "", $s);
    if ($s != floatval($s)) return 'not a number';
    $x = strpos($s, '.');
    $a = ''; $b = ''; $t = '';
    if ($x > 0) {
        $n = explode('.', $s);
        $a = toWords($n[0]);
        $b = toWords($n[1]);
        //alert(b);
        if ($b != "") {
            $t = $a . ' and ' . ' cents ' . $b .'only';
        } else {
            $t = $a;
        }
    } else {
        $a = toWords($s);
        $t = $a;
    }

    return $t;
}

function toWords($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}

/*!
 * Select last three (3) password hash
 * Check if given password is in last pass
 * @param - $userId, (integer)
 * @param - $newPass, (string)
 * return: boolean
 * Added by: Hasan Masud
 **************************************/
function belongsToPassHistory($userId, $newPass){
    $objdal = new dal();

    $sql = "SELECT `passwordHash` FROM `wc_t_pass_history` WHERE `userId` = $userId ORDER BY `id` DESC LIMIT 3;";
    //echo $sql;
    $objdal->read($sql);

    $hashRow = array();
    $checkResult = false;
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $hashRow) {
            $checkResult = password_verify($newPass, $hashRow['passwordHash']);
            if ($checkResult == true) break;
            //var_dump($checkResult);
        }
    }
    unset($objdal);

    return $checkResult;
}

function getRealIpAddr()
{
    return $_SERVER['REMOTE_ADDR'];
}

/*!
 * Add activity log
 * $module(string): The module user performed on
 * $user(string): Logged in user ID
 * $ip(string): User's IP address
 * $status(tint): Will insert 0/1 0 for failed, 1 for success
 * ************************************************************/
function addActivityLog($module, $taskMessage, $user, $status)
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $objdal = new dal();
    $module = $objdal->sanitizeInput($module);
    $taskMessage = $objdal->sanitizeInput($taskMessage);
    $sql = "INSERT INTO `wc_t_activity_log` SET 
            `module` = '$module', `taskMessage` = '$taskMessage', `user` = '$user', `createdFrom` = '$ip', `status` = $status;";
//    echo $sql;
    //var_dump(debug_backtrace());
    $objdal->insert($sql);
    unset($objdal);
    return true;
}

function replaceUIdRegex($string) {
    $string = preg_replace('/[^a-zA-Z0-9._]|[,;]$/s', '', $string);
    return $string;
}

/*!
 * File transfer from 'temp' folder to PO wise 'docs' folder
 * This function will be executed upon completion of po submit
 * Added by: Hasan Masud
 * Added on: 2020-02-16
 * ************************************************************/

function fileTransferTempToDocs($poNo, $dontDelete = 0)
{

    $objdal = new dal();

    $sql = "SELECT 
                a.`poid`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
            FROM `wc_t_attachments` a 
                INNER JOIN `wc_t_pi` p ON p.`poid` = a.`poid`
            WHERE a.`poid` = '$poNo';";
    $documents = $objdal->read($sql);
//    var_dump($sql);
//die();
    $old_dir = realpath(dirname(__FILE__) . "/../../temp/");
    $target_dir = realpath(dirname(__FILE__) . "/../../docs/");

    foreach ($documents as $document) {
        /*!
        * Create files with given name
        * This is for test only
        * ****************************/
        //$myfile = fopen($old_dir.$document['filename'], "w");

        $year = date('Y', strtotime($document['createdon']));
        $month = date('M', strtotime($document['createdon']));
        $poNo = $document['poid'];
        $shipNo = $document['shipno'];

        $target_path_year = $target_dir.'/'.$year;
        if (!file_exists($target_path_year)) {
            mkdir($target_path_year, 0777, true);
        }

        $target_path_month = $target_path_year . '/' . $month;
        if (!file_exists($target_path_month)) {
            mkdir($target_path_month, 0777, true);
        }

        $target_path_poNo = $target_path_month . '/' . $poNo;
        if (!file_exists($target_path_poNo)) {
            mkdir($target_path_poNo, 0777, true);
        }

        $target_path_shipNo = $target_path_poNo . '/' . $shipNo;
        if (!file_exists($target_path_shipNo)) {
            mkdir($target_path_shipNo, 0777, true);
        }

        $file = $document['filename'];
        if ($file != '') {
            if (file_exists($old_dir.'/'.$file)) {
                $copy_status = copy($old_dir.'/'.$file, $target_path_shipNo . '/' . $file);
                if ($copy_status == 1) {
                    if ($dontDelete == 0) {
                        unlink($old_dir . '/' . $file);
                    }
                }

            }
        }

    }
}


/*!
 * File transfer from 'temp' folder to PO wise 'docs' folder
 * This function will be executed upon completion of po submit
 * Added by: Nur Mohammad
 * Added on: 2021-10-27
 * ************************************************************/

function fileTransferBtrc($poNo)
{

    $objdal = new dal();

    $sql = "SELECT 
                a.`poid`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
            FROM `wc_t_attachments` a 
                INNER JOIN `wc_t_pi` p ON p.`poid` = a.`poid`
            WHERE a.`poid` = '$poNo';";
    $documents = $objdal->read($sql);
//    var_dump($sql);
//die();
    $old_dir = realpath(dirname(__FILE__) . "/../../temp/");
    $target_dir = realpath(dirname(__FILE__) . "/../../docs/");

    foreach ($documents as $document) {
        /*!
        * Create files with given name
        * This is for test only
        * ****************************/
        //$myfile = fopen($old_dir.$document['filename'], "w");

        $year = date('Y', strtotime($document['createdon']));
        $month = date('M', strtotime($document['createdon']));
        $poNo = $document['poid'];
        $shipNo = $document['shipno'];

        $target_path_year = $target_dir.'/'.$year;
        if (!file_exists($target_path_year)) {
            mkdir($target_path_year, 0777, true);
        }

        $target_path_month = $target_path_year . '/' . $month;
        if (!file_exists($target_path_month)) {
            mkdir($target_path_month, 0777, true);
        }

        $target_path_poNo = $target_path_month . '/' . $poNo;
        if (!file_exists($target_path_poNo)) {
            mkdir($target_path_poNo, 0777, true);
        }

        $target_path_shipNo = $target_path_poNo . '/' . $shipNo;
        if (!file_exists($target_path_shipNo)) {
            mkdir($target_path_shipNo, 0777, true);
        }

        $file = $document['filename'];
        if ($file != '') {
            if (file_exists($old_dir.'/'.$file)) {
                $copy_status = copy($old_dir.'/'.$file, $target_path_shipNo . '/' . $file);

                /*   if ($copy_status == 1) {
                    unlink($old_dir.'/'.$file);
                }*/

            }
        }

    }
}
/*!
 * File transfer from 'temp' folder to FX wise 'docs' folder
 * This function will be executed upon completion of fx submit
 * Added by:  Nur Mohammad
 * Added on: 2021-09-26
 * ************************************************************/
/*function fileTransferFxRequest($lastFxId){
    $objdal = new dal();

    $sql = "SELECT `attachment` FROM `fx_request` WHERE `id` = '$lastFxId';";
    $document = $objdal->getScalar($sql);
//    var_dump($documents);
//    die();

    if ($document) {

        $old_dir = realpath(dirname(__FILE__) . "/../../temp/" . $document);
        $target_dir = realpath(dirname(__FILE__) . "/../../docs/");
//
//    var_dump($old_dir);
//    die();

        $target_dir_fx = $target_dir . '/' . 'FxRequest';
        if (!is_dir($target_dir_fx)) {
            mkdir($target_dir_fx, 0777, true);
        }

        $target_dir_reqId = $target_dir_fx . '/' . 'Fx00' . $lastFxId;
        if (!is_dir($target_dir_reqId)) {
            mkdir($target_dir_reqId, 0777, true);
        }

        if (file_exists($old_dir)) {

            $copy_status = copy($old_dir, $target_dir_reqId . '/' . $document);
            if ($copy_status == 1) {
                unlink($old_dir);
            }
        }
    }


}*/

function fileTransferFxRequest($lastFxId){

    $objdal = new dal();

    $sql = "SELECT `attachment`, `created_at` FROM `fx_request_primary` WHERE `id` = $lastFxId;";

    $document = $objdal->getRow($sql);

//    var_dump($documents);
//    die();

    if ($document['attachment']) {

        $d_year = date('Y', strtotime($document['created_at']));
        $d_month = date('M', strtotime($document['created_at']));
        $old_dir = realpath(dirname(__FILE__) . "/../../temp/".$document['attachment']);

        $target_dir = realpath(dirname(__FILE__) . "/../../docs/");

        $d_poNo = 'FXRFP'.$lastFxId;

//
//    var_dump($old_dir);
//    die();

        /*$target_dir_fx = $target_dir . '/' . 'FxRequest';
        if (!is_dir($target_dir_fx)) {
            mkdir($target_dir_fx, 0777, true);
        }

        $target_dir_reqId = $target_dir_fx . '/' . 'FXRFP' . $lastFxId;
        if (!is_dir($target_dir_reqId)) {
            mkdir($target_dir_reqId, 0777, true);
        }*/
        $target_dir_fx = $target_dir . '/' . 'FxRequest';
        if (!is_dir($target_dir_fx)) {
            mkdir($target_dir_fx, 0777, true);
        }
        $target_path_year = $target_dir_fx . '/' . $d_year;
        if (!file_exists($target_path_year)) {
            mkdir($target_path_year, 0777, true);
        }

        $target_path_month = $target_path_year . '/' . $d_month;
        if (!file_exists($target_path_month)) {
            mkdir($target_path_month, 0777, true);
        }

        $target_path_poNo = $target_path_month . '/' . $d_poNo;
        if (!file_exists($target_path_poNo)) {
            mkdir($target_path_poNo, 0777, true);
        }
        //
        if (file_exists($old_dir)) {

            $copy_status = copy($old_dir, $target_path_poNo . '/' . $document['attachment']);
            if ($copy_status == 1) {
                unlink($old_dir);
            }
        }
    }


}

//LC PROCESSING DOCUMENTS STORE

function fileTransferLCDocs($lastLCId){

    $objdal = new dal();

    $sql = "SELECT `filename`,`docName`, `updatedDate` FROM `lc_processing_docs` WHERE `id` = $lastLCId;";

    $document = $objdal->getRow($sql);

//    var_dump($documents);
//    die();

    if ($document['filename']) {

        $d_year = date('Y', strtotime($document['updatedDate']));
        $d_month = date('M', strtotime($document['updatedDate']));
        $old_dir = realpath(dirname(__FILE__) . "/../../temp/".$document['filename']);

        $target_dir = realpath(dirname(__FILE__) . "/../../docs/");

        $lcDoc = $document['docName'].$lastLCId;

        $target_dir_fx = $target_dir . '/' . 'LCDocuments';
        if (!is_dir($target_dir_fx)) {
            mkdir($target_dir_fx, 0777, true);
        }
        $target_path_year = $target_dir_fx . '/' . $d_year;
        if (!file_exists($target_path_year)) {
            mkdir($target_path_year, 0777, true);
        }

        $target_path_month = $target_path_year . '/' . $d_month;
        if (!file_exists($target_path_month)) {
            mkdir($target_path_month, 0777, true);
        }

        $target_path_lcDoc = $target_path_month . '/' . $lcDoc;
        if (!file_exists($target_path_lcDoc)) {
            mkdir($target_path_lcDoc, 0777, true);
        }
        //
        if (file_exists($old_dir)) {

            $copy_status = copy($old_dir, $target_path_lcDoc . '/' . $document['filename']);
            if ($copy_status == 1) {
                unlink($old_dir);
            }
        }
    }


}
/*!
 * Copy base PO files to new PI directory
 * *****************************************/
function cp_POtoPI($po, $pi){
    $objdal = new dal();

    $target_dir = realpath(dirname(__FILE__) . "/../../docs");

    $query = "SELECT p.`poid`, p.`createdon` FROM `wc_t_pi` p WHERE p.`poid` = '$pi';";
    //echo $query;
    $result = $objdal->getRow($query);

    $d_year = date('Y', strtotime($result['createdon']));
    $d_month = date('M', strtotime($result['createdon']));
    $d_poNo = $result['poid'];

    $target_path_year = $target_dir . '/' . $d_year;
    if (!file_exists($target_path_year)) {
        mkdir($target_path_year, 0777, true);
    }

    $target_path_month = $target_path_year . '/' . $d_month;
    if (!file_exists($target_path_month)) {
        mkdir($target_path_month, 0777, true);
    }

    $target_path_poNo = $target_path_month . '/' . $d_poNo;
    if (!file_exists($target_path_poNo)) {
        mkdir($target_path_poNo, 0777, true);
    }
    $target_path = $target_path_poNo;
    unset($objdal->data);

    /*!
     * Find files sources
     * *****************************/
    $sql = "SELECT 
                a.`poid`, a.`title`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
            FROM `wc_t_attachments` a 
			INNER JOIN `wc_t_pi` p ON p.`poid` = a.`poid`
            WHERE a.`title` IN ('PO','BOQ') AND a.`poid` = '$po'
            GROUP BY a.`poid`, a.`title` ORDER BY a.`attachedon` DESC;";
    //echo $sql;
    $documents = $objdal->read($sql);

    foreach ($documents as $document) {
        $year = date('Y', strtotime($document['createdon']));
        $month = date('M', strtotime($document['createdon']));
        $poNo = $document['poid'];
        $source = $target_dir.'/'.$year.'/'.$month.'/'.$poNo.'/'.$document['filename'];
        $destination = $target_path.'/'.$document['filename'];
        if (!file_exists($destination)) {
            //echo $document["poid"].'|'.$document["title"].'|'.$document["filename"]."<br/>";
            if (!copy($source, $destination)){
                return 'Could not copy base PO files';
            }
        }
    }

    unset($objdal);
}

/*!
 * Replace special characters from string
 * Added by: Hasan Masud
 * Added on: 2020-08-17
 * ****************************************/
function replaceRegex($string) {
    //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9.\-()@,#\/&_\']/', ' ', $string); // Removes special chars.
    $string = preg_replace('/ {2,}/',' ',$string);// Remove One++(1++) space from data.
    return $string;
}

function replaceTextRegex($string) {
    $string = preg_replace('/[^A-Za-z0-9.\-()@#:|+$;%<>\/,&_\']/', ' ', $string); // Removes special chars.
    $string = preg_replace('/ {2,}/',' ',$string);// Remove One++(1++) space from data.
    return $string;
}

//Logging error on a physical file
function logError($log_msg)
{
    $temp_dir = realpath(dirname(__FILE__) . "/../../temp/");
    if (!file_exists($temp_dir.'/log')) {
        mkdir($temp_dir.'/log', 0777, true);
    }
    $log_dir = $temp_dir.'/log/';
    $log_file_data = $log_dir.'log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}

/*!
 * Get extension of attachments
 * @param - $fileName(string)
 * Note: pathinfo is a better way when enabled (some metalized server disable this function...)
 * Returns: file extension E.G: zip, jpg, xlsx, docx, pdf
 * Added by: Hasan Masud
 * Added on: 2020-02-19
 * ***************************************/
function getFileExt($fileName) {
    return substr(strrchr($fileName,'.'),1);
}

/*!
 * Get folder location of attachments
 * @param - $attachment(string)
 * @arg 1 means it for ZIP, 0 means regular
 * If given param has no extension, then
 * will search with decryptedId
 * @return: array['folderLocation', 'poid', 'title']
 * Added by: Hasan Masud
 * Added on: 2020-02-18
 * ***************************************************/
function getFolderLocation($attachment, $forZip = 0)
{
    $objdal = new dal();

    if (!$forZip) {
        if (!getFileExt($attachment)) {
            $attachmentId = decryptId($attachment);
            $where = " WHERE a.`id` = $attachmentId";
        } else {
            $where = " WHERE a.`filename` = '$attachment'";
        }
    } else {
        $where = " WHERE a.`id` IN ($attachment)";
    }
    $sql = "SELECT 
                CONCAT(DATE_FORMAT(p.`createdon`, '%Y/%b'), '/', a.`poid`, '/', IFNULL(CONCAT(a.`shipno`, '/'), ''), a.`filename`) 
                AS `folderLocation`, a.`poid`, a.`title`
            FROM `wc_t_pi` p 
                INNER JOIN `wc_t_attachments` a ON a.`poid` = p.`poid`
            $where;";
    //echo $sql;
    if (!$forZip) {
        $fileInfo = $objdal->getRow($sql);
    } else {
        $fileInfo = $objdal->read($sql);
    }
    unset($objdal);
    return $fileInfo;
}

//set error handler
set_error_handler("customError");

//error handler function
function customError($errno, $errstr, $errfile, $errline)
{
    $taskMessage = $errstr . ' at line no: ' .  $errline;
    $log_msg = date("[Y-m-d H:i:s]")."\t[".$errfile."]\t[".$taskMessage."]\t";
    //Add info to activity log table
    //addActivityLog( $errfile, $taskMessage, $_SESSION[session_prefix . 'wclogin_userid'], 0);
    logError($log_msg);

    echo "<b>Error:</b> [$errno]";
    die();
}

function FXAction($fxReqId, $fxAction, $Remarks=''){

    $objdal = new dal();
    $userId = $_SESSION[session_prefix.'wclogin_userid'];
    // insert query to log
    $query = "INSERT INTO fx_request_log SET  
            FxRequestId = $fxReqId,
            FXAction = $fxAction,
            Remarks = '$Remarks',
            ActionBy = $userId;";

//    echo $query;

    $objdal->insert($query, "Could not create action log");
    unset($objdal);
}
?>