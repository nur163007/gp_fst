/*
	Author: Shohel Iqbal
	Copyright: 01.2016
	Code fridged on:
*/
var dtInit = false;
var dtInitLCWise = false;
var barChart1Init = false, barChart2Init = false;

$(document).ready(function (e) {

//`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `XRefID`, `ActionBy`, `ActionByRole`, `ActionOn`, `ActionFrom`
$('#dtMyInbox').dataTable( {
    "ajax": "api/dashboard?action=1&my=true",
    "columns": [
        { "data": "ID", "visible": false },
        { "data": "RefID", "visible": false },
        { "data": null, "class": "padding-6",
            "render": function(data, type, full) {
                 return '<input type="checkbox" id="checkOne" name="checkOne[]" value='+full["RefID"]+' >';
                }
        },
        { "data": null, "class": "padding-5",
            "render": function(data, type, full) {
                if(parseInt(full['marge'])==0 || parseInt(full['marge'])==2){
                    if(full["shipNo"]==""){
                        return "<a href=\""+full["TargetForm"]+"?po="+full["PO"]+"&ref="+full["ID"]+"\">"+full["PO"]+"</a>";
                    }else{
                        /*return "<a href=\""+full["TargetForm"]+"?po="+full["PO"]+"&ship="+full["shipNo"]+"&ref="+full["ID"]+"\">"+full["PO"]+"<br />Ship # "+full["shipNo"]+"</a>";*/
                        if (full["eaRefNo"] == "")
                        {
                            return `<a href="${full["TargetForm"]}?po=${full["PO"]}&ship=${full["shipNo"]}&ref=${full["ID"]}">${full["PO"]}<br />Ship # ${full["shipNo"]}</a>`;
                        }else {
                            return `<a href="${full["TargetForm"]}?po=${full["PO"]}&ship=${full["shipNo"]}&ref=${full["ID"]}">${full["PO"]}<br />Ship # ${full["shipNo"]}<br />${full["eaRefNo"]}</a>`;
                        }
                    }
                }else{
                    return "<a href=\"javascript:void(0);\" onclick=\"javascript:alertify.alert('More feedback required to proceed.');\">"+full["PO"]+"</a>";
                }
            }
        },
        { "data": null, "class": "text-left padding-5",
            "render": function(data, type, full) {
                if(full["ID"]!="&nbsp;"){
                    if(full["lastStatus"]=="-1") {
                        return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-danger">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
                    }else{
                        return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-default">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
                    }
                } else { return ""; }
            }
        },
        { "data": "stage"},
        { "data": "Buyer", "class": "padding-5" },
        { "data": "ActionOn", "visible": false },
    ],
    "order": [[ 7, "desc" ]],
    "sDom": 'frtip',
    "bSort": true
});

$('#dtOtherInbox').dataTable( {
    "ajax": "api/dashboard?action=1&my=false",
    "columns": [
        { "data": "ID", "visible": false },
        { "data": "RefID", "visible": false },
        { "data": null, "class": "padding-5",
            "render": function(data, type, full) {
                if(parseInt(full['marge'])==0 || parseInt(full['marge'])==2){
                    if(full["shipNo"]==""){
                        return "<a href=\"view-po?po="+full["PO"]+"&ref="+full["ID"]+"\">"+full["PO"]+"</a>";
                    }else{
                        return "<a href=\"view-po?po="+full["PO"]+"&ship="+full["shipNo"]+"&ref="+full["ID"]+"\">"+full["PO"]+"<br />Ship # "+full["shipNo"]+"</a>";
                    }
                }else{
                    return "<a href=\"javascript:void(0);\" onclick=\"javascript:alertify.alert('More feedback required to proceed.');\">"+full["PO"]+"</a>";
                }
            }
        },
        { "data": null, "sortable": false, "class": "text-left padding-5",
            "render": function(data, type, full) {
                if(full["ID"]!="&nbsp;"){
                    if(full["lastStatus"]=="-1") {
                        return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-danger">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
                    }else{
                        return '<span class="block text-success"><strong>Pending: ' + full["ActionPending"] + '</strong></span><span class="block text-default">' + full["ActionDone"] + '</span><span class="text-' + full["criticality"] + '"><i class="icon fa-clock-o" aria-hidden="true"></i> ' + full["pendingFor"] + ' days old<span>'
                    }
                } else { return ""; }
            }
        },
        { "data": "stage"},
        { "data": "Buyer", "class": "padding-5" },
        { "data": null, "class": "text-left padding-5",
            "render": function(data, type, full){
                if(full["ActionPendingTo"] == const_role_Supplier ){
                    return full["PendingToRoleName"] + "<br />"+ full["CoName"];
                } else {
                    return full["PendingToRoleName"];
                }
            }
        },
        { "data": "ActionOn", "visible": false }
    ],
    "order": [[ 7, "desc" ]],
    "sDom": 'ftip',
    "bSort": true
});

    //btrc division select
    $.getJSON("api/category?action=4&id=100", function(list) {
        // console.log(list);
        $("#btrc_div").select2({
            data: list,
            placeholder: "Select btrc division",
            allowClear: false,
            width: "100%"
        });
    });
    $("#btnCaSubmit").click(function () {
        getCheckData('checkOne');
    });

    var getCheckData = function (refid) {
        var val = [];
        $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
        });
        var btrc = $("#btrc_div").val();
        // console.log(btrc);
        alertify.confirm( 'Are you sure you want submit?', function () {
        $.ajax({
            type: "POST",
            url: "api/ca-interface?action=1",
            dataType: "JSON",
            data:{
                val:val,
                btrc_div: btrc
            },
            cache: false,
            success: function (result) {
                console.log(result);
                // $('.table').DataTable().ajax.reload();
                if (result['status'] == 1) {
                    alertify.success(result['message']);
                    var mtable = $('#dtMyInbox').dataTable();
                    mtable.api().ajax.reload();
                    var otable = $('#dtOtherInbox').dataTable();
                    otable.api().ajax.reload();
                    $('#btrc_div').val('').change();
                } else {
                    alertify.error(result['message']);
                    return false;
                }
            },
            error: function (xhr) {
                console.log('Error: ' + xhr);
            }
          });
        });
    };
    });


