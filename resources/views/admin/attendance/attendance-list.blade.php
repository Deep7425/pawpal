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

            <div class="row form-top-row">
           
            <div class="btn-group">
            <a class="btn btn-success" href="{{ route('admin.dashboardAttendanceList') }}">Dashboard</a>
          </div>
                             
          <div class="btn-group">
  
       
                                            {!! Form::open(array('route' => 'admin.attendanceAdminList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                            <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>

                                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);" style  = "border: 1px solid #ddd;  border-radius: 4px !important;">
                                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                            </select>
                                   
          </div>
          
          <div class="btn-group head-search">

                                        <div class="dataTables_length ml-2 pad-0">
                                            {{--                 <label>Submit By</label>--}}
                                            <select class="form-control sts" id = "added_by" name="added_by">
                                                <option value="">All</option>
                                                @foreach(getAdmins() as $raw)
                                                    <option value="{{$raw->id}}" @if((app('request')->input('added_by'))!='')  @if(base64_decode(app('request')->input('added_by')) == $raw->id) selected @endif @endif >{{$raw->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="dataTables_length ml-2 pad-0">
                                            {{--                                            <label>From</label>--}}
                                            <div class="input-group date">
                                                <input type="text" autocomplete="off" class="form-control fromStartDate" name="created_at" value="@if((app('request')->input('created_at'))!=''){{ base64_decode(app('request')->input('created_at')) }}@endif"/>
                                                <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
												   </span>
                                            </div>
                                        </div>

                                      
                                   
                                  
                                        <div class="dataTables_length ml-2 ml-2 pad-0">
                                            <div class="input-group custom-search-form">
													<span class="input-group-btn">
                                                    
                                                    
                                                    <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
                                                    <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>

													 
													</span>
                                            </div>
                                            {!! Form::close() !!}
                                      
                                    </div>

                             </div>


                                    {{--<div class="btn-group" id="dateRangeFilter">

                                        <div class="dataTables_length">
                                            <div class="j1">
                                                <div class="input-group custom-search-form">
                                                    <input type="date" id="created_at" name="created_at" class="form-control date-picker-input" placeholder="Select create Date" value="{{ old('created_at') }}"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>--}}

                               

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
                                <th>Current Location</th>
                                <th>Added by</th>
                                <th>Date</th>
{{--                                <th style="text-align: center; width:60px;">Action</th>--}}
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
                                        </td>
                                        <td>{{$row->start_time?? 'N/A'}}</td>
                                        <td>
                                            @if ($row->start_pic)
                                                <a href="{{ asset('public/attendanceUserImage/' . $row->start_pic) }}" target="_blank">
                                                    <img src="{{ asset('public/attendanceUserImage/' . $row->start_pic) }}" width="50px;">
                                                </a>
                                            @else
                                                <img src="{{ asset('public/no-image.png') }}" width="50px;"> <!-- Replace 'path_to_dummy_image' with the path to your dummy image -->
                                            @endif
                                        </td>                                        <td>{{$row->end_time?? 'N/A'}}</td>
                                        <td>
                                            @if($row->end_pic)
                                                <a href="{{ URL::asset('public/attendanceUserImage/').'/'.$row->end_pic }}" target="_blank">
                                                    <img src="{{ URL::asset('public/attendanceUserImage/').'/'.$row->end_pic }}" width="50px;"></a></td>

                                        @else
                                            <img src="{{ asset('public/no-image.png') }}" width="50px;"> <!-- Replace 'path_to_dummy_image' with the path to your dummy image -->

                                        @endif
                                         <td>{{$row->location}}</td>
                                        <td class="current-location">{{$row->live_location}}</td>
                                        <td>{{ $row->admin->name??"" }}</td>
                                        <td>{{date($row->created_at)}}</td>
{{--                                        <td style="width:60px;">--}}
{{--                                            <div class="buttonSextion" style="width:70px;">--}}
{{--                                                <button onclick="editAttendance({{$row->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="9">No Record Found </td></tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                        <ul class="pagination pagination-large">
                            {{ $attendances->appends($_GET)->links() }}
                        </ul>
                    </div>

            </div>
         </div>
    </div>
</div>


        <div class="modal fade AddAttendance" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content ">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Add Attendance</h4>
                    </div>
                    <div class="modal-body">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-heading">
                                <!-- Add any heading content here if needed -->
                            </div>
                            <div class="panel-body">
{{--                                <div class="ShiftTime"><h3>Shift Time {{$user}}</h3></div>--}}
                                {!! Form::open(['id' => 'addAttendance', 'name' => 'addAttendance', 'enctype' => 'multipart/form-data']) !!}
                                <div class="row">
                                <div class="WeeekOff col-md-12">
                                    <input type="checkbox" value="1" name="weak_off" id="isWeekOff" />
                                    <label>Week Off</label>
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Shift Start</label>
                                    <input type="time" id="start_time"  name="start_time" class="form-control"  placeholder="Enter Start Time">
                                    <span class="help-block"></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Shift Start Selfie</label>
                                    <input type="file" name="start_pic" class="form-control" placeholder="Start Pic">
                                    <span class="help-block"></span>
                                </div>
                                
                                <div class="form-group col-md-4">
                                    <label>Location</label>
                                    <input type="text" name="location"  class="form-control" placeholder="Location" onclick="getLocation()">
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

    </div>
    <div class="modal fade" id="updateAttendance" role="dialog" data-backdrop="static" data-keyboard="false"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU&libraries=places"></script>
    <script src="{{ URL::asset('js/jquery-printme.js') }}"></script>
    <script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
    

<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<link href="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css') }}" rel='stylesheet'>
<script src="{{ URL::asset('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js') }}"></script>

<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script type="text/javascript">

        $('#added_by').select2();
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
        format: 'yyyy-mm-dd',
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate());
            // Your logic here based on the selected date
        }
    }).on('changeDate', function() {
        $(this).datepicker('hide');
    });
        jQuery('.fromStartDate_cal').click(function () {
            jQuery('.fromStartDate').datepicker('show');
        });

    </script>
@endsection