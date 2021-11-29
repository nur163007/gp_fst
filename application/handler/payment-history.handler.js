/**
 * Created by HasanMasud on 07-Feb-19.
 */

$(document).ready(function () {

    $.getJSON("api/payment-history?action=1", function (list) {
        // alert(list);
        $("#poNo").select2({
            data: list,
            placeholder: "Search PO Number",
            allowClear: true,
            width: "100%"
        });
    });

    /*$("#btnPmntHistory").click(function (e) {
        e.preventDefault();
        if(validate() == true) {
            window.location.href = _dashboardURL + "payment-history?poNo=" + $("#poNo").val();
        }else {
            return false;
        }
    });*/
});

/*********GET PAYMENT HISTORY**********
 **********CREATED BY: HASAN MASUD******/
$("#poNo").change(function (e) {
    //alert("api/payment-history?action=2&poNo=" + $('#poNo').val());
    e.preventDefault();
    if(validate() == true) {
        $.ajax({
            url: "api/payment-history?action=2&poNo=" + $('#poNo').val(),
            dataType: "text",
            success: function(data) {
                var json = $.parseJSON(data);
                $("#payHistory").empty();
                var ci = '';
                for (var i=0;i<json.length;++i){
                    if(ci!= json[i].ciNo){
                        ci = json[i].ciNo;
                        $('#payHistory').append(
                            '<tr><td colspan="6" style="text-align: left; font-weight: bold; font-size: 120%">Commercial Invoice# '+json[i].ciNo+'</td></tr>'
                        );

                    }
                    $('#payHistory').append(
                        '<tr>'+
                        '<td class="text-center">'+json[i].payDocName+'</td>'+
                        '<td class="text-center">'+json[i].paymentPercent+'</td>'+
                        '<td class="text-center">'+json[i].lcno+'</td>'+
                        '<td class="text-center">'+json[i].ciNo+'</td>'+
                        '<td class="text-right">'+commaSeperatedFormat(json[i].payAmount)+'</td>'+
                        '<td>'+json[i].payDate+'</td>'+
                        '</tr>'
                    );
                }
            }
        });
    }else {
        return false;
    }
});


/*VALIDATE PAYMENT HISTORY*/
function validate() {

    if($("#poNo").val() == ""){
        alertify.error('Please select a PO number');
        $("#poNo").select2('open');
        return false;
    }

    return true;

}
