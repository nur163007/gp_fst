<?php $title="Buyer's Draft PI BOQ Catalog"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header">
        <ol class="breadcrumb">
            <li><a href="../index.html">General</a></li>
            <li class="active">Purchase Order</li>
        </ol>
        <h1 class="page-title">Supplier Final PI BOQ Catalog</h1>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="draftpiboq-form" name="po-form" method="post" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Purchaser's Inputs</h4>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PO No:</label>
                                <div class="col-sm-9"><input name="poid" id="poid" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                                    <label class="control-label"><b id="ponum">xxx</b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PO Value:</label>
                                <div class="col-sm-9">
                                    <label class="control-label" id="povalue">000</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Description:</label>
                                <div class="col-sm-9">
                                    <label class="control-label text-left" id="podesc">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">L/C Description:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="lcdesc" id="lcdesc" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Number of shipment allowed:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><b id="nofshipallow">1</b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Number of LC will be issued:</label>
                                <div class="col-sm-9">
                                    <label class="control-label"><b id="noflcissue">1</b></label>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Buyer's Attachments</h4>
                            <div id="buyersAttachment">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PO Copy: </label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>xxx</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">BOQ: </label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>xxx</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Other Docs: </label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>xxx</label>
                                    </div>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">GP Comments</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="gpComments">
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">
                            <h4 class="well well-sm example-title">Supplier's Inputs</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI No:</label>
                                <div class="col-sm-8">
                                     <label class="control-label text-left" id="pinum">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Value:</label>
                                <div class="col-sm-8">
                                     <label class="control-label text-left" id="pivalue">xxx</label>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Shipment Mode:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="shipmode">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes Sea:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="hscsea">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">HS Codes Air:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="hscode">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Insurance/Base Value:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="insurance">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">PI Date:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="piDate">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Country of Origin:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="origin">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Negotiating Bank:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="negobank">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Port of Shipment:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="shipport">xxx</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">L/C Beneficiary &amp; Address:</label>
                                <div class="col-sm-8">
                                    <label class="control-label text-left" id="lcbankaddress">xxx</label>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title">Supplier's Attachments</h4>
                            <div id="suppliersAttachments">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Draft PI:</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>xxx</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Draft BOQ:</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i><a href="#">xxx</a></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Catalog:</label>
                                    <div class="col-sm-9">
                                        <label class="control-label"><i class="icon wb-file"></i>xxx</label>
                                    </div>
                                </div>
                            </div>
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">Vendor's previous Message</h4>
                            <div class="form-group">
                                <div class="col-sm-11 table-bordered margin-left-20 padding-20" id="vendorsPremsg">
                                </div>
                            </div>
                            <h4 class="well well-sm example-title" id="buyersmsgtitle">Supplier Comments</h4>
                            <div class="form-group">
                                <div class="col-sm-11" id="usersmsg">
                                    <textarea class="form-control" name="suppComm" id="suppComm" rows="2" placeholder="Message to Buyer"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-12">
                            <div class="form-group">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary">Submit Final PI-BOQ-Catalog</button>
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