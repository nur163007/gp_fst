/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/

$(document).ready(function() {

	$('#dtNav').dataTable({
		"ajax": "api/navigations?action=2",
		"columns": [
			{"data": "id", "class": "text-center"},
			{"data": "navorder", "visible": false},
			{"data": "name", "sortable": false},
			{"data": "url", "sortable": false},
			{"data": "mask", "sortable": false},
			{
				"data": null, "class": "text-center", "sortable": false,
				"render": function (data, type, full) {
					if (full["category"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
					else return '';
				}
			},
			{"data": "parent", "sortable": false},
			{
				"data": null, "class": "text-center", "sortable": false,
				"render": function (data, type, full) {
					if (full["display"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
					else return '';
				}
			},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#navForm" data-toggle="modal" data-toggle="Edit User" data-original-title="Edit User" onclick="OpenNav(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
						'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteNav(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				}
			}],
		"sorting": [[1, "asc"]],
		"sDom": 'frtipS',
		"paging": true,
		"pageLength": 10
	});

	$.getJSON("api/navigations?action=4", function (list) {
		$("#parent").select2({
			data: list,
			placeholder: "select a parent nav",
			allowClear: false,
			width: "100%"
		});
	});

	$("#btnNavFormSubmit").click(function (e) {

		e.preventDefault();
		if (validate()) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/navigations",
				data: $('#form-nav').serialize(),
				cache: false,
				success: function (response) {
					button.disabled = false;
					//alertify.alert(response);
					try {
						var res = JSON.parse(response);
						if (res['status'] == 1) {
							ResetForm();
							$('#navForm').modal('hide');
							var dtable = $('#dtNav').dataTable();
							alertify.success("Saved SUCCESSFULLY!");
							dtable.api().ajax.reload();
							RefreshNavList();
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
});

function OpenNav(id)
{
	$.get('api/navigations?action=1&id='+id, function (data) {
		if(!$.trim(data)){
			$("#form_error").show();
			$("#form_error").html("No data found!");
		} else{
			var row = JSON.parse(data);
            
			$("#navId").val(row["id"]);			
			$('#name').val(HTMLDecode(row["name"]));
            
            $('#url').val(row["url"]);
			$('#mask').val(row["mask"]);
            
			if(row["category"]==0){
				$('#category').removeAttr('checked');
			} else {
				$('#category').attr('checked','checked');
			}
            if(row["parent"]!=""){
                $('#parent').val(row["parent"]).change();
            }
            
			if(row["display"]==0){
				$('#display').removeAttr('checked');
			} else {
				$('#display').attr('checked','checked');
			}
		}
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
//	if($("#url").val()=="")
//	{
//		$("#form_error").show();
//		$("#form_error").html("Url cannot be blank!");
//		$("#url").focus();
//		return false;
//	}
	return true;	
}

function DeleteNav(id){
	alertify.confirm( 'Are you sure you want to delete this Navigation?', function (e) {
		if(e){
			$.get('api/navigations?action=3&id='+id, function (data) {
				if(data==1){
					alertify.success("Navigation deleted successfully!");
					var dtable = $('#dtNav').dataTable();
					dtable.api().ajax.reload();
                    RefreshNavList();
				} else{
					alertify.error("Delete Fail!");
				}
			});
			
		} else { // canceled
			//alertify.error(e); 
		};		
	});
}

function RefreshNavList()
{
    $.getJSON("api/navigations?action=4", function (list) {
        $("#parent").select2({
            data: list,
            placeholder: "select a parent nav",
            allowClear: false,
            width: "100%"
          });
    });
}

function ResetForm() {
    
	$('#form-nav')[0].reset();
	$("#navId").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
	$('#parent').val('').change();
}
