SELECT 
        case when sh.`shipNo` is null then 
			CONCAT('<a href=''view-po?po=', po.`poid`, '&ref=', (SELECT MAX(al.`ID`) FROM `wc_t_action_log` as al INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID`  WHERE PO = po.`poid` AND a.`ActionPending` != 'Acknowledgement'),''' target=''_blank''>', po.`poid`, '</a>')
        else 
			CONCAT('<a href=''view-po?po=', po.`poid`, '&ship=', sh.`shipNo`,'&ref=',(SELECT MAX(al.`ID`) FROM `wc_t_action_log` as al INNER JOIN `wc_t_action` as a ON al.`ActionID` = a.`ID`  WHERE PO = po.`poid` AND a.`ActionPending` != 'Acknowledgement'),''' target=''_blank''>', po.`poid`, '</a>') end AS `PO Number`,
        sh.`shipNo` AS `Shipment#_center`,
        (SELECT `username` FROM `wc_t_users` WHERE id = po.`createdby`) AS `PO Buyer`,
        (SELECT `name` FROM `wc_t_company` WHERE id = po.`supplier`) AS `Supplier`,
        (SELECT `username` FROM `wc_t_users` WHERE id = po.`pruserto`) AS `PR User`,
        (SELECT `name` FROM `wc_t_category` WHERE id = po.`contractref`) AS `Contact Ref`,
        (SELECT `name` FROM `wc_t_category` WHERE id = po.`currency`) AS `Currency_center`,
        FORMAT(po.`povalue`,2) AS `PO Value_right`,
        replace(`podesc`,'\\r\\n','') AS `PO Description`,
        DATE_FORMAT(po.`createdOn`,'%d-%M-%Y') AS `PO & BOQ Sent to Vendor`,
        DATE_FORMAT(`deliverydate`,'%d-%M-%Y') AS `PO Need by Date`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Draft_PI_Submitted.") AS `PI & BOQ Receive Date`,
        `productiondays` AS `Days required for shipment after getting LC_center`,
        po.`pinum` AS `PI Number`, DATE_FORMAT(po.`pidate`,'%d-%M-%Y') AS `PI Date`,
        FORMAT(po.`pivalue`,2) AS `PI Value_right`, UPPER(po.`shipmode`) AS `Shipment Mode_center`,
        FORMAT(`basevalue`,2) AS `Insurance Value_right`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Sent_for_BTRC_Permission.") AS `Request for BTRC Permission`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Accepted_by_BTRC.") AS `BTRC Permission Received`,
        (SELECT GROUP_CONCAT(`percentage` SEPARATOR ',') FROM `wc_t_payment_terms` pt WHERE pt.`pono` = po.`poid` GROUP BY pt.`pono`)  AS `Payment Terms`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_LC_Request_Sent.") AS `Apply for LC`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`ActionID`=".action_Final_LC_Copy_Sent.") AS `LC Receive Date`,
        (SELECT concat(trim(lc.`lcno`), if(trim(ifnull(lc.`lcno`,'')) ='', (case when lc.`lca` = 0 then 'LC' else 'LCA' end), (case when lc.`lca` = 0 then '<br>LC' else '<br>LCA' end))) FROM `wc_t_lc` AS lc WHERE lc.`pono` = po.`poid`) AS `LC Number`,
        (SELECT FORMAT(lc.`lcvalue`,2) FROM `wc_t_lc` AS lc WHERE lc.`pono` = po.`poid`) AS `LC Value_right`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`shipNo` = sh.`shipno` AND log1.`ActionID`=".action_Original_Document_Delivered.") AS `Scan Copy Receive Date`,
        (SELECT MAX(DATE_FORMAT(`ActionOn`,'%d-%M-%Y')) FROM `wc_t_action_log` log1 WHERE log1.`PO`=po.`poid` AND log1.`shipNo` = sh.`shipno` AND log1.`ActionID`=".action_Shared_Voucher_info_to_Fin.") AS `Pre-Alert & GIT receiving & Doc Endorse Mail`,
        DATE_FORMAT(sh.`gitReceiveDate`,'%d-%M-%Y') AS `GIT Received Date`,
        sh.`ciNo` AS `Commercial Invoice Number`,
        DATE_FORMAT(sh.`ciDate`,'%d-%M-%Y') AS `Commercial Invoice Date`,
        sh.`ciAmount` AS `C.Invoice Amount_right`,
        sh.`mawbNo` as `MAWB No.`,
        sh.`hawbNo` as `HAWB No.`,
        sh.`blNo` as `BL No.`,
        DATE_FORMAT(sh.`awbOrBlDate`,'%d-%M-%Y') AS `AWB/BL Date`,
        '-' AS `Description (For partial shipment only)`,
        sh.`GERPVoucherNo` AS `Voucher No`,
        DATE_FORMAT(sh.`GERPVoucherDate`,'%d-%M-%Y') AS `Voucher Creation Date`,
        DATE_FORMAT(sh.`scheduleETA`,'%d-%M-%Y') AS `ETA`,
        sh.`dhlTrackNo` AS `DHL`,
        DATE_FORMAT(`whArrivalDate`,'%d-%M-%Y') AS `Actual Arrival at WH`,
        (SELECT GROUP_CONCAT(CONCAT('<span class=''custome-db-column01''>',a.ActionPending,'<span>') SEPARATOR ',<br/>')
            FROM wc_t_action_log as l INNER JOIN wc_t_action AS a ON l.ActionID = a.ID
            where l.PO = po.`poid` and l.`shipNo` = sh.`shipNo` and l.Status = 0 and a.ActionPending != 'Acknowledgement'
            order by l.ID desc) AS `Status (pending for)`
        FROM `wc_t_po` po 
        LEFT JOIN `wc_t_shipment` AS sh ON po.`poid` = sh.`pono`
        ORDER BY po.`poid`, sh.`shipNo`