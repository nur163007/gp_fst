/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid, podata, comments, attach;

$(document).ready(function() {

/**
 * ---------------------------------------------------------------------
 * After document ready
 * ---------------------------------------------------------------------
 */
    poid = "";
    
    $.getJSON("api/purchaseorder?action=6&onlypo=1", function (list) {
        $("#selectPO").select2({
            data: list,
            placeholder: "select PO",
            allowClear: false,
            width: "100%"
        });
    });
    
    
    dataLoadCall();
    
    
    $("#selectPO").on("select2:select", function (e) {
        loadOldPOInfo();
    });
    

    /**
     * ---------------------------------------------------------------------
     * Control Events Binding
     * ---------------------------------------------------------------------
     */
    
    $("#SendPO_btn").click(function (e) {
        //alert('ddas');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit this PO?', function (e) {
                if (e) {
                    $("#SendPO_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/new-pi",
                        data: $('#po-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#SendPO_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    resetForm();
                                    alertify.success(res['message']);
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
                        error: function (xhr, textStatus, error) {
                            alertify.error(textStatus + ": " + xhr.status + " " + error);
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
    
    $("#supplier").on("select2:select", function (e) {
        var id = $("#supplier").val();
        
        $.getJSON("api/category?action=9&cid="+id, function (list) {
            $("#contractref").empty();
            $("#contractref").select2({
                data: list,
                minimumResultsForSearch: Infinity,
                placeholder: "Select Contract Ref",
                allowClear: true,
                width: "100%"
            });
        });
        
        $.get("api/company?action=5&id="+id, function (data) {
            if($.trim(data)){
                var row = JSON.parse(data);
                $("#emailto").tokenfield('setTokens',row['emailTo']);
                $("#emailcc").tokenfield('setTokens',row['emailCc']);
            }
        });
    });
    
    $("#contractref").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Select Contract Ref",
        width: "100%"
    });
    
    $("#currency").change(function(e){
        $("#povalueCur").html($("#currency").find('option:selected').text());
        
    });
    
    $("#povalue").blur(function(e){
        $("#povalue").val(commaSeperatedFormat($("#povalue").val()));
    });
    
    $('#messageyes').on('ifClicked', function(event){
        if($('#messageyes').parent().hasClass('checked')){
            $(".isDefMessage").hide();
        }else{
            $(".isDefMessage").show();
        }
    });
    
/**
 * ---------------------------------------------------------------------
 * Control Events Binding   END
 * ---------------------------------------------------------------------
 */
    
});

function dataLoadCall(){
    
    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a company",
            allowClear: false,
            width: "100%"
        });
        if(poid!=""){
            $('#supplier').val(podata['supplier']).change();
            
            $.getJSON("api/category?action=9&cid="+$("#supplier").val(), function (list) {
                //alert('dsfd');
                $("#contractref").select2({
                    data: list,
                    minimumResultsForSearch: Infinity,
                    placeholder: "Select Contract Ref",
                    allowClear: false,
                    width: "100%"
                });
                $('#contractref').val(podata['contractref']).change();
            });
        }
    });

    $.getJSON("api/category?action=4&id=56", function (list) {
        //alert('sds')
        $("#importAs").select2({
            data: list,
            placeholder: "select a import as",
            allowClear: false,
            width: "100%"
        });
        if(poid!=""){
            $('#importAs').val(podata['importAs']).change();
        }
    });

    $.get("api/category?action=8&id=17", function (list) {
        $("#currency").html('<option value="" data-icon="" data-hidden="true"></option>').append(list);
        $("#currency").selectpicker('refresh');
        $("#currency").val(1);
        $("#currency").selectpicker('refresh');
        $("#povalueCur").html($("#currency").find('option:selected').text());
    });
    
    $.getJSON("api/users?action=5&role="+const_role_PR_Users, function (list) {
        $("#prUserEmailTo, #prUserEmailCC").select2({
            data: list,
            placeholder: "PR User",
            allowClear: false,
            width: "100%"
        });
        if(poid!=""){
            var v1 = podata['pruserto'].split(',');
            $('#prUserEmailTo').val(v1).change();
            var v2 = podata['prusercc'].split(',');
            $('#prUserEmailCC').val(v2).change();
        }
    });
}

function loadOldPOInfo(){
    
    //$('#poid1').val($('#poid').val()).attr("readonly", true);
    $('#poid1').val($('#selectPO').val());
    selectedPO = $('#selectPO').val();
    //alert('api/purchaseorder?action=7&forpi=1&id='+poid);
    $.get('api/purchaseorder?action=7&forpi=1&id='+selectedPO, function (data) {
        
        if($.trim(data)){
            
            var row = JSON.parse(data);
            podata = row[0][0];
            comments = row[1];
            attach = row[2];
            
            // PO info
            
            poid = podata['poid'].substring(0,podata['poid'].indexOf('P'));
            piid = podata['poid'].substring(podata['poid'].indexOf('PI')+2);
            piid = parseInt(piid)+1;
            $("#lblPiNo").html('PI'+piid.toString());
            $("#pino").val('PI'+piid.toString());
            
            $('#podesc').val(podata['podesc']);
            $('#povalue').val(commaSeperatedFormat(podata['povalue']));
            //$('#deliverydate').val(Date_toMDY(new Date(podata['deliverydate'])));

            $('#deliverydate').datepicker('setDate', new Date(podata['deliverydate']));
            $('#draftsendby').datepicker('update');

            $('#emailto').tokenfield('setTokens',podata["emailto"]);
            $('#emailcc').tokenfield('setTokens',podata["emailcc"]);
            $('#noflcissue').val(podata['noflcissue']);
            $('#nofshipallow').val(podata['nofshipallow']);

            //alert(podata["installbysupplier"]);
            $('#installBy_' + podata['installbysupplier']).attr('checked','').parent().addClass('checked');

            var attachList = ["PO","BOQ","Other PO Doc"];
            attachmentLogScript(attach, '#usersAttachments', 1, attachList);
            
            for(var i=0; i<attach.length; i++){
                if(attachList.indexOf(attach[i]['title'])>=0){
                    if(attach[i]['title']=='PO'){ $("#attachpo").val(attach[i]['filename']); }
                    if(attach[i]['title']=='BOQ'){ $("#attachboq").val(attach[i]['filename']); }
                    if(attach[i]['title']=='Other PO Doc'){ $("#attachother").val(attach[i]['filename']); }
                    //if(attach[i]['title']=='BOQ'){ $("#attachboq").val(attach[i]['filename']); }
                    //if(attach[i]['title']=='Other PO Doc'){ $("#attachother").val(attach[i]['filename']); }
                }
            }

            //attachmentLogScript(attach, '#usersAttachments');
            //commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');
            
            dataLoadCall();
        }
    });
}

$("#deliverydate, #draftsendby").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});


//$(".isDefMessage").hide();
//$("#messageyes").click(function (event) {
//    if ($(this).is(":checked"))
//        $(".isDefMessage").show();
//    else
//        $(".isDefMessage").hide();
//});    
//


        
function validate()
{

    if($("#poid1").val()=="")
	{
		$("#poid1").focus();
        alertify.error("PO Number is required!");
		return false;
	}
    if($("#importAs").val()=="")
    {
        $("#importAs").focus();
        alertify.error("Please select Import As!");
        return false;
    }
    if($("#povalue").val()=="")
	{
		$("#povalue").focus();
        alertify.error("PO Value is required!");
		return false;
	} else {
	   if(!Number($("#povalue").val().replace(/,/g,""))){
            $("#povalue").focus();
            alertify.error("Not a valid value!");
            return false;
	   }
	}
    if($("#podesc").val()=="")
	{
		$("#podesc").focus();
        alertify.error("PO Description is required!");
		return false;
	}
    if($("#supplier").val()=="")
	{
		$("#supplier").focus();
        alertify.error("Select a Supplier!");
		return false;
	}
    if($("#currency").val()=="")
	{
		$("#currency").focus();
        alertify.error("Select a Currency!");
		return false;
	}
    if($("#contractref").val()=="")
	{
		$("#contractref").focus();
        alertify.error("Contact Reference is required!");
		return false;
	}
    if($("#contractref").val()=="")
	{
		$("#contractref").focus();
        alertify.error("Contact Reference is required!");
		return false;
	}
    if($("#deliverydate").val()=="")
	{
		$("#deliverydate").focus();
        alertify.error("Fill Up the Need by Date field!");
		return false;
	}
    if($("#draftsendby").val()=="")
	{
		$("#draftsendby").focus();
        alertify.error("Fill Up the Draft PI Last Date field!");
		return false;
	}
    if($("#prEmailTo").val()=="")
	{
		$("#prEmailTo").focus();
        alertify.error("Write PR User Email!");
		return false;
	}
    if($("#prEmailCC").val()=="")
	{
		$("#prEmailCC").focus();
        alertify.error("Write PR User Email!");
		return false;
	}
    if($("#emailto").val()=="")
	{
		$("#emailto").focus();
        alertify.error("Supplier's Email is required!");
		return false;
	}
    if($("#emailcc").val()=="")
	{
		$("#emailcc").focus();
        alertify.error("Supplier's Email CC is required!");
		return false;
	}
    if($("#noflcissue").val()=="")
	{
		$("#noflcissue").focus();
        alertify.error("No of LC is required!");
		return false;
	}
    if($("#nofshipallow").val()=="")
	{
		$("#nofshipallow").focus();
        alertify.error("No of Shipment is required!");
		return false;
	}

    var installBy_check = $('input:radio[name=installBy]:checked').val();

    if(installBy_check==undefined)
    {
        alertify.error("Please select Implement by option!");
        return false;
    }
    /*if($("#attachpo").val()=="")
	{
		$("#attachpo").focus();
        alertify.error("Attach Purchase Order Documents!");
		return false;
	} else {
        if(!/([a-z0-9])*\.(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test($("#attachpo").val())){
            alertify.alert('Invalid File Format.');
            return false;
        }
	}
    if($("#attachboq").val()=="")
	{
		$("#attachboq").focus();
        alertify.error("Attach BOQ Documents!");
		return false;
	} else {
        if(!/([a-z0-9])*\.(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test($("#attachboq").val())){
            alertify.alert('Invalid File Format.');
            return false;
        }
	}
    if($("#attachother").val()!="")
	{
		if(!/([a-z0-9])*\.(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test($("#attachother").val())){
            alertify.alert('Invalid File Format.');
            return false;
        }
	}*/
	return true;	
}

function resetForm(){
    $('#po-form')[0].reset();
    
    $('#emailto').tokenfield('setTokens', []);
    $('#emailcc').tokenfield('setTokens', []);
    
    var d = new Date();
    d.setDate(d.getDate()+3);
    $('#draftsendby').datepicker('setDate', d);
    $('#draftsendby').datepicker('update');
    //getNewID();
}

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

    var button = $('#btnUploadOther'), interval;
    var txtbox = $('#attachother');

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

// default 5 days + for draft send date

var d1 = new Date(), d2 = new Date();

d2.setDate(d2.getDate()+5);

var wc = countWeekend(d1, d2);

d2.setDate(d2.getDate()+wc);

$('#draftsendby').datepicker('setDate', d2);
$('#draftsendby').datepicker('update');

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

/*function validatePONumber(){
    //alert('api/purchaseorder?action=5&po='+$("#poid1").val());
    $.ajax({
        url: 'api/purchaseorder?action=5&po='+$("#poid1").val(),
        global: false,
        success: function(data) {
            //alert(data);
            if ($.trim(data) == '1') {
                $("#poid1").closest("div.form-group").addClass('has-error');
                $("#poNumError").html('PO already exist').removeClass("hidden");
            }else{
                $("#poid1").closest("div.form-group").removeClass('has-error');
                $("#poNumError").html('').addClass("hidden");
            }
    	 }
    });
}*/