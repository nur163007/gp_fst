$dtInit = 0;
$(document).ready(function() {
    //    view fx request list
    $('#dtFx').dataTable({
        "ajax": "api/fx_request_primary?action=1&status=0",
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

    //
    // $.get('api/purchaseorder?action=4&po='+poid+'&step='+ACTION_READY_FOR_RFQ, function(r){
    //     // console.log(r)
    //     if(r==1){
    //         // alert(1);
    //         $("#primaryReqForRFQ").hide();
    //         // $("#btnViewIC").removeAttr("disabled");
    //     }else{
    //         $("#primaryReqForRFQ").show();
    //         // $("#btnCoverNote").removeAttr("disabled");
    //     }
    // });

    $("#primaryReqForRFQ").click(function (e) {
        e.preventDefault();
        alertify.confirm( 'Are you sure to submit this request?', function (e) {

            if(e) {
                $.get('api/fx-request?action=12', function (response){
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        window.location.href = _dashboardURL;
                    } else {
                        alertify.error(res['message']);
                        return false;
                    }
                });
            }
            else {

            }
        });

    })
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


/*      DropDown List      */

$(".dropdown-menu li a").click(function(){
    $(".btn:first-child").html($(this).text()+' <span class="caret"></span>');
});
