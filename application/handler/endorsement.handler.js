/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var poid = $('#pono').val();
var endno = $("#endorseNo").val();
var u = $('#usertype').val();
var shipno = $('#shipno').val();
var usertype = $('#usertype').val();
var actionId = $('#actionId').val();

var podata;
var comments;
var attach;

$(document).ready(function() {

//    $.get('api/status?action=1&poid='+poid, function (res) {
//
//        var row = JSON.parse(res);
//        $('#postatus').val(row['status']);
//        
//        var a = row['targetrole'].split(',');
//        
//        if(a.indexOf(u)<0){
//            //$("#endorsement-form input, #endorsement-form textarea, #endorsement-form select, #endorsement-form button").attr('disabled',true);
//        }
//        $("#shipInfo input").attr('readonly',true);
//    });

    $("#shipInfo input").attr('readonly', true);
    $("#shipmodesea").attr('disabled', true);
    $("#shipmodeair").attr('disabled', true);

    //alert('api/purchaseorder?action=2&id='+poid+'&shipno='+shipno);
    $.get('api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno, function (data) {

        if (!$.trim(data)) {
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {

            var row = JSON.parse(data);

            var podata = row[0][0];
            //var comments = row[1];
             attach = row[2];
            //console.log(attach);
            var lcinfo = row[3][0];
            //var pterms = row[4];
            if (usertype == const_role_lc_bank){
                var shipDocs = ["AWB/BL Scan Copy", "CI Scan Copy", "Packing List Scan Copy", "Certificate of Origine Scan Copy", "Shipment Other Docs", "Freight Certificate","Insurance Cover Note","Pay Order Receive Copy","Insurance Other Doc","Endorsement Request"];
            }
            else if (usertype == const_role_LC_Operation){
                var shipDocs = ["AWB/BL Scan Copy", "CI Scan Copy", "Packing List Scan Copy", "Certificate of Origine Scan Copy", "Shipment Other Docs", "Freight Certificate","Insurance Cover Note","Pay Order Receive Copy","Insurance Other Doc","Endorsement Copy","Endorsement Advice","Endorsement Other Docs"];
            }

            // var attachmentHtml = '', mailAttach = '', zipAttach = '';

            // attachmentHtml += '<table class="small" border="0" style="margin-bottom:20px;">';
            // for (var i = 0; i < attach.length; i++) {
            //     if (shipDocs.indexOf(attach[i]['title']) >= 0) {
            //         attachmentHtml += '<tr><td class="col-sm-5 control-label" valign="top">' + attach[i]['title'] + '</td>' +
            //             '<td class="col-sm-7" valign="top">' +
            //             '<label class="control-label text-left"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/' + attach[i][0] + '" target="_blank">' + attach[i]['title'] + '</a></label>' +
            //             '</td></tr>';
            //         mailAttach += window.location.origin + _adminURL + "download-attachment/" + attach[i][0] + "%0D%0A";
            //
            //         //<input type="checkbox" name="files[]" value="SampleFile.pdf" checked="" hidden=""/>
            //         $('div#filesToZip').append(
            //             $('<input>').attr({
            //                 'type': 'checkbox',
            //                 'name': 'files[]',
            //                 'value': attach[i]['id'],
            //                 'checked': '""',
            //                 'hidden': '""'
            //             })
            //         );
            //         /*if(zipAttach==""){
            //          zipAttach=attach[i]['filename'];
            //          } else{
            //          zipAttach+=","+attach[i]['filename'];
            //          }*/
            //     }
            // }
            // attachmentHtml += '</table>';
            attachmentLogScript(attach, '#usersAttachments', 1, shipDocs);
            // $('#usersAttachments').html(attachmentHtml);
            //
            // $('#mailAttachemnt').val(mailAttach.replace(/ /g, "%20"));
            //$('#filesToZip').val(zipAttach);

            $("#pono1").val(lcinfo['pono']);
            $("#LcValue").val(commaSeperatedFormat(lcinfo["lcvalue"]));
            $("#lcno").val(lcinfo['lcno']);
            $("#hiddenlcissuerbank").val(lcinfo['lcissuerbank']);
            $("#hiddenInsurance").val(lcinfo['insurance']);
            $(".curname").html(podata['curname']);

            $.getJSON("api/bankinsurance?action=4&type=118", function (list) {
                $("#lcissuerbank").select2({
                    data: list,
                    placeholder: "Select a Bank",
                    allowClear: false,
                    width: "100%"
                });
                $('#lcissuerbank').val(lcinfo['lcissuerbank']).change();
            });

            $.getJSON("api/bankinsurance?action=4&type=119", function (list) {
                $("#insurance").select2({
                    data: list,
                    placeholder: "Select a Insurance",
                    allowClear: false,
                    width: "100%"
                });
                $('#insurance').val(lcinfo['insurance']).change();
            });

            $.get('api/shipment?action=12&po=' + poid, function (res) {
                if ($.trim(res)) {
                    $("#previousTotalCI").html(commaSeperatedFormat(res) + ' ' + podata['curname']);
                }
            });
        }
    });

    // loading shipment data
    // alert('api/shipment?action=1&po='+poid+'&shipno='+shipno);
    $.get('api/shipment?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

        var ship = JSON.parse(data);
//        $("#scheduleETA").val(Date_toMDY(new Date(ship['scheduleETA'])));
        $('#shipmode' + ship['shipmode']).attr('checked', '').parent().addClass('checked');
        $("#mawbNo").val(ship['mawbNo']);
        $("#hawbNo").val(ship['hawbNo']);
        $("#blNo").val(ship['blNo']);
        $("#awbOrBlDate").val(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));
        $("#ciNo").val(ship['ciNo']);
        $("#ciDate").val(Date_toDetailFormat(new Date(ship['ciDate'])));
        $("#ciValue").val(commaSeperatedFormat(ship['ciAmount']));
        $("#gerpVNo").val(ship['GERPVoucherNo']);

        if (ship['docDeliveredByFin'] != null) {
            $("#docDelivered_btn").attr("disabled", true);
            $("#docDelivered").attr("disabled", true);
            $("#docDelivered").attr("checked", true).parent().addClass("checked");
        }

//        $("#noOfcontainer").val(ship['noOfcontainer']);
//        $("#noOfBoxes").val(ship['noOfBoxes']);
//        $("#ChargeableWeight").val(ship['ChargeableWeight']);
//        $("#dhlNum").val(ship['dhlTrackNo']);
//        $("#docSharebyFinDate").val(ship['docSharebyFinDate']);

    });

/*    $('#btn_mailForInsPolicy').click(function (event) {

        window.location = "mailto:?body=" + "Dear Concern" + "%0D%0A %0D%0A %0D%0A" + $("#mailAttachemnt").val() + "&subject=Insurance Policy.";

    });*/

    $('#btn_mailForInsPolicy').click(function (e) {

        e.preventDefault();
        alertify.confirm('Are you sure you want proceed?', function (e) {
            if (e) {
                $("#btn_mailForInsPolicy").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/original-doc",
                    data: $('#endorsement-form').serialize() + "&insurance=" + $("#hiddenInsurance").val() + "&pono=" + $('#pono').val() + "&shipno=" + $("#shipno").val() + "&refId=" + $("#refId").val() + "&userAction=1",
                    cache: false,
                    success: function (response) {
                        $("#btn_mailForInsPolicy").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                // window.location.href = _dashboardURL;
                                location.reload();
                            } else {
                                alertify.error("FAILED to add!");
                                return false;
                            }
                        } catch (err) {
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
        // var domain = window.location.protocol+"//"+window.location.hostname+_adminURL,
        //     mailAttachemnt = "";
        // $('#usersAttachments').find('a').each(function(e) {
        //     //mailAttachemnt += '<a href="' + domain + $(this).attr('href') + '>'+ $(this).attr('href').replace('temp/','') +'</a>'+"%0D%0A ";
        //     mailAttachemnt += $(this).html() + ": " + domain + $(this).attr('href') + "%0D%0A";
        //     //alert($(this).attr('href'));
        // });
        // //alert(mailAttachemnt);
        // window.location = "mailto:?" +
        //     "body=" + "Dear Concern" +
        //         "%0A%0A" +
        //         "Please issue Insurance Policy against Cover Note No " + $("#coverNoteNo").val() + " as per attached documents." +
        //         "%0A%0A" +
        //         "Best Regards," +
        //         "%0A%0A" +
        //         "Trade Finance Operation,%0D%0A" +
        //         "Treasury, Finance%0D%0A" +
        //         "Grameenphone Ltd.%0D%0A" +
        //         "%0A%0A" +
        //         "Please download the documents from the following links:%0D%0A" +
        //         mailAttachemnt +
        //         "&subject=Insurance Policy against Covernote-" + $("#coverNoteNo").val();

    });

    $.get('api/marine-insurance?action=1&po=' + poid, function (data) {
        if ($.trim(data)) {
            var insurance = JSON.parse(data);
            $("#coverNoteNo").val(insurance['coverNoteNo']);
        }
    });

    if ($("#endorseNo").val() > 0) {
        // alert('api/endorsement?action=1&po='+poid+'&shipno='+shipno);
        $.get('api/endorsement?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

            if ($.trim(data)) {
                var endorse = JSON.parse(data);

                var charge = (endorse['endCharge']);
                var vatCharge = (endorse['vatOnCharge']);
                var vat = (vatCharge*100/charge);
                if (charge !='' && vatCharge !=''){
                    $('#vatRate').val(vat);
                }

                $('#hiddenEndId').val(endorse['id']);

                if (endorse['endDate'] != null) {
                    $("#endDate").val(Date_toMDY(new Date(endorse['endDate'])));
                }
                $("#endCharge").val(endorse['endCharge']);
                $("#vatOnCharge").val(endorse['vatOnCharge']);

                if (endorse["attachEndorsementCopy"] != null) {
                    $("#attachEndorsementCopyOld").val(endorse["attachEndorsementCopy"]);
                    // $("#attachEndorsementCopy").val(endorse["attachEndorsementCopy"]);
                    // $("#attachEndorsementCopyOldLink").html(attachmentLink(endorse["attachEndorsementCopy"]));
                }
                if (endorse["attachEndorsementAdvice"] != null) {
                    $("#attachEndorsementAdviceOld").val(endorse["attachEndorsementAdvice"]);
                    // $("#attachEndorsementAdvice").val(endorse["attachEndorsementAdvice"]);
                    // $("#attachEndorsementAdviceOldLink").html(attachmentLink(endorse["attachEndorsementAdvice"]));
                }
                if (endorse["attachEndorsementOtherDoc"] != null) {
                    $("#attachEndorsementOtherDocOld").val(endorse["attachEndorsementOtherDoc"]);
                    // $("#attachEndorsementOtherDoc").val(endorse["attachEndorsementOtherDoc"]);
                    // $("#attachEndorsementOtherDocOldLink").html(attachmentLink(endorse["attachEndorsementOtherDoc"]));
                }

                if (endorse['docDelivered'] == 1) {
                    $("#docDelivered").attr("checked", true).parent().addClass("checked");
                    //$("#docDelivered_btn").attr("disabled", true);
                }

                $.getJSON("api/category?action=4&id=34", function (list) {
                    $("#chargeType").select2({
                        data: list,
                        minimumResultsForSearch: Infinity,
                        placeholder: "Select Charge Type",
                        allowClear: false,
                        width: "100%"
                    });
                    $("#chargeType").val(endorse['chargeType']).change();
                });
            }
        });
    } else {

        $.getJSON("api/category?action=4&id=34", function (list) {
            $("#chargeType").select2({
                data: list,
                minimumResultsForSearch: Infinity,
                placeholder: "Select Charge Type",
                allowClear: false,
                width: "100%"
            });
            $("#chargeType").val(const_charge_type_opex).change();
        });
    }

    $("#endCharge").blur(function (e) {
        $("#endCharge").val(commaSeperatedFormat($("#endCharge").val()));
    });

    $("#endCharge, #vatRate").keyup(function () {
        vatCalculation();
    });

    $("#exportEndorsementLetter_btn").click(function (e) {
        generate_endorsement_letter();
    });

    $("#btn_RequestDocEndorsement").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to send this request?', function (e) {
                if (e) {
                    $("#btn_RequestDocEndorsement").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/endorsement",
                        data: $('#endorsement-form').serialize() + "&useraction=3",
                        cache: false,
                        success: function (response) {
                            $("#btn_RequestDocEndorsement").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.success('Endorsement information saved.');
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });

    $("#btn_send_endorsementGP").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to save endorsement inputs?', function (e) {
                if (e) {
                    $("#btn_send_endorsementGP").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/endorsement",
                        data: $('#endorsement-form').serialize() + "&useraction=1",
                        cache: false,
                        success: function (response) {
                            $("#btn_send_endorsementGP").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.success('Endorsement information saved.');
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });


    $("#btn_save_endorsement").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to save endorsement inputs?', function (e) {
                if (e) {
                    $("#btn_save_endorsement").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/endorsement",
                        data: $('#endorsement-form').serialize() + "&useraction=4",
                        cache: false,
                        success: function (response) {
                            $("#btn_save_endorsement").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.success('Endorsement information saved.');
                                    // window.location.href = _dashboardURL;
                                    location.reload();
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });
    $("#originalDocProcess_btn").click(function (e) {
        alertify.confirm('Are you sure you want to change the process?', function (e) {
            if (e) {
                $("#originalDocProcess_btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/endorsement",
                    data: $('#endorsement-form').serialize() + "&useraction=2",
                    cache: false,
                    success: function (response) {
                        $("#originalDocProcess_btn").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                //ResetForm();
                                alertify.success(res['message']);
                                window.location.href = _adminURL + "original-doc?po=" + poid + "&ship=" + shipno + "&ref=" + res['lastaction'];
                            } else {
                                alertify.error("FAILED to add!");
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    },
                    error: function (xhr) {
                        console.log('Error: ' + xhr);
                    }
                });
            }
        });
    });

    $("#docDelivered_btn").click(function (e) {
        //alert('api/endorsement?action=2&po=' + poid + '&endno=' + endno + "&refId=" + $("#refId").val() + "&shipno=" + shipno);
        alertify.confirm('Are you sure you want to update document delivery status?', function (e) {
            if (e) {
                $("#docDelivered_btn").prop('disabled', true);
                $.get('api/endorsement?action=2&po=' + poid + '&endno=' + endno + "&refId=" + $("#refId").val() + "&shipno=" + shipno, function (response) {
                    //alert(result);
                    $("#docDelivered_btn").prop('disabled', false);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            $("#docDelivered").attr("checked", true).parent().addClass("checked");

                            $("#docDelivered_btn").attr("disabled", true);
                            $("#docDelivered").attr("disabled", true);
                            $("#docDelivered").attr("checked", true).parent().addClass("checked");

                            alertify.alert("Document delivery status updated.");
                        }
                    } catch (e) {
                        alertify.error(response + ' Failed to process the request.', 20);
                        return false;
                    }
                });
            }
        });
    });

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_REQUEST_FOR_DOC_ENDORSEMENT_SEND_BY_GP, function (r) {
        // console.log(r)
        if (r == 1) {
            // alert(1);
            $("#btn_RequestDocEndorsement").hide();
            $("#btn_generateEndorsement").hide();
            }
        else {
            $("#btn_RequestDocEndorsement").show();
            $("#btn_generateEndorsement").show();
        }
    });
    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_DOC_ENDORSEMENT_SEND_BY_BANK, function (r) {
        // console.log(r)
        if (r == 1) {
            // alert(1);
            $("#btn_mailForInsPolicy").show();
        }
        else {
            $("#btn_mailForInsPolicy").hide();
        }
    });
    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_REQUEST_FOR_INS_POLICY_BY_TFO, function (r) {
        // console.log(r)
        if (r == 1) {
            // alert(1);
            $("#btn_mailForInsPolicy").attr('disabled',true);
        }

    });
});

$('#endDate').datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
});

function validateZip(){
    return true;
}


function vatCalculation(){
    var vatRate = parseToCurrency($("#vatRate").val());
    var endCharge = parseToCurrency($("#endCharge").val());
    var vat = (endCharge * vatRate)/100;
    $("#vatOnCharge").val(commaSeperatedFormat(vat));
}

function validate()
{
    /*if($("#endCharge").val()=="")
    {
        $("#endCharge").focus();
        alertify.error("Endorsement Charge is required!");
        return false;
    }*/

    //alert($("#endCharge").val());
    
    /*if($("#policyNum").val()=="")
	{
		$("#policyNum").focus();
        alertify.error("Policy Number is required!");
		return false;
	}
    if($("#policyValue").val()=="")
	{
		$("#policyValue").focus();
        alertify.error("Policy Value is required!");
		return false;
	}

    if($("#vatOnCharge").val()=="")
	{
		$("#vatOnCharge").focus();
        alertify.error("Vat on Charge is required!");
		return false;
	}
    if($("#coverNoteNo").val()=="")
	{
		$("#coverNoteNo").focus();
        alertify.error("Please provide Cover Note Nnumber!");
		return false;
	}
    if($("#chargeType").val()=="")
	{
		$("#chargeType").focus();
        alertify.error("Please select Charge Type!");
		return false;
	}*/
    if (usertype == const_role_LC_Operation && actionId == 70) {
        if ($("#attachEndorsementLetter").val() == "") {
            $("#attachEndorsementLetter").focus();
            alertify.error("Endorsement Letter attachment is required!");
            return false;
        }
    }
    // if (actionId ==118){
    //     if($("#endDate").val()=="")
    //     {
    //         $("#endDate").focus();
    //         alertify.error("Endorsement Date is required!");
    //         return false;
    //     }
    //     if($("#endCharge").val()==""){
    //         $("#endCharge").val(0);
    //     }
    //     if($("#chargeType").val()==""){
    //         $("#chargeType").val("28").change();
    //     }
    //     if($("#vatOnCharge").val()==""){
    //         vatCalculation();
    //     }
    //
    //     if($("#attachEndorsementCopy").val()=="")
    //     {
    //         $("#attachEndorsementCopy").focus();
    //         alertify.error("Endorsement Copy attachment is required!");
    //         return false;
    //     } else {
    //         if(!validAttachment($("#attachEndorsementCopy").val())){
    //             alertify.error('Invalid File Format.');
    //             return false;
    //         }
    //     }
    //     if($("#endCharge").val()!="" && parseToCurrency($("#endCharge").val())!=0){
    //         if($("#attachEndorsementAdvice").val()=="")
    //         {
    //             $("#attachEndorsementAdvice").focus();
    //             alertify.error("Endorsement Advice attachment is required!");
    //             return false;
    //         } else {
    //             if(!validAttachment($("#attachEndorsementAdvice").val())){
    //                 alertify.error('Invalid File Format.');
    //                 return false;
    //             }
    //         }
    //     }
    // }

	return true;	
}

function ResetForm(){
    //$('#endorsement-request-form')[0].reset();
	//$("#poNum").empty();
    
}



$("#btn_generateEndorsement").click(function(e) {
    e.preventDefault();
    if (validateForLetter()) {

        //$.get("api/shipment?action=4&lc="+$("#lcno").val()+"&cino="+$("#ciNo").val(), function (data1){
        //alert(data1);
        //var paydata = JSON.parse(data1);

        /*var paidAmount = parseToCurrency(paydata['paidAmount']),
            xRate = parseToCurrency(paydata['exchangeRate']),
            paidAmountBDT = paidAmount * xRate;*/

        $.get("api/original-doc?action=3&bank=" + $("#lcissuerbank").val() + "&po=" + $("#pono").val(), function (data) {
            // alert(data);
            var bankData = JSON.parse(data);

            //--- getting letter serial number based on PO, shipment and bank ---------
            $.get('api/lib-helper?req=1&po=' + $("#pono").val() + "&ship=" + $("#shipno").val() + '&orgtype=bank&orgid=' + $("#lcissuerbank").val(), function (sl) {

                //------- generating letter reference number ------------------------
                if (sl != "0") {
                    var d = new Date();
                    letterRef = docref_LC_endorsement_letter_ref + d.getFullYear() + "/" + zeroPad(sl);
                } else {
                    letterRef = docref_LC_endorsement_letter_ref + d.getFullYear() + "/" + zeroPad(1);
                }
                //------- end generating letter reference number ------------------------
                $("#btn_generateEndorsement").prop('disabled', true);
                $.ajax({
                    url: "application/templates/letter_template/temp_document_endorsement_letter.html",
                    cache: false,
                    global: false,
                    success: function (response) {
                        $("#btn_generateEndorsement").prop('disabled', false);
                        try {
                            var temp = response;
                            //---------------replace data-----------------

                            temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                            temp = temp.replace('##REF##', letterRef);
                            temp = temp.replace('##BANKNAME##', bankData["name"]);
                            temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                            temp = temp.replace('##LCNO##', $("#lcno").val());
                            temp = temp.replace('##CINO##', $("#ciNo").val());
                            temp = temp.replace('##CIDATE##', $("#ciDate").val());
                            temp = temp.replace('##CIVALUE##', $("#ciValue").val());
                            temp = temp.replace('##INWORD##', dollarToWords($("#ciValue").val()));

                            var awbnos = "";
                            if ($("#mawbNo").val() != "") {
                                if (awbnos != "") {
                                    awbnos = awbnos + " & "
                                }
                                awbnos = "MAWB: " + $("#mawbNo").val();
                            }
                            /*if($("#hawbNo").val()!=""){
                             if(awbnos!=""){awbnos=awbnos+ " & "}
                             awbnos = "HAWB: " + $("#hawbNo").val();
                             }*/
                            if ($("#blNo").val() != "") {
                                if (awbnos != "") {
                                    awbnos = awbnos + " & "
                                }
                                awbnos = "BL: " + $("#blNo").val();
                            }
                            temp = temp.replace('##AWBNOS##', awbnos);
                            temp = temp.replace('##AWBDATE##', $("#awbOrBlDate").val());

                            //---------------end replace data-------------

                            $("#fileName").val('document_endorsement_' + zeroPad(sl) + '.doc');
                            $("#letterContent").val(temp);

                            document.getElementById("formLetterContent").submit();
                        } catch (err) {
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    }
                });
            });
        });
        //});
    }
});

function validateForLetter(){
    return true;
}


if (usertype == const_role_LC_Operation && actionId == 70){
    $(function () {

        var button = $('#btnUploadEndorsementLetter'), interval;
        var txtbox = $('#attachEndorsementLetter');

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
                    alertify.error('Invalid File Format.');
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
}
if (actionId > 70){
    $(function () {

        var button = $('#btnUploadEndorsementCopy'), interval;
        var txtbox = $('#attachEndorsementCopy');

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

        var button = $('#btnUploadEndorsementAdvice'), interval;
        var txtbox = $('#attachEndorsementAdvice');

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

        var button = $('#btnUploadOtherDoc'), interval;
        var txtbox = $('#attachEndorsementOtherDoc');

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
}

