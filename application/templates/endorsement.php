<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Endorse Doc.</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li>Shipment # <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="endorsement-form" name="endorsement-form" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="endorseNo" id="endorseNo" type="hidden" value="<?php echo $actionLog['LastEndorseNo']; ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php echo $actionLog['shipNo']; ?>" />
                    <!--<input name="lcissuerbank" id="lcissuerbank" type="hidden" value="" />-->
                    <input name="hawbNo" id="hawbNo" type="hidden" value="" />
                    <div class="row row-lg" id="shipInfo">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Shipment Information</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="pono1" name="pono1" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="lcno" name="lcno" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Value: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon curname"></span>
                                        <input type="text" class="form-control curnum" id="LcValue" name="LcValue" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Issuing Bank:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="lcissuerbank" id="lcissuerbank" disabled="">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="ciNo" name="ciNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Value: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon curname"></span>
                                        <input type="text" class="form-control curnum" id="ciValue" name="ciValue" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="ciDate" id="ciDate" />
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">GERP Voucher No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="gerpVNo" name="gerpVNo" />
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-4 control-label">GERP Invoice No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="gerpInvNo" name="gerpInvNo" />
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Shipping Mode:</label>
                                <div class="col-sm-8">
                                    <ul class="list-unstyled list-inline margin-top-5 shippingmode">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AWB / BL Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="awbOrBlDate" id="awbOrBlDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">MAWB Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="mawbNo" name="mawbNo" />
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-4 control-label">HAWB Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="hawbNo" name="hawbNo" />
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BL Number:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="blNo" name="blNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" id="insurance1">Insurance: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="insurance" id="insurance" disabled="" >                                                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cover Note No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="coverNoteNo" name="coverNoteNo" readonly="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Shipping Documents
                                <span class="pull-right" style="margin-top: -7px;">
                                    
                                </span>
                            </h4>
                            <div id="usersAttachments" class="small">
                            </div>
                            <input type="hidden" id="mailAttachemnt" value="" />
                            
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <!--h4 class="well well-sm example-title">Shipping Mode</h4>
                            <div class="form-group" id="shippingMode">
                                <label class="col-sm-6 control-label">&nbsp;</label>
                                <div class="col-sm-6">
                                    <ul class="list-unstyled list-inline margin-top-5 shippingmode">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                    </ul>
                                </div>
                            </div-->
                            <h4 class="well well-sm example-title">Status</h4>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Previous Endorsed value:</label>
                                <div class="col-sm-6 margin-top-5">
                                    <span id="previousTotalCI"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Original Document Delivered: </label>
                                <div class="col-sm-6 margin-top-5">
                                    <input type="checkbox" class="icheckbox-primary" id="docDelivered" name="docDelivered"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" />
                                </div>
                            </div>
                            <hr/>
                            <div class="text-right">
                                <button type="button" class="btn btn-warning" id="originalDocProcess_btn"><i class="icon wb-arrow-right" aria-hidden="true"></i> Go for Original Doc. Process</button>
                                <button type="button" class="btn btn-success" id="docDelivered_btn"><i class="icon wb-add-file" aria-hidden="true"></i> Document Delivered</button>
                            </div>
                        </div>
                    </div>
                    &nbsp;
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Finance Input</h4>
                        </div>    
                        
                        <div class="col-xlg-6 col-md-6">
                            
                            <!--div class="form-group">
                                <label class="col-sm-4 control-label">Policy No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="policyNum" id="policyNum" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Policy Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="policyValue" id="policyValue" />
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Endorsement Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="endDate" id="endDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Endorsement Charge: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">BDT</span>
                                        <input type="text" class="form-control curnum" id="endCharge" name="endCharge" value="0.00" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">VAT on Charge: </label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">BDT</span>
                                        <input type="text" class="form-control curnum" name="vatOnCharge" id="vatOnCharge" value="0.00" readonly="" />
                                    </div>                                    
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRate" id="vatRate" value="15" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Charge Type: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >                                                                        
                                    </select>
                                </div>
                            </div>
                        
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                        
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Endorsement Copy: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachEndorsementCopy" id="attachEndorsementCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadEndorsementCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Endorsement Advice: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachEndorsementAdvice" id="attachEndorsementAdvice" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadEndorsementAdvice" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other Documents: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachEndorsementOtherDoc" id="attachEndorsementOtherDoc" readonly placeholder="incase of multiple file use .zip" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadOtherDoc" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="text-right">
                                <button type="button" class="btn btn-primary pull right" id="btn_save_endorsement"><i class="icon fa-save" aria-hidden="true"></i> Save Endorsement</button>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-success" id="btn_generateEndorsement"><i class="icon fa-file-word-o" aria-hidden="true"></i> Endorsement Letter</button>
                                    <button type="button" class="btn btn-success" id="btn_mailForInsPolicy"><i class="icon wb-envelope" aria-hidden="true"></i> Mail for Policy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--form name="zips" action="application/library/zipDownloader.php" method="post">
                                        <input type="submit" id="submit" name="createzip" value="Download All Seleted Files" >
                                        <input type="hidden" id="zipAttachemnt" value="" />
                                    </form-->
                <form name="zips" action="application/library/zipDownloader.php" method="post">
                    <div id="filesToZip"></div>
                    <input type="hidden" name="docName" value="shipment_docs">
                    <button type="submit" id="submit" name="createzip" class="btn btn-primary" ><i class="icon fa-download" aria-hidden="true"></i> Download All Shipment Documents</button>
                </form>

                <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                    <input type="hidden" id="fileName" name="fileName" />
                    <textarea id="letterContent" name="letterContent"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
