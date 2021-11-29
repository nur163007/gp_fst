<?php
class Privilege
{
	public function __construct(){
		// initialization
	}
	public function __destruct(){
		// dispose
	}
	public function GetPrivilegeByRole($roleid){
		
		$objdal = new dal();
		$sql = "SELECT n.`id`, n.`name`, n.`url`, n.`mask`, n.`category`, n.`parent` 
			FROM `wc_t_navs` n INNER JOIN `wc_t_privilege` p ON p.`navid` = n.`id`
			WHERE n.`display`= b'1' AND p.`role` = $roleid;";
		$objdal->read($sql);
		$rows = $objdal->data;
		
		unset($objdal);
		
		return $rows;
	}
}
?>