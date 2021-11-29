<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 2020-10-28
 */
?>

<div class="page bg-blue-100 animsition">
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="p2p-form" name="p2p-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-612 col-md-12">
                            <h4 class="well well-sm example-title row padding-5"><span class="inline-block margin-10"><i class="fa fa-file-text-o"></i> PO 2 Payment(P2P) Report</span></h4>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="">
                            <div class="row row-lg">
                                <div class="col-xlg-12 col-md-12">
                                    <div class="form-group small">
                                        <label class="col-sm-2 control-label" for="poNo">PO NO: </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" data-plugin="select2" name="poNo" id="poNo" > </select>
                                        </div>
                                        <label class="col-sm-2 control-label" for="status">PO Stage: </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" data-plugin="select2" name="stage" id="stage" ></select>
                                        </div>
                                    </div>
                                    <div class="form-group small">
                                        <label class="col-sm-2 control-label">Date Range: </label>
                                        <div class="col-sm-4">
                                            <div class="input-daterange" data-plugin="datepicker">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="icon wb-calendar" aria-hidden="true"></i></span>
                                                    <input type="text" class="form-control" name="dtStart" id="dtStart" />
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon">to</span>
                                                    <input type="text" class="form-control" name="dtEnd" id="dtEnd" />
                                                </div>
                                            </div>
                                        </div>
                                        <label class="col-sm-2 control-label" for="supplier">Supplier: </label>
                                        <div class="col-sm-4">
                                            <select class="form-control" data-plugin="select2" name="supplier" id="supplier" ></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xlg-12 col-md-12">
                                    <div class="col-sm-offset-5 col-sm-7 text-right">
                                        <button type="button" class="btn btn-primary" id="applyFilter">Filter</button>
                                        <button type="button" class="btn btn-default" id="clearFilter">Clear</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />

                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <table class="table table-bordered dataTable table-hover table-striped width-full small" id="displayTable">

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