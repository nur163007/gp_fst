<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
$title = "Received Document Information"; ?>


<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Received Document Information</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li>Shipment # <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
            <li class="active">Shipment Mode: <span id="shipmode" class="text-info"></span></li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="originaldoc-form" name="originaldoc-form" method="post" autocomplete="off">
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input type="hidden" id="poNumber" name="poNumber" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="LcNo1" id="LcNo1" type="hidden" value="" />
                    <input name="actionId" id="actionId" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="userAction1" id="userAction1" type="hidden" value="6" />
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <h4 class="well well-sm example-title">Shipment & LC Information</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Po No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="PoNo" id="PoNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC No:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="LcNo" id="LcNo" disabled="" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="LcDate" id="LcDate" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Value:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="LcValue" id="LcValue" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="lcvalueCur"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Issuing Bank:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="LcIssuingBank" id="LcIssuingBank" disabled="">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Insurance Company:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="insurance" id="insurance" disabled="">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cover Note No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="coverNoteNo" id="coverNoteNo" readonly="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-5 col-md-5">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Voucher No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="voucherNo" id="voucherNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Voucher Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="voucherDate" id="voucherDate" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Value:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="ciValue" id="ciValue" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="lcvalueCur1"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="CiNo" id="CiNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AWB / BL No.:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="awblNo" id="awblNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AWB / BL Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="awblDate" id="awblDate" readonly="" />
                                    </div>
                                </div>
                            </div>

                            <div id="usersAttachments">
                            </div>
                        </div>
                    </div>
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <h4 class="well well-sm example-title">Document Feedback</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Discrepancy Status: </label>
                                <div class="col-sm-8 padding-top-10">
                                    <ul class="list-unstyled list-inline ">
                                        <li><input type="radio" id="chkDiscStatus_1" name="discStatus" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Yes</li>
                                        <li><input type="radio" id="chkDiscStatus_0" name="discStatus" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;No</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Original Document:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachOriginalDoc" id="attachOriginalDoc" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachOriginalDocOld" id="attachOriginalDocOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadOriginalDoc" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label text-left margin-bottom-15">Discrepancy Detail: </label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="discrepancyList" id="discrepancyList" rows="4" placeholder="discrepancy List" ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnSendDocReceiptNotification">Send for Document Receipt Notification <i class="icon fa-arrow-right" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-default btn-outline" id="close_btn">Close</button>
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
?>
