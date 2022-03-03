/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val();
var u = $('#usertype').val();

var podata,comments,attach,lcinfo,pterms;

$(document).ready(function() {

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

            if (lcinfo["lcno"] == "" || lcinfo["lcno"] == null) {
                $("#addOpeningCharge_btn, #addInsuranceCharge_btn, #SendLCCopyToSourcing_btn").attr('disabled', true);
                // $("#addOpeningCharge_btn, #SendLCCopyToSourcing_btn").attr('disabled', true);
            }

            $.get('api/marine-insurance?action=3&po=' + poid, function (data) {
                if (data == 0) {
                    $("#addInsuranceCharge_btn").removeClass("btn-success").addClass("btn-danger");
                    //$("#SendLCCopyToSourcing_btn").attr('disabled',true);
                }
            });
            //alert('api/lc-opening-bank-charges?action=3&lc='+$("#lcno").val());
            $.get('api/lc-opening-bank-charges?action=3&lc=' + lcinfo["lcno"], function (data) {
                if (data == 0) {
                    $("#addOpeningCharge_btn").removeClass("btn-success").addClass("btn-danger");
                    //$("#SendLCCopyToSourcing_btn").attr('disabled',true);
                }
            });

            $("#btnBCSEXrate").hide();

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

            $('#pivalue').html(commaSeperatedFormat(podata['pivalue']) + ' ' + podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#hscode').html(podata['hscode']);
            $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' + podata['curname']);
            $('#origin').html(podata['origin'].replace(/,/g, ", "));
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);

            //alert(attach);
            attachmentLogScript(attach, '#usersAttachments');
            commentsLogScript(comments, '#buyersmsg', '');

            // LC Information
            if (lcinfo['addconfirmation'] == 1) {
                $('#addconfirmation').removeClass('fa-square-o').addClass('fa-check-square-o');
                if (lcinfo['confchargeatapp'] == 1) {
                    $('#confChargeBearer').html(' charge borne by <b>Applicant</b>');
                    if (lcinfo["conf_id"] > 0) {
                        $("#btnOpenAddConfCharge").html('<i class="icon wb-edit" aria-hidden="true"></i> Edit Confirmation Charge');
                    }
                    $("#addChargeButton").removeClass('hidden').show();
                } else {
                    $('#confChargeBearer').html(' charge borne by <b>Beneficiary</b>');
                    $("#addChargeButton").hide();
                }
            } else {
                $("#confChargeContainer").hide();
                $("#addChargeButton").hide();
            }
            //alert('sdds');

            $("#lcno").val(lcinfo["lcno"]);

            if (lcinfo["lca"] == 1) {
                $("#btnGenerate_BankLetterLCA").removeClass('hidden').show();
                $("#btnGenerate_LCAEnclosure").removeClass('hidden').show();
            } else {
                $("#btnGenerate_BankLetter").removeClass('hidden').show();
            }

            if (lcinfo['lcno'] != "") {
                $("#addOpeningCharge_btn").attr("href", "lc-opening-bank-charges?lc=" + lcinfo['lcno'] + "&po=" + poid + "&ref=" + $("#refId").val());

            }

            $("#serviceremark").val(lcinfo["serviceremark"]);

            if (lcinfo["lcissuedate"] != null) {
                $('#lcissuedate').datepicker('setDate', new Date(lcinfo["lcissuedate"]));
                $('#lcissuedate').datepicker('update');
            }
            //alert(lcinfo["daysofexpiry"]);
            if (lcinfo["daysofexpiry"] != null) {
                $('#daysofexpiry').datepicker('setDate', new Date(lcinfo["daysofexpiry"]));
                $('#daysofexpiry').datepicker('update');
            }
            if (lcinfo["lcvalue"] != null) {
                $("#lcvalue").val(commaSeperatedFormat(lcinfo["lcvalue"]));
            }
            $("#lcvalueCur").html(podata['curname']);
            $("#lcafno").val(lcinfo["lcafno"]);

            if (lcinfo["xeUSD"] != null) {
                $("#xr1").val(commaSeperatedFormat(lcinfo["xeUSD"]));
            }
            if (lcinfo["xeBDT"] != null) {
                $("#xr2").val(commaSeperatedFormat(lcinfo["xeBDT"]));
            }

            if (($("#xr1").val() != "") && (Number($("#xr1").val()))) {
                $("#LcValueInUSD").val(commaSeperatedFormat(lcinfo["lcvalue"] * parseToCurrency($("#xr1").val())));
            }

            if (($("#xr2").val() != "") && (Number($("#xr2").val()))) {
                $("#LcValueInBDT").val(commaSeperatedFormat(parseToCurrency($("#LcValueInUSD").val()) * parseToCurrency($("#xr2").val())));
            }

            if (lcinfo["attachLCORequest"] != null) {
                $("#attachLCOpenRequestOld").val(lcinfo["attachLCORequest"]);
                // $("#attachLCOpenRequest").val(lcinfo["attachLCORequest"]);
                $("#attachLCOpenRequestLink").html(attachmentLink(lcinfo["attachLCORequest"]));
            }

            if (lcinfo["attachBankReceiveCopy"] != null) {
                $("#attachBankReceiveCopyOld").val(lcinfo["attachBankReceiveCopy"]);
                $("#attachBankReceiveCopy").val(lcinfo["attachBankReceiveCopy"]);
            }

            if (lcinfo["attachLCOOther"] != null) {
                $("#attachLCOtherOld").val(lcinfo["attachLCOOther"]);
                $("#attachLCOther").val(lcinfo["attachLCOOther"]);
            }

            if (lcinfo["attachFinalLC"] != null) {
                $("#attachFinalLCCopyOld").val(lcinfo["attachFinalLC"]);
                $("#attachFinalLCCopy").val(lcinfo["attachFinalLC"]);
                $("#SendLCCopyToSourcing_btn").attr('disabled', false);
            }

            $("#paymentTermsText").html(lcinfo["paymentterms"]);
            $("#paymentTermsTextEditable").val(lcinfo["paymentterms"]);

            var ptrow = "";

            for (var i = 0; i < pterms.length; i++) {
                ptrow = "<tr><td class=\"text-center\">" + pterms[i]['percentage'] + "%</td>" +
                    "<td class=\"text-center\">" + pterms[i]['partname'] + "</td>" +
                    "<td class=\"text-center\">" + pterms[i]['dayofmaturity'] + " Days Maturity</td>" +
                    "<td class=\"text-left\">" + pterms[i]['maturityterms'] + "</td></tr>";
                $("#lcPaymentTermsTable").append(ptrow);
                NewPaymentTermsRow(pterms[i]['percentage'], pterms[i]['ccId'], pterms[i]['dayofmaturity'], pterms[i]['termsText']);
            }

            $("#lcissuerbank").change(function (e) {

                var issuerBank = $("#lcissuerbank").val();
                $('#lcissuerbankNew').val($("#lcissuerbank :selected").text());

                $.getJSON("api/company?action=4&type=118&bankid=" + issuerBank, function (list) {
                    $("#bankaccount").empty();
                    $("#bankaccount").select2({
                        data: list,
                        minimumResultsForSearch: Infinity,
                        placeholder: "Select a Bank account",
                        allowClear: false,
                        width: "100%"
                    });
                    if (lcinfo['bankaccount'] != '' && lcinfo['bankaccount'] != null) {
                        $("#bankaccount").val(lcinfo['bankaccount']).change();
                    } else {
                        $.get("api/company?action=6&bankid=" + issuerBank, function (data) {
                            $("#bankaccount").val(data.trim()).change();
                        });
                    }
                });
            });

            $.getJSON("api/company?action=4&type=118", function (list) {
                $("#lcissuerbank").select2({
                    data: list,
                    placeholder: "Select a Bank",
                    allowClear: false,
                    width: "100%"
                });
                $('#lcissuerbank').val(lcinfo['lcissuerbank']).change();
                $('#lcissuerbankOld').val($("#lcissuerbank :selected").text());
            });

            $("#insurance").change(function (e) {
                $('#insuranceNew').val($('#insurance :selected').text());
            });

            $.getJSON("api/company?action=4&type=119", function (list) {
                $("#insurance").select2({
                    data: list,
                    placeholder: "Select an insurance company",
                    allowClear: false,
                    width: "100%"
                });
                $('#insurance').val(lcinfo['insurance']).change();
                $('#insuranceOld').val($('#insurance :selected').text());
            });

            $.getJSON("api/category?action=4&id=32", function (list) {
                $("#lctype").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select LC Type",
                    allowClear: false,
                    width: "100%"
                });
                if (lcinfo['lctype'] != "" && lcinfo['lctype'] != null) {
                    $("#lctype").val(lcinfo['lctype']).change();
                } else {
                    $("#lctype").val(22).change();
                }
            });

            $.getJSON("api/category?action=4&id=33", function (list) {
                $("#bankservice").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select Performance",
                    allowClear: false,
                    width: "100%"
                });
                if (lcinfo['bankservice'] != "") {
                    $("#bankservice").val(lcinfo['bankservice']).change();
                }
            });

            $.getJSON("api/category?action=4&id=34", function (list) {
                $("#chargeType").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select Charge Type",
                    allowClear: false,
                    width: "100%"
                });
            });

            $.get("api/category?action=6&id=27&tag=1", function (data) {
                $("#producttype").html(data);
                $("#producttype").select2({
                    placeholder: "Select Product Type",
                    width: "100%"
                });
                $("#producttype").val(podata['producttype']).change();
                // alert(lcinfo['producttype']);
                if (lcinfo['producttype'] != "" && lcinfo['producttype'] != 'null' && lcinfo['producttype'] != null && lcinfo['producttype'] != 0) {
                    $("#producttype").val(lcinfo['producttype']).change();
                }
            });

            $('#lastdateofship').datepicker('setDate', new Date(lcinfo["lastdateofship"]));
            $('#lastdateofship').datepicker('update');
            $('#lastdateofshipOld').val($('#lastdateofship').val());
            //$('#lastdateofship').val( Date_toMDY( new Date( lcinfo['lastdateofship'] ) ) );

            $('#lcexpirydate').datepicker('setDate', new Date(lcinfo["lcexpirydate"]));
            $('#lcexpirydate').datepicker('update');
            $('#lcexpirydateOld').val($('#lcexpirydate').val());
            //$('#lcexpirydate').val( Date_toMDY( new Date( lcinfo['lcexpirydate'] ) ) );

        }
    });

    //$('#lastdateofship, #lcexpirydate').change(function(e){
    $('#lastdateofship, #lcexpirydate').datepicker().on('changeDate', function (ev) {
        if ($('#lastdateofshipOld').val() != "" && $('#lcexpirydateOld').val() != "") {
            $("#editedDateSaveOption").removeClass("hidden").show(1000);
        }
    });

    $("#btnDiscardEditedDate").click(function () {
        //e.preventDefault();

        $('#lastdateofship').val($('#lastdateofshipOld').val());
        $('#lastdateofship').datepicker('update');

        $('#lcexpirydate').val($('#lcexpirydateOld').val());
        $('#lcexpirydate').datepicker('update');

        $("#editedDateSaveOption").addClass("hidden").hide(10000);
    });

    $("#btnSaveEditedDate").click(function () {
        //e.preventDefault();
        alertify.confirm('Are you sure you want to change it?', function (e) {
            if (e) {
                $("#btnSaveEditedDate").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/lc-opening",
                    data: $('#editedDates :input').serialize() + "&lcno=" + $("#lcno").val() + "&userAction=5",
                    cache: false,
                    success: function (response) {
                        $("#btnSaveEditedDate").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                $('#lastdateofshipOld').val($('#lastdateofship').val());
                                $('#lcexpirydateOld').val($('#lcexpirydate').val());
                                $("#editedDateSaveOption").addClass("hidden").hide(1000);
                                alertify.success(res['message']);
                            } else {
                                alertify.error("FAILED to update!");
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
    });

    $("#btnOpenAddConfCharge").click(function (e) {

        if (lcinfo["conf_id"] == "" || lcinfo["conf_id"] == null) {
            $('#confChargeId').val("0");
            $('#lcno1').val($('#lcno').val());
            $('#lcno2').val($('#lcno').val());
        } else {
            $('#lcno1').val(lcinfo["lcno"]);
            $('#lcno2').val(lcinfo["lcno"]);
            $('#confChargeId').val(lcinfo["conf_id"]);
            $('#chargeType').val(lcinfo["conf_chargetype"]).change();
            $('#confChargeAmount').val(commaSeperatedFormat(lcinfo["conf_amount"]));
            if (lcinfo["conf_currency"] != null) {
                $('#currency').val(lcinfo["conf_currency"]).change();
            }
            $('#exchangeRate').val(lcinfo["conf_exchangerate"]);
            $('#vatOnConfCharge').val(commaSeperatedFormat(lcinfo["conf_vat"]));
            $('#otherCharge').val(commaSeperatedFormat(lcinfo["conf_othercharge"]));
            $('#totalCharge').val(commaSeperatedFormat(lcinfo["conf_total"]));

            if (lcinfo["attachConfChargeAdv"] != null) {
                $("#attachConfChargeAdviceOld").val(lcinfo["attachConfChargeAdv"]);
                $("#attachConfChargeAdvice").val(lcinfo["attachConfChargeAdv"]);
            }
        }
    });

    // Submit
    $("#SaveLC_btn").click(function (e) {
        $('#userAction').val('1');
        e.preventDefault();
        if (validate() === true) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/lc-opening",
                data: $('#lcrequest-form').serialize(),
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    // alert(response);
                    try {
                        var res = JSON.parse(response);

                        if (res['status'] == 1) {
                            alertify.success(res['message']);
                            window.location.reload();
                        } else {
                            alertify.error("FAILED!");
                            return false;
                        }
                    } catch (e) {
                        alertify.error(response + ' Failed to process the request.', 20);
                        return false;
                    }
                }
            });
        } else {
            return false;
        }
    });

    //COVER NOTE SUBMIT
    $("#btnCoverNote").click(function (e) {
        if ($("#xr2").val() > 0) {
            $('#userAction').val('6');
            e.preventDefault();
            $("#btnCoverNote").prop('disabled', true);
            alertify.confirm('Are you sure you want submit?', function () {
                $.ajax({
                    type: "POST",
                    url: "api/lc-opening",
                    data: $('#lcrequest-form').serialize(),
                    cache: false,
                    success: function (response) {
                        $("#btnCoverNote").prop('disabled', false);
                        // alert(response);
                        try {
                            var res = JSON.parse(response);

                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    }
                });

            });
        } else {
            alertify.error('Please provide BCS rate', 5);
            return false;
        }
    });

    $("#SendLCCopyToSourcing_btn").click(function (e) {
        $('#userAction').val('2');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want to Send Final LC Copy?', function (e) {
                if (e) {
                    $("#SendLCCopyToSourcing_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-opening",
                        data: $('#form-finallccopy').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#SendLCCopyToSourcing_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
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
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });

    // Confirmation Charge Submit
    $("#btnAddConfChargeSubmit").click(function (e) {
        //$('#userAction').val('3');
        e.preventDefault();
        if (validate() === true) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/lc-opening",
                data: $('#form-ConfirmationCharge').serialize(),
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    //alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            //resetConfChargeForm();
                            $('#form-ConfirmationCharge').modal('hide');
                            alertify.success(res['message']);
                        } else {
                            alertify.error("FAILED!");
                            return false;
                        }
                    } catch (e) {
                        alertify.error(response + ' Failed to process the request.', 20);
                        return false;
                    }
                }
            });
        } else {
            return false;
        }
    });

    $("#producttype").change(function (e) {

        //commented on 15.12.21 because everything auto load popups a warning
        /*if ($("#lcissuedate").val() == "") {
            $("#lcissuedate").focus();
            alertify.warning("Please provide LC Date to calculate expiry!");
            return false;
        }*/
        if ($("#lcissuedate").val() != '' && lcinfo['daysofexpiry'] == null) {
            var selected = $(this).find('option:selected');
            var tag = selected.data('tag');

            var d = new Date($("#lcissuedate").val());
            d.setDate(d.getDate() + tag);
            $('#daysofexpiry').datepicker('setDate', d);
            $('#daysofexpiry').datepicker('update');
        }
    });

    $('#lcissuedate').datepicker().on('changeDate', function (ev) {
        if ($("#producttype").val() == "") {
            alertify.error("Please select Product type!");
            return false;
        }
        var selected = $("#producttype").find('option:selected');
        var tag = selected.data('tag');

        var d = new Date($("#lcissuedate").val());
        d.setDate(d.getDate() + tag);
        $('#daysofexpiry').datepicker('setDate', d);
        $('#daysofexpiry').datepicker('update');
    });

    // formating comma seperated
    $("#confChargeAmount").blur(function (e) {
        $("#confChargeAmount").val(commaSeperatedFormat($("#confChargeAmount").val()));
    });
    $("#otherCharge").blur(function (e) {
        $("#otherCharge").val(commaSeperatedFormat($("#otherCharge").val()));
    });

    // calculation
    $("#exchangeRate, #otherCharge").keyup(function (e) {

        totalCalculation();

    });

    $("#xr1").keyup(function (e) {
        if (($("#xr1").val() != "") && (Number($("#xr1").val()))) {
            $("#LcValueInUSD").val(commaSeperatedFormat(lcinfo["lcvalue"] * parseToCurrency($("#xr1").val())));
        }
    });

    $("#xr2").keyup(function (e) {
        if (($("#xr2").val() != "") && (Number($("#xr2").val()))) {
            $("#LcValueInBDT").val(commaSeperatedFormat(parseToCurrency($("#LcValueInUSD").val()) * parseToCurrency($("#xr2").val())));
        }
    });

    $("#approverLevel").select2({
        placeholder: "select approver"
    });

    $("#btnEditTerms").click(function (e) {
        e.preventDefault();
        $("#lcPaymentTermsTable").addClass("hidden");
        $("#btnEditTerms").addClass("hidden");
        $("#paymentTermsText").addClass("hidden");

        $("#lcPaymentTermsTableEditable").removeClass("hidden").show();
        $("#btnSaveTerms").removeClass("hidden").show();
        $("#btnCancelEditTerms").removeClass("hidden").show();
        $("#paymentTermsTextEditable").parent().removeClass("hidden").show();
    });

    $("#btnCancelEditTerms").click(function (e) {
        e.preventDefault();
        $("#lcPaymentTermsTable").removeClass("hidden").show();
        $("#btnEditTerms").removeClass("hidden").show();
        $("#paymentTermsText").removeClass("hidden").show();

        $("#lcPaymentTermsTableEditable").addClass("hidden");
        $("#btnSaveTerms").addClass("hidden");
        $("#btnCancelEditTerms").addClass("hidden");
        $("#paymentTermsTextEditable").parent().addClass("hidden");
    });

    $("#btnSaveTerms").click(function (e) {
        e.preventDefault();
        //alert($('#divTermsEdited :input').serialize());
        if (validate_terms() === true) {
            alertify.confirm('Are you sure you want to change it?', function (e) {
                if (e) {
                    $("#btnSaveTerms").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-opening",
                        data: $('#divTermsEdited :input').serialize() + "&pono=" + poid + "&userAction=4",
                        cache: false,
                        success: function (response) {
                            $("#btnSaveTerms").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                } else {
                                    alertify.error("FAILED to update!");
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
        }
    });

    $("#btnGenerate_LCAEnclosure").click(function (e) {

        e.preventDefault();

        if (validateForLCALetter()) {
            $.get("api/lc-opening?action=4&bank=" + $("#lcissuerbank").val() + "&po=" + $("#pono").val(), function (data) {
                bankData = JSON.parse(data);
                $("#btnGenerate_LCAEnclosure").prop('disabled', true);
                $.ajax({
                    url: "application/templates/letter_template/temp_lca_terms_condition_letter.html",
                    cache: false,
                    global: false,
                    success: function (response) {
                        $("#btnGenerate_LCAEnclosure").prop('disabled', false);
                        // alert(response);
                        try {
                            var temp = response;

                            //---------------replace data-----------------
                            temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                            temp = temp.replace(/##LCVALUE##/g, $("#lcvalue").val());
                            temp = temp.replace('##HSCODE##', $("#hscode").html());
                            temp = temp.replace(/##CUR##/g, $("#lcvalueCur").html());
                            temp = temp.replace('##GOODSDESC##', $("#lcdesc").html());
                            temp = temp.replace('##PO##', originalPO($("#pono").val()));
                            temp = temp.replace('##SHIPPORT##', $("#shipport").html());
                            temp = temp.replace('##ESTSHIPDATE##', (Date_toDetailFormat(new Date(lcinfo['lastdateofship']))));
                            temp = temp.replace('##LCEXPIRYDATE##', (Date_toDetailFormat(new Date(lcinfo['lcexpirydate']))));
                            temp = temp.replace('##LCTERMS##', $("#paymentTermsText").html());
                            temp = temp.replace('##LCANUM##', $("#lcafno").val());
                            temp = temp.replace('##LCABANK##', $("#lcissuerbank :selected").text());
                            temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                            //---------------end replace data-------------

                            $("#fileName").val('LCA_Enclosure_A_' + originalPO(poid) + '.doc');
                            $("#letterContent").val(temp);

                            document.getElementById("formLetterContent").submit();
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    }
                });

            });
        }

    });

    $("#btnGenerate_BankLetter").click(function (e) {

        e.preventDefault();

        if (validateForLetter()) {
            $.get("api/lc-opening?action=4&bank=" + $("#lcissuerbank").val() + "&po=" + $("#pono").val(), function (data) {

                bankData = JSON.parse(data);

                //--- getting letter serial number based on PO, shipment and bank ---------
                $.get('api/lib-helper?req=1&po=' + $("#pono").val() + '&ship=0&orgtype=bank&orgid=' + $("#lcissuerbank").val(), function (sl) {

                    //------- generating letter reference number ------------------------
                    if (sl != "0") {
                        var d = new Date();
                        letterRef = docref_bank_instruction_LC_letter_ref + d.getFullYear() + "/" + zeroPad(sl);
                    } else {
                        letterRef = docref_bank_instruction_LC_letter_ref + d.getFullYear() + "/" + zeroPad(1);
                    }
                    //------- end generating letter reference number ------------------------
                    $("#btnGenerate_BankLetter").prop('disabled', true);
                    $.ajax({
                        url: "application/templates/letter_template/temp_bank_instruction_letter_LC.html",
                        cache: false,
                        global: false,
                        success: function (response) {
                            $("#btnGenerate_BankLetter").prop('disabled', false);
                            // alert(response);
                            try {
                                var temp = response;

                                //---------------replace data-----------------
                                temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                                temp = temp.replace('##REF##', letterRef);
                                temp = temp.replace('##BANKNAME##', bankData["name"]);
                                temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                                temp = temp.replace(/##CONAME##/g, bankData["coname"]);
                                temp = temp.replace('##COADDRESS##', bankData["coaddress"].replace(/\n/g, "<br />"));
                                temp = temp.replace('##PINO##', $("#pinum").html());
                                temp = temp.replace('##PIDATE##', Date_toDetailFormat(new Date($("#pidate").html())));
                                temp = temp.replace('##CUR##', podata['curname']);
                                temp = temp.replace(/##LCVALUE##/g, commaSeperatedFormat(lcinfo["lcvalue"]));
                                temp = temp.replace('##INSURANCECO##', $("#insurance").find('option:selected').text());
                                temp = temp.replace('##ACCNO##', $("#bankaccount").find('option:selected').text());
                                //---------------end replace data-------------

                                $("#fileName").val('bank_instruction_LC_' + poid + '.doc');
                                $("#letterContent").val(temp);

                                document.getElementById("formLetterContent").submit();
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });
                });
            });
        }

    });

    $("#btnGenerate_BankLetterLCA").click(function (e) {

        e.preventDefault();

        if (validateForLetter()) {
            $.get("api/lc-opening?action=4&bank=" + $("#lcissuerbank").val() + "&po=" + $("#pono").val(), function (data) {

                bankData = JSON.parse(data);

                //--- getting letter serial number based on PO, shipment and bank ---------
                $.get('api/lib-helper?req=1&po=' + $("#pono").val() + '&ship=0&orgtype=bank&orgid=' + $("#lcissuerbank").val(), function (sl) {

                    //------- generating letter reference number ------------------------
                    if (sl != "0") {
                        var d = new Date();
                        letterRef = docref_bank_instruction_LCA_letter_ref + d.getFullYear() + "/" + zeroPad(sl);
                    } else {
                        letterRef = docref_bank_instruction_LCA_letter_ref + d.getFullYear() + "/" + zeroPad(1);
                    }
                    //------- end generating letter reference number ------------------------
                    $("#btnGenerate_BankLetterLCA").prop('disabled', true);
                    $.ajax({
                        url: "application/templates/letter_template/temp_bank_instruction_letter_LCA.html",
                        cache: false,
                        global: false,
                        success: function (response) {
                            $("#btnGenerate_BankLetterLCA").prop('disabled', false);
                            //alert(response);
                            try {
                                var temp = response;

                                //---------------replace data-----------------
                                temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                                temp = temp.replace('##REF##', letterRef);
                                temp = temp.replace('##BANKNAME##', bankData["name"]);
                                temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                                temp = temp.replace(/##CONAME##/g, bankData["coname"]);
                                temp = temp.replace('##COADDRESS##', bankData["coaddress"].replace(/\n/g, "<br />"));
                                temp = temp.replace('##PINO##', $("#pinum").html());
                                temp = temp.replace('##PIDATE##', Date_toDetailFormat(new Date($("#pidate").html())));
                                temp = temp.replace('##CUR##', podata['curname']);
                                temp = temp.replace(/##LCAF##/g, $("#lcafno").val());
                                temp = temp.replace(/##LCVALUE##/g, commaSeperatedFormat(lcinfo["lcvalue"]));
                                temp = temp.replace('##ACCNO##', $("#bankaccount").find('option:selected').text());
                                //---------------end replace data-------------

                                $("#fileName").val('bank_instruction_LCA_' + poid + '.doc');
                                $("#letterContent").val(temp);

                                document.getElementById("formLetterContent").submit();
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });
                });
            });
        }

    });

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_COVER_NOTE_SUBMITTED_BY_IC, function (r) {
        // console.log(r)
        if (r == 1) {
            // alert(1);
            $("#btnCoverNote").hide();
            $("#btnViewIC").show();
            // $("#btnViewIC").removeAttr("disabled");
            $("#addInsuranceCharge_btn").attr('disabled', false);
            $("#addInsuranceCharge_btn").attr("href", "marine-insurance?po=" + poid + "&ref=" + $("#refId").val());
        } else {
            $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_COVER_NOTE_REQUESTED_BY_TFO, function (r) {
                // console.log(r)
                if (r == 1) {
                    // alert(2);
                    $("#btnCoverNote").attr('disabled', true)
                    $("#btnViewIC").hide();
                    $.get('api/cn-request?action=4&poid=' + poid, function (cnInfo) {
                        var cni = JSON.parse(cnInfo);
                        if (cni["cn_no"] != "") {
                            $("#addInsuranceCharge_btn").attr('disabled', false);
                            $("#addInsuranceCharge_btn").attr("href", "marine-insurance?po=" + poid + "&ref=" + $("#refId").val());
                        } else {
                            $("#addInsuranceCharge_btn").attr('disabled', true);
                        }
                    });
                } else {
                    $("#btnViewIC").hide();
                    // $("#addInsuranceCharge_btn").attr('disabled',true);
                }
            });
        }
    });

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_FINAL_LC_COPY_SENT_TO_GP, function (r) {
        // console.log(r)
        if (r == 1) {
            $("#btnBCSEXrate").show();
        }
    });

    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_BCS_EX_SENT_TO_FSO, function (r) {
        // console.log(r)
        if (r == 1) {
            // alert(1);
            $("#btnBCSEXrate").hide();
            $("#SendLCCopyToSourcing_btn").attr('disabled', false)
            // $("#btnViewIC").removeAttr("disabled");
        }
    });

    //LC request status
    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_FINAL_LC_REQUEST_SENT_TO_BANK, function (r) {
        if (r == 1) {
            $("#draftLC").attr("disabled", true);
            $("#finalLC").attr("disabled", true);
            $("#finalLC").attr("checked", true).parent().addClass("checked");
            $("#btnLCRequestToBank").attr('disabled', true);
            $("#btnLCRequestToBank").attr('title', 'Final LC request sent');
        } else {
            $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_DRAFT_LC_REQUEST_SENT_TO_BANK, function (r) {
                if (r == 1) {
                    //LC request status
                    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_BUYER_SUPPLIER_FEEDBACK_ACCEPTED, function (r) {
                        if (r == 1) {
                            $("#draftLC").attr("checked", true).parent().addClass("checked");
                            $("#draftLC").attr("disabled", true);
                            $("#finalLC").attr("checked", true).parent().addClass("checked");
                            /*$("#finalLC").attr("disabled",false);
                            $("#btnLCRequestToBank").attr('disabled',false);*/
                        } else {
                            $("#draftLC").attr("disabled", true);
                            $("#finalLC").attr("disabled", true);
                            $("#btnLCRequestToBank").attr('disabled', true);
                            $("#btnLCRequestToBank").attr('title', 'Draft LC request sent');
                        }
                    });
                }
            });
        }
    });


    //all data show
    $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_COVER_NOTE_SUBMITTED_BY_IC, function (r) {
        if (r == 1) {
            $.get('api/ici-interface?action=2&id=' + poid, function (data) {

                if (!$.trim(data)) {
                    $(".panel-body").empty();
                    $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
                } else {

                    var row = JSON.parse(data);
                    podata = row[0][0];
                    attach = row[1];
                    if (podata != null) {
                        // CN info
                        $('#cn_number').val(podata['cn_no']);
                        $('#cn_date').val(podata['cn_date']);
                        $('#pay_order_amount').val(commaSeperatedFormat(podata['pay_order_amount']));
                        $('#pay_order_charge').val(commaSeperatedFormat(podata['pay_order_charge']));

                        //alert(attach.length);
                        attachmentLogScript(attach, '#CNAttachments');

                        $.get('api/purchaseorder?action=4&po=' + poid + '&step=' + ACTION_COVER_NOTE_ACCEPTED_BY_TFO, function (r) {
                            if (r == 1) {
                                // alert(1);
                                $("#btnCnRequest").attr('disabled', true);
                                $("#btnRejectCN").attr('disabled', true);
                                $("#remarks").attr('disabled', true);
                                // $("#btnViewIC").removeAttr("disabled");
                            }
                        });
                    }
                }
            });
        }
    });
    //CN REQUEST SUBMIT

    $("#btnCnRequest").click(function (e) {
        $('#userAction1').val('7');
        e.preventDefault();
        $("#btnCnRequest").prop('disabled', true);
        $.ajax({
            type: "POST",
            url: "api/lc-opening",
            data: $('#form-cn-request').serialize(),
            cache: false,
            success: function (response) {
                $("#btnCnRequest").prop('disabled', false);
                // alert(response);
                try {
                    var res = JSON.parse(response);

                    if (res['status'] == 1) {
                        alertify.success(res['message']);

                        window.location.href = _dashboardURL;
                    } else {
                        alertify.error("FAILED!");
                        return false;
                    }
                } catch (e) {
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            }
        });
    });

    //CN REJECT BUTTON
    $("#btnRejectCN").click(function (e) {
        // alert('clicked');
        $('#userAction1').val('8');
        e.preventDefault();
        if (validateCN() === true) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/lc-opening",
                    data: $('#form-cn-request').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res["status"] == 1) {

                                alertify.success(res['message']);
                                location.reload(true);
                            } else {
                                //$("#SendPO_btn").show();
                                alertify.error(res['message']);
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.');
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error);
                    }
                });
            } else {

            }
        } else {
            return false;
        }
    });

    //LC SENT TO BANK
    $("#btnLCRequestToBank").click(function (e) {
        $('#userAction').val('9');
        e.preventDefault();
        if (validateAttach() === true) {
            alertify.confirm('Are you sure you want submit?', function () {
                var button = e.target;
                button.disabled = true;
                $.ajax({
                    type: "POST",
                    url: "api/lc-opening",
                    data: $('#lcrequest-form').serialize(),
                    cache: false,
                    success: function (response) {
                        button.disabled = false;
                        // alert(response);
                        try {
                            var res = JSON.parse(response);

                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    }
                });
            });
        } else {
            return false;
        }
    });

    //BCS EX RATE SUBMIT TO FSO
    $("#btnBCSEXrate").click(function (e) {
        $('#userAction').val('10');
        e.preventDefault();
        alertify.confirm('Are you sure you want submit?', function () {
            $("#btnBCSEXrate").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/lc-opening",
                data: $('#lcrequest-form').serialize(),
                cache: false,
                success: function (response) {
                    $("#btnBCSEXrate").prop('disabled', false);
                    // alert(response);
                    try {
                        var res = JSON.parse(response);

                        if (res['status'] == 1) {
                            alertify.success(res['message']);

                            window.location.href = _dashboardURL;
                        } else {
                            alertify.error("FAILED!");
                            return false;
                        }
                    } catch (e) {
                        alertify.error(response + ' Failed to process the request.', 20);
                        return false;
                    }
                }
            });

        });
    });

});

function validateAttach() {

    /*if ($("input[name='lcRequestType']").filter(":checked").length < 1){
        alertify.error("Please select option for LC request");
        return false;
    }*/

    var lcReqType = $('input:radio[name=lcRequestType]:checked').val();

    if(lcReqType==undefined)
    {
        alertify.error("Please select option for LC request!");
        return false;
    }

    if(lcReqType=="1"){
        if ($("#attachLCOpenRequest").val() == "") {
            $("#attachLCOpenRequest").focus();
            alertify.error("Attach LC Opening Request letter");
            return false;
        }
    }


    return true;
}

function validateCN() {
    if ($("#remarks").val() == "") {
        $("#remarks").focus();
        alertify.error("Specify the Reason");
        return false;
    }
    return true;
}

function ResetForm() {
    $('#remarks').val("");

}
function validate_terms(){
    return true;
}

$("#lcissuedate, #daysofexpiry, #lastdateofship, #lcexpirydate").datepicker({
    todayHighlight: true,
    autoclose: true
});

function totalCalculation(){
    
    var charge = parseToCurrency($("#confChargeAmount").val()),
        xrate = parseToCurrency($("#exchangeRate").val()),
        other = parseToCurrency($("#otherCharge").val());
    
    var vat = (charge * xrate * 15) / 100;
    $("#vatOnConfCharge").val(commaSeperatedFormat(vat));
    
    var total = charge + vat + other;
    $("#totalCharge").val(commaSeperatedFormat(total));

}


function validate(){
    
    if( $('#userAction').val()==1){
        
        if($("#lctype").val()==""){
            alertify.error("Please select LC type!");
            $("#lctype").select2('open');
    		return false;
        }
        if($("#producttype").val()==""){
            alertify.error("Please select product type!");
            $("#producttype").select2('open');
            return false;
        }
        if($("#bankaccount").val()==""){
            alertify.error("Please select Bank account!");
            $("#bankaccount").select2('open');
            return false;
        }
        
        /*if($("#bankservice").find('option:selected').text()!="Good"){
            if($("#serviceremark").val()==""){
                $("#serviceremark").focus();
                alertify.error("You must write remarks for this performance.");
                return false;
            }
        }*/

    
    } else if($('#userAction').val()==2){
        if($("#attachFinalLCCopy").val()==""){
            $("#attachFinalLCCopy").focus();
            alertify.error("Please attach final LC copy!");
    		return false;
        } else {
            if(!validAttachment($("#attachFinalLCCopy").val())){
                alertify.error('Invalid File Format.');
                return false;
            }
    	}
    } else if($('#userAction').val()==3){
        if($("#lcno1").val()==""){
            $("#lcno1").focus();
            alertify.error("Please provide LC number!");
    		return false;
        }
        if($("#chargeType").val()==""){
            $("#chargeType").focus();
            alertify.error("Please select Charge Type!");
    		return false;
        }
        if($("#confChargeAmount").val()==""){
            $("#confChargeAmount").focus();
            alertify.error('"Confirmation Charge" is required field!');
    		return false;
        }        
        if($("#currency").val()==""){
            $("#currency").focus();
            alertify.error("Please select Currency!");
    		return false;
        }
        if($("#exchangeRate").val()==""){
            $("#exchangeRate").focus();
            alertify.error("Please provide Exchange Rate!");
    		return false;
        }
        if($("#vatOnConfCharge").val()==""){
            $("#vatOnConfCharge").focus();
            alertify.error('"VAT" is required field!');
    		return false;
        }
        if($("#totalCharge").val()==""){
            $("#totalCharge").focus();
            alertify.error('"Total Charge" is required field!');
    		return false;
        }
        if($("#attachChargeAdvice").val()==""){
            $("#attachChargeAdvice").focus();
            alertify.error('You must attach the "Charge Advice Document"!');
    		return false;
        } else {
            if(!validAttachment($("#attachChargeAdvice").val())){
                alertify.error('Invalid File Format.');
                return false;
            }
    	}
    }
    return true;
}

function resetConfChargeForm(){
    $('#form-ConfirmationCharge')[0].reset();
}

$(function () {

    var button = $('#btnUploadConfChargeAdvice'), interval;
    var txtbox = $('#attachConfChargeAdvice');

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

    var button = $('#btnUploadLCOpenRequest'), interval;
    var txtbox = $('#attachLCOpenRequest');

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

    var button = $('#btnUploadBankReceiveCopy'), interval;
    var txtbox = $('#attachBankReceiveCopy');

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

    var button = $('#btnUploadLCOther'), interval;
    var txtbox = $('#attachLCOther');

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

    var button = $('#btnUploadFinalLCCopy'), interval;
    var txtbox = $('#attachFinalLCCopy');

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

function ResetConfChargeForm(){
    
}



function validateForLetter(){
    if($("#bankaccount").val()==""){
        $("#bankaccount").focus();
        alertify.error("Please select a bank account");
        return false;
    }
    return true;
}

function validateForLCALetter(){
    return true;
}

$(function() {
    $('#printTerms').click(function(e) {
        e.preventDefault();
        
        // preparing format
        
        var newstr = $("#printFormat").html();
        
        newstr = newstr.replace('##lcdesc1##',(lcinfo['lcdesc']));
        newstr = newstr.replace('##lcvalue##',(commaSeperatedFormat(lcinfo['lcvalue'])+" "+podata['curname']));
        
        if(podata['shipmode']=='sea'){
            newstr = newstr.replace('##hscsea1##',(podata['hscode']));
            newstr = newstr.replace('##hscair1##','');
        } else {
            newstr = newstr.replace('##hscsea1##','');
            newstr = newstr.replace('##hscair1##',(podata['hscode']));
        }
        
        var defaultCheckIcon = 'fa-square-o';
        
        if(lcinfo['cocorigin']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##cocorigin##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['iplbltolcbank']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##iplbltolcbank##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['delivcertify']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##delivcertify##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['qualitycertify']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##qualitycertify##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['qualitycertify1']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##qualitycertify1##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['advshipdoc']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##advshipdoc##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['advshipdocwithbl']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##advshipdocwithbl##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['addconfirmation']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##addconfirmation##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['preshipinspection']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##preshipinspection##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['transshipment']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##transshipment##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['partship']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##partship##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['confchargeatapp']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }

        newstr = newstr.replace('##ircno##',(lcinfo['ircno']));
        newstr = newstr.replace('##imppermitno##',(lcinfo['imppermitno']));
        newstr = newstr.replace('##tinno##',(lcinfo['tinno']));
        newstr = newstr.replace('##lcno1##',(lcinfo['lcno']));
        newstr = newstr.replace('##vatregno##',(lcinfo['vatregno']));
        newstr = newstr.replace('##pono1##',originalPO(podata['poid']));
        newstr = newstr.replace('##shipmode2##',(podata['shipmode'].toUpperCase()));
        newstr = newstr.replace('##shipport1##',(podata['shipport']));
        newstr = newstr.replace('##origin1##',(podata['origin'].replace(/,/g,", ")));
        newstr = newstr.replace('##pono2##',originalPO(podata['poid']));
        newstr = newstr.replace('##customername##',(lcinfo['customername']));
        newstr = newstr.replace('##customeraddress##',(lcinfo['customeraddress']));
        newstr = newstr.replace('##tinno1##',(lcinfo['tinno']));
        newstr = newstr.replace('##lcno##',(lcinfo['lcno']));
        if(podata['shipmode']=='sea'){
            newstr = newstr.replace('##destination##',('Chittagong Sea Port'));
        }else if(podata['shipmode']=='air'){
            newstr = newstr.replace('##destination##',('Dhaka Air Port'));
        }
        newstr = newstr.replace('##supplier1##',(podata['supname']));
        newstr = newstr.replace('##origin2##',(podata['origin'].replace(/,/g,", ")));
        newstr = newstr.replace('##lastdateofship##',(Date_toMDY(new Date(lcinfo['lastdateofship']))));
        newstr = newstr.replace('##lcexpirydate##',(Date_toMDY(new Date(lcinfo['lcexpirydate']))));
        newstr = newstr.replace('##lcbankaddress1##',(HTMLDecode(podata['lcbankaddress'])));
        newstr = newstr.replace('##negobank1##',(HTMLDecode(podata['negobank'])));
        newstr = newstr.replace('##selectPaymentTerm##',(lcinfo['paymentterms']));
        //newstr = newstr.replace('##selectPaymentTerm##','<table width="100%" border="1">'+$("#lcPaymentTermsTable").html()+'</table>');
        
        newstr = newstr.replace('##advBank##',(lcinfo['advBank']));
        newstr = newstr.replace('##contactPSI##',(lcinfo['contactPSI']));
        newstr = newstr.replace('##psiClauseA##',(lcinfo['psiClauseA']));
        newstr = newstr.replace('##psiClauseB##',(lcinfo['psiClauseB']));
        newstr = newstr.replace('##insNotification1##',(lcinfo['insNotification1']));
        newstr = newstr.replace('##insNotification2##',(lcinfo['insNotification2']));
        newstr = newstr.replace('##insNotification3##',(lcinfo['insNotification3']));
        newstr = newstr.replace('##forAirShipment1##',(lcinfo['forAirShipment1']));
        newstr = newstr.replace('##forSeaShipment1##',(lcinfo['forSeaShipment1']));
        newstr = newstr.replace('##forAirShipment2##',(lcinfo['forAirShipment2']));
        newstr = newstr.replace('##forSeaShipment2##',(lcinfo['forSeaShipment2']));
        newstr = newstr.replace('##shippingRemarks##',(lcinfo['shippingRemarks']));
        /*
        
        $("#printFormat").html(newstr);
        
        $("#printFormat").removeClass("hidden");
        //alert('sfsf');
        //$("#printFormat").printThis();
        
        $("#printFormat").printThis({
            debug: false,               //* show the iframe for debugging
            importCSS: true,            //* import page CSS
            importStyle: true,         //* import style tags
            printContainer: false,       //* grab outer container as well as the contents of the selector
            //loadCSS: "path/to/my.css",  //* path to additional css file - us an array [] for multiple
            pageTitle: "",              //* add title to print page
            removeInline: false,        //* remove all inline styles from print elements
            //printDelay: 333,            //* variable print delay
            header: null,               //* prefix to html
            formValues: true            //* preserve input/form values
        });
        
        $("#printFormat").addClass("hidden");*/
        
        $("#printFormat").html(newstr);
        
        //alert('sfsfd');
        //Print ele4 with custom options
        $("#printFormat").removeClass("hidden");
        $("#printFormat").print({
            //Use Global styles
            globalStyles : true,
            //Add link with attrbute media=print
            mediaPrint : false,
            //Custom stylesheet
            stylesheet : "assets/css/print-form.css",
            //Print in a hidden iframe
            iframe : false,
            //Don't print this
            noPrintSelector : ".avoid-this",
            //Add this at top
            //prepend : "Hello World!!!<br/>",
            //Add this on bottom
            //append : "<br/>Buh Bye!"
        });
        $("#printFormat").addClass("hidden");
    });
    // Fork https://github.com/sathvikp/ for the full list of options
});

// Generate new payment terms
function NewPaymentTermsRow(pp, cc, dd, tt){

    var id = $('div#lcPaymentTermsTableEditable').children().length+1;

    $('div#lcPaymentTermsTableEditable').append(
        $('<div>').attr({'class':'form-group termsRow', 'id':'lcPaymentTermsRow'+id}
        ).append(
            $('<div>').attr('class','col-sm-3').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppPercentage[]', 'id':'ppPercentage_'+id.toString()}).val(pp)
                ).append(
                    $('<span>').attr('class','input-group-addon').html('%')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppPartName[]', 'id':'ppPartName_'+id.toString()}).append(
                    $('<option>').attr('value','6').html('Sight')
                ).append(
                    $('<option>').attr('value','7').html('CAC')
                ).append(
                    $('<option>').attr('value','8').html('FAC')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-3').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppMaturityDay[]', 'id':'ppDay_'+id.toString()}).val(dd)
                ).append(
                    $('<span>').attr('class','input-group-addon').html('Days Maturity')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-3').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppMaturityTerm[]', 'id':'ppMaturityTerm_'+id.toString()}).append(
                    $('<option>').attr('value','').html('')
                ).append(
                    $('<option>').attr('value','9').html('Air Way Bill Date')
                ).append(
                    $('<option>').attr('value','10').html('Bill of Lading')
                ).append(
                    $('<option>').attr('value','11').html('LC Issuence')
                ).append(
                    $('<option>').attr('value','12').html('Shipment Date')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<input>').attr({'type':'button', 'class':'btn btn-warning pull-right minusClauseRow','id':'delTermsRow_'+id.toString(), 'onclick':'removeClause(this.id)','value':'X'})
            )
        )
    );
    
    $('#ppPartName_'+id.toString()).selectpicker('refresh');
    $('#ppPartName_'+id.toString()).val(cc).change();
    
    $('#ppMaturityTerm_'+id.toString()).selectpicker('refresh');
    $('#ppMaturityTerm_'+id.toString()).val(tt).change();
    
}

function removeClause(obj){
    //alert(obj);
    $("#"+obj).closest("div.termsRow").remove();
}