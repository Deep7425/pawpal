@extends('layouts.admin.Masters.Master')
@section('title', 'Spaciality Group Master')
@section('content') 
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
               <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row form-top-row">
                
                   <div class="btn-group"> 
                              <a class="btn btn-success" href="{{ route('admin.addGroupSpeciality') }}"> <i class="fa fa-plus"></i>  Add Speciality Group</a>  
                    </div>

									<div class="btn-group"> 
                                        <a class="btn btn-success" href="javascript:void();">{{$specialities->total()}}</a>
                                    </div>


                                  <div class="btn-group head-search">
                                  <div class="ml-sm-2">
											{!! Form::open(array('route' => 'admin.specialityGroupMaster', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
												</select>
                                  </div>

                                  <div class="ml-sm-2">
												<div class="input-group custom-search-form">
													<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Group Name" value="{{ old('search') }}"/>
													
												</div>
											</div>

                                            <div class=" ml-sm-2">
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
           

               <div class="layout-content " >

               <div class="table-responsive table-container">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Group Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($specialities->count() > 0)
								@foreach($specialities as $index => $blg)
                                    <tr>
										<td>
											<label>{{$index+($specialities->currentpage()-1)*$specialities->perpage()+1}}.</label>   
										</td>
										<td>{{$blg->group_name}}</td>
										<td>
											<button onclick="editGroupSpeciality({{$blg->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
										</td>
									</tr>
								@endforeach
								@else
									<tr><td colspan="6">No Record Found </td></tr>
								@endif	
								</tbody>
							</table>
						</div>

                        <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
				<ul class="pagination pagination-large">
					{{ $specialities->appends($_GET)->links() }}
				</ul>
			</div>
              </div>
      
      </div>
   </div>
   <div class="modal fade" id="spacialityEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <!-- <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
  <!-- /.content-wrapper -->

<script>
function editGroupSpeciality(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editGroupSpeciality')!!}",
    data:{'id':id},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#spacialityEditModal").html(data);
      jQuery('#spacialityEditModal').modal('show');
    },
    error: function(error)
    {
        jQuery('.loading-all').hide();
        alert("Oops Something goes Wrong.");
    }
  });
}

function chnagePagination(e) { 
	$("#chnagePagination").submit();
}

</script>
@endsection