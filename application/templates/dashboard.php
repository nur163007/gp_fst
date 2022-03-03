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
</style>
<!-- Page -->

<div class="page animsition">

    <input type="hidden" id="currentRole" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
    <input type="hidden" id="currentBuyer" value="<?php echo $_SESSION[session_prefix.'wclogin_username']; ?>" />
    <?php if($_SESSION[session_prefix.'wclogin_role']!=role_coupa_user){ ?>
    <div class="page-content container-fluid">

        <div class="row" id="pendingsRow">
        
            <div class="col-xlg-7 col-lg-7 col-md-7" id="myPendingsBlock">

                <div class="widget widget-shadow">
                    <div class="widget-content widget-radius bg-white padding-30 padding-top-10" style="min-width:480px; min-height:379px; ">
                        <div class="panel nav-tabs-horizontal">
                            <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                <li class="active dropdown" role="presentation">
                                    <a data-toggle="tab" href="#myPendings" aria-controls="myPendings" role="tab"><span class="hot">MY PENDING</span></a>
                                </li>
                                <?php if(in_array($_SESSION[session_prefix.'wclogin_role'],
                                    array(role_Supplier, role_bank_fx, role_bank_lc, role_insurance_company,
                                        role_cnf_agent, role_foreign_payment_team,
                                        role_foreign_strategy))!=1){ ?>
                                <li role="presentation">
                                    <a data-toggle="tab" href="#otherPendings" aria-controls="otherPendings" role="tab">OTHER PENDING</a>
                                </li>
                                <?php }?>
                            </ul>
                            <div class="tab-content padding-top-5">

                                <div class="tab-pane active" id="myPendings" role="tabpanel">
                                    <div class="row padding-top-0">
                                        <div class="col-xs-5 text-right">
                                            <input type="text" id="dtMyInbox_filter_new" class="dataTables_filter form-control input-sm" placeholder="Search">
                                        </div>
                                        <div class="col-xs-7 text-right">
                                            <?php if($_SESSION[session_prefix.'wclogin_role']==role_Buyer){ ?>
                                                <label class="text-primary small">Buyer: </label>
                                                <div class="btn-group dropdown">
                                                <button class="btn btn-default dropdown-toggle bg-white btn-sm" id="buyersList"
                                                    type="button" data-toggle="dropdown" aria-expanded="false" style="border-radius: 20px; width: 100px;">
                                                    Select Buyer
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu bullet dropdown-menu-right" role="menu" id="buyerList" aria-labelledby="buyersList"></ul>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-hover dataTable table-striped width-full small" id="dtMyInbox">
                                                <thead>
                                                <tr class="nomargin ">
                                                    <th>ID</th>
                                                    <th>RefID</th>
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
                        </div>
                    </div>
                </div>

                <?php if($_SESSION[session_prefix.'wclogin_role']!=role_Supplier){ ?>

                <!--<div class="widget widget-shadow">
                    <div class="widget-content widget-radius  bg-white padding-30">
                        <div class="row padding-bottom-20">
                            <div class="col-xs-6">
                                <div class="blue-grey-700">LC PROCESS STATUS</div>
                            </div>
                            <div class="col-xs-6">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-hover dataTable table-striped width-full small" id="dtLCWiseAct">
                                    <thead>
                                    <tr class="nomargin ">
                                        <th>PO</th>
                                        <th>Status</th>
                                        <th>Pending For</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>-->

                <?php }?>
            </div>
            <?php /*if($_SESSION[session_prefix.'wclogin_role']!=role_Supplier){ */?>
            <?php if(in_array($_SESSION[session_prefix.'wclogin_role'],
                    array(role_Supplier, role_bank_fx, role_bank_lc, role_insurance_company,
                        role_cnf_agent, role_foreign_payment_team,
                        role_foreign_strategy))!=1){ ?>
                <!-- PO operation bar chart -->
            <div class="col-xlg-5 col-lg-5 col-md-5">

                <div class="widget widget-shadow" id="widgetBar">
                    <div class="widget-content widget-radius  bg-white padding-30">
                        <div class="row padding-bottom-20">
                            <div class="col-xs-4">
                                <div class="blue-grey-700">PO OPERATION</div>
                            </div>
                            <div class="col-xs-8">
                                <div class="dropdown clearfix pull-right">
                                    <?php
                                    $date = date("W");
                                    ?>
                                    <input type="hidden" id="weekNum" value="<?php echo $date; ?>" />
                                    <!--<button class="btn btn-default dropdown-toggle bg-white btn-sm" style="border-radius: 20px;"
                                        type="button" data-toggle="dropdown" aria-expanded="false" id="weekNumber">
                                        Week
                                        <span class="icon wb-chevron-down-mini" aria-hidden="true"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
/*                                        for($i=$date-4; $i<$date+1; $i++) {
                                            */?>
                                            <li role="presentation"><a href="javascript:refreshWeeklyData(<?php /*echo $i; */?>);" role="menuitem"><i class="icon wb-calendar" aria-hidden="true"></i> Week <?php /*echo $i; */?></a></li>
                                            <?php
/*                                        }
                                        */?>
                                    </ul>-->
                                    <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 7px 15px; border-radius:5px; border: 1px solid #ccc; width: 100%">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span></span> <b class="caret"></b><input type="hidden" id="startDate" /><input type="hidden" id="endDate" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="ct-chart" style="height: 230px;"></div>-->
                        <div style="width:100%">
                            <canvas id="POOperationChartjsBar"></canvas>
                        </div>
                        <!--<hr/>
                        <div class="text-center">Buyer Wise PO Activities</div>
                        <div style="width:100%">
                            <canvas id="POOperationChartjsBarBuyerWise"></canvas>
                        </div>-->
                    </div>
                </div>

                <!-- End PO operation bar chart -->

                <!--<div class="widget widget-shadow">
                    <div class="widget-content widget-radius  bg-white padding-30">
                        <div class="row padding-bottom-20">
                            <div class="col-xs-6">
                                <div class="blue-grey-700">BUYER WISE ACTIVITIES</div>
                            </div>
                            <div class="col-xs-6">
                                &nbsp;
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-hover dataTable table-striped width-full small" id="dtBuyerWiseAct">
                                    <thead>
                                    <tr class="nomargin ">
                                        <th>Buyer</th>
                                        <th>PO</th>
                                        <th>PI</th>
                                        <th>BTRC</th>
                                        <th>LC</th>
                                        <th>Invoice</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>

            <?php }?>
        </div>
    </div>
    <?php }?>
</div>
<!-- End Page -->