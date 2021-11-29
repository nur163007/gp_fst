<?php $title="Amendment Entry"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <ol class="breadcrumb">
            <li><a href="../index.html">General</a></li>
            <li class="active">Letter of Credit</li>
        </ol>
        <h1 class="page-title">C &amp; F Cost Update</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="cnf-cost-update-form" name="cnf-cost-update-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <h4 class="well well-sm example-title">C &amp; F Costs</h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">GP Reference No: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="gpRefNum" id="gpRefNum" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="lcno" name="lcno" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HAWB No: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="MawbNum" name="MawbNum" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">MAWB NO: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="HawbNum" name="HawbNum" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">BL NO: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="BlNum" name="BlNum" value="" readonly="" />
                                </div>
                            </div>
                        </div>
                        <!--Right-->
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="ciValue" name="ciValue" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CNF Agent: </label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="cnfAgent" id="cnfAgent" >
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">C &amp; F Amount: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="cNfAmount" name="cNfAmount" value="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cost Update Status: </label>
                                <div class="col-sm-8 margin-top-5">
                                    <input type="checkbox" class="icheckbox-primary" id="costUpdateStatus" name="costUpdateStatus" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue" />
                                </div>
                            </div>
                            <div class="form-group">
                               <label class="col-sm-4 control-label">Remarks: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="cnfCostUpdate_btn">Update C &amp; F Costs</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->