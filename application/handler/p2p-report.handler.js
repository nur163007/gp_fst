/**
 * Created by PhpStorm.
 * User: HasanMasud
 * Date: 2020-10-28
 */


var dtInit = false;

$(document).ready(function() {

    refreshReport();

    $("#applyFilter").click(function (e) {
        if (validate() === true) {
            refreshReport();
        } else {
            return false;
        }

    });

    $("#clearFilter").click(function (e) {
        $("#stage, #supplier, #poNo, #dtStart, #dtEnd").val("").change();
        refreshReport();
    });

    $.getJSON("api/purchaseorder?action=3", function (list) {
        $("#poNo").select2({
            data: list,
            placeholder: "Select a PO number",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/purchaseorder?action=10", function (list) {
        $("#stage").select2({
            data: list,
            placeholder: "Select a PO stage",
            allowClear: true,
            width: "100%"
        });
    });
    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a company",
            allowClear: true,
            width: "100%"
        });
    });

});

$("#dtStart, #dtEnd").datepicker({
    format: 'yyyy-m-d',
    todayHighlight: true,
    orientation: 'auto',
    autoclose: true
});

function refreshReport() {

    var supplier = $("#supplier").val(),
        poNo = $("#poNo").val(),
        dtStart = $("#dtStart").val(),
        dtEnd = $("#dtEnd").val(),
        stage = $("#stage").val(),
        url = `api/p2p-report?action=1&stage=${stage}&poNo=${poNo}&dtStart=${dtStart}&dtEnd=${dtEnd}&supplier=${supplier}`;

    if (!dtInit) {
        $.ajax({
            "url": url,
            "success": function (json) {
                if (json.data != 0) {
                    //clear the table
                    $('#displayTable').dataTable({
                        data: json.data,
                        columns: json.columns,
                        sDom: 'ifrtp',
                        paging: true,
                        bSort: true,
                        order: [[1, "desc"]],
                        //"ordering": false,
                        bAutoWidth: false,
                        scrollX: '100%',
                        scrollY: '300',
                        bScrollCollapse: true,
                        fixedColumns: {
                            leftColumns: 3
                        }
                    }, initTable());

                    var table = $('#displayTable').DataTable();
                    $('#exportBtn').empty();
                    var curDate = new Date().toLocaleString().replace('/','-');
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        //buttons: ['excelHtml5']
                        buttons: [{
                            extend: 'csv',
                            text: 'Excel',
                            title: 'FST P2P Report_'+curDate,
                            exportOptions: {
                                columns: ':visible',
                            }
                        }]
                    }).container().appendTo($('#exportBtn'));
                    //$body.removeClass("working_ajax");
                } else {
                    alertify.error('There might be no record to display. Try new filter.');
                }
            },
            "dataType": "json"
        });

    } else {
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url(url).load();
    }
}


function initTable(){
    dtInit = true;
}

/*VALIDATE FILTER
* ADDED BY: HASAN MASUD
* ADDED ON: 07-03-2019
************************/

function validate() {
    if($("#poNo").val()==="" && $("#stage").val()==="" && $("#dtStart").val()==="" && $("#dtEnd").val()==="" &&
        $("#supplier").val()==="" ){
        alertify.error('Use at least one filter.');
        $("#dtStart").focus();
        return false;
    }
    /*if(!$("#dtStart").val()){
        alertify.error('Please select start date');
        $("#dtStart").focus();
        return false;
    }

    if(!$("#dtEnd").val()){
        alertify.error('Please select end date');
        $("#dtEnd").focus();
        return false;
    }*/
    return true;
}