<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
    <style>
        @media (min-width: 768px) {
            .modal-xl {
                width: 70%;
                max-width:900px;
            }
    </style>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">LC Opening : PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
        <ol class="breadcrumb">
            <li><a>Status: </a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
        </ol>
		<div class="page-header-actions">
			&nbsp;
		</div>

        <!-- Modal -->
        <div class="modal fade modal-slide-in-top" id="CNViewModal" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl ">
                <form class="form-horizontal" id="form-cn-request" name="form-cn-request" method="post" autocomplete="off" >
                    <input name="refId2" id="refId2" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="ponum1" id="ponum1" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="userAction" id="userAction1" type="hidden" value="" />
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm()">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Cover Note Information</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row row-lg">

                                <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CN Number: </label>
                                <div class="col-sm-7">
                                    <span data-placement="top" data-toggle="tooltip" data-original-title="">
									    <input type="text" class="form-control" id="cn_number" name="cn_number" placeholder="Enter a CN number"/>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CN Date:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="cn_date" id="cn_date">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Pay Order Amount:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="pay_order_amount" name="pay_order_amount" placeholder="Enter pay order amount"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Pay Order Charge:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="pay_order_charge" name="pay_order_charge" placeholder="Enter pay order charge"/>
                                </div>
                            </div>
                                </div>

                                <div class="col-xlg-6 col-md-6" style="margin-top: -13px;">
                                    <div id="CNAttachments">
                                    </div>

                                </div>

                            </div>
                            <div class="model-footer margin-top-40">
                                <div class="row">
                                    <div class="col-sm-3 text-right">
                                        <label class="wc-error pull-left" id="form_error"></label>
                                        <button type="button" class="btn btn-primary" id="btnCnRequest" >Accept</button>
                                        <button type="button" class="btn btn-danger" id="btnRejectCN" data-dismiss="modal" aria-label="Close">Reject</button>
                                    </div>
                                    <div class="col-sm-9 text-left">
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Remarks:</label>
                                            <div class="col-sm-5">
                                                <textarea class="form-control" name="remarks" id="remarks" placeholder="Write something...."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
    </div>
    <div class="page-content container-fluid">
    
        <div class="panel">
        
            <div class="panel-body container-fluid">

                <div class="nav-tabs-horizontal">
                    <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                        <li role="presentation"><a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab"><span class="text-primary">PO Detail</span></a></li>
                        <li role="presentation"><a data-toggle="tab" href="#tabUserFeedback" aria-controls="tabUserFeedback" role="tab"><span class="text-primary">Attachments &amp; Comments</span></a></li>
                        <li role="presentation" class="active"><a data-toggle="tab" href="#tabLCOpening" aria-controls="tabLCRequest" role="tab"><span class="text-primary">LC Opening</span></a></li>
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

                        <div class="tab-pane active" id="tabLCOpening" role="tabpanel">
                            <form class="form-horizontal" id="lcrequest-form" name="lcrequest-form" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="refId1" id="refId1" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <div id="PO_submit_error" style="display:none;"></div>

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Payment Terms :
                                            <span class="pull-right" style="margin-top: -7px;">
                                                <button type="button" class="btn btn-sm btn-default" id="printTerms"><i class="icon wb-print" aria-hidden="true"></i> Print Terms in Detail</button>
                                            </span>
                                        </h4>
                                        <div class="row row-lg" id="divTermsEdited">
                                            <div class="col-md-4 small" id="paymentTermsText">
                                            </div>
                                            <div class="col-md-4 hidden">
                                                <textarea class="form-control small" rows="8" id="paymentTermsTextEditable" name="paymentTermsTextEditable"></textarea>
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                                </table>
                                                <div class="hidden" id="lcPaymentTermsTableEditable">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-default" id="btnEditTerms"><i class="icon wb-edit" aria-hidden="true"></i> Edit Terms</button>
                                            <button type="button" class="btn btn-success hidden" id="btnSaveTerms"><i class="icon fa-save" aria-hidden="true"></i> Save Terms</button>
                                            <button type="button" class="btn btn-warning hidden" id="btnCancelEditTerms"><i class="icon wb-warning" aria-hidden="true"></i> Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="row row-lg hidden">
                                    <div class="col-xlg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="col-sm-2 pull-right">
                                                <button type="button" class="btn btn-danger pull-right" id="Reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject Back</button>
                                            </div>
                                            <div class="col-sm-2 pull-right">
                                                <select class="form-control" data-plugin="select2" name="approverLevel" id="approverLevel" >
                                                    <option value=""></option>
                                                    <option value="<?php echo role_LC_Approvar_3 ?>">LC Approvar 3</option>
                                                    <option value="<?php echo role_LC_Approvar_4 ?>">LC Approvar 4</option>
                                                    <option value="<?php echo role_LC_Approvar_5 ?>">LC Approvar 5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row row-lg">

                                    <div class="col-md-12">
                                        <h4 class="well well-sm example-title">LC Operation's Input</h4>
                                    </div>

                                    <div class="col-xlg-6 col-md-5">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Type:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="lctype" id="lctype">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Product Type:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="producttype" id="producttype">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Bank:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="lcissuerbank" id="lcissuerbank">
                                                </select>
                                                <input type="hidden" id="lcissuerbankOld" name="lcissuerbankOld" />
                                                <input type="hidden" id="lcissuerbankNew" name="lcissuerbankNew" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Insurance:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="insurance" id="insurance">
                                                </select>
                                                <input type="hidden" id="insuranceOld" name="insuranceOld" />
                                                <input type="hidden" id="insuranceNew" name="insuranceNew" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Account No.:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="bankaccount" id="bankaccount">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Bank Service:</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" data-plugin="select2" name="bankservice" id="bankservice">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Service Remark:</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="serviceremark" id="serviceremark"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-7">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC No:</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="lcno" id="lcno" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LCAF No.:</label>
                                            <div class="col-sm-7">
                                                <input type="text" class="form-control" name="lcafno" id="lcafno" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Date:</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="lcissuedate" id="lcissuedate" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Day of Expiry:</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="form-control" name="daysofexpiry" id="daysofexpiry" readonly/>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="editedDates">

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Last Shipment Date:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="hidden" name="lastdateofshipOld" id="lastdateofshipOld" />
                                                        <input type="text" class="form-control" name="lastdateofship" id="lastdateofship" readonly="" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">LC Expiry Date:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="hidden" name="lcexpirydateOld" id="lcexpirydateOld" />
                                                        <input type="text" class="form-control" name="lcexpirydate" id="lcexpirydate" readonly="" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group hidden text-right" id="editedDateSaveOption">
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-success" id="btnSaveEditedDate"><i class="icon fa-save" aria-hidden="true"></i> Save</button>
                                                    <button type="button" class="btn btn-warning" id="btnDiscardEditedDate"><i class="icon fa-close" aria-hidden="true"></i> Discard</button>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Value (original Currency):</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label id="lcvalueCur"></label>
                                                    </div>
                                                    <input type="text" class="form-control curnum" name="lcvalue" id="lcvalue" readonly="" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Value (USD):</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control curnum" id="LcValueInUSD" name="LcValueInUSD" readonly="" />
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label>XE</label>
                                                    </div>
                                                    <input type="text" class="form-control curnum" id="xr1" name="xr1" value="1.00" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Value (BDT):</label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control curnum" id="LcValueInBDT" name="LcValueInBDT" readonly="" />
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label>XE</label>
                                                    </div>
                                                    <input type="text" class="form-control curnum" id="xr2" name="xr2" value="0.00" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="confChargeContainer">
                                            <label class="col-sm-4 control-label">Add Confirmation</label>
                                            <div class="col-sm-8 padding-top-5">
                                                <h3 class="pull-left"><i class="icon fa-square-o text-primary" aria-hidden="true" id="addconfirmation"></i></h3><span id="confChargeBearer"></span>
                                            </div>
                                        </div>
                                        <div class="form-group hidden" id="addChargeButton">
                                            <label class="col-sm-4 control-label">&nbsp;</label>
                                            <div class="col-sm-8 padding-top-5">
                                                <button type="button" id="btnOpenAddConfCharge" class="btn btn-sm btn-default btn-round" data-target="#addConfCharge-form"
                                                        data-toggle="modal" data-toggle="Add Confirmation Charge" data-original-title="Add Confirmation Charge"><i class="icon wb-plus" aria-hidden="true"></i> Add Confirmation Charge</button>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row row-lg">
                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title">LC Opening Documents</h4>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Opening Request:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="attachLCOpenRequest" id="attachLCOpenRequest" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                    <input type="hidden" name="attachLCOpenRequestOld" id="attachLCOpenRequestOld" />
                                                    <span class="input-group-btn">
                                            <button type="button" id="btnUploadLCOpenRequest" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($actionLog['ActionID'] !=action_Final_LC_Copy_Sent_to_GP ){ ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">LC Request for:</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group margin-top-10">
                                                        <input type="radio" name="lc" id="draftLC" value="0" />  &nbsp;<label for="draftLC">Draft</label> &nbsp;&nbsp;
                                                        <input type="radio" name="lc" id="finalLC" value="1" /> &nbsp;<label for="finalLC">Final</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label"></label>
                                                <div class="col-sm-8">
                                                    <button type="button" class="btn btn-primary margin-bottom-15" id="btnLCToBank"><i class="fas fa-arrow-right font-weight-900" aria-hidden="true"></i> Send LC Request to Bank</button>
                                                </div>
                                            </div>

                                        <?php } ?>


                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Bank Received Copy:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="attachBankReceiveCopy" id="attachBankReceiveCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                    <input type="hidden" name="attachBankReceiveCopyOld" id="attachBankReceiveCopyOld" />
                                                    <span class="input-group-btn">
                                            <button type="button" id="btnUploadBankReceiveCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Other:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="attachLCOther" id="attachLCOther" readonly placeholder="incase of multiple file use .zip" />
                                                    <input type="hidden" name="attachLCOtherOld" id="attachLCOtherOld" />
                                                    <span class="input-group-btn">
                                            <button type="button" id="btnUploadLCOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <hr />
                                        <div class="text-right margin-bottom-10">
                                            <button type="button" class="btn btn-primary" id="btnBCSEXrate"><i class="icon fa-save" aria-hidden="true"></i> Request for BCS EX Rate</button>
                                            <button type="button" class="btn btn-primary" id="btnCoverNote"><i class="icon fa-save" aria-hidden="true"></i> Send CoverNote Request</button>
                                            <button type="button" class="btn btn-primary" id="btnViewIC" data-target="#CNViewModal" data-toggle="modal" data-original-title="New CN Request"><i class="icon fa-save" aria-hidden="true"></i> View IC Inputs</button><hr>
                                            <button type="button" class="btn btn-primary hidden" id="btnGenerate_BankLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Bank Letter (LC)</button>
                                            <button type="button" class="btn btn-primary hidden" id="btnGenerate_BankLetterLCA"><i class="icon fa-file-word-o" aria-hidden="true"></i> Bank Letter (LCA)</button>
                                            <button type="button" class="btn btn-primary hidden" id="btnGenerate_LCAEnclosure"><i class="icon fa-file-word-o" aria-hidden="true"></i> LCA Enclosure</button>
                                        </div>
                                        <div class="text-right">
                                            <a type="button" href="lc-opening-bank-charges" class="btn btn-success" id="addOpeningCharge_btn"><i class="icon fa-folder-open-o" aria-hidden="true"></i> Opening Charge</a>
                                            <a type="button" href="marine-insurance" class="btn btn-success" id="addInsuranceCharge_btn"><i class="icon fa-cubes" aria-hidden="true"></i> Insurance Charge</a>
                                        </div>
                                        <hr />
                                        <div class="text-right">
                                            <button type="button" class="btn btn-primary" id="SaveLC_btn"><i class="icon fa-save" aria-hidden="true"></i> Save LC Opening Information</button>
                                            <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline"><i class="icon fa-sign-out" aria-hidden="true"></i> Close</a>
                                        </div>
                                    </div>

                                </div>

                                <hr />

                            </form>
                            <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                                <input type="hidden" id="fileName" name="fileName" />
                                <textarea id="letterContent" name="letterContent"></textarea>
                            </form>
                            <form class="form-horizontal" id="form-finallccopy" name="form-finallccopy" autocomplete="off" >
                                <input name="pono2" id="pono2" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="lcno2" id="lcno2" type="hidden" value="" />
                                <input name="userAction" id="userAction" type="hidden" value="2" />
                                <div class="row row-lg">
                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Final LC Copy:</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="attachFinalLCCopy" id="attachFinalLCCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                    <input type="hidden" name="attachFinalLCCopyOld" id="attachFinalLCCopyOld" />
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btnUploadFinalLCCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xlg-6 col-md-6">
                                        <button type="button" class="btn btn-warning pull-left" id="SendLCCopyToSourcing_btn"><i class="icon wb-arrow-left" aria-hidden="true"></i> Send LC Copy to Sourcing</button>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>
                </div>


            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade modal-slide-in-top" id="addConfCharge-form" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog modal-lg">
				<form class="form-horizontal" id="form-ConfirmationCharge" name="form-ConfirmationCharge" autocomplete="off" >
                    <input type="hidden" id="confChargeId" name="confChargeId" value="0" />
                    <input name="pono1" id="pono1" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="userAction" id="userAction" type="hidden" value="3" />
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Confirmation Charge</h4>
						</div>
						<div class="modal-body">
							
                            <div class="row row-lg">
                                <div class="col-md-6">
                                
                                    <div class="form-group">
        								<label class="col-sm-4 control-label">LC No:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" name="lcno1" id="lcno1" readonly="" />
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">Charge Type: </label>
        								<div class="col-sm-8">
        									<select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >                                                            
                                            </select>
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">Charge Amount:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" id="confChargeAmount" name="confChargeAmount" />
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">Currency:</label>
        								<div class="col-sm-8">
        									<select class="form-control" data-plugin="selectpicker" name="currency" id="currency">
                                            </select>
        								</div>
        							</div>
                                
                                </div>
                                
                                <div class="col-md-6">
                                
                                    <div class="form-group">
        								<label class="col-sm-4 control-label">Exchange Rate:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" id="exchangeRate" name="exchangeRate" />
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">15% VAT:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" id="vatOnConfCharge" name="vatOnConfCharge" placeholder="15% VAT on Charge" />
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">Other Charge:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" id="otherCharge" name="otherCharge" value="0" />
        								</div>
        							</div>
        							<div class="form-group">
        								<label class="col-sm-4 control-label">Total:</label>
        								<div class="col-sm-8">
        									<input type="text" class="form-control" id="totalCharge" name="totalCharge" />
        								</div>
        							</div>
                                
                                </div>
                                
                            </div>
                            
                            <hr />
                            
                            <div class="row row-lg">                            
                                <div class="col-xlg-6 col-md-12">
                                
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Advice Document:</label>
                                        <div class="col-sm-7">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="attachConfChargeAdvice" id="attachConfChargeAdvice" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                <input type="hidden" name="attachConfChargeAdviceOld" id="attachConfChargeAdviceOld" />
                                                <span class="input-group-btn">
                                                    <button type="button" id="btnUploadConfChargeAdvice" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                            
                            <hr />
							
                            <div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
                                <button type="button" class="btn btn-primary" id="btnAddConfChargeSubmit">Submit</button>
								<button type="button" class="btn btn-default btn-outline"data-dismiss="modal" aria-label="Close" onclick="resetConfChargeForm();">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
        <!-- End Modal -->
    </div>
</div>
<!-- End Page -->
<style>
    #printFormat { margin: 0; top:0; }
    #printFormat table { border-collapse: separate; border-spacing: 3px; font-size: 80%; }
    #printFormat td{ background-color: #fff; padding: 5px; margin: 0; }
    #printFormat h3 { margin: 0; font-size: 110%;}
    .tdbox { border: 1px solid #000; }
    .tdboxc { border: 1px solid #000; text-align: center; }
</style>
<?php include("letter_template/lc-request-terms-print-format.php"); ?>