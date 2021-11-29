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
                            <h4 class="well well-sm example-title"><i class="fa fa-file-text-o"></i> Endorsement Report
                                <span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                           data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Show Summary Report</span>
                                </span>
                            </h4>
                        </div>
                    </div>

                    <div class="row row-lg">

                        <div class="col-xlg-10 col-md-10 hidden" id="summaryFilter">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Summary By: </label>
                                <div class="col-sm-3 margin-top-5">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="summaryByBank" name="summaryBy" value="bank" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Bank</li>
                                        <li><input type="radio" id="summaryBySupplier" name="summaryBy" value="supplier" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                    </ul>
                                </div>
                                <label class="col-sm-1 control-label">Date:</label>
                                <div class="col-sm-6">
                                    <div class="input-daterange" data-plugin="datepicker">
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                          <i class="icon wb-calendar" aria-hidden="true"></i>
                                        </span>
                                            <input type="text" class="form-control" name="dtpStart2" id="dtpStart2" />
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="dtpEnd2" id="dtpEnd2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-10 col-md-10" id="nonSummaryFilter">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Bank:</label>
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
                                            <input type="text" class="form-control" name="dtpStart1" id="dtpStart1" />
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">to</span>
                                            <input type="text" class="form-control" name="dtpEnd1" id="dtpEnd1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Supplier:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">PO No: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="pono" id="pono" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">FCY:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="fcy" id="fcy" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">LC No: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" name="lcno" id="lcno" >
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-2 col-md-2">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="button" id="btnRefresh" title="Apply Filter" class="btn btn-sm btn-primary"><i class="icon wb-refresh" aria-hidden="true"></i></button>
                                    <button type="button" id="btnClearFilter" title="Clear Filter" class="btn btn-sm btn-default"><i class="icon wb-close" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr />

                    <div class="row row-lg" id="dtContainer">
                        <div class="col-xlg-12 col-md-12">
                            <div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small hidden" id="displayTable">
                                    <thead>
                                    <th>SL</th>
                                    <th>Endorsement Date</th>
                                    <th>LC No.</th>
                                    <th>Bank</th>
                                    <th>Commercial Invoice #</th>
                                    <th>Document Type</th>
                                    <th>GERP  Voucher No.</th>
                                    <th>GERP  Voucher Date</th>
                                    <th>PO No.</th>
                                    <th>Supplier</th>
                                    <th>Description</th>
                                    <th>Currency</th>
                                    <th>LC Value</th>
                                    <th>Endorsed Amount</th>
                                    <th>Endorsed Amount in USD</th>
                                    <th>Endorsed Amount in BDT</th>
                                    <th>Ex. Rate</th>
                                    <th>Shipment/ Endorsement No.</th>
                                    <th>Request from PO Operation</th>
                                    <th>Doc Delivered by Trare Finance Operation</th>
                                    <th>Query Resolve Date</th>
                                    <th>Gross day Required(No of days)</th>
                                    <th>Weekend & holiday(no of days)</th>
                                    <th>Actual Day Required(No of days)</th>
                                    </thead>
                                </table>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small hidden" id="displayTableSum">
                                    <thead>
                                    <th id="sumColumnName" class="text-capitalize">Bank/Supplier</th>
                                    <th>Currency</th>
                                    <th>Number of LC</th>
                                    <th>End. Value</th>
                                    <th>End. Value in USD</th>
                                    <th>End. Value in BDT</th>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2"><span class="pull-right">Total:</span></th>
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
