<?php
$title="FX RFQ Process";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <input type="hidden" id="cobankid" value="<?php echo $_SESSION[session_prefix . 'wclogin_company']; ?>" />
    <input type="hidden" id="currentRole" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
    <input type="hidden" id="currentBuyer" value="<?php echo $_SESSION[session_prefix.'wclogin_username']; ?>" />
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

        <div class="panel">

            <div class="panel-body container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="well well-sm example-title"> Fx RFQ Requests</h4>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="well well-sm example-title"> Offer amount</h4>
                    </div>
                </div>
                <form id="frmBankRate">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input type="hidden" id="hdnFxRequestId" name="hdnFxRequestId">
                    <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                            <input type="hidden" name="fxrFqRowId" id="fxrFqRowId" value="" >
                            <div class="form-group">
                                <label class="col-sm-6 control-label text-right">Fx Request ID :</label>
                                <div class="col-sm-6">
                                    <label class="control-label text-left" id="fx_req_id"><?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label text-right">Fx Value :</label>
                                <div class="col-sm-6">
                                    <label class="control-label text-left" id="fx_value"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label text-right">Value Date :</label>
                                <div class="col-sm-6">
                                    <label class="control-label text-left" id="fx_date"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label text-right">Currency :</label>
                                <div class="col-sm-6">
                                    <label class="control-label text-left" id="currency"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label text-right">Cuttsoff Time :</label>
                                <div class="col-sm-6">
                                    <label class="control-label text-left" id="cuttsofftime"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-sm-5 control-label text-right margin-top-5">Fx Rate :</label>
                                    <div class="col-sm-7 text-left margin-bottom-20">
                                        <input type="number" class="form-control" id="FxRate" name="FxRate">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label text-right margin-top-5">Offered Volume Amount :</label>
                                    <div class="col-sm-7 text-left margin-bottom-20">
                                        <input type="number" class="form-control" id="OfferedVolumeAmount" name="OfferedVolumeAmount">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-5 control-label text-right margin-top-5">Remarks :</label>
                                    <div class="col-sm-7 text-left margin-bottom-20">
                                        <input type="text" height="50px" class="form-control" id="remarks" name="remarks">
                                    </div>
                                </div>
                                <div class="row margin-right-0 text-right">
                                    <div class="col-sm-9 text-right margin-top-5">
                                        <label class="wc-error pull-center" id="form_error"></label>
                                    </div>
                                    <div class="col-sm-3 text-right">
                                        <button type="button" class="btn btn-primary" id="btnFxRfqRequest">Submit</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>