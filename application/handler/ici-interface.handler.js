
var poid = $('#pono').val();
var podata, comments, attach;
$(document).ready(function() {
if(poid){

    $.get('api/ici-interface?action=1&id='+poid, function (data) {

        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            podata = row[0][0];
            attach = row[1];

            // PO info
            $('#ponum').html(poid);
            $('#insurancebank').html(podata['insurancebank']);
            $('#icvalue').html(commaSeperatedFormat(podata['povalue'])+' '+podata['curname']);
            $('#lcdesc').html(podata['lcdesc']);
            $('#supplier').html(podata['supname']);
            $('#pi_num').html(podata['pinum']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());

            //alert(attach.length);
            attachmentLogScript(attach, '#usersAttachments');

        }
    });
}

    $(function () {
        $("#btnCnRequest").click(function (e) {
            // alert('clicked');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm('Are you sure you want submit?', function () {
                    $.ajax({
                        type: "POST",
                        url: "api/ici-interface",
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
                                    window.location.href = _dashboardURL;
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

                    });

            }else{
                return false;
            }
        });
    });



});

function ResetForm() {

    $('#form-cn-request')[0].reset();
    $('#cn_number').val("")
    $('#pay_order_amount').val("")
    $('#pay_order_charge').val("")

    var d = new Date();
    $('#cn_date').datepicker('setDate', d);
    $('#cn_date').datepicker('update');

}

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