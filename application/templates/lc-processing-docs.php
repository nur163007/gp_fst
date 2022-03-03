<?php
$title = "LC Processing Docs";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
//$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header page-header-bordered">
        <ol class="breadcrumb">
            <li><a href="dashboard">TFO Dashboard</a></li>
        </ol>
        <h1 class="page-title">LC Processing Documents</h1>
        <div class="page-header-actions hidden">

        </div>
    </div>
    <div class="page-content">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">

                <div class="col-xlg-12 col-md-12">
                    <div class="row" style="min-height : 250px;">
                        <div class="col-xs-12">
                            <div class="example table-responsive">
                                <form id="lcProcessingDocuments" name="lcProcessingDocuments">
                                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                                    <!-- Start view all attachment modal-->
                                    <div class="modal fade modal-slide-in-top" id="viewAllDocAttach" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                                        <span aria-hidden="true">x</span>
                                                    </button>
                                                    <h4 class="modal-title">All Documents</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered table-hover dataTable table-striped width-full"
                                                           id="allLCDocs">
                                                        <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>File Name</th>
                                                            <th>Updated Date</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="myTable">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default margin-top-5" data-dismiss="modal" >Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End view all attachment  modal-->

                                       <!-- Attachment replce modal form-->
                                    <div class="modal fade modal-slide-in-top" id="replaceDocAttach" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">
                                                        <span aria-hidden="true">x</span>
                                                    </button>
                                                    <h4 class="modal-title">Replace document</h4>
                                                </div>
                                                <div class="modal-body">
<!--                                                    <span><strong id="replaceDocAttachmentPrev">.....docx</strong></span> replace with...-->
                                                    <div class="input-group">
                                                         <input type="text" class="form-control" name="replaceDocAttachNew" id="replaceDocAttachNew" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                                         <input type="hidden" class="form-control" name="replaceDocAttachOld" id="replaceDocAttachOld"/>
                                                        <span class="input-group-btn">
                                            <button type="button" id="btnReplaceDocAttachNew" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                        </span>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default margin-top-5" data-dismiss="modal"  onclick="ResetForm();">Cancel</button>
                                                    <button type="button" class="btn btn-danger margin-top-5" data-dismiss="modal" id="replaceDocAttachment_btn">Replace</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End attachment replce modal form -->



                                    <table class="table table-bordered table-hover dataTable table-striped width-full"
                                           id="lcProcessingDocs">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Document Name</th>
<!--                                            <th>File Download</th>-->
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Panel End -->
    </div>
</div>
<!-- Page End -->
