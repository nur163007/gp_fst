<?php
$title="New Purchase Order";
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
                <form class="form-horizontal" id="form-PO-Detail" name="form-PO-Detail" method="post" autocomplete="off">
                    <input type="hidden" name="consolidatedPoLines" id="consolidatedPoLines" value="" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO No:<span class="required"> *</span></label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="poNo" id="poNo">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Description:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="poDesc" id="poDesc" readonly></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Supplier:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="supplierName" value="" placeholder="Supplier" readonly >
                                    <input type="hidden" name="supplier" id="supplier" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Value:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="poValue" id="poValue" readonly placeholder="PO Value">
                                        <div class="input-group-addon">
                                            <label id="currencyName">CUR</label>
                                            <input type="hidden" name="currency" id="currency" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Contract Ref:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="contractRefName" value="" placeholder="Contract Ref" readonly >
                                    <input type="hidden" name="contractRef" id="contractRef" value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Need by Date:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="deliveryDate" id="deliveryDate" readonly />
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
                                        <input type="text" class="form-control" data-plugin="datepicker" name="draftSendBy" id="draftSendBy" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="actualPoDate">Actual PO Date:<span class="required"> *</span></label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control required-field" name="actualPoDate" id="actualPoDate" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">PR Information</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PR NO<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="prNo" id="prNo" value="" placeholder="Enter PR no" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deptartment<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="department" id="department" value="" placeholder="Enter Department" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">User:<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="prUserName" value="" placeholder="User Name" readonly >
                                    <input type="hidden" name="prUser" id="prUser" value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">CC: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" data-plugin="select2" name="prUserCc[]" id="prUserCc" multiple="">
                                    </select>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Supplier's Information</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email To:<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly data-plugin="tokenfield" name="supplierEmailTo" id="supplierEmailTo" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email CC:<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" readonly data-plugin="tokenfield" name="supplieremailCc" id="supplieremailCc" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Address<span class="required"> *</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control"  name="supplierAddress" id="supplierAddress" placeholder="Write Address..." readonly></textarea>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="row row-lg">
                        <!--<div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Preferances</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of LC Issue:<span class="required"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="nofLcIssue" id="nofLcIssue" readonly/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Number of Shipment Allow:<span class="required"> *</span></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="nofShipAllow" id="nofShipAllow" readonly/>
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
                                </div>
                            </div>
                        </div>-->
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Message</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control isDefMessage" name="buyersMessage" id="buyersMessage" rows="8">Dear Valued Vendor,

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
                            <h4 class="well well-sm example-title">Number of PO Lines <span id="POLineCount"></span></h4>
                            <div class="col-xlg-12 col-md-12 padding-0">
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
                                    <!--<tr>
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
                                    </tr>-->
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