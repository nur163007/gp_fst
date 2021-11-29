<?php
$title="Buyer's Draft PI BOQ Catalog";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
$actionLog = GetActionRef($_GET['ref']);
/*echo '<pre>';
    print_r($actionLog);
echo '</pre>';*/
?>


<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">Buyer's PI-BOQ-Catalog</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="draftpiboq-form" name="draftpiboq-form" method="post" autocomplete="off">
                    <input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="postatus" id="postatus" type="hidden" value="<?php echo $actionLog['ActionID']; ?>" />
                    <input name="userAction" id="userAction" type="hidden" value="" />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Order Information</h4>
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
                                <label class="col-sm-5 control-label">L/C Description:</label>
                                <div class="col-sm-7">
                                    <textarea class="form-control" name="lcdesc" id="lcdesc"></textarea>
                                    <label class="control-label text-left" id="lcdescLabel"><img src="assets/images/busy.gif" /></label>
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
                                    <label class="control-label"><b id="pr_no"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Department:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="department"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Need by Date:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="deliverydate"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Implementation by:</label>
                                <div class="col-sm-7">
                                    <label class="control-label"><b id="installbysupplier"><img src="assets/images/busy.gif" /></b></label>
                                </div>
                                <label class="col-sm-5 control-label">Buyer's Contact:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="buyercontact"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Technical Contact:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="techcontact"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            
                            <h4 class="well well-sm example-title">PI Information</h4>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">PI No:</label>
                                <div class="col-sm-7">
                                     <label class="control-label text-left" id="pinum"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">PI Value:</label>
                                <div class="col-sm-7">
                                     <label class="control-label text-left" id="pivalue"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">PI Description:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="pi_desc"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Shipment Mode:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="shipmode"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">HS Code:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="hscode"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <?php if($actionLog['ActionID']>=action_Final_PI_Submitted){?>
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
                                <label class="col-sm-5 control-label">L/C Beneficiary &amp Address:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="lcbankaddress"><img src="assets/images/busy.gif" /></label>
                                </div>
                                <label class="col-sm-5 control-label">Production Time:</label>
                                <div class="col-sm-7">
                                    <label class="control-label text-left" id="productiondays"><img src="assets/images/busy.gif" /></label>
                                </div>
                            </div>
                            
                            <div id="usersAttachments">
                                <h4 class="well well-sm example-title">Buyer's Attachments</h4>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PO</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="temp/po_25012016034653.docx" target="_blank">po_25012016034653.docx</a></label>
                                    </div>
                                    <label class="col-sm-3 control-label">BOQ</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="temp/boq_25012016034656.docx" target="_blank">boq_25012016034656.docx</a></label>
                                    </div>
                                    <label class="col-sm-3 control-label">Other Doc</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="temp/other_25012016034700.docx" target="_blank">other_25012016034700.docx</a></label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">Buyer's Message</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="buyersmsg"></div>
                            </div>
                                                        
                            <h4 class="well well-sm example-title" id="suppliersmsgtitle">Supplyer's Message</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="suppliersmsg">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <hr />
                    <!--LINE WAYS PO-->

                    <div class="row row-lg">
                        <!--PO Lines-->
                        <div class="col-xlg-12 col-md-12 margin-bottom-20">
                            <h4 class="well well-sm example-title" style="background-color: #BFEDD8;">PO Lines
                                <!--<span class="pull-right">
                                    <button class="btn btn-primary btn-xs" style="margin-top: -5px;" id="btnLoadPoLines"><i class="icon wb-refresh" aria-hidden="true"></i> Reload PO Lines</button>
                                </span>-->
                            </h4>
                            <div class="nav-tabs-horizontal">
                                <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a data-toggle="tab" href="#tabDelivered" aria-controls="tabDelivered" role="tab"><span class="text-primary">Delivered <span id="delivCount1">(0)</span></span></a></li>
                                </ul>
                            </div>
                            <div class="tab-content padding-top-20">
                                <!--Delivered PO Lines-->
                                <div class="tab-pane active" id="tabDelivered" role="tabpanel">
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

                        </div>
                    </div>

            <!--End PO Information-->

                    <hr/>
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <?php /*if($actionLog['ActionID']==action_Draft_PI_Submitted ||
                                $actionLog['ActionID']==action_Final_PI_Submitted ||
                                $actionLog['1stLastAction']==action_PO_Edited_by_Buyer){*/?>
                            <div class="col-sm-6">
                                
                                <h4 class="well well-sm example-title clearfix">Message to PR User
                                    <span class="pull-right"><input type="checkbox" class="icheckbox-primary" id="messageToPRUserYes" name="messageToPRUserYes" checked="" /></span>
                                </h4>
                                <div class="form-group isDefMessageToPRUser">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="messageToPRUser" id="messageToPRUser" rows="4" placeholder="Message to PR User">Dear Concern,

Please find the draft/final PI and related attachments. By checking those please send your feedback.

Thanks.</textarea>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="col-sm-6">
                                
                                <h4 class="well well-sm example-title clearfix">Message to EA Team
                                    <span class="pull-right"><input type="checkbox" class="icheckbox-primary" id="messageToEATeamYes" name="messageToEATeamYes" checked="" /></span>
                                </h4>
                                <div class="form-group isDefMessageToEATeam">
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="messageToEATeam" id="messageToEATeam" rows="4" placeholder="Message to PR User">Dear Concern,

Please find the draft/final PI and related attachments. By checking those please send your feedback.

Thanks.</textarea>
                                    </div>
                                </div>
                                
                            </div>
                            <?php /*}*/?>
                        </div>
                    </div>
                    
                    <hr />
                    
                    <div class="row row-lg padding-25">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <?php

                                    $a1 = array(action_Final_PI_Accepted_by_PR,
                                        action_Final_PI_Accepted_by_EA,
                                        action_PO_Edited_by_Buyer);

                                    if(in_array($actionLog['1stLastAction'], $a1) && in_array($actionLog['2ndLastAction'], $a1)){?>
                                        <button type="button" class="btn btn-warning" id="SendToReCheck_btn"><i class="icon wb-warning"></i> Send to PR User &amp; EA Team to Re-Check</button>
                                        <button type="button" class="btn btn-success" id="AcceptFinalPI_btn"><i class="icon wb-check"></i> Accept &amp; Proceed for BTRC Permission</button>
                                    <?php } elseif(in_array($actionLog['1stLastAction'], $a1)){ ?>
                                        <button type="button" class="btn btn-warning" id="SendToReCheck_btn"><i class="icon wb-warning"></i> Send to PR User &amp; EA Team to Re-Check</button>
                                    <?php }?>
                                    <?php if($actionLog['ActionID']==action_Draft_PI_Submitted || $actionLog['ActionID']==action_Final_PI_Submitted){?>
                                    <button type="button" class="btn btn-primary" id="btnSendForPREAFeedback">Send to PR User &amp; EA Team for Feedback</button>
                                    <?php }?>
                                    <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->