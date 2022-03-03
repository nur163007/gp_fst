<?php
// PO dump object

/*
 * Payload format
 {
	"po_no": "60006457PI1",
	"po_value": 8853847.66,
	"po_desc": "NFVI Network Switch and Accessories",
	"import_as": "Software",
	"supplier_id": "BDFS0078",
	"po_buyer": "a_bakar@grameenphone.com",
	"pr_no": "50000774",
	"pr_user": "faisal.mobarak@grameenphone.com",
	"pr_user_dept": "faisal.mobarak@grameenphone.com",
	"currency": "USD",
	"actual_po_date": "2021-06-05",
	"draft_pi_last_date": "2021-06-10",
    "need_by_date": "2021-08-09",
	"contract_no": "GP-03835",
	"po_type": "CAPEX",
	"implementation_by":"GP",
	"attachments": {
		"po": "abc_xyz.pdf",
		"boq": "def_xyz.pdf"
	},
	"po_lines": [
		{
			"line_no": 1,
			"item_code": "3899553",
			"item_desc": "NFVI Network Switch and Accessories",
			"project_code": "Random",
			"uom": "Each",
			"line_qty": 48,
			"unit_price": 541.35,
	        "delivery_date": "2021-08-09"
		},
		{
			"line_no": 2,
			"item_code": "3888841",
			"item_desc": "NFVI Network Switch and Accessories",
			"project_code": "Random",
			"uom": "Each",
			"line_qty": 4,
			"unit_price": 4748.64,
	        "delivery_date": "2021-08-09"
		},
		{
			"po_id": "60006457PI1",
			"line_no": 3,
			"item_code": "3770075",
			"item_desc": "NFVI Network Switch and Accessories",
			"project_code": "IPL551",
			"uom": "Each",
			"line_qty": 7,
			"unit_price": 881.32,
	        "delivery_date": "2021-08-09"
		}
	]
}
 */

class Po
{
    // database connection and table name
    private $conn;
    private $table_name = "po_dump_temp";

    // object properties
    public $po_no;
    public $po_desc;
    public $po_date;
    public $item_code;
    public $item_desc;
    public $line_no;
    public $uom;
    public $unit_price;
    public $po_total;
    public $po_qty;
    public $po_amount;
    public $supplier;
    public $currency;
    public $need_by_date;
    public $contract_no;
    public $buyer;
    public $pr_no;
    public $pr_user;
    public $pr_user_dept;
    public $created_by;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // create new user record
    function create()
    {

        if (empty($this->po_no) ||
            empty($this->po_desc) ||
            empty($this->po_date) ||
            empty($this->item_code) ||
            empty($this->item_desc) ||
            empty($this->line_no) ||
            empty($this->uom) ||
            empty($this->unit_price) ||
            empty($this->po_total) ||
            empty($this->po_qty) ||
            empty($this->po_amount) ||
            empty($this->supplier) ||
            empty($this->currency) ||
            empty($this->need_by_date) ||
            empty($this->contract_no) ||
            empty($this->buyer) ||
            empty($this->pr_no) ||
            empty($this->pr_user) ||
            empty($this->pr_user_dept) ||
            empty($this->created_by)) {
            return false;
        }

        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    poNo	    = :po_no,
                    poDesc	    = :po_desc,
                    poDate	    = :po_date,
                    itemCode	= :item_code,
                    itemDesc	= :item_desc,
                    lineNo	    = :line_no,
                    uom	        = :uom,
                    unitPrice	= :unit_price,
                    poTotal	    = :po_total,
                    poQty	    = :po_qty,
                    poAmount	= :po_amount,
                    supplier	= :supplier,
                    currency	= :currency,
                    needByDate	= :need_by_date,
                    contractNo	= :contract_no,
                    buyer	    = :buyer,
                    prNo	    = :pr_no,
                    prUser	    = :pr_user,
                    prUserDept	= :pr_user_dept,
                    createdBy	= :created_by";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->po_no = htmlspecialchars(strip_tags($this->po_no));
        $this->po_desc = htmlspecialchars(strip_tags($this->po_desc));
        $this->po_date = htmlspecialchars(strip_tags($this->po_date));
        $this->item_code = htmlspecialchars(strip_tags($this->item_code));
        $this->item_desc = htmlspecialchars(strip_tags($this->item_desc));
        $this->line_no = htmlspecialchars(strip_tags($this->line_no));
        $this->uom = htmlspecialchars(strip_tags($this->uom));
        $this->unit_price = htmlspecialchars(strip_tags($this->unit_price));
        $this->po_total = htmlspecialchars(strip_tags($this->po_total));
        $this->po_qty = htmlspecialchars(strip_tags($this->po_qty));
        $this->po_amount = htmlspecialchars(strip_tags($this->po_amount));
        $this->supplier = htmlspecialchars(strip_tags($this->supplier));
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $this->need_by_date = htmlspecialchars(strip_tags($this->need_by_date));
        $this->contract_no = htmlspecialchars(strip_tags($this->contract_no));
        $this->buyer = htmlspecialchars(strip_tags($this->buyer));
        $this->pr_no = htmlspecialchars(strip_tags($this->pr_no));
        $this->pr_user = htmlspecialchars(strip_tags($this->pr_user));
        $this->pr_user_dept = htmlspecialchars(strip_tags($this->pr_user_dept));
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));

        // bind the values
        $stmt->bindParam(':po_no', $this->po_no);
        $stmt->bindParam(':po_desc', $this->po_desc);
        $stmt->bindParam(':po_date', $this->po_date);
        $stmt->bindParam(':item_code', $this->item_code);
        $stmt->bindParam(':item_desc', $this->item_desc);
        $stmt->bindParam(':line_no', $this->line_no);
        $stmt->bindParam(':uom', $this->uom);
        $stmt->bindParam(':unit_price', $this->unit_price);
        $stmt->bindParam(':po_total', $this->po_total);
        $stmt->bindParam(':po_qty', $this->po_qty);
        $stmt->bindParam(':po_amount', $this->po_amount);
        $stmt->bindParam(':supplier', $this->supplier);
        $stmt->bindParam(':currency', $this->currency);
        $stmt->bindParam(':need_by_date', $this->need_by_date);
        $stmt->bindParam(':contract_no', $this->contract_no);
        $stmt->bindParam(':buyer', $this->buyer);
        $stmt->bindParam(':pr_no', $this->pr_no);
        $stmt->bindParam(':pr_user', $this->pr_user);
        $stmt->bindParam(':pr_user_dept', $this->pr_user_dept);
        $stmt->bindParam(':created_by', $this->created_by);

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;
        } else {
            $file = fopen('../error.txt', 'a');
            fwrite($file, json_encode($stmt->errorInfo())."\n");
            fclose($file);
            return 0;
        }

        return false;
    }

}