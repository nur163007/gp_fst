var poid = $('#pono').val();
var shipno = $('#shipno').val();
var podata, comments, attach;
$(document).ready(function() {
    if(poid){

        $.get('api/ici-interface?action=1&id='+poid, function (data) {

            if(!$.trim(data)){
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            }else {

                var row = JSON.parse(data);
                podata = row[0][0];
                attach = row[1];

                // PO info
                /*$('#ponum').html(poid);
                $('#insurancebank').html(podata['insurancebank']);
                $('#icvalue').html(commaSeperatedFormat(podata['povalue'])+' '+podata['curname']);
                $('#lcdesc').html(podata['lcdesc']);
                $('#supplier').html(podata['supname']);
                $('#pi_num').html(podata['pinum']);
                $('#shipmode').html(podata['shipmode'].toUpperCase());*/

                //alert(attach.length);
                // var attachList = ["CI Scan Copy", "Insurance Cover Note", "AWB/BL Scan Copy"];
                attachmentLogScript(attach, '#usersAttachments'/*, 1, attachList*/);

            }
        });
    }

    $(function () {
        $("#btnInsuranseFileSubmit").click(function (e) {
            // alert('clicked');
            $('#userAction').val('3');
            e.preventDefault();
            if (validate() === true) {
                alertify.confirm('Are you sure you want submit?', function () {
                    $.ajax({
                        type: "POST",
                        url: "api/ici-interface",
                        data: $('#form-cn-request').serialize(),
                        cache: false,
                        success: function (response) {
                            // alert(response);
                            try {
                                var res = JSON.parse(response);
                                console.log(res)
                                if (res["status"] == 1) {
                                    // ResetForm();
                                    alertify.success(res['message']);
                                    window.location.href = _dashboardURL;
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

                });

            }else{
                return false;
            }
        });
    });



});

function validate() {

    if ($("#attachIcFile").val() == "") {
        $("#attachIcFile").focus();
        alertify.error("Attached policy file!");
        return false;
    }

    return true
}

// Insurance File Upload
$(function () {

    var button = $('#btnUploadIcFile'), interval;
    var txtbox = $('#attachIcFile');

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