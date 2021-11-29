<?php $title="Outstanding LC list"; ?>
   <div class="page bg-blue-100 animsition">
    <!--div class="page-header">
        
        <h1 class="page-title">Outstanding LC list</h1>
        
        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li class="active">Outstanding</li>
        </ol>
		
        <div class="page-header-actions">
			 
		</div>        
    </div-->
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <!--form class="form-horizontal" id="outstanding-form" name="outstanding-form" method="post" autocomplete="off"-->
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">VAT Report
                                <!--<span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Show Summary Report</span>
                                </span>-->
                            </h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <div class="col-xlg-5 col-md-5" id="summaryFilter">
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-3 col-xlg-2 control-label">VAT on: </label>
                                <div class="col-sm-9 col-lg-9 col-xlg-10 margin-top-11">
                                    <ul class="list-unstyled list-inline">
                                        <li><input type="radio" id="byBankCharge" name="summaryBy" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-green" /> Bank Charge</li>
                                        <li><input type="radio" id="byInsurancePremium" name="summaryBy" value="2" data-plugin="iCheck" data-radio-class="iradio_flat-blue" /> Insurance Premium</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-7 col-md-7" id="nonSummaryFilter">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Date Range: </label>
                                <div class="col-sm-9">
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
                    <hr />
                    
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">

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
