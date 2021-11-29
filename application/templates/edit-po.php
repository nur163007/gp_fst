<?php
$title="Edit PO";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
$poContact = getPOContacts($_GET["po"]);

?>
<!-- Page -->
<style>
    #ship-modes > li{
        padding-right: 0px;
    }
</style>
<div class="page bg-blue-100 animsition">

    <div class="page-header page-header-bordered">

        <!-- PO Status & Contact Information-->
        <h1 class="page-title">PO # <?php if(!empty($_GET['po'])){ echo $_GET['po']; } if(!empty($_GET['ship'])){ echo ' : Shipment # '.$_GET['ship']; } ?></h1>

        <ol class="breadcrumb small">
            <li><a>Status:</a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
            <li><a>Pending for:</a></li>
            <li class="active"><?php echo $actionLog['ActionPending']; ?></li>
        </ol>

        <ol class="breadcrumb small">
            <li><a>Buyer's contact:</a></li>
            <li><?php echo $poContact["buyerName"]; ?></li>
            <li><i class="icon fa-mobile" aria-hidden="true"></i> <?php echo $poContact["buyerMobile"]; ?></li>
            <li><i class="icon fa-envelope-o" aria-hidden="true"></i> <a href="emailto:<?php echo $poContact["buyerEmail"]; ?>"><?php echo $poContact["buyerEmail"]; ?></a></li>
        </ol>

        <ol class="breadcrumb small">
            <li><a>User's contact:</a></li>
            <li><?php echo $poContact["userName"]; ?></li>
            <li><i class="icon fa-mobile" aria-hidden="true"></i> <?php echo $poContact["userMobile"]; ?></li>
            <li><i class="icon fa-envelope-o" aria-hidden="true"></i> <a href="emailto:<?php echo $poContact["userEmail"]; ?>"><?php echo $poContact["userEmail"]; ?></a></li>
        </ol>
        <!-- End PO Status & Contact Information-->

        <div class="page-header-actions">
            <a class="btn btn-sm btn-inverse btn-round" href="<?php echo const_wcadmin_path."all-po";?>">
                <i class="icon wb-arrow-left" aria-hidden="true"></i>
                <span class="hidden-xs">Back to All PO</span>
            </a>
        </div>
    </div>

    <?php
    if(!empty($_GET['po']))
        $pono = $_GET['po'];
    else
        $pono = "";

    if(!empty($_GET['ship']))
        $ship = $_GET['ship'];
    else
        $ship = "";
    ?>

    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">

                <form id="editpo-form" name="finalpiboq-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID'];?>" />
                    <input name="userAction" id="userAction" type="hidden" value="<?php if(!empty($_GET['action'])){ echo $_GET['action']; } ?>" />
                    <input name="maxStatus" id="maxStatus" type="hidden" value="<?php echo $actionLog['1stLastAction'];?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />

                    <div class="nav-tabs-horizontal">
                        <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab"><span class="text-primary">PO Detail</span></a></li>
                            <li role="presentation"><a data-toggle="tab" href="#tabAttachmentLog" aria-controls="tabAttachmentLog" role="tab"><span class="text-primary">Attachments &amp; Comments</span></a></li>
                            <li role="presentation"><a data-toggle="tab" href="#tabLCInfo" aria-controls="tabLCInfo" role="tab"><span class="text-primary">LC Info.</span></a></li>
                            <li role="presentation"><a data-toggle="tab" href="#tabShipmentInfo" aria-controls="tabShipmentInfo" role="tab"><span class="text-primary">Shipment Info.</span></a></li>
                        </ul>

                        <div class="tab-content padding-top-20">

                            <!--PO Information-->
                            <div class="tab-pane active" id="tabPOInfo" role="tabpanel">
                                <div class="form-horizontal">
                                    <div class="row row-lg">

                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">PO Information</h4>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PO No:</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="poid" id="poid" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" readonly="" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PO Value:</label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <label id="currency">&nbsp;</label>
                                                        </span>
                                                        <input type="text" class="form-control curnum" name="povalue" id="povalue" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PO Description:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="podesc" id="podesc" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <?php if(checkStepOver($_GET['po'],action_Draft_PI_Submitted)){ ?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">LC Description:</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="lcdesc" id="lcdesc" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Need by Date:</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="deliverydate" id="deliverydate" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label" for="actualPoDate">Actual PO Date:</label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="actualPoDate" id="actualPoDate" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Number of LC Issue: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="noflcissue" id="noflcissue" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Shipment Allowed: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" data-plugin="asSpinner" value="1" name="nofshipallow" id="nofshipallow" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Install by </label>
                                                <div class="col-sm-8">
                                                    <ul class="list-unstyled list-inline padding-top-5">
                                                        <li><input type="radio" id="installBy_0" name="installBy" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;GP</li>
                                                        <li><input type="radio" id="installBy_1" name="installBy" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Supplier</li>
                                                        <li><input type="radio" id="installBy_2" name="installBy" value="2" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Other</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PR User (To): </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" name="prUserEmailTo" id="prUserEmailTo" >
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PR User (CC): </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" name="prUserEmailCC[]" id="prUserEmailCC" multiple="" >
                                                    </select>
                                                </div>
                                            </div>
                                            <h4 class="well well-sm example-title">Supplier's Info</h4>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Name: </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" name="supplier" id="supplier" >
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Contract Ref: </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" title="Select Contract Ref" name="contractref" id="contractref" >
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Email To: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailto" id="emailto" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Email CC: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" data-plugin="tokenfield" name="emailcc" id="emailcc" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title">PI Information</h4>
                                            <?php if( checkStepOver($pono, action_Draft_PI_Submitted)>0 ){?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PI No: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="pinum" id="pinum" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PI Value: </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <label id="piCurrency">&nbsp;</label>
                                                        </span>
                                                        <input type="text" class="form-control curnum" name="pivalue" id="pivalue" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Shipment Mode: </label>
                                                <div class="col-sm-8">
                                                    <ul class="list-unstyled list-inline padding-top-5" id="ship-modes">
                                                        <li><input type="radio" id="shipmodesea" name="shipmode" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                                        <li><input type="radio" id="shipmodeair" name="shipmode" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                                        <li><input type="radio" id="shipmodesea+air" name="shipmode" value="sea+air" data-plugin="iCheck" data-radio-class="iradio_flat-red" />&nbsp;Sea + Air</li>
                                                        <li><input type="radio" id="shipmodeE-Delivery" name="shipmode" value="E-Delivery" data-plugin="iCheck" data-radio-class="iradio_flat-orange" /> E-Delivery</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">HS Code: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="hscode" id="hscode" autocomplete="off" />
                                                </div>
                                            </div>
                                            <?php if( checkPIFeedbackLevelOver($pono)>1 ){?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">PI Date: </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="pidate" id="pidate" />
                                                    </div>
                                                    <span class="comment-meta">(supplier inputs w.r. to Final PI)</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Insurance / Base Value: </label>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <label id="bvCurrency">&nbsp;</label>
                                                        </span>
                                                        <input type="text" class="form-control curnum" name="basevalue" id="basevalue" autocomplete="off" />
                                                    </div>
                                                    <span class="comment-meta">(value without discount)</span>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Country of Origin: </label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" data-plugin="select2" multiple="" name="origin[]" id="origin">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Negotiating Bank: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="negobank" id="negobank" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Port of Shipment: </label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="shipport" id="shipport" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">L/C Beneficiary &amp Address:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="lcbankaddress" id="lcbankaddress" autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">Days required for shipment after getting LC:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="productiondays" id="productiondays" autocomplete="off" />
                                                </div>
                                            </div>
                                            <?php } else{?>
                                            <div class="form-group">
                                                <label class="col-sm-12 text-center"><i class="icon fa-info-circle" aria-hidden="true"></i> PI information not available.</label>
                                            </div>
                                            <?php }?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--End PO Information-->

                            <!--Attachments & Log-->
                            <div class="tab-pane" id="tabAttachmentLog" role="tabpanel">
                                <div class="form-horizontal">

                                    <div class="row row-lg">

                                        <div class="col-xlg-6 col-md-6">
                                            <div id="usersAttachments" class="small">
                                            </div>
                                        </div>

                                        <div class="col-xlg-6 col-md-6">
                                            <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
                                            <div class="form-group">
                                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" style="" id="buyersmsg">
                                                </div>
                                            </div>

                                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Comments</h4>
                                            <div class="form-group">
                                                <div class="table-bordered margin-left-20 margin-right-20 padding-20 comment-body" id="suppliersmsg">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!--End Attachments & Log-->

                            <!--LC Information-->
                            <div class="tab-pane" id="tabLCInfo" role="tabpanel">
                                <div class="form-horizontal">
                                    <?php if( checkStepOver($pono, action_LC_Request_Sent) >0 || checkStepOver($pono, action_Sent_Revised_LC_Request_1) >0 ){?>

                                        <div class="row row-lg">
                                            <div class="col-xlg-12 col-md-12">
                                                <h4 class="well well-sm example-title">Payment Terms</h4>
                                                <div class="pull-right margin-bottom-10" id="addRowDiv_btn">
                                                    <div class="">
                                                        <button type="button" class="btn btn-primary pull-right" id="addNewPaymentTermsRow">Add Row +</button>
                                                    </div>
                                                </div>
                                                <!--<div class="row row-lg" id="divTermsEdited">
                                                    <div class="col-md-4 small" id="paymentTermsText">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                                        </table>
                                                    </div>
                                                </div>-->
                                                <div class="row row-lg">
                                                    <div class="col-md-12">
                                                        <div class="col-sm-offset-1 col-sm-1">
                                                            <label for="percentage">Per.(%)</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="certificate">Certificate</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="matDays">Maturity Days</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="matDays">Maturity Terms</label>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <label for="matDays">Days</label>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <label for="matDays">Certificate Title</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12" id="lcPaymentTermsTable">

                                                    </div>
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="paymentTermsText" id="paymentTermsText" rows="6"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br/>

                                        <div class="row row-lg">

                                            <div class="col-md-12">
                                                <h4 class="well well-sm example-title">LC Operation's Input</h4>
                                            </div>

                                            <div class="col-xlg-6 col-md-5">
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Request Type</label>
                                                    <div class="col-sm-8">
                                                        <ul class="list-unstyled list-inline padding-top-5">
                                                            <li><input type="radio" id="lcrequesttype_0" name="lcrequesttype" value="0" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;LC</li>
                                                            <li><input type="radio" id="lcrequesttype_1" name="lcrequesttype" value="1" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;LCA</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">LC Type:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" data-plugin="select2" name="lctype" id="lctype">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">LC Value:</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label id="lcvalueCur"></label>
                                                            </div>
                                                            <input type="text" class="form-control curnum" name="lcvalue" id="lcvalue" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Product Type:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" data-plugin="select2" name="producttype" id="producttype">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Bank:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" data-plugin="select2" name="lcissuerbank" id="lcissuerbank">
                                                        </select>
                                                        <input type="hidden" id="lcissuerbankNew" name="lcissuerbankNew" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Account No.:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" data-plugin="select2" name="bankaccount" id="bankaccount">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Insurance:</label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" data-plugin="select2" name="insurance" id="insurance">
                                                        </select>
                                                        <input type="hidden" id="insuranceNew" name="insuranceNew" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-xlg-6 col-md-7">

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">LC No:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="lcno" id="lcno" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">LCAF No.:</label>
                                                    <div class="col-sm-7">
                                                        <input type="text" class="form-control" name="lcafno" id="lcafno" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">LC Date:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                            <input type="text" class="form-control" data-plugin="datepicker" name="lcissuedate" id="lcissuedate" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Day of Expiry:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                            <input type="text" class="form-control" name="daysofexpiry" id="daysofexpiry" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Last Shipment Date:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                            <input type="text" class="form-control" name="lastdateofship" id="lastdateofship" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">LC Expiry Date:</label>
                                                    <div class="col-sm-7">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="icon wb-calendar" aria-hidden="true"></i>
                                                            </span>
                                                            <input type="text" class="form-control" name="lcexpirydate" id="lcexpirydate" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    <?php } else{?>
                                        <div class="form-group">
                                            <label class="col-sm-12 text-center"><i class="icon fa-info-circle" aria-hidden="true"></i> LC information not available.</label>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                            <!--End LC Information-->

                            <!--Shipment Information-->
                            <div class="tab-pane" id="tabShipmentInfo" role="tabpanel">
                                <div class="form-horizontal">
                                    <div class="row row-lg">
                                        <?php if( checkStepOver($pono, action_Shared_Shipment_Document, $ship) > 0 ){?>
                                        <div class="col-md-12">
                                            <h4 class="well well-sm example-title">Shipment Information</h4>
                                        </div>

                                        <div class="col-xlg-6 col-md-6">
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Shipment Mode: </label>
                                                <div class="col-sm-7">
                                                    <ul class="list-unstyled list-inline margin-top-51">
                                                        <li><input type="radio" id="shipmode1sea" name="shipmode1" value="sea" data-plugin="iCheck" data-radio-class="iradio_flat-blue" />&nbsp;Sea</li>
                                                        <li><input type="radio" id="shipmode1air" name="shipmode1" value="air" data-plugin="iCheck" data-radio-class="iradio_flat-green" />&nbsp;Air</li>
                                                        <li><input type="radio" id="shipmode1E-Delivery" name="shipmode1" value="E-Delivery" data-plugin="iCheck" data-radio-class="iradio_flat-orange" /> E-Delivery</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Estimated Time of Arrival (ETA): </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="scheduleETA" id="scheduleETA" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Estimated Time of Delivery (ETD): </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="scheduleETD" id="scheduleETD" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">MAWB Number: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="mawbNo" id="mawbNo" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">HAWB Number: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="hawbNo" id="hawbNo" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">BL Number: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="blNo" id="blNo" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">AWB / BL Date: </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="awbOrBlDate" id="awbOrBlDate" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">DHL Tracking NO: </label>
                                                <div class="col-sm-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon fa-cube" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" name="dhlTrackNo" id="dhlTrackNo" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">GERP Voucher No: </label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" name="GERPVoucherNo" id="GERPVoucherNo" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xlg-6 col-md-6">

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">CI Number: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="ciNo" id="ciNo" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">CI Date: </label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="icon wb-calendar" aria-hidden="true"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin="datepicker" name="ciDate" id="ciDate" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">CI Amount: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="ciAmount" id="ciAmount" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Invoice Quantity: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="invoiceQty" id="invoiceQty" placeholder="e.g. 100 Nos, 100 Meter, 1 Lot, 100 set, etc." title="e.g. 100 Nos, 100 Meter, 1 Lot, 100 set, etc." />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">No. of Container: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="noOfcontainer" id="noOfcontainer" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Total number of Boxes: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="noOfBoxes" id="noOfBoxes" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Gross Chargeable Weight: </label>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="ChargeableWeight" id="ChargeableWeight" />
                                                </div>
                                            </div>

                                        </div>

                                        <?php } else{?>
                                        <div class="form-group">
                                            <label class="col-sm-12 text-center"><i class="icon fa-info-circle" aria-hidden="true"></i> Shipment information not available.</label>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <!--End Shipment Information-->

                        </div>
                    </div>

                    <hr />
                    <div class="form-horizontal">

                        <div class="row row-lg">

                            <div class="col-xlg-6 col-md-6">
                                <h4 class="well well-sm example-title clearfix">Edit Note</h4>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="buyersEditComment" id="buyersEditComment" rows="4" placeholder="Write the purpose of editing."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xlg-6 col-md-6 text-right">
                                <button type="button" class="btn btn-primary" id="savePOUpdate_btn"><i class="icon fa-save"></i> Save</button>
                                <?php //if(isset($_GET['action']) && $_GET['action']=='pi_rejection_edit'){?>
                                    <!-- <button type="button" class="btn btn-warning" id="gotoBuyers_piboq_btn"><i class="icon fa-save"></i> Proceed without Change</button>-->
                                <?php //}?>
                                <a href="<?php echo $adminUrl."/all-po";?>" class="btn btn-default btn-outline">Close</a>
                            </div>

                        </div>
                    </div>

                </form>

                <!-- Attachment replce modal form-->
                <div class="modal fade modal-fade-in-scale-up" id="replaceAttach" aria-hidden="true" aria-labelledby="replaceDocument" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-center">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="">
								<span aria-hidden="true">x</span>
							</button>
                                <h4 class="modal-title">Replace document</h4>
                            </div>
                            <div class="modal-body">
                                <span><strong id="replaceAttachOld">.....docx</strong></span> replace with...
                                <form id="form-replacedoc">
                                    <div class="input-group">
                                        <input type="hidden" id="attachmentPOID" name="attachmentPOID" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                        <input type="hidden" id="attachmentDocID" name="attachmentDocID" value="" />
                                        <input type="text" class="form-control" name="replaceAttachNew" id="replaceAttachNew" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnReplaceAttachNew" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                        
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default margin-top-5" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger margin-top-5" data-dismiss="modal" id="replaceAttachment_btn">Replace</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End attachment replce modal form -->

            </div>
        </div>
    </div>
</div>
<!-- End Page -->