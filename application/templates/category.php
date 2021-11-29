<?php $title="Category";?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Category</li>
		</ol>
		<h1 class="page-title">All Category</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round" data-target="#catForm" data-toggle="modal">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">Add New Category</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="catForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
			<div class="modal-dialog modal-center">
				<form class="form-horizontal" id="form-cat" name="form-cat" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">Category Form</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="catId" name="catId" value="0" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Category/Lookup:</label>
								<div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" id="category" name="category" >
                                    </select>
								</div>
                                <div>
                                    <button type="button" class="btn btn-primary" id="btnAddNewLookup" data-target="#addNewLookupSet"
				data-toggle="modal" data-toggle="Add New Category" data-original-title="Add New Category">+</button>
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
								<label class="col-sm-3 control-label">Item: </label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="name" name="name" />
									<label class="wc-error" id="usernameError"></label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Tag/Value: </label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="tag" name="tag" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Meta Text: </label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="4" id="metatext" name="metatext"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Active: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="active" name="active" checked="" />
										<label for="active"></label>
									</div>
								</div>
								<label class="col-sm-3 control-label">Moderation: </label>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
										<input type="checkbox" id="moderation" name="moderation" />
										<label for="moderation"></label>
									</div>
								</div>
							</div>
							<hr />
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnCatFormSubmit" >Submit</button>
								<button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
        
        <div class="modal fade modal-fade-in-scale-up" id="addNewLookupSet" aria-hidden="true" aria-labelledby="exampleOptionalSmall" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-sm modal-center">
				<form class="form-horizontal" id="form-lookupset" name="form-lookupset" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetLookupForm();">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title" id="exampleOptionalSmall">Lookup Set Name</h4>
						</div>
						<div class="modal-body">
							<input type="text" class="form-control" id="lookupSetName" name="lookupSetName" placeholder="lookup set name" autocomplete="off" />
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default margin-top-5" data-dismiss="modal" onclick="resetLookupForm();">Cancel</button>
							<button type="button" class="btn btn-primary margin-top-5" data-dismiss="modal" id="btnCreateLookupSet">Create</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtCat">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Lookup Set</th>
                                <th>Active</th>
                                <th>Moderation</th>
                                <th>Parent</th>
                                <th>Tag/Value</th>
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
    <button type="button" class="btn-raised btn btn-success btn-floating" data-target="#catForm" 
				data-toggle="modal" data-toggle="Add new Category" data-original-title="Add new Category">
        <i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
    </button>
</div>
<!-- End Page -->