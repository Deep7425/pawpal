@extends('layouts.admin.Masters.Master')
@section('title', 'Lab Companies')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">
            <div class="container-fluid flex-grow-1 container-p-y">

                <div class="row mb-2 ml-1 form-top-row">
				    <div class="btn-group">
						<a class="btn btn-success" href="" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i> Add New Company</a>
				    </div>

					<div class="row-right">
					<form class="" action="{{route('lab.company')}}" method="post">
					@csrf
     				 <div class="row-right-head">
						<div class="head-search left">
							<div class="custom-search-form symptom-search-box">
								<input name="search" type="search" class="col-sm-5 form-control capitalizee" placeholder="Search By Title" />
							</div>
						</div>

						<div class="head-search-btn mar-l0">
						<div class=" custom-search-form">
							<span class="input-group-btn">
								<button class="btn btn-primary" type="submit">
									SEARCH
								</button>
							</span>
						</div>
					</div>
				</div>
					</form>

					</div>
                 </div>

			    <div class="layout-content">
				
				<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th style="width:70px;">S.No.</th>
							<th>Company Name</th>
								<th>Discounted </th>
								<th>desiccation </th>
								<th>Start Time </th>
								<th>End Time </th>
								<th> Slot Duration </th>

								<th>Logo</th>
								<th>Status</th>
								<th>Ceated By</th>
								<th style="width:115px;">Action</th>
							</tr>
						</thead>
						<tbody>

                             @foreach($labs as $key =>$data)
								<tr>

									<td>{{$key+1}}</td>
									<td>{{$data->title}}</td>
									<td>{{$data->discount}}</td>
									<td>{{$data->desc}} </td>
									<td>{{$data->start_time}} </td>
									<td>{{$data->end_time}} </td>
									<td>{{$data->slot_duration}} </td>
									<td><img src="{{url("/")}}/public/others/company_logos/{{$data->icon}}" height="50px" width="50px"/ style="background-size: cover;"> </td>
									<td><span class="label-default label @if($data->status == '1') label-success @else label-danger @endif changeStatus"
											status="{{$data->status}}" data-id="{{$data->id}}">@if($data->status == '1') Active @else Inactive @endif</span> </td>

                        <td>{{@$data->admin->name}}</td>
								<td style="width:115px;">
									<button onclick="editLab('{{$data->id}}');" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button onclick="deleteLab({{$data->id}});" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete "><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
								</tr>
                          	@endforeach
						</tbody>
					</table>
				</div>
				
				<div class="page-nation text-right">
					<ul class="pagination pagination-large">

					</ul>
				</div>
				
			   </div> 
		   </div>
      </div>
  </div>

  <div class="modal fade" id="editLabModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<div class="modal fade modal-dialog1234" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
<div class="modal-content ">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Add Lab Company</h4>
</div>
<div class="modal-body thyrocare-package">
	
		<div class="panel-heading"></div>
		<div class="panel-body form-groupTtalNew">
			{!! Form::open(array('name' => 'addLab', 'method' => 'POST')) !!}
				<div class="row">	
			<div class="col-md-6 pad-left0">
				<div class="form-group">
					<label>Company Name</label>
			<input  name="title"  type="text" class="form-control" placeholder="Enter Title"/>
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group labDropDown">
					<label>Discount</label>
				<input  name="discount"  type="text" class="form-control" placeholder="Enter Discount"/>
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Desiccation</label>
					<input value="" name="desc"  type="text" class="form-control" placeholder="Enter desiccation"/>
					<span class="help-block"></span>
					<div class="suggesstion-box" style="display:none;"></div>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Start Time</label>
					<input type="time" name="start_time" class="form-control" placeholder="Enter Start Time" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>End Time</label>
					<input  type="time" name="end_time" class="form-control" placeholder="Enter  End Time" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Slot Duration</label>
					<input  type="number" name="slot_duration" class="form-control" placeholder="Enter  Slot Duration" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="col-md-6">
				<div class="form-group">
					<label>Company Logo</label>
					<input type="file" name="icon" class="form-control" />
					<span class="help-block"></span>
				</div>
				</div>
				<div class="reset-button">
				   <button type="reset" class="btn btn-warning">Reset</button>
				   <button type="submit" class="btn btn-success submitLab">Submit</button>
				</div></div>
			{!! Form::close() !!}
		</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>


<script src="{{ URL::asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script> 
<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
setValue();
});
function setValue(){
	$('#exampleSelect1').multiselect({
		includeSelectAllOption: true,
		enableFiltering: true,
		enableCaseInsensitiveFiltering: true,
	});
}
function editLab(id) {
	jQuery('.loading-all').show();
	jQuery.ajax({
	type: "POST",
	dataType : "HTML",
	url: "{!! route('admin.LabCompany.edit')!!}",
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
	url: "{!! route('admin.LabCompany.delete')!!}",
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
		title: {required:true},
		price: {required:true,number:true},
		discount_price: {required:true,number:true},
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
			url: "{!! route('admin.addCompany')!!}",
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
      url: "{!! route('company.status.update') !!}",
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
jQuery(document).on("change", ".company_id", function () {
	var company_id = $(this).val();
	console.log(company_id);
	getLabsByCompany(company_id);
});
function getLabsByCompany(company_id) {
// jQuery(document).on("keyup paste", ".labSearch", function () {
// if(clinicSearchRequest) {
	// clinicSearchRequest.abort();
// }
// var currSearch = this;
var company_id = jQuery(".company_id").val();
// var searchText = $(this).find('.bs-searchbox .input-block-level').val()
// if(searchText.length >= 2) {
  clinicSearchRequest = jQuery.ajax({
  type: "POST",
  url: "{!! route('getLabByCompany') !!}",
  data: {'company_id':company_id},
  beforeSend: function(){
	// jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
  },
  success: function(response){
	  var liToAppend = "";
		if(response.length > 0){
		  // liToAppend += '<option value="" class="dataLabList" >Select</option>';
		  jQuery.each(response,function(k,v) {
			 var title = null;
			 var short_name = null;
			 if(v.default_labs.title){
				title = v.default_labs.title;
			 }
			 if(v.default_labs.short_name){
				short_name = v.default_labs.short_name;
			 }
			liToAppend += '<option value="'+v.id+'" class="dataLabList">'+title+' '+short_name+'</option>';
		  });
		}else{
			liToAppend += '<option value="0">'+jQuery(currSearch).val()+'Lab Not Found.</option>';
	  }
	  $(".labDropDown").find(".selectpicker:first").html('');
	  $(".labDropDown").find(".selectpicker:first").html(liToAppend);
	  $("#exampleSelect1").multiselect('destroy');
	  setValue();
	  // $(".labDropDown").find(".selectpicker:first").selectpicker('refresh');
	  // jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
	  // jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
  }
  });
// }
// else{
// jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
// jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
// }
}
// jQuery(document).on("click", ".dataLabList", function () {
// $('input[name="lab_id"]').val(jQuery(this).attr('value'));
// jQuery(this).closest(".form-group").find(".labSearch").val(jQuery(this).find('.txt').text());
// jQuery(this).closest(".form-group").find(".labSearch").attr('readonly',true);
// jQuery(this).closest(".suggesstion-box").hide();
// jQuery(this).closest(".suggesstion-box ul").remove();
// });
</script>

@endsection
