@extends('amp.layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments')
@section('description', 'Book Appointment with doctors by Health Gennie. Order Medicine and lab from the comfort of your home. Read about health issues and get solutions.')
@section('content') 
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="container wrapper-appoint camp-appointment-form">
    <div id="AppointmentWrapper" class="container-inner slot-details">
	@if (Session::has('message'))
   	 <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
	@endif
		<?php 
			
			// $increment = 900;
			$slot_array = [];
			if(!empty($doctor)) {
				// if(!empty($doctor->slot_duration)){
					// $increment = $doctor->slot_duration*60;
				// }
				/*$selected_date = date('d-m-Y');
				$nameOfDay = date('N', strtotime($selected_date));
				if($nameOfDay == "7"){
					$nameOfDay = "0";
				}
				$opd_time = array();
				$time_slot = array();
				if(!empty($doctor->opd_timings)){
					foreach(json_decode($doctor->opd_timings) as $key=>$schedule){
						if(!empty($schedule->days)){
							if(in_array($nameOfDay,$schedule->days)){
								foreach($schedule->timings as $k=>$v) {
										$startTime = strtotime($v->start_time);
										while($startTime <= strtotime($v->end_time)) {
										  $time_slot[] = $startTime;
										  $startTime += $increment;
										}
								}
							}
						}
					}
				}
				$from = "";
				$slot_array = array();
				if(count($time_slot)>0){
					foreach($time_slot as $k=>$val){
						$from = date('Y-m-d H:i:s',strtotime($selected_date." ".date("h:i A",$val)));
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							if(!checkAppointmentAvailable($from,base64_encode($doctor->user_id))) {
								$slot_array[] = $val;
							}
						}
					}
				}
				$increment = $docData->slot_duration*60;*/
			}
			// $slot_array[] = strtotime(date('H:i:s',strtotime(date("Y-m-d H:i:s"))+$increment));
			$slot_array[] = strtotime(date('H:i:s'));
		?>	
		 <input class="doc_id_app_book" type="hidden" value="{{$doctor->id}}"/>
		 <div class="doctor-listtop doctor-listtop2">
			 <div class="doctor-listtop-img">
				<img src="@if(!empty($doctor->profile_pic)){{$doctor->profile_pic}}@else{{url('/img')}}/doc-img.png @endif" width="100" height="100"/>
			 </div>
			 <div class="doctor-listtop-content doctor-listtop-content2">
					<h2>Dr. {{@$doctor->first_name}} {{@$doctor->last_name}},&nbsp; {{@$doctor->qualification}}</h2>
					<p>
					  {{$doctor->content}}</p>
					  @if(!empty($doctor->speciality))
						<span>{{getSpecialityName($doctor->speciality )}}</span>
					  @endif
					<div class="consult-div">
					Consultation Fee: <strong>₹{{$doctor->oncall_fee}}</strong>
					</div>
					<img src="{{ URL::asset('img/calendar-icon-img.png') }}" /> {{date('d-m-Y')}} &nbsp;
					<img src="{{ URL::asset('img/time-icon-img.png') }}" />
					<select class="tele-consult-slot">
						@if(count($slot_array)>0)
							@foreach($slot_array as $val)
								<option value="{{ $val }}">{{date('g:i A',$val)}}</option>
							@endforeach
						@endif
					</select>
					
			</div>
		 </div>
		 <div class="from-widget-top">
			{!! Form::open(array('name' => 'appointmentForm','url' => route('doctor.bookSlotConfirm'), 'method'=>"POST" )) !!}
				<div class="appointment-popup-block22">
					 <input type="hidden" name="otherPatient"  value='0'/>
					 <!-- <input type="hidden" name="onCallStatus"  value='{{app("request")->input("online")}}'/> -->
					 <input type="hidden" name="p_id" value=''>
					 <input type="hidden" name="order_by" value=''>
					 <input type="hidden" name="doctor" value='{{$doctor->id}}'>
					 <input type="hidden" name="date" value="{{date('Y-m-d')}}">
					 <input type="hidden" name="time" value=''>
					 <input type="hidden" name="conType" value='1'>
					</div>
				 <div class="from-widget PatientDetails">
						<div class="from-widget-section">
							 <label>Mobile Number <i class="required_star">*</i></label>
							 <input class="NumericFeild searchByMobile" type="text" name="mobile_no" placeholder="Mobile Number..." value="" />
							 <span class="help-block"></span>
							 <div class="suggesstion-box" style="display:none;"></div>
						 </div>

						<div class="from-widget-section">
							 <label>Patient's Full Name<i class="required_star">*</i></label>
							 <input type="text" name="patient_name" placeholder="Full Name..." value="" />
							 <span class="help-block"></span>
						 </div>
						 <div class="from-widget-section gender">
						  <label>Gender<i class="required_star">*</i></label>
						  <div class="radio-wrap radioBtn">
							<input type="radio" name="gender" id="male" value="Male"/>
							<label for="male">Male</label>
						  </div>
						  <div class="radio-wrap">
							<input type="radio" name="gender" id="female" value="Female"/>
							<label for="female">Female</label>
						  </div>
						  <div class="radio-wrap">
							<input type="radio" name="gender" id="other" value="Other" />
							<label for="other">Other</label>
						  </div>
						    <span class="help-block"><label for="gender" generated="true" class="error" style="display:none;">This field is required.</label></span>
						</div>
						<!--<div class="from-widget-section dob-wrapper">
							<label>Age<i class="required_star">*</i></label>
							<!--<div class="input-group date">
							  <input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off" />
							  <span class="input-group-addon patient_age_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
							  <span class="help-block"></span>
							</div>
						  </div>-->
						<div class="from-widget-section AgeBlokTop">
								 <div class="appointment-popup-block">
									<label>Age(In Digit Only)<i class="required_star">*</i></label>
									<input type="hidden" class="form-control dob_feild ageFormDobCalculate NumericFeild" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off" @if(isset($user))@if($user->dob != null) value="{{ date('d-m-Y',$user->dob)}}"@endif @endif />
									<div class="input-age-group fromDateModal">
										<input type="text" name="age" class="form-control age_in_number NumericFeild" placeholder="Age" />
										<span class="help-block"></span>
										<select name="age_in_type" class="age_in_type" readonly>
											<option value="y">Y</option>
											<option value="m">M</option>
											<option value="d">D</option>
										</select>
										
									</div>
								</div>
						</div>
					<div class="paymentDetails">
						@php
							$consultation_fees = $doctor->oncall_fee;
							$convFee = getSetting("service_charge_rupee")[0];
							$total_fee = $consultation_fees + $convFee;
						@endphp	
						<div class="appointment-checkout-table">
							<table>
								<tr><td>Consultation Fee : </td><td>₹{{number_format($consultation_fees,2)}} /-</td></tr>
								<tr><td>Convenience Fee : </td><td>₹{{number_format($convFee,2)}} /-</td></tr>
								<!--<tr><td>Coupon : </td><td>- ₹ <span class="coupon_discount">0.00</span> /-</td></tr>-->
								<tr><td>Total Pay : </td><td>₹ <span class="total_fee">{{number_format($total_fee,2)}}</span>/-</td></tr>
							</table>
						</div>
						<input type="hidden" name="total_fee" value='{{base64_encode($total_fee)}}'/>
						<input type="hidden" name="service_charge" value='{{base64_encode($convFee)}}'/>
						<input type="hidden" name="consultation_fees" value="{{base64_encode($consultation_fees)}}"/>
						
						<div class="appointment-tandc-div">
							<p>Terms & Conditions</p>
							{!!getTermsBySLug('terms-conditions-tele-appointment')!!}
						</div>
					</div>
				</div>
				  <input type="hidden" name="is_subscribed" value='0'>
					<div class="from-widget-btn">
						<button type="submit" class="btn btn-default subbtn" next="1">Confirm</button>
					 </div>
			{!! Form::close() !!} 
			 </div>
	</div>    
</div>
	<script>
		$(document).ready(function(){
			// $( ".dob_feild" ).datepicker({
			  // dateFormat: 'dd-mm-yy',
			  // changeMonth: true,
			  // changeYear: true,
			  // yearRange: "1900:+0",
			  // endDate: "today",
			  // maxDate: new Date()
			// });
			var p_age = $('.dob_feild').val();
			if(p_age){ getPatientAgeAdd(p_age);}
		 
		  function getPatientAgeAdd(dob) {
			var p_age = showAge(dob);
			if(p_age != "") {
			  var age_number = p_age.split(',');
			  $(".slot-details").find(".fromDateModal .age_in_number").val(age_number[0]);
			  if(age_number[1] == "d"){
				$(".slot-details").find('.fromDateModal select option[value="d"]').prop("selected", true);
			  }
			  else if(age_number[1] == "m"){
				$(".slot-details").find('.fromDateModal select option[value="m"]').prop("selected", true);
			  }
			  else if(age_number[1] == "y"){
				$(".slot-details").find('.fromDateModal select option[value="y"]').prop("selected", true);
			  }
			 }
		  }
		  
	     //Age To Dob Functionalities
		jQuery(document).on("change", ".age_in_type", function () {
			var age_in_number = $(".age_in_number").val();
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
				$(".age_in_number").val("");
			}
			if(age_in_number){
				$(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
			}
			else{
				$(".ageFormDobCalculate").val("");
			}
		});	
		
		 jQuery(document).on("keypress keyup keydown click", ".age_in_number", function () { 
			if ($(this).val() > 100) {
				$(this).css('border','1px solid red');
				alert("Invalid value");
				$(this).val("");
			}
			else{
				$(this).css('border','');
				var type = $(".fromDateModal .age_in_type").val();
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
					// $("#newPatient").find(".fromDateModal .age_in_number").val("");
				}
				if(age_in_number){
					$(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
				}
				else{
					$(".ageFormDobCalculate").val("");
				}
			}	
		});
			var time = $(".tele-consult-slot").val();
			$('form[name="appointmentForm"]').find('input[name="time"]').val(time);
			jQuery(document).on("change", ".tele-consult-slot", function () {
				time = $(this).val();
				$('form[name="appointmentForm"]').find('input[name="time"]').val(time);
			});
	
			var currentMobileRequest;
			 jQuery(document).on("keyup", ".searchByMobile", function () { 
				var currSearch = this;
				var mobile = $(this).val();
				
				if(jQuery(this).val().length == 10) {
					if(currentMobileRequest){
						currentMobileRequest.abort();
					}
					 currentMobileRequest = jQuery.ajax({
						type: "POST",
						url: "{!! route('searchUserByMobile') !!}",
						data: {'mobile':mobile},
						beforeSend: function() {
						},
						success: function(data){
							var liToAppend = "";
							if(data.length > 0){
								var user_main_id = ''; 
								jQuery.each(data,function(k,v) {
									var first_name = '';
									var last_name = '';
									var dob = '';
									var parent = 'Self';
									if(v.first_name){
										first_name = v.first_name;
									}
									if(v.last_name){
										last_name = v.last_name;
									}
									if(v.dob){
										dob = v.dob;
									}
									if(v.parent_id != '0') {
										parent = 'Child';
									}
									if(v.parent_id == '0') {
										user_main_id = v.id;
									}
									
									liToAppend += '<li id="'+v.id+'" p_id="'+user_main_id+'" first_name="'+first_name+'" last_name="'+last_name+'" dob="'+dob+'" last_name="'+last_name+'" gender="'+v.gender+'"  class="dataListMobiles"><div class="detail-clinic"><span class="txt">'+first_name+' '+last_name+' ('+parent+')</span></div></li>';
								});
								liToAppend += '<li id="0" p_id="'+user_main_id+'" class="dataListMobiles"><div class="detail-clinic"><span class="txt">Someone Else</span></div></li>';
							}
							console.log(data);
							jQuery(currSearch).closest(".from-widget-section").find(".suggesstion-box").show();
							jQuery(currSearch).closest(".from-widget-section").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
						},
						error: function(error) {
							if(error.status == 401 || error.status == 419 || error.status == 500){
								//location.reload();
							}
						}
					 });
				 }
				 else{
					 //$('form[name="appointmentForm"]').find('input[name="mobile_no"]').val('');
					 jQuery(".container").find(".suggesstion-box").hide();
					 jQuery(".container").find(".suggesstion-box ul").remove();
				 }
			 });
			 jQuery(document).on("click", ".dataListMobiles", function () {
					//$('form[name="appointmentForm"]').find('input[name="mobile_no"]').val(jQuery(this).find('.txt').text());
					if(jQuery(this).attr("id") == '0') {
						jQuery("form[name='appointmentForm']").find('input[name=otherPatient]').val("1");
						$('form[name="appointmentForm"]').find('input[name="order_by"]').val(jQuery(this).attr("p_id"));
						$('form[name="appointmentForm"]').find('input[name="p_id"]').val(jQuery(this).attr("p_id"));
						$('form[name="appointmentForm"]').find('input[name="patient_name"]').val('');
						$('form[name="appointmentForm"]').find('input[name="dob"]').val('');
						$(".slot-details").find(".fromDateModal .age_in_number").val('');
					}
					else{
						jQuery("form[name='appointmentForm']").find('input[name=otherPatient]').val("0");
						if(jQuery(this).attr("id") != "null") {
							$('form[name="appointmentForm"]').find('input[name="order_by"]').val(jQuery(this).attr("id"));
						}
						if(jQuery(this).attr("id") != "null") {
							$('form[name="appointmentForm"]').find('input[name="p_id"]').val(jQuery(this).attr("id"));
						}
						if(jQuery(this).attr("first_name") != "null") {
							var name = jQuery(this).attr("first_name")+' '+jQuery(this).attr("last_name");
							$('form[name="appointmentForm"]').find('input[name="patient_name"]').val(name);
						}
						if(jQuery(this).attr("dob") != "null") {
							$('form[name="appointmentForm"]').find('input[name="dob"]').val(jQuery(this).attr("dob"));
							getPatientAgeAdd(jQuery(this).attr("dob"));
						}
						 if(jQuery(this).attr("gender") != "null") {
							var gender = jQuery(this).attr("gender");
							if(gender == "Male") {
							  $('form[name="appointmentForm"]').find('#male').prop('checked',true);
							}
							else {
							  $('form[name="appointmentForm"]').find('#female').prop('checked',true);
							}
						}
					}
					jQuery(this).closest(".suggesstion-box").hide();
					jQuery(this).closest(".suggesstion-box ul").remove();
				});
				
			
			 jQuery("form[name='appointmentForm']").validate({
				rules: {
					//age: "required",
					//relationship: "required",
					patient_name: {required:true,maxlength:50},
					gender: {required:true},
					//call_type: {required:true},
					age: {required:true},
					// last_name: {required:true,maxlength:20},
					mobile_no:{required:true,minlength:10,maxlength:10,number: true},
					// email: {required: true,email: true,maxlength:30},
				},
				messages: {
					patient_name: "Please enter Name",
					//last_name: "Please enter Last Name",
					dob: "Please select DOB",
					//email: "Please enter valid Email ID",
					//mobile_no:{"required": "Please enter Mobile No.","number": "Please enter valid Mobile No."},
					// relationship: "Please Choose a Relation ",
				},
				errorPlacement: function(error, element) {
					 error.appendTo(element.next());
				},ignore: ":hidden",
				submitHandler: function(form) {
					  jQuery('.loading-all').show();
					  jQuery('.subbtn').attr('disabled',true);
					  jQuery.ajax({
					  type: "POST",
					  dataType : "JSON",
					  url: "{!!route('teleCamp')!!}",
					  data:  new FormData(form),
					  contentType: false,
					  // cache: false,
					  processData:false,
					  success: function(result) {
							if(result == '1'){
								location.reload();
							}
							else{
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								$.alert('Oops Something goes Wrong.');
							}
						},
						error: function(error) {
							jQuery('.loading-all').hide();
							jQuery('.subbtn').attr('disabled',false);
							$.alert('Oops Something goes Wrong.');
						}
					   });
				}
			});
        });	
</script>
@endsection