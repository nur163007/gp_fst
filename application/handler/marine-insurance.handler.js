/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var poid = $('#pono').val();
var minsInfo;


$(document).ready(function() {
    
    $.get('api/purchaseorder?action=2&id='+poid, function (data) {
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {
            var row = JSON.parse(data);
            podata = row[0][0];
            lcinfo = row[3][0];
            
            $("#ponum").val(poid);
            $("#insuranceValue").val( commaSeperatedFormat(podata['basevalue']));
            
            $.get("api/category?action=8&id=17", function (list) {
                $("#currency").html('<option value="" data-icon="" data-hidden="true"></option>').append(list);
                $("#currency").selectpicker('refresh');
                $("#currency").val(podata['currency']);
                $("#currency").selectpicker('refresh');
                
                $("#assuredAmountCur").html($("#currency").find('option:selected').text());
            });            
            
            $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
                $("#lcissuerbank").select2({
                    data: list,
                    placeholder: "Select a Bank",
                    allowClear: false,
                    width: "100%"
                });
                $('#lcissuerbank').val(lcinfo['lcissuerbank']).change();
            });
            
            $.getJSON("api/bankinsurance?action=4&type=insurance", function (list) {
                $("#insurance").select2({
                    data: list,
                    placeholder: "Select an insurance company",
                    allowClear: false,
                    width: "100%"
                });
                $('#insurance').val(lcinfo['insurance']).change();
            });
            
            $.getJSON("api/category?action=4&id=33", function (list) {
                $("#servicePerformance").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select Performance",
                    allowClear: false,
                    width: "100%"
                });
                if(minsInfo){
                    $("#servicePerformance").val(minsInfo["servicePerformance"]).change();
                }
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
            
            CalculateAll();
        }
    });
    
    $("#marine_ins_btn").click(function (e) {
        /*alert('abcd');*/
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function (e) {
                if (e) {
                    $("#marine_ins_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/marine-insurance",
                        data: $('#marine-insurance-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#marine_ins_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    /*ResetForm();*/
                                    alertify.success('Marine Insurance Premium updated successfully.');
                                    window.location.href = _dashboardURL + "lc-opening?po=" + $("#pono").val() + "&ref=" + $("#refId").val();
                                } else {
                                    alertify.error("FAILED to add!");
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
    // Calculate Events ------
    $("#exchangeRate, #stampDuty, #otherCharges, #vatRebate, #lcCommAddVAT, #payOrderIssueCharge").keyup(function() {
        CalculateAll();
    });
    
    $("#close_btn").attr("href", _dashboardURL+"lc-opening?po="+$("#pono").val()+"&ref="+$("#refId").val());
    
    $.get("api/marine-insurance?action=1&po="+$("#pono").val(), function (data){
        
        if($.trim(data)!=""){
            
            var row = JSON.parse(data);
            
            minsInfo = row;
            
            $("#ponum").val(row["ponum"]);
            $("#insuranceValue").val(row["insuranceValue"]);
            $("#coverNoteNo").val(row["coverNoteNo"]);
            $("#coverNoteDate").val(row["coverNoteDate"]);
            $("#assuredAmount").val(row["assuredAmount"]);
            $("#exchangeRate").val(row["exchangeRate"]);
            $("#marine").val(row["marine"]);
            $("#war").val(row["war"]);
            $("#netPremium").val(row["netPremium"]);
            $("#vat").val(row["vat"]);
            $("#stampDuty").val(row["stampDuty"]);
            $("#otherCharges").val(row["otherCharges"]);
            $("#total").val(row["total"]);
            $("#chargeType").val(row["chargeType"]);
            $("#vatRebate").val(row["vatRebate"]);
            $("#capex").val(row["capex"]);
            $("#vatPayable").val(row["vatPayable"]);
            $("#chargeRemarks").val(row["chargeRemarks"]);
            $("#servicePerformance").val(row["servicePerformance"]).change();
            $("#serviceRemarks").val(row["serviceRemarks"]);
            $("#vatRebateAmount").val(row["vatRebateAmount"]);
            $("#premiumBorneBy_"+row["premiumBorneBy"]).attr("checked", true).parent().addClass("checked");
            
            if(row["attachInsCoverNote"]!=null){
                $("#attachInsCoverNoteOld").val(row["attachInsCoverNote"]);
                //$("#attachInsCoverNote").val(row["attachInsCoverNote"]);
                $("#attachInsCoverNoteLink").html(attachmentLink(row["attachInsCoverNote"]));
            }
            if(row["attachPayOrderReceivedCopy"]!=null){
                $("#attachPayOrderReceivedCopyOld").val(row["attachPayOrderReceivedCopy"]);
                // $("#attachPayOrderReceivedCopy").val(row["attachPayOrderReceivedCopy"]);
                $("#attachPayOrderReceivedCopyLink").html(attachmentLink(row["attachPayOrderReceivedCopy"]));
            }
            if(row["attachInsChargeOther"]!=null){
                $("#attachInsChargeOtherOld").val(row["attachInsChargeOther"]);
                // $("#attachInsChargeOther").val(row["attachInsChargeOther"]);
                $("#attachInsChargeOtherLink").html(attachmentLink(row["attachInsChargeOther"]));
            }
            
            CalculateAll();
            
            // $("#marine_ins_btn").attr("disabled", true);
            // $("#marine-insurance-form input, #marine-insurance-form textarea, #marine-insurance-form select, #marine-insurance-form button").attr('disabled',true);
        
        } else{
            
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

$("#coverNoteDate").datepicker({
    todayHighlight: true,
    autoclose: true
});

function CalculateAll(){
    
    var insuranceValue = parseToCurrency($("#insuranceValue").val()),
        assuredAmount = parseToCurrency($("#assuredAmount").val()),
        marine = parseToCurrency($("#marine").val()),
        war = parseToCurrency($("#war").val()),
        netPremium = parseToCurrency($("#netPremium").val()),
        vat = parseToCurrency($("#vat").val()),
        stampDuty = parseToCurrency($("#stampDuty").val()),
        otherCharges = parseToCurrency($("#otherCharges").val()),
        total = parseToCurrency($("#total").val()),
        vatRebate = parseToCurrency($("#vatRebate").val()),
        vatRebateAmount = parseToCurrency($("#vatRebateAmount").val()),
        exchangeRate = parseToCurrency($("#exchangeRate").val()),
        total = 0, capex = 0, insuranceValueBDT = 0;
    //alert(assuredAmount);
    insuranceValueBDT = insuranceValue * exchangeRate;
    assuredAmount = (insuranceValue * 1.1);
    $("#assuredAmount").val(commaSeperatedFormat(assuredAmount.toFixed(2)));
    
    assuredAmountBDT = (insuranceValueBDT * 1.1);
    $("#assuredAmountBDT").val(commaSeperatedFormat(assuredAmountBDT.toFixed(2)));
    
    //marine = (assuredAmountBDT * 0.00135); //Changed as suggested by Tamzid bhai,on 2020-08-27
    marine = (assuredAmountBDT * 0.0015);
    $("#marine").val(commaSeperatedFormat(marine.toFixed(2)));
    war = (assuredAmountBDT * 0.0005);
    $("#war").val(commaSeperatedFormat(war.toFixed(2)));
    netPremium = (marine + war);
    $("#netPremium").val(commaSeperatedFormat(netPremium.toFixed(2)));
    vat = (netPremium * 0.15);
    $("#vat").val(commaSeperatedFormat(vat.toFixed(2)));
    total = (netPremium + vat + stampDuty + otherCharges);
    $("#total").val(commaSeperatedFormat(total.toFixed(2)));
    vatRebateAmount = (vat * vatRebate)/100;
    $("#vatRebateAmount").val(commaSeperatedFormat(vatRebateAmount.toFixed(2)));
    capex = (total - vatRebateAmount);
    $("#capex").val(commaSeperatedFormat(capex.toFixed(2)));
    vatPayable = (vat - vatRebateAmount);
    $("#vatPayable").val(commaSeperatedFormat(vatPayable.toFixed(2)));
}

        
function validate()
{
    if($("#bankname").val()=="")
	{
		$("#bankname").focus();
        alertify.error("Bank name is mandatory!");
		return false;
	}
    if($("#currency").val()=="")
	{
		$("#currency").focus();
        alertify.error("Please select a currency!");
		return false;
	}
    if($("#insuranceValue").val()=="")
	{
		$("#insuranceValue").focus();
        alertify.error("Insurance value is required!");
		return false;
	}
    if($("#insurance").val()=="")
	{
		$("#insurance").focus();
        alertify.error("Please select an Insurance Company!");
		return false;
	}
    if($("#coverNoteNo").val()=="")
	{
		$("#coverNoteNo").focus();
        alertify.error("Cover note number is required!");
		return false;
	}
    if($("#coverNoteDate").val()=="")
	{
		$("#coverNoteDate").focus();
        alertify.error("Cover note date is mandatory!");
		return false;
	}
    if($("#exchangeRate").val()=="")
	{
		$("#exchangeRate").focus();
        alertify.error("Exchange rate field is required!");
		return false;
	}
    if($("#stampDuty").val()=="")
	{
		$("#stampDuty").focus();
        alertify.error("Stamp Duty field is required!");
		return false;
	}
    if($("#otherCharges").val()=="")
	{
		$("#otherCharges").focus();
        alertify.error("Other charges is required!");
		return false;
	}
    if($("#assuredAmount").val()=="")
	{
		$("#assuredAmount").focus();
        alertify.error("Assured amount isrequired!");
		return false;
	}
    if($("#marine").val()=="")
	{
		$("#marine").focus();
        alertify.error("Marine charge is required!");
		return false;
	}
    if($("#war").val()=="")
	{
		$("#war").focus();
        alertify.error("This field is required!");
		return false;
	}
    if($("#netPremium").val()=="")
	{
		$("#netPremium").focus();
        alertify.error("Net premium field is required!");
		return false;
	}
    if($("#vat").val()=="")
	{
		$("#vat").focus();
        alertify.error("VAT is required field!");
		return false;
	}
    if($("#total").val()=="")
	{
		$("#total").focus();
        alertify.error("Total field is required!");
		return false;
	}
    if($("#vatRebate").val()=="")
	{
		$("#vatRebate").focus();
        alertify.error("VAT rebate field is mandatory!");
		return false;
	}
    if($("#vatRebateAmount").val()=="")
	{
		$("#vatRebateAmount").focus();
        alertify.error("VAT rebate amount field is required!");
		return false;
	}
    if($("#capex").val()=="")
	{
		$("#capex").focus();
        alertify.error("CAPEX field is required!");
		return false;
	}
    if($("#vatPayable").val()=="")
	{
		$("#vatPayable").focus();
        alertify.error("VAT payable field is required!");
		return false;
	}
    var premiumBorneBy_check = $('input:radio[name=premiumBorneBy]:checked').val();
    
	if(premiumBorneBy_check==undefined)
	{
		alertify.error("Premium Borne By field is required!");
		return false;
	}
    if($("#chargeRemarks").val()=="")
	{
		$("#chargeRemarks").focus();
        alertify.error("Please write a remarks!");
		return false;
	}
    if($("#servicePerformance").val()=="")
	{
		$("#servicePerformance").focus();
        alertify.error("Please select service performance!");
		return false;
	}
    if($("#servicePerformance").find('option:selected').text()!="Good"){
        if($("#serviceRemarks").val()==""){
            $("#serviceRemarks").focus();
            alertify.error("You must write a remarks for this performance.");
            return false;
        }
    }
    if($("#attachInsCoverNote").val()=="" && $("#attachInsCoverNoteOld").val()=="")
	{
		$("#attachInsCoverNote").focus();
        alertify.error("You must Attach insurance cover note!");
		return false;
	} else {
        if($("#attachInsCoverNote").val()!="") {
            if (!validAttachment($("#attachInsCoverNote").val())) {
                alertify.error('Invalid File Format.');
                return false;
            }
        }
	}
    if($("#attachPayOrderReceivedCopy").val()!="")
	{
        if(!validAttachment($("#attachPayOrderReceivedCopy").val())){
            alertify.error('Invalid File Format.');
            return false;
        }
	}
    if($("#attachInsChargeOther").val()!="")
    {
        if(!validAttachment($("#attachInsChargeOther").val())){
            alertify.error('Invalid File Format.');
            return false;
        }
    }
	return true;	
}

function ResetForm(){
    /*$('#marine-insurance-form')[0].reset();
	$("#insuranceValue").empty();*/
    
}


$(function () {

    var button = $('#btnUploadInsCoverNote'), interval;
    var txtbox = $('#attachInsCoverNote');

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

    var button = $('#btnUploadPayOrderReceivedCopy'), interval;
    var txtbox = $('#attachPayOrderReceivedCopy');

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

    var button = $('#btnUploadInsChargeOther'), interval;
    var txtbox = $('#attachInsChargeOther');

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