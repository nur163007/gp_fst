<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <div id="leftMenuContainer">
                    <?php
                        //echo $activeNode;
						require_once(CLASS_PATH . "/leftnav.controller.php");
						echo GetNavGroup($_SESSION[session_prefix.'wclogin_role']);
					?>
                </div>
                <div>
                    <ul class="site-menu">

                        <!--<li class="site-menu-category">General</li>-->
                        
                        <!--li class="site-menu-item has-sub">
                            <a href="javascript:void(0)" data-slug="layout">
                                <i class="site-menu-icon wb-order" aria-hidden="true"></i>
                                <span class="site-menu-title">Purchase Order</span>
                                <span class="site-menu-arrow"></span>
                            </a>
                            <ul class="site-menu-sub">
                                <li class="site-menu-item">
                                    <a class="animsition-link" href="/application/CreatePO" data-slug="layout-grids">
                                        <i class="site-menu-icon " aria-hidden="true"></i>
                                        <span class="site-menu-title">Create New PO</span>
                                    </a>
                                </li>
                                <li class="site-menu-item">
                                    <a class="animsition-link" href="/application/DraftPIBOQCatelog" data-slug="layout-headers">
                                        <i class="site-menu-icon " aria-hidden="true"></i>
                                        <span class="site-menu-title">Draft PI BOQ &amp; Catelog</span>
                                    </a>
                                </li>
                                <li class="site-menu-item">
                                    <a class="animsition-link" href="/application/DraftPOPI" data-slug="layout-headers">
                                        <i class="site-menu-icon " aria-hidden="true"></i>
                                        <span class="site-menu-title">Draft PO &amp; PI</span>
                                    </a>
                                </li>
                            </ul>
                        </li-->

                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="site-menubar-footer">
        <a href="profile" class="fold-show" data-placement="top" data-toggle="tooltip"
            data-original-title="Settings">
            <span class="icon wb-settings" aria-hidden="true"></span>
        </a>
        <?php if ($_SESSION[session_prefix.'wclogin_role'] != role_Supplier) {?>
        <a href="<?php echo 'http://support.aaqa.co/api/auto-login?al-action=auto-login&params='.appToken.'|'.$_SESSION[session_prefix . 'wclogin_fullname'].'|'.$_SESSION[session_prefix . 'wclogin_email'].'|'.appId;?>" target="_blank" class="fold-show" data-placement="top" data-toggle="tooltip"
            data-original-title="Support">
            <span class="icon fa fa-support" aria-hidden="true"></span>
        </a>
        <?php }?>
        <a href="/logout" data-placement="top" data-toggle="tooltip" data-original-title="Logout">
            <span class="icon wb-power" aria-hidden="true"></span>
        </a>
    </div>
</div>