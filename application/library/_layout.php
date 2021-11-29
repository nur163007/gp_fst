<?php
if ( !session_id() ) {
    session_start();// Starting Session
}
	
	$now = time();
	if(!isset($_SESSION[session_prefix.'wclogin_username']) || $now > $_SESSION[session_prefix.'wclogin_expire']){
		session_destroy();
		if(empty($_REQUEST['q']) || ($_REQUEST['q']=='')){
			header('location:login');
		} else {
			header('location:login?returnto='.$_SERVER['REQUEST_URI']);
		}
	} else {
	    if(isset($_REQUEST['q'])){
            $page = $_REQUEST['q'];
        } else{
            $page = 'dashboard';
        }
        // Checking the user role have sufficient privilege
        // If not then show the access denied page
        if(!in_array($page, $_SESSION[session_prefix . 'wclogin_access'])){
            header('location:access-denied?bf='.$page);
        }
    }
    
	require_once(realpath(dirname(__FILE__) . "/../config.php"));

	function renderLayoutWithContentFile($contentFile, $variables = array())
	{
        $contentFileFullPath = TEMPLATES_PATH . "/" . $contentFile;
	
		// making sure passed in variables are in scope of the template
		// each key in the $variables array will become a variable
		if (count($variables) > 0) {
			foreach ($variables as $key => $value) {
				if (strlen($key) > 0) {
					${$key} = $value;
				}
			}
		}
		
		require_once(LIBRARY_PATH . "/_pageSpecificLinkCss.php");
		
		require_once(TEMPLATES_PATH . "/_header.php");
		
        //global $activeNode;
        $GLOBALS['activeNode'] = $contentFile;
        
        //echo $activeNode;
		require_once(TEMPLATES_PATH . "/_leftnav.php");
		
		$contentFileFullPath .= '.php';

        //echo file_exists($contentFileFullPath);

		if (file_exists($contentFileFullPath)) {

		    require_once($contentFileFullPath);

		} else {
		/*
			If the file isn't found the error can be handled in lots of ways.
			In this case we will just include an error template.
		*/
			require_once(TEMPLATES_PATH . "/error.php");
		}

		require_once(LIBRARY_PATH . "/_pageSpecificLinkJS.php");
		require_once(TEMPLATES_PATH . "/_footer.php");
	
}
?>