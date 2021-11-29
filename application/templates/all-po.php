<?php $title="All Purchase Order"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">General</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">All Purchase Order</h1>

        <input type="hidden" id="loginRole" value="<?php echo $_SESSION[session_prefix.'wclogin_role']?>"/>

        <!-- Modal -->
        <div class="modal fade modal-slide-in-top" id="closePOForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
            <div class="modal-dialog">
                <form class="form-horizontal" id="form-close-po" name="form-close-po" autocomplete="off" >
                    <input type="hidden" name="action" value="1" />
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Close/Cancel PO</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PO NO:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="poNo" name="poNo" value="" readonly>
                                    <input type="hidden" name="shipNo" id="shipNo" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label" for="action_type">Type:</label>
                                <div class="col-sm-9">
                                    <select class="form-control small" data-plugin="select2" name="action_type" id="action_type">
                                        <option disabled selected>Choose one of the following</option>
                                        <option value="<?php echo action_PO_Cancel?>">Cancel</option>
                                        <option value="<?php echo action_Close_PO?>">Close</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Justification</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="closeJstifctn" id="closeJstifctn" cols="30" rows="5"></textarea>
                                </div>
                            </div>

                            <hr />
                            <div class="modal-footer text-right">
                                <label class="wc-error pull-left" id="form_error"></label>
                                <button type="button" class="btn btn-danger" id="btnClosePo" >Submit</button>
                                <button type="button" class="btn btn-default btn-outline pull-left" data-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
    </div>
    
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                
                <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtAllPo">
                    <thead class="small">
                        <tr class="nomargin ">
                            <th>PO No.</th>
                            <th>PO Value</th>
                            <th>FC</th>
                            <th>Buyer</th>
                            <th>Supplier</th>
                            <th>PO Desc.</th>
                            <th>LC No.</th>
                            <th>GP Ref: no</th>
                            <th>Status</th>
                            <th>Action</th>
                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_Admin){ ?>
                                <th>Remarks</th>
                                <th>Cancel/Close PO</th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody class="small">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->