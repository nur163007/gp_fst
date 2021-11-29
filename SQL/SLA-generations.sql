SELECT 
	l.id, 
	l.PO, 
	l.ActionOn, 
	if(l.ActionOn < CAST(CONCAT(YEAR(l.ActionOn), '-', MONTH(l.ActionOn), '-', DAY(l.ActionOn), ' 17:00:00') AS DATETIME),0,1) as newActionDate, 
	DAYOFWEEK(l.ActionOn) as weekDay, 
	l.ActionID, 
	(select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) as SLADays, 
	if(l.ActionOn < CAST(CONCAT(YEAR(l.ActionOn), '-', MONTH(l.ActionOn), '-', DAY(l.ActionOn), ' 17:00:00') AS DATETIME),
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
	) as SLADate
FROM 
	wc_t_action_log as l 
ORDER BY l.ID DESC;

/* 

SELECT l.id, l.PO, l.ActionOn, DAYOFWEEK(ActionOn), l.ActionID, 
date_add(
date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY), INTERVAL 
if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
(if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) DAY) as SLADate
FROM wc_t_action_log as l
order by l.ID desc;

*/

/*SLA Update query
// OLD
UPDATE `wc_t_action_log` as l
	SET l.`SLADate` = date_add(
		date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY), INTERVAL 
		if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
		(if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
		if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) DAY)
	WHERE `ID` in (515,514,513,512,511,510)

/*
SELECT l.id, l.PO, l.ActionOn, DAYOFWEEK(ActionOn), l.ActionID, 
date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY) as SLADate,
if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
(if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) as addDay
FROM wc_t_action_log as l
order by l.ID desc;
*/

#update wc_t_action_log set SLADate = null where ActionOn = SLADate

/*
UPDATE `wc_t_action_log` as l
	SET l.`SLADate` = date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)
WHERE `ID` = 47


UPDATE `wc_t_action_log` as l
	SET l.`SLADate` = date_add(
date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY), INTERVAL 
if((select workingDay from wc_t_action where id = l.ActionID) = 0, 0,
(if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 6, 2, 
if(DAYOFWEEK(date_add(l.`ActionOn`, INTERVAL (select a.`SLA` from `wc_t_action` as a where a.`ID` = l.`ActionID`) DAY)) = 7, 1, 0)))) DAY)

// New 01.04.2017
UPDATE `wc_t_action_log` as l
	SET 
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
*/