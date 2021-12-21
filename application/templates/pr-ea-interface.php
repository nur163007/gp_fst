<?php $title="PI BOQ Catalog"; ?>
<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">PR User/ EA Team Interface</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="draftpiboq-form" name="draftpiboq-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="userAction" id="userAction" type="hidden" value="" />
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
                                    <textarea class="form-control" name="lcdesc" id="lcdesc"></textarea>
                                    <label class="control-label text-left" id="lcdescLabel"><img src="assets/images/busy.gif" /></label>
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
                                    <label class="control-label"><b id="pr_no"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Department:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="department"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Need by Date:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Implementation by:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Max shipment allowed:</label>
                                <div class="col-sm-7">
                                    <label class="control-label" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Max LC will be issued:</label>
                                <div class="col-sm-7">
                                    <label class="control-label" id="noflcissue"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            
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
                                <label class="col-sm-5 control-label">PI Description:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="pi_desc"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <div id="shipModeEditable">
                                <label class="col-sm-5 control-label">Shipment Mode:</label>
                                <div class="col-sm-7">
                                    <ul class="list-unstyled list-inline margin-top-5">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                        <li><input type="radio" id="shipmodeseaair" name="shipmode" value="sea+air" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Sea + Air</li>
                                    </ul>
                                </div>
                                </div>
                                <div id="shipModeNonEditable">
                                <label class="col-sm-5 control-label">Shipment Mode:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="shipmodeLabel"><img src="assets/images/busy.gif" /></label>
                                </div>
                                </div>
                                <label class="col-sm-5 control-label">HS Code:</label>
                                <div class="col-sm-7">
                                    <input class="form-control" id="hscode" name="hscode" />
                                    <label class="control-label text-left" id="hscodeLabel"><img src="assets/images/busy.gif" /></label>
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
                                <label class="col-sm-5 control-label">Buyer's Contact:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Technical Contact:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            
                            <div id="usersAttachments">
                            </div>                            
                            
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" style="" id="buyersmsg">
                                </div>
                            </div>
                            
                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" id="suppliersmsg">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row row-lg">
                        <!--PO Lines-->
                        <div class="col-xlg-12 col-md-12 margin-bottom-20">
                            <h4 class="well well-sm example-title" style="background-color: #BFEDD8;">PO Lines</h4>
                            <div id="deliveredPOLinesDetail">
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
                    </div>

            <!--End PO Information-->

            <hr/>
                    <div class="row row-lg">
                        
                        <div class="col-xlg-6 col-md-6">
                            <?php if($_SESSION[session_prefix.'wclogin_role'] == role_PR_Users){ ?>
                            <h4 class="well well-sm example-title">PR Attachment</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Justification:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachJustification" id="attachJustification" readonly placeholder=".pdf, .docx, .zip" />
                                        <input type="hidden" name="attachJustificationOld" id="attachJustificationOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadJustification" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachJustificationLink"></span>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Expense Type: </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" data-plugin="select2" name="exp_type" id="exp_type">
                                            <option value="">Select expanse type</option>
                                            <option value="1">Capex</option>
                                            <option value="2">Opex</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">User Justification: </label>
                                    <div class="col-sm-8">
                                        <textarea class="form-control" name="user_just" id="user_just" placeholder="Write Something..."></textarea>
                                    </div>
                                </div>
                            <?php } else {echo "&nbsp;"; } ?>
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Comment</h4>
                            <div class="form-group isMessageUser">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="messageUser" id="messageUser" rows="4" placeholder="New Comment"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg padding-25">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <?php if(in_array($actionLog['ActionID'],
                                            array(action_Draft_PI_Rejected_by_PR,
                                                action_Draft_PI_Accepted_by_PR,
                                                action_Draft_PI_Rejected_by_EA,
                                                action_Draft_PI_Accepted_by_EA)) || in_array($actionLog['ActionID'],
                                            array(action_Final_PI_Rejected_by_PR,
                                                action_Final_PI_Accepted_by_PR,
                                                action_Final_PI_Rejected_by_EA,
                                                action_Final_PI_Accepted_by_EA))) {?>
                                    <button type="button" class="btn btn-warning" id="toSupplierRectify_btn"><i class="icon wb-warning"></i> Send to Supplier for Rectification</button>
                                    <?php /*if(in_array($actionLog['ActionID'],
                                                array(action_Draft_PI_Rejected_by_PR,
                                                    action_Draft_PI_Rejected_by_EA,
                                                    action_Final_PI_Rejected_by_PR,
                                                    action_Final_PI_Rejected_by_EA))){*/?>
                                        <!--<button type="button" class="btn btn-dark" id="toEditAndSendForPREAReCheck_btn"><i class="icon wb-warning"></i> Edit to Send for Recheck</button>-->
                                    <?php //}
                                        } if(in_array($actionLog['ActionID'],
                                        array(action_Draft_PI_Accepted_by_PR,
                                            action_Draft_PI_Accepted_by_EA))) {?>
                                    <button type="button" class="btn btn-success" id="toSupplierFinalPI_btn"><i class="icon wb-check"></i> Send to Supplier for Final PI</button>
                                    <!--<button type="button" class="btn btn-primary" id="AcceptFinalPI_btn"><i class="icon wb-check"></i> Accept &amp; Proceed for BTRC Permission</button>-->
                                    <?php } if(in_array($_SESSION[session_prefix.'wclogin_role'],
                                        array(action_Draft_PI_Submitted,
                                            action_Final_PI_Sent_for_EA_Feedback))){?>
                                        <button type="button" class="btn btn-danger pull-left" id="RejectToBuyer_btn"><i class="icon wb-warning"></i> Reject &amp; Send to Buyer</button>
                                        <button type="button" class="btn btn-primary" id="AcceptToBuyer_btn"><i class="icon wb-check"></i> Accept &amp; Send to Buyer</button>
                                    <?php }?>
                                    <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->