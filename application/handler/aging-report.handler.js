/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {

    $.getJSON("api/shipment?action=11&po=", function (list) {
        $("#ciList").select2({
            data: list,
            placeholder: "select CI number",
            allowClear: true,
            width: "100%"
        });
    });
    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "Select a Supplier",
            allowClear: true,
            width: "100%"
        });
    });
    
    $("#expiryStatus").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Expiry Status",
        allowClear: true,
        width: "100%"
    });
    
    /*$("#bank, #supplier, #expiryStatus").on("select2:select", function (e) {
        refreshNonSumReport();
    });*/
    
    $("#ciList, #supplier, #expiryStatus").change(function(e){
        refreshReport();
    });
    /*
    $('#isSummary').on('ifClicked', function(event){
        if($('#isSummary').parent().hasClass('checked')){
            showSum = 0;
            $("#nonSummaryFilter").removeClass("hidden").show();
            $("#summaryFilter").addClass("hidden").hide();

            $("#dtOutstandingSummary").addClass("hidden").hide();
            $("#dtOutstanding").removeClass("hidden").show();
            refreshNonSumReport();
        }else{
            showSum = 1;
            $("#summaryFilter").removeClass("hidden").show();
            $("#nonSummaryFilter").addClass("hidden").hide();
            
            $("#dtOutstandingSummary").removeClass("hidden").show();
            $("#dtOutstanding").addClass("hidden").hide();
            //refreshSumReport();
        }
        //refreshNonSumReport();
    });

    $('#summaryByBank, #summaryBySupplier').on('ifChecked', function(event){
        summaryBy = $('input:radio[name=summaryBy]:checked').val();
        $("#sumColumnName").html(summaryBy);
        refreshSumReport();
    });
    */
    refreshReport();
    
});

function refreshReport(){

    var action = $("#report").val(),
        ci = $("#ciList").val(),
        supplier = $("#supplier").val(),
        expiry = $("#expiryStatus").val();

    // alert('api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry);
    if(!dtInit) {
        $.ajax({
            "url": 'api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry,
            "success": function (json) {
                var tableHeaders;
                try {
                    $.each(json.columns, function (i, val) {
                        tableHeaders += "<th>" + val + "</th>";
                    });

                    $("#displayTable").empty();
                    $("#displayTable").append('<thead><tr>' + tableHeaders + '</tr></thead>');

                    $('#displayTable').dataTable({
                        data: json.data,
                        sDom: 'rtip',
                        paging: false,
                        bSort: false
                    }, initTable());
                } catch(err) {
                    // alert(err.message);
                    alertify.error("There might be no record to display.");
                    return;
                }
            },
            "dataType": "json"
        });
    } else{
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url('api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry).load();
    }

}

function initTable(){
    dtInit = true;
}