/**
 * Created by aaqa on 2/5/2017.
 */


var dtInit1 = false, dtInit2 = false, dtInit3 = false, dtInit4 = false, dtInit5 = false;

$(document).ready(function() {

    $("#dtpStart, #dtpEnd").change(function(e){
        // refreshReport(1);
        // refreshReport(2);
        // refreshReport(3);
    });

    $("#btnRefresh").click(function (e) {
        $("#tablesContainer").removeClass('hidden');
        refreshReport(1);
        refreshReport(2);
        refreshReport(3);
        refreshReport(4);
        refreshReport(5);
    });

    // refreshReport(1);
    // refreshReport(2);
    // refreshReport(3);
    // refreshReport(4);
    // refreshReport(5);

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

/*var d1 = new Date()
var d2 = new Date(d1.getFullYear(), 0, 1);

$('#dtpEnd').datepicker('setDate', d1);
$('#dtpEnd').datepicker('update');
$('#dtpStart').datepicker('setDate', d2);
$('#dtpStart').datepicker('update');*/

function refreshReport(action){

    var start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    var dtStatus = false;

    if(action==1) {
        dtStatus = dtInit1;
    }
    if(action==2) {
        dtStatus = dtInit2;
    }
    if(action==3) {
        dtStatus = dtInit3;
    }
    if(action==4) {
        dtStatus = dtInit4;
    }
    if(action==5) {
        dtStatus = dtInit5;
    }

    if(!dtStatus) {
        //alert(1);
        $.ajax({
            "url": "api/fn-report-operational-update?action=" + action + "&start=" + start + "&end=" + end,
            "success": function (json) {
                try {
                    $('#displayTable' + action).dataTable({
                        data: json.data,
                        columns: json.columns,
                        sDom: 'rtp',
                        paging: false,
                        bSort: false
                    }, initTable(action));

                    var table = $('#displayTable' + action).DataTable();
                    $('#exportBtn' + action).empty();
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: ['excelHtml5']
                    }).container().appendTo($('#exportBtn' + action));

                } catch (err) {
                    alertify.error(err.message);
                    return;
                }
            },
            "dataType": "json"
        });
    } else{

        $.ajax({
            "url": "api/fn-report-operational-update?action=" + action + "&start=" + start + "&end=" + end,
            "success": function (json) {

                try {
                    var t= $('#displayTable' + action).DataTable();
                    t.destroy(true);
                    $('#c'+action).append('<table class="table table-bordered table-hover dataTable table-striped width-full small" id="displayTable'+action+'"></table>');

                    $('#displayTable' + action).dataTable({
                        bDestroy : true,
                        bProcessing : false,
                        aaData : json.data,
                        aoColumns : json.columns,
                        sDom : 'rtp',
                        paging : false,
                        bSort : false,
                    });

                    var table = $('#displayTable' + action).DataTable();
                    $('#exportBtn' + action).empty();
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: ['excelHtml5']
                    }).container().appendTo($('#exportBtn' + action));

                } catch (err) {
                    alertify.error(err.message);
                    return;
                }
            },
            "dataType": "json"
        });
    }

}
function initTable(action){
    if(action==1) {
        dtInit1 = true;
    }
    if(action==2) {
        dtInit2 = true;
    }
    if(action==3) {
        dtInit3 = true;
    }
    if(action==4) {
        dtInit4 = true;
    }
    if(action==5) {
        dtInit5 = true;
    }
}