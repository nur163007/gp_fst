<?php $title="Bank Charge";?>
<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");

?>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Bank Charge</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="bank-charge-form" name="bank-charge-form" method="post" autocomplete="off">
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
<!--                    <input type="hidden" id="poNumber" name="poNumber" value="--><?php //if(!empty($_GET['po'])){ echo $_GET['po']; } ?><!--" />-->
<!--                    <input name="refId" id="refId" type="hidden" value="--><?php //if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?><!--" />-->
<!--                    <input name="shipno" id="shipno" type="hidden" value="--><?php //if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?><!--" />-->
<!--                    <input name="LcNo1" id="LcNo1" type="hidden" value="" />-->
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <h4 class="well well-sm example-title">Bank Charge Information</h4>
                        </div>
                        <div class="col-xlg-7 col-md-7">

                            <div class="form-group">
                                <label class="col-sm-4 control-label">LC Issuing Bank:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" data-plugin="select2" name="LcIssuingBank" id="LcIssuingBank">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Cable Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="cableCharge" id="cableCharge"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Stamp Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="stampCharge" id="stampCharge"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Non Vat Other Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="nonVatOtherCharge" id="nonVatOtherCharge"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Other Charge:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="otherCharge" id="otherCharge"/>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr />

                        <div class="row row-lg">
                            <div class="col-xlg-12 col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-primary" id="SaveBankCharge_btn">Save Bank Charge</button>
                                        <button type="button" class="btn btn-default btn-outline" id="close_btn">Close</button>
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
