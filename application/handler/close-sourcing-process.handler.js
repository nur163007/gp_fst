/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val();

var podata;
var comments;
var attach;

// Loading pre data from PO
$.get('api/purchaseorder?action=2&id='+poid, function (data) {
    if(!$.trim(data)){
        $(".panel-body").empty();
        $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
    } else {
        var row = JSON.parse(data);
        var podata = row[0][0];
        var comments = row[1];
        var attach = row[2];
        
        // PO Information
        $('#ponum').html(podata['poid']);
        $('#povalue').html(podata['povalue']);
        $('#currency').html(podata['curname']);
        $('#podesc').html(podata['podesc']);
        $('#lcdesc').html('<b>'+podata['lcdesc']+'</b>');
        
        $('#supplier').html(podata['supname']).attr('data-value',podata['supname']);
        
        $('#contractref').html(podata['contractref']);
        $('#deliverydate').html(podata['deliverydate']);
        if(podata["installbysupplier"]==0){
			$('#installbysupplier').html('No');
		} else {
			$('#installbysupplier').html('Yes');
		}
        $('#noflcissue').html(podata['noflcissue']).attr('data-value',podata['noflcissue']);
        $('#nofshipallow').html(podata['nofshipallow']).attr('data-value',podata['nofshipallow']);
        
        // Buyer's comments log
        $('#buyersmsgtitle').hide();
        $('#buyersmsg').hide();
        var commentsLog = '';
        for(var i=0; i<comments.length; i++){
            if(comments[i]['msg']!=null && comments[i]['fromgroup']!=_supplier){
                if(commentsLog.length>0){commentsLog += '<hr />';}
                commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span> <i class="icon wb-arrow-right"></i> '+ comments[i]['torole'] +'</span><div class="comment-meta"> on '+comments[i]['msgon'] + '</div>';
                commentsLog += '<div class="comment-content">' + comments[i]['msg'] + '</div>';
            }
        }
        if(commentsLog!=''){
            $('#buyersmsg').html(commentsLog);
            $('#buyersmsgtitle').show();
            $('#buyersmsg').show();
        }
        
        var attachmentHtml = '';
        var attachedBy = '';
        for(var i=0; i<attach.length; i++){
            if(attach[i]['rolename']!=attachedBy){
                if(attachedBy!=''){
                    attachmentHtml += '</div>';
                }
                attachedBy = attach[i]['rolename'];
                attachmentHtml += '<h4 class="well well-sm example-title">'+attachedBy+'\'s Attachments</h4>'+
                    '<div class="form-group">';
            }
            attachmentHtml += '<label class="col-sm-3 control-label">'+attach[i]['title']+'</label>'+
                '<div class="col-sm-9">'+
                    '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="download-attachment/'+attach[i][0]+'" target="_blank">'+attach[i]['title']+'</a></label>'+
                '</div>';
            //}
        }
        //alert(attachmentHtml);
        $('#usersAttachments').html(attachmentHtml);
        
        // PI info
        $('#pinum').html(podata['pinum']);
        
        $('#pivalue').html(podata['pivalue']);
        $('#picurrency').html(podata['curname']);
        $('#shipmode').html(podata['shipmode'].toUpperCase());
        
        $('#shipHSCsea').hide();
        $('#shipHSCair').hide();
        
        if(podata['shipmode']=='sea'){$('#shipHSCsea').show();}
        if(podata['shipmode']=='air'){$('#shipHSCair').show();}
        if(podata['shipmode']=='sea+air'){$('#shipHSCsea').show(); $('#shipHSCair').show();}
        
        $('#hscsea').html(podata['hscsea']);
        $('#hscair').html(podata['hscair']);
        
        $('#pidate').html(podata['pidate']);
        $('#basevalue').html(podata['basevalue']);
        $('#basecurrency').html(podata['curname']);
        
        $('#origin').html(podata['origin']).attr('data-value',podata['origin']);
        $('#negobank').html(podata['negobank']);
        $('#shipport').html(podata['shipport']);
        $('#lcbankaddress').html(podata['lcbankaddress']);
        $('#productiondays').html(podata['productiondays']);
        $('#buyercontact').html(podata['buyercontact']);
        $('#techcontact').html(podata['techcontact']);
        
        
        // EDITABLE --
        var init_x_editable = function() {
        
            $.fn.editableform.buttons =
                '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
                '<i class="icon wb-check" aria-hidden="true"></i>' +
                '</button>' +
                '<button type="button" class="btn btn-default btn-sm editable-cancel">' +
                '<i class="icon wb-close" aria-hidden="true"></i>' +
                '</button>';
    
            $.fn.editabletypes.datefield.defaults.inputclass =
                "form-control input-sm";
            //defaults
            $.fn.editable.defaults.url = _adminURL + 'api/xeditpo';
    
            //editables
            $('#povalue').editable({
                pk: poid,
                type: 'text',
                name: 'povalue'
            });
            
            $('#podesc').editable({
                pk: poid,
                type: 'textarea',
                name: 'podesc'
            });
            
            $('#lcdesc').editable({
                pk: poid,
                type: 'textarea',
                name: 'lcdesc'
            });
            
            $.getJSON("api/company?action=4", function (list) {
                $('#supplier').editable({
                    type: 'select',
                    pk: poid,
    				source: list,
                    name: 'supplier',
                    showbuttons: false,
                    display: function (value, response) {
                       $(this).html(value);
                    }
    			});
            });
            
            $('#contractref').editable({
                pk: poid,
                type: 'text',
                name: 'contractref'
            });

			$('#deliverydate').editable({
                pk: poid,
                type: 'text',
                name: 'deliverydate'
			});
            
			$('#installbysupplier').editable({
			    prepend: $('#installbysupplier').html(),
                pk: poid,
                type: 'select',
                name: 'installbysupplier',
                showbuttons: false,
                source: [
                    {value: 0, text: "No"},
                    {value: 1, text: "Yes"}
                ]
			});
            
            $('#noflcissue').editable({
                pk: poid,
                type: 'number',
                name: 'noflcissue'
            });
            
            $('#nofshipallow').editable({
                pk: poid,
                type: 'number',
                name: 'nofshipallow'
            });            
            					            
            $('#pinum').editable({
                pk: poid,
                type: 'text',
                name: 'pinum'
            });
            					            
            $('#pivalue').editable({
                pk: poid,
                type: 'text',
                name: 'pivalue'
            });
            
			$('#shipmode').editable({
			    prepend: $('#shipmode').html(),
                pk: poid,
                type: 'select',
                showbuttons: false,
                name: 'shipmode',
                source: [
                    {value: "sea", text: "Sea"},
                    {value: "air", text: "Air"},
                    {value: "sea+air", text: "Sea + Air"}
                ]
			});
            
			$('#hscsea').editable({
                pk: poid,
                type: 'text',
                name: 'hscsea'
            });
            
			$('#hscair').editable({
                pk: poid,
                type: 'text',
                name: 'hscair'
            });
            
			$('#pidate').editable({
                pk: poid,
                type: 'text',
                name: 'pidate'
			});
            
			$('#basevalue').editable({
                pk: poid,
                type: 'text',
                name: 'basevalue'
            });
            
            $.getJSON("application/library/country.txt", function (data) {
                $('#origin').editable({
                    pk: poid,
    				source: data,
                    name: 'origin',
                    showbuttons: false,
                    display: function (value, response) {
                       $(this).html(value);
                    }
    			});
            });
            
			$('#negobank').editable({
                pk: poid,
                type: 'text',
                name: 'negobank'
            });
            
			$('#shipport').editable({
                pk: poid,
                type: 'text',
                name: 'shipport'
            });
            
			$('#lcbankaddress').editable({
                pk: poid,
                type: 'text',
                name: 'lcbankaddress'
            });
            
			$('#productiondays').editable({
                pk: poid,
                type: 'text',
                name: 'productiondays'
            });
            
			$('#buyercontact').editable({
                pk: poid,
                type: 'text',
                name: 'buyercontact'
            });
            
			$('#techcontact').editable({
                pk: poid,
                type: 'text',
                name: 'techcontact'
            });

        };

        var destory_x_editable = function() {
            $('#povalue').editable('destroy');
            $('#podesc').editable('destroy');
            $('#lcdesc').editable('destroy');
            $('#supplier').editable('destroy');
            $('#contractref').editable('destroy');
            $('#deliverydate').editable('destroy');
            $('#installbysupplier').editable('destroy');
            $('#noflcissue').editable('destroy');
            $('#nofshipallow').editable('destroy');
			
			$('#pivalue').editable('destroy');
			$('#shipmode').editable('destroy');
			$('#hscsea').editable('destroy');
			$('#hscair').editable('destroy');
			$('#pidate').editable('destroy');
			$('#basevalue').editable('destroy');
			$('#origin').editable('destroy');
			$('#negobank').editable('destroy');
			$('#shipport').editable('destroy');
			$('#lcbankaddress').editable('destroy');
			$('#productiondays').editable('destroy');
			$('#buyercontact').editable('destroy');
			$('#techcontact').editable('destroy');
			
        };

        $.fn.editable.defaults.mode = 'popup';
        init_x_editable();
        
        // EDITABLE -- END
        
        
        // LC Request Form
        
        $('#pono1').val(podata['poid']);
        $('#pono2').val(podata['poid']);
        $('#lcdesc1').val(podata['lcdesc']);
        $('#supplier1').val(podata['supname']);
        $('#shipmode1').html('Shipment Mode: '+podata['shipmode']);
        $('#shipmode2').val(podata['shipmode'].toUpperCase());
        $('#hscsea1').val(podata['hscsea']);
        $('#hscair1').val(podata['hscair']);
        $('#origin1').val(podata['origin']);
        $('#origin2').val(podata['origin']);
        $('#negobank1').val(podata['negobank']);
        $('#shipport1').val(podata['shipport']);
        $('#destination').val(podata['shipport']);
        $('#lcbankaddress1').val(podata['lcbankaddress']);
        
        $('#lcvalue').val(commaSeperatedFormat(podata['pivalue']));
        $('#lccurrency').html(podata['curname']);
    }
});



