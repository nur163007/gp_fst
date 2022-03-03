<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
//echo decryptId($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Warehouse Input</h1>
        <ol class="breadcrumb">
            <li><a>PO: </a></li>
            <li class="active"><?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>, Ship #<?php echo $actionLog['shipNo'];?></li>
        </ol>
		<div class="page-header-actions">
			&nbsp;
		</div>        
    </div>
    <div class="page-content container-fluid">
    
        <div class="panel">
        
            <div class="panel-body container-fluid">

                <div class="nav-tabs-horizontal">
                    <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                        <li role="presentation"><a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab"><span class="text-primary">PO Detail</span></a></li>
                        <li role="presentation"><a data-toggle="tab" href="#tabUserFeedback" aria-controls="tabUserFeedback" role="tab"><span class="text-primary">Attachments &amp; Comments</span></a></li>
                        <li role="presentation" <?php if($actionLog['ActionID']==action_Requested_for_Warehouse_Inputs){?>class="active"<?php }?>><a data-toggle="tab" href="#tabShipment" aria-controls="tabLCRequest" role="tab"><span class="text-primary">Shipment Info.</span></a></li>
                        <li role="presentation" <?php if($actionLog['ActionID']==action_Warehouse_Input_Updated_Pending_Avg_Cost){?>class="active"<?php }?>><a data-toggle="tab" href="#tabAverageCostData" aria-controls="tabAverageCostData" role="tab"><span class="text-primary">Average Cost. Update</span></a></li>
                    </ul>

                    <div class="tab-content padding-top-20">

                        <div class="tab-pane" id="tabPOInfo" role="tabpanel">
                            <div class="form-horizontal">
                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title">Order Information</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">PO No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PO Value:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="povalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">LC Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Supplier:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title">PI Information</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">PI No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pinum"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PI Value:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pivalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Shipment Mode:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="shipmode"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">HS Code:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PI Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pidate"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Insurance / Base Value:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="basevalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Country of Origin:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="origin"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Negotiating Bank:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="negobank"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Port of Shipment:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="shipport"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tabUserFeedback" role="tabpanel">

                            <div class="form-horizontal">
                                <div class="row row-lg">
                                    <div class="col-xlg-6 col-md-6">
                                        <div id="usersAttachments">
                                        </div>
                                    </div>
                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title" id="buyersmsgtitle">Comment History</h4>
                                        <div class="form-group">
                                            <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" id="buyersmsg">
                                            </div>
                                        </div>

                                        <!--h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Comments</h4>
                                        <div class="form-group">
                                            <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" id="suppliersmsg">
                                            </div>
                                        </div-->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane <?php if($actionLog['ActionID']==action_Requested_for_Warehouse_Inputs){?>active<?php }?>" id="tabShipment" role="tabpanel">
                            <form class="form-horizontal" id="warehouse-form" name="po-form" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="shipno" id="shipno" type="hidden" value="<?php echo $actionLog['shipNo']; ?>" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="postatus" id="postatus" type="hidden" value="" />
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <div id="PO_submit_error" style="display:none;"></div>

                                <div class="row row-lg" id="shipmentInfo">

                                    <div class="col-md-12">
                                        <h4 class="well well-sm example-title">Supplier's Shipment Inputs</h4>
                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Shipment Schedule (ETA): </label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                                    <input type="text" class="form-control" name="scheduleETA" id="scheduleETA" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">MAWB Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="mawbNo" id="mawbNo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">HAWB Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="hawbNo" id="hawbNo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">BL Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="blNo" id="blNo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">AWB / BL Date: </label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                                    <input type="text" class="form-control" name="awbOrBlDate" id="awbOrBlDate" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="ciNo" id="ciNo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Date: </label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                                    <input type="text" class="form-control" name="ciDate" id="ciDate" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Amount: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="ciAmount" id="ciAmount" value="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Invoice Quantity: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="invoiceQty" id="invoiceQty" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">No. of Container: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="noOfcontainer" id="noOfcontainer" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Total number of Boxes: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="noOfBoxes" id="noOfBoxes" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Gross Chargeable Weight: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="ChargeableWeight" id="ChargeableWeight" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">DHL Tracking Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="dhlNum" id="dhlNum" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Endorsment Doc. Shared by Finance: </label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                                    <input type="text" class="form-control" name="docDeliveredByFin" id="docDeliveredByFin" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">IPC No: </label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="ipcNo" id="ipcNo" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary" id="ipcNo_btn"><i class="icon wb-check" aria-hidden="true"></i> Update</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">GIT Receiving Date: </label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="gitReceiveDate" id="gitReceiveDate" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary" id="gitReceiveDate_btn"><i class="icon wb-check" aria-hidden="true"></i> Update</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="form-group" id="ActualArrivalDateWarehouse">
                                            <label class="col-sm-6 control-label">Date of Actual Arrival at Warehouse: </label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="whArrivalDate" id="whArrivalDate" />
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-primary" id="whArrivalDate_btn"><i class="icon wb-check" aria-hidden="true"></i> Update</button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />
                                        <div class="form-group">
                                            <div class="col-sm-12 text-right">
                                                <button type="button" class="btn btn-primary" id="testMail"><i class="icon wb-bell" aria-hidden="true"></i> testMail</button>
                                                <button type="button" class="btn btn-primary" id="btn_NotifyToBuyer"><i class="icon wb-bell" aria-hidden="true"></i> Notify to Buyer</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="rejectMessage" id="rejectMessage" rows="3" placeholder="Rejection cause to buyer..."></textarea>
                                            </div>
                                            <div class="col-sm-12 text-right margin-top-20">
                                                <button type="button" class="btn btn-danger" id="reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject </button>
                                                <!--button type="button" class="btn btn-success" id="accept_btn"><i class="icon wb-check" aria-hidden="true"></i> Accept </button-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane <?php if($actionLog['ActionID']==action_Warehouse_Input_Updated_Pending_Avg_Cost){?>active<?php }?>" id="tabAverageCostData" role="tabpanel">

                            <form class="form-horizontal" id="avgcost-form" name="po-form" method="post" autocomplete="off">
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Average cost update data
<!--                                            <input class="hidden" type="text" id="importCSVFile" name="importCSVFile" value="" />-->
<!--                                            <button class="btn btn-sm btn-inverse btn-round pull-right" style="margin-top: -8px;" id="importCSVFile_btn">-->
<!--                                                <i class="icon wb-download" aria-hidden="true"></i>-->
<!--                                                <span class="hidden-xs">Import from CSV</span>-->
<!--                                            </button>-->
                                        </h4>
                                        <!-- Table-->
                                        <div>
                                            <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtAverageCostdate">
                                                <thead>
                                                <tr>
                                                    <th>PO</th>
                                                    <th>IPCNo</th>
                                                    <th>ShipNo</th>
                                                    <th>POLine</th>
                                                    <th>Item</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>UOM</th>
                                                    <th>UnitPrice</th>
                                                    <th>Amount</th>
                                                    <th>Currency</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="9"><span class="pull-right">Total:</span></th>
                                                    <td style="font-weight: bold;" id="dtTotalAmount"></td>
                                                    <td></td>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- End Table-->

                                    </div>
                                </div>
                            </form>
                            <br /><br />
                            <div class="form-horizontal">
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12 text-right">
                                        <button type="button" class="btn btn-primary" id="btn_NotifyToFinance"><i class="icon wb-bell" aria-hidden="true"></i> Notify Average Cost Data Update to Finance </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <hr />
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12 text-right">
                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
