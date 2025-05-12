@extends('layouts.admin.Masters.Master')
@section('title', 'HealthGennie Patient Portal Admin panel')
@section('content')

                <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <div class="header-icon">
                            <i class="pe-7s-box1"></i>
                        </div>
                        <div class="header-title">
                            <form action="#" method="get" class="sidebar-form search-box pull-right hidden-md hidden-lg hidden-sm">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button type="submit" name="search" id="search-btn" class="btn"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                            <h1>Sponsor & Suggest Doctor</h1>
                            <small>Doctor list</small>
                            <ol class="breadcrumb hidden-xs">
                                <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                                <li class="active">Doctor</li>
                            </ol>
                        </div>
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel panel-bd lobidrag">
                                    <div class="panel-heading">
                                       <!-- <div class="btn-group">
                                            <a class="btn btn-success" href="{{ route('admin.addDoctor') }}"> <i class="fa fa-plus"></i> Add Doctor
                                            </a>
                                        </div>-->
                                      <div class="btn-group">
                                        <a class="btn btn-success" href="{{ route('admin.sponsorDoc', ['action' => base64_encode('add')]) }}"> <i class="fa fa-plus"></i>  Add Sponsor</a>
                                      </div>
                  										<div class="btn-group">
                  											<a class="btn btn-success" href="javascript:void();">{{$doctors->total()}}</a>
                  										</div>

                                    </div>
                                    <div class="panel-body">
                                      {!! Form::open(array('route' => 'admin.sponsoredDoctor', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                                      <input type="hidden" name="file_type" id="file_type">
                                        <div class="row">
                                            <div class="panel-header panel-headerTop123">
                                                <div class="col-sm-2 col-xs-12">
                                                    <div class="dataTables_length">
														                               <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
                                                                <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>
                                                                <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
                                                                <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
                                                                <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                     <div class="col-sm-3 col-xs-12">
                                                        <div class="dataTables_length">
                                                          <select class="searchDropDown form-control" name="package_id">
                                                           <option value="">Select Package</option>
                                         									<option value="1" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '1') selected @endif
                                                          @endif>Package 1</option>
                                         									<option value="2" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '2') selected @endif
                                                          @endif>Package 2</option>
                                         									<option value="3" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '3') selected @endif
                                                          @endif>Package 3</option>
                                         									<option value="4" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '4') selected @endif
                                                          @endif>Package 4</option>
                                         									<option value="5" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '5') selected @endif
                                                          @endif>Package 5</option>
                                         									<option value="6" @if((app('request')->input('package_id'))!='') @if(base64_decode(app('request')->input('package_id')) == '6') selected @endif
                                                          @endif>Package 6</option>

                                                          </select>
                                                        </div>
                                                     </div>
                                                     <div class="col-sm-3 col-xs-12">
                                                        <div class="dataTables_length">
                                                          <select class="form-control" name="status">
                                                           <option value="">Select Status</option>
                                                           <option value="1" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '1') selected @endif
                                                           @endif>Active</option>
                                         									 <option value="0" @if((app('request')->input('status'))!='') @if(base64_decode(app('request')->input('status')) == '0') selected @endif
                                                           @endif>Deactive</option>
                                                          </select>
                                                        </div>
                                                     </div>
                                                     <div class="col-sm-3 col-xs-12">
                                                        <div class="dataTables_length">
                                                          <div class="input-group custom-search-form">
                                                                <input name="search" type="text" class="form-control capitalizee" placeholder="Clinic Name" value="{{ old('search') }}"/>
                                                                <span class="input-group-btn">
                                                                  <button class="btn btn-primary" type="submit">
                                                                      <span class="glyphicon glyphicon-search"></span>
                                                                  </button>
                                                              </span>
                                                          </div>
                                                     </div>
                                                   </div>
                                              </div>
                                          </div>
                                          {!! Form::close() !!}
                                          <!-- <div class="document-in">
                                             <a href="javascript:void(0);" class="btn btn-defaultp" onclick="ForExcel()" title="Excel"><img src="http://192.168.2.141/ehrLive/img/excel-icon.png"></a>
                                          </div> -->
                                          <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                  <tr>
                                                      <th>S.No.</th>
                                                      <th>Clinic Name</th>
                                                      <th>Package</th>
                                                      <th>Start Date</th>
                                                      <th>End Date</th>
                                                      <th>Status</th>
                                                      <th>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                      												@if($doctors->count() > 0)
                      													@foreach($doctors as $index => $doc)
                                                                          <tr>
                      														 <td>
                      															<label>{{$index+($doctors->currentpage()-1)*$doctors->perpage()+1}}.</label>
                      														 </td>

                      														 <td>{{@$doc->Doctors->clinic_name}}</td>
                      														 <td>{{$doc->package_id}}</td>
                                                   <td>{{ date('d-M-Y', strtotime($doc->start_date))}}</td>
                      														 <td>{{ date('d-M-Y', strtotime($doc->end_date))}}</td>

                      														 <td>
                                                     <a class="btn @if($doc->status == '1') btn-success @else btn-danger @endif changeStatus"  status="{{$doc->status}}" data-id="{{$doc->id}}" href="javascript:void();">@if($doc->status == '1') Active @else Inactive @endif</a>
                                                     <!-- <span class="label-default label @if($doc->status == '1') label-success @else label-danger @endif">@if($doc->status == '1') Active @else Inactive @endif</span> -->

                                                   </td>
                      														 <td>
                                                     <a href="{{ route('admin.sponsorDoc', ['action' => base64_encode('edit'), 'id' => base64_encode($doc->id)]) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                     <a href="{{ route('admin.sponsorDoc', ['action' => base64_encode('view'), 'id' => base64_encode($doc->id)]) }}" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                      														</td>
                      													</tr>
                      													@endforeach
                      												@else
                      													<tr><td colspan="7">No Record Found </td></tr>
                      												@endif
                      												</tbody>

											</table>
										</div>
                    <div class="page-nation text-right">
                        <ul class="pagination pagination-large">
							{{ $doctors->appends($_GET)->links() }}
                           <!-- <li class="disabled"><span>«</span></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li class="disabled"><span>...</span></li><li>
                            <li><a rel="next" href="#">Next</a></li>-->
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section> <!-- /.content -->
<div class="modal fade" id="doctorEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script type="text/javascript">

function ForExcel() {
      jQuery("#file_type").val("excel");
      jQuery("#chnagePagination").submit();
      jQuery("#file_type").val("");
    }

function chnagePagination(e) {
	$("#chnagePagination").submit();
}
$(".fromStartDate").datepicker({
   changeMonth: true,
   changeYear: true,
   dateFormat: 'yy-mm-dd',
 //minDate: new Date(),
 onSelect: function (selected) {
   var dt = new Date(selected);
   dt.setDate(dt.getDate());
 }
});

$(".toStartDate").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
  //minDate: new Date(),
  onSelect: function (selected) {
    var dt = new Date(selected);
    dt.setDate(dt.getDate());
  }
});

jQuery('.changeStatus').on('click', function() {
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
  if (confirm('Are you sure ?')) {
    jQuery.ajax({
      url: "{!! route('admin.changeSponsorStatus') !!}",
     // type : "POST",
      dataType : "JSON",
      data:{'id':id, 'status':status},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
  }


})

</script>
@endsection
