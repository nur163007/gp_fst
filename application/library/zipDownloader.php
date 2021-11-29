<?php
if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

if (isset($_POST['files'])) {
    $error = ""; //error holder
    if (isset($_POST['createzip'])) {
        $post = $_POST;
        //$fileList = "'" . implode ( "', '", $_POST['files'] ) . "'";
        $fileList = implode ( ",", $_POST['files'] );
        $file_dirs = getFolderLocation($fileList, 1); //Arg 1 means it for zip
        //var_dump($file_dirs);
        $file_folder = "../../docs/"; // folder to load files
        //$file_folder = "";
        if (extension_loaded('zip')) {
            // Checking ZIP extension is available
            if (isset($post['files']) and count($post['files']) > 0) {
                // Checking files are selected
                $zip = new ZipArchive(); // Load zip library
                $zip_name = $_POST["docName"]. "_" . $file_dirs[0]["poid"] . ".zip"; // Zip name
                if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== true) {
                    // Opening zip file to load files
                    $error .= "* Sorry ZIP creation failed at this time";
                }

                foreach ($file_dirs as $fileInfo) {
                    $fileLocation = $file_folder.$fileInfo['folderLocation'];
                    $ext = pathinfo($fileLocation, PATHINFO_EXTENSION);
                    $fileName = preg_replace('~[\\\\/:*?"<>| ]~', '_', $fileInfo["title"])."_".$fileInfo["poid"].".".$ext;
                    $zip->addFile($fileLocation, basename($fileName)); // Adding files into zip
                }
                $zip->close();
                if (file_exists($zip_name)) {
                    // push to download the zip
                    header('Content-type: application/zip');
                    header('Content-Disposition: attachment; filename="' . $zip_name . '"');
                    header('Pragma: no-cache');
                    header('Expires: 0');
                    readfile($zip_name);
                    // remove zip file is exists in temp path
                    unlink($zip_name);
                    exit;
                }

            } else
                $error .= "* Please select file to zip ";
        } else
            $error .= "* You dont have ZIP extension";
    }
}


?>