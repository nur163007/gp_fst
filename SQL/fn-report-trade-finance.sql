	SELECT n4.*, count(s1.`shipNo`) AS `NumOfACostCap`, format(sum(s1.`proportionateCost`)/1000000,2) AS `ACostCapValueInMn`, format((sum(s1.`proportionateCost`)/1000000 * (SELECT x.BDT FROM wc_t_xerate as x ORDER BY id desc LIMIT 1)),2) AS `ACostCapValueInMnBDT`
	FROM (SELECT n3.*, count(c.`lcNo`) AS `NumOfCDPayment`, format(sum(c.`CdPayAmount`)/1000000,2) AS `CDPaymentInMn`, format((sum(c.`CdPayAmount`)/1000000 * (SELECT x.BDT FROM wc_t_xerate as x ORDER BY id desc LIMIT 1)),2) AS `CDPaymentInMnBDT`
        FROM (SELECT n2.* , count(p.`LcNo`) AS `NumOfLCSett`, format(sum(p.`amount`)/1000000,2) AS `SettAmountInMn`, format((sum(p.`amount`)/1000000 * p.exchangeRate),2) AS `SettAmountInMnBDT`
            FROM (SELECT n1.*, count(s.`lcNo`) AS `NumOfLCEnd`, format(sum(s.`ciAmount`)/1000000,2) AS `EndValueInMn`, format((sum(s.`ciAmount`)/1000000 * s.GERPExchangeRate),2) AS `EndValueInMnBDT`
                FROM (SELECT m.*, count(l.`lcNo`) AS `NumOfLCOpen`, format(sum(l.`lcvalue`)/1000000,2) AS `LCValueInMn`, format((sum(l.`lcvalue`)/1000000 * l.xeBDT),2) AS `LCValueInMnBDT`
                        FROM (
                            SELECT month(`lcissuedate`) AS `m`, monthname(`lcissuedate`) AS `mn` FROM wc_t_lc WHERE `lcissuedate` BETWEEN '2015-01-01' AND '2017-03-09' UNION
                            SELECT month(`docDeliveredByFin`) AS `m`, monthname(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment WHERE `docDeliveredByFin` BETWEEN '2015-01-01' AND '2017-03-09' UNION
                            SELECT month(`payDate`) AS `m`, monthname(`payDate`) AS `mn` FROM wc_t_payment WHERE `payDate` BETWEEN '2015-01-01' AND '2017-03-09' UNION
                            SELECT month(`createdon`) AS `m`, monthname(`createdon`) AS `mn` FROM wc_t_custom_duty WHERE `createdon` BETWEEN '2015-01-01' AND '2017-03-09') m
                        LEFT JOIN wc_t_lc AS l ON month(l.`lcissuedate`) = m.`m` AND l.`lcissuedate` BETWEEN '2015-01-01' AND '2017-03-09'
                        GROUP BY m.`m`, m.`mn`) AS n1
                LEFT JOIN wc_t_shipment AS s ON month(s.`docDeliveredByFin`) = n1.`m` AND s.`docDeliveredByFin` BETWEEN '2015-01-01' AND '2017-03-09'
                GROUP BY n1.`m`, n1.`mn`) AS n2
            LEFT JOIN `wc_t_payment` AS p ON month(p.`payDate`) = n2.`m` AND p.`payDate` BETWEEN '2015-01-01' AND '2017-03-09'
            GROUP BY n2.`m`, n2.`mn`) AS n3
		LEFT JOIN `wc_t_custom_duty` AS c ON month(c.`createdon`) = n3.`m` AND c.`createdon` BETWEEN '2015-01-01' AND '2017-03-09'
        GROUP BY n3.`m`, n3.`mn`) n4
	LEFT JOIN wc_t_shipment AS s1 ON month(s1.`whReceiveDate`) = n4.`m` AND s1.`whReceiveDate` BETWEEN '2015-01-01' AND '2017-03-09'
	GROUP BY n4.`m`, n4.`mn`
	ORDER BY n4.`m`, n4.`mn`;