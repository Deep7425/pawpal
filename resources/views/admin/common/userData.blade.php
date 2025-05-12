@extends('layouts.admin.Masters.Master')
@section('title', 'Users list')
@section('content')
<!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
<!-- Datepicker CSS end-->

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style="padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y data-list">


                <div class="row form-top-row">

                    <div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$enquirys->total()}}</a>
                    </div>
               

                                        <div class="btn-group file-type">
                                            <input type="file" class="file-input" name="select_file" required>
                                            <input type="submit" name="upload" class="btn btn-primary" value="Upload" />
                                        </div>
                                            

                   

                    <div class="btn-group head-search">

                        <form method="post" class="UserDataExcelImports UserDataExcelImports2"
                            enctype="multipart/form-data" action="{{ route('admin.userDataExcelImport') }}">
                            {{ csrf_field() }}
                            <div class="form-group data-list-btn">

                            <div class="btn-group TOPMENU head-small">
                        <a href="javascript:void(0);" class="btn btn-defaultp excel-btn" onClick='ForExcel()' title='Excel'><img
                                src='{{ url("/img/excel-icon.png") }}' /></a>
                    </div>

                                <div class="btn-group or">
                                    <a href="{{asset('public/user-data-sample.xls')}}"
                                        class="btn btn-success btn-icon-split" download>
                                        <span class="icon text-white-50">
                                            <i class="fa fa-download"></i>
                                        </span>
                                        <span class="text">Sample Download</span>
                                    </a>
                                </div>
                                <div class="btn-group or Addnewdata123">
                                    <a class="btn btn-primary" href="{{route('admin.newUserData')}}"><i
                                            class="fa fa-user"></i>Add new data</a>
                                </div>

                            </div>
                            <div class="update-user-div" style="display:none;">
                                <button type="button" class="regUser">Mark as Primary User</button>
                            </div>

                        </form>
						<div class="">
							  {!! Form::open(array('route' => 'admin.userDataList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
							  <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/>
							  <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                <option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
							  </select>
							</div>
                    </div>
                </div>
				<div class="layout-content card appointment-master user-data-form">
                
				    <div class="row mb-2 mt-2 ml-2">

						  <div class="col-sm-3">
                            <div class="">
                                    <label>Name </label>
                                    <input name="search" type="text" class="form-control capitalizee" placeholder="Name" value="@if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}@endif">
                            </div>
						  </div>

						  <div class="col-sm-3">
                            <div class="">
                                <label>Mobile No. </label>
                                <input name="mobile" type="text" class="form-control capitalizee" placeholder="Mobile" value="@if(isset($_GET['mobile'])){{base64_decode($_GET['mobile'])}}@endif">
                            </div>
						  </div>

							<div class="col-sm-3">
                                <div class="">
                                    <label>From</label>
                                    <div class="input-group date">
                                        <input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif"/>
                                        <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
							 </div>

						 <div class="col-sm-3">
								<div class="">
								<label>To</label>
								<div class="input-group date">
									 <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif"/>
									 <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
								</div>
								 </div>
							 </div>

						  <div class="col-sm-3">
						  <div class="">
						  <label>Data type</label>
						  <select name="data_type" class="form-control capitalizee">
							<option value="">Select Data Type</option>
							@forelse(getItemsByKey('data_type') as $raw)
							<option @if((app('request')->input('data_type'))!='') @if(base64_decode(app('request')->input('data_type')) == $raw->data_type) selected @endif @endif>{{$raw->data_type}}</option>
							@empty
							@endforelse
						  </select>
						  </div>
						  </div>

						  <div class="col-sm-3">
						  <div class="">
						  <label>Type</label>
						  <select name="type" class="form-control capitalizee">
							<option value="">Select Type</option>
							@forelse(getItemsByKey('type') as $raw)
							<option @if((app('request')->input('type'))!='') @if(base64_decode(app('request')->input('type')) == $raw->type) selected @endif @endif>{{$raw->type}}</option>
							@empty
							@endforelse
						  </select>
						  </div>
						  </div>

						  <div class="col-sm-3">
						  <div class="">
						  <label>BP/sugar</label>
						  <select name="bpst" class="form-control capitalizee">
							<option value="">Select Type</option>
							<option value="1" @if((app('request')->input('bpst'))!='') @if(base64_decode(app('request')->input('bpst')) == '1') selected @endif @endif>BP Systolic</option>
							<option value="2" @if((app('request')->input('bpst'))!='') @if(base64_decode(app('request')->input('bpst')) == '2') selected @endif @endif>BP Diastolic</option>
							<option value="3" @if((app('request')->input('bpst'))!='') @if(base64_decode(app('request')->input('bpst')) == '3') selected @endif @endif>High Sugar</option>
						  </select>
						  </div>
						  </div>

						  <div class="col-sm-2">
							<div class="">
							  <label>&nbsp </label>
							  <div class="input-group custom-search-form">
								<span class="input-group-btn">
								<button class="btn btn-primary form-control" type="submit">
								SEARCH
								</button>
								</span>
							  </div>
							</div>
						  </div>
							{!! Form::close() !!}

				    </div>
				</div>


				<div class="table-responsive plan-master">
                     
					 <table class="table table-bordered table-hover">
						 <thead>
						   <tr>
							 <th><label class="checkbox-inline" style="padding-left:0px; font-weight:600;">
							 <input type="checkbox" id="allcb" name="allcb" style="margin-right: 5px;">All</label>
							 </th>
							 <th>S.No.</th>
							 <th>Data Type</th>
							 <th>Name</th>
							 <th>Mobile</th>
							 <th>E-Mail</th>
							 <th>Title</th>
							 <th>Type</th>
							 <th>Company Name</th>
							 <th>Url</th>
							 <th>Bp Systolic</th>
							 <th>Bp Diastolic</th>
							 <th>Blood Sugar</th>
							 <th>Dob</th>
							 <th>Gender</th>
							 <th>Organization</th>
							 <th>Created Date</th>
						   </tr>
						 </thead>
						 <tbody>
						   @if($enquirys->count() > 0)
						   @foreach($enquirys as $index => $element)
						   <tr>
						   <td ><input type="checkbox" class="checkbox sub_chk" name="cb[]" value="{{$element->id}}"></td>
						   <td>
						   <label>{{$index+($enquirys->currentpage()-1)*$enquirys->perpage()+1}}.</label>
						   </td>
						   <td>{{$element->data_type}}</td>
						   <td>{{$element->name}}</td>
						   <td>{{$element->mobile}}</td>
						   <td>{{$element->email}}</td>
						   <td>{{$element->title}}</td>
						   <td>{{$element->type}}</td>
						   <td>{{$element->com_name}}</td>
						   <td>{{$element->url}}</td>
						   <td>@if($element->bp_s > 120) <code>{{$element->bp_s}}</code> @else {{$element->bp_s}} @endif</td>
						   <td>@if($element->bp_d < 80) <code>{{$element->bp_d}}</code> @else {{$element->bp_d}} @endif</td>
						   <td>@if($element->sugar >= 140) <code>{{$element->sugar}}</code> @else {{$element->sugar}} @endif</td>
						   <td>@if(!empty($element->dob)){{date('d-m-Y',$element->dob)}}@endif</td>
						   <td>{{$element->gender}}</td>
						   <td>{{@$element->OrganizationMaster->title}}</td>
						   <td>{{date('d M Y', strtotime($element->created_at))}} </td>
						   </tr>
						   @endforeach
						   @else
						   <tr><td colspan="8">No Record Found </td></tr>
						   @endif
						 </tbody>
					   </table>
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








    <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/timepicker/timepicker.js') }}"></script>




<script>
function chnagePagination(e) {
    $("#chnagePagination").submit();
}
$('#allcb').change(function() {
    $('tbody tr td input[type="checkbox"]').prop('checked', $(this).prop('checked'));
    if ($('[name="cb[]"]:checked').length > 0) {
        $(".update-user-div").show();
    } else {
        $(".update-user-div").hide();
    }
});
$('[name="cb[]"]').click(function(e) {
    if ($('[name="cb[]"]:checked').length > 0) {
        $(".update-user-div").show();
    } else {
        $(".update-user-div").hide();
    }
    if ($('[name="cb[]"]:checked').length == $('[name="cb[]"]').length || !this.checked)
        $('#allcb').prop('checked', this.checked);

});

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
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
                $(this).datepicker('hide');
            });


jQuery('.fromStartDate_cal').click(function() {
    jQuery('.fromStartDate').datepicker('show');
});

$(".toStartDate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose: true,
    todayHighlight: true
}).datepicker('setDate', new Date()).on('changeDate', function () {
    $(this).datepicker('hide');
 });

jQuery('.toStartDate_cal').click(function() {
    jQuery('.toStartDate').datepicker('show');
});

$(document).ready(function () {
        // Get the current date
        var currentDate = new Date();

        // Format the date as YYYY-MM-DD
        var formattedDate = currentDate.toISOString().split('T')[0];

        // Set the input field value
        $('#end_date').val(formattedDate);
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
            'ids': id
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

jQuery('.regUser').click(function() {
    if (confirm('Are you sure?')) {
        var cbs = $("input[name='cb[]']:checked").map(function() {
            return $(this).val();
        }).get();
        $('.loading-all').show();
        jQuery.ajax({
            url: "{!! route('admin.regPrimaryUsers') !!}",
            type: "POST",
            dataType: "JSON",
            data: {
                'id': JSON.stringify(cbs)
            },
            success: function(result) {
                if (result == 1) {
                    alert('Registerd users successfully..');
                    jQuery('.loading-all').hide();
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
    } else return false;
});



jQuery('.regUser').click(function() {
    if (confirm('Are you sure?')) {
        var cbs = $("input[name='cb[]']:checked").map(function() {
            return $(this).val();
        }).get();
        $('.loading-all').show();
        jQuery.ajax({
            url: "{!! route('admin.regPrimaryUsers') !!}",
            type: "POST",
            dataType: "JSON",
            data: {
                'id': JSON.stringify(cbs)
            },
            success: function(result) {
                if (result == 1) {
                    alert('Registerd users successfully..');
                    jQuery('.loading-all').hide();
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
    } else return false;
});


//   jQuery('.deleteUSer').click(function () {
// 	  if(confirm('Are you sure?')){
//       var cbs = $("input[name='cb[]']:checked").map(function(){return $(this).val();}).get();
// 	  console.log("============1213",cbs);

//       $('.loading-all').show();
//       jQuery.ajax({
//         url: "",
//         type : "POST",
//         dataType : "JSON",
//         data:{'id':JSON.stringify(cbs)},
//         success: function(result) {
//         if(result == 1) {
// 		  alert('Users Deleted successfully..');
// 		  location.reload();
//           jQuery('.loading-all').hide();
//         }
//         else {
//           jQuery('.loading-all').hide();
//           alert("Oops Something Problem");
//         }
//         },
//         error: function(error){
//           jQuery('.loading-all').hide();
//           if(error.status == 401 || error.status == 419){
//             //alert("Session Expired,Please logged in..");
//             location.reload();
//           }
//           else{
//             //alert("Oops Something goes Wrong.");
//           }
//         }
//       });
// 	  }
// 	  else return false;
//   });
</script>
@endsection