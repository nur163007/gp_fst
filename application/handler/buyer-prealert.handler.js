/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

var podata,
    comments,
    attach,
    lcinfo,
    pterms,
    ship;

$(document).ready(function() {

    var poid = $('#pono').val();
    var shipno = $('#shipno').val();
    var u = $('#usertype').val();

    $('#shipmodesea, #shipmodeair').on('ifChecked', function (event) {
        var shipmode_check = $('input:radio[name=shipmode]:checked').val();
        toggleShipmodeAction(shipmode_check);
    });

    $('#docEndorse, #docOriginal').on('ifChecked', function (event) {
        var doc_check = $('input:radio[name=docType]:checked').val();
        toggleDocType(doc_check);
    });

    // Loading pre data from PO
    $.get('api/purchaseorder?action=2&id=' + poid, function (data) {

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

            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
            $('#podesc').html(HTMLDecode(podata['podesc']));
            $('#lcdesc').html(HTMLDecode(podata['lcdesc']));
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

            $("#lcno1").val(lcinfo['lcno']);
            $("#lcno2").val(lcinfo['lcno']);
            $("#lcno3").val(lcinfo['lcno']);
            $("#lcno4").val(lcinfo['lcno']);

            $("#lcbank").val(lcinfo['lcissuerbank']);

            // LC info
            $('#lctype').html(lcinfo['lctypename']);
            $('#producttype').html(lcinfo['producttypename']);
            $('#lcNo').html(lcinfo['lcno']);
            $('#lcafno').html(lcinfo['lcafno']);
            $('#lcissuedate').html(Date_toDetailFormat(new Date(lcinfo['lcissuedate'])));
            $('#daysofexpiry').html(Date_toDetailFormat(new Date(lcinfo['daysofexpiry'])));
            $('#lastdateofship').html(Date_toDetailFormat(new Date(lcinfo['lastdateofship'])));

            $('#lcvalue').html(podata['curname'] + ' ' + commaSeperatedFormat(lcinfo["lcvalue"]));
            $('#lcissuerbank').html(lcinfo['lcissuerbankname']);
            $('#insurance').html(lcinfo['insurancename']);
            $('#lcdesc1').html(HTMLDecode(podata['lcdesc']));

            $("#paymentTermsText").html(lcinfo["paymentterms"]);

            var ptrow = "";
            for (var i = 0; i < pterms.length; i++) {
                ptrow = "<tr><td class=\"text-center\">" + pterms[i]['percentage'] + "%</td>" +
                    "<td class=\"text-center\">" + pterms[i]['partname'] + "</td>" +
                    "<td class=\"text-center\">" + pterms[i]['dayofmaturity'] + " Days Maturity</td>" +
                    "<td class=\"text-left\">" + pterms[i]['maturityterms'] + "</td></tr>";
                $("#lcPaymentTermsTable").append(ptrow);
            }
        }
    });

    // loading shipment data
    if ($("#shipno").val() == "") {
        shipNum = 0;
    } else {
        shipNum = $("#shipno").val();
    }
    // alert('api/shipment?action=1&po='+poid+"&shipno="+shipNum);
    $.get('api/shipment?action=1&po=' + poid + "&shipno=" + shipNum, function (data) {
        var ship = JSON.parse(data);

        shipinfo = ship;

        $('#shipmode' + ship['shipmode']).attr('checked', '').parent().addClass('checked');
        toggleShipmodeAction(ship['shipmode']);

        $('#scheduleETA').val(Date_toDetailFormat(new Date(ship['scheduleETA'])));

        $("#mawbNo").val(ship['mawbNo']);
        $("#hawbNo").val(ship['hawbNo']);
        $("#blNo").val(ship['blNo']);

        $('#awbOrBlDate').val(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));

        $("#ciNo").val(ship['ciNo']);

        $('#ciDate').val(Date_toDetailFormat(new Date(ship['ciDate'])));

        $("#ciAmount").val(commaSeperatedFormat(ship['ciAmount']));
        $("#invoiceQty").val(ship['invoiceQty']);
        $("#noOfcontainer").val(ship['noOfcontainer']);
        $("#noOfBoxes").val(ship['noOfBoxes']);
        $("#ChargeableWeight").val(ship['ChargeableWeight']);
        $("#dhlNum").val(ship['dhlTrackNo']);
        $("#docSharebyFinDate").val(ship['docSharebyFinDate']);

        if (ship['ipcNo'] != null) {
            $("#ipcNum").val(ship['ipcNo']);
        }
        if (ship['gitReceiveDate'] != null) {
            $("#gitReceiveDate").val(Date_toDetailFormat(new Date(ship['gitReceiveDate'])));
        }
        if (ship['whArrivalDate'] != null) {
            $("#whDate").val(Date_toDetailFormat(new Date(ship['whArrivalDate'])));
        }

        $("#shipmentInfo input").attr('readonly', true);
        $(".shippingmode input").attr('disabled', true);

        // checkStepOvered();

    });

    $("#accept_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to accept Shipping docs?', function (e) {
                if (e) {
                    $("#accept_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyer-prealert",
                        data: $('#buyersfeedback-form').serialize() + "&userAction=1",
                        cache: false,
                        success: function (response) {
                            $("#accept_btn").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(response['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            }catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error, 20);
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });

    $("#reject_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to reject Shipping docs?', function (e) {
                if (e) {
                    $("#reject_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyer-prealert",
                        data: $('#buyersfeedback-form').serialize() + "&userAction=2",
                        cache: false,
                        success: function (response) {
                            $("#reject_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(response['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error);
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });

    $("#btnMailToWarehouse").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        var shipmode_check = $('input:radio[name=shipmode]:checked').val();
        if (validate()) {
            alertify.confirm('Are you sure you want to send mail to Warehouse?', function (e) {
                if (e) {
                    //alert($('#formMailToWarehouse').serialize()+"&userAction=3&shipmode="+shipmode_check);
                    $("#btnMailToWarehouse").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyer-prealert",
                        data: $('#formMailToWarehouse').serialize() + "&userAction=3&shipmode=" + shipmode_check,
                        cache: false,
                        success: function (response) {
                            $("#btnMailToWarehouse").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                    // checkStepOvered();
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error, 20);
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

    $("#btnMailToEATemm").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want send mail to EA Team?', function (e) {
                if (e) {
                    $("#btnMailToEATemm").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyer-prealert",
                        data: $('#formMailToEATeam').serialize() + "&userAction=4",
                        cache: false,
                        success: function (response) {
                            $("#btnMailToEATemm").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                    // checkStepOvered();
                                } else {
                                    alertify.error(res['message']);
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error, 20);
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

    $("#btnMailToFinance").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        var shipmode_check = $('input:radio[name=shipmode]:checked').val();
        if (validateFinanceTeam() === true) {
            alertify.confirm('Are you sure you want to send mail for Original/Endorsed Document ?', function (e) {
                if (e) {
                    $("#btnMailToFinance").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyer-prealert",
                        data: $('#formMailToFinance').serialize() + "&shipmode=" + shipmode_check + "&userAction=5",
                        cache: false,
                        success: function (response) {
                            $("#btnMailToFinance").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                    // checkStepOvered();
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
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
        } else {
            return false;
        }
    });

});

$("#voucherCreateDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});

function validateFinanceTeam(){
    
    if($("#voucherCreateDate").val()==""){
        $("#voucherCreateDate").focus();
        alertify.error("Voucher Date is required!");
        return false;  
    }
    
    if($("#voucherNo").val()==""){
        $("#voucherNo").focus();
        alertify.error("Voucher Number is required!");
        return false;
    }
    
    if($("#exchangeRate").val()==""){
        $("#exchangeRate").focus();
        alertify.error("Exchange Rate is required!");
        return false;
    } else {
        if(!Number($("#exchangeRate").val().replace(/,/g,""))){
            $("#exchangeRate").focus();
            alertify.error("Value not in a valid format!");
            return false;
        }
    }
    return true;
    
}

function validate(){
    return true;
}

function toggleDocType(dtype){
    //alert(dtype)
    if(dtype=='original'){
        $("#buyersMsgToFinance").val("Voucher number updated. Please collect original document.");
        // $("#btnMailToFinance").html('<i class="icon wb-envelope" aria-hidden="true"></i> Mail for Original Doc');
    } else if(dtype=='endorse'){
        $("#buyersMsgToFinance").val("Voucher number updated. Please collect Endorse document.");
        // $("#btnMailToFinance").html('<i class="icon wb-envelope" aria-hidden="true"></i> Mail for Doc Endorsement');
    }
}

function toggleShipmodeAction(smode){
    
    if(smode=='sea'){

        $("#mawbNo").attr("readonly",true);
        $("#hawbNo").attr("readonly",true);
        $("#blNo").removeAttr("readonly");
        
        // Select for Original doc
        if ($('#docEndorse').is(':checked')) {
            $('#docEndorse').removeAttribute('checked').parent().removeClass('checked');
        }
        $('#docOriginal').attr('checked','').parent().addClass('checked');
        
    
    } else if(smode=='air'){

        $("#blNo").attr("readonly",true);
        $("#mawbNo").removeAttr("readonly");
        $("#hawbNo").removeAttr("readonly");
        
        // Select for Endorse doc
        if ($('#docOriginal').is(':checked')) {
            $('#docOriginal').removeAttribute('checked').parent().removeClass('checked');
        }
        $('#docEndorse').attr('checked','').parent().addClass('checked');        
    }
    
    var doc_check = $('input:radio[name=docType]:checked').val();
    toggleDocType(doc_check);

}