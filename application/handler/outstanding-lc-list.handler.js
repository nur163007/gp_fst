/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var dtInit = false, dtInitSum = false, showSum = 0, summaryBy = '';

$(document).ready(function() {
    
    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
        $("#bank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: true,
            width: "100%"
        });
    });
    
    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "Select a Supplier",
            allowClear: true,
            width: "100%"
        });
    });
    
    $("#expiryStatus").select2({
        minimumResultsForSearch: Infinity,
        placeholder: "Expiry Status",
        allowClear: true,
        width: "100%"
    });
    
    /*$("#bank, #supplier, #expiryStatus").on("select2:select", function (e) {
        refreshNonSumReport();
    });*/
    
    $("#bank, #supplier, #expiryStatus").change(function(e){
        refreshNonSumReport();
    });
    
    $('#isSummary').on('ifClicked', function(event){
        if($('#isSummary').parent().hasClass('checked')){
            showSum = 0;
            $("#nonSummaryFilter").removeClass("hidden").show();
            $("#summaryFilter").addClass("hidden").hide();

            $("#dtOutstandingSummary").addClass("hidden").hide();
            $("#dtOutstanding").removeClass("hidden").show();
            refreshNonSumReport();
        }else{
            showSum = 1;
            $("#summaryFilter").removeClass("hidden").show();
            $("#nonSummaryFilter").addClass("hidden").hide();
            
            $("#dtOutstandingSummary").removeClass("hidden").show();
            $("#dtOutstanding").addClass("hidden").hide();
            //refreshSumReport();
        }
        //refreshNonSumReport();
    });
    
    $('#summaryByBank, #summaryBySupplier').on('ifChecked', function(event){
        summaryBy = $('input:radio[name=summaryBy]:checked').val();
        $("#sumColumnName").html(summaryBy);
        refreshSumReport();
    });
    
    refreshNonSumReport();
    
});

function refreshNonSumReport(){
    if(!dtInit){
        var bank = $("#bank").val();
        var supplier = $("#supplier").val();
        var expiry = $("#expiryStatus").val();
        //alert('api/outstanding-lc-list?action=1&bank='+bank+"&supplier="+supplier+"&expiry="+expiry+"&isSum="+showSum);
        $('#dtOutstanding').DataTable({
            
            "ajax": 'api/outstanding-lc-list?action=1&bank='+bank+"&supplier="+supplier+"&expiry="+expiry+"&isSum="+showSum,
            "columns": [
    			{ "data": "lcno" },
    			{ "data": "bank" },
    			{ "data": "pono" },
    			{ "data": "supplier" },
    			{ "data": "lcdesc" },
    			{ "data": "lcissuedate" },
    			{ "data": "currency" },
    			{ "data": "lcvalue", "visible": false },
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return commaSeperatedFormat(full["lcvalue"]);
    			}},
    			{ "data": "endValue", "visible": false },
                { "data": null, "class":"text-right", "render": function(data, type, full){
    			     return '<a href="javascript:void(0)" data-target="#formEndorsedData" data-toggle="modal">'+commaSeperatedFormat(full['endValue'])+'</a>';
    			}},
                { "data": "totalPayment", "visible": false },
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return '<a href="javascript:void(0)" data-target="#formPaymentData" data-toggle="modal">'+commaSeperatedFormat(full['totalPayment'])+'</a>';
    			}},
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     //alert(parseFloat(full['lcvalue'])-parseFloat(full['totalPayment']));
    			     return commaSeperatedFormat(parseFloat(full['lcvalue'])-parseFloat(full['totalPayment']));
    			     //return parseFloat(full['lcvalue'])-parseFloat(full['totalPayment']);
    			}},
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return commaSeperatedFormat(full['endValue']-full['totalPayment']);
    			}},
    			{ "data": null, "class": "text-center",
    				"render": function(data, type, full) {
    				    return '<span class="text-'+full['status']+'"><strong>'+full['dayExpiry']+'</strong></span>';
    				    //return '<i class="icon wb-large-point font-size-40 text-'+full['status']+'"></i>';
    				}
    			}],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
                
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                var curVal = function(str){
                    alert(str);
                    if(str!=""){
                        str = str.replace(/,/g,"");
                        return parseFloat(str);
                    }else{
                        return 0;
                    }
                };
                //alert(data);     
                // Total over all pages
                totalLcValue = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalEndValue = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalPayment = api
                    .column( 11 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalOSOnLCValue = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalOSEndValue = api
                    .column( 14 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
                // Update footer
                $( api.column( 8 ).footer() ).html(
                    commaSeperatedFormat(totalLcValue.toFixed(2))
                );
                $( api.column( 10 ).footer() ).html(
                    commaSeperatedFormat(totalEndValue.toFixed(2))
                );
                $( api.column( 12 ).footer() ).html(
                    commaSeperatedFormat(totalPayment.toFixed(2))
                );
                $( api.column( 13 ).footer() ).html(
                    commaSeperatedFormat(totalOSOnLCValue.toFixed(2))
                );
                $( api.column( 14 ).footer() ).html(
                    commaSeperatedFormat(totalOSEndValue.toFixed(2))
                );
            },
    		"sDom": '',
            "autoWidth": false,
            "paging": false,
            "bSort": false,
            buttons: [
               'copyHtml5',
               'excelHtml5',
               'csvHtml5',
               'pdfHtml5'
            ]
        }, initTable());
    } else{
        var bank = $("#bank").val();
        var supplier = $("#supplier").val();
        var expiry = $("#expiryStatus").val();
        //alert('api/outstanding-lc-list?action=1&bank='+bank+"&supplier="+supplier+"&expiry="+expiry+"&isSum="+showSum);
        var dtable = $('#dtOutstanding').dataTable();
        dtable.api().ajax.url('api/outstanding-lc-list?action=1&bank='+bank+"&supplier="+supplier+"&expiry="+expiry+"&isSum="+showSum).load();
    }
}

function initTable(){
    dtInit = true;
}


function refreshSumReport(){
    
    if(!dtInitSum){
        //alert('api/outstanding-lc-list?action=1&isSum='+showSum+'&sumBy='+summaryBy);
        $('#dtOutstandingSummary').DataTable({
            
            "ajax": 'api/outstanding-lc-list?action=1&isSum='+showSum+'&sumBy='+summaryBy,
            "columns": [
    			{ "data": "sumByColumn" },
    			{ "data": "lcCount", "class":"text-right" },
    			{ "data": "lcValue", "class":"text-right" },
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return '<a href="javascript:void(0)" data-target="#formEndorsedData" data-toggle="modal">'+commaSeperatedFormat(full['endValue'])+'</a>';
    			}},
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return '<a href="javascript:void(0)" data-target="#formPaymentData" data-toggle="modal">'+commaSeperatedFormat(full['totalPayment'])+'</a>';
    			}},
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return commaSeperatedFormat(parseFloat(full['lcValue'])-parseFloat(full['totalPayment']));
    			}},
    			{ "data": null, "class":"text-right", "render": function(data, type, full){
    			     return commaSeperatedFormat(full['endValue']-full['totalPayment']);
    			}},
    			{ "data": "expired", "class":"text-center" },
    			{ "data": "live", "class":"text-center" }
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
                var curVal = function(str){
                    alert(str);
                    if(str!=""){
                        str = str.replace(/,/g,"");
                        return parseFloat(str);
                    }else{
                        return 0;
                    }
                };
                //alert(data);     
                // Total over all pages
                totalLcCount = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalLcValue = api
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalEndValue = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalPayment = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalOSOnLCValue = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalOSEndValue = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalExpired = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                totalLive = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
    
                // Update footer
                $( api.column( 1 ).footer() ).html(
                    totalLcCount
                );
                $( api.column( 2 ).footer() ).html(
                    commaSeperatedFormat(totalLcValue.toFixed(2))
                );
                $( api.column( 3 ).footer() ).html(
                    commaSeperatedFormat(totalEndValue.toFixed(2))
                );
                $( api.column( 4 ).footer() ).html(
                    commaSeperatedFormat(totalPayment.toFixed(2))
                );
                $( api.column( 5 ).footer() ).html(
                    commaSeperatedFormat(totalOSOnLCValue.toFixed(2))
                );
                $( api.column( 6 ).footer() ).html(
                    commaSeperatedFormat(totalOSEndValue.toFixed(2))
                );
                $( api.column( 7 ).footer() ).html(
                    totalExpired
                );
                $( api.column( 8 ).footer() ).html(
                    totalLive
                );
            },
    		"sDom": '',
            "autoWidth": false,
            "paging": false,
            "bSort": false,
            buttons: [
               'copyHtml5',
               'excelHtml5',
               'csvHtml5',
               'pdfHtml5'
            ]
        }, initTableSum());
    } else{
        var dtable = $('#dtOutstandingSummary').dataTable();
        dtable.api().ajax.url('api/outstanding-lc-list?action=1&isSum='+showSum+'&sumBy='+summaryBy).load();
    }
}

function initTableSum(){
    dtInitSum = true;
}