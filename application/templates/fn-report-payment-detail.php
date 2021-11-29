<?php $title = "Payment Detail Report"; ?>
<div class="page bg-blue-100 animsition">
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-612 col-md-12">
                            <h4 class="well well-sm example-title row padding-5"><span class="inline-block margin-10"><i
                                            class="fa fa-file-text-o"></i> Payment Detail Report</span>
                                <span class="pull-right">
                                    <button type="button" id="btnRefresh" title="Apply Filter"
                                            class="btn btn-sm btn-primary"><i class="icon wb-refresh"
                                                                              aria-hidden="true"></i></button>
                                    <button type="button" id="btnClearFilter" title="Clear Filter"
                                            class="btn btn-sm btn-default"><i class="icon wb-close"
                                                                              aria-hidden="true"></i></button>
                                </span>
                            </h4>
                        </div>
                    </div>

                    <div class="row row-lg">

                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">PO:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="pono" id="pono">
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">LC:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="lcno" id="lcno">
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Date: </label>
                                <div class="col-sm-5">
                                    <div class="input-daterange">
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                            <input type="text" data-plugin="datepicker" class="form-control" name="dtpStart" id="dtpStart"/>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">to</span>
                                            <input type="text" data-plugin="datepicker" class="form-control" name="dtpEnd" id="dtpEnd"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">LC Issuer:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="lcBank" id="lcBank">
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Source:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="sourceBank"
                                            id="sourceBank">
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Supplier:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier">
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">FCY:</label>
                                <div class="col-sm-2">
                                    <select class="form-control" data-plugin="select2" name="currency" id="currency">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small"
                                       id="displayTable">
                                    <thead>
                                    <th>SL.</th>
                                    <th>Bank Notification Date</th>
                                    <th>Payment Date</th>
                                    <th>PO No.</th>
                                    <th>LC No.</th>
                                    <th>LC issuing Bank</th>
                                    <th>Sourcing Bank</th>
                                    <th>Supplier</th>
                                    <th>FCY</th>
                                    <th>CI No</th>
                                    <th>GERP Invoice #</th>
                                    <th>Invoice Value</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Amount (USD)</th>
                                    <th>Payment Amount (BDT)</th>
                                    <th>FX Rate</th>
                                    <th>Basis of payment</th>
                                    <th>% of Invoice Paid</th>
                                    <th>BC Selling Rate</th>
                                    <th>Invoice Booking rate</th>
                                    <th>Gross Saving in Fx rate</th>
                                    <th>Net Saving in Fx rate</th>
                                    <th>Gross Cost savings against LC payment(in BDT)</th>
                                    <th>Net Cost savings against LC payment(in BDT)</th>
                                    <th>Gross Day(No of days)</th>
                                    <th>Week End & Holiday(No of days)</th>
                                    <th>Actual Date required</th>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <td colspan="10"><span class="pull-right">Total:</span>
                                        </th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
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