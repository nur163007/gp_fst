/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {

    $("#btnRefresh").click(function (e) {
        refreshReport();
    });

    //refreshReport();

    $('#isSummary').on('ifClicked', function(event){
        if($('#isSummary').parent().hasClass('checked')){
            showSum = 0;
            $("#displayTableSum").addClass("hidden").hide();
            $("#displayTable_wrapper").removeClass("hidden").show();
            // refreshNonSumReport();
        }else{
            showSum = 1;
            $("#displayTableSum").removeClass("hidden").show();
            $("#displayTable_wrapper").addClass("hidden").hide();
            //refreshSumReport();
        }
        //refreshNonSumReport();
    });
    
});

$("#dtpStart, #dtpEnd").datepicker({
    todayHighlight: true,
    autoclose: true
});

function refreshReport(){

    var action = $("#report").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val();

    if(showSum==0) {
        //alert('api/fn-report-insurance-premium?action=1&start='+start+"&end="+end);
        if (!dtInit) {
            $.ajax({
                "url": 'api/fn-report-insurance-premium?action=1&start=' + start + "&end=" + end,
                "success": function (json) {
                    try {
                        $('#displayTable').dataTable({
                            data: json.data,
                            columns: json.columns,
                            sDom: 'rtp',
                            paging: false,
                            bSort: false,
                            bAutoWidth: false,
                            scrollX: true
                        }, initTable());

                        var table = $('#displayTable').DataTable();
                        $('#exportBtn').empty();
                        var buttons = new $.fn.dataTable.Buttons(table, {
                            buttons: ['excelHtml5']
                        }).container().appendTo($('#exportBtn'));

                    } catch (err) {
                        // alert(err.message);
                        alertify.error("There might be no record to display.");
                        return;
                    }
                },
                "dataType": "json"
            });
        } else {
            var dtable = $('#displayTable').dataTable();
            // alert('api/insurance-premium-report?action=1&start='+start+"&end="+end);
            dtable.api().ajax.url('api/fn-report-insurance-premium?action=1&start=' + start + "&end=" + end).load();
        }
    }else{
        //alert('api/fn-report-insurance-premium?action=1&start='+start+"&end="+end);
        if (!dtInitSum) {
            $.ajax({
                "url": 'api/fn-report-insurance-premium?action=2&start=' + start + "&end=" + end,
                "success": function (json) {
                    try {
                        $('#displayTableSum').dataTable({
                            data: json.data,
                            columns: json.columns,
                            sDom: 'rtp',
                            paging: false,
                            bSort: false,
                            bAutoWidth: false,
                            scrollX: true
                        }, initTableSum());

                        var table = $('#displayTableSum').DataTable();
                        $('#exportBtn').empty();
                        var buttons = new $.fn.dataTable.Buttons(table, {
                            buttons: ['excelHtml5']
                        }).container().appendTo($('#exportBtn'));

                    } catch (err) {
                        // alert(err.message);
                        alertify.error("There might be no record to display.");
                        return;
                    }
                },
                "dataType": "json"
            });
        } else {
            var dtable = $('#displayTableSum').dataTable();
            // alert('api/insurance-premium-report?action=1&start='+start+"&end="+end);
            dtable.api().ajax.url('api/fn-report-insurance-premium?action=2&start=' + start + "&end=" + end).load();
        }
    }
}

function initTable(){
    dtInit = true;
}
function initTableSum(){
    dtInitSum = true;
}