/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/


$(document).ready(function() {
	$('#dtCompany').dataTable({
		"ajax": "api/company?action=2",
		"columns": [
			{"data": "id"},
			{"data": "name"},
			{"data": "address"},
			{"data": "phone"},
			{"data": "fax"},
			{"data": "emailTo"},
			{"data": "emailCc"},
			{"data": "concernPerson"},
			{"data": "designation"},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#CompanyForm" data-toggle="modal" onclick="OpenCompany(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
						'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteCompany(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				}
			}],
		"sDom": 'frtip',
		"paging": true,
		"bAutoWidth": true
	} );
    
    
    $("#btnCompanyFormSubmit").click(function(e) {

		e.preventDefault();
		if (validate()) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/company",
				data: $('#form-company').serialize(),
				cache: false,
				success: function (response) {
					button.disabled = false;
					try {
						var res = JSON.parse(response);
						if (res['status'] == 1) {
							ResetForm();
							$('#CompanyForm').modal('hide');
							var dtable = $('#dtCompany').dataTable();
							alertify.success("Saved SUCCESSFULLY!");
							dtable.api().ajax.reload();
							return true;
						} else {
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
					button.disabled = false;
					alertify.error(textStatus + ": " + xhr.status + " " + error);
				}
			});
		} else {
			return false;
		}
	});

    $.getJSON("api/contract?action=5", function (list) {
        $("#contractRef").select2({
            data: list,
            minimumResultsForSearch: Infinity,
            placeholder: "Select Contract",
            allowClear: false,
            width: "100%"
        });
    });
    
});

function OpenCompany(id)
{
	//console.log('api/company?action=1&id='+id);
	$.get('api/company?action=1&id='+id, function (data) {
		if(!$.trim(data)){
			$("#form_error").show();
			$("#form_error").html("No data found!");
		} else{
			var row = JSON.parse(data);
			$("#companyid").val(row["id"]);			
			$('#name').val(row["name"]);
			$('#address').val(row["address"]);
			$('#phone').val(row["phone"]);
			$('#fax').val(row["fax"]);
			$('#emailTo').tokenfield('setTokens',row["emailTo"]);
			$('#emailCc').tokenfield('setTokens',row["emailCc"]);
			$('#concernPerson').val(row["concernPerson"]);
			$('#designation').val(row["designation"]);
			//$('#contractRef').val(row["contractRef"]).change();
            
            var v = row["contractRef"].split(',');
            $('#contractRef').val(v).change();
            
		}
    });
}


function DeleteCompany(id){
    
	alertify.confirm( 'Are you sure you want to delete this Company?', function (e) {
		if(e){
			$.get('api/company?action=3&id='+id, function (data) {
				if(data==1){
					alertify.success("Company DELETED!");
					var dtable = $('#dtCompany').dataTable();
					dtable.api().ajax.reload();
				} else{
					alertify.error("FAILED!");
				}
			});
			
		} else { // canceled
			//alertify.error(e); 
		};		
	});
}



function validate()
{
	if($("#name").val()=="")
	{
		$("#form_error").show();
		$("#form_error").html("Name cannot be blank!");
		$("#name").focus();
		return false;
	}
	if($("#phone").val()=="")
	{
		$("#form_error").show();
		$("#form_error").html("Phone Number cannot be blank!");
		$("#phone").focus();
		return false;
	}
	return true;	
}


function ResetForm() {
    
	$('#form-company')[0].reset();
	$("#companyid").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
    $('#emailTo').tokenfield('setTokens', []);
    $('#emailCc').tokenfield('setTokens', []);
    
}
