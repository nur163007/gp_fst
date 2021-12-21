/*	
	Author: Shohel Iqbal
	Copyright: 01.2016
	Code fridged on:
*/
var dtInit = false;
var dtInitLCWise = false;
var barChart1Init = false, barChart2Init = false;

$(document).ready(function (e) {

    if ($("#currentRole").val() == const_role_Buyer) {
        var pendingCurUser = false;
        $.get("api/purchaseorder?action=8", function (data) {
            //alert(data[0][0]);
            var list = JSON.parse(data);
            var lis = '<li role="presentation"><a class="small" href="javascript:filterMyPendings(\'All\')" role="menuitem">All</a></li>';
            for (var i = 0; i < list.length; i++) {
                if (list[i]['poCount'] != 0) {
                    lis += '<li role="presentation"><a class="small" href="javascript:filterMyPendings(\'' + list[i]['username'].trim() + '\')" role="menuitem">' + list[i]['fullname'].trim() + ' <span class="badge badge-danger">' + list[i]['poCount'] + '</span></a></li>';
                } else {
                    lis += '<li role="presentation"><a class="small" href="javascript:filterMyPendings(\'' + list[i]['username'].trim() + '\')" role="menuitem">' + list[i]['fullname'].trim() + '</a></li>';
                }
                if ($("#currentBuyer").val() == list[i]['username'].trim() && list[i]['poCount'] != "0") {
                    //alert('SDFSF');
                    pendingCurUser = true;
                }
            }
            $("#buyerList").html(lis);
            if (pendingCurUser == true) {
                filterMyPendings($("#currentBuyer").val())
            } else {
                filterMyPendings("All");
            }
        });
    }

    var SCMFeatureExcludeRole = [const_role_Supplier,
        const_role_lc_bank,
        const_role_bank_fx,
        const_role_insurance_company,
        const_role_cnf_agent,
        const_role_foreign_strategy,
        const_role_foreign_payment_team
    ];
    //if ($("#currentRole").val() != const_role_Supplier) {
    // alert($("#currentRole").val());
    if(jQuery.inArray(parseInt($("#currentRole").val()), SCMFeatureExcludeRole) == -1) {
        $(function () {
            start = moment().subtract(1, 'week').startOf('week');
            end = moment().subtract(1, 'week').endOf('week').add(1, 'day');

            function cb(start, end) {
                $('#reportrange span').html(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
                $("#startDate").val(start.format('YYYY-MM-DD'));
                $("#endDate").val(end.format('YYYY-MM-DD'));
                refreshWeeklyData($("#startDate").val(), $("#endDate").val());
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week').add(1, 'day')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

        });
        //getLCWiseActivities();
    }//End role based condition

    var dataTable = $('#dtMyInbox').dataTable();
    $("#dtMyInbox_filter_new").keyup(function () {
        dataTable.fnFilter(this.value);
    });

});

//`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `XRefID`, `ActionBy`, `ActionByRole`, `ActionOn`, `ActionFrom`
$('#dtMyInbox').dataTable( {
	"ajax": "api/dashboard?action=1&my=true",
	"columns": [
		{ "data": "ID", "visible": false },
		{ "data": "RefID", "visible": false },
		{ "data": null, "class": "padding-5",
			"render": function(data, type, full) {
				if(parseInt(full['marge'])==0 || parseInt(full['marge'])==2){
					if(full["shipNo"]==""){
						return "<a href=\""+full["TargetForm"]+"?po="+full["PO"]+"&ref="+full["ID"]+"\">"+full["PO"]+"</a>";
					}else{
						/*return "<a href=\""+full["TargetForm"]+"?po="+full["PO"]+"&ship="+full["shipNo"]+"&ref="+full["ID"]+"\">"+full["PO"]+"<br />Ship # "+full["shipNo"]+"</a>";*/
                        if (full["eaRefNo"] == "")
                        {
                            return `<a href="${full["TargetForm"]}?po=${full["PO"]}&ship=${full["shipNo"]}&ref=${full["ID"]}">${full["PO"]}<br />Ship # ${full["shipNo"]}</a>`;
                        }else {
                            return `<a href="${full["TargetForm"]}?po=${full["PO"]}&ship=${full["shipNo"]}&ref=${full["ID"]}">${full["PO"]}<br />Ship # ${full["shipNo"]}<br />${full["eaRefNo"]}</a>`;
                        }
					}
				}else{
					return "<a href=\"javascript:void(0);\" onclick=\"javascript:alertify.alert('More feedback required to proceed.');\">"+full["PO"]+"</a>";
				}
			  }
		},
		{ "data": null, "class": "text-left padding-5",
			"render": function(data, type, full) {
				if(full["ID"]!="&nbsp;"){
					if(full["lastStatus"]=="-1") {
                        return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-danger">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
                    }else{
						return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-default">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
					}
				} else { return ""; }
			  }
		},
		{ "data": "stage"},
		{ "data": "Buyer", "class": "padding-5" },
		{ "data": "ActionOn", "visible": false },
        /*{ "data": null, "class": "padding-5 text-right",
            "render": function(data, type, full) {
                return full["Buyer"] + "<br/><span class='text-"+full["criticality"]+"'>for " + full["pendingFor"] + " days<span>";
            }
        }*/
        ],
	"order": [[ 6, "desc" ]],
	"sDom": 'frtip',
	"bSort": true
});

$('#dtOtherInbox').dataTable( {
	"ajax": "api/dashboard?action=1&my=false",
	"columns": [
		{ "data": "ID", "visible": false },
		{ "data": "RefID", "visible": false },
		/*{ "data": null, "class": "padding-5",
			"render": function(data, type, full) {
				return ""+full["PO"]+"";
			  }
		},*/
		{ "data": null, "class": "padding-5",
			"render": function(data, type, full) {
				if(parseInt(full['marge'])==0 || parseInt(full['marge'])==2){
					if(full["shipNo"]==""){
						return "<a href=\"view-po?po="+full["PO"]+"&ref="+full["ID"]+"\">"+full["PO"]+"</a>";
					}else{
						return "<a href=\"view-po?po="+full["PO"]+"&ship="+full["shipNo"]+"&ref="+full["ID"]+"\">"+full["PO"]+"<br />Ship # "+full["shipNo"]+"</a>";
					}
				}else{
					return "<a href=\"javascript:void(0);\" onclick=\"javascript:alertify.alert('More feedback required to proceed.');\">"+full["PO"]+"</a>";
				}
			  }
		},
		{ "data": null, "sortable": false, "class": "text-left padding-5",
			"render": function(data, type, full) {
				if(full["ID"]!="&nbsp;"){
					if(full["lastStatus"]=="-1") {
						return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-danger">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
					}else{
						return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-default">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
					}
				} else { return ""; }
			  }
		},
		{ "data": "stage"},
		{ "data": "Buyer", "class": "padding-5" },
		{ "data": null, "class": "text-left padding-5",
			"render": function(data, type, full){
				if(full["ActionPendingTo"] == const_role_Supplier ){
					return full["PendingToRoleName"] + "<br />"+ full["CoName"];
				} else {
					return full["PendingToRoleName"];
				}
			}
		},
        { "data": "ActionOn", "visible": false },
	],
	"order": [[ 7, "desc" ]],
	"sDom": 'ftip',
	"bSort": true
});

function filterMyPendings(buyersName) {

    $("#buyersList").html(buyersName + ' <span class="caret"></span>');
    oTable = $('#dtMyInbox').dataTable();

    if (buyersName == 'All') {
        oTable.fnFilter("", 5);
    } else {
        oTable.fnFilter(buyersName, 5);
    }
    $("#myPendingsBlock").css("height", "Auto");
}

function getBarsData(startDate, endDate) {

    //$("#weekNumber").html('Week ' + week + ' <span class="icon wb-chevron-down-mini" aria-hidden="true"></span>');

	//alert("api/dashboard?action=3&start=" + startDate + "&end=" + endDate);
    $.get("api/dashboard?action=3&start=" + startDate + "&end=" + endDate, function (result) {
        //alert(result);
        var row = JSON.parse(result);

        //----- getting suggested Max level ---------------
        var cols = [];
        var maxVal = 0;
        for (var col in row[0]) {
            cols.push(col);
        }

        for (var i = 0; i < row.length; i++) {
            for (var c = 1; c < cols.length; c++) {
                //alert(row[i][cols[c]]);
                if (parseFloat(row[i][cols[c]]) > maxVal) {
                    maxVal = row[i][cols[c]];
                }
            }
        }
        maxVal = parseFloat(maxVal) + 2;
        //----- end getting suggested Max level ---------------

        //----- start bar chart data preparation --------------
        var barChartData = {
            labels: ['PO', 'PI', 'BTRC', 'LC', 'Invoice'],
            datasets: [
                {
                    type: 'line',
                    label: 'Activities in KPI',
                    data: [row[1]['PO'], row[1]['PI'], row[1]['BTRC'], row[1]['LC'], row[1]['Invoice']],
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                },
                {
                    type: 'bar',
                    label: 'Activities',
                    data: [row[0]['PO'], row[0]['PI'], row[0]['BTRC'], row[0]['LC'], row[0]['Invoice']],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }
            ]
        };

        //----- end bar chart data preparation ---------------

        if (barChart1Init == true) {
            window.barChart1.destroy();
        }
        var ctx = document.getElementById("POOperationChartjsBar");
        window.barChart1 = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            suggestedMax: maxVal,
                            stepSize: 1
                        }
                    }]
                }
            }
        }, barChart1Init = true);

        // Define a plugin to provide data labels
        Chart.plugins.register({
            afterDatasetsDraw: function (chartInstance, easing) {
                // To only draw at the end of animation, check for easing === 1
                var ctx = chartInstance.chart.ctx;

                chartInstance.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function (element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(98,168,234)';

                            var fontSize = 10;
                            var fontStyle = 'normal';
                            var fontFamily = 'Arial';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        });

    });
}

function getBarsDataBuyerWise(startDate, endDate) {

    //$("#weekNumber").html('Week ' + week + ' <span class="icon wb-chevron-down-mini" aria-hidden="true"></span>');

    $.get("api/dashboard?action=6&start=" + startDate + "&end=" + endDate, function (result) {
        //alert(result);
        var row = JSON.parse(result);

        //----- getting suggested Max level ---------------
        // var cols = [];
        var maxVal = 0;

        var aBuyer = [], aPO = [], aPI = [], aBTRC = [], aLC = [], aInvoice = [];

        /*for(var col in row[0]) {
            cols.push(col);
        }*/

        for(var i = 0; i < row.length; i++) {
			/*for(var c = 1; c < cols.length; c++) {
			 if(row[i][cols[c]]>maxVal) {
			 maxVal = row[i][cols[c]];
			 }
			 }*/
            //alert(row[i]['Buyer']);
            aBuyer.push(row[i]['username']);
            aPO.push(row[i]['PO']);
            if (row[i]['PO'] > maxVal) {
                maxVal = row[i]['PO'];
            }
            aPI.push(row[i]['PI']);
            if (row[i]['PI'] > maxVal) {
                maxVal = row[i]['PI'];
            }
            aBTRC.push(row[i]['BTRC']);
            if (row[i]['BTRC'] > maxVal) {
                maxVal = row[i]['BTRC'];
            }
            aLC.push(row[i]['LC']);
            if (row[i]['LC'] > maxVal) {
                maxVal = row[i]['LC'];
            }
            aInvoice.push(row[i]['GIT']);
            if (row[i]['GIT'] > maxVal) {
                maxVal = row[i]['GIT'];
            }
        }
// alert(aBuyer);

        maxVal = parseFloat(maxVal)+1;
        //----- end getting suggested Max level ---------------

        if (barChart2Init == true) {
            window.barChart2.destroy();
        }
        var ctx = document.getElementById("POOperationChartjsBarBuyerWise");
        window.barChart2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: aBuyer,
                datasets: [
                    {
                        type: 'bar',
                        label: 'PO',
                        data: aPO,
                        backgroundColor: 'rgba(236, 0, 140, 0.3)',
                        borderColor: 'rgba(54, 162, 235, .8)',
                        borderWidth: 1,
                    },
                    {
                        type: 'bar',
                        label: 'PI',
                        data: aPI,
                        backgroundColor: 'rgba(0, 166, 81, 0.3)',
                        borderColor: 'rgba(54, 162, 235, .8)',
                        borderWidth: 1,
                    },
                    {
                        type: 'bar',
                        label: 'BTRC',
                        data: aBTRC,
                        backgroundColor: 'rgba(146, 39, 143, 0.3)',
                        borderColor: 'rgba(54, 162, 235, .8)',
                        borderWidth: 1,
                    },
                    {
                        type: 'bar',
                        label: 'LC',
                        data: aLC,
                        backgroundColor: 'rgba(255, 222, 23, 0.3)',
                        borderColor: 'rgba(54, 162, 235, .8)',
                        borderWidth: 1,
                    },
                    {
                        type: 'bar',
                        label: 'Invoice',
                        data: aInvoice,
                        backgroundColor: 'rgba(237, 28, 36, 0.3)',
                        borderColor: 'rgba(237, 28, 36, .8)',
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom',
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            suggestedMax: maxVal,
                            stepSize: 1
                        }
                    }]
                }
            }
        }, barChart2Init = true);
        /*// Define a plugin to provide data labels
        Chart.plugins.register({
            afterDatasetsDraw: function(chartInstance, easing) {
                // To only draw at the end of animation, check for easing === 1
                var ctx = chartInstance.chart.ctx;

                chartInstance.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(98,168,234)';

                            var fontSize = 10;
                            var fontStyle = 'normal';
                            var fontFamily = 'Arial';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        });*/
    });
}

function getBuyerWiseActivities(startDate, endDate){
	if(!dtInit) {
		$('#dtBuyerWiseAct').dataTable({
			"ajax": "api/dashboard?action=4&start=" + startDate + "&end=" + endDate,
			"columns": [
				{"data": "Buyer"},
				{"data": "PO", "class": "text-center"},
				{"data": "PI", "class": "text-center"},
				{"data": "BTRC", "class": "text-center"},
				{"data": "LC", "class": "text-center"},
				{"data": "GIT", "class": "text-center"}
			],
			"sDom": 'ti',
			"bSort": false
		}, initTable());
	}else{
		var dtable = $('#dtBuyerWiseAct').dataTable();
		dtable.api().ajax.url("api/dashboard?action=4&start=" + startDate + "&end=" + endDate).load();
	}
}

function getLCWiseActivities(){
	if(!dtInitLCWise) {
		$('#dtLCWiseAct').dataTable({
			"ajax": "api/dashboard?action=5",
			"columns": [
				{"data": "PO"},
				{ "data": null, "class": "text-left padding-5",
					"render": function(data, type, full){
						return full["ActionDone"] + "<br/>"+ "on: "+ full["ActionOn"];
					}
				},
				{ "data": null, "class": "text-left padding-5",
					"render": function(data, type, full){
						return full["ActionPending"] + " for: "+ full["pendingFor"] + " days<br/>on: "+ full["ActionPendingToRole"];
					}
				}
			],
			"sDom": 'ftip',
			"bSort": false
		}, initTableLCWise());
	}else{
		var dtable = $('#dtLCWiseAct').dataTable();
		dtable.api().ajax.url("api/dashboard?action=5").load();
	}
}

function initTable(){
	dtInit = true;
}

function initTableLCWise(){
	dtInitLCWise = true;
}
function refreshWeeklyData(startDate, endDate){
	getBarsData(startDate, endDate);
    //getBarsDataBuyerWise(startDate, endDate);
	//getBuyerWiseActivities(startDate, endDate);
}