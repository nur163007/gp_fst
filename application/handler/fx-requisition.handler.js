$(document).ready(function() {
    $(function() {
        $("#btnFxFormSubmit").click(function(e) {
            // alert('clicked');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm( 'Are you sure you want submit this request?', function (e) {

                    if (e) {
                        $.ajax({
                            type: "POST",
                            url: "api/fx-requisition",
                            data: $('#form-fx').serialize(),
                            cache: false,
                            success: function (response) {
                                console.log(response);
                                try {
                                    var res = JSON.parse(response);
                                    // console.log(res)
                                    if (res["status"] == 1) {
                                        ResetForm();
                                        alertify.success(res['message']);
                                        location.replace('feepayment-dashboard');
                                    } else {
                                        //$("#SendPO_btn").show();
                                        alertify.error(res['message']);
                                        return false;
                                    }
                                } catch (e) {
                                    console.log(e);
                                    alertify.error(response + ' Failed to process the request.');
                                    return false;
                                }
                            },
                            error: function (xhr, textStatus, error) {
                                alertify.error(textStatus + ": " + xhr.status + " " + error);
                            }
                        });
                    } else {

                    }
                });
            } else {
                return false;
            }
        });
    });

    //currency selected in fx value
    $("#currency").change(function(e) {
        $("#fxvalueCur").html($("#currency").find('option:selected').text());
    });

    // Supplier select
    $.getJSON("api/company?action=4", function(list) {
        //alert('sds')
        $("#supplier_id").select2({
            data: list,
            placeholder: "Select a supplier",
            allowClear: false,
            width: "100%"
        });
    });

    //Nature of service
    $.getJSON("api/category?action=4&id=94", function(list) {
        //alert('sds')
        $("#nature_of_service").select2({
            data: list,
            placeholder: "Select a nature of service",
            allowClear: false,
            width: "100%"
        });
    });

     // view fx request list
    $('#dtFx').dataTable({
        "ajax": "api/fx-requisition?action=1",
        "columns": [
            { "data": "id", "visible": false },
            { "data": "supplier_name" },
            { "data": "nature_of_service" },
            { "data": "currency" },
            { "data": "fx_value" },
            { "data": "value_date" },
            { "data": "created_by" },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    if (full["status"] == 0){
                        return '<p class="text-warning">Pending</p>';
                    }
                    else if (full["status"] == 1){
                        return '<p class="text-success">Accepted</p>';
                    }
                   }
            },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#fxForm" data-toggle="modal" data-toggle="Edit Fx" data-original-title="Edit Fx" onclick="EditFx(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
                        '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteFx(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
                }
            }
        ],
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });
});

/*      edit fx request     */

function EditFx(id) {
    //console.log('api/users?action=1&id='+id);
    $.get('api/fx-request?action=2&id=' + id, function(data) {
        if (!$.trim(data)) {

            $("#form_error").show();
            $("#form_error").html("No data found!");

        } else {

            var row = JSON.parse(data);
            //alert(row);
            $("#fxId").val(row["id"]);

            $('#supplier_id').val(row["supplier_id"]).change();
            $('#nature_of_service').val(row["nature_of_service"]).change();
            $('#currency').val(row["currency"]).change();
            $('#value').val(row["fx_value"]);
            $('#value_date').val(row["value_date"]).change();
            $('#remarks').val(row["remarks"]);
            $('#attachfx').val(row["attachment"]);
        }
    });
}

/*      delete fx request       */

function DeleteFx(id) {
    alertify.confirm('Are you sure you want to delete this fx request?', function(e) {
        if (e) {
            $.get('api/fx-request?action=3&id=' + id, function(data) {
                if (data == 1) {
                    alertify.success("Data deleted successfully!");
                    var dtable = $('#dtFx').dataTable();
                    dtable.api().ajax.reload();
                } else {
                    alertify.error("Delete Fail!");
                }
            });

        } else { // canceled
            //alertify.error(e);
        }
    });
}


//currency list
$.get("api/category?action=8&id=17", function(list) {
    $("#currency").html('<option value="" data-icon="" data-hidden="true"></option>').append(list);
    $("#currency").selectpicker('refresh');
    $("#currency").val(1);
    $("#currency").selectpicker('refresh');
    $("#fxvalueCur").html($("#currency").find('option:selected').text());
});

//date format
$("#value_date").datepicker({
    format: 'd-MM-yyyy',
    todayHighlight: true,
    autoclose: true
});

// default 5 days + for draft send date
function ResetForm() {
    $('#form-fx')[0].reset();

    var d = new Date();
    $('#value_date').datepicker('setDate', d);
    $('#value_date').datepicker('update');
    //getNewID();

    $('#req_type').val(113).change();
    $('#supplier_id').val('').change();
    $('#nature_of_service').val('').change();
    $('#currency').val('Select').change();
    $('#value').val('').change();
    //$('#remarks').val('').change();
    $('#attachfx').val('').change();
}

var d = new Date();
$('#value_date').datepicker('setDate', d);
$('#value_date').datepicker('update');

// form validation
function validate() {

    if ($("#supplier_id").val() == "") {
        $("#supplier_id").focus();
        alertify.error("Please select supplier!");
        return false;
    }
    if ($("#nature_of_service").val() == "") {
        $("#nature_of_service").focus();
        alertify.error("Please select nature of service!");
        return false;
    }
   /* if ($("#req_type").val() == "") {
        $("#req_type").focus();
        alertify.error("Please select requisition type!");
        return false;
    }*/
    if ($("#currency").val() == "") {
        $("#currency").focus();
        alertify.error("Select a Currency!");
        return false;
    }
    if ($("#value").val() == "") {
        $("#value").focus();
        alertify.error("FX value is required!");
        return false;
    } else {
        if (!Number($("#value").val().replace(/,/g, ""))) {
            $("#value").focus();
            alertify.error("Not a valid value!");
            return false;
        }
    }
    if ($("#value_date").val() == "") {
        $("#value_date").focus();
        alertify.error("Fill Up the FX Date field!");
        return false;
    }
    /*if ($("#remarks").val() == "") {
        $("#remarks").focus();
        alertify.error("Remarks is required!");
        return false;
    }*/

// Attachments
/*    if ($("#attachfx").val() == "") {
        $("#attachfx").focus();
        alertify.error("Attach FX Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachfx").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }*/
    return true;
}

$(function() {

    var button = $('#btnUploadFx'),
        interval;
    var txtbox = $('#attachfx');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function(file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function(file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function() {
                var text = txtbox.val();
                if (txtbox.val().length < 13) {
                    txtbox.val(txtbox.val() + '.');
                } else {
                    txtbox.val('Uploading');
                }
            }, 200);
        }
    });
});