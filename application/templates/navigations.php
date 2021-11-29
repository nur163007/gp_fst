<?php $title="Navigations";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Navigation</li>
		</ol>
		<h1 class="page-title">All Navigation</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round" data-target="#navForm" data-toggle="modal" >
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Navigation</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="navForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-nav" name="form-nav" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Navigation Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="navId" name="navId" value="0" >
							<div class="form-group">
								<label class="col-sm-3 control-label">Name: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name" name="name" />
									<label class="wc-error" id="usernameError"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Url: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="url" name="url" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Mask: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="mask" name="mask" />
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
								<label class="col-sm-3 control-label">Category: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="category" name="category" />
										<label for="category"></label>
									</div>
								</div>
								
								<label class="col-sm-3 control-label">Display: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="display" name="display" checked />
										<label for="display"></label>
									</div>
								</div>
							</div>
							<hr>
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnNavFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtNav">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>NavOrder</th>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Mask</th>
                                <th>Category</th>
                                <th>Parent</th>
                                <th>Display</th>
                                <th class="text-center" style="width:80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                <!-- End Table-->
			</div>
		</div>
	</div>
</div>
<div class="site-action">
    <button type="button" class="btn-raised btn btn-success btn-floating" data-target="#navForm" 
				data-toggle="modal" data-toggle="Add new Nav" data-original-title="Add new Nav">
        <i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
    </button>
</div>
<!-- End Page -->