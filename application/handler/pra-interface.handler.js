/*
	Author: Shohel Iqbal
	Copyright: 01.2016
	Code fridged on:
*/
var attach

$(document).ready(function (e) {

//`RefID`, `PO`, `ActionID`, `Status`, `Msg`, `XRefID`, `ActionBy`, `ActionByRole`, `ActionOn`, `ActionFrom`
    myIndex();
    function myIndex() {

        $.ajax({
            type:"GET",
            dataType:"json",
            url: "api/pra-interface?action=1&my=true",
            success:function (response) {
                // console.log((response))
                var get_data ="";
                var caRef = 0;
                var caRefColumn = '';
                var downloadColumn = '';
                var letter = '';
                var attachControl = [];

                for (let i = 0; i < response.length; ++i) {

                    var btrcDiv= response[i].btrc_division;

                    if(btrcDiv == btrc_division_ENO){
                        letter = '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadApp_eo_letter('+btrcDiv+')"><i class="icon fa-file-word-o" aria-hidden="true"></i> EO</a>&nbsp;&nbsp;' +
                                 '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadApp_equipment_letter('+btrcDiv+')"><i class="icon fa-file-word-o" aria-hidden="true"></i> Equip</a><br>' +
                                 '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadEOZip('+response[i].pra_ref+')"><i class="fa fa-file-zip-o text-danger" aria-hidden="true"></i> Download Zip</a>';

                        attachmentColumn = '<td rowspan="' + response[i].poCount + '">' +
                            '<div class="input-group small col-sm-12">' +
                            '   <input type="hidden" name="attachRefNo[]" id="attachRefNo' + i + '" value="' + response[i].pra_ref + '" />' +
                            '   <input type="text" class="form-control attachEODoc input-sm" name="attachEODoc[]" id="attachEODoc' + i + '" readonly placeholder="EO.docx" />' +
                            '   <span class="input-group-btn">' +
                            '       <button type="button" id="btnUploadAttachEODoc' + i + '" class="btn btn-outline btn-sm"><i class="icon wb-upload" aria-hidden="true"></i></button>' +
                            '   </span>' +
                            '</div>' +
                            '<div class="input-group small col-sm-12">' +
                            '   <input type="text" class="form-control attachEquipDoc input-sm" name="attachEquipDoc[]" id="attachEquipDoc' + i + '" readonly placeholder="Equip.docx" />' +
                            '   <span class="input-group-btn">' +
                            '       <button type="button" id="btnUploadAttachEquipDoc' + i + '" class="btn btn-outline btn-sm"><i class="icon wb-upload" aria-hidden="true"></i></button>' +
                            '   </span>' +
                            '</div>' +
                            '</td>';
                    }
                    else if(btrcDiv == btrc_division_SM){
                        letter = '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadApp_spectrum_letter('+btrcDiv+')"><i class="icon fa-file-word-o" aria-hidden="true"></i> Spec</a>&nbsp;&nbsp;'+
                                 '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadApp_equipment_letter('+btrcDiv+')"><i class="icon fa-file-word-o" aria-hidden="true"></i> Equip</a>&nbsp;&nbsp;'+
                                 '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadApp_quantity_letter('+btrcDiv+')"><i class="icon fa-file-word-o" aria-hidden="true"></i> Qty</a><br>' +
                                 '<a href="javascript:void(0)" style="text-decoration: none" onclick="downloadSMZip('+response[i].pra_ref+')"><i class="fa fa-file-zip-o text-danger" aria-hidden="true"></i> Download Zip</a>';

                        attachmentColumn = '<td rowspan="' + response[i].poCount + '">' +
                            '<div class="input-group small col-sm-12">' +
                            '   <input type="hidden" name="attachRefNo[]" id="attachRefNo' + i + '" value="' + response[i].pra_ref + '" />' +
                            '   <input type="text" class="form-control attachSpecDoc input-sm" name="attachSpecDoc[]" id="attachSpecDoc' + i + '" readonly placeholder="Spec.docx" />' +
                            '   <span class="input-group-btn">' +
                            '       <button type="button" id="btnUploadAttachSpecDoc' + i + '" class="btn btn-outline btn-sm"><i class="icon wb-upload" aria-hidden="true"></i></button>' +
                            '   </span>' +
                            '</div>' +
                            '<div class="input-group small col-sm-12">' +
                            '   <input type="text" class="form-control attachEquipDoc input-sm" name="attachEquipDoc[]" id="attachEquipDoc' + i + '" readonly placeholder="Equip.docx" />' +
                            '   <span class="input-group-btn">' +
                            '       <button type="button" id="btnUploadAttachEquipDoc' + i + '" class="btn btn-outline btn-sm"><i class="icon wb-upload" aria-hidden="true"></i></button>' +
                            '   </span>' +
                            '</div>' +
                            '<div class="input-group small col-sm-12">' +
                            '   <input type="text" class="form-control attachQtyDoc input-sm" name="attachQtyDoc[]" id="attachQtyDoc' + i + '" readonly placeholder="Qty.docx" />' +
                            '   <span class="input-group-btn">' +
                            '       <button type="button" id="btnUploadAttachQtyDoc' + i + '" class="btn btn-outline btn-sm"><i class="icon wb-upload" aria-hidden="true"></i></button>' +
                            '   </span>' +
                            '</div>' +
                            '</td>';
                    }

                    if(caRef != response[i].pra_ref) {
                        caRefColumn = '<td rowspan="' + response[i].poCount + '">' + response[i].pra_ref + '</br> (' + response[i].division + ') ' +
                            '<input type="hidden" class="btrcDivision" id="btrcDivision' + i + '" value="' + btrcDiv + '" /></td>';
                        downloadColumn = '<td rowspan="' + response[i].poCount + '">' + letter + '</td>'
                        caRef = response[i].pra_ref;

                        /*-------------------------------------------------------------------------------------
                        * Keeping attachment element names in array to bind function after HTML append
                        * -------------------------------------------------------------------------------------*/
                        let arrCount = attachControl.length;
                        if(btrcDiv == btrc_division_ENO){
                            attachControl[arrCount] = "attachEODoc" + i + "|btnUploadAttachEODoc" + i +
                                ",attachEquipDoc" + i + "|btnUploadAttachEquipDoc" + i;
                        } else if(btrcDiv == btrc_division_SM) {
                            attachControl[arrCount] = "attachSpecDoc" + i + "|btnUploadAttachSpecDoc" + i +
                                ",attachEquipDoc" + i + "|btnUploadAttachEquipDoc" + i +
                                ",attachQtyDoc" + i + "|btnUploadAttachQtyDoc" + i;
                        }
                        /*-------------------------------------------------------------------------------------*/
                    }
                    else {
                        caRefColumn = '';
                        downloadColumn = '';
                        attachmentColumn = '';
                    }

                    get_data +='<tr>'+
                        caRefColumn +
                        '<td>' +response[i].pono+ '</td>'+
                        '<td>' +response[i].pinum+ '</td>'+
                        '<td>' +response[i].pidate+ '</td>'+
                        '<td>' +response[i].pivalue+ '</td>'+
                        '<td>'+
                            '<a href="download-attachment/'+response[i].PO.split(',',2)[1]+'" style="text-decoration: none;" target="_blank"><i class="icon fa-'+response[i].extpo+'"></i>&nbsp;PO</a>&nbsp;&nbsp;&nbsp;' +
                            '<a href="download-attachment/'+response[i].BOQ.split(',',2)[1]+'" style="text-decoration: none;" target="_blank"><i class="icon fa-'+response[i].extboq+'"></i>&nbsp;BOQ</a>&nbsp;&nbsp;&nbsp;' +
                            '<a href="download-attachment/'+response[i].Justification.split(',',2)[1]+'" style="text-decoration: none;" target="_blank"><i class="icon fa-'+response[i].extjust+'"></i>&nbsp;Justification</a>&nbsp;&nbsp;&nbsp;' +
                            '<a href="download-attachment/'+response[i].Catalog.split(',',2)[1]+'" style="text-decoration: none;" target="_blank"><i class="icon fa-'+response[i].extcat+'"></i>&nbsp;Catalog</a>' +
                        '</td>'+
                        '<td>'+
                            '<input type="checkbox" id="AllPra" name="AllPra[]" value="'+response[i].actionRef+'" title="Accept">' +
                            '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" title="Reject" style="margin-top: -6px;" data-target="#praRejectForm" data-toggle="modal" onclick="DeletePRA(' + response[i].id +')"><i class="icon wb-close text-danger" aria-hidden="true"></i></button>'+
                        '</td>'+
                        downloadColumn + attachmentColumn +
                        '</tr>';
                }

                $("#myTable").append(get_data);
                /*---------------------------------------------------------------------------------
                * Binding function to control
                *---------------------------------------------------------------------------------*/
                //alert(attachControl.length);
                for (let c = 0; c < attachControl.length; ++c) {

                    //alert(attachControl);
                    var aValRow = attachControl[c].split(",");
                    //alert(aValRow.length);

                    for (let d = 0; d < aValRow.length; ++d) {

                        var aVal = aValRow[d].split("|");

                        //alert(aVal[0]);
                        //alert(aVal[1]);

                        $(function () {
                            var button = $('#' + aVal[1]), interval;
                            var txtbox = $('#' + aVal[0]);

                            if (!$(button).is(':disabled')) {

                                new AjaxUpload(button, {
                                    action: 'application/library/uploadhandler.php',
                                    name: 'upl',
                                    onComplete: function (file, response) {
                                        var res = JSON.parse(response);
                                        txtbox.val(res['filename']);
                                        window.clearInterval(interval);
                                    },
                                    onSubmit: function (file, ext) {
                                        if (!(ext && /^(doc|docx|pdf)$/i.test(ext))) {
                                            alert('Invalid File Format.');
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
                            }
                        });
                    }
                }
                //---------------------------------------------------------------------------------
            },
            error:function (err) {
                console.log(err);
            }
        });
    }

    otherIndex();
    function otherIndex() {

        $.ajax({
            type:"GET",
            dataType:"json",
            url: "api/pra-interface?action=1&my=false",
            success:function (response) {
                // console.log((response))
                var get_data ="";
                var caRef = 0;
                var caRefColumn = '';
                for (let i = 0; i < response.length; ++i) {
                    if(caRef != response[i].pra_ref) {
                        caRefColumn = '<td rowspan="' + response[i].poCount + '">' + response[i].pra_ref + '</br> (' + response[i].division + ') </td>';
                        caRef = response[i].pra_ref;
                    }
                    else {
                        caRefColumn = '';
                    }

                    get_data +='<tr>'+
                        caRefColumn +
                        '<td>' +response[i].pono+ '</td>'+
                        '<td>' + '<input type="checkbox" id="submitPRA" name="submitPRA[]" value="'+response[i].actionRef+'">' +
                        '</td>'+
                        '<td>'+ '<button type="button" class="btn btn-sm btn-icon btn-flat btn-default" style="margin-top: -6px;" data-target="#btrcRejectForm" data-toggle="modal" onclick="DeleteBTRC(' + response[i].id + ')"><i class="icon wb-close text-danger" aria-hidden="true"></i></button>'+'</td>'+
                        '</tr>';
                }
                $("#otherTable").html(get_data);
            },
            error:function (err) {
                console.log(err);
            }
        });
    }

    // STRAT REQUEST SENT FROM PRA TO BTRC

    $("#btnPRASubmit").click(function () {
        getCheckData('AllPra');
    });

    var getCheckData = function (refid) {
        var val = [];
        var attach = '';

        if ($("input[name='AllPra[]']").filter(":checked").length < 1){
            alertify.error('Please checked at least one');
            return false;
        }
        else {

            var finalDocs = "";
            var btrcDivision = "";
            $("input[name='AllPra[]']").filter(":checked").each(function(i){

                /* ---------------------------------------------------------------------------------
                 * Why using this condition here?
                 * When we run a loop for each row of a table, the element found in
                 * undefined state which are inside a marged row cell. Here in case of 'TA Ref' and
                 * 'Attachment' columns we can not find it's inside element from each rows
                 * related to those elements except first row.
                 *---------------------------------------------------------------------------------*/
                if($(this).closest("tr").find("input.btrcDivision").val()!==undefined){
                    btrcDivision = $(this).closest("tr").find("input.btrcDivision").val();
                }

                if(btrcDivision==btrc_division_ENO){
                    if ($(this).closest("tr").find("input.attachEODoc").val() === "" ||
                        $(this).closest("tr").find("input.attachEquipDoc").val() === "") {
                        alertify.error('Please attach all required documents');
                        return false;
                    } else {
                        /*
                         * For the same reason as above
                         */
                        if($(this).closest("tr").find("input.attachSpecDoc").val()!==undefined) {
                            finalDocs = $(this).closest("tr").find("input.attachSpecDoc").val() + "," +
                                $(this).closest("tr").find("input.attachEquipDoc").val() + "," +
                                $(this).closest("tr").find("input.attachQtyDoc").val();
                        }
                        val[i] = $(this).val() + "|" + finalDocs;
                    }
                } else if(btrcDivision==btrc_division_SM)
                {
                    if ($(this).closest("tr").find("input.attachSpecDoc").val() === "" ||
                        $(this).closest("tr").find("input.attachEquipDoc").val() === "" ||
                        $(this).closest("tr").find("input.attachQtyDoc").val() === "") {
                        alertify.error('Please attach all required documents');
                        return false;
                    } else {
                        /*
                         * For the same reason as above
                         */
                        if($(this).closest("tr").find("input.attachSpecDoc").val()!==undefined) {
                            finalDocs = $(this).closest("tr").find("input.attachSpecDoc").val() + "," +
                                $(this).closest("tr").find("input.attachEquipDoc").val() + "," +
                                $(this).closest("tr").find("input.attachQtyDoc").val();
                        }
                        val[i] = $(this).val() + "|" + finalDocs;
                    }
                }
            });
            alert(val);
            if(val.length>0){
                alertify.confirm( 'Are you sure you want submit?', function () {
                    $.ajax({
                        type: "POST",
                        url: "api/pra-interface?action=3",
                        dataType: "JSON",
                        data:{
                            val:val
                        },
                        cache: false,
                        success: function (result) {
                            console.log(result);
                            // $('.table').DataTable().ajax.reload();
                            if (result['status'] == 1) {
                                alertify.success(result['message']);
                                location.reload(true);
                                /* var mtable = $('#dtMyInbox').dataTable();
                                 mtable.api().ajax.reload();
                                 var otable = $('#dtOtherInbox').dataTable();
                                 otable.api().ajax.reload();*/
                            } else {
                                alertify.error(result['message']);
                                return false;
                            }
                        },
                        error: function (xhr) {
                            console.log('Error: ' + xhr);
                        }
                    });
                });
            }
        }
    }

// END REQUEST SENT FROM PRA TO BTRC

// STRAT REQUEST SENT FROM BTRC TO BUYER
    $("#btnPraToBuyerSubmit").click(function () {
        getPraData('submitPRA');
    });

    var getPraData = function (refid) {
        if (validate() === true) {
            var val = [];
            $(':checkbox:checked').each(function (i) {
                val[i] = $(this).val();
            });

            var btrcAttah = $("#attachBTRCNOC").val();
            // console.log(btrcAttah);
            // console.log(val);
            alertify.confirm('Are you sure you want submit?', function () {
                $.ajax({
                    type: "POST",
                    url: "api/pra-interface?action=5",
                    dataType: "JSON",
                    data: {
                        val: val,
                        attachment: btrcAttah
                    },
                    cache: false,
                    success: function (result) {
                        console.log(result);
                        // $('.table').DataTable().ajax.reload();
                        if (result['status'] == 1) {
                            alertify.success(result['message']);
                            location.reload(true);
                            $('#attachBTRCNOC').val('').change();
                        } else {
                            alertify.error(result['message']);
                            return false;
                        }
                    },
                    error: function (xhr) {
                        console.log('Error: ' + xhr);
                    }
                });
            });
        }else {
            return false;
        }
    }

    // END REQUEST SENT FROM BTRC TO BUYER

    //APPLICATION E&O LETTER GENERATE
/*    $("#btnGenerate_EOLetter").click (function (e) {
// alert("clicked")
        e.preventDefault();

                //--- getting letter serial number based on PO, shipment and bank ---------
                    $.ajax({
                        url: "application/templates/letter_template/application_eo_letter.html",
                        cache: false,
                        global: false,
                        success: function (response) {
                            //alert(response);
                            try {
                                var temp = response;

                                //---------------replace data-----------------
                                $("#fileName").val('Application_E&O'+'.doc');
                                $("#letterContent").val(temp);
                                document.getElementById("formLetterContent").submit();
                            } catch (e) {
                                alertify.error(response + ' Failed to process the request.', 20);
                                return false;
                            }
                        }
                    });




    });*/

    //APPLICATION SPECTRUM LETTER GENERATE

/*    $("#btnGenerate_SpectrumLetter").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: "application/templates/letter_template/application_spectrum_letter.html",
            cache: false,
            global: false,
            success: function (response) {
                //alert(response);
                try {
                    var temp = response;

                    //---------------replace data-----------------
                    $("#fileName").val('Application_Spectrum'+'.doc');
                    $("#letterContent").val(temp);
                    document.getElementById("formLetterContent").submit();
                } catch (e) {
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            }
        });

    });*/


    //APPLICATION EQUIPMENT LIST LETTER GENERATE
/*    $("#btnGenerate_EquipmentLetter").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: "application/templates/letter_template/equipment_list_description.html",
            cache: false,
            global: false,
            success: function (response) {
                //alert(response);
                try {
                    var temp = response;

                    //---------------replace data-----------------
                    $("#fileName").val('Equipment_list_description'+'.doc');
                    $("#letterContent").val(temp);
                    document.getElementById("formLetterContent").submit();
                } catch (e) {
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            }
        });

    });*/

    //APPLICATION EQUIPMENT QUANTITY LETTER GENERATE
/*    $("#btnGenerate_QuantityLetter").click(function (e) {
        e.preventDefault();

        $.ajax({
            url: "application/templates/letter_template/equipments_quantity.html",
            cache: false,
            global: false,
            success: function (response) {
                //alert(response);
                try {
                    var temp = response;

                    //---------------replace data-----------------
                    $("#fileName").val('Equipments_Quantity'+'.doc');
                    $("#letterContent").val(temp);
                    document.getElementById("formLetterContent").submit();
                } catch (e) {
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            }
        });

    });*/
    // setTimeout(document.attachmentZip.submit(),6000);
    // $("#attachmentZip").load(function($) {$('#submit').click();});
    // $("#attachmentZip").load(function() {
    //     $("#attachmentZip").submit();
    // });
});

function DeletePRA(id) {

      $("#btnPraReject").click(function (e) {
        // alert('clicked');
        e.preventDefault();
        if (validatePRA() === true) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/pra-interface?action=2&id="+id,
                    data: $('#form-pra-reject').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res["status"] == 1) {
                                ResetForm();
                                alertify.success(res['message']);
                                $('#praRejectForm').modal('hide');
                                location.reload(true);
                            } else {
                                //$("#SendPO_btn").show();
                                alertify.error(res['message']);
                                return false;
                            }
                        } catch (e) {
                            console.log(e);
                            alertify.error(response + ' Failed to process the request.');
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error);
                    }
                });
            }else {

            }
        }else{
            return false;
        }
    });
    // console.log(button)
            // $.get('api/pra-interface?action=2&id=' + id, function(data) {
            //     if (data == 1) {
            //         alertify.success("CA Rejected successfully!");
            //         var dtable = $('#dtMyInbox').dataTable();
            //         dtable.api().ajax.reload();
            //     } else {
            //         alertify.error("Rejection Fail!");
            //     }
            // });
}

function ResetForm() {
    $('#form-pra-reject')[0].reset();
}
function DeleteBTRC(id) {
    console.log(id);

    $("#btnBtrcReject").click(function (e) {
        // alert('clicked');
        e.preventDefault();
        if (validateBTRC() === true) {
            if (e) {
                $.ajax({
                    type: "POST",
                    url: "api/pra-interface?action=4&id="+id,
                    data: $('#form-btrc-reject').serialize(),
                    cache: false,
                    success: function (response) {
                        // alert(response);
                        try {
                            var res = JSON.parse(response);
                            console.log(res)
                            if (res["status"] == 1) {
                                ResetForm();
                                alertify.success(res['message']);
                                $('#btrcRejectForm').modal('hide');
                                location.reload(true);
                            } else {
                                //$("#SendPO_btn").show();
                                alertify.error(res['message']);
                                return false;
                            }
                        } catch (e) {
                            console.log(e);
                            alertify.error(response + ' Failed to process the request.');
                            return false;
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        alertify.error(textStatus + ": " + xhr.status + " " + error);
                    }
                });
            }else {

            }
        }else{
            return false;
        }
    });
  /*  $.get('api/pra-interface?action=4&id=' + id, function(data) {
        if (data == 1) {
            alertify.success("PRA Rejected successfully!");
            var dtable = $('#dtOtherInbox').dataTable();
            dtable.api().ajax.reload();
            var mtable = $('#dtMyInbox').dataTable();
            mtable.api().ajax.reload();
        } else {
            alertify.error("Rejection Fail!");
        }
    });*/
}

function validatePRA() {
    if ($("#remarks").val() == "") {
        $("#remarks").focus();
        alertify.error("Specify the Reason");
        return false;
    }
    return true;
}

function validateBTRC() {
    if ($("#remarks1").val() == "") {
        $("#remarks1").focus();
        alertify.error("Specify the Reason");
        return false;
    }
    return true;
}
function validate() {
    if ($("input[name='submitPRA[]']").filter(":checked").length < 1){
        alertify.error("Please checked at least one");
        return false;
    }
    if ($("#attachBTRCNOC").val() == "") {
        $("#attachBTRCNOC").focus();
        alertify.error("Give an attachment");
        return false;
    }

    return true;
}

//ATTACHMENTS UPLOAD SCRIPT FOR SUBMITTED FORM BTRC

$(function () {

    if($("#usertype").val()==const_role_public_regulatory_affairs){

        var button = $('#btnUploadBTRCNOC'), interval;
        var txtbox = $('#attachBTRCNOC');

        new AjaxUpload(button, {
            action: 'application/library/uploadhandler.php',
            name: 'upl',
            onComplete: function (file, response) {
                var res = JSON.parse(response);
                txtbox.val(res['filename']);
                window.clearInterval(interval);
            },
            onSubmit: function (file, ext) {
                if (!(ext && /^(jpg|png|xlsx|xls|doc|docx|pdf|zip)$/i.test(ext))) {
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
    }
});
//individual attahment upload script

/*
function fileUpload(id) {
    // console.log($('#file'+id)[0].files[0])
    $('#att'+id).val($('#file'+id)[0].files[0].name)
}
function UpdateAttached(id) {
    // console.log(id)
    //document.getElementById('my_file').click();
    //  $('#att'+id).val('ki khobor')
    var button= $('#file'+id).click();
}*/


//EO LETTER GENERATE

function downloadApp_eo_letter(ref){
    console.log(ref)
    $.get("api/pra-interface?action=7&ref=" + ref, function (data) {

        poData = JSON.parse(data);
        console.log(poData.length)
        var d = new Date();
        letterRef = "";
            if (ref == "115") {
                letterRef = docref_custom_pra_letter_ref + d.getFullYear() + "/" + zeroPad(1);
            } else if(ref == "116"){
                letterRef = docref_custom_pra_letter_ref + d.getFullYear() + "/" + zeroPad(2);
            }
        $.ajax({
            url: 'application/templates/letter_template/application_eo_letter.html',
            cache: false,
            global: false,
            success: function (response) {
                //alert(response);

                try {
                    var temp = response;
                    // console.log(temp)
                    //---------------replace data-----------------
                    var html="";
                    for (var i=0; i < poData.length;i++){

                        html += '<tr style="border: 1px solid black;border-collapse: collapse;">\n' +
                            '        <td style="width: 50%;border-collapse: collapse;border: 1px solid black; font-size: 12px;">'+poData[i]["itemDesc"]+'</td>\n' +
                            '        <td style="width: 50%;border-collapse: collapse;border: 1px solid black;font-size: 12px;">'+poData[i]["justification"]+'</td>\n' +
                            '    </tr>'
                        // temp = temp.replace('##itemDesc##', poData[i]["itemDesc"]);
                        // temp = temp.replace('##summary##', poData[i]["justification"]);
                    }
                    temp = temp.replace('##tBody##',html);
                    temp = temp.replace('##SL##',letterRef);
                    //---------------end replace data-------------

                    $("#fileName").val('Application_E&O'+'.doc');
                    $("#letterContent").val(temp);
                    document.getElementById("formLetterContent").submit();


                } catch (e) {
                    alertify.error(response + ' Failed to process the request.', 20);
                    return false;
                }
            }
        // });

        });

         });
    // }


}

// EQUIPMENT LETTER GENERATE

function downloadApp_equipment_letter(ref){
    console.log(ref)
    $.get("api/pra-interface?action=7&ref=" + ref, function (data) {

        poData = JSON.parse(data);
        console.log(poData)
    $.ajax({
        url: "application/templates/letter_template/equipment_list_description.html",
        cache: false,
        global: false,
        success: function (response) {
            //alert(response);
            try {
                var temp = response;

                var html="";
                for (var i=0; i < poData.length;i++){

                    html += '<tr style="border: 1px solid black;border-collapse: collapse;">\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+ (i+1) +'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["supplier"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["poNo"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["pinum"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["pidate"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["itemDesc"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["poTotal"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["currency"]+'</td>\n' +
                        '    </tr>'
                    // temp = temp.replace('##itemDesc##', poData[i]["itemDesc"]);
                    // temp = temp.replace('##summary##', poData[i]["justification"]);
                }
                temp = temp.replace('##tBody##',html);
                //---------------replace data-----------------
                $("#fileName").val('Equipment_list_description'+'.doc');
                $("#letterContent").val(temp);
                document.getElementById("formLetterContent").submit();
            } catch (e) {
                alertify.error(response + ' Failed to process the request.', 20);
                return false;
            }
        }
    });

    });
}

//SPECTTRUM LETTER GENERATE

function downloadApp_spectrum_letter(ref) {
    var d = new Date();
    letterRef = "";
    if (ref == "115") {
        letterRef = docref_custom_pra_letter_ref + d.getFullYear() + "/" + zeroPad(1);
    } else if(ref == "116"){
        letterRef = docref_custom_pra_letter_ref + d.getFullYear() + "/" + zeroPad(1);
    }
    $.ajax({
        url: "application/templates/letter_template/application_spectrum_letter.html",
        cache: false,
        global: false,
        success: function (response) {
            //alert(response);
            try {
                var temp = response;

                temp = temp.replace('##SL##',letterRef);
                //---------------replace data-----------------
                $("#fileName").val('Application_Spectrum'+'.doc');
                $("#letterContent").val(temp);
                document.getElementById("formLetterContent").submit();
            } catch (e) {
                alertify.error(response + ' Failed to process the request.', 20);
                return false;
            }
        }
    });

}

//QUANTITY LETTER GENERATE

function  downloadApp_quantity_letter(ref) {
    console.log(ref)
    $.get("api/pra-interface?action=7&ref=" + ref, function (data) {

        poData = JSON.parse(data);
        // console.log(poData)
    $.ajax({
        url: "application/templates/letter_template/equipments_quantity.html",
        cache: false,
        global: false,
        success: function (response) {
            //alert(response);
            try {
                var temp = response;
                var html="";
                for (var i=0; i < poData.length;i++){

                    html += '<tr style="border: 1px solid black;border-collapse: collapse;">\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+ (i+1) +'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;text-align: left;">'+poData[i]["itemDesc"]+'</td>\n' +
                        '        <td style="border-collapse: collapse;border: 1px solid black; font-size: 10px;">'+poData[i]["poQty"]+'</td>\n' +
                        '    </tr>'
                    // temp = temp.replace('##itemDesc##', poData[i]["itemDesc"]);
                    // temp = temp.replace('##summary##', poData[i]["justification"]);
                }
                temp = temp.replace('##tBody##',html);
                //---------------replace data-----------------
                $("#fileName").val('Equipments_Quantity'+'.doc');
                $("#letterContent").val(temp);
                document.getElementById("formLetterContent").submit();
            } catch (e) {
                alertify.error(response + ' Failed to process the request.', 20);
                return false;
            }
        }
    });

      });

}

function downloadEOZip(cref) {

    $.ajax({
        type:"GET",
        dataType:"json",
        url: "api/pra-interface?action=6&cref="+cref,
        success:function (response) {
            console.log((response))

            for (let i = 0; i < response.length; ++i) {

                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].PO.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].BOQ.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].Justification.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].Catalog.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
            }
            // alert("eo");

                $('#submit').trigger('click');
                 reset();
        },
        error:function (err) {
            console.log(err);
        }
    });
}

function downloadSMZip(cref) {
    // console.log(cref)
    $.ajax({
        type:"GET",
        dataType:"json",
        url: "api/pra-interface?action=6&cref="+cref,
        success:function (response) {
            console.log((response))

            for (let i = 0; i < response.length; ++i) {

                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].PO.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].BOQ.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].Justification.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
                $('div#filesToZip').append(
                    $('<input>').attr({
                        'type': 'checkbox',
                        'name': 'files[]',
                        'value': response[i].Catalog.split(',',2)[0],
                        'checked': '""',
                        'hidden': '""'
                    })
                );
            }
            // alert("ok");
                $('#submit').trigger('click');
                reset();
        },
        error:function (err) {
            console.log(err);
        }
    });
}
function reset(){
    setTimeout(function(){
        $('div#filesToZip').html("");
    }, 100);
}
