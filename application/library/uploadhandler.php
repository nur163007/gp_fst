<?php

// A list of permitted file extensions
$allowed = array('jpg','jpeg', 'png', 'xlsx', 'xls', 'doc', 'docx', 'pdf', 'zip', 'csv');

$res["status"] = 0; // 0 = fail, 1 = success
$res["msg"] = 'Failed!';
$res["filename"] = '';

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}
    
    date_default_timezone_set('Asia/Dhaka');

    $filename = $_FILES['upl']['name'];

    $str = strip_tags($filename);
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace(' ', '-', $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    $str = str_replace('--', '-', $str);

    //$newname = substr($str,0,stripos($str,'.')).'_'.date("dmYhis").'.'.$extension;
    $newname = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'),1, 10).'_'.date("YmdHis").'.'.$extension;
    $newname = preg_replace('/_{2,}/','_',$newname); //Added on: 2020-07-05

	if(move_uploaded_file($_FILES['upl']['tmp_name'], '../../temp/'.$newname )){
		$res["status"] = 1; // 0 = fail, 1 = success
        $res["msg"] = 'Success!';
        $res["filename"] = $newname;
        echo json_encode($res);
		exit;
	}
}


if(isset($_GET['del']) && !empty($_GET['del'])){
    try{
        if(file_exists('../../'.$_GET['del'])) {
            //unlink( str_replace('temp', '../../temp', $_GET['del']));
            unlink('../../' . $_GET['del']);
            $res["status"] = 1; // 0 = fail, 1 = success
            $res["msg"] = 'File deleted!';
            echo json_encode($res);
        } else{
            $res["status"] = 1; // 0 = fail, 1 = success
            $res["msg"] = 'File does not exist!';
            echo json_encode($res);
        }
    } catch (e$x){
        $res["status"] = 0; // 0 = fail, 1 = success
        $res["msg"] = 'Failed!';
        echo json_encode($res);
        exit;
    }
    exit;
}