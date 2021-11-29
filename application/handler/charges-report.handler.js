/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

$(document).ready(function() {
    
    $("#btnLoadReport").click(function(e){
        e.preventDefault();

        var choise = $("#report").val();
        
        $("#dtReportView").html("");
        
        getReport(choise, "#dtReportView");
    });
    
    $("#reportName").select2({
        placeholder: "select report",
        width: "100%"
    });

    $("#reportName").change(function () {
        loadReport($("#reportName").val());
    });

    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#lcno").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: true,
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
    $.get("api/category?action=8&id=17", function (list) {
        $("#currency").html('<option value="" data-icon="">Select Currency</option>').append(list);
        $("#currency").selectpicker('refresh');
    });
});

$("#start, #end").datepicker({
    todayHighlight: true,
    autoclose: true
});

function loadReport(i){

}