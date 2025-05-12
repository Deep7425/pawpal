@extends('layouts.admin.Masters.Master')
@section('title', 'Contact list')
@section('content')
    <!-- Content Wrapper. Contains page content -->

<div class="layout-wrapper layout-2">
     <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                <div class="container-fluid flex-grow-1 container-p-y">

				<div class="row mb-2">
					<div class="col-sm-3">
					<div class="btn-group">
                            <a class="btn btn-success" href="javascript:void();">{{$contacts->total()}}</a>
                    </div>
					</div>
				</div>
				
			<div class="layout-content card">

				<div class="row mb-2 mt-2 ml-1">
                  
                                  <div class="col-sm-2">
                                        <div class="dataTables_length">
											<label>Paginate By</label>
											{!! Form::open(array('route' => 'admin.contactQuery', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

												<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
													<!-- <option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option> -->
													<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
													<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
													<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
													<option value="300" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '300') selected @endif @endif>300</option>
												</select>
                                       </div>
                                 </div>

								 
				<div class="col-sm-3">
					<div class="dataTables_length">
                        <label>Intrested in</label>
                        <select name="interest_in" class="form-control">
                          	<option selected="selected" value="">Please Select</option>

                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Create free profile on Healgennie.com') selected @endif
                              @endif>Create free profile on Healgennie.com</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Free trial for software to manage clinic') selected @endif
                              @endif>Free trial for software to manage clinic</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Software to manage my hospital(s) and Clinic') selected @endif
                              @endif>Software to manage my hospital(s) and Clinic</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Advertising my clinic/hospital on Healgennie.com') selected @endif
                              @endif>Advertising my clinic/hospital on Healgennie.com</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Channel partnerships for clinic management software sales') selected @endif
                              @endif>Channel partnerships for clinic management software sales</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Support for an existing product/subscription') selected @endif
                              @endif>Support for an existing product/subscription</option>
                              <option @if((app('request')->input('interest_in'))!='') @if(base64_decode(app('request')->input('interest_in')) == 'Career opportunities') selected @endif
                              @endif>Career opportunities</option>
                        </select>
					</div>
				</div>
				<div class="col-sm-3">
						<div class="dataTables_length">
							<label>Name </label>
							<input name="search" type="text" class="form-control capitalizee" placeholder="Name" value=" @if(isset($_GET['search'])) {{base64_decode($_GET['search'])}}  @endif">
						</div>
				</div>

				<div class="col-sm-1">
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
										 <th>Intrested In</th>
										 <th>Name</th>
										 <th>Mobile</th>
										 <th>E-Mail</th>
										 <th>Subject</th>
										<!-- <th>Messages</th>-->
                     <!-- <th>Status</th> -->
                     <th style="width:90px;">Created Date</th>
										 <th style="width: 82px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								@if($contacts->count() > 0)
								@foreach($contacts as $index => $element)
                                    <tr>
										<td>
											<label>{{$index+($contacts->currentpage()-1)*$contacts->perpage()+1}}.</label>
										</td>
										<td>{{@$element->interest_in}} </td>
                    <td>{{$element->name}}</td>
                    <td>{{$element->mobile}}</td>
                    <td>{{$element->email}}</td>
                    <td>{{$element->subject}}</td>
                    <!--<td>{{$element->message}}</td>-->
										<!-- <td><span class="label-default label @if($element->status == '1') label-success @else label-danger @endif">@if($element->status == '1') Active @else Cancel @endif</span></td> -->
                      <td>{{date('d M Y', strtotime($element->created_at))}} </td>
                    <td>
                    	<div style="width: 130px;">
                     <a href="javascript:void();" class="btn btn-info btn-sm" onclick="viewFeedback({{$element->id}},1);"><i class="fa fa-eye" aria-hidden="true"></i></a>
                      <a href="javascript:void();" class="btn btn-danger btn-sm RemoveDati" onclick="viewFeedback({{$element->id}},2);" title="Remove"><i class="fa fa-trash" aria-hidden="true"></i></a>
					  <a href="javascript:void(0);" pkey="{{base64_encode(@$element->id)}}" r_from="4" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/></a>
                    </div>
                    </td>

									</tr>
								@endforeach
								@else
									<tr><td colspan="8">No Record Found </td></tr>
								@endif
								</tbody>
							</table>
						</div>
			<div class="page-nation text-right d-flex justify-content-end mt-2 mb-2">
				<ul class="pagination pagination-large">
					{{ $contacts->appends($_GET)->links() }}
				</ul>
			</div>
			</div>
			</div>
		</div>
	</div>




<script>
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
function viewFeedback(id, type) {
	if(type == '2'){
		if(confirm('Are you sure want to delete?') == true){
			jQuery('.loading-all').show();
			jQuery.ajax({
			type: "POST",
			dataType : "HTML",
			url: "{!! route('admin.viewContact')!!}",
			data:{'id':id,'action':type},
			success: function(data)
			{
			  jQuery('.loading-all').hide();
			  if (type == 2) {
				location.reload();
			  }
			  else {
				jQuery("#viewModal").html(data);
				jQuery('#viewModal').modal('show');
			  }
			},
			error: function(error)
			{
				jQuery('.loading-all').hide();
				alert("Oops Something goes Wrong.");
			}
		  });
		}
	}
	else{
		jQuery('.loading-all').show();
			jQuery.ajax({
			type: "POST",
			dataType : "HTML",
			url: "{!! route('admin.viewContact')!!}",
			data:{'id':id,'action':type},
			success: function(data)
			{
			  jQuery('.loading-all').hide();
			  if (type == 2) {
				location.reload();
			  }
			  else {
				jQuery("#viewModal").html(data);
				jQuery('#viewModal').modal('show');
			  }
			},
			error: function(error)
			{
				jQuery('.loading-all').hide();
				alert("Oops Something goes Wrong.");
			}
		  });
	}
    
}


</script>
@endsection
