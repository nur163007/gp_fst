/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val(),
    shipno = $('#shipno').val();
var u = $('#usertype').val();

var podata,comments,attach,lcinfo,pterms;

$(document).ready(function(){
    
    $("#shipmentInfo input").attr("readonly",true);
    
    $('#dtAverageCostdate').dataTable( {
		"ajax": "api/average-cost-fin?action=3&po="+poid+"&shipno="+shipno,
		"columns": [
			{ "data": "pono" },
			{ "data": "ipcno" },
			{ "data": "poline", "class":"text-center" },
			{ "data": "item" },
			{ "data": "desc" },
			{ "data": "qty", "class":"text-center" },
			{ "data": "uom" },
			{ "data": "price", "class":"text-right" },
			{ "data": "amount", "class":"text-right" },
			{ "data": "curr", "class":"text-center" }
            ],
        "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                totalAmount = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                usedCurrency = api
                    .column( 9 )
                    .data()
                    .reduce( function(a,b){
                        return b;
                    },0);
                
                // Update footer
                $( api.column( 8 ).footer() ).html(
                    commaSeperatedFormat(totalAmount.toFixed(2))
                );
                $( api.column( 9 ).footer() ).html(
                    usedCurrency
                );
                validTotal();
            },
		"sDom": '',
        "paging": false,
        "bProcessing": true,
        "autoWidth": false
	});

    // Loading pre data from PO
    $.get('api/purchaseorder?action=2&id='+poid+'&shipno='+shipno, function (data) {
       
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {
            
            var row = JSON.parse(data);
            
            podata = row[0][0];
            var comments = row[1];
            attach = row[2];
            //var lcinfo = row[3][0]; 
            /*alert('fssd');*/
            //var pterms = row[4];
            
            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>'+commaSeperatedFormat(podata['povalue'])+'</b> '+podata['curname']);
            $('#podesc').html(podata['podesc']);
            $('#lcdesc').html('<b>'+podata['lcdesc']+'</b>');
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
            
            $('#pivalue').html('<b>'+commaSeperatedFormat(podata['pivalue'])+'</b> '+podata['curname']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#hscode').html(podata['hscode']);
            
            $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
            $('#basevalue').html(podata['basevalue']);
            
            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);
            
            var attachFilter = ["Bank Charge Advice","Pay Order Issue Charge","Insurance Cover Note","Pay Order Receive Copy","Amendment Advice Note"];
            attachmentLogScript(attach,'#usersAttachments',1,attachFilter,-1);
            commentsLogScript(comments, '#buyersmsg', '');
            
            
            $.get("api/shipment?action=1&po="+poid+"&shipno="+$("#shipno").val(), function (data){
                
                var ship = JSON.parse(data);
                
                if(ship["ipcNo"]!=null){
                    $("#ipcNo").val(ship["ipcNo"]);
                    restrictRejection();
                }
                if(ship["gitReceiveDate"]!=null){
                    var d = new Date(ship['gitReceiveDate']);
                    $('#gitReceiveDate').datepicker('setDate', d);
                    $('#gitReceiveDate').datepicker('update');
                    restrictRejection();
                }
                if(ship["whArrivalDate"]!=null){
                    var d = new Date(ship['whArrivalDate']);
                    $('#whArrivalDate').datepicker('setDate', d);
                    $('#whArrivalDate').datepicker('update');
                    restrictRejection();
                }
                
                $("#scheduleETA").val(Date_toDetailFormat(new Date(ship['scheduleETA'])));
                $("#mawbNo").val(ship['mawbNo']);
                $("#hawbNo").val(ship['hawbNo']);
                $("#blNo").val(ship['blNo']);
                $("#awbOrBlDate").val(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));
                $("#ciNo").val(ship['ciNo']);
                $("#ciDate").val(Date_toDetailFormat(new Date(ship['ciDate'])));
                $("#ciAmount").val(commaSeperatedFormat(ship['ciAmount']));
                $("#ciAmountHidden").val(commaSeperatedFormat(ship['ciAmount']));//To compare with total amount in average cost update
                $("#invoiceQty").val(ship['invoiceQty']);
                $("#noOfcontainer").val(ship['noOfcontainer']);
                $("#noOfBoxes").val(ship['noOfBoxes']);
                $("#ChargeableWeight").val(ship['ChargeableWeight']);
                $("#dhlNum").val(ship['dhlTrackNo']);
                if(ship['docDeliveredByFin']!=null){
                    $("#docDeliveredByFin").val(Date_toMDY_HMS(new Date(ship['docDeliveredByFin'])));
                }
                validTotal();
            });
        }
    });
    
    checkStepOvered();

    $("#reject_btn").click(function (e) {
        e.preventDefault();
        if($("#rejectMessage").val()!=""){
            alertify.confirm( 'Are you sure you want to reject?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/warehouse-inputs",
                        data: "userAction=1"+"&pono="+poid+"&shipno="+$("#shipno").val()+"&ref="+$("#refId").val()+"&message="+$("#rejectMessage").val(),
                        cache: false,
                        success: function (result) {
                            var res = JSON.parse(result);
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED to add!");
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
            $("#ipcNo").focus();
            alertify.error("Please mention the cause for rejection");
        }
    });
    
    $("#btn_NotifyToBuyer").click(function (e) {
        e.preventDefault();
        if(validateForBuyerNotification()===true){
            alertify.confirm( 'Are you sure you want to send notification to Buyer?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/warehouse-inputs",
                        data: "userAction=2"+"&pono="+poid+"&shipno="+$("#shipno").val()+"&refId="+$("#refId").val(),
                        cache: false,
                        success: function (result) {
                            // alert(result);
                            var res = JSON.parse(result);
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED to add!");
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
            $("#ipcNo").focus();
            alertify.error("Please type IPC number");
        }
    });
    
    $("#ipcNo_btn").click(function (e) {

        e.preventDefault();
        if($("#ipcNo").val()!=""){
            $.ajax({
                type: "POST",
                url: "api/warehouse-inputs",
                data: "userAction=3"+"&pono="+poid+"&shipno="+$("#shipno").val()+"&ipcNo="+$("#ipcNo").val(),
                cache: false,
                success: function (result) {
                    //alert(result);
                    var res = JSON.parse(result);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        //checkStepOvered();
                    } else {
                        alertify.error("FAILED to add!");
                        return false;
                    }
                }
            });
        } else {
            $("#ipcNo").focus();
            alertify.error("Please typr IPC number");
        }
    });
    
    $("#gitReceiveDate_btn").click(function (e) {
        e.preventDefault();
        if($("#gitReceiveDate").val()!=""){
            $.ajax({
                type: "POST",
                url: "api/warehouse-inputs",
                data: "userAction=4"+"&pono="+$("#pono").val()+"&shipno="+$("#shipno").val()+"&gitReceiveDate="+$("#gitReceiveDate").val(),
                cache: false,
                success: function (result) {
                    var res = JSON.parse(result);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        checkStepOvered();
                    } else {
                        alertify.error("FAILED to add!");
                        return false;
                    }
                }
            });
        } else {
            $("#ipcNo").focus();
            alertify.error("Please enter IPC number");
        }
    });
    
    $("#whArrivalDate_btn").click(function (e) {
        e.preventDefault();
        if(whValidation()){
            $.ajax({
                type: "POST",
                url: "api/warehouse-inputs",
                data: "userAction=5"+"&pono="+$("#pono").val()+"&shipno="+$("#shipno").val()+"&whArrivalDate="+$("#whArrivalDate").val(),
                cache: false,
                success: function (result) {
                    var res = JSON.parse(result);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        checkStepOvered();
                        //window.location.href = _adminURL;
                    } else {
                        alertify.error("FAILED to add!");
                        return false;
                    }
                }
            });
        } else {
            $("#ipcNo").focus();
            alertify.error("Please type IPC number");
        }
    });

    $("#btn_NotifyToFinance").click(function (e) {
        e.preventDefault();
        if(validateAvgCostUpdate()===true){
            alertify.confirm( 'Are you sure you want to Notify Finance?', function (e) {
                if(e){
                    $.ajax({
                        type: "POST",
                        url: "api/warehouse-inputs",
                        data: "userAction=7&refId="+$('#refId').val()+"&pono="+$('#pono').val()+"&shipno="+$("#shipno").val(),
                        cache: false,
                        success: function (result) {
                            //alert(result);
                            var res = JSON.parse(result);
                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED to add!");
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
        }
    });

});

function validateForBuyerNotification(){

    if($("#ipcNo").val()==""){
        $("#ipcNo").focus();
        alertify.error("IPC Number can not blank!");
        return false;
    }
    if($("#gitReceiveDate").val()==""){
        $("#gitReceiveDate").focus();
        alertify.error("GIT receiving date can not blank!");
        return false;
    }
    /*if($("#whArrivalDate").val()==""){
        $("#whArrivalDate").focus();
        alertify.error("Warehouse receive date can not blank!");
        return false;
    }*/

    return true;
}

function whValidation(){
    if($("#whArrivalDate").val()==""){
        $("#whArrivalDate").focus();
        alertify.error("Arrival at Warehouse date is mandatory");
        return false;
    }
    return true;
}

function restrictRejection() {
    //alert('yes');
    $("#rejectMessage, #reject_btn").attr("disabled", true);
}

function validTotal(){
    
    //alert($("#dtTotalAmount").html());
    
    var ci = parseToCurrency($("#ciAmount").val());
    var total = parseToCurrency($("#dtTotalAmount").html());
    
    if(ci!=total){
        $("#dtTotalAmount").addClass("text-danger");
        return false;
    } else{
        $("#dtTotalAmount").removeClass("text-danger");
        $("#dtTotalAmount").addClass("text-success");
        return true;
    }
}    

function validateAvgCostUpdate(){
    
    if(validTotal()!= true){
        alertify.error("Average cost total is not same as CI value.");
        return false;
    }
    
    return true;
}

function checkStepOvered(){

    // alert('api/warehouse-inputs?action=1&po='+poid+'&shipno='+$("#shipno").val());
    $.get('api/warehouse-inputs?action=1&po='+poid+'&shipno='+$("#shipno").val(), function(result){
        if(result > 0){
            $("#btn_NotifyToBuyer").attr("disabled",true);
        }
    });
}


$("#gitReceiveDate, #whArrivalDate").datepicker({
    format: 'MM dd, yyyy',
    todayHighlight: true,
    autoclose: true
});

$(function () {

    var button = $('#importCSVFile_btn'), interval;
    var txtbox = $('#importCSVFile');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
            submitCSV(res['filename']);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(csv)$/i.test(ext))) {
                alertify.alert('Invalid File Format.');
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

function submitCSV(csvfile){
    $.ajax({
        type: "POST",
        url: "api/warehouse-inputs",
        data: "userAction=6"+"&csv="+csvfile+"&po="+poid+"&shipno="+$("#shipno").val(),
        cache: false,
        success: function (result) {
            var res = JSON.parse(result);
            if (res['status'] == 1) {					
				alertify.success(res['message']);
                var dtable = $('#dtAverageCostdate').dataTable();	
				dtable.api().ajax.reload();                
            } else {
                alertify.error("FAILED to add!");
                return false;
            }
        }
    });
}
