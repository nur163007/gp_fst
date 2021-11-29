/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
$(document).ready(function() {
    
    $("#cnfCostUpdate_btn").click(function (e) {
       /* alert('abcd');*/
        e.preventDefault(); 
        if(validate() === true){
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/cnf-cost-update",
                data: $('#cnf-cost-update-form').serialize(),
                cache: false,
                success: function (result) {
                    button.disabled = false;
                    /*alert(result);*/
                    var res = JSON.parse(result);
                    if (res['status'] == 1) {
                        alertify.success('C & F Cost Updated.');
                        window.location.href = _dashboardURL;
                    } else {
                        alertify.error("FAILED to update!");
                        return false;
                    }
                }
            });    
        }else {
            return false;
        }
    });
        
    $.getJSON("api/custom-duty?action=1", function (list) {
        $("#gpRefNum").select2({
            data: list,
            placeholder: "Search GP Ref. Number",
            allowClear: false,
            width: "100%"
        });
    });
    
    $.getJSON("api/bankinsurance?action=4&type=insurance", function (list) {
        $("#cnfAgent").select2({
            data: list,
            placeholder: "Select a Agent",
            allowClear: false,
            width: "100%",
            disabled: true
        });
    });
    
     $("#gpRefNum").change(function(e){
        var ref = $("#gpRefNum").val();
        $.get("api/custom-duty?action=2&gpref="+ref, function (data) {
            var row = JSON.parse(data);
            $("#lcno").val(row['lcno']);
            $("#MawbNum").val(row['MawbNum']);
            $("#HawbNum").val(row['HawbNum']);
            $("#BlNum").val(row['BlNum']);
            $("#ciValue").val(commaSeperatedFormat(row['ciValue']));
            $("#cnfAgent").val(row['CnFAgent']).change();
        });
     });
    
    
});


        
function validate()
{
    if($("#gpRefNum").val()=="")
	{
		$("#gpRefNum").focus();
        alertify.error("Reference number is required!");
		return false;
	}
    if($("#lcno").val()=="")
	{
		$("#lcno").focus();
        alertify.error("LC number is required!");
		return false;
	}
    /*if($("#MawbNum").val()=="")
	{
		$("#MawbNum").focus();
        alertify.error("MAWB number is required!");
		return false;
	}
    if($("#HawbNum").val()=="")
	{
		$("#HawbNum").focus();
        alertify.error("HAWB number is required!");
		return false;
	}
    if($("#BlNum").val()=="")
	{
		$("#BlNum").focus();
        alertify.error("BL number is required!");
		return false;
	}*/
    if($("#ciValue").val()=="")
	{
		$("#ciValue").focus();
        alertify.error("CI Value is required!");
		return false;
	}
    if($("#cnfAgent").val()=="")
	{
		$("#cnfAgent").focus();
        alertify.error("CNF Agent is required!");
		return false;
	}
    if($("#cNfAmount").val()=="")
	{
		$("#cNfAmount").focus();
        alertify.error("C &amp; F Amount is required!");
		return false;
	}
    if($("#remarks").val()=="")
	{
		$("#remarks").focus();
        alertify.error("Remarks is required!");
		return false;
	}
	return true;	
}



