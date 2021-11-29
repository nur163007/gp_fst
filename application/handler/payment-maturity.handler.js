/**
 * Created by HasanMasud on 28-Feb-19.
 */
var startDate_val = '';
var endDate_val = '';
$(document).ready(function () {

    /******THIS MONTH******
     **********************/
    /*start = moment().startOf('month');
    end = moment().endOf('month');*/

    /******THIS WEEK******
     **********************/
    start = moment().startOf('week');
     end = moment().endOf('week');

    /*Last Week*/
    /*start = moment().subtract(1, 'week').startOf('week');
     end = moment().subtract(1, 'week').endOf('week').add(1, 'day');*/

    function cb(start, end) {
        $('#reportRange span').html(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        $("#startDate").val(start.format('YYYY-MM-DD'));
        $("#endDate").val(end.format('YYYY-MM-DD'));

        if ($('#applyDate').parent().hasClass('checked')) {
            startDate_val = $("#startDate").val();
            endDate_val = $("#endDate").val();
        } else {
            startDate_val = '';
            endDate_val = '';
        }
    }

    $('#reportRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week').add(1, 'day')],
            'This Week': [moment().startOf('week'), moment().endOf('week')],
            'Next Week': [moment().add(1, 'week').startOf('week'), moment().add(1, 'week').endOf('week').add(1, 'day')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')]
        },
        "autoUpdateInput": false
    }, cb);

    cb(start, end);

    $("#poNo").select2({
        placeholder: "loading...",
        width: "100%"
    });

    $.getJSON("api/payment-history?action=1", function (list) {
        $("#poNo").select2({
            data: list,
            placeholder: "Select a PO Number",
            allowClear: true,
            width: "100%"
        });
    });

    $('#applyDate').on('ifClicked', function (event) {
        if ($('#applyDate').parent().hasClass('checked')) {
            startDate_val = '';
            endDate_val = '';
        } else {
            startDate_val = $("#startDate").val();
            endDate_val = $("#endDate").val();
        }
    });

    $("#applyFilter").click(function (e) {
        e.preventDefault();
        if (validate() === true) {
            getPayMaturityData(startDate_val, endDate_val, $("#poNo").val());
        } else {
            return false;
        }
    });
});

/*********GET PAYMENT MATURITY**********
 **********CREATED BY: HASAN MASUD******/
function getPayMaturityData(startDate, endDate, poNo) {
    //"api/payment-history?action=3&poNo=" + $('#poNo').val()
    var url = "api/payment-history?action=3&startDate=" + startDate + "&endDate=" + endDate + "&poNo="+poNo;
    //alert(url);
        $.ajax({
            url: url,
            dataType: "text",
            success: function (data) {
                var json = $.parseJSON(data);
                $("#payMaturity").empty();
                var ci = '';
                var payableAmntHTML = '';
                var payableDateHTML = '';
                for (var i = 0; i < json.length; ++i) {
                    if (ci != json[i].ciNo) {
                        ci = json[i].ciNo;
                        $('#payMaturity').append(
                            '<tr><td colspan="6" style="text-align: left; font-weight: bold; font-size: 120%">PO# ' + json[i].pono + '\, CI# ' + json[i].ciNo + ' & CI Value: ' + commaSeperatedFormat(json[i].ciAmount) +' ' +json[i].currencyName + '</td></tr>'
                        );

                    }
                    /*CONDITION TO CHECK PAID AMOUNT
                    *IF PAYMENT ALREADY MADE SHOW BLANK FIELD*/
                    if (json[i].paidAmount === null) {
                        payableAmntHTML = '<td class="text-right">' + commaSeperatedFormat(json[i].payableAmount) + '</td>';
                    } else {
                        payableAmntHTML = '<td></td>';

                    }

                    /*CONDITION TO CHECK PAID DATE
                     *IF PAYMENT ALREADY MADE SHOW BLANK FIELD*/
                    if (json[i].paidDate === null) {
                        payableDateHTML = '<td class="text-center">' + json[i].payableDate + '</td>';
                    } else {
                        payableDateHTML = '<td class="text-center"></td>';

                    }
                    $('#payMaturity').append(
                        '<tr>' +
                        '<td class="text-center">' + json[i].payDocName + '</td>' +
                        '<td class="text-center">' + json[i].paymentPercent + '</td>' +
                        '<td class="text-center">' + json[i].lcno + '</td>' +
                        '<td class="text-center">' + json[i].ciNo + '</td>' +
                        '<td class="text-right">' + commaSeperatedFormat(json[i].paidAmount) + '</td>' +
                        '<td>' + json[i].paidDate + '</td>' +
                        payableAmntHTML +
                        payableDateHTML +
                        '</tr>'
                    );
                }
            }
        });
}

function validate() {
    if($("#poNo").val()==="" && endDate_val ===''){
        alertify.error('Use at least one filter.');
        return false;
    }
    return true;
}