<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 1/9/2017
 * Time: 1:27 PM
 */
?>

<?php $title="Explorer"; ?>
<div class="page bg-blue-100 animsition">
    <!--div class="page-header">

        <h1 class="page-title">Outstanding LC list</h1>

        <ol class="breadcrumb">
            <li><a>Report: </a></li>
            <li class="active">Outstanding</li>
        </ol>

        <div class="page-header-actions">
			&nbsp;
		</div>
    </div-->
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="form-horizontal">
                    <div class="row row-lg">

                        <div class="col-xlg-12 col-md-12">
                            <h4 class="well well-sm example-title">Explorer</h4>
                        </div>

                    </div>

                    <div class="row row-lg">
                        <div class="col-xlg-6 col-md-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Destination:</label>
                                <div class="col-sm-10">
                                    <select class="form-control" data-plugin="select2" name="destFolder" id="destFolder" >
                                        <option ></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">File:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="attachpo" id="attachpo" readonly placeholder=".pdf, .docx, .jpg, .png" />
                                        <span class="input-group-btn">
                                                <button type="button" id="btnUploadPo" class="btn btn-outline"><i class="icon wb-upload" aria-hidden="true"></i></button>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xlg-6 col-md-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Page -->
