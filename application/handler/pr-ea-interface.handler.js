buyersmsgtitle/*
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid, podata, comments, attach;

$(document).ready(function() {

    if ($('#poid').val() != "") {

        poid = $('#poid').val();
        $('#ponum').html(poid);

        $.get('api/purchaseorder?action=2&id=' + poid, function (data) {
            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {
                var row = JSON.parse(data);
                podata = row[0][0];
                comments = row[1];
                attach = row[2];

                // PO info
                $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
                $('#podesc').html(podata['podesc']);
                if ($('#postatus').val() == ACTION_DRAFT_PI_SENT_FOR_EA_FEEDBACK || $('#postatus').val() == ACTION_FINAL_PI_SENT_FOR_EA_FEEDBACK) {
                    $('#lcdescLabel').hide();
                    $('#lcdesc').val(podata['lcdesc']);
                } else {
                    $('#lcdesc').val(podata['lcdesc']);
                    $('#lcdesc').hide();
                    $('#lcdescLabel').show().html(podata['lcdesc']);
                }

                // $('#exp_type').val(podata['exp_type']).trigger("change");
                $('#exp_type').val(podata['exp_type']).change();
                $('#user_just').html(podata['user_just']);
                $('#short_prodName').val(podata['short_prod_name']);
                $('#supplier').html(podata['supname']);
                $('#sup_address').html(podata['supadd']);
                $('#contractref').html(podata['contractrefName']);
                $('#pr_no').html(podata['pr_no']);
                $('#department').html(podata['department']);
                $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
                $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
                $('#installbysupplier').html(getImplementedBy(podata["installbysupplier"]));
                $('#noflcissue').html(podata['noflcissue']);
                $('#nofshipallow').html(podata['nofshipallow']);

                // PI info
                $('#pinum').html(podata['pinum']);
                $('#pi_desc').html(podata['pidesc']);
                $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);

                $('#shipModeEditable').hide();
                $('#shipmodeLabel').html(podata['shipmode'].toUpperCase());
                $('#hscode').val(podata['hscode']);
                $('#hscode').hide();
                $('#hscodeLabel').show().html(podata['hscode']);

                $('#origin').html(podata['origin']);
                $('#negobank').html(podata['negobank']);
                $('#shipport').html(podata['shipport']);
                $('#lcbankaddress').html(podata['lcbankaddress']);
                $('#productiondays').html(podata['productiondays']);
                $('#buyercontact').html(podata['buyersName']);
                $('#techcontact').html(podata['prName']);

                attachmentLogScript(attach, '#usersAttachments');

                commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');

                for (var i = 0; i < attach.length; i++) {
                    //alert(attach[i]["title"]);
                    if (attach[i]["title"] == "Justification") {
                        $("#attachJustificationOld").val(attach[i]['filename']);
                        $("#attachJustificationLink").html(attachmentLink(attach[i]['filename']));
                        //$("#attachJustificationLink").html(attachmentLink(attach[i][0], attach[i]['filename']));
                    }
                }
            }
        });
    }

    // Rejection by PR or EA
    $("#RejectToBuyer_btn").click(function (e) {

        $('#userAction').val('1');
        e.preventDefault();

        if (validateReject() === true) {
            alertify.confirm('Are you sure you want to reject this PI?', function (e) {
                if (e) {
                    $("#RejectToBuyer_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#RejectToBuyer_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to proceed!");
                                    return false;
                                }
                            } catch (e) {
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

    // Accepted by PR or EA
    $("#AcceptToBuyer_btn").click(function (e) {
        //alert('sdfs');
        $('#userAction').val('2');
        e.preventDefault();
        if (validateAccept() === true) {
            alertify.confirm('Are you sure you want to accept this PI?', function (e) {
                if (e) {
                    $("#AcceptToBuyer_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#AcceptToBuyer_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            } catch (e) {
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

    // Request to Supplier to for Rectification
    $("#toSupplierRectify_btn").click(function (e) {

        $('#userAction').val('3');
        e.preventDefault();

        if (validateAccept() === true) {
            alertify.confirm("Are you sure you want to send for Supplier's rectification?", function (e) {
                if (e) {
                    $("#toSupplierRectify_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            // alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message'], 60);
                                    //window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            } catch (e) {
                                $("#toSupplierRectify_btn").prop('disabled', false);
                                alertify.error(response + ' Failed to process the request.', 30);
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

    // Request to Supplier to send Final PI
    $("#toSupplierFinalPI_btn").click(function (e) {

        $('#userAction').val('4');
        e.preventDefault();

        if (validateAccept() === true) {
            alertify.confirm('Are you sure you want to send request for Final PI?', function (e) {
                if (e) {
                    $("#toSupplierFinalPI_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#toSupplierFinalPI_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to submit final PI");
                                    return false;
                                }
                            } catch (e) {
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

    $("#toEditAndSendForPREAReCheck_btn").click(function (e) {
        e.preventDefault();
        $('#userAction').val('5');
        if (validateAccept() === true) {
            alertify.confirm('Are you sure you want to proceed for Edit?', function (e) {
                if (e) {
                    $("#toEditAndSendForPREAReCheck_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#toEditAndSendForPREAReCheck_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    // window.location.href = _dashboardURL;
                                    window.location.href = _dashboardURL + "edit-po" + "?action=pi_rejection_edit&po=" + poid + "&ref=" + res["lastaction"];
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            } catch (e) {
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
        //window.location.href = _dashboardURL+"edit-po" + "?action=pi_rejection_edit&po=" + $("#poid").val() + "&ref=" + $("#refId").val();
    });

    $("#AcceptFinalPI_btn").click(function (e) {

        $('#userAction').val('6');
        e.preventDefault();

        if (validateAcceptAsFinal() === true) {
            alertify.confirm('Are you sure you want to accept PI as Final PI?', function (e) {
                if (e) {
                    $("#AcceptFinalPI_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/pr-ea-interface",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#AcceptFinalPI_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _adminURL + "btrc-interface?po=" + poid + "&ref=" + res["lastaction"];
                                } else {
                                    alertify.error("FAILED to send!");
                                    return false;
                                }
                            } catch (e) {
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

    //Loading PO Lines
    writeDeliveredPOLines(poid);

    /*//PO LINE LOAD
    $.get("api/view-po?action=2&id="+id, function (data) {
        var res = JSON.parse(data);
        var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
            delivTotal = 0;
        //alert(rejectedLines);

        /!**
         * Delivered po lines
         *!/
        var d1 = res[0];


        $("#delivCount1").html('(' + d1.length + ')');

        if (d1.length > 0) {
            $("#dtPOLinesDelivered tbody").empty();
            // loading already delivered po lines
            for (var i = 0; i < d1.length; i++) {
                strRow = '<tr>' +
                    '<td class="text-center">' + d1[i]['lineNo'] + '</td>' +
                    '<td class="text-center">' + d1[i]['itemCode'] + '</td>' +
                    '<td class="text-left">' + d1[i]['itemDesc'] + '</td>' +
                    '<td class="text-center">' + d1[i]['poDate'] + '</td>' +
                    '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                    '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                    '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                    '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                    '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                    '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                    /!*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*!/
                    '</tr>';
                $("#dtPOLinesDelivered tbody:last").append(strRow);

                // alert(qty)
            }
        } else {
            $("#dtPOLinesDelivered tbody").empty();
            $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }

    });

    $.get('api/view-po?action=3&id='+id, function (data) {
        // console.log(data)
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            // console.log(row)
            sumdata = row[0][0];
            // console.log(commaSeperatedFormat(row['grandpoQty']))

            // PO info
            // $('#podesc').val(podata['podesc']);
            $('#poQtyTotal').html(commaSeperatedFormat(sumdata['grandpoQty']));
            $('#grandTotal').html(commaSeperatedFormat(sumdata['grandPoTotal']));
            $('#dlvQtyTotal').html(commaSeperatedFormat(sumdata['grandDelivQty']));
            $('#dlvGrandTotal').html(commaSeperatedFormat(sumdata['grandDelivTotal']));
        }
    });*/
});



//alert($('#postatus').val());
function validateAccept(){

    if($("#usertype").val()==const_role_PR_Users){
        if($("#attachJustification").val()=="" && $("#attachJustificationOld").val()=="")
    	{
    		$("#attachJustification").focus();
            alertify.error("Justification document is required!");
    		return false;
    	} else {
            if($("#attachJustification").val()!="") {
                if (!validAttachment($("#attachJustification").val())) {
                    $("#attachJustification").focus();
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
            if($("#attachJustificationOld").val()!="") {
                if (!validAttachment($("#attachJustificationOld").val())) {
                    $("#attachJustification").focus();
                    alertify.error('Invalid File Format.');
                    return false;
                }
            }
    	}
        if ($("#exp_type").val() == "") {
            $("#exp_type").focus();
            alertify.error("Select a expanse type!");
            return false;
        }
        if ($("#user_just").val() == "") {
            $("#user_just").focus();
            alertify.error("Add justification!");
            return false;
        }
        if ($("#short_prodName").val() == "") {
            $("#short_prodName").focus();
            alertify.error("Add short product name!");
            return false;
        }
    }

    return true;
}

function validateAcceptAsFinal(){

    if($("#usertype").val()==const_role_Buyer){
        if($("#messageUser").val()=="")
    	{
    		$("#messageUser").focus();
            alertify.error("Please write a comment as you are skipping Final PI vetting stage!");
    		return false;
    	}
    }

    return true;
}

function validateReject(){
    if($("#messageUser").val()==""){
        $("#messageUser").focus();
        alertify.error("Please mention rejection causes.");
		return false;
    }
    return true;
}

$(function () {

    var button = $('#btnUploadJustification'), interval;
    var txtbox = $('#attachJustification');

    if(!$(button).is(':disabled')){

        new AjaxUpload(button, {
            action: 'application/library/uploadhandler.php',
            name: 'upl',
            onComplete: function (file, response) {
                var res = JSON.parse(response);
                txtbox.val(res['filename']);
                window.clearInterval(interval);
            },
            onSubmit: function (file, ext) {
                if (!(ext && /^(doc|docx|pdf|zip)$/i.test(ext))) {
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
    }
});
