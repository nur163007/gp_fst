<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 02.2016
    Code fridged on: 
*/
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_POST)){
    
    $value = "'".$_POST["value"]."'";   // By default I prepared it for varcharchar type field
    
    switch($_POST["name"]){
        case 'povalue':
            $value = $_POST["value"];
            break;
        case 'podesc':            
        	break;
        case 'lcdesc':
        	break;
        case 'supplier':
            $value = $_POST["value"];
        	break;
        case 'currency':
            $value = $_POST["value"];
        	break;
        case 'contractref':
        	break;
        case 'deliverydate':
        	break;
        case 'draftsendby':
        	break;
        case 'emailto':
        	break;
        case 'emailcc':
        	break;
        case 'pruserto':
        	break;
        case 'prusercc':
        	break;
        case 'noflcissue':
            $value = $_POST["value"];
        	break;
        case 'nofshipallow':
            $value = $_POST["value"];
        	break;
        case 'installbysupplier':
            $value = "b'".$_POST["value"]."'";
        	break;
        case 'pinum':
        	break;
        case 'pivalue':
            $value = $_POST["value"];
        	break;
        case 'hscode':
        	break;
        case 'hscsea':
        	break;
        case 'shipmode':
        	break;
        case 'pidate':
        	break;
        case 'basevalue':
            $value = $_POST["value"];
        	break;
        case 'origin':
        	break;
        case 'negobank':
        	break;
        case 'shipport':
        	break;
        case 'lcbankaddress':
        	break;
        case 'productiondays':
        	break;
        case 'buyercontact': 
        	break;
        default:
            break;
    }
    
    $dal = new dal();    
    $sql = "UPDATE `wc_t_pi` SET `".$_POST["name"]."` = ".$value." WHERE `poid`='".$_POST["pk"]."';";
    $dal->update($sql);
    
    unset($dal);
    echo $value;
}

?>