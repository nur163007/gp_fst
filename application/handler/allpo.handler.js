/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
//api/allpo?action=
$(document).ready(function() {

	$('#dtAllPo').dataTable({
		"ajax": "api/allpo?action=1",
		"columnDefs": [
			{targets: [10, 11], visible: $("#loginRole").val() === '1'}
		],
		"columns": [
			{
				"data": null, "sortable": false, "class": "text-left padding-5",
				"render": function (data, type, full) {
					if (full["shipNo"] == "") {
						return '<a href="view-po' + '?po=' + full['poid'] + '&ref=' + full['refId'] + '" target="_blank">' + full['poid'] + '</a>';
					} else {
						return '<a href="view-po' + '?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '" target="_blank">' + full['poid'] + '<br />Ship # ' + full['shipNo'] + '</a>';
					}
				}
			},
			{"data": "povalue", "class": "padding-5 text-right"},
			{"data": "currency", "class": "padding-5"},
			{"data": "buyer", "class": "padding-5"},
			{"data": "supplier", "class": "padding-5"},
			{"data": "podesc", "sortable": false, "class": "padding-5"},
			{"data": "lcNo", "class": "padding-5"},
			{"data": "gprefno", "class": "padding-5"},
			{
				"data": null, "class": "padding-5",
				"render": function (data, type, full) {
					return '<u>' + full['status'] + '</u><br/><span class="text-success">Pending: ' + full['pendingto'] + '</span>'
				}
			},
			{
				"data": null, "class": "padding-5 text-left",
				"render": function (data, type, full) {
					var moduleLink = "";
					if (full['lcRequest'] == 1) {
						moduleLink = '<a href="lc-request?po=' + full['poid'] + '&ref=' + full['refId'] + '">LcRequest</a>';
					}
					if (full['lcOpening'] == 1) {
						moduleLink = '<a href="lc-opening?po=' + full['poid'] + '&ref=' + full['refId'] + '">LcOpening</a>';
					}
					if (full['lcAmendment'] == 1) {
						moduleLink += '<br /><a href="amendment-request?po=' + full['poid'] + '&ref=' + full['refId'] + '">Amendment</a>';
					}
					if (full['endorsedDoc'] == 1) {
						moduleLink += '<br /><a href="endorsement?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '">EndorsedDoc</a>';
					}
					if (full['originalDoc'] == 1) {
						moduleLink += '<br /><a href="original-doc?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '">OriginalDoc</a>';
					}
					if (full['customDuty'] == 1) {
						moduleLink += '<br /><a href="custom-duty?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '">CustomDuty</a>';
					}
					if (full['avgCostUpdate'] == 1) {
						moduleLink += '<br /><a href="average-cost-fin?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '">AvgCostUpdate</a>';
					}
					if (full['eaInputs'] == 1) {
						moduleLink += '<br /><a href="ea-inputs?po=' + full['poid'] + '&ship=' + full["shipNo"] + '&ref=' + full['refId'] + '">EAInputs</a>';
					}
					return moduleLink;
				}
			},
			{"data": "remarks", "class": "padding-5"},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return `<button class="btn btn-sm btn-icon btn-flat btn-danger" data-target="#closePOForm" data-toggle="modal" onclick="getPoNo('${full['poid']}', ${full["shipNo"]})"><i class="icon wb-close" aria-hidden="true"></i></button>`;
				}
			}],
		"sDom": 'frtip',
		"bAutoWidth": true,
		"bSort": false
	});


	/*CLOSE PO*/
	$("#btnClosePo").click(function (e) {
		e.preventDefault();
		if (validate() === true) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/allpo",
				data: $('#form-close-po').serialize(),
				cache: false,
				success: function (response) {
					button.disabled = false;
					//alertify.alert(response);
					try {
						var res = JSON.parse(response);
						if (res['status'] == 1) {
							resetForm();
							$('#closePOForm').modal('hide');
							var dtable = $('#dtAllPo').dataTable();
							alertify.success(res['message']);
							dtable.api().ajax.reload(null, false);
							return true;
						} else {
							alertify.error("FAILED!");
							return false;
						}
					} catch (err) {
						alertify.error(response + ' Failed to process the request.', 20);
						return false;
					}
				}
			});
		} else {
			return false;
		}
	});
});

function getPoNo(poNo, shipNo = '') {
    $('#poNo').val(poNo);
    $('#shipNo').val(shipNo);
}

function validate() {
	if(!$("#action_type").val()){
		$('#action_type').select2('open');
		alertify.error("Please select a type");
		return false;
	}
    if($('#closeJstifctn').val() === '' || $('#closeJstifctn').val() === null){
        $('#closeJstifctn').focus();
        alertify.error("Please write PO closing justification.");
        return false;
    }
    return true;
}
function resetForm() {
	$("#form-close-po").trigger("reset");
	$("#poNo").val('');
	$("#shipNo").val('');
	$("#action_type").change();

}