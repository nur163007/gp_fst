<?php


require_once(realpath(dirname(__FILE__) . "/../application/config.php"));
require_once(realpath(dirname(__FILE__) . "/../application/library/dal.php"));
//require_once(LIBRARY_PATH . "/dal.php");

$objdal = new dal();

$sql = "SELECT 
            a.`poid`, a.`shipno`, a.`filename`, a.`attachedon`, p.`createdon`
		FROM `wc_t_attachments` a 
			INNER JOIN `wc_t_po` p ON p.`poid` = a.`poid`
		WHERE a.`poid` = '300038440PI1';";
$documents = $objdal->read($sql);

	var_dump($documents);
	
	$old_dir = "../temp/";
	$target_dir = "../docs/";

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
		if (file_exists($old_dir.'/'.$file)) {
			$copy_status = copy($old_dir.'/'.$file, $target_path_shipNo . '/' . $file);
			if ($copy_status == 1) {
				unlink($old_dir.'/'.$file);
			}
		}
	}
}

?>

