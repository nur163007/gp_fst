<?php $title="LC wise Report"; ?>
   <div class="page bg-blue-100 animsition">
    <!--<div class="page-header">

        <h1 class="page-title">LC wise Report</h1>

        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li class="active">LC wise Report</li>
        </ol>

        <div class="page-header-actions">
			&nbsp;
		</div>
    </div>-->
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <h4 class="well well-sm example-title"><i class="fa fa-file-text-o"></i> LC wise Report </h4>
                        </div>

                    </div>

                    <div class="row row-lg">

                        <div class="col-xlg-6 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Report: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="report" id="report" >
                                        <option value=""></option>
                                        <option value="1">LC Opening</option>
                                        <option value="2">LC Endorsement</option>
                                        <option value="3">LC Amendment</option>
                                        <option value="4">Supplier Wise LC Opening Sum</option>
                                        <option value="5">Supplier Wise LC End. Sum</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Date Range: </label>
                                <div class="col-sm-5">
                                   <div class="input-daterange" data-plugin="datepicker">
                                     <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                        <input type="text" class="form-control" name="dtpStart" id="dtpStart" />
                                      </div>
                                      <div class="input-group">
                                        <span class="input-group-addon">to</span>
                                        <input type="text" class="form-control" name="dtpEnd" id="dtpEnd" />
                                      </div>
                                   </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">LC No: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="lcno" id="lcno" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Bank: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="bank" id="bank" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Supplier: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Currency </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="selectpicker" name="currency" id="currency" >                                                                        <option value=""></option>                                                          <option value="expired">Expired</option>                                            <option value="toBeExpired">To be Expired in 3 months</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <!--<h4 class="well well-sm example-title">LC wise Report
                                <button type="button" class="btn btn-sm btn-inverse btn-round pull-right" style="margin-top: -8px;" id="btnLoadReport">
                                    <i class="icon wb-refresh" aria-hidden="true"></i>
                                    <span class="hidden-xs">Load Report</span>
                                </button>
                            </h4>-->

                            <div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">

                                </table>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="export_btn"><i class="icon fa-download" aria-hidden="true"></i> Export to Excel </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->