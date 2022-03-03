<?php

//For live
//$jsVersion = "2.5.1";
//For local
$jsVersion = date("dmYhis");

$page_specific_js = array(
    '<script src="assets/vendor/matchheight/jquery.matchHeight-min.js"></script>',
    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
    '<script src="assets/vendor/intro-js/intro.js"></script>'
);
		
$page_specific_jsfunc = array("<script></script>");

$page_specific_js1 = array(
    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
    '<script src="assets/vendor/chart-js/Chart.js?v=1"></script>',
    '<script src="assets/vendor/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>',
    '<script src="application/handler/dashboard.handler.js?v='. $jsVersion .'"></script>'
);

if(isset($_REQUEST['q'])) {
    $page = $_REQUEST['q'];

    switch ($page) {
        case "dashboard":
            $page_specific_js = array(
                '<script src="assets/vendor/matchheight/jquery.matchHeight-min.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/intro-js/intro.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/chart-js/Chart.js?v=1"></script>',
                '<script src="assets/vendor/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>',
                '<script src="application/handler/dashboard.handler.js?v=' . $jsVersion . '"></script>'
            );
            break;
        case "category":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="application/handler/category.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "users":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/users.handler.js?v=' . $jsVersion . '"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "profile":
            $page_specific_js = array(
                '<script src="assets/vendor/toastr/toastr.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/toastr.js"></script>',
                '<script src="application/handler/profile.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "user-roles":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/userroles.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "contract":
            $page_specific_js = array(
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/contract.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "contracts":
            $page_specific_js = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/contracts.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "privilege":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/privilege.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "content":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/content.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array("<script>
                $(document).ready(function ($) {
                    (function () {
                        $('#tag')
                          .on('tokenfield:createtoken', function (e) {
                              var data = e.attrs.value.split('|');
                              e.attrs.value = data[1] || data[0];
                              e.attrs.label = data[1] ? data[0] + ' (' + data[1] + ')' :
                                data[0];
                          })
                          .on('tokenfield:createdtoken', function (e) {
                              var re = /\S+@@\S+\.\S+/;
                              var valid = re.test(e.attrs.value);
                              if (!valid) {
                                  $(e.relatedTarget).addClass('invalid');
                              }
                          })
                          .on('tokenfield:edittoken', function (e) {
                              if (e.attrs.label !== e.attrs.value) {
                                  var label = e.attrs.label.split(' (');
                                  e.attrs.value = label[0] + '|' + e.attrs.value;
                              }
                          })
                          .on('tokenfield:removedtoken', function (e) {
                              if (e.attrs.length > 1) {
                                  var values = $.map(e.attrs, function (attrs) {
                                      return attrs.value;
                                  });
                              }
                          })
                          .tokenfield();
                            })();
                        });
                    </script>");

            break;

        case "company":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/company.handler.js?v=' . $jsVersion . '"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>'
            );
            $page_specific_jsfunc = array("<script>
                $(document).ready(function ($) {
                    (function () {
                        $('#emailTo, #emailCc')
                          .on('tokenfield:createtoken', function (e) {
                              var data = e.attrs.value.split('|');
                              e.attrs.value = data[1] || data[0];
                              e.attrs.label = data[1] ? data[0] + ' (' + data[1] + ')' :
                                data[0];
                          })
                          .on('tokenfield:createdtoken', function (e) {
                              var re = /\S+@@\S+\.\S+/;
                              var valid = re.test(e.attrs.value);
                              if (!valid) {
                                  $(e.relatedTarget).addClass('invalid');
                              }
                          })
                          .on('tokenfield:edittoken', function (e) {
                              if (e.attrs.label !== e.attrs.value) {
                                  var label = e.attrs.label.split(' (');
                                  e.attrs.value = label[0] + '|' + e.attrs.value;
                              }
                          })
                          .on('tokenfield:removedtoken', function (e) {
                              if (e.attrs.length > 1) {
                                  var values = $.map(e.attrs, function (attrs) {
                                      return attrs.value;
                                  });
                              }
                          })
                          .tokenfield();
                            })();
                        });
                    </script>");

            break;

        case "navigations":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/navigations.handler.js?v=' . $jsVersion . '"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "bank-insurance":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/bankinsurance.handler.js?v=' . $jsVersion . '"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "events":
            $page_specific_js = array(
                '<script src="assets/vendor/moment/moment.min.js"></script>',
                '<script src="assets/vendor/fullcalendar/fullcalendar.js"></script>',
                '<script src="assets/vendor/jquery-selective/jquery-selective.min.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>'
            );

            $page_specific_js1 = array(
                '<script src="application/handler/events.handler.js?v=' . $jsVersion . '"></script>',
                '<script src="assets/js/configs/config-colors.js"></script>',
                '<script src="assets/js/configs/config-tour.js"></script>',
                '<script src="assets/js/components/asscrollable.js"></script>',
                '<script src="assets/js/components/animsition.js"></script>',
                '<script src="assets/js/components/bootstrap-touchspin.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/apps/app.js"></script>',
                '<script src="assets/js/apps/calendar.js"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "new-po":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/new-po.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array("<script>
                $(document).ready(function ($) {
                    (function () {
                        $('#prEmailTo, #prEmailCC, #emailto, #emailcc')
                          .on('tokenfield:createtoken', function (e) {
                              var data = e.attrs.value.split('|');
                              e.attrs.value = data[1] || data[0];
                              e.attrs.label = data[1] ? data[0] + ' (' + data[1] + ')' :
                                data[0];
                          })
                          .on('tokenfield:createdtoken', function (e) {
                              var re = /\S+@@\S+\.\S+/;
                              var valid = re.test(e.attrs.value);
                              if (!valid) {
                                  $(e.relatedTarget).addClass('invalid');
                              }
                          })
                  .on('tokenfield:edittoken', function (e) {
                      if (e.attrs.label !== e.attrs.value) {
                          var label = e.attrs.label.split(' (');
                          e.attrs.value = label[0] + '|' + e.attrs.value;
                      }
                  })
                  .on('tokenfield:removedtoken', function (e) {
                      if (e.attrs.length > 1) {
                          var values = $.map(e.attrs, function (attrs) {
                              return attrs.value;
                          });
                      }
                  })
                  .tokenfield();
                    })();
                });
            </script>");
            break;


        case "new-pi":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/new-pi.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array("<script>
                $(document).ready(function ($) {
                    (function () {
                        $('#prEmailTo, #prEmailCC, #emailto, #emailcc')
                          .on('tokenfield:createtoken', function (e) {
                              var data = e.attrs.value.split('|');
                              e.attrs.value = data[1] || data[0];
                              e.attrs.label = data[1] ? data[0] + ' (' + data[1] + ')' :
                                data[0];
                          })
                          .on('tokenfield:createdtoken', function (e) {
                              var re = /\S+@@\S+\.\S+/;
                              var valid = re.test(e.attrs.value);
                              if (!valid) {
                                  $(e.relatedTarget).addClass('invalid');
                              }
                          })
                  .on('tokenfield:edittoken', function (e) {
                      if (e.attrs.label !== e.attrs.value) {
                          var label = e.attrs.label.split(' (');
                          e.attrs.value = label[0] + '|' + e.attrs.value;
                      }
                  })
                  .on('tokenfield:removedtoken', function (e) {
                      if (e.attrs.length > 1) {
                          var values = $.map(e.attrs, function (attrs) {
                              return attrs.value;
                          });
                      }
                  })
                  .tokenfield();
                    })();
                });
            </script>");
            break;

        case "edit-po":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/edit-po.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "all-po":
            $page_specific_js = array('<script src="assets/vendor/select2/select2.min.js"></script>');
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="application/handler/allpo.handler.js?v=2.5.4"></script>'
            );
            $page_specific_jsfunc = array('');

            break;


        case "suppliers-pi":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/suppliers-pi.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;


        case "buyers-piboq":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/buyers-piboq.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;


        case "pr-ea-interface":

            $page_specific_js = array(
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/pr-ea-interface.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;


        case "final-pi":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-tokenfield/bootstrap-tokenfield.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-tokenfield.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/finalpi.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;


        case "view-po":

            $page_specific_js = array('');
            $page_specific_js1 = array(
                '<script src="application/handler/view-po.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;


        case "btrc-interface":

            $page_specific_js = array(
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="application/handler/btrc-interface.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "buyers-lc-request":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/x-editable/bootstrap-editable.js"></script>',
                '<script src="assets/vendor/typeahead-js/bloodhound.min.js"></script>',
                '<script src="assets/vendor/typeahead-js/typeahead.jquery.min.js"></script>',
                '<script src="assets/vendor/x-editable/address.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/panel.js"></script>',
                '<script src="application/handler/buyers-lc-request.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-request":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/jqprint/jQuery.print.js"></script>',
                '<script src="assets/vendor/jqprint/printThis.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/lc-request.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-opening":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/jqprint/jQuery.print.js"></script>',
                '<script src="assets/vendor/jqprint/printThis.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/lc-opening.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-acceptance":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/jspdf/jspdf.min.js"></script>',
                '<script src="assets/vendor/jspdf/from_html.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/lc-acceptance.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;
        case "marine-insurance":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/marine-insurance.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;
        case "lc-opening-bank-charges":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/lc-opening-bank-charges.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "original-doc":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/original-doc.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "amendment-request":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/amendment-request.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "endorsement":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/endorsement.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "average-cost-fin":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',

                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',

                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',

                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/average-cost-fin.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "payment-entry":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/alertify-js/alertify.js"></script>',
                '<script src="assets/vendor/jspdf/jspdf.min.js"></script>',
                '<script src="assets/vendor/jspdf/from_html.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/payment-entry.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "custom-duty":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/generatefile/jquery.generateFile.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/custom-duty.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "shipment":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/shipment.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "buyer-prealert":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/buyer-prealert.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "shipment-ext":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="application/handler/shipment-ext.handler.js"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "warehouse-inputs":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/warehouse-inputs.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "ea-inputs":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/generatefile/jquery.generateFile.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/ea-inputs.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "cnf-cost-update":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/cnf-cost-update.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-outstanding-lc-list":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-outstanding-lc-list.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-payment-detail":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-payment-detail.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "custom-duty-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="application/handler/custom-duty-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-commission-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="application/handler/lc-commission-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "vat-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/vat-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-insurance-premium":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="application/handler/fn-report-insurance-premium.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-fund-utilization":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-fund-utilization.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-trade-finance":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-trade-finance.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-operational-update":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-operational-update.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "aging-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/aging-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-wise-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/lc-wise-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-opening-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/lc-opening-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-charges":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-charges.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "buyer-wise-po-report":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/buyer-wise-po-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-lc-opening":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-lc-opening.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-lc-endorsement":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-lc-endorsement.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fn-report-lc-amendment":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fn-report-lc-amendment.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "sourcing-report-po-database":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/sourcing-report-po-database.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "sourcing-report-ea-team-act":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/sourcing-report-ea-team-act.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "sourcing-report-po-wise-sla":

            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="application/handler/sourcing-report-po-wise-sla.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "close-sourcing-process":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/x-editable/bootstrap-editable.js"></script>',
                '<script src="assets/vendor/typeahead-js/bloodhound.min.js"></script>',
                '<script src="assets/vendor/typeahead-js/typeahead.jquery.min.js"></script>',
                '<script src="assets/vendor/x-editable/address.js"></script>',
                '<script src="assets/vendor/asspinner/jquery-asSpinner.min.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/components/panel.js"></script>',
                '<script src="application/handler/close-sourcing-process.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "charge-entry":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="application/handler/charge-entry.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "explorer":

            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/explorer.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "access-denied":

            $page_specific_js = array();
            $page_specific_js1 = array();
            $page_specific_jsfunc = array('');
            break;

        case "tac-request":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/tac-request.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;
		
		case "certificate-download":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/certificate-download.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

		case "payment-history":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="application/handler/payment-history.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

		case "payment-maturity":
            $page_specific_js = array(
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="application/handler/payment-maturity.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

		case "p2p-report":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/p2p-report.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

            case "fx-request":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-request.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "fx-settelment-report":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-settelment-report.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
    
                case "cn-request":
                    $page_specific_js = array(
                        '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                        '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                        '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                        '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                        '<script src="assets/vendor/select2/select2.min.js"></script>',
                        '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                        '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                        '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                        '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                        '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                        '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                        '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>'
                    );
                    $page_specific_js1 = array(
                        '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                        '<script src="assets/js/components/select2.js"></script>',
                        '<script src="assets/js/components/bootstrap-select.js"></script>',
                        '<script src="assets/js/components/icheck.js"></script>',
                        '<script src="assets/js/components/asspinner.js"></script>',
                        '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                        '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                        '<script src="application/handler/cn-request.handler.js?v=' . $jsVersion . '"></script>'
                    );
                    $page_specific_jsfunc = array('');
        
                    break;
    
            case "fx-requisition":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-requisition.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "feepayment-dashboard":
                $page_specific_js = array(
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/feepayment-dashboard.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "fx-rfq-request":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-rfq-request.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "bank-dashboard":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/bank-dashboard.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "fx-rfq-bank-offer":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-rfq-bank-offer.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "fx-request-hot":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-request-hot.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
            case "fx-rfq-process":
                $page_specific_js = array(
                    '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                    '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                    '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                    '<script src="assets/vendor/select2/select2.min.js"></script>',
                    '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                    '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                    '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                    '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                    '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                    '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
                );
                $page_specific_js1 = array(
                    '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                    '<script src="assets/js/components/select2.js"></script>',
                    '<script src="assets/js/components/bootstrap-select.js"></script>',
                    '<script src="assets/js/components/icheck.js"></script>',
                    '<script src="assets/js/components/asspinner.js"></script>',
                    '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                    '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                    '<script src="application/handler/fx-rfq-process.handler.js?v=' . $jsVersion . '"></script>'
                );
                $page_specific_jsfunc = array('');
    
                break;
    

        case "ca-interface":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/ca-interface.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "pra-interface":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/pra-interface.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "delivery-notification":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/delivery-notification.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "ici-interface":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/ici-interface.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "lc-bank":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/lc-bank.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "cnf-inputs":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/cnf-inputs.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "fx-request-primary":
            $page_specific_js = array(
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="application/handler/fx-request-primary.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;
        case "doc-notifications-bank":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/doc-notifications-bank.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;
        case "bank-document-receive":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/bank-document-receive.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;
        case "fx-settelment-pending-fn":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/icheck/icheck.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/fx-settelment-pending-fn.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');
            break;

        case "ins-policy-share":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/ins-policy-share.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "po-history":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/po-history.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "edelivery-doc-request":
            $page_specific_js = array(
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="application/handler/edelivery-doc-request.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "workflow-procedure":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/workflow-procedure.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

        case "shipment-schedule":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/shipment-schedule.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;

            case "lc-processing-docs":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/lc-processing-docs.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;


            case "bank-charge":
            $page_specific_js = array(
                '<script src="assets/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>',
                '<script src="assets/vendor/daterangepicker/moment.js"></script>',
                '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
                '<script src="assets/vendor/select2/select2.min.js"></script>',
                '<script src="assets/vendor/bootstrap-select/bootstrap-select.js"></script>',
                '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
                '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.buttons.min.js"></script>',
                '<script src="assets/vendor/datatables/dataTables.fixedColumns.min.js"></script>',
                '<script src="assets/vendor/jszip/jszip.min.js"></script>',
                '<script src="assets/vendor/datatables/buttons.html5.min.js"></script>',
            );
            $page_specific_js1 = array(
                '<script src="assets/js/components/bootstrap-datepicker.js"></script>',
                '<script src="assets/js/components/select2.js"></script>',
                '<script src="assets/js/components/bootstrap-select.js"></script>',
                '<script src="assets/js/components/icheck.js"></script>',
                '<script src="assets/js/components/asspinner.js"></script>',
                '<script src="assets/js/fupload/ajaxupload.js" type="text/javascript"></script>',
                '<script src="assets/js/fupload/jquery.ajax_upload.0.6.min.js" type="text/javascript"></script>',
                '<script src="application/handler/bank-charge.handler.js?v=' . $jsVersion . '"></script>'
            );
            $page_specific_jsfunc = array('');

            break;



        default:
            $page_specific_js = array();
            $page_specific_js1 = array();
            $page_specific_jsfunc = array();
    }

} else {

    $page_specific_js = array(
        '<script src="assets/vendor/matchheight/jquery.matchHeight-min.js"></script>',
        '<script src="assets/vendor/daterangepicker/moment.js"></script>',
        '<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>',
        '<script src="assets/vendor/intro-js/intro.js"></script>'
    );

    $page_specific_jsfunc = array("<script></script>");

    $page_specific_js1 = array(
        '<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>',
        '<script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>',
        '<script src="assets/vendor/chart-js/Chart.js?v=1"></script>',
        '<script src="assets/vendor/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>',
        '<script src="application/handler/dashboard.handler.js?v=' . $jsVersion . '"></script>'
    );
}


?>