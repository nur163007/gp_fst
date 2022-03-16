var userType = $("#usertype").val();
var company = $("#hiddenCompanyId").val();
$(document).ready(function() {
    $('#dtUpdateCreditReport').dataTable( {
        "ajax": "api/update-credit-report?action=1",
        "columns": [
            { "data": "id"},
            { "data": "supplierName" },
            { "data": "bankName" },
            { "data": "issueDate" },
            { "data": "expiryDate" },
            { "data": null, "sortable": false, "class": "text-center",
                "render": function(data, type, full) {
                        return '<a href="docs/CreditReport/BankandTFO/' + full['crReport'] + '" title="Credit Report" download style="text-decoration: none;color: red">' + full['crReport'] + '</a>';
                }
            },
            { "data": null, "sortable": false, "class": "text-center",
                "render": function(data, type, full) {
                if (userType == const_role_lc_bank){
                    if (full['bankId']==company){
                        return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#updateCreditReportForm" data-toggle="modal" data-toggle="Update Credit Report" data-original-title="Edit Credit Report" onclick="openUpdateCreditReport(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
                            '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="deleteUpdateCreditReport(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
                    }else {
                        return '<p ><i class="fas fa-ban" aria-hidden="true"></i></p>&nbsp;';
                    }
                }else {
                    return '<button class="btn btn-sm btn-icon btn-flat btn-default" data-target="#updateCreditReportForm" data-toggle="modal" data-toggle="Update Credit Report" data-original-title="Edit Credit Report" onclick="openUpdateCreditReport(' + full['id'] + ')"><i class="icon wb-wrench" aria-hidden="true"></i></button>&nbsp;'+
                        '<button class="btn btn-sm btn-icon btn-flat btn-default" data-toggle="tooltip" data-original-title="Edit" onclick="deleteUpdateCreditReport(' + full['id'] + ')"><i class="icon wb-close" aria-hidden="true"></i></button>';
                }

                   }
            }],
        "rowCallback": function( row, data, index ) {
            if ( userType == const_role_lc_bank ){
                if (data['bankId']==company){
                    $('td', row).css('background-color', '#93DED1');
                    $('td', row).css('color', 'white');
                }
            }
        },
        "sDom": 'frtip',
        "bProcessing": true,
        "bStateSave": false
    });

    $("#btnUpdateCreditReportFormSubmit").click(function(e) {

        /*alert('sdfd');*/
        e.preventDefault();
        if(validate() == true)
        {
            alertify.confirm('Are you sure you want to submit it?', function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: "api/update-credit-report",
                        data: $('#updateCreditReportFormData').serialize(),
                        cache: false,
                        success: function (data) {
                            /*alert(html);*/
                            var res = JSON.parse(data);
                            if (res['status'] == 1) {
                                ResetForm();
                                $('#updateCreditReportForm').modal('hide');
                                var dtable = $('#dtUpdateCreditReport').dataTable();
                                alertify.success("Saved SUCCESSFULLY!");
                                dtable.api().ajax.reload();
                                return true;
                            } else {
                                alertify.error("FAILED!");
                                return false;
                            }
                        }
                    });
                }
            });
        } else {
            return false;
        }
    });
    $.getJSON("api/company?action=4", function (list) {
        $("#supplier").select2({
            data: list,
            placeholder: "select a supplier",
            allowClear: true,
            width: "100%"
        });
    });

    $.getJSON("api/company?action=4&type=118", function (list) {
        $("#bankid").select2({
            data: list,
            placeholder: "Select a Bank",
            allowClear: false,
            width: "100%"
        });
        $("#bankid").val(company).change();
    });

    $('#creditReportDate').datepicker().on('changeDate', function (ev) {

        var d = new Date($("#creditReportDate").val());
        d.setDate(d.getDate() + 360);
        $('#reportExpiryDate').datepicker('setDate', d);
        $('#reportExpiryDate').datepicker('update');
    });

});

$("#creditReportDate, #reportExpiryDate")
    .datepicker({
        format: 'MM dd, yyyy',
        todayHighlight: true,
        autoclose: true
    });

function validate()
{
    if($("#supplier").val()=="")
    {
        alertify.error("Please select a Supplier!");
        $("#supplier").select2('open');
        return false;
    }
    if($("#bankid").val()=="")
    {
        alertify.error("Please select a bank!");
        $("#bankid").select2('open');
        return false;
    }
    if($("#creditReportDate").val()=="")
    {
        $("#creditReportDate").focus();
        alertify.error("Credit report issue date required");
        return false;
    }
    if($("#reportExpiryDate").val()=="")
    {
        $("#reportExpiryDate").focus();
        alertify.error("Expiry date required");
        return false;
    }
    if($("#attachCreditReport").val()=="" && $("#attachCreditReportOld").val()==""){
        $("#attachCreditReport").focus();
        alertify.error("Please attach credit report copy!");
        return false;
    }
    return true;
}

function ResetForm() {

    $("#reportID").val('');
    $('#supplier').val('').change();
    if (userType!=const_role_lc_bank){
        $('#bankid').val('').change();
    }
    $("#creditReportDate").val('');
    $("#reportExpiryDate").val('');
    $("#attachCreditReport").val('');
    $("#attachOldCreditReport").html('');

}


function openUpdateCreditReport(id)
{
    $.get('api/update-credit-report?action=2&id='+id, function (data) {

        var row = JSON.parse(data);
        //alert(row);
        $("#reportID").val(row["id"]);
        $('#supplier').val(row["supplierId"]).change();
        $('#bankid').val(row["bankId"]).change();
        $('#creditReportDate').val(row["issueDate"]);
        $('#reportExpiryDate').val(row["expiryDate"]);

        if (row["crReport"] != null) {
            $("#attachCreditReportOld").val(row["crReport"]);
            $("#attachOldCreditReport").html(creditAttachmentLink(row["crReport"]));
         }
    });
}

function deleteUpdateCreditReport(id){
    alertify.confirm( 'Are you sure you want to delete this Report?', function (e) {
        if(e){
            $.get('api/update-credit-report?action=3&id='+id, function (data) {
                if(data==1){
                    alertify.success("Credit Report deleted successfully!");
                    var dtable = $('#dtUpdateCreditReport').dataTable();
                    dtable.api().ajax.reload();
                } else{
                    alertify.error("Delete Fail!");
                }
            });

        }
    });
}
$(function () {

    var button = $('#btnUploadCreditReport'), interval;
    var txtbox = $('#attachCreditReport');

    new AjaxUpload(button, {
        action: 'application/library/uploadhandler.php',
        name: 'upl',
        onComplete: function (file, response) {
            var res = JSON.parse(response);
            txtbox.val(res['filename']);
            window.clearInterval(interval);
        },
        onSubmit: function (file, ext) {
            if (!(ext && /^(pdf)$/i.test(ext))) {
                alertify.error('Invalid File Format.');
                return false;
            }
            txtbox.val("Uploading...");
            // Uploding -> Uploading. -> Uploading...
            interval = window.setInterval(function () {
                var text = txtbox.val();
                if (txtbox.val().length < 13) {
                    txtbox.val(txtbox.val() + '.');
                } else {
                    txtbox.val('Uploading');
                }
            }, 200);
        }
    });
});