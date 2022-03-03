const date = new Date();
const year = date.getFullYear();
const month = date.toLocaleString('default', { month: 'short' });
$(document).ready(function() {

    $('#lcProcessingDocs').dataTable({
        "ajax": "api/lc-processing-docs?action=1",
        "columns": [
            {"data": "id"},
            {"data": "docName"},
            // {
            //     "data": null, "sortable": true, "class": "text-center",
            //     "render": function (data, type, full) {
            //         if (full['filename'] != null){
            //             // $('#replaceDocAttachmentPrev').html(full['filename']);
            //             // $('#replaceDocAttachOld').val(full['filename']);
            //             // return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="modal"  data-original-title="download file" onclick="EditLCDoc(' + full['id'] + ')"><i class="fas fa-download" aria-hidden="true"></i></button>';
            //             return '<a href="docs/LCDocuments/'+year+'/'+month+'/'+ full['docName']+full['id']+'/'+ full['filename']+'" download style="text-decoration: none;" target="_blank"><i class="fas fa-download" aria-hidden="true"></i></a>';
            //         }
            //         else {
            //             // $('#replaceDocAttachOld').val('');
            //             return '<h5>No File</h5>';
            //         }
            //        }
            // },
            {
                "data": null, "sortable": false, "class": "text-center",
                "render": function (data, type, full) {
                    if ($("#usertype").val() == const_role_LC_Operation) {
                        if (full['filename'] != null){
                            return '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-target="#replaceDocAttach" data-toggle="modal"  data-original-title="Edit LC doc" onclick="EditLCDoc(' + full['id'] + ')"><i class="fas fa-upload" aria-hidden="true"></i></button>&nbsp;' +
                                '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-target="#viewAllDocAttach" data-toggle="modal" data-original-title="View LC doc" onclick="getAllDocs(' + full['id'] + ')"><i class="fas fa-list"></i></button>';
                        }
                        else {
                            return '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-target="#replaceDocAttach" data-toggle="modal"  data-original-title="Edit LC doc" onclick="EditLCDoc(' + full['id'] + ')"><i class="fas fa-upload" aria-hidden="true"></i></button>&nbsp;' +
                                '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" ><i class="fas fa-ban" aria-hidden="true"></i></button>';
                        }
                    }
                    else if ($("#usertype").val() == const_role_lc_bank) {
                        if (full['filename'] != null){
                            return '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" data-target="#viewAllDocAttach" data-toggle="modal" data-original-title="View LC doc" onclick="getAllDocs(' + full['id'] + ')"><i class="fas fa-download" aria-hidden="true"></i></button>';
                        }
                        else {
                            return '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" ><i class="fas fa-ban" aria-hidden="true"></i></button>';
                        }
                    }

                      }
            }],
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false,
        // "order": [[2, "desc"]]

    });

});

function EditLCDoc(id) {
    // $('#replaceDocAttachOld').html('ok');
    $("#replaceDocAttachment_btn").click(function (e) {
        // alert('clicked');
        e.preventDefault();
        if (validate() === true) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/lc-processing-docs",
                    data: $('#lcProcessingDocuments').serialize() + '&userAction=1' + '&id='+id,
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res["status"] == 1) {
                                ResetForm();
                                alertify.success(res['message']);
                                $('#replaceDocAttach').modal('hide');
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

function getAllDocs(id) {
    $.ajax({
        type:"GET",
        dataType:"json",
        url: "api/lc-processing-docs?action=2&id="+id,
        success:function (response) {
            // console.log((response))
            var get_data ="";
            for (let i = 0; i < response.length; ++i) {

                get_data +='<tr>'+
                    '<td>' +response[i].id+ '</td>'+
                    '<td>'+
                    '<a href="docs/LCDocuments/'+year+'/'+month+'/'+ response[i].docName + response[i].id+'/'+response[i].filename+'" download style="text-decoration: none; color: red;" target="_blank"> '+ response[i].filename + "  &nbsp;<span class='text-success'>(download now)</span>"+'</a>' +
                    '</td>'+
                    '<td>' +response[i].updatedDate+ '</td>'+
                    '</tr>';
            }
            $("#myTable").html(get_data);
        },
        error:function (err) {
            console.log(err);
        }
    });
}

function validate() {
    if ($("#replaceDocAttachNew").val() == "") {
        $("#replaceDocAttachNew").focus();
        alertify.error("Give an attahment");
        return false;
    }
    return true;
}
function ResetForm() {
    $('#lcProcessingDocuments')[0].reset();
}

$(function () {

    var button = $('#btnReplaceDocAttachNew'), interval;
    var txtbox = $('#replaceDocAttachNew');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
                var text = txtbox.val();
                if (txtbox.val().length < 13) {
                    txtbox.val(txtbox.val() + '.');
                } else {
                    txtbox.val('Uploading');
                }
            }, 200);
        }
    });
});