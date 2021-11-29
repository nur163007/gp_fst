<?php
/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 28-Feb-19
 * Time: 5:10 PM
 */
?>
<?php $title="Maturity Payment"; ?>
<!-- Page -->
<input type="hidden" id="loggedSupplier" value="<?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){ echo $_SESSION[session_prefix.'wclogin_company']; } else {echo "0";} ?>"/>
<input type="hidden" id="userType" value="<?php echo $_SESSION[session_prefix.'wclogin_role']?>"/>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h3 class="page-title">Maturity Payment</h3>
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Maturity Payment</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="form-payment-maturity" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <!--<div class="col-md-1">
                                    <div class="blue-grey-700">Filter <i class="fa fa-filter"></i></div>
                                </div>-->
                                <label class="col-sm-1 control-label" for="poNo">PO#: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="poNo" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label" for="poNo">Apply Date Range: </label>
                                <div class="col-sm-1">
                                    <div class="checkbox-custom checkbox-primary">
                                        <!--<input type="checkbox" id="applyDate" name="applyDate" />
                                        <label for="applyDate"></label>-->
                                        <input type="checkbox" class="icheckbox-primary" name="applyDate" id="applyDate" data-plugin="iCheck" data-checkbox-class="icheckbox_flat-blue"  />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="dropdown clearfix text-right">
                                        <?php $date = date("W"); ?>
                                        <input type="hidden" id="weekNum" value="<?php echo $date; ?>" />
                                        <div id="reportRange" class="pull-right disabled" style="background: #fff; cursor: pointer; padding: 7px 15px; border-radius:5px; border: 1px solid #ededed; width: 100%">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> 
                                            <span></span> <b class="caret"></b>
                                            <input type="hidden" id="startDate" />
                                            <input type="hidden" id="endDate" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary" id="applyFilter">Filter <i class="fa fa-filter"></i></button>
                                </div>
                                <!--<div class="col-md-1">
                                    <button type="button" class="btn btn-default" id="clearFilter">Clear <i class="fa fa-filter"></i></button>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </form>
                <hr>


                <!--PAYMENT HISTORY-->
                <div class="widget widget-shadow" id="widgetTable">
                    <div class="widget-content widget-radius bg-white">
                        <table class="table table-bordered table-hover table-striped width-full small">
                            <thead>
                            <tr>
                                <th class="text-center">Payment Type</th>
                                <th class="text-center">Payment Percent</th>
                                <th class="text-center">LC N0#</th>
                                <th class="text-center">CI NO#</th>
                                <th class="text-center">Paid Amount</th>
                                <th class="text-center">Paid Date</th>
                                <th class="text-center">Payable Amount</th>
                                <th class="text-center">Maturity Date</th>
                            </tr>
                            </thead>
                            <tbody id="payMaturity">
                            <tr>
                                <!--<td>GMY</td>
                                <td>$ 9,500</td>
                                <td class="green-600">+ 458</td>-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
