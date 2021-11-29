/*
 Author: Shohel Iqbal
 Copyright: 01.2016
 Code fridged on:
 */
var dtInit = false, dtInitSum = false, reportOn = '';

$(document).ready(function() {

    $("#dtpStart, #dtpEnd").change(function(e){
        refreshReport();
    });

    $('#byBankCharge, #byInsurancePremium').on('ifChecked', function(event){
        if(dtInit==true) {
            dtInit = false;
            $('#displayTable').dataTable().fnDestroy();
            $('#displayTable').empty();
        }
        reportOn = $('input:radio[name=summaryBy]:checked').val();
        //$("#sumColumnName").html(reportOn);
        refreshReport();
    });

    refreshReport();

});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshReport(){

    var action = reportOn,
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    // alert('api/aging-report?action=1&ci='+ci+"&supplier="+supplier+"&expiry="+expiry);
    if(!dtInit) {
        $.ajax({
            "url": 'api/vat-report?action='+action+'&start='+start+"&end="+end,
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
                        bScrollCollapse: true,
                        /*fixedColumns: {
                         leftColumns: 2
                         }*/
                    }, initTable());

                    var table = $('#displayTable').DataTable();
                    $('#exportBtn').empty();
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: ['excelHtml5']
                    }).container().appendTo($('#exportBtn'));

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
        dtable.api().ajax.url('api/vat-report?action='+action+'&start='+start+"&end="+end).load();
    }

}

function initTable(){
    dtInit = true;
}