<?php $title="Users";?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
	
    <div class="page-header page-header-bordered">
		<ol class="breadcrumb">
			<li><a href="dashboard">Admin</a></li>
			<li class="active">Site Manager</li>
		</ol>
		<h1 class="page-title">Privilege</h1>
	</div>
    
    <div class="page-content container-fluid">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-xlg-3 col-md-3">
                        <div class="nav-tabs-vertical">
                            <ul class="nav nav-tabs" style="width: 100%;" id="userRole">
                                <!--li class="active"><a data-toggle="tab" href="javascript:void(0)">Administrator</a></li>
                                <li><a data-toggle="tab" href="javascript:void(0)">Components</a></li>
                                <li><a data-toggle="tab" href="javascript:void(0)">Css</a></li>
                                <li><a data-toggle="tab" href="javascript:void(0)">Javascript</a></li-->
                            </ul>
                        </div>                    
                    </div>
                    <div class="col-xlg-8 col-md-8">
                        <!-- Table-->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable table-striped width-full" id="dtPrivilege">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>RoleId</th>
                                        <th>Name</th>
                                        <th>Url</th>
                                        <th>Category</th>
                                        <th class="text-center" style="width:80px;">Enable</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table-->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Panel -->
    </div>    
</div>
<!-- End Page -->