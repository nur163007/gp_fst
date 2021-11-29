<?php $title="Technical Acceptance Certificate"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <h1 class="page-title">Technical Acceptance Certificate</h1>
        <ol class="breadcrumb">
            <li>Sourcing</li>
            <li class="active">Buyer</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal hidden" id="form-temp" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">PO: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" >
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">CI Number: </label>
                                <div class="col-sm-3">
                                    <select class="form-control" data-plugin="select2" id="ciList" >
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="goPayment_btn"><i class="icon wb-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="form-horizontal" id="payment-entry-form" name="cac-form" method="post" autocomplete="off">
                    <input name="ciValue" id="ciValue" type="hidden" value="" />
                    <input name="dayOfMaturity" id="dayOfMaturity" type="hidden" value="" />
                    <!--input name="awbDate" id="awbDate" type="hidden" value="" /-->
                    <input name="lcissueDate" id="lcissueDate" type="hidden" value="" />
                    <input name="defaultDoc" id="defaultDoc" type="hidden" value="<?php if(!empty($_GET['d'])){ echo $_GET['d']; } ?>" />
                    <input name="ciNo1" id="ciNo1" type="hidden" value="<?php if(!empty($_GET['ci'])){ echo $_GET['ci']; } ?>" />
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="refId" id="refId" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <div class="row row-lg">

                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Technical Information<span class="pull-right" id="paid"></span></h4>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="facIssueDate" name="facIssueDate" value="" readonly/>
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Project Name: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="projectName" id="projectName" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Commercial Information<span class="pull-right" id="paid"></span></h4>
                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PO Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Commercial Invoice Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control curnum" id="ciNo" name="ciNo" value="0.00" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Value of this FAC: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="valueOfFac" name="valueOfFac" readonly="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Number: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="LcNo" name="LcNo" readonly="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Commercial Invoice Value: </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="ciValue" name="ciValue" value="0.00" readonly="" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xlg-12 col-md-12">
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="form-control" name="" id="certificateText" cols="130" rows="3" readonly>This TAC has been issued for Commercial Purpose.
Based on above mentioned information and fulfilling milestone of this project has been Finally Accepted and add with Grameenphone Network.
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6">
                                <input class="form-control" type="text" id="tacEffectiveFrom" value="TAC effective from: " readonly>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div id="usersAttachments">
                            </div>
                            <div class="form-group">
                                <label class="col-sm-5 control-label">Upload CAC:</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachLCPaymentAdvice" id="attachLCPaymentAdvice" readonly placeholder=".pdf, .docx" />
                                        <input type="hidden" name="attachLCPaymentAdviceOld" id="attachLCPaymentAdviceOld" />
                                        <span class="input-group-btn">
                                            <button type="button" id="btnUploadLCPaymentAdvice" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-warning" id="paymentEntryDraft_btn"><i class="fa fa-save" aria-hidden="true"></i> Save & Generate...</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form class="hidden" id="formLetterContent" method="post" action="application/library/docGen.php">
                    <input type="hidden" id="fileName" name="fileName" />
                    <textarea id="letterContent" name="letterContent"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
