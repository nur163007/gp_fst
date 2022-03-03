<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// required to encode json web token
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/po.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate PO object
$po = new Po($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));
//var_dump($_SERVER);
//exit();
/*$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);*/

/*echo json_encode(array(
    "message" => "sd" .$arr[1]
));*/

//$jwt = $arr[1];


// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";

// if jwt is not empty
if($jwt){
    // if decode succeed, show user details
    try {

        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // set po property values
        $po->po_no = $data->po_no;
        $po->po_desc = $data->po_desc;
        $po->po_date = $data->po_date;
        $po->item_code = $data->item_code;
        $po->item_desc = $data->item_desc;
        $po->line_no = $data->line_no;
        $po->uom = $data->uom;
        $po->unit_price = $data->unit_price;
        $po->po_total = $data->po_total;
        $po->po_qty = $data->po_qty;
        $po->po_amount = $data->po_amount;
        $po->supplier = $data->supplier;
        $po->currency = $data->currency;
        $po->need_by_date = $data->need_by_date;
        $po->contract_no = $data->contract_no;
        $po->buyer = $data->buyer;
        $po->pr_no = $data->pr_no;
        $po->pr_user = $data->pr_user;
        $po->pr_user_dept = $data->pr_user_dept;
        $po->created_by = $decoded->data->id;

        // create the PO
        if($po->create()){

            // set response code
            http_response_code(200);

            // display message: user was created
            echo json_encode(array("message" => "PO created Successfully."));
        }

        // message if unable to create user
        else{

            // set response code
            http_response_code(400);

            // display message: unable to create user
            echo json_encode(array("message" => "Unable to create PO."));
        }

    }

        // if decode fails, it means jwt is invalid
    catch (Exception $e){

        // set response code
        http_response_code(401);

        // show error message
        echo json_encode(array(
            "message" => "Something went wrong!",
            "error" => $e->getMessage()
        ));
    }
}


// show error message if jwt is empty
else{

    // set response code
    http_response_code(401);

    // tell the user access denied
    echo json_encode(array("message" => "Invalid JSON or JWT Token."));
}
?>