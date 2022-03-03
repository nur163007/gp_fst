<?php
$title="Amendment Request";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
if(!empty($_GET['ref'])){ 
    $actionLog = GetActionRef($_GET['ref']);
}

?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <h1 class="page-title">Amendment Request</h1>
        <ol class="breadcrumb">
            <li><a>Status</a></li>
            <li class="active"><?php //echo $actionLog['ActionDone']; ?></li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal hidden" id="form-temp" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">PO: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" >             
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">LC Number: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="lcList" >             
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="goAmendment_btn"><i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal" id="amendment-request-form" name="amendment-request-form" method="post" autocomplete="off">
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="lcno" id="lcno" type="hidden" value="<?php if(!empty($_GET['lc'])){ echo $_GET['lc']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="reqType" id="reqType" type="hidden" value="<?php if(!empty($_GET['req'])){ echo $_GET['req']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="amndStatus" id="amndStatus" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $actionLog['ActionID']; } ?>" />
                    <input name="lcissuerbank" id="lcissuerbank" type="hidden" value="" />
                    <input name="clausData" id="clausData" type="hidden" value="" />
                    <input name="amndId" id="amndId" type="hidden" value="" />
                    <div id="PO_submit_error" style="display:none;"></div>
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6" id="forLCOper">
                            <h4 class="well well-sm example-title">Amendment Fields</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="poNum" name="poNum" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="lcNum" name="lcNum" value="" readonly="" />
                                </div>
                            </div>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation){ ?>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Charge Borne By:</label>
                                <div class="col-sm-8">
                                    <ul class="list-unstyled list-inline margin-top-5">
                                        <li><input type="radio" id="chargeBorneBy_49" name="chargeBorneBy" value="49" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Applicant(GP)</li>
                                        <li><input type="radio" id="chargeBorneBy_50" name="chargeBorneBy" value="50" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Beneficiary</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Charge Type: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amendment Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="charge" name="charge" value="0" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="otherCharge" name="otherCharge" value="0" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">VAT Rate:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="vatRate" name="vatRate" value="15" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">VAT on Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="vatOnCharge" name="vatOnCharge" value="0" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">VAT Rebate:</label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="vatRebateRate" name="vatRebateRate" value="80" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" id="vatRebate" name="vatRebate" value="0" />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group text-right">
                                <div class="col-sm-12">
                                    <?php if ($actionLog['ActionID']> action_Amendment_Request_By_TFO) {?>
                                    <button type="button" class="btn btn-primary" id="SaveAmendmentCharge_btn"><i class="icon fa-save" aria-hidden="true"></i> Save Amendment Charge</button>
                                <?php } ?>
                                </div>
                            </div>
                            <?php }?>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){ ?>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Amendment Charge:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="bankAmendCharge" name="bankAmendCharge" placeholder="0.00" />
                                    </div>
                                </div>
                            <?php }?>

                            <span id="attachAmendmentOpenRequestLink"></span>

                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Amendment Details</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amendment No:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amendNo" name="amendNo" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amendment Reason:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amendReason" name="amendReason" value="" />
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Clauses</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table class="table table-bordered dataTable table-striped width-full" id="clauseInfoTable">
                                    </table>
                                </div>
                            </div>
                            <div class="form-group" id="clauseControl">
                               <div id="amndClauseRows">
                                    <div class="col-sm-12 clauseRow">
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label text-left">1.</label>
                                            <label class="col-sm-3 control-label text-left">Clause Number :</label>
                                            <div class="col-sm-7">
                                               <input type="text" class="form-control" id="clauseNumber_1" name="clauseNumber[]" placeholder="Clause Number" />
                                            </div>
                                            <div class="col-sm-1">
                                                <!--button type="button" class="btn btn-warning pull-right minusClauseRow" id="minusClauseRow_1"><i class="icon wb-close"></i></button-->
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label text-left">&nbsp;</label>
                                            <label class="col-sm-3 control-label text-left">Clause Title :</label>
                                            <div class="col-sm-7">
                                               <input type="text" class="form-control" id="clauseTitle_1" name="clauseTitle[]" placeholder="Clause Title" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label text-left">&nbsp;</label>
                                            <label class="col-sm-3 control-label text-left">Existing Clause :</label>
                                            <div class="col-sm-7">
                                               <input type="text" class="form-control" id="existingClause_1" name="existingClause[]" placeholder="Existing Clause" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1 control-label text-left">&nbsp;</label>
                                            <label class="col-sm-3 control-label text-left">New Clause :</label>
                                            <div class="col-sm-7">
                                               <input type="text" class="form-control" id="newClause_1" name="newClause[]" placeholder="New Clause" />
                                            </div>
                                        </div>
                                        <hr />
                                    </div>
                                </div>
                                <div class="col-sm-11">
                                    <button type="button" class="btn btn-primary pull-right" id="addClauseRow"><i class="icon wb-plus"></i> Add More Clause</button>
                                    <input type="hidden" id="clauseSl" value="1" />
                                </div>
                            </div>
                            <?php //if(in_array($_SESSION[session_prefix.'wclogin_role'],array(2,7,9,10,11))){?>
                            <!-- Panel Products Sales -->
                            <!--div class="panel" id="widgetSales">
                                <div class="panel-heading">
                                    <h4 class="well well-sm example-title">STATUS</h4>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover dataTable table-striped width-full" id="dtStatus">
                                        <thead>
                                            <tr class="nomargin ">
                                                <th>Approvers</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            <tr role="row" class="odd">
                                                <td>2nd Level LC Approver</td>
                                                <td>Approved</td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td>3rd Level LC Approver</td>
                                                <td>Approved</td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td>4th Level LC Approver</td>
                                                <td>Approved</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div-->
                            <!-- End Panel Products Sales -->
                            <?php //}?>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation && $actionLog['ActionID']< action_Amendment_Process_Done_By_Bank){?>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-success" id="btnGenerateAmndInstructionLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Generate Amendment Instruction Letter</button>
                                </div>
                            </div>


                                <h4 class="well well-sm example-title">Attachments</h4>

                                <div class="form-group">
                                    <label class="col-sm-5 control-label">Amendment Instruction Letter: </label>
                                    <div class="col-sm-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="attachAmendmentLetter" id="attachAmendmentLetter" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                            <span class="input-group-btn">
                                            <button type="button" id="btnUploadAmendmentLetter" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-info" id="btnAmendmentSendToBank"><i class="icon fa-send-o" aria-hidden="true"></i>Send to Bank</button>
                                    </div>
                                </div>

                            <?php }?>

                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation && $actionLog['ActionID']== action_Amendment_Process_Done_By_Bank){?>
                                <h4 class="well well-sm example-title">Remarks</h4>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row row-lg">
                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc && $actionLog['ActionID'] == action_Amendment_Request_By_TFO ){?>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amendment Copy: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachAmendmentCopy" id="attachAmendmentCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadAmendmentCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Advice Note: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachAdviceNote" id="attachAdviceNote" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadAdviceNote" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Remarks</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                            <hr />
                        <?php }?>

                        <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation && $actionLog['ActionID'] == action_Amendment_Request_By_TFO ){?>
                            <div class="col-xlg-6 col-md-6">
                                <h4 class="well well-sm example-title">Attachments</h4>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Amendment Copy: </label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="attachAmendmentCopy" id="attachAmendmentCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                            <span class="input-group-btn">
                                            <button type="button" id="btnUploadAmendmentCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Advice Note: </label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="attachAdviceNote" id="attachAdviceNote" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                            <span class="input-group-btn">
                                            <button type="button" id="btnUploadAdviceNote" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xlg-6 col-md-6">
                                <h4 class="well well-sm example-title">Remarks</h4>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        <?php }?>


                        <?php if(in_array($_SESSION[session_prefix.'wclogin_role'],array(role_Supplier))){?>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amendment Documents: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachAmendmentDocs" id="attachAmendmentDocs" readonly placeholder=".zip, .pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadAmendmentDocs" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>

                        <?php if(in_array($_SESSION[session_prefix.'wclogin_role'],
                            array(role_Buyer,
                                role_LC_Approvar_1,
                                role_LC_Approvar_2,
                                role_LC_Approvar_4,
                                role_LC_Approvar_5))){?>
                        <div class="col-xlg-6 col-md-6 pull-right">
                            <h4 class="well well-sm example-title">Remarks</h4>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
<!--                            <hr />-->
                        <?php }?>
                    </div>

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){?><a href="#" type="button" class="btn btn-primary" id="btnAcceptExisting">Go for Accept Existing Clause</a>
                                    <button type="button" class="btn btn-warning" id="SendAmendmentRequest_btn"><i class="icon fa-send" aria-hidden="true"></i> Send Amendment Request</button><?php }?>
                                    <?php if(in_array($_SESSION[session_prefix.'wclogin_role'],array(role_Buyer,role_LC_Approvar_1,role_LC_Approvar_2,role_LC_Approvar_4,role_LC_Approvar_5))){?><button type="button" class="btn btn-success" id="acceptRequest_btn">Accept Request</button>
                                    <button type="button" class="btn btn-danger" id="rejectRequest_btn">Reject Request</button><?php }?>
                                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_LC_Operation && $actionLog['ActionID'] == action_Amendment_Process_Done_By_Bank ){?><button type="button" class="btn btn-primary" id="SendAmendmentCopy_btn"><i class="icon fa-send" aria-hidden="true"></i> Send Amendment Copy</button>
                                    <?php }?>
                                    <?php if($_SESSION[session_prefix.'wclogin_role']==role_bank_lc){?><button type="button" class="btn btn-primary" id="SendAmendmentToTFO_btn"><i class="icon fa-send" aria-hidden="true"></i> Send Amendment Copy To TFO</button>
                                    <?php }?>
                                    <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                    <input type="hidden" id="fileName" name="fileName" />
                    <textarea id="letterContent" name="letterContent"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->