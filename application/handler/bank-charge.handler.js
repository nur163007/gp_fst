$(document).ready(function() {
    $.getJSON("api/company?action=4&type=118", function (list) {
        $("#LcIssuingBank").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: false,
            width: "100%"
        });
    });


    $("#SaveBankCharge_btn").click(function (e) {
        //alert('abcd');
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want to send document for acceptance?', function (e) {
                if (e) {
                    $("#SaveBankCharge_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/bank-charge",
                        data: $('#bank-charge-form').serialize(),
                        cache: false,
                        success: function (response) {
                            $("#SaveBankCharge_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    ResetForm();
                                } else {
                                    alertify.error("FAILED to update!");
                                    return false;
                                }
                            } catch (err) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });
});

function validate()
{
    //alert('sdfssd');
    if($("#LcIssuingBank").val()=="")
    {
        $("#LcIssuingBank").focus();
        alertify.error("Please select a Bank!");
        return false;
    }
    if($("#cableCharge").val()=="")
    {
        $("#cableCharge").focus();
        alertify.error("Cable charge filed is required!");
        return false;
    }
    if($("#stampCharge").val()=="")
    {
        $("#stampCharge").focus();
        alertify.error("Stamp charge filed is required!");
        return false;
    }
    if($("#nonVatOtherCharge").val()=="")
    {
        $("#nonVatOtherCharge").focus();
        alertify.error("No vat other charge filed is required!");
        return false;
    }
    if($("#otherCharge").val()=="")
    {
        $("#otherCharge").focus();
        alertify.error("Other charge filed is required!");
        return false;
    }

    return true;
}

function ResetForm() {
$("#bank-charge-form")[0].reset();
$("#LcIssuingBank").val("").change();
}