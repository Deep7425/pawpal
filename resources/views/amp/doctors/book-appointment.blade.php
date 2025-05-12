@extends('amp.layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments')
@section('description', 'Book Appointment with doctors by Health Gennie. Order Medicine and lab from the comfort of your home. Read about health issues and get solutions.')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="container wrapper-appoint">
    <div id="AppointmentWrapper" class="container-inner slot-details">
	<?php $user = Auth::user(); $is_subscribed = 0; $conType = base64_decode(app("request")->input("conType"));?>
    @if(isset($doctor) && !empty($doctor))
		 <input class="doc_id_app_book" type="hidden" value="{{base64_encode($doctor->id)}}"/>
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
					  <?php
							$docFee = $doctor->consultation_fees;
							/*if($doctor->consultation_discount != null && $doctor->consultation_discount < $doctor->consultation_fees){
								$docFee = $doctor->consultation_discount;
							}
							else if($doctor->consultation_discount == '0'){
								$docFee = $doctor->consultation_discount;
							}*/
					  ?>
						@if($conType==1)
							<div class="consult-div">
								Tele-consultation Fees(₹) :<strong>{{$doctor->oncall_fee}}</strong>
							</div>
						@else
							<div class="consult-div">
								In-clinic Fees(₹) : <strong>@if($docFee == "0") FREE @else ₹{{$docFee}} @endif</strong>
							</div>
						@endif
					<img src="{{ URL::asset('img/calendar-icon-img.png') }}" /> @if(app("request")->input("date") != "") {{ base64_decode(app("request")->input("date")) }} @endif &nbsp;
					<img src="{{ URL::asset('img/time-icon-img.png') }}" />@if(app("request")->input("time") != "") {{ date ("h:i A",base64_decode(app("request")->input("time"))) }} @endif
			</div>
			@if($conType==2)
			<div class="doctor-listtop doctor-listtop3">
				@if(!empty($doctor->clinic_name))
					<p><strong>Clinic</strong> : <span>{{@$doctor->clinic_name}}</span></p>
					<span>
						{{@$doctor->address_1}}
						@if(!empty($doctor->getCityName)) {{@$doctor->getCityName->name}}, {{@$doctor->getStateName->name}} @endif
						<div class="location hideforPaytm">
						<a target="_blank" @if(!empty($doctor->address_1))  href="https://maps.google.com/maps?q={{$doctor->clinic_name}} {{$doctor->address_1}} {{@$doctor->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get Direction )</a>
						</div>
					</span>
				@endif
			</div>
			@endif
		 </div>
		 @if($doctor->claim_status == '1' && $doctor->varify_status == '1')
		 <div class="from-widget-top" style="@if($app_id != '') display:none; @else  display:block @endif">
			{!! Form::open(array('name' => 'appointmentForm','url' => route('doctor.bookSlotConfirm'), 'method'=>"POST" )) !!}
        <ul class="serverError" style="color:red;"></ul>
				<div class="appointment-popup-block22">
					 <input type="hidden" name="isPaytmTab"  id="isPaytmTab"/>
					 <input type="hidden" name="otherPatient"  value='0'/>
					 <input type="hidden" name="onCallStatus"  value='{{base64_encode($conType)}}'/>
					 <input type="hidden" name="order_by" value='{{$user->id}}'>
					 <input type="hidden" name="doctor" value='{{ app("request")->input("doc") }}'>
					 <input type="hidden" name="date" value='{{base64_decode(app("request")->input("date"))}}'>
					 <input type="hidden" name="time" value='{{base64_decode(app("request")->input("time"))}}'>
					</div>
				 <div class="from-widget PatientDetails">
						<div class="form-fields  form-field-mid pad-r0 specialization">
						  <label>Select the patient name<i class="required_star">*</i></label>
						  <select name="p_id" class="searchDropDown form-control AptBookFor">
							@foreach ($users as $userDetail)
							<option value="{{ $userDetail->id }}" p_type="self" {{$userDetail->id == $user->id ? 'selected="selected"' : ''}} @if(!empty($userDetail->first_name)) fname="{{$userDetail->first_name}}" @else fname="" @endif @if(!empty($userDetail->last_name)) lname="{{$userDetail->last_name}}" @else lname=""  @endif @if(!empty($userDetail->dob)) dob="{{date('d-m-Y',$user->dob)}}"  @else dob="" @endif @if(!empty($user->gender)) gender="{{$user->gender}}" @else gender="Male" @endif parent_id="{{$userDetail->parent_id}}" @if(!empty($userDetail->other_mobile_no)) other_mobile_no="{{$userDetail->other_mobile_no}}" @else other_mobile_no="" @endif>@if(!empty($userDetail->first_name)) {{ $userDetail->first_name.' '.$userDetail->last_name }} @else Self @endif</option>
							@endforeach
							<option value='{{$user->id}}' parent_id="{{$user->id}}" other_mobile_no="" p_type="other" >Someone Else</option>
						  </select>
	                      <span class="help-block"><label for="speciality" generated="true" class="error" style="display:none;">This field is required.</label></span>
						</div>

						<div class="from-widget-section">
							 <label>Patient's Full Name<i class="required_star">*</i></label>
							 <input type="text" name="patient_name" placeholder="Full Name..." value="{{@$user->first_name.' '.$user->last_name}}" />
							 <span class="help-block"></span>
						 </div>
						 <div class="from-widget-section gender">
						  <label>Gender<i class="required_star">*</i></label>
						  <div class="radio-wrap radioBtn">
							<input type="radio" name="gender" id="male" value="Male" @if($user->gender == 'Male') checked @endif @if($user->gender == 'Male') checked @endif >
							<label for="male">Male</label>
						  </div>
						  <div class="radio-wrap">
							<input type="radio" name="gender" id="female" value="Female" @if($user->gender == 'Female') checked @endif>
							<label for="female">Female</label>
						  </div>
						  <div class="radio-wrap">
							<input type="radio" name="gender" id="other" value="Other" @if($user->gender == 'Other') checked @endif >
							<label for="other">Other</label>
						  </div>
						    <span class="help-block"><label for="gender" generated="true" class="error" style="display:none;">This field is required.</label></span>
						</div>
						<!--<div class="from-widget-section dob-wrapper">
							<label>DOB<i class="required_star">*</i></label>
							<div class="input-group date">
							  <input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off"  @if($user->dob != null) value="{{ date('d-m-Y',$user->dob)}}"@endif />
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
						 <div class="from-widget-section">
							 <label>Mobile Number @if(!empty($user->mobile_no))(Not Editable)@endif <i class="required_star">*</i></label>
							 <input class="NumericFeild" type="text" name="mobile_no" placeholder="Mobile Number..." value="{{@$user->mobile_no}}" @if(!empty($user->mobile_no)) readonly @endif />
							 <span class="help-block"></span>
						 </div>
						 <div class="from-widget-section otherMobileDiv" style="display:none;">
							 <label>Alternate Mobile Number</label>
							 <input class="NumericFeild" type="text" name="other_mobile_no" placeholder="Mobile Number..." value="{{@$user->other_mobile_no}}"/>
							 <span class="help-block"></span>
						 </div>
				</div>
				@if(Auth::id() != null && checkUserSubcriptionStatus(Auth::id()))
				 <?php
					$plan = getUserPlan(Auth::id());
					$max_fee = "";
					if(!empty($plan->UserSubscribedPlans)) {
						if(!empty($plan->UserSubscribedPlans->meta_data)) {
							$plan_data = json_decode($plan->UserSubscribedPlans->meta_data);
							$max_fee = @$plan_data->max_appointment_fee;
						}
					}
					$is_subscribed = 1;
				 ?>
					 @if($docFee <= $max_fee && @$plan->remaining_appointment > 0)
					 <!--<div class="consult-fee-free-div text-success">
						<marquee>Elite membership appointment charges free.This appointment will not be cancelled.</marquee>
					 </div>-->
					 @endif
				 @endif
				  <input type="hidden" name="is_subscribed" value='{{$is_subscribed}}'/>
					<div class="paymentDetails" style="display: none;">
						<div class="form-address-details CouponBox divForHide">
						<div class="input-box">
							<input type="text" placeholder="Enter Coupon" class="couponInput" id="couponInputCode" value="" />
							<input type="hidden" name="coupon_id" id="coupanId" value="">
							<input type="hidden" name="coupon_code" id="couponCode" value="">
							<input type="hidden" name="coupon_discount" value="">
						  </div>
						  <button id="coupanApply" type="button" class="btn-add-address">Apply</button>
						  <strong class="CouponAvailableMsg" style="display:none;"></strong>
						</div>
						<div class="coupanApplyedBox" style="display:none;">
						  <div class="save-icon"><img width="13" height="14" src="{{asset('img/right-icon.png')}}" /> You saved <strong>₹</strong><strong class="applyCouponAmount"></strong></div>
						  <p><strong class="applyCouponCode"></strong> <span class="applyCouponText"></span> </p>
						  <div class="remove-icon"><a href="javascript:void(0)" class="removeCoupan">Remove</a> </div>
						</div>
						<?php
							  if($conType==1) {
								$consultation_fees = $doctor->oncall_fee;
								$convFee = getSetting("service_charge_rupee")[0];
								$total_fee = $consultation_fees + $convFee;
							  }
							  else {
								$consultation_fees = $doctor->consultation_fees;
								$convFee = getSetting("inclinic-service-charge")[0];
								$total_fee = $convFee;
							  }

						?>
						<div class="appointment-checkout-table">
							<table>
								<tr><td>Consultation Fee @if($conType == "2")<strong>(Pay at clinic)</strong>@endif : </td><td> ₹ {{number_format($consultation_fees,2)}} /-</td></tr>
								<tr><td>Convenience Fee : </td><td> ₹ {{number_format($convFee,2)}} /-</td></tr>
								<tr><td>Coupon : </td><td> ₹ <span class="coupon_discount">0.00</span> /-</td></tr>
								<tr><td>Total Pay : </td><td> ₹ <span class="total_fee">{{number_format($total_fee,2)}}</span>/-</td></tr>
							</table>
						</div>
						<input type="hidden" name="total_fee" value='{{base64_encode($total_fee)}}'/>
						<input type="hidden" name="service_charge" value='{{base64_encode($convFee)}}'/>
						<input type="hidden" name="consultation_fees" value="{{base64_encode($consultation_fees)}}"/>

						<div class="appointment-tandc-div">
							<p>Terms & Conditions</p>
							@if($conType == "1")
								{!!getTermsBySLug('terms-conditions-tele-appointment')!!}
							@else
								{!!getTermsBySLug('term-conditions-appointment')!!}
							@endif
						</div>
					</div>
					<div class="from-widget-btn">
            <button type="button" class="btn btn-default backButton" style="display:none;">Back</button>
						<button type="submit" class="btn btn-default subbtn" next="1">Confirm and Pay</button>
					 </div>
			{!! Form::close() !!}
			 </div>
			  <div class="from-widget-top" style="@if($app_id != '') display:block; @else display:none; @endif">
				 <div class="steps-index-suss">
					<p><img src="{{ URL::asset('img/Success-icon-image-1.png') }}" /> Your appointment has been processed</p>
				 </div>
				 <div class="appointment-conf">
					<div class="success-section">
					<div class="success-top-image">

					</div>
					 <div class="appointment-conf-contnet">
						<p>
							Thanks for booking an appointment with <strong class="dr-name">Dr. {{$doctor->first_name}} {{$doctor->last_name}}</strong> on <strong class="dr-name">Health Gennie</strong>. Your appointment will be confirmed by Dr. shortly.</br>
							<strong>@if(app("request")->input("date") != "") {{ base64_decode(app("request")->input("date")) }} @endif</strong> at <strong>@if(app("request")->input("time") != "") {{ date ("h:i A",base64_decode(app("request")->input("time"))) }} @endif</strong>
							<h3>Please visit the clinic at </h3><span>{{$doctor->address_1}}, {{$doctor->address_2}}<br />{{getCityName($doctor->city_id)}}, {{getStateName($doctor->state_id)}}, {{getCountrieName($doctor->country_id)}}, {{$doctor->zipcode}}.</span>
						</p>
						<p class="btn-wrapper">
                        <a href="{{ route('userAppointment') }}" class="btn btn-info">My Appointments</a>
						<a href="{{ route('downloadReceipt',['aPiD' => $app_id ]) }}" class="downloadReceipt btn btn-info" href="javascript:void(0);">Download Details</a>
						</p>

					 </div>
					</div>
				</div>
			  </div>
			  @else
				<div class="from-widget-top no-bg confirmation_div">
                	<img src="{{ URL::asset('img/verification.png') }}" />
					<h1>Doctor Verification is Under process</h1>
					<div class="steps-index-suss text-wrapper">
						<p>Please book appointment with another Dr., Thanks Team Health Gennie.</p>
					</div>
				</div>
			@endif

	@else
		<div class="appoint-confirm-wrapper no-bg confirmation_div">
			<h1>Appointment Slot Not Available</h1>
			<div class="text-wrapper">
				 <p>Please choose other slot for appointment.</p>
			</div>
		</div>
	@endif

	</div>
</div>
	<script>$(document).ready(function(){
			console.log(isPaytmTab);
			$("#isPaytmTab").val(isPaytmTab);
			$( ".dob_feild" ).datepicker({
			  dateFormat: 'dd-mm-yy',
			  changeMonth: true,
			  changeYear: true,
			  yearRange: "1900:+0",
			  endDate: "today",
			  maxDate: new Date()
			});
		});
	jQuery(document).on("change", ".AptBookFor", function () {
		var thisOption = $('option:selected', this);
		if (thisOption.attr("p_type") != "other") {
			jQuery("form[name='appointmentForm']").find('input[name=patient_name]').val(thisOption.attr("fname").trim()+' '+thisOption.attr("lname").trim());
			jQuery("form[name='appointmentForm']").find('input[name=dob]').val(thisOption.attr("dob").trim());
			jQuery("form[name='appointmentForm']").find('input[name=gender][value='+thisOption.attr("gender")+']').prop('checked', true);
			jQuery("form[name='appointmentForm']").find('input[name=otherPatient]').val("0");
			jQuery("form[name='appointmentForm']").find('input[name=other_mobile_no]').val(thisOption.attr("other_mobile_no").trim());
		}
		else {
			jQuery("form[name='appointmentForm']").find('input[name=patient_name]').val("");
			jQuery("form[name='appointmentForm']").find('input[name=dob]').val("");
			jQuery("form[name='appointmentForm']").find('input[name=otherPatient]').val("1");
			jQuery("form[name='appointmentForm']").find("input[name=gender][value='male']").prop("checked",true);
			jQuery("form[name='appointmentForm']").find('input[name=other_mobile_no]').val(thisOption.attr("other_mobile_no").trim());
		}
		if(thisOption.attr('parent_id') != 0) {
			$(".otherMobileDiv").show();
		}
		else{
			$(".otherMobileDiv").hide();
		}
	});
	jQuery(document).ready(function () {
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
      jQuery(document).on("click", ".backButton", function () {
        $('.paymentDetails').slideUp();
        setTimeout(function(){ $('.PatientDetails').slideDown(); }, 150);

        $('.subbtn').attr('next','1');
        $('.subbtn').text('Confirm and Pay');
        $('.backButton').hide();
      });

			 jQuery("form[name='appointmentForm']").validate({
				// Specify the validation rules
				rules: {
					age: "required",
					relationship: "required",
					patient_name: {required:true,maxlength:50},
					gender: {required:true},
					call_type: {required:true},
					dob: {required:true},
					// last_name: {required:true,maxlength:20},
					mobile_no:{required:true,minlength:10,maxlength:10,number: true},
					other_mobile_no:{minlength:10,maxlength:10,number: true},
					email: {required: true,email: true,maxlength:30},
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
					var flag = true;
					if ($('.subbtn').attr('next') == '1') {
						$('.PatientDetails').slideUp();
						$('.paymentDetails').slideDown();
						$('.subbtn').attr('next','0');
						$('.subbtn').text('Pay Now');
						$('.backButton').show();
						flag = false;
					}
					if(flag == true) {
					  doc_id = $(".doc_id_app_book").val();
					  date = $('form[name="appointmentForm"]').find('input[name="date"]').val();
					  time = $('form[name="appointmentForm"]').find('input[name="time"]').val();
					  conType = $('form[name="appointmentForm"]').find('input[name="conType"]').val();
					  jQuery('.loading-all').show();
					  jQuery('.subbtn').attr('disabled',true);
					  jQuery.ajax({
					  type: "POST",
					  dataType : "JSON",
					  url: "{!!route('doctor.bookSlotConfirm')!!}",
					  data:  new FormData(form),
					  contentType: false,
					  // cache: false,
					  processData:false,
					  success: function(result) {
							console.log(result);
							if(result.status == '0') {
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								var url = '{!! url("/doctor/appointment-book?doc='+doc_id+'&app_id='+result.app_id+'&date='+btoa(date)+'&time='+btoa(time)+'&conType='+btoa(conType)+'") !!}';
								window.location = url;
							}
							else if(result.status == '1') {
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								var url = '{!! url("/appointmentCheckout?tid='+result.tid+'&order_id='+result.order_id+'&amount='+result.amount+'&merchant_param1='+result.merchant_param1+'&order_by='+result.order_by+'") !!}';
								window.location = url;
							}
							else if(result.status == '2') {
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								  $.alert({
									title: 'oops !',
									content: 'Time slot not available. Please choose another time slot.',
									draggable: false,
									type: 'red',
									typeAnimated: true,
									buttons: {
										ok: function(){
										// location.reload();
										},
									}
								  });
							}
							else if(result.status == '3') {
								const requestObject={
								  "amount": atob(result.amount),
								  "orderId": atob(result.order_id),
								  "txnToken": atob(result.txnToken),
								  "mid": atob(result.MID),
								}
								//alert(atob(result.txnToken));
								var myOrderid = atob(result.order_id);
								console.log(requestObject);
								jQuery('.loading-all').hide();
								function ready (callback) {
									//alert("success");
								if(window.JSBridge) {
								   callback && callback();
								   } else{
								  document.addEventListener('JSBridgeReady', callback, false);
								}}
								ready(function () { //console.log('kapssss');
								 JSBridge.call('paytmPayment',requestObject,
								  function(result) {
								   console.log(result);

								   //var payResult = JSON.stringify(result);
								   if(result.data == false){
									   jQuery.ajax({
										type: "POST",
										url: '{!! url("paymentcancelMiniProgram") !!}',
										data: {'id':myOrderid},
										success: function(data)
										{
											location.reload();
										}
									   });
								   }else{
									   jQuery.ajax({
										type: "POST",
										url: '{!! url("paytmResponseMIniApp") !!}',
										data: {'data':result.data},
										success: function(data)
										{
											console.log(data);
											location.href = data;
										}
									});
								   }
								  });
								});
							}
							 else if(result.status == '4') {
								var liToAppend = "";
								jQuery.each(result.errors,function(k,v){
								  liToAppend += '<li>'+v+'</li>';
								});
								$('.serverError').append(liToAppend);
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',true);
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
				}
				});
        });

		jQuery(document).on("click", ".BackButtonWithSearch", function (e) {
			var search_type = $(this).attr('search_type');
			$("#searchDocInfo").find("input[name='search_type']").val(search_type);
			if(search_type == "1") {
				var data_info_type = $(this).attr('info_type');
				var data_info_id = $(this).attr('data_id');
				$("#searchDocInfo").find("input[name='info_type']").val(data_info_type);
				$("#searchDocInfo").find("input[name='id']").val(data_info_id);
			}
			setTimeout(function(){
				$("#searchDocInfo").submit();
			}, 500);
		});
		function ApplyCoupon(couponcode) {
  jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyCoupon') !!}",
  data: {'couponcode':couponcode},
  success: function(data){
		if (data.coupon_code) {

      // $('#coupanDiscount').val(btoa(data.coupon_rate));
      // $('#couponCode').val(data.coupon_code);
      var consultation_fees = $("form[name='appointmentForm']").find('input[name=consultation_fees]').val();
      var service_charge = $("form[name='appointmentForm']").find('input[name=service_charge]').val();
      var coupanDiscountAmount = atob(service_charge) * data.coupon_rate / 100;
      var total_fee = $("form[name='appointmentForm']").find('input[name=total_fee]').val();
      total_fee = atob(total_fee) - coupanDiscountAmount;
      $("form[name='appointmentForm']").find('input[name=total_fee]').val(btoa(total_fee));
      $("form[name='appointmentForm']").find('.total_fee').text(total_fee.toFixed(2));
      $("form[name='appointmentForm']").find('input[name=coupon_id]').val(data.coupon_id);
      $("form[name='appointmentForm']").find('input[name=coupon_code]').val(data.coupon_code);
      $("form[name='appointmentForm']").find('input[name=coupon_discount]').val(btoa(coupanDiscountAmount));
      $("form[name='appointmentForm']").find('.coupon_discount').text(coupanDiscountAmount.toFixed(2));

			$('.coupanApplyedBox').find('.applyCouponCode').text(data.coupon_code);
			$('.coupanApplyedBox').find('.applyCouponAmount').text(coupanDiscountAmount);
			if(data.other_text != null) {
				$('.coupanApplyedBox').find('.applyCouponText').text(data.other_text);
			}
			$('.divForHide').slideUp();
			$('.coupanApplyedBox').slideDown();
		}
    else if (data == 0) {
      $('.CouponAvailableMsg').text('Invalid Or Expired Coupan Code');
			$('.CouponAvailableMsg').css("color", "red");
			$('.CouponAvailableMsg').slideDown();
    }
		else{
			$('.CouponAvailableMsg').text(data[0]);
			$('.CouponAvailableMsg').css("color", "red");
			$('.CouponAvailableMsg').slideDown();
		}
      jQuery('#coupanApply').attr('disabled',false);
    },
    error: function(error)
    {
      if(error.status == 401)
      {
        //  alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}
$("#couponInputCode").on("keyup", function(){
	$('.CouponAvailableMsg').slideUp();
	$('.CouponAvailableMsg').text('');
	$('.CouponAvailableMsg').css("color", "");
});
jQuery(document).on("click", "#coupanApply", function () {
	var couponCode = $('#couponInputCode').val();
	if (couponCode != "") {
		ApplyCoupon(couponCode);
	}
	else{
		$('.CouponAvailableMsg').text('please enter Coupon Code');
		$('.CouponAvailableMsg').css("color", "red");
		$('.CouponAvailableMsg').slideDown();
	}
});

jQuery(document).on("click", ".removeCoupan", function () {
  $('#couponInputCode').val('');
  // $('#coupanDiscount').val('');
  // $('#coupanDiscountAmount').val('');
  $('#coupanId').val('')
  $('#couponCode').val('')
  // $('.coupanDiscountAmount').text('0.00')
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
  var consultation_fees = atob($("form[name='appointmentForm']").find('input[name=consultation_fees]').val());
  var service_charge = atob($("form[name='appointmentForm']").find('input[name=service_charge]').val());
  var total_fee = parseInt(consultation_fees)+parseInt(service_charge);
  @if($conType == "2")
	total_fee = parseInt(service_charge);
  @endif
  $("form[name='appointmentForm']").find('input[name=total_fee]').val(btoa(total_fee));
  $("form[name='appointmentForm']").find('.total_fee').text(total_fee.toFixed(2));
  $("form[name='appointmentForm']").find('input[name=coupon_discount]').val("");
  $("form[name='appointmentForm']").find('.coupon_discount').text('0.00');

});

</script>
@if($app_id != '')
<script type="text/javascript">
  $(document).ready(function() {
	  window.history.pushState(null, "", window.location.href);
	  window.onpopstate = function() {
		  window.history.pushState(null, "", window.location.href);
	  };
  });
</script>
@endif
@endsection