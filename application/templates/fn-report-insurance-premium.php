<?php $title="Outstanding LC list"; ?>
   <div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Insurance Premium Report
                                <span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                           data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Summary Report</span>
                                </span>
                            </h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <!--<div class="col-xlg-12 col-md-12" id="summaryFilter">
                            <div class="form-group">
                                <label class="col-sm-2 col-lg-2 col-xlg-1 control-label">Summary by: </label>
                                <div class="col-sm-10 col-lg-10 col-xlg-11 margin-top-11">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="summaryByBank" name="summaryBy" value="bank" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Bank</li>
                                        <li><input type="radio" id="summaryBySupplier" name="summaryBy" value="supplier" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                    </ul>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-xlg-12 col-md-12" id="nonSummaryFilter">
                            <div class="form-group">
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

                                </table>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small hidden" id="displayTableSum">

                                </table>
                            </div>
                            <div class="col-xlg-12 col-md-12 text-right">
                                <span id="exportBtn" class="pull-right"></span>
                                <span class="pull-right">Export to: &nbsp;</span>
                            </div>
                        </div>
                    </div>
                <!--/form-->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
