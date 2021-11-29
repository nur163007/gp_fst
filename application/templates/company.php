<?php $title="Company";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Roles</li>
		</ol>
		<h1 class="page-title">Company</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round" data-target="#CompanyForm" data-toggle="modal" >
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Company</span>
			</button>
		</div>
		<!-- Modal -->
        <div class="modal fade example-modal-lg" id="CompanyForm" aria-hidden="true" aria-labelledby="exampleOptionalLarge" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog modal-lg">
				<form class="form-horizontal" id="form-company" name="form-company" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Company Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="companyid" name="companyid" value="0" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Name: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name" name="name" />
									<label class="wc-error" id="usernameError"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Address: </label>
								<div class="col-sm-6">
									<textarea class="form-control" id="address" name="address" cols="30" rows="3"></textarea>
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Phone: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" id="phone" name="phone" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Fax: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" id="fax" name="fax" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email TO: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" data-tokens="" name="emailTo" id="emailTo" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email CC: </label>
								<div class="col-sm-6">
                                   <input type="text" class="form-control" data-tokens="" name="emailCc" id="emailCc" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Concern Person: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" id="concernPerson" name="concernPerson" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Designation: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" id="designation" name="designation" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Contract Ref: </label>
								<div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" name="contractRef[]" id="contractRef" multiple="" >
                                    </select>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnCompanyFormSubmit" >Submit</button>
								<button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtCompany">
                        <thead class="small">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Fax</th>
                                <th>Email TO</th>
                                <th>Email CC</th>
                                <th>Concern Person</th>
                                <th>Designation</th>
                                <th class="text-center" style="width:80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <!--<tr role="row" class="odd">
                                <td>1</td>
                                <td>admin</td>
                                <td>Admin</td>
                                <td>GrameenPhone</td>
                                <td>8801700000000</td>
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