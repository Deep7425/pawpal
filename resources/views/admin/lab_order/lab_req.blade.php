@extends('layouts.admin.Masters.Master')
@section('title', 'Lab Request')
@section('content')

<link rel="shortcut icon" href="{{ URL::asset('css/assets/dist/img/ico/favicon.png') }}" type="image/x-icon">
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y lab-req">
            
			  <div class="layout-content">

             <div class="row mb-2 ml-1 form-top-row">
			  {!! Form::open(array('route' => 'admin.labRequests.index', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
				
								<div class="col-sm-3">
									<div class="">
										<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
											<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
											<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
											<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3">
									<input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="search by name" value="{{ old('search') }}"/>
								</div>
								
										<div class="col-sm-2 input-group custom-search-form">
											<span class="input-group-btn">
											  <button class="btn btn-primary" type="submit">
												  SEARCH
											  </button>
											</span>

										</div>
								
								
								{!! Form::close() !!}

				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:70px;">S.No.</th>
								<th>User Name</th>
								<th>Mobile</th>
								<th>Prescription</th>
								<th>Status</th>
								<th>Created At</th>
								<th style="width:115px;">Action</th>
							</tr>
						</thead>
						<tbody>
						@if($labs->count() > 0)
							@foreach($labs as $index => $raw)
								<tr>
								<td>{{$index+($labs->currentpage()-1)*$labs->perpage()+1}}.</td>
								<td>{{@$raw->user->first_name}} {{@$raw->user->last_name}}</td>
								<td>{{@$raw->mobile_no}}</td>
								<td>@if(!empty($raw->pres_id))<a href="<?=url("/")."/public/medicine-files/".@$raw->MedicinePrescriptions->prescription?>" target="_blank">Show</a>@endif</td>
								<td>
								  <button class="btn btn-default changeLabReqSts" type="button" status="{{$raw->status}}" id="{{$raw->id}}">@if($raw->status == 0) Pending @else Order Created @endif </button>
								</td>
								<td>{{$raw->created_at}}</td>
								<td>
								<!--<button class="btn btn-default createNewOrder" type="button" id="{{base64_encode(@$raw->user->id)}}">Create New Order</button>-->
								<button  style="width:115px;" title="Create Lab Order" class="btn btn-default createNewOrder" type="button" pname="{{base64_encode(@$raw->user->first_name." ".@$raw->user->last_name)}}" address="{{base64_encode(@$raw->user->address)}}" age="{{base64_encode(@$raw->user->age)}}" gender="{{base64_encode(@$raw->user->gender)}}" mobile_no="{{base64_encode(@$raw->user->mobile_no)}}"  email="{{base64_encode(@$raw->user->email)}}" id="{{base64_encode(@$raw->user->id)}}">Create Lab Order</button>
								
								<a href="javascript:void(0);" pkey="{{base64_encode(@$raw->id)}}" r_from="8" class="btn btn-info btn-sm manageSprt" title="Manage Leads"><img src='{{ url("/img/customer-care-icon.png") }}'/></a>
								</td>
								</tr>
								@endforeach
							@else
								 <tr><td colspan="9">No Record Found </td></tr>
							@endif
						</tbody>
					</table>
				</div>
			<div class="page-nation text-right d-flex justify-content-end mt-2 mb-2 mr-1">
				<ul class="pagination pagination-large">
					{{ $labs->appends($_GET)->links() }}
				</ul>
			</div>
			
			  </div>

		    </div>
			
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
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
jQuery(document).on("click", ".createNewOrder", function () {
	var id = $(this).attr('id');
	var pname = $(this).attr('pname');
	var address = $(this).attr('address');
	var age = $(this).attr('age');
	var mobile_no=$(this).attr('mobile_no');
	var gender = $(this).attr('gender');
	var email=$(this).attr('email');
	var locality=$(this).attr('locality');
	var landmark=$(this).attr('landmark');
	var pincode=$(this).attr('pincode');
	window.location.href = '{!! url("/admin/make-lab-order?id='+id+'&pname='+pname+'&age='+age+'&address='+address+'&gender='+gender+'&mobile_no='+mobile_no+'&email='+email+'") !!}';
});
jQuery(document).on("click", ".changeLabReqSts", function () {
	if(confirm('Are you sure?') == true) {
	var status = $(this).attr('status');
    var id = $(this).attr('id');
    jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('admin.changeLabReqSts')!!}",
		data:{"_token":"{{ csrf_token() }}",'id':id,'status':status},
		success: function(data){
		  jQuery('.loading-all').hide();
		  alert("Status Changed Successfully");
		  location.reload();
		},
		error: function(error){
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		}
	  });
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