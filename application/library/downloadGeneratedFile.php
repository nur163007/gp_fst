<?php

if(empty($_POST['fileName']) || empty($_POST['letterContent'])){
	echo "nothing";
    exit;
}

$filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['fileName']);

$myfile = fopen($filename, "w");

$txt = $_POST['letterContent'];
fwrite($myfile, $txt);
fclose($myfile);

//header("Cache-Control: ");
//header("Content-type: application/vnd.ms-word");
//header("Content-Disposition: attachment;Filename=".$filename."");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$filename."");

echo $_POST['letterContent'];

?>