<?php
if ( !session_id() ) {
    session_start();
}
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
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
        case 1:
//			echo getNotification();
			echo "";
            break;
		case 2:
			echo markAsRead($_GET["id"]);
			break;
		default:
			break;
	}
}

function getNotification(){
    
    global $loginRole;
    global $user_id;
    
    $objdal = new dal();
    $where = "";
    if($loginRole == role_Supplier){
        $where = " AND `supplier` = " . $_SESSION[session_prefix.'wclogin_company'];
    }

    $sql = "SELECT `userId`, `user`, `supplier`, `nId`, `po`, `msg`, `target`, `actionOn` FROM
        (
            SELECT * FROM
            (SELECT `id` `userId`, `username` `user` FROM `wc_t_users` WHERE `id` = $user_id) u1 LEFT JOIN
            (SELECT n1.`ID` `nId`, n1.`PO` `po`, n1.`Msg` `msg`, nl1.`readBy`, a1.`TargetForm` `target`, n1.`ActionOn` `actionOn`, po.`supplier`
             	FROM `wc_t_action_log` n1 LEFT JOIN `wc_t_action` a1 ON n1.`ActionID`=a1.`ID` LEFT JOIN `wc_t_notification_log` nl1 ON n1.id=nl1.actionID
                LEFT JOIN `wc_t_po` po ON n1.`PO` = po.`poid`
            	WHERE a1.`ActionPendingTo` = $loginRole) log1
            ON u1.`userId` = log1.`readBy`
            UNION
            SELECT * FROM
            (SELECT `id` `userId`, `username` `user` FROM `wc_t_users` WHERE `id` = $user_id) u1 RIGHT JOIN
            (SELECT n1.`ID` `nId`, n1.`PO` `po`, n1.`Msg` `msg`, nl1.`readBy`, a1.`TargetForm` `target`, n1.`ActionOn` `actionOn`, po.`supplier`
             	FROM `wc_t_action_log` n1 LEFT JOIN `wc_t_action` a1 ON n1.`ActionID`=a1.`ID` LEFT JOIN `wc_t_notification_log` nl1 ON n1.id=nl1.actionID
                LEFT JOIN `wc_t_po` po ON n1.`PO` = po.`poid`
            	WHERE a1.`ActionPendingTo` = $loginRole) log1
            ON u1.`userId` = log1.`readBy`
        ) res1 WHERE `userId` IS null ".$where." 
        GROUP BY `userId`, `user`, `supplier`, `nId`, `po`, `msg`, `target`, `actionOn`
        ORDER by `nId` DESC;";

    $objdal->read($sql);
    $html = '';
    $c = 0;    
    if(!empty($objdal->data)){
        foreach($objdal->data as $val){
            extract($val);
            
            $d = new DateTime($val['actionOn']);
            $n = new DateTime('now');
            $dd = $d->diff($n);
            
            $html .= '<div class="list-group-item" role="menuitem">
                        <div class="media">
                            <div class="media-left padding-right-10">
                                <i class="icon wb-info bg-green-400 white icon-circle" aria-hidden="true"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">'.$val['msg'].'</h6>
                                <time class="media-meta" datetime="'.$val['actionOn'].'">'.$d->format('Y-m-d H:i').' (Age '.$dd->format('%D days %H:%I').')</time><br />
                                <button class="btn btn-round btn-outline btn-xs btn-primary" onclick="markAsRead('.$val['nId'].')"><i class="icon wb-check" aria-hidden="true"></i> Mark as Read</button>&nbsp;
                                <button class="btn btn-round btn-outline btn-xs btn-warning" onclick="openNotification(\''.$val['target'].'?po='.$val['po'].'&ref='.encryptId($val['nId']).'\','.$val['nId'].')"><i class="icon wb-file" aria-hidden="true"></i> Open</button>
                            </div>
                        </div>
                    </div>';
            /*$html .= '<div class="list-group-item" role="menuitem">
                        <div class="media">
                            <div class="media-left padding-right-10">
                                <i class="icon wb-info bg-green-400 white icon-circle" aria-hidden="true"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">'.$val['msg'].'</h6>
                                <time class="media-meta" datetime="'.$val['actionOn'].'">'.$d->format('Y-m-d H:i').' (Age '.$dd->format('%D days %H:%I').')</time><br />
                                <button class="btn btn-round btn-outline btn-xs btn-primary" onclick="markAsRead('.$val['nId'].')"><i class="icon wb-check" aria-hidden="true"></i> Mark as Read</button>
                            </div>
                        </div>
                    </div>';*/
            $c++;
        }
    }
    unset($objdal);
    return json_encode(array($c,$html));
    //return $html;
    
}

function markAsRead($nId){
    
    global $loginRole;
    global $user_id;
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $objdal = new dal();
    $sql = "INSERT INTO `wc_t_notification_log` 
        (`actionID`, `readBy`, `readFrom`) 
            VALUES ($nId, $user_id, '$ip')";
    $objdal->insert($sql);
    unset($objdal);
    return 1;
    
}

?>





