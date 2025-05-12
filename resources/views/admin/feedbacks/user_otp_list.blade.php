@extends('layouts.admin.Masters.Master')
@section('title', 'User Otp List')
@section('content')

<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />



<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$otps->total()}}</a>
                    </div>
					<div class="btn-group form-head">
                    <div class="col-md-3">
                           
                            <input name="search" placeholder="Enter Mobile number" type="text" class="form-control capitalizee" 
                                value="@if(isset($_GET['search'])){{base64_decode($_GET['search'])}}@endif">
                        </div>

                        <div class="col-md-3">
                        {!! Form::open(array('route' => 'admin.userOtpList', 'id' => 'chnagePagination',
                            'method'=>'POST')) !!}
                            <div class="input-group date">
                                <input type="text" autocomplete="off" class="form-control fromStartDate"
                                    name="start_date"
                                    value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif" />
                                <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar"
                                        aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3">
                          
                            <div class="input-group date">
                                <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date"
                                    value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif" />
                                <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar"
                                        aria-hidden="true"></i> </span>
                            </div>
                        </div>

                        <div class="col-md-2">
                           
                            <div class="input-group custom-search-form">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary form-control" type="submit">
                                        SEARCH
                                    </button>
                                </span>
                            </div>
                        </div>

                
                    </div>

						<div class="btn-group head-search">

						<div class="">
                            <!-- <label>Paginate By </label> -->
                          

                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
                                <option value="25" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='25' ) selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='50' ) selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='100' ) selected @endif @endif>100</option>
                                <option value="300" @if(isset($_GET['page_no']))
                                    @if(base64_decode($_GET['page_no'])=='300' ) selected @endif @endif>300</option>
                            </select>
                        </div>
						</div>

                    
                        {!! Form::close() !!}
                </div>

                <div class="layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Mobile</th>
                                    <th>OTP</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($otps->count() > 0)
                                @foreach($otps as $index => $element)
                                <tr>
                                    <td>
                                        <label>{{$index+($otps->currentpage()-1)*$otps->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$element->mobile_no}}</td>
                                    <td>{{$element->otp}}</td>
                                    <td>{{date('d M Y g:i:s', strtotime($element->updated_at))}} </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
              
                </div>
                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                        <ul class="pagination pagination-large">
                            {{ $otps->appends($_GET)->links() }}
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


<script>
function chnagePagination(e) {
    $("#chnagePagination").submit();
}
$(".fromStartDate").datepicker({
                format: 'yyyy-mm-dd',
                onSelect: function (selected) {
                    var dt = new Date(selected);
                    dt.setDate(dt.getDate());
                    // Your logic here based on the selected date
                }
            }).on('changeDate', function () {
                $(this).datepicker('hide');
            });
jQuery('.fromStartDate_cal').click(function() {
    jQuery('.fromStartDate').datepicker('show');
});



$(".toStartDate").datepicker({
                 format: 'yyyy-mm-dd',
     onSelect: function (selected) {
         var dt = new Date(selected);
         dt.setDate(dt.getDate());
         // Your logic here based on the selected date
     }
 }).on('changeDate', function () {
     $(this).datepicker('hide');
 });
jQuery('.toStartDate_cal').click(function() {
    jQuery('.toStartDate').datepicker('show');
});
</script>
@endsection