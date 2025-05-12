@extends('layouts.admin.Masters.Master')
@section('title', 'Add User')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
	<div class="header-icon">
		<i class="pe-7s-note2"></i>
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
		<h1>User</h1>
		<small>User list</small>
		<ol class="breadcrumb hidden-xs">
			<li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
			<li class="active">User</li>
		</ol>
	</div>
</section>
<!-- Main content -->
<section class="content">
	<div class="row">
		<!-- Form controls -->
		<div class="col-sm-12">
			<div class="panel panel-bd lobidrag">
				<div class="panel-heading">
					<div class="btn-group">
						<a class="btn btn-primary" @if(Route::currentRouteName() == "admin.addPatients") href="{{ route('admin.corporateUsers') }} @else href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }} @endif"><i class="fa fa-list"></i>Users List</a>
					</div>
				</div>
				<div class="panel-body">
					{!! Form::open(array('route' => 'admin.addPatients', 'id' => 'addPatients','enctype' => 'multipart/form-data' , 'class' => 'col-sm-12')) !!}
						<div class="form-group col-sm-6">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Enter User Name"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6">
							<label>Mobile <i class="required_star">*</i></label>
							<input type="text" name="mobile_no" class="form-control" placeholder="Enter User Mobile number"/>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6">
							<label>Gender<i class="required_star">*</i></label>
							<div class="radio-wrap">
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Male">Male
								  </label>
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Female">Female
								  </label>
								  <label class="form-check-label">
								    <input type="radio" class="form-check-input" name="gender" value="Other">Other
								  </label>
							</div>
							<span class="help-block"></span>
						</div>
						<div class="form-group col-sm-6">
						<label>DOB</label>
						<div class="input-group date">
							<input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off"  />
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
						<div class="form-group col-sm-6">
						  <label>Classification</label>
						  <div class="radio-wrap">
						  <label class="form-check-label">
							  <input type="radio" name="profession_type" class="profession_type" value="1" checked="checked">Professional</label>
							  <label class="form-check-label"><input type="radio" class="profession_type" name="profession_type" value="2">Student</label>
							  </div>
						</div>
					   <div class="form-group col-sm-6">
							<label>Organization</label>
							<select class="form-control organization" name="organization">
							<option value="">Select Any</option>
							@if(count($OrganizationList))
								@foreach($OrganizationList as $raw)
									<option  value="{{$raw->id}}">{{$raw->title}}</option>
								@endforeach
							@endif
							</select>
							<span class="help-block"></span>
						</div>
						<div class="reset-button col-sm-12">
						   <button type="reset" class="btn btn-warning">Reset</button>
						   <button type="submit" class="btn btn-success submit">Save</button>
						</div>
					 {!! Form::close() !!}
			   </div>
		   </div>
	   </div>
   </div>
</section> <!-- /.content -->
</div>
<script type="text/javascript">
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
		$("#addPatients").find(".fromDateModal .age_in_number").val(age_number[0]);
		if(age_number[1] == "d"){
		$("#addPatients").find('.fromDateModal select option[value="d"]').prop("selected", true);
		}
		else if(age_number[1] == "m"){
		$("#addPatients").find('.fromDateModal select option[value="m"]').prop("selected", true);
		}
		else if(age_number[1] == "y"){
		$("#addPatients").find('.fromDateModal select option[value="y"]').prop("selected", true);
		}
	 }
	}

	//Age To Dob Functionalities
	jQuery(document).on("change", ".age_in_type", function () {
	var age_in_number = $("#addPatients").find(".age_in_number").val();
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
		$("#addPatients").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
	}
	else{
		$("#addPatients").find(".ageFormDobCalculate").val("");
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

	var type = $("#addPatients").find(".fromDateModal .age_in_type").val();
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
	// $("#addPatients").find(".fromDateModal .age_in_number").val("");
	}
	if(age_in_number){
	$("#addPatients").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
	}
	else{
	$("#addPatients").find(".ageFormDobCalculate").val("");
	}
}
});
jQuery(document).ready(function(){
	jQuery("#addPatients").validate({
		rules: {
			// name: {required:true,maxlength:50},
			mobile_no:{required:true,minlength:10,maxlength:10,number: true},
			gender: "required",
			// dob: "required",
			// profession_type: "required",
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
				url: "{!! route('admin.addPatients')!!}",
				data:  new FormData(form),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data) {
					 if(data==1) {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
						var route = '{{Route::currentRouteName()}}';
						if (route == 'admin.addPatients') {
							document.location.href='{!! route("admin.corporateUsers")!!}';
						}else {
							document.location.href="{{ route('admin.patientList',['start_date'=>base64_encode(date('Y-m-d')),'end_date'=>base64_encode(date('Y-m-d'))]) }}";
						}

					 }
					 else if(data==2) {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
					  alert("Mobile Number already exist.");
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
</script>
@endsection
