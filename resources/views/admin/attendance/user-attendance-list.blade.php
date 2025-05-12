@extends('layouts.admin.Masters.Master')
@section('title', 'Daily Attendance Sheet')
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
                                <a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal" onclick="getLocation()">Today Attendance</a>
                            </div>

                 </div>
                  
                 
                 <div class="table-responsive attendence">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Present/Absent</th>
                                <th>Shift Start</th>
                                <th>Shift Start Selfie</th>
                                <th>Shift End</th>
                                <th>Shift End Selfie</th>
                                <th>Location</th>
                                <th>Date</th>
                                <th style="text-align: center; width:60px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($attendances->count() > 0)
                                @foreach($attendances as $index => $row)
                                    <tr>
                                        <td>
                                        <label>{{$index+($attendances->currentpage()-1)*$attendances->perpage()+1}}.</label>
                                        </td>
                                        <td>
                                            @if($row->weak_off == 1)
                                                Absent
                                            @else
                                                Present
                                            @endif
                                        </td>                                        <td>{{$row->start_time?? 'N/A'}}</td>
                                        <td>
                                            @if ($row->start_pic)
                                                <a href="{{ asset('public/attendanceUserImage/' . $row->start_pic) }}" target="_blank">
                                                    <img src="{{ asset('public/attendanceUserImage/' . $row->start_pic) }}" width="50px;">
                                                </a>
                                            @else
                                                <img src="{{ asset('public/no-image.png') }}" width="50px;"> <!-- Replace 'path_to_dummy_image' with the path to your dummy image -->
                                            @endif
                                        </td>
                                        <td>{{$row->end_time?? 'N/A'}}</td>
                                        <td>
                                            @if($row->end_pic)
                                                <a href="{{ URL::asset('public/attendanceUserImage/').'/'.$row->end_pic }}" target="_blank">
                                                    <img src="{{ URL::asset('public/attendanceUserImage/').'/'.$row->end_pic }}" width="50px;"></a></td>

                                            @else
                                            <img src="{{ asset('public/no-image.png') }}" width="50px;"> <!-- Replace 'path_to_dummy_image' with the path to your dummy image -->

                                        @endif
                                        <td>{{$row->location}}</td>
                                        <td>{{date($row->created_at)}}</td>
                                        <td style="width:80px;">
                                            <div class="buttonSextion" style="width:80px;">
                                                @if($row->end_time == null && $row->location != 0)
                                                    <button onclick="editAttendance({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit">End Shift</button>
                                                @else
                                                    <span class="text-success">Done</span>
                                                @endif
                                            </div>
                                        </td>


                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="9">No Record Found </td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
                            {{ $attendances->appends($_GET)->links() }}
                        </ul>
                    </div>


           </div>
        </div>
    </div>
</div>


  
    
      

        <div class="modal fade TodayAttendance" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Today Attendance</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-bd lobidrag">
                             <!-- <div class="panel-heading">
                               Add any heading content here if needed
                            </div> -->
                            <div class="panel-body">
                                <div class="WeeekOff123">
                                    <label class="EnableLocation">Location Access Required to Fill Form</label>
                                    <label class="EnableLocation">Location Access Required to Fill Form</label>                                    <label class="toggle-btn"> <input type="checkbox" disabled class="toggle-btn-radio" id="locationCheckbox"> <span class="button-slider round"></span> </label>
                                    <span class="help-block"></span>
                                </div>


                                <div class="ShiftTime"><h3>Shift Timing {{$user ?? ''}}  </h3></div>
                                {!! Form::open(['id' => 'addAttendance', 'name' => 'addAttendance', 'enctype' => 'multipart/form-data']) !!}
                                <div class="row">
                                <div class="WeeekOff col-md-12">
                                    <input type="checkbox" value="1" name="weak_off" id="isWeekOff" />
                                    <label>Week Off</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Shift Timings Start</label>
                                    <input type="time" id="start_time"  name="start_time" class="form-control startTime"  placeholder="Enter Start Time">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Start Pic</label>
                                    <input type="file" name="start_pic" class="form-control" placeholder="Start Pic">
                                    <span class="help-block"></span>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Location</label>
                                    <input type="text" name="location"  class="form-control" placeholder="Location">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="hidden" name="lat" id="lat" class="form-control" placeholder="Latitude">
                                    <input type="hidden"  name="lng" id="long" class="form-control" placeholder="Longitude">
                                    <input type="hidden" name="live_location" id="live_location" class="form-control" placeholder="Location">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
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
                        document.getElementById("locationCheckbox").checked = true; // Check the checkbox

                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });
        }
        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
            document.getElementById("locationCheckbox").checked = false; // Uncheck the checkbox
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
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var time = hours + ':' + minutes;
            if ($(this).is(':checked')) {
                $("input[name=start_time]").val("0");
                $("input[name=location]").val("0");
                // var hardcodedImageUrl = "https://www.google.com/imgres?imgurl=https%3A%2F%2Fbeforeigosolutions.com%2Fwp-content%2Fuploads%2F2021%2F12%2Fdummy-profile-pic-300x300-1.png&tbnid=lwajYrMcLkqoMM&vet=12ahUKEwizvv2OkuSEAxWJ7zgGHYPPD5MQMygAegQIARBk..i&imgrefurl=https%3A%2F%2Fbeforeigosolutions.com%2Fpascale-atkinson%2Fattachment%2Fdummy-profile-pic-300x300-1%2F&docid=-be1M6COtQJYCM&w=300&h=300&q=dummy%20profile%20image&ved=2ahUKEwizvv2OkuSEAxWJ7zgGHYPPD5MQMygAegQIARBk";

                // Set the value of the image input field to the hardcoded image URL
                // $("#start_pic").val(hardcodedImageUrl);
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
                'startTime': {
                    required: function(element) {
                        // Check if the weak_off checkbox is checked and return true if it is not checked
                        return !$("#isWeekOff").is(":checked");
                    },
                    maxlength: 100
                }
            });


            $('#addAttendance').validate({
                rules: {
                    location: {required: true},
                    // start_time: {required:true},
                },
                messages:{

                },
                submitHandler: function (form) {
                    console.log('data', form)
                    jQuery('.loading-all').show();
                    var formData = new FormData(form)
                        $.ajax({
                            type: 'POST',
                            url: "{!! route('admin.storeAttendance')!!}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                console.log(data)
                                // console.log('sdfafasd');
                                // getLocation();
                                if (data) {
                                    jQuery('.loading-all').hide();
                                    location.reload();
                                    alert('Attendance Added Successfully');
                                }
                            },
                            error: function (xhr, status, error) {
                                jQuery('.loading-all').hide();
                                console.log('xhr', xhr)
                                console.log('status', status)
                                console.log('error', error)
                                var response = JSON.parse(xhr.responseText);
                                console.log('=======', response)
                                if (response.errors && response.errors.length > 0) {
                                    alert(response.errors[0]); // Display the first error message
                                }
                                else if(response.status === 'error') {
                                    alert('Please Upload Image'); // Fallback to generic error message
                                } else {
                                    alert('Oops something went wrong')
                                }
                            }
                        });
                    // }

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
    </script>
@endsection