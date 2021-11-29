<?php $title="Bank Charge for LC Opening"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">LC Opening Bank Charge</h1>
        <ol class="breadcrumb">
            <li>PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lcOBC-form" name="lcOBC-form" method="post" autocomplete="off">
                    <input type="hidden" id="lcNumber" name="lcNumber" value="<?php if(!empty($_GET['lc'])){ echo $_GET['lc']; } ?>" />
                    <input type="hidden" id="pono" name="pono" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input type="hidden" id="refId" name="refId" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input type="hidden" id="bocId" name="bocId" value="" />
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <h4 class="well well-sm example-title">Charges &amp; VAT</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC No:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" name="LcNo" id="LcNo" readonly="" />
                                    <!--select class="form-control" data-plugin="select2" name="LcNo" id="LcNo" >
                                    </select-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Issuing Bank:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="LcIssuingBank" id="LcIssuingBank" disabled="">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Charge Type: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >   
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
								<label class="col-sm-5 control-label">LC Date:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="LcDate" id="LcDate" readonly="" />
                                    </div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Currency:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="selectpicker" name="currency" id="currency" title="select currency" disabled="">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Charge Bearer: </label>
                                <div class="col-sm-7 padding-top-10">
                                    <ul class="list-unstyled list-inline ">
                                        <li><input type="radio" id="applicant" name="chargeBearer" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-blue" checked />&nbsp;Applicant</li>
                                        <li><input type="radio" id="benificiary" name="chargeBearer" value="2" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Benificiary</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Value:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="lcvalueCur"></label>
                                        </div>
                                        <input type="text" class="form-control curnum" id="LcValue" name="LcValue" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Comission Rate:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">&nbsp;&nbsp;%&nbsp;&nbsp;</span>
                                        <input type="text" class="form-control curnum" name="LcCommissionRate" id="LcCommissionRate" value="0.1" />
                                    </div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Comission:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="commissionCur"></label>
                                        </div>
                                        <input type="text" class="form-control curnum" name="commission" id="commission" value="0.00" readonly="" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Exchange Rate:</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="exchangeRate" id="exchangeRate" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Comission BDT:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="comissionBDTCur">BDT</label>
                                        </div>
                                        <input type="text" class="form-control curnum" name="comissionBDT" id="comissionBDT" value="0.00" readonly="" />
                                    </div>                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Cable Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="cableCharge" id="cableCharge" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Other Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="otherCharge" id="otherCharge" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Non VAT Other Charges: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="nonVAtOtherCharge" id="nonVAtOtherCharge" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Comission Additional VAT: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="lcCommAddVAT" id="lcCommAddVAT" value="0.00" />
                                </div>
                            </div>
                        </div>
                        <!--Right-->
                        <div class="col-xlg-6 col-md-6">
                            
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT on Comission: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="vatOnComm" id="vatOnComm" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT on Other Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="vatOnOtherCharge" id="vatOnOtherCharge" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total VAT: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="totalVAT" id="totalVAT" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="totalCharge" id="totalCharge" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT Rebate on LC Comission: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" name="vatRebateOnLcComm" id="vatRebateOnLcComm" value="0.00" readonly="" />
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRebateOnLcCommRate" id="vatRebateOnLcCommRate" value="100" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT Rebate on Other Charges: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" name="vatRebateOnOtherCharges" id="vatRebateOnOtherCharges" value="0.00" readonly="" />
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRebateOnOtherChargesRate" id="vatRebateOnOtherChargesRate" value="100" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Rebate: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="totalRebate" id="totalRebate" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Capex: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="capex" id="capex" value="0.00" readonly="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6"> 
                            <h4 class="well well-sm example-title">Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Bank Charge Advice:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachBankCharge" id="attachBankCharge" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <input type="hidden" name="attachBankChargeOld" id="attachBankChargeOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadBankCharge" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachBankChargeLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Pay Order Issue Charge:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachIssueCharge" id="attachIssueCharge" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <input type="hidden" name="attachIssueChargeOld" id="attachIssueChargeOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadIssueCharge" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachIssueChargeLink"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title clearfix">Pay Order Charges</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Pay Order Issue Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="payOrderIssueCharge" id="payOrderIssueCharge" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">VAT On Pay order Issue Charge: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="vatPayOrderIssueCharge" id="vatPayOrderIssueCharge" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Vat Rebate: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control curnum" name="vatRebateOnPayOrderCharge" id="vatRebateOnPayOrderCharge" value="0.00" readonly="" />
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" name="vatRebateOnPayOrderChargeRate" id="vatRebateOnPayOrderChargeRate" value="80" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Total Charge(Pay Order): </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" name="totalChargePayOrder" id="totalChargePayOrder" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Charge Type: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="payOrderChargeType" id="payOrderChargeType" >   
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="SaveBankCharge_btn"><i class="icon fa-save" aria-hidden="true"></i> Save Opening Charge</button>
                                    <a href="#" class="btn btn-default btn-outline" id="close_btn">Close</a>
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