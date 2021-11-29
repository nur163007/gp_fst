/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {
    
    $("#dtpStart, #dtpEnd").change(function(e){
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
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    // alert('api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry);
    if(!dtInit) {
        $.ajax({
            "url": 'api/utilization-report?action=1&start='+start+"&end="+end,
            "success": function (json) {
                var tableHeaders;
                try {
                    $.each(json.columns, function (i, val) {
                        tableHeaders += '<th>' + val + '</th>';
                    });

                    $("#displayTable").empty();
                    $("#displayTable").append('<thead><tr>' + tableHeaders + '</tr></thead>');

                    $('#displayTable').dataTable({
                        data: json.data,
                        sDom: 'rtip',
                        paging: false,
                        bAutoWidth: false,
                        scrollX: true,
                        bSort: false
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
        dtable.api().ajax.url('api/utilization-report?action=1&start='+start+"&end="+end).load();
    }

}

function initTable(){
    dtInit = true;
}