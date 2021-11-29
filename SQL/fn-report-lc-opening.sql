SELECT @rownum:=@rownum + 1 as `SL`, 
            t.*, datediff(`TradeFinanceApprovalDate`, `SourcingApprovalDate`) AS `GrossDay`,
            null `WeekendHoliday`,
            null `ActualDayRequired`
            FROM ( SELECT 
                lc.`lcno` AS `LCNo`,
                bi1.`name` AS `InsCompany`,
                insC.`coverNoteNo` AS `InsCoverNote`,
                bi2.`name` AS `Bank`,
                DATE_FORMAT(lc.`lcissuedate`,'%d-%M-%Y') AS `LC Opening Date`,
                DATE_FORMAT(lc.`lastdateofship`,'%d-%M-%Y') AS `Last Date of Shipment`,
                DATE_FORMAT(lc.`lcexpirydate`,'%d-%M-%Y') AS `Expiry Date`,
                co1.`name` AS `Supplier`,
                lc.`lcdesc` AS `Description of Goods`,
                cat1.`name` AS `Currency`,
                FORMAT(`lcvalue`,2) AS `LC Value`,
                FORMAT((`lcvalue` * `xeUSD`),2) AS `LC Value in USD`,
                FORMAT((`lcvalue` * `xeBDT`),2) AS `LC Value in BDT`,
                (SELECT GROUP_CONCAT(CONCAT(c.`name`, ' : ', pt.`percentage`, '%') SEPARATOR ', ') FROM `wc_t_payment_terms` pt LEFT JOIN `wc_t_category` c ON pt.partname = c.id WHERE pt.`pono` = po.`poid` GROUP BY pt.`pono`)  AS `Payment Terms`,
                `pono` AS `PONo`,
                (SELECT `createdon` FROM `wc_t_po` AS p WHERE p.`poid`=lc.`pono`) AS `SourcingApprovalDate`,
                (SELECT `ActionOn` FROM `wc_t_action_log` WHERE `PO` = po.`poid` AND `ActionByRole` = " . role_LC_Approvar_5 . " AND `Status` = 1) AS `TradeFinanceApprovalDate`,
                null AS `QueryResolveDate`,
                null AS `Remarks`
            FROM `wc_t_lc` lc 
                LEFT JOIN `wc_t_po` po ON lc.`pono` = po.`poid`
                LEFT JOIN `wc_t_company` co1 ON co1.`id` = po.`supplier`
                LEFT JOIN `wc_t_category` cat1 ON cat1.`id` = po.`currency`
                LEFT JOIN `wc_t_bank_insurance` bi1 ON lc.`insurance` = bi1.`id`
                LEFT JOIN `wc_t_bank_insurance` bi2 ON lc.`lcissuerbank` = bi2.`id`
                LEFT JOIN `wc_t_insurance_charge` insC ON lc.`pono` = insC.`ponum` 
            ) t,
            (SELECT @rownum := 0) r;