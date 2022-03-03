$(document).ready(function() {

    $('#tableFxSettementReport').dataTable({
        "ajax": "api/fx-settelment-report?action=1",
        "columns": [
            { "data": "id" },
            { "data": "RfqDate" },
            { "data": "req_type" },
            { "data": "currency" },
            { "data": "fx_value" },
            { "data": "name" },
            { "data": "FxRate" },
            { "data": "DealAmount" },
            { "data": "PotentialLoss" },
            { "data": "remarks" },
        ],
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });

    /*------Excel Export-------*/
    var table = $('#tableFxSettementReport').DataTable();
    $('#exportBtnForFxSettlementReport').empty();
    var buttons = new $.fn.dataTable.Buttons(table, {
        buttons: ['excelHtml5']
    }).container().appendTo($('#exportBtnForFxSettlementReport'));
});