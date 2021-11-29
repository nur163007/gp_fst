<?php $title="Content";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Roles</li>
		</ol>
		<h1 class="page-title">Content</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#ContentForm" 
				data-toggle="modal" data-toggle="Add New Content" data-original-title="Add New Content">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Content</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="ContentForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-content" name="form-content" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Content Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="contentId" name="contentId" value="0" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Meta Title: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="metaTitle" name="metaTitle" />
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Main Title:</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="mainTitle" name="mainTitle" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Sub Title:</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="subTitle" name="subTitle" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Content: </label>
								<div class="col-sm-6">
									<textarea class="form-control" id="content" name="content" cols="30" rows="3"></textarea>
									<label class="wc-error"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Tag: </label>
								<div class="col-sm-6">
                                    <input type="text" class="form-control" data-tokens="" name="tag" id="tag" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Category:</label>
								<div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" name="category" id="category" >
                                    </select>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnContentFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtContent">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Meta Title</th>
                                <th>Main Title</th>
                                <th>Sub Title</th>
                                <th>Content</th>
                                <th>Tag</th>
                                <th>Category</th>
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
                                <td>192.168.0.1</td>
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