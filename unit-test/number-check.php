<?php


require_once(realpath(dirname(__FILE__) . "/../application/config.php"));
require_once(realpath(dirname(__FILE__) . "/../application/library/dal.php"));
//require_once(LIBRARY_PATH . "/dal.php");

/*$objdal = new dal();

$testNum = 'SELECT * FROM `wc_t_users`';
$testNum1 = $objdal->sanitizeInput($testNum);*/
$data = '???&*(^///\\\(%^*$ -- ???&amp;*(^///\(%^*$';
function sanitizeInput($data)
{
	$objdal = new dal();
	//$data = trim($data);// 1
	$data = htmlspecialchars($data, ENT_QUOTES, "ISO-8859-1");// 2
	$data = stripslashes($data);// 3
	$data = $objdal->real_escape_string($data);// 4
	return $data;
}

$data = sanitizeInput($data);

$objdal = new dal();
$sql = "INSERT INTO `wc_t_category` set `name` = '$data';";
$objdal->insert($sql);
unset($objdal);

?>

