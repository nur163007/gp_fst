<?php $title = "FX Settelment Pending to Finance"; ?>

<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">Fx Settlement Pending</h1>
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
                                <form id="formFxSettlementPendingFn" name="formFxSettlementPendingFn">
                                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="tableFxSettlementPendingFn">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th></th>
                                                <th>PO#</th>
                                                <th>LC#</th>
                                                <th>Ship#</th>
                                                <th>Doc Name</th>
                                                <th>Payment Part</th>
                                                <th>Amount</th>
                                                <th>CI Amount</th>
                                                <th>Cur.</th>
                                                <th>Bank</th>
                                                <th>Value Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnSendForFXSettlement">Send for FX Settlement</button>
                                    <button type="button" class="btn btn-default btn-outline" id="close_btn">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Panel End -->
    </div>
</div>
<!-- Page End -->
