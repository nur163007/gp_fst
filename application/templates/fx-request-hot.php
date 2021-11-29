<?php $title = "Navigations"; ?>
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
<!--        <input type="hidden" id="hdnFxRequestId" name="hdnFxRequestId">-->

<!--        Modal Starts    -->

        <div class="modal fade modal-slide-in-top" id="statusModal" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header padding-0">
                        <div class="modal-body">
                            <div class="table-responsive" style="overflow-x: hidden;">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="panel margin-bottom-0">
                                            <div class="row">
                                                <div class="col-sm-10">
                                                    <h5 class="modal-title" id="exampleModalLongTitle" style="font-size: large">FX Request # <span id="modalFxRequestId"></span></h5>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="relodModal" name="relodModal">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--    nav-tav panel start    -->

                    <div class="panel">
                        <div class="row" style="padding: 0px 30px; height: 480px;">
                            <div class="col-sm-12">
                                <div class="example-wrap">
                                    <div class="nav-tabs-horizontal">
                                        <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                            <li class="active" role="presentation"><a data-toggle="tab" href="#exampleTabsOne" aria-controls="exampleTabsOne" role="tab">Bank Offers</a></li>
                                            <li role="presentation"><a data-toggle="tab" href="#exampleTabsTwo" aria-controls="exampleTabsTwo" role="tab">Messages</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="exampleTabsOne" role="tabpanel">
                                                <form class="form-horizontal" id="formstat" autocomplete="off">
                                                    <input type="hidden" id="hdnFxRequestId" name="hdnFxRequestId">
                                                    <div class="row">
                                                        <div class="col-xs-12" style="margin-top: 25px;">
                                                            <table class="table table-bordered table-hover dataTable table-striped width-full tab-pane active" id="dtFxhot">
                                                                <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>BankName</th>
                                                                    <th>Fx Rate</th>
                                                                    <th>Offer Amount</th>
                                                                    <th>Value Date</th>
                                                                    <th>Total Amount</th>
                                                                    <th>Remarks</th>
                                                                    <th>Potential Loss</th>
                                                                    <th>Deal Amount</th>
                                                                    <th>Select</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-7">
                                                            <div class="modal-footer2" style="padding: 15px">
                                                                <div class="row">
                                                                    <div class="col-sm-8">
                                                                        <textarea class="form-control" type="text" name="reject_note" id="reject_note" rows="3" placeholder="Rejection Cause" style="width: 100%;"></textarea>
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label class="wc-error pull-center" id="form_error"></label>
                                                                        <button type="button" class="btn btn-outline btn-danger" id="reject" style="text-align: left"><i class="icon fa-close" aria-hidden="true"></i>Reject</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            <div class="modal-footer" style="padding: 15px">
                                                                <label class="wc-error pull-center" id="form_error"></label>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="relodcloseModal" name="relodcloseModal">Close</button>
                                                                <button type="button" class="btn btn-outline btn-success" id="accept"><i class="icon fa-check-circle" aria-hidden="true"></i>Accept</button>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="tab-pane" id="exampleTabsTwo" role="tabpanel">
                                                <form class="form-horizontal" id="fxreqmesghot" name="fxreqmesghot" autocomplete="off">
                                                    <input type="hidden" id="fxLastMsgId" name="fxLastMsgId">
                                                    <input type="hidden" id="fxReqIdMsg" name="fxReqIdMsg">
                                                    <input type="hidden" name="postAction" value="1">
                                                    <input type="hidden" name="msgTitle" value="HOT Message">
                                                    <div class="tab-pane" id="otherPendings" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <!--                                                                        <h4 style="text-align: center">Message</h4>-->
                                                                <div class="row" style="margin: 10px; height: 250px;">
                                                                    <div class="col-xs-12" style="text-align: right; margin: 20px 0px;">
                                                                        <textarea type="text" class="form-control" id="FxConvMsg" name="FxConvMsg" placeholder="Type your Message" rows="3" maxlength="300" style="margin-top: 10px; background: white; border-radius: 15px;"></textarea>
                                                                        <div style="margin-bottom: 25px;"><span id="display_count">0/300</span></div>
                                                                        <button type="submit" class="btn-primary" id="hotmessage" style="border: none;border-radius: 3px ;padding: 8px;line-height: 20px; margin-bottom: 20px;">Send To FSO</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <!--                                                                        <h4 style="text-align: center">History</h4>-->
                                                                <div class="row">
                                                                    <div class="col-xs-12">
                                                                        <div class="example-wrap" id="exampleApi">
                                                                            <div class="example">
                                                                                <div class="height-350" id="exampleScollableApi" style="overflow:scroll;overflow-x:hidden;max-height:350px;border-left: 2px solid #cfcfcf;">
                                                                                    <div data-role="container">
                                                                                        <div data-role="content" id="messageLoopView" style="padding: 20px">
                                                                                            <div style="text-align: left" >
                                                                                                <div class="list-group-item" style=" background-color:#efefef; padding: 10px;border-radius: 25px; border-top-right-radius: 0px;margin-bottom: 20px;">
                                                                                                    <div class="media-body">
                                                                                                        <h6 class="media-heading" id="title">HOT</h6>
                                                                                                        <div class="media-meta">
                                                                                                            <time datetime="2015-06-17T20:22:05+08:00" id="chattime">
                                                                                                                12313
                                                                                                            </time>
                                                                                                        </div>
                                                                                                        <div class="media-detail" id="leftFSO">
                                                                                                            sfjsfgsfsd
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div style="text-align: right;">
                                                                                                <div style="margin: 30px 0px;background-color: #62a8ea;border-radius: 25px;border-bottom-left-radius: 0px;padding: 10px;">
                                                                                                    <div class="media">
                                                                                                        <div class="media-body">
                                                                                                            <h6 class="media-heading" style="color: #f5ffff;">HOT</h6>
                                                                                                            <div class="media-meta">
                                                                                                                <time datetime="2015-06-17T12:30:30+08:00" style="color: #f5ffff;">
                                                                                                                    15 minutes ago
                                                                                                                </time>
                                                                                                            </div>
                                                                                                            <div class="media-detail" style="color: #f5ffff;">I checheck the document. But there seems
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--    nav-tav panel end    -->

                </div>
            </div>
        </div>
    </div>
    </div>

<!--        Modal Ends      -->

<!--    Page body      -->

    <div class="page-content">
        <!-- Panel -->
        <div class="panel" style="min-height: 350px;">
            <div class="panel-body container-fluid">
                <!-- Data Table Start -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtFx">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Supplier Name</th>
                            <th>Nature of Service</th>
                            <th>Requisition Type</th>
                            <th>Currency</th>
                            <th>Value</th>
                            <th>Value Date</th>
                            <th>Cutts of Date</th>
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