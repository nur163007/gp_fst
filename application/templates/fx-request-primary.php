<?php $title="FX Request";?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">All Fx Request</h1>
        <div class="page-header-actions hidden">

        </div>

    </div>

    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button type="button" class="btn btn-primary margin-bottom-25 text-right" id="primaryReqForRFQ" style="text-align: right">Create Request for RFQ</button>
                    </div>
                </div>
                <!-- Table-->
                <div class="example table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtFx">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Request Type</th>
                            <th>Supplier Name</th>
                            <th>Nature of Service</th>
                            <th>LC Bank</th>
                            <th>Currency</th>
                            <th>Value</th>
                            <th>Value Date</th>
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

<!-- End Page -->