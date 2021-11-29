<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 10-Dec-18
 * Time: 10:38 AM
 */

if ( !session_id() ) {
    session_start();
}

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

if (!empty($_GET["action"]) || isset($_GET["action"]))
{
    switch($_GET["action"])
    {
        case 1:	// get contract List
            echo getContractList();
            break;
        case 2:	// get contract details
            echo getContractDetails($_GET["contractId"]);
            break;
        case 3:	// get all contracts
            echo allContracts();
            break;
        case 4:	// get contract
            echo getContract($_GET["contractId"]);
            break;
        case 5:	// get contract list for select
            echo contractList();
            break;
    }
}
// Case for Insert or Update
if (!empty($_POST)){

    if (!empty($_POST["contractId"]) || isset($_POST["contractId"])){
        echo json_encode(saveContract());
    }

}

/*!
 * Insert or update contract terms
 * *****************************************************/
function saveContract()
{
    /*echo '<pre>';
    var_dump($_POST);
    echo '</pre>';
    die();*/
    $objdal = new dal();
    global $user_id;

    $contractId =  $objdal->sanitizeInput($_POST['contractId']);
    $contractName =  $objdal->sanitizeInput($_POST['contractName']);
    $contractDesc =  ($_POST['contractDesc']) ? $objdal->sanitizeInput($_POST['contractDesc']) : null;
    $termAttach = $objdal->sanitizeInput($_POST['termAttach']);

    /*IMPLEMENTATION BY GP
    * ***************************/
    if (isset($_POST['enableGp'])) {
        $gp_implnBy = $objdal->sanitizeInput($_POST['gp_implnBy']);
        $gp_paymentTermsText = $objdal->sanitizeInput($_POST['gp_paymentTermsText']);
    }

    /*IMPLEMENTATION BY SUPPLIER
     * ******************************/
    if (isset($_POST['enableSup'])) {
        $sup_implnBy = $objdal->sanitizeInput($_POST['sup_implnBy']);
        $sup_paymentTermsText = $objdal->sanitizeInput($_POST['sup_paymentTermsText']);
    }

    /*IMPLEMENTATION BY OTHER
    * *****************************/
    if (isset($_POST['enableOth'])) {
        $oth_implnBy = $objdal->sanitizeInput($_POST['oth_implnBy']);
        $oth_paymentTermsText = $objdal->sanitizeInput($_POST['oth_paymentTermsText']);
    }

    $ip = $objdal->sanitizeInput($_SERVER['REMOTE_ADDR']);

    //------------------------------------------------------------------------------

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'Failed to process the request!';
    //------------------------------------------------------------------------------
    //@todo upload this file
    if ($termAttach != ""){
        $old_dir = realpath(dirname(__FILE__) . "/../../temp/");
        $target_path_reqId = realpath(dirname(__FILE__) . "/../../docs/vendors_terms/");
        if (file_exists($old_dir.'/'.$termAttach)) {
            $copy_status = copy($old_dir.'/'.$termAttach , $target_path_reqId.'/'.$termAttach);
            if($copy_status==1) {
                unlink($old_dir.'/'.$termAttach);
            }
        }
    }
    if ($contractId == 0) {
        $query = "INSERT INTO `wc_t_contract` SET 
		`contractName` = '$contractName', 
		`contractDesc` = '$contractDesc', 
		`termAttach` = '$termAttach', 
		`createdBy` = $user_id,
		`createdFrom` = '$ip';";
        //echo $query;
        //die();
        $objdal->insert($query);
        $contractId = $objdal->LastInsertId();
    }else{
        $upQuery = "UPDATE `wc_t_contract` SET 
		`contractName` = '$contractName', 
		`contractDesc` = '$contractDesc', 
		`termAttach` = '$termAttach',
		`createdBy` = $user_id,
		`createdFrom` = '$ip'
		WHERE `id` = $contractId;";
        //echo $upQuery;
        $objdal->update($upQuery);

        //Delete old contract terms
        $delSql = "DELETE FROM `wc_t_contract_terms` WHERE `contractId` = $contractId;";
        $objdal->delete($delSql);
    }


    $sql_Terms = 'INSERT INTO `wc_t_contract_terms` (
                    `contractId`, `implnBy`, `percentage`, `certificateName`, `matDays`, `matTerms`, `certDays`, `certTitle`,
                    `paymentTermsText`, `createdBy`, `createdFrom` 
                    ) VALUES ';

    /*IMPLEMENTATION BY GP
    **********************/
    $query_parts_gp = array();
    if (isset($_POST['enableGp'])) {
        for ($x = 0; $x < count($_POST['gp_percentage']); $x++) {

            $gp_percentage[$x] = $objdal->sanitizeInput($_POST['gp_percentage'][$x]);
            $gp_certificateName[$x] = $objdal->sanitizeInput($_POST['gp_certificateName'][$x]);
            $gp_matDays[$x] = $objdal->sanitizeInput($_POST['gp_matDays'][$x]);
            $gp_matTerms[$x] = $objdal->sanitizeInput($_POST['gp_matTerms'][$x]);
            //$gp_matTerms[$x] = ($_POST['gp_matTerms'][$x]) ? $objdal->sanitizeInput($_POST['gp_matTerms'][$x]) : 0;
            $gp_certDays[$x] = ($_POST['gp_certDays'][$x]) ? $objdal->sanitizeInput($_POST['gp_certDays'][$x]) : 0;
            $gp_certTitle[$x] = $objdal->sanitizeInput($_POST['gp_certTitle'][$x]);

            $query_parts_gp[] = "($contractId, $gp_implnBy, '" . $gp_percentage[$x] . "', $gp_certificateName[$x], '" . $gp_matDays[$x] . "',
                            '" . $gp_matTerms[$x] . "', '" . $gp_certDays[$x] . "', '" . $gp_certTitle[$x] . "', '" . $gp_paymentTermsText . "', 
                                 $user_id, '" . $ip . "')";
        }
    }

    /*IMPLEMENTATION BY Supplier
    ****************************/
    $query_parts_supp = array();
    if (isset($_POST['enableSup'])) {
        for ($x = 0; $x < count($_POST['sup_percentage']); $x++) {

            $sup_percentage[$x] = $objdal->sanitizeInput($_POST['sup_percentage'][$x]);
            $sup_certificateName[$x] = $objdal->sanitizeInput($_POST['sup_certificateName'][$x]);
            $sup_matDays[$x] = $objdal->sanitizeInput($_POST['sup_matDays'][$x]);
            $sup_matTerms[$x] = $objdal->sanitizeInput($_POST['sup_matTerms'][$x]);
            $sup_certDays[$x] = ($_POST['sup_certDays'][$x]) ? $objdal->sanitizeInput($_POST['sup_certDays'][$x]) : 0;
            $sup_certTitle[$x] = $objdal->sanitizeInput($_POST['sup_certTitle'][$x]);

            $query_parts_supp[] = "($contractId, $sup_implnBy, '" . $sup_percentage[$x] . "', $sup_certificateName[$x], '" . $sup_matDays[$x] . "',
                            '" . $sup_matTerms[$x] . "', '" . $sup_certDays[$x] . "', '" . $sup_certTitle[$x] . "', '" . $sup_paymentTermsText . "', 
                                 $user_id, '" . $ip . "')";
        }
    }

    /*IMPLEMENTATION BY Other
    ****************************/
    $query_parts_oth = array();
    if (isset($_POST['enableOth'])) {
        for ($x = 0; $x < count($_POST['oth_percentage']); $x++) {

            $oth_percentage[$x] = $objdal->sanitizeInput($_POST['oth_percentage'][$x]);
            $oth_certificateName[$x] = $objdal->sanitizeInput($_POST['oth_certificateName'][$x]);
            $oth_matDays[$x] = $objdal->sanitizeInput($_POST['oth_matDays'][$x]);
            $oth_matTerms[$x] = $objdal->sanitizeInput($_POST['oth_matTerms'][$x]);
            $oth_certDays[$x] = ($_POST['oth_certDays'][$x]) ? $objdal->sanitizeInput($_POST['oth_certDays'][$x]) : 0;
            $oth_certTitle[$x] = $objdal->sanitizeInput($_POST['oth_certTitle'][$x]);

            $query_parts_oth[] = "($contractId, $oth_implnBy, '" . $oth_percentage[$x] . "', $oth_certificateName[$x], '" . $oth_matDays[$x] . "',
                            '" . $oth_matTerms[$x] . "', '" . $oth_certDays[$x] . "', '" . $oth_certTitle[$x] . "', '" . $oth_paymentTermsText . "', 
                                 $user_id, '" . $ip . "')";
        }
    }

    $sql_Terms .= implode(',', array_merge($query_parts_gp, $query_parts_supp, $query_parts_oth));
    //echo $sqlGpLines.'<br>';
    $objdal->insert($sql_Terms);

    unset($objdal);

    $res = ["status" => 1, "message" => "Contract created successfully"];
    return $res;
}


// Generating JSON data for select2 list control
function getContractList()
{
    $objdal = new dal();
    $strQuery="SELECT `id`, `contractName` FROM `wc_t_contract`;";
    //echo $strQuery;
    $objdal->read($strQuery);

    // json
    $jsondata = '[';
    $jsondata .= '{"id": "", "text": ""}';
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            $jsondata .= ', {"id": "'.$id.'", "text": "'.$contractName.'"}';
        }
    }
    $jsondata .= ']';
    unset($objdal);
    return $jsondata;

}

// get contract details
function getContractDetails($cId)
{

    $objdal = new dal();

    //Query to get contact info
    $sql_contract = "SELECT `id`, `contractName`, `contractDesc`, `termAttach` FROM `wc_t_contract` WHERE `id` = $cId;";

    $objdal->read($sql_contract);

    if (!empty($objdal->data)) {
        $contract[0] = $objdal->data[0];
    }
    $contract_terms_gp = contractTerms($cId, 0);
    $contract_terms_sup = contractTerms($cId, 1);
    $contract_terms_oth = contractTerms($cId, 2);
    unset($objdal);

    return json_encode(array($contract, $contract_terms_gp, $contract_terms_sup, $contract_terms_oth));
}

function contractTerms ($cId, $implnBy)
{
    $objdal = new dal();

    $i = 0;
    $sql_terms = "SELECT 
                    `id`, 
                    `contractId`, 
                    `implnBy`, 
                    `percentage`, 
                    `certificateName`, 
                    `matDays`, 
                    `matTerms`, 
                    `certDays`, 
                    `certTitle`, 
                    `paymentTermsText` 
                FROM 
                    `wc_t_contract_terms` 
                WHERE 
                    `contractId` = $cId AND `implnBy` = $implnBy;";
    //echo $sql_terms;
    $objdal->read($sql_terms);
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $val) {
            $contract_terms[$i] = $val;
            $i++;
        }
    } else {
        $contract_terms = [];
    }

    unset($objdal);

    return $contract_terms;

}

/*!
 * Get all contracts
 * *********************/
function allContracts()
{
    global $loginRole;
    $objdal = new dal();

    $sql = "SELECT
               c.`id`, cm.`name` AS `supplierName`, c.`contractName`,
               (SELECT GROUP_CONCAT(ct0.`percentage`) FROM `wc_t_contract_terms` ct0
               WHERE ct0.`contractId` = c.`id` AND ct0.`implnBy` = 0) AS `payTermGP`,
    
               (SELECT GROUP_CONCAT(ct1.`percentage`) FROM `wc_t_contract_terms` ct1
               WHERE ct1.`contractId` = c.`id` AND ct1.`implnBy` = 1) AS `payTermSup`,
            
               (SELECT GROUP_CONCAT(ct2.`percentage`) FROM `wc_t_contract_terms` ct2
               WHERE ct2.`contractId` = c.`id` AND ct2.`implnBy` = 2) AS `payTermOth`
            FROM `wc_t_contract` c
                INNER JOIN `wc_t_contract_terms` ct ON ct.`contractId` = c.`id`
                LEFT JOIN `wc_t_company` cm ON FIND_IN_SET(c.`id`, cm.`contractRef`)
            GROUP BY c.`id` ORDER BY c.`id` DESC;";
    //echo $sql;
    $objdal->read($sql);

    $rows = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $row) {
            $rows[] = $row;
        }
    }
    unset($objdal);
    $json = json_encode($rows);
    if ($json == "" || $json == 'null') {
        $json = "[]";
    }
    $table_data = '{"data": ' . $json . '}';
    //return $table_data;
    if ($loginRole == role_Admin) {
        return $table_data;
    } else {
        return $table_data = '{"data": ' . "[]" . '}';
    }
}

/*!
 * Get single contract implementation details
 * This function is used in buyer's-lc-request module
 * @param $contractId(int)
 * @param $implnBy(int)
 * Added by: Hasan Masud
 * Added on: 2020-07-25
 * *********************************************************/
function getContract($contractId){
    $objdal = new dal();

    $sql_contract = "SELECT `contractName`, `termAttach` FROM `wc_t_contract` WHERE `id` = $contractId;";
    $contract = $objdal->getRow($sql_contract);
    unset($objdal->data);

    $sql_terms = "SELECT `implnBy`, `percentage`, `certificateName`, `matDays`, `matTerms`, `certDays`, `certTitle`, `paymentTermsText`
                FROM `wc_t_contract_terms` 
            WHERE `contractId` = $contractId;";
    $objdal->read($sql_terms);
    //echo $sql_terms;
    $contract_terms = array();
    if (!empty($objdal->data)) {
        foreach ($objdal->data as $val) {
            $contract_terms[] = $val;
        }
    }

    unset($objdal);

    return json_encode(array($contract, $contract_terms));

}

/*!
 * get contract list for select2
 * *****************************/
function contractList(){
    global $loginRole;
    if($loginRole == role_Admin) {
        $objdal = new dal();
        $sql = "SELECT `id`, `contractName` FROM `wc_t_contract`;";
        $objdal->read($sql);

        // json
        $jsondata = '[';
        $jsondata .= '{"id": "", "text": ""}';
        if (!empty($objdal->data)) {
            foreach ($objdal->data as $val) {
                extract($val);
                $jsondata .= ', {"id": "' . $id . '", "text": "' . $contractName . '"}';
            }
        }
        $jsondata .= ']';
        unset($objdal);
        return $jsondata;
    }else{
        return json_encode('Invalid request');
    }
}