/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val();

var podata,
    comments,
    attach,
    lcinfo,
    pterms;

var termsData;

$(document).ready(function(){
    // Loading pre data from PO
    $.get('api/purchaseorder?action=2&id='+poid, function (data) {
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {
            var row = JSON.parse(data);
            podata = row[0][0];
            comments = row[1];
            attach = row[2];
            if(typeof(row[3]) != 'undefined'){
                lcinfo = row[3][0];
                //alert(lcinfo["lcdesc"]);
                pterms = row[4];
            }

            // PO Information
            $('#ponum').html(podata['poid']);
            $('#povalue').html(commaSeperatedFormat(podata['povalue']));
            $('#currency').html(podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#lcdesc').html('<b>'+podata['lcdesc']+'</b>');
            
            $('#supplier').html(podata['supname']).attr('data-value',podata['supname']);
            
            $('#contractref').html(podata['contractrefName']);
            $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
            $('#installbysupplier').html(getImplementedBy(podata["installbysupplier"]));
            $('#noflcissue').html(podata['noflcissue']).attr('data-value',podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']).attr('data-value',podata['nofshipallow']);

            attachmentLogScript(attach, usersAttachments);
            commentsLogScript(comments, '#buyersmsg', '');
            
            // PI info
            $('#pinum').html(podata['pinum']);
            
            $('#pivalue').html(commaSeperatedFormat(podata['pivalue']));
            $('#picurrency').html(podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            
            $('#shipHSCsea').hide();
            $('#shiphscode').hide();
            
            if(podata['shipmode']=='sea'){$('#shipHSCsea').show(); $("#forAirShipment1").val(""); $("#forAirShipment2").val(""); }
            if(podata['shipmode']=='air'){$('#shiphscode').show(); $("#forSeaShipment1").val(""); $("#forSeaShipment2").val(""); }
            if(podata['shipmode']=='sea+air'){$('#shipHSCsea').show(); $('#shiphscode').show();}

            if(podata['shipmode']=='sea'){$('#shippingRemarks').val( $('#shippingRemarks').val().replace("XXXX", "Bill of Lading") );}
            if(podata['shipmode']=='air'){$('#shippingRemarks').val( $('#shippingRemarks').val().replace("XXXX", "Air Way Bill") );}

            $('#hscode').html(podata['hscode']);
            
            $('#pidate').html(Date_toDetailFormat(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']));
            $('#basecurrency').html(podata['curname']);
            
            $('#origin').html(podata['origin']).attr('data-value',podata['origin']);
            $('#negobank').html(HTMLDecode(podata['negobank']));
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']+' days');
            //$('#buyercontact').html(podata['buyercontact']);
            //$('#techcontact').html(podata['techcontact']);
            
            // LC Request Form
            
            $('#pono1').val(podata['poid']);
            $('#pono2').val(podata['poid']);
            $('#lcdesc1').val(podata['lcdesc']);
            $('#supplier1').val(podata['supname']);
            $('#shipmode1').html('Shipment Mode: '+podata['shipmode']);
            $('#shipmode2').val(podata['shipmode'].toUpperCase());
            $('#hscsea1').val(podata['hscsea']);
            $('#hscode1').val(podata['hscode']);
            $('#origin1').val(podata['origin']);
            $('#origin2').val(podata['origin']);
            $('#negobank1').val(HTMLDecode(podata['negobank']));
            $('#shipport1').val(podata['shipport']);
            if(podata['shipmode']=='sea'){
                $('#destination').val('Chittagong Sea Port');
            }else if(podata['shipmode']=='air'){
                $('#destination').val('Dhaka Air Port');
            }
            $('#lcbankaddress1').val(HTMLDecode(podata['lcbankaddress']));
            
            $('#lcvalue').val(commaSeperatedFormat(podata['pivalue']));
            $('#lccurrency').html(podata['curname']);
            
            //$("#paymentTermsText").val("api/category?action=7&id="+podata["contractref"]);
            //alert("api/category?action=1&id="+podata["contractref"]);
            $.get("api/category?action=1&id="+podata["contractref"], function (data) {
                
                termsData = JSON.parse(data);
                //alert(HTMLDecode(termsData['tag']));
                var contractFile = termsData['name']+".pdf";
                
                $("#contractDetailPdf").attr("href","temp/vendors_terms/"+contractFile).attr("target","_blank");
                $("#contractDetailPdf").html(termsData['name']);
                
                loadTermsInformation(HTMLDecode(termsData['tag']), podata['installbysupplier']);
                
                $("#paymentTermsText").val(termsData['metatext'].split('|')[podata['installbysupplier']]);
            });
            
            //alert(podata["installbysupplier"]);
            $("#installBy").val(podata["installbysupplier"]);
            $("#installByOld").val(podata["installbysupplier"]);
            $("#installBy").selectpicker('refresh');
        }
    });
    
    var d1 = new Date();
    d1.setDate(d1.getDate()+89);

    //seting last shipment date to before 21days of LC expiry date
    $('#lcexpirydate').datepicker().on('changeDate', function(e) {
        var d = new Date($("#lcexpirydate").val());
        d.setDate(d.getDate()-21);
        $('#lastdateofship').datepicker('setDate', d);
        $('#lastdateofship').datepicker('update');
    });

    $('#lcexpirydate').datepicker('setDate', d1);
    $('#lcexpirydate').datepicker('update');

    $("#addNewPaymentTermsRow").click(function(e){
        NewPaymentTermsRow('','','');
    });
    
    $("#tinno").keyup(function() {
        $("#tinno1").val($("#tinno").val());
    });
    
    if(isset(lcinfo)){
        alert(lcinfo["lcexpirydate"]);
        $("#lcdesc1").val(lcinfo["lcdesc"]);
        $("#imppermitno").val(lcinfo["imppermitno"]);
        $('#lcvalue').val(commaSeperatedFormat(lcinfo['lcvalue']));
        $("#ircno").val(lcinfo['ircno']);
        $("#tinno").val(lcinfo['tinno']);
        $("#lcno").val(lcinfo['lcno']);
        $("#vatregno").val(lcinfo['vatregno']);
        $("#customername").val(lcinfo['customername']);
        $("#customeraddress").val(lcinfo['customeraddress']);
        $("#tinno1").val(lcinfo['tinno']);
        $("#paymentTermsText").val(lcinfo['paymentterms']);
        
        var xd = new Date(Date_toMDY(new Date(lcinfo['lcexpirydate'])));
        //xd.setDate(xd.getDate()-21);
        $('#lcexpirydate').datepicker('setDate', xd);
        $('#lcexpirydate').datepicker('update');
        
        var ld = new Date(Date_toMDY(new Date(lcinfo['lastdateofship'])));
        //ld.setDate(ld.getDate()-21);
        $('#lastdateofship').datepicker('setDate', ld);
        $('#lastdateofship').datepicker('update');

    }
    
    // Submit LC request
    $("#SendLCRequest_btn").click(function(e){
        //alert($('#lcrequest-form').serialize());
        e.preventDefault();
        if(validate() === true){
            alertify.confirm( 'Are you sure you want send LC request?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/buyers-lc-request",
                        data: $('#lcrequest-form').serialize(),
                        cache: false,
                        success: function (result) {
                       //alertify.alert(result);
                            var res = JSON.parse(result);
                            
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
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
        		}
        	});
        } else {
            return false;
        }
    });
    
    $("#installBy").change(function(e){
        //alert(termsData['tag']);
        loadTermsInformation(HTMLDecode(termsData['tag']), $("#installBy").val());
    });
});

$("#lcexpirydate, #lastdateofship").datepicker({
    todayHighlight: true,
    autoclose: true
});

function loadTermsInformation(termsArray, installBy){
    
    var arr = eval(termsArray.split('|')[installBy]);

    $('div#lcPaymentTermsTable').empty();

    for(i=0; i<arr.length; i++){
        NewPaymentTermsRow(arr[i][0],arr[i][1],arr[i][2],arr[i][3],'','');
    }
    
    $("#paymentTermsText").val(termsData['metatext'].split('|')[installBy]);
}

function validate(){

    var lctype_check = $('input:radio[name=lcRequestType]:checked').val();

    if(lctype_check==undefined)
    {
        alertify.error("Please select LC type!");
        return false;
    }

    if($("#ircno").val()==""){
        $("#ircno").focus();
        alertify.error("IRC Number is required!");
		return false;
    }
    if($("#tinno").val()==""){
        $("#tinno").focus();
        alertify.error("TIN Number is required!");
		return false;
    }
    if($("#vatregno").val()==""){
        $("#vatregno").focus();
        alertify.error("VAT Reg. Number is required!");
		return false;
    }
    if($("#lcPaymentTermsTable").html()=="" || $("#paymentTermsText").val()==""){
        $("#paymentTermsText").focus();
        alertify.error("Please select appropriate option for Implemented by!");
		return false;
    }
    if($("#customername").val()==""){
        $("#customername").focus();
        alertify.error("Customer name is required!");
		return false;
    }
    if($("#customeraddress").val()==""){
        $("#customeraddress").focus();
        alertify.error("Customer address is required!");
		return false;
    }
    if($("#lcexpirydate").val()==""){
        $("#lcexpirydate").focus();
        alertify.error("LC Expiry date is required!");
		return false;
    }
    if($("#lastdateofship").val()==""){
        $("#lastdateofship").focus();
        alertify.error("Last date of shipment is required!");
		return false;
    }

    var value = $('.required-entry').filter(function () {
        return this.value === '';
    });
    if (value.length > 0) {
        alertify.error('Please fill out Certificate title in payment terms.');
        return false;
    }

    return true;
}

// Generate new payment terms
function NewPaymentTermsRow(pp, cc, dd, tt, cfd, cft){

    cfd = (!cfd) ? 0 : cfd ;

    var id = $('div#lcPaymentTermsTable').children().length+1;

    $('div#lcPaymentTermsTable').append(
        $('<div>').attr({'class':'form-group', 'id':'lcPaymentTermsRow'+id}).append(
            $('<label>').attr('class','col-sm-1 control-label text-left').html(id.toString()+'.')
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppPercentage[]', 'id':'ppPercentage_'+id.toString()}).val(pp)
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                //$('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppPartName[]', 'id':'ppPartName_'+id.toString()})
                '<select class="form-control" data-plugin="select2" name="ppPartName[]" id="ppPartName_'+id.toString()+'" ></select>'
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppMaturityDay[]', 'id':'ppDay_'+id.toString()}).val(dd)
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppMaturityTerm[]', 'id':'ppMaturityTerm_'+id.toString()}).append(
                    $('<option>').attr('value','').html('')
                ).append(
                    $('<option>').attr('value','9').html('Air Way Bill Date')
                ).append(
                    $('<option>').attr('value','10').html('Bill of Lading')
                ).append(
                    $('<option>').attr('value','11').html('LC Issuance')
                ).append(
                    $('<option>').attr('value','12').html('Shipment Date')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-1').append(
                $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'cacFacDay[]', 'id':'cacFacDay_'+id.toString()}).val(cfd)
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<input>').attr({'type':'text', 'class':'form-control required-entry', 'name':'cacFacText[]', 'id':'cacFacText_'+id.toString()}).val(cft)
            )
        )/*.append(
            $('<div>').attr('class','col-sm-2').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'cacFacText[]', 'id':'cacFacText_'+id.toString()}).append(
                    $('<option>').attr('value','').html('')
                ).append(
                    $('<option>').attr('value','76').html('CAC FAC Text One')
                ).append(
                    $('<option>').attr('value','77').html('CAC FAC Text Two')
                ).append(
                    $('<option>').attr('value','78').html('CAC FAC Text Three')
                ).append(
                    $('<option>').attr('value','79').html('CAC FAC Text Four')
                )
            )
        )*/.append(
            $('<div>').attr('class','col-sm-1').append(
                $('<button>').attr({'type':'button','data-toggle':'tooltip','title':'Delete this row','class':'btn btn-danger',onclick:"delTermsRow(this);"}).append(
                    $('<i>').attr('class','fa fa-close')
                )
            )
        )
    );

    /*$("#ppPartName_"+id.toString()).change(function () {
        //alert("api/buyers-lc-request?action=4&name=" + $("#ppPartName_"+id.toString()).val());
        $.getJSON("api/buyers-lc-request?action=4&name=" + $("#ppPartName_"+id.toString()).val(), function (data) {
            $("#cacFacText_"+id.toString()).val(data.tag);
        });
    });*/

    $.getJSON("api/buyers-lc-request?action=3", function (list) {
        $("#ppPartName_"+id.toString()).select2({
            data: list,
            placeholder: "select a certificate",
            allowClear: false,
            width: "100%"
        });
        $('#ppPartName_'+id.toString()).val(cc).change();
    });

    //$('#ppPartName_'+id.toString()).selectpicker('refresh');

    // Dynamic selection : Air Way Bill Date/Bill of Lading according to shipment mode Sea/Air
    if(tt == 9 || tt == 10){
        if(podata['shipmode']=='sea'){
            tt = 10;    // Airway Bill Date (lookup ID)
        } else if(podata['shipmode']=='air'){
            tt = 9;     // Bill of Lading (lookup ID)
        }
    }

    $('#ppMaturityTerm_'+id.toString()).selectpicker('refresh');
    $('#ppMaturityTerm_'+id.toString()).val(tt).change();

    $('#cacFacText_'+id.toString()).selectpicker('refresh');
    $('#cacFacText_'+id.toString()).val(cft).change();

}
function delTermsRow(e) {
    $(e).parent().parent().remove();
}