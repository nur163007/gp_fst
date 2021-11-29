<?php $title="Outstanding LC list"; ?>
<div class="page bg-blue-100 animsition">

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <!--form class="form-horizontal" id="outstanding-form" name="outstanding-form" method="post" autocomplete="off"-->
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Outstanding LC Report
                                <span class="pull-right">
                                    <input type="checkbox" class="icheckbox-primary" name="isSummary" id="isSummary"
                                        data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" /> <span class="text-capitalize ">Show Summary Report</span>
                                </span>
                            </h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <div class="col-xlg-12 col-md-12 hidden" id="summaryFilter">
                            <div class="form-group">
                                <label class="col-sm-2 col-lg-2 col-xlg-1 control-label">Summary By: </label>
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
                                <label class="col-sm-1 control-label">Bank: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" name="bank" id="bank" >                                                                        
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
                                        <option value="0">Expired</option>
                                        <option value="90">To be Expired in 3 months</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div style="overflow-x: auto;">
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtOutstanding">
                                    <thead>
                                        <th>LC#</th>
                                        <th>Bank</th>
                                        <th>PO#</th>
                                        <th>Supplier</th>
                                        <th>Item Description</th>
                                        <th>LC Opening Date</th>
                                        <th>Currency</th>
                                        <th>LC Value</th>
                                        <th>Endorsement Value</th>
                                        <th>Total Payment</th>
                                        <th>Outstanding On LC Value</th>
                                        <th>Outstanding On End Value</th>
                                        <th>Days to Expire</th>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7"><span class="pull-right">Total:</span></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small hidden" id="dtOutstandingSummary">
                                    <thead>
                                        <th id="sumColumnName" class="text-capitalize">Bank/Supplier</th>
                                        <th>LC Count</th>
                                        <th>LC Value</th>
                                        <th>Endorsement Value</th>
                                        <th>Total Payment</th>
                                        <th>Outstanding On LC Value</th>
                                        <th>Outstanding On End Value</th>
                                        <th>Expired</th>
                                        <th>Live</th>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <td><span class="pull-right">Total:</span></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
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
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>

                    </div>
                    <!-- Payment Modal -->
            		<div class="modal fade modal-slide-in-top" id="formPaymentData" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
            			<div class="modal-dialog">
        					<div class="modal-content">
        						<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
        								<span aria-hidden="true">x</span>
        							</button>
        							<h4 class="modal-title">Payment Detail</h4>
        						</div>
        						<div class="modal-body">
        							<div class="row row-lg">
                                        <div class="col-xlg-12 col-md-12">
                                            <div>
                                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtOutstandingReport">
                                                    <thead>
                                                        <th>CI Value</th>
                                                        <th>AWB/BL Date</th>
                                                        <th>Amount</th>
                                                        <th>in USD</th>
                                                        <th>in BDT</th>
                                                        <th>Ex. Rate</th>
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
                                            <div class="form-group">
                                                <div class="col-sm-12 text-right" id="buttons">
                                                    <!--button type="button" class="btn btn-primary" id="export_btn"><i class="icon fa-download" aria-hidden="true"></i> Export to Excel </button-->
                                                </div> 
                                            </div>                            
                                        </div>
                                    </div>
        							<hr />
        							<div class="model-footer text-right">
        								<button type="button" class="btn btn-default btn-outline"data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
        							</div>
        						</div>
        					</div>
            			</div>
            		</div>
            		<!-- End Modal -->
                    <!-- Endorsement Modal -->
            		<div class="modal fade modal-slide-in-top" id="formEndorsedData" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
            			<div class="modal-dialog">
        					<div class="modal-content">
        						<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
        								<span aria-hidden="true">x</span>
        							</button>
        							<h4 class="modal-title">Endorsement Detail</h4>
        						</div>
        						<div class="modal-body">
        							<div class="row row-lg">
                                        <div class="col-xlg-12 col-md-12">
                                            <div>
                                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="dtOutstandingReport">
                                                    <thead>
                                                        <th>CI Value</th>
                                                        <th>AWB/BL Date</th>
                                                        <th>Amount</th>
                                                        <th>in USD</th>
                                                        <th>in BDT</th>
                                                        <th>Ex. Rate</th>
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
                                            <div class="form-group">
                                                <div class="col-sm-12 text-right" id="buttons">
                                                    <!--button type="button" class="btn btn-primary" id="export_btn"><i class="icon fa-download" aria-hidden="true"></i> Export to Excel </button-->
                                                </div> 
                                            </div>                            
                                        </div>
                                    </div>
        							<hr />
        							<div class="model-footer text-right">
        								<button type="button" class="btn btn-default btn-outline"data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
        							</div>
        						</div>
        					</div>
            			</div>
            		</div>
            		<!-- End Modal -->
                <!--/form-->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->

?>