/**
 * Created by aaqa on 2/19/2017.
 */

$(document).ready(function(){

    $('#btnSendAccessRequest').click(function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "api/access-denied",
            data: "module="+$("#module").val(),
            cash: false,
            success: function(res){

            }
        });
    });

});