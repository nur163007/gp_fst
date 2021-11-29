/**
 * Created by Shohel on 3/19/2017.
 */


var dtInit = false;

$(document).ready(function() {

    $.getJSON("api/lib-helper?req=2", function (list) {
        $("#buyerList").select2({
            data: list,
            placeholder: "select a Buyer",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/lib-helper?req=3", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a Supplier",
            allowClear: true,
            width: "100%"
        });
    });

    $("#currentStatus").select2({
        placeholder: "select a Status",
        allowClear: true,
        width: "100%"
    });

    $("#buyerList, #supplier, #currentStatus").change(function(e){
        //
    });
    refreshReport();
    
    $("#applyFilter").click(function (e) {
        refreshReport();
        $("#navbar-collapse-2 ul li").removeClass('open');
        $("#navbar-collapse-2 ul li a").attr('aria-expanded', false);
    });

    $("#clearFilter").click(function (e) {
        $("#buyerList, #supplier, #currentStatus").val("").change();
        /*refreshReport();
        $("#navbar-collapse-2 ul li").removeClass('open');
        $("#navbar-collapse-2 ul li a").attr('aria-expanded', false);*/
    });
    
});

function refreshReport(){

    var buyer = $("#buyerList").val(),
        supplier = $("#supplier").val(),
        start = $("#dtpStart").val(),
        end = $("#dtpEnd").val(),
        currentStatus = $("#currentStatus").val();

    if(!dtInit) {
        //alert('api/sourcing-report-po-database?action=1&start=' + start + "&end=" + end);
        $.ajax({
            "url": 'api/sourcing-report-po-database?action=1&start=' + start + "&end=" + end,
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
                        scrollY: '300',
                        bScrollCollapse: true,
                        fixedColumns: {
                            leftColumns: 2
                        }
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

    } else{
        //alert('api/sourcing-report-po-database?action=1&start='+start+"&end="+end+'&buyer='+buyer+'&supplier='+supplier+'&currentStatus='+currentStatus);
        var dtable = $('#displayTable').dataTable();
        dtable.api().ajax.url('api/sourcing-report-po-database?action=1&start='+start+"&end="+end+'&buyer='+buyer+'&supplier='+supplier+'&currentStatus='+currentStatus).load();
    }
}


function initTable(){
    dtInit = true;
}