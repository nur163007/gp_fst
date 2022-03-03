/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/

var poid = $('#pono').val();
var u = $('#usertype').val();

var podata,comments,attach,lcinfo,pterms;

$(document).ready(function() {

    // Loading pre data from PO
    $.get('api/purchaseorder?action=2&id=' + poid, function (data) {
        if (!$.trim(data)) {
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        } else {
            var row = JSON.parse(data);
            podata = row[0][0];
            //comments = row[1];
            attach = row[2];
            lcinfo = row[3][0];
            //pterms = row[4];

            // PO info
            $('#ponum').html(poid);
            $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
            $('#podesc').html(podata['podesc']);
            if (lcinfo['lcdesc'] == "") {
                $('#lcdesc').html(podata['lcdesc']);
            } else {
                $('#lcdesc').html(lcinfo['lcdesc']);
            }
            $('#supplier').html(podata['supname']);
            $('#sup_address').html(podata['supadd']);
            $('#contractref').html(podata['contractrefName']);
            $('#pr_no').html(podata['pr_no']);
            $('#department').html(podata['department']);
            $('#deliverydate').html(Date_toDetailFormat(new Date(podata['deliverydate'])));
            $('#actualPoDate').html(Date_toDetailFormat(new Date(podata['actualPoDate'])));
            if (podata["installbysupplier"] == 0) {
                $('#installbysupplier').html('No');
            } else {
                $('#installbysupplier').html('Yes');
            }
            $('#noflcissue').html(podata['noflcissue']);
            $('#nofshipallow').html(podata['nofshipallow']);

            // PI info
            $('#pinum').html(podata['pinum']);


            $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);
            $('#pi_desc').html(podata['pidesc']);
            $('#producttype').html(podata['producttypeName']);
            $('#importAs').html(podata['importAsName']);
            $('#shipmode').html(podata['shipmode'].toUpperCase());
            $('#shippingMode').val(podata['shipmode']);
            $('#hscode').html(podata['hscode']);

            $('#pidate').html(Date_toMDY(new Date(podata['pidate'])));
            $('#basevalue').html(commaSeperatedFormat(podata['basevalue']) + ' ' + podata['curname']);

            $('#origin').html(podata['origin']);
            $('#negobank').html(podata['negobank']);
            $('#shipport').html(podata['shipport']);
            $('#lcbankaddress').html(podata['lcbankaddress']);
            $('#productiondays').html(podata['productiondays']);
            $('#buyercontact').html(podata['buyercontact']);
            $('#techcontact').html(podata['techcontact']);

            // Attachment
            var attachList = ["Final LC Copy", "Amendment LC Copy", "Amendment Advice Note"];
            attachmentLogScript(attach, '#usersAttachments', 1, attachList);

            $("#lcno").val(lcinfo['lcno']);
            $("#lcnum").html(lcinfo['lcno']);
            $("#lcvalue").html(commaSeperatedFormat(lcinfo['lcvalue']) + ' ' + podata['curname']);

            checkStepOver();

            // Amendment request link
            $("#ammendmentRequest_btn").attr("href", "amendment-request?po=" + poid + "&lc=" + lcinfo['lcno'] + "&req=new" + "&ref=" + $("#refId").val());

            $.get('api/lc-acceptance?action=1&po=' + poid, function (data) {

                if ($.trim(data)) {

                    var rows = JSON.parse(data);
                    var sRow = "";

                    if ($("#usertype").val() == const_role_Buyer) {
                        for (var i = 0; i < rows.length; i++) {
                            var sn = i + 1;
                            sRow = "<tr><td class=\"text-center\" >Shipment # " + rows[i]['shipNo'] + "</td>" +
                                "<td>Date: " + Date_toMDY(new Date(rows[i]['scheduleETA'])) + "</td></tr>";
                            $("#scheduleInfoTable").append(sRow);
                        }
                    }
                    if ($("#usertype").val() == const_role_Supplier) {
                        for (var i = 0; i < rows.length; i++) {
                            var sn = i + 1;
                            if (sn > 1) {
                                newScheduleRow();
                            }
                            var d = new Date(rows[i]['scheduleETA']);
                            $("#shipmentSchedule_" + sn).datepicker('setDate', d);
                            $("#shipmentSchedule_" + sn).datepicker('update');
                        }
                    }
                }
            });
        }
    });

    // Submit
    $("#acceptLC_btn").click(function (e) {
        e.preventDefault();
        alertify.confirm('Are you sure you want to Accept LC?', function (e) {
            if (e) {
                $("#acceptLC_btn").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/lc-acceptance",
                    data: "userAction=1&refId=" + $("#refId").val() + "&pono=" + $("#pono").val() + "&lcno=" + $("#lcno").val(),
                    cache: false,
                    success: function (response) {
                        $("#acceptLC_btn").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);
                            if (res['status'] == 1) {
                                //$("#refId").val(res['lastaction']);
                                alertify.success(res['message']);
                                //checkStepOver();
                                window.location.href = _adminURL + "shipment-schedule?po=" + poid + "&ref=" + res["lastaction"];
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.');
                            return false;
                        }
                    },
                    error: function (xhr) {
                        console.log('Error: ' + xhr);
                    }
                });
            } else { // canceled
                //alertify.error(e);
            }
        });
    });

    $("#scheduleSubmit_btn").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            alertify.confirm('Are you sure you want to Submit?', function (e) {
                if (e) {
                    $("#scheduleSubmit_btn").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-acceptance",
                        data: $('#lcacceptance-form').serialize() + "&userAction=2",
                        cache: false,
                        success: function (response) {
                            $("#scheduleSubmit_btn").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error(res['message'], 20);
                                    return false;
                                }
                            } catch (e) {
                                console.log(e);
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });

    $("#btn_RejectSchedule").click(function (e) {
        e.preventDefault();
        if (validateScheduleReject() === true) {
            alertify.confirm('Are you sure you want to Reject this/these schedule(s)?', function (e) {
                if (e) {
                    $("#btn_RejectSchedule").prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: "api/lc-acceptance",
                        data: "userAction=3&refId=" + $("#refId").val() + "&pono=" + $("#pono").val() + "&comments=" + $("#comments").val(),
                        cache: false,
                        success: function (response) {
                            $("#btn_RejectSchedule").prop('disabled', false);
                            //alert(response);
                            try {
                                var res = JSON.parse(response);

                                if (res['status'] == 1) {
                                    alertify.success(res['message']);
                                    //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                    window.location.href = _dashboardURL;
                                } else {
                                    alertify.error("FAILED!");
                                    return false;
                                }
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.');
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                } else { // canceled
                    //alertify.error(e);
                }
            });
        } else {
            return false;
        }
    });

    $("#btn_AcceptSchedule").click(function (e) {
        e.preventDefault();
        alertify.confirm('Are you sure you want to accept this/these schedule(s)?', function (e) {
            if (e) {
                $("#btn_AcceptSchedule").prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "api/lc-acceptance",
                    data: "userAction=4&refId=" + $("#refId").val() + "&pono=" + $("#pono").val() + "&comments=" + $("#comments").val(),
                    cache: false,
                    success: function (response) {
                        $("#btn_AcceptSchedule").prop('disabled', false);
                        //alert(response);
                        try {
                            var res = JSON.parse(response);

                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                //window.location.href = _adminURL + "shipment?po="+poid+"&ref="+res["lastaction"];
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        } catch (e) {
                            alertify.error(response + ' Failed to process the request.');
                            return false;
                        }
                    },
                    error: function (xhr) {
                        console.log('Error: ' + xhr);
                    }
                });
            } else { // canceled
                //alertify.error(e);
            }
        });
    });

    $("#addScheduleRow").click(function (e) {
        newScheduleRow();
    });

});

$('#shipmentSchedule_1').datepicker({
    todayHighlight: true,
    autoclose: true
});

function validateScheduleReject() {
    if ($("#comments").val() == "") {
        $("#comments").focus();
        alertify.error("Please comment on the rejection!");
        return false;
    }
    return true;
}

function validate() {

    if ($('#userAction').val() == 1) {

        var scheduleNum = $('div#scheduleRows').children().length;
        for (var i = 1; i <= scheduleNum; i++) {

            if ($("#shipmentSchedule_" + i).val() == "") {
                $("#shipmentSchedule_" + i).focus();
                alertify.error("Shipment schedule is required!");
                return false;
            }
        }

    }
    return true;
}

//$('<input>').attr({'class':'form-control','id':'scheduleNumber_'+id, 'name':'scheduleNumber[]','placeholder':'Approximate Schedule'})


function removeSchedule(obj){
    $("#"+obj).closest("div.scheduleRow").remove();
}


function newScheduleRow() {

    var id = $('div#scheduleRows').children().length + 1;
    //var id = parseInt($('#scheduleSl').val())+1;

    $('div#scheduleRows').append(
        $('<div>').attr('class', 'col-sm-12 scheduleRow').append(
            $('<div>').attr('class', 'form-group').append(
                $('<label>').attr('class', 'col-sm-3 control-label text-left').html('Shipment ' + id + ':')
            ).append(
                $('<div>').attr('class', 'col-sm-7').append(
                    $('<div>').attr('class', 'input-group').append(
                        $('<span>').attr('class', 'input-group-addon').append(
                            $('<i>').attr({'class': 'icon wb-calendar', 'aria-hidden': 'true'})
                        )
                    ).append(
                        $('<input>').attr({
                            'type': 'text',
                            'class': 'form-control',
                            'data-plugin': 'datepicker',
                            'id': 'shipmentSchedule_' + id,
                            'name': 'shipmentSchedule[]',
                            'placeholder': 'Approximate Schedule'
                        })
                    )
                )
            ).append(
                $('<div>').attr('class', 'col-sm-2').append(
                    $('<button>').attr({
                        'type': 'button',
                        'class': 'btn btn-sm btn-outline btn-warning minusScheduleRow',
                        'id': 'minusScheduleRow_' + id,
                        'onclick': 'removeSchedule(this.id)'
                    }).append(
                        $('<i>').attr({'class': 'icon wb-minus', 'aria-hidden': 'true'})
                    )
                )
            )
        )
    );

    $('#shipmentSchedule_' + id.toString()).datepicker({
        todayHighlight: true,
        autoclose: true
    });

    //$("#scheduleSl").val(id);
}

function checkStepOver(){
    
    $.get('api/purchaseorder?action=4&po='+poid+'&step='+ACTION_LC_ACCEPTED, function(r){
        if(r==1){
            $("#acceptLC_btn, #ammendmentRequest_btn").attr("disabled",true);
            $("#clauseControl input, #clauseControl button").removeAttr("disabled");
        }else{
            $("#clauseControl input, #clauseControl button").attr("disabled",true);
            $("#acceptLC_btn, #ammendmentRequest_btn").removeAttr("disabled");
        }
    });
}
