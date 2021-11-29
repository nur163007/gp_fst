<?php
$title="Final PI BOQ Catalog"; 

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$postatus = POStatus($_GET['po']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <ol class="breadcrumb">
            <li><a href="../index.html">General</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">Final PI BOQ Catalog</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="finalpiboq-form" name="finalpiboq-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="" />
                    <input name="userAction" id="userAction" type="hidden" value="" />
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
                            
                            <h4 class="well well-sm example-title">PI Information</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pinum" id="pinum" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="pivalue" id="pivalue" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Shipment Mode: </label>
                                <div class="col-sm-8">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                        <li><input type="radio" id="shipmodesea+air" name="shipmode" value="sea+air" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Air</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes Sea: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="hscsea" id="hscsea" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes Air: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="hscode" id="hscode" autocomplete="off" />
                                </div>
                            </div>
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
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country of Origin: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" multiple="" name="origin[]" id="origin">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Negotiating Bank: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="negobank" id="negobank" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Port of Shipment: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="shipport" id="shipport" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">L/C Beneficiary &amp Address:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="lcbankaddress" id="lcbankaddress" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Production Days:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="productiondays" id="productiondays" autocomplete="off" />
                                </div>
                            </div>
                            <div class="form-group">
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
                            </div>
                            <!--div class="form-group">
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
                                <div id="shipHSCsea">
                                    <label class="col-sm-5 control-label">HS Codes Sea:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="hscsea"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div id="shiphscode">
                                    <label class="col-sm-5 control-label">HS Codes Air:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
                                    </div>
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
                            </div-->
                            
                            <h4 class="well well-sm example-title">Supplier's Previous Attachments</h4>
                            <div class="form-group" id="suppliersAttachments">
                                <label class="col-sm-3 control-label">Draft PI:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-3 control-label">Draft BOQ:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><a href="#"><img src="assets/images/busy.gif" /></a></label>
                                </div>
                                <label class="col-sm-3 control-label">Catalog:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Buyer's Attachments</h4>
                            <div class="form-group" id="buyersAttachment">
                                <label class="col-sm-3 control-label">PO: </label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-3 control-label">BOQ: </label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-3 control-label">Other Docs: </label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            
                            <h4 class="well well-sm example-title">PR Attachment</h4>
                            <div class="form-group" id="prAttachment">
                                <label class="col-sm-3 control-label">Justification:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><i class="icon wb-file"></i><img src="assets/images/busy.gif" /></label>
                                </div>
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
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <?php //if($_SESSION[session_prefix.'wclogin_role']==14){ ?>
                        <div class="col-xlg-6 col-md-6" id="newSuppliersAttachments">
                            <h4 class="well well-sm example-title">Final Version Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Final PI:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachFinalPI" id="attachFinalPI" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadFinalPI" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Final BOQ:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachFinalBOQ" id="attachFinalBOQ" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadFinalBOQ" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Final Catalog:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachFinalCatelog" id="attachFinalCatelog" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadFinalCatelog" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php //}?>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">New Comment
                                <span class="pull-right"><input type="checkbox" class="icheckbox-primary" id="messageUserYes" name="messageUserYes" checked="" /></span>
                            </h4>
                            <div class="form-group isMessageUser">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="messageUser" id="messageUser" rows="4" placeholder="New Comment">We are submitting the final PI with final version of attachments for your further observation.

Thanks.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="finalPIToBuyer_btn">Submit Final-PI-BOQ-Catalog</button>
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