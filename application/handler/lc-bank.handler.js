
var poid = $('#pono').val();
var podata, comments, attach,commentsTFO;
$(document).ready(function() {
    if (poid) {

        $.get('api/lc-bank?action=1&id=' + poid, function (data) {

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

                //alert(attach.length);
                attachmentLogScript(attach, '#usersAttachments');

            }
        });
    }

    $("#btnDraftLCShareToTFO").click(function (e) {
        // alert('clicked');
        $('#userAction1').val('1');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function () {
                $.ajax({
                    type: "POST",
                    url: "api/lc-bank",
                    data: $('#form-lc').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res);
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

    $("#btnFinalLCShareToTFO").click(function (e) {
        // alert('clicked');
        $('#userAction1').val('1');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function () {
                $.ajax({
                    type: "POST",
                    url: "api/lc-bank",
                    data: $('#form-lc').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res);
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

    //LC DRAFT/FINAL COPY FROM BANK

    $.get('api/lc-bank?action=2&id=' + poid, function (data) {

        if (!$.trim(data)) {
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {

            var row = JSON.parse(data);
            attach = row[0];

            attachmentLogScript(attach, '#lcAttachments');
            attachmentLogScript(attach, '#lcfeedbackAttachments');

        }
    });

    //LC FEEDBACK MESSAGE FROM BUYERS AND SUPPLIERS

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_FINAL_LC_REQUEST_SENT_TO_BANK, function (r) {
        if (r == 1) {
            $.get('api/lc-bank?action=3&id=' + poid, function (data) {

                if (!$.trim(data)) {
                    $(".panel-body").empty();
                    $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
                } else {

                    var row = JSON.parse(data);
                    comments = row[0];

                    if (comments != null) {

                        commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');
                    }
                }
            });
        }
    });

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_FEEDBACK_GIVEN_BY_BUYER, function (r) {
        if (r == 1) {
            $.get('api/lc-bank?action=4&id=' + poid, function (data) {
                if (!$.trim(data)) {
                    $(".panel-body").empty();
                    $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
                } else {

                    var row = JSON.parse(data);
                    commentsTFO = row[0];

                    if (commentsTFO != null) {

                        commentsTFOLogScript(commentsTFO, '#buyersmsgTFO', '#suppliersmsgTFO');
                    }
                }
            });
        }
    });

    //SHARE LC COPY TO BUYERS AND SUPPLIERS

    $("#btnLCShareBuyerSupplier").click(function (e) {
        $('#userAction1').val('2');
        e.preventDefault();
        alertify.confirm('Are you sure you want submit?', function () {
            $.ajax({
                type: "POST",
                url: "api/lc-bank",
                data: $('#form-lc').serialize(),
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

    });

    //SEND FEEDBACK BY BUYER TO TFO
    $("#btnLCFeedbackBuyer").click(function (e) {
        $('#userAction1').val('3');
        e.preventDefault();
        if (e) {
            if (validatemsg() === true) {
                alertify.confirm( 'Are you sure you want submit?', function (e) {
                $.ajax({
                    type: "POST",
                    url: "api/lc-bank",
                    data: $('#form-lc').serialize(),
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
            }
        } else {

        }
    });

    //SEND FEEDBACK BY SUPPLIER TO TFO
    $("#btnLCFeedbackSupplier").click(function (e) {
        $('#userAction1').val('4');
        e.preventDefault();
        if (e) {
            if (validatemsg() === true) {
                alertify.confirm( 'Are you sure you want submit?', function (e) {
                $.ajax({
                    type: "POST",
                    url: "api/lc-bank",
                    data: $('#form-lc').serialize(),
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

            }
        } else {

        }
    });

    //SEND FINAL LC TO BANK
    $("#btnFinalLCtoBank").click(function (e) {
        $('#userAction1').val('5');
        e.preventDefault();
        alertify.confirm('Are you sure you want submit?', function () {
            $.ajax({
                type: "POST",
                url: "api/lc-bank",
                data: $('#form-lc').serialize(),
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

    });
});

function ResetForm() {

    $('#form-lc')[0].reset();
}

function validate() {

    if ($("#lcno").val() == "") {
        $("#lcno").focus();
        alertify.error("LC no required");
        return false;
    }

    if ($("#lcissuedate").val() == "") {
        $("#lcissuedate").focus();
        alertify.error("LC issue date required");
        return false;
    }

    if ($("#lcexpirydate").val() == "") {
        $("#lcexpirydate").focus();
        alertify.error("LC expiry date required");
        return false;
    }

    if ($("#attachLC").val() == "") {
        $("#attachLC").focus();
        alertify.error("Attached LC Copy");
        return false;
    }

    if ($("#attachBRC").val() == "") {
        $("#attachBRC").focus();
        alertify.error("Attached bank received copy!");
        return false;
    }

    if ($("#attachBCA").val() == "") {
        $("#attachBCA").focus();
        alertify.error("Attached bank charge advise!");
        return false;
    }

    return true
}

function validatemsg() {

    if ($("#feedbackmessage").val() == "") {
        $("#feedbackmessage").focus();
        alertify.error("Give a Feedback");
        return false;
    }

    return true
}

$(function () {

    var button = $('#btnUploadLC'), interval;
    var txtbox = $('#attachLC');

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

    var button = $('#btnUploadBRC'), interval;
    var txtbox = $('#attachBRC');

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

    var button = $('#btnUploadBCA'), interval;
    var txtbox = $('#attachBCA');

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
