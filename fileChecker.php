<?php
/*require_once(realpath(dirname(__FILE__) . "/application/library/dal.php"));

$objdal = new dal();

$sql = "SELECT `filename` FROM `wc_t_attachments`;";
$result = $objdal->read($sql);

$arrSize = count($result);
$filePath = $_SERVER["HTTP_HOST"]."/fst/temp/";
//var_dump($hostName);
for ($i = 0; $i < $arrSize; $i++){
    $filename = $result[$i]["filename"];
    if (!file_exists($filename)) {
        echo $filename ."<br/>";
    }
}

unset($objdal);
die();*/



