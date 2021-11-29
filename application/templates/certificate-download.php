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

<?php $title="Certificates & Payment History"; ?>
<!-- Page -->
<input type="hidden" id="loggedSupplier" value="<?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){ echo $_SESSION[session_prefix.'wclogin_company']; } else {echo "0";} ?>"/>
<input type="hidden" id="loggedUser" value="<?php echo $_SESSION[session_prefix.'wclogin_userid']; ?>"/>
<input type="hidden" id="userType" value="<?php echo $_SESSION[session_prefix.'wclogin_role']?>"/>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h3 class="page-title">Certificates Download</h3>
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Certificates</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="form-payment-history" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-offset-2 col-sm-2 control-label" for="poNo">PO Number: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poNo" >
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>


                <!--CERTIFICATES & PAYMENT HISTORY-->
                <div class="widget widget-shadow" id="widgetTable">
                    <div class="widget-content widget-radius bg-white">
                        <div class="padding-15 height-xs-200">
                            <p class="clearfix font-size-20 margin-bottom-20">
                                <span class="text-truncate">Certificates & Payment History</span>
                            </p>
                        </div>
                        <table class="table table-bordered table-hover table-striped width-full small no-footer">
                            <thead>
                            <tr>
                                <th class="text-center">Payment Type</th>
                                <th class="text-center">Payment Percent</th>
                                <th class="text-center">TAC</th>
                                <th class="text-center">Certificate</th>
                                <th class="text-center">Paid Amount</th>
                                <th class="text-center">Paid Date</th>
                            </tr>
                            </thead>
                            <tbody id="payHistory">
                            <tr>
                                <!--<td>GMY</td>
                                <td>$ 9,500</td>
                                <td class="green-600">+ 458</td>-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
