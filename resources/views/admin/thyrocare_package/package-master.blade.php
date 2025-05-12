@extends('layouts.admin.Masters.Master')
@section('title', 'Thyrocare Package Master')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
     <div class="layout-inner">
         <div class="layout-container" style = "padding-top: 0px !important;">
            
            <div class="container-fluid flex-grow-1 container-p-y">
                 <div class="row mb-2 ml-1 form-top-row">
				     <div class="btn-group mr-1">
                        <a class="btn btn-success" href="{{ route('admin.addThyrocarePackage') }}"> <i class="fa fa-plus"></i>  Add Package Group</a>
                    </div>
					<div class="btn-group">
                        <a class="btn btn-success" href="javascript:void();">{{$packages->total()}}</a>
                    </div>
				<div class="row-right">
							<div class="row">
                
							{!! Form::open(array('route' => 'admin.thyrocarePackageMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
						<div class="head-select">
						
							 <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
								 <!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
								 <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
								 <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
								 <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
							 </select>
						
					 </div>
					 <div class="head-select btn btn-defaultp excel-btn">
					 <a href="javascript:void(0);" class="btn btn-defaultp" onClick='ForExcel()' title='Excel'><img src='{{ url("/img/excel-icon.png") }}'/></a>
					</div>
					 <div class="head-search-sm">
						

							 <div class="input-group custom-search-form">
								 <input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
								 
							 </div>
						
					 </div>
					 <div class="head-search-btn">
						
							 <div class="input-group custom-search-form">
								 <span class="input-group-btn">
								   <button class="btn btn-primary" type="submit">
									   SEARCH
								   </button>
								 </span>
							 </div>
						 
						
					 </div>

					</div></div>
					{!! Form::close() !!}
				 
</div>
<div class="layout-content">

                         



				   <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($packages->count() > 0)
								@foreach($packages as $index => $package)
                                    <tr>
										<td>
											<label>{{$index+($packages->currentpage()-1)*$packages->perpage()+1}}.</label>
										</td>
										<td>
											<img src="<?php
												if(!empty($package->image)){
													echo url("/")."/public/thyrocarePackageFiles/".$package->image;
												}
												else { echo url("/")."/img/camera-icon.jpg"; }
											?>" class="img-circle" alt="User Image" height="50" width="50">
										</td>
										<td>{{$package->group_name}}</td>
										<td><span class="label-default label @if($package->status == '1') label-success @else label-danger @endif">@if($package->status == '1') Active @else Inactive @endif</span></td>
										<td>
											<button onclick="editOffersBanner({{$package->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
											<button onclick="deleteOffersBanner({{$package->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="6">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			
			      </div>
				  <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2 ml-1">
				<ul class="pagination pagination-large">
					{{ $packages->appends($_GET)->links() }}
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
	 <div class="modal fade" id="packageEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->

<!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
<!-- /.content-wrapper -->

<script>
function editOffersBanner(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editThyrocarePackage')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#packageEditModal").html(data);
      jQuery('#packageEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

function deleteOffersBanner(id) {
	if(confirm('Are you sure want to delete?') == true){
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.deleteThyrocarePackage')!!}",
		data:{'id':id},
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

function chnagePagination(e) {
	$("#chnagePagination").submit();
}

</script>
@endsection
