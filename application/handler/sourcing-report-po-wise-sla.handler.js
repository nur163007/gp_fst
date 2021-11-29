/**
 * Created by User on 9/10/2017.
 */

var dtInit = false;

$(document).ready(function() {

    $("#btnRefresh").click(function(e){
        if ($("#dtpStart").val() == "" &&
            $("#dtpEnd").val() == "") {

            alertify.error("please select date range");

        } else{
            refreshReport();
        }
    });

    $("#btnClearFilter").click(function(e){
        $("#dtpStart").val("");
        $("#dtpEnd").val("");
        //refreshReport();
    });
});

function refreshReport(){

    var start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(!dtInit) {
        $.ajax({
            "url": "api/sourcing-report-po-wise-sla?action=1&start=" + start + "&end=" + end,
            "success": function (json) {
                try {
                    $('#displayTable').dataTable({
                        data: json.data,
                        columns: json.columns,
                        sDom: 'ifrtp',
                        paging: false,
                        bSort: false,
                        bAutoWidth: false,
                        scrollX: '100%',
                        scrollY: '500',
                        bScrollCollapse: true
                    }, initTable());

                    var table = $('#displayTable').DataTable();
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

    } else{
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url("api/sourcing-report-po-wise-sla?action=1&start=" + start + "&end=" + end).load();
    }
}


function initTable(){
    dtInit = true;
}