<?php
/**
 * Created by Shohel.
 * User: aaqa
 * Date: 3/16/2017
 * Time: 3:01 PM
 */

if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if(isset($_GET['req']) && !empty($_GET['req'])){
    switch ($_GET['req']){
        case 1:
            echo getLetterreferenceSerial($_GET['po'], $_GET['ship'], $_GET['orgtype'], $_GET['orgid']);
            break;
        case 2:
            echo GetPOBuyersSelectList();
            break;
        case 3:
            echo GetPOSupplierSelectList();
            break;
        default:
            break;
    }
}

function GetPOBuyersSelectList(){

    $objdal=new dal();

    $sql = "SELECT 
            po.`createdby` as buyersId, trim(concat(u.firstname,' ' , u.lastname)) as buyersName
        FROM
            wc_t_pi AS po
                INNER JOIN
            wc_t_users AS u ON po.createdby = u.id
        GROUP BY po.createdby
        ORDER BY trim(concat(u.firstname,' ' , u.lastname));";

    $objdal->read(trim($sql));

    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$buyersId.'", "text": "'.$buyersName.'"}';
        }
    }
    $jsondata .= ']';

    unset($objdal);
    return $jsondata;
}

function GetPOSupplierSelectList(){

    $objdal=new dal();

    $sql = "SELECT 
            po.`supplier`, c.`name`
        FROM
            wc_t_pi AS po
                INNER JOIN
            wc_t_company AS c ON po.supplier = c.id
        GROUP BY po.supplier
        ORDER BY c.name;";

    $objdal->read(trim($sql));

    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$supplier.'", "text": "'.$name.'"}';
        }
    }
    $jsondata .= ']';

    unset($objdal);
    return $jsondata;
}

?>