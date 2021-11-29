/**
 * Created by Shohel Iqbal on 4/18/2017.
 */



var dtInit = false;

$(document).ready(function() {

    $.getJSON("api/lib-helper?req=2", function (list) {
        $("#buyerList").select2({
            data: list,
            placeholder: "select a Buyer",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/lib-helper?req=3", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a Supplier",
            allowClear: true,
            width: "100%"
        });
    });

    $("#currentStatus").select2({
        placeholder: "select a Status",
        allowClear: true,
        width: "100%"
    });

    $("#btnRefresh").click(function(e){
        refreshReport();
    });

    $("#btnClearFilter").click(function(e){
        $("#dtpStart1").val("");
        $("#dtpEnd1").val("");
        $("#dtpStart2").val("");
        $("#dtpEnd2").val("");
        refreshReport();
    });
    refreshReport();
});

function refreshReport(){

    var start1 = $("#dtpStart1").val(),
        end1 = $("#dtpEnd1").val(),
        start2 = $("#dtpStart2").val(),
        end2 = $("#dtpEnd2").val();

    if(!dtInit) {
        $.ajax({
            "url": "api/sourcing-report-ea-team-act?action=1&start1=" + start1 + "&end1=" + end1 + "&start2=" + start2 + "&end2=" + end2,
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
                        scrollY: '300',
                        bScrollCollapse: true,
                        fixedColumns: {
                            leftColumns: 2
                        }
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
        //alert('api/sourcing-report-po-database?action=1&start='+start+"&end="+end+'&buyer='+buyer+'&supplier='+supplier+'&currentStatus='+currentStatus);
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url("api/sourcing-report-ea-team-act?action=1&start1=" + start1 + "&end1=" + end1 + "&start2=" + start2 + "&end2=" + end2).load();
    }
}


function initTable(){
    dtInit = true;
}