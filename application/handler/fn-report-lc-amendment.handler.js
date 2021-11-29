/**
 * Created by aaqa on 1/30/2017.
 */
var dtInit = false;
$(document).ready(function() {

    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#lcno").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: true,
            width: "100%"
        });
    });
    $.getJSON("api/purchaseorder?action=3", function (list) {
        $("#pono").select2({
            data: list,
            placeholder: "Search PO number",
            allowClear: false,
            width: "100%"
        });
    });
    $.getJSON("api/bankinsurance?action=4&type=bank", function(list) {
        $("#bank").select2({
            data: list,
            placeholder: "Select a Bank",
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

    $.getJSON("api/category?action=4&id=17", function (list) {
        $("#cBorneBy").select2({
            data: list,
            placeholder: "Select Currency",
            allowClear: false,
            width: "100%"
        });
    });

    $("#lcno, #bank, #supplier, #cBorneBy, #dtpStart, #dtpEnd").change(function(e){
        refreshReport();
    });

    refreshReport();

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshReport() {

    var action = $("#report").val(),
        bank = $("#bank").val(),
        supplier = $("#supplier").val(),
        pono = $("#pono").val(),
        lcno = $("#lcno").val(),
        cBorneBy = $("#cBorneBy").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(!dtInit) {
        // alert("api/fn-report-lc-wise?action=" + action + "&lcno=" + lcno + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur + "&start="+start+"&end="+end);
        $('#displayTable').DataTable({
            "ajax": "api/fn-report-lc-wise?action=3&lcno=" + lcno + "&pono=" + pono + "&bank=" + bank + "&supplier=" + supplier + "&chargeby=" + cBorneBy + "&start="+start+"&end="+end,
            "columns": [
                { "data" : "SL" },
                { "data" : "LCNo" },
                { "data" : "PONo" },
                { "data" : "AmendmentNo" },
                { "data" : "AmendmentDate" },
                { "data" : "Bank" },
                { "data" : "Supplier" },
                { "data" : "description" },
                { "data" : "FCY" },
                { "data" : "LCValue", "class":"text-right"  },
                { "data" : "LCValueinUSD", "class":"text-right"  },
                { "data" : "LCValueinBDT", "class":"text-right" },
                { "data" : "ExRate", "class":"text-right" },
                { "data" : "AmendmentCost", "class":"text-right" },
                { "data" : "chargeBorneBy" },
                { "data" : "SourcingApprovalDate" },
                { "data" : "TradeFinanceApprovalDate" },
                { "data" : "QueryResolveDate" },
                { "data" : "GrossDay", "class":"text-center" },
                { "data" : "WeekendHoliday", "class":"text-center"},
                { "data" : "ActualDayRequired", "class":"text-center"}
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
        dtable.api().ajax.url("api/fn-report-lc-wise?action=2&lcno=" + lcno + "&pono=" + pono + "&bank=" + bank + "&supplier=" + supplier + "&chargeby=" + cBorneBy + "&start="+start+"&end="+end).load();
    }
}

function initTable(){
    dtInit = true;
}