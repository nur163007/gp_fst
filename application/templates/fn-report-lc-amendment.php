<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 1/30/2017
 * Time: 7:44 PM
 */
$title="LC Opening Report";
?>

<div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-612 col-md-12">
                            <h4 class="well well-sm example-title"><i class="fa fa-file-text-o"></i> LC Amendment Report </h4>
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
                                <label class="col-sm-2 control-label">Date:</label>
                                <div class="col-sm-4">
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
                                <label class="col-sm-1 control-label">PO No: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="pono" id="pono" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Supplier:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">LC No: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="lcno" id="lcno" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Charge Borne By:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="cBorneBy" id="cBorneBy" >
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr />

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <!--<h4 class="well well-sm example-title">LC wise Report
                                <button type="button" class="btn btn-sm btn-inverse btn-round pull-right" style="margin-top: -8px;" id="btnLoadReport">
                                    <i class="icon wb-refresh" aria-hidden="true"></i>
                                    <span class="hidden-xs">Load Report</span>
                                </button>
                            </h4>-->
                            <div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">
                                    <thead>
                                    <th>SL</th>
                                    <th>LC#</th>
                                    <th>PO#</th>
                                    <th>Amendment No.</th>
                                    <th>Amendment Date</th>
                                    <th>Bank</th>
                                    <th>Supplier</th>
                                    <th>Description</th>
                                    <th>FCY</th>
                                    <th>LC Value</th>
                                    <th>LC Value in USD</th>
                                    <th>LC Value in BDT</th>
                                    <th>Ex. Rate</th>
                                    <th>Amendment Cost</th>
                                    <th>Cost Borne by</th>
                                    <th>PO Operation Approval Date</th>
                                    <th>Trade Finance Approval Date</th>
                                    <th>Query Resolve Date</th>
                                    <th>Gross day Required(No of days)</th>
                                    <th>Weekend & holiday(no of days)</th>
                                    <th>Actual Day Required(No of days)</th>
                                    </thead>
                                </table>
                            </div>
                            <!--<div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">
                                </table>
                            </div>-->
                            <div class="col-xlg-12 col-md-12 text-right">
                                <span id="exportBtn" class="pull-right"></span>
                                <span class="pull-right">Export to: &nbsp;</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
