$(document).ready(function() {

    $('#tblBankNotification').dataTable({
        "ajax": "api/lc-bank?action=5",
        "columns": [
            {"data": "ID", "visible": false},
            // {"data": "PO"},
            { "data": null, "class": "padding-5",
                "render": function(data, type, full) {
                    return `<a href="bank-document-receive?po=${full["PO"]}&ship=${full["shipNo"]}&ref=${full["ID"]}">${full["PO"]}<br />Ship # ${full["shipNo"]}</a>`;
                }
            },
            {"data": "lcno"},
            {"data": "Cur", "class": "text-center"},
            {"data": "ciNo"},
            {"data": "ciAmount", "class": "text-right"},
            {"data": "lcvalue", "class": "text-right"},
            {"data": "lcdate", "class": "text-right"},
            {"data": "lcdesc"}
            ],
        "sorting": [[1, "asc"]],
        "sDom": 'frtipS',
        "paging": true,
        "pageLength": 10
    });
});