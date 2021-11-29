<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
require_once(LIBRARY_PATH . "/dal.php");
require_once(LIBRARY_PATH . "/lib.php");
//$postatus = POStatus($_GET['po']);
//$actionLog = GetActionRef($_GET['ref']);/**
 /* Created by PhpStorm.
 * User: aaqa
 * Date: 12/18/2016
 * Time: 9:43 AM
 */

$title="User Profile";
?>
<!-- Page -->
<script src="assets/js/plugins/pass-strength.js" type="text/javascript"></script>
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">User Profile</h1>
        <ol class="breadcrumb">
            <li><a>Home: </a></li>
            <li class="active">User Profile</li>
        </ol>
        <div class="page-header-actions">
            &nbsp;
        </div>
    </div>
    <div class="page-content container-fluid">

        <div class="panel">

            <div class="panel-body container-fluid">

                <div class="nav-tabs-horizontal">
                    <ul class="nav nav-tabs" data-plugin="nav-tabs" role="tablist">
                        <li role="presentation" class="<?php  echo !($_SESSION['fst_wclogin_isPassResetRequired']) ? 'active' : '' ; ?>">
                            <a data-toggle="tab" href="#tabProfile" aria-controls="tabProfile" role="tab">
                                <span class="text-primary">Profile</span>
                            </a>
                        </li>
                        <!--<li role="presentation">
                            <a data-toggle="tab" href="#tabSecurity" aria-controls="tabSecurity" role="tab">
                                <span class="text-primary">Security</span>
                            </a>
                        </li>-->
                        <?php if ($_SESSION['fst_wclogin_isPassResetRequired']) {?>
                            <input type="hidden" id="isPassResetRequired" value="true">
                            <li role="presentation" class="active">
                                <a data-toggle="tab" href="#tabSecurity" aria-controls="tabSecurity"  role="tab">
                                    <span class="text-primary"><i class="icon fa fa-lock"></i> Security</span>
                                </a>
                            </li>
                        <?php } else{?>
                            <li role="presentation">
                                <a data-toggle="tab" href="#tabSecurity" aria-controls="tabSecurity" role="tab">
                                    <span class="text-primary"><i class="icon fa fa-lock"></i> Security</span>
                                </a>
                            </li>
                        <?php }?>
                    </ul>

                    <div class="tab-content padding-top-20">

                        <div class="tab-pane <?php  echo !($_SESSION['fst_wclogin_isPassResetRequired']) ? 'active' : '' ; ?>" id="tabProfile" role="tabpanel">

                            <form class="form-horizontal" id="formProfile" name="formProfile" method="post" autocomplete="off">
                                <input type="hidden" name="action" value="2" />
                                <input type="hidden" name="csrf_token" value="<?php echo generateToken('formProfile'); ?>"/>
                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">
                                        <!--<div class="form-group">
                                            <label class="col-sm-4 control-label">Username: </label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="username" name="username" readonly />
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">First Name:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="firstname" name="firstname" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Last Name:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="lastname" name="lastname" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Mobile:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="mobile" name="mobile" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Email:</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" id="email" name="email" />
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="modal-footer text-right">
                                            <label class="wc-error pull-left" id="form_error"></label>
                                            <button type="button" class="btn btn-primary" id="btnProfileSave" ><i class="icon fa-save" aria-hidden="true"></i> Save</button>
                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                        <div class="tab-pane <?php  echo ($_SESSION['fst_wclogin_isPassResetRequired']) ? 'active' : '' ; ?>" id="tabSecurity" role="tabpanel">

                            <form class="form-horizontal" id="formPassword" name="formPassword" method="post" autocomplete="off">
                                <input type="hidden" name="action" value="3" />
                                <input type="hidden" name="csrf_token_p" value="<?php echo generateToken('formPassword'); ?>"/>
                                <div class="row row-lg">

                                    <div class="col-xlg-6 col-md-6">

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label" for="currentPassword">Current Password:</label>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" id="currentPassword" name="currentPassword" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label" for="newpassword">New Password:</label>
                                            <div class="col-sm-6">
                                                <div class='pwdwidgetdiv' id='thepwddiv'></div>
                                                <script  type="text/javascript" >
                                                    var pwdwidget = new PasswordWidget('thepwddiv','newpassword');
                                                    pwdwidget.MakePWDWidget();
                                                </script>
                                                <noscript>
                                                    <div><input type="password" class="form-control" id="newpassword" name="newpassword" minlength="8" /></div>
                                                </noscript>
                                                <input type="hidden" id="passScore">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-6 control-label" for="confirmnewpassword">Confirm New Password:</label>
                                            <div class="col-sm-6">
                                                <input type="password" class="form-control" id="confirmnewpassword" name="confirmnewpassword" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label"></label>
                                            <div class="col-sm-6">
                                                <div class="checkbox-custom checkbox-primary">
                                                    <input type="checkbox" id="shPass" name="shPass" onclick="togglePassword()" />
                                                    <label for="shPass">Show Password</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="modal-footer text-right">
                                            <label class="wc-error pull-left" id="form_error"></label>
                                            <button type="button" class="btn btn-primary" id="btnSecuritySave" ><i class="icon fa-save" aria-hidden="true"></i> Save</button>
                                        </div>

                                    </div>

                                    <div class="col-xlg-6 col-md-6">
                                        <div class="example example-popover" style="margin-top: 0">
                                            <button type="button" class="btn btn-info popover-info sr-only">
                                                Info
                                            </button>
                                            <div class="popover right" style="max-width: 100%">
                                                <div class="arrow"></div>
                                                <h3 class="popover-title"><i class="icon fa-info-circle" aria-hidden="true"></i>Password Policy</h3>
                                                <div class="popover-content">
                                                    <ul style="padding-left: 10px;">
                                                        <li >Be a minimum length of eight (8) characters</li>
                                                        <li>Should include at least one upper case letter [A-Z].</li>
                                                        <li>Should include at least one lower case letter [a-z].</li>
                                                        <li>Should include at least one number [0-9].</li>
                                                        <li>Should include at least one special character  (e.g. !@#$%()^&*\-=_+[\]{}/?. ).</li>
                                                        <li >Not be a dictionary word or proper name.</li>
                                                        <li >Not be the same as the User ID.</li>
                                                        <li >Not be identical to the previous three (3) passwords.</li>
                                                    </ul>
                                                    <p>To see example, click on <b>Generate</b> button(below the <i>New Password</i> box).</p>
                                                    <!--<p><?php /*var_dump(belongsToPassHistory(30, '[1Qv62!}L>gUj'));*/?></p>-->
                                                </div>
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
    </div>
</div>
<!-- End Page -->
