/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on:
*/

var poid = $('#pono').val();
var u = $('#usertype').val();

var podata,
    comments,
    attach,
    lcinfo,
    pterms;

$(document).ready(function(){
    
    // Loading pre data from PO
    // alert('api/purchaseorder?action=2&id='+poid);
    $.get('api/purchaseorder?action=2&id='+poid, function (data) {

        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            podata = row[0][0];
            comments = row[1];
            attach = row[2];
            lcinfo = row[3][0];
            pterms = row[4];
            // PO Information


            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>'+commaSeperatedFormat(podata['povalue'])+'</b> '+podata['curname']);
            $('#podesc').html(podata['podesc']);
            if(lcinfo['lcdesc']==""){
                $('#lcdesc1').html(podata['lcdesc']);
            }else{
                $('#lcdesc1').html(lcinfo['lcdesc']);
            }
            $('#supplier').html(podata['supname']);
            $('#contractref').html(podata['contractrefName']);
            $('#deliverydate').html(podata['deliverydate']);
            if(podata["installbysupplier"]==0){
                $('#installbysupplier').html('No');
            } else {
                $('#installbysupplier').html('Yes');
            }
            $('#noflcissue').html(podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']);

            // PI info
            $('#pinum').html(podata['pinum']);

            $('#pivalue').html(commaSeperatedFormat(podata['pivalue'])+' '+podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#hscode1').html(podata['hscode']);
            $('#pidate').html(Date_toDetailFormat( new Date( podata['pidate'] ) ));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue'])+' '+podata['curname']);
            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            // $('#buyercontact').html(podata['buyercontact']);
            // $('#techcontact').html(podata['techcontact']);
            
            // From 3th level don't need supplier's attachment

            var roleList = [const_role_LC_Approvar_4,const_role_LC_Approvar_5];
            
            //alert($("#usertype").val());
            //alert(roleList.indexOf(parseFloat($("#usertype").val())));
            
            if(roleList.indexOf(parseFloat($("#usertype").val()))>=0){
                var roleFilter = ["PO","BOQ","BTRC NOC"];
                attachmentLogScript(attach, '#usersAttachments', 1);
            } else{
                attachmentLogScript(attach, '#usersAttachments', 1);
            }
            commentsLogScript(comments, '#buyersmsg', '');

            // LC Request Form
            
            $('#pono1').html(podata['poid']);
            $('#pono2').html(podata['poid']);
            $('#lcdesc').html(lcinfo['lcdesc']);
            $('#supplier1').html(podata['supname']);
            $('#shipmode1').html('Shipment Mode: '+podata['shipmode']);
            $('#shipmode2').html(podata['shipmode'].toUpperCase());
            $('#hiddenShipMode').val(podata['shipmode'].toUpperCase());
            $('#hscsea').html(podata['hscsea']);
            $('#hscode').html(podata['hscode']);
            if(podata['shipmode']=='sea'){
                $("#shipModeAir_container").hide();
            }else if(podata['shipmode']=='air'){
                $("#shipModeSea_container").hide();
            }
            $('#origin1').html(podata['origin'].replace(',',', '));
            $('#origin2').html(podata['origin'].replace(',',', '));
            $('#negobank1').html(HTMLDecode(podata['negobank']));
            $('#shipport1').html(podata['shipport']);
            if(podata['shipmode']=='sea'){
                $('#destination').html('Chittagong Sea Port');
            }else if(podata['shipmode']=='air'){
                $('#destination').html('Dhaka Air Port');
            }
            $('#lcbankaddress1').html(HTMLDecode(podata['lcbankaddress']));

            if(lcinfo['lca']==0) {
                $('#lcRequestType').html("LC");
            }else{
                $('#lcRequestType').html("LCA");
            }
            $('#lcno').html(lcinfo['lcno']);
            $('#lcno1').html(lcinfo['lcno']);
            $('#ircno').html(lcinfo['ircno']);
            $('#tinno').html(lcinfo['tinno']);
            $('#tinno1').html(lcinfo['tinno']);
            $('#lcvalue').html(commaSeperatedFormat(lcinfo['lcvalue'])+" "+podata['curname']);
            $('#lcAmount').val(lcinfo['lcvalue']);
            $('#lcCur').val(podata['curname']);
            $('#vatregno').html(lcinfo['vatregno']);
            $('#imppermitno').html(lcinfo['imppermitno']);
            $('#customername').html(lcinfo['customername']);
            $('#customeraddress').html(lcinfo['customeraddress']);
            $('#lcexpirydate').html(Date_toDetailFormat(new Date(lcinfo['lcexpirydate'])));
            $('#lastdateofship').html(Date_toDetailFormat(new Date(lcinfo['lastdateofship'])));
            $('#paymentTermsText').html(lcinfo['paymentterms']);
            
            $('#advBank').html(lcinfo['advBank']);
            $('#contactPSI').html(lcinfo['contactPSI']);
            $('#psiClauseA').html(lcinfo['psiClauseA']);
            $('#psiClauseB').html(lcinfo['psiClauseB']);
            $('#insNotification1').html(lcinfo['insNotification1']);
            $('#insNotification2').html(lcinfo['insNotification2']);
            $('#insNotification3').html(lcinfo['insNotification3']);
            $('#forAirShipment1').html(lcinfo['forAirShipment1']);
            $('#forSeaShipment1').html(lcinfo['forSeaShipment1']);
            $('#forAirShipment2').html(lcinfo['forAirShipment2']);
            $('#forSeaShipment2').html(lcinfo['forSeaShipment2']);
            $('#shippingRemarks').html(lcinfo['shippingRemarks']);
            
            //alert(lcinfo['cocorigin']);
            if(lcinfo['cocorigin']==1){ $('#cocorigin').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['iplbltolcbank']==1){ $('#iplbltolcbank').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['delivcertify']==1){ $('#delivcertify').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['qualitycertify']==1){ $('#qualitycertify').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['qualitycertify1']==1){ $('#qualitycertify1').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['advshipdoc']==1){ $('#advshipdoc').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['advshipdocwithbl']==1){ $('#advshipdocwithbl').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['addconfirmation']==1){ $('#addconfirmation').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['preshipinspection']==1){ $('#preshipinspection').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['transshipment']==1){ $('#transshipment').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['partship']==1){ $('#partship').removeClass('fa-square-o').addClass('fa-check-square-o'); }
            if(lcinfo['confchargeatapp']==1){ $('#confchargeatapp').removeClass('fa-square-o').addClass('fa-check-square-o'); }

            if(lcinfo['otherTerms']==""){
                $("#otherTermsContent").addClass('hidden');
            } else{
                $("#otherTerms").html(lcinfo['otherTerms']);
            }

            var ptrow = "";
            for(var i=0; i<pterms.length; i++){
                ptrow = "<tr><td class=\"text-center\">"+pterms[i]['percentage']+"%</td>"+
                    "<td class=\"text-center\">"+pterms[i]['partname']+"</td>"+
                    "<td class=\"text-center\">"+pterms[i]['dayofmaturity']+" Days Maturity</td>"+
                    "<td class=\"text-left\">"+pterms[i]['maturityterms']+"</td></tr>";
                //ptrow = "<tr><td>""</td></tr>"
                $("#lcPaymentTermsTable").append(ptrow);
            }
            
            if([const_role_LC_Approvar_4,const_role_LC_Approvar_5].indexOf(u)){
                
                if(u == const_role_LC_Approvar_5.toString()){
                    
                    $("#bank").change(function(e){
                        $('#lcissuerbankNew').val($('#bank :selected').text());
                    });
                    
                    $("#insurance").change(function(e){
                        $('#insuranceNew').val($('#insurance :selected').text());
                    });
                }

                $.getJSON("api/company?action=4&type=118", function (list) {
                    $("#bank").select2({
                        data: list,
                        placeholder: "Select a Bank",
                        allowClear: false,
                        width: "100%"
                    });
                    $('#bank').val(lcinfo['lcissuerbank']).change();
                    
                    if(u == const_role_LC_Approvar_5.toString()){
                        $('#lcissuerbankOld').val($('#bank :selected').text());
                    }
                });
                
                $.getJSON("api/company?action=4&type=119", function (list) {
                    if(podata['shipmode']=='E-Delivery'){
                        $('#insurance').html('').select2({data: [{id: '0', text: 'NA'}]});
                    }
                    $("#insurance").select2({
                        data: list,
                        placeholder: "Select a Insurance",
                        allowClear: false,
                        width: "100%"
                    });
                    if(podata['shipmode']=='E-Delivery'){
                        $('#insurance').val(0).change();
                        $('#hiddenShipType').show();
                    } else {
                        $('#insurance').val(lcinfo['insurance']).change();
                        $('#hiddenShipType').hide();
                    }
                    if(u == const_role_LC_Approvar_5.toString()){
                        $('#insuranceOld').val($('#insurance :selected').text());
                    }
                });
                
                
            }
        }
    });

    // Submit
    $("#Accept_btn").click(function(e){
        $('#userAction').val('1');
        e.preventDefault();
        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/lc-request",
                        data: $('#lcrequest-form').serialize(),
                        cache: false,
                        success: function (result) {
                            //alert(result);
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
    
    $("#Reject_btn").click(function(e) {
        $('#userAction').val('2');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: "api/lc-request",
                        data: $('#lcrequest-form').serialize(),
                        cache: false,
                        success: function (result) {
                            //alert(result);
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
                }
            });
        } else {
            return false;
        }
    });

    $("#btnGenerateLCALetter").click(function(e) {
        e.preventDefault();
        if (validateForLetter()) {
            //console.log("api/lc-opening?action=4&bank=8&po=" + $("#pono").val());
            $.get("api/lc-opening?action=4&bank=8&po=" + $("#pono").val(), function (data) {
                bankData = JSON.parse(data);
                $.ajax({
                    url: "application/templates/letter_template/temp_lca_terms_condition_letter.html",
                    cache: false,
                    global: false,
                    success: function (result) {
                        // alert(result);
                        var temp = result;

                        //---------------replace data-----------------
                        temp = temp.replace(/##LCVALUE##/g, $("#lcAmount").val());
                        temp = temp.replace('##HSCODE##', $("#hscode").html());
                        temp = temp.replace(/##CUR##/g, $("#lcCur").val());
                        temp = temp.replace('##GOODSDESC##', $("#lcdesc").html());
                        temp = temp.replace('##PO##', $("#pono").val());
                        temp = temp.replace('##SHIPPORT##', $("#shipport1").html());
                        temp = temp.replace('##ESTSHIPDATE##', $("#lastdateofship").html());
                        temp = temp.replace('##LCEXPIRYDATE##', $("#lcexpirydate").html());
                        temp = temp.replace('##LCTERMS##', $("#paymentTermsText").html());
                        temp = temp.replace('##LCANUM##', $("#lcno1").html());
                        //temp = temp.replace('##LCABANK##', $("#bank").val());
                        temp = temp.replace('##LCABANK##', bankData["name"]);
                        temp = temp.replace('##BANKADDRESS##', bankData["address"].replace(/\n/g, "<br />"));
                        temp = temp.replace('##LETTERDATE##', Date_toDetailFormat(new Date()));
                        //---------------end replace data-------------

                        $("#fileName").val('LCA_terms_conditiion_' + poid + '.doc');
                        $("#letterContent").val(temp);

                        document.getElementById("formLetterContent").submit();
                    }
                });

            });
        }

    });

});

function validateForLetter(){
    return true;
}

function validate(){
    if($("#approversComment").val()==""){
        $("#approversComment").focus();
        alertify.error("Please mention your valued comment!");
		return false;
    }
    if($('#userAction').val()==2){
        return true;
    }
    //var a = '9,10'.split(',');
    //alert(a.indexOf(u));
    //if(a.indexOf(u)>=0){
    if(u==const_role_LC_Approvar_4){
        if($("#bank").val()==""){
            alertify.error("Please select a Bank!");
            $("#bank").select2('open');
    		return false;
        }
        if($("#insurance").val()==""){
            alertify.error("Please select an Insurance!");
            $("#insurance").select2('open');
            return false;
        }
        if ($('#hiddenShipMode').val() == 'E-DELIVERY'){
            var hideShip = $('input:radio[name=withLC]:checked').val();

            if (hideShip == undefined) {
                alertify.error("Please select shipment type option!");
                return false;
            }
        }
    }
    return true;
}

// Generate new payment terms
function NewPaymentTermsRow(pp, cc, dd, tt){

    var id = $('div#lcPaymentTermsTable').children().length+1;

    $('div#lcPaymentTermsTable').append(
        $('<div>').attr({'class':'form-group', 'id':'lcPaymentTermsRow'+id}).append(
            $('<label>').attr('class','col-sm-1 control-label text-left').html(id.toString()+'.')
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppPercentage[]', 'id':'ppPercentage_'+id.toString()}).val(pp)
                ).append(
                    $('<span>').attr('class','input-group-addon').html('%')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-2').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppPartName[]', 'id':'ppPartName_'+id.toString()}).append(
                    $('<option>').attr('value','6').html('Sight')
                ).append(
                    $('<option>').attr('value','7').html('CAC')
                ).append(
                    $('<option>').attr('value','8').html('FAC')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-3').append(
                $('<div>').attr('class','input-group').append(
                    $('<input>').attr({'type':'text', 'class':'form-control text-right', 'name':'ppMaturityDay[]', 'id':'ppDay_'+id.toString()}).val(dd)
                ).append(
                    $('<span>').attr('class','input-group-addon').html('Days Maturity')
                )
            )
        ).append(
            $('<div>').attr('class','col-sm-4').append(
                $('<select>').attr({'class':'form-control', 'data-plugin':'selectpicker', 'data-style':'btn-select', 'name':'ppMaturityTerm[]', 'id':'ppMaturityTerm_'+id.toString()}).append(
                    $('<option>').attr('value','').html('')
                ).append(
                    $('<option>').attr('value','9').html('Air Way Bill Date')
                ).append(
                    $('<option>').attr('value','10').html('Bill of Lading')
                ).append(
                    $('<option>').attr('value','11').html('LC Issuence')
                ).append(
                    $('<option>').attr('value','12').html('Shipment Date')
                )
            )
        )
    );
    
    $('#ppPartName_'+id.toString()).selectpicker('refresh');
    $('#ppPartName_'+id.toString()).val(cc).change();
    
    $('#ppMaturityTerm_'+id.toString()).selectpicker('refresh');
    $('#ppMaturityTerm_'+id.toString()).val(tt).change();
    
}

$(function() {
    $('#printTerms, #printTerms1').click(function(e) {
        e.preventDefault();
        
        // preparing format
        
        var newstr = $("#printFormat").html();
        
        newstr = newstr.replace('##lcdesc1##',(lcinfo['lcdesc']));
        newstr = newstr.replace('##lcvalue##',(commaSeperatedFormat(lcinfo['lcvalue'])+" "+podata['curname']));
        
        if(podata['shipmode']=='sea'){
            newstr = newstr.replace('##hscsea1##',(podata['hscode']));
            newstr = newstr.replace('##hscair1##','');
        } else {
            newstr = newstr.replace('##hscsea1##','');
            newstr = newstr.replace('##hscair1##',(podata['hscode']));
        }
        
        var defaultCheckIcon = 'fa-square-o';
        
        if(lcinfo['cocorigin']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##cocorigin##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['iplbltolcbank']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##iplbltolcbank##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['delivcertify']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##delivcertify##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['qualitycertify']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##qualitycertify##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['qualitycertify1']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##qualitycertify1##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['advshipdoc']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##advshipdoc##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['advshipdocwithbl']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##advshipdocwithbl##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['addconfirmation']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##addconfirmation##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['preshipinspection']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##preshipinspection##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['transshipment']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##transshipment##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['partship']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
        newstr = newstr.replace('##partship##','<h3><i class="icon '+defaultCheckIcon+' text-primary" aria-hidden="true"></i></h3>');
        if(lcinfo['confchargeatapp']==1){ defaultCheckIcon = 'fa-check-square-o'; } else { defaultCheckIcon = 'fa-square-o'; }
                
        newstr = newstr.replace('##ircno##',(lcinfo['ircno']));
        newstr = newstr.replace('##imppermitno##',(lcinfo['imppermitno']));
        newstr = newstr.replace('##tinno##',(lcinfo['tinno']));
        newstr = newstr.replace('##lcno1##',(lcinfo['lcno']));
        newstr = newstr.replace('##vatregno##',(lcinfo['vatregno']));
        newstr = newstr.replace('##pono1##',originalPO(podata['poid']));
        newstr = newstr.replace('##shipmode2##',(podata['shipmode'].toUpperCase()));
        newstr = newstr.replace('##shipport1##',(podata['shipport']));
        newstr = newstr.replace('##origin1##',(podata['origin'].replace(',',', ')));
        newstr = newstr.replace('##pono2##',originalPO(podata['poid']));
        newstr = newstr.replace('##customername##',(lcinfo['customername']));
        newstr = newstr.replace('##customeraddress##',(lcinfo['customeraddress']));
        newstr = newstr.replace('##tinno1##',(lcinfo['tinno']));
        newstr = newstr.replace('##lcno##',(lcinfo['lcno']));
        if(podata['shipmode']=='sea'){
            newstr = newstr.replace('##destination##',('Chittagong Sea Port'));
        }else if(podata['shipmode']=='air'){
            newstr = newstr.replace('##destination##',('Dhaka Air Port'));
        }
        newstr = newstr.replace('##supplier1##',(podata['supname']));
        newstr = newstr.replace('##origin2##',(podata['origin'].replace(',',', ')));
        newstr = newstr.replace('##lastdateofship##',(Date_toMDY(new Date(lcinfo['lastdateofship']))));
        newstr = newstr.replace('##lcexpirydate##',(Date_toMDY(new Date(lcinfo['lcexpirydate']))));
        newstr = newstr.replace('##lcbankaddress1##',(HTMLDecode(podata['lcbankaddress'])));
        newstr = newstr.replace('##negobank1##',(HTMLDecode(podata['negobank'])));
        newstr = newstr.replace('##selectPaymentTerm##',(lcinfo['paymentterms']));
        //newstr = newstr.replace('##selectPaymentTerm##','<table width="100%" border="1">'+$("#lcPaymentTermsTable").html()+'</table>');
        
        newstr = newstr.replace('##advBank##',(lcinfo['advBank']));
        newstr = newstr.replace('##contactPSI##',(lcinfo['contactPSI']));
        newstr = newstr.replace('##psiClauseA##',(lcinfo['psiClauseA']));
        newstr = newstr.replace('##psiClauseB##',(lcinfo['psiClauseB']));
        newstr = newstr.replace('##insNotification1##',(lcinfo['insNotification1']));
        newstr = newstr.replace('##insNotification2##',(lcinfo['insNotification2']));
        newstr = newstr.replace('##insNotification3##',(lcinfo['insNotification3']));
        newstr = newstr.replace('##forAirShipment1##',(lcinfo['forAirShipment1']));
        newstr = newstr.replace('##forSeaShipment1##',(lcinfo['forSeaShipment1']));
        newstr = newstr.replace('##forAirShipment2##',(lcinfo['forAirShipment2']));
        newstr = newstr.replace('##forSeaShipment2##',(lcinfo['forSeaShipment2']));
        newstr = newstr.replace('##shippingRemarks##',(lcinfo['shippingRemarks']));

        $("#printFormat").html(newstr);
        
        //alert('sfsfd');
        //Print ele4 with custom options
        $("#printFormat").removeClass("hidden");
        $("#printFormat").print({
            //Use Global styles
            globalStyles : true,
            //Add link with attrbute media=print
            mediaPrint : false,
            //Custom stylesheet
            stylesheet : "assets/css/print-form.css",
            //Print in a hidden iframe
            iframe : false,
            //Don't print this
            noPrintSelector : ".avoid-this",
            //Add this at top
            //prepend : "Hello World!!!<br/>",
            //Add this on bottom
            //append : "<br/>Buh Bye!"
        });
        $("#printFormat").addClass("hidden");
    });
    // Fork https://github.com/sathvikp/ for the full list of options
});