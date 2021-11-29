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
                <form class="form-horizontal" id="lcacceptance-form" name="po-form" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="lcno" id="lcno" type="hidden" value="" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="" />
                    <input name="shippingMode" id="shippingMode" type="hidden" value="" />
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
                            </div>                        
                            
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-6 col-md-6">
                            <div id="usersAttachments">
                    
                            </div>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>
                            <hr />
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <a href="amendment-request" class="btn btn-danger" id="ammendmentRequest_btn"><i class="icon wb-warning"></i> Request For Amendment</a>
                                    <button type="button" class="btn btn-primary" id="acceptLC_btn"><i class="icon wb-check"></i> Accept LC</button>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Approximate Shiping Document sharing Schedule(s)</h4>
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
                                            <label class="col-sm-3 control-label text-left">Shipment 1:</label>
                                            <div class="col-sm-7">
                                               <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="icon wb-calendar" aria-hidden="true"></i>
                                                    </span>
                                                    <input type="text" class="form-control" data-plugin="datepicker" id="shipmentSchedule_1" name="shipmentSchedule[]" placeholder="Approximate Schedule" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <!--button type="button" class="btn btn-sm btn-outline btn-warning" id="minusClauseRow_1"><i class="icon wb-minus"></i></button-->
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="col-sm-10">
                                    <button type="button" class="btn pull-right" id="addScheduleRow"><i class="icon wb-plus"></i> Add More Schedule</button>
                                </div>
                                
                                <div class="col-sm-10">
                                    <hr />
                                    <button type="button" class="btn btn-primary pull-right" id="scheduleSubmit_btn"><i class="icon wb-check"></i> Submit Sharing Schedule</button>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?>
                    <hr />                    
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
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