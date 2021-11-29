<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
//@todo upload this file
$title="New Contract";
$contractId = isset($_GET['contractId']) ? $_GET['contractId'] : 0;

?>
<style>
    #info-block section {
        border: 1px solid #457ed9;
    }

    .file-marker > div {
        padding: 0 10px;
        height: auto;
        margin-top: -0.8em;

    }
    .box-title {
        background: white none repeat scroll 0 0;
        display: inline-block;
        padding: 0 2px;
        margin-left: 19em;
    }
</style>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">Dashboard</a></li>
            <li class="active">Contract</li>
        </ol>
        <h1 class="page-title"><?php echo ($contractId > 0) ? " Edit Contract" : " New Contract"?></h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <!--<div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="oldContracts">Contracts</label>
                        <div class="col-sm-3">
                            <select class="form-control" data-plugin="select2" name="oldContracts" id="oldContracts" > </select>
                        </div>
                    </div>
                </div>-->
                <div class="" id="payment-terms">
                    <form class="form-horizontal" id="contract-form" name="contract-form" method="post" autocomplete="off">
                        <input type="hidden" id="contractId" name="contractId" value="<?php echo $contractId ?>">
                        <div class="row row-lg">
                            <div class="col-xlg-12 col-md-12">
                                <h4 class="well well-sm example-title">Payment Terms </h4>
                                <div class="row row-lg">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="contractName">Contract Number: </label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="contractName" name="contractName" value="GP-"  />
                                        </div>
                                        <label class="col-sm-2 control-label" for="contractDesc">Description: </label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" name="contractDesc" id="contractDesc" rows="2" placeholder="Terms description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Contract Terms:</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="hidden" name="termAttach" id="termAttach" />
                                                <label class="form-control" id="lblTermAttach">.pdf,.zip</label>
                                                <div class="clearAttachment" id="clearAttachment">X</div>
                                                <input type="hidden" value=".pdf,.zip" />
                                                <span class="input-group-btn">
                                                    <button type="button" id="btnTermAttach" class="btn btn-outline">
                                                        <i class="icon wb-upload" aria-hidden="true"></i>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-sm-12">
                                        <!-- Example Radios -->
                                        <div class="margin-left-15">
                                            <h4 class="example-title">Implementation By</h4>
                                            <ul class="list-unstyled list-inline">
                                                <li class="checkbox-custom checkbox-primary margin-right-15">
                                                    <input type="checkbox" id="by_0" name="enableGp" autocomplete="off">
                                                    <label for="by_0">Grameenphone</label>
                                                </li>
                                                <li class="checkbox-custom checkbox-info margin-right-15">
                                                    <input type="checkbox" id="by_1" name="enableSup" autocomplete="off">
                                                    <label for="by_1">Supplier</label>
                                                </li>
                                                <li class="checkbox-custom checkbox-warning">
                                                    <input type="checkbox" id="by_2" name="enableOth" autocomplete="off">
                                                    <label for="by_2">Other</label>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- End Example Radios -->
                                    </div>

                                    <div class="col-md-12">
                                        <aside id="info-block">
                                            <section class="file-marker" id="gp-impl-area">
                                                <div class="box-title">
                                                    <div class="form-group">
                                                        <label class="col-sm-7">Implementation by: </label>
                                                        <div class="col-sm-5">
                                                            <span style="font-weight: 700;">Grameenphone</span>
                                                            <input type="hidden" id="gp_implnBy" name="gp_implnBy" value="0" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="" id="gp_addContractHtml">
                                                    <div class="form-group">
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="gp_rowSerial">Serial</label>
                                                            <!--<label id="gp_rowSerial" class="form-control" style="border:0px">1 .</label>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="gp_percentage">Per.(%)</label>
                                                            <!--<input type="text" class="form-control" name="gp_percentage[]" id="gp_percentage">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="gp_certificateName">Certificate</label>
                                                            <!--<select class="form-control" data-plugin="select2" name="gp_certificateName[]" id="gp_certificateName1" > </select>-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="gp_matDays">Maturity Days</label>
                                                            <!--<input type="text" class="form-control" name="gp_matDays[]" id="gp_matDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="gp_matTerms">Maturity Terms</label>
                                                            <!--<select class="form-control" data-style="btn-select" data-plugin="selectpicker"  name="gp_matTerms[]" id="gp_matTerms"  title="Maturity Terms">
                                                                <option disabled selected>Select a term</option>
                                                                <option value="9">Air Way Bill Date</option>
                                                                <option value="10">Bill of Lading</option>
                                                                <option value="11">LC Issuance</option>
                                                                <option value="12">Shipment Date</option>
                                                            </select>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" data-placement="top" data-toggle="tooltip" data-original-title="Certificate Days" for="gp_certDays">Days</label>
                                                            <!--<input type="text" class="form-control" name="gp_certDays[]" id="gp_certDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="gp_certTitle">Certificate Title</label>
                                                            <!--<input type="text" class="form-control" name="gp_certTitle[]" id="gp_certTitle">-->
                                                        </div>

                                                        <div class="col-md-1">
                                                            <!--<label class="control-label">&nbsp;</label>-->
                                                            <button class="btn btn-primary pull-right" type="button" id="gp_btnAddNewRow" style="margin-top: 28px;;">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="gp_addedRows">

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="gp_paymentTermsText" id="gp_paymentTermsText" rows="3" placeholder="Terms description"></textarea>
                                                    </div>
                                                </div>
                                            </section>
                                        </aside>

                                        <hr>

                                        <aside id="info-block">
                                            <section class="file-marker" id="sup-impl-area">
                                                <div class="box-title">
                                                    <div class="form-group">
                                                        <label class="col-sm-8">Implementation by: </label>
                                                        <div class="col-sm-4">
                                                            <span style="font-weight: 700;">Supplier</span>
                                                            <input type="hidden" id="sup_implnBy" name="sup_implnBy" value="1" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="" id="sup_addContractHtml">
                                                    <div class="form-group">
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="sup_rowSerial">Serial</label>
                                                            <!--<label id="sup_rowSerial" class="form-control" style="border:0px">1 .</label>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="sup_percentage">Per.(%)</label>
                                                            <!--<input type="text" class="form-control" name="sup_percentage[]" id="sup_percentage">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="sup_certificateName">Certificate</label>
                                                            <!--<select class="form-control" data-plugin="select2" name="sup_certificateName[]" id="sup_certificateName1" > </select>-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="sup_matDays">Maturity Days</label>
                                                            <!--<input type="text" class="form-control" name="sup_matDays[]" id="sup_matDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="sup_matTerms">Maturity Terms</label>
                                                            <!--<select class="form-control" data-style="btn-select" data-plugin="selectpicker"  name="sup_matTerms[]" id="sup_matTerms"  title="Maturity Terms">
                                                                <option disabled selected>Select a term</option>
                                                                <option value="9">Air Way Bill Date</option>
                                                                <option value="10">Bill of Lading</option>
                                                                <option value="11">LC Issuance</option>
                                                                <option value="12">Shipment Date</option>
                                                            </select>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" data-placement="top" data-toggle="tooltip" data-original-title="Certificate Days" for="sup_certDays">Days</label>
                                                            <!--<input type="text" class="form-control" name="sup_certDays[]" id="sup_certDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="sup_certTitle">Certificate Title</label>
                                                            <!--<input type="text" class="form-control" name="sup_certTitle[]" id="sup_certTitle">-->
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label class="control-label">&nbsp;</label>
                                                            <button class="btn btn-primary pull-right" type="button" id="sup_btnAddNewRow" style="margin-top: 28px;;">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="sup_addedRows">

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="sup_paymentTermsText" id="sup_paymentTermsText" rows="3" placeholder="Terms description"></textarea>
                                                    </div>
                                                </div>
                                            </section>
                                        </aside>

                                        <hr>

                                        <aside id="info-block">
                                            <section class="file-marker" id="other-impl-area">
                                                <div class="box-title">
                                                    <div class="form-group">
                                                        <label class="col-sm-8">Implement by: </label>
                                                        <div class="col-sm-4">
                                                            <span style="font-weight: 700;">Other</span>
                                                            <input type="hidden" id="oth_implnBy" name="oth_implnBy" value="2" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="" id="oth_addContractHtml">
                                                    <div class="form-group">
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="oth_rowSerial">Serial</label>
                                                            <!--<label id="oth_rowSerial" class="form-control" style="border:0px">1 .</label>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" for="oth_percentage">Per.(%)</label>
                                                            <!--<input type="text" class="form-control" name="oth_percentage[]" id="oth_percentage">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="oth_certificateName">Certificate</label>
                                                            <!--<select class="form-control" data-plugin="select2" name="oth_certificateName[]" id="oth_certificateName1" > </select>-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="oth_matDays">Maturity Days</label>
                                                            <!--<input type="text" class="form-control" name="oth_matDays[]" id="oth_matDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="oth_matTerms">Maturity Terms</label>
                                                            <!--<select class="form-control" data-style="btn-select" data-plugin="selectpicker"  name="oth_matTerms[]" id="oth_matTerms"  title="Maturity Terms">
                                                                <option disabled selected>Select a term</option>
                                                                <option value="9">Air Way Bill Date</option>
                                                                <option value="10">Bill of Lading</option>
                                                                <option value="11">LC Issuance</option>
                                                                <option value="12">Shipment Date</option>
                                                            </select>-->
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label class="control-label" data-placement="top" data-toggle="tooltip" data-original-title="Certificate Days" for="oth_certDays">Days</label>
                                                            <!--<input type="text" class="form-control" name="oth_certDays[]" id="oth_certDays">-->
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="control-label" for="oth_certTitle">Certificate Title</label>
                                                            <!--<input type="text" class="form-control" name="oth_certTitle[]" id="oth_certTitle">-->
                                                        </div>

                                                        <div class="col-md-1">
                                                            <label class="control-label">&nbsp;</label>
                                                            <!--<a href="javascript:void(0);" onclick="removeRow(1);" style="float:left; margin-top: 28px; display: none;"> <i  class="fa fa-close btn btn-danger"></i></a>-->
                                                            <button class="btn btn-primary pull-right" type="button" id="oth_btnAddNewRow" style="margin-top: 28px;;">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="oth_addedRows">

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="oth_paymentTermsText" id="oth_paymentTermsText" rows="3" placeholder="Terms description"></textarea>
                                                    </div>
                                                </div>
                                            </section>
                                        </aside>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="row row-lg">
                            <div class="col-xlg-12 col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-primary" id="btn_SubmitContract">Submit</button>
                                        <a href="<?php echo $adminUrl;?>" class="btn btn-default btn-outline">Close</a>
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
<!-- End Page -->
