<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 2/4/2017
 * Time: 10:55 PM
 */
$title="Activity wise Trade Finance Report"; ?>
<div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <h4 class="well well-sm example-title">Activity wise Trade Finance Report</h4>
                    </div>
                </div>

                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
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
                            <div class="col-sm-5">
                                <button type="button" id="btnRefresh" class="btn btn-primary"><i class="icon wb-refresh" aria-hidden="true"></i></button>
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
                                    <tr>
                                        <th rowspan="2">Month</th>

                                        <th rowspan="2">No of LC Opening</th>
                                        <th colspan="2" class="text-center">LC Opening Value</th>

                                        <th rowspan="2">No of LC Endorsement</th>
                                        <th colspan="2" class="text-center">LC Endorsement Value</th>

                                        <th rowspan="2">No of LC Settlement</th>
                                        <th colspan="2" class="text-center">LC Settlement Value</th>

                                        <th rowspan="2">No of Custom Duty Payment</th>
                                        <th colspan="2" class="text-center">Custom Duty Payment Value</th>

                                        <th rowspan="2">No of Ancillary Cost Capitalization</th>
                                        <th colspan="2" class="text-center">Ancillary Cost Capitalization</th>

                                    </tr>
                                    <tr>
                                        <th>USD(Mn)</th>
                                        <th>BDT(Mn)</th>
                                        <th>USD(Mn)</th>
                                        <th>BDT(Mn)</th>
                                        <th>USD(Mn)</th>
                                        <th>BDT(Mn)</th>
                                        <th>USD(Mn)</th>
                                        <th>BDT(Mn)</th>
                                        <th>USD(Mn)</th>
                                        <th>BDT(Mn)</th>
                                    </tr>
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