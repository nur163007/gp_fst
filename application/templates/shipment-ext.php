<?php $title="Shipment Extension"; ?>
<!-- Page -->
<div class="page bg-blue-100 animsition">
    <div class="page-header page-header-bordered">
        <h1 class="page-title">Shipment Extension</h1>
        <ol class="breadcrumb">
            <li>PO</li>
            <li class="active">Shipment</li>
        </ol>
    </div>
    <div class="page-content container-fluid">
        <div class="panel">
            <div class="panel-body container-fluid">
                <form class="form-horizontal" id="form-ship-ext" autocomplete="off">
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">PO: </label>
                                <div class="col-sm-4">
                                    <select class="form-control" data-plugin="select2" id="poList" name="poList">
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Existing Shipment: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="existingShip" name="existingShip" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-lg">
                        <div class="col-xlg-12 col-md-12">
                            <div class="form-group">
                                <label class="col-sm-offset-5 col-sm-2 control-label">Add New Shipment: </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="noship" name="noship" maxlength="2" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary" id="execute_btn"><i class="icon wb-plus" aria-hidden="true"></i></button>
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
