<?php
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

function downloadAttachment($attachmentFile){

    $fileInfo = getFolderLocation($attachmentFile);
    $file = urlencode(dirname(__FILE__) . '/../../docs/'.$fileInfo["folderLocation"]);
    $file = realpath(urldecode($file));

    //echo $file;
    if(!$file)
    {
        //File doesn't exist, output error
         die('File not found');
    }
    else {
        header("Expires: 0");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $basename = pathinfo($file, PATHINFO_BASENAME);
        $fileName = preg_replace('~[\\\\/:*?"<>| ]~', '_', $fileInfo["title"])."_".$fileInfo["poid"].".".$ext;
        header("Content-type: application/" . $ext);
        // tell file size
        header('Content-length: ' . filesize($file));
        // set file name
        //header("Content-Disposition: attachment; filename=\"$basename\"");
        //header("Content-Disposition: attachment; filename=\"$fileName\""); //Forced download/prompt
        header("Content-Disposition: inline; filename=\"$fileName\""); //Browser tries to open the attachment
        readfile($file);
        // Exit script. So that no useless data is output.
        exit;
    }
}

//$attachmentId = decryptId($_GET['id']);
downloadAttachment($_GET['id']);


?>