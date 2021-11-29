
SELECT 'achive' AS `type`,
	kpi1 AS `PO_Send`,
	kpi2 AS `PI_Receive`,
    kpi3 AS `Sent_BTRC`,
    kpi4 AS `LC_Request`,
    kpi5 AS `Invoice`
FROM
(SELECT 
(SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 1 AND `ActionOn` BETWEEN '2017-03-12' AND '2017-03-19') AS kpi1,
(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
WHERE lg001.`ActionID` = 4 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19') AS kpi2,
(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
WHERE lg001.`ActionID` = 22 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19') AS kpi3,
(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
WHERE lg001.`ActionID` = 25 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19') AS kpi4,
(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, lg1.`shipNo`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`) AS lg001
WHERE lg001.`ActionID` = 89 AND lg001.`ActionOn` BETWEEN '2017-03-12' AND '2017-03-19') AS kpi5) AS a

UNION

SELECT 'KPI' AS `type`,
	kpi1 AS `PO_Send`,
	kpi2 AS `PI_Receive`,
    kpi3 AS `Sent_BTRC`,
    kpi4 AS `LC_Request`,
    kpi5 AS `Invoice`
FROM
(SELECT 
(SELECT COUNT(PO) FROM `wc_t_action_log` WHERE `ActionID` = 1 AND `ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19') AS kpi1,

(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001 
	WHERE lg001.`ActionID` = 4 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19' 
	AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`ActionID` = 1)) AS kpi2,

(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001
WHERE lg001.`ActionID` = 22 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19') AS kpi3,

(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`ActionID`) AS lg001 
	WHERE lg001.`ActionID` = 25 AND lg001.`ActionOn` BETWEEN  '2017-03-12' AND '2017-03-19' 
	AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`ActionID` = 24)) AS kpi4,

(SELECT COUNT(PO) FROM (SELECT lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`, MIN(lg1.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg1 GROUP BY lg1.`PO`, lg1.`shipNo`, lg1.`ActionID`) AS lg001 
		WHERE lg001.`ActionID` = 89 AND lg001.`ActionOn` BETWEEN '2017-03-12' AND '2017-03-19' 
		AND lg001.`ActionOn` <= (SELECT lg2.`SLADate` FROM `wc_t_action_log` AS lg2 WHERE lg2.`PO` = lg001.`PO` AND lg2.`shipNo` = lg001.`shipNo` AND lg2.`ActionID` = 73 limit 1)) AS kpi5) AS a

