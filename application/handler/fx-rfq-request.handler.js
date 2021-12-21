$(document).ready(function () {
    $(function () {
        $("#btnFxRfqRequest").click(function (e) {
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm( 'Are you sure to submit this request?', function (e) {

                    if(e) {
                        $.ajax({
                            type: "POST",
                            url: "api/fx-rfq-request",
                            data: $("#rfq_form").serialize(),
                            cache: false,
                            success: function (response) {
                                //console.log(response);
                                var res = JSON.parse(response);
                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    location.replace("dashboard");
                                } else {
                                    alertify.error(res['message']);
                                    return false;
                                }
                            },
                            error: function (xhr, textStatus, error) {
                                alertify.error(textStatus + ": " + xhr.status + " " + error);
                            }
                        });
                    }
                    else {

                    }
                });
            } else {
                return false;
            }
        });
    });

    /*      fetch fx request detail        */

    if ($('#poid').val() != "") {
        var fxRequestId = $('#poid').val().substr(5);
        $('#id').val(fxRequestId);
        //alert(fxRequestId);
        $.getJSON('api/fx-rfq-request?action=2&id=' + fxRequestId, function (data) {
            var row = JSON.parse(JSON.stringify(data));
            // console.log(row);
            $("#rfqId").html($("#poid").val());
            // $('#supplier_id').html(row["supplier_name"]);
            // $('#nature_of_service').html(row["nature_of_services"]);
            $('#requisition_type').html(row["requisition_type"]);
            $('#currency').html(row["currency"]);
            $('#fx_value').html(row["value"]);
            // $('#value_date').html(row["value_date"]);
            // $('#remarks').html((row["remarks"] == '')  ? 'N/A' : row["remarks"]);

            // var attachmentFileName = row["attachment"];
            // var attachmentFilePath = "FxRequest/" + $("#poid").val() + "/" + row["attachment"];
            // var ext = attachmentFileName.substring(attachmentFileName.lastIndexOf('.')+1).toLowerCase();
            // $('#attachment').html((row["attachment"] == '') ? 'N/A' : `<i class="icon fa-${ext}"></i> <a href="download-attachment/${attachmentFilePath}" title="${attachmentFileName}" target="_blank">${attachmentFileName}</a>`);

        });
    }

    /*---------fetch all bank list----------*/
    $.getJSON("api/bankinsurance?action=5", function (list) {
        //console.log(list);
        let text = "";
        for (i = 1; i < list.length; i++) {
            //text += list[i].text;
            text += ' <div class="form-check ">' +
                '                                    <input class="form-check-input" id="rfqBank[]" name="rfqBank[]" type="checkbox" value="' + list[i].id + '" >' +
                '                                    <label class="form-check-label" for="flexCheckDefault">' +
                list[i].text +
                '                                    </label>' +

                '                                </div>';
        }
        $('#bank_list').html(text);
    });


    /**
     * EDITED: Abir Date 13/11/21
     **/


    /*---------Validation cuttoff date time selection and Check Box selection---------*/
    function validate() {

        if ($("#cutoff_date").val() == "") {
            $("#cutoff_date").focus();
            alertify.error("Fill Up the Cutt-Off Time field!");
            return false;
        }

        if ($("input[name='rfqBank[]']").filter(":checked").length < 3) {
            alertify.error("Check At least 3 Bank List!");
            return false;

        }

        return true;
    }
});









