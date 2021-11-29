/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/


$(document).ready(function() {

	$('#dtUserRoles').dataTable({
		"ajax": "api/userroles?action=2",
		"columns": [
			{"data": "id"},
			{"data": "name"},
			{"data": "parent"},
			{"data": "description"},
			{"data": "tag"},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#UserRoleForm" data-toggle="modal" data-toggle="Edit User" data-original-title="Edit User" onclick="OpenUserRole(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button> ' +
						'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteUserRoles(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				}
			}],
		"sDom": 'frtip',
		"paging": true
	});

	$.getJSON("api/userroles?action=4", function (list) {
		$("#parent").select2({
			data: list,
			placeholder: "select a role",
			allowClear: false,
			width: "100%"
		});
	});

	$("#btnUserRolesFormSubmit").click(function (e) {

		e.preventDefault();
		if (validate() === true) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/userroles",
				data: $('#form-userroles').serialize(),
				cache: false,
				success: function (html) {
					button.disabled = false;
					var res = JSON.parse(html);
					if (res['status'] == 1) {
						ResetForm();
						$('#UserRoleForm').modal('hide');
						var dtable = $('#dtUserRoles').dataTable();
						alertify.success("Saved SUCCESSFULLY!");
						dtable.api().ajax.reload();
						RefreshRoleList();
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

});

function OpenUserRole(id)
{
	$.get('api/userroles?action=1&id='+id, function (data) {
		if(!$.trim(data)){
			$("#form_error").show();
			$("#form_error").html("No data found!");
		} else{
			var row = JSON.parse(data);
			$("#userroleid").val(row["id"]);			
			$('#name').val(row["name"]);
            $('#parent').val(row["parent"]).change();
			$('#description').val(row["description"]);
			$('#tag').val(row["tag"]);
		}
    });
}


function DeleteUserRoles(id){
    
	alertify.confirm( 'Are you sure you want to delete this user role?', function (e) {
		if(e){
			$.get('api/userroles?action=3&id='+id, function (data) {
				if(data==1){
					alertify.success("Role DELETED!");
					var dtable = $('#dtUserRoles').dataTable();
					dtable.api().ajax.reload();
                    RefreshRoleList();
				} else{
					alertify.error("FAILED!");
				}
			});
			
		} else { // canceled
			//alertify.error(e); 
		};		
	});
}

function RefreshRoleList()
{
    $.getJSON("api/userroles?action=4", function (list) {
        $("#parent").select2({
            data: list,
            placeholder: "select a role",
            allowClear: false,
            width: "100%"
          });
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
	if($("#description").val()=="")
	{
		$("#form_error").show();
		$("#form_error").html("Description cannot be blank!");
		$("#description").focus();
		return false;
	}
	return true;	
}


function ResetForm() {
    
	$('#form-userroles')[0].reset();
	$("#userroleid").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
	$('#parent').val('').change();
    
}
