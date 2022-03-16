<?php $title="Update Credit Reports"; ?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Update Credit Report</li>
        </ol>
        <h1 class="page-title">Update Credit Reports</h1>
        <div class="page-header-actions">
            <button class="btn btn-sm btn-inverse btn-round"  data-target="#updateCreditReportForm"
                    data-toggle="modal" data-toggle="Add New Report" data-original-title="Add New Report">
                <i class="icon wb-plus" aria-hidden="true"></i>
                <span class="hidden-xs">Add New Report</span>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade modal-slide-in-top" id="updateCreditReportForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
            <div class="modal-dialog">
                <form class="form-horizontal" id="updateCreditReportFormData" name="updateCreditReportFormData" autocomplete="off" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Credit Report Form</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="reportID" name="reportID" />
                            <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                            <input name="hiddenCompanyId" id="hiddenCompanyId" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_company']; ?>" />
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Supplier: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Bank Name: </label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="bankid" id="bankid" >
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Report Issue Date: </label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" data-plugin="datepicker" name="creditReportDate" id="creditReportDate" placeholder="Report issue date"/>
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
                                        <input type="text" class="form-control" data-plugin="datepicker" name="reportExpiryDate" id="reportExpiryDate" placeholder="Report expiry date"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">Credit Report Attachment:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachCreditReport" id="attachCreditReport" readonly placeholder=".pdf" />
                                        <input type="hidden" name="attachCreditReportOld" id="attachCreditReportOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadCreditReport" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                    <span id="attachOldCreditReport"></span>
                                </div>
                            </div>
                            <hr />
                            <div class="model-footer text-right">
                                <label class="wc-error pull-left" id="form_error"></label>
                                <button type="button" class="btn btn-primary" id="btnUpdateCreditReportFormSubmit" >Submit</button>
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
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtUpdateCreditReport">
                        <thead>
                        <tr>
                            <th>Id#</th>
                            <th>Supplier</th>
                            <th>Bank Name</th>
                            <th>Issue Date</th>
                            <th>Expiry Date</th>
                            <th>Credit Report</th>
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
<!-- End Page -->