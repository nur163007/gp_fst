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
                            <h4 class="well well-sm example-title">Aging Report
                                <!--<span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Show Summary Report</span>
                                </span>-->
                            </h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <!--<div class="col-xlg-12 col-md-12 hidden" id="summaryFilter">
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
                                <label class="col-sm-1 control-label">Invoice #: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" name="ciList" id="ciList" >
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Supplier: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >                          
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Status:</label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" name="expiryStatus" id="expiryStatus" >
                                        <option value=""></option>
                                        <option value="expired">Expired</option>
                                        <option value="toBeExpired">To be Expired in 3 months</option>
                                    </select>
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
