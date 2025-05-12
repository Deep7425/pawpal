@extends('layouts.admin.Masters.Master')
@section('title', 'Leave Request')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y appoint-list">
                @if(session()->get('successMsg'))
                <div class="alert alert-success">
                    <strong>Success!</strong> {{ session()->get('successMsg') }}
                </div>
                @endif

                <div class="row mb-2 ml-1 form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal"
                            data-target="#AddModal">Leave Request</a>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                {{--                                <th>Leave Date</th>--}}
                                <th>Remark</th>
                                <th>Leave</th>
                                <th>Status</th>
                                <th>Date</th>
                                {{--                                <th style="text-align: center; width:60px;">Action</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            @if($leaves->count() > 0)
                            @foreach($leaves as $index => $row)
                            <tr>
                                <td>
                                    <label>{{$index+($leaves->currentpage()-1)*$leaves->perpage()+1}}.</label>
                                </td>
                                {{--                                        <td>{{$row->l_date}}</td>--}}
                                <td>{{$row->remark}}</td>
                                <td>
                                    @if($row->type == 0) Half Day
                                    @elseif($row->type == 1) One Day
                                    @elseif($row->type == 2) Two Days
                                    @elseif($row->type == 3) Three Days
                                    @elseif($row->type == 4) Four Days
                                    @elseif($row->type == 5) Five Days
                                    @elseif($row->type == 6) Six Days
                                    @elseif($row->type == 7) Seven Days
                                    @elseif($row->type == 10) Ten Days
                                    @elseif($row->type == 15) Fifteen Days
                                    @endif
                                </td>
                                <td style="color: {{ $row->status == 1 ? 'green' : 'red' }}">
                                    @if($row->status == 0)
                                    Pending
                                    @elseif($row->status == 1)
                                    Approved
                                    @endif
                                </td>

                                <td>{{ $row->created_at }}</td>

                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7">No Record Found </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                    <ul class="pagination pagination-large">
                        {{ $leaves->appends($_GET)->links() }}

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade AddAttendance " id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg leave-request-edit">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Add Leave Request</h4>
            </div>
            <div class="modal-body feedback">
                <div class="">
                  
                    <div class="panel-body">
                    {!! Form::open(['id' => 'addLeaveRequest', 'name' => 'addLeaveRequest', 'enctype' => 'multipart/form-data']) !!}
    <div class="row">
<div class="form-group col-md-12">
    <label>Leave Type<i>*</i></label>
    <select name="type" id="leaveType" class="form-control">
        <option value="">Select</option>
        <option value="0">Half Day</option>
        <option value="1">One Day</option>
        <option value="2">Two Days</option>
        <option value="3">Three Days</option>
        <option value="4">Four Days</option>
        <option value="5">Five Days</option>
        <option value="6">Six Days</option>
        <option value="7">Seven Days</option>
        <option value="10">Ten Days</option>
        <option value="15">Fifteen Days</option>
    </select>
    <span class="help-block"></span>
</div>
<div class="form-group col-md-6" id="leaveDateGroup" style="display:none;">
    <label>Leave Date<i>*</i></label>
    <div class="input-group date">
        <input type="date" id="l_date" name="l_date" class="form-control fromStartDate" placeholder="Enter Leave Date">
        <span class="help-block"></span>
    </div>
</div>

<div class="form-group col-md-12">
    <label>Manager Email<i>*</i></label>
    <textarea type="text" name="manager_email" class="form-control" placeholder="Enter your manager email with comma separated like john@example.com,louis@example.com"></textarea>
    <span class="help-block"></span>
</div>
<div class="form-group col-md-12">
    <label>Remark (Mention your leave date)<i>*</i></label>
    <textarea type="text" name="remark" class="form-control" placeholder="Text here..."></textarea>
    <span class="help-block"></span>
</div>

<div class="reset-button col-sm-12">
    <button type="reset" class="btn btn-warning">Reset</button>
    <button type="submit" class="btn btn-success submit">Save</button>
</div></div>
{!! Form::close() !!}
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateAttendance" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU&libraries=places">
</script>
<script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            document.getElementById("lat").value = latitude;
            document.getElementById("long").value = longitude;

            // Reverse geocoding to get address from latitude and longitude
            var geocoder = new google.maps.Geocoder();
            var latlng = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
            geocoder.geocode({ 'location': latlng }, function(results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        document.getElementById("live_location").value = results[0].formatted_address;
                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });
        }

        window.onload = function() {
            getLocation();
        };

        window.onload = function() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var time = hours + ':' + minutes;
            document.getElementById('start_time').value = time;
        };

        // Change event listener for checkbox with id 'isWeekOff'
        $("#isWeekOff").on('change', function() {
            if ($(this).is(':checked')) {
                $("input[name=start_time]").val("0");
                $("input[name=location]").val("0");
                $("input[name=live_location]").val("0");
                $("input[name=lat]").val("0");
                $("input[name=lng]").val("0");
                $("#start_pic").val(null); // Reset file input
                $("#start_pic").prop('disabled', true); // Disable file input
            } else {
                var now = new Date();
                var hours = now.getHours().toString().padStart(2, '0');
                var minutes = now.getMinutes().toString().padStart(2, '0');
                var time = hours + ':' + minutes;

                // Fill current time into the start_time input field
                $("input[name=start_time]").val(time);
                $("input[name=location]").val("");
                $("input[name=live_location]").val("");
                $("input[name=lat]").val("");
                $("input[name=lng]").val("");
                $("#start_pic").prop('disabled', false);
            }
        });


        $(document).ready(function () {
            $.validator.addClassRules({
                'l_date': {
                    maxlength: 100
                }
            });

            $('#leaveType').change(function() {
                var leaveType = $(this).val();
                var l_date = $('#l_date');
                var validator = $("form").validate();

                if (leaveType === '0') {
                    l_date.rules("add", {
                        required: true
                    });
                    validator.settings.messages['l_date'] = "Please enter a leave date.";
                } else {
                    l_date.rules("remove", "required");
                    delete validator.settings.messages['l_date'];
                }
            });


            $('#addLeaveRequest').validate({
                rules: {
                    // l_date: {required:true},
                    type: {required:true},
                    remark:{required:true},
                    manager_email:{required:true},
                },
                messages:{
                    manager_email: "Please enter you manager email"
                },
                submitHandler: function (form) {
                    jQuery('.loading-all').show();
                    var formData = new FormData(form)

                    $.ajax({
                        type: 'POST',
                        url: "{!! route('admin.storeLeaveRequest')!!}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data) {
                                jQuery('.loading-all').hide();
                                location.reload();
                                alert('Leave Request Added Successfully');
                            }
                        },
                        error: function (xhr, status, error) {
                            jQuery('.loading-all').hide();
                            alert('Error occurred while adding the leave request.');
                        }
                    });
                }
            });
        });
        $(document).ready(function () {
            $('.status-checkbox').click(function () {
                var checkbox = $(this);
                var id = checkbox.attr('id');
                var status = checkbox.is(':checked') ? 1 : 0;

                $.ajax({
                    type: 'POST',
                    url: "{!! route('admin.leaveUpdate')!!}",
                    data: { id: id, status: status },
                    success: function (data) {
                        alert('Status updated successfully')
                        console.log('Status updated successfully');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating status:', error);
                    }
                });
            });
        });
        function editAttendance(id) {
            jQuery('.loading-all').show();
            jQuery.ajax({
                type: "GET",
                dataType : "HTML",
                url: "{!! route('admin.editAttendance')!!}",
                data:{'id':id},
                success: function(data)
                {
                    jQuery('.loading-all').hide();
                    jQuery("#updateAttendance").html(data);
                    $('#updateAttendance').modal('show');
                },
                error: function(xhr, status, error) {
                    jQuery('.loading-all').hide();
                    // Handle error
                }
            });

            // Open the modal

        }
        document.getElementById('leaveType').addEventListener('change', function() {
            var leaveType = this.value;
            var leaveDateGroup = document.getElementById('leaveDateGroup');
            var l_date = document.getElementById('l_date');

            if (leaveType === '0') {
                leaveDateGroup.style.display = 'block';
                l_date.required = true;
            } else {
                leaveDateGroup.style.display = 'none';
                l_date.required = false;
            }
        });

    </script>

@endsection