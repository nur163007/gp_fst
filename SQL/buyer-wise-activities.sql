SELECT DISTINCT
        po.createdby,
        u.username,
        trim(concat(u.firstname,' ' , u.lastname)) as fullname,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 1
                    AND lg1.ActionOn BETWEEN '2017-03-12' AND '2017-03-19') AS `PO`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 4
                    AND lg1.ActionOn BETWEEN '2017-03-12' AND '2017-03-19') AS `PI`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 22
                    AND lg1.ActionOn BETWEEN '2017-03-12' AND '2017-03-19') AS `BTRC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 25
                    AND lg1.ActionOn BETWEEN '2017-03-12' AND '2017-03-19') AS `LC`,
        (SELECT COUNT(PO) FROM
                (SELECT lg.`PO`, lg.`shipNo`, lg.`ActionID`, MIN(lg.`ActionOn`) AS `ActionOn` FROM `wc_t_action_log` AS lg GROUP BY lg.`PO`, lg.`shipNo`, lg.`ActionID`) AS lg1
                    INNER JOIN wc_t_po AS po1 ON lg1.PO = po1.poid WHERE po1.createdby = po.createdby AND lg1.ActionId = 89
                    AND lg1.ActionOn BETWEEN '2017-03-12' AND '2017-03-19') AS `GIT`
    FROM
        wc_t_po AS po INNER JOIN
        wc_t_users AS u ON po.createdby = u.id
    ORDER BY trim(concat(u.firstname,' ' , u.lastname));