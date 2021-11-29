$(document).ready(function() {

    $('#dtContracts').dataTable({
        "bSort": false,
        "ajax": "api/contract?action=3",
        "columns": [
            {"data": "id", visible: false},
            {"data": "supplierName"},
            {
                "data": null, "sortable": false, "class": "text-center",
                "render": function (data, type, full) {
                    return `<a class="" target="_blank" href="contract?contractId=${full['id']}">${full['contractName']}</a>`
                }
            },
            {"data": "payTermGP", "sortable": false},
            {"data": "payTermSup", "sortable": false},
            {"data": "payTermOth", "sortable": false}],
        "sDom": 'frtip',
        "paging": true
    });
    var table = $('#dtContracts').DataTable();
    $('#exportBtn').empty();
    var buttons = new $.fn.dataTable.Buttons(table, {
        buttons: ['excelHtml5']
    }).container().appendTo($('#exportBtn'));


});