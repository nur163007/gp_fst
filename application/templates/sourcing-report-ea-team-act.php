<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/18/2017
 * Time: 12:34 PM
 */
?>

<div class="page bg-blue-100 animsition">
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-612 col-md-12">
                            <h4 class="well well-sm example-title"><i class="fa fa-file-text-o"></i> EA Team Activities Report</h4>
                        </div>
                    </div>

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group small">
                                <label class="col-sm-1 control-label">Warehouse Date:</label>
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

                                <label class="col-sm-1 control-label">Custom Date: </label>
                                <div class="col-sm-4">
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
                            <table class="table table-bordered dataTable table-striped width-full small" id="displayTable">

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

