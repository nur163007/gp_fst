<?php $title = "All Contracts"; ?>
<!-- Page-->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Admin</a></li>
            <li class="active">App Manager</li>
        </ol>
        <h1 class="page-title">All Contracts</h1>
        <div class="page-header-actions">
            <a class="btn btn-sm btn-inverse btn-round" href="contract" target="_blank">
                <i class="icon wb-plus" aria-hidden="true"></i>
                <span class="hidden-xs">Add New Contract</span>
            </a>
        </div>
    </div>

    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <!-- Table-->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtContracts">
                        <thead>
                        <tr>
                            <th rowspan="2">Id</th>
                            <th rowspan="2">Supplier</th>
                            <th rowspan="2">Contract Number</th>
                            <th class="text-center" colspan="3">Payment Terms (%)</th>
                        </tr>
                        <tr>
                            <th>Implement by GP</th>
                            <th>Implement by Supplier</th>
                            <th>Implement by Other</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="col-xlg-12 col-md-12 text-right">
                        <span id="exportBtn" class="pull-right"></span>
                        <span class="pull-right">Export to: &nbsp;</span>
                    </div>
                </div>
                <!-- End Table-->
            </div>
        </div>
    </div>
</div>
<!-- End Page -->