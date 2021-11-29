/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val(),
    shipno = $('#shipno').val();
var u = $('#usertype').val();

var dtInit = false;

$(document).ready(function() {

    /*$(document).ready(function() {
    	$('#csvAvgCost').DataTable( {
    		dom: 'B',
    		buttons: [
    			'copyHtml5',
    			'excelHtml5',
    			'csvHtml5',
    			'pdfHtml5'
    		]
    	} );
    } );*/


    poid = $('#pono').val();
    // alert("api/shipment?action=1&po="+poid+"&shipno="+shipno);
    $.get("api/shipment?action=1&po=" + poid + "&shipno=" + shipno, function (data) {

        var ship = JSON.parse(data);

        $("#LcNo").val(ship["lcno"]);
        $("#LcValue").val(ship["lcvalue"]);

        $("#MAWBNo").val(ship['mawbNo']);
        $("#HAWBNo").val(ship['hawbNo']);
        $("#BLNo").val(ship['blNo']);
        $("#CIValue").val(commaSeperatedFormat(ship['ciAmount']));
        $("#gpRefNo").val(ship['eaRefNo']);
        $("#ipcNo").val(ship['ipcNo']);
        $("#cnfNetPayment").val(commaSeperatedFormat(ship['cnfNetPayment']));

        GetLCOCapex();
        GetInshuranceCapex();
        GetCustomDuty();

    });

    /*//LoadCostUpdateData(0,0);
    $("#pono").change(function(e){
        
        poid = $("#pono").val();
        
        $("#LcNo").val("loading...");
        $("#LcValue").val("loading...");
        $("#lcOpenCharge").val("loading...");
        $("#insPremium").val("loading...");
        
        $.getJSON("api/shipment?action=5&po="+poid+"&col=mawbNo", function (list) {
            $("#MAWBNo").select2({
                data: list,
                placeholder: "Select MAWB number",
                allowClear: false,
                width: "100%"
            });
        });
        
        $.getJSON("api/shipment?action=5&po="+poid+"&col=hawbNo", function (list) {
            $("#HAWBNo").select2({
                data: list,
                placeholder: "Select HAWB number",
                allowClear: false,
                width: "100%"
            });
        });
        
        $.getJSON("api/shipment?action=5&po="+poid+"&col=blNo", function (list) {
            $("#BLNo").select2({
                data: list,
                placeholder: "Select BL number",
                allowClear: false,
                width: "100%"
            });
        });
        
        $.get("api/shipment?action=1&po="+poid, function (data) {
            var row = JSON.parse(data);
            $("#LcNo").val(row['lcno']);
            $("#LcValue").val(commaSeperatedFormat(row['lcvalue']));
            GetLCOCapex();
            GetInshuranceCapex();
        });
        
        $("#MAWBNo, #HAWBNo, #BLNo").change(function(e){
            GetCIAmount();
            GetGPRefNo();
        });
        
        
        
        $("#refreshCostData_btn").click(function(e){
            e.preventDefault();
            var propcost = parseToCurrency($("#proportionateCost").val());
            LoadCostUpdateData(poid, propcost);
        });
        
        
        
    });
    */
    /*$.getJSON("api/purchaseorder?action=3", function (list) {
        $("#PoNo").select2({
            data: list,
            placeholder: "Search PO Number",
            allowClear: false,
            width: "100%"
        });
        if(poid!=""){
            $("#PoNo").val(poid).change();
        }
    });*/
    $("#cnfNetPayment").keyup(function (e) {
        CalculateCost();
    });
    $(".curnum").blur(function (e) {
        $(this).val(commaSeperatedFormat($(this).val()));
    });

    $("#save_btn").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want submit?', function (e) {
                if (e) {
                    $("#save_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/average-cost-fin",
                        data: $('#averagecost-form').serialize() + "&userAction=1",
                        cache: false,
                        success: function (response) {
                            $("#save_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res["message"]);
                                    //window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
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

    $("#notify_btn").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want send notification?', function (e) {
                if (e) {
                    $("#notify_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/average-cost-fin",
                        data: $('#updateNotify-form').serialize() + "&userAction=2&refId=" + $("#refId").val() + "&pono=" + poid + "&shipno=" + shipno,
                        cache: false,
                        success: function (response) {
                            $("#notify_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res["message"]);
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
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

});

function validate(){
    return true;
}

/*function GetCIAmount(){
    var mawb = $("#MAWBNo").val();
    var hawb = $("#HAWBNo").val();
    var bl = $("#BLNo").val();
    
    //alert("api/shipment?action=6&po="+poid+"&mawb="+mawb+"&hawb="+hawb+"&bl="+bl);
    
    $.get("api/shipment?action=6&po="+poid+"&mawb="+mawb+"&hawb="+hawb+"&bl="+bl, function (data) {
        
        var row = JSON.parse(data);
        $("#CIValue").val(commaSeperatedFormat(row['ciAmount']));
        if(row['bankChargeCapex']!=null){
            $("#lcOpenCharge1").val(commaSeperatedFormat(row['bankChargeCapex']));
        }
        if(row['insuranceCapex']!=null){
            $("#insPremium1").val(commaSeperatedFormat(row['insuranceCapex']));
        }
        if(row['cnfNetPayment']!=null){
            $("#cnfNetPayment").val(commaSeperatedFormat(row['cnfNetPayment']));
        }
        if(row['proportionateCost']!=null){
            $("#proportionateCost").val(commaSeperatedFormat(row['proportionateCost']));
        }
        CalculateCost();
    });
}
*/
/*function GetGPRefNo(){
    
    var lcno = $("#LcNo").val();
    var mawb = $("#MAWBNo").val();
    var hawb = $("#HAWBNo").val();
    var bl = $("#BLNo").val();
    
    $.get("api/custom-duty?action=3&lc="+lcno+"&mawb="+mawb+"&hawb="+hawb+"&bl="+bl, function (data) {
        var row = JSON.parse(data);
        $("#gpRefNo").val(row['gpRefNum']);
        $("#customDuty").val(commaSeperatedFormat(row['customDuty']));
        CalculateCost();
    });
}*/

function GetLCOCapex(){
    
    var lcno = $("#LcNo").val();
    
    $.get("api/lc-opening-bank-charges?action=1&lc="+lcno, function (data) {
        var row = JSON.parse(data);
        $("#lcOpenCharge").val(commaSeperatedFormat(row['capex']));
        CalculateCost();
    });
}

function GetInshuranceCapex(){
    
    $.get("api/marine-insurance?action=2&po="+poid, function (data) {
        var row = JSON.parse(data);
        $("#insPremium").val(commaSeperatedFormat(row['capex']));
        CalculateCost();
    });
}

function GetCustomDuty(){
    //alert("api/custom-duty?action=4&po="+poid+"&shipno="+shipno);
    $.get("api/custom-duty?action=4&po="+poid+"&shipno="+shipno, function (data) {
        //alert(data);
        if($.trim(data)!='null'){
            var row = JSON.parse(data);
            $("#customDuty").val(commaSeperatedFormat(row['customDuty']));
        }else{
            $("#customDuty").val(commaSeperatedFormat('0'));
        }
        CalculateCost();
    });
}

function CalculateCost(){
    var lcoCharge = parseToCurrency($("#lcOpenCharge").val()),
        lcval = parseToCurrency($("#LcValue").val()),
        cival = parseToCurrency($("#CIValue").val()),
        insPrem = parseToCurrency($("#insPremium").val()),
        cdCapex = parseToCurrency($("#customDuty").val()),
        cnfBill = parseToCurrency($("#cnfNetPayment").val());
    
    var lcoChargeProp = (lcoCharge/lcval)*cival;
    var insPremProp = (insPrem/lcval)*cival;
    var totalPropCost = lcoChargeProp + insPremProp + cdCapex + cnfBill;
    
    $("#lcOpenCharge1").val(commaSeperatedFormat(lcoChargeProp.toFixed(2)));
    $("#insPremium1").val(commaSeperatedFormat(insPremProp.toFixed(2)));

    $("#proportionateCost").val(commaSeperatedFormat(totalPropCost.toFixed(2)));
    
    // load datatables data
    if($("#proportionateCost").val()!=""){
        var propcost = parseToCurrency($("#proportionateCost").val());
        if(propcost>0){
            LoadCostUpdateData(poid, propcost);
        }
    }
}

function LoadCostUpdateData(poid, propcost){

    if(!dtInit){
        $('#csvAvgCost').DataTable( {
            "ajax": 'api/average-cost-fin?action=1&po='+poid+"&shipno="+shipno+"&propcost="+propcost,
            "global": false,
            "columns": [
    			{ "data": "pono" },
    			{ "data": "ipcno" },
    			{ "data": "poline", "class": "text-center" },
    			{ "data": "item" },
    			{ "data": "desc" },
    			{ "data": "qty", "class": "text-center" },
    			{ "data": "uom" },
    			{ "data": "price", "class": "text-right" },
    			{ "data": "amount", "class": "text-right" },
    			{ "data": "averagecost", "class": "text-right" },
    			{ "data": "curr" }],
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
                
                totalCost = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                usedCurrency = api
                    .column( 10 )
                    .data()
                    .reduce( function(a,b){
                        return b;
                    },0);
                
                // Update footer
                $( api.column( 8 ).footer() ).html(
                    commaSeperatedFormat(totalAmount.toFixed(2))
                );
                $( api.column( 9 ).footer() ).html(
                    commaSeperatedFormat(totalCost.toFixed(2))
                );
                $( api.column( 10 ).footer() ).html(
                    usedCurrency
                );
            },
            dom: '',
            "autoWidth": false,
            "paging": false,
            "bSort": false
        }, initTable());
        
        var table = $('#csvAvgCost').DataTable();
 
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: ['excelHtml5']
        }).container().appendTo($('#exportBtn'));
        
    } else {
        var dtable = $('#csvAvgCost').dataTable();
        dtable.api().ajax.url('api/average-cost-fin?action=1&po='+poid+"&shipno="+shipno+"&propcost="+propcost).load();
    }
}


function initTable(){
    dtInit = true;
}

/*$(function () {

    var button = $('#importCSVFile_btn'), interval;
    var txtbox = $('#importCSVFile');

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
});*/
