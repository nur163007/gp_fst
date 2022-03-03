<?php $title="PO View"; ?>
<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
$poContact = getPOContacts($_GET["po"]);
//echo decryptId($_GET["ref"]);
//echo $poContact;
//echo json_encode($actionLog);
?>
<!-- Page -->
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
            <?php if($actionLog['ActionPending']=='Acknowledgement' && $_SESSION[session_prefix.'wclogin_role'] == $actionLog['ActionPendingTo']){ ?>
            <button type="button" class="btn btn-primary" id="btnAcknowledged"><i class="icon wb-check" aria-hidden="true"></i> Acknowledged</button>
            <?php } else {
                if($_SESSION[session_prefix.'wclogin_role'] == role_Admin){
                    if(!empty($_GET['ship'])) {
                        $param = "po=" . $_GET["po"] . "&ship=" . $_GET["ship"] . "&ref=" . $_GET["ref"];
                    }else {
                        $param = "po=" . $_GET["po"] . "&ref=" . $_GET["ref"];
                    }
                    ?>
                    <a class="btn btn-sm btn-inverse btn-round" href="<?php echo const_wcadmin_path."edit-po?".$param;?>">
                        <i class="icon fa-edit" aria-hidden="true"></i>
                        <span class="hidden-xs">Edit this PO</span>
                    </a>
            <?php }
            }?>
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

                <div class="nav-tabs-horizontal">
                    <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                        <li class="active" role="presentation">
                            <a data-toggle="tab" href="#tabPOInfo" aria-controls="tabPOInfo" role="tab">PO &amp; PI Information</a></li>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tabAttachmentLog" aria-controls="tabAttachmentLog" role="tab">Attachments &amp; Comments</a></li>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tabLCInfo" aria-controls="tabLCInfo" role="tab">LC Info.</a></li>
                        <li role="presentation">
                            <a data-toggle="tab" href="#tabShipmentInfo" aria-controls="tabShipmentInfo" role="tab">Shipment Info.</a></li>
                    </ul>

                    <div class="tab-content padding-top-20">

                        <!--PO Information-->
                        <div class="tab-pane active" id="tabPOInfo" role="tabpanel">
                            <div class="form-horizontal">

                                <div class="row row-lg">
                                    <div class="col-xlg-6 col-md-6">
                                        <h4 class="well well-sm example-title">PO Information</h4>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">PO No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ponum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PO Value:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="povalue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Description:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="podesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Supplier:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="supplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Supplier Address:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left"><b id="sup_address"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Contract Ref:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="contractref"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">PR No:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="pr_no"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Department:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label text-left" id="department"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Need by Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Actual PO Date:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="actualPoDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Implementation by:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Max. shipment allowed:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="nofshipallow"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                            <label class="col-sm-5 control-label">Max. LC will be issued:</label>
                                            <div class="col-sm-7">
                                                <label class="control-label" id="noflcissue"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <h4 class="well well-sm example-title">PI Information</h4>
                                        <?php if( checkStepOver($pono, action_Draft_PI_Submitted)>0 ){?>
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">PI No:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="pinum"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">PI Value:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="pivalue"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Shipment Mode:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="shipmode"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">PI Description: </label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="pi_desc"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Product Type:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="producttype"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Import As: </label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="importAs"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <div id="shiphscode">
                                                    <label class="col-sm-5 control-label">HS Code:</label>
                                                    <div class="col-sm-7">
                                                        <label class="control-label text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
                                                    </div>
                                                </div>
                                                <?php if( checkStepOver($pono, action_Final_PI_Submitted)>0 ){?>
                                                    <label class="col-sm-5 control-label">PI Date:</label>
                                                    <div class="col-sm-7">
                                                        <label class="control-label text-left" id="pidate"><img src="assets/images/busy.gif" /></label>
                                                    </div>
                                                    <label class="col-sm-5 control-label">Insurance / Base Value:</label>
                                                    <div class="col-sm-7">
                                                        <label class="control-label text-left" id="basevalue"><img src="assets/images/busy.gif" /></label>
                                                    </div>
                                                <?php }?>
                                                <label class="col-sm-5 control-label">Country of Origin:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="origin"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Negotiating Bank:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="negobank"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Port of Shipment:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="shipport"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">L/C Beneficiary:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="lcbankaddress"><img src="assets/images/busy.gif" /></label>
                                                </div>
                                                <label class="col-sm-5 control-label">Days required for shipment <br/>after getting LC:</label>
                                                <div class="col-sm-7">
                                                    <label class="control-label text-left" id="productiondays"><img src="assets/images/busy.gif" /></label> Days
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
                            <hr>

                            <!--Start PO Lines-->
                            <div class="row row-lg">
                                <div class="col-xlg-12 col-md-12 margin-bottom-20">
                                    <h4 class="well well-sm example-title">PO Lines
                                        <span class="pull-right" style="margin-top: 0;">
                                    Delivered Number of Line: <label style="font-weight: bold" id="delivCount1">0</label>
                                </span>
                                    </h4>
                                    <table class="table table-bordered table-striped table-highlight order margin-0 small" id="dtPOLinesDelivered">
                                        <thead>
                                        <tr>
                                            <th style="width:5%" class="text-center" rowspan="2">Line #</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Item</th>
                                            <th style="width:24%" class="text-center" rowspan="2">Item Description</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Delivery Date</th>
                                            <th style="width:5%" class="text-center" rowspan="2">UOM</th>
                                            <th style="width:10%" class="text-center" rowspan="2">Unit Price</th>
                                            <th style="width:10%" class="text-center poBg" colspan="2">PO</th>
                                            <th style="width:5%" class="text-center delivBg" colspan="2">Delivered</th>
                                            <!--<th style="width:5%" class="text-center" rowspan="2">LD</th>-->
                                        </tr>
                                        <tr>
                                            <th style="width:5%" class="text-center poBg">Qty.</th>
                                            <th style="width:10%" class="text-center poBg">Total Price</th>
                                            <th style="width:5%" class="text-cente delivBg">Qty.</th>
                                            <th style="width:10%" class="text-center delivBg">Total Price</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-left"></td>
                                            <td class="text-left"></td>
                                            <td class="text-left"></td>
                                            <td class="text-left"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right poBg"></td>
                                            <td class="text-right poBg"></td>
                                            <td class="text-right delivBg"></td>
                                            <td class="text-right delivBg"></td>
                                            <!--<td class="text-right"></td>-->
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr style="font-weight: bolder;">
                                            <th colspan="6" class="text-right padding-top-15" style="font-weight: bold; font-size: inherit">Total: </th>
                                            <th class="text-center poBg padding-top-15" id="poQtyTotal" style="font-weight: bold; font-size: inherit"></th>
                                            <th class="text-right poBg padding-top-15" id="grandTotal" style="font-weight: bold; font-size: inherit"></th>
                                            <th class="text-center delivBg padding-top-15" id="dlvQtyTotal" style="font-weight: bold; font-size: inherit"></th>
                                            <th class="text-right delivBg padding-top-15" id="dlvGrandTotal" style="font-weight: bold; font-size: inherit"></th>
                                            <!--<th class="text-right" id="ldAmntTotal"></th>-->
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!--End PO Lines-->
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
                                                <!--div class="col-sm-11 table-bordered margin-left-20 padding-20" style="white-space: pre-wrap;" id="suppliersmsg"-->
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

                                    <div class="col-md-12">
                                        <h4 class="well well-sm example-title">LC Information</h4>
                                    </div>

                                    <div class="col-xlg-5 col-md-5">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Value: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcvalue"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LCAF No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcafno"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Issue Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lcissuedate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">LC Expiry Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="daysofexpiry"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Last Shipment Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="lastdateofship"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-7 col-md-7">

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Request Type: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="requesttype"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Type: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="lctype"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Product Type: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="producttype"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Bank: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="lcissuerbank"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Insurance: </label>
                                            <div class="col-sm-8">
                                                <label class="control-label"><b id="insurance"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">LC Description:</label>
                                            <div class="col-sm-8">
                                                <label class="control-label text-left" id="lcdesc"><img src="assets/images/busy.gif" /></label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="row row-lg">
                                    <div class="col-xlg-12 col-md-12">
                                        <h4 class="well well-sm example-title">Payment Terms</h4>
                                        <div class="row row-lg" id="divTermsEdited">
                                            <div class="col-md-4 small" id="paymentTermsText">
                                            </div>
                                            <div class="col-md-8">
                                                <table class="table table-bordered width-full" id="lcPaymentTermsTable">
                                                </table>
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
                                                <label class="control-label text-uppercase"><b id="shipmode1"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Estimated Time of Arrival (ETA): </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="scheduleETA"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Estimated Time of Delivery (ETD): </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="scheduleETD"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">MAWB Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="mawbNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">HAWB Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="hawbNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">BL Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="blNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">AWB / BL Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="awbOrBlDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciNo"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">CI Amount: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ciAmount"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">IPC No.: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ipcNum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">GIT Receiving Date: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="gitReceiveDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Arrival at Warehouse: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="whArrivalDate"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Invoice Quantity: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="invoiceQty"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">No. of Container: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="noOfcontainer"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Total number of Boxes: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="noOfBoxes"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Gross Chargeable Weight: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="ChargeableWeight"><img src="assets/images/busy.gif" /></b></label>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">DHL Tracking Number: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="dhlNum"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Endorsment Doc. Shared by Finance: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="docDeliveredByFin"><img src="assets/images/busy.gif" /></b></label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-5 control-label">Custom Duty: </label>
                                            <div class="col-sm-7">
                                                <label class="control-label"><b id="cdAmount"><img src="assets/images/busy.gif" /></b></label>
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
                <div class="row row-lg">
                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12 text-right">
                                <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="podetail-form" name="podetail-form" method="post" autocomplete="off" class="padding-0 margin-0 hidden">
                <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                <input name="userAction" id="userAction" type="hidden" value="" />
            </form>

        </div>
    </div>
</div>
<!-- End Page -->