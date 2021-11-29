<?php
/*
    Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
//require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
// require_once(CLASS_PATH . "\class.security.php");
$GLOBALS['wclogin_rolename'] = $_SESSION[session_prefix.'wclogin_rolename'];

function GetNavGroup($roleid) {

    global $wclogin_rolename;
    global $activeNode;
	$nav = '';
	$nav = '<ul class="site-menu">';
	$nav .= '<li class="site-menu-category">'.$wclogin_rolename.'</li>';
	
	GetChildNodes('n.`parent` IS NULL', $roleid, $nav, '', $activeNode);
	
	$nav .= '</ul>';
	
	return $nav;
}

function GetChildNodes($parent, $roleid, &$nav, $slug = '')
{
    global $activeNode;
	$objdal = new dal();
	$sql = "SELECT n.`id`, n.`name`, n.`url`, n.`mask`, n.`category`, n.`parent`,
        (SELECT COUNT(n1.`id`) FROM `wc_t_navs` n1 WHERE n1.`parent`= n.`id` AND n1.`url` = '$activeNode') activeMenu 
		FROM `wc_t_navs` n INNER JOIN `wc_t_privilege` p ON p.`navid` = n.`id`
		WHERE n.`display`= b'1' AND p.`role` = $roleid AND $parent
        ORDER BY n.`id`;";
	$objdal->read($sql);
	$rows = $objdal->data;
	
	if(!empty($rows)){
		foreach($rows as $row)
		{
			extract($row);
            
			//$nav .= '<li>'.$row['name'].'</li>';
            if($activeMenu==1){
		         $activeMenuParent = ' open active';
		     } else{
		         $activeMenuParent = '';
		     }
			if($row['url']==''){
				$nav .= '<li class="site-menu-item has-sub'.$activeMenuParent.'">
                    <a href="javascript:void(0)" data-slug="'.$row['name'].'">
                        <i class="site-menu-icon wb-menu" aria-hidden="true"></i>
                            <span class="site-menu-title">'.$row['name'].'</span>
                                <span class="site-menu-arrow"></span></a>';
				$nav .= '<ul class="site-menu-sub">';
				GetChildNodes('n.`parent`='.$row['id'], $roleid, $nav, $row['name']);
				$nav .= '</ul>';
			} else{
			     if(strtolower($activeNode)==strtolower($row['url'])){
			         $activeItem = ' active';
			     } else{
			         $activeItem = '';
			     }
//                    
				if ($slug != '')
                {
                    $nSlug = 'data-slug="'.$slug.'-'.$row['name'].'"';
                }
                else
                {
                    $nSlug = '';
                }
				$nav .= '<li class="site-menu-item'.$activeItem.'">
                    <a class="animsition-link" href="'.$row['url'].'" '.$nSlug.'>
                        <i class="site-menu-icon wb-play" aria-hidden="true"></i>
                            <span class="site-menu-title">'.$row['name'].'</span>
                                </a></li>';
			}
		}
	} else{
		$nav .= '<li>No Data</li>';		
	}
	
	unset($objdal);
}
/* 
function LoadChildTree(DataTable dt, int iParentID, ref StringBuilder sbMenu, string slug = "")
    {
        string sFilter = "[ParentId] = " + iParentID.ToString();
        string sSort = "[ParentId]";
        DataRow[] drs = dt.Select(sFilter, sSort, DataViewRowState.CurrentRows);
        string nSlug = "";
        foreach (DataRow dr in drs)
        {
            if (dr["ActionUrl"].ToString() != "")
            {
                if (slug != "")
                {
                    nSlug = "data-slug=\"" + slug + "-" + dr["MenuName"].ToString().Replace(" ", "") + "\"";
                }
                else
                {
                    nSlug = "";
                }
                sbMenu.Append("<li class=\"site-menu-item\">" +
                    "<a class=\"animsition-link\" href=\"/" + dr["ActionUrl"].ToString() + "\" " + nSlug + ">" +
                        "<i class=\"site-menu-icon wb-dashboard\" aria-hidden=\"true\"></i>" +
                            "<span class=\"site-menu-title\">" + dr["MenuName"].ToString() + "</span>" +
                                "</a></li>");
            }
            else
            {
                sbMenu.Append("<li class=\"site-menu-item has-sub\">" +
                    "<a href=\"javascript:void(0)\" data-slug=\"" + dr["MenuName"].ToString() + "\">" +
                        "<i class=\"site-menu-icon wb-order\" aria-hidden=\"true\"></i>" +
                            "<span class=\"site-menu-title\">" + dr["MenuName"].ToString() + "</span>" +
                                "<span class=\"site-menu-arrow\"></span></a>");
                sbMenu.Append("<ul class=\"site-menu-sub\">");
                LoadChildTree(dt, (int)dr["MenuId"], ref sbMenu, dr["MenuName"].ToString());
                sbMenu.Append("</ul>");
            }
        }
        dt.Dispose();
    } */

?>