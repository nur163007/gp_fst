$(document).ready(function() {
    //    view fx request list
    $('#dtFx').dataTable({
        "ajax": "api/fx-request-primary?action=1&status=0",
        "columns": [
            {"data": "id"},
            {"data": "req_type"},
            {"data": "supplier_name"},
            {"data": "nature_of_service"},
            {"data": "lc_bank"},
            {"data": "currency"},
            {"data": "fx_value"},
            {"data": "value_date"}
        ],
        "sorting": [[1, "asc"]],
        "sDom": 'frtip',
        "paging": true,
        "pageLength": 10
    });

    $("#primaryReqForRFQ").click(function (e) {
        e.preventDefault();
        alertify.confirm('Are you sure to submit this request?', function (e) {
            if (e) {
                $.get('api/fx-request?action=12', function (response) {
                    var res = JSON.parse(response);
                    if (res['status'] == 1) {
                        alertify.success(res['message']);
                        window.location.href = _dashboardURL;
                    } else {
                        alertify.error(res['message']);
                        return false;
                    }
                });
            } else {
                //
            }
        });
    });
});
