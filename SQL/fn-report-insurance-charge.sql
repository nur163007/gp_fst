SELECT 
    lc.lcno AS `LC#`,
    DATE_FORMAT(lc.lcissuedate, '%d-%M-%Y') AS `LC Date`,
    bi1.name AS `Bank`,
    '' AS `Bank GL`,
    bi2.name AS `Insurance Compnay`,
    ic.coverNoteNo as `Cover Note No.`,
    c.name AS `Currency`,
    FORMAT(lc.lcvalue,2) AS `LC Amount_right`,
    FORMAT(ic.insuranceValue,2) as `Insurance Amount`,
    FORMAT(ic.exchangeRate,2) AS `FX rate_right`,
    FORMAT(ic.assuredAmount,2) AS `Assured amount BDT_right`,
    FORMAT(ic.marine,2) AS `Marine_right`,
    FORMAT(ic.war,2) AS `War_right`,
    FORMAT(ic.netPremium,2) AS `Net premium_right`,
    FORMAT(ic.vat,2) AS `Total VAT_right`,
    FORMAT(ic.stampDuty,2) AS `Stamp charge_right`,
    FORMAT(ic.otherCharges,2) AS `Other charges_right`,
    FORMAT(ic.total,2) AS ` Total_right`,
    FORMAT(ic.capex,2) AS `Capex_right`,
    FORMAT(ic.vatRebateAmount,2) as `VAT Rebate(80%)_right`,
    FORMAT(ic.vatPayable,2) AS `Vat Payable_right`
FROM
    wc_t_insurance_charge AS ic
        INNER JOIN
    wc_t_po AS po ON ic.ponum = po.poid
        INNER JOIN
    wc_t_lc AS lc ON po.poid = lc.pono
        INNER JOIN
    wc_t_category AS c ON po.currency = c.id
        INNER JOIN
    wc_t_bank_insurance AS bi1 ON lc.lcissuerbank = bi1.id
        INNER JOIN
    wc_t_bank_insurance AS bi2 ON lc.insurance = bi2.id