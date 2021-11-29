$dtInit = 0;
$(document).ready(function () {
    $('#dtFeePayment').dataTable({
        "ajax": "api/feepayment-dashboard?action=1&status=0",
        "columns": [
            { "data": "id", "class": "text-center" },
            { "data": "supplier_name" },
            { "data": "nature_of_service" },
            { "data": "currency" },
            { "data": "fx_value" },
            { "data": "value_date" },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    if (full["status"] == 0){
                        return 'Pending';
                    }
                    else if (full["status"] == 1){
                        return 'RFQ Float Done';
                    }
                    else if(full["status"] == 2){
                        return 'RFQ Closed';
                    }
                    else if(full["status"] == 3){
                        return 'Processing';
                    }
                    else if(full["status"] == 4){
                        return 'Settled';
                    }
                    else if(full["status"] == 5){
                        return 'Rejected';
                    }
                }
            },
        ],

        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });

    $("#inlineRadio1").click(function () {
        var dtable = $('#dtFeePayment').dataTable();
        dtable.api().ajax.url("api/feepayment-dashboard?action=1&status=0").load();
    })
    $("#inlineRadio2").click(function () {
        var dtable = $('#dtFeePayment').dataTable();
        dtable.api().ajax.url("api/feepayment-dashboard?action=1&status=1").load();
    })

});
