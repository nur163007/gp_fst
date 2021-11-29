// JavaScript Document
$(function() {
	$("#login_btn").click(function (e) {
		e.preventDefault();
		//alert(location.search);
		//alert(location.protocol +"//"+ location.host+"/"+location.search.replace("?returnto=/",""));
		if (validate() === true) {
			var button = e.target;
			//$(this).html("Processing...");
			button.disabled = true;
			$.ajax({
				type: "POST",
				url: "api/login",
				data: $('#login-form').serialize(),
				cache: false,
				success: function (response) {
					button.disabled = false;
					//alert(response);
					try {
						var row = JSON.parse(response);

						if (row['success'] == 1) {
							//var nextPage = getParameterByName('returnto');
							var nextPage = location.protocol + "//" + location.host + "/" + location.search.replace("?returnto=/", "")
							//alert(nextPage);
							// window.location.href = '/GPST/wc-admin/';
							if (location.search == '') {
								window.location.href = _adminURL;
							} else {
								window.location.href = nextPage;
							}
							// alert(html);
						} else {
							$('#login_error').html(row['msg']).show();
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
		}
		return false;
	});

	$(".toggle-password").click(function () {

		$(this).toggleClass("wb-eye wb-eye-close");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});

});

function validate()
{
	if ($("#inputUserName").val() == "") {
		alertify.error('Please enter your user name.');
		$("#inputUserName").focus();
		return false;
	}
	if ($("#inputPassword").val() == "") {
		alertify.error('Please enter your password');
		$("#inputPassword").focus();
		return false;
	}
	return true;
}

