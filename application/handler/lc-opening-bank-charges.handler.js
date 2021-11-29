/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var lcocInfo;

$(document).ready(function() {
    
    $("#SaveBankCharge_btn").click(function (e) {
        /*alert('abcd');*/
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function (e) {
                if (e) {
                    $("#SaveBankCharge_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-opening-bank-charges",
                        data: $('#lcOBC-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#SaveBankCharge_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    //ResetForm();
                                    alertify.success('LC Opening Bank Charges updated successfully.');
                                    window.location.href = _dashboardURL + "lc-opening?po=" + $("#pono").val() + "&ref=" + $("#refId").val();
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
    
//    $.get("api/lc-opening?action=1&lc="+$("#lcNumber").val(), function (data) {
//        
//        var lcrow = JSON.parse(data);
//        alert(data);
//    });
    
    //$.getJSON("api/lc-opening?action=2", function (list) {
//        $("#LcNo").select2({
//            data: list,
//            placeholder: "Search LC Number",
//            allowClear: false,
//            width: "100%"
//        });
//        if($("#lcNumber").val()!=""){
//            $("#LcNo").val($("#lcNumber").val());
//            //$("#LcNo").val($("#lcNumber").val()).change();
//            //refreshLCInfo($("#lcNumber").val());
//            //$("#LcNo").attr("disabled", true);
//        }
//    });
    
    if($("#lcNumber").val()!=""){
        $("#LcNo").val($("#lcNumber").val());
        refreshLCInfo($("#LcNo").val()); 
    }
    
    //$("#LcNo").change(function(e){
//       refreshLCInfo($("#LcNo").val());       
//    });
    
    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
        $("#LcIssuingBank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: false,
            width: "100%"
        });
        refreshLCInfo($("#LcNo").val()); 
    });
    
    $.getJSON("api/category?action=4&id=34", function (list) {
        $("#chargeType").select2({
            data: list,
            minimumResultsForSearch: Infinity,
            placeholder: "Select Charge Type",
            allowClear: false,
            width: "100%"
        });
        $("#chargeType").val(28).change();
    });
    
    $.getJSON("api/category?action=4&id=34", function (list) {
        $("#payOrderChargeType").select2({
            data: list,
            minimumResultsForSearch: Infinity,
            placeholder: "Select Charge Type",
            allowClear: false,
            width: "100%"
        });        
        $("#payOrderChargeType").val(29).change();
    });
    
    $.get("api/category?action=8&id=17", function (list) {
        $("#currency").html('<option value="" data-icon="" data-hidden="true"></option>').append(list);
        $("#currency").selectpicker('refresh');
    });
    // Calculate Events ------
    $("#LcCommissionRate, #exchangeRate, #cableCharge, #otherCharge, #nonVAtOtherCharge, #lcCommAddVAT, " +
        "#payOrderIssueCharge, #vatRebateOnLcCommRate, #vatRebateOnOtherChargesRate").keyup(function() {
        CalculateAll();
    });
    
    $("#close_btn").attr("href", _dashboardURL+"lc-opening?po="+$("#pono").val()+"&ref="+$("#refId").val());
    
    //alert("api/lc-opening-bank-charges?action=2&lc="+$("#lcNumber").val());
    $.get("api/lc-opening-bank-charges?action=2&lc="+$("#lcNumber").val(), function (data){
        //alert(data);
        lcocInfo = $.trim(data);
        if($.trim(data)!=""){
            var row = JSON.parse(data);
            $("#chargeType").val(row["chargeType"]).change();
            $("#LcCommissionRate").val(row["LcCommissionRate"]);
            $("#commission").val(commaSeperatedFormat(row["commission"]));
            $("#exchangeRate").val(commaSeperatedFormat(row["exchangeRate"]));
            $("#comissionBDT").val(commaSeperatedFormat(row["comissionBDT"]));
            $("#cableCharge").val(commaSeperatedFormat(row["cableCharge"]));
            $("#nonVAtOtherCharge").val(commaSeperatedFormat(row["nonVAtOtherCharge"]));
            $("#otherCharge").val(commaSeperatedFormat(row["otherCharge"]));
            $("#lcCommAddVAT").val(commaSeperatedFormat(row["lcCommAddVAT"]));
            $("#vatOnComm").val(commaSeperatedFormat(row["vatOnComm"]));
            $("#vatOnOtherCharge").val(commaSeperatedFormat(row["vatOnOtherCharge"]));
            $("#totalVAT").val(commaSeperatedFormat(row["totalVAT"]));
            $("#totalCharge").val(commaSeperatedFormat(row["totalCharge"]));
            $("#vatRebateOnLcComm").val(commaSeperatedFormat(row["vatRebateOnLcComm"]));
            $("#vatRebateOnLcCommRate").val(commaSeperatedFormat(row["vatRebateOnLcCommRate"]));
            $("#vatRebateOnOtherCharges").val(commaSeperatedFormat(row["vatRebateOnOtherCharges"]));
            $("#vatRebateOnOtherChargesRate").val(commaSeperatedFormat(row["vatRebateOnOtherChargesRate"]));
            $("#totalRebate").val(commaSeperatedFormat(row["totalRebate"]));
            $("#capex").val(commaSeperatedFormat(row["capex"]));
            $("#payOrderIssueCharge").val(commaSeperatedFormat(row["payOrderIssueCharge"]));
            $("#vatPayOrderIssueCharge").val(commaSeperatedFormat(row["vatPayOrderIssueCharge"]));
            $("#vatRebateOnPayOrderCharge").val(commaSeperatedFormat(row["vatRebateOnPayOrderCharge"]));
            $("#vatRebateOnPayOrderChargeRate").val(commaSeperatedFormat(row["vatRebateOnPayOrderChargeRate"]));
            $("#totalChargePayOrder").val(commaSeperatedFormat(row["totalChargePayOrder"]));
            $("#payOrderChargeType").val(row["payorderChargeType"]).change();
            
            if(row["attachBankCharge"]!=null) {
                $("#attachBankChargeOld").val(row["attachBankCharge"]);
                //$("#attachBankCharge").val(row["attachBankCharge"]);
                //$("#attachBankChargeLink").html('<i class="icon fa-' + row["attachBankCharge"].substr(row["attachBankCharge"].lastIndexOf(".")+1).toLowerCase() + '"></i>&nbsp;&nbsp;<a href="temp/' + row["attachBankCharge"] + '" title="' + row["attachBankCharge"] + '" target="_blank">Bank Charge Advice</a>');
                $("#attachBankChargeLink").html(attachmentLink(row["attachBankCharge"]));
            }
            if(row["attachIssueCharge"]!=null){
                $("#attachIssueChargeOld").val(row["attachIssueCharge"]);
                //$("#attachIssueCharge").val(row["attachIssueCharge"]);
                // $("#attachIssueChargeLink").html('<i class="icon fa-' + row["attachIssueCharge"].substr(row["attachIssueCharge"].lastIndexOf(".")+1).toLowerCase() + '"></i>&nbsp;&nbsp;<a href="temp/' + row["attachIssueCharge"] + '" title="' + row["attachIssueCharge"] + '" target="_blank">Pay Order Issue Charge</a>');
                $("#attachIssueChargeLink").html(attachmentLink(row["attachIssueCharge"]));
            }

            //$("#lcOBC-form input, #lcOBC-form textarea, #lcOBC-form select, #lcOBC-form button").attr('disabled',true);

        } else{
            //alert("api/lc-opening?action=3&po="+$("#pono").val());
            $.get("api/lc-opening?action=3&po="+$("#pono").val(), function (lcInfo){
                
                if($.trim(lcInfo)!=""){
                    var rowLCInfo = JSON.parse(lcInfo);
                    $("#exchangeRate").val(commaSeperatedFormat(rowLCInfo["xeBDT"]));
                    CalculateAll();
                }
            });
        }
    });
    
});

function CalculateAll(){
    
    var lcvalue = parseToCurrency($("#LcValue").val()),
        commRate = parseToCurrency($("#LcCommissionRate").val()),
        xrate = parseToCurrency($("#exchangeRate").val()),
        cable = parseToCurrency($("#cableCharge").val()),
        other = parseToCurrency($("#otherCharge").val()),
        nvother = parseToCurrency($("#nonVAtOtherCharge").val()),
        commAddVat = parseToCurrency($("#lcCommAddVAT").val()),
        commission = 0, commissionBdt = 0, vatOnComm = 0, vatOnOther = 0,        
        totalvat = 0, totalCharge = 0, vatRebateLCCom = 0, vatRebateOther = 0, totalRebate = 0, capex = 0, 
        vatRebateOnLcCommRate = $("#vatRebateOnLcCommRate").val(), 
        vatRebateOnOtherChargesRate = $("#vatRebateOnOtherChargesRate").val(), 
        vatRebateOnPayOrderChargeRate = $("#vatRebateOnPayOrderChargeRate").val(), 
        poIssueCharge = parseToCurrency($("#payOrderIssueCharge").val()),
        vatOnPOIssueCharge = 0, vatRebateOnPOIssueCharge = 0, totalChargeOnPOIssue = 0;
    
    commission = (lcvalue * commRate)/100;
    $("#commission").val(commaSeperatedFormat(commission));
    commissionBdt = (lcvalue * xrate * commRate)/100;
    $("#comissionBDT").val(commaSeperatedFormat(commissionBdt));
    vatOnComm = (commissionBdt * 15)/100;
    $("#vatOnComm").val(commaSeperatedFormat(vatOnComm));
    vatOnOther = ((other+cable) * 15)/100;
    $("#vatOnOtherCharge").val(commaSeperatedFormat(vatOnOther));
    totalVat = commAddVat + vatOnComm + vatOnOther;
    $("#totalVAT").val(commaSeperatedFormat(totalVat));
    totalCharge = commissionBdt + cable + other + totalVat + nvother;
    $("#totalCharge").val(commaSeperatedFormat(totalCharge));
    vatRebateLCCom = ((vatOnComm + commAddVat)/100) * vatRebateOnLcCommRate;
    $("#vatRebateOnLcComm").val(commaSeperatedFormat(vatRebateLCCom));
    vatRebateOther = (vatOnOther/100) * vatRebateOnOtherChargesRate;
    $("#vatRebateOnOtherCharges").val(commaSeperatedFormat(vatRebateOther));
    totalRebate = vatRebateLCCom + vatRebateOther;
    $("#totalRebate").val(commaSeperatedFormat(totalRebate));
    capex = totalCharge - totalRebate;
    $("#capex").val(commaSeperatedFormat(capex));
    
    vatOnPOIssueCharge = (poIssueCharge * 15)/100;
    $("#vatPayOrderIssueCharge").val(commaSeperatedFormat(vatOnPOIssueCharge));
    vatRebateOnPOIssueCharge = (vatOnPOIssueCharge / 100) * vatRebateOnPayOrderChargeRate;
    $("#vatRebateOnPayOrderCharge").val(commaSeperatedFormat(vatRebateOnPOIssueCharge));
    totalChargeOnPOIssue = poIssueCharge + vatOnPOIssueCharge;
    $("#totalChargePayOrder").val(commaSeperatedFormat(totalChargeOnPOIssue));
}

function refreshLCInfo(lc){
    
    $.get("api/lc-opening?action=1&lc="+lc, function (data) {
        
        var row = JSON.parse(data);
        //alert('row');
        $("#LcIssuingBank").val(row["lcissuerbank"]).change();
		$("#LcDate").val( Date_toMDY( new Date( row["lcissuedate"] ) ));
		$("#currency").val(row['currency']);
        $("#currency").selectpicker('refresh');
		//$("#LcValue").val(row["lcvalue"]);
		$("#LcValue").val(commaSeperatedFormat(row["lcvalue"]));
		$("#lcvalueCur").html($("#currency").find('option:selected').text());
		$("#commissionCur").html($("#lcvalueCur").html());
        
        if(lcocInfo==""){
            CalculateAll();
        }
    });
}

        
function validate()
{
    if($("#LcNo").val()=="")
	{
		$("#LcNo").focus();
        alertify.error("Write LC No!");
		return false;
	}
    if($("#commission").val()=="")
	{
		$("#commission").focus();
        alertify.error("Commission can't be empty!");
		return false;
	}
    if($("#LcCommissionRate").val()=="")
	{
		$("#LcCommissionRate").focus();
        alertify.error("LC comission on VAT is required!");
		return false;
	}
    if($("#exchangeRate").val()=="")
	{
		$("#exchangeRate").focus();
        alertify.error("Exchange rate is required!");
		return false;
	}
    if($("#comissionBDT").val()=="")
	{
		$("#comissionBDT").focus();
        alertify.error("Commission in BDT is required!");
		return false;
	}
    if($("#cableCharge").val()=="")
	{
		$("#cableCharge").focus();
        alertify.error("Cable charge is required!");
		return false;
	}
    if($("#otherCharge").val()=="")
	{
		$("#otherCharge").focus();
        alertify.error("Other charge field is required!");
		return false;
	}
    if($("#nonVAtOtherCharge").val()=="")
	{
		$("#nonVAtOtherCharge").focus();
        alertify.error("Non VAT other charge is required!");
		return false;
	}
    if($("#chargeType").val()=="")
	{
		$("#chargeType").focus();
        alertify.error("Charge type is required!");
		return false;
	}
    if($("#lcCommAddVAT").val()=="")
	{
		$("#lcCommAddVAT").focus();
        alertify.error("LC commission additional VAT is required!");
		return false;
	}
    if($("#vatOnComm").val()=="")
	{
		$("#vatOnComm").focus();
        alertify.error("VAT on commission is required!");
		return false;
	}
    if($("#vatOnOtherCharge").val()=="")
	{
		$("#vatOnOtherCharge").focus();
        alertify.error("VAT on other is required!");
		return false;
	}
    if($("#totalVAT").val()=="")
	{
		$("#totalVAT").focus();
        alertify.error("Total VAT can't be blank!");
		return false;
	}
    if($("#totalCharge").val()=="")
	{
		$("#totalCharge").focus();
        alertify.error("Total charge can't be blank!");
		return false;
	}
    if($("#capex").val()=="")
	{
		$("#capex").focus();
        alertify.error("CAPEX can't be blank!");
		return false;
	}
    if($("#vatRebateOnLcComm").val()=="")
	{
		$("#vatRebateOnLcComm").focus();
        alertify.error("VAT rebate on LC commission is required!");
		return false;
	}
    if($("#vatRebateOnOtherCharges").val()=="")
	{
		$("#vatRebateOnOtherCharges").focus();
        alertify.error("VAT rebate on other charge is required!");
		return false;
	}
    if($("#totalRebate").val()=="")
	{
		$("#totalRebate").focus();
        alertify.error("Total rebate can't be blank!");
		return false;
	}
    
    var chargeBearer_check = $('input:radio[name=chargeBearer]:checked').val();
    
	if(chargeBearer_check==undefined)
	{
		alertify.error("Please select a Charge Bearer!");
		return false;
	}
    if($("#payOrderIssueCharge").val()=="")
	{
		$("#payOrderIssueCharge").focus();
        alertify.error("Pay order issue charge is required!");
		return false;
	}
    if($("#VatPayOrderIssueCharge").val()=="")
	{
		$("#VatPayOrderIssueCharge").focus();
        alertify.error("VAT pay order issue charge is required!");
		return false;
	}
    if($("#vatRebateOnPayOrderCharge").val()=="")
	{
		$("#vatRebateOnPayOrderCharge").focus();
        alertify.error("VAT rebate can't be blank!");
		return false;
	}
    if($("#totalChargePayOrder").val()=="")
	{
		$("#totalChargePayOrder").focus();
        alertify.error("Total charge pay order can't be blank !");
		return false;
	}
    if($("#attachBankCharge").val()=="" && $("#attachBankChargeOld").val()=="")
	{
		$("#attachBankCharge").focus();
        alertify.error("Attach bank charge advice!");
		return false;
	} else {
	    if($("#attachBankCharge").val()!="") {
            if (!validAttachment($("#attachBankCharge").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
        }
	}
    if($("#attachIssueCharge").val()!="")
	{
		if(!validAttachment($("#attachIssueCharge").val())){
            alertify.error('Invalid File Format.');
            return false;
        }
	}
    
	return true;	
}

function ResetForm(){
    //$('#lc-opening-bank-charges-form')[0].reset();
//	$("#LcNo").empty();
    
}


$(function () {

    var button = $('#btnUploadBankCharge'), interval;
    var txtbox = $('#attachBankCharge');

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

$(function () {

    var button = $('#btnUploadIssueCharge'), interval;
    var txtbox = $('#attachIssueCharge');

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
