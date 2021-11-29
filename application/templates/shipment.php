<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
//echo $actionLog['1stLastAction'];
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Sharing Shipment Document</h1>
        <ol class="breadcrumb">
            <li><a>Shipment # </a></li>
            <li class="active"><?php echo $actionLog['shipNo']; ?></li>
        </ol>
		<div class="page-header-actions">
			&nbsp;
		</div>        
    </div>
    <div class="page-content container-fluid">
    
        <div class="panel">
        
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="shipment-schedule-form" name="po-form" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="lcno" id="lcno" type="hidden" value="" />
                    <input name="lcvalue" id="lcvalue" type="hidden" value="" />
                    <input name="totalShipValue" id="totalShipValue" type="hidden" value="0" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="userAction" id="userAction" type="hidden" value="" />
                    <input name="shipNo" id="shipNo" type="hidden" value="<?php echo $actionLog['shipNo'];?>" />
                    <input name="piamount" id="piamount" type="hidden" value="" />
                    <input name="lastAction" id="lastAction" type="hidden" value="<?php echo $actionLog['1stLastAction']; ?>" />
                    <input name="ciAmountError" id="ciAmountError" type="hidden" value="0" />
                    <input name="shipno1" id="shipno1" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <div id="PO_submit_error" style="display:none;"></div>
                    
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
                                    <label class="control-label text-left"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
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
                        
                        <div class="col-xlg-6 col-md-6">
                            <div id="usersAttachments" class="small">
                    
                            </div>
                        </div>
                        
                    </div>
                    
                    <hr />
                    
                    <div class="row row-lg" id="shipmentInputesRow">
                        
                        <div class="col-md-12">
                            <h4 class="well well-sm example-title">Supplier's Shipment Inputs<span class="pull-right font-size-10" id="previousTotalCI"></span></h4>
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Shipment Mode: </label>
                                <div class="col-sm-7">
                                    <ul class="list-unstyled list-inline margin-top-5 shippingmode">
                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                        <li><input type="radio" id="shipmodeE-Delivery" name="shipmode" value="E-Delivery" data-plugin="iCheck" data-radio-class="iradio_flat-orange" />&nbsp;E-Delivery</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Estimated Time of Arrival (ETA): </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="scheduleETA" id="scheduleETA" />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-5 control-label">Estimated Time of Delivery (ETD): </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="scheduleETD" id="scheduleETD" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">MAWB Number: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="mawbNo" id="mawbNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">HAWB Number: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="hawbNo" id="hawbNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">BL Number: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="blNo" id="blNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">AWB / BL Date/Delivery Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="awbOrBlDate" id="awbOrBlDate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CI Number: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ciNo" id="ciNo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CI Date: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="ciDate" id="ciDate" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CI Amount: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ciAmount" id="ciAmount" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Invoice Quantity: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="invoiceQty" id="invoiceQty" placeholder="e.g. 100 Nos, 100 Meter, 1 Lot, 100 set, etc." title="e.g. 100 Nos, 100 Meter, 1 Lot, 100 set, etc." />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">No. of Container: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="noOfcontainer" id="noOfcontainer" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total number of Boxes: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="noOfBoxes" id="noOfBoxes" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Gross Chargeable Weight: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="ChargeableWeight" id="ChargeableWeight" />
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <div class="row row-lg" id="shipDocAttachmentsRow">
                        <div class="col-xlg-6 col-md-6">
                            
                            <h4 class="well well-sm example-title">Shipping Documents Scan Copy</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">AWB / BL: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachAwbOrBlScanCopy" id="attachAwbOrBlScanCopy" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachAwbOrBlScanCopyOld" id="attachAwbOrBlScanCopyOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadAwbOrBlScanCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachAwbOrBlScanCopyLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Commercial Invoice:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachCiScanCopy" id="attachCiScanCopy" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachCiScanCopyOld" id="attachCiScanCopyOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadCiScanCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachCiScanCopyLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Packing List: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachPackListScanCopy" id="attachPackListScanCopy" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachPackListScanCopyOld" id="attachPackListScanCopyOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadPackListScanCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachPackListScanCopyLink"></span>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Other Documents</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Certificate of Origin: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachOriginCertificate" id="attachOriginCertificate" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachOriginCertificateOld" id="attachOriginCertificateOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadOriginCertificate" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachOriginCertificateLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Freight Certificate:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachFreightCertificate" id="attachFreightCertificate" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachFreightCertificateOld" id="attachFreightCertificateOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadFreightCertificate" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachFreightCertificateLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Other:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachShipmentOther" id="attachShipmentOther" readonly placeholder="incase of multiple file use .zip" />
                                        <input type="hidden" name="attachShipmentOtherOld" id="attachShipmentOtherOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadShipmentOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachShipmentOtherLink"></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <hr />
                    
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">DHL Tracking NO: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="dhlTrackNo" id="dhlTrackNo" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" id="dhlTrackNoUpdate_btn" title="Share DHL Tracking Number"><i class="icon fa-share" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-success pull-right" id="SendShipDoctoGp_btn"><i class="icon fa-send" aria-hidden="true"></i> Send Shipment Doc to GP</button>
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