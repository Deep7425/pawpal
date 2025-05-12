@extends('layouts.admin.Masters.Master')
@section('title', 'Enquiry list')
@section('content')
<!-- Content Wrapper. Contains page content -->

<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet"
    href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row ml-1 form-top-row">


                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$enquirys->total()}}</a>
                    </div>
                    <div class="btn-group form-head">


                        <div class="col-md-3">

                        {!! Form::open(array('route' => 'admin.enquiryQuery', 'id' => 'chnagePagination',
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
                        <div class="col-md-3">

                            <input name="search" type="text" placeholder="Enter Name Here"
                                class="form-control capitalizee"
                                value="@if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}  @endif">
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
                        <div class="btn-group">



                            <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}" />
                            <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
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
                        <div class="btn-group excel">
                            <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()'
                                title='Excel'><img src='{{ url("/img/excel-icon.png") }}' /></a>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
              
                <div class=" layout-content">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>E-Mail</th>
                                    <th>City Name</th>
                                    <th>Is Subscribed</th>
                                    <th>Tot Appt</th>
                                    <th>From</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($enquirys->count() > 0)
                                @foreach($enquirys as $index => $element)
                                <tr>
                                    <td>
                                        <label>{{$index+($enquirys->currentpage()-1)*$enquirys->perpage()+1}}.</label>
                                    </td>
                                    <td>{{$element->name}}</td>
                                    <td>{{$element->mobile}}</td>
                                    <td>{{$element->email}}</td>
                                    <td>{{$element->city}}</td>
                                    <td>@if(!empty($element->User->UsersSubscriptions)) Yes @else No @endif</td>
                                    <td>@if(!empty($element->User)) {{$element->User->tot_appointment}} @else 0 @endif
                                    </td>
                                    <td>@if($element->req_from==1) Ad @else HealthGennie @endif</td>
                                    <td>{{date('d M Y', strtotime($element->created_at))}} </td>
                                    <td>
                                        @if($element->status == 0)
                                        <a href="javascript:void();"
                                            class="btn btn-info btn-sm enQStatusChange enQStatusChange123"
                                            rowId="{{$element->id}}">Pending</a>
                                        @else
                                        <a href="javascript:void();" class="btn btn-info btn-sm">Done</a>
                                        @endif
                                        <a href="javascript:void(0);" pkey="{{base64_encode(@$element->id)}}" r_from="5"
                                            class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img
                                                class="customer-care-icon"
                                                src='{{ url("/img/customer-care-icon.png") }}' /></a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="12">No Record Found </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2">
                    <ul class="pagination pagination-large">
                        {{ $enquirys->appends($_GET)->links() }}
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal md-effect-1 md-show" id="viewModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<script src="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ URL::asset('assets/libs/moment/moment.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}">
</script>
<script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>
<script src="{{ URL::asset('assets/libs/minicolors/minicolors.js') }}"></script>


<script>
function chnagePagination(e) {
    $("#chnagePagination").submit();
}

function viewFeedback(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
        type: "POST",
        dataType: "HTML",
        url: "{!! route('admin.viewContact')!!}",
        data: {
            'id': id
        },
        success: function(data) {
            jQuery('.loading-all').hide();
            jQuery("#viewModal").html(data);
            jQuery('#viewModal').modal('show');
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            alert("Oops Something goes Wrong.");
        }
    });
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
jQuery('.fromStartDate_cal').click(function() {
    jQuery('.fromStartDate').datepicker('show');
});

$(".toStartDate").datepicker({
    format: 'yyyy-mm-dd',
    onSelect: function(selected) {
        var dt = new Date(selected);
        dt.setDate(dt.getDate());
        // Your logic here based on the selected date
    }
}).on('changeDate', function() {
    $(this).datepicker('hide');
});

jQuery('.toStartDate_cal').click(function() {
    jQuery('.toStartDate').datepicker('show');
});

function ForExcel() {
    jQuery("#file_type").val("excel");
    $("#chnagePagination").submit();
    jQuery("#file_type").val("");
}

jQuery('.enQStatusChange').click(function() {
    var id = $(this).attr('rowId');
    var row = this;
    $('.loading-all').show();
    jQuery.ajax({
        url: "{!! route('admin.enquiryStatus') !!}",
        type: "POST",
        dataType: "JSON",
        data: {
            'id': id
        },
        success: function(result) {
            if (result == 1) {
                jQuery('.loading-all').hide();
                $(row).text('Done');
            } else {
                jQuery('.loading-all').hide();
                alert("Oops Something Problem");
            }
        },
        error: function(error) {
            jQuery('.loading-all').hide();
            if (error.status == 401 || error.status == 419) {
                //alert("Session Expired,Please logged in..");
                location.reload();
            } else {
                //alert("Oops Something goes Wrong.");
            }
        }
    });

});
</script>
@endsection