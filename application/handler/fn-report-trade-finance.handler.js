/**
 * Created by aaqa on 2/4/2017.
 */

var dtInit = false;

$(document).ready(function() {

    $("#btnRefresh").click(function (e) {
        refreshReport();
    });

    //refreshReport();

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

function refreshReport(){

    var action = $("#report").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(!dtInit) {
        // alert("api/fn-report-trade-finance?action=1&start=" + start + "&end=" + end);
        $('#displayTable').DataTable({
            "ajax": "api/fn-report-trade-finance?action=1&start=" + start + "&end=" + end,
            "columns": [
                { "data" : "mn" },
                { "data" : "NumOfLCOpen", "class":"text-center" },
                { "data" : "LCValueInMn", "class":"text-right" },
                { "data" : "LCValueInMnBDT", "class":"text-right" },
                { "data" : "NumOfLCEnd", "class":"text-center" },
                { "data" : "EndValueInMn", "class":"text-right" },
                { "data" : "EndValueInMnBDT", "class":"text-right" },
                { "data" : "NumOfLCSett", "class":"text-center" },
                { "data" : "SettAmountInMn", "class":"text-right" },
                { "data" : "SettAmountInMnBDT", "class":"text-right" },
                { "data" : "NumOfCDPayment", "class":"text-center" },
                { "data" : "CDPaymentInMn", "class":"text-right" },
                { "data" : "CDPaymentInMnBDT", "class":"text-right" },
                { "data" : "NumOfACostCap", "class":"text-center" },
                { "data" : "ACostCapValueInMn", "class":"text-right" },
                { "data" : "ACostCapValueInMnBDT", "class":"text-right" }
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
        dtable.api().ajax.url("api/fn-report-trade-finance?action=1&start=" + start + "&end=" + end).load();
    }

}
function initTable(){
    dtInit = true;
}