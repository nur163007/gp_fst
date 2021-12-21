var poid, podata, comments, attach, pterms, lastshipno, shipno, u, ship;

poid = $('#pono').val();
lastshipno = $('#lastshipno').val();
shipno = $('#shipno').val();
u = $('#usertype').val();

$(document).ready(function() {

    $("#shipmentInfo input").attr('readonly', true);
    $(".shippingmode input").attr('disabled', true);

    $("#ipcReceivedQtyStatus").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "select receiving status",
        width: "100%"
    });

    $("#cNfAgentName").change(function (e) {
        $('#cNfAgentFullName').val($("#cNfAgentName").find('option:selected').text());
    });

    // Loading pre data from PO
    $.ajax({
        method: 'GET',
        url: 'api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno,
        success: function (data) {

            var row = JSON.parse(data);

            podata = row[0][0];
            comments = row[1];
            attach = row[2];
            lcinfo = row[3][0];
            pterms = row[4];

            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#lcdesc').html('<b>' + podata['lcdesc'] + '</b>');
            $('#productDesc').val(podata['lcdesc']);
            $('#supplier').html(podata['supname']);
            $('#contractref').html(podata['contractrefName']);
            $('#deliverydate').html(podata['deliverydate']);
            $('#noflcissue').html(podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']);

            // PI info
            $('#pinum').html(podata['pinum']);
            $('#pivalue').html(commaSeperatedFormat(podata['pivalue']) + ' ' + podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#hscode').html(podata['hscode']);

            $('#pidate').html(Date_toDetailFormat(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' + podata['curname']);

            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);

            attachmentLogScript(attach, '#usersAttachments');

            $.get('api/shipment?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

                ship = JSON.parse(data);

                $("#shipmode1").html(ship['shipmode']);
                $("#scheduleETA").html(Date_toDetailFormat(new Date(ship['scheduleETA'])));
                if (ship['scheduleETD'] != null) {
                    $("#scheduleETD").html(Date_toDetailFormat(new Date(ship['scheduleETD'])));
                } else {
                    $("#scheduleETD").html("");
                }

                $("#mawbNo").html(ship['mawbNo']);
                $("#hawbNo").html(ship['hawbNo']);
                $("#blNo").html(ship['blNo']);

                $("#awbOrBlDate").html(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));

                $("#ciNo").html(ship['ciNo']);
                $("#ciDate").html(Date_toDetailFormat(new Date(ship['ciDate'])));
                $("#ciAmount").html(podata['curname'] + ' ' + commaSeperatedFormat(ship['ciAmount']));
                $("#invoiceQty").html(ship['invoiceQty']);
                $("#noOfcontainer").html(ship['noOfcontainer']);
                $("#noOfBoxes").html(ship['noOfBoxes']);
                $("#ChargeableWeight").html(ship['ChargeableWeight']);

                if (ship['dhlTrackNo'] != null && ship['dhlTrackNo'] != "") {
                    $("#dhlNum").html('<a class="block text-left" target="_blank" href="http://www.dhl.com/en/express/tracking.shtml?AWB=' + ship['dhlTrackNo'] + '&brand=DHL">' + ship['dhlTrackNo'] + '</a><span class="comment-meta">(click ti view DHL status)</span>');
                } else {
                    $("#dhlNum").html("Not updated");
                }
                if (ship['docDeliveredByFin'] != null) {
                    $("#docDeliveredByFin").html(Date_toMDY_HMS(new Date(ship['docDeliveredByFin'])));
                } else {
                    $("#docDeliveredByFin").html('N/A');
                }

                $("#cdAmount").html('BDT ' + commaSeperatedFormat(ship['customDuty']));

                if (ship['ipcNo'] != null) {
                    $("#ipcNum").html(ship['ipcNo']);
                } else {
                    $("#ipcNum").html("N/A");
                }
                if (ship['gitReceiveDate'] != null) {
                    $("#gitReceiveDate").html(Date_toDetailFormat(new Date(ship['gitReceiveDate'])));
                } else {
                    $("#gitReceiveDate").html("N/A");
                }
                if (ship['whArrivalDate'] != null) {
                    $("#whArrivalDate").html(Date_toDetailFormat(new Date(ship['whArrivalDate'])));
                } else {
                    $("#whArrivalDate").html("N/A");
                }

            });

            $.get('api/cnf-inputs?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

                ship = JSON.parse(data);
                console.log(ship)
                // CDVAT Inputs

                if (ship['billofentryno'] != null) {
                    $("#billOfEntryNo").val(ship['billofentryno']);
                    restrictRejection();
                }

                if (ship['billofentrydate'] != null) {
                    var d = new Date(ship['billofentrydate']);
                    $('#billOfEntryDate').datepicker('setDate', d);
                    $('#billOfEntryDate').datepicker('update');
                    restrictRejection();
                }

                $.getJSON("api/category?action=4&id=55", function (list) {
                    $("#ddlBeneficiary").select2({
                        data: list,
                        minimumResultsForSearch: Infinity,
                        placeholder: "select beneficiary",
                        allowClear: false,
                        width: "100%"
                    });

                    if (ship['beneficiary'] != null) {
                        $("#ddlBeneficiary").val(ship['beneficiary']).change();
                    }
                });

                // CDVAT - Global Taxs

                if (ship['itoncnfcomm'] != null) {
                    $("#itOnCnFComm").val(commaSeperatedFormat(ship['itoncnfcomm']));
                    restrictRejection();
                }

                if (ship['vatoncnfcomm'] != null) {
                    $("#vatOnCnFComm").val(commaSeperatedFormat(ship['vatoncnfcomm']));
                    restrictRejection();
                }

                if (ship['docprocessfee'] != null) {
                    $("#docProcessFee").val(commaSeperatedFormat(ship['docprocessfee']));
                    restrictRejection();
                }

                if (ship['finepenalties'] != null) {
                    $("#finePenalties").val(commaSeperatedFormat(ship['finepenalties']));
                    restrictRejection();
                }

                if (ship['contscanningfee'] != null) {
                    $("#contScanningFee").val(commaSeperatedFormat(ship['contscanningfee']));
                    restrictRejection();
                }

                // CDVAT - Item Taxs

                if (ship['customduty'] != null) {
                    $("#customDuty").val(commaSeperatedFormat(ship['customduty'])).change();
                    restrictRejection();
                }

                if (ship['regulatoryduty'] != null) {
                    $("#regulatoryDuty").val(commaSeperatedFormat(ship['regulatoryduty']));
                    restrictRejection();
                }

                if (ship['supplementaryduty'] != null) {
                    $("#supplementaryDuty").val(commaSeperatedFormat(ship['supplementaryduty']));
                    restrictRejection();
                }

                if (ship['valueaddedtax'] != null) {
                    $("#valueAddedTax").val(commaSeperatedFormat(ship['valueaddedtax']));
                    restrictRejection();
                }

                if (ship['advanceincometax'] != null) {
                    $("#advanceIncomeTax").val(commaSeperatedFormat(ship['advanceincometax']));
                    restrictRejection();
                }

                if (ship['advancetradevat'] != null) {
                    $("#advanceTradeVat").val(commaSeperatedFormat(ship['advancetradevat']));
                    restrictRejection();
                }

                if (ship['advancetax'] != null) {
                    $("#advanceTax").val(commaSeperatedFormat(ship['advancetax']));
                    restrictRejection();
                }

                calculateTaxTotal();

                // Original Bank Document
                if (ship["attachOriginalBankDoc"] != null) {
                    //$("#attachOriginalBankDoc").val(ship["attachBillOfEntry"]);
                    $("#attachOriginalBankDocLink").html(attachmentLink(ship['attachOriginalBankDoc']));
                }

                // Bill of Entry Copy
                if (ship["attachBillOfEntry"] != null) {
                    $("#attachBillOfEntryOld").val(ship["attachBillOfEntry"]);
                    $("#attachBillOfEntryLink").html(attachmentLink(ship['attachBillOfEntry']));
                }
                // Other Customs Doc
                if (ship["attachOtherCustomDoc"] != null) {
                    $("#attachOtherCustomDocOld").val(ship["attachOtherCustomDoc"]);
                    $("#attachOtherCustomDocLink").html(attachmentLink(ship['attachOtherCustomDoc']));
                }
            });

            /*!
            * Create ZIP file
            * Added by: Hasan Masud
            * Added on: 2020-07-27
            * 'Suppliers PI' added in ref of
            * mail: FMfcgzGkZkTvvSMcWBLbDcRmgGGkhFtB
            * ******************************/
            for (var i = 0; i < attach.length; i++) {
                var attachList = ["Suppliers PI", "BTRC NOC", "Suppliers Catalog", "AWB/BL Scan Copy", "CI Scan Copy", "Packing List Scan Copy", "Certificate of Origine Scan Copy", "Freight Certificate", "Shipment Other Docs"];
                if (attachList.indexOf(attach[i]['title']) >= 0) {
                    $('div#filesToZip').append(
                        $('<input>').attr({
                            'type': 'checkbox',
                            'name': 'files[]',
                            'value': attach[i]['id'],
                            'checked': '""',
                            'hidden': '""'
                        })
                    );
                }
            }

        },
        error: function (xhr) {
            console.log('Error: ' + xhr);
        }
    });

    $("#btnSubmitCDVATInputs").click(function (e) {
        e.preventDefault();
        $("#btnSubmitCDVATInputs").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "api/cnf-inputs",
            data: $("#formCNFinputs").serialize() + "&userAction=1",
            cache: false,
            success: function (response) {
                $("#btnSubmitCDVATInputs").prop('disabled', false);
                // alertify.alert(response);
                try {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        // restrictRejection();
                        return true;
                    } else {
                        alertify.error("FAILED to update CDVAT inputs!");
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

    $("#btnSubmitToGP").click(function (e) {
        e.preventDefault();
        if (validateNoticeToFin()) {
        alertify.confirm('Are you sure you want to send the request?', function (e) {
            $("#btnSubmitToGP").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/cnf-inputs",
                data: $("#formCNFinputs").serialize() + "&userAction=2",
                cache: false,
                success: function (response) {
                    $("#btnSubmitToGP").prop('disabled', false);
                    // alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            alertify.success(res['message']);
                            window.location.href = _dashboardURL;
                            // restrictRejection();
                            return true;
                        } else {
                            alertify.error("FAILED to update CDVAT inputs!");
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
    });

    $("#btnAcceptCDVATInputs").click(function (e) {
        e.preventDefault();
        if (validateNoticeToFin()) {
        alertify.confirm('Are you sure you want to accept the cnf inputs?', function (e) {
            $("#btnAcceptCDVATInputs").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/cnf-inputs",
                data: $("#formCNFinputs").serialize() + "&userAction=3",
                cache: false,
                success: function (response) {
                    $("#btnAcceptCDVATInputs").prop('disabled', false);
                    // alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            alertify.success(res['message']);
                            window.location.href = _dashboardURL;
                            // restrictRejection();
                            return true;
                        } else {
                            alertify.error("FAILED to update CDVAT inputs!");
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
    });

    $("#btnRejectCNF").click(function (e) {
        e.preventDefault();
        if($("#remarks").val()==""){
            $("#remarks").focus();
            alertify.error('Please write the rejection cause');
            return false;
        }
        alertify.confirm('Are you sure you want to accept the cnf inputs?', function (e) {
            $("#btnRejectCNF").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/cnf-inputs",
                data: $("#formCNFinputs").serialize() + "&userAction=4",
                cache: false,
                success: function (response) {
                    $("#btnRejectCNF").prop('disabled', false);
                    // alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            alertify.success(res['message']);
                            window.location.href = _dashboardURL;
                            // restrictRejection();
                            return true;
                        } else {
                            alertify.error("FAILED to update CDVAT inputs!");
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


    $(".taxCal").keyup(function (e) {
        //alert('yes');
        calculateTaxTotal();
    });
});

function validateNoticeToFin(){

    if($("#billOfEntryNo").val()==""){
        alertify.error("Give bill no");
        $("#billOfEntryNo").focus();
        return false;
    }
    if($("#billOfEntryDate").val()==""){
        alertify.error("Give a date");
        $("#billOfEntryDate").focus();
        return false;
    }
    if($("#ddlBeneficiary").val()==""){
        alertify.error("Please select a Beneficiary");
        $("#ddlBeneficiary").focus();
        return false;
    }

    if($("#itOnCnFComm").val()==""){
        alertify.error("Field is required.");
        $("#itOnCnFComm").focus();
        return false;
    }


    if($("#vatOnCnFComm").val()==""){
        alertify.error("Field is required.");
        $("#vatOnCnFComm").focus();
        return false;
    }
    if($("#docProcessFee").val()==""){
        alertify.error("Field is required.");
        $("#docProcessFee").focus();
        return false;
    }
    if($("#finePenalties").val()==""){
        alertify.error("Field is required.");
        $("#finePenalties").focus();
        return false;
    }
    if($("#contScanningFee").val()==""){
        alertify.error("Field is required.");
        $("#contScanningFee").focus();
        return false;
    }
    if($("#customDuty").val()==""){
        alertify.error("Field is required.");
        $("#customDuty").focus();
        return false;
    }
    if($("#regulatoryDuty").val()==""){
        alertify.error("Field is required.");
        $("#regulatoryDuty").focus();
        return false;
    }
    if($("#supplementaryDuty").val()==""){
        alertify.error("Field is required.");
        $("#supplementaryDuty").focus();
        return false;
    }
    if($("#valueAddedTax").val()==""){
        alertify.error("Field is required.");
        $("#valueAddedTax").focus();
        return false;
    }
    if($("#advanceIncomeTax").val()==""){
        alertify.error("Field is required.");
        $("#advanceIncomeTax").focus();
        return false;
    }
    /*if($("#advanceTradeVat").val()==""){
        $("#advanceTradeVat").focus();
        alertify.error("Field is required.");
        return false;
    }*/
    if($("#advanceTax").val()==""){
        alertify.error("Field is required.");
        $("#advanceTax").focus();
        return false;
    }

    if($("#attachBillOfEntry").val()=="" && $("#attachBillOfEntryOld").val()=="")
    {
        $("#attachBillOfEntry").focus();
        alertify.error("Attach Bill of Entry copy!");
        return false;
    } else {
        if($("#attachBillOfEntry").val()!="") {
            if (!validAttachment($("#attachBillOfEntry").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
        }
    }

    return true;
}

function calculateTaxTotal() {

    var globalTaxes = parseCurOrBlank2Zero($("#itOnCnFComm").val()) +
        parseCurOrBlank2Zero($("#vatOnCnFComm").val()) +
        parseCurOrBlank2Zero($("#docProcessFee").val()) +
        parseCurOrBlank2Zero($("#finePenalties").val()) +
        parseCurOrBlank2Zero($("#contScanningFee").val());

    var itemTaxes = parseCurOrBlank2Zero($("#customDuty").val()) +
        parseCurOrBlank2Zero($("#regulatoryDuty").val()) +
        parseCurOrBlank2Zero($("#supplementaryDuty").val()) +
        parseCurOrBlank2Zero($("#valueAddedTax").val()) +
        parseCurOrBlank2Zero($("#advanceIncomeTax").val()) +
        parseCurOrBlank2Zero($("#advanceTradeVat").val()) +
        parseCurOrBlank2Zero($("#advanceTax").val());

    $("#totalGlobalTaxes").val(commaSeperatedFormat(globalTaxes));
    $("#totalItemTaxes").val(commaSeperatedFormat(itemTaxes));
    $("#totalCDVATAmount").val(commaSeperatedFormat(globalTaxes + itemTaxes));

    $("#ipcServiceValue").val($("#totalCDVATAmount").val());

    var ipcServiceValue = parseCurOrBlank2Zero($("#ipcServiceValue").val());
    var ipcPOAmount = parseCurOrBlank2Zero($("#ipcPOAmount").val());
    //alert(ipcPOAmount + ","+ ipcServiceValue);
    var receivedQty = ipcServiceValue / ipcPOAmount;
    //alert(receivedQty);
    $("#ipcReceivedQty").val(receivedQty);

}

function restrictRejection() {
    if($("#rejectMessage, #reject_btn").attr("disabled")!="disabled") {
        $("#rejectMessage, #reject_btn").attr("disabled", true);
    }
}

$(function () {

    var button = $('#btnUploadBillOfEntry'), interval;
    var txtbox = $('#attachBillOfEntry');

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

    var button = $('#btnUploadOtherCustomDoc'), interval;
    var txtbox = $('#attachOtherCustomDoc');

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