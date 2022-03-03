$(document).ready(function() {

    $('#tableFxSettlementPendingFn').dataTable({
        "ajax": "api/lc-bank?action=6",
        "columns": [
            {"data": "id", "visible": false},
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    return '<input type="checkbox" class="chkLine" id="chk_'+full["id"]+'" name="chkLine[]" value='+full["id"]+' >';
                }
            },
            {"data": "pono"},
            {"data": "lcno"},
            {"data": "shipment", "class": "text-center"},
            {"data": "docname"},
            {"data": "percentage", "class": "text-right"},
            {"data": "amount", "class": "text-right"},
            {"data": "ciamount", "class": "text-right"},
            {"data": "currency", "class": "text-center"},
            {"data": "lcbankaddress", "class": "text-right"},
            // {"data": "ciamount", "class": "text-right"}
           /* {
                "targets": -1,
                "data": null,
                "sortable": false,
                "defaultContent": ['<input type="text" class="form-control valueDtPicker" id="valueDate_'+full["id"]+'" data-plugin="datepicker" name="valueDate[]">']
            }*/
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    return '<input type="text" class="form-control valueDtPicker" id="valueDate_'+full["id"]+'" data-plugin="datepicker" name="valueDate[]">';
                }
            },
        ],
        "sorting": [[1, "asc"]],
        "sDom": 'frtipS',
        "paging": true,
        "pageLength": 10,
        "drawCallback": function() {
            $('.valueDtPicker').datepicker({
                todayHighlight: true,
                autoclose: true
            });
        }
    });

    $("#btnSendForFXSettlement").click(function () {
        // e.preventDefault();
        if(ValidateSubmit()) {
            alertify.confirm('Are you sure you want to change it?', function (e) {
                if (e) {
                    $("#btnSaveEditedDate").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-opening",
                        data: $("#formFxSettlementPendingFn").serialize() + "&userAction=10",
                        cache: false,
                        success: function (response) {
                            $("#btnSendForFXSettlement").prop('disabled', false);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    var tbl = $('#tableFxSettlementPendingFn').dataTable();
                                    tbl.api().ajax.reload();
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            } catch (err) {
                                alertify.error(err + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                } else { // canceled
                    //alertify.error(e);
                }
            });
        }
    });

});

function ValidateSubmit() {

    //alert(1212);
    var chekedCount = 0;
    $("#tableFxSettlementPendingFn tbody").find('tr').each(function () {
        // alert($(this).html());
        if($(this).find('input.chkLine').is(":checked")){
            chekedCount++;
        }
    });
    if(chekedCount==0){
        alertify.error("Please select some lines");
        return false;
    }
    $("#tableFxSettlementPendingFn tbody").find('tr').each(function () {
        // alert($(this).html());
        if($(this).find('input.chkLine').is(":checked")){
            if($(this).find("input.valueDtPicker").val()==""){
                alertify.error("Please select value Date");
                return false;
            }
        }
    });
    return true;

}