/*
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/
//alert("fdsfsfdg");

var poid = $('#pono').val();
$(document).ready(function() {

    if($('#pono').val()==""){
        $("#amendment-request-form").hide();
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

    $.getJSON("api/category?action=4&id=34", function (list) {
        $("#chargeType").select2({
            data: list,
            minimumResultsForSearch: Infinity,
            placeholder: "Select Charge Type",
            allowClear: false,
            width: "100%"
        });
        $("#chargeType").val(29).change();
    });

    $("#chargeBorneBy").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Select",
        allowClear: false,
        width: "100%"
    });
    if(poid!=""){
        $.get('api/purchaseorder?action=2&id='+poid, function (data) {

            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
                alertify.error(data, 30);
            }else {

                var row = JSON.parse(data);
                podata = row[0][0];
                //var comments = row[1];
                var attach = row[2];
                var lcinfo = row[3][0];
                //var pterms = row[4];
                
                $("#poNum").val(podata["poid"]);
                $("#lcNum").val(lcinfo["lcno"]);
                $("#lcissuerbank").val(lcinfo["lcissuerbank"]);

                /*$.get('api/lc-opening-bank-charges?action=2&lc='+lcinfo["lcno"], function (data) {
                    if($.trim(data)){
                        var lcoc = JSON.parse(data);
                        $("#commission").val(lcoc["commission"]);
                        CalculateAll();
                    }
                });*/

                if($("#reqType").val()=="new"){
                    $.get('api/amendment-request?action=1&po='+poid+"&lc="+$("#lcno").val(), function (data){
                        $("#amendNo").val(data);
                    });
                    $("#clauseInfoTable").hide();
                     //CalculateAll();
                    if($("#usertype").val()!=11){
                        $("#forLCOper input").attr('readonly',true);
                        $("#forLCOper select").attr('disabled',true);
                    }
                } else{
                    $("#clauseControl").hide();
                    //alert('api/amendment-request?action=2&po='+poid+"&lc="+lcinfo["lcno"]);
                    $.get('api/amendment-request?action=2&po='+poid+"&lc="+lcinfo["lcno"], function (data){
                        //alert(data);
                        var row = JSON.parse(data);
                        var amend = row[0][0],
                            clause = row[1];
                        //$("#commission").val(lcoc["commission"]);
                        $("#amndId").val(amend["id"]);
                        $("#amendNo").val(amend["amendNo"]);
                        $("#amendReason").val(amend["amendReason"]).attr("readonly",true);
                        $("#chargeBorneBy_"+amend["chargeBorneBy"]).attr("checked", true).parent().addClass("checked");
                        if(amend["charge"]!=null){
                            $("#charge").val(commaSeperatedFormat(amend["charge"]));
                            $("#otherCharge").val(commaSeperatedFormat(amend["otherCharge"]));
                            $("#vatRate").val(commaSeperatedFormat(amend["vatRate"]));
                            $("#vatOnCharge").val(commaSeperatedFormat(amend["vatOnCharge"]));
                            $("#vatRebateRate").val(commaSeperatedFormat(amend["vatRebateRate"]));
                            $("#vatRebate").val(commaSeperatedFormat(amend["vatRebate"]));
                        }

                        var cRow = "", clausData = "";
                        for(var i=0; i<clause.length; i++){
                            var sn = i+1;
                            cRow = "<tr><td class=\"text-center\" style=\"width:50px;\" rowspan=\"4\">"+sn+"</td>"+
                                "<td class=\"col-md-3\">Clause #</td><td>"+clause[i]['clauseNumber']+"</td></tr>"+
                                "<tr><td>Title:</td><td>"+clause[i]['clauseTitle']+"</td></tr>"+
                                "<tr><td>Existing Clause:</td><td>"+clause[i]['existingClause']+"</td></tr>"+
                                "<tr><td>New Clause:</td><td>"+clause[i]['newClause']+"</td></tr>";
                            // for letter purpose
                            clausData += "<p><b><u>"+sn+".Clause Number: "+clause[i]['clauseNumber']+"</u></b><br />Title: "+clause[i]['clauseTitle']+"<br />Please Amend: "+clause[i]['newClause']+"</p>";
                            $("#clauseInfoTable").append(cRow);
                        }
                        $("#clausData").val(clausData);
                        if($("#usertype").val()!=11){
                            $("#amendment-request-form input").attr('readonly',true);
                            $("#amendment-request-form select").attr('disabled',true);
                        }
                    });
                }

            }
        });
    }

    $("#SendAmendmentRequest_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validateRequest()) {
            alertify.confirm('Are you sure you want to send amendment request?', function (e) {
                if (e) {
                    $("#SendAmendmentRequest_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/amendment-request",
                        data: $('#amendment-request-form').serialize() + "&userAction=1",
                        cache: false,
                        success: function (response) {
                            //alert(response);
                            try {
                                var res = JSON.parse(response)
                                if (res['status'] == 1) {
                                    alertify.success('Amendment request sent successfully.');
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                $("#SendAmendmentRequest_btn").prop('disabled', false);
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

    $("#acceptRequest_btn").click(function (e) {
        e.preventDefault();
        if (validateAccept()) {
            //alert('abcd');
            alertify.confirm('Are you sure you want to accept?', function (e) {
                if (e) {
                    $("#acceptRequest_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/amendment-request",
                        data: $('#amendment-request-form').serialize() + "&userAction=2",
                        cache: false,
                        success: function (response) {
                            //alert(response);
                            try {
                                var res = JSON.parse(response)
                                if (res['status'] == 1) {
                                    alertify.success('Amendment request Approved.');
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                $("#acceptRequest_btn").prop('disabled', false);
                                alertify.error(response + ' Failed to process the request.', 30);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                }
            });

        } else {
            return false;
        }
    });

    $("#rejectRequest_btn").click(function (e) {
        e.preventDefault();
        if (validateAccept()) {
            //alert('abcd');
            alertify.confirm('Are you sure you want to reject?', function (e) {
                if (e) {
                    $("#rejectRequest_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/amendment-request",
                        data: $('#amendment-request-form').serialize() + "&userAction=3",
                        cache: false,
                        success: function (response) {
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success('Amendment request Rejected.');
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED to add!");
                                    return false;
                                }
                            } catch (e) {
                                $("#rejectRequest_btn").prop('disabled', false);
                                alertify.error(response + ' Failed to process the request.', 30);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                }
            });

        } else {
            return false;
        }
    });

    $("#SendAmendmentCopy_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validateSendCopy()) {
            $("#SendAmendmentCopy_btn").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/amendment-request",
                data: $('#amendment-request-form').serialize() + "&userAction=4",
                cache: false,
                success: function (response) {
                    //alert(response);
                    try {
                        var res = JSON.parse(response)
                        if (res['status'] == 1) {
                            alertify.success('Amendment copy sent successfully.');
                            window.location.href = _dashboardURL;
                        } else {
                            alertify.error("FAILED to add!");
                            return false;
                        }
                    } catch (e) {
                        $("#SendAmendmentCopy_btn").prop('disabled', false);
                        alertify.error(response + ' Failed to process the request.', 30);
                        return false;
                    }
                },
                error: function (xhr) {
                    console.log('Error: ' + xhr);
                }
            });
        } else {
            return false;
        }
    });

    $("#SaveAmendmentCharge_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validateSave()) {
            $("#SaveAmendmentCharge_btn").prop('disabled', true);
            $.ajax({
                type: "POST",
                url: "api/amendment-request",
                data: $('#amendment-request-form').serialize() + "&userAction=5",
                cache: false,
                success: function (response) {
                    //alert(response);
                    try {
                        var res = JSON.parse(response)
                        if (res['status'] == 1) {
                            alertify.success('Amendment charge saved.');
                            //window.location.href = _dashboardURL;
                        } else {
                            alertify.error("FAILED to add!");
                            return false;
                        }
                    } catch (e) {
                        $("#SaveAmendmentCharge_btn").prop('disabled', false);
                        alertify.error(response + ' Failed to process the request.', 30);
                        return false;
                    }
                },
                error: function (xhr) {
                    console.log('Error: ' + xhr);
                }
            });
        } else {
            return false;
        }
    });

    // Calculate Events ------
    $("#charge, #otherCharge, #vatRate, #vatRebateRate").keyup(function() {
        CalculateAll();
    });

    $("#addClauseRow").click(function(e){
        NewClauseRow();
    });


//    $(".minusClauseRow").click(function(e){
//        e.preventDefault();
//        $.this.parent.remove();
//    });

    $("#btnAcceptExisting").attr("href", _dashboardURL+"lc-acceptance?po="+$("#pono").val()+"&ref="+$("#refId").val());

});

function removeClause(obj){
    //alert(obj);
    $("#"+obj).closest("div.clauseRow").remove();
    //$.obj.parent.remove();
    //return false;
}

function CalculateAll(){

    if($("#reqType").val()!="new" && $("#usertype").val()==11){
        var charge = parseToCurrency($("#charge").val()),
            otherCharge = parseToCurrency($("#otherCharge").val()),
            //commission = parseToCurrency($("#commission").val()),
            vatOnCharge = 0,
            vatRate = parseToCurrency($("#vatRate").val()),
            vatRebateRate = parseToCurrency($("#vatRebateRate").val()),
            vatRebate = 0;

        vatOnCharge = ((charge + otherCharge) * vatRate)/100;
        $("#vatOnCharge").val(commaSeperatedFormat(vatOnCharge));
        //vatOnOtherCharge = (otherCharge * vatRate)/100;
        //$("#vatOnOtherCharge").val(commaSeperatedFormat(vatOnOtherCharge));
        vatRebate = (vatOnCharge * vatRebateRate)/100;
        $("#vatRebate").val(commaSeperatedFormat(vatRebate));
    }

}

function validateAccept(){
    if($("#remarks").val()==""){
        $("#remarks").focus();
        alertify.error("Please write your valued comment");
        return false;
    }
    return true;
}

function validateReject(){
    if($("#remarks").val()==""){
        $("#remarks").focus();
        alertify.error("Please write your valued comment");
        return false;
    }
    return true;
}

function validateSendCopy(){
    if($("#attachAmendmentCopy").val()=="")
	{
		$("#attachAmendmentCopy").focus();
        alertify.error("Please Attach Amendment Copy!");
		return false;
	} else {
        if(!validAttachment($("#attachAmendmentCopy").val())){
            alertify.error('Invalid File Format.');
            return false;
        }
	}

    /*if(parseToCurrency($("#charge").val())>0){
        if($("#attachAdviceNote").val()=="")
    	{
    		$("#attachAdviceNote").focus();
            alertify.error("Please Attach Amendment Advice Note!");
    		return false;
    	} else {
            if(!validAttachment($("#attachAdviceNote").val())){
                alertify.error('Invalid File Format.');
                return false;
            }
    	}
    }*/
    return true;
}

function validateRequest(){
    if($("#poNum").val()=="")
	{
		$("#poNum").focus();
        alertify.error("PO Number is required field!");
		return false;
	}

    if($("#lcNum").val()=="")
	{
		$("#lcNum").focus();
        alertify.error("LC Number is required field!");
		return false;
	}

    /*if($("#commmission").val()=="")
	{
		$("#commmission").focus();
        alertify.error("Commission is required field!");
		return false;
	}*/

    /*var chargeBorneBy_check = $('input:radio[name=chargeBorneBy]:checked').val();

	if(chargeBorneBy_check==undefined)
	{
		alertify.error("Please select who will Borne this Charge !");
		return false;
	}*/
    if($("#amendNum").val()=="")
	{
		$("#amendNum").focus();
        alertify.error("Amendment Number is required field!");
		return false;
	}

    if($("#amendReason").val()=="")
	{
		$("#amendReason").focus();
        alertify.error("Amendment Reason is required field!");
		return false;
	}

    var clauseNum = parseInt($('#clauseSl').val())
    for(var i=1; i<=clauseNum; i++){

        if($("#clauseNumber_"+i).val()=="")
    	{
    		$("#clauseNumber_"+i).focus();
            alertify.error("Clause Number is required field!");
    		return false;
    	}

        if($("#clauseTitle_"+i).val()=="")
    	{
    		$("#clauseTitle_"+i).focus();
            alertify.error("Clause title is required field!");
    		return false;
    	}

        if($("#existingClause_"+i).val()=="")
    	{
    		$("#existingClause_"+i).focus();
            alertify.error("Existing Clause is required field!");
    		return false;
    	}

        if($("#newClause_"+i).val()=="")
    	{
    		$("#newClause_"+i).focus();
            alertify.error("New Clause is required field!");
    		return false;
    	}
    }

	return true;
}

function validateSave() {
    if($("#poNum").val()=="")
	{
		$("#poNum").focus();
        alertify.error("PO Number is required field!");
		return false;
	}

    if($("#lcNum").val()=="")
	{
		$("#lcNum").focus();
        alertify.error("LC Number is required field!");
		return false;
	}

    /*if($("#commmission").val()=="")
	{
		$("#commmission").focus();
        alertify.error("Commission is required field!");
		return false;
	}*/

    var chargeBorneBy_check = $('input:radio[name=chargeBorneBy]:checked').val();

	if(chargeBorneBy_check==undefined)
	{
		alertify.error("Please select who will Borne this Charge !");
		return false;
	}

    if($("#charge").val()=="")
	{
		$("#charge").focus();
        alertify.error("Amendment Charge is required field!");
		return false;
	}

    if($("#vatRate").val()=="")
	{
		$("#vatRate").focus();
        alertify.error("This field is required!");
		return false;
	}

    if($("#vatOnCharge").val()=="")
	{
		$("#vatOnCharge").focus();
        alertify.error("VAT On Charge is required field!");
		return false;
	}

    if($("#amendNum").val()=="")
	{
		$("#amendNum").focus();
        alertify.error("Amendment Number is required field!");
		return false;
	}
	return true;
}

if($("#usertype").val()==const_role_LC_Operation) {
    $(function () {

        var button = $('#btnUploadAmendmentCopy'), interval;
        var txtbox = $('#attachAmendmentCopy');

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

        var button = $('#btnUploadAdviceNote'), interval;
        var txtbox = $('#attachAdviceNote');

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
}

if($("#usertype").val()==const_role_Buyer || $("#usertype").val()==const_role_Supplier) {
    $(function () {

        var button = $('#btnUploadAmendmentDocs'), interval;
        var txtbox = $('#attachAmendmentDocs');

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
}

$("#btnGenerateAmndInstructionLetter").click(function(e) {
    e.preventDefault();
    if (validateForLetter()) {
        $.get("api/original-doc?action=3&bank=" + $("#lcissuerbank").val() + "&po=" + $("#poNum").val(), function (data) {
            bankData = JSON.parse(data);
            //--- getting letter serial number based on PO, shipment and bank ---------
            $.get('api/lib-helper?req=1&po=' + $("#poNum").val() + '&ship=0&orgtype=bank&orgid=' + $("#lcissuerbank").val(), function (sl) {

                //------- generating letter reference number ------------------------
                if (sl != "0") {
                    var d = new Date();
                    letterRef = docref_LC_amendment_instruction_letter_ref + d.getFullYear() + "/" + zeroPad(sl);
                } else {
                    letterRef = docref_LC_amendment_instruction_letter_ref + d.getFullYear() + "/" + zeroPad(1);
                }
                //------- end generating letter reference number ------------------------
                $("#btnGenerateAmndInstructionLetter").prop('disabled', true);
                $.ajax({
                    url: "application/templates/letter_template/temp_amendment_instruction_letter.html",
                    cache: false,
                    global: false,
                    success: function (response) {
                        //alert(response);
                        try {
                            var temp = response;

                            //---------------replace data-----------------
                            temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                            temp = temp.replace('##REF##', letterRef);

                            temp = temp.replace(/##LCNO##/g, $("#lcNum").val());
                            temp = temp.replace('##BANKNAME##', bankData["name"]);
                            temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                            temp = temp.replace('##PONO##', originalPO($("#poNum").val()));
                            var chargeBorneBy_check = $('input:radio[name=chargeBorneBy]:checked').val();
                            if (chargeBorneBy_check == 1) {
                                temp = temp.replace('##BORNBY##', "Applicant");
                            } else {
                                temp = temp.replace('##BORNBY##', "Beneficiary");
                            }
                            temp = temp.replace('##CLAUS##', $("#clausData").val());
                            //---------------end replace data-------------

                            $("#fileName").val('amendment_instruction_' + poid + '.doc');
                            $("#letterContent").val(temp);

                            document.getElementById("formLetterContent").submit();
                        } catch (e) {
                            $("#btnGenerateAmndInstructionLetter").prop('disabled', false);
                            alertify.error(response + ' Failed to process the request.', 30);
                            return false;
                        }
                    },
                    error: function (xhr) {
                        console.log('Error: ' + xhr);
                    }
                });
            });
        });
    }

});

function validateForLetter(){
    return true;
}

function NewClauseRow(){

    //var id = $('div#amndClauseRows').children().length+1;
    var id = parseInt($('#clauseSl').val())+1;
    //alert(id);

    $('div#amndClauseRows').append(
       $('<div>').attr('class','col-sm-12 clauseRow').append(
            $('<div>').attr('class','form-group').append(
                $('<label>').attr('class','col-sm-1 control-label text-left').html(id)
            ).append(
                $('<label>').attr('class','col-sm-3 control-label text-left').html('Clause Number:')
            ).append(
                $('<div>').attr('class','col-sm-7').append(
                    $('<input>').attr({'class':'form-control','id':'clauseNumber_'+id, 'name':'clauseNumber[]','placeholder':'Clause Number'})
                )
            ).append(
                $('<div>').attr('class','col-sm-1').append(
                    //$('<button>').attr({'class':'btn btn-warning pull-right minusClauseRow','id':'minusClauseRow_'+id, 'onclick':'removeClause(this)'}).html('<i class="icon wb-close"></i>')
                    $('<input>').attr({'type':'button', 'class':'btn btn-warning pull-right minusClauseRow','id':'minusClauseRow_'+id, 'onclick':'removeClause(this.id)','value':'X'})
                )
            )
        ).append(
            $('<div>').attr('class','form-group').append(
                $('<label>').attr('class','col-sm-1 control-label text-left').html('&nbsp;')
            ).append(
                $('<label>').attr('class','col-sm-3 control-label text-left').html('Clause Title:')
            ).append(
                $('<div>').attr('class','col-sm-7').append(
                    $('<input>').attr({'class':'form-control','id':'clauseTitle_'+id,'name':'clauseTitle[]','placeholder':'Clause Title'})
                )
            )
        ).append(
            $('<div>').attr('class','form-group').append(
                $('<label>').attr('class','col-sm-1 control-label text-left').html('&nbsp;')
            ).append(
                $('<label>').attr('class','col-sm-3 control-label text-left').html('Existing Clause:')
            ).append(
                $('<div>').attr('class','col-sm-7').append(
                    $('<input>').attr({'class':'form-control','id':'existingClause_'+id,'name':'existingClause[]','placeholder':'Existing Clause'})
                )
            )
        ).append(
            $('<div>').attr('class','form-group').append(
                $('<label>').attr('class','col-sm-1 control-label text-left').html('&nbsp;')
            ).append(
                $('<label>').attr('class','col-sm-3 control-label text-left').html('New Title:')
            ).append(
                $('<div>').attr('class','col-sm-7').append(
                    $('<input>').attr({'class':'form-control','id':'newClause_'+id,'name':'newClause[]','placeholder':'New Title'})
                )
            )
        ).append('<hr />')
    );

    $("#clauseSl").val(id);
}

