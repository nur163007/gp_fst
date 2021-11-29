<?php
function download($name){

    $file = $name;
    $file = urlencode(dirname(__FILE__) . '/../../temp/'.$file);
    $file = realpath(urldecode($file));
    echo $file;
    
    if (file_exists($file)) {
        ob_start();
        header('Content-Description: File Transfer');
        //header('Content-Type: application/octet-stream');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        //if(ob_get_length() > 0) { ob_end_clean(); }
        flush();
        readfile($file);
        exit;
    }
}

$name= $_GET['ref'];
download($name);


?>