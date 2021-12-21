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

                <!--modal start pra rejection-->
                <div class="modal fade modal-slide-in-top" id="praRejectForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                    <div class="modal-dialog">
                        <form class="form-horizontal" id="form-pra-reject" name="form-pra-reject" method="post" autocomplete="off" enctype='multipart/form-data'>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">PRA Rejection</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Remarks:</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" name="remarks" id="remarks" placeholder="Write something...."></textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="model-footer text-right">
                                        <label class="wc-error pull-left" id="form_error"></label>
                                        <button type="button" class="btn btn-danger" id="btnPraReject" >Reject</button>
                                        <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--modal end pra rejection-->

                <!--modal start btrc rejection-->
                <div class="modal fade modal-slide-in-top" id="btrcRejectForm" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                    <div class="modal-dialog">
                        <form class="form-horizontal" id="form-btrc-reject" name="form-btrc-reject" method="post" autocomplete="off" enctype='multipart/form-data'>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title">BTRC Rejection</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Remarks:</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" name="remarks1" id="remarks1" placeholder="Write something...."></textarea>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="model-footer text-right">
                                        <label class="wc-error pull-left" id="form_error"></label>
                                        <button type="button" class="btn btn-danger" id="btnBtrcReject" >Reject</button>
                                        <button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--modal end btrc rejection-->

                <div class="widget widget-shadow">
                    <!--<div class="widget-content widget-radius bg-white padding-30 padding-top-10" style="min-width:480px; min-height:379px; ">
                        <div class="text-right">
                            <button type="button" class="btn btn-primary" id="btnGenerate_EOLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> E&O Letter</button>
                            <button type="button" class="btn btn-primary" id="btnGenerate_SpectrumLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Spectrum Letter</button>
                            <button type="button" class="btn btn-primary" id="btnGenerate_EquipmentLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Equipment List</button>
                            <button type="button" class="btn btn-primary" id="btnGenerate_QuantityLetter"><i class="icon fa-file-word-o" aria-hidden="true"></i> Equipment Quantity</button>
                        </div>-->
                        <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                            <input type="hidden" id="fileName" name="fileName" />
                            <textarea id="letterContent" name="letterContent"></textarea>
                        </form>
<!--                        <form class="hidden" id="formLetterContent1" method="post" action="application/library/docSpectrum.php">-->
<!--                            <input type="hidden" id="fileName1" name="fileName1" />-->
<!--                            <textarea id="letterContent1" name="letterContent1"></textarea>-->
<!--                        </form>-->

                        <form name="zips" action="application/library/zipDownloader.php" id="attachmentZip" method="post">
                            <div id="filesToZip"></div>
                            <input type="hidden" name="docName" value="pra_attachments">
                            <button  type="submit" id="submit" name="createzip" class="btn btn-primary hidden" ><i class="icon fa-download" aria-hidden="true"></i> Download All Shipment Documents</button>
                        </form>

                        <div class="panel nav-tabs-horizontal">
                            <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                <li class="active dropdown" role="presentation">
                                    <a data-toggle="tab" href="#myPendings" aria-controls="myPendings" role="tab"><span class="hot">PENDING</span></a>
                                </li>
                                <li role="presentation">
                                    <a data-toggle="tab" href="#otherPendings" aria-controls="otherPendings" role="tab">SUBMITTED</a>
                                </li>
                            </ul>
                            <form class="form-horizontal" id=btrc-form" name="btrc-form" method="post" autocomplete="off" action="">
                                <input name="userAction" id="userAction" type="hidden" value="" />
                                <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />

                                <div class="tab-content padding-top-5">

                                    <div class="tab-pane active" id="myPendings" role="tabpanel">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table text-center" id="dtMyInbox" >
                                                    <thead >
                                                    <tr >
                                                        <!--<th>ID</th>-->
                                                        <th class="text-center">CARef</th>
                                                        <th class="text-center">PONo(s)</th>
                                                        <th class="text-center">Attachments</th>
<!--                                                        <th>PO</th>-->
<!--                                                        <th>BOQ</th>-->
<!--                                                        <th>Justification</th>-->
<!--                                                        <th>Catalog</th>-->
                                                        <th class="text-center">Action</th>
                                                        <th class="text-center">Download</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="myTable">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="text-right" style="margin-top: 40px!important;">
                                            <button type="button" class="btn btn-success padding-6 margin-15" id="btnPRASubmit" >Send to BTRC For NOC</button>
                                        </div>

                                    </div>


                                    <div class="tab-pane" id="otherPendings" role="tabpanel">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table text-center" id="dtOtherInbox">
                                                    <thead>
                                                    <tr>
<!--                                                        <th>ID</th>-->
                                                        <th class="text-center">CARef</th>
                                                        <th class="text-center">PONo(s)</th>
<!--                                                        <th>Attachments</th>-->
                                                        <th class="text-center">Accept</th>
                                                        <th class="text-center">Reject</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="otherTable">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div style="margin-top: 40px!important;margin-left: 15px;">
                                            <div class="form-group">

                                                <label class="col-sm-12" style="font-weight: bold;">BTRC NOC Attachment:</label>
                                                <div class="col-sm-4" style="margin-right: 10px">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="attachBTRCNOC" id="attachBTRCNOC" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                        <span class="input-group-btn">
                                                            <button type="button" id="btnUploadBTRCNOC" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-success padding-7 margin-10" id="btnPraToBuyerSubmit" >Send to Buyer</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>


<!--                        <?php
/*                        if ($_SESSION[session_prefix . 'wclogin_role'] == role_public_regulatory_affairs){
                        if($actionLog['1stLastAction']==action_Ready_For_Submission){*/?>
                            <div class="text-right">
                                <button type="button" class="btn btn-success p-5 mt-3 mb-3" id="btnPRASubmit" >Send to BTRC For NOC</button>
                            </div>
                        <?php
/*                        }else{*/?>
                            <div class="text-right">
                                <button type="button" class="btn btn-success p-5 mt-3 mb-3" id="btnPRASubmit" >Send to PRA</button>
                            </div>
                        --><?php /*}
                        }
                        */?>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
<!-- End Page -->
