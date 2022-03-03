<?php

/*
	The important thing to realize is that the config file should be included in every
	page of your project (or at least any page you want access to these settings).
	This allows you to confidently use these settings throughout a big project because
	if something changes such as your DB credentials or a path to a specific resource
	you'll only need to update it here.
*/

/**
 * Modify PHP default settings
 * */
date_default_timezone_set('Asia/Dhaka');
if(php_sapi_name() !== 'cli') {
    ini_set("error_reporting", "true");
    error_reporting(E_ALL);
    ini_set('log_errors', TRUE); // Error logging engine
    //ini_set('error_log', CLASS_PATH); // Logging file path
    ini_set('log_errors_max_len', 1024); // Logging file size

    define("phpSelf", $_SERVER['PHP_SELF']); // /sub-dir/unit-test/url-check.php
    define("remoteAddr", $_SERVER['REMOTE_ADDR']); // 127.1.1.0
    define("requestUri", $_SERVER['REQUEST_URI']); // /sub-dir/unit-test/url-check.php
    define("userAgent", $_SERVER['HTTP_USER_AGENT']); // Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36
    if (isset($_SERVER['HTTPS'])) {
        define("domainName", "https" . "://$_SERVER[HTTP_HOST]");// https://example.com
    } else {
        define("domainName", "http" . "://$_SERVER[HTTP_HOST]");// http://example.com
    }
    define("fullUrl", domainName . requestUri); // http://example.com/sub-dir/unit-test/url-check.php
}

$config = array(
	"db" => array(
		"db1" => array(
			"dbname" => "database1",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		),
		"db2" => array(
			"dbname" => "database2",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		)
	),
	"urls" => array(
		"baseUrl" => "http://example.com"
	),
	"paths" => array(
		"resources" => "/path/to/resources",
		"images" => array(
			//"content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
			//"layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
		)
	)
);

/*
	I will usually place these next things in a bootstrap file or some type of environment
	setup file, but they work just as well in your config file if it's in php (some alternatives
	to php are xml or ini files).
*/

/*
	Creating constants for heavily used paths makes things a lot easier.
	ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("APPLICATION_PATH")
or define("APPLICATION_PATH", realpath(dirname(__FILE__) . '/'));

defined("LIBRARY_PATH")
	or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("TEMPLATES_PATH")
	or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

defined("CLASS_PATH")
	or define("CLASS_PATH", realpath(dirname(__FILE__) . '/controller'));

defined("HANDLER_PATH")
	or define("HANDLER_PATH", realpath(dirname(__FILE__) . '/handler'));


// for local
//define("const_wcadmin_path", "/fstV3/");
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define("const_wcadmin_path", "/fstV3/");
//    define("const_wcorporate_path", "/fstV3/ca-interface");
    define("const_LOGIN_PATH", "/fstV3/login");
    define("const_LOGOUT_PATH", "/fstV3/logout");
}else{
    define("const_wcadmin_path", "/");
    define("const_LOGIN_PATH", "/login");
    define("const_LOGOUT_PATH", "/logout");
}

// session prefix
define("session_prefix", "fst_");

/*!
 * Password strength check
 * @function - isPassStrong - Password strength checker
 * returns integer(bit)
 * @param - $userPass - Password given by user(String)
 * Added by: Hasan Masud
 * Added on: 2020-01-18
 * **********************************************************/
function isPassStrong($userPass)
{
    // Given password
    $password = $userPass;

    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        return false;
    } else {
        return true;
    }
}

define("appToken", "f6bc9c0b346a56e28f747b65a907252fcec8ca6c");
define("appId", 1);

// User Role constent---------------------------
define("role_Admin",1);
define("role_Buyer",2);
define("role_Supplier",3);
define("role_External_Approval",4);
define("role_Corporate_Affairs",5);
define("role_LC_Approvar_1",6);
define("role_LC_Approvar_2",7);
define("role_LC_Approvar_3",8);
define("role_LC_Approvar_4",9);
define("role_LC_Approvar_5",10);
define("role_LC_Operation",11);
define("role_LC_Ops_data_entry",12);
define("role_Warehouse",13);
define("role_Management",14);
define("role_PR_Users",15);
define("role_cert_final_approver",16);
define("role_Report_Viewer",17);
define("role_LC_Report_Viewer",18);
define("role_foreign_payment_team",19);
define("role_foreign_strategy",20);
define("role_public_regulatory_affairs",21);
define("role_insurance_company",22);
define("role_bank_lc",23);
define("role_head_of_treasury",24);
define("role_bank_fx",25);
define("role_cnf_agent",26);
define("role_coupa_user",27);

// Action constant---------------------------
define("action_New_PO_Issued",1);
define("action_PO_Rejected_by_Supplier",2);
define("action_Revised_PO_Sent",3);
define("action_Draft_PI_Submitted",4);
define("action_Draft_PI_Sent_for_PR_Feedback",5);
define("action_Draft_PI_Sent_for_EA_Feedback",6);
define("action_Draft_PI_Rejected_by_PR",7);
define("action_Draft_PI_Accepted_by_PR",8);
define("action_Draft_PI_Rejected_by_EA",9);
define("action_Draft_PI_Accepted_by_EA",10);
define("action_Requested_for_Draft_PI_Rectification",11);
define("action_Requested_for_Final_PI",12);
define("action_Final_PI_Submitted",13);
define("action_Final_PI_Sent_for_PR_Feedback",14);
define("action_Final_PI_Sent_for_EA_Feedback",15);
define("action_Final_PI_Rejected_by_PR",16);
define("action_Final_PI_Accepted_by_PR",17);
define("action_Final_PI_Rejected_by_EA",18);
define("action_Final_PI_Accepted_by_EA",19);
define("action_Requested_for_Final_PI_Rectification",20);
define("action_Final_PI_Accepted",21);
define("action_Sent_for_BTRC_Permission",22);
define("action_Rejected_by_BTRC",23);
define("action_Accepted_by_BTRC",24);
//-------------Start E delivery flow-------------
define("action_Final_PI_Accepted_EDelivery_with_LC",109);
define("action_Request_for_Bank_Forwarding_Letter", 110);
define("action_Request_for_BASIS_Approval_Letter", 111);
define("action_BASIS_Approval_Letter_Sent_by_Bank", 112);
define("action_BASIS_Approval_Letter_Shared_to_Buyer", 113);
//-------------End E delivery flow---------------
define("action_LC_Request_Sent",25);
define("action_Rejected_by_1st_Level",26);
define("action_Sent_Revised_LC_Request_1",27);
define("action_Approved_by_1st_Level",28);
define("action_Rejected_by_2nd_Level",29);
define("action_Sent_Revised_LC_Request_2",30);
define("action_Approved_by_2nd_Level",31);
define("action_Rejected_by_3rd_Level",32);
define("action_Sent_Revised_LC_Request_3",33);
define("action_Approved_by_3rd_Level",34);
define("action_Rejected_by_4th_Level",35);
define("action_Sent_Revised_LC_Request_4",36);
define("action_Approved_by_4th_Level",37);
define("action_Rejected_by_5th_Level",38);
define("action_Sent_Revised_LC_Request_5",39);
define("action_Approved_by_5th_Level",40);
define("action_Final_LC_Copy_Sent",41);
define("action_Requested_for_LC_Amendment",42);
define("action_Rejected_Amendment_Request",43);
define("action_Accepted_Amendment_Request",44);
define("action_Amendment_Request_Rejected_by_1nd_Level",45);
define("action_Revised_LC_Amendment_Sent_1",46);
define("action_Amendment_Request_Approved_by_1st_Level",47);
define("action_Amendment_Request_Rejected_by_4th_Level",48);
define("action_Revised_LC_Amendment_Sent_4",49);
define("action_Amendment_Request_Approved_by_4th_Level",50);
define("action_Amendment_Request_Rejected_by_5th_Level",51);
define("action_Revised_LC_Amendment_Sent_5",52);
define("action_Amendment_Request_Approved_by_5th_Level",53);
define("action_Amendment_Copy_Sent",54);
define("action_LC_Accepted",55);
define("action_Shared_Shipment_Schedule",56);
define("action_Rejected_Shipment_Schedule",57);
define("action_Accepted_Shipment_Schedule",58);
define("action_Shared_Shipment_Document",59);
define("action_Shipment_Document_Rejected",60);
define("action_Requested_for_Warehouse_Inputs",61);
define("action_Ship_Doc_Rejected_Warehouse",62);
define("action_Warehouse_Input_Updated_Pending_FN",63);
define("action_Requested_for_EA_Inputs",64);
define("action_Sent_for_Original_Document_Accpetance",65);
define("action_Original_Document_Rejected",66);
define("action_Original_Document_Accepted_By_EA",67);
define("action_Original_Document_Accepted_For_Document_Delivery",68);
define("action_Original_Document_Delivered",69);
define("action_Sent_for_Document_Endorsement",70);
define("action_Endorsed_Document_Delivered",71);
define("action_Requested_to_Collect_Original_Doc",72);
define("action_GIT_Receiving_Date_updated",73);
define("action_CD_BE_Copy_updated",74);     // Pay-Order requisition send to Finance
define("action_CD_Payment_updated_by_Fin",75);  // Pay-Order requisition updated
define("action_Avg_Cost_Cal_Done_by_Fin",76);
define("action_Edit_And_Send_For_Recheck",77);
define("action_BTRC_Process_Approved_by_3rd_Level",78);
define("action_BTRC_Process_Rejected_by_3rd_Level",79);
define("action_Ship_Doc_Shared_DHL_Track_Pending",80);
define("action_DHL_Track_No_Updated",81);
define("action_Ship_Doc_Accepted_Buyer_Pending_WH",82);
define("action_Ship_Doc_Accepted_Buyer_Pending_EA",83);
define("action_Ship_Doc_Rejected_EATeam",84);
define("action_Warehouse_Input_Updated_Pending_Avg_Cost",85);
define("action_Avg_Cost_Data_Updated",86);
define("action_Tentative_Delivery_Date_Updated",87);    // Mailed for IPC Number
define("action_CD_BE_Rejected_by_Fin",88);
define("action_Shared_Voucher_info_to_Fin",89);
define("action_Shipping_Doc_Rectified_by_Supplier",90);
define("action_Sent_to_BTRC_for_NOC",91);
define("action_EA_Inputs_Completed",92);
//CAC FAC Request Automation
define("action_Sight_Payment_Done_by_Fin",93);
define("action_TAC_Request_Send_by_Supplier",94);
define("action_TAC_Approved_by_PRUser",95);
define("action_TAC_Reject_by_PRUser",96);
define("action_TAC_Approved_by_Buyer",97);
define("action_TAC_Rejected_by_Buyer",98);
define("action_TAC_Approved_by_CPO",99);
define("action_TAC_Rejected_by_CPO",100);
define("action_TAC_Request_Rectified_by_Supplier",101);
define("action_Ready_For_Submission",102);
define("action_Rejected_by_PRA",103);

//C&F PROCESS
define("action_Request_for_CNF_Input",104);
define("action_CNF_Input_Given",105);
define("action_Accept_CNF_Inputs",106);
define("action_Reject_CNF_Inputs",107);
define("action_Pre_Alert_To_Bank_for_Org_Doc",108);
define("action_Shared_Shipment_Schedule_For_EDeliv_WO_LC",114);
define("action_Amendment_Request_By_TFO",115);
define("action_Amendment_Process_Done_By_Bank",116);

//--------FX Action----------------------------------------
define("action_fx_request_for_lc", 201);        //0 pending
define("action_fx_request_for_fee", 202);       //0 pending

define("action_fx_rfq_float_done", 203);        //1 RFQ float done by FSO
define("action_fx_rfq_invited_by_GP", 204);        //bank participation
define("action_fx_rfq_rate_given_by_bank", 205);        //bank participation
define("action_fx_rfq_end", 206);               //2 RFQ auto closed by system according to cutt of date

define("action_fx_rfq_sent_for_HOT_approval", 207);        //3 Selected bank(s) and submitted to HOT
define("action_fx_rfq_accepted_by_hot", 208);   //4 Accepted by HOT
define("action_fx_rfq_rejected_by_hot", 209);   //5 Rejected by HOT and pending
define("action_ready_for_fx_request", 210);   //...
// to FSO with rejection mark

// Inshurance company action
define("action_Request_for_CN_To_IC",301);
define("action_CN_Issued_by_IC",302);
define("action_CN_Accepted_by_TFO",303);
define("action_CN_Rejected_by_TFO",304);
define("action_Requested_for_Ins_Policy_by_TFO",310);
define("action_Ins_Policy_Sent_by_IC",311);

//LC PROCESS FOR TFO & BANK
define("action_Draft_LC_Request_sent_to_Bank",401);
define("action_Final_LC_Request_sent_to_Bank",402);
define("action_Draft_LC_Copy_Sent_to_GP",403);
define("action_Final_LC_Copy_Sent_to_GP",404);
define("action_Draft_LC_shared_to_buyer",405);
define("action_Draft_LC_shared_to_supplier",406);
define("action_Draft_LC_feedback_given_by_buyer",407);
define("action_Draft_LC_feedback_given_by_supplier",408);
define("action_Buyer_Supplier_feedback_accepted",409);



define("action_PO_Edited_by_Buyer",149);
define("action_PO_Cancel",150);
define("action_Payment_Complete",151); //Payment 100% complete/ FAC Payment
define("action_Docs_Received_by_EA",152); //Documents received by EA (Custom clearance process starts)
define("action_actual_arrival_at_port",153); //Bill of entry given by EA
define("action_Release_from_Port",154); //Items released from port
define("action_CAC_Payment",155); // CAC/PAC(both are same)
define("action_FAC_Payment",156);
define("action_GLC_Payment",157);
define("action_SIC_Payment",158);
define("action_SIAC_Payment",159);
define("action_DFS_Payment",160);
define("action_Close_PO",161);
//---------------------------------------------------------
define("requisition_type",114);

//---------------------------------------------------------

define("doctype_original", 47);
define("doctype_endorsed", 48);
define("party_applicant", 49);
define("party_benificiary", 50);

//Payment Types
define("payment_Sight", 6);
define("payment_CAC", 7);
define("payment_FAC", 8);
define("payment_PAC", 77);
define("payment_GLC", 81);
define("payment_SIC", 82);
define("payment_SIAC", 83);
define("payment_DFS", 84);

// Company type
define("company_type_OWNER", 117);
define("company_type_BANK", 118);
define("company_type_INSURANCE", 119);
define("company_type_CNF", 120);
define("company_type_SUPPLIER", 121);

//BTRC division
define("btrc_division_ENO", 115);
define("btrc_division_SM", 116);
?>