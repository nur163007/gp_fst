<?php
$title="Supplier's PI";

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

if(isset($_GET['ref'])){
    echo 'testqq';
    $actionLog = GetActionRef($_GET['ref']);
    $actionId = $actionLog['ActionID'];
} else {
    $actionLog['ActionID'] = 0;
    $actionId = '';
}

?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">New PI</li>
        </ol>
        <h1 class="page-title">Supplier's PI</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal hidden" id="formPOSelection" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Select PO Number: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" >
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="btnLoadPO"><i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal" id="formSuppliersPi" name="formSuppliersPi" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionId; ?>" />
                    <input name="pov" id="pov" type="hidden" value="" />
                    <input name="piReqNo" id="piReqNo" type="hidden" value="" />
                    <input name="validPIVal" id="validPIVal" type="hidden" value="" />
                    <input type="hidden" name="consolidatedPoLines" id="consolidatedPoLines" value="" />

                    <!--PO Information Start-->
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">PO Information
                                <span class="pull-right" style="margin-top: -7px;">
                                    <button type="button" class="btn btn-sm btn-flat btn-default blue-800" id="btnDownloadPO"><i class="icon wb-download" aria-hidden="true"></i> Download PO Copy</button>
                                </span>
                            </h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="stPOInfo">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">PO No:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left"><b id="ponum"><img src="assets/images/busy.gif" /></b></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">PO Value:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="povalue"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">PO Description:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Supplier:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="supplier"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Supplier Address:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="sup_address"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Contract Ref:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="contractref"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Buyer's Contact:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Technical Contact:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="stPOInfo">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">PR No:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="pr_no"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Department:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="department"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Need by Date:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Implementation by:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="installbysupplier"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Max. shipment allowed:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="noflcissue"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Max. LC will be issued:</label>
                                    <div class="col-sm-7">
                                        <label class="control-label text-left" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--PO Information End-->

                    <!--Start PO Lines-->
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12 margin-bottom-20">
                            <h4 class="well well-sm example-title">PO Lines</h4>
                            <div class="example-wrap margin-lg-0">
                                <div class="nav-tabs-horizontal">
                                    <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                        <li role="presentation" id="deliverableTab">
                                            <a data-toggle="tab" href="#tabDeliverable" aria-controls="tabDeliverable" role="tab">Deliverable <text id="delivCount2">(0)</text></a></li>
                                        <li role="presentation" id="deliveredTtab">
                                            <a data-toggle="tab" href="#tabDelivered" aria-controls="tabDelivered" role="tab">Deliver In Progress <text id="delivCount1">(0)</text></a></li>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content padding-top-20">
                                <!--Non-Delivered PO Lines-->
                                <div class="tab-pane active" id="tabDeliverable" role="tabpanel">
                                    <table class="table table-bordered table-striped table-highlight order margin-0 small" id="dtPOLines">
                                        <thead>
                                        <tr>
                                            <th style="width:5%" class="text-center" rowspan="2">
                                                <span class="checkbox-custom checkbox-default">
                                                    <input type="checkbox" id="chkAllLine"><label for="chkAllLine"></label>
                                                </span>
                                            </th>
                                            <th style="width:5%" class="text-center" rowspan="2">Line #</th>
                                            <th style="width:8%" class="text-center" rowspan="2">Item</th>
                                            <th style="width:20%" class="text-center" rowspan="2">Item Description</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Delivery Date</th>
                                            <th style="width:5%" class="text-center" rowspan="2">UOM</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Unit Price</th>
                                            <th style="width:7%;" class="text-center poBg" colspan="2">PO</th>
                                            <th style="width:10%;" class="text-center delivBg" colspan="2">Deliverable</th>
                                            <!--<th style="width:5%" class="text-center" rowspan="2">LD</th>-->
                                            <!--<th style="width:1%" class="text-center" rowspan="2">&nbsp;</th>-->
                                        </tr>
                                        <tr>
                                            <th style="width:7%" class="text-center poBg">Qty.</th>
                                            <th style="width:10%" class="text-center poBg">Total Price</th>
                                            <th style="width:7%" class="text-center delivBg">Qty.</th>
                                            <th style="width:10%" class="text-center delivBg">Total Price</th>
                                        </tr>
                                        <!--<tr>
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
                                            <th colspan="6" class="text-right">Total: </th>
                                            <th class="text-center poBg" id="otherpoQtyTotal"></th>
                                            <th class="text-right poBg" id="othergrandTotal"></th>
                                            <th class="text-center delivBg" id="otherdlvQtyTotal"></th>
                                            <th class="text-right delivBg" id="otherdlvGrandTotal"></th>
                                            <!--<th class="text-right" id="ldAmntTotal"></th>-->
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!--End PO Lines-->

                    <!--Start PI Inputs-->
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">PI Inputs
                                <span class="pull-right" style="margin-top: 0;">
                                    PI Request No. <label style="font-weight: bold" id="piReqNoText">0</label>
                                </span>
                            </h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="pinum" id="pinum" placeholder="PI Number" autocomplete="off" maxlength="50" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Value: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pivalue" id="pivalue" readonly autocomplete="off" />
                                        <div class="input-group-addon">
                                            <label id="pivalueCur">CUR</label>
                                        </div>
                                    </div>
                                    <span id="piValueWarning" class="col-md-12 margin-top-10 small label-outline label-warning hidden">PI value less then PO value means multiple PI? Are you sure? Please consult with buyer.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Description: </label>
                                <div class="col-sm-7">
                                    <textarea type="text" class="form-control" name="pi_description" id="pi_description" placeholder="Write Something..."></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Product Type:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="producttype" id="producttype">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Import As: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="importAs" id="importAs">
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
                                    <!--input type="checkbox" class="icheckbox-primary" name="installbysupplier" id="installbysupplier"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" checked="" /-->
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="hscode" id="hscode" autocomplete="off" maxlength="200" />
                                </div>
                            </div>
                            <?php if($actionId>=action_Requested_for_Draft_PI_Rectification){?>
                                <!--This fields required for Final PI-->
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">PI Date: </label>
                                    <div class="col-sm-7">
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
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="basevalue" id="basevalue" autocomplete="off" />
                                        <span class="comment-meta">(value without discount)</span>
                                    </div>
                                </div>
                            <?php }?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country of Origin: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" multiple="" name="origin[]" id="origin">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Negotiating Bank &amp Address:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="negobank" id="negobank" autocomplete="off" maxlength="300" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Port of Shipment: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="shipport" id="shipport" autocomplete="off" maxlength="100" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">L/C Beneficiary &amp Address:</label>
                                <div class="col-sm-7">
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
                        </div>
                    </div>
                    <!--EndPI Inputs-->

                    <!--Start Documents & Comments-->
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div id="usersAttachments"></div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">Buyer's Message</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="buyersmsg"></div>
                            </div>
                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Message</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="suppliersmsg">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!--End Documents & Comments-->

                    <!--Start Supplier's Attachment-->
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Required Documents</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <?php if($actionLog['ActionID'] < action_Draft_PI_Submitted){ ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Buyer's PO:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachpo" id="attachpo" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadPo" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Buyer's BOQ:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachboq" id="attachboq" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadBoq" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other (If any):</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachother" id="attachother" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <?php if($actionLog['ActionID'] < action_Draft_PI_Submitted){ ?>
                                <label class="col-sm-4 control-label">Draft PI:</label>
                                <?php } else {?>
                                <label class="col-sm-4 control-label">Final PI:</label>
                                <?php }?>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachDraftPI" id="attachDraftPI" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadDraftPI" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Supplier's BOQ:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachDraftBOQ" id="attachDraftBOQ" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadDraftBOQ" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Supplier's Catalog:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachCatelog" id="attachCatelog" readonly placeholder=".pdf" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadCatelog" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Supplier's Attachment-->

                    <hr>
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">&nbsp;</div>
                        <div class="col-xlg-6 col-md-6">
                            <!--<h4 class="well well-sm example-title clearfix">Remarks</h4>-->
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control isDefMessage" name="suppliersmessage" id="suppliersmessage" rows="4" placeholder="If you have any remarks please write here..."></textarea>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-xlg-6 col-md-6">

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
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" style="white-space: pre-wrap;" id="suppliersmsg">
                                </div>
                            </div>
                            
                        </div>-->

                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnSubmitPI"><i class="icon wb-check"></i> Submit PI</button>
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