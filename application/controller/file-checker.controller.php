<?php
if ( !session_id() ) {
	session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// Get All missing files
			echo getMissingFiles();
			break;
		case 2:	// Transfer temp to docs
			echo tempToDocs();
			break;
		case 3:	// system under maintenance
			echo systemUC($_GET["ucVal"]);
			break;
		case 4:
			echo missingPIFiles();
			break;
		case 5:	//Copy from PO to PI folder
			echo cpPOtoPI(' 60001130PI1', ' 60001130PI2');
			break;
	}
}

// Get All missing files in temp
function getMissingFiles()
{
	$objdal = new dal();

	$sql = "SELECT `filename` FROM `wc_t_attachments`;";
	$result = $objdal->read($sql);

	$arrSize = count($result);
	$filePath = realpath(dirname(__FILE__) . "/../../temp");
	//var_dump($filePath);
	for ($i = 0; $i < $arrSize; $i++){
		$filename = $filePath."\\".$result[$i]["filename"];
		var_dump($filename);
		if (!file_exists($filename)) {
			echo $result[$i]["filename"]."<br/>";
		}
	}

	unset($objdal);
	die();
}

function tempToDocs()
{
	$objdal = new dal();

	$sql = "SELECT 
            a.`poid`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
		FROM `wc_t_attachments` a
			INNER JOIN `wc_t_po` p ON p.`poid` = a.`poid`
		WHERE p.`poid` = '300043132PI1';";
	$documents = $objdal->read($sql);

	var_dump($documents);

	$old_dir = "../../temp/";
	$target_dir = "../../docs/";

	foreach ($documents as $document) {

		/*!
         * Create files with given name
         * This is for test only
         * ****************************/
		//$myfile = fopen($old_dir.$document['filename'], "w");

		$year = date('Y', strtotime($document['createdon']));
		$month = date('M', strtotime($document['createdon']));
		$poNo = $document['poid'];
		$shipNo = $document['shipno'];

		$target_path_year = $target_dir . $year;
		if (!file_exists($target_path_year)) {
			mkdir($target_path_year, 0777, true);
		}

		$target_path_month = $target_path_year . '/' . $month;
		if (!file_exists($target_path_month)) {
			mkdir($target_path_month, 0777, true);
		}

		$target_path_poNo = $target_path_month . '/' . $poNo;
		if (!file_exists($target_path_poNo)) {
			mkdir($target_path_poNo, 0777, true);
		}

		$target_path_shipNo = $target_path_poNo . '/' . $shipNo;
		if (!file_exists($target_path_shipNo)) {
			mkdir($target_path_shipNo, 0777, true);
		}


		$file = $document['filename'];
		if ($file != '') {
			if (file_exists($old_dir . '/' . $file)) {
				$copy_status = copy($old_dir . '/' . $file, $target_path_shipNo . '/' . $file);
				/*if ($copy_status == 1) {
					unlink($old_dir.'/'.$file);
				}*/
				echo $file . " Copied </br>";
			}
		}
	}
}

/*!
 * System under maintenance
 * */
function systemUC($ucVal = 0)
{
	/*$fname = "../../index.php";
	$fhandle = fopen($fname,"r");
	$content = fread($fhandle,filesize($fname));

	if ($ucVal == 0) {
		$message = "FST in now live/available.";
		$content = str_replace("maintenance = 1", "maintenance = 0", $content);
	}else{
		$message = "FST in now under maintenance.";
		$content = str_replace("maintenance = 0", "maintenance = 1", $content);
	}
	$fhandle = fopen($fname,"w");
	fwrite($fhandle,$content);
	fclose($fhandle);
	die($message);*/
}

/*!
 * Get missing files in PI folder
 * ******************************/
function missingPIFiles(){
	$poNo = ' 60001130PI2';
	$objdal = new dal();

	$sql = "SELECT 
                a.`poid`, a.`title`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
            FROM `wc_t_attachments` a 
			INNER JOIN `wc_t_po` p ON p.`poid` = a.`poid`
            WHERE a.`title` IN ('PO','BOQ') AND a.`poid` IN('60001084PI2', '300041235PI5', '300047064PI2', '60000045PI2') 
            GROUP BY a.`poid`, a.`title` ORDER BY a.`attachedon` DESC;";
	$documents = $objdal->read($sql);

	$target_dir = realpath(dirname(__FILE__) . "/../../docs/");

	foreach ($documents as $document) {
		$year = date('Y', strtotime($document['createdon']));
		$month = date('M', strtotime($document['createdon']));
		$poNo = $document['poid'];
		$target_path = $target_dir.'/'.$year.'/'.$month.'/'.$poNo.'/'.$document['filename'];
		//echo $target_path."</br>";
		if (!file_exists($target_path)) {
			echo $document["poid"].'|'.$document["title"].'|'.$document["filename"]."<br/>";
		}
	}

	unset($objdal);
	die();
}

function cpPOtoPI($po, $pi){
	$objdal = new dal();

	$target_dir = realpath(dirname(__FILE__) . "/../../docs");

	$query = "SELECT p.`poid`, p.`createdon` FROM `wc_t_po` p WHERE p.`poid` = '$pi';";
	//echo $query;
	$result = $objdal->getRow($query);

	$d_year = date('Y', strtotime($result['createdon']));
	$d_month = date('M', strtotime($result['createdon']));
	$d_poNo = $result['poid'];

	$target_path_year = $target_dir . '/' . $d_year;
	if (!file_exists($target_path_year)) {
		mkdir($target_path_year, 0777, true);
	}

	$target_path_month = $target_path_year . '/' . $d_month;
	if (!file_exists($target_path_month)) {
		mkdir($target_path_month, 0777, true);
	}

	$target_path_poNo = $target_path_month . '/' . $d_poNo;
	if (!file_exists($target_path_poNo)) {
		mkdir($target_path_poNo, 0777, true);
	}
	$target_path = $target_path_poNo;
	unset($objdal->data);


	$sql = "SELECT 
                a.`poid`, a.`title`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
            FROM `wc_t_attachments` a 
			INNER JOIN `wc_t_po` p ON p.`poid` = a.`poid`
            WHERE a.`title` IN ('PO','BOQ') AND a.`poid` = '$po'
            GROUP BY a.`poid`, a.`title` ORDER BY a.`attachedon` DESC;";
	$documents = $objdal->read($sql);

	foreach ($documents as $document) {
		$year = date('Y', strtotime($document['createdon']));
		$month = date('M', strtotime($document['createdon']));
		$poNo = $document['poid'];
		$source = $target_dir.'/'.$year.'/'.$month.'/'.$poNo.'/'.$document['filename'];
		$destination = $target_path.'/'.$document['filename'];
		if (!file_exists($destination)) {
			//echo $document["poid"].'|'.$document["title"].'|'.$document["filename"]."<br/>";
			if (!copy($source, $destination)){
				return 'Could not copy base PO files';
			}
		}
	}

	unset($objdal);
	die();
}

?>

