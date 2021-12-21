/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
    Updated on: 2020-08-23 (Hasan Masud)
    1. Added Advance Tax(advanceTax) calculation
    2. Loaded stored advanceTax data
    3. Added remarks(eaRemarksOnBasic) field in basic inputs field
    4. Loaded stored eaRemarksOnBasic data
    5. Implemented try-catch on click event
*******************************************************************/

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
            commentsLogScript(comments, '#buyersmsg', '');

            // loading shipment data
            //alert('api/shipment?action=1&po='+poid+'&shipno='+shipno);
            $.get('api/shipment?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

                ship = JSON.parse(data);
                $("#shipmode" + ship['shipmode']).attr('checked', '').parent().addClass('checked');
                $("#scheduleETA").val(Date_toDetailFormat(new Date(ship['scheduleETA'])));

                $("#mawbNo").val(ship['mawbNo']);
                $("#tdMAWB").html(ship['mawbNo']);

                $("#hawbNo").val(ship['hawbNo']);
                $("#tdHAWB").html(ship['hawbNo']);

                $("#blNo").val(ship['blNo']);
                $("#tdBL").html(ship['blNo']);

                $("#awbOrBlDate").val(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));
                $("#tdAWBDate").html(Date_toDetailFormat(new Date(ship['awbOrBlDate']), '.'));

                $("#ciNo").val(ship['ciNo']);
                $("#ciDate").val(Date_toDetailFormat(new Date(ship['ciDate'])));
                $("#ciAmount").val(commaSeperatedFormat(ship['ciAmount']));
                $("#invoiceQty").val(ship['invoiceQty']);

                $("#noOfcontainer").val(ship['noOfcontainer']);
                $("#tdContainer").html(ship['noOfcontainer']);

                $("#noOfBoxes").val(ship['noOfBoxes']);
                $("#tdBoxes").html(ship['noOfBoxes']);

                $("#ChargeableWeight").val(ship['ChargeableWeight']);
                $("#tdWeight").html(ship['ChargeableWeight']);

                $("#dhlNum").val(ship['dhlTrackNo']);

                if (ship['docDeliveredByFin'] != null) {
                    $("#docDeliveredByFin").val(Date_toMDY_HMS(new Date(ship['docDeliveredByFin'])));
                }

                // EA Inputs ------------------------------------------------------
                $.get('api/purchaseorder?action=4&po='+poid+'&step='+ACTION_REQUEST_FOR_CNF_INPUT, function(r){
                    if(r==1){
                        // alert(1);
                        $("#btnPreAlerttoCNF").attr('disabled',true);
                        // $("#btnViewIC").removeAttr("disabled");
                    }
                });
                // Basic Inputs

                if (ship['docReceiveByEA'] != null) {
                    var d = new Date(ship['docReceiveByEA']);
                    $('#docReceiveByEA').datepicker('setDate', d);
                    $('#docReceiveByEA').datepicker('update');
                    restrictRejection();
                }

                if (ship['actualArrivalAtPort'] != null) {
                    var d = new Date(ship['actualArrivalAtPort']);
                    $('#actualArrivalAtPort').datepicker('setDate', d);
                    $('#actualArrivalAtPort').datepicker('update');
                    $("#tdAADate").html(Date_toDetailFormat(new Date(ship['actualArrivalAtPort']), '.'));
                    restrictRejection();
                }

                if (ship['whReceiveDate'] != null) {
                    var d = new Date(ship['whReceiveDate']);
                    $('#whReceiveDate').datepicker('setDate', d);
                    $('#whReceiveDate').datepicker('update');
                    restrictRejection();
                }

                if (ship['releaseFromPort'] != null) {
                    var d = new Date(ship['releaseFromPort']);
                    $('#releaseFromPort').datepicker('setDate', d);
                    $('#releaseFromPort').datepicker('update');
                    restrictRejection();
                }

                if (ship['eaRemarksOnBasic'] != null) {
                    $("#eaRemarksOnBasic").val(htmlspecialchars_decode(ship['eaRemarksOnBasic']));
                    restrictRejection();
                }

                if (ship['eaRefNo'] != null) {
                    $("#eaRefNo").val(ship['eaRefNo']);
                    restrictRejection();
                }

                if (ship['cnfNetPayment'] != null) {
                    $("#cnfNetPayment").val(commaSeperatedFormat(ship['cnfNetPayment']));
                    restrictRejection();
                }

                if (ship['demurrageAmount'] != null) {
                    $("#demurrageAmount").val(commaSeperatedFormat(ship['demurrageAmount']));
                    restrictRejection();
                }

                $.getJSON("api/company?action=4&type=120", function (list) {
                    $("#cNfAgentName").select2({
                        data: list,
                        placeholder: "select C & F agent",
                        allowClear: false,
                        width: "100%"
                    });
                    if (ship['cnfAgent'] != null) {
                        $("#cNfAgentName").val(ship['cnfAgent']).change();
                    }
                });

                if (ship['btrcNocNo'] != null) {
                    $("#btrcNocNo").val(ship['btrcNocNo']);
                    restrictRejection();
                }

                if (ship['btrcNocDate'] != null) {
                    var d = new Date(ship['btrcNocDate']);
                    $('#btrcNocDate').datepicker('setDate', d);
                    $('#btrcNocDate').datepicker('update');
                    restrictRejection();
                }
                calCompletionDays();

                // CDVAT Inputs

                if (ship['billOfEntryNo'] != null) {
                    $("#billOfEntryNo").val(ship['billOfEntryNo']);
                    restrictRejection();
                }

                if (ship['billOfEntryDate'] != null) {
                    var d = new Date(ship['billOfEntryDate']);
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

                if (ship['itOnCnFComm'] != null) {
                    $("#itOnCnFComm").val(commaSeperatedFormat(ship['itOnCnFComm']));
                    restrictRejection();
                }

                if (ship['vatOnCnFComm'] != null) {
                    $("#vatOnCnFComm").val(commaSeperatedFormat(ship['vatOnCnFComm']));
                    restrictRejection();
                }

                if (ship['docProcessFee'] != null) {
                    $("#docProcessFee").val(commaSeperatedFormat(ship['docProcessFee']));
                    restrictRejection();
                }

                if (ship['finePenalties'] != null) {
                    $("#finePenalties").val(commaSeperatedFormat(ship['finePenalties']));
                    restrictRejection();
                }

                if (ship['contScanningFee'] != null) {
                    $("#contScanningFee").val(commaSeperatedFormat(ship['contScanningFee']));
                    restrictRejection();
                }

                // CDVAT - Item Taxs

                if (ship['customDuty'] != null) {
                    $("#customDuty").val(commaSeperatedFormat(ship['customDuty'])).change();
                    restrictRejection();
                }

                if (ship['regulatoryDuty'] != null) {
                    $("#regulatoryDuty").val(commaSeperatedFormat(ship['regulatoryDuty']));
                    restrictRejection();
                }

                if (ship['supplementaryDuty'] != null) {
                    $("#supplementaryDuty").val(commaSeperatedFormat(ship['supplementaryDuty']));
                    restrictRejection();
                }

                if (ship['valueAddedTax'] != null) {
                    $("#valueAddedTax").val(commaSeperatedFormat(ship['valueAddedTax']));
                    restrictRejection();
                }

                if (ship['advanceIncomeTax'] != null) {
                    $("#advanceIncomeTax").val(commaSeperatedFormat(ship['advanceIncomeTax']));
                    restrictRejection();
                }

                if (ship['advanceTradeVat'] != null) {
                    $("#advanceTradeVat").val(commaSeperatedFormat(ship['advanceTradeVat']));
                    restrictRejection();
                }

                if (ship['advanceTax'] != null) {
                    $("#advanceTax").val(commaSeperatedFormat(ship['advanceTax']));
                    restrictRejection();
                }

                calculateTaxTotal();

                // IPC inputs
                if (ship['ipcPONO'] != null) {
                    $("#ipcPONO").val(ship['ipcPONO']);
                    restrictRejection();
                }

                if (ship['ipcPOAmount'] != null) {
                    $("#ipcPOAmount").val(commaSeperatedFormat(ship['ipcPOAmount']));
                    restrictRejection();
                }

                if (ship['ipcItemCode'] != null) {
                    $("#ipcItemCode").val(ship['ipcItemCode']);
                    restrictRejection();
                }

                if (ship['ipcServiceValue'] != null) {
                    $("#ipcServiceValue").val(commaSeperatedFormat(ship['ipcServiceValue']));
                    restrictRejection();
                }

                if (ship['ipcReceivedQty'] != null) {
                    $("#ipcReceivedQty").val(ship['ipcReceivedQty']);
                    restrictRejection();
                }

                if (ship['ipcReceivedQtyStatus'] != null) {
                    $("#ipcReceivedQtyStatus").val(ship['ipcReceivedQtyStatus']).change();
                    restrictRejection();
                }

                if (ship['ipcPONeedByDate'] != null) {
                    var d = new Date(ship['ipcPONeedByDate']);
                    $('#ipcPONeedByDate').datepicker('setDate', d);
                    $('#ipcPONeedByDate').datepicker('update');
                    restrictRejection();
                }

                if (ship['ipcReceivedDate'] != null) {
                    var d = new Date(ship['ipcReceivedDate']);
                    $('#ipcReceivedDate').datepicker('setDate', d);
                    $('#ipcReceivedDate').datepicker('update');
                    restrictRejection();
                }

                if (ship['tentativeDelivDate'] != null) {
                    var d = new Date(ship['tentativeDelivDate']);
                    $('#tentativeDelivDate').datepicker('setDate', d);
                    $('#tentativeDelivDate').datepicker('update');
                    restrictRejection();
                }
                //-- end EA Inputs

                financeNotificationStatus();
                warehouseNotificationStatus();
                // End EA Inputs ------------------------------------------------------

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

            if (lcinfo['lcno'] != "") {
                $("#lcNo").val(lcinfo["lcno"]);
                $('#lcissuedate').val(Date_toDetailFormat(new Date(lcinfo['lcissuedate'])));
                $('#daysofexpiry').val(Date_toDetailFormat(new Date(lcinfo['daysofexpiry'])));
            }


            /*!
            * Create ZIP file
            * Added by: Hasan Masud
            * Added on: 2020-07-27
            * 'Suppliers PI' added in ref of
            * mail: FMfcgzGkZkTvvSMcWBLbDcRmgGGkhFtB
            * ******************************/
            for(var i=0; i<attach.length; i++) {
                var attachList = ["Suppliers PI", "BTRC NOC", "Suppliers Catalog", "AWB/BL Scan Copy", "CI Scan Copy", "Packing List Scan Copy", "Certificate of Origine Scan Copy", "Freight Certificate", "Shipment Other Docs"];
                if(attachList.indexOf(attach[i]['title'])>=0) {
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

    $("#ddlEALetterName").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "select letter...",
        allowClear: false,
        width: "100%"
    });

    $("#reject_btn").click(function (e) {
        e.preventDefault();
        if ($("#rejectMessage").val() != "") {
            alertify.confirm('Are you sure you want to reject Shipment Documents?', function (e) {
                if (e) {
                    $("#reject_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/ea-inputs",
                        data: "userAction=4" + "&pono=" + poid + "&shipno=" + $("#shipno").val() + "&refId=" + $("#refId").val() + "&rejectMessage=" + $("#rejectMessage").val(),
                        cache: false,
                        success: function (response) {
                            $("#reject_btn").prop('disabled', false);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
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
            $("#ipcNo").focus();
            alertify.error("Please mention the cause for rejection");
        }
    });

    $("#btnSubmitBasicInputs").click(function (e) {
        e.preventDefault();

        if ($("#whReceiveDate").val() != "") {
            alertify.confirm("You have input the <strong>Warehouse Receive Date</strong>. It will close the EA Team inputs.<br/> Are you sure you want to proceed?", function (e) {
                if (e) {
                    saveBasicInputs();
                } else {

                }
            });
        } else {
            saveBasicInputs();
        }
    });

    //SEND PRE ALERT TO C&F

    $("#btnPreAlerttoCNF").click(function (e) {
        if($("#cNfAgentName").val()==""){
            alertify.error("Please select C&F agent!");
            return false;
        }
        e.preventDefault();
        $("#btnPreAlerttoCNF").prop('disabled', true);
        alertify.confirm('Are you sure you want to reject Shipment Documents?', function (e) {
        $.ajax({
            type: "POST",
            url: "api/ea-inputs",
            data: $("#formEAinputs, #formBasicInputs").serialize() + "&userAction=8",
            cache: false,
            success: function (response) {
                $("#btnPreAlerttoCNF").prop('disabled', false);
                // alertify.alert(response);
                try {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        window.location.href = _dashboardURL;
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

    $("#btnSubmitCDVATInputs").click(function (e) {
        e.preventDefault();
        $("#btnSubmitCDVATInputs").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "api/ea-inputs",
            data: $("#formEAinputs, #formCDVATInputs, #ipcInputs").serialize() + "&userAction=2",
            cache: false,
            success: function (response) {
                $("#btnSubmitCDVATInputs").prop('disabled', false);
                // alertify.alert(response);
                try {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        restrictRejection();
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

    $("#btn_RequestToFinance").click(function (e) {
        e.preventDefault();
        if (validateNoticeToFin()) {
            alertify.confirm('Are you sure you want send the request to Finance?', function (e) {
                if (e) {
                    $("#btn_RequestToFinance").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/ea-inputs",
                        data: $("#formEAinputs, #formCDVATInputs").serialize() + "&eaRefNo=" + $("#eaRefNo").val() + "&userAction=3",
                        cache: false,
                        success: function (response) {
                            $("#btn_RequestToFinance").prop('disabled', false);
                            // alert(response)
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    financeNotificationStatus();
                                    alertify.success(res['message']);
                                    //window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(res['message']);
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response);
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

    $("#btnSaveIPCInputs").click(function (e) {
        e.preventDefault();
        $("#btnSaveIPCInputs").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "api/ea-inputs",
            data: $("#formEAinputs, #ipcInputs").serialize() + "&eaRefNo=" + $("#eaRefNo").val() + "&userAction=6",
            cache: false,
            success: function (response) {
                $("#btnSaveIPCInputs").prop('disabled', false);
                // alertify.alert(response);
                try {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        restrictRejection();
                        return true;
                    } else {
                        alertify.error(res['message']);
                        return false;
                    }
                } catch (e) {
                    console.log(e);
                    alertify.error(response);
                    return false;
                }
            },
            error: function (xhr, textStatus, error) {
                alertify.error(textStatus + ": " + xhr.status + " " + error);
            }
        });
    });

    $("#btnMailForIPCNo").click(function (e) {
        e.preventDefault();
        //alert($("#tabShipment input").serialize());
        alertify.confirm('Are you sure you want to send mail to warehouse?', function (e) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/ea-inputs",
                    data: $("#formEAinputs, #ipcInputs, #formBasicInputs, #tabShipment input").serialize() + "&userAction=7",
                    cache: false,
                    success: function (result) {
                        //alert(result);
                        var res = JSON.parse(result);
                        if (res['status'] == 1) {
                            alertify.success(res['message']);
                            warehouseNotificationStatus();
                            return true;
                        } else {
                            alertify.error("FAILED to add!");
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
    });

    $('#docReceiveByEA, #whReceiveDate, #actualArrivalAtPort').datepicker().on('changeDate', function (ev) {
        calCompletionDays();
    });

    $("#btnGenerateletter").click(function (e) {

        e.preventDefault();

        if (validateForLetter()) {

            // Authorization Customs---------------------------------------------------------------------
            if ($("#ddlEALetterName").val() == 1) {
                $.ajax({
                    url: "application/templates/letter_template/temp_ea_authorization_customs_letter.html",
                    cache: false,
                    global: false,
                    success: function (result) {
                        var temp = result;

                        //---------------replace data-----------------
                        var NOs = "";
                        if ($("#mawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "MAWB NO. :" + $("#mawbNo").val();
                        }
                        if ($("#hawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "HAWB NO. :" + $("#hawbNo").val();
                        }
                        if ($("#blNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "BL NO. :" + $("#blNo").val();
                        }
                        temp = temp.replace('##NOs##', NOs);
                        temp = temp.replace(/##CNFAGENTNAME##/g, $("#cNfAgentName").find('option:selected').text());
                        temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date($("#letterDate").val()), "."));
                        //---------------end replace data-------------

                        $("#fileName").val('authorization_customs_' + poid + '.doc');
                        $("#letterContent").val(temp);
                        document.getElementById("formLetterContent").submit();
                    }
                });
            }

            // Authorization HAWB------------------------------------------------------------------------
            if ($("#ddlEALetterName").val() == 2) {
                $.ajax({
                    url: "application/templates/letter_template/temp_ea_authorization_hawb_letter.html",
                    cache: false,
                    global: false,
                    success: function (result) {
                        var temp = result;

                        //---------------replace data-----------------
                        var NOs = "";
                        if ($("#mawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "MAWB NO. :" + $("#mawbNo").val();
                        }
                        if ($("#hawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "HAWB NO. :" + $("#hawbNo").val();
                        }
                        if ($("#blNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "BL NO. :" + $("#blNo").val();
                        }
                        temp = temp.replace('##NOs##', NOs);
                        temp = temp.replace(/##CNFAGENTNAME##/g, $("#cNfAgentName").find('option:selected').text());
                        temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date($("#letterDate").val()), "."));
                        //---------------end replace data-------------

                        $("#fileName").val('authorization_hawb_' + poid + '.doc');
                        $("#letterContent").val(temp);
                        document.getElementById("formLetterContent").submit();
                    }
                });
            }

            // Authorization CTG-------------------------------------------------------------------------
            if ($("#ddlEALetterName").val() == 3) {
                $.ajax({
                    url: "application/templates/letter_template/temp_ea_authorization_ctg_letter.html",
                    cache: false,
                    global: false,
                    success: function (result) {
                        var temp = result;

                        //---------------replace data-----------------
                        var NOs = "";
                        if ($("#mawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "MAWB NO. :" + $("#mawbNo").val();
                        }
                        if ($("#hawbNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "HAWB NO. :" + $("#hawbNo").val();
                        }
                        if ($("#blNo").val() != "") {
                            if (NOs != "") {
                                NOs += "<br>";
                            }
                            NOs = "BL NO. :" + $("#blNo").val();
                        }
                        temp = temp.replace('##NOs##', NOs);
                        temp = temp.replace(/##CNFAGENTNAME##/g, $("#cNfAgentName").find('option:selected').text());
                        temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date($("#letterDate").val()), "."));
                        //---------------end replace data-------------

                        $("#fileName").val('authorization_ctg_' + poid + '.doc');
                        $("#letterContent").val(temp);
                        document.getElementById("formLetterContent").submit();
                    }
                });
            }
        }
    });

    var attachList = ["BTRC NOC", "Suppliers Catalog", "AWB/BL Scan Copy", "CI Scan Copy", "Packing List Scan Copy", "Certificate of Origine Scan Copy", "Freight Certificate", "Shipment Other Docs"];
    $("#btnGenerateEmail").click(function (e) {

        var domain = window.location.protocol + "//" + window.location.hostname + _adminURL,
            mailAttachemnt = "";
        $('#usersAttachments').find('a').each(function (e) {
             //console.log($(this).html());
            if (attachList.indexOf($(this).html()) > -1) {
                mailAttachemnt += $(this).html() + ": " + domain + $(this).attr('href') + "%0D%0A";
            }
        });

        var report = "MAWB No: " + $("#tdMAWB").html() + "%0D%0A" +
            "HAWB No: " + $("#tdHAWB").html() + "%0D%0A" +
            "BL No: " + $("#tdBL").html() + "%0D%0A" +
            "AWB/BL Date: " + $("#tdAWBDate").html() + "%0D%0A" +
            "Container: " + $("#tdContainer").html() + "%0D%0A" +
            "Boxes: " + $("#tdBoxes").html() + "%0D%0A" +
            "Weight: " + $("#tdWeight").html() + "%0D%0A" +
            "Actual Arrival Date: " + $("#tdAADate").html() + "%0D%0A";

        // var subject = "Consignment Notification";
        var subject = "Consignment Notification for PO# " + poid + " Shipment# " + shipno + " GP-REF# " + $("#eaRefNo").val();

        //alert(yourMessage);
        window.location = "mailto:?" +
            "subject=" + subject +
            "&body=" + "Dear Concern" +
            "%0A%0A" +
            "Please be informed that the subject mentioned consignment is about to/already reach at port. Now you are requested to take necessary steps to keep the following consignment in safe place and provide us the actual arrival date by mail.%0A%0A" +
            report + "%0A%0A" +
            "Download the documents from the following links:%0D%0A" +
            mailAttachemnt + "%0A%0A%0A%0A" +
            "Best Regards,%0A%0A" +
            "Benazir Ahmed,%0D%0A" +
            "Specialist, External Approvals," +
            "%0D%0A" +
            "Sourcing Operation." +
            "%0D%0A" +
            "Grameenphone Ltd." +
            "%0D%0A" +
            "%0D%0A";
    });

    $(".taxCal").keyup(function (e) {
        //alert('yes');
        calculateTaxTotal();
    });


});

function saveBasicInputs() {
    $.ajax({
        type: "POST",
        url: "api/ea-inputs",
        data: $("#formEAinputs, #formBasicInputs").serialize() + "&userAction=1",
        cache: false,
        success: function (response) {
            //alertify.alert(response);
            try {
                var res = JSON.parse(response);
                if (res['status'] == 1) {
                    alertify.success(res['message']);
                    if (res['stepover'] == 1) {
                        window.location.href = _dashboardURL;
                    }
                    restrictRejection();
                    return true;
                } else {
                    alertify.error("FAILED to add!");
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

function financeNotificationStatus() {
    // Checking if already notified to finance
    $.get("api/shipment?action=14&pono=" + poid + "&shipno=" + shipno + "&actionId=" + ACTION_CD_BE_COPY_UPDATED, function (result) {
        if (result > 0) {
            $("#beupdate-form input, #beupdate-form button, #beupdate-form textarea, #btn_RequestToFinance").attr("disabled", true);
            $("#attachBillOfEntry, #attachOtherCustomDoc").parent().parent().css("padding-top", "10px");
            $("#attachBillOfEntry, #attachOtherCustomDoc").parent().addClass("hidden").hide();
        } else {
            $("#eaRemarksOnCD").html("Pay-Order pending against PO: " + poid + " and GP-Ref: " + $("#eaRefNo").val());
        }
    });
}

function warehouseNotificationStatus() {
    // Checking if already notified to warehouse
    $.get("api/shipment?action=13&pono=" + poid + "&shipno=" + shipno + "&actionId=" + ACTION_TENTATIVE_DELIVERY_DATE_UPDATED, function (result) {
        if (result > 0) {
            $("#btnMailForIPCNo").attr("disabled", true);
        }
    });
}

function calCompletionDays(){

    // Warehouse receive date - "Doc receive by EA" or "Actual Arrival date" which is latest
    if($('#docReceiveByEA').val()!="" && $('#whReceiveDate').val()!=""){
        var completionDays = 0;

        var dDocReceive = new Date($('#docReceiveByEA').val());
        var dWhReceive = new Date($('#whReceiveDate').val());

        if($('#actualArrivalAtPort').val()!=""){
            var dActualArrival = new Date($('#actualArrivalAtPort').val());
            if(dDocReceive>dActualArrival){
                completionDays = parseInt((dWhReceive - dDocReceive) / (1000 * 60 * 60 * 24));
            } else {
                completionDays = parseInt((dWhReceive - dActualArrival) / (1000 * 60 * 60 * 24));
            }
        }else{
            completionDays = parseInt((dWhReceive - dDocReceive) / (1000 * 60 * 60 * 24));
        }

        $("#completionDays").val(completionDays);
    }
}

$("#docReceiveByEA,#actualArrivalAtPort,#customsEntryDate,#releaseFromPort,#letterDate,#whReceiveDate,#billOfEntryDate,#tentativeDelivDate,#btrcNocDate,#ipcPONeedByDate,#ipcReceivedDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});

function validateNoticeToFin(){
    
    if($("#ddlBeneficiary").val()==""){
        alertify.error("Please select a Beneficiary");
        $("#ddlBeneficiary").focus();
        return false;
    }
    if($("#cNfAgentName").val()==""){
        alertify.error("Please select a C&F Agent");
        $("#cNfAgentName").focus();
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
    return true;
}

function validateForLetter(){
    
    if($("#cNfAgentName").val()==""){
        var tabid = $("#cNfAgentName").closest("div.tab-pane").attr("id");
        $('.nav-tabs a[href="#' + tabid + '"]').tab('show');

        $("#cNfAgentName").focus();
        alertify.error("Please select a CNF Agent");
        return false;
    }
    if($("#letterDate").val()==""){
        $("#letterDate").focus();
        alertify.error("Please select Date");
        return false;
    }
    if($("#ddlEALetterName").val()==""){
        $("#ddlEALetterName").focus();
        alertify.error("Please select letter name");
        return false;
    }
    return true;

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