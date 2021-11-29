/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
$(document).ready(function() {
    
    $("#btnEventForm").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if(validate() === true){
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/events",
                data: $('#form-events').serialize(),
                cache: false,
                success: function (result) {
                    button.disabled = false;
                    var res = JSON.parse(result);
                    if (res['status'] == 1) {
                        ResetForm();
                        alert('Event has been added successfully.');
                        window.location.href = _dashboardURL;
                    } else {
                        alertify.error("FAILED to add!");
                        return false;
                    }
                }
            });    
        } else {
            return false;
        }
    });
        
    
});
    
    $('#start')
    .datepicker({
        todayHighlight: true,
        autoclose: true
    });
    $('#end')
        .datepicker({
            todayHighlight: true,
            autoclose: true
    });


        
function validate()
{
    if($("#title").val()=="")
	{
		$("#title").focus();
        alertify.error("Write a Title!");
		return false;
	}
    if($("#description").val()=="")
	{
		$("#description").focus();
        alertify.error("Write some description!");
		return false;
	}
    if($("#start").val()=="")
	{
		$("#start").focus();
        alertify.error("Start Date is required!");
		return false;
	}
	return true;	
}

function ResetForm(){
    $('#form-events')[0].reset();
	$("#title").empty();
	$("#description").empty();
	$("#start").val('').change();
	$("#end").val('').change();
    
}
