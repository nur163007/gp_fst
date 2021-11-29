<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 29-Sep-18
 * Time: 12:06 PM
 */
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
if(isset($_GET['ref'])) {
	$actionLog = GetActionRef($_GET['ref']);
} else {
	$actionLog['ActionID'] = 0;
}

//echo $_SESSION[session_prefix.'wclogin_role'].' #### abc def ghijk';

?>

<?php $title="TAC Request"; ?>
<!-- Page -->
<input type="hidden" id="loggedSupplier" value="<?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){ echo $_SESSION[session_prefix.'wclogin_company']; } else {echo "0";} ?>"/>
<input type="hidden" id="loggedUser" value="<?php echo $_SESSION[session_prefix.'wclogin_userid']; ?>"/>
<input type="hidden" id="userType" value="<?php echo $_SESSION[session_prefix.'wclogin_role']?>"/>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Certificates Download</h1>
        <ol class="breadcrumb">
            <li>PO</li>
            <li class="active">Supplier</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="form-certificate" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">PO: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">CI Number: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="shipNo" >
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="goTAC_btn">
									<i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
				<div id="message" style="font-size: 16px; margin-left: 30px;"></div>
			</div>
        </div>
    </div>
</div>
<!-- End Page -->
