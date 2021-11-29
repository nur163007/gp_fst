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
        <h1 class="page-title">FX Request # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
        <ol class="breadcrumb">
            <li><a>Status: </a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
        </ol>
        <div class="page-header-actions">
            &nbsp;
        </div>
    </div>

    <div class="page-content container-fluid">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="well well-sm example-title">Fx Request Details</h4>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="well well-sm example-title">RFQ</h4>
                    </div>
                </div>
                <form class="form-horizontal" id="rfq_form" name="rfq_form" method="post" autocomplete="off" enctype='multipart/form-data'>
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="id" id="id" type="hidden" value="" />

                    <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Request # </label>
                            <div class="col-sm-8">
<!--                                <input type="hidden" id="id" name="id" value="--><?php //echo $_GET['id'];?><!--">-->
                                <label class="control-label text-primary text-left" id="rfqId"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Supplier Name :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="supplier_id"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Nature of Service :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="nature_of_service"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Requisition Type :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="requisition_type"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Currency :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="currency"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Value :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="fx_value"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Value Date :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="value_date"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Remarks :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="remarks"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label text-right">Attachments :</label>
                            <div class="col-sm-8">
                                <label class="control-label text-left" id="attachment"><img src="assets/images/busy.gif" /></b></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-4 control-label"> Cutt-Off Time :</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
<!--                                        <span class="input-group-addon">-->
<!--                                            <i class="icon wb-calendar" id="date_picker" aria-hidden="true"></i>-->
<!--                                        </span>-->
                                        <input type="datetime-local" class="form-control" name="cutoff_date" id="cutoff_date"/>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">RFQ Banks :</label>
                                <div class="col-sm-8 padding-10 margin-left-20" id="bank_list" style="border: 1px solid #cfcfcf; width: fit-content">
                                </div>
<!--                                <div class="col-sm-2"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            <div class="model-footer text-right">
                                <label class="wc-error pull-left" id="form_error"></label>
                                <button type="button" class="btn btn-primary" id="btnFxRfqRequest" >Send RFQ</button>
<!--                                <a href="dashboard" class="btn btn-primary" id="btnFxRfqRequest">Send RFQ</a>-->
                                <a href="dashboard" class="btn btn-default btn-outline">Close</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- End Page -->