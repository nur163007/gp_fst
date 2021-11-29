<?php $title="Payment Entry"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Payment Entry</h1>
        <ol class="breadcrumb">
            <li>Finance</li>
            <li class="active">LC Operation</li>
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
                                <label class="col-sm-2 control-label">CI Number: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="ciList" >             
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="goPayment_btn"><i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal" id="payment-entry-form" name="payment-entry-form" method="post" autocomplete="off">
                    <input name="ciValue" id="ciValue" type="hidden" value="" />
                    <input name="dayOfMaturity" id="dayOfMaturity" type="hidden" value="" />
                    <!--input name="awbDate" id="awbDate" type="hidden" value="" /-->
                    <input name="lcissueDate" id="lcissueDate" type="hidden" value="" />
                    <input name="defaultDoc" id="defaultDoc" type="hidden" value="<?php if(!empty($_GET['d'])){ echo $_GET['d']; } ?>" />
                    <input name="ciNo1" id="ciNo1" type="hidden" value="<?php if(!empty($_GET['ci'])){ echo $_GET['ci']; } ?>" />
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <div class="row row-lg">
                        
                        <div class="col-xlg-12 col-md-12 margin-bottom-15">
                            <h4 class="well well-sm example-title">LC Payment Terms</h4>
                            <div class="row row-lg">
                                <div class="col-md-12">
                                    <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                    </table>
                                </div>
                                <div class="col-md-12" id="lcPaymentTermsText">
                                    
                                </div>
                            </div>                        
                        </div>
                        
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Payments<span class="pull-right" id="paid"></span></h4>
                            <input type="hidden" id="ppPercentage" name="ppPercentage" />
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" readonly="" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC No: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="LcNo" id="LcNo" >             
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Type: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="lcType" id="lcType" >             
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="lcValue" name="lcValue" readonly="" />
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-5 control-label">Invoice Number: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="GERPinvoiceNo" name="GERPinvoiceNo" />
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Issuer Bank: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="bank" id="bank" >             
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Currency: </label>
                                <div class="col-sm-8">
                                    <select data-plugin="selectpicker" data-style="btn-select" name="currency" id="currency">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI No: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="ciNo" id="ciNo" >                       
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Amount: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="ciAmount" name="ciAmount" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Document Name: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="docName" id="docName" >                                                                 
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="paymentPercent" name="paymentPercent" value="" />
                                        <span class="input-group-addon">%</span> 
                                    </div>
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-sm-5 control-label">Portion of Payment: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="paymentPercent" name="paymentPercent" value="" />
                                        <span class="input-group-addon">%</span> 
                                    </div>
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amount: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="amount" name="amount" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Exchange Rate: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="exchangeRate" name="exchangeRate" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Amount in BDT: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="payAmountBDT" name="payAmountBDT" value="0.00" readonly="" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BB Ref. No.</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="BBRefNo" name="BBRefNo" />
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BB Ref. Date:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="BBRefDate" id="BBRefDate" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Remarks</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" rows="3" id="remarks" name="remarks"></textarea>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank Notification Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="bankNotifyDate" id="bankNotifyDate" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">AWB/BL Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="awblDate" id="awblDate" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Document Receive date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="docReceiveDate" id="docReceiveDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Payment Due date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="payDueDate" id="payDueDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Payment Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="payDate" id="payDate" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Payment Maturity Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="payMatureDate" id="payMatureDate" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Fund Collected From: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="fundCollectFrom" id="fundCollectFrom" >                                                    
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">BC Selling Rate: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="bcSellingRate" name="bcSellingRate" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Maturity Payment: </label>
                                <div class="col-sm-7 padding-top-5">
                                    <input type="checkbox" class="icheckbox-primary" name="maturityPayment" id="maturityPayment" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" />
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Settlement Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="stlmntCharge" name="stlmntCharge" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" name="vatOnStlmntCharge" id="vatOnStlmntCharge" value="0.00" readonly="" />
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRate" id="vatRate" value="15" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Vat Rebate: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" name="vatRebate" id="vatRebate" value="0.00" readonly="" />
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRebateRate" id="vatRebateRate" value="80" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="bankCharge" name="bankCharge" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="totalCharge" name="totalCharge" value="0.00" readonly="" />
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div id="usersAttachments">
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Payment Advice:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachLCPaymentAdvice" id="attachLCPaymentAdvice" readonly placeholder=".pdf, .docx" />
                                        <input type="hidden" name="attachLCPaymentAdviceOld" id="attachLCPaymentAdviceOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadLCPaymentAdvice" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Acceptance Certificate:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachLCPayAcceptCertificate" id="attachLCPayAcceptCertificate" readonly placeholder=".pdf, .docx" />
                                        <input type="hidden" name="attachLCPayAcceptCertificateOld" id="attachLCPayAcceptCertificateOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadLCPayAcceptCertificate" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Payment Instruction Letter:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachPaymentInstructionLetter" id="attachPaymentInstructionLetter" readonly placeholder=".pdf, .docx" />
                                        <input type="hidden" name="attachPaymentInstructionLetterOld" id="attachPaymentInstructionLetterOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadPaymentInstructionLetter" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank Received Letter:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachBankReceivedLetter" id="attachBankReceivedLetter" readonly placeholder=".pdf, .docx" />
                                        <input type="hidden" name="attachBankReceivedLetterOld" id="attachBankReceivedLetterOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadBankReceivedLetter" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-warning" id="paymentEntryDraft_btn"><i class="fa fa-save" aria-hidden="true"></i> Save Draft Payment</button>
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <?php if(empty($_GET['d'])){ ?>
                                    <button type="button" class="btn btn-primary" id="btn_generatePaymentInstruction"><i class="icon fa-file-pdf-o" aria-hidden="true"></i> Payment Instruction Letter</button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-primary" id="paymentEntry_btn"><i class="icon wb-payment" aria-hidden="true"></i> Submit Payment</button>
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
