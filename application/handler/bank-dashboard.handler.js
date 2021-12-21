$(document).ready(function() {
    //    view fx request list
    $('#dtMyInbox').dataTable({
        "ajax": "api/bank-dashboard?action=1",
        "columns": [
            { "data": "Id", "visible":false },
            { "data": "FxRequestId" },
            { "data": "FxValue" },
            { "data": "CurName" },
            { "data": "FxDate" },
            { "data": "CuttsOffTime" },
            // { "data": "FxRate" },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    if (full["FxRateStatus"] == 0){
                        return '<p class="text-danger margin-top-10"><i class="fas fa-times"></i></p>';
                    }
                    else if (full["FxRateStatus"] == 1){
                        return '<p class="text-success margin-top-10"><i class="fas fa-check"></i></p>';
                    }
                }
            },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    return '<div class="row">\n' +
                        '                                                    <div class="col-sm-12 text-center">\n' +
                        '                                                        <div class="form-group model-footer text-right">\n' +
                        '                                                          <button type="button" class="btn btn-sm btn-icon btn-flat btn-default" id="btnbankpopup" data-toggle="modal" data-target="#statusBankModal" onclick="getFxRowDetail(' + full['Id'] + ')"><i class="icon wb-edit" aria-hidden="true"></i></button>\n' +
                        '                                                        </div>\n' +
                        '                                                    </div>\n' +
                        '                                                    </div>';
                }
            }
        ],
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });
})

function getFxRowDetail(Id) {
    $.get('api/bank-dashboard?action=2&fxrFqRowId='+Id, function(data) {
        //console.log(data);
        var row = JSON.parse(data);
        //console.log(row);
        //alert(row["Id"]);
        $("#fxrFqRowId").val(row["Id"]);
        $('#fx_req_id').html(row["FxRequestId"]);
        $('#fx_value').html(row["FxValue"]);
        $('#fx_date').html(row["FxDate"]);
        $('#currency').html(row["CurName"]);
        $('#cuttsofftime').html(row["CuttsOffTime"]);
        $('#FxRate').val(row["FxRate"]);
        $('#OfferedVolumeAmount').val(row["OfferedVolumeAmount"]);
        $('#remarks').val(row["remarks"]);
    });
}

/*      Validation      */

$("#btnFxRfqRequest").click(function (e) {

    e.preventDefault();

    if($("#FxRate").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Bank Rate Cannot be Blank!");
        $("#FxRate").focus();
        return false;
    }
    if($("#OfferedVolumeAmount").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Bank Offered Amount Cannot be Blank!");
        $("#OfferedVolumeAmount").focus();
        return false;
    }
    if($("#remarks").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Remarks Cannot be Blank!");
        $("#remarks").focus();
        return false;
    }
    // alert($('#frmBankRate').serialize());

    /*     Submitt bank rate and Offer Amount    */
    alertify.confirm( 'Are you sure to submit this request?', function (e) {

        if(e) {
            $.ajax({
                type: "POST",
                url: "api/bank-dashboard",
                data: $('#frmBankRate').serialize(),
                cache: false,
                success: function (html) {
                    // alert(html);
                    var res = JSON.parse(html);
                    if (res['status'] == 1) {
                        // ResetForm();
                        $('#statusBankModal').modal('hide');
                        var dtable = $('#dtMyInbox').dataTable();
                        alertify.success("Saved SUCCESSFULLY!");
                        dtable.api().ajax.reload();
                        $("#fxrFqRowId").val("");
                        $("#FxRate").val("");
                        $("#OfferedVolumeAmount").val("");
                        $("#remarks").val("");

                        return true;
                    } else {
                        alertify.error("FAILED!");
                        return false;
                    }
                }
            });
        }
        else {

        }
    });


})

/*
$("#inlineRadio1").click(function () {
    var dtable = $('#dtMyInbox').dataTable();
    dtable.api().ajax.url("api/bank-dashboard?action=1").load();
})
$("#inlineRadio2").click(function () {
    var dtable = $('#dtMyInbox').dataTable();
    dtable.api().ajax.url("api/bank-dashboard?action=3").load();
})*/
