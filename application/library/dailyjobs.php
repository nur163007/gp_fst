<?php
/**
 * Created by Shohel.
 * Date: 7/17/2017
 * Time: 5:15 AM
 */

/*
require("mail/mail2.php");
require_once("dal.php");

$sql = "SELECT 
        l.`ID`,
        l.`PO`,
        l.`Msg`,
        l.`shipNo`,
        l.`ActionID`,
        l.`Status`,
        a.`ActionDone`,
        a.`ActionDoneBy`,
        a.`ActionPending`,
        a.`ActionPendingTo`,
        DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) AS `PendingFor`,
        p.`supplier`,
        p.`createdby` AS `Buyer`,
        u1.`email` AS `BuyersEmail`,
        p.`pruserto` AS `PRUser`,
        u2.`email` AS `PRUsersEmail`,
        IF(a.`ActionPendingTo` = 2,
                (SELECT `email` FROM wc_t_users WHERE id = p.`createdby`),
                IF(a.`ActionPendingTo` = 3,
                    (SELECT  emailTo FROM wc_t_company WHERE id = p.`supplier`),
                    (SELECT GROUP_CONCAT(`email`) FROM wc_t_users WHERE role = a.`ActionPendingTo`))) AS `emailTo`
    FROM
        `wc_t_action_log` l
            INNER JOIN
        `wc_t_action` a ON l.`ActionID` = a.`ID`
            INNER JOIN
        `wc_t_roles` r ON a.`ActionPendingTo` = r.`id`
            INNER JOIN
        `wc_t_pi` p ON p.`poid` = l.`PO`
            INNER JOIN
        `wc_t_users` u1 ON p.`createdby` = u1.`id`
            INNER JOIN
        `wc_t_users` u2 ON p.`pruserto` = u2.`id`
    WHERE
        l.`Status` = 0
            AND a.`ActionPending` != 'Acknowledgement'
            AND DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`);";

$objdal = new dal();
$objdal->read($sql);

$subject = 'FST Action pending at your end';
$res = '';

if(!empty($objdal->data)) {
    foreach ($objdal->data as $val) {
        extract($val);
        $msg = 'Pending: '.$ActionPending.' for '.$PendingFor.' days<br />Please take the action accordingly.<br/>
            <div style="color:#a5b2cb">* This is an auto generated email. Please do not reply this.</div> ';
        $res .= wcMailFunction(trim($emailTo), array(trim($BuyersEmail), trim($PRUsersEmail)), $subject, $msg);
        //wcMailFunction(trim($emailTo), 'saifullah@grameenphone.com', 'Pending: '.$ActionPending, $msg);
    }
}

unset($objdal);

echo 'Email notification has been sent Successfully'.$res;
*/
?>