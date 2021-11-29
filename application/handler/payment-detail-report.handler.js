/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {

    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#lcno").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: true,
            width: "100%"
        });
    });

    $("#lcno, #dtpStart, #dtpEnd").change(function(e){
        refreshReport();
    });

    refreshReport();
    
});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshReport(){

    var action = $("#report").val(),
        lcno = $("#lcno").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    // alert('api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry);
    if(!dtInit) {
        $.ajax({
            "url": 'api/payment-detail-report?action=1&lc='+lcno+'&start='+start+"&end="+end,
            "success": function (json) {
                var tableHeaders;
                try {
                    $.each(json.columns, function (i, val) {
                        tableHeaders += "<th>" + val + "</th>";
                    });

                    $("#displayTable").empty();
                    $("#displayTable").append('<thead><tr>' + tableHeaders + '</tr></thead>');

                    $('#displayTable').dataTable({
                        data: json.data,
                        sDom: 'rtip',
                        paging: false,
                        bSort: false,
                        bAutoWidth: false,
                        scrollX: true
                    }, initTable());
                } catch(err) {
                    // alert(err.message);
                    alertify.error("There might be no record to display.");
                    return;
                }
            },
            "dataType": "json"
        });
    } else{
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url('api/payment-detail-report?action=1&lc='+lcno+'&start='+start+"&end="+end).load();
    }

}

function initTable(){
    dtInit = true;
}