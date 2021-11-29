/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
*/


   

$(document).ready(function() {
    $('#dtAllCreditReport').dataTable( {
		"ajax": "api/credit-report?action=2",
		"columns": [
			{ "data": "id", "visible": false },
			{ "data": "supplier" },
			{ "data": "creditReportDate" },
			{ "data": "reportExpiryDate" },
			{ "data": "creditReportCharge" },
			{ "data": "vatOnCharges" },
			{ "data": "rebate" },
			{ "data": "vatRebate" },
			{ "data": "chargeType" },
			{ "data": null, "sortable": false, "class": "text-center",
				"render": function(data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#creditReportForm" data-toggle="modal" data-toggle="Edit Credit Report" data-original-title="Edit Credit Report" onclick="openCreditReport(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
					'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="deleteCreditReport(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				  }
			} ],
		"sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": true
	});
    
    $("#btnCreditReportFormSubmit").click(function(e) {
        /*alert('sdfd');*/
		e.preventDefault();
		if(validate() == true)
		{
			$.ajax({
				type: "POST",
				url: "api/credit-report",
				data: $('#form-credit-report').serialize(),
				cache: false,
				success: function(html){
				    /*alert(html);*/
					var res = JSON.parse(html);
					if(res['status']==1){
						ResetForm();
						$('#creditReportForm').modal('hide');
						var dtable = $('#dtAllCreditReport').dataTable();						
						alertify.success("Saved SUCCESSFULLY!");
						dtable.api().ajax.reload();
						return true;
					} else{
						alertify.error("FAILED!");						
						return false;						
					}
				}  
			});
		} else {
			return false;
		}
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

$.getJSON("api/category?action=4&id=34", function (list) {
    $("#chargeType").select2({
        data: list,
        minimumResultsForSearch: Infinity,
        placeholder: "Select Charge Type",
        allowClear: false,
        width: "100%"
    });
});

function validate()
{
    if($("#supplier").val()=="")
	{
		$("#supplier").focus();
        alertify.error("Select a Supplier!");
		return false;
	} 
	return true;	
}

function openCreditReport(id)
{
	$.get('api/credit-report?action=1&id='+id, function (data) {
		  
			var row = JSON.parse(data);
            //alert(row);
			$("#reportID").val(row["id"]);
			$('#supplier').val(row["supplier"]).change();
			$('#creditReportDate').val(row["creditReportDate"]);
			$('#reportExpiryDate').val(row["reportExpiryDate"]);
			$('#creditReportCharge').val(row["creditReportCharge"]);
			$('#vatOnCharges').val(row["vatOnCharges"]);
			$('#rebate').val(row["rebate"]);
			$('#vatRebate').val(row["vatRebate"]);
			$('#chargeType').val(row["chargeType"]).change();
    });
}

function deleteCreditReport(id){
    
	alertify.confirm( 'Are you sure you want to delete this Report?', function (e) {
        if(e){
            $.get('api/credit-report?action=3&id='+id, function (data) {
                if(data==1){
					alertify.success("Credit Report deleted successfully!");
					var dtable = $('#dtAllCreditReport').dataTable();
					dtable.api().ajax.reload();
				} else{
					alertify.error("Delete Fail!");
				}
			});
			
		} 		
	});
    
}
function ResetForm() {
	$("#reportID").empty();
	$('#supplier').val('').change();
	$("#creditReportDate").empty();
	$("#reportExpiryDate").empty();
	$("#creditReportCharge").empty();
	$("#vatOnCharges").empty();
	$("#rebate").empty();
	$("#vatRebate").empty();
	$('#chargeType').val('').change();
    
}
$("#creditReportDate, #reportExpiryDate")
.datepicker({
    todayHighlight: true,
    autoclose: true
});
