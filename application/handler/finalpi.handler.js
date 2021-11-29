/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
$(document).ready(function() {
    
    if($('#poid').val()!=""){
        var poid = $('#poid').val();
        $('#ponum').html(poid);
        
        $.get('api/status?action=1&poid='+poid, function (res) {
            var row = JSON.parse(res);
            $('#postatus').val(row['status']);
            
            var u = $('#usertype').val();
            var a = row['targetrole'].split(',');
            //alert(a.indexOf(u));
            if(a.indexOf(u)<0){
                $("#finalpiboq-form input, #finalpiboq-form textarea, #finalpiboq-form select, #finalpiboq-form button").attr('disabled',true);
            }
        });
        
        $.getJSON("application/library/country.txt", function (data) {
            $("#origin").select2({
                data: data,
                placeholder: "Select a origin"
              });
        });
        
        $.get('api/purchaseorder?action=2&id='+poid, function (data) {
            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {
                var row = JSON.parse(data);
                var podata = row[0][0];
                var comments = row[1];
                var attach = row[2];
                
                // PO info
                $('#povalue').html('<b>'+commaSeperatedFormat(podata['povalue'])+'</b> '+podata['curname']);
                $('#podesc').html(podata['podesc']);
                $('#lcdesc').html('<b>'+podata['lcdesc']+'</b>');
                $('#supplier').html(podata['supname']);
                $('#contractref').html(podata['contractref']);
                $('#deliverydate').html(podata['deliverydate']);
                if(podata["installbysupplier"]==0){
    				$('#installbysupplier').html('No');
    			} else {
    				$('#installbysupplier').html('Yes');
    			}
                $('#noflcissue').html(podata['noflcissue']);
                $('#nofshipallow').html(podata['nofshipallow']);
                // Buyer's comments log
                $('#buyersmsgtitle').hide();
                $('#buyersmsg').hide();
                var commentsLog = '';
                for(var i=0; i<comments.length; i++){
                    if(comments[i]['msg']!=null && comments[i]['fromgroup']!=_supplier){
                        if(commentsLog.length>0){commentsLog += '<hr />';}
                        commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span></span> <div class="comment-meta">'+comments[i]['msgon'] + '</div>';
                        commentsLog += '<div class="comment-content">' + comments[i]['msg'] + '</div>';
                    }
                }
                if(commentsLog!=''){
                    $('#buyersmsg').html(commentsLog);
                    $('#buyersmsgtitle').show();
                    $('#buyersmsg').show();
                }
                
                // Supplier's comments log
                $('#suppliersmsgtitle').hide();
                $('#suppliersmsg').hide();
                var commentsLog = '';
                for(var i=0; i<comments.length; i++){
                    if(comments[i]['msg']!=null && comments[i]['fromgroup']==_supplier){
                        if(commentsLog.length>0){commentsLog += '<hr />';}
                        commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span></span> <div class="comment-meta">'+comments[i]['msgon'] + '</div>';
                        commentsLog += '<div class="comment-content">' + comments[i]['msg'] + '</div>';
                    }
                }
                if(commentsLog!=''){
                    $('#suppliersmsg').html(commentsLog);
                    $('#suppliersmsgtitle').show();
                    $('#suppliersmsg').show();
                }
                
                
                // loading buyer's 'attachments
                var buyersAttachments = '';
                var buyersSubject = ["po", "boq", "other doc"];
                
                for(var i=0; i<attach.length; i++){
                    if(buyersSubject.indexOf(attach[i]['title'].toLowerCase())>-1){
                        buyersAttachments += '<label class="col-sm-3 control-label">'+attach[i]['title']+'</label>'+
                            '<div class="col-sm-9">'+
                                '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/'+attach[i][0]+'" target="_blank">'+attach[i]['title']+'</a></label>'+
                            '</div>';
                    }
                }
                $('#buyersAttachment').html(buyersAttachments);
                
                // PI info
                $('#pinum').val(podata['pinum']);                
                $('#pivalue').val(podata['pivalue']);
                $("#shipmode"+podata['shipmode']).attr("checked","").parent().addClass("checked");
                
                $('#hscsea').val(podata['hscsea']);
                $('#hscode').val(podata['hscode']);
                $('#origin').val(podata['origin']).change();
                $('#negobank').val(podata['negobank']);
                $('#shipport').val(podata['shipport']);
                $('#lcbankaddress').val(podata['lcbankaddress']);
                $('#productiondays').val(podata['productiondays']);
                $('#buyercontact').val(podata['buyercontact']);
                $('#techcontact').val(podata['techcontact']);
                
                // loading supplier's 'attachments
                var suppliersAttachments = '';
                var suppliersSubject = ["draft pi", "draft boq", "catalog"];
                
                for(var i=0; i<attach.length; i++){
                    if(suppliersSubject.indexOf(attach[i]['title'].toLowerCase())>-1){
                        suppliersAttachments += '<label class="col-sm-3 control-label">'+attach[i]['title']+'</label>'+
                            '<div class="col-sm-9">'+
                                '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/'+attach[i][0]+'" target="_blank">'+attach[i]['title']+'</a></label>'+
                            '</div>';
                    }
                }
                $('#suppliersAttachments').html(suppliersAttachments);
                
                // loading pr's 'attachments
                var prAttachment = '';
                var prSubject = ["justification"];
                
                for(var i=0; i<attach.length; i++){
                    if(prSubject.indexOf(attach[i]['title'].toLowerCase())>-1){
                        prAttachment += '<label class="col-sm-3 control-label">'+attach[i]['title']+'</label>'+
                            '<div class="col-sm-9">'+
                                '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/'+attach[i][0]+'" target="_blank">'+attach[i]['title']+'</a></label>'+
                            '</div>';
                    }
                }
                $('#prAttachment').html(prAttachment);
            }
        });
    }
    
    $("#messageUserYes").click(function (event) {
        if ($(this).is(":checked"))
            $(".isMessageUser").show();
        else
            $(".isMessageUser").hide();
    });
    

    // Final PI submit to Buyer
    $("#finalPIToBuyer_btn").click(function (e) {
        
        e.preventDefault();
        
        if(validate() === true){
            
            alertify.confirm( 'Are you sure you want submit this final PI?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/finalpi",
                        data: $('#finalpiboq-form').serialize(),
                        cache: false,
                        success: function (result) {
                            //alert(result)
                            var res = JSON.parse(result);
                            
                            if (res['status'] == 1) {
                                alertify.alert(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED to send!");
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
    
});

function validate()
{
	if($("#pinum").val()=="")
	{
		$("#pinum").focus();
        alertify.error("PI Number is required!");
		return false;
	}
	if($("#pivalue").val()=="")
	{
		$("#pivalue").focus();
        alertify.error("PI Value is required!");
		return false;
	} else {
	   if(!Number($("#pivalue").val())){
            $("#pivalue").focus();
            alertify.error("Not a valid amount!");
            return false;
	   }
	}
    
    var shipmode_check = $('input:radio[name=shipmode]:checked').val();
    
	if(shipmode_check==undefined)
	{
		alertify.error("Please select a Shipment Mode!");
		return false;
	}
	if(shipmode_check=="sea")
	{
        if($("#hscsea").val()==""){
            $("#hscsea").focus();
            alertify.error("HS Code Sea is required!");
            return false;
        }
	}
    if(shipmode_check=="air")
	{
        if($("#hscode").val()=="")
    	{
    		$("#hscode").focus();
            alertify.error("HS Code Air is required!");
    		return false;
    	}
	}
    if(shipmode_check=="sea+air")
	{
        if($("#hscode").val()=="" || $("#hscsea").val()=="")
    	{
            if($("#hscsea").val()==""){
                $("#hscsea").focus();
            }
            if($("#hscode").val()==""){
                $("#hscode").focus();
            }
    		
            alertify.error("Both HS Codes are required!");
    		return false;
    	}
	}
	
	if($("#pidate").val()=="")
	{
		$("#pidate").focus();
        alertify.error("PI Date is required!");
		return false;
	}
	if($("#basevalue").val()=="")
	{
		$("#basevalue").focus();
        alertify.error("Base value is required!");
		return false;
	}else {
	   if(!Number($("#basevalue").val())){
            $("#basevalue").focus();
            alertify.error("Not a valid value type!");
            return false;
	   }
	}
	if($("#origin").val()=="")
	{
		$("#origin").focus();
        alertify.error("Country of Origin is required!");
		return false;
	}
	if($("#negobank").val()=="")
	{
		$("#negobank").focus();
        alertify.error("Negotiating Bank is required!");
		return false;
	}
	if($("#shipport").val()=="")
	{
		$("#shipport").focus();
        alertify.error("Port of Shipment is required!");
		return false;
	}
	if($("#lcbankaddress").val()=="")
	{
		$("#lcbankaddress").focus();
        alertify.error("L/C Beneficiary & Address is required!");
		return false;
	}
	if($("#productiondays").val()=="")
	{
		$("#productiondays").focus();
        alertify.error("Production Days is required!");
		return false;
	} else {
	   if(!Number($("#productiondays").val())){
            $("#productiondays").focus();
            alertify.error("Not a valid number!");
            return false;
	   }
	}
	if($("#buyercontact").val()=="")
	{
		$("#buyercontact").focus();
        alertify.error("Buyer contact is required!");
		return false;
	}
	if($("#techcontact").val()=="")
	{
		$("#techcontact").focus();
        alertify.error("Technical contact is required!");
		return false;
	}
    if($("#attachFinalPI").val()=="")
	{
		$("#attachFinalPI").focus();
        alertify.error("Attach Final PI Document!");
		return false;
	}
    if($("#attachFinalBOQ").val()=="")
	{
		$("#attachFinalBOQ").focus();
        alertify.error("Attach Final PI BOQ Document!");
		return false;
	}
    if($("#attachFinalCatelog").val()=="")
	{
		$("#attachFinalCatelog").focus();
        alertify.error("Attach Final Catalog Document!");
		return false;
	}
	return true;
}

function resetForm(){
    $('#finalpiboq-form')[0].reset();
    $('#origin').val('').change();
}

$(function () {

    var button = $('#btnUploadFinalPI'), interval;
    var txtbox = $('#attachFinalPI');
    
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
    }
});


$(function () {

    var button = $('#btnUploadFinalBOQ'), interval;
    var txtbox = $('#attachFinalBOQ');

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
    }
});


$(function () {

    var button = $('#btnUploadFinalCatelog'), interval;
    var txtbox = $('#attachFinalCatelog');

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
    }
});
