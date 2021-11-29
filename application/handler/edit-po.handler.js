/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

// Arrays for PO details
var podata,
    comments,
    attach,
    lcinfo,
    pterms,
    ship;

var poid, shipno;

var statusLevel = 0;    // PO initiated
/*
 0	// New PO initiated
 1	// Draft PI submitted
 2	// Draft PI sent for PR-EA feedback
 3	// Draft PI verification done by PR-EA
 4	// Final PI submitted
 5	// Final PI sent for PR-EA feedback
 */

$(document).ready(function(){

    // If in case PO number missing exit
    if($("#poid").val()==""){
        return;
    }

    poid = $("#poid").val();
    shipno = $("#shipno").val();

    //alert('api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno);
    $.get('api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno, function (data) {

        if (!$.trim(data)) {
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {
            var row = JSON.parse(data);
            podata = row[0][0];
            comments = row[1];
            attach = row[2];

            // PO info
            $('#poid').val(podata['poid']);
            $('#povalue').val(commaSeperatedFormat(podata['povalue']));
            $('#currency').html(podata['curname']);
            $('#podesc').val(HTMLDecode(podata['podesc']));

            // Loading supplier list
            $.getJSON("api/company?action=4", function (list) {
                $("#supplier").select2({
                    data: list,
                    placeholder: "select a company",
                    width: "100%"
                });
                $('#supplier').val(podata['supplier']).change();
            });

            // Loading supplier wise contract list then selecting contract reference
            $("#supplier").change(function (e) {
                var id = $("#supplier").val();
                $.getJSON("api/category?action=9&cid=" + id, function (list) {
                    $("#contractref").empty();
                    $("#contractref").select2({
                        data: list,
                        minimumResultsForSearch: Infinity,
                        placeholder: "Select Contract Ref",
                        width: "100%"
                    });
                    $('#contractref').val(podata['contractref']).change();
                });
            });

            var deliverydate = new Date(podata['deliverydate']);
            $('#deliverydate').datepicker('setDate', deliverydate);
            $('#deliverydate').datepicker('update');

            $('#actualPoDate').datepicker( "setDate" , new Date(podata['actualPoDate']));
            $("#installBy_" + podata['installbysupplier']).attr("checked", "").parent().addClass("checked");
            $('#emailto').tokenfield('setTokens',podata["emailto"]);
            $('#emailcc').tokenfield('setTokens',podata["emailcc"]);
            $('#noflcissue').val(podata['noflcissue']);
            $('#nofshipallow').val(podata['nofshipallow']);

            $.getJSON("api/users?action=5&role=" + const_role_PR_Users, function (list) {
                $("#prUserEmailTo, #prUserEmailCC").select2({
                    data: list,
                    placeholder: "PR User",
                    allowClear: false,
                    width: "100%"
                });
                if (poid != "") {
                    var v1 = podata['pruserto'].split(',');
                    $('#prUserEmailTo').val(v1).change();
                    var v2 = podata['prusercc'].split(',');
                    $('#prUserEmailCC').val(v2).change();
                }
            });

            // PI info
            $.get("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_DRAFT_PI_SUBMITTED, function (res) {
                if (res > 0) {

                    stageLevel = 1; // Draft PI submitted

                    $('#pinum').val(podata['pinum']);
                    $('#pivalue').val(commaSeperatedFormat(podata['pivalue']));
                    $("#shipmode" + podata['shipmode']).attr("checked", "").parent().addClass("checked");

                    $('#hscsea').val(podata['hscsea']);
                    $('#hscode').val(podata['hscode']);

                    $('#piCurrency, #bvCurrency').html(podata['curname']);

                    $.getJSON("application/library/country.txt", function (data) {
                        $("#origin").select2({
                            data: data,
                            placeholder: "Select a origin",
                            width: "100%"
                        });

                        var v = podata['origin'].split(',');
                        $('#origin').val(v).change();
                    });

                    $('#negobank').val(htmlspecialchars_decode(podata['negobank']));
                    $('#shipport').val(htmlspecialchars_decode(podata['shipport']));
                    $('#lcbankaddress').val(htmlspecialchars_decode(podata['lcbankaddress']));
                    $('#productiondays').val(podata['productiondays']);
                    $('#buyercontact').val(podata['buyercontact']);
                    $('#techcontact').val(podata['techcontact']);

                    $('#lcdesc').val(HTMLDecode(podata['lcdesc']));
                    if (podata['lcdesc'] != null && podata['lcdesc'] != "") {
                        statusLevel = 2;    // Draft PI sent for PR-EA feedback
                    }

                    if (podata['pidate'] != null) {
                        var piDate = new Date(podata['pidate']);
                        $('#pidate').datepicker('setDate', piDate);
                        $('#pidate').datepicker('update');
                        statusLevel = 3;
                    }
                    $('#basevalue').val(commaSeperatedFormat(podata['basevalue']));

                }
            });
            // Buyer's comments log
            commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');

            // loading attachments
            var attachmentHtml = '';
            var attachedBy = '';
            for (var i = 0; i < attach.length; i++) {
                if (attach[i]['rolename'] != attachedBy) {
                    if (attachedBy != '') {
                        attachmentHtml += '</div>';
                    }
                    attachedBy = attach[i]['rolename'];
                    attachmentHtml += '<h4 class="well well-sm example-title">' + attachedBy + '\'s Attachments</h4>' +
                        '<div class="form-group">';
                }
                attachmentHtml += '<label class="col-sm-6 control-label">' + attach[i]['title'] + '</label>' +
                    '<div class="col-sm-6">' +
                    '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/' + attach[i][0] + '" target="_blank">' + attach[i]['title'].substring(0, 15) + '...' + '</a></label>&nbsp;<button type="button" class="btn btn-default btn-outline btn-xs replaceAttachment" data-target="#replaceAttach" data-toggle="modal" data-id="' + attach[i]['id'] + ',' + attach[i]['filename'] + '"><i class="icon success wb-upload"></i></button>' +
                    '</div>';
            }

            $('#usersAttachments').html(attachmentHtml);

            $(document).on("click", ".replaceAttachment", function () {

                var attachmentData = $(this).data('id').split(',');
                var docID = attachmentData[0];
                var docName = attachmentData[1];

                $("#attachmentDocID").val(docID);
                $("#replaceAttachOld").html(docName);
            });

            // LC info
            $.get("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_LC_REQUEST_SENT, function (res) {
                if (res > 0) {

                    stageLevel = 2; // LC copy send from Finance

                    lcinfo = row[3][0];
                    pterms = row[4];

                    $.getJSON("api/category?action=4&id=32", function (list) {
                        $("#lctype").select2({
                            data: list,
                            minimumResultsForSearch: Infinity,
                            placeholder: "Select LC Type",
                            allowClear: false,
                            width: "100%"
                        });
                        $("#lctype").val(lcinfo['lctype']).change();
                    });

                    $.get("api/category?action=6&id=27&tag=1", function (data) {
                        $("#producttype").html(data);
                        $("#producttype").select2({
                            placeholder: "Select Product Type",
                            width: "100%"
                        });
                        $("#producttype").val(lcinfo['producttype']).change();
                    });

                    $("#lcrequesttype_" + lcinfo['lca']).attr("checked", "").parent().addClass("checked");
                    $('#lcno').val(lcinfo['lcno']);
                    $('#lcafno').val(lcinfo['lcafno']);

                    $('#lcissuedate').datepicker('setDate', new Date(lcinfo['lcissuedate']));
                    $('#lcissuedate').datepicker('update');
                    $('#daysofexpiry').datepicker('setDate', new Date(lcinfo['daysofexpiry']));
                    $('#daysofexpiry').datepicker('update');
                    $('#lastdateofship').datepicker('setDate', new Date(lcinfo['lastdateofship']));
                    $('#lastdateofship').datepicker('update');
                    $('#lcexpirydate').datepicker('setDate', new Date(lcinfo["lcexpirydate"]));
                    $('#lcexpirydate').datepicker('update');

                    $("#lcvalue").val(commaSeperatedFormat(lcinfo["lcvalue"]));
                    $("#lcvalueCur").html(podata['curname']);

                    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
                        $("#lcissuerbank").select2({
                            data: list,
                            placeholder: "Select a Bank",
                            allowClear: false,
                            width: "100%"
                        });
                        $('#lcissuerbank').val(lcinfo['lcissuerbank']).change();

                        $.getJSON("api/bankinsurance?action=4&type=bank&id="+$('#lcissuerbank').val(), function (list) {
                            $("#bankaccount").empty();
                            $("#bankaccount").select2({
                                data: list,
                                minimumResultsForSearch: Infinity,
                                placeholder: "Select a Bank account",
                                allowClear: false,
                                width: "100%"
                            });
                            if(lcinfo['bankaccount']!=""){
                                $("#bankaccount").val(lcinfo['bankaccount']).change();
                            }
                        });
                    });
                    //$('#insurance').html(lcinfo['insurancename']);
                    $.getJSON("api/bankinsurance?action=4&type=insurance", function (list) {
                        $("#insurance").select2({
                            data: list,
                            placeholder: "Select an insurance company",
                            allowClear: false,
                            width: "100%"
                        });
                        $('#insurance').val(lcinfo['insurance']).change();
                    });

                    $('#lcdesc1').html(HTMLDecode(podata['lcdesc']));
                    $("#paymentTermsText").val(lcinfo["paymentterms"]);

                    var ptrow = "";
                    for (var i = 0; i < pterms.length; i++) {
                        /*ptrow = "<tr><td class=\"text-center\">" + pterms[i]['percentage'] + "%</td>" +
                            "<td class=\"text-center\">" + pterms[i]['partname'] + "</td>" +
                            "<td class=\"text-center\">" + pterms[i]['dayofmaturity'] + " Days Maturity</td>" +
                            "<td class=\"text-left\">" + pterms[i]['maturityterms'] + "</td></tr>";
                        $("#lcPaymentTermsTable").append(ptrow);*/
                        newPaymentTermsRow(pterms[i]['id'], pterms[i]['percentage'],pterms[i]['ccId'],pterms[i]['dayofmaturity'],pterms[i]['termsText'],pterms[i]['cacFacDay'],pterms[i]['cacFacText']);
                    }
                }
            });

            // Shipment info
            $.get("api/view-po?action=1&po=" + poid + "&shipno=" + shipno + "&status=" + ACTION_SHARED_SHIPMENT_DOCUMENT, function (res) {
                if (res > 0) {

                    stageLevel = 3; // LC accepted by supplier and shared shipment documents

                    $('#shipmode1sea, #shipmode1air').on('ifChecked', function(event){
                        var shipmode1_check = $('input:radio[name=shipmode1]:checked').val();
                        if(shipmode1_check=='sea'){
                            $("#mawbNo").attr("readonly",true);
                            $("#hawbNo").attr("readonly",true);
                            $("#blNo").removeAttr("readonly");
                        } else if(shipmode1_check=='air'){
                            $("#blNo").attr("readonly",true);
                            $("#mawbNo").removeAttr("readonly");
                            $("#hawbNo").removeAttr("readonly");
                        }
                    });

                    // alert('api/shipment?action=1&po=' + poid + '&shipno=' + shipno);
                    $.get('api/shipment?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

                        ship = JSON.parse(data);

                        $('#shipmode1' + ship['shipmode']).attr('checked', '').parent().addClass('checked');
                        if (ship['shipmode'] == "sea") {
                            $("#mawbNo").attr("readonly", true);
                            $("#hawbNo").attr("readonly", true);
                        } else if (ship['shipmode'] == "air") {
                            $("#blNo").attr("readonly", true);
                        }

                        $("#scheduleETA").datepicker('setDate', new Date(ship["scheduleETA"]));
                        $("#scheduleETA").datepicker('update');

                        if(ship["scheduleETD"]!=null) {
                            $("#scheduleETD").datepicker('setDate', new Date(ship["scheduleETD"]));
                            $("#scheduleETD").datepicker('update');
                        }
                        $("#mawbNo").val(ship["mawbNo"]);
                        $("#hawbNo").val(ship["hawbNo"]);
                        $("#blNo").val(ship["blNo"]);
                        $("#awbOrBlDate").datepicker('setDate', new Date(ship["awbOrBlDate"]));
                        $("#awbOrBlDate").datepicker('update');
                        $("#dhlTrackNo").val(ship["dhlTrackNo"]);
                        $("#GERPVoucherNo").val(ship["GERPVoucherNo"]);
                        $("#ciNo").val(ship["ciNo"]);
                        $("#ciDate").datepicker('setDate', new Date(ship["ciDate"]));
                        $("#ciDate").datepicker('update');
                        $("#ciAmount").val(commaSeperatedFormat(ship["ciAmount"]));
                        $("#invoiceQty").val(ship["invoiceQty"]);
                        $("#noOfcontainer").val(ship["noOfcontainer"]);
                        $("#noOfBoxes").val(ship["noOfBoxes"]);
                        $("#ChargeableWeight").val(ship["ChargeableWeight"]);

                        // AWB/BL Scan Copy
                        if (ship["attachAwbOrBlScanCopy"] != null) {
                            $("#attachAwbOrBlScanCopyOld").val(ship["attachAwbOrBlScanCopy"]);
                            $("#attachAwbOrBlScanCopyLink").html(attachmentLink(ship['attachAwbOrBlScanCopy']));
                        }
                        // CI Scan Copy
                        if (ship["attachCiScanCopy"] != null) {
                            $("#attachCiScanCopyOld").val(ship["attachCiScanCopy"]);
                            $("#attachCiScanCopyLink").html(attachmentLink(ship['attachCiScanCopy']));
                        }
                        // Packing List Scan Copy
                        if (ship["attachPackListScanCopy"] != null) {
                            $("#attachPackListScanCopyOld").val(ship["attachPackListScanCopy"]);
                            $("#attachPackListScanCopyLink").html(attachmentLink(ship['attachPackListScanCopy']));
                        }
                        // Certificate of Origine Scan Copy
                        if (ship["attachAwbOrBlScanCopy"] != null) {
                            $("#attachOriginCertificateOld").val(ship["attachOriginCertificate"]);
                            $("#attachOriginCertificateLink").html(attachmentLink(ship['attachOriginCertificate']));
                        }
                        // Freight Certificate
                        if (ship["attachFreightCertificate"] != null) {
                            $("#attachFreightCertificateOld").val(ship["attachFreightCertificate"]);
                            $("#attachFreightCertificateLink").html(attachmentLink(ship['attachFreightCertificate']));
                        }
                        // Shipment Other Docs
                        if (ship["attachShipmentOther"] != null) {
                            $("#attachShipmentOtherOld").val(ship["attachShipmentOther"]);
                            $("#attachShipmentOtherLink").html(attachmentLink(ship['attachShipmentOther']));
                        }

                        if ($("#lastAction").val() != ACTION_SHIP_DOC_REJECTED_EATEAM) {
                            $("#shipmentInputesRow input, #SendShipDoctoGp_btn").attr('disabled', true);
                            $("#shipDocAttachmentsRow").addClass('hidden');
                        } else {
                            $("#dhlTrackNo, #dhlTrackNoUpdate_btn").attr('disabled', true);
                        }
                    });
                }
            });
        }
    });

    $("#addNewPaymentTermsRow").click(function(e){
        //alert('vd');
        newPaymentTermsRow('',0,'',0,'');
    });

});

$("#deliverydate, #pidate, #scheduleETA, #scheduleETD, #awbOrBlDate, #ciDate, #actualPoDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});

// Replace attachment functions

$(function () {

    var button = $('#btnReplaceAttachNew'), interval;
    var txtbox = $('#replaceAttachNew');

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

$("#replaceAttachment_btn").click(function (e) {
    e.preventDefault();
    if($('#replaceAttachNew').val() != ""){
        $.ajax({
            type: "POST",
            url: "api/attachment",
            data: $('#form-replacedoc').serialize(),
            cache: false,
            success: function (result) {
                var res = JSON.parse(result);
                if (res['status'] == 1) {
                    alertify.success("Replaced SUCCESSFULLY!");
                    refreshAttachment();
                } else {
                    alertify.error("FAILED!");
                    return false;
                }
            }
        });    
    } else {
        alertify.error("Please select an attachment!");
        return false;
    }
});

function refreshAttachment(){
    $.get('api/purchaseorder?action=2&id='+poid, function (data) {
        
        var row = JSON.parse(data);
        var attach = row[2];
    
        // loading attachments
        var attachmentHtml = '';
        var attachedBy = '';
        for(var i=0; i<attach.length; i++){
            if(attach[i]['rolename']!=attachedBy){
                if(attachedBy!=''){
                    attachmentHtml += '</div>';
                }
                attachedBy = attach[i]['rolename'];
                attachmentHtml += `<h4 class="well well-sm example-title margin-bottom-5">${attachedBy}'s Attachments</h4>
                    <div class="form-group">`;
            }
            attachmentHtml += '<label class="col-sm-6 control-label">'+attach[i]['title']+'</label>'+
                '<div class="col-sm-6">'+
                    '<label class="control-label">' +
                        '<i class="icon wb-file"></i>&nbsp;&nbsp;' +
                        '<a href="download-attachment/'+attach[i][0]+'" target="_blank">'+attach[i]['title'].substring(0,15)+'...'+'</a>' +
                    '</label>&nbsp;' +
                '<button type="button" class="btn btn-default btn-outline btn-xs replaceAttachment" data-target="#replaceAttach" data-toggle="modal" data-id="'+attach[i]['id']+','+attach[i]['filename']+'"><i class="icon wb-upload"></i></button>'+
                '</div>';
        }
        $('#usersAttachments').html(attachmentHtml);
        
        $(document).on("click", ".replaceAttachment", function () {
            
            var attachmentData = $(this).data('id').split(',');
            var docID = attachmentData[0];
            var docName = attachmentData[1];
            
            $("#attachmentDocID").val(docID);
            $("#replaceAttachOld").html(docName);
        });
    });
}

// End replace attachment functions


$("#savePOUpdate_btn").click(function (e) {

    e.preventDefault();
    
    if(validate() === true){
        alertify.confirm( 'Are you sure you want to submit the update?', function (e) {
            if(e){
                $("#savePOUpdate_btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/edit-po",
                    data: $("#editpo-form").serialize(),
                    cache: false,
                    success: function (response) {
                        $("#savePOUpdate_btn").prop('disabled', false);
                        //alert(response);
                        try{
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                //alertify.alert(response);
                                //resetForm()
                                alertify.success(res['message']);
                                if($('#userAction').val()=='pi_rejection_edit') {
                                    // window.location.href = _adminURL + 'buyers-piboq?po=' + poid + "&ref=" + res["lastaction"];
                                    window.location.href = _adminURL + 'buyers-piboq?po=' + poid + "&ref=" + $("#refId").val();
                                    // window.location.href = _dashboardURL;
                                }else{
                                    window.location.reload();
                                }
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (err){
                            alertify.error(response + ' Failed to process the request.', 10);
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error, 10);
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

// Validate inputs before submit
function validate() {

    if (statusLevel >= 0) {

        if ($("#poid").val() == "") {
            alertify.error("PO Number is required!");
            $("#poid").focus();
            return false;
        }
        if ($("#povalue").val() == "") {
            alertify.error("PO Value is required!");
            $("#povalue").focus();
            return false;
        } else {
            if (!Number($("#povalue").val().replace(/,/g,''))) {
                alertify.error("Not a valid value!");
                $("#povalue").focus();
                return false;
            }
        }
        if ($("#podesc").val() == "") {
            alertify.error("PO Description is required!");
            $("#podesc").focus();
            return false;
        }
        if ($("#supplier").val() == "") {
            alertify.error("Select a Supplier!");
            $("#supplier").select2('open');
            return false;
        }
        if ($("#contractref").val() == "") {
            alertify.error("Contact Reference is required!");
            $("#contractref").select2('open');
            return false;
        }
        if ($("#deliverydate").val() == "") {
            alertify.error("Need by date field is required!");
            $("#deliverydate").focus();
            return false;
        }
        if ($("#noflcissue").val() == "") {
            alertify.error("No of LC is required!");
            $("#noflcissue").focus();
            return false;
        }
        if ($("#nofshipallow").val() == "") {
            alertify.error("No of Shipment is required!");
            $("#nofshipallow").focus();
            return false;
        }
        if ($("#installbysupplier").val() == "") {
            alertify.error("This field is required!");
            $("#installbysupplier").focus();
            return false;
        }
    }

    if (statusLevel >= 1) {

        if ($("#lcdesc").val() == "") {
            alertify.error("LC description is required!");
            $("#lcdesc").focus();
            return false;
        }

        if ($("#pinum").val() == "") {
            alertify.error("PI Number is required!");
            $("#pinum").focus();
            return false;
        }
        if ($("#pivalue").val() == "") {
            alertify.error("PI Value is required!");
            $("#pivalue").focus();
            return false;
        } else {
            if (!Number($("#pivalue").val().replace(/,/g,''))) {
                alertify.error("Not a valid amount!");
                $("#pivalue").focus();
                return false;
            }
        }
        var shipmode_check = $("input[name=shipmode]:checked").val();
        if (shipmode_check == undefined) {
            alertify.error("Please select a Shipment Mode!");
            return false;
        }
        if (shipmode_check == "sea") {
            if ($("#hscsea").val() == "") {
                alertify.error("HS Code Sea is required!");
                $("#hscsea").focus();
                return false;
            }
        }
        if (shipmode_check == "air") {
            if ($("#hscode").val() == "") {
                alertify.error("HS Code Air is required!");
                $("#hscode").focus();
                return false;
            }
        }
        if (shipmode_check == "sea+air") {
            if ($("#hscode").val() == "" || $("#hscsea").val() == "") {
                if ($("#hscsea").val() == "") {
                    $("#hscsea").focus();
                }
                if ($("#hscode").val() == "") {
                    $("#hscode").focus();
                }

                alertify.error("Both HS Codes are required!");
                return false;
            }
        }
        if ($("#origin").val() == "") {
            alertify.error("Country of Origin is required!");
            $("#origin").focus();
            return false;
        }
        if ($("#negobank").val() == "") {
            alertify.error("Negotiating Bank is required!");
            $("#negobank").focus();
            return false;
        }
        if ($("#shipport").val() == "") {
            alertify.error("Port of Shipment is required!");
            $("#shipport").focus();
            return false;
        }
        if ($("#lcbankaddress").val() == "") {
            alertify.error("L/C Beneficiary & Address is required!");
            $("#lcbankaddress").focus();
            return false;
        }
        if ($("#productiondays").val() == "") {
            alertify.error("Production Days is required!");
            $("#productiondays").focus();
            return false;
        } else {
            if (!Number($("#productiondays").val())) {
                alertify.error("Not a valid number!");
                $("#productiondays").focus();
                return false;
            }
        }
        if ($("#buyercontact").val() == "") {
            alertify.error("Buyer contact is required!");
            $("#buyercontact").focus();
            return false;
        }
        if ($("#techcontact").val() == "") {
            alertify.error("Technical contact is required!");
            $("#techcontact").focus();
            return false;
        }

    }


    if ($("#maxStatus") >= 10) {

        if ($("#pidate").val() == "") {
            alertify.error("PI date is required!");
            $("#pidate").focus();
            return false;
        }
        if ($("#basevalue").val() == "") {
            alertify.error("Insurance / Base value is required!");
            $("#basevalue").focus();
            return false;
        } else {
            if (!Number($("#basevalue").val().replace(/,/g,''))) {
                alertify.error("Not a valid value format!");
                $("#basevalue").focus();
                return false;
            }
        }

    }
    if ($("#buyersEditComment").val() == "") {
        alertify.error("You have to write comments to save the changes.");
        $("#buyersEditComment").focus();
        return false;
    }

    var value = $('.required-entry').filter(function () {
        return this.value === '';
    });
    if (value.length > 0) {
        alertify.error('Please fill out Certificate title in payment terms.');
        return false;
    }
    return true;
}


// Generate new payment terms
function newPaymentTermsRow(tid, pp, cc, dd, tt, cfd, cft){

    var id = $('div#lcPaymentTermsTable').children().length+1;

    $('div#lcPaymentTermsTable').append(
        $('<div>').attr({'class':'form-group', 'id':'lcPaymentTermsRow'+id}).append(
            $('<label>').attr('class','col-sm-1 control-label text-right').html(id.toString()+'.')
        ).append(
            $('<input>').attr({'type':'hidden', 'name':'termId[]', 'value':tid.toString()})
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppPercentage[]', 'id':'ppPercentage_'+id.toString()}).val(pp)
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                '<select class="form-control" data-plugin="select2" name="ppPartName[]" id="ppPartName_'+id.toString()+'" ></select>'
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppMaturityDay[]', 'id':'ppDay_'+id.toString()}).val(dd)
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppMaturityTerm[]', 'id':'ppMaturityTerm_'+id.toString()}).append(
                    $('<option>').attr('value','').html('')
                ).append(
                    $('<option>').attr('value','104').html('N/A')
                ).append(
                    $('<option>').attr('value','9').html('Air Way Bill Date')
                ).append(
                    $('<option>').attr('value','10').html('Bill of Lading')
                ).append(
                    $('<option>').attr('value','11').html('LC Issuance')
                ).append(
                    $('<option>').attr('value','12').html('Shipment Date')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'cacFacDay[]', 'id':'cacFacDay_'+id.toString()}).val(cfd)
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<input>').attr({'type':'text', 'class':'form-control required-entry', 'name':'cacFacText[]', 'id':'cacFacText_'+id.toString()}).val(cft)
            )
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<button>').attr({'type':'button','data-toggle':'tooltip','title':'Delete this row','class':'btn btn-danger',onclick:"delTermsRow("+id+");"}).append(
                    $('<i>').attr('class','fa fa-close')
                )
            )
        )
    );

    /*$('#ppPartName_'+id.toString()).selectpicker('refresh');
    $('#ppPartName_'+id.toString()).val(cc).change();*/
    $.getJSON("api/buyers-lc-request?action=3", function (list) {
        $("#ppPartName_"+id.toString()).select2({
            data: list,
            placeholder: "select a certificate",
            allowClear: false,
            width: "100%"
        });
        $('#ppPartName_'+id.toString()).val(cc).change();
    });

    // Dynamic selection : Air Way Bill Date/Bill of Lading according to shipment mode Sea/Air
    if(tt == 9 || tt == 10){
        if(podata['shipmode']=='sea'){
            tt = 10;    // Airway Bill Date (lookup ID)
        } else if(podata['shipmode']=='air'){
            tt = 9;     // Bill of Lading (lookup ID)
        }
    }

    $('#ppMaturityTerm_'+id.toString()).selectpicker('refresh');
    $('#ppMaturityTerm_'+id.toString()).val(tt).change();

}
function delTermsRow(removeNum, e) {
    if (removeNum == 1) {
        alertify.error("You have to keep at least 1 Row");
        return false;
    } else {
        $('#lcPaymentTermsRow'+removeNum).remove();
    }
}
