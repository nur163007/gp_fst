/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false;
$(document).ready(function() {

    $.getJSON("api/users?action=5&role=2", function (list) {
        $("#buyerList").select2({
            data: list,
            placeholder: "select a Buyer",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a Supplier",
            allowClear: true,
            width: "100%"
        });
    });

    $("#buyerList, #supplier").change(function(e){
        refreshReport();
    });
    refreshReport();
});

function refreshReport(){

    var buyerList = $("#buyerList").val(),
        supplier = $("#supplier").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(!dtInit) {
        //alert('api/buyer-wise-po-report?action=1&start='+start+"&end="+end+'&buyerList='+buyerList+'&supplier='+supplier);
        $('#displayTable').DataTable({
            "ajax": 'api/buyer-wise-po-report?action=1&start='+start+"&end="+end+'&buyerList='+buyerList+'&supplier='+supplier,
            "columns": [
                { "data" : "PO Number" },
                { "data" : "PO Buyer" },
                { "data" : "Supplier" },
                /*{ "data" : "PR Approval Date" },
                { "data" : "PO Approval Date" },*/
                { "data" : "PO Description" },
                { "data" : "PO & BOQ Sent to Vendor" },
                { "data" : "PO Need by Date" },
                { "data" : "PI & BOQ Receive Date" },
                { "data" : "Lead Time" },
                { "data" : "Discount", "class":"text-right" },
                { "data" : "Request for BTRC Permission" },
                { "data" : "BTRC Permission Received" },
                { "data" : "Apply for LC" },
                { "data" : "LC Receive Date" },
                { "data" : "Scan Copy Receive Date" },
                { "data" : "Pre-Alert & GIT receiving & Doc Endorse Mail" },
                { "data" : "GIT Received Date" },
                { "data" : "AWB No / BL No" },
                { "data" : "CInvoice Number" },
                { "data" : "CInvoice Date" },
                { "data" : "CInvoice Amount", "class":"text-right" },
                { "data" : "Description (For partial shipment only)"},
                { "data" : "Voucher No"},
                { "data" : "V Creation Date"},
                { "data" : "ETA"},
                { "data" : "Actual Arrival at WH"}
            ],
            "sDom": 'rtip',
            "autoWidth": false,
            "paging": false,
            "bSort": false,
            "scrollX": true
        }, initTable());

        //alert(1);
        var table = $('#displayTable').DataTable();
        $('#exportBtn').empty();
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: ['excelHtml5']
        }).container().appendTo($('#exportBtn'));

    } else{
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url('api/buyer-wise-po-report?action=1&start='+start+"&end="+end+'&buyerList='+buyerList+'&supplier='+supplier).load();
    }
}


function initTable(){
    dtInit = true;
}