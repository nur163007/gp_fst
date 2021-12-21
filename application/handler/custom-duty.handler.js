/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
    Updated on: 2020-08-24 (Hasan Masud)
    1. Added Advance Tax(advanceTax)
    2. Loaded stored advanceTax data
    3. Implemented try-catch on click event
*************************************************************/

var pono = $("#poid").val(),
    shipno = $("#shipno").val(),
    cnfContact = "";

$(document).ready(function() {

    /*$.getJSON("api/shipment?action=10&po="+pono+"&shipno="+shipno, function(list) {
        $("#lcno").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: false,
            width: "100%"
        });
    });*/

    $("#pono").val(pono);
    //alert('api/shipment?action=1&po='+pono+"&shipno="+shipno);
    $.get('api/shipment?action=1&po=' + pono + "&shipno=" + shipno, function (data) {
        //alert(data);
        var ship = JSON.parse(data);

        $("#lcno").val(ship['lcno']);

        $("#mawbNo").val(ship['mawbNo']);
        $("#hawbNo").val(ship['hawbNo']);
        $("#blNo").val(ship['blNo']);

        $("#lcissuedate").val(Date_toDetailFormat(new Date(ship["lcissuedate"])));

        $("#ciAmount").val(commaSeperatedFormat(ship['ciAmount']));
        $("#CdPayAmount").val(commaSeperatedFormat(ship['totalCDVATAmount']));
        $("#Vat").val(commaSeperatedFormat(ship['valueAddedTax']));
        $("#vatOnCnFC").val(commaSeperatedFormat(ship['vatOnCnFComm']));
        $("#atv").val(commaSeperatedFormat(ship['advanceTradeVat']));
        $("#advanceTax").val(commaSeperatedFormat(ship['advanceTax']));
        $("#ait").val(commaSeperatedFormat(ship['advanceIncomeTax']));
        $("#gpRefNum").val(ship['eaRefNo']);
        $("#billOfEntryNo").val(ship['billOfEntryNo']);
        $("#billOfEntryDate").val(Date_toDetailFormat(new Date(ship["billOfEntryDate"])));
        $("#RequisitionDate").val(Date_toDetailFormat(new Date(ship["payOrderReqDate"])));
        $('#producttype').html(ship['producttype']);
        $('#lcdesc').html(ship['lcdesc']);

        cdCapexCalculation();

        // Original Bank Document
        if (ship["attachOriginalBankDoc"] != null) {
            //$("#attachOriginalBankDoc").val(ship["attachBillOfEntry"]);
            $("#attachOriginalBankDocLink").html(attachmentLink(ship['attachOriginalBankDoc']));
        } else {
            if (ship["attachEndorsedBankDoc"] != null) {
                $("#attachOriginalBankDocLink").html(attachmentLink(ship['attachEndorsedBankDoc']));
            }
        }

        // Bill of Entry Copy
        if (ship["attachBillOfEntry"] != null) {
            $("#attachBillOfEntryLink").html(attachmentLink(ship['attachBillOfEntry']));
        }
        // Other Customs Doc
        if (ship["attachOtherCustomDoc"] != null) {
            $("#attachOtherCustomDocLink").html(attachmentLink(ship['attachOtherCustomDoc']));
        } else {
            $("#attachOtherCustomDocLink").html("N/A");
        }

//        $("#ChargeableWeight").val(ship['ChargeableWeight']);
//        $("#dhlNum").val(ship['dhlTrackNo']);
//        $("#docSharebyFinDate").val(ship['docSharebyFinDate']);

        $.getJSON("api/category?action=4&id=55", function (list) {
            $("#beneficiary").select2({
                data: list,
                minimumResultsForSearch: Infinity,
                placeholder: "select beneficiary",
                allowClear: false,
                width: "100%"
            });
            $("#beneficiary").val(ship["beneficiary"]).change();
            $("#beneficiary").next('span').css('display', 'none');
            $("#beneficiaryText").html($("#beneficiary").find('option:selected').text());
        });

        $("#cnfAgent").change(function (e) {

            var id = $("#cnfAgent").val();
            //alert('api/company?action=1&id='+id);
            $.get('api/company?action=1&id=' + id, function (data) {

                if ($.trim(data)) {
                    //alert(data);
                    var row = JSON.parse(data);
                    //alert('sdfsds');
                    var arr = eval(row["address"].split('|'));
                    if ($("#beneficiary").val() == "40") {
                        cnfContact = arr[0];
                    } else if ($("#beneficiary").val() == "41") {
                        cnfContact = arr[1];
                    }
                }

            });
        });


        $.getJSON("api/company?action=4&type=120", function (list) {
            $("#cnfAgent").select2({
                data: list,
                placeholder: "select C&F agent",
                allowClear: false,
                width: "100%"
            });
            $("#cnfAgent").val(ship["cnfAgent"]).change();
        });

        /*$.get("api/attachment?action=1&po="+pono+"&shipno="+shipno, function (data){
             if($.trim(data)){
                //alert
                var row = JSON.parse(data);
                attach = row[0];
                //alert()
                var attachList = ["Bill of Entry Copy","Other Customs Doc","Original Bank Document"];
                //alert(attach);
                attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            }
            
        });*/

        // Loading data from Custom Duty table
        //alert("api/custom-duty?action=4&po="+pono+"&shipno="+shipno);
        $.get("api/custom-duty?action=4&po=" + pono + "&shipno=" + shipno, function (data) {

            if ($.trim(data) != "null") {

                var cd = JSON.parse(data);

                var d = new Date(cd['RequisitionDate']);
                $('#RequisitionDate').datepicker('setDate', d);
                $('#RequisitionDate').datepicker('update');

                // $('#CdPayAmount').val(commaSeperatedFormat(cd['CdPayAmount']));
                // $('#Vat').val(commaSeperatedFormat(cd['Vat']));
                // $('#vatOnCnFC').val(commaSeperatedFormat(cd['vatOnCnFC']));
                // $('#atv').val(commaSeperatedFormat(cd['atv']));
                // $('#ait').val(commaSeperatedFormat(cd['ait']));
                $('#vrPercentage').val(commaSeperatedFormat(cd['percentage']));
                $('#RebateAmount').val(commaSeperatedFormat(cd['RebateAmount']));
                //$('#customDuty').val(commaSeperatedFormat(cd['customDuty']));
                if (cd['payorderDeliveryTime'] != null) {
                    $("#payorderDeliveryTime").val(Date_toMDY_HMS_detail(new Date(cd['payorderDeliveryTime'])));
                }
            }
        });

        // Checking if already notified to buyer
        $.get("api/custom-duty?action=5&po=" + pono + "&shipno=" + shipno, function (result) {
            if (result > 0) {
                // $("#customDuty_btn, #userMessage, #reject_btn, #notifyToSourcing_btn, #saveCustomDuty_btn").attr("disabled", true);
                $("#userMessage, #reject_btn, #notifyToSourcing_btn").attr("disabled", true);
            }
        });

    });

    $("#btnUpdatePODelivTime").click(function (e) {
        $("#payorderDeliveryTime").val(Date_toMDY_HMS_detail(new Date()));
    });


    $("#reject_btn").click(function (e) {
        /*alert('abcd');*/
        e.preventDefault();
        if ($("#userMessage").val() != "") {
            alertify.confirm('Are you sure you want to reject?', function (e) {
                if (e) {
                    $("#reject_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/custom-duty",
                        data: "userAction=1" + "&poid=" + pono + "&shipno=" + shipno + "&refId=" + $("#refId").val() + "&userMessage=" + $("#userMessage").val(),
                        cache: false,
                        success: function (response) {
                            $("#reject_btn").prop('disabled', false);
                            // alert(response);
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
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            alertify.error("Please write the remarks on rejection");
            return false;
        }
    });

    $("#saveCustomDuty_btn").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/custom-duty",
                data: $('#custom-duty-form').serialize() + "&userAction=2",
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    // alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            alertify.success('Custom Duty information Saved.');
                            //window.location.href = _dashboardURL;
                        } else {
                            alertify.error("FAILED to save!");
                            return false;
                        }
                    } catch (e) {
                        console.log(e);
                        alertify.error(response + ' Failed to process the request.');
                        return false;
                    }
                },
                error: function (xhr) {
                    console.log('Error: ' + xhr);
                }
            });
        } else {
            return false;
        }
    });

    $("#notifyToSourcing_btn").click(function (e) {
        /*alert('abcd');*/
        e.preventDefault();
        if (validateNoticeToSourcing() === true) {
            alertify.confirm('Are you sure you want to Notify to Sourcing?', function (e) {
                if (e) {
                    $("#notifyToSourcing_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/custom-duty",
                        data: "poid=" + pono + "&shipno=" + shipno + "&refId=" + $("#refId").val() + "&userAction=3",
                        cache: false,
                        success: function (response) {
                            $("#notifyToSourcing_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success('Notification sent to Sourcing.');
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

    $("#btnGenerate_CDLetter").click(function (e) {

        e.preventDefault();

        if (validateForLetter()) {

            var portRef = '';
            if ($("#beneficiary").val() == "40") {
                portRef = docref_custom_duty_dhk_letter_ref;
            } else if ($("#beneficiary").val() == "41") {
                portRef = docref_custom_duty_ctg_letter_ref;
            }
            //--- getting letter serial number based on PO, shipment and bank ---------
            $.get('api/lib-helper?req=1&po=' + pono + '&ship=' + shipno + '&orgtype=cd&orgid=' + $("#beneficiary").val(), function (sl) {

                //------- generating letter reference number ------------------------
                if (sl != "0") {
                    var d = new Date();
                    letterRef = portRef + d.getFullYear() + "/" + zeroPad(sl);
                } else {
                    letterRef = portRef + d.getFullYear() + "/" + zeroPad(1);
                }
                //------- end generating letter reference number ------------------------

                $.ajax({
                    url: "application/templates/letter_template/temp_custom_duty_letter.html",
                    cache: false,
                    global: false,
                    success: function (result) {
                        //alert(result);
                        var temp = result;
                        //---------------replace data-----------------
                        temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date(), "."));
                        temp = temp.replace('##REF##', letterRef);
                        temp = temp.replace(/##BENEFECIARY##/g, $("#beneficiary").find('option:selected').text());
                        if ($("#beneficiary").val() == "40") {
                            temp = temp.replace('##PORT##', "Dhaka Airport");
                            temp = temp.replace(/##PORTBRANCH##/g, "Bashundhara Branch, Dhaka");
                        } else if ($("#beneficiary").val() == "41") {
                            temp = temp.replace('##PORT##', "Chittagong Port");
                            temp = temp.replace(/##PORTBRANCH##/g, "Agrabad Branch, Chittagong");
                        }
                        temp = temp.replace('##CONTACT##', cnfContact.replace(/,/g, '<br/>'));
                        temp = temp.replace('##CDAMOUNT##', commaSeperatedFormat($("#CdPayAmount").val()));
                        temp = temp.replace('##AMOUNTINWORD##', takaToWords($("#CdPayAmount").val()));
                        temp = temp.replace('##CNFAGENT##', $("#cnfAgent").find('option:selected').text());
                        //---------------end replace data-------------

                        $("#fileName").val('customs_duty2_' + pono + '.doc');
                        $("#letterContent").val(temp);

                        document.getElementById("formLetterContent").submit();
                        /*$.generateFile({
                         filename	: 'customs_duty_'+pono+'.doc',
                         content		: $('#letter1').val(),
                         script		: 'application/library/downloadGeneratedFile.php'
                         });*/
                    }
                });
            });
        }
    });

    /*$("#RebateAmount").keyup(function(e){
        cdCapexCalculation();
    });*/

    /*$(".curnum").blur(function(e){
        $(this).val(commaSeperatedFormat($(this).val()));
    });*/


    $("#vrPercentage").keyup(function () {
        cdCapexCalculation();
    });
}); //End document ready function

function cdCapexCalculation() {
    //alert('asda');
    var vrPercentage = parseToCurrency($("#vrPercentage").val());
    $("#RebateAmount").val(commaSeperatedFormat((parseToCurrency($("#vatOnCnFC").val()) * vrPercentage) / 100));
    var t = parseToCurrency($("#CdPayAmount").val()) -
        parseToCurrency($("#Vat").val()) - parseToCurrency($("#atv").val()) - parseToCurrency($("#advanceTax").val()) -
        parseToCurrency($("#ait").val()) - parseToCurrency($("#RebateAmount").val());
    $("#customDuty").val(commaSeperatedFormat(t));
}

/*$('#RequisitionDate')
    .datepicker({
        todayHighlight: true,
        autoclose: true
})*/

function validateForLetter(){
    return true;
}

function validateNoticeToSourcing(){
    return true;
}

function validate(){
    if($("#lcno").val()=="")
	{
		$("#lcno").focus();
        alertify.error("LC number is required!");
		return false;
	}
    /*if($("#MawbNum").val()=="")
	{
		$("#MawbNum").focus();
        alertify.error("MAWB number is required!");
		return false;
	}
    if($("#HawbNum").val()=="")
	{
		$("#HawbNum").focus();
        alertify.error("HAWB number is required!");
		return false;
	}
    if($("#BlNum").val()=="")
	{
		$("#BlNum").focus();
        alertify.error("BL number is required!");
		return false;
	}*/
    if($("#gpRefNum").val()=="")
	{
		$("#gpRefNum").focus();
        alertify.error("Reference number is required!");
		return false;
	}
    if($("#BoENum").val()=="")
	{
		$("#BoENum").focus();
        alertify.error("BOE number is required!");
		return false;
	}
    if($("#BoEDate").val()=="")
	{
		$("#BoEDate").focus();
        alertify.error("BOE Date is required!");
		return false;
	}
    if($("#RequisitionDate").val()=="")
	{
		$("#RequisitionDate").focus();
        alertify.error("Requisition Date is required!");
		return false;
	}
    if($("#Beneficiary").val()=="")
	{
		$("#Beneficiary").focus();
        alertify.error("Beneficiary field is required!");
		return false;
	}
    if($("#CnFAgent").val()=="")
	{
		$("#CnFAgent").focus();
        alertify.error("C & F Agent field is required!");
		return false;
	}
    if($("#customDuty").val()=="")
	{
		$("#customDuty").focus();
        alertify.error("Custom Duty is required!");
		return false;
	}
    if($("#Vat").val()=="")
	{
		$("#Vat").focus();
        alertify.error("Vat is required field!");
		return false;
	}
    if($("#vatOnCnFC").val()=="")
	{
		$("#vatOnCnFC").focus();
        alertify.error("VAT On C & FC is required field!");
		return false;
	}
    if($("#atv").val()=="")
	{
		$("#atv").focus();
        alertify.error("ATV is required field!");
		return false;
	}
    if($("#ait").val()=="")
	{
		$("#ait").focus();
        alertify.error("AIT is required field!");
		return false;
	}
    if($("#CdPayAmount").val()=="")
	{
		$("#CdPayAmount").focus();
        alertify.error("Cd PayAmount is required field!");
		return false;
	}
    if($("#vrPercentage").val()=="")
	{
		$("#vrPercentage").focus();
        alertify.error("Percentage field is required!");
		return false;
	}
    if($("#RebateAmount").val()=="")
	{
		$("#RebateAmount").focus();
        alertify.error("Rebate Amount is required field!");
		return false;
	}
    /*if($("#RemarksFromEA").val()=="")
	{
		$("#RemarksFromEA").focus();
        alertify.error("Remarks From EA is required field!");
		return false;
	}*/
	return true;	
}



