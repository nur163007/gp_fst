/*
    Author      : A'qa Technology
    Created By  : Shohel Iqbal
    Created on  : 30.2021
    Purpose     : New PO creation
*/


/*$("#draftSendBy").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true,
    onClose: function () { $(this).valid(); }
});*/


// Default 5 days + for draft send date
var d1 = new Date(), d2 = new Date();
d2.setDate(d2.getDate()+5);
var wc = countWeekend(d1, d2);
d2.setDate(d2.getDate()+wc);

$('#draftSendBy').val(Date_toDetailFormat(d2));
/*

$('#draftSendBy').datepicker('setDate', d2);
$('#draftSendBy').datepicker('update');
*/

function countWeekend(date1,date2){
    var date1 = new Date(date1), date2 = new Date(date2);
    var wCount = 0;
    while(date1 < date2){
        date1.setDate(date1.getDate()+1);
        var dayNo = date1.getDay();
        if(dayNo==5 || dayNo == 6){ // 5 = Friday, 6 = Saturday, 0 = Sunday
            wCount +=1;
        }
    }
    return wCount;
}

$(document).ready(function() {

    $.getJSON("api/purchaseorder?action=11", function (list) {
        //alert('sds')
        $("#poNo").select2({
            data: list,
            placeholder: "Select a PO",
            allowClear: false,
            width: "100%"
        });
    });
    $.getJSON("api/users?action=5&role="+const_role_PR_Users, function (list) {
        $("#prUserCc").select2({
            data: list,
            placeholder: "PR User",
            allowClear: false,
            width: "100%"
        });
    });

    $("#poNo").on("select2:select", function (e) {

        var id = $("#poNo").val();

        /*-------------------------------------------------------------
        * Loading PO Information
        *-------------------------------------------------------------*/
        $.get('api/new-po?action=1&id='+id, function (data) {

            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            }else {

                var row = JSON.parse(data);
                podata = row[0][0];

                // PO info
                $('#poValue').val(commaSeperatedFormat(podata['POAmount']));
                $('#currencyName').html(podata['currency']);
                $('#currency').val(podata['currencyId']);
                $('#deliveryDate').val(podata['needByDate']);
                $('#actualPoDate').val(podata['poDate']);
                $('#poDesc').val(podata['poDesc']);
                $('#department').val(podata['PRUserDept']);
                // $('#supplier').val(podata['supplierId']).change();
                $('#supplierName').val(podata['supplier']);
                $('#supplier').val(podata['supplierId']);

                $('#contractRefName').val(podata['ContractNo']);
                $('#contractRef').val(podata['contractId']);
                $('#prNo').val(podata['prNo']);
                $('#prUserName').val(podata['PRUser']);
                $('#prUser').val(podata['PRUserId']);

                $.get("api/company?action=5&id="+podata['supplierId'], function (data) {
                    if($.trim(data)){
                        var row = JSON.parse(data);
                        $("#supplierEmailTo").tokenfield('setTokens',row['emailTo']);
                        $("#supplieremailCc").tokenfield('setTokens',row['emailCc']);
                        $("#supplierAddress").val(row['address']);
                    }
                });
            }
        });

        /*-------------------------------------------------------------
        * Loading PO Lines
        *-------------------------------------------------------------*/

        $.get("api/delivery-notification?action=1&id="+id, function (data) {
            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            }else {
                var res = JSON.parse(data);
                rejectedLines = ""
                /**
                 * Deliverable lines
                 */
                var d2 = res[1];
                $("#POLineCount").html('(' + d2.length + ')');
                if (d2.length > 0) {
                    //alert('Deliverable section');
                    $("#dtPOLines tbody").empty();

                    /*$('#poDate').datepicker('setDate', new Date(d2[0]["poDate"])).change();
                    $('#poDate').datepicker('update');

                    $('#needByDate').datepicker('setdate', new Date(d2[0]["needByDate"]));
                    $('#needByDate').datepicker('update');*/

                    /*$('#currency').val(d2[0]["currency"]);
                    $('.currencyText').html(d2[0]["currency"]);*/

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
            }

        });

    });

    $(function () {
        $("#SendPO_btn").click(function (e) {
            //alert('ddas');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm('Are you sure you want submit this PO?', function (e) {
                    if (e) {
                        //$("#SendPO_btn").hide();
                        $.ajax({
                            type: "POST",
                            url: "api/new-po",
                            data: "action=1&" + $('#form-PO-Detail').serialize(),
                            cache: false,
                            success: function (response) {
                                //alert(response);
                                try {
                                    var res = JSON.parse(response);
                                    if (res['status'] == 1) {
                                        alertify.success(res['message']);
                                        location.reload();
                                    } else {
                                        //$("#SendPO_btn").show();
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
                                alertify.error(textStatus + ": " + xhr.status + " " + error);
                            }
                        });
                    } else { // canceled
                        //alertify.error(e);
                        //$(this).show();
                    }
                });
            } else {
                return false;
            }
        });
    });

});

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
            '<td><input type="text" class="form-control input-sm needByDate" /></td>' +
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
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '" ' + addTick + ' disabled>' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" value="' + row["itemCode"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-center needByDate" value="' + row["needByDate"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm uom" value="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" readonly /><input type="hidden" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
            /*'<td><input type="text" class="form-control input-sm text-right ldAmnt" name="ldAmnt[]" value="'+row["ldAmount"]+'" /></td>' +*/
            // '<td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>' +
            '</tr>');
    }
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
        $("#dlvQtyTotal").val(+(totalDelivQty).toFixed(2));
        //alert(totalDelivQty);

        if ($(this).find('input.chkLine').is(':checked')) {
            delivPrice = parseToCurrency($(this).find('input.delivTotal').val());
        } else {
            delivPrice = 0;
        }
        delivTotal += delivPrice;
        $("#dlvGrandTotal").val(+(delivTotal).toFixed(2));
        // $("#invAmount").val(commaSeperatedFormat(delivTotal));
        // $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
        //calculateInvoiceAmount()
    });
}

function validate() {

    if ($("#poNo").val() == "") {
        $("#poNo").focus();
        alertify.error("Select a PO Number!");
        return false;
    }
    if ($("#poDesc").val() == "") {
        $("#poDesc").focus();
        alertify.error("PO Description is required!");
        return false;
    }
    if ($("#supplier").val() == "") {
        $("#supplier").focus();
        alertify.error("Select a Supplier!");
        return false;
    }
    if ($("#currency").val() == "") {
        $("#currency").focus();
        alertify.error("Select a Currency!");
        return false;
    }
    if ($("#poValue").val() == "") {
        $("#poValue").focus();
        alertify.error("PO Value is required!");
        return false;
    } else {
        if (!Number($("#poValue").val().replace(/,/g, ""))) {
            $("#poValue").focus();
            alertify.error("Not a valid value!");
            return false;
        }
    }
    if ($("#contractRef").val() == "") {
        $("#contractRef").focus();
        alertify.error("Contact Reference is required!");
        return false;
    }
    if ($("#deliveryDate").val() == "") {
        $("#deliveryDate").focus();
        alertify.error("Fill Up the Need by Date field!");
        return false;
    }
    if ($("#draftSendBy").val() == "") {
        $("#draftSendBy").focus();
        alertify.error("Fill Up the Draft PI Last Date field!");
        return false;
    }
    if (!$("#actualPoDate").val()) {
        $("#actualPoDate").focus();
        alertify.error("Actual PO date is required");
        return false;
    }
    if ($("#prNo").val() == "") {
        $("#prNo").focus();
        alertify.error("PR Nnumber is required!");
        return false;
    }
    if ($("#deptartment").val() == "") {
        $("#deptartment").focus();
        alertify.error("Department is required!");
        return false;
    }
    if ($("#prUser").val() == "") {
        $("#prUser").focus();
        alertify.error("Write PR User Email!");
        return false;
    }
    if ($("#supplierEmailTo").val() == "") {
        $("#supplierEmailTo").focus();
        alertify.error("Supplier's Email is required!");
        return false;
    }
    if ($("#supplieremailCc").val() == "") {
        $("#supplieremailCc").focus();
        alertify.error("Supplier's Email CC is required!");
        return false;
    }
    if ($("#supplierAddress").val() == "") {
        $("#supplierAddress").focus();
        alertify.error("Supplier Address is required!");
        return false;
    }
    /*if ($("#nofLcIssue").val() == "") {
        $("#nofLcIssue").focus();
        alertify.error("No of LC is required!");
        return false;
    }
    if ($("#nofShipAllow").val() == "") {
        $("#nofShipAllow").focus();
        alertify.error("No of Shipment is required!");
        return false;
    }*/

    /*var installBy_check = $('input:radio[name=installBy]:checked').val();

    if (installBy_check == undefined) {
        alertify.error("Please select Implement by option!");
        return false;
    }*/

    /*if ($("#attachpo").val() == "") {
        $("#attachpo").focus();
        alertify.error("Attach Purchase Order Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachpo").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachboq").val() == "") {
        $("#attachboq").focus();
        alertify.error("Attach BOQ Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachboq").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachother").val() != "") {
        if (!validAttachment($("#attachother").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }*/
    return true;
}


