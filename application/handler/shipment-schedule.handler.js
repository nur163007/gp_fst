/*
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

var poid = $('#pono').val();
var u = $('#usertype').val();
var ship = $('#shipNo').val();
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
            //comments = row[1];
            attach = row[2];
            lcinfo = row[3][0];
            //pterms = row[4];

            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#supplier').html(podata['supname']);
            $('#sup_address').html(podata['supadd']);
            $('#contractref').html(podata['contractrefName']);
            $('#pr_no').html(podata['pr_no']);
            $('#department').html(podata['department']);
            $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
            $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
            if (podata["installbysupplier"] == 0) {
                $('#installbysupplier').html('No');
            } else {
                $('#installbysupplier').html('Yes');
            }
            $('#nofshipallow').html(podata['nofshipallow']);

            // PI info
            $('#pinum').html(podata['pinum']);


            $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);
            $('#pi_desc').html(podata['pidesc']);
            $('#producttype').html(podata['producttypeName']);
            $('#importAs').html(podata['importAsName']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#shippingMode').val(podata['shipmode']);
            $('#hscode').html(podata['hscode']);

            $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' + podata['curname']);

            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);

            //LC INFO
            if (podata['shipmode']=='E-Delivery' && podata['withLC']== 1){
                $('#noflcissue').html('N/A');
                $('#lcdesc').html('N/A');
                $('#lcno').val('N/A');
                $('#lcnum').html('N/A');
                $('#lcvalue').html('N/A');
                $('#lcshipmentType').val(podata['withLC']);
            }else {
                $('#noflcissue').html(podata['noflcissue']);
                if (lcinfo['lcdesc'] == "") {
                    $('#lcdesc').html(podata['lcdesc']);
                } else {
                    $('#lcdesc').html(lcinfo['lcdesc']);
                }
                $("#lcno").val(lcinfo['lcno']);
                $("#lcnum").html(lcinfo['lcno']);
                $("#lcvalue").html(commaSeperatedFormat(lcinfo['lcvalue']) + ' ' + podata['curname']);

            }
            $.get('api/shipment-schedule?action=1&po='+poid, function (data) {

                if ($.trim(data)) {

                    var rows = JSON.parse(data);
                    var sRow = "";
                    var shipNo = 0;
                    var deleteButton ="";

                    if ($("#usertype").val() == const_role_Supplier || $("#usertype").val() == const_role_Buyer) {
                        for (var i = 0; i < rows.length; i++) {

                            if(shipNo!==rows[i]['shipNo']) {

                                if(shipNo!==0 && shipNo!==rows[i]['shipNo']) {
                                    sRow += "</tbody>" +
                                        "</table>" +
                                        "</div> ";

                                }
                                if ($("#usertype").val() == const_role_Supplier){
                                    deleteButton="<span class=\"margin-10 pull-right\">" +
                                        "<button type='button' class='btn btn-danger btn-xs' data-target=\"#scheduleDeleteForm\" data-toggle=\"modal\" onclick='DeleteSchedule(" + rows[i]['shipNo'] + ")' ><i class='fas fa-close'></i></button>" +
                                        "</span>";
                                }
                                else {
                                    deleteButton ="";
                                }
                                sRow += "<div class=\"text-left margin-top-50\">" +
                                    "<div class=\"well well-sm example-title col-sm-12 padding-3\">" +
                                    "<h5 class=\"margin-10 pull-left\">Shipment # " + rows[i]['shipNo'] + "</h5>" +
                                     deleteButton+
                                    "</div>" +
                                    " <table class=\"table table-bordered dataTable table-striped width-full\" id='shipmentLineData'>" +
                                    "<thead>" +
                                    "<tr>" +
                                    "<th>PO#</th>" +
                                    "<th>Ship#</th>" +
                                    "<th>Line#</th>" +
                                    "<th>ItemCode</th>" +
                                    "<th>ItemDesc</th>" +
                                    "<th>Shipment Schedule</th>" +
                                    "<th>UOM</th>" +
                                    "<th>Unit Price</th>" +
                                    "<th>Delivery Qty</th>" +
                                    "<th>Total</th>" +
                                    "</tr>" +
                                    "</thead>" +
                                    "<tbody>" +
                                    "<tr>" +
                                    "<td>" + rows[i]['poNo'] + " </td>" +
                                    "<td>" + rows[i]['shipNo'] + " </td>" +
                                    "<td>" + rows[i]['lineNo'] + " </td>" +
                                    "<td>" + rows[i]['itemCode'] + " </td>" +
                                    "<td>" + rows[i]['itemDesc'] + " </td>" +
                                    "<td>" + rows[i]['scheduleETA'] + " </td>" +
                                    "<td>" + rows[i]['UOM'] + " </td>" +
                                    "<td>" + rows[i]['unitPrice'] + " </td>" +
                                    "<td>" + rows[i]['delivQty'] + " </td>" +
                                    "<td>" + rows[i]['delivTotal'] + " </td>" +
                                    "</tr>";
                                //$("#proposedShipLine").append(sRow);
                                shipNo = rows[i]['shipNo'];
                            } else {
                                sRow += "<tr>" +
                                    "<td>" + rows[i]['poNo'] + " </td>" +
                                    "<td>" + rows[i]['shipNo'] + " </td>" +
                                    "<td>" + rows[i]['lineNo'] + " </td>" +
                                    "<td>" + rows[i]['itemCode'] + " </td>" +
                                    "<td>" + rows[i]['itemDesc'] + " </td>" +
                                    "<td>" + rows[i]['scheduleETA'] + " </td>" +
                                    "<td>" + rows[i]['UOM'] + " </td>" +
                                    "<td>" + rows[i]['unitPrice'] + " </td>" +
                                    "<td>" + rows[i]['delivQty'] + " </td>" +
                                    "<td>" + rows[i]['delivTotal'] + " </td>" +
                                    "</tr>";
                                //$("#proposedShipLine").append(sRow);
                            }

                        }
                        if(shipNo!==0) {
                            sRow += "</tbody>" +
                                "</table>" +
                                "</div> ";
                            $("#proposedShipLine").html(sRow);
                        }
                    }
                }
            });

        }
    });


    if(poid!="") {

        LoadPOLines();
        // }
        /**
         * ---------------------------------------------------------------------
         * Control Events Binding END
         * ---------------------------------------------------------------------
         */
        $(document).on('keyup', '.unitPrice, .poQty', function () {
            poLineTotal(this);
            poGrandTotal();
        });

        $(document).on('keyup blur', '.delivQty', function (e) {
            //alert(1);
            poLineDelivTotal(this);
            poGrandTotal();
            // calculateInvoiceAmount($("#poNo").val());
        });

        /*************************************************************************
         * One click action
         *************************************************************************/
        $('#chkAllLine').click(function (e) {
            if (this.checked) {
                $('.chkLine').prop('checked', true);
            } else {
                $('.chkLine').prop('checked', false);
            }

            $("#dtPOLines tbody").find('tr').each(function () {

                if ($(this).find('input.chkLine').is(':checked')) {
                    $(this).find('input.delivQty').val($(this).find('input.delivQtyValid').val());
                    $(this).find('input.delivTotal').val($(this).find('input.delivAmountValid').val());
                } else {
                    $(this).find('input.delivQty').val(0);
                    $(this).find('input.delivTotal').val(0);
                }
            });
            poGrandTotal();
            // calculateInvoiceAmount($("#poNo").val());
        });

        $(document).on('click', '.chkLine', function () {
            if (!this.checked) {
                $('#chkAllLine').prop('checked', false);
                $(this).parent().parent().parent().find('input.delivQty').val(0);
            } else {
                var d = $(this).parent().parent().parent().find('input.delivQtyValid').val();
                $(this).parent().parent().parent().find('input.delivQty').val(d);
            }
            poLineWisePoTotal();
            poGrandTotal();

            $('#reg_fee').val("");
        });

        $('#reject-message').hide();

    }

    //SCHEDULE CREATE
    $("#scheduleCreate_btn").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want to Submit?', function (e) {
                if (e) {
                    $("#scheduleCreate_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/shipment-schedule",
                        data: $('#shipment-schedule-form').serialize() + "&userAction=1",
                        cache: false,
                        success: function (response) {
                            $("#scheduleCreate_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                    window.location.href = _adminURL + "shipment-schedule?po=" + poid + "&ref=" + res["lastaction"];
                                } else {
                                    alertify.error(res['message'], 20);
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
    //SCHEDULE SUBMIT
    $("#scheduleSubmit_btn").click(function (e) {
        e.preventDefault();
            alertify.confirm('Are you sure you want to Submit?', function (e) {
                if (e) {
                    $("#scheduleSubmit_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/shipment-schedule",
                        data: $('#shipment-schedule-form').serialize() + "&userAction=2",
                        cache: false,
                        success: function (response) {
                            $("#scheduleSubmit_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(res['message'], 20);
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
    });

//    SHIPMENT ACCEPTANCE
    $("#btn_AcceptSchedule").click(function (e) {
        e.preventDefault();
        alertify.confirm('Are you sure you want to accept this/these schedule(s)?', function (e) {
            if (e) {
                $("#btn_AcceptSchedule").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/shipment-schedule",
                    data: "userAction=3&refId=" + $("#refId").val() + "&pono=" + $("#pono").val() + "&comments=" + $("#comments").val(),
                    cache: false,
                    success: function (response) {
                        $("#btn_AcceptSchedule").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);

                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED!");
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
    });

});
function getShipmentNumber(){
    var shipNum = $('#shipmentNo').val();
    $.get('api/shipment-schedule?action=4&pono='+poid, function (data) {

        if ($.trim(data)) {

            var rows = JSON.parse(data);
            var ship = 0;
            // console.log(rows)
            // console.log(rows.length)
            $("#shipmentNo").closest("div.form-group").removeClass('has-warning');
            $("#shipmentWarning").addClass("hidden");
            $('#margeShipment').val(0);
            $('#shipmentSchedule_1').val("");
                for (var i = 0; i < rows.length; i++) {

                        if (shipNum == rows[i]['shipNo']) {
                            $("#shipmentNo").closest("div.form-group").addClass('has-warning');
                            $("#shipmentWarning").removeClass("hidden");
                            $('#margeShipment').val(1);
                            $('#shipmentSchedule_1').val(rows[i]['scheduleETA']);
                            return false;
                        }/* else {
                            $("#shipmentNo").closest("div.form-group").removeClass('has-warning');
                            $("#shipmentWarning").addClass("hidden");
                        }*/

            }

        }
    });

}

function validate() {

    if ($("#shipmentNo").val() == "") {
        $("#shipmentNo").focus();
        alertify.error("Choose a Shipment No!");
        return false;
    }
    if ($("#shipmentSchedule_1").val() == "") {
        $("#shipmentSchedule_1").focus();
        alertify.error("Shipment schedule is required!");
        return false;
    }



    poLineVerify();

    if (!poLinesOkay) {
        return false;
    }
    return true;
}

$('#shipmentSchedule_1').datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});

function LoadPOLines(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/shipment-schedule?action=3&pono=" + poid, function (data) {
        var res = JSON.parse(data);
        if (res[1].length > 0) {
            var rejectedLines = (res[1][0]["rejectedlines"]).split(",");
            /*
             * Message for rejected lines
             * */
            $('#reject-message').show();
            //document.getElementById("rejected-line").innerHTML = rejectedLines;
            $("#rejected-line").html(rejectedLines);

        } else {
            rejectedLines = "";
            $('#reject-message').hide();
        }
        //alert(rejectedLines);

        /**
         * Deliverable po lines
         */
        var d2 = res[0];
        $("#delivCount2").html('(' + d2.length + ')');
        if (d2.length > 0) {
            $("#dtPOLines tbody").empty();

            $('#bpo').val(d2[0]["buyersPo"]);
            $('#piReqNo').val(d2[0]["PIReqNo"]);
            $('#ciAmountCur').html(d2[0]["currencyName"]);

            for (var j = 0; j < d2.length; j++) {
                if (rejectedLines != "") {
                    if (rejectedLines.indexOf(d2[j]["lineNo"]) < 0) {
                        addPOLine(d2[j]);
                    }
                } else {
                    addPOLine(d2[j]);
                }
            }
            $('#chkAllLine').prop('checked', true);
            $('.chkLine').prop('checked', true);

            poGrandTotal();
            $("#shareButton").hide();

        } else {
            $("#dtPOLines tbody").empty();
            $("#piLineHide").hide();
            $("#clauseControl").hide();
            $("#shareButton").show();
            $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }
        // ldGrandTotal();
    });
}

function LoadDeliveredPOLinesInEditMode(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/shipment-schedule?action=3&pono=" + poid, function (data) {
        var res = JSON.parse(data);
        if (res[1].length > 0) {
            var rejectedLines = (res[1][0]["rejectedlines"]).split(",");
            /*
             * Message for rejected lines
             * */
            $('#reject-message').show();
            //document.getElementById("rejected-line").innerHTML = rejectedLines;
            $("#rejected-line").html(rejectedLines);

        } else {
            rejectedLines = "";
            $('#reject-message').hide();
        }
        //alert(rejectedLines);

        /**
         * Deliverable po lines
         */
        var d2 = res[0];
        $("#delivCount2").html('(' + d2.length + ')');
        if (d2.length > 0) {
            $("#dtPOLines tbody").empty();

            $('#bpo').val(d2[0]["buyersPo"]);
            $('#piReqNo').val(d2[0]["PIReqNo"]);
            $('#ciAmountCur').html(d2[0]["currencyName"]);

            for (var j = 0; j < d2.length; j++) {
                if (rejectedLines != "") {
                    if (rejectedLines.indexOf(d2[j]["lineNo"]) < 0) {
                        addPOLine(d2[j]);
                    }
                } else {
                    addPOLine(d2[j]);
                }
            }
            $('#chkAllLine').prop('checked', true);
            $('.chkLine').prop('checked', true);

            poGrandTotal();
            $("#shareButton").hide();
        } else {
            $("#dtPOLines tbody").empty();
            $("#piLineHide").hide();
            $("#clauseControl").hide();
            $("#shareButton").show();
            $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }
        // ldGrandTotal();
    });
}

// function vatAmount(pa, vr) {
//     return pa*(vr/100);
// }

function poLineTotal(elm) {

    var linePrice, lineQty, lineTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = linePrice * lineQty;

    $(elm).closest('tr').find('input.lineTotal').val(commaSeperatedFormat(lineTotal));
    $(elm).closest('tr').find('input.lineTotal').attr('title', commaSeperatedFormat(lineTotal));

}

function poLineDelivTotal(elm) {
    var linePrice, lineDelivQty, lineDelivTotal, lineDelivQtyValid;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineDelivQty = parseToCurrency($(elm).closest('tr').find('input.delivQty').val());
    lineDelivQtyValid = parseToCurrency($(elm).closest('tr').find('input.delivQtyValid').val());

    lineDelivTotal = linePrice * lineDelivQty;

    $(elm).closest('tr').find('input.delivTotal').val(lineDelivTotal.toFixed(2));
    $(elm).closest('tr').find('input.delivTotal').attr('title', lineDelivTotal.toFixed(2));
    // calculateInvoiceAmount($("#poNo").val());

    if(lineDelivQty>lineDelivQtyValid){
        $(elm).closest('tr').find('input.delivQty').addClass('has-txt-error');
        $(elm).closest('tr').find('input.delivQty').focus();
    } else {
        $(elm).closest('tr').find('input.delivQty').removeClass('has-txt-error');
    }
}

function poLineDelivQty(elm) {

    var lineQty, lineTotal, lineDelivQty, lineDelivTotal;

    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = parseToCurrency($(elm).closest('tr').find('input.lineTotal').val());
    lineDelivTotal = parseToCurrency($(elm).closest('tr').find('input.delivTotal').val());

    lineDelivQty = lineQty / (lineTotal / lineDelivTotal);
    $(elm).closest('tr').find('input.delivQty').val(commaSeperatedFormat(lineDelivQty, 4));
    $(elm).closest('tr').find('input.delivQty').attr('title', commaSeperatedFormat(lineDelivQty, 4));
}

function poLineWisePoTotal() {

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        poLineDelivTotal(this);

    });
}

function poGrandTotal() {

    var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
        delivTotal = 0;

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        qty = parseToCurrency($(this).find('input.poQty').val());
        totalQty += qty;
        $("#poQtyTotal").val(totalQty);

        totalPrice = parseToCurrency($(this).find('input.lineTotal').val());
        grandTotal += totalPrice;
        $("#grandTotal").val(+(grandTotal).toFixed(2));

        if ($(this).find('input.chkLine').is(':checked')) {
            delivQty = parseToCurrency($(this).find('input.delivQty').val());
        } else {
            delivQty = 0;
        }
        totalDelivQty += delivQty;
        $("#dlvQtyTotal").val(+(totalDelivQty).toFixed(12));
        //alert(totalDelivQty);

        if ($(this).find('input.chkLine').is(':checked')) {
            delivPrice = parseToCurrency($(this).find('input.delivTotal').val());
        } else {
            delivPrice = 0;
        }
        delivTotal += delivPrice;
        $("#dlvGrandTotal").val(+(delivTotal).toFixed(2));
        $("#ciAmount").val(commaSeperatedFormat(delivTotal)).change();
        // $("#invAmount").val(commaSeperatedFormat(delivTotal));
        // $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
        //calculateInvoiceAmount()
    });
}

function addPOLine(row) {
    var i = $("#dtPOLines tbody tr").length;

    row = row || '';

    if (row == '') {
        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '">' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" /></td>' +
            '<td><input type="text" class="form-control input-sm uom" /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="0" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="0" /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="0" readonly /></td>' +
            '</tr>');
    } else {
        //@todo analyze here
        //console.log(row["status"]);
        if (!row["status"]) {
            var addTick = "";
            /*var voidDelivQty = 0;
            var voidDelivAmount = 0*/
            var voidDelivQty = row["delivQtyValid"];
            var voidDelivAmount = row["delivAmountValid"]
        } else {
            addTick = "checked";
            voidDelivQty = row["delivQtyValid"];
            voidDelivAmount = row["delivAmountValid"];
        }

        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '" ' + addTick + ' >' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" name="poLine[]" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" name="poItem[]" value="' + row["itemCode"] + '" title="' + row["itemCode"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" name="poDesc[]" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" title="' + row["itemDesc"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" name="poDate[]" value="' + row["deliveryDate"] + '" title="' + row["deliveryDate"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm uom" name="uom[]" value="' + row["uom"] + '" title="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" name="unitPrice[]" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" name="poQty[]" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" name="lineTotal[]" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty"  name="delivQty[]" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" /><input type="hidden" name="delivQtyValid[]" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" name="delivTotal[]" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
            '</tr>');
    }
}

/*!
* PO lines verification
* */
function poLineVerify() {
    poLinesOkay = true;
    $("#consolidatedPoLines").val("");
    $("#dtPOLines tbody").find('tr').each(function () {

        if ($("#consolidatedPoLines").val() != "") {
            $("#consolidatedPoLines").val($("#consolidatedPoLines").val() + "|");
        }

        if ($(this).find('input.chkLine').is(':checked')) {

            if ($(this).find('input.poLine').val() == "") {
                alertify.error('PO line number missing!');
                $(this).find('input.poLine').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poItem').val() == "") {
                alertify.error('Item code missing!');
                $(this).find('input.poItem').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poDesc').val() == "") {
                alertify.error('Item description missing!');
                $(this).find('input.poDesc').focus();
                poLinesOkay = false;
                return false;
            }
            /*if ($(this).find('input.projCode').val() == "") {
             alertify.error('Project code missing!');
             $(this).find('input.projCode').focus();
             poLinesOkay = false;
             return false;
             }*/
            if ($(this).find('input.uom').val() == "") {
                alertify.error('UOM missing!');
                $(this).find('input.uom').focus();
                poLinesOkay = false;
                return false;
            }
            /*!
            * O value allowed in
            * Unit Price, PO line total, Delivered price
            * as per the mail ref: FMfcgxwKjKqwmqZcjBQZSfQhhFFQKGSK
            * *************************************************************/
            if ($(this).find('input.unitPrice').val() == "" || parseFloat($(this).find('input.unitPrice').val()) < 0) {
                alertify.error('Unit price missing!');
                $(this).find('input.unitPrice').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poQty').val() == "" || parseFloat($(this).find('input.poQty').val()) <= 0) {
                alertify.error('PO line qty missing!');
                $(this).find('input.poQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.lineTotal').val() == "" || parseFloat($(this).find('input.lineTotal').val()) < 0) {
                alertify.error('PO line total missing!');
                $(this).find('input.lineTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivQty').val() == "" || parseFloat($(this).find('input.delivQty').val()) <= 0) {
                alertify.error('Delivered qty missing!');
                $(this).find('input.delivQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivTotal').val() == "" || parseFloat($(this).find('input.delivTotal').val()) < 0) {
                alertify.error('Delivered price missing!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if (parseToCurrency($(this).find('input.delivAmountValid').val()) < parseToCurrency($(this).find('input.delivTotal').val())) {
                //alertify.error('Delivered amount can not be grater then PO amount!');
                alertify.error('Invalid delivered qty or amount!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.ldAmnt').val() == "") {
                $(this).find('input.ldAmnt').val('0');
            }
// alert("find");
            var poDate = ($(this).find('input.poDate').val()) ? $(this).find('input.poDate').val() : "NA";
            //var projectCode = (1===1) ? 'test' : 'empty' ;
            $("#consolidatedPoLines").val(
                $("#consolidatedPoLines").val() + $(this).find('input.poLine').val() + ";" +
                $(this).find('input.poItem').val() + ";" +
                $(this).find('input.poDesc').val() + ";" +
                //$(this).find('input.projCode').val() + ";" +
                poDate + ";" +
                $(this).find('input.uom').val() + ";" +
                $(this).find('input.unitPrice').val() + ";" +
                $(this).find('input.poQty').val() + ";" +
                $(this).find('input.lineTotal').val() + ";" +
                $(this).find('input.delivQty').val() + ";" +
                $(this).find('input.delivQtyValid').val() + ";" +
                $(this).find('input.delivTotal').val()
            )
        }
    });
}

function DeleteSchedule(shipNo) {
    $("#btnScheduleDelete").click(function (e) {
        e.preventDefault();
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/shipment-schedule",
                    data: "userAction=4&refId=" + $("#refId").val() + "&pono=" + poid + "&ship="+shipNo,
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                $('#scheduleDeleteForm').modal('hide');
                                window.location.href = _adminURL + "shipment-schedule?po=" + poid + "&ref=" + res["lastaction"];
                            } else {
                                alertify.error(res['message'], 20);
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
            }else {

            }
    });
}



