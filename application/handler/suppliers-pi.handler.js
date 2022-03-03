/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var pono, podata, comments, attach, pireqno,sumdata;

$(document).ready(function() {

    pono = $('#pono').val();

    if(pono==""){
        $("#formSuppliersPi").hide();
        $("#formPOSelection").removeClass('hidden');

        $.getJSON("api/new-po?action=2", function (list) {
            //alert('sds')
            $("#poList").select2({
                data: list,
                placeholder: "Search PO Number",
                allowClear: false,
                width: "100%"
            });
        });
        var a;
        $("#poList").change(function(e){
            a = $("#poList").val().split("|");
        });
        $("#btnLoadPO").click(function(e){
            window.location.href = _dashboardURL + "suppliers-pi?po="+a[0]+"&ref="+a[1];
        });
    }


    if(pono!="") {
        $('#ponum').html(pono);
        //alert(pono);
        if(pono.indexOf('PI')>1){
            apiUrl = "api/new-po?action=6&pono=" + pono;
        } else {
            apiUrl = "api/new-po?action=3&pono=" + pono;
        }
        //alert(apiUrl);
        $.get(apiUrl, function (data) {

            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {
                var row = JSON.parse(data);
                podata = row[0];
                comments = row[1];
                attach = row[2];

                pireqno = podata['piReqNo'];
                // PO info
                $('#pov').val(podata['poValue']);
                $('#validPIVal').val(podata['validPIVal']);
                $('#piReqNo').val(podata['piReqNo']);
                $('#piReqNoText').html(podata['piReqNo']);
                $('#povalue').html('<b>' + commaSeperatedFormat(podata['poValue']) + '</b> ' + podata['currencyName']);
                $('#pivalueCur').html(podata['currencyName']);
                $('#podesc').html(htmlspecialchars_decode(podata['poDesc']));
                $('#supplier').html(podata['supplierName']);
                $('#sup_address').html(podata['supplierAddress']);
                $('#contractref').html(podata['contractName']);
                $('#pr_no').html(podata['prNo']);
                $('#department').html(podata['department']);
                $('#deliverydate').html(Date_toMDY(new Date(podata['deliveryDate'])));
                $('#buyercontact').html(podata['buyersName']);
                $('#techcontact').html(podata['PRUser']);

                $('#installbysupplier').html(getImplementedBy(podata["installBy"]));

                $('#noflcissue').html(podata['nofLcIssue']);
                $('#nofshipallow').html(podata['nofShipAllow']);

                //attachmentLogScript(attach, '#usersAttachments');
                //commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');

                // When need to rectify by supplier
                if ($('#postatus').val() > ACTION_DRAFT_PI_SUBMITTED) {

                    // PI info
                    $('#pinum').val(podata['pinum']);
                    $('#pivalue').val(commaSeperatedFormat(podata['pivalue']));
                    $('#pi_description').val(htmlspecialchars_decode(podata['pidesc']));
                    $('#shipmode' + podata['shipmode']).attr('checked', '').parent().addClass('checked');
                    $('#hscode').val(htmlspecialchars_decode(podata['hscode']));
                    $('#negobank').val(htmlspecialchars_decode(podata['negobank']));
                    $('#shipport').val(htmlspecialchars_decode(podata['shipport']));
                    $('#lcbankaddress').val(htmlspecialchars_decode(podata['lcbankaddress']));
                    $('#productiondays').val(podata['productiondays']);

                    attachmentLogScript(attach, '#usersAttachments');
                    commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');

                    if ($('#postatus').val() >= ACTION_FINAL_PI_SUBMITTED) {
                        $('#pidate').val(Date_toMDY(new Date(podata['pidate'])));
                        $('#basevalue').val(commaSeperatedFormat(podata['basevalue']));
                    }

                    $.get("api/category?action=6&id=27&tag=1", function (data) {
                        $("#producttype").html(data);
                        $("#producttype").select2({
                            placeholder: "Select Product Type",
                            width: "100%"
                        });
                        $("#producttype").val(podata['producttype']).change();
                    });

                    //alert('sdfd');
                    $.getJSON("application/library/country.txt", function (data) {
                        $("#origin").select2({
                            data: data,
                            placeholder: "Select origin"
                        });
                        // alert(podata['origin']);
                        if (podata['origin'] != undefined) {
                            var v = podata['origin'].split(',');
                            $('#origin').val(v).change();
                        }
                    });

                    $.getJSON("api/category?action=4&id=56", function (list) {
                        $("#importAs").select2({
                            data: list,
                            placeholder: "Select from the list"
                        });
                        if (pono != "") {
                            $('#importAs').val(podata['importAs']).change();
                        }
                    });

                } else {
                    $.get("api/category?action=6&id=27&tag=1", function (data) {
                        $("#producttype").html(data);
                        $("#producttype").select2({
                            placeholder: "Select from the list",
                            width: "100%"
                        });
                    });

                    $.getJSON("api/category?action=4&id=56", function (list) {
                        $("#importAs").select2({
                            data: list,
                            placeholder: "Select from the list"
                        });
                    });

                    $.getJSON("application/library/country.txt", function (data) {
                        $("#origin").select2({
                            data: data,
                            placeholder: "Select origin"
                        });
                    });
                }
            }
        });

        $(document).on('keyup blur change', '#pivalue', function (e) {
            $("#pivalue").val(commaSeperatedFormat($("#pivalue").val()));
            var piv = parseToCurrency($("#pivalue").val());
            var pov = parseToCurrency($("#pov").val());
            var piValidVal = parseToCurrency($("#validPIVal").val());

            if (piv < piValidVal) {
                $("#pivalue").closest("div.form-group").addClass('has-warning');
                $("#piValueWarning").removeClass("hidden");
            } else {
                $("#pivalue").closest("div.form-group").removeClass('has-warning');
                $("#piValueWarning").addClass("hidden");
            }
            if (piv > piValidVal) {
                $("#pivalue").closest("div.form-group").addClass('has-error');
            } else {
                $("#pivalue").closest("div.form-group").removeClass('has-error');
            }
        });

        /*$("#pivalue").blur(function (e) {
            $("#pivalue").val(commaSeperatedFormat($("#pivalue").val()));
            var piv = parseToCurrency($("#pivalue").val());
            var pov = parseToCurrency($("#pov").val());

            if (piv < pov) {
                $("#pivalue").closest("div.form-group").addClass('has-warning');
                $("#piValueWarning").removeClass("hidden");
            } else {
                $("#pivalue").closest("div.form-group").removeClass('has-warning');
                $("#piValueWarning").addClass("hidden");
            }
            if (piv > pov) {
                $("#pivalue").closest("div.form-group").addClass('has-error');
            } else {
                $("#pivalue").closest("div.form-group").removeClass('has-error');
            }
        });*/

        $("#basevalue").blur(function (e) {
            $("#basevalue").val(commaSeperatedFormat($("#basevalue").val()));
            var piv = parseToCurrency($("#pivalue").val());
            var bsv = parseToCurrency($("#basevalue").val());
            var piValidVal = parseToCurrency($("#validPIVal").val());

            if (bsv < piValidVal) {
                $("#basevalue").closest("div.form-group").addClass('has-error');
            } else {
                $("#basevalue").closest("div.form-group").removeClass('has-error');
            }
        });


        $("#messageyes").click(function (event) {
            if ($(this).is(":checked"))
                $(".isDefMessage").show();
            else
                $(".isDefMessage").hide();
        });

        $("#btnSubmitPI").click(function (e) {

            e.preventDefault();

            if (validate() === true) {
                alertify.confirm('Are you sure you want to submit PI?', function (e) {
                    if (e) {
                        $("#btnSubmitPI").prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            url: "api/suppliers-pi",
                            data: $('#formSuppliersPi').serialize() + "&userAction=1",
                            cache: false,
                            success: function (response) {
                                $("#btnSubmitPI").prop('disabled', false);
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

        $("#btnRejectPO").click(function (e) {

            e.preventDefault();

            if (validateReject() === true) {
                alertify.confirm('Confirm', 'Are you sure you want to reject PO?', function (e) {
                    if (e) {
                        //alertify.success("Clicked yes");
                        $("#btnRejectPO").prop('disabled', true);
                        $.ajax({
                            type: "POST",
                            url: "api/suppliers-pi",
                            data: $('#formSuppliersPi').serialize() + "&userAction=2",
                            cache: false,
                            success: function (response) {
                                $("#btnRejectPO").prop('disabled', false);
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
                }, null).set('labels', {ok: 'Yes', cancel: 'Cancel'});

            } else {
                return false;
            }
        });

        /* //PO INFO LOAD
        var id = originalPO(pono);
        $.get('api/purchaseorder?action=13&id=' + id, function (data) {

            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {

                var row = JSON.parse(data);
                podata = row[0][0];


                // PO info
                // $('#podesc').val(podata['podesc']);
                $('#povalue').val(commaSeperatedFormat(podata['poTotal']));
                // $('#currency').val(podata['currency']);
                $('#deliverydate').val(podata['needByDate']);
                $('#podesc').val(podata['poDesc']);
                // $('#emailto').tokenfield('setTokens',podata["emailto"]);
                // $('#emailcc').tokenfield('setTokens',podata["emailcc"]);

                // $('#installBy_' + podata['installbysupplier']).attr('checked','').parent().addClass('checked');

            }
        });*/

        //PO LINE LOAD
        // if($('#postatus').val()==ACTION_REQUESTED_FOR_FINAL_PI){
        //     LoadDeliveredPOLinesInEditMode();
        // } else {
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
            // calculateInvoiceAmount($("#poNo").val());
            /*var cIndex = this.id.substr(this.id.indexOf("_") + 1, 1);

            if ($("#dlvQty_" + cIndex).val() == 0) {
                $("#dlvQty_" + cIndex).val($("#poQty_" + cIndex).val());
            }
            doCalculation();*/
            $('#reg_fee').val("");
        });

        /*$("#dlvQtyAll").keyup(function () {
            $(".delivQty").val($("#dlvQtyAll").val());
            $(".delivQty").attr("title", $("#dlvQtyAll").val());
            poLineWisePoTotal();
            poGrandTotal()
            //doCalculation();
        });*/

        $('#reject-message').hide();

        $('#btnDownloadPO').on('click', function (event) {
            event.preventDefault();
            //let originalPONum = originalPO(pono);
            let url = 'api/po-generate?pono=' + pono;
            window.open(url);
        });

        /*$("#btnLoadPoLines").click(function(e){
            alert(1);
            loadPOLines();
        });*/
    }

    //FOR DRAFT PI
    // $.get('api/purchaseorder?action=4&po=' + pono + '&step=' + ACTION_NEW_PO_INITIATED, function (r) {
    //     // console.log(r)
    //     if (r == 1) {
    //         $.get('api/view-po?action=4&id='+pono, function (data) {
    //             // console.log(data)
    //             if(!$.trim(data)){
    //                 $(".panel-body").empty();
    //                 $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
    //             }else {
    //
    //                 var row = JSON.parse(data);
    //                 // console.log(row)
    //                 sumdata = row[0][0];
    //                 // console.log(commaSeperatedFormat(row['grandpoQty']))
    //
    //                 // PO info
    //                 // $('#podesc').val(podata['podesc']);
    //                 $('#otherpoQtyTotal').html(commaSeperatedFormat(sumdata['grandpoQty']));
    //                 $('#othergrandTotal').html(commaSeperatedFormat(sumdata['grandPoTotal']));
    //                 $('#otherdlvQtyTotal').html(commaSeperatedFormat(sumdata['grandDelivQty']));
    //                 $('#otherdlvGrandTotal').html(commaSeperatedFormat(sumdata['grandDelivTotal']));
    //             }
    //         });
    //     }
    // });

    //FOR FINAL PI
    $.get('api/purchaseorder?action=4&po=' + pono + '&step=' + ACTION_REQUESTED_FOR_FINAL_PI, function (r) {
        // console.log(r)
        if (r == 1) {
            $.get('api/view-po?action=3&id='+pono, function (data) {
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
                    $('#otherpoQtyTotal').html(commaSeperatedFormat(sumdata['grandpoQty']));
                    $('#othergrandTotal').html(commaSeperatedFormat(sumdata['grandPoTotal']));
                    $('#otherdlvQtyTotal').html(commaSeperatedFormat(sumdata['grandDelivQty']));
                    $('#otherdlvGrandTotal').html(commaSeperatedFormat(sumdata['grandDelivTotal']));
                }
            });
            $("#tabDeliverable").hide();
            $("#deliverableTab").hide();
            $("#tabDelivered").show();

        }
    });
});

$("#pidate").datepicker({
    todayHighlight: true,
    autoclose: true
});

function validate() {


    if ($("#pinum").val() == "") {
        $("#pinum").focus();
        alertify.error("PI Number is required!");
        return false;
    }
    if ($("#pivalue").val() == "") {
        alertify.error("PI Value is required!");
        $("#pivalue").focus();
        return false;
    } else {
        if (!Number(parseToCurrency($("#pivalue").val()))) {
            $("#pivalue").focus();
            alertify.error("Not a valid amount!");
            return false;
        } else {
            var piv = parseToCurrency($("#pivalue").val());
            var pov = parseToCurrency($("#validPIVal").val());

            if (piv > pov) {
                $("#pivalue").focus();
                $("#pivalue").closest("div.form-group").addClass('has-error');
                alertify.error("PI Value cannot be greater then PO value!");
                return false;
            } else {
                $("#pivalue").closest("div.form-group").removeClass('has-error');
            }
        }
    }

    if ($("#pi_description").val() == "") {
        $("#pi_description").focus();
        alertify.error("Add description!");
        return false;
    }
    if($("#producttype").val()==""){
        alertify.error("Please select product type!");
        $("#producttype").select2('open');
        return false;
    }
    if ($("#importAs").val() == "") {
        $("#importAs").focus();
        alertify.error("Please select Import As!");
        return false;
    }

    var shipmode_check = $('input:radio[name=shipmode]:checked').val();

    if (shipmode_check == undefined) {
        alertify.error("Please select a Shipment Mode!");
        $("#shipmodesea").focus();
        return false;
    }

    if ($("#hscode").val() == "") {
        $("#hscode").focus();
        alertify.error("HS Code is required!");
        return false;
    }

    //alert($('#postatus').val());
    if ($('#postatus').val() >= ACTION_DRAFT_PI_REJECTED_BY_EA) {

        if ($("#pidate").val() == "") {
            $("#pidate").focus();
            alertify.error("PI Date is required!");
            return false;
        }
        if ($("#basevalue").val() == "") {
            $("#basevalue").focus();
            alertify.error("Base value is required!");
            return false;
        } else {

            if (!Number(parseToCurrency($("#basevalue").val()))) {
                $("#basevalue").focus();
                alertify.error("Not a valid amount!");
                return false;
            } else {
                var piv = parseToCurrency($("#pivalue").val());
                var bsv = parseToCurrency($("#basevalue").val());

                if (bsv < piv) {
                    $("#basevalue").focus();
                    $("#basevalue").closest("div.form-group").addClass('has-error');
                    alertify.error("Insurance/Base value cannot be less then PI value!");
                    return false;
                } else {
                    $("#basevalue").closest("div.form-group").removeClass('has-error');
                }
            }

        }

    }

    if (!$("#origin").val()) {
        alertify.error("Country of Origin is required!");
        $("#origin").select2('open');
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

    if ($("#nofLcIssue").val() == "") {
        $("#nofLcIssue").focus();
        alertify.error("No of LC is required!");
        return false;
    }
    if ($("#nofShipAllow").val() == "") {
        $("#nofShipAllow").focus();
        alertify.error("No of Shipment is required!");
        return false;
    }
    var installBy_check = $('input:radio[name=installBy]:checked').val();

    if (installBy_check == undefined) {
        alertify.error("Please select Implement by option!");
        return false;
    }
    if ($('#postatus').val() < ACTION_DRAFT_PI_SUBMITTED) {
        if ($("#attachpo").val() == "") {
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
        }
    }
    if ($("#attachDraftPI").val() == "") {
        alertify.error("Attach PI Document!");
        $("#attachDraftPI").focus();
        return false;
    } else {
        if (!validAttachment($("#attachDraftPI").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachDraftBOQ").val() == "") {
        $("#attachDraftBOQ").focus();
        alertify.error("Attach BOQ Document!");
        return false;
    } else {
        if (!validAttachment($("#attachDraftBOQ").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachCatelog").val() == "") {
        alertify.error("Attach Catalog Files!");
        $("#attachCatelog").focus();
        return false;
    } else {
        if (!validAttachment($("#attachCatelog").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }

    poLineVerify();

    if (!poLinesOkay) {
        return false;
    }
    return true;
}

function validateReject(){
    if($("#suppliersmessage").val()==""){
        $("#suppliersmessage").focus();
        alertify.error("Please write the reason of rejection.");
        return false;
    }
    return true;
}

function resetForm(){
    $('#formSuppliersPi')[0].reset();
    $('#origin').val('').change();
}

$(function () {

    var button = $('#btnUploadDraftPI'), interval;
    var txtbox = $('#attachDraftPI');

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
                if (!(ext && /^(pdf)$/i.test(ext))) {
                    alertify.error('Invalid File Format,Only pdf file required.');
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

$(function () {

    var button = $('#btnUploadDraftBOQ'), interval;
    var txtbox = $('#attachDraftBOQ');

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
                if (!(ext && /^(pdf)$/i.test(ext))) {
                    alertify.error('Invalid File Format,Only pdf file required.');
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

$(function () {

    var button = $('#btnUploadCatelog'), interval;
    var txtbox = $('#attachCatelog');

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
                if (!(ext && /^(pdf)$/i.test(ext))) {
                    alertify.error('Invalid File Format,Only pdf file required.');
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

$(function () {

    var button = $('#btnUploadPo'), interval;
    var txtbox = $('#attachpo');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.error('Invalid File Format,Only pdf file required.');
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

    var button = $('#btnUploadBoq'), interval;
    var txtbox = $('#attachboq');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.error('Invalid File Format,Only pdf file required.');
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

    var button = $('#btnUploadOther'), interval;
    var txtbox = $('#attachother');
    //var button_cancel = $('#attachother_cancel');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        autoSubmit: true,
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            //alert(ext);
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.error('Invalid File Format,Only pdf file required.');
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

function LoadPOLines(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/new-po?action=5&pono=" + pono, function (data) {
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

function LoadDeliveredPOLinesInEditMode(){
    // alert("api/new-po?action=5&pono=" + pono);
    $.get("api/new-po?action=5&pono=" + pono, function (data) {
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
        var d1 = res[1];
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
            }
        } else {
            $("#dtPOLinesDelivered tbody").empty();
            $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }

        /**
         * Deliverable po lines
         */
        var d2 = res[0];
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
        $("#pivalue").val(+(delivTotal).toFixed(2));

        // $("#pivalue").val(commaSeperatedFormat(delivTotal)).change();
        // $("#invAmount").val(commaSeperatedFormat(delivTotal));
        // $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
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
            '<td><input type="text" class="form-control input-sm poDate" /></td>' +
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
            '<td><input type="text" class="form-control input-sm text-center poLine" name="poLine[]" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" name="poItem[]" value="' + row["itemCode"] + '" title="' + row["itemCode"] + '" readonly /></td>' +
            // '<td><textarea class="form-control input-sm poDesc" name="poDesc[]" title="' + row["itemDesc"] + '" readonly>' + htmlspecialchars_decode(row["itemDesc"]) + '</textarea></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" name="poDesc[]" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" title="' + row["itemDesc"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" name="poDate[]" value="' + row["deliveryDate"] + '" title="' + row["deliveryDate"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm uom" name="uom[]" value="' + row["uom"] + '" title="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" name="unitPrice[]" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" name="poQty[]" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" name="lineTotal[]" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty"  name="delivQty[]" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" /><input type="hidden" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" name="delivTotal[]" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
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

/*$("#actualDelivDate").datepicker({
    format: 'dd-M-yyyy',
    todayHighlight: true,
    orientation: 'auto',
    autoclose: true
});*/
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
