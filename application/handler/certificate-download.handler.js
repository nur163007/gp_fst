/**
 * Created by HasanMasud on 29-Sep-18.
 */

$(document).ready(function() {
	//alert("api/tac-request?action=10&user="+$("#loggedUser").val());
	$.getJSON("api/tac-request?action=10&user="+$("#loggedUser").val(), function (list) {
		// alert(list);
		$("#poNo").select2({
			data: list,
			placeholder: "Search PO Number",
			allowClear: true,
			width: "100%"
		});
	});
});

/*********GET CERTIFICATES & PAYMENT HISTORY**********
 **********CREATED BY: HASAN MASUD******/
$("#poNo").change(function (e) {
    e.preventDefault();
    $.ajax({
        url: "api/tac-request?action=16&poNo=" + $('#poNo').val(),
        dataType: "text",
        success: function (data) {
            var json = $.parseJSON(data);
            $("#payHistory").empty();
            var ci = '';
            var tac_pdf_link = '';
            var cfac_pdf_link = '';
            for (var i = 0; i < json.length; ++i) {
                if (ci != json[i].ciNo) {
                    ci = json[i].ciNo;
                    $('#payHistory').append(
                        '<tr><td colspan="6" style="text-align: left; font-weight: bold; font-size: 120%">Commercial Invoice# ' + json[i].ciNo + ' & Commercial Invoice Value: ' + commaSeperatedFormat(json[i].ciAmount) +' ' +json[i].currencyName + '</td></tr>'
                    );

                }
                /*CONDITION FOR TAC*/
                //alert(json[i].tacStatus == 1);
                if (json[i].tacStatus == 1) {
                    //tac_pdf_link = 'aaadafssa';
                    tac_pdf_link = '<td class="text-center">' + '<a href="' +
                        'api/tac-request?action=6&poNo=' + json[i].pono + '&shipNo=' + json[i].shipNo + '&partName=' + json[i].partname +
                        '" target="_blank"><i class="icon fa-pdf"></i></a></td>';
                } else {
                    tac_pdf_link = '<td class="text-center">N/A</td>';

                }
                /*CONDITION FOR CFAC*/
                if (json[i].cfacStatus == 1) {
                    cfac_pdf_link = '<td class="text-center">' +
                        '<a href="' + 'api/tac-request?action=8&po=' + json[i].pono + '&ship=' + json[i].shipNo + '&partName=' + json[i].partname +
                        '" target="_blank"><i class="icon fa-pdf"></i></a></td>';
                } else {
                    cfac_pdf_link = '<td class="text-center">N/A</td>';

                }
                //cfac_pdf_link = 'api/tac-request?action=8&po=' + json[i].pono + '&ship=' + json[i].shipNo + '&partName=' + json[i].partname;


                $('#payHistory').append(
                    '<tr>' +
                    '<td class="text-center">' + json[i].cacFacText + '</td>' +
                    '<td class="text-center">' + json[i].paymentPercent + '</td>' +
                    tac_pdf_link +
                    cfac_pdf_link +
                    '<td class="text-right">' + commaSeperatedFormat(json[i].payAmount) + '</td>' +
                    '<td>' + json[i].payDate + '</td>' +
                    '</tr>'
                );
            }
        }
    });
});

/*VALIDATE DOWNLOAD REQUEST MODULE*/
function validate() {
    if($("#poList").val() == ""){
        alertify.error('Please select a PO number');
        $("#poList").select2('open');
        return false;
    }
    /*if($("#shipNo").val() == ""){
        alertify.error('Please select a shipment number');
        $("#shipNo").select2('open');
        return false;
    }*/
    return true;
}