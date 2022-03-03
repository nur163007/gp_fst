<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">LC Request : PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
        <ol class="breadcrumb">
            <li><a>Status: </a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
        </ol>
		<div class="page-header-actions">
            <button class="btn btn-sm btn-inverse btn-round" id="printTerms">
                <i class="icon wb-print" aria-hidden="true"></i>
				<span class="hidden-xs">Print</span>
            </button>
		</div>
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
                                            <label class="col-sm-4 control-label">PO No:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></a></b></label>
                                            </div>
                                            <label class="col-sm-4 control-label">PO Value:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label" id="povalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-4 control-label">Description:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-4 control-label">LC Description:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-left" id="lcdesc1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-4 control-label">Supplier:</label>
                                            <div class="col-sm-8">
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
                                                <label class="control-label text-left" id="hscode1"><img src="assets/images/busy.gif" /></label>
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

                        <div class="tab-pane active" id="tabLCRequest" role="tabpanel">
                            <form class="form-horizontal" id="lcrequest-form" name="po-form" method="post" autocomplete="off">
                                <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="postatus" id="postatus" type="hidden" value="" />
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <input name="lcAmount" id="lcAmount" type="hidden" value="" />
                                <input name="lcCur" id="lcCur" type="hidden" value="" />
                                <input name="hiddenShipMode" id="hiddenShipMode" type="hidden" value="" />
                                <div id="PO_submit_error" style="display:none;"></div>

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title">Description of Equipment to be mentioned in the L/C:</h4>
                                        <div class="form-group">
                                            <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" id="lcdesc"></div>
                                        </div>
                                    </div>

                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title" id="shipmode1">Shipment Mode</h4>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">HS Code:</label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Value:</label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="lcvalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Request Type: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="lcRequestType"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <h4 class="well well-sm example-title">Terms</h4>

                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="cocorigin"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Certificate of Origin to be issued by local Chamber of Commerce of the exporting country</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="iplbltolcbank"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Invoice/Packing List/BL/Air Waybill/Certificate of Origin must be submitted in original to the L/C Negotiating Bank</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="delivcertify"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">The supplier must certify that the delivery has been made in accordance to the terms &amp; condition of the L/C and Purchase Order</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="qualitycertify"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Quality certificate from supplier or manufacturer.</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="qualitycertify1"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Phytosanitary certificate from concerned authority.</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="advshipdoc"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">One set of advance shipping document to sent by e-mail to the Applicant within 7 (seven) Working days from the date of shipment</label>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="advshipdocwithbl"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">One set of advance shipping document consisting of Invoice and Bill of Lading/Airway bill to be sent by e-mail to the insurance company within 7 (seven) Working days from the date of shipment. (Note: Insurance Company's Full Address and FAX number must be mentioned in the L/C).</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="addconfirmation"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Add Confirmation</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="preshipinspection"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Pre-shipment inspection</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="transshipment"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Allow Transhipment</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="partship"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Allow Part Shipment</label>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-1 padding-top-5">
                                                <h3><i class="icon fa-square-o text-primary" aria-hidden="true" id="confchargeatapp"></i></h3>
                                            </div>
                                            <label class="col-sm-9 control-label text-left">Add confirmation charges at Applicant's end</label>
                                        </div>

                                    </div>

                                </div>
                                <div id="otherTermsContent">
                                    <h4 class="well well-sm example-title">Other Terms</h4>
                                    <div class="row row-lg">
                                        <div class="col-xlg-12 col-md-12">
                                            <div class="form-group">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="otherTerms"></div>
                                            </div>
                                        </div>
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
                                                <label class="control-label text-primary" id="ircno"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">B.</span>Import Permit No.: </label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="imppermitno"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label"><span class="pull-left">C.</span>TIN No.: </label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="tinno"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">D.</span>LCA No.: </label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="lcno1"></label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-1">&nbsp;</label>
                                            <label class="col-sm-2 control-label"><span class="pull-left">E.</span>VAT Reg. No.: </label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="vatregno"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-2 control-label"><span class="pull-left">F.</span>PO No.: </label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="pono1"><img src="assets/images/busy.gif" /></label>
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
                                                <label class="control-label text-primary" id="shipmode2"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-md-4 control-label">Port of Shipment:</label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary" id="shipport1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Country of Origin:</label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="origin1"><img src="assets/images/busy.gif" /></label>
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
                                                <label class="control-label text-primary text-left" id="pono2"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">B.</span> Customer Name: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="customername"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">C.</span> Customer Address: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="customeraddress"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2">&nbsp;</label>
                                            <label class="col-sm-4 control-label"><span class="pull-left">D.</span> TIN No.: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="tinno1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">E.</span> LC No.: </label>
                                            <div class="col-sm-86">
                                                <label class="control-label text-primary text-left" id="lcno1"></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">F.</span> Destination: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="destination"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">G.</span> Supplier: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="supplier1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"><span class="pull-left">H.</span> Country of Origin: </label>
                                            <div class="col-sm-6">
                                                <label class="control-label text-primary text-left" id="origin2"><img src="assets/images/busy.gif" /></label>
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
                                                <label class="control-label text-primary" id="lcexpirydate"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-2 control-label">Last date of Shipment:</label>
                                            <div class="col-sm-3">
                                                <label class="control-label text-primary" id="lastdateofship"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">LC beneficiary &amp; Address:</label>
                                            <div class="col-sm-9">
                                                <label class="control-label text-primary text-left" id="lcbankaddress1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Negotiating Bank:</label>
                                            <div class="col-sm-9">
                                                <label class="control-label text-primary" id="negobank1"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Payment Terms :</h4>
                                        <div class="row row-lg">
                                            <div class="col-md-12">
                                                <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                                </table>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="paymentTermsText"></div>
                                        </div>

                                    </div>
                                </div>

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Advising Bank:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-primary" id="advBank">N/A</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person for PSI:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-primary" id="contactPSI">N/A</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">PSI Clause to be mentioned in the L/C :</label>
                                            <label class="col-sm-1 control-label">A.</label>
                                            <label class="col-sm-1 control-label">SEA</label>
                                            <div class="col-sm-2">
                                                <label class="control-label text-primary" id="psiClauseA">N/A</label>
                                            </div>
                                            <label class="col-sm-1 control-label">B.</label>
                                            <label class="col-sm-1 control-label">AIR</label>
                                            <div class="col-sm-2">
                                                <label class="control-label text-primary" id="psiClauseB">N/A</label>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Insurance Notification:</label>
                                            <div class="col-sm-8">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="insNotification1">All shipments under this credit must be advised by the shipper immediately aftershipmentdirect to M/S.</div><br />
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="insNotification2">by email and a email copy to the applicant or by DHL and an Airway Bill receipt/Sticker to the applicant referringto Insurance Cover Note No.</div><br />
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="insNotification3">Giving full details of shipment. Copies of these advice(s) must accompany each set of documents.</div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Packing Instruction:</label>
                                            <label class="col-sm-2 control-label">A. For AIR Shipment:</label>
                                            <div class="col-sm-6">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="forAirShipment1">Standard Export packing in Wooden boxes with proper plastic cover for rainwater protection.</div><br />
                                            </div>
                                            <label class="col-sm-3 control-label">&nbsp;</label>
                                            <label class="col-sm-2 control-label">B. For SEA Shipment:</label>
                                            <div class="col-sm-6">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="forSeaShipment1">Goods to be packed in FCL/FCL</div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">Note: Please do not mention the text "Invoice also to mention Name of Carrier:</label>
                                            <label class="col-sm-2 control-label">A. AIR:</label>
                                            <div class="col-sm-6">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="forAirShipment2">Airport of Loading, Airway Bill no. and Date  in the L/C</div><br />
                                            </div>
                                            <label class="col-sm-3 control-label">&nbsp;</label>
                                            <label class="col-sm-2 control-label">B. SEA:</label>
                                            <div class="col-sm-6">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="forSeaShipment2">Seaport of Loading, Bill of Lading no. and Date  in the L/C</div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">OTHER REMARKS: (For additional information other than the above)</label>
                                            <div class="col-sm-8">
                                                <div class="table-bordered margin-left-15 margin-right-15 padding-20 comment-body" style="white-space: pre-wrap;" id="shippingRemarks">Shipping Marks :Shipping Marks should be mentioned in Commercial Invoice, Packing List, Air Way Bill, Certificate of Origin and on all Packages. Also proper labeling/Shipping marks of Importers Bin No, Tin No. & VAT address must be mentioned on all Packages.</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <hr />

                                <div class="row row-lg">
                                    <?php //if(!in_array($_SESSION[session_prefix.'wclogin_role'],array(role_LC_Approvar_4,role_LC_Approvar_5))) {?>
                                    <?php if($_SESSION[session_prefix.'wclogin_role']!=role_LC_Approvar_4) {?>
                                        <div class="col-xlg-6 col-md-6">
                                        </div>
                                    <?php }?>
                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title">Approver's Comment :</h4>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="approversComment" id="approversComment" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Approvar_4){?>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">Bank &amp; Insurance</h4>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" id="bank1">Bank: </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" name="bank" id="bank" >
                                                    </select>
                                                    <input type="hidden" id="lcissuerbankOld" name="lcissuerbankOld" />
                                                    <input type="hidden" id="lcissuerbankNew" name="lcissuerbankNew" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" id="insurance1">Insurance: </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" name="insurance" id="insurance" >
                                                    </select>
                                                    <input type="hidden" id="insuranceOld" name="insuranceOld" />
                                                    <input type="hidden" id="insuranceNew" name="insuranceNew" />
                                                </div>
                                            </div>

                                            <div class="form-group" id="hiddenShipType">
                                                <label class="col-sm-4 control-label">Shipment Type: </label>
                                                <div class="col-sm-8 margin-top-5">
                                                    <ul class="list-unstyled list-inline">
                                                        <li><input type="radio" id="withLC" name="withLC" value="0"
                                                                   data-plugin="iCheck" data-radio-class="iradio_flat-green" checked />&nbsp;With LC
                                                        </li>
                                                        <li><input type="radio" id="withoutLC" name="withLC" value="1"
                                                                   data-plugin="iCheck"
                                                                   data-radio-class="iradio_flat-red" />&nbsp;Without LC</li>
                                                    </ul>
                                                    <!--input type="checkbox" class="icheckbox-primary" name="installbysupplier" id="installbysupplier"
                                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" /-->
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>
                                </div>

                                <hr />
                                <!--div id="ele4" class="b">
                                    <table style="background: #ccc;" class="table table-bordered">
                                        <tr>
                                            <td style="background: #fff; padding: 20px;">Test Cell value</td>
                                            <td style="background: #fff; padding: 20px;">21,121,121212</td>
                                        </tr>
                                    </table>
                                    <button class="print-link avoid-this" id="printJsPrint"> Print </button>
                                </div>
                                <hr /-->

                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <div class="form-group">
                                            <div class="col-sm-12 text-right">
                                                <button type="button" class="btn btn-danger pull-left" id="Reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject</button>
                                                <button type="button" class="btn btn-primary" id="Accept_btn"><i class="icon wb-check" aria-hidden="true"></i> Approve and Send for Next Level Approver</button>
                                                <button type="button" class="btn btn-default" id="btnGenerateLCALetter"><i class="icon fa-download" aria-hidden="true"></i> Download LCA Letter</button>
                                                <button type="button" class="btn btn-default" id="printTerms1"><i class="icon wb-print" aria-hidden="true"></i> Print Terms</button>
                                                <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                                <input type="hidden" id="fileName" name="fileName" />
                                <textarea id="letterContent" name="letterContent"></textarea>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
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