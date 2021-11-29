<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">LC</li>
        </ol>
        <h1 class="page-title">New LC Request</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="nav-tabs-horizontal">
                    <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                        <li role="presentation"><a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab"><span class="text-primary">PO Detail</span></a></li>
                        <li role="presentation"><a data-toggle="tab" href="#tabUserFeedback" aria-controls="tabUserFeedback" role="tab"><span class="text-primary">Attachments &amp; Comments</span></a></li>
                        <li role="presentation" class="active"><a data-toggle="tab" href="#tabLCRequest" aria-controls="tabLCRequest" role="tab"><span class="text-primary">LC Request</span></a></li>
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
                                            <label class="col-sm-5 control-label">Contract Ref:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="contractref"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Need by Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Implementation by:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Number of shipment allowed:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="noflcissue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Number of LC will be issued:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
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
                                            <label class="col-sm-5 control-label">L/C Beneficiary &amp; Address:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="lcbankaddress"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Production Time:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="productiondays"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <!--<label class="col-sm-5 control-label">Buyer's Contact:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Technical Contact:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                            </div>-->
                                        </div>
                                    </div>
                                </div>
                                <!--<hr />
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12 text-right">
                                        <a type="button" class="btn btn-warning" href="edit-po?po=<?php /*echo $_GET['po']; */?>&ref=<?php /*echo $_GET['ref']; */?>"<i class="icon wb-edit"></i> Edit PO</a>
                                    </div>
                                </div>-->
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

                                        <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
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
                        <div class="tab-pane active" id="tabLCRequest" role="tabpanel">
                            <form class="form-horizontal" id="lcrequest-form" name="po-form" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <div id="PO_submit_error" style="display:none;"></div>

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title">Description of Equipment to be mentioned in the L/C:</h4>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="lcdesc1" id="lcdesc1" rows="4"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title" id="shipmode1">Shipment Mode</h4>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">HS Code:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="hscode1" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">LC Value:</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <input type="text" class="form-control curnum" id="lcvalue" name="lcvalue" />
                                                    <div class="input-group-addon">
                                                        <label id="lccurrency"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Request Type: </label>
                                            <div class="col-sm-6">
                                                <ul class="list-unstyled list-inline margin-top-5 shippingmode">
                                                    <li><input type="radio" id="lcRequestType0" name="lcRequestType" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;LC</li>
                                                    <li><input type="radio" id="lcRequestType1" name="lcRequestType" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;LCA</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <h4 class="well well-sm example-title">Terms</h4>

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="cocorigin" id="cocorigin" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Certificate of Origin to be issued by local Chamber of Commerce of the exporting country</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="iplbltolcbank" id="iplbltolcbank" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Invoice/Packing List/BL/Air Waybill/Certificate of Origin must be submitted in original to the L/C Negotiating Bank</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="delivcertify" id="delivcertify" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">The supplier must certify that the delivery has been made in accordance to the terms &amp; condition of the L/C and Purchase Order</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="qualitycertify" id="qualitycertify" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Quality certificate from supplier or manufacturer</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="qualitycertify1" id="qualitycertify1" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Phytosanitary certificate from concerned authority</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="advshipdoc" id="advshipdoc" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">One set of advance shipping document to sent by e-mail to the Applicant within 7 (seven) Working days from the date of shipment</label>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="advshipdocwithbl" id="advshipdocwithbl" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">One set of advance shipping document consisting of Invoice and Bill of Lading/Airway bill to be sent by e-mail to the insurance company within 7 (seven) Working days from the date of shipment. (Note: Insurance Company's Full Address and FAX number must be mentioned in the L/C).</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="addconfirmation" id="addconfirmation" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Add Confirmation</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="preshipinspection" id="preshipinspection" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Pre-shipment inspection</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="transshipment" id="transshipment" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Allow Transshipment</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="partship" id="partship" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Allow Part Shipment</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <input type="checkbox" class="icheckbox-primary" name="confchargeatapp" id="confchargeatapp" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue"  />
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Add confirmation charges at Applicant's end</label>
                                        </div>

                                    </div>

                                </div>

                                <h4 class="well well-sm example-title">Other Terms</h4>
                                <div class="row row-lg">
                                    <div class="col-xls-12 col-md-12">
                                        <textarea class="form-control" name="otherTerms" id="otherTerms" rows="2" placeholder="Other terms..." maxlength="50000"></textarea>
                                    </div>
                                </div>

                                <hr />

                                <h4 class="well well-sm example-title">Following numbers to be mentioned in each and every shipping documents:</h4>

                                <div class="row row-lg">

                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label"><span class="pull-left">A.</span>IRC No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="ircno" id="ircno" value="BA 186898" />
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">B.</span>Import Permit No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="imppermitno" id="imppermitno" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label"><span class="pull-left">C.</span>TIN No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="tinno" id="tinno" value="148483575191" />
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">D.</span>LCA No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="lcANo" id="lcANo" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label"><span class="pull-left">E.</span>VAT Reg. No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" name="vatregno" id="vatregno" value="000000081" />
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">F.</span>PO No.: </label>
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="pono1" readonly="" />
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-md-4 control-label">Delivery Mode:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipmode2" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-md-4 control-label">Port of Shipment:</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="shipport1" />
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Country of Origin:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" id="origin1"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">

                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Shipping Marks :</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">A.</span> PO No.: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="pono2" readonly="" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">B.</span> Customer Name: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="customername" id="customername" value="Grameenphone Limited" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">C.</span> Customer Address: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="customeraddress" id="customeraddress" value="GPHOUSE, Baridhara, Bashundhara, Dhaka-1229, Bangladesh" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">D.</span> TIN No.: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="tinno1" value="148483575191" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">E.</span> LC No.: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="lcno" id="lcno" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">F.</span> Destination: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="destination" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">G.</span> Supplier: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="supplier1" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">H.</span> Country of Origin: </label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" id="origin2" />
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">

                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label">L/C Expiry Date:</label>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="lcexpirydate" id="lcexpirydate" />
                                                </div>
                                            </div>
                                            <label class="col-sm-2 control-label">Last date of Shipment:</label>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </div>
                                                    <input type="text" class="form-control" data-plugin="datepicker" name="lastdateofship" id="lastdateofship" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">LC beneficiary &amp; Address:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="lcbankaddress1" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Negotiating Bank:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="negobank1" />
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Payment Terms <span class="pull-right"><i class="icon wb-download"></i> <span class="font-size-12">Contract Ref # <a id="contractDetailPdf">-</a></span></span>
                                        </h4>
                                        <div class="row row-lg">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Implementation by: </label>
                                                    <div class="col-sm-2">
                                                        <input type="hidden" id="installByOld" name="installByOld" />
                                                        <select class="form-control" data-style="btn-select" data-plugin="selectpicker" id="installBy" name="installBy" title="Install by">
                                                            <option value="0">GP</option>
                                                            <option value="1">Supplier</option>
                                                            <option value="2">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-offset-6 col-sm-2" id="addRowDiv_btn">
                                                        <div class="">
                                                            <button type="button" class="btn btn-primary pull-right" id="addNewPaymentTermsRow">Add Row +</button>
                                                        </div>
                                                    </div>

                                                    <!--div class="col-sm-12">
                                                        <select class="form-control" data-style="btn-select" data-plugin="selectpicker" name="selectPaymentTerm" id="selectPaymentTerm" title="Select Terms" >
                                                            <option value="">Payment Terms</option>
                                                            <option value="1" data-tag="[[70,'Sight',180],[20,'CAC',180],[10,'FAC',240]]">70%, 20%, 10%</option>
                                                            <option value="2" data-tag="[[60,'Sight',180],[40,'CAC',180]]">60%, 40%</option>
                                                            <option value="3" data-tag="[[50,'Sight',180],[30,'CAC',180],[10,'FAC',240]]">50%, 30%, 20%</option>
                                                            <option value="custom">Custom</option>
                                                        </select>
                                                    </div>
                                                    <label class="col-sm-12 control-label text-left margin-bottom-10 margin-left-5 small">Contract File: </label-->
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-sm-offset-1 col-sm-1">
                                                    <label for="percentage">Per.(%)</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="certificate">Certificate</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="matDays">Maturity Days</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="matDays">Maturity Terms</label>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label for="matDays">Days</label>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label for="matDays">Certificate Title</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="lcPaymentTermsTable">

                                            </div>
                                        </div>

                                        <hr />
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="paymentTermsText" id="paymentTermsText" rows="6"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Advising Bank:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="advBank" name="advBank" value="N/A" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person for PSI:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="contactPSI" name="contactPSI" value="N/A" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">PSI Clause to be mentioned in the L/C :</label>
                                            <label class="col-sm-1 control-label">A.</label>
                                            <label class="col-sm-1 control-label">SEA</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="psiClauseA" name="psiClauseA" value="N/A" />
                                            </div>
                                            <label class="col-sm-1 control-label">B.</label>
                                            <label class="col-sm-1 control-label">AIR</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="psiClauseB" name="psiClauseB" value="N/A" />
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Insurance Notification:</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="insNotification1" id="insNotification1">All shipments under this credit must be advised by the shipper immediately aftershipmentdirect to M/S.</textarea><br />
                                                <textarea class="form-control" name="insNotification2" id="insNotification2">by email and a email copy to the applicant or by DHL and an Airway Bill receipt/Sticker to the applicant referringto Insurance Cover Note No.</textarea><br />
                                                <textarea class="form-control" name="insNotification3" id="insNotification3">Giving full details of shipment. Copies of these advice(s) must accompany each set of documents.</textarea>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Packing Instruction:</label>
                                            <label class="col-sm-2 control-label">A. For AIR Shipment:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="forAirShipment1" id="forAirShipment1">Standard Export packing in Wooden boxes with proper plastic cover for rainwater protection.</textarea><br />
                                            </div>
                                            <label class="col-sm-3 control-label">&nbsp;</label>
                                            <label class="col-sm-2 control-label">B. For SEA Shipment:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="forSeaShipment1" id="forSeaShipment1">Goods to be packed in FCL/FCL</textarea>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Note: Please do not mention the text "Invoice also to mention Name of Carrier:</label>
                                            <label class="col-sm-2 control-label">A. AIR:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="forAirShipment2" id="forAirShipment2">Airport of Loading, Airway Bill no. and Date  in the L/C</textarea><br />
                                            </div>
                                            <label class="col-sm-3 control-label">&nbsp;</label>
                                            <label class="col-sm-2 control-label">B. SEA:</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="forSeaShipment2" id="forSeaShipment2">Seaport of Loading, Bill of Lading no. and Date  in the L/C</textarea>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">OTHER REMARKS: (For additional information other than the above)</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" name="shippingRemarks" id="shippingRemarks" rows="3">Shipping Marks : Shipping Marks should be mentioned in Commercial Invoice, Packing List, XXXX, Certificate of Origin and on all Packages. Also proper labeling/Shipping marks of Importers Bin No, Tin No. & VAT address must be mentioned on all Packages.</textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="col-sm-12 text-right">
                                                <button type="button" class="btn btn-primary" id="SendLCRequest_btn">Send LC Request to Finance</button>
                                                <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                                            </div>
                                        </div>
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