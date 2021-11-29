<?php $title="Bank &amp; Insurance";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">LC</li>
		</ol>
		<h1 class="page-title">Bank &amp; Insurance</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#BankForm" 
				data-toggle="modal" data-toggle="Add New User" data-original-title="Add New User">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Bank/Insurance</span>
			</button>
		</div>
		<!-- Modal -->
        <div class="modal fade example-modal-lg" id="BankForm" aria-hidden="true" aria-labelledby="exampleOptionalLarge" role="dialog">
			<div class="modal-dialog modal-lg">
				<form class="form-horizontal" id="form-bank" name="form-bank" autocomplete="off" >
                    <input type="hidden" id="id" name="id" value="0" />
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Bank Form</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-2 control-label">Name: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="name" name="name" />
									<label class="wc-error" id=""></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Address:</label>
								<div class="col-sm-10">
                                    <textarea class="form-control" id="address" name="address" cols="30" rows="3"></textarea>
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Manager: </label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="manager" name="manager" />
									<label class="wc-error" id=""></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Telephone:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="telephone" name="telephone" />
								</div>
								<label class="col-sm-2 control-label">Mobile:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="mobile" name="mobile" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">E-mail:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="email" name="email" />
								</div>
								<label class="col-sm-2 control-label">Website:</label>
								<div class="col-sm-4">
									<input type="text" class="form-control" id="website" name="website" />
								</div>
							</div>
							<div class="form-group">
                                <label class="col-sm-2 control-label">Type: </label>
                                <div class="col-sm-4">
                                    <ul class="list-unstyled list-inline ">
                                        <li><input type="radio" id="type_bank" name="type" value="bank" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Bank</li>
                                        <li><input type="radio" id="type_account" name="type" value="account" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Account</li>
                                        <li><input type="radio" id="type_insurance" name="type" value="insurance" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Insurance</li>
                                        <li><input type="radio" id="type_cnf" name="type" value="cnf" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;C &amp; F</li>
                                    </ul>
                                </div>
								<label class="col-sm-2 control-label">Bank:</label>
								<div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="bank" id="bank" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<!--label class="col-sm-2 control-label">Service Rank:</label>
								<div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="servicerank" id="servicerank">
                                        <option value="">Select Service rank</option>
                                        <option value="Poor">Poor</option>
                                        <option value="Good">Good</option>
                                        <option value="Excellent">Excellent</option>
                                    </select>
								</div-->
								<label class="col-sm-2 control-label">Tag:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" data-plugin="tokenfield" name="tag" id="tag" />
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnBankFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtAllBanks">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>bankorder</th>
                                <th>Name</th>
                                <th>Bank</th>
                                <th>Address</th>
                                <th>E-mail</th>
                                <th>Type</th>
                                <th class="text-center" style="width:80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--tr role="row" class="odd">
                                <td>1</td>
                                <td>admin</td>
                                <td>Admin</td>
                                <td>GrameenPhone</td>
                                <td>8801700000000</td>
                                <td>admin@grameenphone.com</td>
                                <td>admin</td>
                                <td><i class="icon wb-right" aria-hidden="true"></i></td>
                                <td class=" text-center">
									<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-wrench" aria-hidden="true"></i></button>
									<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Delete" data-plugin="alertify" data-type="confirm" data-confirm-title="Are you sure you want to delete?" onclick="deleteGroup(1)"><i class="icon wb-close" aria-hidden="true"></i></button>
                                </td>
                            </tr-->
                        </tbody>
                    </table>
                </div>
                <!-- End Table-->
			</div>
		</div>
	</div>
</div>
<!-- End Page -->