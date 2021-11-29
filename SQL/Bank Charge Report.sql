SELECT 
    bc.LcNo AS `LC#`,
    DATE_FORMAT(lc.lcissuedate, '%d-%M-%Y') AS `LC Date`,
    bi.name AS `Bank`,
    '' AS `Bank GL`,
    c.name AS `Currency`,
    FORMAT(lc.lcvalue,2) AS `LC Amount_right`,
    FORMAT(bc.exchangeRate,2) AS `FX rate_right`,
    FORMAT(bc.commission,2) AS `Commission_right`,
    FORMAT(bc.comissionBDT,2) AS `Commission in BDT_right`,
    FORMAT(bc.vatOnComm,2) AS `VAT ON LC Comission_right`,
    FORMAT(bc.vatOnOtherCharge,2) AS `VAT on Other Charges_right`,
    FORMAT(bc.totalCharge,2) AS `Total VAT Charges_right`,
    FORMAT(bc.cableCharge,2) AS `Cable Charge_right`,
    FORMAT(bc.otherCharge,2) AS `Other Charges_right`,
    FORMAT(bc.nonVAtOtherCharge,2) AS `Stamp Charge_right`,
    FORMAT(bc.vatOnComm + bc.vatOnOtherCharge,2) AS `Total_right`,
    FORMAT(bc.capex,2) AS `Capex_right`,
    FORMAT(((bc.vatOnComm + bc.vatOnOtherCharge) / 100) * bc.vatRebateOnOtherChargesRate,
        2) AS `VAT Rebate_right`
FROM
    wc_t_lc_opening_bank_charge AS bc
        INNER JOIN
    wc_t_lc AS lc ON bc.LcNo = lc.lcno
        INNER JOIN
    wc_t_po AS po ON lc.pono = po.poid
        INNER JOIN
    wc_t_category AS c ON po.currency = c.id
        INNER JOIN
    wc_t_bank_insurance AS bi ON lc.lcissuerbank = bi.id;