<?php
$title = "Delivery Notification Form";
if(isset($_GET['ref'])) {
    require_once(LIBRARY_PATH . "/dal.php");
    require_once(LIBRARY_PATH . "/lib.php");
    $actionRef = GetActionRef($_GET['ref']);
}
?>

<style>
    [class^="icheckbox_"] + label, [class^="iradio_"] + label {
        margin: 0;
    }
    .mandatory{
        border: 1px solid #FF0000;
    }
    #invoice-info label {
        font-size: 12px;
    }
</style>

<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Sourcing</a></li>
            <li class="active">Service Receiving</li>
        </ol>
        <?php if(isset($_GET['ref'])) { ?>
            <h1 class="page-title"><?php echo $actionRef['stage']; ?></h1>
            <ol class="breadcrumb small">
                <li><a>Status:</a></li>
                <li class="active"><?php echo $actionRef['ActionDone']; ?></li>
                <li><a>Pending for:</a></li>
                <li class="active"><?php echo $actionRef['ActionPending']; ?></li>
            </ol>
            <div class="page-header-actions text-right">
                <h4>Request ID: <?php echo 'SR'.str_pad($actionRef['requestId'], 5, '0', STR_PAD_LEFT); ?></h4>
                <ol class="breadcrumb small">
                    <li><a>PO #</a></li>
                    <li class="active"><?php echo $_GET['po']; ?></li>
                    <li><a>Delivery #</a></li>
                    <li class="active"><?php echo $_GET['ship']; ?></li>
                </ol>
            </div>
        <?php } else { ?>
            <h1 class="page-title">Delivery Notification</h1>
        <?php } ?>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">

                <div class="nav-tabs-horizontal">

                    <div class="tab-content padding-top-20">

                        <div class="tab-pane active" id="tabPOInfo" role="tabpanel">

                            <form class="form-horizontal" id="form_delivery_notice" name="form_delivery_notice" method="post" autocomplete="off">
                                <input type="text" id="refId" name="refId" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                <input name="ponum" id="ponum" type="text" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                <input name="ship" id="ship" type="text" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                                <input name="usertype" id="usertype" type="text" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                <input name="supplierId" id="supplierId" type="text" value="<?php echo $_SESSION[session_prefix.'wclogin_userid']; ?>" />
<!--                                <input name="companyId" id="companyId" type="text" value="--><?php //echo $_SESSION[session_prefix.'wclogin_companyId']; ?><!--" />-->
                                <input type="text" class="form-control" name="supplierLoginID" id="supplierLoginID" value="<?php echo $_SESSION[session_prefix.'wclogin_username']; ?>" readonly />
                                <input name="reqId" id="reqId" type="text" value="<?php if(isset($_GET['ref'])){ echo $actionRef['requestId'];} ?>" />
                                <input name="draftId" id="draftId" type="text" value="<?php if(isset($_GET['draft'])){ echo $_GET["draft"];} ?>" />
                                <input type="text" name="currency" id="currency" value="BDT" />
                                <input type="text" name="consolidatedPoLines" id="consolidatedPoLines" value="" />

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
                                                        <td><input type="text" class="form-control input-sm projCode" name="projCode[]" /></td>
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
                                           <!-- <div class="col-sm-6">
                                                <label class="control-label">Supplier's Remarks (if any):</label>
                                                <textarea class="form-control" name="suppliersRemarks" id="suppliersRemarks" rows="3" placeholder="remarks..."></textarea>
                                            </div>-->
                                            <div class="col-sm-12 text-right">
                                                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checklistModal" onclick="validateSubmit()">Submit</button>-->
                                                <button type="button" class="btn btn-primary" id="modalRequest">Submit</button>
                                                <?php if(!isset($_GET['ref'])){ ?>
                                                    <button type="button" class="btn btn-info" id="btnSubmitRequestDraft">Save as Draft</button>
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
        </div>
    </div>

</div>