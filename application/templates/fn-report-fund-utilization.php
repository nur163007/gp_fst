<?php $title="Outstanding LC list"; ?>

<div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <h4 class="well well-sm example-title">Utilization Report</h4>
                    </div>
                </div>
                <div class="row row-lg">

                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">Bank:</label>
                            <div class="col-sm-4">
                                <select class="form-control" data-plugin="select2" name="bank" id="bank" >
                                </select>
                            </div>
                            <label class="col-sm-1 control-label">Date:</label>
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
                    </div>

                </div>
                <hr />
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">
                                <thead>
                                    <th>SL</th>
                                    <th>Bank</th>
                                    <th>Non Funded Facility (USD)</th>
                                    <th>Non Funded Facility (BDT)</th>
                                    <th>Capacity Utilized (USD)</th>
                                    <th>Capacity Utilized (BDT)</th>
                                    <th>Space Available (USD)</th>
                                    <th>Space Available (BDT)</th>
                                </thead>
                            </table>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
