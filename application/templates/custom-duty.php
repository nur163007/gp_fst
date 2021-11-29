<?php 
$title="Custom Duty"; 

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);

?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Custom Duty</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li>Shipment # <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="custom-duty-form" name="custom-duty-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="pono" name="pono" readonly="" />
                                    <!--select class="form-control" data-plugin="select2" name="lcno" id="lcno" >
                                    </select-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="lcno" name="lcno" readonly="" />
                                    <!--select class="form-control" data-plugin="select2" name="lcno" id="lcno" >
                                    </select-->
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-sm-4 control-label">LC Date:</label>
								<div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="lcissuedate" id="lcissuedate" readonly="" />
                                    </div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Value:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">USD</span>
                                        <input type="text" class="form-control curnum" id="ciAmount" name="ciAmount" readonly />
                                    </div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Description:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Type: </label>
                                <div class="col-sm-8">
                                    <label class="control-label"><b id="producttype"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                            </div>
                        </div>
                        <!--TOP RIGHT-->
                        <div class="col-xlg-5 col-md-5">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">MAWB Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="mawbNo" name="mawbNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HAWB Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="hawbNo" name="hawbNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BL Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="blNo" name="blNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Original/Endorsed Bank Document:</label>
                                <div class="col-sm-8 padding-top-5">
                                    <span id="attachOriginalBankDocLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bill of Entry Copy:</label>
                                <div class="col-sm-8 padding-top-5">
                                    <span id="attachBillOfEntryLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other Custom's Doc<span class="comment-meta">(If any)</span>:</label>
                                <div class="col-sm-8 padding-top-5">
                                    <span id="attachOtherCustomDocLink"></span>
                                </div>
                            </div>
                            <!--<div id="usersAttachments">
                            </div>-->
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Ref.No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="gpRefNum" name="gpRefNum" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">B/E Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="billOfEntryNo" name="billOfEntryNo" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">B/E Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="billOfEntryDate" id="billOfEntryDate" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Requisition Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="RequisitionDate" id="RequisitionDate" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Beneficiary: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="beneficiary" id="beneficiary" disabled >
                                    </select>
                                    <textarea class="form-control" id="beneficiaryText" cols="30" rows="2" disabled style="resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">C &amp; F Agent: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="cnfAgent" id="cnfAgent" disabled >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Remarks from EA: </label>
                                <div class="col-sm-8">
                                    <input type="hidden" id="RemarksFromEA" name="RemarksFromEA" value="<?php echo $actionLog["UserMsg"]; ?>" />
                                    <textarea class="form-control" name="Remarks" id="Remarks" cols="30" rows="3" disabled><?php echo $actionLog["UserMsg"]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!--BOTTOM Right-->
                        <div class="col-xlg-5 col-md-5">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Pay Order Amount: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="CdPayAmount" name="CdPayAmount" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">VAT: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="Vat" id="Vat" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Vat on C&amp;FC: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="vatOnCnFC" id="vatOnCnFC" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AIT: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="ait" id="ait" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">ATV: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="atv" id="atv" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AT: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="advanceTax" id="advanceTax" value="0.00" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Applicable rate for Rebate on Vat on C&amp;FC amount: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">%</span>
                                        <input type="text" class="form-control curnum" name="vrPercentage" id="vrPercentage" value="100.00" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Rebate on Vat on C&amp;FC: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="RebateAmount" id="RebateAmount" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Custom Duty (Capex): </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" name="customDuty" id="customDuty" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Pay Order Delivery Time: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="payorderDeliveryTime" id="payorderDeliveryTime" />
                                        <span class="input-group-addon btn" id="btnUpdatePODelivTime">
                                            <i class="icon wb-time" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Reject Comment</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="userMessage" id="userMessage" rows="2" placeholder="Feedback to sourcing..."></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-danger" id="reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            &nbsp;<div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnGenerate_CDLetter"><i class="icon wb-download" aria-hidden="true"></i> Custom Duty Letter</button>
                                    <button type="button" class="btn btn-primary" id="saveCustomDuty_btn"><i class="icon fa-save" aria-hidden="true"></i> Save</button><br />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-success" id="notifyToSourcing_btn"><i class="icon wb-bell" aria-hidden="true"></i> Notify to Sourcing</button>
                                    <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="formLetterContent" method="post" action="application/library/docGen.php">
                    <input type="hidden" id="fileName" name="fileName" />
                    <textarea class="hidden" id="letterContent" name="letterContent"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->