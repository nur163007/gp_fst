/**
 * Created by Shohel Iqbal on 11/17/2017.
 * Updated by: Hasan Masud
 * Updated on: 2020-08-26
 * Added js fetch to get company information
 */

var telRequired = false, ldRequired = 0;
var pono = $("#ponum").val(),
    ship = $("#ship").val(),
    serviceType = $("#serviceType").val(),
    oldDraft = false,
    oldReq = false;
var req, poline;

$(document).ready(function () {

    /*!
    * Call the form submit confirmation modal
    * ***************************************/

    /*!
    * Submit delivery notification
    * *****************************/
    //$("#btnSubmitRequest").one('click', function(e){
/*    $("#btnSubmitRequest").click(function(e){
        e.preventDefault();
        $("#btnSubmitRequest").prop('disabled', true);
        $('#checklistModal').modal('hide');
        $.ajax({
            type: "POST",
            url: "api/delivery-notification",
            data: $('#form_delivery_notice').serialize() + "&action=1",
            cache: false,
            success: function (response) {
                //alert(response);
                $("#btnSubmitRequest").prop('disabled', false);
                try {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message'], 20);
                        window.location.href = "my-pending";
                        return true;
                    } else {
                        alertify.error(res['message'], 20);
                        return false;
                    }
                } catch (error) {
                    console.log(error);
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            },
            error: function (xhr, textStatus, error) {
                $("#btnSubmitRequest").prop('disabled', false);
                alertify.error(textStatus + ": " + xhr.status + " " + error, 20);
            }
        });
    });*/


    /*!
    * Draft delivery notification
    * var requestSuccess,
    * make sure this gets reset to false right before AJAX call
    * *********************************************************/

/*    $("#btnSubmitRequestDraft").click(function (e) {
        e.preventDefault();
        if (validateDraft() === true) {
            var requestSuccess = false;
            alertify.confirm('Are you sure you want to save as draft?', function (e) {
                if (e) {
                    $("#btnSubmitRequestDraft").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/delivery-notification",
                        data: $('#form_delivery_notice').serialize() + "&action=2",
                        cache: false,
                        success: function (response, status, xhr) {
                            $("#btnSubmitRequestDraft").prop('disabled', false);
                            try {
                                requestSuccess = true;
                                // alert(html);
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = "draft-request";
                                    return true;
                                } else {
                                    alertify.error(res['message']);
                                    return false;
                                }
                            } catch (error) {
                                console.log(error);
                                alertify.error(response + ' Failed to process the request.');
                                return false;
                            }
                        },
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error);
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });*/

  /*  $("#poNo").change(function () {
        //e.preventDefault();
        $("#reg_fee").removeAttr("readonly");
        if ($("#poNo").val() != "" && oldReq == false && oldDraft == false) {
            $("#poValueBDT").val();
            $("#baseAmount").val();
            loadPOLines();

            if (isVatIncluded($("#poNo").val())) {
                $("#vat-calculation").addClass('hidden');
            } else {
                $("#vat-calculation").removeClass('hidden');
                $("#vatRate").change(function () {
                    calculateInvoiceAmount($("#poNo").val());
                });
            }
            getPoInfo($("#poNo").val());
        }
    });*/

    $(document).on('keyup', '.unitPrice, .poQty', function () {
        poLineTotal(this);
        poGrandTotal();
    });

    $(document).on('keyup blur', '.delivQty', function (e) {
        //alert(1);
        poLineDelivTotal(this);
        poGrandTotal();
        calculateInvoiceAmount($("#poNo").val());
    });

    //Deduct registration fee from invoice amount
    $('#reg_fee').on("input paste", function(e) {
        e.preventDefault();
        var dInput = this.value;
        if (dInput != "") {
            //$('#invAmount').val($('#dlvGrandTotal').val() - dInput);
            calculateInvoiceAmount(pono);
        }
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
        calculateInvoiceAmount($("#poNo").val());
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
        calculateInvoiceAmount($("#poNo").val());
        /*var cIndex = this.id.substr(this.id.indexOf("_") + 1, 1);

        if ($("#dlvQty_" + cIndex).val() == 0) {
            $("#dlvQty_" + cIndex).val($("#poQty_" + cIndex).val());
        }
        doCalculation();*/
        $('#reg_fee').val("");
    });

    $("#dlvQtyAll").keyup(function () {
        $(".delivQty").val($("#dlvQtyAll").val());
        $(".delivQty").attr("title", $("#dlvQtyAll").val());
        poLineWisePoTotal();
        poGrandTotal()
        //doCalculation();
    });

    $('#reject-message').hide();

    loadPOLines();
});

/*DATEPICKER CUSTOMIZATION
 **************************/
$("#actualDelivDate").datepicker({
    format: 'dd-M-yyyy',
    todayHighlight: true,
    orientation: 'auto',
    autoclose: true
});
/*$("#invDate, #needByDate, #poDate").datepicker({
    format: 'dd-M-yyyy',
    todayHighlight: true,
    autoclose: true
});*/
$("#invDate, #needByDate, #poDate").datepicker({enableOnReadonly: false});

/**
 * Remove color from radio button
 * */
$("input[name='incType'], input[name='deductAuth']").on('ifChanged', function (event) {

    //Check if checkbox is checked or not
    var checkboxChecked = $(this).is(':checked');
    //alert(this.value);

    if (checkboxChecked) {
        $("#incTypeArea").removeClass("mandatory");
        $("#deductAuthArea").removeClass("mandatory");
    }
});

/*!
* *****************/
function getPoInfo() {
    fetch(`api/delivery-notification?action=3&poNo=600067572PI1`)
        .then(
            function (response) {
                if (response.status !== 200) {
                    alertify.error(`Problem while fetching data.<br>Status Code: ${response.status} <br> Error: ${response.statusText}`, 20);
                    return;
                }
                response.json().then(function (data) {
                    //console.log(data);
                    $("#poValueBDT").val(commaSeperatedFormat(data[0].poValue));
                    if ($("#usertype").val() == 1) {
                        $("#supplier").empty();
                        $("#supplier").val(data[0].supplier);
                        $("#supplierRefNo").val(data[0].supplierRefNo);
                    }

                });
            }
        ).catch(function (err) {
        console.log('Fetch Error :-S', err);
    });
}

function loadPOLines() {
    //alert("api/service-receiving?action=1&po=" + $("#poNo").val());
    $.get("api/delivery-notification?action=1", function (data) {
        var res = JSON.parse(data);
        if (res[2].length > 0) {
            var rejectedLines = (res[2][0]["rejectedlines"]).split(",");
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
                    '<td class="text-center">' + d1[i]['poDate'] + '</td>' +
                    '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                    '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                    '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                    '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                    '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                    '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                    /*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*/
                    '</tr>';
                $("#dtPOLinesDelivered tbody:last").append(strRow);
            }
        } else {
            $("#dtPOLinesDelivered tbody").empty();
            $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }

        /**
         * Deliverable po lines
         */
        var d2 = res[1];
        $("#delivCount2").html('(' + d2.length + ')');
        if (d2.length > 0) {
            $("#dtPOLines tbody").empty();
            if (d2[0]["poDate"] != null) {
                $('#poDate').val(Date_toDetailFormat(new Date(d2[0]["poDate"])));

                // $('#poDate').datepicker('setDate', new Date(d2[0]["poDate"]));
                // $('#poDate').datepicker('update');
            }
            $('#needByDate').val(Date_toDetailFormat(new Date(d2[0]["needByDate"]))).change();
            // $('#needByDate').datepicker('setDate', new Date(d2[0]["needByDate"]));
            // $('#needByDate').datepicker('update');

            $('#currency').val(d2[0]["currency"]);
            $('.currencyText').html(d2[0]["currency"]);

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
        } else {
            $("#dtPOLines tbody").empty();
            $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }
        // ldGrandTotal();
    });
}


function vatAmount(pa, vr) {
    return pa*(vr/100);
}

function poLineTotal(elm) {

    var linePrice, lineQty, lineTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = linePrice * lineQty;

    $(elm).closest('tr').find('input.lineTotal').val(commaSeperatedFormat(lineTotal));
    $(elm).closest('tr').find('input.lineTotal').attr('title', commaSeperatedFormat(lineTotal));

}

function poLineDelivTotal(elm) {
    var linePrice, lineDelivQty, lineDelivTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineDelivQty = parseToCurrency($(elm).closest('tr').find('input.delivQty').val());
    lineDelivTotal = linePrice * lineDelivQty;

    $(elm).closest('tr').find('input.delivTotal').val(lineDelivTotal);
    $(elm).closest('tr').find('input.delivTotal').attr('title', lineDelivTotal);
    calculateInvoiceAmount($("#poNo").val());
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
        $("#grandTotal").val(+(grandTotal).toFixed(12));

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
        $("#dlvGrandTotal").val(+(delivTotal).toFixed(12));
        $("#invAmount").val(commaSeperatedFormat(delivTotal));
        $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
        //calculateInvoiceAmount()
    });
}


// datepart: 'y', 'm', 'w', 'd', 'h', 'n', 's'
Date.dateDiff = function (datepart, fromdate, todate) {
    datepart = datepart.toLowerCase();
    var diff = todate - fromdate;
    var divideBy = {
        w: 604800000,
        d: 86400000,
        h: 3600000,
        n: 60000,
        s: 1000
    };

    return Math.floor(diff / divideBy[datepart]);
};


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
            '<td><input type="text" class="form-control input-sm projCode" /></td>' +
            '<td><input type="text" class="form-control input-sm uom" /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="0" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="0" /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="0" readonly /></td>' +
            /*'<td><input type="text" class="form-control input-sm text-right ldAmnt" value="0" /></td>' +*/
            // '<td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>' +
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
            '<td><input type="text" class="form-control input-sm text-center poLine" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" value="' + row["itemCode"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm projCode" value="' + row["poDate"] + '" /></td>' +
            '<td><input type="text" class="form-control input-sm uom" value="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" /><input type="hidden" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
            /*'<td><input type="text" class="form-control input-sm text-right ldAmnt" name="ldAmnt[]" value="'+row["ldAmount"]+'" /></td>' +*/
            // '<td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>' +
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

            var projectCode = ($(this).find('input.projCode').val()) ? $(this).find('input.projCode').val() : "NA";
            //var projectCode = (1===1) ? 'test' : 'empty' ;
            $("#consolidatedPoLines").val(
                $("#consolidatedPoLines").val() + $(this).find('input.poLine').val() + ";" +
                $(this).find('input.poItem').val() + ";" +
                $(this).find('input.poDesc').val() + ";" +
                //$(this).find('input.projCode').val() + ";" +
                projectCode + ";" +
                $(this).find('input.uom').val() + ";" +
                $(this).find('input.unitPrice').val() + ";" +
                $(this).find('input.poQty').val() + ";" +
                $(this).find('input.lineTotal').val() + ";" +
                $(this).find('input.delivQty').val() + ";" +
                $(this).find('input.delivTotal').val()
            )
        }
    });
}


/*!
* Delete attachment file from directory
* **************************************** */
/*function deleteFile(objfile) {

    $.ajax({
        url: 'application/library/uploadhandler.php?del=' + encodeURI(objfile.replace("../", "")),
        success: function (result) {
            var res = JSON.parse(result);
            if (res['status'] == 1) {
                alertify.success("Deleted");
            }
        }
    });
}*/
