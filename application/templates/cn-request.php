<?php
    $title="Users";
    require_once(LIBRARY_PATH . "/csrf_token.php");

?>
<!-- Page -->
<div class="page animsition">
	<div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Dashboard</a></li>
		</ol>
		<h1 class="page-title">CN Request</h1>
		<div class="page-header-actions">
			<button class="btn btn-sm btn-inverse btn-round"  data-target="#CnRequestModal" data-toggle="modal" data-original-title="New CN Request" onclick="ResetForm();">
				<i class="icon wb-plus" aria-hidden="true"></i>
				<span class="hidden-xs">New CN Request</span>
			</button>
		</div>
		<!-- Modal -->
		<div class="modal fade modal-slide-in-top" id="CnRequestModal" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form class="form-horizontal" id="form-cn-request" name="form-cn-request" method="post" autocomplete="off" >
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm()">
								<span aria-hidden="true">×</span>
							</button>
							<h4 class="modal-title">CN Request</h4>
						</div>
						<div class="modal-body">
							<input type="hidden" id="cnId" name="cnId" value="<?php if(!empty($_GET['id'])){ echo $_GET['id']; } ?>" />
							<div class="form-group">
								<label class="col-sm-4 control-label">CN Number: </label>
								<div class="col-sm-5">
                                    <span data-placement="top" data-toggle="tooltip" data-original-title="">
									    <input type="text" class="form-control" id="cn_number" name="cn_number" placeholder="Enter a CN number"/>
                                    </span>
								</div>
								<div class="col-sm-2">
									<div class="checkbox-custom checkbox-primary">
								</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">CN Date:</label>
								<div class="col-sm-5">
								<div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="cn_date" id="cn_date">
                                    </div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Pay Order Amount:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="pay_order_amount" name="pay_order_amount" placeholder="Enter pay order amount"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Pay Order Charge:</label>
								<div class="col-sm-5">
									<input type="text" class="form-control" id="pay_order_charge" name="pay_order_charge" placeholder="Enter pay order charge"/>
								</div>
							</div>
                            <div class="row row-lg">
                                <div class="col-xlg-12 col-md-12">
                                    <h4 class="well well-sm example-title">Attachments</h4>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">CN Copy:</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="attachcn" id="attachcn" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                <input type="hidden" class="form-control" name="attachcnhidden" id="attachcnhidden"  />
                                                <span class="input-group-btn">
                                            <button type="button" id="btnUploadCn" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">Pay Order Receive Copy:</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="attachporc" id="attachporc" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                <input type="hidden" class="form-control" name="attachporchidden" id="attachporchidden"  />
                                                <span class="input-group-btn">
                                            <button type="button" id="btnUploadPorc" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label">CN Other Docs:</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="attachother" id="attachother" readonly placeholder="incase of multiple file use .zip" />
                                                <input type="hidden" class="form-control" name="attachotherhidden" id="attachotherhidden"  />
                                                <span class="input-group-btn">
                                            <button type="button" id="btnUploadOther" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
							<div class="model-footer text-right">
								<label class="wc-error pull-left" id="form_error"></label>
								<button type="button" class="btn btn-primary" id="btnCnRequest" >Submit</button>
								<button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm()">Close</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtAllCn">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>PO NUMBER</th>
                                <th>CN NUMBER</th>
                                <th>CN DATE</th>
                                <th>PAY ORDER AMOUNT</th>
                                <th>PAY ORDER CHARGE</th>
                                <th>Created By</th>
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
	<button type="button" class="btn-raised btn btn-success btn-floating" data-target="#CnRequestModal" data-toggle="modal" onclick="ResetForm();">
		<i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
	</button>
</div>
<!-- End Page -->