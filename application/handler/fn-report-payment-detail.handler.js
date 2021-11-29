/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {

    $.getJSON("api/purchaseorder?action=3", function (list) {
        $("#pono").select2({
            data: list,
            placeholder: "PO number",
            allowClear: false,
            width: "100%"
        });
    });

    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#lcno").select2({
            data: list,
            placeholder: "LC number",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/bankinsurance?action=4&type=bank", function(list) {
        $("#lcBank, #sourceBank").select2({
            data: list,
            placeholder: "Select Bank",
            allowClear: true,
            width: "100%",
        });
    });

    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "Select Supplier",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/category?action=4&id=17", function (list) {
        $("#currency").select2({
            data: list,
            placeholder: "Select Currency",
            allowClear: false,
            width: "100%"
        });
    });

    /*$("#pono, #lcno, #lcBank, #sourceBank, #currency, #supplier, #dtpStart, #dtpEnd").change(function(e){
        refreshReport();
    });*/

    //refreshReport();

    /*$(document).on({
        ajaxStop: function() {
            var table = $('#displayTable').DataTable();
            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: ['excelHtml5']
            }).container().appendTo($('#exportBtn'));
        }
    });*/

    $("#btnRefresh").click(function(e) {

        if ($("#pono").val() == "" &&
            $("#lcno").val() == "" &&
            $("#lcBank").val() == "" &&
            $("#sourceBank").val() == "" &&
            $("#supplier").val() == "" &&
            $("#currency").val() == "" &&
            $("#dtpStart").val() == "" &&
            $("#dtpEnd").val() == "") {

            alertify.confirm("Without selecting any filter it may take a bit unusual time to display report due to large volume of data. Are you sure you want to proceed?", function (e) {
                if (e) {
                    refreshReport();
                }
            });

        } else{
            refreshReport();
        }

    });

    $("#btnClearFilter").click(function(e) {
        $("#pono").val('').change();
        $("#lcno").val('').change();
        $("#lcBank").val('').change();
        $("#sourceBank").val('').change();
        $("#supplier").val('').change();
        $("#currency").val('').change();
        $("#dtpStart, #dtpEnd").val('');
    });

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshReport(){

    var pono = $("#pono").val(),
        lcno = $("#lcno").val(),
        lcbank = $("#lcBank").val(),
        sourcebank = $("#sourceBank").val(),
        supplier = $("#supplier").val(),
        currency = $("#currency").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    // alert('api/fn-report-payment-detail?action=1&start='+start+"&end="+end+'&po='+pono+'&lc='+lcno+'&lcbank='+lcbank+'&sourceBank='+sourcebank+'&supplier='+supplier+'&currency='+currency);
    if(!dtInit) {
        $('#displayTable').DataTable({

            "ajax": 'api/fn-report-payment-detail?action=1&start='+start+"&end="+end+'&po='+pono+'&lc='+lcno+'&lcbank='+lcbank+'&sourceBank='+sourcebank+'&supplier='+supplier+'&currency='+currency,
            "columns": [
                { "data" : "SL" },
                { "data" : "BankNotificationDate" },
                { "data" : "PaymentDate" },
                { "data" : "PONo" },
                { "data" : "LCNo" },
                { "data" : "LCissuingBank" },
                { "data" : "SourcingBank" },
                { "data" : "Supplier" },
                { "data" : "Currency" },
                { "data" : "ciNo" },
                { "data" : "GERPInvoice" },
                { "data" : "InvoiceValue", "class":"text-right" },
                { "data" : "PaymentAmount", "class":"text-right" },
                { "data" : "PaymentAmountUSD", "class":"text-right" },
                { "data" : "PaymentAmountBDT", "class":"text-right" },
                { "data" : "FXRate", "class":"text-right" },
                { "data" : "BasisOfPayment", "class":"text-center" },
                { "data" : "InvoicePortion", "class":"text-center" },
                { "data" : "BCSellingRate", "class":"text-right" },
                { "data" : "InvoiceBookingRate", "class":"text-right" },
                { "data" : "GrossSavingFxRate", "class":"text-right" },
                { "data" : "NetSavingFxRate", "class":"text-right" },
                { "data" : "GrossCostSavingsAgainstLCPaymentBDT", "class":"text-right" },
                { "data" : "NetCostSavingsAgainstLCPaymentBDT", "class":"text-right" },
                { "data" : "GrossDay", "class":"text-center" },
                { "data" : "WeekendHoliday", "class":"text-center" },
                { "data" : "ActualDayRequired", "class":"text-center" }
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                totalInvoiceValue = api.column( 10 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                totalPaymentAmount = api.column( 11 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                totalPaymentAmountUSD = api.column( 12 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                totalPaymentAmountBDT = api.column( 13 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                totalGrossCostsavingsagainstLCpaymentinBDT = api.column( 21 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                totalNetCostsavingsagainstLCpaymentinBDT = api.column( 22 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );

                $( api.column( 10 ).footer() ).html( commaSeperatedFormat(totalInvoiceValue.toFixed(2)) );
                $( api.column( 11 ).footer() ).html( commaSeperatedFormat(totalPaymentAmount.toFixed(2)) );
                $( api.column( 12 ).footer() ).html( commaSeperatedFormat(totalPaymentAmountUSD.toFixed(2)) );
                $( api.column( 13 ).footer() ).html( commaSeperatedFormat(totalPaymentAmountBDT.toFixed(2)) );
                $( api.column( 21 ).footer() ).html( commaSeperatedFormat(totalGrossCostsavingsagainstLCpaymentinBDT.toFixed(2)) );
                $( api.column( 22 ).footer() ).html( commaSeperatedFormat(totalNetCostsavingsagainstLCpaymentinBDT.toFixed(2)) );

            },
            "sDom": 'frtip',
            "autoWidth": false,
            "paging": false,
            "bSort": true,
        }, initTable());

        //alert(1);
        var table = $('#displayTable').DataTable();
        $('#exportBtn').empty();
        var curDate = new Date().toLocaleString().replace('/','-');
        var buttons = new $.fn.dataTable.Buttons(table, {
            //buttons: ['excelHtml5']
            buttons: [{
                extend: 'csvHtml5',
                title: 'FST_Payment_Detail_Report_'+curDate,
                text: 'Export to Excel'
            },{text: ' | '},{
                extend: 'copyHtml5',
                text: 'Copy to Clipboard'
            }]
        }).container().appendTo($('#exportBtn'));

    } else{
        var dtable = $('#displayTable').dataTable();
        // alert('api/fn-report-payment-detail?action=1&start='+start+"&end="+end+'&po='+pono+'&lc='+lcno+'&lcbank='+lcbank+'&sourceBank='+sourcebank+'&supplier='+supplier+'&currency='+currency);
        dtable.api().ajax.url('api/fn-report-payment-detail?action=1&start='+start+"&end="+end+'&po='+pono+'&lc='+lcno+'&lcbank='+lcbank+'&sourceBank='+sourcebank+'&supplier='+supplier+'&currency='+currency).load();
    }
}

function initTable(){
    dtInit = true;
}