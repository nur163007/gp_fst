<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 2/5/2017
 * Time: 12:17 PM
 */
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"])){

    $where = "";
    $action = $_GET["action"];

    if((!empty($_GET["start"]) && isset($_GET["start"])) && (!empty($_GET["end"]) && isset($_GET["end"]))){

        $start = htmlspecialchars($_GET['start'],ENT_QUOTES, "ISO-8859-1");
        $start = date('Y-m-d', strtotime($start));
        $end = htmlspecialchars($_GET['end'],ENT_QUOTES, "ISO-8859-1");
        $end = date('Y-m-d', strtotime($end));

        $where = " '".$start."' AND '".$end."'";
        //$where = "'2015-01-01 00:00:00' AND '2015-06-30 00:00:00'";
    }

    $query = "SELECT * FROM 
        (SELECT month(`lcissuedate`) AS `m`, monthname(`lcissuedate`) AS `mn` FROM wc_t_lc WHERE `lcissuedate` BETWEEN $where UNION
        SELECT month(`docDeliveredByFin`) AS `m`, monthname(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment WHERE `docDeliveredByFin` BETWEEN $where UNION 
        SELECT month(`payDate`) AS `m`, monthname(`payDate`) AS `mn` FROM wc_t_payment WHERE `payDate` BETWEEN $where UNION 
        SELECT month(`createdon`) AS `m`, monthname(`createdon`) AS `mn` FROM wc_t_custom_duty WHERE `createdon` BETWEEN $where)  AS m
        ORDER BY m.`m`;";

    $objdal = new dal();
    $objdal->read(trim($query));

    $str = "";
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $str .= "format(sum(CASE WHEN n1.`mn` = '" . $mn . "' THEN n1.`xxxxx` ELSE 0 END),2) AS `" . $mn . "_right`,";
        }
        $str .= "format(sum(n1.`xxxxx`),2) AS `Total_right`";
    }
    unset($objdal->data);

    $monthTab = "SELECT monthname(`lcissuedate`) AS `mn` FROM wc_t_lc WHERE `lcissuedate` BETWEEN $where UNION
            SELECT monthname(`docDeliveredByFin`) AS `mn` FROM wc_t_shipment WHERE `docDeliveredByFin` BETWEEN $where UNION 
            SELECT monthname(`payDate`) AS `mn` FROM wc_t_payment WHERE `payDate` BETWEEN $where UNION 
            SELECT monthname(`createdon`) AS `mn` FROM wc_t_custom_duty WHERE `createdon` BETWEEN $where";

    $vars = array(
        array("", "wc_t_lc", "wc_t_shipment", "wc_t_payment", "wc_t_shipment", "wc_t_payment"), // Table names for individual report
        array("", "lcissuedate", "lcNo", "lcvalue", "xeBDT", "No of LC  Opening", "LC Value(Mn BDT)", "LC Value(Mn USD)"),
        array("", "docDeliveredByFin", "lcNo", "ciAmount", "GERPExchangeRate", "No of LC End/Original doc", "End/Original doc Value(Mn BDT)", "End/Original doc Value(Mn USD)"),
        array("", "payDate", "LcNo", "amount", "exchangeRate", "No of LC Settlement", "Settlement Value(Mn BDT)", "Settlement Value(Mn USD)"),
        array("", "whReceiveDate", "shipNo", "proportionateCost", "GERPExchangeRate", "No of Transactions", "Ancillary Cost Capitalization (Mn BDT)", "Ancillary Cost Capitalization (Mn USD)"),
        array("", "payDate", "LcNo", "amount", "exchangeRate", "Savings on LC payment(Mn BDT)", "Average Settled Rate", "Average BC Selling  Rate", "Saving in Fx rate"),
    );

    if($action==5){
        $query = "SELECT '" . $vars[$action][5] . "' AS `Particulars&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_left`, " . str_replace('xxxxx', 'num', $str) . "
        FROM (SELECT m.*, sum(((l.`bcSellingRate`-l.`exchangeRate`)*l.`" . $vars[$action][3] . "`)/ 1000000 * l.`".$vars[$action][4]."`) AS `num`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1
        UNION
        SELECT '" . $vars[$action][6] . "' AS `Particulars_left`, " . str_replace('xxxxx', 'valueBDT', $str) . "
        FROM (SELECT m.*, avg(l.`exchangeRate`) AS `valueBDT`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1
        UNION
        SELECT '" . $vars[$action][7] . "' AS `Particulars_left`, " . str_replace('xxxxx', 'value', $str) . "
        FROM (SELECT m.*, avg(l.`bcSellingRate`) AS `value`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1
        UNION
        SELECT '" . $vars[$action][8] . "' AS `Particulars_left`, " . str_replace('xxxxx', 'value', $str) . "
        FROM (SELECT m.*, (l.`bcSellingRate`-l.`exchangeRate`) AS `value`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1;";
    }else {
        $query = "SELECT '" . $vars[$action][5] . "' AS `Particulars&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_left`, " . str_replace('xxxxx', 'num', $str) . "
        FROM (SELECT m.*, count(l.`" . $vars[$action][2] . "`) AS `num`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1
        UNION
        SELECT '" . $vars[$action][6] . "' AS `Particulars_left`, " . str_replace('xxxxx', 'valueBDT', $str) . "
        FROM (SELECT m.*, (sum(l.`" . $vars[$action][3] . "`)/1000000 * l.`".$vars[$action][4]."`) AS `valueBDT`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1
        UNION
        SELECT '" . $vars[$action][7] . "' AS `Particulars_left`, " . str_replace('xxxxx', 'value', $str) . "
        FROM (SELECT m.*, (sum(l.`" . $vars[$action][3] . "`)/1000000) AS `value`
        FROM ( $monthTab ) m
            LEFT JOIN `" . $vars[0][$action] . "` AS l ON monthname(l.`" . $vars[$action][1] . "`) = m.`mn` AND l.`" . $vars[$action][1] . "` BETWEEN " . $where . "
        GROUP BY m.`mn`) n1;";
    }
    //echo $query;

    $objdal->read(trim($query));
    $rows = array();

    if(!empty($objdal->data)){
        // generating column array in JSON format
        $columnArray = '';
        $rowArray = '';
        $dataArray = '';
        $cols = $objdal->data[0];
        foreach ($cols as $key => $value){
            if($columnArray!=""){ $columnArray .= ','; }
            $columnArray .= '{"title":"' . substr($key, 0, strripos($key,'_')) . '", "class":"text-'.substr($key, strripos($key,'_')+1).'"}';
        }
        $columnArray = '"columns": ['.$columnArray.']';

        // generating Data
        foreach($objdal->data as $row){
            if($rowArray!=""){ $rowArray .= ','; }
            $dataArray = '';
            foreach ($row as $val){
                if($dataArray!=""){ $dataArray .= ','; }
                $dataArray .= '"'.$val.'"';
            }
            $rowArray .= '['.$dataArray.']';
        }
        $rowArray = '"data": ['.$rowArray.']';

        $json = '{'.$columnArray.','.$rowArray.'}';
    } else{
        $json = '{"data":[]}';
    }
    unset($objdal);
    echo $json;
}

?>

