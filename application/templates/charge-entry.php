<?php $title="Various Charge Entry";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header">
		<ol class="breadcrumb">
			<li><a href="dashboard">Finance</a></li>
			<li class="active">LC Operation</li>
		</ol>
		<h1 class="page-title">Various Charge Entries</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#popChargeEntry" 
				data-toggle="modal" data-toggle="Add New Charge" data-original-title="Add New Charge">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Charge</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="popChargeEntry" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog">
				<form class="form-horizontal" id="formChargeEntry" name="formChargeEntry" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Charge Entry Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="chargeId" name="chargeId" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Date: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="chargeDate" id="chargeDate" />
                                    </div>
                                </div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Type: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >
                                    </select>
                                </div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Amount: </label>
								<div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tk.</span>
                                        <input type="text" class="form-control curnum" id="amount" name="amount" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">VAT: </label>
								<div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="vatPercentage" name="vatPercentage" value="15.00" />
                                        <span class="input-group-addon">%</span>
                                    </div>
								</div>
								<div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tk.</span>
                                        <input type="text" class="form-control curnum" id="vatOnCharges" name="vatOnCharges" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">VAT Rebate: </label>
								<div class="col-sm-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control curnum" id="vatRebatePercentage" name="vatRebatePercentage" value="80.00" />
                                        <span class="input-group-addon">%</span>
                                    </div>
								</div>
								<div class="col-sm-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tk.</span>
                                        <input type="text" class="form-control curnum" id="vatRebate" name="vatRebate" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Total Charge: </label>
								<div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon">Tk.</span>
                                        <input type="text" class="form-control curnum" id="totalCharge" name="totalCharge" />
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Related to:</label>
								<div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="relatedTo" id="relatedTo" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Supplier: </label>
								<div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Remarks: </label>
								<div class="col-sm-8">
									<textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnChargeEntrySubmit" >Submit</button>
								<button type="button" class="btn btn-default btn-outline"data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- End Modal -->
	</div>
	
	<div class="page-content">
	<!-- Panel -->
		<div class="panel">
			<div class="panel-body container-fluid">
				<!-- Table-->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtAllChargeEntry">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>VAT</th>
                                <th>Vat Rebate</th>
                                <th>Total Charge</th>
                                <th>Related To</th>
                                <th>Remark</th>
                                <th class="text-center" style="width:80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--<tr role="row" class="odd">
                                <td>1</td>
                                <td>admin</td>
                                <td>Admin</td>
                                <td>GrameenPhone</td>
                                <td>8801700000000</td>
                                <td>admin@grameenphone.com</td>
                                <td>admin</td>
                                <td>admin</td>
                                <td><i class="icon wb-right" aria-hidden="true"></i></td>
                                <td class=" text-center">
									<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-wrench" aria-hidden="true"></i></button>
									<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Delete" data-plugin="alertify" data-type="confirm" data-confirm-title="Are you sure you want to delete?" onclick="deleteGroup(1)"><i class="icon wb-close" aria-hidden="true"></i></button>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
                <!-- End Table-->
			</div>
		</div>
	</div>
</div>
<div class="site-action">
    <button type="button" class="btn-raised btn btn-success btn-floating" data-target="#popChargeEntry" 
				data-toggle="modal" data-toggle="Add new Charge" data-original-title="Add new Charge">
        <i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
    </button>
</div>
<!-- End Page -->