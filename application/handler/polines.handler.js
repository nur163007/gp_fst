function writeDeliveredPOLones(poid){
    //PO INFO LOAD
    $.get("api/view-po?action=2&id="+poid, function (data) {
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
                    '<td class="text-center">' + d1[i]['poDate'] + '</td>' +
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

    $.get('api/view-po?action=3&id='+poid, function (data) {
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
}