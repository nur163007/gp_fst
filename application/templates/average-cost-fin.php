<?php
$title="Average Cost";

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);

?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        
        <h1 class="page-title">Average Cost Update</h1>
        
        <ol class="breadcrumb">
            <li><a>Finance: </a></li>
            <li class="active">Operation</li>
        </ol>
		
        <div class="page-header-actions">
			&nbsp;
		</div>        
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="averagecost-form" name="averagecost-form" method="post" autocomplete="off">
                    <input name="pono1" id="pono1" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    
                    <div class="row row-lg">
                    
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Shipment Information</h4>
                        </div>
                    
                    </div>
                    
                    <div class="row row-lg">
                        
                        <div class="col-xlg-5 col-md-5">
                            <div class="form-group">
                                <label class="col-sm-5 control-label">PO No: </label>
                                <div class="col-sm-7">
                                    <!--select class="form-control" data-plugin="select2" name="PoNo" id="PoNo" >                                                                        
                                    </select-->
                                    <input type="text" class="form-control" id="pono" name="pono" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="LcNo" name="LcNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">LC Value: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="LcValue" name="LcValue" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">BL No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="BLNo" name="BLNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">MAWB No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="MAWBNo" name="MAWBNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">HAWB No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="HAWBNo" name="HAWBNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">CI Value: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control curnum" id="CIValue" name="CIValue" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Ref No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="gpRefNo" name="gpRefNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">IPC No: </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" id="ipcNo" name="ipcNo" readonly="" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            
                            <div class="form-group">
                                <label class="col-sm-6 control-label">LC Opening Charge (Capex): </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="lcOpenCharge" name="lcOpenCharge" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Insurance Charge (Capex): </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="insPremium" name="insPremium" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Custom Duty: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="customDuty" name="customDuty" readonly="" />
                                </div>
                            </div>
                            
                            <h4 class="well well-sm example-title">Consignment Wise Proportionate Cost:</h4>
                            
                            <div class="form-group">
                                <label class="col-sm-6 control-label">LC Opening Charge (Capex): </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="lcOpenCharge1" name="lcOpenCharge1" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Insurance Charge (Capex): </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="insPremium1" name="insPremium1" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">C&amp;F Net Payment: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="cnfNetPayment" name="cnfNetPayment" value="0.00" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Proportionate Cost: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control curnum" id="proportionateCost" name="proportionateCost" value="0.00" />
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <hr />
                    
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Average cost update data
                                <span id="exportBtn" class="pull-right"></span>
                                <span class="pull-right">Export to: &nbsp;</span>
                                <!--button class="btn btn-sm btn-inverse btn-round pull-right" style="margin-top: -8px;" id="refreshCostData_btn">
                                    <i class="icon wb-refresh" aria-hidden="true"></i>
                                    <span class="hidden-xs">Refresh</span>
                                </button-->
                            </h4>
                            
                            <div>
                                <table class="table table-bordered table-hover dataTable table-striped width-full small" id="csvAvgCost">
                                    <thead style="font-weight: bold;">
                                        <th>PO#</th>
                                        <th>IPCNo</th>
                                        <th>POLine</th>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                        <th>ItemWiseCost</th>
                                        <th>Currency</th>
                                    </thead>
                                    <tfoot>
                                        <tr style="font-weight: bold;">
                                            <td colspan="8"><span class="pull-right">Total:</span></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><br/>
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="save_btn"><i class="icon fa-save" aria-hidden="true"></i> Save Average Cost </button>
                                </div> 
                            </div>                            
                        </div>
                    </div>
                </form>
                <hr />
                <form class="form-horizontal" id="updateNotify-form" name="updateNotify-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6 text-right">
                            
                        </div>
                        <div class="col-xlg-6 col-md-6 text-right">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Remarks: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="Remarks" id="Remarks" rows="3"></textarea>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="notify_btn"><i class="icon fa-bell" aria-hidden="true"></i> Notify to Warehouse </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->