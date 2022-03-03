<?php
$title="Cover Note";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
    <!-- Page -->
    <div class="page bg-blue-100 animsition">
        <div class="page-header page-header-bordered">
            <h1 class="page-title">Cover Note Against: PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
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
                      <!--  <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                            <li role="presentation" active><a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab"><span class="text-primary">PO Detail</span></a></li>
                       </ul>-->

                        <div class="tab-content padding-top-20">

                            <div class="tab-pane active" id="tabPOInfo" role="tabpanel">
                                <div class="form-horizontal">

                                    <div class="row row-lg">
                                        <form action="">
                                            <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                            <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                            <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                                            <input name="refId1" id="refId1" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                            <div id="PO_submit_error" style="display:none;"></div>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">LC Information</h4>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PO No:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></a></b></label>
                                                </div>
                                                <label class="col-sm-4 control-label">PI no:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label"><b id="pi_num"><img src="assets/images/busy.gif" /></b></label>
                                                </div>
                                                <label class="col-sm-4 control-label">LC Bank:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label" id="insurancebank"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-4 control-label">Insurance Value:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label" id="icvalue"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-4 control-label">LC Description:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-4 control-label">Supplier:</label>
                                                <div class="col-sm-8">
                                                    <label class="control-label"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
                                                </div>
                                                <label class="col-sm-4 control-label">Shipmode:</label>
                                                 <div class="col-sm-8">
                                                  <label class="control-label"><b id="shipmode"><img src="assets/images/busy.gif" /></b></label>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-sm-12">
                                                <div id="usersAttachments">
                                                </div>
                                            </div>

                                        </div>
                                        </form>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">Cover Note Submission</h4>
                                            <div class="form-group">
                                                <form class="form-horizontal" id="form-cn-request" name="form-cn-request" method="post" autocomplete="off" >

                                                        <div class="modal-body">
                                                            <input type="hidden" id="po" name="po" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                                            <input name="refId1" id="refId1" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                                            <input name="userAction" id="userAction" type="hidden" value="" />
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">CN Number: </label>
                                                                <div class="col-sm-8">
                                                                <span data-placement="top" data-toggle="tooltip" data-original-title="">
                                                                    <input type="text" class="form-control" id="cn_number" name="cn_number" placeholder="Enter a CN number"/>
                                                                </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">CN Date:</label>
                                                                <div class="col-sm-8">
                                                                    <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                                        <input type="text" class="form-control" data-plugin="datepicker" name="cn_date" id="cn_date">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Pay Order Amount:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="pay_order_amount" name="pay_order_amount" placeholder="Enter pay order amount"/>
                                                                </div>
                                                            </div>
                                                            <!--<div class="form-group">
                                                                <label class="col-sm-4 control-label">Pay Order Charge:</label>
                                                                <div class="col-sm-8">
                                                                    <input type="text" class="form-control" id="pay_order_charge" name="pay_order_charge" placeholder="Enter pay order charge"/>
                                                                </div>
                                                            </div>-->
                                                            <div class="row row-lg">
                                                                <div class="col-xlg-12 col-md-12">
                                                                    <h4 class="well well-sm example-title">Attachments</h4>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">CN Copy:</label>
                                                                        <div class="col-sm-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" name="attachcn" id="attachcn" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                                                <input type="hidden" class="form-control" name="attachcnOld" id="attachcnOld"  />
                                                                                <span class="input-group-btn">
                                                                                    <button type="button" id="btnUploadCn" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                                                </span>
                                                                            </div>
                                                                            <span id="attachInsCoverNoteLink"></span>
                                                                        </div>

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Pay Order Receive Copy:</label>
                                                                        <div class="col-sm-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" name="attachporc" id="attachporc" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                                                <input type="hidden" class="form-control" name="attachporcOld" id="attachporcOld"  />
                                                                                <span class="input-group-btn">
                                                                                    <button type="button" id="btnUploadPorc" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                                                </span>
                                                                            </div>
                                                                            <span id="attachInsPORC"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">CN Other Docs:</label>
                                                                        <div class="col-sm-8">
                                                                            <div class="input-group">
                                                                                <input type="text" class="form-control" name="attachother" id="attachother" readonly placeholder="incase of multiple file use .zip" />
                                                                                <input type="hidden" class="form-control" name="attachotherOld" id="attachotherOld"  />
                                                                                <span class="input-group-btn">
                                                                                <button type="button" id="btnUploadOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                                                </span>
                                                                            </div>
                                                                            <span id="attachInsIOD"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="model-footer text-right">
                                                                <label class="wc-error pull-left" id="form_error"></label>
                                                                <button type="button" class="btn btn-primary" id="btnSendCNToGP" >Send Cover Note to GP</button>
                                                                <button type="button" class="btn btn-primary hidden" id="btnCloseCNRequest" >Close Request</button>
                                                                <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="CancelForm()">Cancel</button>
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


                </div>
            </div>

        </div>
    </div>
    <!-- End Page -->
    <style>
        #printFormat { margin: 0; top:0; }
        #printFormat table { border-collapse: separate; border-spacing: 3px; font-size: 80%; }
        #printFormat td{ background-color: #fff; padding: 5px; margin: 0; }
        #printFormat h3 { margin: 0; font-size: 110%;}
        .tdbox { border: 1px solid #000; }
        .tdboxc { border: 1px solid #000; text-align: center; }
    </style>
