/*
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

var poid;

$(document).ready(function() {

    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#LcNo").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: false,
            width: "100%"
        });
        if($("#poNumber").val()!=""){
            refreshLCInfo();
        }
    });

    $.getJSON("api/company?action=4&type=bank", function (list) {
        $("#LcIssuingBank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: false,
            width: "100%"
        });
    });
    $.getJSON("api/company?action=4&type=insurance", function (list) {
        $("#insurance").select2({
            data: list,
            placeholder: "Select a Insurance",
            allowClear: false,
            width: "100%"
        });
    });
    $("#LcNo").change(function(e){
        $("#LcNo1").val($("#LcNo").val());
        if($("#poNumber").val()==""){
            refreshLCInfo();
        }
    });

    $("#btnSendDocReceiptNotification").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want to send document receive notification?', function (e) {
                if (e) {
                    $("#btnSendDocReceiptNotification").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-bank",
                        data: $('#originaldoc-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#btnSendDocReceiptNotification").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to update!");
                                    return false;
                                }
                            } catch (err) {
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

    /*if($("#usertype").val()!=const_role_lc_bank){
        $("#LcNo").attr("disabled",true);
        $("#bankNotifyDate").attr("disabled",true);
        $("#discrepancyList").attr("disabled",true);
        $("#chkDiscStatus_0").attr("disabled",true);
        $("#chkDiscStatus_1").attr("disabled",true);
    }
    */

    $.getJSON("api/category?action=4&id=32", function (list) {
        $("#lcType").select2({
            data: list,
            placeholder: "Select a Type",
            allowClear: false,
            width: "100%"
        });
    });

    /*$.getJSON("api/category?action=4&id=25", function (list) {
        $("#docName").select2({
            data: list,
            minimumResultsForSearch: Infinity,
            placeholder: "Select Document Name",
            allowClear: false,
            width: "100%"
        });
    });*/

    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
        $("#bank").select2({
            data: list,
            placeholder: "Select a Source",
            allowClear: false,
            width: "100%"
        });
    });

    /*$.get("api/category?action=8&id=17", function (list) {
        $("#currency").html('<option value="" data-icon="">Select Currency</option>').append(list);
        $("#currency").selectpicker('refresh');
    });*/

    /*$("#ciNo").change(function(e){
        var ci = $("#ciNo").val();
        var lc = $("#LcNo").val();
        //alert(ci);
        //alert("api/shipment?action=1&lc="+lc+"&cino="+ci);
        $.get("api/shipment?action=4&lc="+lc+"&cino="+ci, function (data){
            //alert(data);
            var row = JSON.parse(data);
            $("#ciValue").val(row["ciAmount"]);
            $("#paid").html("Total paid: " + row["paidPart"] + "% = " + commaSeperatedFormat(row["paidAmount"]));
        });
    });*/

    /*$("#docName").change(function(e){
        var doc = $("#docName").val(),
            lc = $("#LcNo").val();
        //alert("api/buyers-lc-request?action=1&lc="+lc+"&term="+doc);
        $.get("api/buyers-lc-request?action=1&lc="+lc+"&term="+doc, function (data) {
            var row = JSON.parse(data);
            $("#paymentPercent").val(row['percentage']);
            $("#amount").val( commaSeperatedFormat(($("#ciValue").val()*row['percentage'])/100));
        });
    });*/
    // Calculate Events ------
    /*$("#exchangeRate, #stlmntCharge, #vatOnStlmntCharge").keyup(function() {
        CalculateAll();
        //alert('sfdf');
    });*/

});

/*$("#bankNotifyDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});*/

$(function () {

    var button = $('#btnUploadOriginalDoc'), interval;
    var txtbox = $('#attachOriginalDoc');

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

function refreshLCInfo(){

    var url = "api/lc-opening?";
    if($("#poNumber").val()!=""){
        url = url + "action=3&po=" + $("#poNumber").val();
    } else {
        url = url + "action=1&lc=" + $("#LcNo").val();
    }
    // alert(url);
    $.get(url, function (data) {

        var row = JSON.parse(data);

        poid = row["pono"];
        $("#PoNo").val(row["pono"]);
        $("#LcIssuingBank").val(row["lcissuerbank"]).change();
        $("#insurance").val(row["insurance"]).change();
        $("#LcDate").val( Date_toDetailFormat( new Date( row["lcissuedate"] ) ));
        $("#LcValue").val(commaSeperatedFormat(row["lcvalue"]));
        $("#lcvalueCur, #lcvalueCur1").html(row['curname']);
        $("#coverNoteNo").val(row['coverNoteNo']);

        $("#LcNo").val(row["lcno"]).change();
        $("#LcNo1").val(row["lcno"]);

        $.get('api/shipment?action=1&po='+poid+'&shipno='+$("#shipno").val(), function (data) {

            ship = JSON.parse(data);

            $("#CiNo").val(ship['ciNo']);
            $("#ciValue").val(commaSeperatedFormat(ship['ciAmount']));
            if(ship['blNo']==""){
                $("#awblNo").val(ship['mawbNo']);
            } else{
                $("#awblNo").val(ship['blNo']);
            }
            $("#awblDate").val(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));
            $("#voucherNo").val(ship["GERPVoucherNo"]);
            $("#voucherDate").val(Date_toDetailFormat(new Date(ship['GERPVoucherDate'])));

            /*$.get('api/original-doc?action=2&po='+poid+'&shipno='+$("#shipno").val(), function (data) {
                if($.trim(data)){
                    var row = JSON.parse(data);
                    $("#EAFeedback").html(row["UserMsg"]);

                    if(row["ActionID"]==ACTION_ORIGINAL_DOCUMENT_ACCEPTED_FOR_DOCUMENT_DELIVERY){
                        $("#originaldoc-form input, #originaldoc-form textarea, #originaldoc-form select, #originaldoc-form button").attr('disabled',true);
                        // alert("api/shipment?action=4&lc="+$("#LcNo").val()+"&cino="+$("#CiNo").val());
                        $.get("api/shipment?action=4&lc="+$("#LcNo").val()+"&cino="+$("#CiNo").val(), function (data){
                            //alert(data);
                            if(data!=0){
                                var row = JSON.parse(data);
                                if(row["docName"]==6){  // Doc name 6 = "SIGHT" out of Sight, CEC, FAC
                                    $("#btn_makeSightPayment").attr({"href":"javascript:void(0)", "disabled":"true"});
                                }else{
                                    $("#btn_makeSightPayment").attr("href","payment-entry?d=6&ci="+$("#CiNo").val()+"&po="+$("#PoNo").val()+"&ship="+$("#shipno").val()+"&ref="+$("#refId").val());
                                    $("#btn_generatePaymentInstruction").attr("disabled", true);
                                }
                            }
                        });
                    } else {
                        $("#btn_generateBankLetter").attr("disabled",true);
                        $("#btn_makeSightPayment").attr("disabled",true);
                        $("#btn_mailForInsPolicy").attr("disabled",true);
                        $("#btn_generatePaymentInstruction").attr("disabled", true);
                    }
                }else{
                    $("#btn_generateBankLetter").attr("disabled",true);
                    $("#btn_makeSightPayment").attr("disabled",true);
                    $("#btn_mailForInsPolicy").attr("disabled",true);
                    $("#btn_generatePaymentInstruction").attr("disabled", true);
                }
            });*/

        });
        // Attachment
        $.get("api/attachment?action=1&po="+poid+"&shipno="+$("#shipno").val(), function (data){
            if($.trim(data)){
                var row = JSON.parse(data);
                attach = row[0];
                var attachList = ["CI Scan Copy","AWB/BL Scan Copy"];
                attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            }
        });

        /*$.get("api/original-doc?action=1&po="+poid+"&shipno="+$("#shipno").val(), function (data){
            if($.trim(data)){
                var row = JSON.parse(data);
                $("#bankNotifyDate").val(Date_toDetailFormat(new Date(row['banknotifydate'])));
                $("#chkDiscStatus_"+row['status']).attr("checked", true).parent().addClass("checked");
                $("#discrepancyList").val(row['discrepancy']);
            }
        });
        $.get('api/shipment?action=12&po='+poid, function(res){
            if($.trim(res)){
                $("#previousTotalCI").html(commaSeperatedFormat(res) + ' ' + row['curname']);
            }
        });*/
    });
}

function oDocValidate() {

}

function validate() {
    //alert('sdfssd');
    if($("#PoNo").val()=="")
    {
        $("#LcNo").focus();
        alertify.error("Please select a valid LC number!");
        return false;
    }

    if($("#bankNotifyDate").val()==""){
        $("#bankNotifyDate").focus();
        alertify.error("Please select bank notification date!");
        return false;
    }

    var descStatus = $('input:radio[name=discStatus]:checked').val();

    if(descStatus==undefined)
    {
        alertify.error("Please select Discrepancy Status!");
        return false;
    }
    if(descStatus=="1"){
        if($("#discrepancyList").val()==""){
            $("#discrepancyList").focus();
            alertify.error("Please write the discrepancy list!");
            return false;
        }
    }
    if($("#attachOriginalDoc").val()=="")
    {
        $("#attachOriginalDoc").focus();
        alertify.error("Please attach Original Document!");
        return false;
    } else {
        if(!validAttachment($("#attachOriginalDoc").val())){
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    return true;
}

/*function validate1()
{
    if($("#LcNo").val()=="")
    {
        $("#LcNo").focus();
        alertify.error("Write LC No!");
        return false;
    }
    if($("#ciNo").val()=="")
    {
        $("#ciNo").focus();
        alertify.error("Commercial invoice number is required!");
        return false;
    }
    if($("#docArrivalDate").val()=="")
    {
        $("#docArrivalDate").focus();
        alertify.error("Document Arrival date is required!");
        return false;
    }
    if($("#docName").val()=="")
    {
        $("#docName").focus();
        alertify.error("Document Name is required!");
        return false;
    }
    if($("#paymentPercent").val()=="")
    {
        $("#paymentPercent").focus();
        alertify.error("Payment Percent is required field!");
        return false;
    }
    if($("#docReceiveDate").val()=="")
    {
        $("#docReceiveDate").focus();
        alertify.error("Document Receive Date is required field!");
        return false;
    }
    if($("#payDueDate").val()=="")
    {
        $("#payDueDate").focus();
        alertify.error("Payment Due Date is required field!");
        return false;
    }
    if($("#payDate").val()=="")
    {
        $("#payDate").focus();
        alertify.error("Pay Date is required field!");
        return false;
    }
    if($("#payMatureDate").val()=="")
    {
        $("#payMatureDate").focus();
        alertify.error("Payment Maturity Date is required field!");
        return false;
    }
    if($("#exchangeRate").val()=="")
    {
        $("#exchangeRate").focus();
        alertify.error("Exchange Rate is required field!");
        return false;
    }
    if($("#fundCollectFrom").val()=="")
    {
        $("#fundCollectFrom").focus();
        alertify.error("Fund Collected From is required field!");
        return false;
    }
    return true;
}*/
