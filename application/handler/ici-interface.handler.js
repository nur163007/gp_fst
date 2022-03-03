
var poid = $('#pono').val();
var podata, comments, attach;
$(document).ready(function() {
    if (poid) {

        $.get('api/ici-interface?action=1&id=' + poid, function (data) {

            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {

                var row = JSON.parse(data);
                podata = row[0][0];
                attach = row[1];

                // PO info
                $('#ponum').html(poid);
                $('#insurancebank').html(podata['insurancebank']);
                $('#icvalue').html(commaSeperatedFormat(podata['povalue']) + ' ' + podata['curname']);
                $('#lcdesc').html(podata['lcdesc']);
                $('#supplier').html(podata['supname']);
                $('#pi_num').html(podata['pinum']);
                $('#shipmode').html(podata['shipmode'].toUpperCase());

                $("#cn_number").val(podata['cn_no']);
                if(podata['cn_date']!=null){
                    $('#cn_date').datepicker('setDate', new Date(podata['cn_date']));
                    $('#cn_date').datepicker('update');
                }
                // $("#pay_order_amount").val(podata['pay_order_amount']);
                if(podata['pay_order_amount']!=null){
                    payOrderAmount = parseToCurrency(podata['pay_order_amount']);
                    $("#pay_order_amount").val(commaSeperatedFormat(payOrderAmount.toFixed(2)));
                }
                if (podata["attachCNCopy"] != null) {
                    $("#attachcnOld").val(podata["attachCNCopy"]);
                    $("#attachInsCoverNoteLink").html(attachmentLink(podata["attachCNCopy"]));
                    // $("#attachLCOpenRequest").val(lcinfo["attachLCORequest"]);
                }
                if (podata["attachPORC"] != null) {
                    $("#attachporcOld").val(podata["attachPORC"]);
                    $("#attachInsPORC").html(attachmentLink(podata["attachPORC"]));
                    // $("#attachLCOpenRequest").val(lcinfo["attachLCORequest"]);
                }
                if (podata["attachIOD"] != null) {
                    $("#attachotherOld").val(podata["attachIOD"]);
                    $("#attachInsIOD").html(attachmentLink(podata["attachIOD"]));
                    // $("#attachLCOpenRequest").val(lcinfo["attachLCORequest"]);
                }
                else {
                    $("#attachcnOld").val(podata["attachCNCopy"]);
                    $("#attachporcOld").val(podata["attachCNCopy"]);
                    $("#attachotherOld").val(podata["attachCNCopy"]);
                }
                //alert(attach.length);
                // attachmentLogScript(attach, '#usersAttachments');

            }
        });
    }

    $(function () {
        $("#btnSendCNToGP").click(function (e) {
            // alert('clicked');
            $('#userAction').val('1');

            e.preventDefault();
            if (validate() === true) {
                // alertify.confirm('Are you sure you want submit?', function () {
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
                                    // window.location.href = _dashboardURL;
                                    location.reload();
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

                // });

            } else {
                return false;
            }
        });
    });

    $(function () {
        $("#btnCloseCNRequest").click(function (e) {
            $("#userAction").val(2);
            // alert('clicked');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm('Are you sure you want submit to GP?', function () {
                    $.ajax({
                        type: "POST",
                        url: "api/ici-interface",
                        data: $('#form-cn-request').serialize(),
                        cache: false,
                        success: function (response) {
                            alert(response);
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

            } else {
                return false;
            }
        });
    });


});

function CancelForm(){
    window.location.href = _dashboardURL;
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
    if ($("#attachcn").val() == "" && $("#attachcnOld").val() == "") {
        $("#attachcn").focus();
        alertify.error("Attach CN Documents!");
        return false;
    } else {
        if($("#attachcn").val()!="") {
            if (!validAttachment($("#attachcn").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
        }
    }
    if ($("#userAction").val() == "2") {
        if ($("#attachporc").val() == "" && $("#attachporcOld").val() == "") {
            $("#attachporc").focus();
            alertify.error("Attach PORC Documents!");
            return false;
        } else {
            if($("#attachporc").val()!="") {
                if (!validAttachment($("#attachporc").val())) {
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
        }
    } else {
        if ($("#attachporc").val() != "") {
            if (!validAttachment($("#attachporc").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
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