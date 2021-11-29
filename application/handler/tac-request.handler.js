/**
 * Created by HasanMasud on 29-Sep-18.
 */

var poid = $('#pono').val();
var ciNo = $('#ciNo1').val();
var pendingCount = 0;
$(document).ready(function() {

    if ($('#pono').val() == "") {
        $("#tac-request-form").hide();
        $("#form-temp").removeClass('hidden');

        //alert("api/tac-request?action=1&supplier="+$("#loggedSupplier").val());
        $.getJSON("api/tac-request?action=1&supplier=" + $("#loggedSupplier").val(), function (list) {
            // alert(list);
            $("#poList").select2({
                data: list,
                placeholder: "Search PO Number",
                allowClear: true,
                width: "100%"
            });
        });

        $("#poList").change(function (e) {
            // $.getJSON("api/shipment?action=11&po=" + $("#poList").val(), function (list) {
            $.getJSON("api/tac-request?action=4&po=" + $("#poList").val(), function (list) {
                $("#ciList").empty();
                $("#ciList").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "select CI number",
                    allowClear: false,
                    width: "100%"
                });
            });
        });

        $("#ciList").change(function (e) {
            $.get("api/tac-request?action=15&poNo=" + $("#poList").val() + "&shipNo=" + $("#ciList").val(), function (data) {
                //alert(data);
                pendingCount = data;
            });
            /*********GET PAYMENT HISTORY**********/
            paymentHistory($("#ciList option:selected").text());
        });

        $("#goTAC_btn").click(function (e) {
            e.preventDefault();
            if(validate()) {
                window.location.href = _dashboardURL + "tac-request?po=" + $("#poList").val() + "&ship=" + $("#ciList").val() + "&ci=" + $("#ciList").find('option:selected').text();
            }else {
                return false;
            }
        });

    } else {
        //alert("api/tac-request?action=3&po=" + poid+"&shipNo=" + $("#shipno").val());
        $.get("api/tac-request?action=3&po=" + poid+"&shipNo=" + $("#shipno").val(), function (data) {
            //alert(data);
            if (!$.trim(data)) {
                $("#form_error").show();
                $("#form_error").html("No data found!");
            } else {

                //alert('hlkh');
                var row = JSON.parse(data);
                //alert(row);
                //TAC Request
                $('#supplierName').val(row["supplierName"]);
                $('#po').val(row["pono"]);
                $('#LcNo').val(row["lcNo"]);
                $('#ciNo').val(row["ciNo"]);
                $("#ciValue").val(commaSeperatedFormat(row['ciAmount']));
                $('#poCurrName').html(row["currencyName"]);
                $('#docCurrName').html(row["currencyName"]);
                $('#ciCurrName').html(row["currencyName"]);
                $('#podesc').val(row["podesc"]);
                $('#poValue').val(commaSeperatedFormat(row["povalue"]));


                //CFAC - Request
                $('#supplierName_1').val(row["supplierName"]);
                $('#lcBeneficiary').val(row["supplierName"]);
                $('#cfacPo').val(row["pono"]);
                $('#cfacPoValue').val(commaSeperatedFormat(row["povalue"]));
                $('#cfacLcNo').val(row["lcNo"]);
                $('#cfacCiNo').val(row["ciNo"]);
                $("#cfacCiValue").val(commaSeperatedFormat(row['ciAmount']));
                $('#poCurrName_1').html(row["currencyName"]);
                $('#docCurrName_1').html(row["currencyName"]);
                $('#lcCurrName').html(row["currencyName"]);
                $('#ciCurrName_1').html(row["currencyName"]);
                $('#description').val(row["podesc"]);


                //alertify.alert("api/tac-request?action=7&po=" + $('#po').val() + "&ship=" + $('#cafcShipNo').val() + "&lcno=" + $('#cfacLcNo').val());
                //alert("api/tac-request?action=5&po=" + $('#po').val() + "&ciNo=" + $('#ciNo').val());
                $.get("api/tac-request?action=5&po=" + $('#po').val() + "&ciNo=" + $('#ciNo').val(), function (data) {
                    var row = JSON.parse(data),
                        ciValue = parseToCurrency($("#ciValue").val()) ,
                        docPercentage = parseFloat( row["percentage"]);
                    var valueOfDoc = (ciValue * docPercentage)/100;
                    $('#valueOfDoc').val(commaSeperatedFormat(valueOfDoc));
                    $('#acceptCertValue').val(commaSeperatedFormat(valueOfDoc));
                    $('#partName').val(row["partName"]);
                    $('#partName_cfac').val(row["partName"]);
                    var docName_tac = ''+row["cacFacText"]+' Value';
                    $('#docName').html(docName_tac);

                    /*LOAD HTML PAGE TITLE*/
                    var pageTitle_user = 'Request for '+row["cacFacText"];
                    $('#pageTitle_user').html(pageTitle_user);
                    var pageTitle = 'Request for '+row["cacFacText"]+' Approval';
                    $('#pageTitle').html(pageTitle);

                    $('#certPercent').val(row["percentage"]);
                    $('#acceptCertPercent').val(row["percentage"]);

                    var docName_cfac = ''+row["cacFacText"]+' Value';
                    //var docName_cfac = 'Value of this '+row["docName"]+'';
                    $("#docName_cfac").html(docName_cfac);

                    if($("#userType").val() !== const_role_Supplier){

                    }

                    var ciValuePercent = 'This represents '+row["percentage"]+'% of the Commercial Invoice value of the Finally Accepted Equipment.';

                    $("#ciValuePercent").val(ciValuePercent);

                    /*THESE CONDITIONS ARE TEMPORARILY DISABLED. CAN BE ENABLED IF ASKED FROM GP
                     ****************************************************************************/
                    /*if(row["partname"] == 7){
                     var partnameC = "This TAC has been issued for Commercial Purpose.\n\nBased on above mentioned information and fulfilling milestone of this project has been Commercially Accepted and add with Grameenphone Network.";
                     $('#certificateText').val(partnameC);
                     }else {
                     var partnameF = "This TAC has been issued for Commercial Purpose.\n\nBased on above mentioned information and fulfilling milestone of this project has been Finally Accepted and add with Grameenphone Network.";
                     $('#certificateText').val(partnameF);
                     }*/
                    var partnameF = "This TAC has been issued for Commercial Purpose.\n\nBased on above mentioned information and fulfilling milestone of this project has been Finally Accepted and added with Grameenphone Network.";
                    $('#certificateText').val(partnameF);

                    /*VIEW TAC FROM BUYER WINDOW*/
                    var viewTAC_btnUrl = "api/tac-request?action=6&poNo=" + $("#pono").val() + "&shipNo=" + $("#cafcShipNo").val() + "&partName=" + $("#partName_cfac").val();
                    //alert(viewTAC_btnUrl);
                    $("#tac_TacFilename").html('PO '+$("#pono").val() +'_TAC.pdf');
                    $("#viewTAC_btn").attr("href",viewTAC_btnUrl);

                    /*GET LC BENEFICIARY INFO & LETTER BODY TEXT*/
                    //alert("api/tac-request?action=13&pono=" + $('#po').val() + "&ship=" + $('#cafcShipNo').val() + "&partName=" + $('#partName_cfac').val() + "&lastActId=" + $('#lastActionId').val());
                    $.get("api/tac-request?action=13&pono=" + $('#po').val() + "&ship=" + $('#cafcShipNo').val() + "&partName=" + $('#partName_cfac').val() + "&lastActId=" + $('#lastActionId').val(), function (data) {
                        var row = JSON.parse(data);
                        $('#cfacCertificateText').val(row["cfacLetterBody"]);
                        $('#certFinalApprover').val(row["certFinalApprover"]).change();

                    });

                    $.get(`api/tac-request?action=17&poNo=${$('#po').val()}&shipNo=${$('#cafcShipNo').val()}&partName=${$('#partName_cfac').val()}`, function (data) {
                        //console.log(`api/tac-request?action=17&poNo=${$('#po').val()}&shipNo=${$('#cafcShipNo').val()}&partName=${$('#partName_cfac').val()}`);
                        var row = JSON.parse(data);
                        if (row) {
                            $(".certReqId").val(row.certReqId);
                            $(".ciDesc").val(row.ciDesc);
                            $(".ciQty").val(row.ciQty);
                        }
                    });

                });



                $.get("api/tac-request?action=7&po=" + $('#po').val() + "&ship=" + $('#cafcShipNo').val() + "&lcno=" + $('#cfacLcNo').val(), function (data) {
                    var row = JSON.parse(data);

                    $('#lcValue').val(commaSeperatedFormat(row['lcvalue']));
                    /*$("#lcBeneficiary").val(row['lcbeneficiary']);*/


                    $('#awbBlNo').val(row["blNo"]);

                    var inWord = ' (US Dollar '+dollarToWords($("#acceptCertValue").val())+')';
                    setTimeout(function () {
                        $("#acceptValueInWord").val('USD ' + $("#acceptCertValue").val() + inWord);
                    }, 2000);

                    //$("#acceptValueInWord").val('USD '+$("#acceptCertValue").val()+inWord);
                    $(".attachment").append( `<a href="download-attachment/${row["attachment"]}" title="PO Copy" target="_blank"><i class="icon fa-pdf"></i> PO </a>`);

                });

                /*********GET PAYMENT HISTORY**********/
                paymentHistory($('#ciNo').val());

            }
        });
    }


    $("#requestSubmitBtn, #requestRejectBtn").click(function (e) {
        var btnToUse = "";
        var actionText = "";
        if ($(this).attr('id') == 'requestSubmitBtn') {
            $("#action").val(1);
            actionText = "submit";
            btnToUse = $("#requestSubmitBtn");
        } else if ($(this).attr('id') == 'requestRejectBtn') {
            $("#action").val(2);
            actionText = "reject";
            btnToUse = $("#requestRejectBtn");
        }
        e.preventDefault();
        if (validateTac()) {
            alertify.confirm('Are you sure you want to ' + actionText + ' this request?', function (e) {
                $(btnToUse).prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/tac-request",
                    data: $('#tac-request-form').serialize(),
                    cache: false,
                    success: function (response) {
                        $(btnToUse).prop('disabled', false);
                        //alertify.alert(response);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                alertify.success("Saved SUCCESSFULLY!");
                                window.location.href = _dashboardURL;
                                return true;
                            } else {
                                alertify.error(res['message'], 20);
                                return false;
                            }
                        } catch (error) {
                            console.log(error);
                            alertify.error(response + ' Failed to process the request.', 20);
                            $(btnToUse).prop('disabled', false);
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error, 10);
                    }
                });
            });
        }else {
            return false;
        }
    });

    $("#cfacSubmitBtn, #requestRejectBtn_cfac").click(function (e) {
        var btnToUse = "";
        var actionText = "";
        if ($(this).attr('id') == 'cfacSubmitBtn') {
            $("#action_cfac").val(1);
            actionText = "accept";
            btnToUse = $("#cfacSubmitBtn");
        } else if ($(this).attr('id') == 'requestRejectBtn_cfac') {
            $("#action_cfac").val(2);
            actionText = "reject";
            btnToUse = $("#requestRejectBtn_cfac");
        }

        e.preventDefault();

        if ($("#action_cfac").val() == 1) {
            if (validate_cfac() === true) {
                alertify.confirm('Are you sure you want to ' + actionText + ' this request?', function (e) {
                    $(btnToUse).prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/tac-request",
                        data: $('#cfac-action-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $(btnToUse).prop('disabled', false);
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success("Saved SUCCESSFULLY!");
                                    window.location.href = _dashboardURL;
                                    return true;
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            } catch (e) {
                                alertify.error(response + 'Failed to process the request.', 20)
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error, 10);
                        }
                    });
                });

            } else {
                return false;
            }
        } else {
            alertify.confirm('Are you sure you want to ' + actionText + ' this request?', function (e) {
                $(btnToUse).prop('disabled', false);
                $.ajax({
                    type: "POST",
                    url: "api/tac-request",
                    data: $('#cfac-action-form').serialize(),
                    cache: false,
                    success: function (response) {
                        $(btnToUse).prop('disabled', false);
                        //alertify.alert(html);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                alertify.success("Saved SUCCESSFULLY!");
                                window.location.href = _dashboardURL;
                                return true;
                            } else {
                                alertify.error(res['message'], 20);
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + 'Failed to process the request.', 20)
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error, 10);
                    }
                });
            });
        }
    });

    /*Get Certificate Final Approver List
    * Created By: Hasan Masud
    * Created On: 24-04-2019
    * **********************************/
    $.getJSON("api/users?action=5&role=" + const_role_cert_final_approver, function (list) {
        $("#certFinalApprover").select2({
            data: list,
            placeholder: "Select an approver",
            allowClear: true,
            width: "100%"
        })
    });

});

/*VALIDATE TAC REQUEST MODULE*/
function validate() {

    if($("#poList").val() == ""){
        alertify.error('Please select a PO number');
        $("#poList").select2('open');
        return false;
    }
    if($("#ciList").val() == ""){
        alertify.error('Please select a CI number');
        $("#ciList").select2('open');
        return false;
    }

    if (pendingCount > 0) {
        alertify.alert('You have already made a request for this PO & CI number, which is pending at approval flow/payment was not made. In this regard please contact buyer.');
        return false;
    }
    return true;

}

function paymentHistory(ciNo) {
    $.ajax({
        url: "api/tac-request?action=11&ciNo=" + ciNo,
        dataType: "text",
        success: function(data) {
            var json = $.parseJSON(data);
            $('#payHistory').empty();
            for (var i=0;i<json.length;++i){
                $('#payHistory').append(
                    '<tr>'+
                    '<td>'+json[i].poid+'</td>'+
                    '<td>'+json[i].poDate+'</td>'+
                    '<td>'+json[i].docName+'</td>'+
                    '<td>'+json[i].paymentPercent+'</td>'+
                    '<td>'+json[i].amount+'</td>'+
                    '<td>'+json[i].payDate+'</td>'+
                    '</tr>'
                );
            }
        }
    });
}

//Validate CFAC Forward/Submit
function validate_cfac() {
    if($("#certFinalApprover").val() == ""){
        alertify.error('Please select an approver');
        $("#certFinalApprover").select2('open');
        return false;
    }
    return true;
}

//Validate TAC request
function validateTac() {
    if($("#userType").val() == const_role_Supplier){
        if (!$("#ciDesc_t").val()){
            alertify.error("Please write item description.");
            $("#ciDesc_t").focus();
            return false;
        }
        if (!$("#ciQty_t").val()){
            alertify.error("Please write item quantity.");
            $("#ciQty_t").focus();
            return false;
        }
    }
    return true;
}