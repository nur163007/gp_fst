<?php


require_once(realpath(dirname(__FILE__) . "/../application/config.php"));
require_once(realpath(dirname(__FILE__) . "/../application/library/dal.php"));
//require_once(LIBRARY_PATH . "/dal.php");

function getFileExt($fileName) {
    return substr(strrchr($fileName,'.'),1);
}

function getFolderLocation($attachment)
{
    $objdal = new dal();

    //echo getFileExt($attachment);

    if (!getFileExt($attachment)) {
        $attachmentId = decryptId($attachment);
        $where = " WHERE a.`id` = $attachmentId";
    } else {
        $where = " WHERE a.`filename` = '$attachment'";
    }

    $sql = "SELECT 
                CONCAT(DATE_FORMAT(p.`createdon`, '%Y/%b'), '/', a.`poid`, '/', IFNULL(CONCAT(a.`shipno`, '/'), ''), a.`filename`) AS `folderLocation`
            FROM `wc_t_po` p 
                INNER JOIN `wc_t_attachments` a ON a.`poid` = p.`poid`
            $where;";
    //echo $sql;
    echo 'After building the array.<br>';
    print_mem();
    $folderLocation = $objdal->getScalar($sql);
    unset($objdal);
    return $folderLocation;
}


echo getFolderLocation('multiple_file_17022020102841.zip').'<br>';

echo 'After unsetting the array.<br>';
print_mem();

function print_mem()
{
    /* Currently used memory */
    $mem_usage = memory_get_usage();

    /* Peak memory usage */
    $mem_peak = memory_get_peak_usage();

    echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
    echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
}

?>

