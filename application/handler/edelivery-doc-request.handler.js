/*
	Author: A'qa Technology
    Code by: Shohel Iqbal
    Date: 13.01.2022
*/

var poid = $('#pono').val();

var podata,
    comments,
    attach

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
            if (typeof (row[3]) != 'undefined') {
                lcinfo = row[3][0];
                //alert(lcinfo["lcdesc"]);
                pterms = row[4];
            }

            // Start PO & PI Information-------------------------------------------------------
            $('#ponum').html(podata['poid']);
            $('#povalue').html(commaSeperatedFormat(podata['povalue']));
            $('#currency').html(podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#lcdesc').html('<b>' + HTMLDecode(podata['lcdesc']) + '</b>');

            $('#supplier').html(podata['supname']).attr('data-value', podata['supname']);
            $('#sup_address').html(podata['supadd']);
            $('#pr_no').html(podata['pr_no']);
            $('#department').html(podata['department']);

            $('#contractref').html(podata['contractrefName']);
            $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
            $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
            $('#installbysupplier').html(getImplementedBy(podata["installbysupplier"]));
            $('#noflcissue').html(podata['noflcissue']).attr('data-value', podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']).attr('data-value', podata['nofshipallow']);

            if($('#roleId').val()==const_role_lc_bank){
                var attachList = ["Request for BASIS Approval Letter"];
                attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            } else if($('#poAction').val()==112){
                var attachList = ["BASIS Letter for Approval of Import"];
                attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            } else {
                attachmentLogScript(attach, '#usersAttachments');
            }


            commentsLogScript(comments, '#buyersmsg', '');

            // PI info
            $('#pinum').html(podata['pinum']);
            $('#pi_desc').html(podata['pidesc']);

            $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());

            if (podata['shipmode'] == 'sea') {
                $("#forAirShipment1").val("");
                $("#forAirShipment2").val("");
            }
            if (podata['shipmode'] == 'air') {
                $("#forSeaShipment1").val("");
                $("#forSeaShipment2").val("");
            }

            if (podata['shipmode'] == 'sea') {
                $('#shippingRemarks').val($('#shippingRemarks').val().replace("XXXX", "Bill of Lading"));
            }
            if (podata['shipmode'] == 'air') {
                $('#shippingRemarks').val($('#shippingRemarks').val().replace("XXXX", "Air Way Bill"));
            }

            $('#hscode').html(podata['hscode']);

            $('#pidate').html(Date_toDetailFormat(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']));
            $('#basecurrency').html(podata['curname']);

            $('#origin').html(podata['origin']).attr('data-value', podata['origin']);
            $('#negobank').html(HTMLDecode(podata['negobank']));
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays'] + ' days');
            $('#buyercontact').html(podata['buyersName']);
            $('#techcontact').html(podata['prName']);

            //Loading PO Lines
            writeDeliveredPOLines(poid);
            // End PO & PI Information-------------------------------------------------------
        }
    });

    // Submit Letter request
    $("#btnSendBASISDocRequest").click(function(e){
        e.preventDefault();
        if(validateBuyersRequest()){
            alertify.confirm( 'Are you sure you want send this request?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/edelivery-doc-request",
                        data: $('#formEDeliveryDoc').serialize()+"&userAction=1",
                        cache: false,
                        success: function (response) {
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            }catch (e) {
                                console.log(e);
                                alertify.error(' Failed to process the request.');
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

    if($("#roleId").val()==const_role_LC_Operation) {
        $.getJSON("api/company?action=4&type=118", function (list) {
            $("#letterIssuerBank").select2({
                data: list,
                placeholder: "Select a Bank",
                allowClear: false,
                width: "100%"
            });
        });
    }

    $("#btnSendRequestToBank").click(function(e){
        e.preventDefault();
        if(validateTFORequest()){
            alertify.confirm( 'Are you sure you want send this request?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/edelivery-doc-request",
                        data: $('#formEDeliveryDoc, #formTFORequestToBank').serialize()+"&userAction=2",
                        cache: false,
                        success: function (response) {
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(response + "FAILED to send!");
                                    return false;
                                }
                            }catch (e) {
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

    $("#btnSendApprovalToTFO").click(function(e){
        e.preventDefault();
        if(validateSendApprovalToTFO()){
            alertify.confirm( 'Are you sure you want send this approval?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/edelivery-doc-request",
                        data: $('#formEDeliveryDoc, #formSendApprovalToTFO').serialize()+"&userAction=3",
                        cache: false,
                        success: function (response) {
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(response + "FAILED to send!");
                                    return false;
                                }
                            }catch (e) {
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

    $("#btnShareBASISApproval").click(function(e){
        e.preventDefault();
        if(validateBuyersRequest()){
            alertify.confirm( 'Are you sure you want send this approval?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/edelivery-doc-request",
                        data: $('#formEDeliveryDoc').serialize()+"&userAction=4",
                        cache: false,
                        success: function (response) {
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(response + "FAILED to send!");
                                    return false;
                                }
                            }catch (e) {
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

});

function validateBuyersRequest(){
    return true;
}

function validateTFORequest(){
    if($("#letterIssuerBank").val()===""){
        $("#letterIssuerBank").focus();
        alertify.error("Please select letter Issuer Bank!")
        return false;
    }
    if($("#attachRequestLetter").val()===""){
        $("#attachRequestLetter").focus();
        alertify.error("Please attach Request Letter!")
        return false;
    }
    return true;
}

function validateSendApprovalToTFO(){
    if($("#attachBasisApproval").val()===""){
        $("#attachBasisApproval").focus();
        alertify.error("Please attach Approval Letter!")
        return false;
    }
    return true;
}

if($("#roleId").val()==const_role_LC_Operation) {
    if($('#poAction').val()!=112) {
        $(function () {

            var button = $('#btnUploadRequestLetter'), interval;
            var txtbox = $('#attachRequestLetter');

            new AjaxUpload(button, {
                action: 'application/library/uploadhandler.php',
                name: 'upl',
                onComplete: function (file, response) {
                    var res = JSON.parse(response);
                    txtbox.val(res['filename']);
                    window.clearInterval(interval);
                },
                onSubmit: function (file, ext) {
                    if (!(ext && /^(jpg|png|pdf)$/i.test(ext))) {
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
}

if($("#roleId").val()==const_role_lc_bank) {
    $(function () {

        var button = $('#btnUploadBasisApproval'), interval;
        var txtbox = $('#attachBasisApproval');

        new AjaxUpload(button, {
            action: 'application/library/uploadhandler.php',
            name: 'upl',
            onComplete: function (file, response) {
                var res = JSON.parse(response);
                txtbox.val(res['filename']);
                window.clearInterval(interval);
            },
            onSubmit: function (file, ext) {
                if (!(ext && /^(jpg|png|pdf)$/i.test(ext))) {
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