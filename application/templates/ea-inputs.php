<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
$title="EA Inputs";
?>
<style>
    .custom-label{
        font-size: 13px;
    }
</style>
<iframe id="iframeID" class="hidden"></iframe>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">EA Inputs</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li>Shipment # <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
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
                        <li role="presentation"><a data-toggle="tab" href="#tabShipment" aria-controls="tabLCRequest" role="tab"><span class="text-primary">Shipment Info.</span></a></li>
                        <li role="presentation" class="active"><a data-toggle="tab" href="#tabEAInputs" aria-controls="tabEAInputs" role="tab"><span class="text-primary">EA Inputs</span></a></li>
                        <li role="presentation"><a data-toggle="tab" href="#tabEALetter" aria-controls="tabEALetter" role="tab"><span class="text-primary">Letters</span></a></li>
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

                        <div class="tab-pane" id="tabShipment" role="tabpanel">

                            <div class="form-horizontal">
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
                                            <label class="col-sm-5 control-label">LC Number: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="lcNo" id="lcNo" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Issue Date: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="lcissuedate" id="lcissuedate" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Expiry Date: </label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="daysofexpiry" id="daysofexpiry" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

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
                                        &nbsp;
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

                            </div>

                        </div>

                        <div class="tab-pane active" id="tabEAInputs" role="tabpanel">

                            <form class="form-horizontal" id="formEAinputs" name="formEAinputs" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="lastshipno" id="lastshipno" type="hidden" value="<?php echo $actionLog['LastShipment']; ?>" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="postatus" id="postatus" type="hidden" value="" />
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                                <input name="productDesc" id="productDesc" type="hidden" value="" />
                            </form>

                            <div class="nav-tabs-horizontal">
                                <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                    <li class="active" role="presentation"><a data-toggle="tab" href="#eaInputsOne" aria-controls="eaInputsOne" role="tab">Basic Inputs</a></li>
                                    <li role="presentation"><a data-toggle="tab" href="#eaInputsTwo" aria-controls="eaInputsTwo" role="tab">CD/VAT Inputs</a></li>
                                    <li role="presentation"><a data-toggle="tab" href="#eaInputsThree" aria-controls="eaInputsThree" role="tab">IPC Inputs</a></li>
                                </ul>
                                <div class="tab-content padding-top-20">

                                    <div class="tab-pane active" id="eaInputsOne" role="tabpanel">
                                        <form class="form-horizontal" id="formBasicInputs" name="formBasicInputs" autocomplete="off">
                                            <div class="row row-lg">

                                                <div class="col-xlg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label"><span class="comment-meta">(Endorsed/Original)</span><br>Doc. Received from Finance:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="docReceiveByEA" id="docReceiveByEA" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Actual Arrival at port: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="actualArrivalAtPort" id="actualArrivalAtPort" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">WH Receive Date: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control" name="whReceiveDate" id="whReceiveDate"  data-plugin="datepicker" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Completed by: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control curnum" name="completionDays" id="completionDays" readonly="" />
                                                                <span class="input-group-addon"><label>Days</label></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Goods Release from port: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control" name="releaseFromPort" id="releaseFromPort"  data-plugin="datepicker" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label" for="eaRemarksOnBasic">Remarks: </label>
                                                        <div class="col-sm-7">
                                                            <textarea class="form-control" maxlength="500" name="eaRemarksOnBasic" id="eaRemarksOnBasic" cols="30" rows="5" placeholder="remarks on basic inputs"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xlg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">EA Ref. Number: </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" name="eaRefNo" id="eaRefNo" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">C&amp;F Net payment: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control curnum" name="cnfNetPayment" id="cnfNetPayment" />
                                                                <span class="input-group-addon">
                                                                    <label>BDT</label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Only Demurrage Amount: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control curnum" name="demurrageAmount" id="demurrageAmount" />
                                                                <span class="input-group-addon">
                                                                    <label>BDT</label>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">C&amp;F Agent Name:</label>
                                                        <div class="col-sm-7 ">
                                                            <select class="form-control" data-plugin="select2" name="cNfAgentName" id="cNfAgentName">
                                                            </select>
                                                            <input type="hidden" id="cNfAgentFullName" name="cNfAgentFullName"/>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">BTRC NOC No.: </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" name="btrcNocNo" id="btrcNocNo" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">NOC Date: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control" name="btrcNocDate" id="btrcNocDate"  data-plugin="datepicker" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr />
                                            <div class="row row-lg">
                                                <div class="col-sm-12 text-right">
                                                    <?php if ($actionLog['ActionID']==action_Requested_for_EA_Inputs){?>
                                                        <button type="button" class="btn btn-primary" id="btnPreAlerttoCNF"><i class="icon fa-arrow-circle-right" aria-hidden="true"></i> Send Pre-Alert to C&F</button>
                                                    <?php }
                                                    else {?>
                                                        <button type="button" class="btn btn-primary" id="btnPreAlerttoCNF" disabled><i class="icon fa-arrow-circle-right" aria-hidden="true"></i> Send Pre-Alert to C&F</button>
                                                    <?php } ?>
                                                    <button type="button" class="btn btn-primary" id="btnSubmitBasicInputs"><i class="icon fa-save" aria-hidden="true"></i> Save Basic Inputs</button>
                                                </div>`
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="eaInputsTwo" role="tabpanel">
                                        <form class="form-horizontal" id="formCDVATInputs" name="formCDVATInputs" autocomplete="off">
                                            <div class="row row-lg">
                                                <div class="col-xlg-6 col-md-6">

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">B/E No.: </label>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control" name="billOfEntryNo" id="billOfEntryNo" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">B/E Date: </label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                </span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="billOfEntryDate" id="billOfEntryDate" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-xlg-6 col-md-6">

                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Beneficiary:</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" data-plugin="select2" name="ddlBeneficiary" id="ddlBeneficiary">
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <hr/>
                                            <div class="row row-lg">

                                                <div class="col-xlg-6 col-md-6">
                                                    <h4 class="well well-sm example-title">Global Taxes</h4>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label custom-label">Income Tax on C&amp;F Commission:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="itOnCnFComm" id="itOnCnFComm" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">VAT on C&amp;F Commission:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="vatOnCnFComm" id="vatOnCnFComm" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Document Processing Fee:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="docProcessFee" id="docProcessFee" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Fines/Penalties:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="finePenalties" id="finePenalties" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Container Scanning Fee:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="contScanningFee" id="contScanningFee" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xlg-6 col-md-6">
                                                    <h4 class="well well-sm example-title">Item Taxes</h4>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Customs Duty:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="customDuty" id="customDuty" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Regulatory Duty:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="regulatoryDuty" id="regulatoryDuty" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Supplementary Duty:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="supplementaryDuty" id="supplementaryDuty" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Value Added Tax:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="valueAddedTax" id="valueAddedTax" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label custom-label">Advance Income Tax:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="advanceIncomeTax" id="advanceIncomeTax" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Advance Trade Vat:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="advanceTradeVat" id="advanceTradeVat" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Advance Tax:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="advanceTax" id="advanceTax" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr/>
                                            <div class="row row-lg">

                                                <div class="col-xlg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Total Global Taxes:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum" name="totalGlobalTaxes" id="totalGlobalTaxes" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xlg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Total Item Taxes:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum" name="totalItemTaxes" id="totalItemTaxes" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label custom-label">Total CDVAT Amount:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum" name="totalCDVATAmount" id="totalCDVATAmount" readonly />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <hr />
                                            <div class="row row-lg">
                                                <div class="col-sm-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="btnSubmitCDVATInputs"><i class="icon fa-save" aria-hidden="true"></i> Save CD/VAT Inputs</button>
                                                </div>`
                                            </div>
                                            <hr/>

                                            <div class="row row-lg" id="beupdate-form">

                                                <div class="col-xlg-6 col-md-6">

                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Original Bank Document:</label>
                                                        <div class="col-sm-7 padding-top-5">
                                                            <span id="attachOriginalBankDocLink"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Bill of Entry Copy:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="attachBillOfEntry" id="attachBillOfEntry" readonly placeholder=".pdf" />
                                                                <input type="hidden" name="attachBillOfEntryOld" id="attachBillOfEntryOld" />
                                                                <span class="input-group-btn">
                                                            <button type="button" id="btnUploadBillOfEntry" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                        </span>
                                                            </div>
                                                            <span id="attachBillOfEntryLink"></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Other Custom's Doc:<br><span class="comment-meta">(If any)</span></label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="attachOtherCustomDoc" id="attachOtherCustomDoc" readonly placeholder=".pdf, .zip" />
                                                                <input type="hidden" name="attachOtherCustomDocOld" id="attachOtherCustomDocOld" />
                                                                <span class="input-group-btn">
                                                            <button type="button" id="btnUploadOtherCustomDoc" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                        </span>
                                                            </div>
                                                            <span id="attachOtherCustomDocLink"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xlg-6 col-md-6">
                                                    <h4 class="well well-sm example-title">Remarks on CDVAT:</h4>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <textarea class="form-control" name="eaRemarksOnCD" id="eaRemarksOnCD" cols="30" rows="4" placeholder="remarks on Pay-Order request or Custom Duty..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <hr />
                                            <div class="row row-lg">
                                                <div class="col-xlg-12 col-md-12 text-right">
                                                    <button type="button" class="btn btn-primary" id="btn_RequestToFinance"><i class="icon fa-envelope" aria-hidden="true"></i> Pay-Order Request to Finance</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="eaInputsThree" role="tabpanel">
                                        <form class="form-horizontal" id="ipcInputs" name="ipcInputs" autocomplete="off">
                                            <div class="row row-lg">
                                                <div class="col-xlg-5 col-md-5">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">CDVAT PO No.:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="ipcPONO" id="ipcPONO" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">CDVAT PO Amount:</label>
                                                        <div class="col-sm-8">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><label>BDT</label></span>
                                                                <input type="text" class="form-control curnum taxCal" name="ipcPOAmount" id="ipcPOAmount" value="740,000,000.00" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Item Code:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="ipcItemCode" id="ipcItemCode" value="3003142" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Service Vallue:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control curnum taxCal" name="ipcServiceValue" id="ipcServiceValue" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Received Qty.:</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control text-right" name="ipcReceivedQty" id="ipcReceivedQty" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xlg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Received Qty. Status:</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" data-plugin="select2" name="ipcReceivedQtyStatus" id="ipcReceivedQtyStatus">
                                                                <option></option>
                                                                <option>Partial</option>
                                                                <option>Final</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">PO Need by Date:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="icon wb-calendar" aria-hidden="true"></i></span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="ipcPONeedByDate" id="ipcPONeedByDate" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Received Date:</label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"><i class="icon wb-calendar" aria-hidden="true"></i></span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="ipcReceivedDate" id="ipcReceivedDate" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-5 control-label">Tentative Delivery Date:<!--<br><span class="comment-meta">(It will notify to WH)</span>--></label>
                                                        <div class="col-sm-7">
                                                            <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                    </span>
                                                                <input type="text" class="form-control" data-plugin="datepicker" name="tentativeDelivDate" id="tentativeDelivDate" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row row-lg">
                                                <div class="col-xlg-12 col-md-12 text-right">
                                                    <button type="button" class="btn btn-success" id="btnMailForIPCNo"><i class="icon fa-envelope" aria-hidden="true"></i> Mail for IPC No.</button>
                                                    <button type="button" class="btn btn-primary" id="btnSaveIPCInputs"><i class="icon fa-save" aria-hidden="true"></i> Save IPC Inputs</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="tab-pane" id="tabEALetter" role="tabpanel">
                            <div class="form-horizontal">
                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Letter Date: </label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="letterDate" id="letterDate" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Letter Name: </label>
                                            <div class="col-sm-7">
                                                <select class="form-control" data-plugin="select2" name="ddlEALetterName" id="ddlEALetterName">
                                                    <option value=""></option>
                                                    <option value="1">Authorization Customs</option>
                                                    <option value="2">Authorization HAWB</option>
                                                    <option value="3">Authorization CTG</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xlg-2 col-md-3">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btnGenerateletter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Generate Letter</button>
                                        </div>
                                    </div>

                                    <form id="formLetterContent" method="post" action="application/library/docGen.php">
                                        <input type="hidden" id="fileName" name="fileName" />
                                        <textarea class="hidden" id="letterContent" name="letterContent"></textarea>
                                    </form>
                                </div>

                                <div class="row row-lg">

                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Consignment Snap:</h4>
                                        <div class="form-group">
                                            <div class="col-sm-12" id="emailTable">
                                                <table class="table table-bordered width-full" id="dtAverageCostdate">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">MAWB Number</th>
                                                        <th class="text-center">HAWB Number</th>
                                                        <th class="text-center">BL Number</th>
                                                        <th class="text-center">AWB / BL Date</th>
                                                        <th class="text-center">Container</th>
                                                        <th class="text-center">Boxes</th>
                                                        <th class="text-center">Weight</th>
                                                        <th class="text-center">Actual Arrival Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-center" id="tdMAWB">-</td>
                                                        <td class="text-center" id="tdHAWB">-</td>
                                                        <td class="text-center" id="tdBL">-</td>
                                                        <td class="text-center" id="tdAWBDate">-</td>
                                                        <td class="text-center" id="tdContainer">-</td>
                                                        <td class="text-center" id="tdBoxes">-</td>
                                                        <td class="text-center" id="tdWeight">-</td>
                                                        <td class="text-center" id="tdAADate">-</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xlg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-6 text-left">
                                                <form name="zips" action="application/library/zipDownloader.php" method="post">
                                                    <div id="filesToZip"></div>
                                                    <input type="hidden" name="docName" value="cnf_docs">
                                                    <span data-placement="top" data-toggle="tooltip" data-original-title="On click, system will create and download a ZIP file with required documents">
                                                        <button type="submit" id="createZip" name="createzip" class="btn btn-info" >
                                                            <i class="icon fa-download" aria-hidden="true"></i> ZIP Docs
                                                        </button>
                                                    </span>
                                                </form>
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <button type="button" class="btn btn-primary" id="btnGenerateEmail">
                                                    <i class="icon fa-envelope" aria-hidden="true"></i> Generate Email
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- End Page -->
