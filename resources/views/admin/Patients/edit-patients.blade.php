@extends('layouts.admin.Masters.Master')
@section('title', 'Edit User')
@section('content')

<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <div class="layout-container" style = "padding-top: 0px !important;">

            <div class="container-fluid flex-grow-1 container-p-y data-list edit-patients" >

			<div class="row form-top-row">
			  <div class="btn-group">
				  <a class="btn btn-primary" href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}"><i class="fa fa-list"></i>Users List</a>
			   </div>
			</div>
			<div class="layout-content card mt-2 ml-1 mr-1 user-data-form">
             
			{!! Form::open(array('route' => ['admin.editUser',base64_encode($user->id)], 'id' => 'editUser','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
			        <div class="row mt-3 mr-1 ml-1">

			            <div class="form-group col-sm-3">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Enter User Name" value="{{$user->first_name.' '.$user->last_name}}"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3">
							<label>Mobile<i class="required_star">*</i></label>
							<input type="text" name="mobile_no" class="form-control" placeholder="Enter User Mobile number" readonly value="{{$user->mobile_no}}" />
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3">
							<label>E-Mail</label>
							<input type="text" name="email" class="form-control" placeholder="Enter E-Mail id" value="{{$user->email}}"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3">
							<label>Gender<i class="required_star">*</i></label>
							<div class="radio-wrap">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Male" {{$user->gender == 'Male' ? 'checked' : ''}}>Male
								  </label>
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Female" {{$user->gender == 'Female' ? 'checked' : ''}}>Female
								  </label>
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Other" {{$user->gender == 'Other' ? 'checked' : ''}}>Other
								  </label>
							</div>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3 dob">
						<label>DOB<i class="required_star">*</i></label>
						<div class="input-group date">
							<input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off" @if($user->dob != null) value="{{ date('d-m-Y',$user->dob)}}"@endif />
							<span class="input-group-addon patient_age_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
						</div>
						<div class="input-age-group fromDateModal">
							<input type="text" class="form-control age_in_number NumericFeild" placeholder="Age"/>
							<select name="age_in_type" class="age_in_type form-control">
							<option value="y">Y</option>
							<option value="m">M</option>
							<option value="d">D</option>
							</select>
						</div>
						<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3">
							<label>Address</label>
							<input type="text" name="address" class="form-control" placeholder="Enter Address" value="{{$user->address}}" />
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3 dd-wrap">
							<label>Country</label>
						<select class="form-control country_id " name="country_id" id="country_id">
						<option value="">Select country</option>
						@foreach(getCountriesList() as $country)
							<option value="{{$country->id}}"  @if(@$user->country_id == $country->id) selected @endif >{{$country->name}}</option>
						@endforeach
						</select>
						<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3 dd-wrap">
					 <label>State</label>
						 <select class="form-control state_id " name="state_id">
							<option value="">Select State</option>
							@if(!empty($user->country_id))
							@foreach (getStateList($user->country_id) as $state)
								<option value="{{ $state->id }}" @if($user->state_id == $state->id) selected @endif >{{ $state->name }}</option>
							@endforeach
							@endif
						</select>
						<span class="help-block"></span>
					 </div>
					 <div class="form-group col-sm-3 dd-wrap">
						<label>City</label><br>
						<select class="form-control city_id " name="city_id">
							<option value="">Select City</option>
							@if(!empty($user->state_id))
							@foreach (getCityList($user->state_id) as $city)
								<option value="{{ $city->id }}" @if($user->city_id == $city->id) selected @endif >{{ $city->name }}</option>
							@endforeach
							@endif
						</select>
						<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3">
							<label>Zipcode(In Digits Only)</label>
							<input type="text" name="zipcode" class="form-control" placeholder="Enter Zipcode" value="{{$user->zipcode}}" />
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-3 Classification">
						  <label>Classification</label><br>
						  <label class="radio-inline">
							  <input type="radio" name="profession_type" class="profession_type" value="1" {{$user->profession_type == '1' ? 'checked' : 'checked'}}>Professional</label>
							  <label class="radio-inline"><input type="radio" class="profession_type" name="profession_type" {{$user->profession_type == '2' ? 'checked' : ''}} value="2">Student</label>
						</div>
					   <div class="form-group col-sm-3">
							<label>Organization</label>
							<select class="form-control organization" name="organization">
							<option value="">Select Any</option>
							@if(count($OrganizationList))
								@foreach($OrganizationList as $raw)
									<option @if($raw->id == $user->organization) selected @endif value="{{$raw->id}}">{{$raw->title}}</option>
								@endforeach
							@endif
							</select>
							<span class="help-block"></span>
						</div>
						<div class="reset-button col-sm-12">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success submit">Save</button>
						</div>
						</div>			 
					 {!! Form::close() !!}
			 
		    </div>

<!-- Main content -->

<!-- /.content -->



</div>
</div>
</div>
</div>

<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/libs/select2/select2.css' ) }}">
<script src="{{URL::asset('js/select2.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">

	
$(".city_id").select2({
          placeholder: "Select a Doctor",
          allowClear: true
      });
 $(".country_id").select2({
     placeholder: "Select a Doctor",
     allowClear: true
 });
$(".city_id").select2({
          placeholder: "Select a Doctor",
          allowClear: true
      });
	  
$(document).ready(function(){

 var p_age = $('.dob_feild').val();
	if(p_age !=""){getPatientAgeAdd(p_age);}
	$( ".dob_feild" ).datepicker({
		dateFormat: 'dd-mm-yy',
		changeMonth: true,
		changeYear: true,
		yearRange: "1900:+0",
		endDate: "today",
		maxDate: new Date(),
		onSelect: function () {
		 getPatientAgeAdd(this.value);
		 $('.dob_feild').val(this.value);
		 }
	});
	jQuery('.patient_age_cal').click(function () {
		 jQuery('.dob_feild').datepicker('show');
	});

function getPatientAgeAdd(dob) {
	var p_age = showAge(dob);
	if(p_age != "") {
		var age_number = p_age.split(',');
		$("#editUser").find(".fromDateModal .age_in_number").val(age_number[0]);
		if(age_number[1] == "d"){
		$("#editUser").find('.fromDateModal select option[value="d"]').prop("selected", true);
		}
		else if(age_number[1] == "m"){
		$("#editUser").find('.fromDateModal select option[value="m"]').prop("selected", true);
		}
		else if(age_number[1] == "y"){
		$("#editUser").find('.fromDateModal select option[value="y"]').prop("selected", true);
		}
	 }
	}

	//Age To Dob Functionalities
	jQuery(document).on("change", ".age_in_type", function () {
	var age_in_number = $("#editUser").find(".age_in_number").val();
	var now = new Date();
	var type = $(this).val();
	if(!age_in_number) {
		$(this).find('option[value="0"]').prop("selected", true);
	}
	else if(type == 'y') {
		now.setFullYear(now.getFullYear() - parseInt(age_in_number) );
	}
	else if(type == 'm') {
		now.setMonth(now.getMonth() - parseInt(age_in_number) );
	}
	else if(type == 'd') {
		now.setDate(now.getDate() - parseInt(age_in_number));
	}
	else if(type == '0') {
		$(".fromDateModal").find(".age_in_number").val("");
	}
	if(age_in_number){
		$("#editUser").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
	}
	else{
		$("#editUser").find(".ageFormDobCalculate").val("");
	}
	});
});
jQuery(document).on("keypress keyup keydown", ".age_in_number", function () {
if ($(this).val() > 150) {
	$(this).css('border','1px solid red');
	alert("Invalid value");
	$(this).val("");
}
else{
	$(this).css('border','');

	var type = $("#editUser").find(".fromDateModal .age_in_type").val();
	var now = new Date();
	var age_in_number = $(this).val();
	if(!age_in_number) {
	$(this).find('option[value="0"]').prop("selected", true);
	}
	else if(type == 'y') {
	now.setFullYear(now.getFullYear() - parseInt(age_in_number) );
	}
	else if(type == 'm') {
	now.setMonth(now.getMonth() - parseInt(age_in_number) );
	}
	else if(type == 'd') {
	now.setDate(now.getDate() - parseInt(age_in_number));
	}
	else if(type == '0') {
	// $("#editUser").find(".fromDateModal .age_in_number").val("");
	}
	if(age_in_number){
	$("#editUser").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
	}
	else{
	$("#editUser").find(".ageFormDobCalculate").val("");
	}
}
});
jQuery(document).ready(function(){
	jQuery("#editUser").validate({
	// alert("eerr")
		rules: {
			// name: {required:true,maxlength:50},
			mobile_no:{required:true,minlength:10,maxlength:10,number: true},
			gender: "required",
			dob: "required",
			// organization: "required",
		 },
		messages:{
		},
		errorPlacement: function(error, element){
			element.closest('.form-group').find('.help-block').append(error);
		},ignore: ":hidden",
		submitHandler: function(form) {
			jQuery('.loading-all').show();
			$(form).find('.submit').attr('disabled',true);
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: $(form).attr('action'),
				data:  new FormData(form),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data) {
					 if(data==1) {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
						location.reload();
					 }
					 else if(data==2) {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
					  alert("Email already exist.");
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
	jQuery(document).on("click", ".profession_type", function (e) { console.log($(this).val());
		if($(this).val() == "1") {
			$(".organization").prop("required",false);
			$('.organization option:first-child').prop("selected", "selected");
		}
		else{
			$(".organization").prop("required",true);
			$('.organization option[value=4]').prop("selected", "selected");
		}
	});
});
jQuery('.country_id').on('change', function() {
	var cid = this.value;
	var $el = $('.state_id');
	$el.empty();
	jQuery.ajax({
		url: "{!! route('getStateList') !!}",
	 // type : "POST",
		dataType : "JSON",
		data:{'id':cid},
		success: function(result){
		jQuery("#editUser").find("select[name='city_id']").html('<option value="">Select City</option>');
		jQuery("#editUser").find("select[name='state_id']").html('<option value="">Select State</option>');
		jQuery.each(result,function(index, element) {
			 $el.append(jQuery('<option>', {
				 value: element.id,
				 text : element.name
			}));
		});
	}}
	);
})
jQuery(document).on("change", ".state_id", function (e) {
//jQuery('.state_id').on('change', function() {
	var cid = this.value;
	var $el = jQuery('.city_id');
	$el.empty();
	jQuery.ajax({
		url: "{!! route('getCityList') !!}",
		// type : "POST",
		dataType : "JSON",
		data:{'id':cid},
		success: function(result){
		jQuery("#editUser").find("select[name='city_id']").html('<option value="">Select City</option>');
		jQuery.each(result,function(index, element) {
			$el.append(jQuery('<option>', {
			 value: element.id,
			 text : element.name
			}));
		});
	}}
	);
});
</script>
@endsection
