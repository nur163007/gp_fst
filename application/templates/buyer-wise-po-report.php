<?php $title="Buyer wise Report"; ?>
    <div class="page bg-blue-100 animsition">
    <!--<div class="page-header">
        <h1 class="page-title">Buyer wise Report</h1>
        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li class="active">Buyer wise Report</li>
        </ol>
        <div class="page-header-actions">
			 
		</div>        
    </div>-->
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="lc-wise-form" name="lc-wise-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title"><i class="fa fa-file-text-o"></i>  Buyer wise PO Report</h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Buyer: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" name="buyerList" id="buyerList" >
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label">Supplier: </label>
                                <div class="col-sm-5">
                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                    </select>
                                </div>
                                <label class="col-sm-1 control-label hidden">Date: </label>
                                <div class="col-sm-4 hidden">
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
                            <div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable">
                                    <thead>
                                    <th>PO Number</th>
                                    <th>PO Buyer</th>
                                    <th>Supplier</th>
                                    <th>PO Description</th>
                                    <th>PO & BOQ Sent to Vendor</th>
                                    <th>PO need by date</th>
                                    <th>PI & BOQ Receive Date</th>
                                    <th>Lead Time (Days)</th>
                                    <th>Discount</th>
                                    <th>Request for BTRC Permission</th>
                                    <th>BTRC Permission Received</th>
                                    <th>Apply for LC</th>
                                    <th>LC Receive Date</th>
                                    <th>Soft Scan Copy DOC Rec.Date</th>
                                    <th>Pre-Alert & GIT receiving & Doc Endorse mail</th>
                                    <th>GIT received Date</th>
                                    <th>AWB No / BL No</th>
                                    <th>C.Invoice Number</th>
                                    <th>C.Invoice Date</th>
                                    <th>C.Invoice Amount</th>
                                    <th>Description (For partial shipment only)</th>
                                    <th>Voucher No</th>
                                    <th>V.Creation Date</th>
                                    <th>ETA</th>
                                    <th>Actual Arrival at WH</th>
                                    </thead>
                                </table>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12 text-right" id="buttons">
                                    <!--button type="button" class="btn btn-primary" id="export_btn"><i class="icon fa-download" aria-hidden="true"></i> Export to Excel </button-->
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtn" class="pull-right"></span>
                            <span class="pull-right">Export to:  </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->