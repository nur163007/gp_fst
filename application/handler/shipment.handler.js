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
            if (lcinfo['lcdesc'] == "") {
                $('#lcdesc').html(podata['lcdesc']);
            } else {
                $('#lcdesc').html(lcinfo['lcdesc']);
            }
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

            $('#shipmode').html(podata['shipmode'].toUpperCase());
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
            $("#lcno").val(lcinfo['lcno']);
            $("#lcvalue").val(lcinfo['lcvalue']);

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

    $("#ciAmount").blur(function (e) {
        validateCIAmount();
    });

    $("#SendShipDoctoGp_btn").click(function (e) {

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

    writeDeliveredPOLones(poid);

});

$('#scheduleETA,#scheduleETD, #awbOrBlDate, #ciDate')
    .datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
})

function validateCIAmount() {
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
}

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
        /*if(!Number(parseToCurrency($("#ciAmount").val()))){
            $("#ciAmount").focus();
            alertify.error("Not a valid amount!");
            return false;
	   } else{
            var piv = parseToCurrency($("#piamount").val());
            var civ = parseToCurrency($("#ciAmount").val());

            if(civ > piv){
                $("#ciAmount").focus();
                $("#ciAmount").closest("div.form-group").addClass('has-error');
                alertify.error("CI value cannot be greater than PI value!");
                return false;
            }else{
                $("#ciAmount").closest("div.form-group").removeClass('has-error');
            }
	   }*/
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
