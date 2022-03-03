
var podata;
$(document).ready(function() {

    //SELECT PO FROM ACTION LOG
    $.getJSON("api/po-history?action=1", function (list) {
        //alert('sds')
        $("#poNo").select2({
            data: list,
            placeholder: "Select a PO",
            allowClear: false,
            width: "100%"
        });
    });
    $("#poNo").on("select2:select", function (e) {
   var poid = $("#poNo").val();
   // alert(poid)
    //DATA TABLE FOR ACTION LOG
    $('#tablePoHistory').dataTable({
        "ajax": "api/po-history?action=2&id="+poid,
        "columns": [
            {"data": "ID"},
            {"data": "ActionDone"},
            {"data": "ActionOn"},
            {"data": "ActionDoneBy"},
            {"data": "ActionPendingTo"},
            {
                "data": null, "sortable": false, "class": "text-center",
                "render": function (data, type, full) {
                    if (full["Status"] == 0) {
                        return `<button type="button" data-target="#ActionLogDeleteForm" data-toggle="modal" onclick="DeleteAction( ${full["ID"]})" style="background: none; border: none"><i class="fas fa-times" aria-hidden="true"></i></button>`;
                    }
                    else if (full["Status"] == 3){
                        return `<button type="button" data-target="#ActionLogDeleteInfo" data-toggle="modal" onclick="ShowActionInfo( ${full["ID"]})" style="background: none; border: none"><i class="fa fa-info-circle" style="color: black" aria-hidden="true"></i></button>`;
                    }else {
                        return `<p class="text-success" ><i class="fa fa-check" aria-hidden="true"></i></p>`;
                    }}
            }
            ],
        "rowCallback": function( row, data, index ) {
            if ( data["Status"] == 0 )
            {
                $('td', row).css('color', 'Red');
            }
            else if(data["Status"] == 3){
                $('td', row).css('background-color', '#E1E0E0');
            }
            else
            {
                $('td', row).css('color', '#000000');
            }
        },
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false,
        "destroy": true,
        "paging":false,
        "aaSorting": [[0,'desc']]
    });
    });
});

function DeleteAction(id) {

    $("#btnDeleteAction").click(function (e) {
        // alert('clicked');
        e.preventDefault();
        if (validate() === true) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/po-history?action=3&id="+id,
                    data: $('#formPoHistory').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res["status"] == 1) {
                                ResetForm();
                                alertify.success(res['message']);
                                $('#praRejectForm').modal('hide');
                                location.reload(true);
                            } else {
                                //$("#SendPO_btn").show();
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
                        alertify.error(textStatus + ": " + xhr.status + " " + error);
                    }
                });
            }else {

            }
        }else{
            return false;
        }
    });
}

function ShowActionInfo(id) {
    $.get('api/po-history?action=4&id='+id, function (data) {
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            // console.log(row)
            podata = row[0][0];

            $('#pono').html(podata['PO']);
            if ((podata['isDeleted'])==1){
                $('#status').html('Deleted');
            }
            $('#deletedBy').html(podata['firstname']);
            $('#deletedOn').html(podata['deletedOn']);
            $('#deleteRemarks').html(podata['deleteRemarks']);
        }
    });
}

function validate() {
    if ($("#remarks").val() == "") {
        $("#remarks").focus();
        alertify.error("Specify the Reason");
        return false;
    }
    return true;
}

function ResetForm() {
    $('#formPoHistory')[0].reset();
}