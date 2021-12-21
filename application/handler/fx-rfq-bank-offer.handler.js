$(document).ready(function() {

    if ($('#poid').val() != "") {
        var fxRequestId = $('#poid').val().substr(5);
        $('#hdnFxRequestId').val(fxRequestId);

        $.get('api/fx-rfq-bank-offer?action=1&fxrFqRowId='+fxRequestId, function(data) {

            //console.log(data);
            var row = JSON.parse(data);
            //console.log(row);
            //alert(row["Id"]);
            $("#fxrFqRowId").val(row["Id"]);
            // $('#fx_req_id').html(row["FxRequestId"]);
            $('#fx_value').html(row["FxValue"]);
            $('#fx_date').html(row["FxDate"]);
            $('#currency').html(row["CurName"]);
            $('#cuttsofftime').html(row["CuttsOffTime"]);

            $('#FxRate').val(row["FxRate"]);
            if(row["OfferedVolumeAmount"]!=null) {
                $('#OfferedVolumeAmount').val(row["OfferedVolumeAmount"]);
            }
            $('#remarks').val(row["remarks"]);
        });

    }


    $("#btnFxRfqRequest").click(function (e) {

        e.preventDefault();

        if($("#FxRate").val()=="")
        {
            $("#form_error").show();
            $("#form_error").html("Bank Rate Cannot be Blank!");
            $("#FxRate").focus();
            return false;
        }
        if($("#OfferedVolumeAmount").val()=="")
        {
            $("#form_error").show();
            $("#form_error").html("Bank Offered Amount Cannot be Blank!");
            $("#OfferedVolumeAmount").focus();
            return false;
        }
        if($("#remarks").val()=="")
        {
            $("#form_error").show();
            $("#form_error").html("Remarks Cannot be Blank!");
            $("#remarks").focus();
            return false;
        }
        // alert($('#frmBankRate').serialize());

        /*     Submitt bank rate and Offer Amount    */
        alertify.confirm( 'Are you sure to submit this request?', function (e) {

            if(e) {
                $.ajax({
                    type: "POST",
                    url: "api/fx-rfq-bank-offer",
                    data: $('#frmBankRate').serialize(),
                    cache: false,
                    success: function (html) {
                        // alert(html);
                        var res = JSON.parse(html);
                        if (res['status'] == 1) {
                            alertify.success("Saved SUCCESSFULLY!");
                            // $("#fxrFqRowId").val("");
                            // $("#FxRate").val("");
                            // $("#OfferedVolumeAmount").val("");
                            // $("#remarks").val("");
                            location.replace("dashboard");

                            return true;
                        } else {
                            alertify.error("FAILED!");
                            return false;
                        }
                    }
                });
            }
            else {

            }
        });


    })
})