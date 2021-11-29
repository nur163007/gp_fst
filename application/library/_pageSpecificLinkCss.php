<?php
$page_specific_css = array(
    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
);
if(isset($_REQUEST['q'])) {
    $page = $_REQUEST['q'];

    switch ($page) {
        case "dashboard":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
            );
            break;
        case "category":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "users":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "profile":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/toastr/toastr.css" />',
                '<link rel="stylesheet" href="assets/css/widgets/pass-strength.css" />'
            );
            break;
        case "user-roles":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "contract":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "contracts":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
            );
            break;

        case "privilege":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
            );
            break;
        case "company":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "content":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "navigations":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "bank-insurance":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "events":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/fullcalendar/fullcalendar.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-touchspin/bootstrap-touchspin.css">',
                '<link rel="stylesheet" href="assets/vendor/jquery-selective/jquery-selective.css">',
                '<link rel="stylesheet" href="assets/css/apps/calendar.css">'
            );
            break;
        case "new-po":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "new-pi":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "edit-po":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "all-po":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">'
            );
            break;
        case "suppliers-pi":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "buyers-piboq":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "pr-ea-interface":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "final-pi":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "view-po":
            $page_specific_css = array(
                ''
            );
            break;

        case "btrc-interface":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/x-editable/address.css">',
                '<link rel="stylesheet" href="assets/vendor/typeahead-js/typeahead.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">'
            );
            break;
        case "buyers-lc-request":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/typeahead-js/typeahead.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
        case "lc-request":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/css/print-table.css" media="print" type="text/css" />'
            );
            break;
        case "lc-opening":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/css/print-table.css" media="print" type="text/css" />'
            );
            break;
        case "lc-acceptance":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">'
            );
            break;
        case "marine-insurance":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "lc-opening-bank-charges":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "original-doc":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "amendment-request":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "endorsement":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
            );
            break;
        case "average-cost-fin":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
            );
            break;
        case "payment-entry":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "custom-duty":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        case "shipment":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">'
            );
            break;
        case "buyer-prealert":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">'
            );
            break;
        case "shipment-ext":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">'
            );
            break;
        case "warehouse-inputs":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">'
            );
            break;
        case "ea-inputs":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">'
            );
            break;

        case "cnf-cost-update":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "fn-report-outstanding-lc-list":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "fn-report-payment-detail":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "custom-duty-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "lc-commission-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "vat-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "insurance-premium-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "fn-report-fund-utilization":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "fn-report-trade-finance":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "fn-report-operational-update":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "aging-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/alertify-js/alertify.css">'
            );
            break;

        case "lc-wise-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "lc-opening-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "close-sourcing-process":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/x-editable/x-editable.css">',
                '<link rel="stylesheet" href="assets/vendor/x-editable/address.css">',
                '<link rel="stylesheet" href="assets/vendor/typeahead-js/typeahead.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "sourcing-report-po-database":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "sourcing-report-po-wise-sla":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "buyer-wise-po-report":
        case "fn-report-lc-opening" :
        case "fn-report-lc-endorsement":
        case "fn-report-lc-amendment":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/flag-icon-css/flag-icon.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "charge-entry":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "explorer":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "service-receiving-form":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

/*        case "delivery-notification":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;*/
			
        case "service":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
			
        case "tac-request":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;
		
		case "certificate-download":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

		case "payment-history":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "payment-maturity":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css" />',
                '<link rel="stylesheet" href="assets/vendor/icheck/icheck.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css" />'
            );
            break;

        case "p2p-report":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

            case "fx-request":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "fx-settelment-report":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "fx-request-hot":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "fx-rfq-process":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
    
            case "fx-requisition":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "feepayment-dashboard":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "fx-rfq-request":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "bank-dashboard":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;
            case "fx-rfq-bank-offer":
                $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;

        case "cn-request":
             $page_specific_css = array(
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                    '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                    '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                    '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                    '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                    '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
                );
                break;


        case "ca-interface":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "pra-interface":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "delivery-notification":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "ici-interface":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;

        case "lc-bank":
            $page_specific_css = array(
                '<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.css">',
                '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
                '<link rel="stylesheet" href="assets/vendor/select2/select2.css">',
                '<link rel="stylesheet" href="assets/vendor/bootstrap-select/bootstrap-select.css">',
                '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />',
                '<link rel="stylesheet" href="assets/vendor/asspinner/asSpinner.css">'
            );
            break;
        default:
            $page_specific_css = array();
    }

} else {
    $page_specific_css = array(
        '<link rel="stylesheet" href="assets/vendor/daterangepicker/daterangepicker.css" />',
        '<link rel="stylesheet" href="assets/vendor/datatables-bootstrap/dataTables.bootstrap.css" />'
    );
}


?>