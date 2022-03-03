<?php
$title="Buyers Pre Alert";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
$isShipDocAccepted = checkStepOver($_GET['po'], action_Ship_Doc_Accepted_Buyer_Pending_WH, $_GET['ship']);
//echo 'testres'.$isShipDocAccepted;
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Buyer's Pre Alert</h1>
        <ol class="breadcrumb">
            <li class="active">PO : <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li class="active">Shipment: <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
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
                        <li role="presentation"><a data-toggle="tab" href="#tabLCInfo" aria-controls="tabLCInfo" role="tab"><span class="text-primary">LC Info.</span></a></li>
                        <li role="presentation" <?php if($isShipDocAccepted==0){?>class="active"<?php }?>><a data-toggle="tab" href="#tabShipment" aria-controls="tabShipment" role="tab"><span class="text-primary">Shipment Info</span></a></li>
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_Buyer && $isShipDocAccepted==1){ ?>
                        <li role="presentation" class="active"><a data-toggle="tab" href="#tabPreAlert" aria-controls="tabPreAlert" role="tab"><span class="text-primary">Pre-Alert</span></a></li>
                        <?php }?>
                    </ul>

                    <div class="tab-content padding-top-20">
                        <!--PO Information-->
                        <div class="tab-pane" id="tabPOInfo" role="tabpanel">
                            <div class="form-horizontal">

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title">Order Information</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">PO No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></a></b></label>
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
                        <!--End PO Information-->

                        <!--Attachments & Log-->
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
                        <!--End Attachments & Log-->

                        <!--LC Information-->
                        <div class="tab-pane" id="tabLCInfo" role="tabpanel">
                            <div class="form-horizontal">

                                <div class="row row-lg">

                                    <div class="col-md-12">
                                        <h4 class="well well-sm example-title">LC Information</h4>
                                    </div>

                                    <div class="col-xlg-5 col-md-5">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Value: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcvalue"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LCAF No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcafno"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Issue Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcissuedate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Expiry Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="daysofexpiry"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Last Shipment Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lastdateofship"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-7 col-md-7">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Type: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="lctype"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Product Type: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="producttype"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Bank: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="lcissuerbank"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Insurance: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="insurance"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Description:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-left" id="lcdesc1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Payment Terms</h4>
                                        <div class="row row-lg" id="divTermsEdited">
                                            <div class="col-md-4 small" id="paymentTermsText">
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!--End LC Information-->

                        <!--Shipment Information-->
                        <div class="tab-pane <?php if($isShipDocAccepted==0){?>active<?php }?>" id="tabShipment" role="tabpanel">
                            <div class="form-horizontal">

                                <form class="form-horizontal" id="buyersfeedback-form" name="buyersfeedback-form" method="post" autocomplete="off">
                                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } else {echo $actionLog['shipNo'];} ?>" />
                                    <input name="lcno" id="lcno1" type="hidden" value="" />
                                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                    <input name="postatus" id="postatus" type="hidden" value="" />
                                    <!--<input name="userAction" id="userAction" type="hidden" value="1" />-->

                                    <div class="row row-lg" id="shipmentInfo">

                                        <div class="col-md-12">
                                            <h4 class="well well-sm example-title">Supplier's Shipment Inputs</h4>
                                        </div>

                                        <div class="col-xlg-6 col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Shipment Mode: </label>
                                                <div class="col-sm-7">
                                                    <ul class="list-unstyled list-inline margin-top-5 shippingmode">
                                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                                        <li><input type="radio" id="shipmodeE-Delivery" name="shipmode" value="E-Delivery" data-plugin="iCheck" data-radio-class="iradio_flat-orange" />&nbsp;E-Delivery</li>
                                                    </ul>
                                                </div>
                                            </div>

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
                                                <label class="col-sm-5 control-label">IPC No: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="ipcNum" id="ipcNum" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">GIT Receiving Date: </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="gitReceiveDate" id="gitReceiveDate" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Arrival at Warehouse: </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="whDate" id="whDate" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-xlg-6 col-md-6">

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

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">CI Amount: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="ciAmount" id="ciAmount" />
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
                                                    <input type="text" class="form-control" name="dhlNum" id="dhlNum" readonly="" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Endorsment Doc. Shared by Finance: </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="docSharebyFinDate" id="docSharebyFinDate" readonly="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($actionLog['ActionID']==action_Shared_Shipment_Document || $actionLog['ActionID']==action_Shipping_Doc_Rectified_by_Supplier || $actionLog['ActionID']==action_Ship_Doc_Rejected_Warehouse || $actionLog['ActionID']==action_Ship_Doc_Rejected_EATeam){ ?>
                                    <hr />
                                    <div class="row row-lg">
                                        <div class="col-xlg-6 col-md-6">
                                            &nbsp;
                                        </div>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title clearfix">Accept/Reject Comment</h4>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" name="userMessage" id="userMessage" rows="2" placeholder="Feedback to Supplier..."></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12 text-right">
                                                    <button type="button" class="btn btn-danger pull-left" id="reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject </button>
                                                    <button type="button" class="btn btn-success" id="accept_btn"><i class="icon wb-check" aria-hidden="true"></i> Accept</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </form>

                            </div>
                        </div>
                        <!--End Shipment Information-->

                        <!--Pre Alert-->
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_Buyer && $isShipDocAccepted==1){ ?>
                        <div class="tab-pane active" id="tabPreAlert" role="tabpanel">
                            <div class="form-horizontal">
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <?php if($actionLog['ActionID']==action_Ship_Doc_Accepted_Buyer_Pending_WH){ ?>
                                        <form class="form-horizontal" id="formMailToWarehouse" name="formMailToWarehouse" method="post" autocomplete="off">
                                            <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                            <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                            <input name="shipno" id="shipno" type="hidden" value="<?php echo $actionLog['shipNo']; ?>" />
                                            <input name="lcno" id="lcno2" type="hidden" value="" />
                                            <!--<input name="userAction" id="userAction" type="hidden" value="2" />-->
                                            <div class="col-sm-6">
                                                <h4 class="well well-sm example-title clearfix" id="buyersMsgToWareHouseTitle">To Warehouse Team</h4>
                                                <div class="form-group ">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="buyersMsgToWareHouse" id="buyersMsgToWareHouse" rows="4" placeholder="Message to Supplier">As per the decision & practice (as a part of GIT elimination), in case of Foreign purchase, Items will be received virtually, while good are in transit. Please find the attached documents for upcoming shipment alert of PO in the system.</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" class="btn btn-primary" id="btnMailToWarehouse"><i class="icon wb-envelope" aria-hidden="true"></i> Send to Warehouse</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php }
                                        if($actionLog['ActionID']==action_Ship_Doc_Accepted_Buyer_Pending_EA){ ?>
                                        <form class="form-horizontal" id="formMailToEATeam" name="formMailToEATeam" method="post" autocomplete="off">
                                            <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                            <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                            <input name="shipno" id="shipno" type="hidden" value="<?php echo $actionLog['shipNo']; ?>" />
                                            <input name="lcno" id="lcno3" type="hidden" value="" />
                                            <input name="lcbank" id="lcbank" type="hidden" value="" />
                                            <!--<input name="userAction" id="userAction" type="hidden" value="3" />-->
                                            <div class="col-sm-6">
                                                <h4 class="well well-sm example-title clearfix" id="buyersMsgToEATitle">To EA Team</h4>
                                                <div class="form-group ">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="buyersMsgToEA" id="buyersMsgToEA" rows="4" placeholder="Message to Warehouse group">Please find the pre-alert attached herewith and let us know in advance if the docs lack to support your clearing.</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" class="btn btn-primary" id="btnMailToEATemm"><i class="icon wb-envelope" aria-hidden="true"></i> Mail to EA Team</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php }
                                        if($actionLog['ActionID']==action_Warehouse_Input_Updated_Pending_FN){ ?>
                                        <form class="form-horizontal" id="formMailToFinance" name="formMailToFinance" method="post" autocomplete="off">
                                            <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                            <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                            <input name="shipno" id="shipno" type="hidden" value="<?php echo $actionLog['shipNo']; ?>" />
                                            <input name="lcno" id="lcno4" type="hidden" value="" />
                                            <!--<input name="userAction" id="userAction" type="hidden" value="4" />-->
                                            <div class="col-sm-6">
                                                <h4 class="well well-sm example-title clearfix" id="buyersMsgToFinanceTitle">To Finance Team</h4>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Voucher Date:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                            <input type="text" class="form-control" data-plugin="datepicker" name="voucherCreateDate" id="voucherCreateDate" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Voucher No.:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="voucherNo" id="voucherNo" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Exchange Rate.:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">BDT</span>
                                                            <input type="text" class="form-control curnum" name="exchangeRate" id="exchangeRate" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Document Type:</label>
                                                    <div class="col-sm-7">
                                                        <ul class="list-unstyled list-inline margin-top-5">
                                                            <li><input type="radio" id="docEndorse" name="docType" value="endorse" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Endorse Doc</li>
                                                            <li><input type="radio" id="docOriginal" name="docType" value="original" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Original Doc</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="buyersMsgToFinance" id="buyersMsgToFinance" rows="3" placeholder="Message to Supplier">Please find the shipment documents and give your feedback.</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12 text-right">
                                                        <button type="button" class="btn btn-primary" id="btnMailToFinance"><i class="icon wb-check" aria-hidden="true"></i> Update Voucher</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <!--End Pre Alert-->
                    </div>
                </div>

                <hr />
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12 text-right">
                        <!--<button type="button" class="btn btn-primary" id="preLaertComplete_btn">Pre Alert Notification Complete</button>-->
                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
