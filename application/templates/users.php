<?php
    $title="Users";
    require_once(LIBRARY_PATH . "/csrf_token.php");

?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Roles</li>
		</ol>
		<h1 class="page-title">Users</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#UserForm" data-toggle="modal" data-original-title="Add New User" onclick="ResetForm();">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New User</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="UserForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-users" name="form-users" autocomplete="off" >
					<input type="hidden" name="action" value="1" />
                    <input type="hidden" name="csrf_token" value="<?php echo generateToken('form-users'); ?>"/>
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">User Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="userId" name="userId" value="0" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Username: </label>
								<div class="col-sm-5">
                                    <span data-placement="top" data-toggle="tooltip" data-original-title="Allowed characters: a-zA-Z0-9._">
									    <input type="text" class="form-control" id="username" name="username" onblur="VerifyUserName(this.value)" />
                                    </span>
									<label class="wc-error" id="usernameError"></label>
								</div>
								<label class="col-sm-2 control-label">Active: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="activeUser" name="activeUser" checked="" />
										<label for="activeUser"></label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Password:</label>
								<div class="col-sm-5">
									<input type="password" class="form-control" id="password" name="password" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Full Name:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="firstname" name="firstname" placeholder="first name" />
								</div>
								<!--label class="col-sm-3 control-label">Last Name:</label-->
								<div class="col-sm-4">
									<input type="text" class="form-control" id="lastname" name="lastname" placeholder="last name" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Company:</label>
								<div class="col-sm-9">
                                    <select class="form-control small" data-plugin="select2" name="company" id="company" >
                                    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Department:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="department" name="department" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mobile:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="mobile" name="mobile" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="email" name="email" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Role:</label>
								<div class="col-sm-5">
									<select class="form-control" data-plugin="select2" name="userrole" id="userrole" >
                                    </select>
								</div>
								<label class="col-sm-2 control-label">Manager: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="ismanager" name="ismanager" />
										<label for="ismanager"></label>
									</div>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnUserFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtAllUsers">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Full Name</th>
                                <th>Company</th>
								<th>Department</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Lock Status</th>
                                <th>Active</th>
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
                                <td>c</td>
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
<div class="site-action">
	<button type="button" class="btn-raised btn btn-success btn-floating" data-target="#UserForm" data-toggle="modal" onclick="ResetForm();">
		<i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
	</button>
</div>
<!-- End Page -->