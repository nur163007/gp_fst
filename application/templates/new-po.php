<?php
$title="New Purchase Order";
//require_once(realpath(dirname(__FILE__) . "/../config.php"));
//require_once(LIBRARY_PATH . "/dal.php");
//require_once(LIBRARY_PATH . "/lib.php");
//$actionRef = GetActionRef(0);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">New Purchase Order<br />
        </h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="po-form" name="po-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden"
                        value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden"
                        value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden"
                        value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden"
                        value="<?php //echo $actionLog['ActionID']; ?>" />
                    <input name="pino" id="pino" type="hidden" value="PI1" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO No:</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                       <!-- <input type="text" class="form-control" name="poid1" id="poid1" value=""
                                            maxlength="9" />-->
                                        <select class="form-control" data-plugin="select2" name="poid1" id="poid1">
                                        </select>
                                        <span class="input-group-addon" id="lblPiNo">
                                            PI1
                                        </span>
                                    </div>
                                    <span id="poNumError"
                                        class="col-md-12 margin-top-10 small label-outline text-danger hidden"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Import As: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="importAs" id="importAs">
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
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Currency: </label>
                                <div class="col-sm-8">
                                    <select data-plugin="selectpicker" data-style="btn-select" name="currency"
                                        id="currency" title="Select Currency">
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
                                    <select class="form-control" data-plugin="select2" title="Select Contract Ref"
                                        name="contractref" id="contractref">
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
                                        <input type="text" class="form-control" data-plugin="datepicker"
                                            name="deliverydate" id="deliverydate" />
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
                                        <input type="text" class="form-control" data-plugin="datepicker"
                                            name="draftsendby" id="draftsendby" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="actualPoDate">Actual PO Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker"
                                            name="actualPoDate" id="actualPoDate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">PR User</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PR NO</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="prno" id="prno" value="50023382"
                                        placeholder="Enter PR no">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deptartment</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="dept" id="dept" value="HR"
                                        placeholder="Enter Department">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">To: </label>
                                <div class="col-sm-9">
                                    <!--input type="text" class="form-control" data-plugin="tokenfield" name="prEmailTo" id="prEmailTo" /-->
                                    <select class="form-control" data-plugin="select2" name="prUserEmailTo"
                                        id="prUserEmailTo">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">CC: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" data-plugin="select2" name="prUserEmailCC[]"
                                        id="prUserEmailCC" multiple="">
                                    </select>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Supplier's Email</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">To: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailto"
                                        id="emailto" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CC: </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailcc"
                                        id="emailcc" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Supplier Address</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control"  name="supplier_address" id="supplier_address"
                                        placeholder="Write Address..."></textarea>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of LC Issue: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1"
                                        name="noflcissue" id="noflcissue" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of Shipment Allow: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1"
                                        name="nofshipallow" id="nofshipallow" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Implementation by: </label>
                                <div class="col-sm-7 margin-top-5">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="installBy_0" name="installBy" value="0"
                                                data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;GP
                                        </li>
                                        <li><input type="radio" id="installBy_1" name="installBy" value="1"
                                                data-plugin="iCheck"
                                                data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                        <li><input type="radio" id="installBy_2" name="installBy" value="2"
                                                data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Other
                                        </li>
                                    </ul>
                                    <!--input type="checkbox" class="icheckbox-primary" name="installbysupplier" id="installbysupplier"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" /-->
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php if(!empty($_GET['po'])){?>
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">

                            <div id="usersAttachments">
                            </div>

                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body"
                                    style="" id="buyersmsg">
                                </div>
                            </div>

                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Comments</h4>
                            <div class="form-group">
                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body"
                                    id="suppliersmsg">
                                    <!--div class="col-sm-11 table-bordered margin-left-20 padding-20" style="white-space: pre-wrap;" id="suppliersmsg"-->
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php }?>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">PO:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachpo" id="attachpo" readonly
                                            placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadPo" class="btn btn-outline"><i
                                                    class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">BOQ:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachboq" id="attachboq" readonly
                                            placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadBoq" class="btn btn-outline"><i
                                                    class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Other:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachother" id="attachother"
                                            readonly placeholder="incase of multiple file use .zip" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadOther" class="btn btn-outline"><i
                                                    class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Message
                                <span class="pull-right">
                                    <!--input type="checkbox" class="icheckbox-primary" id="messageyes" name="messageyes" /-->
                                    <input type="checkbox" class="icheckbox-primary" name="messageyes" id="messageyes"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" />
                                </span>
                            </h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control isDefMessage" name="buyersmessage" id="buyersmessage"
                                        rows="8">Dear Valued Vendor,
                                    
Please find attached PO and you are requested to acknowledge us while you receive it. And please send us relevant document (PI, BOQ, Catalogue) to proceed further.

Please Note: Final PI-BOQ-Catalog needs to be send within 5 days.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />

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
                                                                    <input type="checkbox" id="chkAllLine" disabled><label for="chkAllLine"></label>
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
                                            <th><input type="text" class="form-control input-sm text-center" id="dlvQtyAll" title="Delivered Qty" readonly /></th>
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
                                    <button type="button" class="btn btn-primary" id="SendPO_btn">Send PO</button>
                                    <button type="button" class="btn btn-default btn-outline" id="ResetPO_btn"
                                        onclick="resetForm()">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Modal -->
                <!--div class="modal fade modal-fade-in-scale-up" id="existingPOInput" aria-hidden="true" aria-labelledby="exampleOptionalSmall" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-sm modal-center">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">�</span>
                                </button>
                                <h4 class="modal-title" id="exampleOptionalSmall">Existing PO #</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" class="form-control" id="existingPONum" placeholder="7 digit PO number..." autocomplete="off" maxlength="7" />
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default margin-top-5" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary margin-top-5" data-dismiss="modal" id="btnGenfromOld">Generates</button>
                            </div>
                        </div>
                    </div>
                </div-->
                <!-- End Modal -->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->