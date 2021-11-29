<?php
if ( !session_id() ) {
    session_start();
}
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

if(isset($_POST['inputUserName']) && isset($_POST['inputPassword'])) {

    $objdal = new dal();

    $usr = $objdal->sanitizeInput(replaceUIdRegex($_POST['inputUserName']));
    $pass = $objdal->sanitizeInput($_POST['inputPassword']);
    $inputPass = $objdal->sanitizeInput($_POST['inputPassword']);

    $pass = md5($pass);
    try {
        $res['success'] = 0;
        $res['msg'] = '';

        $sqlOne = "SELECT `username`, `password`, `isLocked`, `lockStartTime`, `loginFailCount` FROM `wc_t_users` WHERE `username` = '$usr';";
        //echo $sqlOne;
        $rowOne = $objdal->read($sqlOne);

        $username_exists = false;
        $lockout_minutes = 15;
        $login_fail_max = 3;
        $login_fail_count = 0;
        $timestamp = date("Y-m-d H:i:s");
        $module = requestUri . '; User-Agent: ' . userAgent;

        /*!
         * Step 1: If user exists for given username($usr), then
         *         1.1: Check if user is locked, then
         *         1.2: Check is there any value in `lockStartTime` field, then
         *         1.3: Calculate lockStartTime and current time, if $lockout_minutes have passed
         *              update the columns and give user access.
         *         1.4: If $lockout_minutes have not passed, Return with error message
         * Step 2: If user does not exists for given username($usr), then
         *         2.1: Return with error message
         * Step 3: If $username_exists && password matches with db pass in password_verify/md5, then
         *         3.1: Select & fetch required fields
         *         3.2: Update password in new password_hash method
         * Step 4: If $username_exists && password doesn't match with db pass in password_verify/md5, then
         *         4.1: Increase `loginFailCount` by 1 and if count reaches to 3 set `isLocked` to 1,
         *              set current time to `lockStartTime` then return with error message.
         * */

        if (sizeof($rowOne) == 1) {
            $val = $rowOne[0];
            extract($val);

            $to_time = strtotime($timestamp);
            $lock_start_timestamp = strtotime($val['lockStartTime']);// Lock start time
            //$lock_start_timestamp = $val['lockStartTime'];
            $time_lapsed = round(abs($to_time - $lock_start_timestamp) / 60);
            $unlock_time = $lockout_minutes - $time_lapsed;
            $lockedMessage = "<pre>Account is locked. Please wait for " . $unlock_time . " minutes.</pre>";
            $userName = $val['username'];
            //echo $val['password'];
            $dbPassHash = password_verify($inputPass, $val['password']);
            //var_dump($dbPassHash);
            $username_exists = true;
            if ($val['isLocked'] == 1) {
                //$lock_start_timestamp = $val['lockStartTime'];
                if ($lock_start_timestamp != NULL) {
                    $dif = ($to_time - $lock_start_timestamp);
                    if ($dif > $lockout_minutes * 60) {
                        $login_fail_count = 0;
                        $sql = "UPDATE `wc_t_users` SET `isLocked` = 0, `loginFailCount` = 0, `lockStartTime` = NULL 
                                WHERE `username` = '$userName';";
                        //echo $sql;
                        $objdal->update($sql);
                    } else {
                        $res['msg'] = $lockedMessage;
                        unset($objdal);
                        echo json_encode($res);
                        return;
                    }
                }
            } else {
                $login_fail_count = $val['loginFailCount'];
            }
        } else {
            //Add info to activity log table
            addActivityLog($module, 'Login failed', $usr, 0);
            $res['msg'] = "<pre>Incorrect username or password.</pre>";
            echo json_encode($res);
            return;
        }

        unset($objdal);

        $objdal = new dal();
        if ($username_exists && ($dbPassHash || $pass == $val['password'])) {
            $strQuery = "SELECT 
                    u.`id`,u.`username`,u.`firstname`,u.`lastname`,u.`image`,u.`password`,u.`role`,r.`name` AS `rolename`, 
                    u.`company`, u.`isLocked`, u.`lockStartTime`, u.`loginFailCount`, c1.`name` AS `companyname`,u.`email`,u.`mobile` 
		          FROM `wc_t_users` u 
		            INNER JOIN `wc_t_roles` r ON r.`id` = u.`role` 
                    LEFT JOIN `wc_t_category` c1 ON u.`company` = c1.`id` 
                  WHERE u.`username` = '$usr' AND u.`active`=1;";
            //echo $strQuery;
            $rows = $objdal->read($strQuery);

            /*!
             * Update md5 hash with 'password_hash' method
             * Added by: Hasan Masud
             ********************************/
            if ($pass == $val['password']) {
                $newPass = password_hash($inputPass, PASSWORD_BCRYPT);
                $passUpdate = "UPDATE `wc_t_users` SET `password` = '$newPass' WHERE `username` = '$usr';";
                $objdal->update($passUpdate);
            }

            if (sizeof($rows) == 1) {
                $val = $rows[0];
                extract($val);
                if ($val["isLocked"] == 0) {
                    $sql = "UPDATE `wc_t_users` SET `loginFailCount` = 0 WHERE `username` = '$usr'; ";
                    //echo $sql;
                    $objdal->update($sql);
                    session_destroy();
                    if (!session_id()) {
                        session_start();
                    }

                    $_SESSION[session_prefix . 'wclogin_userid'] = $val['id'];
                    $_SESSION[session_prefix . 'wclogin_username'] = $val['username'];
                    $_SESSION[session_prefix . 'wclogin_fullname'] = trim($val['firstname'] . ' ' . $val['lastname']);
                    $_SESSION[session_prefix . 'wclogin_email'] = $val['email'];
                    $_SESSION[session_prefix . 'wclogin_mobile'] = $val['mobile'];
                    $_SESSION[session_prefix . 'wclogin_role'] = $val['role'];
                    $_SESSION[session_prefix . 'wclogin_proPic'] = $val['image'];
                    $_SESSION[session_prefix . 'wclogin_rolename'] = $val['rolename'];
                    $_SESSION[session_prefix . 'wclogin_company'] = $val['company'];
                    $_SESSION[session_prefix . 'wclogin_companyname'] = $val['companyname'];

                    $query = "SELECT n.`url` FROM `wc_t_privilege` AS p 
                                LEFT JOIN `wc_t_navs` AS n ON p.`navid` = n.`id` WHERE p.`role` = " . $val['role'] . ";";

                    unset($objdal->data);

                    $objdal->read($query);
                    $rights = array('access-denied', 'error');
                    if (!empty($objdal->data)) {
                        foreach ($objdal->data as $val) {
                            extract($val);
                            array_push($rights, $url);
                        }
                    }
                    $_SESSION[session_prefix . 'wclogin_access'] = $rights;
                    $_SESSION[session_prefix . 'wclogin_start'] = time();
                    //$_SESSION[session_prefix . 'wclogin_expire'] = $_SESSION[session_prefix . 'wclogin_start'] + (10 * 60 * 60);    //10 Hr * 60min * 60 sec = 36,000 sec
                    $_SESSION[session_prefix . 'wclogin_expire'] = $_SESSION[session_prefix . 'wclogin_start'] + 3600;    //1 hour in seconds
                    //Password strength checker
                    $_SESSION[session_prefix . 'wclogin_isPassResetRequired'] = false;
                    $_SESSION[session_prefix . 'wclogin_isPassResetRequired'] = !isPassStrong($_POST['inputPassword']);
                    $res['success'] = 1;
                    //var_dump(debug_backtrace());
                    //Add info to activity log table
                    addActivityLog($module, 'Login', $_SESSION[session_prefix . 'wclogin_userid'],  1);
                } else {
                    // Account is locked. Increment failed login count
                    $sql = "UPDATE `wc_t_users` SET `loginFailCount` = `loginFailCount` + 1 WHERE `username` = '$usr';";
                    //echo $sql;
                    $objdal->update($sql);
                    $res['msg'] = $lockedMessage;
                    unset($objdal);
                    echo json_encode($res);
                    return;
                }


            }
            /*unset($objdal);
            echo json_encode($res);*/
        } else {
            //Add info to activity log table
            addActivityLog($module, 'Login failed', $usr, 0);
            // Not Successful. Increment failed login count
            $will_be_locked = ($login_fail_count == $login_fail_max - 1);
            //$timestamp = date("Y-m-d H:i:s");
            if ($will_be_locked) {
                $sql = "UPDATE `wc_t_users` 
                    SET `loginFailCount` = `loginFailCount` + 1, `isLocked` = 1, `lockStartTime` = '$timestamp'
                    WHERE `username` = '$userName';";
            } else {
                $sql = "UPDATE `wc_t_users` SET `loginFailCount` = `loginFailCount` + 1 WHERE `username` = '$userName';";
            }
            $objdal->update($sql);

            if ($will_be_locked) {
                $res['msg'] = '<pre>Account is locked</pre>';
                echo json_encode($res);
                return;
            } else {
                $attempts_remaining = ($login_fail_max - ($login_fail_count + 1));
                if ($attempts_remaining > 0) {
                    $res['msg'] = "<pre>Incorrect username or password.</pre>";
                    if ($attempts_remaining <= 3) {
                        $res['msg'] = "<pre>Incorrect username or password.<br> Attempts remaining: " . ($login_fail_max - ($login_fail_count + 1)) . "</pre>";
                    }
                }
            }
        }
        //$res['msg'] = "<pre>Incorrect username or password.</pre>";
        //echo json_encode($res);
        unset($objdal);
        echo json_encode($res);

    } catch (Exception $e) {
        //echo $e->getMessage();
        $res['msg'] = $e->getMessage();
        unset($objdal);
        echo json_encode($res);
        return;
    }
}
?>