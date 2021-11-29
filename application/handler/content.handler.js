/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 20.01.2016
*/

$(document).ready(function() {

	$('#dtContent').dataTable({
		"ajax": "api/content?action=2",
		"columns": [
			{"data": "id", "visible": false},
			{"data": "metaTitle"},
			{"data": "mainTitle"},
			{"data": "subTitle"},
			{"data": "content"},
			{"data": "tag"},
			{"data": "category"},
			{
				"data": null, "sortable": false, "class": "text-center",
				"render": function (data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#ContentForm" data-toggle="modal" data-toggle="Edit Content" data-original-title="Edit Content" onclick="OpenContent(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;' +
						'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteContent(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				}
			}],
		"sDom": 'frtip'
	});


	$.getJSON("api/category?action=4&id=16", function (list) {
		$("#category").select2({
			data: list,
			placeholder: "select a Category",
			allowClear: true,
			width: "100%"
		});
	});

	$("#btnContentFormSubmit").click(function (e) {
		e.preventDefault();
		if (validate() === true) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/content",
				data: $('#form-content').serialize(),
				cache: false,
				success: function (html) {
					button.disabled = false;
					//alert(html);
					var res = JSON.parse(html);
					if (res['status'] == 1) {
						ResetForm();
						$('#ContentForm').modal('hide');
						var dtable = $('#dtContent').dataTable();
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
});

function OpenContent(id)
{
	$.get('api/content?action=1&id='+id, function (data) {
		if(!$.trim(data)){
		  
			$("#form_error").show();
			$("#form_error").html("No data found!");
            
		} else{
		  
			var row = JSON.parse(data);
			$("#contentId").val(row["id"]);
			$('#metaTitle').val(row["metaTitle"]);
			$('#mainTitle').val(row["mainTitle"]);
			$('#subTitle').val(row["subTitle"]);
			$('#content').val(row["content"]);
			$('#tag').val(row["tag"]);
			$('#category').val(row["category"]).change();
            
		}
    });
}

function validate()
{
	if($("#category").val() == "")
	{
		$("#form_error").show();
		$("#form_error").html("Must select user Category!");
		$("#category").focus();
		return false;
	}
	return true;	
}


function DeleteContent(id){
    
	alertify.confirm( 'Are you sure you want to delete this Content?', function (e) {
        if(e){
            $.get('api/content?action=3&id='+id, function (data) {
                if(data==1){
					alertify.success("Content deleted successfully!");
					var dtable = $('#dtContent').dataTable();
					dtable.api().ajax.reload();
				} else{
					alertify.error("Delete Fail!");
				}
			});
			
		} else { // canceled
			//alertify.error(e); 
		};		
	});
    
}

function ResetForm() {
    
	$('#form-content')[0].reset();
	$("#contentId").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
	$('#category').val('').change();
    
}
