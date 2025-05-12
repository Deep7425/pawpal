@extends('layouts.admin.Masters.Master')
@section('title', 'Daily Attendance Sheet')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y appoint-list">

            <div class="row mb-2 ml-1 form-top-row">
        


          {!! Form::open(['route' => 'admin.dashboardAttendanceList', 'method' => 'GET', 'id' => 'attendanceFilterForm']) !!}

          <div class="btn-group" >
          <div class="dataTables_length ml-sm-2">
                                            <select class="form-control" name="page_no" style  = "border: 1px solid #ddd;  border-radius: 4px !important;">
                                                <option value="25" {{ request('page_no') == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ request('page_no') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ request('page_no') == 100 ? 'selected' : '' }}>100</option>
                                            </select>
                                        </div>
          </div>

          <div class = "btn-group head-search">    
                                     
                                 
                                        <div class="dataTables_length ml-sm-2">
                                            <div class="input-group">
                                                <select class="form-control" id="monthPicker" name="month">
                                                    @foreach (range(1, 12) as $monthNumber)
                                                        <option value="{{ $monthNumber }}" {{ request('month', date('m')) == $monthNumber ? 'selected' : '' }}>
                                                            {{ date('F', mktime(0, 0, 0, $monthNumber, 1)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            </div>
                                 

                               
                                        <div class="dataTables_length ml-sm-2">
                                            <select class="form-control sts" name="year">
                                                <!-- Replace the range with the appropriate year range -->
                                                @for ($i = date('Y'); $i >= 2020; $i--)
                                                    <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                  
                                  
                                        <div class="dataTables_length ml-sm-2">
                                            {{--                                            <label>Submit By</label>--}}
                                            <select class="form-control sts" name="added_by">
                                                <option value="">All</option>
                                                @foreach(getAdmins() as $raw)
                                                    <option value="{{$raw->id}}" @if((app('request')->input('added_by'))!='')  @if(base64_decode(app('request')->input('added_by')) == $raw->id) selected @endif @endif >{{$raw->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                 
                                   
                                        <div class="dataTables_length ml-sm-2">
                                            <div class="input-group custom-search-form">
                                                <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">SEARCH</button>
                                                    <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
                                           
                                                </span>
                                            </div>
                                        </div>
                                        </div>
                                    
                                    {!! Form::close() !!}

           
            
         
                             </div>
                             <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Present</th>
                                <th>Week Off</th>
                                <th>Half Day</th>
                                <th>Working days</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($user_attendance)
                                @foreach($user_attendance as $index => $row)
                                    <tr>

                                    <td>{{ $index +1 }}</td>
                                        <td>{{ $row['added_by'] }}</td> <!-- Replace 'name' with the actual name field from your user model -->
                                        <td>{{ $row['week_off_count_0_attendance'] }}</td>
                                        <td>{{ $row['week_off_count_1_attendance'] }}</td>
                                        <td>{{ $row['half_day_leave_count'] }}</td>
                                        <td>
                                            {{
                                                $row['week_off_count_0_attendance'] - ($row['half_day_leave_count'] * 0.5)
                                            }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="5">No Record Found</td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right">
                        <!-- Pagination links for the records -->
{{--                        {{ $user_attendance->appends(request()->query())->links() }}--}}
                    </div>

</div>
</div>
</div>
</div>
            

  
         
      

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU&libraries=places"></script>
    <script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>

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

            $('#addAttendance').validate({
                rules: {
                    location: {required: true},
                },
                messages:{
                    // start_pic: "Start image cannot be null its required"
                },
                submitHandler: function (form) {
                    jQuery('.loading-all').show();
                    var formData = new FormData(form)

                    $.ajax({
                        type: 'POST',
                        url: "{!! route('admin.storeAttendance')!!}",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data) {
                                jQuery('.loading-all').hide();
                                location.reload();
                                alert('Attendance Added Successfully');
                            }
                        },
                        error: function (xhr, status, error) {
                            jQuery('.loading-all').hide();
                            alert('Please Upload Image');
                        }
                    });
                }
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
        $(".fromStartDate").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            //minDate: new Date(),
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate());
                $(".toDOB").datepicker("option", "minDate", dt);
            }
        });
        jQuery('.fromStartDate_cal').click(function () {
            jQuery('.fromStartDate').datepicker('show');
        });

    </script>
@endsection