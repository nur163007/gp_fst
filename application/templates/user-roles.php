<?php $title="User Roles";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Roles</li>
		</ol>
		<h1 class="page-title">User Roles</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round" data-target="#UserRoleForm" 
				data-toggle="modal" data-toggle="Add New Role" data-original-title="Add New Role">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Role</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="UserRoleForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-userroles" name="form-userroles" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">User Role Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="userroleid" name="userroleid" value="0" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Name: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name" name="name" />
									<label class="wc-error" id="usernameError"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Parent: </label>
								<div class="col-sm-6">
									<select class="form-control" data-plugin="select2" name="parent" id="parent" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Description: </label>
								<div class="col-sm-6">
									<textarea class="form-control" id="description" name="description" cols="30" rows="5"></textarea>
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Tag: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" id="tag" name="tag" />
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnUserRolesFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtUserRoles">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Description</th>
                                <th>Tag</th>
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