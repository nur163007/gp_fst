/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
*/

$(document).ready(function() {

    $('#dtAllChargeEntry').dataTable( {
		"ajax": "api/charge-entry?action=2",
		"columns": [
			{ "data": "id", "visible": false },
			{ "data": "chargeDate" },
			{ "data": "chargeType" },
			{ "data": "amount" },
			{ "data": "vat" },
			{ "data": "vatRebate" },
			{ "data": "totalCharge" },
			{ "data": "relatedTo" },
			{ "data": "remarks" },
			{ "data": null, "sortable": false, "class": "text-center",
				"render": function(data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#popChargeEntry" data-toggle="modal" data-toggle="Edit Credit Report" data-original-title="Edit Credit Report" onclick="openCreditReport(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
					'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="deleteCharge(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				  }
			}],
		"sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": true
	});

    $("#btnChargeEntrySubmit").click(function(e) {
		/*alert('sdfd');*/
		e.preventDefault();
		if (validate() === true) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/charge-entry",
				data: $('#formChargeEntry').serialize(),
				cache: false,
				success: function (html) {
					button.disabled = false;
					// alert(html);
					var res = JSON.parse(html);
					if (res['status'] == 1) {
						ResetForm();
						$('#popChargeEntry').modal('hide');
						var dtable = $('#dtAllChargeEntry').dataTable();
						alertify.success("Saved SUCCESSFULLY!");
						dtable.api().ajax.reload();
						return true;
					} else {
						alertify.error("FAILED!");
						return false;
					}
				}
			});
		} else {
			return false;
		}
	});
    
    $("#amount, #vatPercentage, #vatRebatePercentage").keyup(function(){
        
        var amount = parseToCurrency($("#amount").val()),
            vatPercentage = parseToCurrency($("#vatPercentage").val()),
            vatOnCharges = 0, vatRebate = 0, totalCharge = 0,
            vatRebatePercentage = parseToCurrency($("#vatRebatePercentage").val());
        
        vatOnCharges = (amount * vatPercentage)/100;
        $("#vatOnCharges").val(commaSeperatedFormat(vatOnCharges));
        
        vatRebate = (vatOnCharges * vatRebatePercentage)/100;
        $("#vatRebate").val(commaSeperatedFormat(vatRebate));
        
        totalCharge = amount + vatOnCharges;
        $("#totalCharge").val(commaSeperatedFormat(totalCharge));
        //alert('sdfs');
    });

});

$.getJSON("api/company?action=4", function (list) {
    $("#supplier").select2({
        data: list,
        placeholder: "select a company",
        allowClear: true,
        width: "100%"
    });
});

$.getJSON("api/category?action=4&id=57", function (list) {
    $("#chargeType").select2({
        data: list,
        minimumResultsForSearch: Infinity,
        placeholder: "select charge type",
        allowClear: false,
        width: "100%"
    });
});


$.getJSON("api/category?action=4&id=58", function (list) {
    $("#relatedTo").select2({
        data: list,
        minimumResultsForSearch: Infinity,
        placeholder: "select charge related to",
        allowClear: false,
        width: "100%"
    });
});

function validate()
{
    if($("#chargeDate").val()=="")
	{
		$("#chargeDate").focus();
        alertify.error("Charge date is required!");
		return false;
	}
    if($("#chargeType").val()=="")
	{
		$("#chargeType").focus();
        alertify.error("Please select charge Type!");
		return false;
	}
    if($("#amount").val()=="")
	{
		$("#amount").focus();
        alertify.error("Amount field is required!");
		return false;
	}
    if($("#vatOnCharges").val()=="")
	{
		$("#vatOnCharges").focus();
        alertify.error("VAT is required!");
		return false;
	}
    if($("#vatRebate").val()=="")
	{
		$("#vatRebate").focus();
        alertify.error("VAT rebate is required!");
		return false;
	}
    if($("#totalCharge").val()=="")
	{
		$("#totalCharge").focus();
        alertify.error("Total charge is required!");
		return false;
	}
    if($("#relatedTo").val()=="")
	{
		$("#relatedTo").focus();
        alertify.error("Related to field is required!");
		return false;
	}
	return true;
}

function openCreditReport(id)
{
	$.get('api/charge-entry?action=1&id='+id, function (data) {
		  
		var row = JSON.parse(data);
		//alert(row);
		$("#chargeId").val(row["id"]);

		$('#chargeDate').datepicker('setDate', new Date(row["chargeDate"]));
		$('#chargeDate').datepicker('update');

		$('#chargeType').val(row["chargeType"]).change();
		$('#amount').val(commaSeperatedFormat(row["amount"]));
		$('#vatPercentage').val(commaSeperatedFormat(row["vatPercentage"]));
		$('#vatOnCharges').val(commaSeperatedFormat(row["vat"]));
		$('#vatRebatePercentage').val(commaSeperatedFormat(row["vatRebatePercentage"]));
		$('#vatRebate').val(commaSeperatedFormat(row["vatRebate"]));
		$('#totalCharge').val(commaSeperatedFormat(row["totalCharge"])).change();
		$('#relatedTo').val(row["relatedTo"]).change();
		$('#supplier').val(row["supplier"]).change();
		$('#remarks').val(row["remarks"]);
    });
}

function deleteCharge(id){
    
	alertify.confirm( 'Are you sure you want to delete this charge?', function (e) {
        if(e){
            $.get('api/charge-entry?action=3&id='+id, function (data) {
                if(data==1){
					alertify.success("Charge entry deleted successfully!");
					var dtable = $('#dtAllChargeEntry').dataTable();
					dtable.api().ajax.reload();
				} else{
					alertify.error("Delete Fail!");
				}
			});
			
		} 		
	});
    
}
function ResetForm() {
    
    $('#formChargeEntry')[0].reset();
	$("#chargeId").empty();
	$('#chargeType').val('').change();
	$('#relatedTo').val('').change();
	$('#supplier').val('').change();
    
}
$("#chargeDate").datepicker({
    todayHighlight: true,
    autoclose: true
});
