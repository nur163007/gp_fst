/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var poid = $('#pono').val();

$(document).ready(function() {
    
    if($('#pono').val()==""){
        $("#payment-entry-form").hide();
        $("#form-temp").removeClass('hidden');
        
        $.getJSON("api/purchaseorder?action=3", function (list) {
            $("#poList").select2({
                data: list,
                placeholder: "Search PO Number",
                allowClear: false,
                width: "100%"
            });
        });
        
        $("#poList").change(function(e){            
            $.getJSON("api/shipment?action=11&po="+$("#poList").val(), function (list) {
                $("#ciList").empty();
                $("#ciList").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "select CI number",
                    allowClear: false,
                    width: "100%"
                });
            });
        });
        $("#goPayment_btn").click(function(e){
            window.location.href = _dashboardURL + "payment-entry?po="+$("#poList").val()+"&ship="+$("#ciList").val()+"&ci="+$("#ciList").find('option:selected').text();
        });
    }
    // loading LC list
    $.getJSON("api/lc-opening?action=2", function (list) {
        $("#LcNo").select2({
            data: list,
            placeholder: "Search LC Number",
            allowClear: false,
            width: "100%"
        });
        if(poid!=""){
            loadAfterLCSelect();
        }
    });
    
    $("#LcNo").change(function(e){
        //alert('asddas');
        var flag = poid;
        var lc = $("#LcNo").val();
        
        $.getJSON("api/shipment?action=3&lc="+lc+"&col=ciNo", function (list) {
            
            $("#ciNo").select2({
                data: list,
                placeholder: "Select CI number",
                allowClear: false,
                width: "100%"
            });
            if($("#ciNo1").val()!=""){
                $("#ciNo").val($("#ciNo1").val()).change();
            }
            
            $.getJSON("api/category?action=4&id=25", function (list) {
                $("#docName").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select Document Name",
                    allowClear: false,
                    width: "100%"
                });
                if($("#defaultDoc").val()!=""){
                    $("#docName").val($("#defaultDoc").val()).change();
                }else{
                    $("#bankNotifyDate").removeAttr('readonly');
                    $("#bankNotifyDate").datepicker({
                        todayHighlight: true,
                        autoclose: true
                    });
                }
            });
        });
        
        $.get("api/shipment?action=8&lc="+lc, function (data) {
            if($.trim(data)){
                var row = JSON.parse(data);
                $('#pono').val(row['pono']);
                poid = row['pono'];
                
                if(flag==""){
                    loadAfterLCSelect();
                }
            }            
        });        
    });
    
    $("#ciNo").change(function(e){
        var ci = $("#ciNo").val();
        var lc = $("#LcNo").val();
        //alert("api/shipment?action=4&lc="+lc+"&cino="+ci);
        $.get("api/shipment?action=4&lc="+lc+"&cino="+ci, function (data){
            //alert(data);
            var row = JSON.parse(data);
            $("#ciValue").val(row["ciAmount"]);
            $("#ciAmount").val(commaSeperatedFormat(row["ciAmount"]));
            
            if(row["paidAmount"]!=null){
                $("#paid").html("Total paid: " + row["paidPart"] + "% = " + commaSeperatedFormat(row["paidAmount"]));
                $("#paidPercent").val(row["paidPart"]);
                if (row["paidPart"] == 100) {
                    $("#paymentEntryDraft_btn").prop('disabled', true);
                    $("#paymentEntry_btn").prop('disabled', true);
                }
            } else{
                $("#paid").html('<i class="icon wb-warning" aria-hidden="true"></i>No payment made against this LC');
            }
        });
    });
    
    $("#docName").change(function(e){
        var doc = $("#docName").val(),
            lc = $("#LcNo").val();
            ci = $("#ciNo").val();
        //alert("api/buyers-lc-request?action=1&lc="+lc+"&term="+doc);
        $.get("api/buyers-lc-request?action=1&lc="+lc+"&term="+doc, function (data) {
            if($.trim(data)!='null'){
                var row = JSON.parse(data);
                $("#paymentPercent").val(row['percentage']);
                $("#dayOfMaturity").val(row['dayofmaturity']);

                if(parseFloat(row['dayofmaturity'])>=0) {
                    //alert(row['dayofmaturity']);
                    var awbld = new Date(Date_toMDY(new Date($("#awblDate").val())));
                    // alert(awbld);
                    awbld.setDate(awbld.getDate() + parseFloat(row['dayofmaturity']));
                    $('#payMatureDate').datepicker('setDate', awbld);
                    $('#payMatureDate').datepicker('update');
                }
                $("#amount").val(commaSeperatedFormat(($("#ciValue").val()*row['percentage'])/100));
            } else {
                alertify.error("This document is not applicable in this LC.");
            }
        });
        //alert("api/payment-entry?action=1&lc="+lc+"&doc="+doc);
        $.get("api/payment-entry?action=1&lc="+lc+"&doc="+doc+"&ci="+ci, function (data) {
            //alert(data);
            if($.trim(data)!='null'){
                var row = JSON.parse(data);
                $("#amount").val(commaSeperatedFormat(row['amount']));
                $("#exchangeRate").val(row['exchangeRate']);
                $("#payAmountBDT").val(row['payAmountBDT']);
                $("#BBRefNo").val(row['BBRefNo']);
                if(row["BBRefDate"]!=null) {
                    $("#BBRefDate").val(Date_toDetailFormat(new Date(row["BBRefDate"])));
                }
                $("#remarks").val(row['remarks']);
                $("#bankNotifyDate").val(Date_toDetailFormat(new Date(row["bankNotifyDate"])));
                $("#docReceiveDate").val(Date_toDetailFormat(new Date(row["docReceiveDate"])));
                $("#payDueDate").val(Date_toDetailFormat(new Date(row["payDueDate"])));
                $("#payDate").val(Date_toDetailFormat(new Date(row["payDate"])));
                $("#payMatureDate").val(Date_toDetailFormat(new Date(row["payMatureDate"])));
                $("#fundCollectFrom").val(row['fundCollectFrom']).change();
                $("#bcSellingRate").val(row['bcSellingRate']);
                $("#stlmntCharge").val(commaSeperatedFormat(row['stlmntCharge']));
                $("#vatOnStlmntCharge").val(row['vatOnStlmntCharge']);
                $("#vatRebate").val(row['vatRebate']);
                $("#bankCharge").val(commaSeperatedFormat(row['bankCharge']));
                $("#totalCharge").val(commaSeperatedFormat(row['totalCharge']));

                if(row["maturityPayment"]==0){
                    $('#maturityPayment').removeAttr('checked');
                } else {
                    $('#maturityPayment').attr('checked','checked');
                }

                CalculatePayment();
            } else {
                alertify.error("No data has been drafted on this Document.");
            }
        });

    });
    // Calculate Events ------
    $("#exchangeRate").keyup(function() {
        CalculatePayment();
    });
    
    $("#stlmntCharge, #vatOnStlmntCharge, #vatRate, #vatRebateRate").keyup(function() {
        SettlementCalculate();
    });

    //$("#dayOfMaturity")
    $('#docReceiveDate').datepicker().on('changeDate', function(ev) {
        if($("#dayOfMaturity").val()!=""){
            var awbld = Date_toMDY(new Date($("#awblDate").val()));
            var lcdate = Date_toMDY(new Date($("#lcissueDate").val()));
            
            var dm = parseInt($("#dayOfMaturity").val());
            var d = new Date($("#docReceiveDate").val());

            d.setDate(d.getDate()+dm);
            $('#payMatureDate').val(Date_toDetailFormat(new Date(d)));
            //$('#payMatureDate').datepicker('update');
            
            //alert("AWBD: " + awbld + " LCD: " + lcdate);
        }
    });
    
    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
        $("#fundCollectFrom").select2({
            data: list,
            placeholder: "Select a Source",
            allowClear: false,
            width: "100%"
        });
    });
    
    $("#paymentEntry_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit the payment?', function (e) {
                if(e){
                    $("#paymentEntry_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/payment-entry",
                        data: $('#payment-entry-form').serialize()+"&action=1",
                        cache: false,
                        success: function (response) {
                            $("#paymentEntry_btn").prop('disabled', false);
                            // alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success('Payment Entry Successful.');
                                    if ($("#defaultDoc").val() == 6) { // then we know that it came from original document module
                                        window.location.href = _dashboardURL + "original-doc?po=" + poid + "&ship=" + $("#shipno").val() + "&ref=" + $("#refId").val();
                                    } else {
                                        //window.location.href = _dashboardURL;
                                    }
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (err){
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
        		};		
        	});
                
        } else {
            return false;
        }
    });

    $("#paymentEntryDraft_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if(validate() === true){
            alertify.confirm( 'Are you sure you want save draft payment?', function (e) {
                if(e){
                    $("#paymentEntryDraft_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/payment-entry",
                        data: $('#payment-entry-form').serialize()+"&action=2",
                        cache: false,
                        success: function (response) {
                            $("#paymentEntryDraft_btn").prop('disabled', false);
                            //alertify.alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success('Payment Entry Successful.');
                                    if ($("#defaultDoc").val() == 6) { // then we know that it came from original document module
                                        window.location.href = _dashboardURL + "original-doc?po=" + poid + "&ship=" + $("#shipno").val() + "&ref=" + $("#refId").val();
                                    } else {
                                        //window.location.href = _dashboardURL;
                                    }
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (err){
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
        		};
        	});

        } else {
            return false;
        }
    });


    $('select').select2({}).focus(function () { $(this).select2('focus'); });

    $("#btn_generatePaymentInstruction").click(function(e){

        e.preventDefault();

        if(validateForLetter()){

            $.get("api/shipment?action=4&lc="+$("#LcNo").val()+"&cino="+$("#ciNo").val(), function (data1){
                //alert(data1);
                var paydata = JSON.parse(data1);

                if(paydata['paidAmount']==null){
                    alertify.alert("There is no payment made against this LC. Please save a payment first.");
                    return;
                }

                /*var paidAmount = parseToCurrency(paydata['paidAmount']),
                    xRate = parseToCurrency(paydata['exchangeRate']),
                    paidAmountBDT = paidAmount * xRate;*/

                var paidAmount = parseToCurrency($('#amount').val()),
                    xRate = parseToCurrency($('#exchangeRate').val()),
                    paidAmountBDT = paidAmount * xRate;

                $.get("api/original-doc?action=3&bank="+$("#bank").val()+"&po="+$("#pono").val(), function (data) {
                    var bankData = JSON.parse(data);
                    //alert(data);
                    //--- getting letter serial number based on PO, shipment and bank ---------
                    $.get('api/lib-helper?req=1&po='+$("#pono").val()+"&ship="+$("#shipno").val()+'&orgtype=bank&orgid='+$("#bank").val(), function(sl) {
                    // alert(sl);
                        //------- generating letter reference number ------------------------
                        if (sl != "0") {
                            var d = new Date();
                            letterRef = docref_payment_instruction_letter_ref + d.getFullYear() + "/" + zeroPad(sl);
                        } else {
                            letterRef = docref_payment_instruction_letter_ref + d.getFullYear() + "/" + zeroPad(1);
                        }
                        //------- end generating letter reference number ------------------------

                        $.ajax({
                            url: "application/templates/letter_template/temp_payment_instruction_letter.html",
                            cache: false,
                            global: false,
                            success: function (result) {
                                //alert(result);
                                var temp = result;

                                //---------------replace data-----------------

                                temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                                temp = temp.replace('##REF##', letterRef);
                                temp = temp.replace('##BANKNAME##', bankData["name"]);
                                temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                                temp = temp.replace(/##LCNO##/g, $("#LcNo").val());
                                temp = temp.replace(/##CONAME##/g, bankData["coname"]);
                                temp = temp.replace(/##CUR##/g, bankData["currency"]);
                                temp = temp.replace(/##ACCNO##/g, bankData["account"]);
                                temp = temp.replace(/##PAIDAMOUNTBDT##/g, commaSeperatedFormat(paidAmountBDT.toFixed(2)));
                                temp = temp.replace(/##PAIDAMOUNT##/g, commaSeperatedFormat(paidAmount.toFixed(2)));
                                temp = temp.replace(/##XRATE##/g, xRate.toFixed(2));
                                temp = temp.replace(/##INWORD##/g, dollarToWords(paidAmount.toFixed(2)));

                                //---------------end replace data-------------

                                $("#fileName").val('payment_instruction_' + poid + '.doc');
                                $("#letterContent").val(temp);

                                document.getElementById("formLetterContent").submit();
                            }
                        });
                    });
                });
            });
        }
    });

    $("#bankNotifyDate").change(function () {
        var d = new Date($("#bankNotifyDate").val());
        d.setDate(d.getDate() + 5);
        $('#payDueDate').datepicker('setDate', d);
        $('#payDueDate').datepicker('update');
    });

});


function validateForLetter(){
    return true;
}

function loadAfterLCSelect(){
    //alert("api/shipment?action=1&po="+poid);
    if($("#shipno").val()==""){
        shipNum = 0;
    } else {
        shipNum = $("#shipno").val();
    }
    //alert("api/shipment?action=1&po="+poid+"&shipno="+shipNum);
    $.get("api/shipment?action=1&po="+poid+"&shipno="+shipNum, function (data) {
        
        var row = JSON.parse(data);
        
        $("#LcNo").val(row['lcno']).change();
        $("#GERPinvoiceNo").val(row['GERPinvoiceNo']).change();
        $("#awblDate").val(Date_toDetailFormat(new Date(row['awbOrBlDate'])));
        
        $.getJSON("api/category?action=4&id=32", function (list) {
            $("#lcType").select2({
                data: list,
                placeholder: "Select a Type",
                allowClear: false,
                width: "100%"
            });
            $("#lcType").val(row['lctype']).change();
        });
       
        $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
            $("#bank").select2({
                data: list,
                placeholder: "Select a Bank",
                allowClear: false,
                width: "100%"
            });
            $("#bank").val(row['lcissuerbank']).change();
        });
        
        $("#lcValue").val(commaSeperatedFormat(row['lcvalue']));
        
        $.get("api/category?action=8&id=17", function (list) {
            $("#currency").html('<option value="" data-icon="">Select Currency</option>').append(list);
            $("#currency").selectpicker('refresh');
            $("#currency").val(row['currency']).change();
        });
        if(row["banknotifydate"]!=null) {
            $("#bankNotifyDate").val(Date_toDetailFormat(new Date(row["banknotifydate"])));
            var d = new Date($("#bankNotifyDate").val());
            d.setDate(d.getDate() + 5);
            $('#payDueDate').datepicker('setDate', d);
            $('#payDueDate').datepicker('update');
        }
        //$("#awbDate").val(row['awbOrBlDate']);
        $("#lcissueDate").val(row['lcissuedate']);
        
        //alert('api/lc-opening?action=5&po='+poid);
        $.get('api/lc-opening?action=5&po='+poid, function (data) {
            
            if($.trim(data)){
                var row = JSON.parse(data);
                lcinfo = row[0][0];
                pterms = row[1];               
                
                $("#lcPaymentTermsText").html(lcinfo['paymentterms']);
                
                var ptrow = "";
                for(var i=0; i<pterms.length; i++){
                    ptrow = "<tr><td class=\"text-center\">"+pterms[i]['percentage']+"%</td>"+
                        "<td class=\"text-center\">"+pterms[i]['partname']+"</td>"+
                        "<td class=\"text-center\">"+pterms[i]['dayofmaturity']+" Days Maturity</td>"+
                        "<td class=\"text-left\">"+pterms[i]['maturityterms']+"</td></tr>";
                    $("#lcPaymentTermsTable").append(ptrow);
                }
            }
        });
        
        $.get("api/attachment?action=1&po="+poid+"&shipno="+shipNum, function (data){
             if($.trim(data)){
                //alert
                var row = JSON.parse(data);
                attach = row[0];
                //alert()
                var attachList = ["LC Payment Advice"];
                //alert(attach);
                attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            }
        });
        
    });
    
}

$(function () {

    var button = $('#btnUploadLCPaymentAdvice'), interval;
    var txtbox = $('#attachLCPaymentAdvice');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf)$/i.test(ext))) {
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

$(function () {

    var button = $('#btnUploadLCPayAcceptCertificate'), interval;
    var txtbox = $('#attachLCPayAcceptCertificate');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf)$/i.test(ext))) {
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

$(function () {

    var button = $('#btnUploadPaymentInstructionLetter'), interval;
    var txtbox = $('#attachPaymentInstructionLetter');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf)$/i.test(ext))) {
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

$(function () {

    var button = $('#btnUploadBankReceivedLetter'), interval;
    var txtbox = $('#attachBankReceivedLetter');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf)$/i.test(ext))) {
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

function CalculatePayment(){
    
    var amount = parseToCurrency($("#amount").val()),
        exchangeRate = parseToCurrency($("#exchangeRate").val());
    
    payAmountBDT = (amount * exchangeRate);
    $("#payAmountBDT").val(commaSeperatedFormat(payAmountBDT.toFixed(2)));
    
}

function SettlementCalculate(){
    //alert('fdsf');
    var stlmntCharge = parseToCurrency($("#stlmntCharge").val()),
        vatOnStlmntCharge = parseToCurrency($("#vatOnStlmntCharge").val()),
        vatRate = parseToCurrency($("#vatRate").val()),
        vatRebateRate = parseToCurrency($("#vatRebateRate").val()),
        vatRebate = parseToCurrency($("#vatRebate").val()),
        bankCharge = parseToCurrency($("#bankCharge").val()),
        totalCharge = parseToCurrency($("#totalCharge").val());
    
    vatOnStlmntCharge = (stlmntCharge * vatRate)/100;
    $("#vatOnStlmntCharge").val(commaSeperatedFormat(vatOnStlmntCharge.toFixed(2)));
    vatRebate = (vatOnStlmntCharge * vatRebateRate)/100;
    $("#vatRebate").val(commaSeperatedFormat(vatRebate.toFixed(2)));
    bankCharge = (stlmntCharge + vatOnStlmntCharge - vatRebate);
    $("#bankCharge").val(commaSeperatedFormat(bankCharge.toFixed(2)));
    totalCharge = (stlmntCharge + vatOnStlmntCharge);
    $("#totalCharge").val(commaSeperatedFormat(totalCharge.toFixed(2)));
}

$('#docReceiveDate,#payDueDate,#payDate,#BBRefDate,#bankNotifyDate,#payMatureDate')
    .datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
})

function validate()
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
    if($("#amount").val()=="")
	{
		$("#amount").focus();
        alertify.error("Amount is required!");
		return false;
	}else{
	   if(!Number(parseToCurrency($("#amount").val()))){
            $("#amount").focus();
            alertify.error("Amount not in valid format!");
    		return false;
	   }else{
	       var pa = parseToCurrency($("#amount").val());
	       if(pa==0){
	           $("#amount").focus();
	           alertify.error("Amount cannot be Zero!");
               return false;
	       }else{
	           pa = commaSeperatedFormat((parseToCurrency($("#ciAmount").val())/100)*parseToCurrency($("#paymentPercent").val()));
	           if($("#amount").val()!=commaSeperatedFormat((parseToCurrency($("#ciAmount").val())/100)*parseToCurrency($("#paymentPercent").val()))){
	               if($("#remarks").val()=="") {
                       $("#remarks").focus();
                       alertify.alert("Payable amount should be " + pa + " but here you mentioned " + $("#amount").val()+".<br/>So you should write a justification.");
                       return false;
                   }
	           }
	       }
	   }
	}
    if($("#exchangeRate").val()=="")
    {
        $("#exchangeRate").focus();
        alertify.error("Exchange Rate is required field!");
        return false;
    }else{
        if(parseToCurrency($("#exchangeRate").val())==0){
            $("#exchangeRate").focus();
            alertify.error("Amount cannot be Zero!");
            return false;
        }
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

    if($("#fundCollectFrom").val()=="")
	{
		$("#fundCollectFrom").focus();
        alertify.error("Fund Collected From is required field!");
		return false;
	}

    if($("#bcSellingRate").val()=="")
    {
        $("#bcSellingRate").focus();
        alertify.error("Please fill up the BCSellingRate!");
        return false;
    }else{
        if(parseToCurrency($("#bcSellingRate").val())==0){
            $("#bcSellingRate").focus();
            alertify.error("BC Selling Rate cannot be Zero!");
            return false;
        }
    }

	return true;	
}

