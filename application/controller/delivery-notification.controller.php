<?php

if ( !session_id() ) {
    session_start();
}
/*
 * Updated on: 2020-08-27
 * Updated by: Hasan Masud
 * Fixed xstatus bug
 * */
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:
                echo getPOLines($_GET["id"]);
             break;
       /* case 2:
            echo getRequest($_GET["po"], $_GET["ship"], $_GET["reqId"]);
            break;*/
        case 3:
            echo getPoInfo($_GET["poNo"]);
            break;
        default:
            break;
    }
}

function getPOLines($id){
//var_dump("ok");
    $objdal = new dal();

    /*!
     * Query for Delivered PO Lines
     * **********************************/
    $sql = "SELECT 
            d.`id`, d.`poNo`, d.`poDate`, d.`needByDate`, d.`itemCode`, REPLACE(d.`itemDesc`, CHAR(194), '') AS `itemDesc`, 
            d.`currency`, d.`lineNo`, d.`uom`, d.`unitPrice`, d.`poQty`, AVG(d.`poTotal`) `poTotal`,
            IFNULL(d.`poDate`, '') AS `poDate`,l.`reqId`,l.`status`,
            SUM(IFNULL(IF(l.`status` = 0, l.`delivQty`, 0), 0)) AS `delivQty`,
            SUM(IFNULL(IF(l.`status` = 0, l.`delivTotal`, 0), 0)) AS `delivTotal`
        FROM
            `wc_t_po_dump` d left join `wc_t_po_line` l on (d.`poNo` = l.`poNo` and d.`lineNo` = l.`lineNo`)
        WHERE
            d.`poNo` = '$id' AND NOT l.`reqId` IS NULL
        GROUP BY d.`id`, d.`poNo`, d.`poDate`, d.`needByDate`, d.`itemCode`, d.`itemDesc`, d.`currency`, d.`lineNo`, 
                 d.`uom`, d.`unitPrice`, d.`poQty`, d.`poTotal`,l.`reqId`,l.`status`;";
    //echo $sql;
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $delivered = $objdal->data;
    } else {
        $delivered = array();
    }
    unset($objdal);

    /*!
     * Query for non Delivered PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT * FROM 
        (SELECT 
            d.`id`, d.`poNo`, d.`poDate`, d.`needByDate`, d.`itemCode`, REPLACE(d.`itemDesc`, CHAR(194), '') AS `itemDesc`, 
            d.`currency`, d.`lineNo`, d.`uom`, d.`unitPrice`, d.`poQty`, AVG(d.`poTotal`) AS `poTotal`,
            IFNULL(d.`projCode`, '') AS `projCode`, l.`reqId`, l.`status`,
            SUM(IFNULL(IF(l.`status` = 0, l.`delivQty`, 0), 0)) AS `delivQty`,
            SUM(IFNULL(IF(l.`status` = 0, l.`delivTotal`, 0), 0)) AS `delivTotal`,
            (d.poQty-SUM(IFNULL(IF(l.`status` = 0, l.`delivQty`, 0),0))) AS `delivQtyValid`, 
            ((d.poQty-SUM(IFNULL(IF(l.`status` = 0, l.`delivQty`, 0), 0))) * d.unitPrice) AS `delivAmountValid`
        FROM
            `wc_t_po_dump` d left join `wc_t_po_line` l on (d.`poNo` = l.`poNo` and d.`lineNo` = l.`lineNo`)
        WHERE
            d.`poNo` = '$id'
        GROUP BY d.`id`, d.`poNo`, d.`poDate`, d.`needByDate`, d.`itemCode`, d.`itemDesc`, d.`currency`, d.`lineNo`, 
                 d.`uom`, d.`unitPrice`, d.`poQty`, d.`poTotal`,l.`reqId`,l.`status`) x
        WHERE `poQty` > `delivQty`;";
    //echo $sql;
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $nondelivered = $objdal->data;
    } else {
        $nondelivered = array();
    }

    unset($objdal);

    /*!
     * Query for rejected PO Lines
     * **********************************/
    $objdal = new dal();

    $sql = "SELECT 
            `poNo`, GROUP_CONCAT(`lineNo`) AS `rejectedlines`
        FROM
            `wc_t_po_line`
        WHERE
            `poNo` = '$id' AND `status` = 1
        GROUP BY `poNo`";
    $objdal->read($sql);

    if (!empty($objdal->data)) {
        $rej = $objdal->data;
    } else {
        $rej = array();
    }

    $json = json_encode(array($delivered, $nondelivered, $rej));
    /*if (!empty($objdal->data)) {
        $res = $objdal->data;
        extract($res);
    }
    unset($objdal);
    $json = json_encode($res);*/
    return $json;

}



function getPoInfo($poNo)
{
    global $loginRole;
    global $companyId;
    global $user_id;
    $objdal = new dal();
    $poNo = $objdal->sanitizeInput($poNo);
    $where = ($loginRole == role_Supplier) ? " AND d.`supplierId` = $companyId" : "";
    $sql = "SELECT 
            SUM(d.`poTotal`) AS `poValue`, d.`supplier`, d.`poDate`, d.`needByDate`, d.`currency`,
            u.`supplierRefNo`
            FROM `wc_t_po_dump` d 
            LEFT JOIN `wc_t_users` u ON d.`supplierId` = u.`company`
            WHERE u.`active` = 1 AND d.`poNo` = $poNo $where;";
    $poInfo = $objdal->getRow($sql);

    //Get previously stored information against the PO number
    $where = ($loginRole == role_Supplier) ? " AND rr.`supplierId` = $user_id" : "";
    $query = "SELECT rr.`gpRefNo`, rr.`userId`, rr.`division`, rr.`department`, rr.`serviceType`,
              TRIM(CONCAT(u.`firstname`,' ', ifnull(u.`lastname`,''))) AS `fullname`
              FROM `wc_t_receiving_request` rr
              INNER JOIN `wc_t_users` u ON rr.`userId` = u.`id`
              WHERE rr.`poNo` = $poNo $where ORDER BY rr.`reqId` LIMIT 1;";
    $requestInfo = $objdal->getRow($query);

    return json_encode(array($poInfo, $requestInfo));
}


