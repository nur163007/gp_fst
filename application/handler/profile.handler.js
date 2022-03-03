/**
 * Created by aaqa on 1/5/2017.
 */
$(document).ready(function() {

    $.get('api/users?action=6', function (data) {
        if (!$.trim(data)) {

            $("#form_error").show();
            $("#form_error").html("No data found!");
        } else {
            var row = JSON.parse(data);
            $('#firstname').val(row["firstname"]);
            $('#lastname').val(row["lastname"]);
            $('#mobile').val(row["mobile"]);
            $('#email').val(row["email"]);

            if (row["image"] == null || row["image"] == "") {
                //$('#upload div').empty().html('<img src="assets/images/no-image.png" style="width:100%;" />');
                $("#upload div").empty().html(`<img src="${const_defaultImage}" style="width:100%;" alt="..." />`);

                if ($("#btnPictureUpload").hasClass("hidden")) {
                    $("#btnPictureUpload").removeClass("hidden");
                }
                $("#btnPictureDelete").addClass("hidden");
            } else {

                //doesFileExist(_adminURL+row["image"]);
                $('#upload div').empty().html('<img src="' + row["image"] + '" style="width:100%;" />');
                //$('#upload div').empty().html(`<img src="${ doesFileExist(_adminURL+row["image"]) ? row["image"] : const_defaultImage}" style="width:100%;" />`);

                if ($("#btnPictureDelete").hasClass("hidden")) {
                    $("#btnPictureDelete").removeClass("hidden");
                }
                $("#btnPictureUpload").addClass("hidden");
            }
        }
    });

    $("#btnProfileSave").click(function (e) {

        e.preventDefault();
        if (validateProfile() === true) {
            $('#image_col').val($('#upload div img').attr('src'));
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/users",
                data: $('#formProfile').serialize(),
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    //alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            alertify.success(res['message']);
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

    /*!
     * Submit password change form
     * ****************************/
    $("#btnSecuritySave").click(function (e) {
        e.preventDefault();
        if (validatePassword()) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/users",
                data: $('#formPassword').serialize(),
                cache: false,
                success: function (response) {
                    button.disabled = false;
                    //alertify.alert(response);
                    try {
                        var res = JSON.parse(response);
                        if (res['status'] == 1) {
                            $('#formPassword')[0].reset();
                            alertify.success(res['message']);
                            window.setTimeout(function () {
                                window.location.href = 'logout';
                            }, 3000);
                            return true;
                        } else if (res['attemptRemaining'] < 1) {
                            window.location.href = 'logout';
                        } else {
                            alertify.error(res['message']);
                            return false;
                        }
                    }catch (e) {
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

    //Show toastr message if password reset is required
    if ($("#isPassResetRequired").val()) {

        toastr.options = {
            "positionClass": "toast-top-full-width",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "0",
            "hideDuration": "0",
            "timeOut": "0",
            "extendedTimeOut": "0"
        };
        Command: toastr["error"]("Your current password does not match our policy. Please read the Password Policy and update your password.", "Update password");

    }

});


function doesFileExist(urlToFile) {
    console.log(urlToFile);
    $.ajax({
        url:urlToFile,
        type:'HEAD',
        error: function()
        {
            //file not exists
            return false;
        },
        success: function()
        {
            //file exists
            return true
        }
    });
}

$(function () {

    var imgContainer = $('#upload div');
    var button = $('#btnPictureUpload'), interval;

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        autoSubmit: true,
        onComplete: function (file, response) {

            var res = JSON.parse(response);

            var tpl = $('<img src="temp/' + res['filename'] + '" style="width:100%;" />');

            // Add the HTML to the IMG container div
            imgContainer.fadeIn(function () {
                imgContainer.html(tpl)
            });

            window.clearInterval(interval);

            if ($("#btnPictureDelete").hasClass("hidden")) {
                $("#btnPictureDelete").removeClass("hidden");
            }
            $("#btnPictureUpload").addClass("hidden");
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(jpg|png|jpeg)$/i.test(ext))) {
                alert('Invalid File Format.');
                return false;
            }
            interval = window.setInterval(function () {
                //
            }, 200);
        },
        onChange: function (file, ext) {
            //$('#btnPictureUpload').html("...");
        }
    });

    //Function to delete uploaded file in server.
    $('#btnPictureDelete').click(function () {

        deleteFile($('#upload div img').attr('src'));
        $('#upload div').empty();
        if ($("#btnPictureUpload").hasClass("hidden")) {
            $("#btnPictureUpload").removeClass("hidden");
        }
        $("#btnPictureDelete").addClass("hidden");
    });

});

function deleteFile(objfile) {

    $.ajax({
        url: 'application/library/uploadhandler.php?del=' + encodeURI(objfile.replace("../", "")),
        success: function (result) {
            var res = JSON.parse(result);
            if(res['status']==1) {
                alertify.success("Deleted");
            }
        }
    });
}
/**
 * Toggle password
 * */
function togglePassword() {
    $("#currentPassword, #newpassword, #confirmnewpassword").each(function(){
        if (this.type === "password") {
            this.type = "text";
        } else {
            this.type = "password";
        }
    });
}

function validateProfile()
{
    if($("#firstname").val()=="")
    {
        alertify.error("First Name cannot be blank!");
        $("#firstname").focus();
        return false;
    }
    if($("#lastname").val()=="")
    {
        alertify.error("Last Name cannot be blank!");
        $("#lastname").focus();
        return false;
    }
    if($("#mobile").val()=="")
    {
        alertify.error("Mobile number cannot be blank!");
        $("#mobile").focus();
        return false;
    }
    if($("#email").val()=="")
    {
        alertify.error("Email cannot be blank!");
        $("#email").focus();
        return false;
    }
    return true;
}

function validatePassword()
{
    if($("#currentPassword").val()==""){

        alertify.error("Please enter your current password.");
        $("#currentPassword").focus();
        return false;

    }
    if($("#newpassword").val()==""){

        alertify.error("Password cannot be blank!");
        $("#newpassword").focus();
        return false;

    }
    if($("#confirmnewpassword").val()==""){

        alertify.error("Please confirm your new password!");
        $("#confirmnewpassword").focus();
        return false;

    }
    if($("#passScore").val()<4){
        alertify.error("Your password is not strong enough. Please Generate one.");
        $("#newpassword").focus();
        return false;
    }
    return true;
}
