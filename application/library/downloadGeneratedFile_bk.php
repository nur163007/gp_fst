<?php

if(empty($_POST['filename']) || empty($_POST['content'])){
	exit;
}

$filename = preg_replace('/[^a-z0-9\-\_\.]/i','',$_POST['filename']);

//header("Cache-Control: ");
//header("Content-type: application/vnd.ms-word");
//header("Content-Disposition: attachment;Filename=".$filename."");

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$filename."");


echo $_POST['content'];

?>