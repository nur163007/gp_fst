<?php

/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 29-Sep-18
 * Time: 12:06 PM
 */

require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
if(isset($_GET['ref'])) {
	$actionLog = GetActionRef($_GET['ref']);
} else {
	$actionLog['ActionID'] = 0;
}

?>

<?php $title="TAC Request"; ?>
<!-- Page -->
<input type="hidden" id="loggedSupplier" value="<?php if($_SESSION[session_prefix.'wclogin_role']==role_Supplier){ echo $_SESSION[session_prefix.'wclogin_company']; } else {echo "0";} ?>"/>
<input type="hidden" id="userType" value="<?php echo $_SESSION[session_prefix.'wclogin_role']?>"/>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <?php
            $loginRole = $_SESSION[session_prefix.'wclogin_role'];
            if($loginRole == role_Supplier){
                echo '<h4 class="page-title">Request For Technical Acceptance Certificate</h4>';
            } else if ($loginRole == role_PR_Users){
                echo '<h4 class="page-title" id="pageTitle_user">...</h4>';
            }else {
                echo '<h4 class="page-title" id="pageTitle">...</h4>';
            }
        ?>
        <ol class="breadcrumb">
            <li>Dashboard</li>
            <li class="active">Certificate Request</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal hidden" id="form-temp" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label" for="poList">PO: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label" for="ciList">CI Number: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="ciList" >
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="goTAC_btn"><i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

				<?php
					if($_SESSION[session_prefix . 'wclogin_role']==role_Supplier || $_SESSION[session_prefix . 'wclogin_role']==role_PR_Users){
						$tac_class = 'active';
						$cfac_class = 'hidden';
					} else {
						$tac_class = 'hidden';
						$cfac_class = 'active';
					}
				?>
                <form class="form-horizontal <?php echo $tac_class; ?>" id="tac-request-form" name="tac-request-form" autocomplete="off">
                    <input name="ciNo1" id="ciNo1" type="hidden" value="<?php if(!empty($_GET['ci'])){ echo $_GET['ci']; } ?>" />
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="lastActionId" id="lastActionId" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="certReqId" id="certReqId_tac" class="certReqId" type="hidden" value="" />
                    <input name="action" id="action" type="hidden" value="" />
                    <input type="hidden" id="partName" name="partName" value="" />

                    <input type="hidden" id="reqId" name="reqId" value="0" />
                    <div class="row row-lg">

                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Technical Information<span class="pull-right" id="paid"></span></h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="supplierName">Supplier: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="supplierName" name="supplierName" value="" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="podesc">Project Name: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="podesc" id="podesc" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="po">PO Number: </label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="po" name="po" value="" readonly/>
                                </div>
                                <label class="col-sm-2 control-label" for="poValue">PO Value: </label>
                                <div class="col-sm-4">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="poValue" id="poValue" readonly autocomplete="off">
                                        <div class="input-group-addon">
                                            <label id="poCurrName">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Commercial Information<span class="pull-right"></span></h4>
                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="ciDesc_t">CI Description/Item Description: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control ciDesc" maxlength="500" name="ciDesc" id="ciDesc_t" cols="30" rows="3"></textarea>
                                    <span class="comment-meta">Hint: Description for better understanding of equipment applied for</span>
                                </div>
                                <span class="hint"></span>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="ciNo">CI Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="ciNo" name="ciNo" value="" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label"> <span id="docName">...</span>: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="valueOfDoc" name="valueOfDoc" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="docCurrName">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">(%) of this certificate: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="certPercent" name="certPercent" readonly="" />
                                        <div class="input-group-addon">
                                            <label >%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Copy: </label>
                                <div class="col-sm-8"> <div class="attachment"> </div> </div>
                            </div>
                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="ciQty_t">CI Quantity/Item Qty: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control ciQty" id="ciQty_t" name="ciQty" maxlength="128" />
                                    <span class="comment-meta">Hint: Quantity as per certificate applied for</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="LcNo" name="LcNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Value: </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ciValue" name="ciValue" value="" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="ciCurrName">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($_SESSION[session_prefix . 'wclogin_role']!=role_Supplier){ ?>
                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                                <?php if($_SESSION[session_prefix . 'wclogin_role']==role_PR_Users){ ?>
									<textarea class="form-control" name="certificateText" id="certificateText" cols="130" rows="6"> </textarea>
								<?php } else { ?>
									<textarea class="form-control" name="certificateText" id="certificateText" cols="130" rows="6" readonly> </textarea>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <hr />
                    <div class="row row-lg">

                        <!--REJECT BUTTON START-->
                        <?php if($_SESSION[session_prefix . 'wclogin_role']!=role_Supplier){ ?>
                            <div class="col-xlg-6 col-md-6">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary btn-danger" id="requestRejectBtn"> <i class="fa fa-backward" aria-hidden="true"></i> Reject</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!--REJECT BUTTON END-->

                        <div class="col-xlg-6 col-md-6 pull-right">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="requestSubmitBtn">Submit <i class="fa fa-forward" aria-hidden="true"></i> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

				<form class="form-horizontal <?php echo $cfac_class; ?>" id="cfac-action-form" name="cac-form" method="post" autocomplete="off">
                    <input name="po" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipno" id="cafcShipNo" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="lastActionId" id="lastActionId" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="certReqId" id="certReqId_cfac" class="certReqId" type="hidden" value="" />
                    <input name="action" id="action_cfac" type="hidden" value="" />
                    <input type="hidden" class="form-control" id="partName_cfac" name="partName" value="" />

                    <input type="hidden" id="reqId" name="reqId" value="0" />
                    <div class="row row-lg">
						<div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Payments info<span class="pull-right" id="paid"></span></h4>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Supplier: </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="supplierName_1" value="" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">

                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" id="cfacPo" class="form-control" value="" readonly />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="cfacLcNo" name="LcNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Beneficiary: </label>
                                <div class="col-sm-8">
                                    <!--<input type="text" class="form-control" id="lcBeneficiary" name="lcBeneficiary" readonly="" />-->
                                    <textarea class="form-control" name="lcBeneficiary" id="lcBeneficiary" cols="30" rows="3" readonly></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">CI Description/Item Description: </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control ciDesc" maxlength="500" name="ciDesc" id="ciDesc" cols="30" rows="3" readonly></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">AWB/BL Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="awbBlNo" name="awbBlNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" id="docName_cfac"> : </label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="acceptCertValue" name="valueOfDoc" value="0.00" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="docCurrName_1">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">(%) of this certificate</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="acceptCertPercent" name="acceptCertPercent" value="" readonly="" />
                                        <div class="input-group-addon">
                                            <label>%</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Copy: </label>
                                <div class="col-sm-8"> <div class="attachment"> </div> </div>
                            </div>

                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">PO Value: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" id="cfacPoValue" class="form-control" value="" readonly />
                                        <div class="input-group-addon">
                                            <label id="poCurrName_1">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-6 control-label">Description: </label>
                                <div class="col-sm-6">
                                    <textarea class="form-control" name="description" id="description" cols="130" rows="3"readonly></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">LC Value: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="lcValue" name="lcValue" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="lcCurrName">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Commercial Invoice Number: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="cfacCiNo" name="ciNo" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">CI Quantity/Item Qty: </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control ciQty" id="ciQty" name="ciQty" maxlength="128" readonly />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label">Commercial Invoice Value: </label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cfacCiValue" name="ciValue" value="0.00" readonly="" />
                                        <div class="input-group-addon">
                                            <label id="ciCurrName_1">...</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control" name="cfacCertificateText" id="cfacCertificateText" cols="130" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input class="form-control" type="text" id="acceptValueInWord" value="USD ABC (US Dollar Twenty Thousand only)" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <input class="form-control" type="text" id="ciValuePercent" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Technical Acceptance Certificate: </label>
                                <div class="col-sm-6">
                                    <a target="_blank" id="viewTAC_btn" href="#">
                                        <i class="icon fa-pdf"></i> <span id="tac_TacFilename"></span>
                                    </a>
                                </div>
                            </div>
						</div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <!--REJECT CFAC BUTTON START-->
                        <?php if($_SESSION[session_prefix . 'wclogin_role']!=role_Supplier){ ?>
                            <div class="col-xlg-4 col-md-4">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="button" class="btn btn-primary btn-danger" id="requestRejectBtn_cfac"> <i class="fa fa-backward" aria-hidden="true"></i> Reject</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <!--REJECT CFAC BUTTON END-->

                        <div class="col-xlg-8 col-md-8">
                            <!--<div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="cfacSubmitBtn"><i class="fa fa-save" aria-hidden="true"></i> Accept</button>
                                </div>
                            </div>-->
                            <!--REJECT BUTTON FOR SOURCING TEAM-->
                            <?php if($_SESSION[session_prefix . 'wclogin_role']==role_Buyer){ ?>
                                <label class="col-sm-3 control-label" for="certFinalApprover">Forward To:</label>
                                <div class="col-sm-7">
                                    <select class="form-control" data-plugin="select2" name="certFinalApprover" id="certFinalApprover"></select>
                                </div>
                            <?php } ?>
                            <button type="button" class="btn btn-primary pull-right" id="cfacSubmitBtn">Accept <i class="fa fa-forward" aria-hidden="true"></i></button>
                            <!--REJECT BUTTON FOR SRT END-->
                        </div>
                    </div>
                </form>

                <!--PAYMENT HISTORY-->
                <div class="widget widget-shadow" id="widgetTable">
                    <div class="widget-content widget-radius bg-white">
                        <div class="padding-15 height-xs-200">
                            <p class="clearfix font-size-20 margin-bottom-20">
                                <span class="text-truncate">Payment History</span>
                            </p>
                        </div>
                        <table class="table table-bordered table-hover table-striped width-full small" style="height:calc(100% - 150px);" id="dtPayHistory">
                            <thead>
                            <tr>
                                <th>PO Number</th>
                                <th>PO Date</th>
                                <th>Payment Type</th>
                                <th>Payment Percent</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                            </tr>
                            </thead>
                            <tbody id="payHistory">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->

