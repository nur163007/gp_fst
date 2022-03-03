$(document).ready(function() {

    $('#dtActionTable').dataTable({
        "ajax": "api/workflow-procedure?action=1",
        "columns": [
            {"data": "ID"},
            {"data": "ActionDone"},
            {"data": "ActionDoneBy"},
            {"data": "ActionPending"},
            {"data": "ActionPendingTo"},
            {"data": "TargetForm"},
            {"data": "stage"},
            {"data": "serialNo"},
            {
                "data": null, "sortable": false, "class": "text-center",
                "render": function (data, type, full) {
                    return '<button class="btn btn-lg btn-icon btn-flat btn-default" data-target="#actionForm" data-toggle="modal" data-toggle="Edit Action" data-original-title="Edit Action" onclick="OpenAction(' + full['ID'] + ')"><i class="fas fa-pencil-square-o text-info" aria-hidden="true"></i></button>';
                }
            }
            ],

        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false,
        "paging":true,
    });

    $.getJSON("api/workflow-procedure?action=2", function (list) {
        $("#actionDoneBy").select2({
            data: list,
            placeholder: "Action Done By",
            allowClear: false,
            width: "100%"
        });
    });

    $.getJSON("api/workflow-procedure?action=3", function (list) {
        $("#actionPendingTo").select2({
            data: list,
            placeholder: "Action Pending To",
            allowClear: false,
            width: "100%"
        });
    });

    $("#btnActionFormSubmit").click(function (e) {

        e.preventDefault();
        if (validate()) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/workflow-procedure",
                data: $('#form-action').serialize(),
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    //alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            ResetForm();
                            $('#actionForm').modal('hide');
                            var dtable = $('#dtActionTable').dataTable();
                            alertify.success("Saved SUCCESSFULLY!");
                            dtable.api().ajax.reload();
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

function OpenAction(id)
{
    // console.log(id)
    $.get('api/workflow-procedure?action=4&id='+id, function (data) {
        if(!$.trim(data)){
            $("#form_error").show();
            $("#form_error").html("No data found!");
        } else{
            // console.log(data)
            var row = JSON.parse(data);

            $("#actionId").val(row["ID"]);
            $('#id').val(HTMLDecode(row["ID"]));

            $('#actionDone').val(row["ActionDone"]);

            if(row["ActionDoneBy"]!=""){
                $('#actionDoneBy').val(row["ActionDoneBy"]).change();
            }
            $('#actionPending').val(row["ActionPending"]);

            if(row["ActionPendingTo"]!=""){
                $('#actionPendingTo').val(row["ActionPendingTo"]).change();
            }
            $('#cc').val(row["cc"]);
            $('#targetForm').val(row["TargetForm"]);
            $('#sla').val(row["SLA"]);
            $('#stage').val(row["stage"]);
            $('#serialNo').val(row["serialNo"]);

            if(row["isRejected"]==0){
                $('#isRejected').removeAttr('checked');
            } else {
                $('#isRejected').attr('checked','checked');
            }
        }
    });
}

function ResetForm() {

    $('#form-action')[0].reset();
    $("#actionId").val("0");
    $('#actionDoneBy').val("0").change()
    $('#actionPendingTo').val("0").change()
    $("#form_error").empty();
    $("#form_error").hide();
}

function validate()
{
    if($("#id").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("ID cannot be blank!");
        $("#id").focus();
        return false;
    }

    if($("#actionDone").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Done Action cannot be blank!");
        $("#actionDone").focus();
        return false;
    }

     if($("#actionDoneBy").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Done By required!");
        $("#actionDoneBy").focus();
        return false;
    }

    if($("#actionPending").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Pending Action cannot be blank!");
        $("#actionPending").focus();
        return false;
    }

    if($("#actionPendingTo").val()=="")
    {
        $("#form_error").show();
        $("#form_error").html("Pending To required!");
        $("#actionPendingTo").focus();
        return false;
    }
    return true;
}
