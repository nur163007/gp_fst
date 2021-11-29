<?php
$title="Bankdashboard";
require_once(LIBRARY_PATH . "/csrf_token.php");

?>
<?php $title="Dashboard";?>
<style>
    #examplePie {
        max-width: 350px;
    }

    .pie-progress {
        max-width: 150px;
        margin: 0 auto;
    }

    .pie-progress svg {
        width: 100%;
    }

    .pie-progress-xs {
        max-width: 50px;
    }

    .pie-progress-sm {
        max-width: 100px;
    }

    .pie-progress-lg {
        max-width: 200px;
    }

    .example.inline-block {
        margin-right: 30px;
    }

    .page-content .dropdown-menu {
        width: 240px;
    }

    .blocks-dropdowns > li {
        margin-bottom: 50px;
    }

    @media (min-width: 992px) {
        .blocks-dropdowns > li {
            max-width: 300px;
        }
    }

    .blocks-dropdowns .dropdown-menu {
        width: 100%;
    }

    canvas{
        width: 100% !important;
        max-width: 800px;
        max-height: 400px;
        height: auto !important;
    }
    #dtMyInbox_filter {
        display: none;
    }
    @media (min-width: 768px) {
        .modal-xl {
            width: 90%;
            max-width:1200px;
        }
    }
</style>
<!-- Page -->

<div class="page animsition">
    <input type="hidden" id="cobankid" value="<?php echo $_SESSION[session_prefix . 'wclogin_company']; ?>" />
    <input type="hidden" id="currentRole" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
    <input type="hidden" id="currentBuyer" value="<?php echo $_SESSION[session_prefix.'wclogin_username']; ?>" />

    <div class="page-content container-fluid">

        <div class="row" id="pendingsRow">

            <div class="col-xlg-7 col-lg-12 col-md-7" id="myPendingsBlock">
                <div class="widget widget-shadow">
                    <div class="widget-content widget-radius bg-white padding-30 padding-top-10" style="min-width:480px; min-height:auto; ">
                        <div class="panel nav-tabs-horizontal">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4>Fx RFQ Request</h4>
                                </div>
                                <div class="col-sm-4 text-right">
                                    <div class="row text-right">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-check form-check-inline margin-top-10">
                                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                                                        <label class="form-check-label" for="inlineRadio1">Pending</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 text-left">
                                                    <div class="form-check form-check-inline margin-top-10">
                                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                        <label class="form-check-label" for="inlineRadio2">Submitted</label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content padding-top-5">
                                <div class="tab-pane active" id="myPendings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <form>
                                                <table class="table table-hover dataTable table-striped width-full small" id="dtMyInbox">
                                                    <thead>
                                                    <tr class="nomargin ">
                                                        <th>Id</th>
                                                        <th>Fx Request Id</th>
                                                        <th>Fx Value</th>
                                                        <th>Currency</th>
                                                        <th>Fx Date</th>
                                                        <th>CuttsOffTime</th>
                                                        <th>Offer Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </form>
                                            <form id="frmBankRate">
                                                <div class="modal fade modal-slide-in-top" id="statusBankModal" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" data-backdrop="static" data-keyboard="false">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header" style="padding: 0px 15px">
                                                                    <div class="row well well-sm example-title" style="line-height: 10px; padding: 12px">
                                                                        <div class="col-sm-11">
                                                                            <h4 class="modal-title">FX OFFER</h4>
                                                                        </div>
                                                                        <div class="col-sm-1">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">×</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <div class="row">
                                                                            <input type="hidden" name="fxrFqRowId" id="fxrFqRowId" value="" >
                                                                            <div class="form-group">
                                                                                <label class="col-sm-6 control-label text-right">Fx Request ID :</label>
                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label text-left" id="fx_req_id"><img src="assets/images/busy.gif" /></b></label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="col-sm-6 control-label text-right">Fx Value :</label>
                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label text-left" id="fx_value"><img src="assets/images/busy.gif" /></b></label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="col-sm-6 control-label text-right">Value Date :</label>
                                                                                <div class="col-sm-6">
                                                                                    <label class="control-label text-left" id="fx_date"><img src="assets/images/busy.gif" /></b></label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group">
                                                                            <label class="col-sm-6 control-label text-right">Currency :</label>
                                                                            <div class="col-sm-6">
                                                                                <label class="control-label text-left" id="currency"><img src="assets/images/busy.gif" /></b></label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-sm-6 control-label text-right">Cuttsoff Time :</label>
                                                                            <div class="col-sm-6">
                                                                                <label class="control-label text-left" id="cuttsofftime"><img src="assets/images/busy.gif" /></b></label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <hr>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-sm-5">
                                                                        <div class="row">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-7 control-label text-right margin-top-5">Fx Rate :</label>
                                                                                <div class="col-sm-5 text-left">
                                                                                    <input type="number" class="form-control" id="FxRate" name="FxRate">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row margin-top-20">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-7 control-label text-right margin-top-5">Remarks :</label>
                                                                                <div class="col-sm-5 text-left">
                                                                                    <input type="text" class="form-control" id="remarks" name="remarks">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-7">
                                                                        <div class="row ">
                                                                            <div class="form-group">
                                                                                <label class="col-sm-7 control-label text-right margin-top-5">Offered Volume Amount :</label>
                                                                                <div class="col-sm-5 text-left">
                                                                                    <input type="number" class="form-control" id="OfferedVolumeAmount" name="OfferedVolumeAmount">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row margin-top-50">
                                                                            <div class="col-sm-9 text-right margin-top-5">
                                                                                <label class="wc-error pull-center" id="form_error"></label>
                                                                            </div>
                                                                            <div class="col-sm-3 text-right">
                                                                                <button type="button" class="btn btn-primary" id="btnFxRfqRequest">Submit</button>
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
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<!-- End Page -->