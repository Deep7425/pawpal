@extends('layouts.admin.Masters.Master')
@section('title', 'Default Lab')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">
                     
			           <div class="row mb-2 ml-1 form-top-row">
					   <div class="btn-group mr-1">
								<a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i> Add Lab</a>
							</div>
							<div class="btn-group">
								<a class="btn btn-success" href="javascript:void();">{{$labs->total()}}</a>
							</div>
					   
					<div class="row-right">
					{!! Form::open(array('route' => 'admin.defaultLab.index', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
                       <div class="row">
					   
								<div class="col-sm-4">
									
										<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
											<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
											<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
											<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
										</select>
									
								</div>
								<div class="col-sm-5">
									
										<div class="custom-search-form  symptom-search-box">
											<input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>

										</div>
								
								</div>
								<div class="col-sm-3">
									
										<div class="custom-search-form">
											<span class="input-group-btn">
											  <button class="btn btn-primary" type="submit">
												  SEARCH
											  </button>
											</span>

									
									</div>
								</div>
					      </div>
					   {!! Form::close() !!}
					   </div>
					</div>

			       
					<div class="layout-content ">
				

					<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:70px;">S.No.</th>
								<th>Lab Id</th>
								<th>Title</th>
								<th>Short Name</th>
								<th>Status</th>
								<th>Created By</th>
								<th style="width:100px;">Action</th>
							</tr>
						</thead>
						<tbody>
						@if($labs->count() > 0)
							@foreach($labs as $index => $raw)
								<tr>
								<td>{{$index+($labs->currentpage()-1)*$labs->perpage()+1}}.</td>
								<td>{{$raw->id}}</td>
								<td>{{$raw->title}}</td>
								<td>{{$raw->short_name}}</td>
								<td>
									<span class="label-default label @if($raw->status == '1') label-success @else label-danger @endif changeStatus"
 								    status="{{$raw->status}}" data-id="{{$raw->id}}">@if($raw->status == '1') Active @else Inactive @endif</span>
								</td>
								<td>{{@$raw->admin->name}}</td>
								<td style="width:100px;">
									<button onclick="editLab('{{$raw->id}}');" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
									<button onclick="deleteLab({{$raw->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
								</tr>
								@endforeach
							@else
								 <tr><td colspan="9">No Record Found </td></tr>
							@endif
						</tbody>
					</table>
				</div>
			
					</div>
					<div class="page-nation text-right d-flex justify-content-end mb-2 mt-2 mr-1">
				<ul class="pagination pagination-large">
					{{ $labs->appends($_GET)->links() }}
				</ul>
			</div>
             </div>
			 
        </div>
     </div>
	 <div class="modal fade" id="editLabModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div class="modal fade modal-dialog1234" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content ">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title"> Add Lab </h4>
</div>
<div class="modal-body"  id="editLab">
	<div class=" lobidrag">
		
		<div class="panel-body form-groupTtalNew">
			{!! Form::open(array('name' => 'addLab', 'method' => 'POST')) !!}
				<div class="row">
		     	<div class="col-md-6 pad-left0">
				<div class="form-group">
					<label>Title</label>
					<input value="" type="text" name="title" class="form-control" placeholder="Enter Title" >
					<span class="help-block"></span>
				</div>
				</div>

				<div class="col-md-6">
				<div class="form-group">
					<label>Short Name</label>
					<input value="" type="text" name="short_name" class="form-control" placeholder="Enter Short Name" />
					<span class="help-block"></span>
				</div>
				</div>
				</div>
				<div class="reset-button">
				   <button type="reset" class="btn btn-warning">Reset</button>
				   <button type="submit" class="btn btn-success submitLab">Submit</button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

</div>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script type="text/javascript">
function editLab(id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('admin.defLab.edit')!!}",
	data:{"_token":"{{ csrf_token() }}",'id':id},
	success: function(data) {
	  jQuery('.loading-all').hide();
	  jQuery("#editLabModal").html(data);
	  jQuery('#editLabModal').modal('show');
	},
	error: function(error) {
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
  });
}
function deleteLab(id) {
if(confirm('Are you sure want to delete?') == true){
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "JSON",
	url: "{!! route('admin.defLab.delete')!!}",
	data:{'id':id},
	success: function(data) {
	 if(data==1){
	  location.reload();
	 }
	 else {
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
jQuery(document).ready(function(){
jQuery("form[name='addLab']").validate({
	rules: {
		title: {required:true,maxlength:255},
		short_name: {required:true,maxlength:255},
	 },
	messages:{
	},
	errorPlacement: function(error, element){
		error.appendTo(element.next());
	},ignore: ":hidden",
	submitHandler: function(form) {
		$(form).find('.submit').attr('disabled',true);
		jQuery('.loading-all').show();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('admin.defLab.create')!!}",
			data:  new FormData(form),
			contentType: false,
			cache: false,
			processData:false,
			success: function(data) {
				 if(data==1){
					jQuery('.loading-all').hide();
					$(form).find('.submit').attr('disabled',false);
					location.reload();
				 }
				 else {
				  jQuery('.loading-all').hide();
				  $(form).find('.submit').attr('disabled',false);
				  alert("Oops Something Problem");
				 }
			}
		});
	}
});
});



jQuery('.changeStatus').on('click', function() {
  var type = $(this).attr('type');
  var id = $(this).attr('data-id');
	 var row = this;
  var status = $(this).attr('status');
	  if (status == 1) {
	    var text = 'Are you sure to Inactive?';
	  }
	  else {
	    var text = 'Are you sure to Active?';
	  }
  if (confirm(text)) {
    jQuery.ajax({
      url: "{!! route('defaultLab.status') !!}",
     type : "POST",
      dataType : "JSON",
      data:{'id':id, 'status':status},
      success: function(result){

			 if (status == 1) {
				    jQuery('.loading-all').hide();
						$(row).text('Inactive');
						 $(row).addClass('label-danger');
						 $(row).removeClass('label-success');
						 $(row).attr('status',0);
			 }
			 else {
			   jQuery('.loading-all').hide();
				  $(row).text('Active');
					$(row).addClass('label-success');
					$(row).removeClass('label-danger');
					$(row).attr('status',1);

			 }
    }
	}
    );
  }
  else {
    return false;
  }
});
function chnagePagination(e) {
	$("#chnagePagination").submit();
}
</script>
@endsection
