<?php $title = "FX Request"; ?>

<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">Fx Settlement Report</h1>
        <div class="page-header-actions hidden">

        </div>
    </div>
    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">

                <div class="col-xlg-12 col-md-12">
                    <div class="row" style="min-height : 250px;">
                        <div class="col-xs-12">
                            <div class="example table-responsive">
                                <form id="formFxSettementReport" name="formFxSettementReport">
                                    <table class="table table-bordered table-hover dataTable table-striped width-full"
                                           id="tableFxSettementReport">
                                        <thead>
                                        <tr>
                                            <th>Fx RequestID#</th>
                                            <th>Request Date</th>
                                            <th>Supplier Name</th>
                                            <th>Nature Of Service</th>
                                            <th>Requisition Type</th>
                                            <th>Currency</th>
                                            <th>Fx Value</th>
                                            <th>Value Date</th>
                                            <th>Bank Name</th>
                                            <th>Fx Rate</th>
                                            <th>Deal Amount</th>
                                            <th>Potential Loss</th>
                                            <th>Remarks</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xlg-12 col-md-12 text-right">
                            <span id="exportBtnForFxSettlementReport" class="pull-right"></span>
                            <span class="pull-right">Export to: &nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Panel End -->
    </div>
</div>
    <!-- Page End -->