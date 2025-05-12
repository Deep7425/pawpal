@extends('layouts.admin.Masters.Master')
@section('title', 'User Leave Request')
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

                <div class = "btn-group head-search">
                                <div class="dataTables_length pad-0">
                                            {!! Form::open(array('route' => 'admin.leaveRequestAdminList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                            <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>

                                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                                <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
                                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                            </select>
                                        </div>

                                        <div class="dataTables_length pad-0 ml-sm-2">
{{--                                            <label>From</label>--}}
                                            <div class="input-group date">
                                                <input type="text" autocomplete="off" class="form-control fromStartDate" name="created_at" value="@if((app('request')->input('created_at'))!=''){{ base64_decode(app('request')->input('created_at')) }}@endif"/>
                                                <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
												   </span>
                                            </div>
                                        </div>

                                        <div class="dataTables_length pad-0 ml-sm-2">
                                        <select class="form-control sts" name="status">
                                                <option value="">Select Status</option>
                                                <option value="1" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '1') selected @endif @endif >Approve</option>
                                                <option value="2" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '2') selected @endif @endif >Rejected</option>
                                                <option value="0" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '0') selected @endif @endif >Pending</option>
                                            </select>
                                        </div>

                                        <div class="dataTables_length pad-0 ml-sm-2">
{{--                                            <label>Submit By</label>--}}
                                            <select class="form-control sts" name="added_by">
                                                <option value="">Submit By</option>
                                                @foreach(getAdmins() as $raw)
                                                    <option value="{{$raw->id}}" @if((app('request')->input('added_by'))!='')  @if(base64_decode(app('request')->input('added_by')) == $raw->id) selected @endif @endif >{{$raw->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="dataTables_length pad-0 ml-sm-2">
                                            <div class="input-group custom-search-form">
													<span class="input-group-btn">
                                                   

											 <button class="btn btn-primary" type="submit">SEARCH</button>
                                              <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
													</span>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>

                
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
                                    <th>Added by</th>
                                    <th>Date</th>
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


                                        <td>
{{--                                        @if($row->status == 1)--}}
{{--                                          <div style="background-color: lightgreen; padding: 5px;">Approved</div>--}}
{{--                                            @elseif($row->status == 2)--}}
{{--                                                <div style="background-color: red; padding: 5px; color: white;">Rejected</div>--}}
{{--                                            @else--}}
                                                <select class="form-control status-dropdown dd-wrapper" data-id="{{ $row->id }}">
                                                    <option  value="0" {{ $row->status == 0 ? 'selected' : '' }}>Pending</option>
                                                    <option value="1"{{ $row->status == 1 ? 'selected' : '' }}>Approved</option>
                                                    <option value="2" {{ $row->status == 2 ? 'selected' : '' }}>Rejected</option>
                                                </select>
{{--                                         @endif--}}
                                        </td>


                                        <td>{{ $row->admin->name??"" }}</td>

                                        <td>{{ $row->created_at }}</td>

                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="7">No Record Found </td></tr>
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

        $(document).ready(function () {

            $('#addLeaveRequest').validate({
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
            $('.status-dropdown').change(function () {
                var dropdown = $(this);
                var id = dropdown.data('id');
                var status = dropdown.val();

                $.ajax({
                    type: 'POST',
                    url: "{!! route('admin.leaveUpdate') !!}",
                    data: { id: id, status: status },
                    success: function (data) {
                        alert('Status updated successfully');
                        location.reload();
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
        jQuery('.fromStartDate_cal').click(function () {
            jQuery('.fromStartDate').datepicker('show');
        });

    </script>
@endsection