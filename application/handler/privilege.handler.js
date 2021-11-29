/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 21.01.2016
*/

$(document).ready(function() {
    
	$('#dtPrivilege').dataTable( {
		"ajax": "api/privilege?action=1&id=1",
		"columns": [
			{ "data": "id", "class": "text-center" },
			{ "data": "roleid", "visible": false },
			{ "data": "name" },
			{ "data": "url" },
			{
                "data": null, "class": "text-center", "sortable": false,
                "render": function (data, type, full) {
                    if (full["category"] == "1") return '<i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i>';
                    else return '';
                }
            },
			{ "data": null, "sortable": false, "class": "text-center",
				"render": function(data, type, full) {
				    if(full['access']==0)
					   return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="Enable" data-original-title="Enable" onclick="ChangeEnable(' + full['roleid'] + ', ' + full['id'] + ', 1)"><i class="icon wb-check" style="color:lightgray" aria-hidden="true"></i></button>';
                    else
                        return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="Enable" data-original-title="Enable" onclick="ChangeEnable(' + full['roleid'] + ', ' + full['id'] + ', 0)"><i class="icon wb-check" style="color:limegreen" aria-hidden="true"></i></button>';
				  }
			}],
		"sDom": 'frtipS',
		"paging": true
	});
    
     $.get("api/privilege?action=2", function (list) {
        //alert(list);
        $('#userRole').html(list);   
     });    
});

function RefreshPrivilege(roleid){
    
    var dtable = $('#dtPrivilege').dataTable();						
	dtable.api().ajax.url('api/privilege?action=1&id='+roleid).load();
}

function ChangeEnable(roleid, navid, action){
    $.get('api/privilege?action=3&roleid='+roleid+'&navid='+navid+'&access='+action, function (data) {
        //alert(data);
        if(data==1){
			RefreshPrivilege(roleid);
            alertify.success("UPDATED!");
		} else{
			alertify.error("UPDATE FAIL!");
		}
	});
}


    