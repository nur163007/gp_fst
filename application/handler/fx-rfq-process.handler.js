$(document).ready(function () {

    var fxRequestId = $('#poid').val().substr(5);

    $('#hdnFxRequestId').val(fxRequestId);
    $('#fxReqIdMsg').val(fxRequestId);

    $('#BankData').dataTable({
        "ajax": "api/fx-request?action=4&id=" + fxRequestId,
        "columns": [
            {"data": "Id", "visible": true},
            {"data": "BankName"},
            {"data": "FxRate"},
            {"data": "OfferedVolumeAmount"},
            {"data": "value_date"},
            {"data": "value"},
            {"data": "remarks"},
            {"data": "PotentialLoss"},
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function (data, type, full) {
                    return '<input type="hidden" id="hdnFxRfqRowId" name="hdnFxRfqRowId[]" value="' + full['Id'] + '">' +
                        '<input type="number" class="form-control" id="DealAmount" name="DealAmount[]" placeholder="0" value="' + full['DealAmount'] + '" style="width: 100px">';
                }
            },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function (data, type, full) {
                    if (full["Selected"] == 0) {
                        return '<div class="form-check">\n' +
                            '<input type="hidden" id="SelectCheckbox" value="0" name="SelectCheckbox_' + full['Id'] + '" >' +
                            '  <input class="form-check-input" type="checkbox" value="1" id="SelectCheckbox" name="SelectCheckbox_' + full['Id'] + '">\n' +
                            '</div>';
                    } else {
                        return '<div class="form-check">\n' +
                            '<input type="hidden" id="SelectCheckbox" value="0" name="SelectCheckbox_' + full['Id'] + '" >' +
                            '  <input class="form-check-input" type="checkbox" checked value="1" id="SelectCheckbox" name="SelectCheckbox_' + full['Id'] + '">\n' +
                            '</div>';
                    }
                }
            }
        ],
        "sDom": 'rtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });

    $.get('api/fx-request', function (response) {
        if (response == '')
            $('#fxLastMsgId').val(0);
        else
            $('#fxLastMsgId').val(response);
    });

    /*---------Textarea Character Count---------------*/
    const messageEle = document.getElementById('FxConvMsg');
    const counterEle = document.getElementById('display_count');

    messageEle.addEventListener('input', function (e) {
        const target = e.target;

        // Get the `maxlength` attribute
        const maxLength = target.getAttribute('maxlength');

        // Count the current number of characters
        const currentLength = target.value.length;

        counterEle.innerHTML = `${currentLength}/${maxLength}`;
    });

    // Loading HOT-FSO conversation
    GetCoversation(fxRequestId);
    // GetApprovalLog ID
    GetApprovalLog(fxRequestId);

});

/*-------submit DealAmount----------*/
$("#btnSubmitRFQtoHOT").click(function (e) {

    e.preventDefault();
    alertify.confirm( 'Are you sure to submit this request?', function (e) {

        if(e) {
            /*------Checkbox Validation---------*/
            if($('input[type=checkbox]:checked').length == 0)
            {
                alertify.error("Please select at least one checkbox");
                return  false;
            }else {
                $.ajax({
                    type: "POST",
                    url: "api/fx-rfq-process",
                    data: $('#frmDealAmount').serialize(),
                    cache: false,
                    success: function (html) {
                        //alert(html);
                        var res = JSON.parse(html);
                        if (res['status'] == 1) {
                            alertify.success("Saved SUCCESSFULLY!");
                            location.replace("dashboard");
                            return true;
                        } else {
                            alertify.error("FAILED!");
                            return false;
                        }
                    }
                });
            }
            return true;
        }
        else {

        }
    });

});

/*-----------Submit Message------------*/
$("#fsomessage").click(function (e){
    e.preventDefault();
    //alert($('#fxreqmesgfso').serialize());
    if ($('#FxConvMsg').val() ==""){
        $("#FxConvMsg").focus();
        $("#FxConvMsg").css("border-color" ,"red");
        alertify.error("Could not submitt empty text!");
        return false;
    }
    else {$.ajax({
        type: "POST",
        url: "api/fx-rfq-process",
        data: $('#fxreqmesgfso').serialize(),
        cache: false,
        success: function (response) {
            // alert(response);
            var res = JSON.parse(response);
            //alertify.success(res['message']);
            $('#FxConvMsg').val("");
            GetCoversation($("#fxReqIdMsg").val());
        }

    });
    }
    return true
})

/* Accept */
$("#btnHOTAccept").click(function (e) {

    e.preventDefault();
    alertify.confirm( 'Are you sure you want to Accept this request?', function (e) {

        if(e) {
            $("#postAction").val(2);
            $.ajax({
                type: "POST",
                url: "api/fx-rfq-process",
                data: $('#frmDealAmount').serialize(),
                cache: false,
                success: function (html) {
                    var res = JSON.parse(html);
                    alertify.success("Saved SUCCESSFULLY!");
                    location.replace("dashboard");
                }
            });
        }
        else {

        }
    });

})

/* Reject */
$("#btnHOTReject").click(function (e) {

    //alert("hi");
    e.preventDefault();
    //alert($('#formstat').serialize());
    if ($('#reject_note').val() =="") {
        $("#reject_note").focus();
        $("#reject_note").css("border-color", "red");
        $("#form_error").show();
        $("#form_error").html("Message cannot be blank!");
        // alertify.error("Could not submitt empty text!");
        $('#reject_note').val("");
        return false;
    }
    else {

        alertify.confirm( 'Are you sure to reject this request?', function (e) {

            if(e) {
                $("#postAction").val(3);
                $.ajax({
                    type: "POST",
                    url: "api/fx-rfq-process",
                    data: $('#frmDealAmount').serialize(),
                    cache: false,
                    success: function (html) {
                        var res = JSON.parse(html);
                        alertify.error("Rejected SUCCESSFULLY!");
                        location.replace("dashboard");
                    }
                });
            }
            else {

            }
        });

    }

    return true;
})



function GetLastMsgId(fxreqid) {
    //console.log('api/fx-request?action=8&fxreqid='+ fxreqid);
    $.get('api/fx-request?action=8&fxreqid='+ fxreqid, function (data){
        //console.log(data);
        $("#fxLastMsgId").val(data);
    });
}

/*-----------Get Approval Log-----------------*/
function GetApprovalLog(fxreqid) {
    $.get('api/fx-request?action=9&fxreqid='+ fxreqid, function (data){
        // console.log(fxreqid);
        var x = JSON.parse(data);
        $("#tbapprovalLog").html("");
        for (var i=0; i<x.length; i++){
            // console.log(x[i].Role);
            $("#tbapprovalLog").append("<tr>\n" +
                "                                                                                    <td>"+x[i].FxRequestId+"</td>\n" +
                "                                                                                    <td>"+x[i].username+"</td>\n" +
                "                                                                                    <td>"+x[i].Role+"</td>\n" +
                "                                                                                    <td>"+x[i].ActionOn+"</td>\n" +
                "                                                                                    <td>"+x[i].ActionDone+"</td>\n" +
                "                                                                                    <td>"+x[i].Remarks+"</td>\n" +
                "                                                                                </tr>");
        }
    });
}

/*------Get conversation from hot module------*/
function GetCoversation(fxreqid) {
    //alert('api/fx-request-hot?action=5&fxreqid='+ fxreqid);
    $.get('api/fx-request-hot?action=5&fxreqid='+ fxreqid, function (data) {
        //alert(data.length);
        var x = JSON.parse(data);
        // console.log(x);
        // let text="";
        var align = 'left';
        var bgcolor = '#fffff';
        var forcolor = '#000000';
        $("#messageLoopView").html("");
        for (var i=0 ; i<x.length;){
            // alert((x[i].MsgText));
            if (x[i].Title == "FSO Message") {

                align = 'left';
                bgcolor = '#efefef';
                forcolor = '#000000';
            }  // even
            else {
                align = 'right';
                bgcolor = '#62a8ea';
                forcolor = '#f5ffff';
            } // odd
            $("#messageLoopView").append("<div style=\"text-align: "+align+"\" >\n" +
                "                                                                                    <div class=\"list-group-item\" role=\"menuitem\"  style=\"background-color: "+bgcolor+"; border-radius: 25px;border-top-right-radius: 0px;margin-bottom: 20px;\">\n" +
                "                                                                                        <div class=\"media-body\">\n" +
                "                                                                                            <h6 class=\"media-heading\" style=\"color: "+forcolor+";\">"+x[i].Title+"</h6>\n" +
                "                                                                                            <div class=\"media-meta\">\n" +
                "                                                                                                <time datetime=\"2015-06-17T20:22:05+08:00\" style=\"color: "+forcolor+";\">\n" +
                "                                                                                                    "+x[i].Datetime+"\n" +
                "                                                                                                </time>\n" +
                "                                                                                            </div>\n" +
                "                                                                                            <div class=\"media-detail\" style=\"color: "+forcolor+";white-space: pre-line;\">\n" +
                "                                                                                                "+x[i].MsgText+"\n" +
                "                                                                                            </div>\n" +
                "                                                                                        </div>\n" +
                "                                                                                    </div>\n" +
                "                                                                                </div>");
            i++;
        };

        $('#exampleScollableApi').stop().animate({scrollTop: $('#exampleScollableApi')[0].scrollHeight}, 800);

        // Getting last message id for this FX Request
        GetLastMsgId($("#fxReqIdMsg").val());

    });
}

/*---------RFQ Modification-----------*/
$("#btnOpenRfqforEdit").click(function (e) {
    e.preventDefault();
    alertify.confirm( 'Are you sure you want to open this RFQ?', function (e) {
        if(e) {
            fxreqid = $('#hdnFxRequestId').val();
            $.get('api/fx-request?action=10&fxreqid='+ fxreqid, function (response){
                var res = JSON.parse(response);
                alertify.success(res['message']);
            });
        }
        else {

        }
    });
});