<?php $title="Events";?>
<!-- Page -->
<div class="page animsition">
    <div class="page-header">
      <h1 class="page-title">Event</h1>
    </div>
    <div class="page-content calendar-container padding-horizontal-30 container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="panel">
            <header class="panel-heading">
              <h3 class="panel-title">Events</h3>
            </header>
            <div class="panel-body">
              <ul class="list-group calendar-list">
                <li class="list-group-item">
                  <i class="wb-medium-point red-600 margin-right-10" aria-hidden="true"></i>Admin
                  calendar</li>
                <li class="list-group-item">
                  <i class="wb-medium-point green-600 margin-right-10" aria-hidden="true"></i>Home
                  calendar</li>
                <li class="list-group-item">
                  <i class="wb-medium-point orange-600 margin-right-10" aria-hidden="true"></i>Work
                  calendar</li>
                <li class="list-group-item">
                  <i class="wb-medium-point cyan-600 margin-right-10" aria-hidden="true"></i>Calendar
                  One</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="panel">
            <div class="padding-30" id="calendar"></div>
          </div>
          <div class="modal fade" id="addNewEvent" aria-hidden="true" aria-labelledby="addNewEvent"
          role="dialog">
            <div class="modal-dialog">
              <form class="modal-content form-horizontal"  method="post" role="form" autocomplete="off" id="form-events" name="form-events">
                <div class="modal-header">
                  <button type="button" class="close" aria-hidden="true" data-dismiss="modal" onclick="ResetForm();">×</button>
                  <h4 class="modal-title">New Event</h4>
                </div>
                <div class="modal-body">
                 <input type="hidden" id="EventId" name="EventId" value="0" />
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="title">Title:</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="title" name="title">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="description">Description:</label>
                    <div class="col-sm-10">
                      <textarea class="form-control" name="description" id="description" cols="30" rows="7"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="start">Start Date:</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <input type="text" class="form-control" id="start" name="start" data-container="#addNewEvent"
                        data-plugin="datepicker">
                        <span class="input-group-addon">
                          <i class="icon wb-calendar" aria-hidden="true"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="end">End Date:</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <input type="text" class="form-control" id="end" name="endDate" data-container="#addNewEvent"
                        data-plugin="datepicker">
                        <span class="input-group-addon">
                          <i class="icon wb-calendar" aria-hidden="true"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <hr/>
                <div class="modal-footer">
                  <div class="form-actions">
                    <label class="wc-error pull-left" id="form_error"></label>
                    <button class="btn btn-primary" type="button" id="btnEventForm">Add this event</button>
                    <a class="btn btn-sm btn-white" data-dismiss="modal" aria-label="Close" onclick="ResetForm();">Cancel</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Add Event Form -->
  <div class="site-action">
    <button type="button" class="btn-raised btn btn-success btn-floating">
      <i class="front-icon wb-plus animation-scale-up" aria-hidden="true"></i>
      <i class="back-icon wb-trash animation-scale-up" aria-hidden="true"></i>
    </button>
  </div>
  <div class="modal fade" id="addNewCalendarForm" aria-hidden="true" aria-labelledby="addNewCalendarForm"
  role="dialog" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" action="#" method="post" role="form">
        <div class="modal-header">
          <button type="button" class="close" aria-hidden="true" data-dismiss="modal">×</button>
          <h4 class="modal-title">Create New Event</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label margin-bottom-15" for="name">Calendar name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Calendar name">
          </div>
          <div class="form-group">
            <label class="control-label margin-bottom-15" for="name">Choose a color:</label>
            <ul class="color-selector">
              <li class="bg-blue-600">
                <input type="radio" checked name="colorChosen" id="colorChosen2">
                <label for="colorChosen2"></label>
              </li>
              <li class="bg-green-600">
                <input type="radio" name="colorChosen" id="colorChosen3">
                <label for="colorChosen3"></label>
              </li>
              <li class="bg-cyan-600">
                <input type="radio" name="colorChosen" id="colorChosen4">
                <label for="colorChosen4"></label>
              </li>
              <li class="bg-orange-600">
                <input type="radio" name="colorChosen" id="colorChosen5">
                <label for="colorChosen5"></label>
              </li>
              <li class="bg-red-600">
                <input type="radio" name="colorChosen" id="colorChosen6">
                <label for="colorChosen6"></label>
              </li>
              <li class="bg-blue-grey-600">
                <input type="radio" name="colorChosen" id="colorChosen7">
                <label for="colorChosen7"></label>
              </li>
              <li class="bg-purple-600">
                <input type="radio" name="colorChosen" id="colorChosen8">
                <label for="colorChosen8"></label>
              </li>
            </ul>
          </div>
          <div class="form-group">
            <label class="control-label margin-bottom-15" for="name">Choice people to your project:</label>
            <select multiple="multiple" data-plugin="jquery-selective"></select>
          </div>
        </div>
        <div class="modal-footer">
          <div class="form-actions">
            <button class="btn btn-primary" data-dismiss="modal" type="button">Create</button>
            <a class="btn btn-sm btn-white" data-dismiss="modal" href="javascript:void(0)">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>

<!-- End Page -->