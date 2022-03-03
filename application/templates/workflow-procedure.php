<?php $title="Action";?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Admin</a></li>
            <li class="active">Action</li>
        </ol>
        <h1 class="page-title">All Action</h1>
        <div class="page-header-actions">
            <button class="btn btn-sm btn-inverse btn-round" data-target="#actionForm" data-toggle="modal" >
                <i class="icon wb-plus" aria-hidden="true"></i>
                <span class="hidden-xs">Add New Action</span>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade modal-slide-in-top" id="actionForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
            <div class="modal-dialog">
                <form class="form-horizontal" id="form-action" name="form-action" autocomplete="off" >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title">Action Form</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="actionId" name="actionId" value="0" >
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ID: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="id" name="id" placeholder="Give an Id"/>
                                    <label class="wc-error" id="idError"></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Action Done: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="actionDone" name="actionDone" placeholder="Done Action.."/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Action Done By: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" name="actionDoneBy" id="actionDoneBy">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Action Pending: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="actionPending" name="actionPending" placeholder="Pending Action"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Action Pending To: </label>
                                <div class="col-sm-6">
                                    <select class="form-control" data-plugin="select2" name="actionPendingTo" id="actionPendingTo" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cc: </label>
                                <div class="col-sm-6">
                                    <input type="email" class="form-control" id="cc" name="cc" placeholder="Give an email if any"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Target Form: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="targetForm" name="targetForm" placeholder="Give the target form"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SLA: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="sla" name="sla" placeholder="Give SLA value"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Stage: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="stage" name="stage" placeholder="Give the stage"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Serial No: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="serialNo" name="serialNo" placeholder="Give a serial no"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Rejected (if): </label>
                                <div class="col-sm-2">
                                    <div class="checkbox-custom checkbox-primary">
                                        <input type="checkbox" id="isRejected" name="isRejected" />
                                        <label for="isRejected"></label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="model-footer text-right">
                                <label class="wc-error pull-left" id="form_error"></label>
                                <button type="button" class="btn btn-primary" id="btnActionFormSubmit" >Submit</button>
                                <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Close</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- End Modal -->
    </div>

    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <!-- Table-->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtActionTable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>ActionDone</th>
                            <th>ActionDoneBy</th>
                            <th>ActionPending</th>
                            <th>ActionPendingTo</th>
                            <th>TargetForm</th>
                            <th>Stage</th>
                            <th>SerialNo</th>
                            <th class="text-center" style="width:80px;">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- End Table-->
            </div>
        </div>
    </div>
</div>
<div class="site-action">
    <button type="button" class="btn-raised btn btn-success btn-floating" data-target="#actionForm"
            data-toggle="modal" data-toggle="Add new Nav" data-original-title="Add new Nav">
        <i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
    </button>
</div>
<!-- End Page -->