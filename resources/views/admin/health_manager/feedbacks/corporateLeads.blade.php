@extends('layouts.admin.Masters.Master')
@section('title', 'Corporate list')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<!-- Datepicker CSS start-->
<link rel="stylesheet" href="{{ URL::asset('assets/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.css') }}" />
<!-- Datepicker CSS end-->

<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">
                
               <div class="row mb-2 " >
                  <div class="col-sm-3">

                  <div class="btn-group">
                                    <a class="btn btn-success" href="javascript:void();">{{$leads->total()}}</a>
                                    </div>
                                    <div class="btn-group">
                                      <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
                                    </div>  

                  </div>

               </div>

                <div class="layout-content card">
 
                <div class="row mt-2 ml-1" >

                     <div class="col-sm-2">
                                        <div class="dataTables_length">
                                          <label>Paginate By</label>
                                          {!! Form::open(array('route' => 'admin.corporateLeads', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                          <input type="hidden" name="file_type" id="file_type" value="{{ old('file_type') }}"/> 
                                          <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                          <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                          <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                          <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                          <option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
                                          </select>
                                        </div>
                                      </div>
                      <div class="col-sm-2">
                        <div class="dataTables_length">
                          <label>From</label>
                          <div class="input-group date">
                            <input type="text" autocomplete="off" class="form-control fromStartDate" name="start_date" value="@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif"/>
                            <span class="input-group-addon fromStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i>
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="dataTables_length">
                          <label>To</label>
                          <div class="input-group date">
                            <input type="text" autocomplete="off" class="form-control toStartDate" name="end_date" value="@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif"/>
                            <span class="input-group-addon toStartDate_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
                          </div>
                        </div>
                      </div>                
                      <div class="col-sm-4">
                      <div class="dataTables_length">
                      <label>Name </label>
                      <input name="search" type="text" class="form-control capitalizee" placeholder="Name" value=" @if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}  @endif">
                      </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="dataTables_length">
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

                <div class="table-responsive">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>E-Mail</th>
							<th>Organization Name</th>
							<th>Organization Size</th>
                            <th>Created Date</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if($leads->count() > 0)
                          @foreach($leads as $index => $element)
                          <tr>
                          <td>
                          <label>{{$index+($leads->currentpage()-1)*$leads->perpage()+1}}.</label>
                          </td>
                          <td>{{$element->name}}</td>
                          <td>{{$element->mobile}}</td>
                          <td>{{$element->email}}</td>
						  <td>{{$element->org_name}}</td>
						  <td>{{$element->org_size}}</td>
                          <td>{{date('d M Y', strtotime($element->created_at))}} </td>
                          <td>
							<a href="javascript:void(0);" pkey="{{base64_encode(@$element->id)}}" r_from="7" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/></a>
                          </td>
                          </tr>
                          @endforeach
                          @else
                          <tr><td colspan="8">No Record Found </td></tr>
                          @endif
                        </tbody>
                      </table>
                    </div>  

                    <div class="page-nation text-right">
              				<ul class="pagination pagination-large">
              					{{ $leads->appends($_GET)->links() }}
              				</ul>
              			</div>
                </div>

               </div>
        </div>
    </div>
    <div class="modal md-effect-1 md-show" id="viewModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-material-datetimepicker/bootstrap-material-datetimepicker.js') }}"></script>
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
    dataType : "HTML",
    url: "{!! route('admin.viewContact')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#viewModal").html(data);
      jQuery('#viewModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
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

  $(".toStartDate").datepicker({
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
  jQuery('.toStartDate_cal').click(function () {
    jQuery('.toStartDate').datepicker('show');
  });

  function ForExcel() {
  jQuery("#file_type").val("excel");
  $("#chnagePagination").submit();
  jQuery("#file_type").val("");
}

  jQuery('.enQStatusChange').click(function () {
      var id = $(this).attr('rowId');
      var row = this;
      $('.loading-all').show();
      jQuery.ajax({
        url: "{!! route('admin.corporateStatus') !!}",
        type : "POST",
        dataType : "JSON",
        data:{'id':id},
        success: function(result) {
        if(result == 1) {
          jQuery('.loading-all').hide();
          $(row).text('Done');
        }
        else {
          jQuery('.loading-all').hide();
          alert("Oops Something Problem");
        }
        },
        error: function(error){
          jQuery('.loading-all').hide();
          if(error.status == 401 || error.status == 419){
            //alert("Session Expired,Please logged in..");
            location.reload();
          }
          else{
            //alert("Oops Something goes Wrong.");
          }
        }
      });
     
  });


</script>
@endsection
