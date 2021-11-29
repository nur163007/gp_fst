/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false;
$(document).ready(function() {
    
    /*$("#btnLoadReport").click(function(e){
        e.preventDefault();

        var choise = $("#report").val();
        
        $("#dtReportView").html("");
        
        getReport(choise, "#dtReportView");
    });*/
    
    $("#report").select2({
        placeholder: "select report",
        width: "100%"
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

    $("#report").change(function(e){
        if(dtInit==true) {
            dtInit = false;
            $('#displayTable').dataTable().fnDestroy();
            $('#displayTable').empty();
        }
        validateFilterElements();
        refreshLCOpening();
    });

    $("#lcno, #bank, #supplier, #currency, #dtpStart, #dtpEnd").change(function(e){
        refreshLCOpening();
    });

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function validateFilterElements(){

    if($("#report").val()==4 || $("#report").val()==5){
        $("#bank, #lcno").attr("disabled", true);
    } else{
        $("#bank, #lcno").removeAttr("disabled");
    }

}

function refreshLCOpening(){

    var action = $("#report").val(),
        bank = $("#bank").val(),
        supplier = $("#supplier").val(),
        lcno = $("#lcno").val(),
        cur = $("#currency").val();

    // alert('api/lc-wise-report?action=' + action + '&lcno=' + lcno + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur);
    if(!dtInit) {
        $.ajax({
            "url": 'api/lc-wise-report?action=' + action + '&lcno=' + lcno + "&bank=" + bank + "&supplier=" + supplier + "&cur=" + cur,
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
                        bSort: false,
                        bAutoWidth: false,
                        scrollX: true
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
        dtable.api().ajax.url('api/lc-wise-report?action='+action+'&lcno='+lcno+"&bank="+bank+"&supplier="+supplier+"&cur="+cur).load();
    }

}

function initTable(){
    dtInit = true;
}