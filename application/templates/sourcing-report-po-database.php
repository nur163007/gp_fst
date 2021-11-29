<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 3/19/2017
 * Time: 5:00 AM
 */
?>

<div class="page bg-blue-100 animsition">
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <nav class="navbar navbar-mega well well-sm example-title" style="border: 1px solid #e4eaec;">
                        <div class="container-fluid">
                            <div class="navbar-header" style="padding-left: 0;">
                                <button type="button" class="navbar-toggle hamburger hamburger-close collapsed" data-toggle="collapse" data-target="#navbar-collapse-2">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="hamburger-bar"></span>
                                </button>
                                <h5 style="margin: 0; margin-top: 3px;"><i class="fa fa-file-text-o"></i> &nbsp;&nbsp;Buyer wise PO Report</h5>
                            </div>
                            <div class="navbar-collapse collapse" id="navbar-collapse-2">
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown dropdown-fw dropdown-mega">
                                        <a style="padding-top: 0; padding-bottom: 0; color: #37474f;" class="dropdown-toggle" data-toggle="dropdown" href="#">
                                            <i class="fa fa-filter"></i> &nbsp;&nbsp;Filter <b class="caret"></b></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <div class="mega-content">
                                                    <div class="row row-lg">
                                                        <div class="col-xlg-12 col-md-12">
                                                            <div class="form-group small">
                                                                <label class="col-sm-1 control-label">Buyer:</label>
                                                                <div class="col-sm-4">
                                                                    <select class="form-control" data-plugin="select2" name="buyerList" id="buyerList" >
                                                                    </select>
                                                                </div>
                                                                <label class="col-sm-2 control-label">Supplier:</label>
                                                                <div class="col-sm-5">
                                                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                                                    </select>
                                                                </div>
                                                                <label class="col-sm-1 control-label hidden">Date: </label>
                                                                <div class="col-sm-4 hidden">
                                                                    <div class="input-daterange" data-plugin="datepicker">
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">
                                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="dtpStart" id="dtpStart" />
                                                                        </div>
                                                                        <div class="input-group">
                                                                            <span class="input-group-addon">to</span>
                                                                            <input type="text" class="form-control" name="dtpEnd" id="dtpEnd" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xlg-12 col-md-12">
                                                            <div class="form-group small">
                                                                <label class="col-sm-1 control-label">Status:</label>
                                                                <div class="col-sm-4">
                                                                    <select class="form-control" data-plugin="select2" name="currentStatus" id="currentStatus" >
                                                                        <option></option>
                                                                        <option value="<?php echo action_Draft_PI_Submitted ?>">Draft PI Received</option>
                                                                        <option value="<?php echo action_Draft_PI_Accepted_by_EA ?>">Draft PI Approved by EA</option>
                                                                        <option value="<?php echo action_Final_PI_Submitted ?>">Final PI Received</option>
                                                                        <option value="<?php echo action_Final_PI_Accepted_by_EA ?>">Final PI Approved by EA</option>
                                                                        <option value="<?php echo action_BTRC_Process_Approved_by_3rd_Level ?>">Send to BTRC NOC by EA</option>
                                                                        <option value="<?php echo action_Sent_for_BTRC_Permission ?>">NOC Stage</option>
                                                                        <option value="<?php echo action_Accepted_by_BTRC ?>">LC not yet Raised</option>
                                                                        <option value="<?php echo action_LC_Request_Sent ?>">LC in Approval process</option>
                                                                        <option value="<?php echo action_Approved_by_3rd_Level ?>">LC Pending at LC ops</option>
                                                                        <option value="<?php echo action_LC_Accepted ?>">Shipment Stage</option>
                                                                        <option value="<?php echo action_Original_Document_Accepted_For_Document_Delivery ?>">Payment Stage</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-7 text-right">
                                                                    <button type="button" class="btn btn-primary" id="applyFilter">Apply</button>
                                                                    <button type="button" class="btn btn-default" id="clearFilter">Clear</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <table class="table table-bordered dataTable table-striped width-full small" id="displayTable">

                            </table>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
