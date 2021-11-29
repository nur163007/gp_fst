SELECT 
    sh.eaRefNo AS `EA Ref. Number`,
    sh.pono AS `PO No.`,
    lc.lcno AS `LC No.`,
    lc.lcissuedate AS `LC Date`,
    c.name AS `Currency_center`,
    FORMAT(lc.lcvalue,2) AS `LC value_right`,
    sh.ciNo AS `C. Invoice No.`,
    sh.ciDate AS `Invoice Date`,
    FORMAT(sh.ciAmount,2) AS `Invoice Value_right`,
    sh.shipNo AS `Shipment No._center`,
    sh.hawbNo AS `HAWB Number`,
    sh.mawbNo AS `MAWB Number`,
    sh.blNo AS `BL Number`,
    po.lcdesc AS `Equipments`,
    sh.shipmode AS `Mode of Shipment_center`,
    DATE_FORMAT(sh.docReceiveByEA, '%d-%M-%Y') AS `Document Received`,
    sh.billOfEntryNo AS `Bill Of Entry No.`,
    DATE_FORMAT(sh.billOfEntryDate, '%d-%M-%Y') AS `Bill of Entry Date`,
    FORMAT(sh.CDAmount,2) AS `Total CDVAT Amount_right`,
    DATE_FORMAT(sh.actualArrivalAtPort, '%d-%M-%Y') AS `Goods Arrival at Port`,
    DATE_FORMAT(sh.releaseFromPort, '%d-%M-%Y') AS `Port released Date`,
    DATE_FORMAT(sh.whReceiveDate, '%d-%M-%Y') AS `At Warehouse Date`,
    DATEDIFF(sh.whReceiveDate, IF(sh.docReceiveByEA > sh.actualArrivalAtPort, sh.docReceiveByEA, sh.actualArrivalAtPort)) AS `KPI (days)_center`,
    sh.ChargeableWeight AS `Chargeable Weight`,
    FORMAT(sh.demurrageAmount,2) AS `Only Demurrage Amount_right`
FROM
    wc_t_shipment AS sh
        LEFT JOIN
    wc_t_po AS po ON sh.pono = po.poid
        LEFT JOIN
    wc_t_lc AS lc ON sh.pono = lc.pono
        LEFT JOIN
    wc_t_category AS c ON po.currency = c.id
ORDER BY sh.`eaRefNo` , sh.pono , sh.`shipNo`;