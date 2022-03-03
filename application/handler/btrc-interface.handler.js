/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid, podata, comments, attach;

$(document).ready(function() {
    
    if($('#poid').val()!=""){

        var poid = $('#poid').val();
        $.get('api/purchaseorder?action=2&id='+poid, function (data) {
            
            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            }else {
                
                var row = JSON.parse(data);
                podata = row[0][0];
                comments = row[1];
                attach = row[2];
                
                // PO info
                $('#ponum').html(poid);
                $('#povalue').html(commaSeperatedFormat(podata['povalue'])+' '+podata['curname']);
                $('#podesc').html(podata['podesc']);
                //alert(podata['lcdesc']);
                $('#lcdesc').html(podata['lcdesc']);
                
                $('#supplier').html(podata['supname']);
                $('#sup_address').html(podata['supadd']);
                $('#contractref').html(podata['contractrefName']);
                $('#pr_no').html(podata['pr_no']);
                $('#department').html(podata['department']);
                $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
                $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
                $('#buyercontact').html(podata['buyersName']);
                $('#techcontact').html(podata['prName']);
                $('#installbysupplier').html(getImplementedBy(podata["installbysupplier"]));
                $('#noflcissue').html(podata['noflcissue']);
                $('#nofshipallow').html(podata['nofshipallow']);
                
                // PI info
                $('#pinum').html(podata['pinum']);
                $('#pivalue').html('<b>'+commaSeperatedFormat(podata['pivalue'])+'</b> '+podata['curname']);
                $('#pi_description').html((podata['pidesc']));
                $('#shipmode').html(podata['shipmode'].toUpperCase());
                
                if(podata['shipmode']=='sea'){$('#shipHSCsea').show();}
                if(podata['shipmode']=='air'){$('#shiphscode').show();}
                if(podata['shipmode']=='sea+air'){$('#shipHSCsea').show(); $('#shiphscode').show();}
                
                $('#hscode').html(podata['hscode']);
                $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
                $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' +podata['curname']);
                
                
                $('#origin').html(podata['origin']);
                $('#negobank').html(podata['negobank']);
                $('#shipport').html(podata['shipport']);
                $('#lcbankaddress').html(podata['lcbankaddress']);
                $('#productiondays').html(podata['productiondays']);
                
                commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');
                //alert(attach.length);
                attachmentLogScript(attach, '#usersAttachments');
                
            }
        });
    }

    if($("#usertype").val()==const_role_LC_Approvar_3){
        $("#BTRCRejected_btn").html('Reject &amp; Send to Buyer');
    } else if($("#usertype").val()==const_role_Corporate_Affairs){
        $("#BTRCRejected_btn").html('Rejected by BTRC &amp; Send to Sourcing for Rectification');
    }

    /*$("#messageUserYes").click(function (event) {
        if ($(this).is(":checked"))
            $(".isMessageUser").show();
        else
            $(".isMessageUser").hide();
    });*/
    
    $("#SendForBTRCNOC_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('1');
        e.preventDefault();
        
        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {
                            var res = JSON.parse(result);
                            
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error(res['message']);
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
    
    $("#SendBTRCNOCToSourcing_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('2');
        e.preventDefault();
        
        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {
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

    $("#BTRCProcessApproved_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('3');
        e.preventDefault();

        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {
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
    
    $("#BTRCRejected_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('4');
        e.preventDefault();
        
        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {
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

    $("#btnSendForPREAFeedback").click(function (e) {
        //alert('test');
        $('#userAction').val('5');
        e.preventDefault();

        if(validate() === true){
            alertify.confirm( 'Are you sure about this action?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {

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

    $("#btnRejectToSupplier").click(function (e) {
        //alert('test');
        $('#userAction').val('6');
        e.preventDefault();

        if(validate() === true){
            alertify.confirm( 'Are you sure about this action?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
                        cache: false,
                        success: function (result) {
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
                };
            });
        } else {
            return false;
        }
    });

    $("#SendToBTRCForNOC_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('7');
        e.preventDefault();

        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
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
                };
            });
        } else {
            return false;
        }
    });

    $("#ReadyForSubmission_btn").click(function (e) {
        //alert('test');
        $('#userAction').val('8');
        e.preventDefault();

        if(validate() === true){
            alertify.confirm( 'Are you sure you want submit?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/btrc-interface",
                        data: $('#btrc-form').serialize(),
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
                };
            });
        } else {
            return false;
        }
    });

    //PO INFO LOAD
    var id = $('#poid').val();

    //PO LINE LOAD
    $.get("api/view-po?action=2&id="+id, function (data) {
        var res = JSON.parse(data);
        var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
            delivTotal = 0;
        //alert(rejectedLines);

        /**
         * Delivered po lines
         */
        var d1 = res[0];


        $("#delivCount1").html('(' + d1.length + ')');

        if (d1.length > 0) {
            $("#dtPOLinesDelivered tbody").empty();
            // loading already delivered po lines
            for (var i = 0; i < d1.length; i++) {
                strRow = '<tr>' +
                    '<td class="text-center">' + d1[i]['lineNo'] + '</td>' +
                    '<td class="text-center">' + d1[i]['itemCode'] + '</td>' +
                    '<td class="text-left">' + d1[i]['itemDesc'] + '</td>' +
                    '<td class="text-center">' + d1[i]['deliveryDate'] + '</td>' +
                    '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                    '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                    '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                    '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                    '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                    '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                    /*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*/
                    '</tr>';
                $("#dtPOLinesDelivered tbody:last").append(strRow);

                // alert(qty)
            }
        } else {
            $("#dtPOLinesDelivered tbody").empty();
            $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }

    });

    $.get('api/view-po?action=3&id='+id, function (data) {
        // console.log(data)
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            // console.log(row)
            sumdata = row[0][0];
            // console.log(commaSeperatedFormat(row['grandpoQty']))

            // PO info
            // $('#podesc').val(podata['podesc']);
            $('#poQtyTotal').html(commaSeperatedFormat(sumdata['grandpoQty']));
            $('#grandTotal').html(commaSeperatedFormat(sumdata['grandPoTotal']));
            $('#dlvQtyTotal').html(commaSeperatedFormat(sumdata['grandDelivQty']));
            $('#dlvGrandTotal').html(commaSeperatedFormat(sumdata['grandDelivTotal']));
        }
    });
});


function validate(){
    
    if($('#userAction').val()==2 && $('#userAction').val()==2){
        if($('#attachBTRCNOC').val()==""){
            $('#attachBTRCNOC').focus();
            alertify.error("Please attach the BTRC NOC!");
            return false;
        } else {
            if(!validAttachment($("#attachBTRCNOC").val())){
                alertify.error('Invalid File Format.');
                return false;
            }
    	}
    }
    /*if($('#messageUserYes').is(":checked") && $("#messageUser").val()==""){
        $("#messageUser").focus();
        alertify.error("You should write a comment!");
		return false;
    }*/
    if($('#userAction').val()==5 || $('#userAction').val()==6){
        if($('#messageUser').val()==""){
            $('#messageUser').focus();
            alertify.error("Please mention the rejection or recheck request cause!");
            return false;
        }
    }
    return true;
}


$(function () {

    if($("#usertype").val()==const_role_public_regulatory_affairs){

        var button = $('#btnUploadBTRCNOC'), interval;
        var txtbox = $('#attachBTRCNOC');

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
                    alertify.error('Invalid File Format.');
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

    if($("#usertype").val()==const_role_Buyer) {
        var button = $('#btnUploadLCChecklist'), interval;
        var txtbox = $('#attachLCChecklist');

        new AjaxUpload(button, {
            action: 'application/library/uploadhandler.php',
            name: 'upl',
            onComplete: function (file, response) {
                var res = JSON.parse(response);
                txtbox.val(res['filename']);
                window.clearInterval(interval);
            },
            onSubmit: function (file, ext) {
                if (!(ext && /^(xlsx|xls)$/i.test(ext))) {
                    alertify.error('Invalid File Format.');
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

