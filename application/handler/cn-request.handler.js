// 
$(document).ready(function() {
    $(function () {
        $("#btnCnRequest").click(function (e) {
            // alert('clicked');
            e.preventDefault();
            if (validate() === true) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: "api/cn-request",
                        data: $('#form-cn-request').serialize(),
                        cache: false,
                        success: function (response) {
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                console.log(res)
                                if (res["status"] == 1) {
                                    ResetForm();
                                    alertify.success(res['message']);
                                    $('#CnRequestModal').modal('hide');
                                    var dtable = $('#dtAllCn').dataTable();
                                    dtable.api().ajax.reload();
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
                }else {

                }
            }else{
                return false;
            }
        });
    });

    //    view cn request list

    $('#dtAllCn').dataTable({
        "ajax": "api/cn-request?action=1",
        "columns": [
            {"data": "id", "visible": false},
            {"data": "po_no"},
            {"data": "cn_no"},
            {"data": "cn_date"},
            {"data": "pay_order_amount"},
            {"data": "pay_order_charge"},
            {"data": "created_by"},
            {
                "data": null, "sortable": false, "class": "text-center",
                "render": function (data, type, full) {
                    return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#CnRequestModal" data-toggle="modal" data-toggle="Edit Cn" data-original-title="Edit Cn" onclick="EditCn(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
                        '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteCn(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
                }
            }],
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });


});

//date format
$("#cn_date").datepicker({
    format: 'd-MM-yyyy',
    todayHighlight: true,
    autoclose: true
});
var d = new Date();
$('#cn_date').datepicker('setDate', d);
$('#cn_date').datepicker('update');

function validate() {
    if ($("#cn_number").val() == "") {
        $("#cn_number").focus();
        alertify.error("CN Number is required!");
        return false;
    }
    if ($("#cn_date").val() == "") {
        $("#cn_date").focus();
        alertify.error("CN Date is required!");
        return false;
    }
    if ($("#pay_order_amount").val() == "") {
        $("#pay_order_amount").focus();
        alertify.error("Pay Order Amount is required!");
        return false;
    }
    if ($("#pay_order_charge").val() == "") {
        $("#pay_order_charge").focus();
        alertify.error("Pay Order charge is required!");
        return false;
    }
    if ($("#attachcn").val() == "") {
        $("#attachcn").focus();
        alertify.error("Attach CN Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachcn").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachporc").val() == "") {
        $("#attachporc").focus();
        alertify.error("Attach PORC Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachporc").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }

    return true
}

function ResetForm() {

    $('#form-cn-request')[0].reset();
    $('#cn_number').val("")
    $('#pay_order_amount').val("")
    $('#pay_order_charge').val("")

    var d = new Date();
    $('#cn_date').datepicker('setDate', d);
    $('#cn_date').datepicker('update');

}

//edit cn request form
function EditCn(id) {
//console.log('api/users?action=1&id='+id);
    $.get('api/cn-request?action=2&id='+id, function (data) {
        if(!$.trim(data)){

            $("#form_error").show();
            $("#form_error").html("No data found!");

        } else{

            var row = JSON.parse(data);
            //alert(row);
            var cnData = row[0];
            var attachments = row[1];
            var cn = row[2];
            var porc = row[3];
            var cod = row[4];
            // console.log(attachments);
            $("#cnId").val(cnData["id"]);
            $('#cn_number').val(cnData["cn_no"]);
            $('#cn_date').val(cnData["cn_date"]);
            $('#pay_order_amount').val(cnData["pay_order_amount"]);
            $('#pay_order_charge').val(cnData["pay_order_charge"]);

            $('#attachcn').val(cn);
            $('#attachporc').val(porc);
            $('#attachother').val(cod);


           /* var filteredArray = attachments.map(function(attachment, index){
                attachment.index = index;
                return attachment;
            }).filter(function(attachment){
                return attachment.title === 'CN Copy';
            });

            console.log(filteredArray);*/
        }
    });
}

//delete cn request

function DeleteCn(id){
    alertify.confirm( 'Are you sure you want to delete this cn request?', function (e) {
        if(e){
            $.get('api/cn-request?action=3&id='+id, function (data) {
                if(data==1){
                    alertify.success("Data deleted successfully!");
                    var dtable = $('#dtAllCn').dataTable();
                    dtable.api().ajax.reload();
                } else{
                    alertify.error("Delete Fail!");
                }
            });

        } else { // canceled
            //alertify.error(e);
        }
    });
}

//attachment uploaded

$(function () {

    var button = $('#btnUploadCn'), interval;
    var txtbox = $('#attachcn');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
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


$(function () {

    var button = $('#btnUploadPorc'), interval;
    var txtbox = $('#attachporc');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
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


$(function () {

    var button = $('#btnUploadOther'), interval;
    var txtbox = $('#attachother');
    //var button_cancel = $('#attachother_cancel');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        autoSubmit: true,
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            //alert(ext);
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
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