<?php
if ( !session_id() ) {
	session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	
			if(!empty($_GET["shipno"])){
	           echo getAttachment($_GET["po"], $_GET["shipno"]);
            } else{
                echo getAttachment($_GET["po"]);
            }
			break;
		default:
			break;
	}
}

if (!empty($_POST)){
    if(!empty($_POST["attachmentPOID"]) || isset($_POST["attachmentPOID"])){
        if(!empty($_POST["replaceAttachNew"]) || isset($_POST["replaceAttachNew"])){        
	       echo ReplaceAttachment();
       }
    }
}

// Insert
function ReplaceAttachment()
{
	global $user_id;
	global $loginRole;

	$poid = htmlspecialchars($_POST['attachmentPOID'], ENT_QUOTES, "ISO-8859-1");
	$docid = htmlspecialchars($_POST['attachmentDocID'], ENT_QUOTES, "ISO-8859-1");
	$newfile = htmlspecialchars($_POST['replaceAttachNew'], ENT_QUOTES, "ISO-8859-1");


	$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'], ENT_QUOTES, "ISO-8859-1");


	//---To protect MySQL injection for Security purpose----------------------------
	$poid = stripslashes($poid);
	$docid = stripslashes($docid);
	$newfile = stripslashes($newfile);

	$objdal = new dal();

	$poid = $objdal->real_escape_string($poid);
	$docid = $objdal->real_escape_string($docid);
	$newfile = $objdal->real_escape_string($newfile);
	//------------------------------------------------------------------------------

	//---return array---------------------------------------------------------------
	$res["status"] = 0;    // 0 = failed, 1 = success
	$res["message"] = 'Failed!';
	//------------------------------------------------------------------------------

	// Update attachment
	$res["message"] = 'Failed to save attachments!';
	$query = "UPDATE `wc_t_attachments` SET `filename` = '$newfile' WHERE `poid` = '$poid' AND `id`=$docid;";

	$objdal->update($query);
	//echo($query);
	//Transfer file from 'temp' directory to respective 'docs' directory
	fileTransferTempToDocs($poid);

	unset($objdal);

	$res["status"] = 1;
	$res["message"] = 'Replaced Successfully';
	return json_encode($res);
}

function getAttachment($pono, $shipno=0)
{

	global $user_id;
	global $loginRole;

	$objdal = new dal();

	if ($shipno > 0) {
		$shipCondition = "  AND a2.`shipno` = $shipno ";
	} else {
		$shipCondition = "";
	}

	$query = "SELECT a.`id`, a.`poid`, a.`title`, a.`filename`, a.`attachedon`, r.`name` `rolename`, 
        SUBSTRING(a.`filename`, LENGTH(a.`filename`)-(INSTR(REVERSE(a.`filename`), '.')-2)) `ext`
        FROM `wc_t_attachments` a 
            INNER JOIN `wc_t_users` u ON a.`attachedby` = u.`id` 
            INNER JOIN `wc_t_roles` r ON u.`role` = r.`id` 
        WHERE a.id = (SELECT a2.id
             FROM `wc_t_attachments` a2
             WHERE a2.`title` = a.`title` AND 
                a2.`grouponly` IS null AND 
                a2.`poid` = '$pono' 
                $shipCondition
             ORDER BY a2.`attachedon` DESC
             LIMIT 1)
        ORDER BY a.`attachedby`, a.`id`;";

	$objdal->read($query);

	$i=0;
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
			array_push($val, encryptId($val['id']));
            $attach[$i] = $val;
            $i++;
        }
    }

	/*if (!empty($objdal->data)) {
		$attach = $objdal->data;
		extract($attach);
	}*/
	unset($objdal);
	return json_encode(array($attach));
}

?>

