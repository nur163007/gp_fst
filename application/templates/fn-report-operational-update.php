<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 2/5/2017
 * Time: 12:16 PM
 */
$title="Activity wise Trade Finance Report"; ?>
<div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <h4 class="well well-sm example-title">Operational Update Report</h4>
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
                <div class="row row-lg hidden" id="tablesContainer">
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <h5>&nbsp; LC Opening</h5>
                            <div id="c1">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable1">

                                </table>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn1" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <h5>&nbsp; LC Endorsement/Original Document</h5>
                            <div id="c2">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable2">

                                </table>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn2" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <h5>&nbsp; LC Settlement</h5>
                            <div id="c3">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable3">

                                </table>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn3" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <h5>&nbsp; Ancillary Cost Capitalisation</h5>
                            <div id="c4">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable4">

                                </table>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn4" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                    <div class="col-xlg-12 col-md-12">
                        <div>
                            <h5>&nbsp; Cost Savings Against LC Payment</h5>
                            <div id="c5">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable5">

                                </table>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn5" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End Page -->