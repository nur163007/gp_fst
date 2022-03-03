<?php $title = "Po-History"; ?>

<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">PO Approval History</h1>
        <div class="page-header-actions hidden">

        </div>
    </div>
    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">


                <div class="col-xlg-12 col-md-12">
                    <form id="formPoHistory" name="formPoHistory" method="post">
                        <!--modal start action log delete-->
                        <div class="modal fade modal-slide-in-top" id="ActionLogDeleteForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                            <div class="modal-dialog">
                                   <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h4 class="modal-title">Please Give a Justification</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" rows="5" cols="30" name="remarks" id="remarks" placeholder="Write something...."></textarea>
                                                </div>
                                            </div>

                                            <div class="model-footer text-left">
                                                <button type="button" class="btn btn-danger" id="btnDeleteAction" style="margin: 15px!important;">Submit</button>
                                                <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();"style="margin: 15px!important;">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <!--modal end action log delete-->

                        <!--modal start delete info-->
                        <div class="modal fade modal-slide-in-top" id="ActionLogDeleteInfo" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="tab-pane active" id="tabPOInfo" role="tabpanel">
                                            <div class="form-horizontal">

                                                <div class="row row-lg">
                                                    <div class="col-xlg-12 col-md-12">
                                                        <h4 class="well well-sm example-title">Action log delete information</h4>
                                                        <div class="form-group">
                                                            <label class="col-sm-5 control-label text-left">PO No:</label>
                                                            <div class="col-sm-7">
                                                                <label class="control-label"><b id="pono"><img src="assets/images/busy.gif" /></b></label>
                                                            </div>
                                                            <label class="col-sm-5 control-label text-left">Status:</label>
                                                            <div class="col-sm-7">
                                                                <label class="control-label"><b id="status"><img src="assets/images/busy.gif" /></b></label>
                                                            </div>
                                                            <label class="col-sm-5 control-label text-left">Deleted By:</label>
                                                            <div class="col-sm-7">
                                                                <label class="control-label" id="deletedBy"><img src="assets/images/busy.gif" /></label>
                                                            </div>
                                                            <label class="col-sm-5 control-label text-left">Time:</label>
                                                            <div class="col-sm-7">
                                                                <label class="control-label text-left" id="deletedOn"><img src="assets/images/busy.gif" /></label>
                                                            </div>
                                                            <label class="col-sm-5 control-label text-left">Remarks:</label>
                                                            <div class="col-sm-7">
                                                                <label class="control-label"><b id="deleteRemarks"><img src="assets/images/busy.gif" /></b></label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                            <hr>

                                        </div>
                                        <div class="model-footer text-right">
<!--                                            <button type="button" class="btn btn-danger" id="btnDeleteAction" style="margin: 15px!important;">Delete</button>-->
                                            <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--modal end delete info-->

                    <div class="col-md-8 margin-bottom-25" style="margin-left: -28px;font-size: 17px">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Select PO Number:</label>
                            <div class="col-sm-6">
                                <select class="form-control" data-plugin="select2" name="poNo" id="poNo">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="min-height : 250px;">
                        <div class="col-xs-12">
                            <div class="example table-responsive">

                                    <table class="table table-bordered table-hover dataTable table-striped width-full"
                                           id="tablePoHistory">
                                        <thead>
                                        <tr>
                                            <th style="text-align: center">Id#</th>
                                            <th style="text-align: center">Action Done</th>
                                            <th style="text-align: center">Date</th>
                                            <th style="text-align: center">Action BY</th>
                                            <th style="text-align: center">Pending To</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>

                            </div>
                        </div>

                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Panel End -->
    </div>
</div>
<!-- Page End -->