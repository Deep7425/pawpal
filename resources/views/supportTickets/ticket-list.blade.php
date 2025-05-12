@extends('layouts.admin.Masters.Master')
@section('title', 'Unassigned Ticket')
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
            <div class="layout-container appointment-master" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h3>Unassign Tickets</h3>
                    <div class="row mb-2 ml-1 form-top-row">
                        <form class="user" id="FormAssignTickets" action="{{ route('AssignNow') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="ticket_id" id="ticketID" class="form-control" value="">

                        <div class="form-group row">
                               
                                    <div class="col-sm-4 Assign_Presales">
                                        <label>Select Department</label>
                                        <select class="form-control dynamic" title="Please Select Department" id="departmentSelect" name="department" required>
                                            <option value="">Select Department</option>
                                            @foreach(getDepartmentName() as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                     </div>
                                <div class="col-sm-4 mb-5 mb-sm-0">
                                    <div class="Assign_Presales">
                                        <label>Select User</label>
                                        <select class="form-control dynamic" id="assignUserSelect" title="Please Select User" name="user" required>
                                            <option value="">Select User</option>
                                        </select>
                                    </div>
                                </div>


                               

                            <div class="col-sm-2 mb-2 mb-sm-0">

                                <input type="submit" name="submit"  class="btn btn-primary" value="Assign Now">
                            </div>
                        </div>
                    </form>
                    </div>  
                
                    <div class="table-responsive ptTbl AppointmentptTbl">
                        <table class="table table-bordered table-hover">
                            <thead class="success">
                            <tr>
                                <th><input type="checkbox" class="selecAll" /></th>
                                <th>S.No.</th>
                                <th>Ticket No</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Mobile No</th>
                                <th>Created At</th>
{{--                                <th style="width:40px; text-align: center;">Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @if($query->count() > 0)
                                @foreach($query as $index => $q)
                                    <tr class="tbrow">
                                        <th><input type="checkbox" value="{{$q->id}}" class="sub_chk" /></th>

                                        <td><label>{{$index+1}}.</label></td>
                                        <td>{{$q->ticket_no}}</td>
                                        <td>{{$q->priority}}</td>
                                        <td>{{$q->status}}</td>
                                        <td>{{$q->user->name}}</td>
                                        <td>{{$q->user->email}}</td>
                                        <td>{{$q->user->mobile_no}}</td>
                                        <td>{{$q->created_at}}</td>
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
    <script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>





    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- Datepicker js end-->
    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/moment.min.js') }}"></script>
    <script type="text/javascript">
    </script>
    <script>
        $(document).ready(function() {
            $('#FormAssignTickets').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Validate the form
                if ($(this).valid()) {
                    $('.loading-all').show(); // Show loading indicator

                    // Submit the form via AJAX
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: $(this).attr('action'),
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function(data) {
                            $('.loading-all').hide(); // Hide loading indicator

                            if (data) {
                                alert("Ticket Assigned Successfully");
                                location.reload(); // Reload the page
                            } else {
                                alert("Oops! Something went fsd.");
                            }
                        },
                        error: function(xhr, status, error) {
                            var response = JSON.parse(xhr.responseText);

                            // Access the error message from the parsed response
                            var errorMessage = response.message;

                            console.log('error', errorMessage);
                            $('.loading-all').hide(); // Hide loading indicator
                            alert(errorMessage);
                        }
                    });
                }
            });

            // Initialize form validation
            $('#FormAssignTickets').validate({
                rules: {
                    ticket_id: {
                        required: true
                    }
                },
                messages: {
                    ticket_id: {
                        required: "Please select a ticket"
                    }
                }
            });
        });



        $(document).ready(function() {
            // Event listener for when a ticket is selected
            $('.sub_chk').change(function() {
                // Get the selected ticket ID
                var ticketId = $(this).val();
                console.log('id', ticketId)
                // Update the hidden input field with the selected ticket ID
                $('#ticketID').val(ticketId);
            });
        });
        $(document).ready(function() {
            $('#departmentSelect').change(function() {
                var departmentId = $(this).val();

                $.ajax({
                    url: '{{ route("fetch-users") }}',
                    type: 'POST',
                    data: { department_id: departmentId },
                    success: function(response) {
                        $('#assignUserSelect').empty();

                        // Check if response is not empty
                        if (response.length > 0) {
                            console.log('response', response)
                            // Loop through each user object in the response
                            $.each(response, function(index, user) {
                                console.log('user', user)
                                // Append an option element to the dropdown for each user
                                $('#assignUserSelect').append('<option value="' + user.id + '">' + user.name + '</option>');
                            });
                        } else {
                            // If no users are returned, display a default option
                            $('#assignUserSelect').append('<option value="">No users found</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });


        jQuery(document).ready(function(){

jQuery(document).on("click", ".selecAll", function (e) {
    var ids = [];
    if(!this.checked) {
        $('.sub_chk').prop('checked', false);
      $("#sendUserBulkSms").find("#ids").val('');
    }else{
    $('.sub_chk').prop('checked', true);
    $(".sub_chk").each(function(i){
        if(this.checked){
            ids.push(this.value)
        }
    });
    $("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
    }
});
$('.sub_chk').click(function(e) {
    var flag = 0;
    var ids = [];
    $(".sub_chk").each(function(i){
        if(this.checked){
            ids.push(this.value);
        }
        else{
            flag = 1;
        }
    });
    if(flag == 1){
        $('.selecAll').prop('checked', false);
    }
    else if(flag == 0) {
        $('.selecAll').prop('checked', true);
    }
    $("#sendUserBulkSms").find("#ids").val(JSON.stringify(ids));
    console.log(ids);
});

});


    </script>



    </body>


@endsection
