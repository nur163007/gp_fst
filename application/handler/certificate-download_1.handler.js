/**
 * Created by HasanMasud on 29-Sep-18.
 */

$(document).ready(function() {
	//alert("api/tac-request?action=10&user="+$("#loggedUser").val());
	$.getJSON("api/tac-request?action=10&user="+$("#loggedUser").val(), function (list) {
		// alert(list);
		$("#poList").select2({
			data: list,
			placeholder: "Search PO Number",
			allowClear: true,
			width: "100%"
		});
	});

    $("#poList").change(function (e) {
        $("#message").empty();
	});

	$("#poList").change(function (e) {
        $("#shipNo").empty();
	   $.getJSON("api/tac-request?action=2&po=" + $("#poList").val(), function (list) {
			$("#shipNo").select2({
				data: list,
				minimumResultsForSearch: Infinity,
				placeholder: "Select shipment number",
				allowClear: false,
				width: "100%"
			});
		});
	});

	$("#goTAC_btn").click(function (e) {
        e.preventDefault();
        if(validate() === true) {
            var button = e.target;
            button.disabled = true;
            $.ajax({
                type: "GET",
                url: "api/tac-request?action=9&po=" + $("#poList").val() + '&ship=' + $("#shipNo").val(),
                cache: false,
                success: function (html) {
                    button.disabled = false;
                    $("#form-certificate").after('');
                    if (html == 0) {
                        $("#message").html('<div class="alert alert-danger">This request has not yet been approved by CPO</div>');
                    } else {
                        var tac_pdf_link = 'api/tac-request?action=6&po=' + $("#poList").val() + '&ship=' + $("#shipNo").val();
                        var cfac_pdf_link = 'api/tac-request?action=8&po=' + $("#poList").val() + '&ship=' + $("#shipNo").val();


                        var poNo = $("#poList").val();
                        //var shipNo = $("#shipNo").val();
                        //alert('api/tac-request?action=14&poNo=' + poNo);
                        $.get('api/tac-request?action=14&poNo=' + poNo, function (data) {
                            var row = JSON.parse(data);
                            //alert(row);
                            $("#tacName").html('<i class="icon fa-pdf"></i> ' +'Technical Acceptance Certificate' + '_PO_'+ $("#poList").val()+'_ship_'+$("#shipNo").val());
                            $("#cfacName").html('<i class="icon fa-pdf"></i> ' + row["cacFacText"]+'_PO_'+ $("#poList").val()+'_ship_'+$("#shipNo").val());
                        });


                        //var html = '<a target="_blank" href="' + tac_pdf_link + '" id="tacName"><i class="icon fa-pdf"></i>&nbsp;Technical Acceptance Certificate</a><br/>';
                        var html = '<a target="_blank" href="' + tac_pdf_link + '" id="tacName"></a><br/>';
                        html += '<a target="_blank" href="' + cfac_pdf_link + '" id="cfacName"></a>';

                        $("#message").html(html);
                    }
                }
            });
        }else {
            return false;
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
    if($("#shipNo").val() == ""){
        alertify.error('Please select a shipment number');
        $("#shipNo").select2('open');
        return false;
    }
    return true;
}