<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
require_once(LIBRARY_PATH . "/loginId.php");


if (!empty($_GET["action"]) || isset($_GET["action"]))
{
	switch($_GET["action"])
	{
		case 1:	// get single user info
            if (!empty($_GET["id"])) {
                echo GetUser($_GET["id"]);
            }
			break;
		case 2:	// get all user info
			echo GetAllUsers();
			break;
		case 3:	// delete user
			if(!empty($_GET["id"])) { echo DeleteUser($_GET["id"]); } else { echo 0; };
			break;
		case 4:	// validate new username
			if(!empty($_GET["name"])) { echo ValidNewUser($_GET["name"]); } else { echo 0; };
			break;
		case 5:	// get user list
            if(!empty($_GET["role"])) { 
                if(!empty($_GET["json"])) {
                    echo GetUserList($_GET["role"], $_GET["json"]);
                } else{
                    echo GetUserList($_GET["role"]);
                }
            } else { 
                if(!empty($_GET["json"])) {
                    echo GetUserList(0, $_GET["json"]);
                } else{
                    echo GetUserList(0);
                } 
            };
			break;
        case 6: //Get user profile info
            echo getUserProfile();
            break;
        case 7: //Unlock user
            if (!empty($_GET["lockedId"])) {
                echo unlockUser($_GET["lockedId"]);
            }
            break;
		default:
			break;
	}
}


// Case for Insert and update
if (!empty($_POST)) {

    if (!empty($_POST["userId"]) || isset($_POST["userId"])) {
        if ($_POST["action"] == 1) {
            echo SaveUser();
        } /*elseif ($_POST["action"] == 2) {
            echo SaveProfile();
        } elseif($_POST["action"]==3) {
			echo SavePassword();
		}*/
    }

    //Update User profile
    if (isset($_POST['csrf_token'])) {
        if ($_POST["action"] == 2) {
            echo SaveProfile();
        }
    }

    //Update password
    if (isset($_POST['csrf_token_p'])) {
        if ($_POST["action"] == 3) {
            echo SavePassword();
        }
    }
}

function SaveProfile()
{
    /*echo '<pre>';
        var_dump($_POST);
    echo '</pre>';*/
    global $user_id;
    if ($user_id) {
        $objdal = new dal();

        $firstname = $objdal->sanitizeInput($_POST['firstname']);
        $lastname = $objdal->sanitizeInput($_POST['lastname']);
        $mobile = $objdal->sanitizeInput($_POST['mobile']);
        $email = $objdal->sanitizeInput($_POST['email']);
        $ip = $_SERVER['REMOTE_ADDR'];

        //---return array---------------------------------------------------------------
        $res["status"] = 0;    // 0 = Failed, 1 = Success
        $res["message"] = 'Invalid request, failed to update.';
        //------------------------------------------------------------------------------
        $csrf_token = $_POST['csrf_token'];
        $form_name = 'formProfile';
        $req_type = validateRequest($csrf_token, $form_name);

        if ($req_type == 1) {
            $query = "UPDATE `wc_t_users` SET 
                        `firstname` = '$firstname', 
                        `lastname` = '$lastname',
                        `email` = '$email', 
                        `mobile` = '$mobile' 
                    WHERE `id` = $user_id;";
            $objdal->update($query);

            unset($objdal);

            $res["status"] = 1;
            $res["message"] = 'Saved Successfully!';
            return json_encode($res);
        } else {
            return json_encode($req_type);
        }
    }else{
        return 'Invalid request.';
    }
}

function SavePassword()
{
    /*echo '<pre>';
    	var_dump($_POST);
    echo '</pre>';*/

    global $user_id;

    //---return array---------------------------------------------------------------
    $res["status"] = 0;    // 0 = Failed, 1 = Success
    $res["message"] = 'Invalid request';
    //------------------------------------------------------------------------------

    if ($user_id) {
        $objdal = new dal();
        //$userid = $objdal->sanitizeInput($_POST['userId']);
        $currentPass = $objdal->sanitizeInput($_POST['currentPassword']);
        $password1 = $objdal->sanitizeInput($_POST['newpassword']);
        $password2 = $objdal->sanitizeInput($_POST['confirmnewpassword']);
        $ip = $_SERVER['REMOTE_ADDR'];

        //------------------------------------------------------------------------------
        if ($password1 == $password2) {
            //$newPass = md5($password1);
            $newPass = password_hash($password1, PASSWORD_BCRYPT);
        } else {
            $res["message"] = "Password does not match.";
            return json_encode($res);
        }
        //echo $password1.'<br>'.$password2.'<br>'.$currentPass.'<br>';

        //Encrypt current/old password
        /*if ($currentPass) {
            $currentPass = md5($currentPass);
        }*/
        //------------------------------------------------------------------------------
        $csrf_token = $_POST['csrf_token_p'];
        $form_name = 'formPassword';
        $req_type = validateRequest($csrf_token, $form_name);

        //Get Current password
        $passQuery = $objdal->getRow("SELECT `password`, `loginFailCount` FROM `wc_t_users` WHERE  `id` = $user_id;");
        $currentPassHash = $passQuery['password'];

        $login_fail_count = $passQuery['loginFailCount'];
        $login_fail_max = 3;
        $timestamp = date("Y-m-d H:i:s");
        $attemptRemainingMsg = "Attempts remaining: " . ($login_fail_max - ($login_fail_count + 1));
        $res['attemptRemaining'] = ($login_fail_max - ($login_fail_count + 1));

        if ($req_type == 1) {
            if (password_verify($currentPass, $currentPassHash)) {
                if (!belongsToPassHistory($user_id, $password1)) {
                    $query = "UPDATE `wc_t_users` SET `password` = '$newPass' WHERE `id` = $user_id;";
                    $objdal->update($query);

                    //Insert data in password history table
                    $insSQL = "INSERT INTO `wc_t_pass_history` SET `userId` = $user_id, `passwordHash` = '$newPass', `createdFrom` = '$ip'; ";
                    $objdal->insert($insSQL);

                    //Update fail login count
                    $sql = "UPDATE `wc_t_users` SET `loginFailCount` = 0 WHERE `id` = $user_id; ";
                    //echo $sql;
                    $objdal->update($sql);

                    unset($objdal);

                    $res["status"] = 1;
                    $res["message"] = 'Password has been updated SUCCESSFULLY!';
                    return json_encode($res);
                    //	return $query;
                } else {
                    $res["message"] = 'Your new Password should not be same as any of the previous 3 Passwords.';
                    return json_encode($res);
                }
            } else {
                // Not Successful. Increment failed login count
                $will_be_locked = ($login_fail_count == $login_fail_max - 1);
                if ($will_be_locked) {
                    $sql = "UPDATE `wc_t_users` 
                                    SET `loginFailCount` = `loginFailCount` + 1, `isLocked` = 1, `lockStartTime` = '$timestamp' 
                                WHERE `id` = $user_id;";
                } else {
                    $sql = "UPDATE `wc_t_users` SET `loginFailCount` = `loginFailCount` + 1 WHERE `id` = $user_id;";
                }
                $objdal->update($sql);

                $res["message"] = "Current Password is not correct" . "<br>" . $attemptRemainingMsg;
                return json_encode($res);
            }
        } else {
            return json_encode($req_type);
        }
    } else {
        return json_encode($res); //If !$user_id
    }

}

/*!
 * Insert or Update data
 * */
function SaveUser()
{
    global $user_id;
    global $loginRole;

    if ($loginRole == 1) {
        $objdal = new dal();
        $userid = $objdal->sanitizeInput($_POST['userId']);
        if (isset($_POST['username'])) {
            $username = $objdal->sanitizeInput($_POST['username']);
        } else {
            $username = '';
        }

        $password = $objdal->sanitizeInput($_POST['password']);
        $firstname = $objdal->sanitizeInput($_POST['firstname']);
        $lastname = $objdal->sanitizeInput($_POST['lastname']);
        if ($_POST['company'] != "") {
            $company = $objdal->sanitizeInput($_POST['company']);
        } else {
            $company = 'NULL';
        }
        $department = $objdal->sanitizeInput($_POST['department']);
        $mobile = $objdal->sanitizeInput($_POST['mobile']);
        $email = $objdal->sanitizeInput($_POST['email']);
        $userrole = $objdal->sanitizeInput($_POST['userrole']);
        $activeUser = (!isset($_POST['activeUser'])) ? 0 : 1;
        $ismanager = (!isset($_POST['ismanager'])) ? 0 : 1;

        $ip = $_SERVER['REMOTE_ADDR'];

        if ($password != '') {
            //$password = md5($password);
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
        //------------------------------------------------------------------------------

        //---return array---------------------------------------------------------------
        $res["status"] = 0;    // 0 = Failed, 1 = Success
        $res["message"] = 'FAILED!';
        //------------------------------------------------------------------------------

        $csrf_token = $_POST['csrf_token'];
        $form_name = 'form-users';
        $req_type = validateRequest($csrf_token, $form_name);

        if ($req_type == 1) {
            if ($userid == 0) {
                $taskMessage = 'Insert new data.';
                $query = "INSERT INTO `wc_t_users` SET 
                        `username` = '" . (replaceUIdRegex($username)) . "',
                        `firstname` = '$firstname', 
                        `lastname` = '$lastname', 
                        `password` = '$password', 
                        `role` = $userrole, 
                        `company` = $company, 
                        `email` = '$email', 
                        `mobile` = '$mobile', 
                        `department` = '$department', 
                        `active` = b'$activeUser', 
                        `manager` = b'$ismanager', 
                        `createdby` = '$user_id', 
                        `createdfrom` = '$ip';";
                $objdal->insert($query);
            } else {
                $taskMessage = 'Update old data.';
                // leaving password blank during update will keep password unchanged
                $pass = (!$password) ? '' : $pass = "`password` = '$password', ";

                $query = "UPDATE `wc_t_users` SET 
                        `firstname` = '$firstname', 
                        `lastname` = '$lastname', $pass
                        `role` = $userrole, 
                        `company` = $company, 
                        `email` = '$email', 
                        `mobile` = '$mobile', 
                        `department` = '$department', 
                        `active` = b'$activeUser', 
                        `manager` = b'$ismanager'
                        WHERE `id` = $userid;";
                $objdal->update($query);
            }
            //var_dump(debug_backtrace());
            //Add info to activity log table
            addActivityLog(requestUri, $taskMessage, $user_id, 1);

            unset($objdal);

            $res["status"] = 1;
            $res["message"] = 'Saved successfully.';
            return json_encode($res);
        } else {
            return json_encode($req_type);
        }
    } else {
        return 'Invalid request';
    }
}

// Validat new username
function ValidNewUser($username)
{
	$objdal = new dal();
	$query = "SELECT COUNT(*) res FROM `wc_t_users` WHERE `username` = '".$username."';";
	$objdal->read($query); 
	$res = $objdal->data[0]['res'];
	unset($objdal);
	return $res;
}

//Delete
function DeleteUser($id)
{
    global $loginRole;
    global $user_id;
    if ($loginRole == 1) {
        $objdal = new dal();
        $query = "DELETE FROM `wc_t_users` WHERE `id` = $id;";
        $objdal->delete($query);
        addActivityLog(requestUri, 'User deleted', $user_id, 1);
        unset($objdal);
        return 1;
    } else {
        return "Invalid request";
    }
}

/*!
 * Get single user info
 * This function is only accessible to admin
 * Used in module: User(modal)
 * Modified by: Hasan Masud
 * ******************************************/
function GetUser($id)
{
    global $loginRole;
    if ($loginRole == 1) {
        $objdal = new dal();
        $query = "SELECT 
			        `id`, `username`, `firstname`, `lastname`, `image`, `role`, `company`, `department`, `email`, `mobile`, 
			        `active`, `manager` 
                 FROM 
                    `wc_t_users` 
                 WHERE 
                    `id` = $id;";

        $objdal->read($query);
        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }
        unset($objdal);

        return json_encode($res);
    } else {
        return 'Invalid request';
    }

}

/*!
 * Get single user profile
 * this function is accessible to mass user
 * Used in module: Profile
 * Added by: Hasan Masud
 * ******************************************/
function getUserProfile()
{
    global $user_id;
    if ($user_id) {
        $objdal = new dal();
        $query = "SELECT 
			        `firstname`, `lastname`, `image`, `email`, `mobile`
                FROM 
                    `wc_t_users` 
                WHERE 
                    `id` = $user_id;";

        $objdal->read($query);
        $res = '';
        if (!empty($objdal->data)) {
            $res = $objdal->data[0];
            extract($res);
        }
        unset($objdal);

        return json_encode($res);
    } else {
        return 'Invalid request';
    }

}

/*!
 * Get all users list
 * This function is only accessible to admin
 * *****************************************/
function GetAllUsers()
{
    global $loginRole;
    if ($loginRole == role_Admin) {
        $objdal = new dal();
        $strQuery = "SELECT 
                        u.`id`, u.`username`, CONCAT(u.`firstname`,' ', u.`lastname`) AS `fullName`, u.`role`, r.`name` AS `roleName`, 
				        c1.`name` AS `company`, u.`department`, u.`email`, u.`mobile`, u.`active`, u.`isLocked`
			        FROM `wc_t_users` u 
			            INNER JOIN `wc_t_roles` r ON r.`id` = u.`role`
                        LEFT JOIN `wc_t_company` c1 ON u.`company` = c1.`id`
                    ORDER BY u.`id` ASC;";
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
    } else {
        $json = "[]";
        $table_data = '{"data": ' . $json . '}';
        //return $table_data;
        return $table_data;
    }
}

function GetUserList($role, $json='yes'){
    $objdal = new dal();
    if($role==0){
        $sql = "SELECT `id`, `username` FROM `wc_t_users` WHERE `active` = 1 ORDER BY `username`;";
    } else {
        $sql = "SELECT `id`, `username` FROM `wc_t_users` WHERE `active` = 1 AND `role` = $role ORDER BY `username`;";
    }
	
	$objdal->read($sql); 
	if($json=='yes'){
        // json
    	$jsondata = '[';
        $jsondata .= '{"id": "", "text": ""}';
    	if(!empty($objdal->data)){
    		foreach($objdal->data as $val){
    			extract($val);
                $jsondata .= ', {"id": "'.$id.'", "text": "'.$username.'"}';		
    		}
    	}
        $jsondata .= ']';
    } else{
        if(!empty($objdal->data)){
            $i = 0;
    		foreach($objdal->data as $val){
        		$jsondata[$i] = $val;
                $i++;
            }
            $jsondata = json_encode($jsondata);
    	}
    }
	unset($objdal);
	return $jsondata;
}

/*!
 * Unlock user,
 * This function has only access to Admin
 * @param - $lockedId(integer)
 * Added by: Hasan Masud
 * */
function unlockUser($lockedId)
{
    global $loginRole;
    global $user_id;

    $res["status"] = 0;
    if ($loginRole == 1) {
        $objdal = new dal();
        $sql = "UPDATE `wc_t_users` SET `isLocked` = 0, `loginFailCount` = 0, `lockStartTime` = NULL  WHERE `id` = $lockedId;";
        $objdal->update($sql);
        addActivityLog(requestUri, 'Unlocked user', $user_id, 1);
        $res["status"] = 1;
        $res["message"] = "Unlocked user successfully.";
        unset($objdal);
        return json_encode($res);
    } else {
        $res["message"] = "Invalid request.";
        return json_encode($res);
    }
}
?>

