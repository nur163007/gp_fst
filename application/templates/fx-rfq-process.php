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

                    <div class="nav-tabs-horizontal">
                        <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a data-toggle="tab" href="#tabBankOffers" aria-controls="tabBankOffers" role="tab"><span class="text-primary">Bank Offers</span></a></li>
                            <li role="presentation"><a data-toggle="tab" href="#tabMessage" aria-controls="tabMessage" role="tab"><span class="text-primary">Message</span></a></li>
                            <li role="presentation"><a data-toggle="tab" href="#tabActionLog" aria-controls="tabActionLog" role="tab"><span class="text-primary">Acton Log</span></a></li>
                        </ul>

                        <div class="tab-content padding-top-20">

                            <div class="tab-pane active" id="tabBankOffers" role="tabpanel">
                                <div class="form-horizontal">

                                    <div class="row row-lg">

                                        <div class="col-xlg-12 col-md-12">
                                            <form class="form-horizontal" id="frmDealAmount" autocomplete="off">
                                                <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                                <input type="hidden" id="hdnFxRequestId" name="hdnFxRequestId">
                                                <input type="hidden" id="postAction" name="postAction" value="1">
                                                <input type="hidden" id="userrole" name="userrole" value="<?php echo $_SESSION[session_prefix . 'wclogin_role']; ?>">
                                                <div class="row">
                                                    <div class="col-xs-12" style="margin-top: 25px;">
                                                        <table class="table" id="BankData">
                                                            <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>BankName</th>
                                                                <th>Fx Rate</th>
                                                                <th>Offer Amount</th>
                                                                <th>Value Date </th>
                                                                <th>Total Amount</th>
                                                                <th>Remarks</th>
                                                                <th>Potential Loss</th>
                                                                <th>Deal Amount</th>
                                                                <th>Select</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="BankTable">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <?php if ($_SESSION[session_prefix . 'wclogin_role'] == role_foreign_strategy) { ?>
                                                    <div class="col-xl-12">
                                                        <div class="modal-footer" style="padding: 15px">
                                                            <div class="row">
                                                                <div class="col-sm-4" style="text-align: left; padding-left: 15px;">
                                                                    <button type="button" class="btn-primary" id="btnOpenRfqforEdit" name="btnOpenRfqforEdit" style="padding: 6px 15px; border-radius:3px; border:none; font-size: 14px;">Open</button>
                                                                    <label class="text-left" style="padding: 6px 15px; font-size: 14px;"> For RFQ Modification</label>
                                                                </div>
                                                                <div class="col-sm-8" style="text-align: right; padding-right: 15px;">
                                                                    <label class="wc-error pull-center" id="form_error"></label>
                                                                    <button type="button" class="btn btn-primary" id="btnSubmitRFQtoHOT" name="btnSubmitRFQtoHOT">Submit to HOT</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } elseif ($_SESSION[session_prefix . 'wclogin_role'] == role_head_of_treasury) { ?>
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <div class="modal-footer2" style="padding: 15px">
                                                                    <div class="row">
                                                                        <div class="col-sm-8">
                                                                            <textarea class="form-control" type="text" name="reject_note" id="reject_note" rows="3" placeholder="Rejection Cause" style="width: 100%;"></textarea>
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <label class="wc-error pull-center" id="form_error"></label>
                                                                            <button type="button" class="btn btn-outline btn-danger" id="btnHOTReject" style="text-align: left"><i class="icon fa-close" aria-hidden="true"></i>Reject</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5">
                                                                <div class="modal-footer" style="padding: 15px">
                                                                    <label class="wc-error pull-center" id="form_error"></label>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btnCancel">Cancel</button>
                                                                    <button type="button" class="btn btn-outline btn-success" id="btnHOTAccept"><i class="icon fa-check-circle" aria-hidden="true"></i>Accept</button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }?>

                                                </div>
                                            </form>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane" id="tabMessage" role="tabpanel">

                                <div class="form-horizontal">
                                    <div class="row row-lg">

                                        <div class="col-xlg-12 col-md-12">
                                            <form class="form-horizontal" id="fxreqmesgfso" autocomplete="off">
                                                <input type="hidden" id="fxLastMsgId" name="fxLastMsgId">
                                                <input type="hidden" id="fxReqIdMsg" name="fxReqIdMsg">
                                                <input type="hidden" name="postAction" value="4">
                                                <input type="hidden" name="msgTitle" value="HOT Message">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="row" style="margin: 20px; height: 250px;">
                                                            <div class="col-xs-12" style=" margin-top:10px; text-align: right;">
                                                                <textarea type="text" class="form-control" id="FxConvMsg" name="FxConvMsg" placeholder="Type your Message" rows="3" maxlength="300" style="margin-top: 10px; background: white; border-radius: 15px;"></textarea>
                                                                <div style="margin-bottom: 25px;"><span id="display_count">0/300</span></div>
                                                                <?php if ($_SESSION[session_prefix.'wclogin_role']==role_foreign_strategy){ ?>
                                                                    <button type="submit" class="btn-primary" id="fsomessage" style="border: none;border-radius: 3px ;padding: 8px;line-height: 20px; margin-bottom: 20px;">Send To HOT</button>
                                                               <?php }
                                                               elseif ($_SESSION[session_prefix.'wclogin_role']==role_head_of_treasury){ ?>
                                                                   <button type="submit" class="btn-primary" id="fsomessage" style="border: none;border-radius: 3px ;padding: 8px;line-height: 20px; margin-bottom: 20px;">Send To FSO</button>
                                                               <?php  } ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <div class="example-wrap margin-bottom-0" id="exampleApi">
                                                                    <div class="example">
                                                                        <div class="height-350" id="exampleScollableApi" style=" overflow:scroll;overflow-x:hidden;max-height:350px; border-left: 2px solid #cfcfcf;">
                                                                            <div data-role="container">
                                                                                <div data-role="content" id="messageLoopView" style="padding: 20px">
                                                                                    <div style="text-align: left" >
                                                                                        <div class="list-group-item" style="padding: 10px;border-radius: 25px;border-top-right-radius: 0px;margin-bottom: 20px;">
                                                                                            <div class="media-body">
                                                                                                <h6 class="media-heading" id="title">HOT</h6>
                                                                                                <div class="media-meta">
                                                                                                    <time datetime="2015-06-17T20:22:05+08:00" id="chattime">
                                                                                                        12313
                                                                                                    </time>
                                                                                                </div>
                                                                                                <div class="media-detail" id="leftFSO" style="white-space: pre-line">
                                                                                                    sfjsfgsfsd
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div style="text-align: right;">
                                                                                        <div style="margin: 30px 0px;background-color: #62a8ea;border-radius: 25px;border-bottom-left-radius: 0px;padding: 10px;">
                                                                                            <div class="media">
                                                                                                <div class="media-body">
                                                                                                    <h6 class="media-heading" style="color: #f5ffff;">FSO</h6>
                                                                                                    <div class="media-meta">
                                                                                                        <time datetime="2015-06-17T12:30:30+08:00" style="color: #f5ffff;">
                                                                                                            15 minutes ago
                                                                                                        </time>
                                                                                                    </div>
                                                                                                    <div class="media-detail" style="color: #f5ffff;">I checheck the document. But there seems
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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

                            <div class="tab-pane" id="tabActionLog" role="tabpanel">

                                <div class="col-xlg-12 col-md-12">
                                    <div class="row" style="height: 250px;">
                                        <div class="col-xs-12">
                                            <div class="example table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Fx RequestID#</th>
                                                        <th>User Name</th>
                                                        <th>Role</th>
                                                        <th>Date</th>
                                                        <th>FXAction</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="tbapprovalLog">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- End Page -->