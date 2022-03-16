/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

var poid = $('#pono').val();
var shipno = $('#shipno1').val();
var u = $('#usertype').val();

var podata,comments,attach,lcinfo,pterms;

$(document).ready(function() {

    $('#shipmodesea, #shipmodeair').on('ifChecked', function (event) {
        var shipmode_check = $('input:radio[name=shipmode]:checked').val();
        if (shipmode_check == 'sea') {
            $("#mawbNo").attr("readonly", true);
            $("#hawbNo").attr("readonly", true);
            $("#blNo").removeAttr("readonly");
        } else if (shipmode_check == 'air') {
            $("#blNo").attr("readonly", true);
            $("#mawbNo").removeAttr("readonly");
            $("#hawbNo").removeAttr("readonly");
        }
    });

    // Loading pre data from PO
    //alert('api/purchaseorder?action=2&id='+poid+'&shipno='+shipno);
    $.get('api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno, function (data) {
        if (!$.trim(data)) {
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {

            var row = JSON.parse(data);

            podata = row[0][0];
            comments = row[1];
            attach = row[2];
            lcinfo = row[3][0];
            pterms = row[4];

            /* alert(podata['poid']);*/

            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#supplier').html(podata['supname']);
            $('#contractref').html(podata['contractrefName']);
            $('#deliverydate').html(podata['deliverydate']);
            if (podata["installbysupplier"] == 0) {
                $('#installbysupplier').html('No');
            } else {
                $('#installbysupplier').html('Yes');
            }
            $('#noflcissue').html(podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']);

            // PI info
            $('#pinum').html(podata['pinum']);

            $("#piamount").val(podata['pivalue']);
            $('#pivalue').html(commaSeperatedFormat(podata['pivalue']) + ' ' + podata['curname']);

            $('#shipmode' + podata['shipmode']).attr('checked', '').parent().addClass('checked');

            if (podata['shipmode'] == "sea") {
                $("#mawbNo").attr("readonly", true);
                $("#hawbNo").attr("readonly", true);
            } else if (podata['shipmode'] == "air") {
                $("#blNo").attr("readonly", true);
            }

            $('#hscode').html(podata['hscode']);

            $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' + podata['curname']);

            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);

            // LC Info

            $("#hideShipmentType").val(podata['withLC']);
            $("#lcno").val(lcinfo['lcno']);
            $("#lcvalue").val(lcinfo['lcvalue']);

            if (podata['shipmode']=='E-Delivery') {
                if (podata['withLC']== 1){
                    $('#lcdesc').html('N/A');
                    $('#shipmode').html(podata['shipmode'].toUpperCase()+' '+'(Without LC)');
                    $('#withoutlc').show();
                    $('#withlc').hide();
                }
                else if (podata['withLC']== 0){
                    if (lcinfo['lcdesc'] == "") {
                        $('#lcdesc').html(podata['lcdesc']);
                    } else {
                        $('#lcdesc').html(lcinfo['lcdesc']);
                    }
                    $('#shipmode').html(podata['shipmode'].toUpperCase()+' '+'(With LC)');
                    $('#withoutlc').hide();
                    $('#withlc').show();
                }

            }else{
                if (lcinfo['lcdesc'] == "") {
                    $('#lcdesc').html(podata['lcdesc']);
                } else {
                    $('#lcdesc').html(lcinfo['lcdesc']);
                }
                $('#shipmode').html(podata['shipmode'].toUpperCase());
                $('#withoutlc').hide();
                $('#withlc').hide();
            }
            if (lcinfo['addconfirmation'] == 1) {
                $('#addconfirmation').removeClass('fa-square-o').addClass('fa-check-square-o');
                if (lcinfo['confchargeatapp'] == 1) {
                    $('#confChargeBearer').html(' charge beared by <b>Applicant</b>');
                } else {
                    $('#confChargeBearer').html(' charge beared by <b>Beneficiary</b>');
                    $("#addChargeButton").hide();
                }
            }
            //alert(6);
            //alert('api/shipment?action=7&po=' + poid + '&shipno=' + $("#shipNo").val());
            $.get('api/shipment?action=7&po=' + poid + '&shipno=' + $("#shipNo").val(), function (res) {

                if ($.trim(res) != 1) {
                    $("#dhlTrackNo, #dhlTrackNoUpdate_btn").attr('disabled', true);
                } else {
                    $.get('api/shipment?action=1&po=' + poid + '&shipno=' + $("#shipNo").val(), function (data) {

                        var shipData = JSON.parse(data);

                        // $("#scheduleETA").val(shipData["scheduleETA"]);
                        $("#scheduleETA").datepicker('setDate', new Date(shipData["scheduleETA"]));
                        $("#scheduleETA").datepicker('update');
                        if (shipData["scheduleETD"] != null) {
                            $("#scheduleETD").datepicker('setDate', new Date(shipData["scheduleETD"]));
                            $("#scheduleETD").datepicker('update');
                        }
                        $("#mawbNo").val(shipData["mawbNo"]);
                        $("#hawbNo").val(shipData["hawbNo"]);
                        $("#blNo").val(shipData["blNo"]);
                        $("#awbOrBlDate").datepicker('setDate', new Date(shipData["awbOrBlDate"]));
                        $("#awbOrBlDate").datepicker('update');
                        $("#ciNo").val(shipData["ciNo"]);
                        $("#ciDate").datepicker('setDate', new Date(shipData["ciDate"]));
                        $("#ciDate").datepicker('update');
                        $("#ciAmount").val(commaSeperatedFormat(shipData["ciAmount"]));
                        $("#invoiceQty").val(shipData["invoiceQty"]);
                        $("#noOfcontainer").val(shipData["noOfcontainer"]);
                        $("#noOfBoxes").val(shipData["noOfBoxes"]);
                        $("#ChargeableWeight").val(shipData["ChargeableWeight"]);

                        // AWB/BL Scan Copy
                        if (shipData["attachAwbOrBlScanCopy"] != null) {
                            $("#attachAwbOrBlScanCopyOld").val(shipData["attachAwbOrBlScanCopy"]);
                            $("#attachAwbOrBlScanCopyLink").html(attachmentLink(shipData['attachAwbOrBlScanCopy']));
                        }
                        // CI Scan Copy
                        if (shipData["attachCiScanCopy"] != null) {
                            $("#attachCiScanCopyOld").val(shipData["attachCiScanCopy"]);
                            $("#attachCiScanCopyLink").html(attachmentLink(shipData['attachCiScanCopy']));
                        }
                        // Packing List Scan Copy
                        if (shipData["attachPackListScanCopy"] != null) {
                            $("#attachPackListScanCopyOld").val(shipData["attachPackListScanCopy"]);
                            $("#attachPackListScanCopyLink").html(attachmentLink(shipData['attachPackListScanCopy']));
                        }
                        // Certificate of Origine Scan Copy
                        if (shipData["attachAwbOrBlScanCopy"] != null) {
                            $("#attachOriginCertificateOld").val(shipData["attachOriginCertificate"]);
                            $("#attachOriginCertificateLink").html(attachmentLink(shipData['attachOriginCertificate']));
                        }
                        // Freight Certificate
                        if (shipData["attachFreightCertificate"] != null) {
                            $("#attachFreightCertificateOld").val(shipData["attachFreightCertificate"]);
                            $("#attachFreightCertificateLink").html(attachmentLink(shipData['attachFreightCertificate']));
                        }
                        // Shipment Other Docs
                        if (shipData["attachShipmentOther"] != null) {
                            $("#attachShipmentOtherOld").val(shipData["attachShipmentOther"]);
                            $("#attachShipmentOtherLink").html(attachmentLink(shipData['attachShipmentOther']));
                        }

                        if ($("#lastAction").val() != ACTION_SHIP_DOC_REJECTED_EATEAM && $("#lastAction").val() != ACTION_SHIPMENT_DOCUMENT_REJECTED) {
                            $("#shipmentInputesRow input, #SendShipDoctoGp_btn").attr('disabled', true);
                            $("#shipDocAttachmentsRow").addClass('hidden');
                        } else {
                            $("#dhlTrackNo, #dhlTrackNoUpdate_btn").attr('disabled', true);
                        }
                    });
                }
            });

            // Attachments
            var attachFilter = ["Bank Charge Advice", "Pay Order Issue Charge", "Insurance Cover Note", "Pay Order Receive Copy", "Amendment Advice Note"];
            attachmentLogScript(attach, '#usersAttachments', 1, attachFilter, -1);

            $.get('api/shipment?action=9&po=' + poid, function (res) {
                if ($.trim(res)) {
                    $("#totalShipValue").val(res);
                    $("#previousTotalCI").html('Previous invoiced value: ' + commaSeperatedFormat(res));
                }
            });
        }
    });

    // $("#ciAmount").blur(function (e) {
    //     validateCIAmount();
    // });

    $("#SendShipDoctoGp_btn").click(function (e) {

        // $('#userAction').val('1');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to submit shipping docs?', function (e) {
                if (e) {
                    $("#SendShipDoctoGp_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/shipment",
                        data: $('#shipment-schedule-form').serialize() + "&userAction=1",
                        cache: false,
                        success: function (response) {
                            $("#SendShipDoctoGp_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to send!");
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
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });

    $("#dhlTrackNoUpdate_btn").click(function (e) {
        /*alert('abcd');*/
        e.preventDefault();
        if (validateDHL()) {
            alertify.confirm('Are you sure you want to submit DHL Tracking number?', function (e) {
                if (e) {
                    $("#dhlTrackNoUpdate_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/shipment",
                        data: "pono=" + $("#pono").val() + "&shipNo=" + $("#shipNo").val() + "&refId=" + $("#refId").val() + "&dhlTrackNo=" + $("#dhlTrackNo").val() + "&userAction=2",
                        cache: false,
                        success: function (response) {
                            $("#dhlTrackNoUpdate_btn").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.alert(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to send!");
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
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });

    if(poid!="") {

        LoadPOLines();
        // }
        /**
         * ---------------------------------------------------------------------
         * Control Events Binding END
         * ---------------------------------------------------------------------
         */
        $(document).on('keyup', '.unitPrice, .poQty', function () {
            poLineTotal(this);
            poGrandTotal();
        });

        $(document).on('keyup blur', '.delivQty', function (e) {
            //alert(1);
            poLineDelivTotal(this);
            poGrandTotal();
            // calculateInvoiceAmount($("#poNo").val());
        });

        /*************************************************************************
         * One click action
         *************************************************************************/
        $('#chkAllLine').click(function (e) {
            if (this.checked) {
                $('.chkLine').prop('checked', true);
            } else {
                $('.chkLine').prop('checked', false);
            }

            $("#dtPOLines tbody").find('tr').each(function () {

                if ($(this).find('input.chkLine').is(':checked')) {
                    $(this).find('input.delivQty').val($(this).find('input.delivQtyValid').val());
                    $(this).find('input.delivTotal').val($(this).find('input.delivAmountValid').val());
                } else {
                    $(this).find('input.delivQty').val(0);
                    $(this).find('input.delivTotal').val(0);
                }
            });
            poGrandTotal();
            // calculateInvoiceAmount($("#poNo").val());
        });

        $(document).on('click', '.chkLine', function () {
            if (!this.checked) {
                $('#chkAllLine').prop('checked', false);
                $(this).parent().parent().parent().find('input.delivQty').val(0);
            } else {
                var d = $(this).parent().parent().parent().find('input.delivQtyValid').val();
                $(this).parent().parent().parent().find('input.delivQty').val(d);
            }
            poLineWisePoTotal();
            poGrandTotal();

            $('#reg_fee').val("");
        });

        $('#reject-message').hide();

    }

});



$('#scheduleETA,#scheduleETD, #awbOrBlDate, #ciDate')
    .datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
    })

/*function validateCIAmount() {
    $("#ciAmount").val(commaSeperatedFormat($("#ciAmount").val()));
    //$("#basevalue").val(commaSeperatedFormat($("#basevalue").val()));
    var piv = parseToCurrency($("#piamount").val());
    var civ = parseToCurrency($("#ciAmount").val());
    var shv = parseToCurrency($("#totalShipValue").val());    // total Shipment CI value

    var allowedValue = piv - shv;
    //alert(allowedValue);
    //alert('PI Value: ' + piv + ' CI value: ' + civ.toFixed(2) + ' Ship Value: ' + shv + ' Valid value: ' + allowedValue.toFixed(2));
    //alert("CI : " + parseFloat(civ.toFixed(2)) + " Allowed : " + parseFloat(allowedValue.toFixed(2)));

    // if (civ.toFixed(2) > allowedValue.toFixed(2)) {
    if ( parseFloat(civ.toFixed(2)) > parseFloat(allowedValue.toFixed(2)) ) {
        //alert("how come?");
        $("#ciAmount").closest("div.form-group").addClass('has-error');
        $("#ciAmountError").val("1");
    } else {
        $("#ciAmount").closest("div.form-group").removeClass('has-error');
        $("#ciAmountError").val("0");
    }
}*/

function validateDHL(){
    if($("#dhlTrackNo").val()==""){
        $("#dhlTrackNo").focus();
        alertify.error("DHL tracking number is required field");
        return false;
    }
    return true;
}

function validate() {

    var shipmode_check = $('input:radio[name=shipmode]:checked').val();

    if (shipmode_check == undefined) {
        alertify.error("Please select a Shipment Mode!");
        $("#shipmodesea").focus();
        return false;
    }

    if ($("#scheduleETA").val() == "") {
        $("#scheduleETA").focus();
        alertify.error("Shipment Schedule is required!");
        return false;
    }

    if ($("#scheduleETD").val() == "") {
        $("#scheduleETD").focus();
        alertify.error("Shipment Schedule is required!");
        return false;
    }

    if (shipmode_check == "sea") {
        if ($("#blNo").val() == "") {
            $("#blNo").focus();
            alertify.error("BL Number is required!");
            return false;
        }
    } else if (shipmode_check == "air") {
        // if($("#mawbNo").val()=="" && $("#hawbNo").val()=="")
        if ($("#mawbNo").val() == "") {
            $("#mawbNo").focus();
            alertify.error("MAWB Number is required!");
            return false;
        } else {
            if (!/([a-zA-Z0-9-]{4,10}|[a-zA-Z0-9]{4,15})+/i.test($("#mawbNo").val())) {
                alertify.error("Invalid MAWB Number! It should be 4 to 10 character");
                return false;
            }
        }
    }

    if ($("#awbOrBlDate").val() == "") {
        $("#awbOrBlDate").focus();
        alertify.error("AWB/BL Date is required!");
        return false;
    }
    if ($("#ciNo").val() == "") {
        $("#ciNo").focus();
        alertify.error("CI Number is required!");
        return false;
    }
    if ($("#ciDate").val() == "") {
        $("#ciDate").focus();
        alertify.error("Fill Up the CI Date field!");
        return false;
    }
    if ($("#ciAmount").val() == "") {
        $("#ciAmount").focus();
        alertify.error("Write CI amount!");
        return false;
    } else {
        if ($("#ciAmountError").val() == 1) {
            $("#ciAmount").focus();
            alertify.error("Invalid CI amouunt!!");
            return false;
        }

    }
    if ($("#invoiceQty").val() == "") {
        $("#invoiceQty").focus();
        alertify.error("Write Invoice Quantity!");
        return false;
    }
    if ($("#noOfcontainer").val() == "") {
        $("#noOfcontainer").focus();
        alertify.error("Number of container is required!");
        return false;
    }
    if ($("#noOfBoxes").val() == "") {
        $("#noOfBoxes").focus();
        alertify.error("Number of boxes is required!");
        return false;
    }
    if ($("#ChargeableWeight").val() == "") {
        $("#ChargeableWeight").focus();
        alertify.error("Chargeable Weight is required!");
        return false;
    }

    if(shipmode_check != "E-Delivery") {
        if ($("#attachAwbOrBlScanCopy").val() == "" && $("#attachAwbOrBlScanCopyOld").val() == "") {
            $("#attachAwbOrBlScanCopy").focus();
            alertify.error("Attach AWB/BL!");
            return false;
        } else {
            if ($("#attachAwbOrBlScanCopy").val() != "") {
                if (!validAttachment($("#attachAwbOrBlScanCopy").val())) {
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
        }
    }
    if ($("#attachCiScanCopy").val() == "" && $("#attachCiScanCopyOld").val() == "") {
        $("#attachCiScanCopy").focus();
        alertify.error("Attach CI!");
        return false;
    } else {
        if ($("#attachCiScanCopy").val() != "") {
            if (!validAttachment($("#attachCiScanCopy").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
        }
    }
    if(shipmode_check != "E-Delivery") {
        if ($("#attachPackListScanCopy").val() == "" && $("#attachPackListScanCopyOld").val() == "") {
            $("#attachPackListScanCopy").focus();
            alertify.error("Attach Packing List!");
            return false;
        } else {
            if ($("#attachPackListScanCopy").val() != "") {
                if (!validAttachment($("#attachPackListScanCopy").val())) {
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
        }

        if ($("#attachOriginCertificate").val() == "" && $("#attachOriginCertificateOld").val() == "") {
            $("#attachOriginCertificate").focus();
            alertify.error("Attach Origine Certificate!");
            return false;
        } else {
            if ($("#attachOriginCertificate").val() != "") {
                if (!validAttachment($("#attachOriginCertificate").val())) {
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
        }
        if ($("#attachFreightCertificate").val() == "" && $("#attachFreightCertificateOld").val() == "") {
            $("#attachFreightCertificate").focus();
            alertify.error("Attach Freight Certificate!");
            return false;
        } else {
            if ($("#attachFreightCertificate").val() != "") {
                if (!validAttachment($("#attachFreightCertificate").val())) {
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
        }
    }
    poLineVerify();

    if (!poLinesOkay) {
        return false;
    }
    return true;
}

function ResetForm(){
    $('#shipment-schedule-form')[0].reset();
    $("#scheduleETA").empty();
    $("#scheduleETD").empty();
}

$(function () {

    var button = $('#btnUploadAwbOrBlScanCopy'), interval;
    var txtbox = $('#attachAwbOrBlScanCopy');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF format allowed');
                return false;
            }
            txtbox.val("Uploading...");
            //txtbox.parent.append($('button').attr('class','btn btn-sm btn-danger'));
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

    var button = $('#btnUploadCiScanCopy'), interval;
    var txtbox = $('#attachCiScanCopy');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF format allowed');
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

    var button = $('#btnUploadPackListScanCopy'), interval;
    var txtbox = $('#attachPackListScanCopy');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF format allowed');
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

    var button = $('#btnUploadOriginCertificate'), interval;
    var txtbox = $('#attachOriginCertificate');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF format allowed');
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

    var button = $('#btnUploadFreightCertificate'), interval;
    var txtbox = $('#attachFreightCertificate');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF format allowed');
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

    var button = $('#btnUploadShipmentOther'), interval;
    var txtbox = $('#attachShipmentOther');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf|zip)$/i.test(ext))) {
                alertify.alert('Invalid File Format, only PDF & Zip format allowed');
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

function LoadPOLines(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/shipment?action=15&pono=" + poid +'&ship='+shipno, function (data) {
        var res = JSON.parse(data);
        if (res[1].length > 0) {
            var rejectedLines = (res[1][0]["rejectedlines"]).split(",");
            /*
             * Message for rejected lines
             * */
            $('#reject-message').show();
            //document.getElementById("rejected-line").innerHTML = rejectedLines;
            $("#rejected-line").html(rejectedLines);

        } else {
            rejectedLines = "";
            $('#reject-message').hide();
        }
        //alert(rejectedLines);

        /**
         * Deliverable po lines
         */
        var d2 = res[0];
        $("#delivCount2").html('(' + d2.length + ')');
        if (d2.length > 0) {
            $("#dtPOLines tbody").empty();

            $('#bpo').val(d2[0]["buyersPo"]);
            $('#piReqNo').val(d2[0]["PIReqNo"]);
            $('#ciAmountCur').html(d2[0]["currencyName"]);

            for (var j = 0; j < d2.length; j++) {
                if (rejectedLines != "") {
                    if (rejectedLines.indexOf(d2[j]["lineNo"]) < 0) {
                        addPOLine(d2[j]);
                    }
                } else {
                    addPOLine(d2[j]);
                }
            }
            $('#chkAllLine').prop('checked', true);
            $('.chkLine').prop('checked', true);

            poGrandTotal();
        } else {
            $("#dtPOLines tbody").empty();
            $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }
        // ldGrandTotal();
    });
}

function LoadDeliveredPOLinesInEditMode(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/shipment?action=15&pono=" + poid +'&ship='+shipno, function (data) {
        var res = JSON.parse(data);
        if (res[1].length > 0) {
            var rejectedLines = (res[1][0]["rejectedlines"]).split(",");
            /*
             * Message for rejected lines
             * */
            $('#reject-message').show();
            //document.getElementById("rejected-line").innerHTML = rejectedLines;
            $("#rejected-line").html(rejectedLines);

        } else {
            rejectedLines = "";
            $('#reject-message').hide();
        }
        //alert(rejectedLines);

        /**
         * Deliverable po lines
         */
        var d2 = res[0];
        $("#delivCount2").html('(' + d2.length + ')');
        if (d2.length > 0) {
            $("#dtPOLines tbody").empty();

            $('#bpo').val(d2[0]["buyersPo"]);
            $('#piReqNo').val(d2[0]["PIReqNo"]);
            $('#ciAmountCur').html(d2[0]["currencyName"]);

            for (var j = 0; j < d2.length; j++) {
                if (rejectedLines != "") {
                    if (rejectedLines.indexOf(d2[j]["lineNo"]) < 0) {
                        addPOLine(d2[j]);
                    }
                } else {
                    addPOLine(d2[j]);
                }
            }
            $('#chkAllLine').prop('checked', true);
            $('.chkLine').prop('checked', true);

            poGrandTotal();
        } else {
            $("#dtPOLines tbody").empty();
            $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }
        // ldGrandTotal();
    });
}

// function vatAmount(pa, vr) {
//     return pa*(vr/100);
// }

function poLineTotal(elm) {

    var linePrice, lineQty, lineTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = linePrice * lineQty;

    $(elm).closest('tr').find('input.lineTotal').val(commaSeperatedFormat(lineTotal));
    $(elm).closest('tr').find('input.lineTotal').attr('title', commaSeperatedFormat(lineTotal));

}

function poLineDelivTotal(elm) {
    var linePrice, lineDelivQty, lineDelivTotal, lineDelivQtyValid;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineDelivQty = parseToCurrency($(elm).closest('tr').find('input.delivQty').val());
    lineDelivQtyValid = parseToCurrency($(elm).closest('tr').find('input.delivQtyValid').val());

    lineDelivTotal = linePrice * lineDelivQty;

    $(elm).closest('tr').find('input.delivTotal').val(lineDelivTotal.toFixed(2));
    $(elm).closest('tr').find('input.delivTotal').attr('title', lineDelivTotal.toFixed(2));
    // calculateInvoiceAmount($("#poNo").val());

    if(lineDelivQty>lineDelivQtyValid){
        $(elm).closest('tr').find('input.delivQty').addClass('has-txt-error');
        $(elm).closest('tr').find('input.delivQty').focus();
    } else {
        $(elm).closest('tr').find('input.delivQty').removeClass('has-txt-error');
    }
}

function poLineDelivQty(elm) {

    var lineQty, lineTotal, lineDelivQty, lineDelivTotal;

    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = parseToCurrency($(elm).closest('tr').find('input.lineTotal').val());
    lineDelivTotal = parseToCurrency($(elm).closest('tr').find('input.delivTotal').val());

    lineDelivQty = lineQty / (lineTotal / lineDelivTotal);
    $(elm).closest('tr').find('input.delivQty').val(commaSeperatedFormat(lineDelivQty, 4));
    $(elm).closest('tr').find('input.delivQty').attr('title', commaSeperatedFormat(lineDelivQty, 4));
}

function poLineWisePoTotal() {

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        poLineDelivTotal(this);

    });
}

function poGrandTotal() {

    var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
        delivTotal = 0;

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        qty = parseToCurrency($(this).find('input.poQty').val());
        totalQty += qty;
        $("#poQtyTotal").val(totalQty);

        totalPrice = parseToCurrency($(this).find('input.lineTotal').val());
        grandTotal += totalPrice;
        $("#grandTotal").val(+(grandTotal).toFixed(2));

        if ($(this).find('input.chkLine').is(':checked')) {
            delivQty = parseToCurrency($(this).find('input.delivQty').val());
        } else {
            delivQty = 0;
        }
        totalDelivQty += delivQty;
        $("#dlvQtyTotal").val(+(totalDelivQty).toFixed(12));
        //alert(totalDelivQty);

        if ($(this).find('input.chkLine').is(':checked')) {
            delivPrice = parseToCurrency($(this).find('input.delivTotal').val());
        } else {
            delivPrice = 0;
        }
        delivTotal += delivPrice;
        $("#dlvGrandTotal").val(+(delivTotal).toFixed(2));
        $("#ciAmount").val(commaSeperatedFormat(delivTotal)).change();
        // $("#invAmount").val(commaSeperatedFormat(delivTotal));
        // $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
        //calculateInvoiceAmount()
    });
}

function addPOLine(row) {
    var i = $("#dtPOLines tbody tr").length;

    row = row || '';

    if (row == '') {
        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '">' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" /></td>' +
            '<td><input type="text" class="form-control input-sm uom" /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="0" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="0" /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="0" readonly /></td>' +
            '</tr>');
    } else {
        //@todo analyze here
        //console.log(row["status"]);
        if (!row["status"]) {
            var addTick = "";
            /*var voidDelivQty = 0;
            var voidDelivAmount = 0*/
            var voidDelivQty = row["delivQty"];
            var voidDelivAmount = row["delivTotal"]
        } else {
            addTick = "checked";
            voidDelivQty = row["delivQty"];
            voidDelivAmount = row["delivTotal"];
        }

        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '" ' + addTick + ' disabled>' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" name="poLine[]" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" name="poItem[]" value="' + row["itemCode"] + '" title="' + row["itemCode"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" name="poDesc[]" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" title="' + row["itemDesc"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" name="poDate[]" value="' + row["deliveryDate"] + '" title="' + row["deliveryDate"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm uom" name="uom[]" value="' + row["uom"] + '" title="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" name="unitPrice[]" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" name="poQty[]" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" name="lineTotal[]" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty"  name="delivQty[]" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" readonly /><input type="hidden" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" name="delivTotal[]" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
           '</tr>');
    }
}

/*!
* PO lines verification
* */
function poLineVerify() {
    poLinesOkay = true;
    $("#consolidatedPoLines").val("");
    $("#dtPOLines tbody").find('tr').each(function () {

        if ($("#consolidatedPoLines").val() != "") {
            $("#consolidatedPoLines").val($("#consolidatedPoLines").val() + "|");
        }

        if ($(this).find('input.chkLine').is(':checked')) {

            if ($(this).find('input.poLine').val() == "") {
                alertify.error('PO line number missing!');
                $(this).find('input.poLine').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poItem').val() == "") {
                alertify.error('Item code missing!');
                $(this).find('input.poItem').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poDesc').val() == "") {
                alertify.error('Item description missing!');
                $(this).find('input.poDesc').focus();
                poLinesOkay = false;
                return false;
            }
            /*if ($(this).find('input.projCode').val() == "") {
             alertify.error('Project code missing!');
             $(this).find('input.projCode').focus();
             poLinesOkay = false;
             return false;
             }*/
            if ($(this).find('input.uom').val() == "") {
                alertify.error('UOM missing!');
                $(this).find('input.uom').focus();
                poLinesOkay = false;
                return false;
            }
            /*!
            * O value allowed in
            * Unit Price, PO line total, Delivered price
            * as per the mail ref: FMfcgxwKjKqwmqZcjBQZSfQhhFFQKGSK
            * *************************************************************/
            if ($(this).find('input.unitPrice').val() == "" || parseFloat($(this).find('input.unitPrice').val()) < 0) {
                alertify.error('Unit price missing!');
                $(this).find('input.unitPrice').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poQty').val() == "" || parseFloat($(this).find('input.poQty').val()) <= 0) {
                alertify.error('PO line qty missing!');
                $(this).find('input.poQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.lineTotal').val() == "" || parseFloat($(this).find('input.lineTotal').val()) < 0) {
                alertify.error('PO line total missing!');
                $(this).find('input.lineTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivQty').val() == "" || parseFloat($(this).find('input.delivQty').val()) <= 0) {
                alertify.error('Delivered qty missing!');
                $(this).find('input.delivQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivTotal').val() == "" || parseFloat($(this).find('input.delivTotal').val()) < 0) {
                alertify.error('Delivered price missing!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if (parseToCurrency($(this).find('input.delivAmountValid').val()) < parseToCurrency($(this).find('input.delivTotal').val())) {
                //alertify.error('Delivered amount can not be grater then PO amount!');
                alertify.error('Invalid delivered qty or amount!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.ldAmnt').val() == "") {
                $(this).find('input.ldAmnt').val('0');
            }
// alert("find");
            var poDate = ($(this).find('input.poDate').val()) ? $(this).find('input.poDate').val() : "NA";
            //var projectCode = (1===1) ? 'test' : 'empty' ;
            $("#consolidatedPoLines").val(
                $("#consolidatedPoLines").val() + $(this).find('input.poLine').val() + ";" +
                $(this).find('input.poItem').val() + ";" +
                $(this).find('input.poDesc').val() + ";" +
                //$(this).find('input.projCode').val() + ";" +
                poDate + ";" +
                $(this).find('input.uom').val() + ";" +
                $(this).find('input.unitPrice').val() + ";" +
                $(this).find('input.poQty').val() + ";" +
                $(this).find('input.lineTotal').val() + ";" +
                $(this).find('input.delivQty').val() + ";" +
                $(this).find('input.delivTotal').val()
            )
        }
    });
}