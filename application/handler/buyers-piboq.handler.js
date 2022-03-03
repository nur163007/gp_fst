/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var poid, podata, comments, attach;

$(document).ready(function() {

    if ($('#poid').val() != "") {

        var poid = $('#poid').val();
        $('#ponum').html(poid);

        $.get('api/purchaseorder?action=2&id=' + poid, function (data) {

            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found. Error: ${data}</h4>`);
            } else {

                var row = JSON.parse(data);
                podata = row[0][0];
                comments = row[1];
                attach = row[2];

                // PO info
                $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
                $('#podesc').html(podata['podesc']);
                //alert(podata['lcdesc']);
                if ($('#postatus').val() == ACTION_DRAFT_PI_SUBMITTED || $('#postatus').val() == ACTION_FINAL_PI_SUBMITTED) {
                    $('#lcdescLabel').hide();
                    $('#lcdesc').val(podata['lcdesc']);
                } else {
                    $('#lcdesc').val(podata['lcdesc']);
                    $('#lcdesc').hide();
                    $('#lcdescLabel').show().html('<b>' + podata['lcdesc'] + '</b>');
                }

                $('#supplier').html(podata['supname']);
                $('#sup_address').html(podata['supadd']);
                $('#contractref').html(podata['contractrefName']);
                $('#pr_no').html(podata['pr_no']);
                $('#department').html(podata['department']);
                $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
                $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
                $('#buyercontact').html(podata['buyersName']);
                $('#techcontact').html(podata['prName']);
                $('#installbysupplier').html(getImplementedBy(podata["installbysupplier"]));
                $('#noflcissue').html(podata['noflcissue']);
                $('#nofshipallow').html(podata['nofshipallow']);

                // PI info
                $('#pinum').html(podata['pinum']);
                $('#piReqNoText').html(podata['PIReqNo']);
                $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);
                $('#pi_desc').html(podata['pidesc']);
                $('#producttype').html(podata['producttypeName']);
                $('#importAs').html(podata['importAsName']);
                $('#shipmode').html(podata['shipmode'].toUpperCase());

                if (podata['shipmode'] == 'sea') {
                    $('#shipHSCsea').show();
                }
                if (podata['shipmode'] == 'air') {
                    $('#shiphscode').show();
                }
                if (podata['shipmode'] == 'sea+air') {
                    $('#shipHSCsea').show();
                    $('#shiphscode').show();
                }

                $('#hscode').html(podata['hscode']);

                if ($('#postatus').val() >= 9) {
                    $('#pidate').html(Date_toDetailFormat(new Date(podata['pidate'])));
                    $('#basevalue').html(commaSeperatedFormat(podata['basevalue']));
                }

                $('#origin').html(podata['origin']);
                $('#negobank').html(podata['negobank']);
                $('#shipport').html(podata['shipport']);
                $('#lcbankaddress').html(podata['lcbankaddress']);
                $('#productiondays').html(podata['productiondays']);

                attachmentLogScript(attach, '#usersAttachments');
                commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');
            }
        });
    }


    $("#messageToPRUserYes").click(function (event) {
        if ($(this).is(":checked"))
            $(".isDefMessageToPRUser").show();
        else
            $(".isDefMessageToPRUser").hide();
    });
    $("#messageToEATeamYes").click(function (event) {
        if ($(this).is(":checked"))
            $(".isDefMessageToEATeam").show();
        else
            $(".isDefMessageToEATeam").hide();
    });

    $("#buyersMsgToSupplierYes").click(function (event) {
        if ($(this).is(":checked"))
            $(".isBuyersMsgToSupplier").show();
        else
            $(".isBuyersMsgToSupplier").hide();
    });

    $("#btnSendForPREAFeedback").click(function (e) {

        $('#userAction').val('1');
        e.preventDefault();

        if (validate() === true) {
            alertify.confirm('Are you sure you want to proceed?', function (e) {
                if (e) {
                    $("#btnSendForPREAFeedback").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyers-piboq",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#btnSendForPREAFeedback").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    //alert(result);
                                    //resetForm()
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

    $("#AcceptFinalPI_btn").click(function (e) {

        $('#userAction').val('2');
        e.preventDefault();

        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function (e) {
                if (e) {
                    $("#AcceptFinalPI_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyers-piboq",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#AcceptFinalPI_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //alert(result);
                                    //resetForm()
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

    $("#SendToReCheck_btn").click(function (e) {

        $('#userAction').val('3');
        e.preventDefault();

        if (validate() === true) {
            alertify.confirm('Are you sure you want submit for recheck?', function (e) {
                if (e) {
                    $("#SendToReCheck_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/buyers-piboq",
                        data: $('#draftpiboq-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#SendToReCheck_btn").prop('disabled', false);
                            // alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    //alert(result);
                                    //resetForm()
                                    // alertify.success(res['message']);
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

    //PO INFO LOAD
    var id = $('#poid').val();

    //PO LINE LOAD
    $.get("api/view-po?action=2&id="+id, function (data) {
        var res = JSON.parse(data);
        var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
            delivTotal = 0;
        //alert(rejectedLines);

        /**
         * Delivered po lines
         */
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
                    '<td class="text-center">' + d1[i]['deliveryDate'] + '</td>' +
                    '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                    '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                    '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                    '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                    '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                    '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                    /*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*/
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
    });
});
//alert($('#postatus').val());
function validate(){
    
    if($("#lcdesc").val()=="")
	{
		$("#lcdesc").focus();
        alertify.error("LC Description is required!");
		return false;
	}
    
    return true;
}
