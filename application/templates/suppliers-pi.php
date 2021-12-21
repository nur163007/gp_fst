<?php
$title="Supplier's PI";

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
        <h1 class="page-title">Supplier's PI</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="formSuppliersPi" name="formSuppliersPi" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="pov" id="pov" type="hidden" value="" />
                    <input type="hidden" name="consolidatedPoLines" id="consolidatedPoLines" value="" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
                            <div class="stPOInfo">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">PO No:</label>
                                    <div class="col-sm-7">                                    
                                        <label class="control-label text-left"><b id="ponum"><img src="assets/images/busy.gif" /></b></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">PO Value:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="povalue"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">PO Description:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Supplier:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="supplier"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Supplier Address:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="sup_address"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Contract Ref:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="contractref"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">PR No:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="pr_no"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Department:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="department"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Need by Date:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Implementation by:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="installbysupplier"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Max. shipment allowed:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="noflcissue"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Max. LC will be issued:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Buyer's Contact:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Technical Contact:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                            </div>
                            <div id="usersAttachments">
                            </div>
                            
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" style="" id="buyersmsg">
                                </div>
                            </div>
                            
                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" id="suppliersmsg">
                                <!--div class="col-sm-11 table-bordered margin-left-20 padding-20" style="white-space: pre-wrap;" id="suppliersmsg"-->
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Supplier's Input</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI No: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="pinum" id="pinum" autocomplete="off" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Value: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pivalue" id="pivalue" autocomplete="off" />
                                        <div class="input-group-addon">
                                            <label id="pivalueCur"></label>
                                        </div>
                                    </div>
                                    <span id="piValueWarning" class="col-md-12 margin-top-10 small label-outline label-warning hidden">PI value less then PO value means multiple PI? Are you sure? Please consult with buyer.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Description: </label>
                                <div class="col-sm-6">
                                    <textarea type="text" class="form-control" name="pi_description" id="pi_description" placeholder="Write Something..."></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Type:</label>
                                <div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" name="producttype" id="producttype">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Shipment Mode: </label>
                                <div class="col-sm-8">
                                    <ul class="list-unstyled list-block margin-top-5">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" /> CFR Chittagong by Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" /> CPT Dhaka by Air</li>
                                        <li><input type="radio" id="shipmodeseaair" name="shipmode" value="sea+air" data-plugin="iCheck" data-radio-class="iradio_flat-red" /> Sea + AIR (Both)</li>
                                        <li><input type="radio" id="shipmodeE-Delivery" name="shipmode" value="E-Delivery" data-plugin="iCheck" data-radio-class="iradio_flat-orange" /> E-Delivery</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="hscode" id="hscode" autocomplete="off" maxlength="200" />
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes Air: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="hscode" id="hscode" autocomplete="off" />
                                </div>
                            </div-->
                            <?php if($actionLog['ActionID']>=action_Requested_for_Draft_PI_Rectification){?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="pidate" id="pidate" />
                                    </div>
                                    <span class="comment-meta">(supplier inputs w.r. to Final PI)</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Insurance / Base Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="basevalue" id="basevalue" autocomplete="off" />
                                    <span class="comment-meta">(value without discount)</span>
                                </div>
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country of Origin: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" multiple="" name="origin[]" id="origin">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Negotiating Bank &amp Address:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="negobank" id="negobank" autocomplete="off" maxlength="300" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Port of Shipment: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="shipport" id="shipport" autocomplete="off" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">L/C Beneficiary &amp Address:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="lcbankaddress" id="lcbankaddress" autocomplete="off" maxlength="300" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Lead Time (days):</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="productiondays" id="productiondays" autocomplete="off" maxlength="11" />
                                    <span class="comment-meta">(Lead time for shipment from LC date)</span>
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-4 control-label">Buyer Contact:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="buyercontact" id="buyercontact" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Technical Contact:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="techcontact" id="techcontact" autocomplete="off" />
                                </div>
                            </div-->
                            <h4 class="well well-sm example-title">Supplier's Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PI:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachDraftPI" id="attachDraftPI" readonly placeholder=".pdf, .docx, .xlsx" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadDraftPI" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">BOQ:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachDraftBOQ" id="attachDraftBOQ" readonly placeholder=".pdf, .docx, .xlsx" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadDraftBOQ" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Catalog:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachCatelog" id="attachCatelog" readonly placeholder=".pdf, .docx, .xlsx, .zip" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadCatelog" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title clearfix">Comment
                                <span class="pull-right"><input type="checkbox" class="icheckbox-primary" id="messageyes" name="messageyes" checked="" /></span>
                            </h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control isDefMessage" name="suppliersmessage" id="suppliersmessage" rows="4" placeholder="If you have any comment please write here..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <!--LINE WAYS PO-->

                    <div class="row row-lg">
                        <!--PO Lines-->
                        <div class="col-xlg-12 col-md-12 margin-bottom-20">
                            <h4 class="well well-sm example-title" style="background-color: #BFEDD8;">PO Lines
                                <!--<span class="pull-right">
                                    <button class="btn btn-primary btn-xs" style="margin-top: -5px;" id="btnLoadPoLines"><i class="icon wb-refresh" aria-hidden="true"></i> Reload PO Lines</button>
                                </span>-->
                            </h4>
                            <div class="nav-tabs-horizontal">
                                <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a data-toggle="tab" href="#tabDeliverable" aria-controls="tabDeliverable" role="tab"><span class="text-primary">Deliverable <span id="delivCount2">(0)</span> &nbsp;&nbsp;<button type="button" style="padding: 0px; color: white;" class="btn btn-pure btn-info icon wb-refresh" id="btnLoadPoLines" title="Reload PO Lines"></button></span></a></li>
                                    <li role="presentation"><a data-toggle="tab" href="#tabDelivered" aria-controls="tabDelivered" role="tab"><span class="text-primary">Delivered <span id="delivCount1">(0)</span></span></a></li>
                                </ul>
                            </div>
                            <div class="tab-content padding-top-20">
                                <!-- <div class="toast-danger " id="reject-message">
                                     <div class="alert alert-alt alert-danger alert-dismissible" role="alert">
                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                             <span aria-hidden="true">×</span>
                                         </button>
                                         <p>Line number <span id="rejected-line"></span> has been rejected . Please find the PO/SR number from <a class="alert-link" href="my-pending" target="_blank">here</a> and rectify that.</p>
                                     </div>
                                 </div>-->

                                <!--Deliverable PO Lines-->
                                <div class="tab-pane active" id="tabDeliverable" role="tabpanel">
                                    <table class="table table-bordered table-striped table-highlight order margin-0 small" id="dtPOLines">
                                        <thead>
                                        <tr>
                                            <th style="width:5%" class="text-center" rowspan="2">Select All</th>
                                            <th style="width:5%" class="text-center" rowspan="2">Line #</th>
                                            <th style="width:8%" class="text-center" rowspan="2">Item</th>
                                            <th style="width:17%" class="text-center" rowspan="2">Item Description</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Delivery Date</th>
                                            <th style="width:5%" class="text-center" rowspan="2">UOM</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Unit Price</th>
                                            <th style="width:10%;" class="text-center poBg" colspan="2">PO</th>
                                            <th style="width:10%;" class="text-center delivBg" colspan="2">Deliverable</th>
                                            <!--<th style="width:5%" class="text-center" rowspan="2">LD</th>-->
                                            <!--<th style="width:1%" class="text-center" rowspan="2">&nbsp;</th>-->
                                        </tr>
                                        <tr>
                                            <th style="width:10%" class="text-center poBg">Qty.</th>
                                            <th style="width:10%" class="text-center poBg">Total Price</th>
                                            <th style="width:10%" class="text-center delivBg">Qty.</th>
                                            <th style="width:10%" class="text-center delivBg">Total Price</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">
                                                                <span class="checkbox-custom checkbox-default">
                                                                    <input type="checkbox" id="chkAllLine"><label for="chkAllLine"></label>
                                                                </span>
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th><input type="text" class="form-control input-sm text-center" id="dlvQtyAll" title="Delivered Qty" /></th>
                                            <th></th>
                                            <!--                                                            <th></th>-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center"><span class="checkbox-custom checkbox-default"><input type="checkbox" class="chkLine" name="chkLine[]" id="chkLine_1"><label for="chkLine_1"></label></span></td>
                                            <td><input type="text" class="form-control input-sm text-center poLine" name="poLine[]" /></td>
                                            <td><input type="text" class="form-control input-sm poItem" name="poItem[]" /></td>
                                            <td><input type="text" class="form-control input-sm poDesc" name="poDesc[]" /></td>
                                            <td><input type="text" class="form-control input-sm poDate" name="poDate[]" /></td>
                                            <td><input type="text" class="form-control input-sm uom" name="uom[]" /></td>
                                            <td><input type="text" class="form-control input-sm text-right unitPrice" name="unitPrice[]" value="0" /></td>
                                            <td class="poBg"><input type="text" class="form-control input-sm text-right poQty " name="poQty[]" value="0" /></td>
                                            <td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal " name="lineTotal[]" value="0" readonly /></td>
                                            <td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty " name="delivQty[]" value="0" /></td>
                                            <td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal " name="delivTotal[]" value="0" readonly /></td>
                                            <!--                                                        <td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>-->
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="text-right padding-top-15">Total: </th>
                                            <th class="poBg"><input type="text" class="form-control input-sm text-right" id="poQtyTotal" readonly /></th>
                                            <th class="poBg"><input type="text" class="form-control input-sm text-right" id="grandTotal" readonly /></th>
                                            <th class="delivBg"><input type="text" class="form-control input-sm text-right" id="dlvQtyTotal" readonly /></th>
                                            <th class="delivBg"><input type="text" class="form-control input-sm text-right" id="dlvGrandTotal" readonly /></th>
                                            <!--                                                        <th></th>-->
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!--Delivered PO Lines-->
                                <div class="tab-pane" id="tabDelivered" role="tabpanel">
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
                                        <tr>
                                            <th colspan="6" class="text-right padding-top-15">Total: </th>
                                            <th class="text-center poBg" id="poQtyTotal"></th>
                                            <th class="text-right poBg" id="grandTotal"></th>
                                            <th class="text-center delivBg" id="dlvQtyTotal"></th>
                                            <th class="text-right delivBg" id="dlvGrandTotal"></th>
                                            <!--<th class="text-right" id="ldAmntTotal"></th>-->
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <?php if( $_SESSION[session_prefix.'wclogin_role']==3){?>
                                        <?php if($actionLog['ActionID']==action_New_PO_Initiated){ ?>
                                        <button type="button" class="btn btn-danger pull-left" id="btnRejectPO"><i class="icon wb-warning"></i> Reject PO</button>
                                        <?php }?>
                                        <?php if($actionLog['ActionID']>=action_Draft_PI_Submitted){ ?>
                                        <button type="button" class="btn btn-primary" id="btnSubmitPI"><i class="icon wb-check"></i> Submit PI</button>
                                        <?php } else{?>
                                        <button type="button" class="btn btn-primary" id="btnSubmitPI"><i class="icon wb-check"></i> Accept &amp; Submit PI</button>
                                        <?php }?>
                                    <?php }?>
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
<!-- End Page -->