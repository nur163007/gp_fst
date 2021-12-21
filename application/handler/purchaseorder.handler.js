/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var telRequired = false, ldRequired = 0;
var pono = $("#ponum").val(),
    ship = $("#ship").val(),
    serviceType = $("#serviceType").val(),
    oldDraft = false,
    oldReq = false;
var req, poline;
var poid, podata, comments, attach;

$(document).ready(function() {

/**
 * ---------------------------------------------------------------------
 * After document ready
 * ---------------------------------------------------------------------
 */
    
    $("#poid1").keyup(function(e){        
        validatePONumber();
    });
    
    if($('#poid').val()==""){   // In case of new PO

        $.getJSON("api/purchaseorder?action=11", function (list) {
            //alert('sds')
            $("#poid1").select2({
                data: list,
                placeholder: "select a PO",
                allowClear: false,
                width: "100%"
            });
        });
        poid = "";

        dataLoadCall();
        
    } else{ // In case of Old PO
        // $('#poid1').val(originalPO($('#poid').val())).attr("readonly", true);
        
        poid = $('#poid').val();
        
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
                $('#podesc').val(podata['podesc']);
                $('#povalue').val(commaSeperatedFormat(podata['povalue']));
                $('#deliverydate').val(Date_toMDY(new Date(podata['deliverydate'])));
                $('#actualPoDate').datepicker( "setDate" , new Date(podata['actualPoDate']));
                $('#emailto').tokenfield('setTokens',podata["emailto"]);
                $('#emailcc').tokenfield('setTokens',podata["emailcc"]);
                $('#noflcissue').val(podata['noflcissue']);
                $('#nofshipallow').val(podata['nofshipallow']);

                $('#installBy_' + podata['installbysupplier']).attr('checked','').parent().addClass('checked');
                /*if(podata["installbysupplier"]==1){
    				$('#installbysupplier').attr("checked",true).parent().addClass("checked");
    			} else {
    				$('#installbysupplier').removeAttr("checked").parent().removeClass("checked");
    			}*/
                
                // loading attachments
                /*var attachmentHtml = '';
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
                            '<label class="control-label"><i class="icon wb-file"></i>&nbsp;&nbsp;<a href="temp/'+attach[i]['filename']+'" target="_blank">'+attach[i]['filename']+'</a></label>'+
                        '</div>';
                    //}
                }
                $('#usersAttachments').html(attachmentHtml);*/
                attachmentLogScript(attach, '#usersAttachments');
                /*// Comments log
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
                
                // Supplier's comments log
                $('#suppliersmsgtitle').hide();
                $('#suppliersmsg').hide();
                var commentsLog = '';
                for(var i=0; i<comments.length; i++){
                    if(comments[i]['msg']!=null && comments[i]['fromgroup']==_supplier){
                        if(commentsLog.length>0){commentsLog += '<hr />';}
                        commentsLog += '<span class="comment-author">' + comments[i]['rolename'] + ': <span class="text-primary">' + comments[i]['username'] + '</span> <i class="icon wb-arrow-right small"></i> '+ comments[i]['torole'] +'</span><div class="comment-meta"> on '+comments[i]['msgon'] + '</div>';
                        commentsLog += '<div class="comment-content">' + comments[i]['msg'] + '</div>';
                    }
                }
                if(commentsLog!=''){
                    $('#suppliersmsg').html(commentsLog);
                    $('#suppliersmsgtitle').show();
                    $('#suppliersmsg').show();
                }*/
                commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');
                
                dataLoadCall();
            }
        });
    }

    /**
     * ---------------------------------------------------------------------
     * Control Events Binding
     * ---------------------------------------------------------------------
     */
    
    $(function () {
        $("#SendPO_btn").click(function (e) {
            //alert('ddas');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm('Are you sure you want submit this PO?', function (e) {
                    if (e) {
                        //$("#SendPO_btn").hide();
                        $.ajax({
                            type: "POST",
                            url: "api/purchaseorder",
                            data: $('#po-form').serialize(),
                            cache: false,
                            success: function (response) {
                                //alert(response);
                                try {
                                    var res = JSON.parse(response);
                                    if (res['status'] == 1) {
                                        resetForm();
                                        alertify.success(res['message']);
                                        window.location.href = _dashboardURL;
                                    } else {
                                        //$("#SendPO_btn").show();
                                        alertify.error(res['message']);
                                        return false;
                                    }
                                } catch (e) {
                                    console.log(e);
                                    alertify.error(response + ' Failed to process the request.');
                                    return false;
                                }
                            },
                            error: function (xhr, textStatus, error) {
                                alertify.error(textStatus + ": " + xhr.status + " " + error);
                            }
                        });
                    } else { // canceled
                        //alertify.error(e);
                        //$(this).show();
                    }
                });
            } else {
                return false;
            }
        });
    });
    
    $("#supplier").on("select2:select", function (e) {
        var id = $("#supplier").val();
        console.log(id)
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
                $("#supplier_address").val(row['address']);
            }
        });

        if ($("#supplier").val() == 4){ // 4 = HUAWEI INTERNATIONAL PTE. LTD.
            $("#currency").val(105).change(); // 105 = EURO
        }else {
            $("#currency").val(1).change(); // 1 = USD
        }
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


    $("#poid1").on("select2:select", function (e) {
        
        var id = $("#poid1").val();

        //SUPPLIER LOAD
        $.getJSON("api/purchaseorder?action=12&id="+id, function (list) {
            $("#supplier").empty();
            $("#supplier").select2({
                data: list,
                minimumResultsForSearch: Infinity,
                placeholder: "Select Supplier",
                allowClear: true,
                width: "100%"
            });

        });

        //PO INFO LOAD
        $.get('api/purchaseorder?action=13&id='+id, function (data) {

            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            }else {

                var row = JSON.parse(data);
                podata = row[0][0];


                // PO info
                // $('#podesc').val(podata['podesc']);
                $('#povalue').val(commaSeperatedFormat(podata['POAmount']));
                // $('#currency').val(podata['currency']);
                $('#deliverydate').val(podata['needByDate']);
                $('#actualPoDate').val(podata['poDate']);
                $('#podesc').val(podata['poDesc']);
                $('#dept').val(podata['PRUserDept']);
                // $('#supplier').val(podata['supplierId']).change();
                $('#supplier').val(podata['supplierId']).change();
            
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
                
                $.get("api/company?action=5&id="+podata['supplierId'], function (data) {
                    if($.trim(data)){
                        var row = JSON.parse(data);
                        $("#emailto").tokenfield('setTokens',row['emailTo']);
                        $("#emailcc").tokenfield('setTokens',row['emailCc']);
                        $("#supplier_address").val(row['address']);
                    }
                });
                
                // $('#emailto').tokenfield('setTokens',podata["emailto"]);
                // $('#emailcc').tokenfield('setTokens',podata["emailcc"]);

                // $('#installBy_' + podata['installbysupplier']).attr('checked','').parent().addClass('checked');
            }
        });

        //PO LINE LOAD
        $.get("api/delivery-notification?action=1&id="+id, function (data) {
            var res = JSON.parse(data);
            if (res[2].length > 0) {
                var rejectedLines = (res[2][0]["rejectedlines"]).split(",");
                /*
                 * Message for rejected lines
                 * */
                $('#reject-message').show();
                //document.getElementById("rejected-line").innerHTML = rejectedLines;
                $("#rejected-line").html(rejectedLines);

            } else {
                rejectedLines = "";
                $('#reject-message').hide();
            }
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
                        '<td class="text-center">' + d1[i]['poDate'] + '</td>' +
                        '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                        '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                        '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                        '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                        '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                        '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                        /*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*/
                        '</tr>';
                    $("#dtPOLinesDelivered tbody:last").append(strRow);
                }
            } else {
                $("#dtPOLinesDelivered tbody").empty();
                $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
            }

            /**
             * Deliverable po lines
             */
            var d2 = res[1];
            $("#delivCount2").html('(' + d2.length + ')');
            if (d2.length > 0) {
                $("#dtPOLines tbody").empty();
                if (d2[0]["poDate"] != null) {
                    $('#poDate').val(Date_toDetailFormat(new Date(d2[0]["poDate"])));

                    // $('#poDate').datepicker('setDate', new Date(d2[0]["poDate"]));
                    // $('#poDate').datepicker('update');
                }
                $('#needByDate').val(Date_toDetailFormat(new Date(d2[0]["needByDate"]))).change();
                // $('#needByDate').datepicker('setDate', new Date(d2[0]["needByDate"]));
                // $('#needByDate').datepicker('update');

                $('#currency').val(d2[0]["currency"]);
                $('.currencyText').html(d2[0]["currency"]);

                for (var j = 0; j < d2.length; j++) {
                    if (rejectedLines != "") {
                        if (rejectedLines.indexOf(d2[j]["lineNo"]) < 0) {
                            addPOLine(d2[j]);
                        }
                    } else {
                        addPOLine(d2[j]);
                    }
                }
                $('#chkAllLine').prop('checked', true);
                $('.chkLine').prop('checked', true);

                poGrandTotal();
            } else {
                $("#dtPOLines tbody").empty();
                $("#dtPOLines tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
            }
            // ldGrandTotal();
        });

    });
/**
 * ---------------------------------------------------------------------
 * Control Events Binding   END
 * ---------------------------------------------------------------------
 */
$(document).on('keyup', '.unitPrice, .poQty', function () {
    poLineTotal(this);
    poGrandTotal();
});

    $(document).on('keyup blur', '.delivQty', function (e) {
        //alert(1);
        poLineDelivTotal(this);
        poGrandTotal();
        // calculateInvoiceAmount($("#poNo").val());
    });

    //Deduct registration fee from invoice amount
/*    $('#reg_fee').on("input paste", function(e) {
        e.preventDefault();
        var dInput = this.value;
        if (dInput != "") {
            //$('#invAmount').val($('#dlvGrandTotal').val() - dInput);
            calculateInvoiceAmount(pono);
        }
    });*/


    /*************************************************************************
     * One click action
     *************************************************************************/
    $('#chkAllLine').click(function (e) {
        if (this.checked) {
            $('.chkLine').prop('checked', true);
        } else {
            $('.chkLine').prop('checked', false);
        }

        $("#dtPOLines tbody").find('tr').each(function () {

            if ($(this).find('input.chkLine').is(':checked')) {
                $(this).find('input.delivQty').val($(this).find('input.delivQtyValid').val());
                $(this).find('input.delivTotal').val($(this).find('input.delivAmountValid').val());
            } else {
                $(this).find('input.delivQty').val(0);
                $(this).find('input.delivTotal').val(0);
            }
        });
        poGrandTotal();
        // calculateInvoiceAmount($("#poNo").val());
    });

    $(document).on('click', '.chkLine', function () {
        if (!this.checked) {
            $('#chkAllLine').prop('checked', false);
            $(this).parent().parent().parent().find('input.delivQty').val(0);
        } else {
            var d = $(this).parent().parent().parent().find('input.delivQtyValid').val();
            $(this).parent().parent().parent().find('input.delivQty').val(d);
        }
        poLineWisePoTotal();
        poGrandTotal();
        // calculateInvoiceAmount($("#poNo").val());
        /*var cIndex = this.id.substr(this.id.indexOf("_") + 1, 1);

        if ($("#dlvQty_" + cIndex).val() == 0) {
            $("#dlvQty_" + cIndex).val($("#poQty_" + cIndex).val());
        }
        doCalculation();*/
        $('#reg_fee').val("");
    });

    $("#dlvQtyAll").keyup(function () {
        $(".delivQty").val($("#dlvQtyAll").val());
        $(".delivQty").attr("title", $("#dlvQtyAll").val());
        poLineWisePoTotal();
        poGrandTotal()
        //doCalculation();
    });

    $('#reject-message').hide();
});

function dataLoadCall(){
    
    $.getJSON("api/company?action=4", function (list) {
        //alert('sds')
       /* $("#supplier").select2({
            data: list,
            placeholder: "select a company",
            allowClear: false,
            width: "100%"
        });*/
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

$("#deliverydate, #draftsendby, #actualPoDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});


function validate() {

    if ($("#poid1").val() == "") {
        $("#poid1").focus();
        alertify.error("PO Number is required!");
        return false;
    }
    if ($("#importAs").val() == "") {
        $("#importAs").focus();
        alertify.error("Please select Import As!");
        return false;
    }
    if ($("#podesc").val() == "") {
        $("#podesc").focus();
        alertify.error("PO Description is required!");
        return false;
    }
    if ($("#supplier").val() == "") {
        $("#supplier").focus();
        alertify.error("Select a Supplier!");
        return false;
    }
    if ($("#currency").val() == "") {
        $("#currency").focus();
        alertify.error("Select a Currency!");
        return false;
    }
    if ($("#povalue").val() == "") {
        $("#povalue").focus();
        alertify.error("PO Value is required!");
        return false;
    } else {
        if (!Number($("#povalue").val().replace(/,/g, ""))) {
            $("#povalue").focus();
            alertify.error("Not a valid value!");
            return false;
        }
    }
    if ($("#contractref").val() == "") {
        $("#contractref").focus();
        alertify.error("Contact Reference is required!");
        return false;
    }
    if ($("#contractref").val() == "") {
        $("#contractref").focus();
        alertify.error("Contact Reference is required!");
        return false;
    }
    if ($("#deliverydate").val() == "") {
        $("#deliverydate").focus();
        alertify.error("Fill Up the Need by Date field!");
        return false;
    }
    if ($("#draftsendby").val() == "") {
        $("#draftsendby").focus();
        alertify.error("Fill Up the Draft PI Last Date field!");
        return false;
    }
    if (!$("#actualPoDate").val()) {
        $("#actualPoDate").focus();
        alertify.error("Actual PO date is required");
        return false;
    }
    if ($("#prno").val() == "") {
        $("#prno").focus();
        alertify.error("PR NO is required!");
        return false;
    }
    if ($("#dept").val() == "") {
        $("#dept").focus();
        alertify.error("Department is required!");
        return false;
    }
    if ($("#prUserEmailTo").val() == "") {
        $("#prUserEmailTo").focus();
        alertify.error("Write PR User Email!");
        return false;
    }
    if ($("#prUserEmailCC").val() == "") {
        $("#prUserEmailCC").focus();
        alertify.error("Write PR User Email!");
        return false;
    }
    if ($("#emailto").val() == "") {
        $("#emailto").focus();
        alertify.error("Supplier's Email is required!");
        return false;
    }
    if ($("#emailcc").val() == "") {
        $("#emailcc").focus();
        alertify.error("Supplier's Email CC is required!");
        return false;
    }
    if ($("#noflcissue").val() == "") {
        $("#noflcissue").focus();
        alertify.error("No of LC is required!");
        return false;
    }
    if ($("#nofshipallow").val() == "") {
        $("#nofshipallow").focus();
        alertify.error("No of Shipment is required!");
        return false;
    }
    if ($("#supplier_address").val() == "") {
        $("#supplier_address").focus();
        alertify.error("Supplier Address is required!");
        return false;
    }

    var installBy_check = $('input:radio[name=installBy]:checked').val();

    if (installBy_check == undefined) {
        alertify.error("Please select Implement by option!");
        return false;
    }

    if ($("#attachpo").val() == "") {
        $("#attachpo").focus();
        alertify.error("Attach Purchase Order Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachpo").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachboq").val() == "") {
        $("#attachboq").focus();
        alertify.error("Attach BOQ Documents!");
        return false;
    } else {
        if (!validAttachment($("#attachboq").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
    if ($("#attachother").val() != "") {
        if (!validAttachment($("#attachother").val())) {
            alertify.error('Invalid File Format.');
            return false;
        }
    }
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
    //var button_cancel = $('#attachother_cancel');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        autoSubmit: true,
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            //alert(ext);
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

function validatePONumber(){
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
}


function vatAmount(pa, vr) {
    return pa*(vr/100);
}

function poLineTotal(elm) {

    var linePrice, lineQty, lineTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = linePrice * lineQty;

    $(elm).closest('tr').find('input.lineTotal').val(commaSeperatedFormat(lineTotal));
    $(elm).closest('tr').find('input.lineTotal').attr('title', commaSeperatedFormat(lineTotal));

}

function poLineDelivTotal(elm) {
    var linePrice, lineDelivQty, lineDelivTotal;

    linePrice = parseToCurrency($(elm).closest('tr').find('input.unitPrice').val());
    lineDelivQty = parseToCurrency($(elm).closest('tr').find('input.delivQty').val());
    lineDelivTotal = linePrice * lineDelivQty;

    $(elm).closest('tr').find('input.delivTotal').val(lineDelivTotal);
    $(elm).closest('tr').find('input.delivTotal').attr('title', lineDelivTotal);
    // calculateInvoiceAmount($("#poNo").val());
}

function poLineDelivQty(elm) {

    var lineQty, lineTotal, lineDelivQty, lineDelivTotal;

    lineQty = parseToCurrency($(elm).closest('tr').find('input.poQty').val());
    lineTotal = parseToCurrency($(elm).closest('tr').find('input.lineTotal').val());
    lineDelivTotal = parseToCurrency($(elm).closest('tr').find('input.delivTotal').val());

    lineDelivQty = lineQty / (lineTotal / lineDelivTotal);
    $(elm).closest('tr').find('input.delivQty').val(commaSeperatedFormat(lineDelivQty, 4));
    $(elm).closest('tr').find('input.delivQty').attr('title', commaSeperatedFormat(lineDelivQty, 4));
}

function poLineWisePoTotal() {

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        poLineDelivTotal(this);

    });
}

function poGrandTotal() {

    var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
        delivTotal = 0;

    $("#dtPOLines tbody").find('tr').each(function (rowIndex, r) {

        qty = parseToCurrency($(this).find('input.poQty').val());
        totalQty += qty;
        $("#poQtyTotal").val(totalQty);

        totalPrice = parseToCurrency($(this).find('input.lineTotal').val());
        grandTotal += totalPrice;
        $("#grandTotal").val(+(grandTotal).toFixed(2));

        if ($(this).find('input.chkLine').is(':checked')) {
            delivQty = parseToCurrency($(this).find('input.delivQty').val());
        } else {
            delivQty = 0;
        }
        totalDelivQty += delivQty;
        $("#dlvQtyTotal").val(+(totalDelivQty).toFixed(2));
        //alert(totalDelivQty);

        if ($(this).find('input.chkLine').is(':checked')) {
            delivPrice = parseToCurrency($(this).find('input.delivTotal').val());
        } else {
            delivPrice = 0;
        }
        delivTotal += delivPrice;
        $("#dlvGrandTotal").val(+(delivTotal).toFixed(2));
        // $("#invAmount").val(commaSeperatedFormat(delivTotal));
        // $('#baseAmount').val(commaSeperatedFormat($("#dlvGrandTotal").val()));
        //calculateInvoiceAmount()
    });
}


// datepart: 'y', 'm', 'w', 'd', 'h', 'n', 's'
Date.dateDiff = function (datepart, fromdate, todate) {
    datepart = datepart.toLowerCase();
    var diff = todate - fromdate;
    var divideBy = {
        w: 604800000,
        d: 86400000,
        h: 3600000,
        n: 60000,
        s: 1000
    };

    return Math.floor(diff / divideBy[datepart]);
};


function addPOLine(row) {
    var i = $("#dtPOLines tbody tr").length;

    row = row || '';

    if (row == '') {
        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '">' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" /></td>' +
            '<td><input type="text" class="form-control input-sm uom" /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="0" /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="0" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="0" /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="0" readonly /></td>' +
            /*'<td><input type="text" class="form-control input-sm text-right ldAmnt" value="0" /></td>' +*/
            // '<td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>' +
            '</tr>');
    } else {
        //@todo analyze here
        //console.log(row["status"]);
        if (!row["status"]) {
            var addTick = "";
            /*var voidDelivQty = 0;
            var voidDelivAmount = 0*/
            var voidDelivQty = row["delivQtyValid"];
            var voidDelivAmount = row["delivAmountValid"]
        } else {
            addTick = "checked";
            voidDelivQty = row["delivQtyValid"];
            voidDelivAmount = row["delivAmountValid"];
        }

        $("#dtPOLines tbody:last").append('<tr>' +
            '<td class="text-center"><span class="checkbox-custom checkbox-default">' +
            '<input type="checkbox" class="chkLine" id="chkLine_' + i + '" ' + addTick + ' disabled>' +
            '<label for="chkLine_' + i + '"></label></span></td>' +
            '<td><input type="text" class="form-control input-sm text-center poLine" value="' + row["lineNo"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poItem" value="' + row["itemCode"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDesc" value="' + htmlspecialchars_decode(row["itemDesc"]) + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm poDate" value="' + row["poDate"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm uom" value="' + row["uom"] + '" readonly /></td>' +
            '<td><input type="text" class="form-control input-sm text-right unitPrice" value="' + row["unitPrice"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right poQty" value="' + row["poQty"] + '" readonly /></td>' +
            '<td class="poBg"><input type="text" class="form-control input-sm text-right lineTotal" value="' + row["poTotal"] + '" readonly /></td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivQty" value="' + voidDelivQty + '" title="' + row["delivQtyValid"] + '" readonly /><input type="hidden" class="delivQtyValid" value="' + row["delivQtyValid"] + '" /> <input type="hidden" class="delivAmountValid" value="' + row["delivAmountValid"] + '" /> </td>' +
            '<td class="delivBg"><input type="text" class="form-control input-sm text-right delivTotal" value="' + voidDelivAmount + '" title="' + voidDelivAmount + '" readonly /></td>' +
            /*'<td><input type="text" class="form-control input-sm text-right ldAmnt" name="ldAmnt[]" value="'+row["ldAmount"]+'" /></td>' +*/
            // '<td><button class="btn btn-pure btn-warning btn-xs icon wb-close delPO"></button></td>' +
            '</tr>');
    }
}


/*!
* PO lines verification
* */
function poLineVerify() {
    poLinesOkay = true;
    $("#consolidatedPoLines").val("");
    $("#dtPOLines tbody").find('tr').each(function () {

        if ($("#consolidatedPoLines").val() != "") {
            $("#consolidatedPoLines").val($("#consolidatedPoLines").val() + "|");
        }

        if ($(this).find('input.chkLine').is(':checked')) {

            if ($(this).find('input.poLine').val() == "") {
                alertify.error('PO line number missing!');
                $(this).find('input.poLine').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poItem').val() == "") {
                alertify.error('Item code missing!');
                $(this).find('input.poItem').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poDesc').val() == "") {
                alertify.error('Item description missing!');
                $(this).find('input.poDesc').focus();
                poLinesOkay = false;
                return false;
            }
            /*if ($(this).find('input.projCode').val() == "") {
             alertify.error('Project code missing!');
             $(this).find('input.projCode').focus();
             poLinesOkay = false;
             return false;
             }*/
            if ($(this).find('input.uom').val() == "") {
                alertify.error('UOM missing!');
                $(this).find('input.uom').focus();
                poLinesOkay = false;
                return false;
            }
            /*!
            * O value allowed in
            * Unit Price, PO line total, Delivered price
            * as per the mail ref: FMfcgxwKjKqwmqZcjBQZSfQhhFFQKGSK
            * *************************************************************/
            if ($(this).find('input.unitPrice').val() == "" || parseFloat($(this).find('input.unitPrice').val()) < 0) {
                alertify.error('Unit price missing!');
                $(this).find('input.unitPrice').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.poQty').val() == "" || parseFloat($(this).find('input.poQty').val()) <= 0) {
                alertify.error('PO line qty missing!');
                $(this).find('input.poQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.lineTotal').val() == "" || parseFloat($(this).find('input.lineTotal').val()) < 0) {
                alertify.error('PO line total missing!');
                $(this).find('input.lineTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivQty').val() == "" || parseFloat($(this).find('input.delivQty').val()) <= 0) {
                alertify.error('Delivered qty missing!');
                $(this).find('input.delivQty').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.delivTotal').val() == "" || parseFloat($(this).find('input.delivTotal').val()) < 0) {
                alertify.error('Delivered price missing!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if (parseToCurrency($(this).find('input.delivAmountValid').val()) < parseToCurrency($(this).find('input.delivTotal').val())) {
                //alertify.error('Delivered amount can not be grater then PO amount!');
                alertify.error('Invalid delivered qty or amount!');
                $(this).find('input.delivTotal').focus();
                poLinesOkay = false;
                return false;
            }
            if ($(this).find('input.ldAmnt').val() == "") {
                $(this).find('input.ldAmnt').val('0');
            }

            var projectCode = ($(this).find('input.projCode').val()) ? $(this).find('input.projCode').val() : "NA";
            //var projectCode = (1===1) ? 'test' : 'empty' ;
            $("#consolidatedPoLines").val(
                $("#consolidatedPoLines").val() + $(this).find('input.poLine').val() + ";" +
                $(this).find('input.poItem').val() + ";" +
                $(this).find('input.poDesc').val() + ";" +
                //$(this).find('input.projCode').val() + ";" +
                projectCode + ";" +
                $(this).find('input.uom').val() + ";" +
                $(this).find('input.unitPrice').val() + ";" +
                $(this).find('input.poQty').val() + ";" +
                $(this).find('input.lineTotal').val() + ";" +
                $(this).find('input.delivQty').val() + ";" +
                $(this).find('input.delivTotal').val()
            )
        }
    });
}

/*$("#actualDelivDate").datepicker({
    format: 'dd-M-yyyy',
    todayHighlight: true,
    orientation: 'auto',
    autoclose: true
});*/
/*$("#invDate, #needByDate, #poDate").datepicker({
    format: 'dd-M-yyyy',
    todayHighlight: true,
    autoclose: true
});*/
$("#invDate, #needByDate, #poDate").datepicker({enableOnReadonly: false});

/**
 * Remove color from radio button
 * */
$("input[name='incType'], input[name='deductAuth']").on('ifChanged', function (event) {

    //Check if checkbox is checked or not
    var checkboxChecked = $(this).is(':checked');
    //alert(this.value);

    if (checkboxChecked) {
        $("#incTypeArea").removeClass("mandatory");
        $("#deductAuthArea").removeClass("mandatory");
    }
});


