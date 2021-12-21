<?php
$title="Bank Charge for LC Opening";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Original Bank Document</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
            <li class="active">Shipment # <?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?></li>
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
                    <input name="userAction" id="userAction" type="hidden" value="1" />
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
                            
                            <h4 class="well well-sm example-title">Status</h4>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Previous Endorsed value:</label>
                                <div class="col-sm-6 margin-top-5">
                                    <span id="previousTotalCI"></span>
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
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-5 col-md-5">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank Notification Date with Discrepancy Status:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="bankNotifyDate" id="bankNotifyDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Discrepancy Status: </label>
                                <div class="col-sm-7 padding-top-10">
                                    <ul class="list-unstyled list-inline ">
                                        <li><input type="radio" id="chkDiscStatus_1" name="discStatus" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Yes</li>
                                        <li><input type="radio" id="chkDiscStatus_0" name="discStatus" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;No</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-7 col-md-7">                            
                            <div class="form-group">
                                <label class="col-sm-12 control-label text-left margin-bottom-15">Discrepancy Detail: </label>
								<div class="col-sm-12">
                                    <textarea class="form-control" name="discrepancyList" id="discrepancyList" rows="4" placeholder="discrepancy List" ></textarea>
								</div>
							</div>
                        </div>
                    </div>
                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){ ?>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="SendForAcceptance_btn">Send for Acceptance <i class="icon fa-arrow-right" aria-hidden="true"></i></button>
                                    <button type="button" class="btn btn-default btn-outline" id="close_btn">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? }?>
                </form>
                <hr />
                
                <div class="row row-lg">
                    
                    <div class="col-xlg-6 col-md-6">
                        <div id="usersAttachments">
                        </div>
                        <input type="hidden" id="mailAttachemnt" value="" />
                    </div>
                    
                    <form class="form-horizontal" id="EATeamFeedback-form" name="EATeamFeedback-form" method="post" autocomplete="off">
                        <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                        <div class="col-xlg-6 col-md-6">
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_External_Approval){ ?>
                            <h4 class="well well-sm example-title">EA Team Feedback</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="rejectMessage" id="rejectMessage" rows="3" placeholder="Remarks of acceptance/rejection by EA Team"></textarea>
                                </div>
                                <div class="col-sm-12 text-right margin-top-20">
                                	<button type="button" class="btn btn-danger" id="reject_btn"><i class="icon wb-warning" aria-hidden="true"></i> Reject </button>
                                    <button type="button" class="btn btn-success" id="accept_btn"><i class="icon wb-check" aria-hidden="true"></i> Accept </button>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </form>
                    
                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){ ?>
                    <form class="form-horizontal" id="originalDoc-form" name="originalDoc-form" method="post" autocomplete="off">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Attachments</h4>
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
                                <hr />
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btn_docDelivered"><i class="icon wb-file" aria-hidden="true"></i> Document Delivered</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="col-xlg-6 col-md-6">
                        <h4 class="well well-sm example-title">Sourcing Feedback</h4>
                        <div class="form-group">
                            <div class="table-bordered padding-20 comment-body" id="EAFeedback"></div>
                            <hr />
                            <div class="text-right">
                            	<!--button type="button" class="btn btn-primary" id="btn_makeSightPayment" data-target="#modalSightPayment" data-toggle="modal" ><i class="icon wb-payment" aria-hidden="true"></i> Sight Payment</button-->
                                <a class="btn btn-primary" href="#" id="btn_makeSightPayment"><i class="icon wb-payment" aria-hidden="true"></i> Sight Payment</a>
                                <button type="button" class="btn btn-primary" id="btn_ForInsPolicy"><i class="fas fa-share" aria-hidden="true"></i> Request For Insurance Policy</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <div class="form-group text-right">
                                <button type="button" class="btn btn-primary" id="btn_generateBankLetter"><i class="icon wb-file" aria-hidden="true"></i> Document Acceptance Letter</button>
                                <button type="button" class="btn btn-primary" id="btn_generatePaymentInstruction"><i class="icon fa-file-pdf-o" aria-hidden="true"></i> Payment Instruction Letter</button>
                            </div>
                        </div>
                    </div>
                    <?php }?>


                </div>

                <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                    <input type="hidden" id="fileName" name="fileName" />
                    <textarea id="letterContent" name="letterContent"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->