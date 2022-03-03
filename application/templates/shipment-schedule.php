
<?php
$title="New Purchase Order";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">LC Acceptance : PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
        <ol class="breadcrumb">
            <li><a>Status: </a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
        </ol>
        <div class="page-header-actions">
            &nbsp;
        </div>
    </div>
    <div class="page-content container-fluid">

        <div class="panel">

            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="shipment-schedule-form" name="shipment-schedule-form" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipNo" id="shipNo" type="hidden" value="<?php echo $actionLog['shipNo'];?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="lcno" id="lcno" type="hidden" value="" />
                    <input name="bpo" id="bpo" type="hidden" value="" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="" />
                    <input name="shippingMode" id="shippingMode" type="hidden" value="" />
                    <input name="lcshipmentType" id="lcshipmentType" type="hidden" value="" />
                    <input type="hidden" name="consolidatedPoLines" id="consolidatedPoLines" value="" />
                    <input name="piReqNo" id="piReqNo" type="hidden" value="" />
                    <div id="PO_submit_error" style="display:none;"></div>

                    <div class="row row-lg">

                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">PO No.</label>
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
                                    <label class="control-label"><b id="contractref"><img src="assets/images/busy.gif" /></b></label>
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
                                <label class="col-sm-5 control-label">Actual PO Date:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="actualPoDate"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Implementation by:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Max shipment allowed:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="nofshipallow"><img src="assets/images/busy.gif" /></b></label>
                                </div>

                                <label class="col-sm-5 control-label">Max LC will be issued:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="noflcissue"><img src="assets/images/busy.gif" /></b></label>
                                </div>

                                <label class="col-sm-5 control-label">LC No.</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="lcnum"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">LC Description:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">LC Value:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="lcvalue"><img src="assets/images/busy.gif" /></label>
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
                                <label class="col-sm-5 control-label">PI Value:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="pivalue"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">PI Description:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="pi_desc"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Product Type:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="producttype"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Import As:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="importAs"><img src="assets/images/busy.gif" /></label>
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
                                <label class="col-sm-5 control-label">LC Beneficiary:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="lcbankaddress"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Lead Time(days):</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="productiondays"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!--modal start schedule delete-->
                    <div class="modal fade modal-slide-in-top" id="scheduleDeleteForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                        <div class="modal-dialog">
<!--                            <form class="form-horizontal" id="form-schedule-delete" name="form-schedule-delete" method="post" autocomplete="off" enctype='multipart/form-data'>-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title">Shipment Schedule Delete</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Are you sure to want to delete the shipment schedule?</h5>
                                        <hr>

                                        <div class="model-footer text-right">
                                            <button type="button" class="btn btn-danger" id="btnScheduleDelete" >Delete</button>
                                            <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Cancel</button>
                                        </div>
                                    </div>
                                </div>
<!--                            </form>-->
                        </div>
                    </div>
                    <!--modal end schedule delete-->

                    <div class="col-xlg-12 col-md-12">
<!--                        --><?php //if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>

                            <div class="form-group" id="proposedShipLine">

                            </div>
<!--                        --><?php //}?>
                    </div>

                    <hr />

                    <div class="row row-lg" id="piLineHide">
                        <div class="col-xlg-12 col-md-12 margin-bottom-20 margin-top-40">
                            <h4 class="well well-sm example-title" style="background-color: #BFEDD8;">PI Lines</h4>
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
                                    </tr>
                                    <tr>
                                        <th style="width:7%" class="text-center poBg">Qty.</th>
                                        <th style="width:10%" class="text-center poBg">Total Price</th>
                                        <th style="width:7%" class="text-center delivBg">Qty.</th>
                                        <th style="width:10%" class="text-center delivBg">Total Price</th>
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
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right padding-top-15">Total: </th>
                                        <th class="poBg"><input type="text" class="form-control input-sm text-right" id="poQtyTotal" readonly /></th>
                                        <th class="poBg"><input type="text" class="form-control input-sm text-right" id="grandTotal" readonly /></th>
                                        <th class="delivBg"><input type="text" class="form-control input-sm text-right" id="dlvQtyTotal" readonly /></th>
                                        <th class="delivBg"><input type="text" class="form-control input-sm text-right" id="dlvGrandTotal" readonly /></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row row-lg" id="scheduleDate">

                        <div class="col-xlg-6 col-md-6 pull-left" >
                            <?php
                                if($actionLog['ActionID'] ==action_Shared_Shipment_Schedule){
                                    ?>
                                    <h4 class="well well-sm example-title">Approximate Shiping Document sharing Schedule(s)</h4>
                                <?php } ?>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_Buyer){?>
                                <div class="form-group" id="proposedSchedule">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered dataTable table-striped width-full" id="scheduleInfoTable">
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="comments" id="comments" cols="30" rows="3" placeholder="Buyer's Comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-danger" id="btn_RejectSchedule"><i class="icon wb-warning"></i> Reject</button>
                                        <button type="button" class="btn btn-primary" id="btn_AcceptSchedule"><i class="icon wb-check"></i> Accept</button>
                                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Cancel</a>
                                    </div>
                                </div>
                            <?php }
                            elseif($_SESSION[session_prefix.'wclogin_role']==role_Supplier){
                                if($actionLog['ActionID']==action_Rejected_Shipment_Schedule){
                                    ?>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label text-left">Buyer's last Comment:</label>
                                        <div class="col-sm-8">
                                            <label class="control-label text-left" id="buyersLastComment"><?php echo $actionLog['UserMsg']; ?></label>
                                        </div>
                                    </div>
                                    <hr />
                                <?php }?>
                                <div class="form-group" id="clauseControl">
                                    <div id="scheduleRows">
                                        <div class="col-sm-12 scheduleRow">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label text-left">Shipment No:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <select  data-plugin="select2" name="shipmentNo" id="shipmentNo" onchange="getShipmentNumber()">
                                                            <option value="">Select a shipment no</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                        </select>
                                                    </div>
                                                    <input type="hidden" id="margeShipment" name="margeShipment" value="0">
                                                    <span id="shipmentWarning" class="col-md-12 margin-top-10 small label-outline label-warning hidden" ">This Shipment no allready exists.. Are you sure to add shipment line with this shipment no?</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label text-left">Shipment Date:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" id="shipmentSchedule_1" name="shipmentSchedule" placeholder="Approximate Schedule" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <hr />
                                        <button type="button" class="btn btn-primary pull-right" id="scheduleCreate_btn"><i class="fas fa-save"></i> Create Shipment Schedule</button>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>

                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>
                        <hr />
                        <div class="row row-lg" id="shareButton">

                            <div class="col-xlg-9 col-md-9 margin-top-50">
                                <div class="form-group">
                                    <div class="col-sm-12 text-right">
                                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xlg-3 col-md-3 margin-top-50" >
                                <div class="form-group">
                                    <div class="col-sm-12 text-left">
                                        <button type="button" class="btn btn-primary pull-right" id="scheduleSubmit_btn"><i class="fas fa-share-alt"></i> Sharing Schedule to Buyer</button>
                                    </div>
                                </div>
                            </div>


                        </div>
                    <?php }?>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->