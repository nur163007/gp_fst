<?php
$title = "Navigations"; ?>
<style>
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width: 1200px;
        }
</style>

<!--       Page Start      -->

<div class="page animsition" style="overflow-x: hidden">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
        </ol>
        <h1 class="page-title">All Fx Request</h1>
        <div class="page-header-actions hidden">

        </div>

        <!--        Modal Starts    -->

    </div>

    <!--        Modal Ends      -->

    <!--    Page body      -->

    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row text-left">

                    <!--   Radio Button Start -->

                    <div class="col-sm-6">
                        <div class="row margin-bottom-20">
                            <div class="col-sm-6"><h4 class="margin-top-10 text-right"><b>Settlement Status</b></h4></div>
                            <div class="col-sm-3">
                                <div class="form-check form-check-inline margin-top-10">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                                    <label class="form-check-label" for="inlineRadio1">Pending</label>
                                </div>
                            </div>
                            <div class="col-sm-3 text-left">
                                <div class="form-check form-check-inline margin-top-10">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                    <label class="form-check-label" for="inlineRadio2">Done</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--   Radio Button End -->

                    <div class="col-sm-6"></div>
                </div>
                <!-- Data Table Start -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtFeePayment">
                        <thead>
                        <tr>
                            <th>Req#</th>
                            <th>Supplier</th>
                            <th>Service</th>
                            <th>Cur</th>
                            <th>Value</th>
                            <th>Date</th>
                            <th>Status</th>
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

    <!--    Page body end   -->

</div>

<!--        Page end        -->