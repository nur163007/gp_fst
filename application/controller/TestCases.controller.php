<?php
if (!session_id()) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");

class TestCases
{
    /**
     * @var dal
     */
    private $objdal;
    private $userId;
    private $loginRole;

    public function __construct(dal $objdal, $user_id, $loginRole)
    {
        $this->objdal = $objdal;
        $this->userId = $user_id;
        $this->loginRole = $loginRole;
    }

    public function __destruct()
    {
        // clean up resources here
    }

    /*!
     * Check who receives mail against the particular action ID
     * Mail receiver address can be fetched either against
     * db action_log ID or encrypted refId
     * 1. Change the param of the process in sendActionEmail
     * 2. Need to uncomment the "ELSE" condition in sendActionEmail
     * 3. $base_url/api/TestCases?action=mail-notification&actionId=123
     * ************************************************************/
    public function mailNotification($actionId)
    {
        //$actionId = decryptId('2917cea827db62db');
        $actionId = (is_numeric($actionId)) ? $actionId : decryptId($actionId);
        return sendActionEmail($actionId,$to='', $cc='', $debug=1);
    }
}

// $action = "";
if (!empty($_GET["action"])) {
    $action = $_GET["action"];
    $actionId = (isset($_GET['actionId'])) ? $_GET['actionId'] : '' ;
    global $user_id;
    global $loginRole;
    $objdal = new dal();
    $tcObj = new TestCases($objdal, $user_id, $loginRole);
} else {
    $action = "";
}
switch ($action) {
    case "mail-notification":
        echo $tcObj->mailNotification($actionId);
        unset($tcObj);
        break;

    default:
        http_response_code(404);
        exit('Not Found');
}