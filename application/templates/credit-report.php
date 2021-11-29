<?php $title="Credit Reports";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Credit Report</li>
		</ol>
		<h1 class="page-title">Credit Reports</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#creditReportForm" 
				data-toggle="modal" data-toggle="Add New Report" data-original-title="Add New Report">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Report</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="creditReportForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-credit-report" name="form-credit-report" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Credit Report Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="reportID" name="reportID" />
							<div class="form-group">
								<label class="col-sm-4 control-label">Supplier: </label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Credit Report Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="creditReportDate" id="creditReportDate" />
                                    </div>
                                </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Report Expiry Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="reportExpiryDate" id="reportExpiryDate" />
                                    </div>
                                </div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Credit Report Charge: </label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="creditReportCharge" name="creditReportCharge" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Vat On Charges: </label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="vatOnCharges" name="vatOnCharges" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Rebate: </label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="rebate" name="rebate" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Vat Rebate: </label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="vatRebate" name="vatRebate" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Charge Type:</label>
								<div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="chargeType" id="chargeType" >
                                    </select>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnCreditReportFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtAllCreditReport">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Supplier</th>
                                <th>Credit Report Date</th>
                                <th>Report Expiry Date</th>
                                <th>Credit Report Charge</th>
                                <th>Vat On Charges</th>
                                <th>Rebate</th>
                                <th>Vat Rebate</th>
                                <th>Charge Type</th>
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
<!-- End Page -->