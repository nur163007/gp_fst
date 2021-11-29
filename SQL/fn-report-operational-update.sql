SELECT 'No of LC Opening' AS `Number of LC & Values_left`,
    SUM(CASE WHEN n1.`mn` = 'January' THEN n1.`num` ELSE 0 END) AS `January_right`,
    SUM(CASE WHEN n1.`mn` = 'February' THEN n1.`num` ELSE 0 END) AS `February_right`,
    SUM(CASE WHEN n1.`mn` = 'March' THEN n1.`num` ELSE 0 END) AS `March_right`,
    SUM(CASE WHEN n1.`mn` = 'April' THEN n1.`num` ELSE 0 END) AS `April_right`,
    SUM(CASE WHEN n1.`mn` = 'May' THEN n1.`num` ELSE 0 END) AS `May_right`,
    SUM(n1.`num`) AS `Total_right`
FROM (
	SELECT m.*, COUNT(l.`lcNo`) AS `num`
	FROM (
		SELECT MONTHNAME(`lcissuedate`) AS `mn` FROM wc_t_lc
		WHERE `lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
		UNION 
		SELECT MONTHNAME(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment
		WHERE `docDeliveredByFin` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
		UNION 
		SELECT MONTHNAME(`payDate`) AS `mn` FROM wc_t_payment
		WHERE `payDate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
		UNION 
		SELECT MONTHNAME(`createdon`) AS `mn` FROM wc_t_custom_duty
		WHERE `createdon` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
	) m LEFT JOIN `wc_t_lc` AS l ON MONTHNAME(l.`lcissuedate`) = m.`mn` AND l.`lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
GROUP BY m.`mn`) n1 

UNION 

SELECT 'LC Value(Mn BDT)' AS `Number of LC & Values_left`,
    SUM(CASE WHEN n1.`mn` = 'January' THEN n1.`valueBDT` ELSE 0 END) AS `January_right`,
    SUM(CASE WHEN n1.`mn` = 'February' THEN n1.`valueBDT` ELSE 0 END) AS `February_right`,
    SUM(CASE WHEN n1.`mn` = 'March' THEN n1.`valueBDT` ELSE 0 END) AS `March_right`,
    SUM(CASE WHEN n1.`mn` = 'April' THEN n1.`valueBDT` ELSE 0 END) AS `April_right`,
    SUM(CASE WHEN n1.`mn` = 'May' THEN n1.`valueBDT` ELSE 0 END) AS `May_right`,
    SUM(n1.`valueBDT`) AS `Total_right`
FROM (
	SELECT m.*, FORMAT((SUM(l.`lcvalue`) / 1000000 * l.xeBDT), 2) AS `valueBDT`
    FROM (
		SELECT MONTHNAME(`lcissuedate`) AS `mn` FROM wc_t_lc
		WHERE `lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment
		WHERE `docDeliveredByFin` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`payDate`) AS `mn` FROM wc_t_payment
		WHERE `payDate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`createdon`) AS `mn` FROM wc_t_custom_duty
		WHERE `createdon` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
	) m LEFT JOIN `wc_t_lc` AS l ON MONTHNAME(l.`lcissuedate`) = m.`mn` AND l.`lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
GROUP BY m.`mn`) n1 

UNION 

SELECT 'LC Value(Mn USD)' AS `Number of LC & Values_left`,
    SUM(CASE WHEN n1.`mn` = 'January' THEN n1.`value` ELSE 0 END) AS `January_right`,
    SUM(CASE WHEN n1.`mn` = 'February' THEN n1.`value` ELSE 0 END) AS `February_right`,
    SUM(CASE WHEN n1.`mn` = 'March' THEN n1.`value` ELSE 0 END) AS `March_right`,
    SUM(CASE WHEN n1.`mn` = 'April' THEN n1.`value` ELSE 0 END) AS `April_right`,
    SUM(CASE WHEN n1.`mn` = 'May' THEN n1.`value` ELSE 0 END) AS `May_right`,
    SUM(n1.`value`) AS `Total_right`
FROM (
	SELECT m.*, FORMAT(SUM(l.`lcvalue`) / 1000000, 2) AS `value`
    FROM (
		SELECT MONTHNAME(`lcissuedate`) AS `mn` FROM wc_t_lc
		WHERE `lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment
		WHERE `docDeliveredByFin` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`payDate`) AS `mn` FROM wc_t_payment
		WHERE `payDate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00' 
        UNION 
        SELECT MONTHNAME(`createdon`) AS `mn` FROM wc_t_custom_duty
		WHERE `createdon` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
	) m LEFT JOIN `wc_t_lc` AS l ON MONTHNAME(l.`lcissuedate`) = m.`mn` AND l.`lcissuedate` BETWEEN '2015-01-01 00:00:00' AND '2015-06-30 00:00:00'
GROUP BY m.`mn`) n1;