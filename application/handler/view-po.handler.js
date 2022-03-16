/*	
	Author: Shohel Iqbal
    Copyright: 01.2016
    Code fridged on: 
*/
var podata,
    comments,
    attach,
    lcinfo,
    pterms,
    ship,
    sumdata;

$(document).ready(function() {

    /*$("#scheduleETA, #awbOrBlDate, #ciDate, #voucherCreateDate").datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
    });*/

    if ($('#poid').val() != "") {

        var poid = $('#poid').val();
        var shipno = $('#shipno').val();

        $('#ponum').html(poid);

        $.get('api/purchaseorder?action=2&id=' + poid + '&shipno=' + shipno, function (data) {
            if (!$.trim(data)) {
                $(".panel-body").empty();
                $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
            } else {
                var row = JSON.parse(data);
                podata = row[0][0];
                comments = row[1];
                attach = row[2];

                // PO info
                $('#povalue').html('<b>' + commaSeperatedFormat(podata['povalue']) + '</b> ' + podata['curname']);
                $('#podesc').html(podata['podesc']);
                $('#lcdesc').html('<b>' + podata['lcdesc'] + '</b>');
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

                if ($("#usertype").val() == const_role_Supplier) {
                    var roleFilter = ["Bank Charge Advice", "Pay Order Issue Charge", "Insurance Cover Note", "Pay Order Receive Copy", "Amendment Advice Note"];
                    attachmentLogScript(attach, '#usersAttachments', 1, roleFilter, -1);
                } else {
                    attachmentLogScript(attach, '#usersAttachments');
                }
                //attachmentLogScript(attach, '#usersAttachments');

                commentsLogScript(comments, '#buyersmsg', '#suppliersmsg');

                // PI info
                $.get("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_DRAFT_PI_SUBMITTED, function (res) {
                    if (res > 0) {
                        $('#pinum').html(podata['pinum']);

                        $('#pivalue').html('<b>' + commaSeperatedFormat(podata['pivalue']) + '</b> ' + podata['curname']);
                        $('#pi_desc').html(podata['pidesc']);
                        $('#producttype').html(podata['producttypeName']);
                        $('#producttypeLC').html(podata['producttypeName']);
                        $('#importAs').html(podata['importAsName']);
                        $('#shipmode').html(podata['shipmode'].toUpperCase());

                        $('#hscode').html(podata['hscode']);

                        $('#pidate').html(Date_toDetailFormat(new Date(podata['pidate'])));
                        //$('#basevalue').html(podata['basevalue']);
                        $('#basevalue').html('<b>' + commaSeperatedFormat(podata['basevalue']) + '</b> ' + podata['curname']);

                        $('#origin').html(podata['origin']);
                        $('#negobank').html(podata['negobank']);
                        $('#shipport').html(podata['shipport']);
                        $('#lcbankaddress').html(podata['lcbankaddress']);
                        $('#productiondays').html(podata['productiondays']);
                    }
                });

                // LC info
                //alert("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_LC_REQUEST_SENT+"&reject=true");
                $.get("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_LC_REQUEST_SENT + "&reject=true", function (res) {
                    if (res > 0) {
                        /*$.get("api/view-po?action=1&po=" + poid + "&shipno=&status=" + ACTION_SENT_REVISED_LC_REQUEST_1, function (res) {
                            if (res > 0) {*/
                        lcinfo = row[3][0];
                        pterms = row[4];

                        if (lcinfo['lca'] == 0) {
                            $('#requesttype').html('LC');
                        } else if (lcinfo['lca'] == 1) {
                            $('#requesttype').html('LCA');
                        }
                        $('#lctype').html(lcinfo['lctypename']);
                        $('#producttype').html(lcinfo['producttypename']);
                        $('#lcNo').html(lcinfo['lcno']);
                        $('#lcafno').html(lcinfo['lcafno']);
                        if (lcinfo['lcissuedate'] != null) {
                            $('#lcissuedate').html(Date_toDetailFormat(new Date(lcinfo['lcissuedate'])));
                            $('#daysofexpiry').html(Date_toDetailFormat(new Date(lcinfo['daysofexpiry'])));
                        } else {
                            $('#lcissuedate').html("NA");
                            $('#daysofexpiry').html("NA");
                        }
                        $('#lastdateofship').html(Date_toDetailFormat(new Date(lcinfo['lastdateofship'])));

                        $('#lcvalue').html(podata['curname'] + ' ' + commaSeperatedFormat(lcinfo["lcvalue"]));
                        $('#lcissuerbank').html(lcinfo['lcissuerbankname']);
                        $('#insurance').html(lcinfo['insurancename']);

                        $("#paymentTermsText").html(lcinfo["paymentterms"]);

                        var ptrow = "";
                        for (var i = 0; i < pterms.length; i++) {
                            ptrow = "<tr><td class=\"text-center\">" + pterms[i]['percentage'] + "%</td>" +
                                "<td class=\"text-center\">" + pterms[i]['partname'] + "</td>" +
                                "<td class=\"text-center\">" + pterms[i]['dayofmaturity'] + " Days Maturity</td>" +
                                "<td class=\"text-left\">" + pterms[i]['maturityterms'] + "</td></tr>";
                            $("#lcPaymentTermsTable").append(ptrow);
                        }
                        /*}
                    });*/
                    }
                });


                // Shipment info
                $.get("api/view-po?action=1&po=" + poid + "&shipno=" + shipno + "&status=" + ACTION_SHARED_SHIPMENT_DOCUMENT, function (res) {
                    if (res > 0) {

                        $.get('api/shipment?action=1&po=' + poid + '&shipno=' + shipno, function (data) {

                            ship = JSON.parse(data);

                            $("#shipmode1").html(ship['shipmode']);
                            $("#scheduleETA").html(Date_toDetailFormat(new Date(ship['scheduleETA'])));
                            if (ship['scheduleETD'] != null) {
                                $("#scheduleETD").html(Date_toDetailFormat(new Date(ship['scheduleETD'])));
                            } else {
                                $("#scheduleETD").html("");
                            }

                            $("#mawbNo").html(ship['mawbNo']);
                            $("#hawbNo").html(ship['hawbNo']);
                            $("#blNo").html(ship['blNo']);

                            $("#awbOrBlDate").html(Date_toDetailFormat(new Date(ship['awbOrBlDate'])));

                            $("#ciNo").html(ship['ciNo']);
                            $("#ciDate").html(Date_toDetailFormat(new Date(ship['ciDate'])));
                            $("#ciAmount").html(podata['curname'] + ' ' + commaSeperatedFormat(ship['ciAmount']));
                            $("#invoiceQty").html(ship['invoiceQty']);
                            $("#noOfcontainer").html(ship['noOfcontainer']);
                            $("#noOfBoxes").html(ship['noOfBoxes']);
                            $("#ChargeableWeight").html(ship['ChargeableWeight']);

                            if (ship['dhlTrackNo'] != null && ship['dhlTrackNo'] != "") {
                                $("#dhlNum").html('<a class="block text-left" target="_blank" href="http://www.dhl.com/en/express/tracking.shtml?AWB=' + ship['dhlTrackNo'] + '&brand=DHL">' + ship['dhlTrackNo'] + '</a><span class="comment-meta">(click ti view DHL status)</span>');
                            } else {
                                $("#dhlNum").html("Not updated");
                            }
                            if (ship['docDeliveredByFin'] != null) {
                                $("#docDeliveredByFin").html(Date_toMDY_HMS(new Date(ship['docDeliveredByFin'])));
                            }

                            $("#cdAmount").html('BDT ' + commaSeperatedFormat(ship['customDuty']));

                            if (ship['ipcNo'] != null) {
                                $("#ipcNum").html(ship['ipcNo']);
                            } else {
                                $("#ipcNum").html("N/A");
                            }
                            if (ship['gitReceiveDate'] != null) {
                                $("#gitReceiveDate").html(Date_toDetailFormat(new Date(ship['gitReceiveDate'])));
                            } else {
                                $("#gitReceiveDate").html("N/A");
                            }
                            if (ship['whArrivalDate'] != null) {
                                $("#whArrivalDate").html(Date_toDetailFormat(new Date(ship['whArrivalDate'])));
                            } else {
                                $("#whArrivalDate").html("N/A");
                            }

                        });
                    }
                });
            }
        });
    }

    // Request to Supplier to send Final PI 
    $("#btnAcknowledged").click(function (e) {

        e.preventDefault();

        if (validate() === true) {
            alertify.confirm('Are you sure you want proceed?', function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: "api/view-po",
                        data: "userAction=1&refId=" + $("#refId").val() + "&poid=" + $("#poid").val() + "&shipno=" + $("#shipno").val(),
                        cache: false,
                        success: function (result) {
                            // alert(result);
                            var res = JSON.parse(result);

                            if (res['status'] == 1) {
                                alertify.success(res['message']);
                                window.location.href = _dashboardURL;
                            } else {
                                alertify.error("FAILED to send!");
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

    //PO INFO LOAD
    var id = $('#poid').val();

    //PO LINE LOAD
    $.get("api/view-po?action=2&id="+id, function (data) {
        var res = JSON.parse(data);
        var qty = 0, totalQty = 0, totalPrice = 0, grandTotal = 0, delivQty = 0, totalDelivQty = 0, delivPrice = 0,
            delivTotal = 0;
        //alert(rejectedLines);

        /**
         * Delivered po lines
         */
        var d1 = res[0];


        $("#delivCount1").html('(' + d1.length + ')');

        if (d1.length > 0) {
            $("#dtPOLinesDelivered tbody").empty();
            // loading already delivered po lines
            for (var i = 0; i < d1.length; i++) {
                strRow = '<tr>' +
                    '<td class="text-center">' + d1[i]['lineNo'] + '</td>' +
                    '<td class="text-center">' + d1[i]['itemCode'] + '</td>' +
                    '<td class="text-left">' + d1[i]['itemDesc'] + '</td>' +
                    '<td class="text-center">' + d1[i]['deliveryDate'] + '</td>' +
                    '<td class="text-center">' + d1[i]['uom'] + '</td>' +
                    '<td class="text-right">' + commaSeperatedFormat(d1[i]['unitPrice'], 4) + '</td>' +
                    '<td class="text-center poBg">' + commaSeperatedFormat(d1[i]['poQty']) + '</td>' +
                    '<td class="text-right poBg">' + commaSeperatedFormat(d1[i]['poTotal'], 4) + '</td>' +
                    '<td class="text-center delivBg">' + commaSeperatedFormat(d1[i]['delivQty'], 4) + '</td>' +
                    '<td class="text-right delivBg">' + commaSeperatedFormat(d1[i]['delivTotal'], 4) + '</td>' +
                    /*'<td class="text-right">' + commaSeperatedFormat(poline[i]['ldAmount']) + '</td>' +*/
                    '</tr>';
                $("#dtPOLinesDelivered tbody:last").append(strRow);

                // alert(qty)
            }
        } else {
            $("#dtPOLinesDelivered tbody").empty();
            $("#dtPOLinesDelivered tbody").append('<tr><td></td><td></td><td></td><td></td><td></td><td></td><td class="poBg"></td><td class="poBg"></td><td class="delivBg"></td><td class="delivBg"></td></tr>');
        }

    });

    $.get('api/view-po?action=3&id='+id, function (data) {
        // console.log(data)
        if(!$.trim(data)){
            $(".panel-body").empty();
            $(".panel-body").append(`<h4 class="well well-sm well-warning">No data found</h4>`);
        }else {

            var row = JSON.parse(data);
            // console.log(row)
            sumdata = row[0][0];
            // console.log(commaSeperatedFormat(row['grandpoQty']))

            // PO info
            // $('#podesc').val(podata['podesc']);
            $('#poQtyTotal').html(commaSeperatedFormat(sumdata['grandpoQty']));
            $('#grandTotal').html(commaSeperatedFormat(sumdata['grandPoTotal']));
            $('#dlvQtyTotal').html(commaSeperatedFormat(sumdata['grandDelivQty']));
            $('#dlvGrandTotal').html(commaSeperatedFormat(sumdata['grandDelivTotal']));
        }
    });
});

function validate(){
    return true;
}






