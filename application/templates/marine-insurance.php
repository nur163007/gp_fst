<?php $title="Marine Insurance Premium"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Letter of Credit</li>
        </ol>
        <h1 class="page-title">Marine Insurance</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="marine-insurance-form" name="marine-insurance-form" method="post" autocomplete="off">
                    <input type="hidden" id="pono" name="pono" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input type="hidden" id="refId" name="refId" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Endorsement</h4>
                            <input type="hidden" id="InsId" name="InsId" value="0" />
							<div class="form-group">
								<label class="col-sm-4 control-label">PO NO: </label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control" id="ponum" name="ponum" readonly="" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Bank: </label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="lcissuerbank" id="lcissuerbank" disabled="">
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Currency: </label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="selectpicker" name="currency" id="currency" disabled="">
                                    </select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-4 control-label">Insurance Company:</label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="insurance" id="insurance" disabled="">
                                    </select>
								</div>
							</div>
                            
							<div class="form-group">
								<label class="col-sm-4 control-label">Insurance value:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="insuranceValue" name="insuranceValue" readonly="" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Cover note No:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control" id="coverNoteNo" name="coverNoteNo" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Cover Note Date:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="coverNoteDate" id="coverNoteDate" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Assured Amount:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="assuredAmountCur"></label>
                                        </div>
                                        <input type="text" class="form-control curnum" id="assuredAmount" name="assuredAmount" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Exchange Rate:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="assuredAmountBDTCur">BDT</label>
                                        </div>
                                        <input type="text" class="form-control curnum" id="exchangeRate" name="exchangeRate" value="0" />
                                        <label class="wc-error"></label>
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Assured Amount BDT:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label id="assuredAmountBDTCur">BDT</label>
                                        </div>
                                        <input type="text" class="form-control curnum" id="assuredAmountBDT" name="assuredAmountBDT" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Marine:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="marine" name="marine" title="Assured Amount*0.00135" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">War:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="war" name="war" title="Assured Amount*0.0005" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Net Premium:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="netPremium" name="netPremium" title="Marine+War" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">VAT:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="vat" name="vat" title="Net Premium*0.15" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Stamp Duty:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="stampDuty" name="stampDuty" value="0" />
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Other Charges:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="otherCharges" name="otherCharges" value="0" />
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Total Charge: </label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="total" name="total" title="Net Premium+Total VAT+Stamp Charge+Other Charges" value="0" />
								</div>
							</div>
                                                      
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">VAT &amp; Charges</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Charge Type: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >   
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
								<label class="col-sm-4 control-label">Vat Rebate:</label>
								<div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="vatRebate" name="vatRebate" value="100" />
                                        <span class="input-group-addon">%</span> 
                                    </div>
								</div>
							</div>
                            <div class="form-group">
								<label class="col-sm-4 control-label">VAT Rebate Amount:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="vatRebateAmount" name="vatRebateAmount" title="Total VAT*VAT Rebate%" value="" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Capex:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="capex" name="capex" value="0" title="Total Charge-VAT Rebate Amount" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">VAT Payable:</label>
								<div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="vatPayable" name="vatPayable"  title="Total VAT-VAT Rebate Amount" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Premium Borne By:</label>
								<div class="col-sm-8">
                                    <ul class="list-unstyled list-inline ">
                                        <li><input type="radio" id="premiumBorneBy_1" name="premiumBorneBy" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-blue" checked="" />&nbsp;Applicant</li>
                                        <li><input type="radio" id="premiumBorneBy_0" name="premiumBorneBy" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Beneficiary</li>
                                    </ul>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Charge Remarks:</label>
								<div class="col-sm-8">
                                    <textarea class="form-control" name="chargeRemarks" id="chargeRemarks" cols="30" rows="3"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Insurance Service:</label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="servicePerformance" id="servicePerformance">   
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Service Remarks:</label>
								<div class="col-sm-8">
                                    <textarea class="form-control" name="serviceRemarks" id="serviceRemarks" cols="30" rows="3"></textarea>
								</div>
							</div>
                           <h4 class="well well-sm example-title">Documents Update</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Insurance Cover Note:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachInsCoverNote" id="attachInsCoverNote" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <input type="hidden" name="attachInsCoverNoteOld" id="attachInsCoverNoteOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadInsCoverNote" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachInsCoverNoteLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Pay Order Received Copy:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachPayOrderReceivedCopy" id="attachPayOrderReceivedCopy" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <input type="hidden" name="attachPayOrderReceivedCopyOld" id="attachPayOrderReceivedCopyOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadPayOrderReceivedCopy" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachPayOrderReceivedCopyLink"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other:</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachInsChargeOther" id="attachInsChargeOther" readonly placeholder="incase of multiple file use .zip" />
                                        <input type="hidden" name="attachInsChargeOtherOld" id="attachInsChargeOtherOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadInsChargeOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachInsChargeOtherLink"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                     <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="marine_ins_btn"><i class="icon fa-save"></i> Save Insurance Charge</button>
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