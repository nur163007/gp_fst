<?php
$title="New PI";
//require_once(realpath(dirname(__FILE__) . "/../config.php"));
//require_once(LIBRARY_PATH . "/dal.php");
//require_once(LIBRARY_PATH . "/lib.php");
//$actionRef = GetActionRef(0);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <ol class="breadcrumb">
            <li><a href="../index.html">General</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">New PI<br />
        </h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="po-form" name="po-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="" />
                    <input name="poid1" id="poid1" type="hidden" value="" />
                    <input name="refId" id="refId" type="hidden" value="" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="" />
                    <input name="pino" id="pino" type="hidden" value="" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO No:</label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" title="Select PO" name="selectPO" id="selectPO" >
                                    </select>
                                    <span id="poNumError" class="col-md-12 margin-top-10 small label-outline text-danger hidden"></span>
                                </div>
                                <div class="col-sm-3">
                                    <label id="lblPiNo">PI#</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Import As: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="importAs" id="importAs" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description:</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="podesc" id="podesc"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Supplier: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >                                                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Currency: </label>
                                <div class="col-sm-8">
                                    <select data-plugin="selectpicker" data-style="btn-select" name="currency" id="currency" title="Select Currency">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Value:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="povalue" id="povalue" />
                                        <div class="input-group-addon">
                                            <label id="povalueCur"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contract Ref: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" title="Select Contract Ref" name="contractref" id="contractref" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Need by Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="deliverydate" id="deliverydate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Draft PI Last Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="draftsendby" id="draftsendby" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">PR User</h4>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">To: </label>
                                <div class="col-sm-10">
                                    <!--input type="text" class="form-control" data-plugin="tokenfield" name="prEmailTo" id="prEmailTo" /-->
                                    <select class="form-control" data-plugin="select2" name="prUserEmailTo" id="prUserEmailTo" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CC: </label>
                                <div class="col-sm-10">
                                    <select class="form-control" data-plugin="select2" name="prUserEmailCC[]" id="prUserEmailCC" multiple="" >
                                    </select>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Supplier's Email</h4>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">To: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailto" id="emailto" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">CC: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailcc" id="emailcc" />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of LC Issue: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="noflcissue" id="noflcissue" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of Shipment Allow: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="nofshipallow" id="nofshipallow" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Implementation by: </label>
                                <div class="col-sm-7 margin-top-5">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="installBy_0" name="installBy" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;GP</li>
                                        <li><input type="radio" id="installBy_1" name="installBy" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                        <li><input type="radio" id="installBy_2" name="installBy" value="2" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Other</li>
                                    </ul>
                                    <!--input type="checkbox" class="icheckbox-primary" name="installbysupplier" id="installbysupplier"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" /-->
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            
                            <div id="usersAttachments">
                            </div>
                            <input type="hidden" name="attachpo" id="attachpo" value="" />
                            <input type="hidden" name="attachboq" id="attachboq" value="" />
                            <input type="hidden" name="attachother" id="attachother" value="" />
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Message
                                <span class="pull-right"><!--input type="checkbox" class="icheckbox-primary" id="messageyes" name="messageyes" /-->
                                    <input type="checkbox" class="icheckbox-primary" name="messageyes" id="messageyes"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                </span>
                            </h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control isDefMessage" name="buyersmessage" id="buyersmessage" rows="8">Dear Valued Vendor,
                                    
Please find attached PO and you are requested to acknowledge us while you receive it. And please send us relevant document (PI, BOQ, Catalogue) to proceed further.

Please Note: Final PI-BOQ-Catalog needs to be send within 5 days.</textarea>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    <hr />
                    
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="SendPO_btn">Send PO</button>
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