/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 21.01.2016
*/

$(document).ready(function() {
    
	$('#dtCat').dataTable( {
		"ajax": "api/category?action=2",
		"columns": [
			{ "data": "id", "class": "text-center" },
			{ "data": "name" },
			{ "data": "menu" },
			{
                "data": null, "class": "text-center", "sortable": false,
                "render": function (data, type, full) {
                    if (full["active"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
                    else return '';
                }
            },
			{
                "data": null, "class": "text-center", "sortable": false,
                "render": function (data, type, full) {
                    if (full["moderation"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
                    else return '';
                }
            },
			{ "data": "parent" },
			{ "data": "tag" },
			{ "data": null, "sortable": false, "class": "text-center",
				"render": function(data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#catForm" data-toggle="modal" data-toggle="Edit User" data-original-title="Edit User" onclick="OpenCat(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
					'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteCat(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				  }
			} ],
		"sDom": 'frtip',
        "paging": true
	});
    
    refreshlookupSetList();
    
    $.getJSON("api/category?action=4", function (list) {
        $("#parent").select2({
            data: list,
            placeholder: "select a parent item",
            allowClear: false,
            width: "100%"
          });
    });
    
    $("#btnCatFormSubmit").click(function(e) {
        
        e.preventDefault();
		if(validate() == true)
		{
			$.ajax({
				type: "POST",
				url: "api/category",
				data: $('#form-cat').serialize(),
				cache: false,
				success: function(html){
                    //alert(html);
					var res = JSON.parse(html);
					if(res['status']==1){
						ResetForm();
						$('#catForm').modal('hide');
						var dtable = $('#dtCat').dataTable();						
						alertify.success("Saved SUCCESSFULLY!");
						dtable.api().ajax.reload();	
                        RefreshCatList();				
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

	$("#btnCreateLookupSet").click(function(e) {

		e.preventDefault();
		if(validateLookupName() == true)
		{
			$.ajax({
				type: "POST",
				url: "api/category",
				data: $('#form-lookupset').serialize(),
				cache: false,
				success: function(html){
					//alert(html);
					var res = JSON.parse(html);
					if(res['status']==1){
						resetLookupForm();
						$('#addNewLookupSet').modal('hide');
						refreshlookupSetList();
						alertify.success("Created SUCCESSFULLY!");
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

function refreshlookupSetList(){
	$.getJSON("api/navigations?action=5", function (list) {
		$("#category").empty();
		$("#category").select2({
			data: list,
			placeholder: "select a category/lookup",
			allowClear: false,
			width: "100%"
		});
	});
}


function OpenCat(id)
{
	$.get('api/category?action=1&id='+id, function (data) {
		if(!$.trim(data)){
			$("#form_error").show();
			$("#form_error").html("No data found!");
		} else{
			var row = JSON.parse(data);
			$("#catId").val(row["id"]);
            //row["name"].uns;
           
			$('#name').val(HTMLDecode(row["name"]));
            $('#category').val(row["menu"]).change();
            
			if(row["active"]==0){
				$('#active').removeAttr('checked');
			} else {
				$('#active').attr('checked','checked');
			}
            
			if(row["moderation"]==0){
				$('#moderation').removeAttr('checked');
			} else {
				$('#moderation').attr('checked','checked');
			}
            $('#parent').val(row["parent"]).change();
			$('#tag').val(HTMLDecode(row["tag"]));
			$('#metatext').val(HTMLDecode(row["metatext"]));
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
	if($("#url").val()=="")
	{
		$("#form_error").show();
		$("#form_error").html("Url cannot be blank!");
		$("#url").focus();
		return false;
	}
	return true;	
}

function validateLookupName(){
	alert($("#lookupSetName").val());
	if($("#lookupSetName").val()=="")
	{
		$("#lookupSetName").focus();
		alertify.error("Lookup name cannot be blank!");
		return false;
	}
	return true;
}

function DeleteCat(id){
	alertify.confirm( 'Are you sure you want to delete this Category?', function (e) {
		if(e){
			$.get('api/category?action=3&id='+id, function (data) {
				if(data==1){
					alertify.success("Category deleted successfully!");
					var dtable = $('#dtCat').dataTable();
					dtable.api().ajax.reload();
                    RefreshCatList();
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

function RefreshCatList()
{
    $.getJSON("api/category?action=4", function (list) {
        $("#parent").select2({
            data: list,
            placeholder: "select a parent nav",
            allowClear: false,
            width: "100%"
          });
    });
}

function ResetForm() {
    
	$('#form-cat')[0].reset();
	$("#catId").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
	$('#parent').val('').change();
	$('#category').val('').change();
}

function resetLookupForm() {

	$('#form-lookupset')[0].reset();
	$("#form_error").empty();
	$("#form_error").hide();
}