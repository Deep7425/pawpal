@extends('layouts.admin.Masters.Master')
@section('title', 'Labs Data Collection')
@section('content')

<div class="layout-wrapper layout-2">
      
	
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
                
		@if(Session::has('message'))
			 <div class="alert alert-info">{{ Session::get('message') }}</div>
		 @endif
            
            <div class="container-fluid flex-grow-1 container-p-y">

				   <div class = "row mb-2 ml-1 form-top-row">
                        <div class="btn-group">
								<a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i> Add Lab Collection</a>
							</div>
							<div class="btn-group">
								<a class="btn btn-success" href="javascript:void();">{{$labs->total()}}</a>
							</div>

							<div class="row-right">

							{!! Form::open(array('route' => 'admin.labCollection.index', 'id' => 'chnagePagination', 'method'=>'POST')) !!}

<div class="row-right-head">
   
			  <div class="head-select">
				  
					  <select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
						  <option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
						  <option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
						  <option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
					  </select>
				
			  </div>
			  <div class="head-search-sm">
				 
					  <div class="custom-search-form symptom-search-box">
						  <input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>

					  </div>
				  
			  </div>
			  <div class="head-search-btn">
				 
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
								<th>Lab Name</th>
								<th>Company Name</th>
								<th>Method</th>
								<th>Instruction</th>
								<th>Information</th>
								<th>Cost</th>
								<th>Offer Rate</th>
								<th>Status</th>
								<th>Created By</th>
								<th style="width:105px;">Action</th>
							</tr>
						</thead>
						<tbody>
						@if($labs->count() > 0)
							@foreach($labs as $index => $raw)
								<tr>
								<td>{{$index+($labs->currentpage()-1)*$labs->perpage()+1}}.</td>
								<td>{{@$raw->DefaultLabs->title}}</td>
								<td>{{@$raw->LabCompany->title}}</td>
								<td>{{$raw->method}}</td>
								<td>{{$raw->instruction}}</td>
								<td>{{$raw->information}}</td>
								<td>{{$raw->cost}}</td>
								<td>{{$raw->offer_rate}}</td>
								<td>
									<span class="label-default label @if($raw->status == '1') label-success @else label-danger @endif changeStatus"
 								    status="{{$raw->status}}" data-id="{{$raw->id}}">@if($raw->status == '1') Active @else Inactive @endif</span>
								</td>
								<td>{{@$raw->admin->name}}</td>
								<td style="width:105px;">
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
			   <div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 ml-1">
				<ul class="pagination pagination-large">
					{{ $labs->appends($_GET)->links() }}
				</ul>
			</div>
	</div>
	</div>
	</div>
	</div>
	</div>


<div class="modal fade modal-dialog1234" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content ">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Add Lab Collection</h4>
</div>
<div class="modal-body thyrocare-package">
	<div class="panel panel-bd lobidrag">
		
		<div class="panel-body form-groupTtalNew" style="padding-top:40px;">
			{!! Form::open(array('name' => 'addLab', 'method' => 'POST')) !!}
			<div class="row">	
			<div class="col-md-6 pad-left0">
				<div class="form-group">
					<label>Company Name</label>
					<select name="company_id" class="form-control">
						<option value="">Select Company</option>
						@forelse(getLabCompanies() as $raw)
						<option value="{{$raw->id}}">{{$raw->title}}</option>
						@empty
						@endforelse
					</select>
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Lab Name</label>
					<input type="hidden" name="lab_id" class="form-control"/>
					<input value="" name="lab_name"  type="text" class="form-control labSearch" placeholder="Enter Lab Name"/>
					<span class="help-block"></span>
					<div class="suggesstion-box" style="display:none;"></div>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Method</label>
					<input value="" type="text" name="method" class="form-control" placeholder="Enter Method" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Instruction</label>
					<input value="" type="text" name="instruction" class="form-control" placeholder="Enter instruction" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Information</label>
					<textarea type="text" name="information" class="form-control" placeholder="Enter Information"></textarea>
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Cost</label>
					<input value="" type="text" name="cost" class="form-control" placeholder="Enter Cost" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Offer Rate</label>
					<input value="" type="text" name="offer_rate" class="form-control" placeholder="Enter Offer Rate" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Reporting</label>
					<input value="" type="text" name="reporting" class="form-control" placeholder="Enter Reporting" />
					<span class="help-block"></span>
				</div>
				</div>

			<div class="col-md-6">
			<div class="form-group labDropDown">
				<label>Sub Labs</label>
				<select name="sub_lab_id[]" id="exampleSelect21" data-show-subtext="true" data-live-search="true" class="form-control" multiple>
					<option value="">Select Company</option>
					@foreach($defaultLab as $data)
					<option value="{{$data->id}}">{{$data->title}}</option>
				@endforeach
				</select>
			</div>
		</div>


				<div class="reset-button">
				   <button type="reset" class="btn btn-warning">Reset</button>
				   <button type="submit" class="btn btn-success submitLab">Submit</button>
				</div></div>
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
<div class="modal fade" id="editLabModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">
// function setValue(){

// }
function editLab(id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('admin.labCollection.edit')!!}",
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
	url: "{!! route('admin.labCollection.delete')!!}",
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
	$('#exampleSelect21').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
jQuery("form[name='addLab']").validate({
	rules: {
		company_id: {required:true},
		lab_name: {required:true},
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
			url: "{!! route('admin.labCollection.create')!!}",
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
      url: "{!! route('admin.labCollection.status') !!}",
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
var clinicSearchRequest;
jQuery(document).on("keyup paste", ".labSearch", function () {
if(clinicSearchRequest) {
	clinicSearchRequest.abort();
}
var currSearch = this;
if(jQuery(this).val().length >= 2) {
  clinicSearchRequest = jQuery.ajax({
  type: "POST",
  url: "{!! route('getLabs') !!}",
  data: {'searchText':jQuery(this).val()},
  beforeSend: function(){
	jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
  },
  success: function(data){
  console.log(data);
	  var liToAppend = "";
		if(data.length > 0){
		  jQuery.each(data,function(k,v) {
			 var title = null;
			 var short_name = null;
			 if(v.title){
				title = v.title;
			 }
			 if(v.short_name){
				short_name = v.short_name;
			 }
			liToAppend += '<li value="'+v.id+'" title="'+title+'" short_name="'+short_name+'" class="dataLabList"><div class="detail-clinic"><span class="txt">'+title+'</span></div></li>';
		  });
		}else{
			liToAppend += '<li value="0">"'+jQuery(currSearch).val()+'" Lab Not Found.</li>';
	  }
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
  }
  });
}
else{
	jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
	 jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
}
});

jQuery(document).on("click", ".dataLabList", function () {
$('input[name="lab_id"]').val(jQuery(this).attr('value'));
jQuery(this).closest(".form-group").find(".labSearch").val(jQuery(this).find('.txt').text());
// jQuery(this).closest(".form-group").find(".labSearch").attr('readonly',true);

jQuery(this).closest(".suggesstion-box").hide();
jQuery(this).closest(".suggesstion-box ul").remove();
});
</script>
@endsection
