/**
 * Created by HasanMasud on 10-Dec-18.
 */
$(document).ready(function () {
    /*IMPLEMENTATION BY GP*/
    if($("#by_0").prop("checked")) {
        gp_addMoreRows('', '', '', '', '', '', '', '');
    }
    $("#gp_btnAddNewRow").click(function(e){
        gp_addMoreRows('','','','','','','','');
    });
    /*IMPLEMENTATION BY SUPPLIER*/
    if($("#by_1").prop("checked")) {
        sup_addMoreRows('', '', '', '', '', '', '', '');
    }
    $("#sup_btnAddNewRow").click(function(e){
        sup_addMoreRows('','','','','','','','');
    });
    /*IMPLEMENTATION BY OTHER*/
    if($("#by_2").prop("checked")) {
        oth_addMoreRows('', '', '', '', '', '', '', '');
    }
    $("#oth_btnAddNewRow").click(function(e){
        oth_addMoreRows('','','','','','','','');
    });


    $("#btn_SubmitContract").click(function(e) {

        e.preventDefault();
        if (validate()) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/contract",
                data: $('#contract-form').serialize(),
                cache: false,
                success: function (response) {
                    //alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            //ResetForm();
                            alertify.success(res['message']);
                            window.setTimeout(function () {
                                window.location.href = 'contracts';
                            }, 3000);
                            return true;
                        } else {
                            button.disabled = false;
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
                    button.disabled = false;
                    alertify.error(textStatus + ": " + xhr.status + " " + error);
                }
            });
        } else {
            return false;
        }
    });

    $("#gp-impl-area").hide();
    $('#by_0').change(function(){
        if(this.checked) {
            $('#gp-impl-area').fadeIn('slow');
            gp_addMoreRows('', '', '', '', '', '', '', '');
        }else {
            $('#gp-impl-area').fadeOut('slow');
        }
    });

    $("#sup-impl-area").hide();
    $('#by_1').change(function(){
        if(this.checked) {
            $('#sup-impl-area').fadeIn('slow');
            sup_addMoreRows('', '', '', '', '', '', '', '');
        }else {
            $('#sup-impl-area').fadeOut('slow');
        }
    });

    $("#other-impl-area").hide();
    $('#by_2').change(function(){
        if(this.checked) {
            $('#other-impl-area').fadeIn('slow');
            oth_addMoreRows('', '', '', '', '', '', '', '');
        }else {
            $('#other-impl-area').fadeOut('slow');
        }
    });
    //Load data on contract number change
    if($("#contractId").val() > 0 ) {
        //alert("api/contract?action=2&cId=" + $("#oldContracts").val());
        $.get("api/contract?action=2&contractId=" + $("#contractId").val(), function (data) {
            try {
                var row = JSON.parse(data);
                var contractsInfo = row[0][0];
                var contract_terms_gp = row[1];
                var contract_terms_sup = row[2];
                var contract_terms_oth = row[3];

                $('#contractId').val(contractsInfo['id']);
                $('#contractName').val(contractsInfo['contractName']);
                $('#contractDesc').val(contractsInfo['contractDesc']);
                if(contractsInfo['termAttach']) {
                    $('#termAttach').val(contractsInfo['termAttach']);
                    $('#lblTermAttach').html(` <a href="docs/vendors_terms/${contractsInfo['termAttach']}"  target="_blank">${contractsInfo['termAttach']}</a>`);

                    /*!
                    * Delete  file from given location
                    * @todo upload this file
                    * *********************************/
                    $("#clearAttachment").click(function (e) {
                        e.preventDefault();
                        deleteFile($('#lblTermAttach').html());
                    });
                }

                //$(".file-marker").empty();

                //Implemented by GP
                if (contract_terms_gp.length > 0) {
                    //$("#gp_addedRows").empty();
                    $('#by_0').attr('checked', 'checked');
                    $('#gp-impl-area').show();
                    for (var i = 0; i < contract_terms_gp.length; i++) {
                        gp_addMoreRows(contract_terms_gp[i]['implnBy'], contract_terms_gp[i]['percentage'], contract_terms_gp[i]['certificateName'], contract_terms_gp[i]['matDays'], contract_terms_gp[i]['matTerms'], contract_terms_gp[i]['certDays'], contract_terms_gp[i]['certTitle'], contract_terms_gp[i]['paymentTermsText']);
                    }
                }else {
                    $('#by_0').removeAttr('checked');
                    $('#gp-impl-area').hide();
                }

                //Implemented by Supplier
                if (contract_terms_sup.length > 0) {
                    //$("#sup_addedRows").empty();
                    $('#by_1').attr('checked', 'checked');
                    $('#sup-impl-area').show();
                    for (var j = 0; j < contract_terms_sup.length; j++) {
                        sup_addMoreRows(contract_terms_sup[j]['implnBy'], contract_terms_sup[j]['percentage'], contract_terms_sup[j]['certificateName'], contract_terms_sup[j]['matDays'], contract_terms_sup[j]['matTerms'], contract_terms_sup[j]['certDays'], contract_terms_sup[j]['certTitle'], contract_terms_sup[j]['paymentTermsText']);
                    }
                }else {
                    $('#by_1').removeAttr('checked');
                    $('#sup-impl-area').hide();
                }

                //Implemented by Other
                //$("#oth_addedRows").empty();
                if (contract_terms_oth.length > 0) {
                    $('#by_2').attr('checked', 'checked');
                    $('#other-impl-area').show();
                    for (var k = 0; k < contract_terms_oth.length; k++) {
                        oth_addMoreRows(contract_terms_oth[k]['implnBy'], contract_terms_oth[k]['percentage'], contract_terms_oth[k]['certificateName'], contract_terms_oth[k]['matDays'], contract_terms_oth[k]['matTerms'], contract_terms_oth[k]['certDays'], contract_terms_oth[k]['certTitle'], contract_terms_oth[k]['paymentTermsText']);
                    }
                }else {
                    $('#by_2').removeAttr('checked');
                    $('#other-impl-area').hide();
                }
            } catch (e) {
                console.log(e);
                alertify.error('Insufficient payload');
                $("#payment-terms").empty();
            }
        });
    }

});



/*IMPLEMENTATION BY GP, ADDED BY HASAN MASUD
 ********************************************/
//var rowCount = 0;
function gp_addMoreRows(implnBy, percentage, certificateName, matDays, matTerms, certDays, certTitle, paymentTermsText) {

    var rowCount = $('div#gp_contractEntryArea').children().length+1;

    $.getJSON("api/buyers-lc-request?action=3", function (list) {
        $("#gp_certificateName"+rowCount).select2({
            data: list,
            placeholder: "Select a certificate",
            allowClear: true,
            width: "100%"
        });
        $('#gp_certificateName'+rowCount).val(certificateName).change();

        $('#gp_matTerms'+rowCount).val(matTerms).change();
    });

    //$('#gp_matTerms'+rowCount).val(matTerms).change();
    /*$('#gp_matTerms'+rowCount).selectpicker('refresh');
    $('#gp_matTerms'+rowCount).val(matTerms).change();*/

    //alert(rowCount);
    //rowCount ++;
    var gp_recRow =
        '<div class="" id="gp_contractEntryArea">' +
            '<div class="gp_contractEntry" id="gp_contractEntry'+rowCount+'">' +
                '<div class="form-group">' +
                    '<div class="col-md-1 text-center">'+ rowCount +' '+'.'+' </div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="gp_percentage[]" id="gp_percentage'+rowCount+'" value="'+percentage+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-plugin="select2" name="gp_certificateName[]" id="gp_certificateName'+rowCount+'"> </select>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="gp_matDays[]" id="gp_matDays'+rowCount+'"  value="'+matDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-plugin="select2"  name="gp_matTerms[]" id="gp_matTerms'+rowCount+'"  title="Maturity Terms">' +
                            '<option disabled selected>Select a term</option>' +
                            '<option value="104">N/A</option>' +
                            '<option value="9">Air Way Bill Date</option>' +
                            '<option value="10">Bill of Lading</option>' +
                            '<option value="11">LC Issuance</option>' +
                            '<option value="12">Shipment Date</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="gp_certDays[]" id="gp_certDays'+rowCount+'" value="'+certDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="gp_certTitle[]" id="gp_certTitle'+rowCount+'" value="'+certTitle+'">'+
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<a href="javascript:void(0);" onclick="gp_removeRow('+rowCount+');" style="float:right;"> <i  class="fa fa-close btn btn-danger"></i></a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    $('#gp_paymentTermsText').val(paymentTermsText);

    $('#gp_addedRows').append(gp_recRow);
}

function gp_removeRow(removeNum) {

    if(removeNum == 1){
        alertify.error("Cannot Delete this Row");
        return false;
    } else {
        $('#gp_contractEntry'+removeNum).remove();
    }

}


/*IMPLEMENTATION BY SUPPLIER, ADDED BY HASAN MASUD
***************************************************/
//var sup_rowCount = 1;
function sup_addMoreRows(implnBy, percentage, certificateName, matDays, matTerms, certDays, certTitle, paymentTermsText) {

    var sup_rowCount = $('div#sup_contractEntryArea').children().length+1;

    $.getJSON("api/buyers-lc-request?action=3", function (list) {
        $("#sup_certificateName"+sup_rowCount).select2({
            data: list,
            placeholder: "Select a certificate",
            allowClear: true,
            width: "100%"
        });
        $('#sup_certificateName'+sup_rowCount).val(certificateName).change();

        $('#sup_matTerms'+sup_rowCount).val(matTerms).change();
    });
    // alert(sup_rowCount);
    //sup_rowCount ++;
    var sup_recRow =
        '<div class="" id="sup_contractEntryArea">' +
            '<div class="sup_contractEntry" id="sup_contractEntry'+sup_rowCount+'">' +
                '<div class="form-group">' +
                    '<div class="col-md-1 text-center">'+ sup_rowCount +' '+'.'+' </div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="sup_percentage[]" id="sup_percentage'+sup_rowCount+'" value="'+percentage+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-plugin="select2" name="sup_certificateName[]" id="sup_certificateName'+sup_rowCount+'"> </select>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="sup_matDays[]" id="sup_matDays'+sup_rowCount+'" value="'+matDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-plugin="select2"  name="sup_matTerms[]" id="sup_matTerms'+sup_rowCount+'"  title="Maturity Terms">' +
                            '<option disabled selected>Select a term</option>' +
                            '<option value="104">N/A</option>' +
                            '<option value="9">Air Way Bill Date</option>' +
                            '<option value="10">Bill of Lading</option>' +
                            '<option value="11">LC Issuance</option>' +
                            '<option value="12">Shipment Date</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="sup_certDays[]" id="sup_certDays'+sup_rowCount+'" value="'+certDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="sup_certTitle[]" id="sup_certTitle'+sup_rowCount+'" value="'+certTitle+'">'+
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<a href="javascript:void(0);" onclick="sup_removeRow('+sup_rowCount+');" style="float:right;"> <i  class="fa fa-close btn btn-danger"></i></a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    $('#sup_paymentTermsText').val(paymentTermsText);

    $('#sup_addedRows').append(sup_recRow);
}

function sup_removeRow(removeNum) {

    if(removeNum == 1){
        alertify.error("Cannot Delete this Row");
        return false;
    } else {
        $('#sup_contractEntry'+removeNum).remove();
    }

}

/*IMPLEMENTATION BY OTHER, ADDED BY HASAN MASUD
***********************************************/
//var oth_rowCount = 1;
function oth_addMoreRows(implnBy, percentage, certificateName, matDays, matTerms, certDays, certTitle, paymentTermsText) {

    var oth_rowCount = $('div#oth_contractEntryArea').children().length+1;

    $.getJSON("api/buyers-lc-request?action=3", function (list) {
        $("#oth_certificateName"+oth_rowCount).select2({
            data: list,
            placeholder: "Select a certificate",
            allowClear: true,
            width: "100%"
        });
        $('#oth_certificateName'+oth_rowCount).val(certificateName).change();

        $('#oth_matTerms'+oth_rowCount).val(matTerms).change();
    });
    // alert(oth_rowCount);
    //oth_rowCount ++;
    var oth_recRow =
        '<div id="oth_contractEntryArea">' +
            '<div class="oth_contractEntry" id="oth_contractEntry'+oth_rowCount+'">' +
                '<div class="form-group">' +
                    '<div class="col-md-1 text-center">'+ oth_rowCount +' '+'.'+' </div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="oth_percentage[]" id="oth_percentage'+oth_rowCount+'" value="'+percentage+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-plugin="select2" name="oth_certificateName[]" id="oth_certificateName'+oth_rowCount+'"> </select>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="oth_matDays[]" id="oth_matDays'+oth_rowCount+'" value="'+matDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<select class="form-control" data-style="btn-select" data-plugin="selectpicker"  name="oth_matTerms[]" id="oth_matTerms'+oth_rowCount+'"  title="Maturity Terms">' +
                            '<option disabled selected>Select a term</option>' +
                            '<option value="104">N/A</option>' +
                            '<option value="9">Air Way Bill Date</option>' +
                            '<option value="10">Bill of Lading</option>' +
                            '<option value="11">LC Issuance</option>' +
                            '<option value="12">Shipment Date</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<input type="text" class="form-control" name="oth_certDays[]" id="oth_certDays'+oth_rowCount+'" value="'+certDays+'">'+
                    '</div>' +
                    '<div class="col-md-2">' +
                        '<input type="text" class="form-control" name="oth_certTitle[]" id="oth_certTitle'+oth_rowCount+'"  value="'+certTitle+'">'+
                    '</div>' +
                    '<div class="col-md-1">' +
                        '<a href="javascript:void(0);" onclick="oth_removeRow('+oth_rowCount+');" style="float:right;"> <i  class="fa fa-close btn btn-danger"></i></a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

    $('#oth_paymentTermsText').val(paymentTermsText);

    $('#oth_addedRows').append(oth_recRow);
}

function oth_removeRow(removeNum) {

    if(removeNum == 1){
        alertify.error("Cannot Delete this Row");
        return false;
    } else {
        $('#oth_contractEntry'+removeNum).remove();
    }

}

function validate()
{

    if ($("#contractName").val() == "" || $("#contractName").val() == "GP-") {
        alertify.error('Contract No. missing or invalid.');
        $("#contractName").focus();
        return false;
    }

    if (!$("#by_0").prop("checked") && !$("#by_1").prop("checked") && !$("#by_2").prop("checked")){
        alertify.error('Please select at least 1 implementation option');
        $("#by_0").focus();
        return false
    }

    /*!
    * Validation for Implementation by Grameenphone
    * Added by: Hasan Masud
    * Added on: 2020-07-18
    * **********************************************/
    if($("#by_0").prop("checked")) {
        var g = $('.gp_contractEntry').length;
        for (var gc = 1; gc <= g; gc++) {
            if ($("#gp_percentage" + gc).val() == "") {
                alertify.error("Please put a percentage.");
                $("#gp_percentage" + gc).focus();
                return false;
            }

            if ($("#gp_certificateName" + gc).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#gp_certificateName" + gc).select2('open');
                return false;
            }

            if ($("#gp_matDays" + gc).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#gp_matDays" + gc).focus();
                return false;
            }

            if (!$("#gp_matTerms" + gc).val()) {
                alertify.error("Please select a Maturity Term.");
                $("#gp_matTerms" + gc).select('open');
                return false;
            }

            if ($("#gp_certTitle" + gc).val() == "") {
                alertify.error("Please enter Certificate title.");
                $("#gp_certTitle" + gc).focus();
                return false;
            }
        }
        if (!$("#gp_paymentTermsText").val()) {
            alertify.error("Please write a description about the term");
            $("#gp_paymentTermsText").focus();
            return false;
        }
    }

    /*!
    * Validation for Implementation by Supplier
    * Added by: Hasan Masud
    * Added on: 2020-07-18
    * **********************************************/
    if($("#by_1").prop("checked")) {
        var s = $('.sup_contractEntry').length;
        for (var sc = 1; sc <= s; sc++) {
            if ($("#sup_percentage" + sc).val() == "") {
                alertify.error("Please put a percentage.");
                $("#sup_percentage" + sc).focus();
                return false;
            }

            if ($("#sup_certificateName" + sc).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#sup_certificateName" + sc).select2('open');
                return false;
            }

            if ($("#sup_matDays" + sc).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#sup_matDays" + sc).focus();
                return false;
            }

            if (!$("#sup_matTerms" + sc).val()) {
                alertify.error("Please select a Maturity Term.");
                $("#sup_matTerms" + sc).select('open');
                return false;
            }

            if ($("#sup_certTitle" + sc).val() == "") {
                alertify.error("Please enter Certificate title.");
                $("#sup_certTitle" + sc).focus();
                return false;
            }
        }
        if (!$("#sup_paymentTermsText").val()) {
            alertify.error("Please write a description about the term");
            $("#sup_paymentTermsText").focus();
            return false;
        }
    }

    /*!
    * Validation for Implementation by Other
    * Added by: Hasan Masud
    * Added on: 2020-07-18
    * **********************************************/
    if($("#by_2").prop("checked")) {
        var o = $('.oth_contractEntry').length;
        for (var os = 1; os <= o; os++) {
            if ($("#oth_percentage" + os).val() == "") {
                alertify.error("Please put a percentage.");
                $("#oth_percentage" + os).focus();
                return false;
            }

            if ($("#oth_certificateName" + os).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#oth_certificateName" + os).select2('open');
                return false;
            }

            if ($("#oth_matDays" + os).val() == "") {
                alertify.error("Please select a Certificate.");
                $("#oth_matDays" + os).focus();
                return false;
            }

            if (!$("#oth_matTerms" + os).val()) {
                alertify.error("Please select a Maturity Term.");
                $("#oth_matTerms" + os).select('open');
                return false;
            }

            if ($("#oth_certTitle" + os).val() == "") {
                alertify.error("Please enter Certificate title.");
                $("#oth_certTitle" + os).focus();
                return false;
            }
        }
        if (!$("#oth_paymentTermsText").val()) {
            alertify.error("Please write a description about the term");
            $("#oth_paymentTermsText").focus();
            return false;
        }
    }

    return true;
}

/*!
* Upload contract terms copy
* ********************************/
$(function () {

    var button2 = $('#btnTermAttach'), interval;
    var lbl = $('#lblTermAttach');
    var txtbox = $('#termAttach');

    new AjaxUpload(button2, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            lbl.html(res['filename']);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf|zip)$/i.test(ext))) {
                alertify.error('Invalid File Format.');
                return false;
            }
            lbl.html("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
                if (lbl.html().length < 13) {
                    lbl.html(lbl.html() + '.');
                } else {
                    lbl.html('Uploading');
                }
            }, 200);
        }
    });
});

/*!
* Delete file
* *************************/
function deleteFile(objfile) {

    $.ajax({
        url: 'application/library/uploadhandler.php?del=' + encodeURI(objfile.replace("../", "")),
        success: function (result) {
            var res = JSON.parse(result);
            if(res['status']==1) {
                alertify.success("Deleted");
            }
        }
    });
}
