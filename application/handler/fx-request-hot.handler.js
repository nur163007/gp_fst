$dtInit = 0;
$(document).ready(function () {
    $('#dtFx').dataTable({
        "ajax": "api/fx-request-hot?action=1&status=3",
        "columns": [
            {
                "data": null,
                "sortable": true,
                "class": "text-center",
                "render": function(data, type, full) {
                    if (full["status"] == 1){
                        return "<a style='text-decoration: none' href=\""+"fx-rfq-request"+"?action=2&id="+full["id"]+"\">"+full["id"]+"</a>";
                    }
                    else{
                        return "<p class='text-success'>"+full["id"]+"</p>";
                    }
                }
            },
            { "data": "supplier_name" },
            { "data": "nature_of_service" },
            { "data": "req_type" },
            { "data": "currency" },
            { "data": "fx_value" },
            { "data": "value_date" },
            { "data": "CuttsOffTime" },
            {
                "data": null,
                "sortable": false,
                "class": "text-center",
                "render": function(data, type, full) {
                    if(full["status"] == 3){
                        return '<a class="btn text-primary" data-target="#statusModal" data-toggle="modal" style="text-decoration: none" onclick="ShowFXRFQResult('+full['id']+')"><i class="icon fa-edit" aria-hidden="true"></i></i></a>';
                    }
                    // disabled="true"
                }
            },
        ],

        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false,
        "autoWidth": false
    });

    /*      Textarea Character Count     */

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
});

/*Show popup*/
function ShowFXRFQResult(id) {
    // console.log(id);
    $('#modalFxRequestId').html(id);
    $('#hdnFxRequestId').val(id);
    $('#fxReqIdMsg').val(id);
    //console.log(id);
    $.get('api/fx-request', function (response) {
        if (response == '')
            $('#fxLastMsgId').val(0);
        else
            $('#fxLastMsgId').val(response);
    });

    // loading RFQ bank result
    if ($dtInit == 0) {
        $('#dtFxhot').dataTable({
            "ajax": "api/fx-request?action=4&id=" + id,
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
                            '<input type="number" class="form-control" id="DealAmount" name="DealAmount[]" placeholder="0" value="' + full['DealAmount'] + '" style="width: 80px">';
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
        if ($dtInit == 0) {
            $dtInit = 1;
        } else {
            var dtable = $('#dtFxhot').dataTable();
            dtable.api().ajax.url("api/fx-request?action=4&id=" + id).load();
        }
    }

    // Loading HOT-FSO conversation
    GetCoversation(id);

}
/*
/!* Accept *!/
$("#accept").click(function (e) {
    // alert("hi");

    e.preventDefault();
    //alert($('#frmDealAmount').serialize());
    alertify.confirm( 'Are you sure to submit this request?', function (e) {

        if(e) {
            $.ajax({
                type: "POST",
                url: "api/fx-request-hot?action=2",
                data: $('#formstat').serialize(),
                cache: false,
                success: function (html) {
                    //alert(html);
                    var res = JSON.parse(html);
                    var dtable = $('#dtFx').dataTable();
                    $('#statusModal').modal('hide');
                    alertify.success("Saved SUCCESSFULLY!");
                    dtable.api().ajax.reload();
                }
            });
        }
        else {

        }
    });

})

/!* Reject *!/
$("#reject").click(function (e) {

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

        alertify.confirm( 'Are you sure to submit this request?', function (e) {

            if(e) {
                $.ajax({
                    type: "POST",
                    url: "api/fx-request-hot?action=3",
                    data: $('#formstat').serialize(),
                    cache: false,
                    success: function (html) {
                        //alert(html);
                        var res = JSON.parse(html);
                        var dtable = $('#dtFx').dataTable();
                        $('#statusModal').modal('hide');
                        alertify.error("REJECT SUCCESSFULLY!");
                        $('#reject_note').val("");
                        dtable.api().ajax.reload();
                    }
                });
            }
            else {

            }
        });

    }

    return true;
})
*/

/* Submit Message */
$("#hotmessage").click(function (e){
    e.preventDefault();
    // alert($('#fxreqmesghot').serialize());
    if ($('#FxConvMsg').val() ==""){
        $("#FxConvMsg").focus();
        $("#FxConvMsg").css("border-color" ,"red");
        alertify.error("Could not submitt empty text!");
        return false;
    }
    else {
        $.ajax({
            type: "POST",
            url: "api/fx-request-hot",
            // url: "api/fx-request?action=8",
            data: $('#fxreqmesghot').serialize(),
            cache: false,
            success: function (response) {
                //alert(response);
                console.log(response);
                var res = JSON.parse(response);
                // alertify.success(res['message']);
                $('#FxConvMsg').val("");
                GetCoversation($("#fxReqIdMsg").val());
            }
        });
    }
    return true;
})

function GetCoversation(fxreqid) {
    //alert('api/fx-request-hot?action=5&fxreqid='+ fxreqid);
    $.get('api/fx-request-hot?action=5&fxreqid='+ fxreqid, function (data) {
        //alert(data.length);
        var x = JSON.parse(data);
        // console.log(x);
        var align = 'left';
        var bgcolor = '#fffff';
        var forcolor = '#000000';
        $("#messageLoopView").html("");
        for (var i=0 ; i<x.length;){
            // alert((x[i].MsgText));
            if (x[i].Title == "HOT Message") {
                align = 'right';
                bgcolor = '#62a8ea';
                forcolor = '#f5ffff';
            }  // even
            else {
                align = 'left';
                bgcolor = '#efefef';
                forcolor = '#000000';
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
                "                                                                                            <div class=\"media-detail\" style=\"color: "+forcolor+"; white-space: pre-line;\">\n" +
                "                                                                                                "+x[i].MsgText+"\n" +
                "                                                                                            </div>\n" +
                "                                                                                        </div>\n" +
                "                                                                                    </div>\n" +
                "                                                                                </div>");
            i++;
        };
        $('#messageLoopView').stop().animate({scrollTop: $('#messageLoopView')[0].scrollHeight}, 800);

        // Getting last message id for this FX Request
        GetLastMsgId($("#fxReqIdMsg").val());
    });
}

function GetLastMsgId(fxreqid) {
    console.log('api/fx-request?action=8&fxreqid='+ fxreqid);
    $.get('api/fx-request?action=8&fxreqid='+ fxreqid, function (data){
        console.log(data);
        $("#fxLastMsgId").val(data);
    });
}
// var objDiv = document.getElementById("messageLoopView");
// objDiv.scrollTop = objDiv.scrollHeight(500);
// console.log(x[1].MsgText);
// $("#messageLoopView").animate({ scrollTop: $("#messageLoopView")[0].scrollHeight }, 1000);
// $("#exampleScollableApi").animate({ scrollTop: $('#exampleScollableApi').prop("scrollHeight")}, 1000);