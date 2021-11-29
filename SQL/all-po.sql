SELECT p1.`poid`, sh.`shipNo`, format(p1.`povalue`,2) as `povalue`, c2.name as currency, c1.`name` `supplier`, 
            p1.`podesc`, al1.`ID`, a1.`ActionDone` as statusname, 
            a1.`ActionPending` as pending, a1.`ActionPendingTo`, r1.name as targetrole, u1.`username` as buyer,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=37 AND al.`PO`=al1.`PO`) `lcOpening`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=50 AND al.`PO`=al1.`PO`) `lcAmendment`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=72 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `originalDoc`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=71 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `endorsedDoc`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=75 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `customDuty`,
            (SELECT COUNT(al.`ActionID`) FROM `wc_t_action_log` al WHERE al.`ActionID`=74 AND al.`PO`=al1.`PO` and al.`shipNo`=sh.`shipNo`) `eaInputs`
        FROM wc_t_po p1 
        	INNER JOIN `wc_t_action_log` al1 ON p1.`poid` = al1.`PO` AND al1.`ID` = (SELECT MAX(al.`ID`) FROM `wc_t_action_log` as al INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID`  WHERE PO = al1.`PO` AND a.`ActionPending` != 'Acknowledgement')
            INNER JOIN `wc_t_action` a1 ON al1.`ActionID` = a1.ID
            INNER JOIN `wc_t_roles` r1 ON a1.`ActionPendingTo` = r1.id
            INNER JOIN `wc_t_users` u1 ON p1.`createdby` = u1.id
            INNER JOIN `wc_t_company` c1 ON p1.`supplier` = c1.`id`
            INNER JOIN `wc_t_category` c2 ON p1.`currency` = c2.`id`
            LEFT JOIN `wc_t_shipment` as sh ON p1.`poid` = sh.`pono`
        ORDER BY p1.`poid`, sh.`shipNo`;