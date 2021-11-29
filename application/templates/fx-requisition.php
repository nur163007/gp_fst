<?php $title="Navigations";?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">Fx Requisition</h1>
    </div>
    <!-- Modal -->
        <div class="modal-dialog">
            <form class="form-horizontal" id="form-fx" name="form-fx" method="post" autocomplete="off" enctype='multipart/form-data'>
                <input type="hidden" name="req_type" id="req_type" value="113">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Requisition Form</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="fxId" name="fxId" value="<?php if(!empty($_GET['id'])){ echo $_GET['id']; } ?>" />
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Supplier Name: </label>
                            <div class="col-sm-6">
                                <select class="form-control" data-plugin="select2" name="supplier_id" id="supplier_id" >
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nature Of Service: </label>
                            <div class="col-sm-6">
                                <select class="form-control" data-plugin="select2" name="nature_of_service" id="nature_of_service" >
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Currency: </label>
                            <div class="col-sm-6">
                                <select data-plugin="selectpicker" data-style="btn-select" name="currency" id="currency" title="Select Currency">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Value:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="value" id="value" placeholder="Enter a value" />
                                    <div class="input-group-addon">
                                        <label id="fxvalueCur"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Value Date:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                    <input type="text" class="form-control" data-plugin="datepicker" name="value_date" id="value_date" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Remarks:</label>
                            <div class="col-sm-6">
                                <textarea class="form-control" name="remarks" id="remarks" placeholder="Write something...."></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Attachments:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="attachfx" id="attachfx" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                    <span class="input-group-btn">
                                            <button type="button" id="btnUploadFx" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="model-footer text-right">
                            <label class="wc-error pull-left" id="form_error"></label>
                            <button type="button" class="btn btn-primary" id="btnFxFormSubmit" >Submit</button>
                            <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <!-- End Modal -->