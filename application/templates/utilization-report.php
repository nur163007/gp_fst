<?php $title="Outstanding LC list"; ?>
   <div class="page bg-blue-100 animsition">
    <!--div class="page-header">
        
        <h1 class="page-title">Outstanding LC list</h1>
        
        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li class="active">Outstanding</li>
        </ol>
		
        <div class="page-header-actions">
			&nbsp;
		</div>        
    </div-->
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <!--form class="form-horizontal" id="outstanding-form" name="outstanding-form" method="post" autocomplete="off"-->
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Utilization Report
                                <!--<span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Show Summary Report</span>
                                </span>-->
                            </h4>
                        </div>
                    
                    </div>
                    
                    <!--<div class="row row-lg">
                        
                        <div class="col-xlg-12 col-md-12 hidden" id="summaryFilter">
                            <div class="form-group">
                                <label class="col-sm-2 col-lg-2 col-xlg-1 control-label">Summary by: </label>
                                <div class="col-sm-10 col-lg-10 col-xlg-11 margin-top-11">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="summaryByBank" name="summaryBy" value="bank" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Bank</li>
                                        <li><input type="radio" id="summaryBySupplier" name="summaryBy" value="supplier" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
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
                            </div>
                        </div>
                    </div>
                    <hr />-->
                    
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">

                                </table>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-right" id="buttons">
                                    <!--button type="button" class="btn btn-primary" id="export_btn"><i class="icon fa-download" aria-hidden="true"></i> Export to Excel </button-->
                                </div> 
                            </div>                            
                        </div>
                    </div>
                <!--/form-->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
