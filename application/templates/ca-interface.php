<?php $title="CORPORATE AFFAIRS";?>
<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

?>
<div class="page animsition">

    <input type="hidden" id="currentRole" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
    <input type="hidden" id="currentBuyer" value="<?php echo $_SESSION[session_prefix.'wclogin_username']; ?>" />

    <div class="page-content container-fluid">

        <div class="row" id="pendingsRow">

            <div class="col-xlg-12 col-lg-12 col-md-12" id="myPendingsBlock">

                <div class="widget widget-shadow">
                    <div class="widget-content widget-radius bg-white padding-30 padding-top-10" style="min-width:480px; min-height:379px; ">
                        <div class="panel nav-tabs-horizontal">
                            <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                <li class="active dropdown" role="presentation">
                                    <a data-toggle="tab" href="#myPendings" aria-controls="myPendings" role="tab"><span class="hot">MY PENDING</span></a>
                                </li>
                                <li role="presentation">
                                    <a data-toggle="tab" href="#otherPendings" aria-controls="otherPendings" role="tab">OTHER PENDING</a>
                                </li>
                            </ul>
                            <form class="form-horizontal" id=ca-form" name="ca-form" method="post" autocomplete="off" action="">
                                       <input name="userAction" id="userAction" type="hidden" value="" />
                            <div class="tab-content padding-top-5">

                                <div class="tab-pane active" id="myPendings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-hover dataTable table-striped width-full small" id="dtMyInbox">
                                                <thead>
                                                <tr class="nomargin ">
                                                    <th>ID</th>
                                                    <th>RefID</th>
                                                    <th><input type="checkbox" disabled></th>
                                                    <th>PO#</th>
                                                    <th>Status</th>
                                                    <th>Stage</th>
                                                    <th>Buyer</th>
                                                    <th>ActionOn</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="text-left" style="margin-top: 40px!important;">
                                        <div class="form-group">
                                            <label class="col-sm-2" style="font-weight: bold;">BTRC Division :</label>
                                            <div class="col-sm-3">
                                                <select class="form-control" data-plugin="select2" name="btrc_div" id="btrc_div" >
                                                </select>
                                            </div>
                                        </div>
<!--                                        <lebel>BTRC Division</lebel>-->
<!--                                        <input type="radio" id="btrc_div" name="btrc_div" >-->
                                        <button type="button" class="btn btn-success p-5 mt-3 mb-3" id="btnCaSubmit" >Send to PRA </button>
                                    </div>

                                </div>

                                <div class="tab-pane" id="otherPendings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-hover dataTable table-striped width-full small" id="dtOtherInbox">
                                                <thead>
                                                <tr class="nomargin ">
                                                    <th>ID</th>
                                                    <th>RefID</th>
                                                    <th>PO#</th>
                                                    <th>Status (pending for)</th>
                                                    <th>Stage</th>
                                                    <th>Buyer</th>
                                                    <th>Pending To</th>
                                                    <th>ActionOn</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
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
<!-- End Page -->