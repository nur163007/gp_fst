<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 9/28/2016
 * Time: 2:17 PM
 */

$title="Charge Report";
?>
<div class="page bg-blue-100 animsition">
    <div class="page-header">

        <h1 class="page-title">Charges Report</h1>

        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li>Finance</li>
        </ol>

        <div class="page-header-actions">
            &nbsp;
        </div>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title"><i class="fa fa-filter"></i> Filter </h4>
                        </div>

                    </div>

                    <div class="row row-lg">

                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Report: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="reportName" name="reportName" >
                                        <option value=""></option>
                                        <option value="1">LC Opening Charge</option>
                                        <option value="2">Insurance Premium</option>
                                        <option value="3">Other Charges For LC Opening</option>
                                        <option value="4">Payment Charge</option>
                                        <option value="5">Endorsement Charge</option>
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Date: </label>
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
                                <div class="col-sm-2">
                                    <button type="button" id="btnRefresh" title="Apply Filter" class="btn btn-sm btn-primary"><i class="icon wb-refresh" aria-hidden="true"></i></button>
                                    <button type="button" id="btnClearFilter" title="Clear Filter" class="btn btn-sm btn-default"><i class="icon wb-close" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <table class="table table-bordered dataTable table-striped width-full small" id="dtReportView">

                            </table>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->