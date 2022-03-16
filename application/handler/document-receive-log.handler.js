$(document).ready(function() {
    $('#tblBankDocRecLog').dataTable({
        "ajax": "api/lc-bank?action=7",
        "columns": [
            {"data": "id", "visible": false},
            {"data": "pono", "class": "text-center"},
            {"data": "lcno", "class": "text-center"},
            {"data": "shipno", "class": "text-center"},
            {"data": "banknotifydate", "class": "text-center"},
            // {"data": "PO"},
            { "data": null, "class": "padding-5", "class": "text-center",
                "render": function(data, type, full) {
                if (full["status"] == 0){
                    return `<p class="text-info">No</p>`;
                }
                else if(full["status"] == 1) {
                    return `<p class="text-warning">Yes</p>`;
                }
            }
            },
             { "data": null, "class": "padding-5", "class": "text-center",
                "render": function(data, type, full) {
                if (full["originalDoc"] !=''){
                return '<a href="download-attachment/'+full['originalDoc']+'" style="text-decoration: none; color: red" download >'+full["originalDoc"]+'</a>';
                }
                else {
                    return '<p class="text-warning" >No files</p>';
                }
            }
            },
            { "data": null, "class": "padding-5","class": "text-center",
                "render": function(data, type, full) {
                    if (full["banknotifydate"] != null){
                        return `<p class="text-success">Notified Date</p>`;
                    }
                    else {
                        return `<p class="text-warning">Notification Pending</p>`;
                    }
                }
            }
        ],
        "sorting": [[1, "asc"]],
        "sDom": 'frtipS',
        "paging": true,
        "pageLength": 10
    });
});