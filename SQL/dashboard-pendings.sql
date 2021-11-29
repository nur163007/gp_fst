
SELECT 
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
    p.`supplier`,
    p.`createdby`,
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
    `wc_t_po` p ON p.`poid` = l.`PO`
WHERE
    l.`Status` = 0
        AND a.`ActionPending` != 'Acknowledgement'
        AND DATEDIFF(CURRENT_DATE, l.`BaseActionOn`) > DATEDIFF(l.`SLADate`, l.`BaseActionOn`)