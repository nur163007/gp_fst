    <?php
    if ( !session_id() ) {
        session_start();
    }
    /*
        Author: Nur Mohammad
        Copyright: 09.03.2022
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
            case 1:	// get single info
                echo GetCreditReortData();
                break;
            case 2:	// get all info
                echo json_encode(GetEditCreditReort($_GET["id"]));
                break;
            case 3:	// delete
                if(!empty($_GET["id"])) { echo DeleteUpdateCreditReort($_GET["id"]); } else { echo 0; };
                break;
            default:
                break;
        }
    }

    // Case for Insert and update
    if (!empty($_POST)){
    if (!empty($_POST["reportID"]) || isset($_POST["reportID"])){
    echo json_encode(StoreCreditReport());
    }
    }

    // Insert or update
    function StoreCreditReport(){
        global $user_id;
        $objdal = new dal();
        $id = htmlspecialchars($_POST['reportID'],ENT_QUOTES, "ISO-8859-1");
        $supplier = htmlspecialchars($_POST['supplier'],ENT_QUOTES, "ISO-8859-1");
        $bankid = htmlspecialchars($_POST['bankid'],ENT_QUOTES, "ISO-8859-1");
        $creditReportDate = htmlspecialchars($_POST['creditReportDate'],ENT_QUOTES, "ISO-8859-1");
        $creditReportDate = date('Y-m-d', strtotime($creditReportDate));
        $reportExpiryDate = htmlspecialchars($_POST['reportExpiryDate'],ENT_QUOTES, "ISO-8859-1");
        $reportExpiryDate = date('Y-m-d', strtotime($reportExpiryDate));
        $creditattahment = $objdal->sanitizeInput($_POST['attachCreditReport']);
        $creditattahmentOld = $objdal->sanitizeInput($_POST['attachCreditReportOld']);
        $ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");

        /*$ip = htmlspecialchars($_SERVER['REMOTE_ADDR'],ENT_QUOTES, "ISO-8859-1");*/

        //---To protect MySQL injection for Security purpose----------------------------
        $id = stripslashes($id);
        $supplier = stripslashes($supplier);
        $bankid = stripslashes($bankid);
        $creditReportDate = stripslashes($creditReportDate);
        $reportExpiryDate = stripslashes($reportExpiryDate);




        $id = $objdal->real_escape_string($id);
        $supplier = $objdal->real_escape_string($supplier);
        $bankid = $objdal->real_escape_string($bankid);
        $creditReportDate = $objdal->real_escape_string($creditReportDate);
        $reportExpiryDate = $objdal->real_escape_string($reportExpiryDate);

        //------------------------------------------------------------------------------

        //---return array---------------------------------------------------------------
        $res["status"] = 0;    // 0 = Failed, 1 = Success
        $res["message"] = 'FAILED!';
        //------------------------------------------------------------------------------

            if($id == 0 && $creditattahment !=''){
            $query = "INSERT INTO `update_credit_report` SET
            `supplierId` = $supplier,
            `crReport` = '$creditattahment',
            `issueDate` = '$creditReportDate',
            `expiryDate` = '$reportExpiryDate',
            `bankId` = $bankid,
            `createdby` = $user_id, 
            `createdfrom` = '$ip';";

            $objdal->insert($query);

            $lastCreditId = $objdal->LastInsertId();

                fileTransferCreditDocs($lastCreditId);

            } else {

                if ($creditattahment !=''){
                    $query = "UPDATE `update_credit_report` SET
                    `supplierId` = $supplier,
                    `crReport` = '$creditattahment',
                    `issueDate` = '$creditReportDate',
                    `expiryDate` = '$reportExpiryDate',
                    `bankId` = $bankid,
                    `createdby` = $user_id, 
                    `createdfrom` = '$ip'
                 WHERE `id` = $id;";
                }
                elseif ($creditattahment==''){
                    $query = "UPDATE `update_credit_report` SET
                    `supplierId` = $supplier,
                    `crReport` = '$creditattahmentOld',
                    `issueDate` = '$creditReportDate',
                    `expiryDate` = '$reportExpiryDate',
                    `bankId` = $bankid,
                    `createdby` = $user_id, 
                    `createdfrom` = '$ip'
                 WHERE `id` = $id;";
                }

                $objdal->update($query);

                $lastCreditId=$id;

                fileTransferCreditDocs($lastCreditId);
            }

        unset($objdal);

        $res["status"] = 1;
        $res["message"] = 'SUCCESS!';
        return $res;

        /*echo $query;*/

    }

    function GetCreditReortData()
    {
    $objdal=new dal();
    $strQuery="SELECT 
        ucr.`id`, ucr.`supplierId`, ucr.`crReport`, date_format(ucr.`issueDate`,'%d-%b-%Y') as `issueDate`,
        date_format(ucr.`expiryDate`,'%d-%b-%Y') as `expiryDate`, ucr.`bankId`, bn.`name` as `bankName`,
        co.`name` as `supplierName`
        FROM `update_credit_report` `ucr`
        LEFT JOIN `wc_t_company` bn ON ucr.`bankId` = bn.`id`
        LEFT JOIN `wc_t_company` co ON ucr.`supplierId` = co.`id`
        ORDER BY ucr.`id` ASC;";
    $objdal->read($strQuery);

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
    return $table_data;
    }

    function GetEditCreditReort($id)
    {
        $objdal = new dal();
        $query = "SELECT `id`, `supplierId`, `crReport`, date_format(`issueDate`,'%M %d,%Y') as `issueDate`,
        date_format(`expiryDate`,'%M %d,%Y') as `expiryDate`, `bankId` FROM `update_credit_report` WHERE `id` = $id;";
        $objdal->read($query);
        if(!empty($objdal->data)){
            $res = $objdal->data[0];
            extract($res);
        }
        unset($objdal);
        return $res;
    }

    function DeleteUpdateCreditReort($id)
    {
        fileDeleteCreditDocs($id);

        $objdal=new dal();
        $query="DELETE FROM `update_credit_report` WHERE `id` = $id;";
        $objdal->delete($query);
        unset($objdal);
        return 1;
    }

    ?>
