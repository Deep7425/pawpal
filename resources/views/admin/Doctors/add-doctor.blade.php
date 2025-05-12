@extends('layouts.admin.Masters.Master')
@section('title', 'Add Doctor')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper AddDoctorSectionTop">
<?php $year = range(1950,date("Y")); ?>
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
			<h1>Doctor</h1>
			<small>Doctor list</small>
			<ol class="breadcrumb hidden-xs">
				<li><a href="{{ route('admin.home') }}"><i class="pe-7s-home"></i> Home</a></li>
				<li class="active">Doctors</li>
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
							<a class="btn btn-primary" href="{{ route('admin.nonHgDoctorsList') }}"> <i class="fa fa-list"></i>Doctor List</a>
						</div>
					</div>
					<div class="panel-body">
					{!! Form::open(array('route' => 'admin.addDoctor', 'id' => 'addDoctor', 'class' => 'col-sm-12', 'enctype' => 'multipart/form-data')) !!}
					<input type="hidden" name="clinic_id"/>
							<div class="AddDoctorHeading"><h2>Doctor Details</h2></div>
							<div class="col-sm-4 form-group">
								<label>First Name<i class="required_star">*</i></label>
								<input type="text" class="form-control" name="first_name" placeholder="First Name" value="">
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Last Name<i class="required_star">*</i></label>
								<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="">
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Name (HINDI)<i class="required_star">*</i></label>
								<input type="text" class="form-control" name="name" placeholder="Name In Hindi" value="">
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Gender<i class="required_star">*</i></label>
								<p class="GenderTpo"><label><input type="radio" value="Male" name="gender" checked="checked"/>Male</label>
								<label><input type="radio" value="Female" name="gender"/>Female</label></p>
								<span class="help-block">
								</span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Email<i class="required_star">*</i></label>
								<input v_type="0" type="email" class="form-control verifyDocData" placeholder="Enter Email" aria-describedby="emailHelp"  name="email" value="" />
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Doctor Registration No.<i class="required_star">*</i></label>
								<input type="text" class="form-control" name="reg_no" placeholder="Doctor Registration No." value="">
								<span class="help-block"></span>
							</div>
						  <div class="col-sm-4 form-group">
							<label>Registration Year<i class="required_star">*</i></label>
							<select name="reg_year" class="form-control">
								<option value="">Select Registration Year</option>
								@foreach($year as $raw)
								<option value="{{$raw}}">{{$raw}}</option>
								@endforeach
							</select>
							<span class="help-block"></span>
						  </div>
						  <div class="col-sm-4 form-group select_reg_council_div">
							<label>Registered Council<i class="required_star">*</i></label>
							<select name="reg_council" class="form-control multiSelect select_reg_council">
							<option value="">Select Registered Council</option>
							@foreach(getCouncilingData() as $council)
							<option value="{{ $council->id }}">{{ $council->council_name }}</option>
							@endforeach
							</select>
							<span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
						  </div>
						  <div class="col-sm-4 form-group">
							<label>Last Obtained Degree</label>
							<input type="text" class="form-control" name="last_obtained_degree" placeholder="Last Obtained Degree" value="" autocomplete="off" />
							<span class="help-block"></span>
						  </div>
						  <div class="col-sm-4 form-group">
							<label>Degree Year</label>
							<select name="degree_year" class="form-control">
									<option value="">Select Degree Year</option>
									@foreach($year as $raw)
									<option value="{{$raw}}">{{$raw}}</option>
									@endforeach
							 </select>
							<span class="help-block"></span>
						  </div>
						  <div class="col-sm-4 form-group university_div">
							<label>College/University<i class="required_star">*</i></label>
							<select name="university" class="form-control multiSelect select_university">
							<option value="">Select College/University</option>
							@foreach (getUniversityList() as $university)
							<option value="{{ $university->id }}">{{$university->name}}</option>
							@endforeach
							</select>
							<span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label></span>
						  </div>

							<div class="col-sm-4 form-group">
								<label>Contact No.<i class="required_star">*</i></label>
								<input v_type="1" type="text" class="form-control NumericFeild verifyDocData" name="mobile_no" placeholder="Contact No." value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Experience<i class="required_star">*</i></label>
								<input type="text" class="form-control NumericFeild" name="experience" placeholder="Experience" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Qualification<i class="required_star">*</i></label>
								<input type="text" class="form-control" name="qualification" placeholder="Qualification" value="">
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Doctor Spaciality<i class="required_star">*</i></label>
								<select class="form-control multiSelect" id="speciality" name="speciality">
								  <option value="" selected>Select Speciality</option>
								  @foreach (getSpecialityList() as $specialities)
								  <option value="{{ $specialities->id }}">{{ $specialities->specialities }}</option>
								  @endforeach
								</select>
								<span class="help-block"></span>
							</div>
							<!--<div class="col-sm-6 HealthGennieInterestedFeesShow">
								<div class="form-group2">
								<label>Health Gennie Interested</label>
								<p>
								<label><input type="radio" value="1" name="hg_interested"/>Yes</label>
								<label><input type="radio" value="0" name="hg_interested"/>No</label></p>
								<span class="help-block">
								</span>
								</div>
								<div class="form-group3">
								  <label>In-clinic Fees Show</label><br>
									<p class="GenderTpo">
									<label><input type="radio" name="fees_show" value="1"/>Yes</label>
									<label><input type="radio" name="fees_show" value="0"/>No</label>
									</p>
									<span class="help-block"></span>
								 </div>
							 </div>-->
							<div class="col-sm-6">
							  <label>Consultation Type<i class="required_star">*</i></label>
								<?php $types = [];?>
								<div class="ConsultationType">
									<div class="Consultation11">
									<label class="radio-inline"><input type="checkbox" class="oncall_status" name="oncall_status[]" value="2">In Clinic</label>
									<p class="inclinic_fees"  style="display:none;">
										<input class="form-control NumericFeild" placeholder="Fees" type="text" name="consultation_fees" value="" />
										<span class="help-block"></span>
									</p>
									</div>
									<div class="Consultation11">
										<label class="radio-inline"><input type="checkbox" class="oncall_status" name="oncall_status[]" value="1">Tele</label>
										<p class="oncall_fee oncall_fee123" style="display:none;">
										<input class="form-control NumericFeild" placeholder="Fees" type="text" name="oncall_fee" value='' />
										<span class="help-block"></span>
										</p>
								   </div>
									<span id="oncall_status" class="help-block testtop"></span>
							   </div>
							</div>
							<div class="ConvenienceFeeTop">
							<div class="col-sm-4 form-group">
								<label>Convenience Fee</label><br>
								<input type="text" class="form-control NumericFeild" name="convenience_fee" placeholder="Convenience Fee" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Account Number</label><br>
								<input type="text" class="form-control NumericFeild" name="acc_no" placeholder="Account Number" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								  <label>Account Name</label>
								  <input class="form-control" placeholder="Enter Account Name" type="text" name="acc_name" value=""/>
								  <span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>IFSC Code</label><br>
								<input type="text" class="form-control" name="ifsc_no" placeholder="IFSC Code" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Bank Name</label><br>
								<input type="text" class="form-control" name="bank_name" placeholder="Bank Name" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Paytm Number</label><br>
								<input type="text" class="form-control NumericFeild" name="paytm_no" placeholder="Paytm Number" value=""/>
								<span class="help-block"></span>
							</div>
							</div>

							  <div class="col-sm-6 form-group">
								<label>About For Doctor</label><br>
								<textarea class="form-control" rows="5" id="About" name="content" value=""></textarea>
								<span class="help-block"></span>
							  </div>
							  <div class="col-md-4 form-group">
								<div class="form-check">
								<div class="DoctorImage">
								<label>Doctor image</label><br>
								<input type="file" class="form-control" name="profile_pic" placeholder="profile_pic" />
								<span class="help-block"></span>
							  </div>
  							  </div>
							   </div>

							<div class="AddDoctorHeading"><h2>Clinic Details</h2></div>
							<div class="col-sm-4 form-group">
								<label>Clinic Name (If found select from list)<i class="required_star">*</i></label>
								<input type="text" class="form-control clinic_nameBySearech" name="clinic_name" placeholder="Clinic Name" value=""/>
								<span class="help-block"></span>
								<i class="btn-reset-clinic" style="display:none;"><button type="button">Reset</button></i>
							   <div class="suggesstion-box" style="display:none;"></div>
							</div>
							<div class="col-sm-4 form-group form-fields typeField">
								<label>Type<i class="required_star">*</i></label>
								<div class="radio-wrap clinicRadio">
								<input type="radio" name="practice_type" id="clinic" value="1"/>
								<label for="clinic">Clinic</label>
								</div>
								<div class="radio-wrap hospitalRadio">
								<input type="radio" name="practice_type" id="hospital" value="2" />
								<label for="hospital">Hospital</label>
								</div>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Clinic Mobile</label>
								<input type="text" class="form-control NumericFeild" name="clinic_mobile" placeholder="Contact No." value=""/>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Clinic Email</label>
								<input type="email" class="form-control" placeholder="Clinic Email" aria-describedby="emailHelp" name="clinic_email" value="" />
								<span class="help-block"></span>
							</div>

							<div class="col-sm-4 form-group">
								<label >Recommend</label>
								<input type="text" class="form-control" name="recommend" placeholder="recommend" value="" />
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Clinic Speciality<i class="required_star">*</i></label>
								<select class="form-control multiSelect" id="clinic_speciality" name="clinic_speciality">
								  <option value="" selected>Select Speciality</option>
								  @foreach (getSpecialityList() as $specialities)
								  <option value="{{ $specialities->id }}">{{ $specialities->specialities }}</option>
								  @endforeach
								</select>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Website</label>
								<input type="text" class="form-control" name="website" placeholder="website" value="" />
								<span class="help-block"></span>
							</div>
							<div class="col-sm-4 form-group">
								<label>Follow Up Count</label>
								<input type="text" class="form-control" name="followup_count" placeholder="Follow Up Count" value=""/>
								<span class="help-block"></span>
							</div>
							<div class="AddressCol">
							<div class="col-sm-4 form-group">
								<label>Address</label>
								<textarea class="form-control" rows="5" name="address_1" value=""></textarea>
								<span class="help-block"></span>
							</div>
							<div class="col-sm-8 form-group colsm8form-group">
							<div class="col-sm-4 form-group">
								<label>Country<i class="required_star">*</i></label>
								<select class="form-control country_id multiSelect" name="country_id" id="country_id">
								<option value="">Select country</option>
								@foreach(getCountriesList() as $country)
									<option value="{{$country->id}}">{{$country->name}}</option>
								@endforeach
								</select>
								<span class="help-block"></span>
							</div>

							<div class="col-sm-4 form-group">
							 <label>State<i class="required_star">*</i></label>
								 <select class="form-control state_id multiSelect" name="state_id">
								  <option value="">Select State</option>
								</select>
								<span class="help-block"></span>
							 </div>
							 <div class="col-sm-4 form-group">
							  <label>City<i class="required_star">*</i></label><br>
								<select class="form-control city_id multiSelect" name="city_id">
									<option value="">Select City</option>
								</select>
								<span class="help-block"></span>
							  </div>
							   <div class="col-sm-4 form-group">
							  <label>Locality</label><br>
								<select class="form-control locality_id multiSelect" name="locality_id">
									<option value="">Select Locality</option>
								</select>
								<span class="help-block"></span>
							  </div>
							  <div class="col-sm-4 form-group">
								<label>Zipcode</label><br>
								<input type="text" class="form-control NumericFeild" name="zipcode" placeholder="Zipcode" value=""/>
								<span class="help-block"></span>
							  </div>
							  <div class="col-sm-4">
								<label>Servtel API KEY</label><br>
								<input type="text" class="form-control" name="servtel_api_key" placeholder="Servtel API KEY" />
								<span class="help-block"></span>
							  </div>
							  </div>
							  </div>
								<div class="col-md-12">
									<button type="button" name="button" class="form-control" id="addAddress">Add Address</button>
								</div>
								<div class="col-md-12">
									<div class="row AlternateAddress"></div>
								</div>
							  <div class="col-sm-12  form-group">
								<label>Note</label><br>
								<textarea class="form-control" rows="5" id="comment" name="note" value=""></textarea>
								<span class="help-block"></span>
							  </div>
							<div class="form-check">
							  <div class="col-md-4">
							  <div class="DoctorImage">
								<label>Clinic Image</label>
								<input type="file" class="form-control" name="clinic_image" placeholder="clinic_image" />
								<span class="help-block"></span>
							  </div>
                              </div>
                           </div>
						  <div class="AddDoctorHeading"><h2>OPD Details</h2></div>
						  <div class="col-md-12">
							<?php
								$appt_durations = getAppoimentDurations();
								$increment = 900;
								$day_in_increments = range( 0, (86400 - $increment), $increment);
							 ?>
							<div id="opd_timing_tab" class="opd_timing_tab tab-pane txt-center">
								<div class="registration-wrap doc-register">
								  <div class="checkbox-div complete-str">
									<h3 class="checkbox-divOpd">Opd Timings Schedule<i class="required_star">*</i></h3>
									<div class="opd-timings-slot">
									  <label>Appointment Duration<i class="required_star">*</i></label>
									  <select name="slot_duration" class="slots-data">
										<option value="">Select</option>
										@foreach($appt_durations as $index => $duration)
											<option value="{{$duration->time}}">{{$duration->title}}</option>
										@endforeach
									  </select>
									  <span class="help-block"></span>
									</div>
								  <div class="main-div-schedule">
									<div class="check-wrapper checkbox-div">
									<label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="1">Monday
									  <span class="checkmark"></span>
									</label>
									<label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="2">Tuesday
									  <span class="checkmark"></span>
									</label>

									<label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="3">Wednesday
									  <span class="checkmark"></span>
									</label>

									<label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="4">Thursday
									  <span class="checkmark"></span>
									</label>

									<label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="5">Friday
									  <span class="checkmark"></span>
									</label>

									 <label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="6">Saturday
									  <span class="checkmark"></span>
									</label>

									 <label class="chck-container">
									  <input type="checkbox" class="day_check" name="schedule[1][days][]" value="0">Sunday
									  <span class="checkmark"></span>
									</label>
								  </div>

								  <div class="pop-up-detail">
								   <div class="sessions-div" scheduleCnt="1">
									 <div class="schedulingTop">
									<label>Session 1:</label>
									<div class="teleconsult_section">
									  <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check"  value="1">Tel- Consultation</label>
									  <input type="hidden" class="teleconsult" name="schedule[1][timings][1][teleconsultation]" value="0">
									</div>
									<div class="teleconsult_duration set_error" style="display:none;">
										<select name="schedule[1][timings][1][tele_appt_duration]" class="slots">
											<option value="">Tele Appointment Duration</option>
										  @foreach($appt_durations as $index => $dur)
											<option value="{{$dur->time}}">{{$dur->title}}</option>
										   @endforeach
										</select>
									</div>
									<div class="set_error">
									<select name="schedule[1][timings][1][start_time]" class="session_time_up given_time">
									   <option value="">Start Time</option>
									   @foreach($day_in_increments as $time)
									   <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
									   @endforeach
									</select>
									</div>
									<div class="set_error">
									 <select name="schedule[1][timings][1][end_time]" class="session_time_down given_time">
									  <option value="">End Time</option>
									  @foreach($day_in_increments as $time)
										<option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
									  @endforeach
									</select>
									</div>
								  </div>
								   </div>
								  </div>
								  <div class="add-more-session"><a class="addSession" href="javascript:void(0);">Add More Session</a></div>
								   <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
								</div>
									</div>
								  <div class="add-more-session schedule"><a href="javascript:void(0);" class="addMoreSchedule">Add More Schedule</a></div>
								</div>
							</div>
						  </div>
						  <div class="col-sm-12 reset-button">
							<button type="submit" class="btn btn-success submit">Save</button>
						 </div>
						{!! Form::close() !!}
					 </div>
				 </div>
			 </div>
		 </div>
	 </section> <!-- /.content -->
 </div> <!-- /.content-wrapper -->
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
 $(".teleconsult_check").each(function(){
	if($(this).is(':checked')){
		$(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');
	}
 });
});
jQuery.validator.addMethod("letterswithspace", function(value, element) {
	return this.optional(element) || /^[a-z][a-z\s]*$/i.test(value);
}, "This feild should be in alphabets only.");
jQuery(document).on("click", ".oncall_status", function () {
	if($(this).val() == '1' && $(this).prop("checked") == true) {
	  $(".oncall_fee").show();
	  $(".teleconsult_section").show();
	}
	else if($(this).val() == '2' && $(this).prop("checked") == true) {
		$(".inclinic_fees").show();
	}
	else if($(this).val() == '1'){
		$(".oncall_fee").hide();
		$(".teleconsult_section").hide();
		$(".teleconsult_section").each(function(){
			$(this).find(".teleconsult_check").prop("checked",false);
		});
	}
	else if($(this).val() == '2'){
		$(".inclinic_fees").hide();
	}
});

jQuery(document).ready(function(){
$(".multiSelect").select2();
jQuery("#addDoctor").validate({
	rules: {
		clinic_name:  {required:true,maxlength:255},
		name: {required:true,maxlength:50},
		first_name: {required:true,maxlength:30},
		last_name: {required:true,maxlength:30},
		mobile_no:{required:true,minlength:10,maxlength:10,number: true},
		clinic_mobile:{minlength:10,maxlength:10,number: true},
		email: {required: true,email: true,maxlength:100},
		clinic_email: {email: true,maxlength:100},
		consultation_fees: {required:true,maxlength:6,number: true},
		convenience_fee: {maxlength:4,number: true},
		// consultation_discount: {maxlength:6,number: true},
		address_1: {required:true,maxlength:255},
		qualification: {required: true,maxlength:250},
		speciality: "required",
		clinic_speciality: "required",
		experience: {required: true,maxlength:2},
		reg_no: {required: true,maxlength:50},
		reg_year: {required: true,maxlength:4},
		reg_council: {required: true,maxlength:200},
		oncall_fee: {required: true,maxlength:6,number: true},
		last_obtained_degree: {maxlength:200},
		degree_year: {maxlength:4},
		university: {maxlength:200},
		note: {maxlength:250},
		country_id: "required",
		"oncall_status[]": "required",
		state_id: "required",
		city_id: "required",
		followup_count: {number: true},
		zipcode: {minlength:6,maxlength:6,number: true},
		slot_duration: "required",
		acc_no: {
			  required: function(element) {
				  if ($('input[name=ifsc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
					  return true;
				  } else {
					  return false;
				  }
			  },
			  maxlength:16,
			  number: true
		  },
		   acc_name: {
			  required: function(element) {
				  if ($('input[name=acc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
					  return true;
				  } else {
					  return false;
				  }
			  },
			  maxlength:50,
		  },
		  ifsc_no: {
			  required: function(element) {
				  if ($('input[name=acc_no]').val() != ""  || $('input[name=bank_name]').val() != "") {
					  return true;
				  } else {
					  return false;
				  }
			  },
			  minlength:11,
			  maxlength:11
		  },
		  paytm_no: {
			  minlength:10,
			  maxlength:10,
			  number: true
		  },
		  bank_name: {
			  required: function(element) {
				  if ($('input[name=acc_no]').val() != "" || $('input[name=ifsc_no]').val() != "" ) {
					  return true;
				  } else {
					  return false;
				  }
			  }
		  },
	},
	messages:{
	},
	errorPlacement: function(error, element){
		if (element.attr("name") == 'oncall_status[]') {
			$("#oncall_status").html(error);
		}
		else {
			error.appendTo(element.next());
		}
	},ignore: ":hidden",
	submitHandler: function(form) {
		var flag = true;
		 $('.main-div-schedule').each(function() {
			$(this).find(".success-data").html('');
			$(this).find("#msg").hide();
			if($(this).find('.day_check:checked').length < 1){
				$(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
				$(this).find(".success-data").slideDown();
				flag = false;
			}
		});
		$('.given_time').each(function () {
			if($(this).val()==''){
				$(this).next(".help-block").remove();
				$(this).after('<span style="width:100%" class="help-block">This field is required</span>');
				flag = false;
			}
			else{
				$(this).next(".help-block").remove();
			}
		});
		 $('.teleconsult_section .teleconsult_check').each(function (){
		   var teleconsult_duration = $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val();
		  if($(this).is(':checked') && teleconsult_duration == ""){
			$(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
			$(this).closest('.sessions-div').find('.teleconsult_duration .slots').after('<span style="width:100%" class="help-block">This field is required</span>');
			  flag = false;
			 //return false;
		  }
		  else{
			$(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
		  }
		});
		if(flag == true) {
			$(form).find('.submit').attr('disabled',true);
			 jQuery('.loading-all').show();
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('admin.addDoctor')!!}",
				data:  new FormData(form),
				contentType: false,
				cache: false,
				processData:false,
				success: function(data) {
					 if(data==1)
					 {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
					location.reload();
					// document.location.href='{!! route("admin.nonHgDoctorsList")!!}';
					 }
					 else
					 {
					  jQuery('.loading-all').hide();
					  $(form).find('.submit').attr('disabled',false);
					  alert("Oops Something Problem");
					 }
				}
			});
		}
		else{
			return false;
		}
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
		jQuery("#addDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
		jQuery("#addDoctor").find("select[name='state_id']").html('<option value="">Select State</option>');
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
	  jQuery("#addDoctor").find("select[name='city_id']").html('<option value="">Select City</option>');
	  jQuery.each(result,function(index, element) {
		  $el.append(jQuery('<option>', {
			 value: element.id,
			 text : element.name
		  }));
	  });
  }}
  );
});
jQuery(document).on("change", ".city_id", function (e) {
  var cid = this.value;
  var $el = jQuery('.locality_id');
  $el.empty();
  jQuery.ajax({
	  url: "{!! route('getLocalityList') !!}",
	  // type : "POST",
	  dataType : "JSON",
	  data:{'id':cid},
	success: function(result){
	  jQuery("#updateDoctor").find("select[name='locality_id']").html('<option value="">Select Locality</option>');
	  jQuery.each(result,function(index, element) {
		  $el.append(jQuery('<option>', {
			 value: element.id,
			 text : element.name
		  }));
	  });
	},
	error: function(error) {
		if(error.status == 401){
			alert("Session Expired,Please logged in..");
			location.reload();
		}
		else{}
	}
	}
  );
});
jQuery(document).on("click", ".addSession", function () {
 var cnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').length+1;
 var scheduleCnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').attr('scheduleCnt');
 //alert(scheduleCnt);
 if(cnt <= 8){
	var row =  '<div class="sessions-div" scheduleCnt="1"> <div class="schedulingTop"><label>Session '+cnt+':</label> <div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label><input type="hidden" class="teleconsult" name="schedule['+scheduleCnt+'][timings]['+cnt+'][teleconsultation]" value="0"> </div><div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div></div>';
	row += '<button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>';
	jQuery(this).parents(".main-div-schedule").find('.pop-up-detail').append(row);
	// $('.oncall_status').each(function () {
		// if($(this).val() == '1' && $(this).prop("checked") == true) {
			// $(".teleconsult_section").show();
		// }
		// else if($(this).val() == '1'){
			// $(".teleconsult_section").hide();
			// $(".teleconsult_section").each(function() {
				// $(this).find(".teleconsult_check").prop("checked",false);
			// });
		// }
	// });
}else{
	alert("You can not add more than 8 sessions.");
}
});
jQuery(document).on("click", ".removeSess", function () {
jQuery(this).parents(".main-div-schedule .pop-up-detail  .sessions-div").remove();
});
jQuery(".addMoreSchedule").click(function(){
var cnt = jQuery('.main-div-schedule').length+1;
if(cnt <= 7){
   var row = '<div class="main-div-schedule"> <div class="check-wrapper checkbox-div"> <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="1">Monday <span class="checkmark"></span> </label> <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="2">Tuesday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="3">Wednesday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="4">Thursday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="5">Friday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="6">Saturday <span class="checkmark"></span> </label>  <label class="chck-container"> <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="0">Sunday <span class="checkmark"></span> </label> </div> <div class="pop-up-detail"> <div class="sessions-div" scheduleCnt="1"> <label>Session 1:</label><div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label> <input type="hidden" class="teleconsult" name="schedule['+cnt+'][timings][1][teleconsultation]" value="0"></div><div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+cnt+'][timings][1][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div><div class="set_error"> <select name="schedule['+cnt+'][timings][1][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+cnt+'][timings][1][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div></div> </div> <div class="add-more-session"><a class="addSession" href="javascript:void(0);">Add More Session</a></div><div id="msg" class="success-data alert alert-danger" style="display: none;"></div> <div class="opd-timings-schedule"><button class="btn btn-default remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
	jQuery('.complete-str').append(row);
	// $('.oncall_status').each(function () {
		// if($(this).val() == '1' && $(this).prop("checked") == true) {
			// $(".teleconsult_section").show();
		// }
		// else if($(this).val() == '1'){
			// $(".teleconsult_section").hide();
			// $(".teleconsult_section").each(function() {
				// $(this).find(".teleconsult_check").prop("checked",false);
			// });
		// }
	// });
}
else{
alert("Doctor Can not Schedule More Than 7 Days.");
}
});
jQuery(document).on("click", ".remove", function () {
jQuery(this).parents(".main-div-schedule").remove();
});

jQuery(document).on("change", ".day_check", function () {
var th = $(this), val = th.prop('value');

if(th.is(':checked')){
$('#opd_timing_tab').find(':checkbox[value="'+val+'"]').not($(this)).prop('checked',false);
$(this).closest(".main-div-schedule").each(function() {
$(this).find(".success-data").html('');
$(this).find("#msg").hide();
if($(this).find('.day_check:checked').length < 1) {
  $(this).find(".success-data").html('');
  $(this).find(".success-data").find("#msg").hide();
}
});
}
else{
$(this).closest(".main-div-schedule").find(".success-data").html('');
$(this).closest(".main-div-schedule").find("#msg").hide();
$(this).closest(".main-div-schedule").each(function() {
$(this).find(".success-data").html('');
$(this).find("#msg").hide();
if($(this).find('.day_check:checked').length < 1) {
  $(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
  $(this).find(".success-data").slideDown();
}
});
}
});
jQuery(document).on("change", ".session_time_up", function (){
  var currevent = this;
  var apostart_time = $(currevent).val();
  var practimeslot = 15;
  var updatedEndTime =  moment(apostart_time, "HH:mm:ss").add(practimeslot, 'minutes');
  selectedvar = moment(updatedEndTime).format('HH:mm:ss');
  console.log(selectedvar);
  var stDatetimestamp = moment(selectedvar, "HH:mm:ss").format('X');
  arrsloatEnd = [];
  $(this).find('option').each(function(){
	  if($(this).val() != ''){
		  var endTimestamp = moment($(this).val(), "HH:mm:ss").format('X');
		  if(endTimestamp >= stDatetimestamp){
			  arrsloatEnd.push($(this).val());
		  }
	  }
  });
  var row = '';
  $.each( arrsloatEnd, function( key, value ) {
	  var str = value;
	  var time = new moment(str, 'HH:mm:ss');
	  row += '<option value="'+value+'" >'+moment(time).format('hh:mm A')+'</option>';
  });
  $(currevent).parents('.sessions-div').find('.session_time_down').empty();
  $(currevent).parents('.sessions-div').find('.session_time_down').html(row);
});
jQuery(document).on("change", ".teleconsult_check", function (){
if($(this).is(':checked')){
$(this).closest('.teleconsult_section').find('.teleconsult').val(1);
$(this).closest('.sessions-div').find('.teleconsult_duration').show();
var slot_duration = 5;
$(this).closest('.sessions-div').find('.teleconsult_duration .slots').val(slot_duration).trigger('change');
$(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');

}
else {
 $(this).closest('.teleconsult_section').find('.teleconsult').val(0);
 $(this).closest('.sessions-div').find('.teleconsult_duration').hide();
 $(this).closest('.sessions-div').find('.teleconsult_duration .slots').prop('selectedIndex',0);
  $(this).closest('.sessions-div').find('.set_error').removeClass('CommonWidth');
}
});

function range(start, end, step = 1) {
const len = Math.floor((end - start) / step) + 1
return Array(len).fill().map((_, idx) => start + (idx * step))
}

var clinicSearchRequest;
jQuery(document).on("keyup paste", ".clinic_nameBySearech", function () {
var currSearch = this;
if(jQuery(this).val().length <= 0) {
	$('#addDoctor').find('input[name="clinic_id"]').val('');
	 jQuery(".container").find(".suggesstion-box").hide();
	 jQuery(".container").find(".suggesstion-box ul").remove();
}
if(clinicSearchRequest) {
	clinicSearchRequest.abort();
}
if(jQuery(this).val().length >= 3) {
  clinicSearchRequest = jQuery.ajax({
  type: "POST",
  url: "{!! route('getClinics') !!}",
  data: {'searchText':jQuery(this).val()},
  beforeSend: function(){
	jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
  },
  success: function(data){
  console.log(data);
	  var liToAppend = "";
		if(data.length > 0){
		  jQuery.each(data,function(k,v) {
			 var city_id = null;
			 var locality_id = null;
			 var city = '';
			 var clinic_name = '';
			 var practice_type = null;
			 var locality = '';
			 var clinic_speciality = null;
			 var pic_width  = 15;
			 var pic_height = 12;
			 var search_pic = '{{ URL::asset("img/search-dd.png") }}';
			 if(v.locality_id){
				locality = v.locality_id.name;
				locality_id = v.locality_id.id;
			 }
			 if(v.clinic_speciality){
				clinic_speciality = v.clinic_speciality;
			 }
			 if(v.city_id){
				city = v.city_id.name;
				city_id = v.city_id.id;
			 }
			 if(v.clinic_name){
				clinic_name = v.clinic_name;
			 }
	 if(v.practice_type){
				practice_type = v.practice_type;
			 }
			if(v.clinic_image_url != null){
				search_pic = v.clinic_image_url;
				pic_width  = 30;
				pic_height  = 30;
			}
			liToAppend += '<li value="'+v.id+'" p_id="'+v.practice_id+'" clinic_name="'+v.clinic_name+'" practice_type="'+practice_type+'" city_id="'+city_id+'" locality_id="'+locality_id+'" clinic_speciality="'+clinic_speciality+'" clinic_image="'+v.clinic_image+'"  clinic_image_url="'+v.clinic_image_url+'" clinic_mobile="'+v.clinic_mobile+'" clinic_email="'+v.clinic_email+'" website="'+v.website+'" address_1="'+v.address_1+'" country_id="'+v.country_id+'" state_id="'+v.state_id+'" zipcode="'+v.zipcode+'" class="dataListClinics"><i class="icon-clinicImage-pic"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail-clinic"><span class="txt">'+v.clinic_name+'</span><span class="spec">' +locality+' '+city+'</span></div></li>';
		  });
		}else{
			//liToAppend += '<li value="0">"'+jQuery(currSearch).val()+'" Clinic Not Found.</li>';
	  }
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").show();
	  jQuery(currSearch).closest(".form-group").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
  }
  });
}
});

jQuery(document).on("click", ".dataListClinics", function () {
alert('Your profile has been created as a Visiting doctor under this "'+jQuery(this).find('.txt').text()+'"');
$('#addDoctor').find('input[name="clinic_id"]').val(jQuery(this).attr("p_id"));
jQuery(this).closest(".form-group").find(".clinic_nameBySearech").val(jQuery(this).find('.txt').text());
jQuery(this).closest(".form-group").find(".clinic_nameBySearech").attr('readonly',true);

if(jQuery(this).attr("practice_type") != "null") {
practice_type = jQuery(this).attr("practice_type");
if (practice_type == 2) {
  $('#addDoctor').find('#hospital').prop('checked',true);
  $('#addDoctor').find('.clinicRadio').hide();

}
else {
  $('#addDoctor').find('#clinic ').prop('checked',true);
  $('#addDoctor').find('.hospitalRadio').hide();
}
}


if(jQuery(this).attr("country_id") != "null") {
country_data = jQuery(this).attr("country_id");
$('#addDoctor').find('.country_id option[value="'+country_data+'"]').prop('selected',true);
	var countryField = $('#addDoctor').find('.country_id');
addDupHiddenField(countryField, 1);
}
else {
var countryField = $('#addDoctor').find('.country_id');
addDupHiddenField(countryField, 1);
}
if(jQuery(this).attr("state_id") != "null") {
  var state_data = jQuery(this).attr("state_id");
  $('#addDoctor').find('.state_id').val(state_data).trigger('change');
  var stateField = $('#addDoctor').find('.state_id');
  addDupHiddenField(stateField, 1);
  }
  else {
	var stateField = $('#addDoctor').find('.state_id');
	addDupHiddenField(stateField, 1);
  }
if(jQuery(this).attr("city_id") != "null") {
var city_data = jQuery(this).attr("city_id");
setTimeout(function(){
$('#addDoctor').find('.city_id').val(city_data).trigger('change');
var cityField = $('#addDoctor').find('.city_id');
addDupHiddenField(cityField, 1);
},1000);
}
  else {
	var cityField = $('#addDoctor').find('.city_id');
	addDupHiddenField(cityField, 1);
  }

if(jQuery(this).attr("locality_id") != "null") {
var locality_data = jQuery(this).attr("locality_id");
setTimeout(function(){
$('#addDoctor').find('.locality_id').val(locality_data).trigger('change');
var localityField = $('#addDoctor').find('.locality_id');
addDupHiddenField(localityField, 1);
},1500);
}
else {
	var localityField = $('#addDoctor').find('.locality_id');
	addDupHiddenField(localityField, 1);
}

if(jQuery(this).attr("clinic_speciality") != "null") {
  var clinic_speciality = jQuery(this).attr("clinic_speciality");
  setTimeout(function(){
	$('#addDoctor').find('.clinic_speciality').val(clinic_speciality).trigger('change');
	var clinicSpeField = $('#addDoctor').find('.clinic_speciality');
	addDupHiddenField(clinicSpeField, 1);
  },1500);
}
else {
var clinicSpeField = $('#addDoctor').find('.clinic_speciality');
addDupHiddenField(clinicSpeField, 1);
}
if(jQuery(this).attr("clinic_mobile") != "null") {
	$('#addDoctor').find('input[name="clinic_mobile"]').val(jQuery(this).attr("clinic_mobile"));
}
if(jQuery(this).attr("clinic_email") != "null") {
	$('#addDoctor').find('input[name="clinic_email"]').val(jQuery(this).attr("clinic_email"));
}
if(jQuery(this).attr("website") != "null") {
	$('#addDoctor').find('input[name="website"]').val(jQuery(this).attr("website"));
}
if(jQuery(this).attr("address_1") != "null") {
	$('#addDoctor').find('input[name="address_1"]').val(jQuery(this).attr("address_1"));
	$('#addDoctor').find('input[name="address_1"]').attr('readonly',true);
}
if(jQuery(this).attr("zipcode") != "null") {
	$('#addDoctor').find('input[name="zipcode"]').val(jQuery(this).attr("zipcode"));
}
if(jQuery(this).attr("clinic_image") != "null" && jQuery(this).attr("clinic_image_url") != "null") {
	$('#addDoctor').find('#docClinicImage').attr('src',jQuery(this).attr("clinic_image_url"));
	$('#addDoctor').find('.clinicFIleDIv').hide();
}
else{
	$('#addDoctor').find('#docClinicImage').attr('src',"{{ URL::asset('img/camera-icon.jpg') }}");
	$('#addDoctor').find('.clinicFIleDIv').show();
}
$('#addDoctor').find('input[name="clinic_mobile"]').attr('readonly',true);
$('#addDoctor').find('input[name="clinic_email"]').attr('readonly',true);
$('#addDoctor').find('input[name="website"]').attr('readonly',true);
$('#addDoctor').find('input[name="zipcode"]').attr('readonly',true);

$('#addDoctor').find(".btn-reset-clinic").show();
jQuery(this).closest(".suggesstion-box").hide();
jQuery(this).closest(".suggesstion-box ul").remove();
});

jQuery(document).on("click", ".btn-reset-clinic", function () {
jQuery(".clinic_nameBySearech").attr('readonly',false);
$('#addDoctor').find('input[name="clinic_id"]').val('');
jQuery(".clinic_nameBySearech").val('');


var countryField = $('#addDoctor').find('.country_id');
addDupHiddenField(countryField, 2);

var stateField = $('#addDoctor').find('.state_id');
addDupHiddenField(stateField, 2);

var cityField = $('#addDoctor').find('.city_id');
addDupHiddenField(cityField, 2);

var localityField = $('#addDoctor').find('.locality_id');
addDupHiddenField(localityField, 2);

var clinicSpeField = $('#addDoctor').find('.clinic_speciality');
addDupHiddenField(clinicSpeField, 2);

$('#addDoctor').find('.clinicRadio').show();
$('#addDoctor').find('.hospitalRadio').show();
$('#addDoctor').find('#clinic').prop('checked', false);
$('#addDoctor').find('#hospital').prop('checked', false);



$('#addDoctor').find('.country_id').val('101').trigger('change');
$('#addDoctor').find('.state_id').val('33').trigger('change');
$('#addDoctor').find('.city_id').val('').trigger('change');
$('#addDoctor').find('.locality_id').val('').trigger('change');
$('#addDoctor').find('.clinic_speciality').val('').trigger('change');

$('#addDoctor').find('input[name="clinic_mobile"]').val('');
$('#addDoctor').find('input[name="clinic_email"]').val('');
$('#addDoctor').find('input[name="website"]').val('');
$('#addDoctor').find('input[name="address_1"]').val('');
$('#addDoctor').find('input[name="address_1"]').attr('readonly',false);
$('#addDoctor').find('input[name="zipcode"]').val('');
$('#addDoctor').find('#docClinicImage').attr('src','{{ URL::asset('img/camera-icon.jpg') }}');
$('#addDoctor').find('.clinicFIleDIv').show();

$('#addDoctor').find('input[name="clinic_mobile"]').attr('readonly',false);
$('#addDoctor').find('input[name="clinic_email"]').attr('readonly',false);
$('#addDoctor').find('input[name="website"]').attr('readonly',false);
$('#addDoctor').find('input[name="zipcode"]').attr('readonly',false);
$('#addDoctor').find('input[name="city_id"]').attr('disabled',false);
$('#addDoctor').find('input[name="locality_id"]').attr('disabled',false);
$('#addDoctor').find('input[name="clinic_speciality"]').attr('disabled',false);
$('#addDoctor').find(".btn-reset-clinic").hide();
});

function addDupHiddenField(field, type){
var name = field.prop('name');
var parent = field.parent();
if (type == 1) {
if (field.prop('disabled')) {
var name = field.attr('original-name');
parent.find('input[type="hidden"][name='+name+']').val(field.val());
}
else {
field.attr('original-name', name);
var $hiddenInput = $('<input/>',{ type  : 'hidden',
name  : name,
value : field.val()
});
parent.append( $hiddenInput );
field.prop({ name : name + "_1",disabled : true });
}
}
else if (type == 2 ) {
if(field.prop('disabled')){
var name = field.attr('original-name');
parent.find('input[type="hidden"][name='+name+']').remove();
field.prop({name : name,disabled : false});
field.removeAttr('original-name');
}
}
}
jQuery(document).ready(function () {
$(".yearPick").datepicker({
	dateFormat: "yy",
	// viewMode: "years",
	changeYear: true,
	// showButtonPanel: true,
	// minViewMode: "years",
	// viewSelect: 'years',
	autoclose: true,
	minDate: new Date('1900'),
	maxDate: new Date('2030')
});

jQuery(document).on("keyup", ".verifyDocData", function () {
	var docInfo  = $(this).val();
	var v_type  = $(this).attr('v_type');
	if(v_type == 1){
		if(docInfo.length > 6) {
			verifyDocDetail(docInfo,v_type);
		}
	}
	else{
		verifyDocDetail(docInfo,v_type);
	}
});
function verifyDocDetail(docInfo,v_type) {
	jQuery.ajax({
		type: "POST",
		dataType : "JSON",
		url: "{!! route('verifyDocDetails')!!}",
		data:{'docInfo':docInfo,'v_type':v_type},
		success: function(data) {
			if(data.status == 1){
				if(v_type == 1){
					alert('This mobile number is already registered with another person.');
					jQuery("#addDoctor").find('input[name="mobile_no"]').val('');
				}
				else{
					alert('This Email is already registered with another person.');
					jQuery("#addDoctor").find('input[name="email"]').val('');
				}
			}
		  jQuery('.loading-all').hide();
		},
		error: function(error) {
			jQuery('.loading-all').hide();
			if(error.status == 401){
				//alert("Session Expired,Please logged in..");
				location.reload();
			}
			else{
			//	alert("Oops Something goes Wrong.");
			}
		}
	});
}

});
jQuery(document).on("click", "#addAddress", function (e) {
	var div = '<div class="col-md-4 form-group"><label>Alternate Address</label><br><textarea class="form-control" rows="2" name="alternate_address[]" value=""></textarea><span class="help-block"></span><div class="closeAddress"><i class="fa fa-times" aria-hidden="true"></i></div></div>';
	$('.AlternateAddress').append(div);

});
jQuery(document).on("click", ".closeAddress", function (e) {
	$(this).parent().remove();
});
</script>
@endsection
