$dtInit = 0;
$(document).ready(function() {
    //    view fx request list
    $('#dtFx').dataTable({
        "ajax": "api/fx-request?action=1&status=0",
        "columns": [
            {
                "data": null,
                "sortable": true,
                "class": "text-center",
                "render": function(data, type, full) {
                    if (full["status"] == 0 || full["status"] == 5){
                        if (full["status"] == 5){
                            return "<p class='text-danger' >"+full["id"]+"</p>";
                        }
                        else {
                            return "<a style='text-decoration: underline' href=\""+"fx-rfq-request"+"?action=2&id="+full["id"]+"\">"+full["id"]+"</a>";
                        }
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
                    /*      For Rfq Close       */
                    if (full["status"] == 0 || full["status"] == 5){
                        if(full["status"] == 0) {
                            $("#btnOpenRfqforEdit").hide();
                            return '<p class="text-primary" style="margin: 0;"><i style="font-size: large" class="fa fa-clock-o" aria-hidden="true"></i></p>';
                        }
                        else {
                            const buttonFsoMessage = document.getElementById('fsomessage');
                            // const button = document.getElementById('DealAmountSubmit');
                            buttonFsoMessage.disabled = false;
                            // button.disabled = true;
                            $("#DealAmountSubmit").hide();
                            $("#btnOpenRfqforEdit").hide();
                            return '<a class="btn text-danger" data-target="#statusModal" data-toggle="modal" style="text-decoration: none" onclick="BankList('+full['id']+')"><i style="font-size: large" class="icon fa-edit" aria-hidden="true"></i></a>';
                        }
                    }
                    /*      For Rfq Done       */
                    else if (full["status"] == 1){
                        return '<p class="text-success" style="text-decoration: none"><i style="font-size: large" class="fad fa-check-circle"></i></p>';
                    }
                    /*--------For Rfq Close------*/
                    else if(full["status"] == 2){
                        // const button = document.getElementById('DealAmountSubmit');
                        // button.disabled = false;
                        $("#DealAmountSubmit").hide();
                        $("#btnOpenRfqforEdit").hide();
                        return '<a class="btn text-primary" data-target="#statusModal" data-toggle="modal" style="text-decoration: none" onclick="BankList('+full['id']+')"><i style="font-size: large" class="icon fa-edit" aria-hidden="true"></i></a>';
                    }
                    /*---------For Rfq Processing--------*/
                    else if(full["status"] == 3){
                        // const button = document.getElementById('DealAmountSubmit');
                        // button.disabled = true;
                        $("#DealAmountSubmit").hide();
                        $("#btnOpenRfqforEdit").hide();
                        return '<a class="btn text-primary" data-target="#statusModal" data-toggle="modal" style="text-decoration: none" onclick="BankList('+full['id']+')"><i style="font-size: large" class="icon fa-edit" aria-hidden="true"></i></a>';
                    }
                    /*----------For Rfq Settled-------*/
                    else if(full["status"] == 4){
                        const button = document.getElementById('DealAmountSubmit');
                        const buttonFsoMessage = document.getElementById('fsomessage');
                        button.disabled = true;
                        $("#DealAmountSubmit").hide();
                        buttonFsoMessage.disabled = true;
                        $("#btnOpenRfqforEdit").hide();
                        return '<a class="btn text-success" data-target="#statusModal" data-toggle="modal" style="text-decoration: none" onclick="BankList('+full['id']+')"><i style="font-size: large" class="icon fa-edit" aria-hidden="true"></i></a>';
                    }
                }
            },
        ],
        "rowCallback": function( row, data, index ) {
            if ( data["status"] == 5 )
            {
                $('td', row).css('color', 'Red');
            }
            else
            {
                $('td', row).css('color', 'Default');
            }
        },
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


/*      For PopUp       */

function BankList(id) {
    // console.log(id);
    // $.getJSON("api/fx-request?action=4&id=21", function (list) {
    $('#modalFxRequestId').html(id);
    $('#hdnFxRequestId').val(id);
    $('#fxReqIdMsg').val(id);
    if($dtInit ==0) {
        $('#BankData').dataTable({
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
        $dtInit = 1;
    } else{
        var dtable = $('#BankData').dataTable();
        dtable.api().ajax.url("api/fx-request?action=4&id=" + id).load();
    }
    $.get('api/fx-request', function (response) {
        if (response == '')
            $('#fxLastMsgId').val(0);
        else
            $('#fxLastMsgId').val(response);
    });

    // Loading HOT-FSO conversation
    GetCoversation(id);
    // GetApprovalLog ID
    GetApprovalLog(id);
}

function FxRequestDataGet(status = 0){
    // alert(status);
    var dtable = $('#dtFx').dataTable();
    dtable.api().ajax.url("api/fx-request?action=1&status=" + status).load();
}


/*submit DealAmount*/

$("#DealAmountSubmit").click(function (e) {
    // alert("hi");

    e.preventDefault();
    //alert($('#frmDealAmount').serialize());
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
                    url: "api/fx-request",
                    data: $('#frmDealAmount').serialize(),
                    cache: false,
                    success: function (html) {
                        //alert(html);
                        var res = JSON.parse(html);
                        if (res['status'] == 1) {
                            // ResetForm();
                            $('#statusModal').modal('hide');
                            var dtable = $('#dtFx').dataTable();
                            alertify.success("Saved SUCCESSFULLY!");
                            dtable.api().ajax.reload();
                            $("#DealAmount").val("");
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

})

/*      DropDown List      */

$(".dropdown-menu li a").click(function(){
    $(".btn:first-child").html($(this).text()+' <span class="caret"></span>');
});

/*          Submit Message      */

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
        url: "api/fx-request",
        data: $('#fxreqmesgfso').serialize(),
        cache: false,
        success: function (response) {
            // alert(response);
            var res = JSON.parse(response);
            alertify.success(res['message']);
            $('#FxConvMsg').val("");
            GetCoversation($("#fxReqIdMsg").val());
        }

    });
    }
    return true
})

function GetLastMsgId(fxreqid) {
    //console.log('api/fx-request?action=8&fxreqid='+ fxreqid);
    $.get('api/fx-request?action=8&fxreqid='+ fxreqid, function (data){
        //console.log(data);
        $("#fxLastMsgId").val(data);
    });
}

/*      Get Approval Log       */

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

/*      get conversation from hot module        */

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
            $.get('api/fx-request?action=10&fxreqid='+ fxreqid, function (response){
                var res = JSON.parse(response);
                alertify.success(res['message']);
            });
        }
        else {

        }
    });
});




