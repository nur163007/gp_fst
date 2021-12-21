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
        <h1 class="page-title">C&F CDVAT Inputs</h1>
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
                        <li role="presentation"><a data-toggle="tab" href="#tabShipmentInfo" aria-controls="tabShipmentInfo" role="tab"><span class="text-primary">Shipment Info.</span></a></li>
                        <li role="presentation" class="active"><a data-toggle="tab" href="#tabCNFInputs" aria-controls="tabCNFInputs" role="tab"><span class="text-primary">C&F CDVAT Inputs</span></a></li>
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
                                </div>
                            </div>

                        </div>

                        <!--Shipment Information-->
                        <div class="tab-pane" id="tabShipmentInfo" role="tabpanel">
                            <div class="form-horizontal">
                                <div class="row row-lg">

                                    <div class="col-md-12">
                                        <h4 class="well well-sm example-title">Shipment Information</h4>
                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Shipment Mode: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-uppercase"><b id="shipmode1"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Estimated Time of Arrival (ETA): </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="scheduleETA"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Estimated Time of Delivery (ETD): </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="scheduleETD"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">MAWB Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="mawbNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">HAWB Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="hawbNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">BL Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="blNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">AWB / BL Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="awbOrBlDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Amount: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciAmount"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">IPC No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ipcNum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">GIT Receiving Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="gitReceiveDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Arrival at Warehouse: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="whArrivalDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Invoice Quantity: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="invoiceQty"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">No. of Container: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="noOfcontainer"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Total number of Boxes: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="noOfBoxes"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Gross Chargeable Weight: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ChargeableWeight"><img src="assets/images/busy.gif" /></b></label>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">DHL Tracking Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="dhlNum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Endorsment Doc. Shared by Finance: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="docDeliveredByFin"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Custom Duty: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="cdAmount"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End Shipment Information-->

                        <div class="tab-pane active" id="tabCNFInputs" role="tabpanel">

                            <form class="form-horizontal" id="formCNFinputs" name="formCNFinputs" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="lastshipno" id="lastshipno" type="hidden" value="<?php echo $actionLog['LastShipment']; ?>" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="postatus" id="postatus" type="hidden" value="" />
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                                <input name="productDesc" id="productDesc" type="hidden" value="" />

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
                                                    <label class="col-sm-5 control-label">Income Tax on C&amp;F Commission:</label>
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
                                                    <label class="col-sm-4 control-label">Advance Income Tax:</label>
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
                                                    <label class="col-sm-4 control-label">Total CDVAT Amount:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><label>BDT</label></span>
                                                            <input type="text" class="form-control curnum" name="totalCDVATAmount" id="totalCDVATAmount" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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
                                                            <input type="text" class="form-control" name="attachOtherCustomDoc" id="attachOtherCustomDoc" readonly placeholder=".pdf" />
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
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Remarks: </label>
                                                    <div class="col-sm-8">
                                                        <textarea class="form-control" name="remarks" id="remarks" placeholder="Write Something..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <hr />
                                        <div class="row row-lg">
                                            <div class="col-xlg-12 col-md-12 text-right">
                                                <?php if ($actionLog['ActionID']==action_Request_for_CNF_Input || $actionLog['ActionID']==action_Reject_CNF_Inputs){?>
                                                    <button type="button" class="btn btn-primary" id="btnSubmitToGP"><i class="icon fa-arrow-circle-right" aria-hidden="true"></i>  Send to GP</button>
                                                   <button type="button" class="btn btn-primary" id="btnSubmitCDVATInputs"><i class="icon fa-save" aria-hidden="true"></i>  Save CD/VAT Inputs</button>
                                                <?php }
                                                elseif ($actionLog['ActionID']==action_CNF_Input_Given){?>
                                                    <button type="button" class="btn btn-danger" id="btnRejectCNF"><i class="icon fa-trash" aria-hidden="true"></i>  Reject</button>
                                                    <button type="button" class="btn btn-primary" id="btnAcceptCDVATInputs"><i class="icon fa-check-circle" aria-hidden="true"></i> Accept C&F inputs</button>

                                                <?php } ?>

                                            </div>
                                        </div>

                            </form>


                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- End Page -->
