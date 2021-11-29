set @dtstart = '2017-03-12', @dtend = '2017-03-19';

SELECT 
    a.PO,
    DATE_FORMAT(a.PO_Send, '%d-%M-%Y') AS `PO Sent date`,
            DATE_FORMAT(a.PI_Receive, '%d-%M-%Y') AS `PI Receive Date`,
            DATE_FORMAT(a.PI_Rec_SLA_Date, '%d-%M-%Y') AS `PI Receive SLA Date`,
            DATEDIFF(a.PI_Rec_SLA_Date, a.PI_Receive) AS `PI Done`,
            DATE_FORMAT(a.BTRC_NOC_Receive, '%d-%M-%Y') AS `BTRC NOC Receive Date`,
            DATE_FORMAT(a.LC_Request_Sent, '%d-%M-%Y') AS `LC Request Sent Date`,
            DATE_FORMAT(a.LC_Req_SLA_Date, '%d-%M-%Y') AS `LC Request Sent SLA Date`,
            DATEDIFF(a.LC_Req_SLA_Date, a.LC_Request_Sent) AS `LC Done`,
            DATE_FORMAT(a.GTI_Receive, '%d-%M-%Y') AS `GTI Receive Date`,
            DATE_FORMAT(a.Voucher_Update, '%d-%M-%Y') AS `Voicher Update Date`,
            DATE_FORMAT(a.Invoice_SLA_Date, '%d-%M-%Y') AS `Invoice SLA Date`,
            DATEDIFF(a.Invoice_SLA_Date, a.Voucher_Update) AS `Invoice Done`
FROM
    (SELECT 
        l.PO,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 1 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PO_Send`,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 4 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PI_Receive`,
		(SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 1 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `PI_Rec_SLA_Date`,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 24 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `BTRC_NOC_Receive`,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 25 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `LC_Request_Sent`,
        (SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 24 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `LC_Req_SLA_Date`,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 73 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `GTI_Receive`,
		(SELECT l1.BaseActionOn FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 89 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `Voucher_Update`,
        (SELECT l1.SLADate FROM `wc_t_action_log` l1 WHERE l1.`ActionID` = 73 AND l1.PO = l.PO ORDER BY l1.ID DESC limit 1) `Invoice_SLA_Date`
    FROM
        wc_t_action_log AS l
    WHERE
         #l.`ActionOn` BETWEEN @dtstart AND @dtend
         l.`ActionOn` BETWEEN '2017-03-12' AND '2017-03-19'
    GROUP BY l.PO) a