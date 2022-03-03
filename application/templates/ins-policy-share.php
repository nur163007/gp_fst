<?php
$title="Insurance Policy Share";
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
$actionLog = GetActionRef($_GET['ref']);
?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Insurance Policy Share Against PO# <?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?></h1>
        <ol class="breadcrumb">
            <li><a>Status: </a></li>
            <li class="active"><?php echo $actionLog['ActionDone']; ?></li>
        </ol>
        <div class="page-header-actions">
            &nbsp;
        </div>
    </div>
    <div class="page-content container-fluid">

        <div class="panel">

            <div class="panel-body container-fluid">

                <form class="form-horizontal" id="form-cn-request" name="form-cn-request" method="post" autocomplete="off"  >
                    <input name="pono" id="pono" type="hidden" value="<?php if(!empty($_GET['po'])){ echo $_GET['po']; } ?>" />
                    <input name="shipno" id="shipno" type="hidden" value="<?php if(!empty($_GET['ship'])){ echo $_GET['ship']; } ?>" />
                    <input name="usertype" id="usertype" type="hidden" value="<?php echo $_SESSION[session_prefix.'wclogin_role']; ?>" />
                    <input name="refId1" id="refId1" type="hidden" value="<?php if(!empty($_GET['ref'])){ echo $_GET['ref']; } ?>" />
                    <input name="userAction" id="userAction" type="hidden" value="" />
                    <div class="row">
                        <div class="col-sm-6">
                            <div id="usersAttachments">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <h4 class="well well-sm example-title">Policy File Attachments</h4>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" style="margin-top: 5px">Policy File :</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachIcFile" id="attachIcFile" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <input type="hidden" class="form-control" name="attachIcFilehidden" id="attachIcFilehidden"  />
                                        <span class="input-group-btn"><button type="button" id="btnUploadIcFile" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="model-footer text-right" style="margin-top: 100px">
                                    <label class="wc-error pull-left" id="form_error"></label>
                                    <button type="button" class="btn btn-primary" id="btnInsuranseFileSubmit" >Submit</button>
                                    <!--<button type="button" class="btn btn-default btn-outline" data-dismiss="modal" aria-label="Close" onclick="ResetForm()">Reset</button>-->
                                </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
<!-- End Page -->