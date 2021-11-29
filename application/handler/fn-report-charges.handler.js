/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false;

$(document).ready(function() {
    
    $("#reportName").select2({
        placeholder: "select report",
        width: "100%"
    });

    $("#btnRefresh").click(function(e) {

        if ($("#reportName").val() != "") {

            if ($("#dtpStart").val() == "" &&
                $("#dtpEnd").val() == "") {

                alertify.confirm("Without selecting any filter it may take a bit unusual time to display report due to large volume of data. Are you sure you want to proceed?", function (e) {
                    if (e) {
                        showReport();
                    }
                });

            } else {
                showReport();
            }
        } else {
            alertify.error("Please select a report.");
        }

        if($("#reportName").val()!="") {
            $("#reportName").attr("disabled", true);
        }
    });

    $("#btnClearFilter").click(function(e) {
        $("#reportName").val("").change();
        $("#dtpStart, #dtpEnd").val('');
        location.reload();
    });

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function showReport() {

    var action = $("#reportName").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if (!dtInit) {
        $.ajax({
            "url": "api/fn-report-charges?action=" + action + "&start=" + start + "&end=" + end,
            "success": function (json) {
                try {
                    $('#dtReportView').dataTable({
                        data: json.data,
                        columns: json.columns,
                        sDom: 'ifrtp',
                        paging: false,
                        bSort: false,
                        bAutoWidth: false,
                        scrollX: '100%',
                        bScrollCollapse: true
                        /*fixedColumns: {
                            leftColumns: 2
                        }*/
                    }, initTable());

                    var table = $('#dtReportView').DataTable();
                    $('#exportBtn').empty();
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: ['excelHtml5']
                    }).container().appendTo($('#exportBtn'));

                } catch (err) {
                    // alert(err.message);
                    alertify.error("There might be no record to display.");
                    return;
                }
            },
            "dataType": "json"
        });

    } else {
        //alert('api/sourcing-report-po-database?action=1&start='+start+"&end="+end+'&buyer='+buyer+'&supplier='+supplier+'&currentStatus='+currentStatus);
        var dtable = $('#dtReportView').dataTable();
        dtable.api().ajax.url("api/fn-report-charges?action=" + action + "&start=" + start + "&end=" + end).load();
    }
}


function initTable(){
    dtInit = true;
}