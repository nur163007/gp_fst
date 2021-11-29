/**
 * Created by Shohel Iqbal on 4/5/2017.
 * JavaScript Document
 */

$(function() {
    $("#btnResetPassword").click(function(e) {
        e.preventDefault();
        //alert(location.search);
        //alert(location.protocol +"//"+ location.host+"/"+location.search.replace("?returnto=/",""));
        if (validate() === true) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "POST",
                url: "api/forgot-password",
                data: $('#forgotpass-form').serialize(),
                cache: false,
                success: function (html) {
                    button.disabled = false;
                    var row = JSON.parse(html);

                    if (row['success'] == 1) {
                        //alert(html);
                        $("#login_error").html(row["msg"]).show();
                        window.location.href = _adminURL;
                    } else {
                        $('#login_error').html(row['msg']).show();
                        return false;
                    }
                }
            });
        }
        return false;
    });
});

function validate()
{
    if($("#inputUserEmail").val()=="")
    {
        $('#login_error').html('Email can not be blank!').show();
        $("#inputUserEmail").focus();
        return false;
    }else{
        if(!validEmail($("#inputUserEmail").val())){
            $('#login_error').html('Invalid Email address!').show();
            $("#inputUserEmail").focus();
            return false;
        }
    }
    return true;
}

