/*	
	Author: Shohel Iqbal
    Copyright: 02.2016
    Code fridged on: 
*/

$(document).ready(function() {
    
	$('#dtAllBanks').dataTable( {
		"ajax": "api/bankinsurance?action=2",
		"columns": [
			{ "data": "id" },
			{ "data": "bankorder", "visible": false },
			{ "data": null, "sortable": false, 
                "render": function(data, type, full) { 
                    if(full['type']!='account') { return '<b>'+full['name']+'</b>'; } else { return '&nbsp;&nbsp;&nbsp;'+full['name']; } 
                }
            },
			{ "data": "bank" },
			{ "data": null, "sortable": false, 
                "render": function(data, type, full) { 
                    return '<span>'+full['address']+'</span>';
                }
            },
			{ "data": "email" },
			{ "data": "type", "class": "text-center" },
			{ "data": null, "sortable": false, "class": "text-center",
				"render": function(data, type, full) {
					return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#BankForm" data-toggle="modal" data-toggle="Edit Bank" data-original-title="Edit Bank" onclick="OpenBank(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
					'<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="DeleteBank(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
				  }
			}],
        "sorting": [[1, "asc"]],
		"sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": true,
		"bAutoWidth": false
	});
    
    $("#btnBankFormSubmit").click(function(e) {
		//alert('ddd');
		e.preventDefault();
		if (validate() === true) {
			var button = e.target;
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/bankinsurance",
				data: $('#form-bank').serialize(),
				cache: false,
				success: function (html) {
					button.disabled = false;
					//alert(html);
					var res = JSON.parse(html);
					if (res['status'] == 1) {

						ResetForm();
						$('#BankForm').modal('hide');
						var dtable = $('#dtAllBanks').dataTable();
						alertify.success("Saved SUCCESSFULLY!");
						dtable.api().ajax.reload(null, false);
						loadSelectBankList();

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
    loadSelectBankList();
    
});

function loadSelectBankList(){
    $.getJSON("api/bankinsurance?action=4&type=bank", function (list) {
        $("#bank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: false,
            width: "100%"
          });
    });
}

function OpenBank(id)
{
	$.get('api/bankinsurance?action=1&id='+id, function (data){
		if(!$.trim(data)){
			$("#form_error").show();
			$("#form_error").html("No data found!");
		} else{
			var row = JSON.parse(data);
			$("#id").val(row["id"]);
			
			$('#name').val(row["name"]);
			$('#address').val(row["address"]);
			$('#manager').val(row["manager"]);
			$('#telephone').val(row["telephone"]);
			$('#mobile').val(row["mobile"]);
			$('#email').val(row["email"]);
			$('#website').val(row["website"]);
            $("#type_"+row['type']).attr('checked','').parent().addClass("checked");
			$('#bank').val(row["bank"]).change();
			$('#tag').val(row["tag"]);
		}
    });
}

function validate()
{
	if($("#name").val() == "")
	{
		alertify.error("Name is required!");
        $("#name").focus();
		return false;
	}
    var type_check = $('input[name=type]:checked').val();
    
    //alert(type_check);
    
	if(type_check==undefined)
	{
		alertify.error("Please select a Type!");
		return false;
	} else if(type_check=='account'){
        if($("#bank").val()==""){
            $("#bank").focus();
            alertify.error("Select a bank!");
    		return false;
        }
	}
	return true;	
}


function DeleteBank(id){
    
	alertify.confirm( 'Are you sure you want to delete this Bank?', function (e) {
        if(e){
            $.get('api/bankinsurance?action=3&id='+id, function (data) {
                if(data==1){
					alertify.success("Bank deleted successfully!");
					var dtable = $('#dtAllBanks').dataTable();
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
    
	$('#form-bank')[0].reset();
    
	$("#id").val("0");
	$("#form_error").empty();
	$("#form_error").hide();
	$('#bank').val('').change();
    $('#tag').tokenfield('setTokens', []);
    $('input[name="type"]').removeAttr('checked').parent().removeClass("checked");
    
//    $("#type_b").parent().removeClass("checked");
//    $("#type_a").parent().removeClass("checked");
//    $("#type_i").parent().removeClass("checked");
    
}
