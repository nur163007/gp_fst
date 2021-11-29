<?php
if ( !session_id() ) {
    session_start();
}
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 10/1/2016
 * Time: 12:35 PM.
 */

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])){

    $where = "";

    if($where!=""){ $where = ' WHERE '.$where; }
    $objdal = new dal();
    if($_GET["isSum"]==0){
        $query = "SELECT l.`lcno`, bi.`name` AS `bank`, l.`pono`, co.`name` AS `supplier`, l.`lcdesc`, DATE_FORMAT(l.`lcissuedate`,'%M %d, %Y') AS `lcissuedate`, c1.`name` AS `currency`, l.`lcvalue`, 
                    IFNULL((SELECT SUM(`ciValue`) FROM `wc_t_endorsement` en WHERE en.`pono`=l.`pono` GROUP BY en.`pono`),0) `endValue`, 
                    IFNULL((SELECT SUM(`amount`) FROM `wc_t_payment` pm WHERE pm.`LcNo`=l.`lcno` GROUP BY pm.`LcNo`),0) `totalPayment`, 
                    DATEDIFF(l.`daysofexpiry`, CURRENT_DATE) `dayExpiry`, 
                    IF(DATEDIFF(l.`daysofexpiry`, CURRENT_DATE)>90, 'success', IF(DATEDIFF(l.`daysofexpiry`, CURRENT_DATE)<30, 'danger', 'warning')) `status`
                FROM `wc_t_lc` l 
                INNER JOIN `wc_t_bank_insurance` bi ON l.`lcissuerbank` = bi.`id`
                INNER JOIN `wc_t_po` po ON l.`pono` = po.`poid` 
                	INNER JOIN `wc_t_company` co ON po.`supplier` = co.`id`
                    INNER JOIN `wc_t_category` c1 ON po.`currency` = c1.`id` $where;";
    } else {
        $query = "SELECT $sumByCol AS `sumByColumn`, COUNT(l.`lcno`) `lcCount`, SUM(l.`lcvalue`) `lcValue`, 
                    SUM(IFNULL((SELECT SUM(`ciValue`) FROM `wc_t_endorsement` en WHERE en.`pono`=l.`pono` GROUP BY en.`pono`),0)) `endValue`, 
                    SUM(IFNULL((SELECT SUM(`amount`) FROM `wc_t_payment` pm WHERE pm.`LcNo`=(SELECT lc.`lcno` FROM `wc_t_lc` lc WHERE lc.`pono`=l.`pono`) GROUP BY pm.`LcNo`),0)) `totalPayment`,
                    COUNT(IF(DATEDIFF(l.`daysofexpiry`, CURRENT_DATE)>0, 1, 0)) `live`,
                    COUNT(IF(DATEDIFF(l.`daysofexpiry`, CURRENT_DATE)<0, 1, 0)) `expired`
                FROM `wc_t_lc` l 
                INNER JOIN `wc_t_bank_insurance` bi ON l.`lcissuerbank` = bi.`id`
                INNER JOIN `wc_t_po` po ON l.`pono` = po.`poid` 
                	INNER JOIN `wc_t_company` co ON po.`supplier` = co.`id`
                GROUP BY $sumByCol;";
    }

    $objdal->read(trim($query));
    $rows = array();
    if(!empty($objdal->data)){
        foreach($objdal->data as $row){
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    $table_data = '{"data": '.$json.'}';
    echo $table_data;
    //echo $query;
}


?>

