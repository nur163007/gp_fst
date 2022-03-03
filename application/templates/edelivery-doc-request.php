<?php
$title="E Delivery Document Request";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref'], 1);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Pre LC</li>
        </ol>
        <h1 class="page-title">Request for BASIS Approval Letter</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="nav-tabs-horizontal">

                    <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                        <li class="active" role="presentation">
                            <a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab">PO &amp; PI Information</a></li>
                        <?php if($_SESSION[session_prefix.'wclogin_role']!=role_bank_lc){ ?>
                        <?php if($actionLog["ActionID"]!=action_BASIS_Approval_Letter_Sent_by_Bank) {?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tabAttachmentLog" aria-controls="tabAttachmentLog" role="tab">Attachments &amp; Comments</a></li>
                        <?php }?>
                        <?php }?>
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){ ?>
                        <li role="presentation">
                        <?php if($actionLog["ActionID"]==action_BASIS_Approval_Letter_Sent_by_Bank) {?>
                            <a data-toggle="tab" href="#tabForwardingLetter" aria-controls="tabForwardingLetter" role="tab">BASIS Letter</a></li>
                        <?php } else {?>
                            <a data-toggle="tab" href="#tabForwardingLetter" aria-controls="tabForwardingLetter" role="tab">Letter Process</a></li>
                        <?php }?>
                        <?php }?>
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){ ?>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tabBankLetterRequest" aria-controls="tabBankLetterRequest" role="tab">Letter Request</a></li>
                        <?php }?>
                    </ul>

                    <div class="tab-content padding-top-20">

                        <div class="tab-pane active" id="tabPOInfo" role="tabpanel">
                            <div class="form-horizontal">
                                <!--Start PO & PI Information-->
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
                                            <label class="col-sm-5 control-label">PO Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="actualPoDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Supplier:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Supplier Address:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left"><b id="sup_address"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Contract Ref:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="contractref"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PR No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pr_no"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Department:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="department"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Need by Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Implementation by:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Max. shipment allowed:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Max. LC will be issued:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="noflcissue"><img src="assets/images/busy.gif" /></label>
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
                                            <label class="col-sm-5 control-label">PI Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pidate"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PI Value:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pivalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PI Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="pi_desc"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Shipment Mode:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="shipmode"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">HS Code:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
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
                                            <label class="col-sm-5 control-label">L/C Beneficiary &amp Address:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="lcbankaddress"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Production Time:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="productiondays"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">LC Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Buyer's Contact:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Technical Contact:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--End PO & PI Information-->

                                <!--Start PO Lines-->
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12 margin-bottom-20">
                                        <h4 class="well well-sm example-title">PO Lines
                                            <span class="pull-right" style="margin-top: 0;">
                                                    Delivered Number of Line: <label style="font-weight: bold" id="delivCount1">0</label>
                                                </span>
                                        </h4>
                                        <table class="table table-bordered table-striped table-highlight order margin-0 small" id="dtPOLinesDelivered">
                                            <thead>
                                            <tr>
                                                <th style="width:5%" class="text-center" rowspan="2">Line #</th>
                                                <th style="width:10%" class="text-center" rowspan="2">Item</th>
                                                <th style="width:24%" class="text-center" rowspan="2">Item Description</th>
                                                <th style="width:10%" class="text-center" rowspan="2">Delivery Date</th>
                                                <th style="width:5%" class="text-center" rowspan="2">UOM</th>
                                                <th style="width:10%" class="text-center" rowspan="2">Unit Price</th>
                                                <th style="width:10%" class="text-center poBg" colspan="2">PO</th>
                                                <th style="width:5%" class="text-center delivBg" colspan="2">Delivered</th>
                                                <!--<th style="width:5%" class="text-center" rowspan="2">LD</th>-->
                                            </tr>
                                            <tr>
                                                <th style="width:5%" class="text-center poBg">Qty.</th>
                                                <th style="width:10%" class="text-center poBg">Total Price</th>
                                                <th style="width:5%" class="text-cente delivBg">Qty.</th>
                                                <th style="width:10%" class="text-center delivBg">Total Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-left"></td>
                                                <td class="text-left"></td>
                                                <td class="text-left"></td>
                                                <td class="text-left"></td>
                                                <td class="text-right"></td>
                                                <td class="text-right poBg"></td>
                                                <td class="text-right poBg"></td>
                                                <td class="text-right delivBg"></td>
                                                <td class="text-right delivBg"></td>
                                                <!--<td class="text-right"></td>-->
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr style="font-weight: bolder;">
                                                <th colspan="6" class="text-right padding-top-15" style="font-weight: bold; font-size: inherit">Total: </th>
                                                <th class="text-center poBg padding-top-15" id="poQtyTotal" style="font-weight: bold; font-size: inherit"></th>
                                                <th class="text-right poBg padding-top-15" id="grandTotal" style="font-weight: bold; font-size: inherit"></th>
                                                <th class="text-center delivBg padding-top-15" id="dlvQtyTotal" style="font-weight: bold; font-size: inherit"></th>
                                                <th class="text-right delivBg padding-top-15" id="dlvGrandTotal" style="font-weight: bold; font-size: inherit"></th>
                                                <!--<th class="text-right" id="ldAmntTotal"></th>-->
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!--End PO Lines-->
                            </div>
                        </div>

                        <?php if($_SESSION[session_prefix.'wclogin_role']!=role_bank_lc){ ?>
                        <?php if($actionLog["ActionID"]!=action_BASIS_Approval_Letter_Sent_by_Bank) {?>
                        <div class="tab-pane" id="tabAttachmentLog" role="tabpanel">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <?php }?>

                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){ ?>
                        <div class="tab-pane" id="tabForwardingLetter" role="tabpanel">
                            <div class="form-horizontal">
                                <form id="formTFORequestToBank">
                                <div class="row row-lg">
                                    <?php if($actionLog["ActionID"]!=action_BASIS_Approval_Letter_Sent_by_Bank) {?>
                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title" id="buyersmsgtitle">Generate Letter</h4>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Issuer Bank:</label>
                                            <div class="col-sm-7">
                                                <select class="form-control" data-plugin="select2" name="letterIssuerBank" id="letterIssuerBank">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-11 text-right">
                                                <button type="button" class="btn btn-primary" id="btnGenerateForwardingLetter">Generate Forwarding Letter</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                    <div class="col-xlg-6 col-md-6">
                                        <?php if($actionLog["ActionID"]==action_BASIS_Approval_Letter_Sent_by_Bank) {?>
                                            <div id="usersAttachments">
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-11 text-right">
                                                    <button type="button" class="btn btn-primary" id="btnShareBASISApproval">Share BASIS Approval to Sourcing</button>
                                                </div>
                                            </div>
                                        <?php } else{?>
                                        <h4 class="well well-sm example-title">Attachment</h4>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Letter:</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="attachRequestLetter" id="attachRequestLetter" readonly placeholder=".pdf, .jpg, .png" />
                                                    <span class="input-group-btn">
                                                        <button type="button" id="btnUploadRequestLetter" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-11 text-right">
                                                <button type="button" class="btn btn-primary" id="btnSendRequestToBank">Send Request to Bank</button>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                        <?php }?>

                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){ ?>
                            <div class="tab-pane" id="tabBankLetterRequest" role="tabpanel">
                                <div class="form-horizontal">
                                    <form id="formSendApprovalToTFO">
                                    <div class="row row-lg">
                                        <div class="col-xlg-6 col-md-6">
                                            <div id="usersAttachments">
                                            </div>
                                        </div>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">Attachment</h4>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">BASIS Approval:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="attachBasisApproval" id="attachBasisApproval" readonly placeholder=".pdf, .jpg, .png" />
                                                        <span class="input-group-btn">
                                                        <button type="button" id="btnUploadBasisApproval" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-11 text-right">
                                                    <button type="button" class="btn btn-primary" id="btnSendApprovalToTFO">Send BASIS Approval to GP</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        <?php }?>

                    </div>
                    <hr />
                    <div class="row row-lg padding-bottom-30">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <form class="form-horizontal" id="formEDeliveryDoc" name="formEDeliveryDoc" method="post" autocomplete="off">
                                        <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                        <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                        <input id="roleId" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role'] ?>" />
                                        <input id="poAction" type="hidden" value="<?php echo $actionLog["ActionID"] ?>" />
                                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_Buyer){ ?>
                                            <button type="button" class="btn btn-primary" id="btnSendBASISDocRequest">Send Request for BASIS Approval Letter</button>
                                        <?php }?>
                                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Cancel</a>
                                    </form>
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