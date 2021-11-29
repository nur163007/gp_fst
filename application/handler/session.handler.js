$(document).ready(function() {
    getSession();
    setInterval("getSession()", 7000);
    $.get('api/session?sess_ext=1', function (data) {});
});

function getSession(){
    var returnTo = window.location.pathname + window.location.search;
    $.ajax({
        url: _adminURL + 'api/session?v=1',
        global: false,
        success: function(data) {
    		 if( data == 0 ) {
    			 //alertify.alert("Session expired");
    			 //var p = qs('page');
    			 window.location.href = _adminURL + 'login?returnto=' + returnTo;
    		 } else if (data == 1 ) {
    			 //alert("Session active");
    		 }
    	 }
    });
}