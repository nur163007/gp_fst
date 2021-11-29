$(document).ready(function() {
    getNotification();
	setInterval("getNotification()", 60*1000);
});

function getNotification(){
    $.ajax({
        url: 'api/notification?action=1',
        global: false,
        success: function(data) {
            //alert(data);
            var row = JSON.parse(data);
    		 if( data!="" ) {
    			 $("#notificationCount1").html(row[0]);
    			 $("#notificationCount2").html('New '+row[0]);
    			 $("#userNotification").html(row[1]);
    		 } else{
    		    $("#notificationCount1").html(row[0]);
    		    $("#notificationCount2").html(row[0]);
                $("#userNotification").html('There is no unread notification');
    		 }
    	 }
    });
}

function markAsRead(nId){
    
    $.ajax({
        url: 'api/notification?action=2&id='+nId,
        global: false,
        success: function(data) {
            getNotification();
    	 }
    });
}

function openNotification(url, nId){
    $.ajax({
        url: 'api/notification?action=2&id='+nId,
        global: false,
        success: function(data) {
            window.location.href = url;
    	}
    });
    
}