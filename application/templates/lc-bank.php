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
        <h1 class="page-title">LC Request Against: PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
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
                                        <input name="refId1" id="refId1" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                        <input name="userAction" id="userAction" type="hidden" value="" />
                                        <div id="PO_submit_error" style="display:none;"></div>
                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">LC Information</h4>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">PO No:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></a></b></label>
                                                </div>
                                                <label class="col-sm-5 control-label">PI no:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label"><b id="pi_num"><img src="assets/images/busy.gif" /></b></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Insurance Bank:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label" id="insurancebank"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Insurance Value:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label" id="icvalue"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">LC Description:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Supplier:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Shipmode:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label"><b id="shipmode"><img src="assets/images/busy.gif" /></b></label>
                                                </div>

                                            </div>
                                            <div class="col-md-12 col-sm-12 margin-top-50">
                                                <div id="usersAttachments">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                    <div class="col-xlg-6 col-md-6">
                                        <div class="form-group">
                                            <form class="form-horizontal" id="form-lc" name="form-lc" method="post" autocomplete="off" >

                                                    <input type="hidden" id="po" name="po" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                                    <input name="refId2" id="refId2" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                                                    <input name="actionID" id="actionID" type="hidden" value="<?php if(!empty($actionLog['ActionID'])){ echo $actionLog['ActionID']; } ?>" />
                                                    <input name="userAction1" id="userAction1" type="hidden" value="" />
                                                    <input name="userrole" id="userrole" type="hidden" value="<?php $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                                    <div class="row row-lg">

                                                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){ ?>
                                                        <div class="col-xlg-12 col-md-12">

                                                            <?php if ($actionLog['ActionID']==action_Final_LC_Request_sent_to_Bank){?>
                                                                <h4 class="well well-sm example-title" id="buyerFeedback">Buyers Feedback</h4>
                                                                <div id="buyersmsg"></div>
                                                                <h4 class="well well-sm example-title" id="supplierFeedback">Suppliers Feedback</h4>
                                                                <div id="suppliersmsg"></div>

                                                                <h4 class="well well-sm example-title">LC Information</h4>

                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">LC No:</label>
                                                                    <div class="col-sm-8">
                                                                        <input type="text" class="form-control" name="lcno" id="lcno" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">LC Date:</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                    <span class="input-group-addon">
                                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                    </span>
                                                                            <input type="text" class="form-control" data-plugin="datepicker" name="lcissuedate" id="lcissuedate" />
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">LC Expiry Date:</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                        <span class="input-group-addon">
                                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                                        </span>
                                                                            <input type="text" class="form-control" data-plugin="datepicker" name="lcexpirydate" id="lcexpirydate" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                           <?php }?>


                                                            <h4 class="well well-sm example-title">LC Attachments</h4>


                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label"><?php if ($actionLog['ActionID']==action_Draft_LC_Request_sent_to_Bank){?>Draft LC Copy <?php }
                                                                    elseif ($actionLog['ActionID']==action_Final_LC_Request_sent_to_Bank){?>Final LC Copy <?php }?>:</label>
                                                                <div class="col-sm-8">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" name="attachLC" id="attachLC" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                                        <input type="hidden" class="form-control" name="attachcnhidden" id="attachcnhidden"  />
                                                                        <span class="input-group-btn">
                                                                <button type="button" id="btnUploadLC" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                            </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <?php if ($actionLog['ActionID']==action_Draft_LC_Request_sent_to_Bank){?>
                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Bank Received Copy:</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" name="attachBRC" id="attachBRC" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                                            <input type="hidden" class="form-control" name="attachcnhiddenBRC" id="attachcnhiddenBRC"  />
                                                                            <span class="input-group-btn">
                                                                <button type="button" id="btnUploadBRC" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label class="col-sm-4 control-label">Bank Charge Advise:</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="input-group">
                                                                            <input type="text" class="form-control" name="attachBCA" id="attachBCA" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                                            <input type="hidden" class="form-control" name="attachcnhiddenBCA" id="attachcnhiddenBCA"  />
                                                                            <span class="input-group-btn">
                                                                <button type="button" id="btnUploadBCA" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }?>

                                                        </div>
                                                        <?php }
                                                        elseif ($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){?>
                                                        <div class="col-xlg-12 col-md-12">
                                                            <div id="lcAttachments"></div>
                                                            <?php if ($actionLog['ActionID']==action_Draft_LC_feedback_given_by_buyer || $actionLog['ActionID']==action_Draft_LC_feedback_given_by_supplier){?>
                                                                <h4 class="well well-sm example-title" id="buyerFeedback">Buyers Feedback</h4>
                                                                <div id="buyersmsgTFO"></div>
                                                                <h4 class="well well-sm example-title" id="supplierFeedback">Suppliers Feedback</h4>
                                                                <div id="suppliersmsgTFO"></div>
                                                           <?php }?>

                                                            </div>
                                                       <?php }
                                                       elseif($_SESSION[session_prefix.'wclogin_role']==role_Buyer || $_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>
                                                           <div class="col-xlg-12 col-md-12">
                                                               <div id="lcfeedbackAttachments"></div>
                                                               <h4 class="well well-sm example-title clearfix"> <?php if ($_SESSION[session_prefix.'wclogin_role']==role_Buyer){?>Buyers Message <?php }
                                                               elseif ($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>Suppliers Message <?php }?>
                                                               </h4>
                                                      <div class="form-group">
                                                        <div class="col-sm-12">
                                                        <textarea class="form-control isDefMessage" name="feedbackmessage" id="feedbackmessage"
                                                                  rows="8" placeholder="Write something..........." required></textarea>
                                                                       </div>
                                                                   </div>
                                                           </div>
                                                      <?php }?>
                                                    </div>

                                            </form>
                                        </div>
                                    </div>

                                </div>
                                <div class="model-footer text-right">
                                    <label class="wc-error pull-left" id="form_error"></label>
                                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){?>
                                        <button type="button" class="btn btn-primary" id="btnLCShareToTFO"><i class="icon fa-save" aria-hidden="true"></i> Share LC Copy To TFO</button>
                                    <?php }
                                    elseif ($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){
                                        if ($actionLog['ActionID']==action_Draft_LC_Copy_Sent_to_GP || $actionLog['ActionID']==action_Final_LC_Copy_Sent_to_GP ){?>
                                            <button type="button" class="btn btn-primary" id="btnLCShareBuyerSupplier"><i class="icon fa-save" aria-hidden="true"></i> Share LC Copy to Buyer & Supplier</button>
                                        <?php  }
                                        elseif ($actionLog['ActionID']==action_Draft_LC_feedback_given_by_buyer || $actionLog['ActionID']==action_Draft_LC_feedback_given_by_supplier ){?>
                                            <button type="button" class="btn btn-primary" id="btnFinalLCtoBank"><i class="icon fa-save" aria-hidden="true"></i> Buyer & Supplier's Feedback Accept</button>
                                        <?php }?>

                                    <?php }
                                    elseif ($_SESSION[session_prefix.'wclogin_role']==role_Buyer){?>
                                        <button type="button" class="btn btn-primary" id="btnLCFeedbackBuyer"><i class="icon fa-save" aria-hidden="true"></i> Send feedback to TFO</button>
                                    <?php }
                                   elseif ($_SESSION[session_prefix.'wclogin_role']==role_Supplier){ ?>
                                       <button type="button" class="btn btn-primary" id="btnLCFeedbackSupplier"><i class="icon fa-save" aria-hidden="true"></i> Send feedback to TFO</button>
                                   <?php }?>
                                     <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" ><i class="fa fa-sign-out" aria-hidden="true"></i> Close</button>
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

