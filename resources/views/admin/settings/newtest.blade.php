@extends('layouts.admin.Masters.Master')
@section('title', 'Subadmin list')
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
                        <h1>Subadmin</h1>
                        <small>Subadmin list</small>
                        <ol class="breadcrumb hidden-xs">
                            <li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
                            <li class="active">Subadmin</li>
                        </ol>
                    </div>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                      @if(session()->get('successMsg'))
                      <div class="alert alert-success">
                        <strong>Success!</strong> {{ session()->get('successMsg') }}
                      </div>
                      @endif
                        <div class="col-sm-12">
                            <div class="panel panel-bd lobidrag">
                                <div class="panel-heading">
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="{{ route('admin.addSubAdmin') }}"> <i class="fa fa-plus"></i>  Add Subadmin</a>
                                    </div>
									<div class="btn-group">

                                        <a class="btn btn-success" href="javascript:void();">{{$users->total()}}</a>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                     <div class="panel-header panel-headerTop123">
                                        <div class="col-sm-2">
                                            <div class="dataTables_length">
											{!! Form::open(array('route' => 'admin.subadminList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="dataTables_length">
                                               <a class="btn btn-primary buttons-csv buttons-html5 btn-sm" tabindex="0"><span>CSV</span></a>
                                            </div>
                                       </div>
										<div class="col-sm-4">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="search by name" value="{{ old('search') }}"/>

												</div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="dataTables_length">
												<div class="input-group custom-search-form">
													<span class="input-group-btn">
													  <button class="btn btn-primary" type="submit">
														  SEARCH
													  </button>
													</span>
												</div>
											{!! Form::close() !!}
											</div>
										</div>
                              </div>

                          </div>
                          <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>E-Mail</th>
                                        <th>Mobile No.</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($users->count() > 0)
								@foreach($users as $index => $user)
                  @if($user->id != 1)
                                    <tr>
										<td>
											<label>{{$index+($users->currentpage()-1)*$users->perpage()+1}}.</label>
										</td>

										<td>{{$user->name}}</td>
										<td>{{$user->email}}</td>
										<td>{{$user->mobile_no}}</td>
										<!-- <td><span class="label-default label @if($user->status == '1') label-success @else label-danger @endif">@if($user->status == '1') Active @else Inactive @endif</span></td> -->
                    <td><a class="btn @if($user->status == '0') btn-success @else btn-danger @endif changeStatus" status="{{$user->status}}" data-id="{{$user->id}}" href="javascript:void();">@if($user->status == '0') Active @else Inactive @endif</a></td>
										<td>
											<button onclick="editSubAdmin({{$user->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                      @if(Session::get('userdata')->id == 1)
                      <button onclick="changePassword({{$user->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Change Password"><i class="fa fa-key" aria-hidden="true"></i></button>
                      @endif
											<!-- <button onclick="deleteSubAdmin({{$user->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash-o" aria-hidden="true"></i></button> -->
										</td>
									</tr>
                  @endif
								@endforeach
								@else
									<tr><td colspan="6">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right">
				<ul class="pagination pagination-large">
					{{ $users->appends($_GET)->links() }}
					<!--<li class="disabled"><span>«</span></li>
					<li class="active"><span>1</span></li>
					<li><a href="#">2</a></li>
					<li class="disabled"><span>...</span></li><li>
					<li><a rel="next" href="#">Next</a></li> -->
				</ul>
			</div>
			</div>
		</div>
		</div>
	</div>
</section> <!-- /.content -->
<div class="modal fade" id="subAdminEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div> <!-- /.content-wrapper -->

<script>
function editSubAdmin(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editSubAdmin')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#subAdminEditModal").html(data);
      jQuery('#subAdminEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

function deleteSubAdmin(id) {
	if(confirm('Are you sure want to delete?') == true){
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.modifySubAdmin')!!}",
		data:{'action':'delete','id':id},
		success: function(data)
		{
		 if(data==1)
		 {
		  location.reload();
		 }
		 else
		 {
		  alert("Oops Something Problem");
		 }
		jQuery('.loading-all').hide();
		},
		error: function(error)
		{
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		}
	  });
	}
}
function changePassword(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.modifySubAdmin')!!}",
    data:{'id':id,'action':'openChangePassModal'},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#subAdminEditModal").html(data);
      jQuery('#subAdminEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

jQuery('.changeStatus').on('click', function() {
  var id = $(this).attr('data-id');
  var status = $(this).attr('status');
  if (status == 1) {
    var text = 'Are you sure to Inactive User ?';
  }
  else {
    var text = 'Are you sure to Active User ?';
  }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('admin.modifySubAdmin') !!}",
      type: "POST",
      dataType : "JSON",
      data:{'action':'statusChange', 'id':id, 'status':status},
      success: function(result){
        location.reload();

    }}
    );
  }
  else {
    return false;
  }
})
function chnagePagination(e) {
	$("#chnagePagination").submit();
}

</script>
@endsection
