/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/

$(document).ready(function() {

	$('#dtAllUsers').dataTable({
		"ajax": "api/users?action=2",
		"columns": [
			{"data": "id", "visible": false},
			{"data": "username"},
			{"data": "fullName"},
			{"data": "company"},
			{"data": "department"},
			{"data": "mobile"},
			{"data": "email"},
			{"data": "roleName"},
			{
				"data": null, "class": "text-center", "sortable": false,
				"render": function (data, type, full) {
					if (full['isLocked'] == 0)
						return '<span class="btn btn-sm btn-icon btn-flat btn-default"><i class="icon fa-unlock" style="color:limegreen" aria-hidden="true"></i></button>';
					else
						return '<button class="btn btn-sm btn-icon btn-flat btn-default" onclick="unlockUser(' + full['id'] + ',\'' + full['fullName'] + '\')"><i class="icon fa-lock" style="color:red" aria-hidden="true"></i></button>';

				}
			},
			{
				"data": null, "class": "text-center", "sortable": false,
				"render": function (data, type, full) {
					if (full["active"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
					else return '';
				}
			},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#UserForm" data-toggle="modal" data-toggle="Edit User" data-original-title="Edit User" onclick="OpenUser(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
						'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteUser(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				}
			}],
		"sDom": 'frtip',
		"bProcessing": true,
		"bStateSave": false,
		"autoWidth": false
	});

	$.getJSON("api/userroles?action=4", function (list) {
		//alert(list);
		$("#userrole").select2({
			data: list,
			placeholder: "select a role",
			allowClear: false,
			width: "100%"
		});
	});

	$.getJSON("api/company?action=4", function (list) {
		$("#company").select2({
			data: list,
			placeholder: "select a company",
			allowClear: true,
			width: "100%"
		});
	});

	$("#btnUserFormSubmit").click(function (e) {
		e.preventDefault();
		if (validate()) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/users",
				data: $('#form-users').serialize(),
				cache: false,
				success: function (response) {
					button.disabled = false;
					//alert(response);
					try {
						var res = JSON.parse(response);
						if (res['status'] == 1) {
							ResetForm();
							$('#UserForm').modal('hide');
							var dtable = $('#dtAllUsers').dataTable();
							alertify.success(res['message']);
							dtable.api().ajax.reload(null, false);
							//dtable.fnPageChange('last');
							//dtable.fnDraw();
							return true;
						} else {
							alertify.error(res['message']);
							return false;
						}
					}catch (e) {
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
});

function OpenUser(id)
{
	//console.log('api/users?action=1&id='+id);
	$.get('api/users?action=1&id='+id, function (data) {
		if(!$.trim(data)){
		  
			$("#form_error").show();
			$("#form_error").html("No data found!");
            
		} else{
		  
			var row = JSON.parse(data);
            //alert(row);
			$("#userId").val(row["id"]);
			
			$('#username').val(row["username"]);
			$('#username').attr('disabled','disabled');
			
			$('#firstname').val(row["firstname"]);
			$('#lastname').val(row["lastname"]);
			$('#mobile').val(row["mobile"]);
			$('#email').val(row["email"]);

			$('#company').val(row["company"]).change();
			$('#department').val(row["department"]);
			$('#userrole').val(row["role"]).change();
			
			if(row["active"]==0){
				$('#activeUser').removeAttr('checked');
			} else {
				$('#activeUser').attr('checked','checked');
			}
			if(row["manager"]==0){
				$('#ismanager').removeAttr('checked');
			} else {
				$('#ismanager').attr('checked','checked');
			}
            
		}
    });
}

function validate()
{
	if($("#username").val()=="")
	{
		$("#form_error").show();
		alertify.error("User Name cannot be blank!");
		$("#username").focus();
		return false;
	}else{
		 if($("#usernameError").html()!==""){
			 return false;
		 }
	}
	if($("#password").val()=="")
	{
		if($('#userId').val()!="0") { return true; }
		$("#form_error").show();
		alertify.error("Password cannot be blank!");
		$("#password").focus();
		return false;
	}
	if($("#firstname").val()=="")
	{
		$("#form_error").show();
		alertify.error("First Name cannot be blank!");
		$("#firstname").focus();
		return false;
	}
	if (!$('#company').val()){
		alertify.error("Please select a company");
		$('#company').select2('open');
		return false;
	}
	if($("#userrole").val() == "")
	{
		$("#form_error").show();
		alertify.error("Please select an user role!");
		$("#userrole").select2('open');
		return false;
	}
	return true;	
}

function VerifyUserName(name){
    
	if($("#userId").val()=="0"){
		$.get('api/users?action=4&name='+name, function (data) {
			if(data==1){
				$("#usernameError").show();
				$("#usernameError").html("Username already exist!");
			} else{
				$("#usernameError").empty();
				$("#usernameError").hide();
			}
		});
	}
}

function DeleteUser(id){
    
	alertify.confirm( 'Are you sure you want to delete this user?', function (e) {
        if(e){
            $.get('api/users?action=3&id='+id, function (data) {
                if(data==1){
					alertify.success("User deleted successfully!");
					var dtable = $('#dtAllUsers').dataTable();
					dtable.api().ajax.reload();
				} else{
					alertify.error("Delete Fail!");
				}
			});
			
		} else { // canceled
			//alertify.error(e);
		}
	});
}

function ResetForm() {
    
	$('#form-users')[0].reset();
	$("#userId").val("0");
    $('#username').removeAttr('disabled');
	$("#form_error").empty();
	$("#form_error").hide();
	$("#usernameError").empty();
	$("#usernameError").hide();
	$('#company').val('').change();
	$('#userrole').val('').change();
    
}

/*!
* Unlock user
* @param - lockedId(integer)
* Added by: Hasan Masud
* ************************/
function unlockUser(lockedId, fullname) {
	alertify.confirm("Are you sure you want to unlock this user: "+fullname+"?", function (e) {
		if (e) {
			$.ajax({
				url: 'api/users?action=7&lockedId=' + lockedId,
				success: function (result) {
					var res = JSON.parse(result);
					if (res["status"] == 1) {
						alertify.success(res["message"]);
						var dtable = $('#dtAllUsers').dataTable();
						dtable.api().ajax.reload();
					}else {
						alertify.error(res["message"]);
					}
				}
			});
		}
	});
}