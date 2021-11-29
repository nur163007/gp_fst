<?php
require("mail/mail2.php");

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

    $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");
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
            `ActionFrom` = '".$ip."';";
        //  echo $sql;

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
            WHERE l.`ID` = $newId";
        $objdal->update($sql);

        // Email
        sendActionEmail($newId, $params['mailto'], $params['mailcc']);
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
    	INNER JOIN `wc_t_po` po ON l.`PO`=po.`poid`
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
        if($ActionPendingTo == role_cert_final_approver){
            unset($to);
            $to= array($cfaEmail);
        }

        $addiCc1 = explode(',', $additionalCC);
        for($i=0; $i<sizeof($addiCc1); $i++) {
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

    if ($ActionPendingTo != role_Buyer && $ActionPendingTo != role_Supplier && $ActionPendingTo != role_PR_Users &&
        $ActionPendingTo != role_cert_final_approver) {

        $sql = "SELECT `email` FROM `wc_t_users` AS u WHERE u.`role` = $ActionPendingTo";

        unset($objdal->data);
        $objdal->read($sql);

        if (!empty($objdal->data)) {
            foreach ($objdal->data as $val) {
                extract($val);
                array_push($to, $email);
            }
        }
    }

    // adding current user and his role's other email
    if ($_SESSION[session_prefix . 'wclogin_role'] != role_Buyer &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_Supplier &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_PR_Users &&
        $_SESSION[session_prefix . 'wclogin_role'] != role_cert_final_approver
    ) {

        $sql = "SELECT `email` FROM `wc_t_users` AS u WHERE u.`role` = " . $_SESSION[session_prefix . 'wclogin_role'];

        unset($objdal->data);
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

    if($debug==1) {
        echo "To: " . json_encode($to) . "<br>CC: " . json_encode($cc);
    } else {
        if ($_SERVER['SERVER_NAME'] != 'localhost') {
            $returnVal = wcMailFunction($to, $cc, $subject, $message, $link, $logref);
        }
    }
    return $returnVal;
}

function GetActionRef($ref){

    $objdal = new dal();
    $refNum = '';
    if(is_numeric($ref) && !strlen($ref)>4){
        $refNum = $ref;
    } else{
        $refNum = decryptId($ref);
    }
    //
    $sql = "SELECT l.`ID`,l.`RefID`,l.`Msg`, l.`UserMsg`, l.`ActionID`, a.`ActionDone`,l.`ActionOn`, a.`ActionPendingTo`, 
    IF(a.`ActionPending`='Acknowledgement',IF(l.`Status`=1,'',a.`ActionPending`),a.`ActionPending`) as `ActionPending`,
            l.`Status`, l.`XRefID`, l.`ActionBy`, l.`ActionByRole`, l.`PI`, l.`shipNo`, l.`certReqId`, 
        	(SELECT l2.`ActionID` FROM `wc_t_action_log` l2 WHERE l2.`PO` = l.`PO` AND IFNULL(l2.`shipNo`,0) = IFNULL(l.`shipNo`,0) ORDER BY l2.`ID` DESC LIMIT 1)  `1stLastAction`,
            (SELECT l3.`ActionID` FROM `wc_t_action_log` l3 WHERE l3.`PO` = l.`PO` AND IFNULL(l3.`shipNo`,0) = IFNULL(l.`shipNo`,0) AND l3.`Status` <> 0 AND l3.`ID`<(SELECT l2.`ID` FROM `wc_t_action_log` l2 WHERE l2.`PO` = l.`PO` AND IFNULL(l2.`shipNo`,0) = IFNULL(l.`shipNo`,0) ORDER BY l2.`ID` DESC LIMIT 1) ORDER BY l3.`ID` DESC LIMIT 1) `2ndLastAction`,
            (SELECT IFNULL(MAX(s.`shipNo`),0) FROM `wc_t_shipment` s WHERE s.`pono` = l.`PO`) `LastShipment`,
            (SELECT IFNULL(MAX(e.`endNo`),0)  FROM `wc_t_endorsement` e WHERE e.`pono` = l.`PO`) `LastEndorseNo`
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
          FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` = $actionId AND `Status` in (0,1) ";
    } else {
        $query = "SELECT COUNT(`ActionID`) `stepOver`, `Msg`, `UserMsg`
          FROM `wc_t_action_log` WHERE `PO` = '$po' AND `ActionID` = $actionId AND `Status` in (0,1,-1) ";
    }
    if($shipNo!=null && $shipNo!=""){
        $query .= " AND `shipNo` = $shipNo;";
    }
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
//    echo $query;

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
                    FROM wc_t_po AS po 
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
                    FROM wc_t_po AS po 
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
                    FROM wc_t_po AS po 
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
        wc_t_po AS po
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

?>