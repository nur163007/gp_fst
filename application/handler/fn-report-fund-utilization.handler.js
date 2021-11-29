/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {

    $.getJSON("api/bankinsurance?action=4&type=bank", function(list) {
        $("#bank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: true,
            width: "100%"
        });
    });

    $("#bank, #dtpStart, #dtpEnd").change(function(e){
        refreshReport();
    });

    refreshReport();
    
});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

var d1 = new Date()
var d2 = new Date(d1.getFullYear(), 0, 1);

$('#dtpEnd').datepicker('setDate', d1);
$('#dtpEnd').datepicker('update');
$('#dtpStart').datepicker('setDate', d2);
$('#dtpStart').datepicker('update');

function refreshReport(){

    var action = $("#report").val(),
        bank = $("#bank").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(!dtInit) {
        // alert('api/fn-report-fund-utilization?action=1&bank=' + bank + '&start=' + start + "&end=" + end);
        $('#displayTable').DataTable({
            "ajax": 'api/fn-report-fund-utilization?action=1&lcbank=' + bank + '&start=' + start + "&end=" + end,
            "columns": [
                { "data" : "SL", "class":"text-center" },
                { "data" : "Bank" },
                { "data" : "NonFundedFacilityUSD", "class":"text-right" },
                { "data" : "NonFundedFacilityBDT", "class":"text-right" },
                { "data" : "CapacityUtilizedUSD", "class":"text-right" },
                { "data" : "CapacityUtilizedBDT", "class":"text-right" },
                { "data" : "SpaceAvailableUSD", "class":"text-right" },
                { "data" : "SpaceAvailableBDT", "class":"text-right" }
            ],
            "sDom": 'rtip',
            "autoWidth": false,
            "paging": false,
            "bSort": false
        }, initTable());

        //alert(1);
        var table = $('#displayTable').DataTable();
        $('#exportBtn').empty();
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: ['excelHtml5']
        }).container().appendTo($('#exportBtn'));
    } else{
        var dtable = $('#displayTable').dataTable();
        //alert('api/fn-report-fund-utilization?action=1&bank=' + bank + '&start=' + start + "&end=" + end);
        dtable.api().ajax.url('api/fn-report-fund-utilization?action=1&lcbank=' + bank + '&start=' + start + "&end=" + end).load();
    }

}

function initTable(){
    dtInit = true;
}