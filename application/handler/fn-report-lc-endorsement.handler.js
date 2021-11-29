/**
 * Created by aaqa on 1/30/2017.
 */
var dtInit = false, dtInitSum = false;
var summaryBy = '';
var showSum = 0;

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
        $("#fcy").select2({
            data: list,
            placeholder: "Select Currency",
            allowClear: false,
            width: "100%"
        });
    });

    $('#isSummary').on('ifClicked', function(event) {
        if ($('#isSummary').parent().hasClass('checked')) {
            showSum = 0;
        } else {
            showSum = 1;
        }
        toggleReportDisplay();
    });

    $('#summaryByBank, #summaryBySupplier').on('ifChecked', function(event){
        summaryBy = $('input:radio[name=summaryBy]:checked').val();
        //alert(summaryBy);
        //$("#sumColumnName").html(summaryBy);

    });

    $("#btnRefresh").click(function(e) {

        if(showSum==0){
            if ($("#bank").val() == "" &&
                $("#supplier").val() == "" &&
                $("#pono").val() == "" &&
                $("#lcno").val() == "" &&
                $("#fcy").val() == "" &&
                $("#dtpStart1").val() == "" &&
                $("#dtpEnd1").val() == "") {

                alertify.confirm("Without selecting any filter it may take a bit unusual time to display report due to large volume of data. Are you sure you want to proceed?", function (e) {
                    if (e) {
                        showReport();
                    }
                });

            } else{
                showReport();
            }
        } else {
            if ($("#dtpStart2").val() == "" &&
                $("#dtpEnd2").val() == "") {

                alertify.confirm("Without selecting any filter it may take a bit unusual time to display report due to large volume of data. Are you sure you want to proceed?", function (e) {
                    if (e) {
                        showReport();
                    }
                });

            } else{
                showReport();
            }
        }



    });

    $("#btnClearFilter").click(function(e) {
        $("#bank").val('').change();
        $("#supplier").val('').change();
        $("#pono").val('').change();
        $("#lcno").val('').change();
        $("#fcy").val('').change();
        $("#dtpStart1, #dtpEnd1, #dtpStart2, #dtpEnd2").val('');
    });

});

function showReport(){

    toggleReportDisplay();

    if (showSum == 1) {
        if (summaryBy == "bank") {
            refreshSumReport(2);
        } else if (summaryBy == "supplier") {
            refreshSumReport(3);
        }
    } else {
        refreshNonSumReport();
    }

}

function toggleReportDisplay(){

    if(showSum==0) {
        //alert("show non summary");
        $("#nonSummaryFilter").removeClass("hidden").show();
        $("#summaryFilter").addClass("hidden").hide();

        //if (dtInitSum) {
        $("#displayTableSum").addClass("hidden").hide();
        $("#displayTableSum_wrapper").addClass("hidden").hide();
        // }
        // if (dtInit) {
        $("#displayTable").removeClass("hidden").show();
        $("#displayTable_wrapper").removeClass("hidden").show();
        // }
    }

    if(showSum==1){
        //alert("show summary");
        $("#summaryFilter").removeClass("hidden").show();
        $("#nonSummaryFilter").addClass("hidden").hide();

        // if (dtInitSum) {
        $("#displayTable").addClass("hidden").hide();
        $("#displayTable_wrapper").addClass("hidden").hide();
        // }
        // if (dtInit) {
        $("#displayTableSum").removeClass("hidden").show();
        $("#displayTableSum_wrapper").removeClass("hidden").show();
        // }
    }

}

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshNonSumReport() {

    var bank = $("#bank").val(),
        supplier = $("#supplier").val(),
        pono = $("#pono").val(),
        lcno = $("#lcno").val(),
        cur = $("#fcy").val(),
        start = $("#dtpStart1").val(),
        end = $("#dtpEnd1").val();

    if(!dtInit) {
        //alert("api/fn-report-lc-endorsement?action=1&lcno=" + lcno + "&pono=" + pono + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur + "&start="+start+"&end="+end);
        $('#displayTable').DataTable({
            "ajax": "api/fn-report-lc-endorsement?action=1&lcno=" + lcno + "&pono=" + pono + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur + "&start="+start+"&end="+end,
            "columns": [
                { "data" : "SL" },
                { "data" : "EndorsementDate" },
                { "data" : "LCNo" },
                { "data" : "Bank" },
                { "data" : "CINo" },
                { "data" : "DocumentType" },
                { "data" : "GERPVoucherNo" },
                { "data" : "GERPVoucherDate" },
                { "data" : "PONo" },
                { "data" : "Supplier" },
                { "data" : "Description" },
                { "data" : "Currency" },
                { "data" : "LCValue", "class":"text-right" },
                { "data" : "EndorsedAmount", "class":"text-right" },
                { "data" : "EndorsedAmountUSD", "class":"text-right" },
                { "data" : "EndorsedAmountBDT", "class":"text-right" },
                { "data" : "ExRate" },
                { "data" : "Shipment/EndorsementNo", "class":"text-center" },
                { "data" : "DocRequestBySourcing" },
                { "data" : "DocDelivered" },
                { "data" : "QueryResolveDate" },
                { "data" : "GrossDay", "class":"text-center" },
                { "data" : "WeekendHoliday", "class":"text-center"},
                { "data" : "ActualDayRequired", "class":"text-center"}
            ],
            "sDom": '<"top"i>rt<"bottom"p><"clear">',
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
        dtable.api().ajax.url("api/fn-report-lc-endorsement?action=1&lcno=" + lcno + "&pono=" + pono + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur + "&start="+start+"&end="+end).load();
    }
}

function refreshSumReport(action) {

    // changing summary column name
    $("#sumColumnName").html(summaryBy);
    var start = $("#dtpStart2").val(),
        end = $("#dtpEnd2").val();

    if (!dtInitSum) {
        // alert("api/fn-report-lc-wise?action="+action);
        $('#displayTableSum').DataTable({

            "ajax": "api/fn-report-lc-endorsement?action=" + action + "&start=" + start + "&end=" + end,
            "columns": [
                { "data": "sumByColumn" },
                { "data": "Currency", "class":"text-center" },
                { "data": "lcCount", "class":"text-center" },
                { "data": "EndAmount", "class":"text-right" },
                { "data": "EndAmountInUSD", "class":"text-right" },
                { "data": "EndAmountInBDT", "class":"text-right" }
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                var curVal = function(str){
                    alert(str);
                    if(str!=""){
                        str = str.replace(/,/g,"");
                        return parseFloat(str);
                    }else{
                        return 0;
                    }
                };
                //alert(data);
                // Total over all pages
                totalLcCount = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                totalLcValue = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                totalLcValueUSD = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                totalLcValueBDT = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 2 ).footer() ).html(
                    totalLcCount
                );
                $( api.column( 3 ).footer() ).html(
                    commaSeperatedFormat(totalLcValue.toFixed(2))
                );
                $( api.column( 4 ).footer() ).html(
                    commaSeperatedFormat(totalLcValueUSD.toFixed(2))
                );
                $( api.column( 5 ).footer() ).html(
                    commaSeperatedFormat(totalLcValueBDT.toFixed(2))
                );
            },
            "sDom": '<"top"i>rt<"bottom"p><"clear">',
            "autoWidth": false,
            "paging": false,
            "bSort": false
        }, initTableSum());
        var table = $('#displayTableSum').DataTable();
        $('#exportBtn').empty();
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: ['excelHtml5']
        }).container().appendTo($('#exportBtn'));
    } else {
        var dtable = $('#displayTableSum').dataTable();
        dtable.api().ajax.url("api/fn-report-lc-endorsement?action=" + action + "&start=" + start + "&end=" + end).load();
    }
}

function initTable(){
    dtInit = true;
}
function initTableSum(){
    dtInitSum = true;
}