SELECT 
    p.PO,
    p.logId,
    l1.ActionId,
    a.ActionDone,
    a.ActionDoneBy,
    l1.ActionOn,
    datediff(current_date(), l1.ActionOn) as `pendingFor`,
    r1.name AS ActionDoneByRole,
    a.ActionPending,
    a.ActionPendingTo,
    r2.name AS ActionPendingToRole
FROM
    (SELECT DISTINCT
        l.PO, MAX(l.ID) AS logId
    FROM
        wc_t_action_log AS l
    WHERE
        l.PO IN (SELECT 
                pono
            FROM
                wc_t_lc) and l.ActionID NOT IN (149)
    GROUP BY l.PO) AS p
        INNER JOIN
    wc_t_action_log AS l1 ON p.logid = l1.ID
        INNER JOIN
    wc_t_action AS a ON l1.ActionID = a.ID
        INNER JOIN
    wc_t_roles AS r1 ON a.ActionDoneBy = r1.id
        INNER JOIN
    wc_t_roles AS r2 ON a.ActionPendingTo = r2.id
