$(document).ready(function() {

    $.getJSON("api/purchaseorder?action=3", function (list) {
        // alert(list);
        $("#poList").select2({
            data: list,
            placeholder: "Search PO Number",
            allowClear: false,
            width: "100%"
        });
    });

    $("#poList").change(function (e) {
        //alert("api/shipment-ext?action=1&po=" + $("#poList").val());
        $.getJSON("api/shipment-ext?action=1&po=" + $("#poList").val(), function (list) {
            // alert(list);
            //var row = JSON.parse(list);
            $("#existingShip").empty();
            $('#existingShip').val(list);

            var lastShip = parseInt(list[list.length - 1]);
            var newShip = lastShip + 1;
            $("#noship").val(newShip);
        });
    });


    $("#execute_btn").click(function (e) {
        e.preventDefault();
        if (validate()) {
            alertify.confirm('Are you sure you want to extend shipment?', function (e) {
                $("#execute_btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/shipment-ext",
                    data: $('#form-ship-ext').serialize(),
                    cache: false,
                    success: function (response) {
                        $("#execute_btn").prop('disabled', false);
                        try {
                            //alertify.alert(response);
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                ResetForm();
                                alertify.success("Shipment extended SUCCESSFULLY!");
                                window.location.href = "shipment-ext";
                                return true;
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (e) {
                            console.log(e);
                            alertify.error(response + ' Failed to process the request.', 20);
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error);
                    }
                });
            });
        } else {
            return false;
        }
    });

});



function validate()
{
    if($("#poList").val()=="")
    {
        $("#poList").select2('open');
        alertify.error("PO number is required!");
        return false;
    }
    if($("#noship").val()=="")
    {
        $("#noship").focus();
        alertify.error("Shipment number is required!");
        return false;
    }

    return true;
}

function ResetForm() {
    $('#form-ship-ext')[0].reset();
    $('#poList').val('').change();
    $("#noship").empty();
}