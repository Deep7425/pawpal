@extends('layouts.admin.Masters.Master')
@section('title', 'Hospital Bed Availability Pages')
@section('content')


<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">

              <div class="container-fluid flex-grow-1 container-p-y hospital-list">

                  <div class="row mb-2 ml-1 form-top-row">

				       <div class="btn-group">
							<a class="btn btn-success" href="javascript::void(0)" data-toggle="modal" data-target="#AddModal"> <i class="fa fa-plus"></i> Add Hospital</a>
						</div>
						<div class="btn-group">
							<a class="btn btn-success" href="javascript:void();">{{$hospitals->total()}}</a>
						</div>

						<div class="btn-group head-search">
										<div class="dml-sm-2">
											{!! Form::open(array('route' => 'admin.HosBedList', 'id' => 'chnagePagination', 'method'=>'POST')) !!}
											<select class="form-control" name="page_no" onchange="chnagePagination(this.value);">
												<!--<option value="10" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '10') selected @endif @endif>10</option>-->
												<option value="25" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '25') selected @endif @endif>25</option>
												<option value="50" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '50') selected @endif @endif>50</option>
												<option value="100" @if(isset($_GET['page_no'])) @if(base64_decode($_GET['page_no']) == '100') selected @endif @endif>100</option>
											</select>
										</div>

										<div class="ml-sm-2">
											<div class="input-group custom-search-form">
												<input name="search" type="text" class="form-control capitalizee" placeholder="Search By Title" value="{{ old('search') }}"/>
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

			   <div class="layout-content">

			   <div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
									<tr style="text-align: center;">
									 <th rowspan="2">S.No.</th>
									 <th rowspan="2">Id</th>
									 <th rowspan="2">Hospital Name</th>
									 <th colspan="3">General Beds</th>
									 <th colspan="3">Oxygen Beds</th>
									 <th colspan="3">ICU Beds without Ventilator</th>
									 <th colspan="3">ICU Beds with Ventilator</th>
									 <th rowspan="2">Hospital Helpline No.</th>
									 <th rowspan="2">Nodal Officer</th>
									 <th rowspan="2">Asst. Nodal Officer</th>
									 <th rowspan="2">Status</th>
									 <th rowspan="2">Action</th>
								  </tr>
								  <tr style="text-align: center;">
									 <th style="width: 25px;"><b>T</b></th>
									 <th style="width: 25px;"><b>O</b></th>
									 <th style="width: 25px;"><b>A</b></th>
									 <th style="width: 25px;"><b>T</b></th>
									 <th style="width: 25px;"><b>O</b></th>
									 <th style="width: 25px;"><b>A</b></th>
									 <th><b>T</b></th>
									 <th><b>O</b></th>
									 <th><b>A</b></th>
									 <th><b>T</b></th>
									 <th><b>O</b></th>
									 <th><b>A</b></th>
								  </tr>
							</thead>
							<tbody>
							  <?php 
								$t_general_beds = 0;
								$t_o_gen_beds = 0;
								$t_a_gen_beds = 0;
								$t_oxygen_beds = 0;
								$t_o_oxy_beds = 0;
								$t_a_oxy_beds = 0;
								$t_icu_beds_w_v = 0;
								$t_o_icu_beds_w_v = 0;
								$t_a_icu_beds_w_v = 0;
								$t_icu_beds_v = 0;
								$t_o_icu_beds_v = 0;
								$t_a_icu_beds_v = 0;
							  ?>
							 @if(count($hospitals)>0)
							  @foreach($hospitals as $index => $raw)
								<?php 
									$t_general_beds += $raw->total_general_beds;
									$t_o_gen_beds += $raw->o_gen_beds;
									$t_a_gen_beds += $raw->a_gen_beds;
									$t_oxygen_beds += $raw->total_oxygen_beds;
									$t_o_oxy_beds += $raw->o_oxy_beds;
									$t_a_oxy_beds += $raw->a_oxy_beds;
									$t_icu_beds_w_v += $raw->total_icu_beds_w_v;
									$t_o_icu_beds_w_v += $raw->o_icu_beds_w_v;
									$t_a_icu_beds_w_v += $raw->a_icu_beds_w_v;
									$t_icu_beds_v += $raw->total_icu_beds_v;
									$t_o_icu_beds_v += $raw->o_icu_beds_v;
									$t_a_icu_beds_v += $raw->a_icu_beds_v;
								?>
							  <tr>
								 <td>{{$index+($hospitals->currentpage()-1)*$hospitals->perpage()+1}}.</td>
								 <td>{{$raw->id}}</td>
								 <td>{{$raw->name}}</td>
								 <td style="width: 25px;">{{$raw->total_general_beds}}</td>
								 <td style="width: 25px;">{{$raw->o_gen_beds}}</td>
								 <td style="color:@if($raw->a_gen_beds == 0)red;@else #12bd0f;@endif font-size:18px;width:25px;">{{$raw->a_gen_beds}}</td>
								 <td style="width: 25px;">{{$raw->total_oxygen_beds}}</td>
								 <td style="width: 25px;">{{$raw->o_oxy_beds}}</td>
								 <td style="color:@if($raw->a_oxy_beds == 0)red;@else #12bd0f;@endif font-size:18px;">{{$raw->a_oxy_beds}}</td>
								 <td>{{$raw->total_icu_beds_w_v}}</td>
								 <td>{{$raw->o_icu_beds_w_v}}</td>
								 <td style="color:@if($raw->a_icu_beds_w_v == 0)red;@else #12bd0f;@endif font-size:18px;">{{$raw->a_icu_beds_w_v}}</td>
								 <td>{{$raw->total_icu_beds_v}}</td>
								 <td>{{$raw->o_icu_beds_v}}</td>
								 <td style="color:@if($raw->a_icu_beds_v == 0)red;@else #12bd0f;@endif font-size:18px;">{{$raw->a_icu_beds_v}}</td>
								 <td>{{$raw->help_line}}</td>
								 <td style="width: 300px; display: inline-block; border-bottom: 0;">{{$raw->nodal_officer}}</td>
								 <td>{{$raw->asst_nodal_officer}}</td>
								 <td>@if($raw->status == '1') ACTIVE @else DEACTIVE @endif</td>
								 <td><button onclick="editHospital({{$raw->id}});" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>
							  </tr>
							@endforeach
							@else
								<tr><td colspan="18">No Record Found </td></tr>
							@endif
							</tbody>
						</table>
					</div>
			
			  </div>
			  <div class="page-nation text-right d-flex justify-content-end mb-2 mt-2 ">
				<ul class="pagination pagination-large">
					{{ $hospitals->appends($_GET)->links() }}
				</ul>
			</div> 
            </div>
        </div>
   </div>
</div>
           

<div class="modal fade modal-dialog1234 add-hospital" id="AddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
  <div class="modal-content ">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title">Add New Hospital</h4>
	</div>
	<div class="modal-body">
		<div class="panel panel-bd lobidrag">
			<div class="panel-heading"></div>
			<div class="panel-body form-groupTtalNew">
				{!! Form::open(array('id' => 'AddHosPage','name'=>'AddHosPage', 'enctype' => 'multipart/form-data')) !!}
					<div class="row">
					<input type="hidden" name="doctors" id="doctors_val">
					<div class="col-md-6 ">
					<div class="form-group">
						<label>Hospital Name</label>
						<input value="" type="text" name="name" class="form-control" placeholder="Enter Hospital Name" >
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-md-6">
					<div class="form-group">
						<label>Hospital Helpline Number</label>
						<input value="" type="text" name="help_line" class="form-control" placeholder="Enter Hospital Helpline Number" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Nodal Officer</label>
						<input value="" type="text" name="nodal_officer" class="form-control" placeholder="Enter Nodal Officer" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Asst. Nodal Officer</label>
						<input value="" type="text" name="asst_nodal_officer" class="form-control" placeholder="Enter Asst. Nodal Officer" />
						<span class="help-block"></span>
					</div>
					</div>
					
					<div class="col-md-6">
					<div class="form-group">
						<label>Website Url</label>
						<input value="" type="text" name="url" class="form-control" placeholder="Enter Website Url" />
						<span class="help-block"></span>
					</div>
					</div>
					<div class="col-sm-6">
					 	<div class=" form-group">
                        <label>State</label>
						 <select class="form-control state_id multiSelect" name="state">
						  <option value="">Select State</option>
							@foreach (getStateList(101) as $state)
								<option value="{{ $state->id }}">{{ $state->name }}</option>
							@endforeach
						</select>
						<span class="help-block"></span>
					 </div>
                     </div>
                     
					 <div class="col-md-6">
                     <div class=" form-group">
					  <label>City</label><br>
						<select class="form-control city_id multiSelect" name="city">
							<option value="">Select City</option>
						</select>
						<span class="help-block"></span>
					  </div>
					    </div>
					
						<label class="main">General Beds</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="" type="text" name="total_general_beds" class="form-control NumericFeild " placeholder="Enter Total General Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="" type="text" name="o_gen_beds" class="form-control NumericFeild " placeholder="Enter Occupied General Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="" type="text" name="a_gen_beds" class="form-control NumericFeild " placeholder="Enter Available General Beds" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					
						<label class="main">Oxygen Beds</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="" type="text" name="total_oxygen_beds" class="form-control NumericFeild " placeholder="Enter Total Oxygen Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="" type="text" name="o_oxy_beds" class="form-control NumericFeild " placeholder="Enter Occupied Oxygen Beds" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="" type="text" name="a_oxy_beds" class="form-control NumericFeild " placeholder="Enter Available Oxygen Beds" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
						<label class="main">ICU Beds without Ventilator</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="" type="text" name="total_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Total ICU Beds without Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="" type="text" name="o_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Occupied ICU Beds without Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="" type="text" name="a_icu_beds_w_v" class="form-control NumericFeild " placeholder="Enter Available ICU Beds without Ventilator" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					
						<label class="main">ICU Beds with Ventilator</label>
						<div class="col-md-6">
						<div class="form-group">
							<label>Total</label>
							<input value="" type="text" name="total_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Total ICU Beds with Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Occupied</label>
							<input value="" type="text" name="o_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Occupied ICU Beds with Ventilator" />
							<span class="help-block"></span>
						</div>
						</div>
						<div class="col-md-6">
						<div class="form-group">
							<label>Available</label>
							<input value="" type="text" name="a_icu_beds_v" class="form-control NumericFeild " placeholder="Enter Available ICU Beds with Ventilator" readonly/>
							<span class="help-block"></span>
						</div>
						</div>
					
					
					<div class="col-md-6">
						<div class="form-group form-group123">
							<label>doctors</label> 
							 <button type="button" class="btn btn-info btn-xs form-control edit_tags" data-toggle="modal">Doctor Add</button>
						</div>
					</div>
					
					<div class="reset-button">
					   <button type="reset" class="btn btn-warning">Reset</button>
					   <button type="submit" class="btn btn-success submit">Submit</button>
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
<div class="modal fade" id="doctorAddModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="closeDoctor">×</button>
			<h4 class="modal-title">Add Doctor</h4>
		</div>
		<div class="modal-body">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group"> 
					</div>
				</div>
				<div class="panel-body">
					<div id="addedRows">
						<div class="form-group">
							<label>Doctor Name</label>
							<input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]"/>
						</div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-default form-control" onclick="addMoreRows();">Add More Rows</button>
					</div>
					<div class="reset button">
						<button type="button" class="btn btn-primary addTags">Save</button>
						<button type="button" class="btn btn-default closeDoctor">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>   
  </div>   
</div> 
<div class="modal fade" id="hospitalEditModal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- /.content-wrapper -->
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script>
jQuery(document).on("change", ".state_id", function (e) {
  var cid = this.value;
  var $el = jQuery('.city_id');
  $el.empty();
  jQuery.ajax({
	  url: "{!! route('getCityList') !!}",
	  // type : "POST",
	  dataType : "JSON",
	  data:{'id':cid},
	  success: function(result){
	  jQuery("#updateDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
	  jQuery.each(result,function(index, element) {
		  $el.append(jQuery('<option>', {
			 value: element.id,
			 text : element.name
		  }));
	  });
  }}
  );
});
jQuery(document).ready(function(){
$(".multiSelect").select2();
});
$(document.body).on('click', '.submit', function(){
 jQuery("form[name='AddHosPage']").validate({
	rules: {
		name:   {required:true,maxlength:255},
		total_general_beds: {required:false,number: true},
		o_gen_beds: {required:false,number: true},
		a_gen_beds: {required:false,number: true},
		total_oxygen_beds: {required:false,number: true},
		o_oxy_beds: {required:false,number: true},
		a_oxy_beds: {required:false,number: true},
		total_icu_beds_w_v: {required:false,number: true},
		o_icu_beds_w_v: {required:false,number: true},
		a_icu_beds_w_v: {required:false,number: true},
		total_icu_beds_v: {required:false,number: true},
		o_icu_beds_v: {required:false,number: true},
		a_icu_beds_v: {required:false,number: true},
		// help_line: {required:true,minlength:10,maxlength:15,number: true},
		// url: "required",
		// nodal_officer: "required",
		// asst_nodal_officer: "required",
		city: "required",
		state: "required",
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
			url: "{!! route('admin.AddHosPage')!!}",
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
function editHospital(id) {
    jQuery('.loading-all').show();
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('admin.editHosBed')!!}",
    data:{'id':id,'action':'1'},
    success: function(data)
    {
      jQuery('.loading-all').hide();
      jQuery("#hospitalEditModal").html(data);
      jQuery('#hospitalEditModal').modal('show');
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

var rowCount = 1;
function addMoreRows() {
	rowCount++;
	var recRow = '<div class="form-group" id="rowCount'+rowCount+'"><input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]"><a class="close-button" href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
	jQuery('#addedRows').append(recRow);
}
function removeRow(removeNum) {
	jQuery('#rowCount'+removeNum).remove();
}

jQuery(document).on("click", ".edit_tags", function (e) {
	if($(this).attr('data')){
		var arr = JSON.parse($(this).attr('data'));
		if(arr.length > 0){
			var recRow = '';
			$("#doctorAddModal").find('#addedRows').html('');
			var i = 1;
			$.each(arr, function( key, value ) {
				recRow += '<div class="form-group" id="rowCount'+i+'"><input type="text" class="form-control tag_names" placeholder="Enter Doctor Name" name="doctors[]" value="'+value.name+'">';
				if(key > 0){ 
					recRow += '<a class="close-button" href="javascript:void(0);" onclick="removeRow('+i+');"><i class="fa fa-times" aria-hidden="true"></i></a>';
				}
				recRow += '</div>';
				i++;
			});
			$("#doctorAddModal").find('#addedRows').append(recRow);
			$("#doctorAddModal").modal('show');
		}
	}
	else{;
		$("#doctorAddModal").modal('show');
	}
});
jQuery(document).on("click", ".closeDoctor", function (e) {
	$("#doctorAddModal").modal('hide');
});
jQuery(document).on("click", ".addTags", function (e) {
	var arr = new Array();
	 $("#doctorAddModal").find(".tag_names").each(function(){
		if($(this).val()) {
			arr.push($(this).val());
		}
	 });
	if(arr.length > 0){
		$("#doctors_val").val(JSON.stringify(arr));
	}
	$("#doctorAddModal").modal('hide');
});
$("input[name=o_gen_beds]").on("change paste keyup", function() {
  // alert($(this).val()+'--'+$("input[name=total_general_beds]").val());
    var av = $("input[name=total_general_beds]").val() - $(this).val();
	$("input[name=a_gen_beds]").val(av);
 });
 $("input[name=o_oxy_beds]").on("change paste keyup", function() {
  // alert($(this).val()+'--'+$("input[name=total_general_beds]").val());
    var av = $("input[name=total_oxygen_beds]").val() - $(this).val();
	$("input[name=a_oxy_beds]").val(av);
 });
 $("input[name=o_icu_beds_w_v]").on("change paste keyup", function() {
  // alert($(this).val()+'--'+$("input[name=total_general_beds]").val());
    var av = $("input[name=total_icu_beds_w_v]").val() - $(this).val();
	$("input[name=a_icu_beds_w_v]").val(av);
 });
 $("input[name=o_icu_beds_v]").on("change paste keyup", function() {
  // alert($(this).val()+'--'+$("input[name=total_general_beds]").val());
    var av = $("input[name=total_icu_beds_v]").val() - $(this).val();
	$("input[name=a_icu_beds_v]").val(av);
 });
 
</script>
@endsection
