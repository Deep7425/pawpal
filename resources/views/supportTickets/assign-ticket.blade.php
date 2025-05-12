@extends('layouts.admin.Masters.Master')
@section('title', 'Assign Ticket List')
@section('content')
    <!-- Datepicker CSS start-->
    <!-- <link href="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css') }}" rel='stylesheet'> -->
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
    <body>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <div class="layout-container  appointment-master" style = "padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y">
            <h3>Assign Tickets</h3>
            <div class="layout-content card appointment-master">
            
            <form class="assign-ticket-list" method="GET" action="{{ route('admin.assignTicketList') }}">
                <div class="row mb-2 mt-2 ml-1 mr-1 ">
                  
                        <div class="col-sm-3">
                          
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Select</option>
                                    <option value="{{ \App\Constants\AppConstants::TICKET_STATUS_PENDING }}" @if(request('status') == \App\Constants\AppConstants::TICKET_STATUS_PENDING) selected @endif>Pending</option>
                                    <option value="{{ \App\Constants\AppConstants::TICKET_STATUS_INPROGRESS }}" @if(request('status') == \App\Constants\AppConstants::TICKET_STATUS_INPROGRESS) selected @endif>In Progress</option>
                                    <option value="{{ \App\Constants\AppConstants::TICKET_STATUS_COMPLETE }}" @if(request('status') == \App\Constants\AppConstants::TICKET_STATUS_COMPLETE) selected @endif>Complete</option>
                                    <option value="{{ \App\Constants\AppConstants::TICKET_STATUS_CANCELLED }}" @if(request('status') == \App\Constants\AppConstants::TICKET_STATUS_CANCELLED) selected @endif>Cancelled</option>
                                </select>

                            
                        </div>

                        <div class="col-sm-3">
                           
                                <label>Ticket No</label>
                                <div class="input-group custom-search-form">
                                    <input name="ticket_no" type="text" class="form-control capitalizee" placeholder="Enter Ticket No" value="{{ request('ticket_no') }}">
                                </div>
                            
                        </div>
                        <div class="col-sm-3">
                            <div class="">
                                <label>Department</label>
                                <select class="form-control"  name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach(getDepartmentName() as $department)
                                        <option value="{{ $department->id }}" selected >{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
 

                        <div class="col-sm-3">
                            <div class="">
                                <span class="input-group-btn">
                                <button class="btn btn-primary form-control" type="submit">Search</button>
                            </span>
                            </div>
                        </div>
                        </div>
                    
                </div>
                </form>
              
                    
                    <div class="table-responsive ptTbl AppointmentptTbl">
                        <table class="table table-bordered table-hover">
                            <thead class="success">
                            <tr>
                                <th>S.No.</th>
                                <th>Ticket No</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>Assign By</th>
                                <th>Department</th>
                                <th>Created At</th>
                                <th style="width:40px; text-align: center;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($query->count() > 0)
                                @foreach($query as $index => $q)
                                    <tr class="tbrow">

                                        <td><label>{{$index+1}}.</label></td>
                                        <td>{{$q->ticket_no}}</td>
                                        <td>{{$q->priority}}</td>
                                        <td>{{$q->status}}</td>
                                        <td>{{$q->user->name}}</td>
                                        <td>{{$q->user->email}}</td>
                                        <td>{{$q->user->mobile_no}}</td>
                                        <td>{{$q->assignByUser->name}}</td>
                                        <td>{{$q->assignByUser->departments->name}}</td>
                                        <td>{{$q->created_at}}</td>

                                            <td>
                                                <div class="viewSubscription123">
                                                <button onclick="editTicket({{$q->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button onclick="replayMessage({{$q->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                </div>
                                            </td>

                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="19">No Record Found </td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 mr-1">
                        <ul class="pagination pagination-large">
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ticketEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div><div></div>
    <div class="modal fade" id="ticketUpdateModal" role="dialog" data-backdrop="static" data-keyboard="false"></div><div></div>
    <script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Datepicker js end-->
    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script type="text/javascript"></script>
    <script>

        function editTicket(id) {
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "GET",
                dataType: "HTML",
                url: "{!! route('admin.editTicket')!!}",
                data: {'id': id},
                success: function (data) {
                    jQuery('.loading-all').hide();
                    jQuery("#ticketEditModal").html(data);
                    jQuery('#ticketEditModal').modal('show');
                },
                error: function (error) {
                    jQuery('.loading-all').hide();
                    // location.reload()
                    alert("Oops Something goes Wrong.");
                }
            });
        }
        function replayMessage(id) {
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "GET",
                dataType: "HTML",
                url: "{!! route('admin.getReplyMessage')!!}",
                data: {'id': id},
                success: function (data) {
                    jQuery('.loading-all').hide();
                    jQuery("#ticketUpdateModal").html(data);
                    jQuery('#ticketUpdateModal').modal('show');
                },
                error: function (error) {
                    jQuery('.loading-all').hide();
                    // location.reload()
                    alert("Oops Something goes Wrong.");
                }
            });
        }



    </script>
    </body>


@endsection