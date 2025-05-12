@extends('layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments')
@section('description', 'Book Appointment with doctors by Health Gennie. Order Medicine and lab from the comfort of your home. Read about health issues and get solutions.')
@section('content')
<style>
.modal.fade:not(.in).right .modal-dialog {
    -webkit-transform: translate3d(25%, 0, 0);
    transform: translate3d(25%, 0, 0);
}
</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="container wrapper-appoint">
<?php $wltSts = Session::get('wltSts');?>
    <div id="AppointmentWrapper" class="container-inner slot-details NewtestNew testCupanAppyle">
	<?php $user = Auth::user(); $is_subscribed = 0; $conType = base64_decode(app("request")->input("conType"));
	$wLtamnt=0;
	if(@$user->userDetails->wallet_amount){
		@$wLtamnt=$user->userDetails->wallet_amount;	
	}
	  
	?>
	@if(Auth::id() != null && checkUserSubcriptionStatus(Auth::id()))
	 <?php
		$userPlan = getUserPlan(Auth::id());
		$max_fee = "";
		if(!empty($userPlan->UserSubscribedPlans)) {
			if(!empty($userPlan->UserSubscribedPlans->meta_data)) {
				$plan_data = json_decode($userPlan->UserSubscribedPlans->meta_data);
				$max_fee = @$plan_data->max_appointment_fee;
			}
		}
		$is_subscribed = 1;
	 ?>
	 @endif
	@if(isset($doctor) && !empty($doctor))
		<?php
		  if($wltSts == 1 && $is_subscribed == 0){
			$isFree = 0;
		  }
		  
		  $tele_main_price = number_format(getSetting("tele_main_price")[0],2);
		  if($conType==1) {
			$consultation_fees = $doctor->oncall_fee;
			$convFee = getSetting("service_charge_rupee")[0];
			if(!empty($doctor->convenience_fee)){
				$convFee = $doctor->convenience_fee;
			}
			
			if($isDirect == '1') {
				if($isFree == '1') {
					$convFee = 0;
					$consultation_fees = getSetting("tele_main_price")[0];
				}
				else{
					$convFee = 0;
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				}
			}
			
			$total_fee = $consultation_fees + $convFee;
		  }
		  else {
			$consultation_fees = $doctor->consultation_fees;
			$convFee = getSetting("inclinic-service-charge")[0];
			if(!empty($doctor->convenience_fee)){
				$convFee = $doctor->convenience_fee;
			}
			$total_fee = $convFee;
		  }
		  
		  $docFee = $doctor->consultation_fees;
		  $wltAmt = Session::get('wltAmt');
		 if(Session::get('lanEmitraData') != null && $isDirect == '1') {
			 if($is_subscribed == 0){
			  $total_fee = 250;
			  $consultation_fees = 250;
			  $tele_main_price = 500;
			 }
			 else{
			  $total_fee = 500;
			  $consultation_fees = 500;
			  $tele_main_price = 500;
			 }
			 $isFree = 0;
		 }
		 if(Session::get('lanEmitraData') != null) {
			$plan = getPlanDetails(7);	
		 }
		 
		 else{
			$plan = getPlanDetails(11);
		 }
		?>
		 <input class="doc_id_app_book" type="hidden" value="{{base64_encode($doctor->id)}}"/>
		@if($isDirect == '1')
		 <div class="doctor-listtop doctor-listtop2 ap-section-new">
			@if(Session::get('lanEmitraData') != null)
			{!!getTermsBySLug('direct-appointment-emitra','en')!!}
			@else
			{!!getTermsBySLug('direct-appointment-app','en')!!}
			@endif	
			<div class="consult-fee-div"><span>Consultation Fee:</span>
				<p> ₹@if($isFree == '1')<strike class="total_fee">{{$tele_main_price}}
				/-</strike> FREE		
				@else <strike class="total_fee">{{$tele_main_price}}</strike>
				/- {{number_format($consultation_fees,2)}} /- @endif
				</p>
			</div>
		 </div>
		 @else
			  <div class="doctor-listtop doctor-listtop2">
				 <div class="doctor-listtop-img">
					<img src="@if(!empty($doctor->profile_pic)){{$doctor->profile_pic}}@else{{url('/img')}}/doc-img.png @endif" width="100" height="100"/>
				 </div>
				 <div class="doctor-listtop-content doctor-listtop-content2">
					<h2>Dr. {{@$doctor->first_name}} {{@$doctor->last_name}},<span> {{@$doctor->qualification}}</span></h2>
					<p>{{$doctor->content}}</p>
						@if(!empty($doctor->speciality))
							<!-- <span>{{getSpecialityName($doctor->speciality )}}</span>-->
						@endif
						@if($conType==1)
							<div class="consult-div">
								Tele-consultation Fees(₹) :<strong>{{$doctor->oncall_fee}}</strong>
							</div>
						@else
						@if($doctor->fees_show == '1')	
							<div class="consult-div">
								In-clinic Fees(₹) : <strong>@if($docFee == "0") FREE @else ₹{{$docFee}} @endif</strong>
							</div>
						@endif
						@endif
						<img src="{{ URL::asset('img/calendar-icon-img.png') }}" /> @if(app("request")->input("date") != "") {{ base64_decode(app("request")->input("date")) }} @endif &nbsp;
						<img src="{{ URL::asset('img/time-icon-img.png') }}" /> @if(app("request")->input("time") != "") {{ date ("h:i A",base64_decode(app("request")->input("time"))) }} @endif
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
		 @endif
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
					 <input type="hidden" name="isFree" value="{{base64_encode($isFree)}}" class="isFree" />
					 <input type="hidden" name="isDirect" value="{{base64_encode($isDirect)}}" class="isDirect" />
					 <input type="hidden" value="0" class="isFreeCoupon" />
					 <input type="hidden" name="walletDiscountAmount" value="0" class="walletDiscountAmount"/>
					</div>
					<div class="from-widget PatientDetails">
						<div class="ThisAppointment">
						<h2 class="block-title">This Appointment is for:<i class="required_star">*</i></h2>
						<div class="form-fields  form-field-mid pad-r0 specialization">
						  <select name="p_id" class="searchDropDown form-control AptBookFor">
							@foreach($users as $userDetail)
							<option value="{{ $userDetail->id }}" p_type="self" {{$userDetail->id == $user->id ? 'selected="selected"' : ''}} @if(!empty($userDetail->first_name)) fname="{{$userDetail->first_name}}" @else fname="" @endif @if(!empty($userDetail->last_name)) lname="{{$userDetail->last_name}}" @else lname=""  @endif @if(!empty($userDetail->dob)) dob="{{date('d-m-Y',$user->dob)}}"  @else dob="" @endif @if(!empty($user->gender)) gender="{{$user->gender}}" @else gender="Male" @endif parent_id="{{$userDetail->parent_id}}" @if(!empty($userDetail->other_mobile_no)) other_mobile_no="{{$userDetail->other_mobile_no}}" @else other_mobile_no="" @endif>@if(!empty($userDetail->first_name)) {{ $userDetail->first_name.' '.$userDetail->last_name }} @else Self @endif</option>
							@endforeach
							@if(checkEligibilityAppt($user->mobile_no,$isDirect))
							<option value='{{$user->id}}' parent_id="{{$user->id}}" other_mobile_no="" p_type="other" >Someone Else</option>
							@endif
						  </select>
	                      <span class="help-block"><label for="speciality" generated="true" class="error" style="display:none;">This field is required.</label></span>
						</div>
                            </div>
						<div class="ThisAppointment">
						<h2 class="block-title-second">Please Fill Patient Information:<i class="required_star">*</i></h2>
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
				</div>
				  <input type="hidden" name="is_subscribed" value='{{$is_subscribed}}'/>
					<div class="paymentDetails wrapper" style="display: none;">
                         
						 

						@if(Session::get('lanEmitraData') == null)
				
						<div class="ccModal cdDiv" role="button" aria-label="Apply Coupon"><img width="20" src="../img/discount-icon.png" />Apply Coupon</div>
								
						@endif
						
						<div class="removeDiv" style="display:none;"><span class="scd"></span>
							<p><span class="applyCouponText">Offer applied on the bill</span> You saved <strong>₹</strong><strong class="applyCouponAmount"></strong></p>
							<span class="rmvCd">Remove</span>
						</div>
						<div class="form-address-details CouponBox divForHide" @if($isFree == '1' && $isDirect == '1') style="display:none;" @endif>
						<div class="input-box">
							<!--<input type="text" placeholder="Enter Coupon" class="couponInput" id="couponInputCode" value="" />-->
							<input type="hidden" name="coupon_id" id="coupanId" value="">
							<input type="hidden" name="coupon_code" id="couponCode" value="">
							<input type="hidden" id="coupon_discount" name="coupon_discount" value="">
						
							
						  </div>
						</div>
						<div class="appointment-checkout-table">
							@if($isDirect == '1')
								<table>
									@if($doctor->fees_show == '1' && $conType == "2")
									<tr><td>Consultation Fee @if($conType == "2")<strong>(Pay at clinic)</strong>@endif : </td>
									<td> ₹ @if($isFree == '1')<span>{{number_format($consultation_fees,2)}}</span> /- @else <strike class="total_fee">{{$tele_main_price}}</strike> /- {{number_format($consultation_fees,2)}}/- @endif
									</td></tr>
									@elseif($conType == "1")
										<tr><td>Consultation Fee @if($conType == "2")<strong>(Pay at clinic)</strong>@endif : </td>
										<td> ₹ @if($isFree == '1')<span>{{number_format($consultation_fees,2)}}</span>
										/- @else <strike class="total_fee">{{$tele_main_price}}</strike>
										/- {{number_format($consultation_fees,2)}} /- @endif
										</td></tr>
									@endif	
									<tr><td>Convenience Fee : </td><td> ₹ {{number_format($convFee,2)}} /-</td></tr>
									<tr><td>Coupon : </td><td> ₹ <span class="coupon_discount">0.00</span> /-</td></tr>
									<tr><td>Total Pay : </td><td class="onlineFee"> ₹ @if($isFree == '0')<span class="total_fee">{{number_format($total_fee,2)}}</span> /- @else <strike class="total_fee">{{number_format($total_fee,2)}}</strike> /- FREE @endif</td></tr>
								</table>
							@else
								<table>
									@if($doctor->fees_show == '1' && $conType == "2")
										<tr><td>Consultation Fee @if($conType == "2")<strong>(Pay at clinic)</strong>@endif : </td>
										<td> ₹ {{number_format($consultation_fees,2)}} /-</td></tr>
									@elseif($conType == "1")
										<tr><td>Consultation Fee @if($conType == "2")<strong>(Pay at clinic)</strong>@endif : </td>
										<td class="consultaion_fee"> ₹ {{number_format($consultation_fees,2)}} /-</td></tr>
									@endif	
									<tr><td class="convenience_fee"> Convenience Fee : </td><td> ₹ {{number_format($convFee,2)}} /-</td></tr>
									<tr><td>Coupon : </td><td> ₹ <span class="coupon_discount">0.00</span> /-</td></tr>
									<tr><td>@if($wLtamnt) @if($conType==1)<input type="checkbox" class="healthgennie_cash" name="healthgennie_cash" value='50'/> @else <input type="checkbox" class="healthgennie_cash" name="healthgennie_cash" value='in_clinic'/> @endif   @endif Health Gennie Cash : </td><td> ₹ <span id="walletDiscountAmount">@if($wLtamnt) {{$wLtamnt}} @else 0.00 @endif</span> /-</td></tr>
									<tr><td class="walletMessage"></td></tr>
									<tr><td>Total Pay : </td>
									<td> ₹ <span class="total_fee final_total_fee">{{number_format($total_fee,2)}}</span> /-</td></tr>
									
								</table>
							@endif			
						</div>
						<input type="hidden" name="total_fee" value='{{base64_encode($total_fee)}}'/>
						<input type="hidden" id="service_charge" name="service_charge" value='{{base64_encode($convFee)}}'/>
						<input type="hidden" id="consultation_fees" name="consultation_fees" value="{{base64_encode($consultation_fees)}}"/>
						@if($conType==1)
						<div class="SingleOnline box SingleOnline12">
							<input type="radio" name="is_pln" class="apL" id="value-1" checked value="0" />
							<label for="value-1" class="value-1">
								<div class="select-dots"></div>
								@if($isDirect == '1' && $isFree == '1')
									<div class="text"><strong>Single Online Consultation <span><strike class="total_fee">₹ {{number_format($total_fee,2)}}</strike> /- FREE</span> </strong></div>
								@else
									<div class="text"><strong>Single Online Consultation <span>₹<i class="total_fee plnCs">{{number_format($total_fee,2)}}</i> /-</span> </strong></div>
								@endif
							</label>
						</div>
						@if(!empty($plan))
						<div class="SingleOnline box">
							<input type="radio" name="is_pln" class="apL" id="value-2" value="{{$plan->id}}"/>
							<label for="value-2" class="value-2"><div class="select-dots"></div>
								<div class="text"><strong>{{$plan->plan_title}}<div class="actual-price-wrapper"><span><strike>₹{{number_format($plan->price,2)}}</strike> ₹{{number_format($plan->price - $plan->discount_price,2)}} /-</span></div></strong></div>
								<div class="plan-content">{!!$plan->content!!}</div>
							</label>
						</div>
						@endif
						@endif
						<div class="appointment-tandc-div TandcDivClasss">
							<h2>Terms & Conditions</h2>
							@if($conType == "1")
								{!!getTermsBySLug('terms-conditions-tele-appointment')!!}
							@else
								{!!getTermsBySLug('term-conditions-appointment')!!}
							@endif
						</div>
					</div>
					@if($wltSts == 1) 
					
						<div class="SingleOnline box payoptions wallet-wrapper left" style="display: none;">
									<div class="paytm"><input type="radio" name="PaymentVia" class="orderByPaytmcls" id="value-3" value="1" checked="checked"/>
										<label for="value-3" class="value-3">
										<div class="select-dots"></div>
										Via Paytm
									</label>
										
										</div>
									
									<div class="wallet"><input type="radio" name="PaymentVia" class="orderByPaytmcls"  id="value-4"  value="2"/>
										<label for="value-4" class="value-4">
										<div class="select-dots"></div>
										Via Help India Wallet
									</label>
										
										</div>
									</div>
					
					@endif
					<div class="from-widget-btn">
						<button type="button" class="btn btn-default backButton" style="display:none;">Back</button>
						
                        <button type="submit" class="btn btn-default subbtn" next="1" isFree="{{$isFree}}" isDirect="{{$isDirect}}" @if(Session::get('lanEmitraData') != null) data-checkType ='1' @else data-checkType ='0' @endif>Continue</button>
                        
						<a class="btn btn-default conBtn" id="bpn" href='{{route("choosePlan",["id" => base64_encode($plan->id),"tp"=>base64_encode(1)])}}' style="display:none;" @if(Session::get('lanEmitraData') != null) data-checkType ='1' @else data-checkType ='0' @endif>Continue</a>
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
<div class="modal fade right" id="ccModal" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
  <div class="modal-header" data-dismiss="modal"><i class="fa fa-times"></i></div>
  <div class="ClassInput1234">
  <div class="ClassInput">
		<input class="ClassInput12 form-control" type="text" name="" placeholder="Enter coupon code" maxlength="50">
		<div class="ApplyBtn"><a class="ApplyBtn123">APPLY</a></div><label class="ArrowClass" style="display:none;">Coupon code is required</label>
	</div>
	</div>
  <div class="modal-content">
    <div class="modal-body">
	<div class="termConditions">
		@forelse(getCouponCodes(2) as $raw)
		<div class="coupon_code">{{$raw['coupon_code']}}</div>
		<div class="codeApplyClass"><button class="btn btn-info codeApply" cde_="{{$raw['coupon_code']}}" codeData="{{json_encode($raw)}}">Apply</button></div>
		<div class="ts">
		{!!$raw['term_conditions']!!}
		</div>
		@empty
			<div class="notFound"><img src="../img/coupon-icon.png" /> Coupons Not Available
				<p>Offers will be available soon.</p>
			</div>
		@endforelse
	</div>
  </div>
</div>
</div>
</div>


@if($wltSts == 1) 
<form action="https://www.b2me.co.in/PaywithHIO.aspx" method="POST" style="display:none;" id="helpIndFrm">
<input type="hidden" name="merchantId" value="B2ME004"/>
<input type="hidden" name="merchantIdKey" value="s78erc5c8x9s"/>
<input type="hidden" name="merchantTxnId" value=""/>
<input type="hidden" name="orderAmount" value="{{$total_fee}}"/>
<input type="hidden" name="returnUrl" value="https://www.healthgennie.com/helpIndPay"/>
<button type="submit" >Submit</button>
</form>
@endif
<script>
$(document).ready(function(){
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
jQuery(document).on("change", ".apL", function () {
	if($(this).val() != '0'){
		$('.subbtn').hide();
		$('.subbtnwlt').hide();
		$('#bpn').show();
		$('.payoptions').hide();
	}
	else{
		$('.subbtn').show();
		$('.subbtnwlt').show();
		$('#bpn').hide();
	}
});
jQuery(document).on("click", ".rmvCd", function () {
	$(".removeDiv").hide();
	$(".cdDiv").show();
	removeCode();
});
jQuery(document).on("click", ".ApplyBtn123", function () {
	if(actionPerform($(".ClassInput12").val())){
		ApplyCoupon($(".ClassInput12").val());	
	}
});
jQuery(document).on("keyup", ".ClassInput12", function () {
	actionPerform($(this).val());
});
function actionPerform($val){
	var flag = false;
	if($val) {
		var flag = true;
		$(".ArrowClass").hide();
	}
	else{
		$(".ArrowClass").text('Coupon code is required.');
		$(".ArrowClass").show();
		$(".ClassInput12").focus();
	}
	return flag;
}
jQuery(document).on("click", ".codeApply", function () {
	var cde_ = $(this).attr("cde_");
	codeData = $(this).attr("codeData");
	codeData = JSON.parse(codeData);
	verifyCode(codeData);
	// codeAfterApplied(cde_);
});
function codeAfterApplied(cde_){
$(".cdDiv").hide();
$(".removeDiv").find(".scd").text(cde_);
$(".removeDiv").show();
$("#ccModal").modal("hide");
$(".ClassInput12").val('');
}
jQuery(document).on("click", ".cdDiv", function () {
$("#ccModal").modal("show");
});

jQuery(document).on("click", ".walletDiv", function () {
$("#walletmodal").modal("show");
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
		$('.subbtn').text('Continue');
		$('.backButton').hide();
		$('.subbtnwlt').hide();
		$('.subbtn').show();
		$('#bpn').hide();
		$('.payoptions').hide();
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
				if($('.subbtn').attr('isFree') == '1' && $('.subbtn').attr('isDirect') == '1') {
					$('.subbtn').text('Confirm');
					$('.payoptions').hide();
				}
				else{
					$('.payoptions').show();
					$('.subbtn').text('Pay Now');
				}
				$(".subbtnwlt").show();
				$('.backButton').show();
				flag = false;
			}
			if(flag == true) {
				let goforsubmit= 0;
	   let checkType = $("form[name='appointmentForm']").find(':submit').attr('data-checktype');
	   if(checkType == 1){
			if(confirm("Are you sure you want to go for payment?")){
			    console.log('yes');
                goforsubmit = 1;
			}
			
	   }else{
           goforsubmit = 1;
	   }
	   
	  if(goforsubmit == 1){
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
				return false;
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
					}else if(result.status == '7') {
						jQuery('.loading-all').hide();
						jQuery('.subbtn').attr('disabled',false);
						if($("input[name=PaymentVia]:checked").val() == 1){
                            var url = '{!! url("/appointmentCheckout?tid='+result.tid+'&order_id='+result.order_id+'&amount='+result.amount+'&merchant_param1='+result.merchant_param1+'&order_by='+result.order_by+'") !!}';
						window.location = url;
						}else{
                            $("#helpIndFrm").find("input[name='merchantTxnId']").val(atob(result.order_id));
							$("#helpIndFrm").find("input[name='orderAmount']").val(atob(result.amount));
							$("#helpIndFrm").submit();
						}
						
					}
					else if(result.status == '8') {
						jQuery('.loading-all').hide();
						jQuery('.subbtn').attr('disabled',false);
						alert(result.msg);
						// window.location = result.return_url;
						if(result.tracking_id){
							window.location = 'https://www.healthgennie.com/appointment/success?order_id='+btoa(result.tracking_id);
						}
						else if(result.app_order_id){
							window.location = 'https://www.healthgennie.com/appointment/success?app_order_id='+btoa(result.app_order_id);
						}
						else{
							window.location = 'https://www.healthgennie.com/appointment/success';
						}
					}else if(result.status == '2') {
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
					   else if(result.status == '5') {
						var url = '{!! url("/appointment/success") !!}';
						window.location = url;
					  }
					  else if(result.status == '6') {
						jQuery('.loading-all').hide();
						jQuery('.subbtn').attr('disabled',false);
						$.alert('Your first appointment has been processed.');
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
			  /* */
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
var ajaxReq; 
function ApplyCoupon(couponcode) {
  var consultation_fees = $("form[name='appointmentForm']").find('input[name=consultation_fees]').val();
  var onCallStatus = $("form[name='appointmentForm']").find('input[name=onCallStatus]').val();
  var order_by = $("form[name='appointmentForm']").find('input[name=order_by]').val();
  var isDirect = $("form[name='appointmentForm']").find('input[name=isDirect]').val();
  if(ajaxReq) {
	ajaxReq.abort();
  }
 ajaxReq = jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyCoupon') !!}",
  data: {'couponcode':couponcode,'consultation_fees':consultation_fees,'onCallStatus':onCallStatus,'order_by':order_by,'isDirect':isDirect},
  success: function(data){
	  if(data.coupon_code) {
		  verifyCode(data);
	  }
	  else if(data.status == '0'){
		$(".ArrowClass").text(data.msg);
		$(".ArrowClass").show();
	  }
    },
    error: function(error){
      if(error.status == 401){
          location.reload();
      }
      else{
        jQuery('.loading-all').hide();
      }
    }
  });
}

function verifyCode(data){
  var consultation_fees = $("form[name='appointmentForm']").find('input[name=consultation_fees]').val();
  var service_charge = $("form[name='appointmentForm']").find('input[name=service_charge]').val();
  var total_fee = $("form[name='appointmentForm']").find('input[name=total_fee]').val();

   console.log("==========0-90-9",total_fee);

  var coupanDiscountAmount = 0;
  if(data.apply_type == '2'){
	  coupanDiscountAmount = atob(service_charge) * data.coupon_rate / 100;
  }
  else{
	coupanDiscountAmount = atob(consultation_fees) * data.coupon_rate / 100;  
  }
  total_fee = atob(total_fee) - coupanDiscountAmount;
  $("form[name='appointmentForm']").find('input[name=total_fee]').val(btoa(total_fee));  
  $("form[name='appointmentForm']").find('.total_fee').text(total_fee.toFixed(2));
  if(total_fee == '0') {
	  $("form[name='appointmentForm']").find('.isFreeCoupon').val(1);
	  $("form[name='appointmentForm']").find('.onlineFee').html('₹ <strike class="total_fee">'+total_fee.toFixed(2)+'</strike> /- FREE');
	  $('.subbtn').text('Confirm');
	  $("form[name='appointmentForm']").find('input[name=isFree]').val(btoa(1));
  }
  
  $("form[name='appointmentForm']").find('input[name=coupon_id]').val(data.coupon_id);
  $("form[name='appointmentForm']").find('input[name=coupon_code]').val(data.coupon_code);
  $("form[name='appointmentForm']").find('input[name=coupon_discount]').val(btoa(coupanDiscountAmount));
  $("form[name='appointmentForm']").find('.coupon_discount').text(coupanDiscountAmount.toFixed(2));
  $('.removeDiv').find('.applyCouponAmount').text(coupanDiscountAmount);
  codeAfterApplied(data.coupon_code);
}

function removeCode() {
  $('#couponInputCode').val('');
  $('#coupanId').val('')
  $('#couponCode').val('')
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
  var consultation_fees = atob($("form[name='appointmentForm']").find('input[name=consultation_fees]').val());
  var service_charge = atob($("form[name='appointmentForm']").find('input[name=service_charge]').val());
  var total_fee = parseInt(consultation_fees)+parseInt(service_charge);
  @if($conType == "2")
	total_fee = parseInt(service_charge);
  @endif
  if($("form[name='appointmentForm']").find('.isFreeCoupon').val() == 1) {
	$("form[name='appointmentForm']").find('.onlineFee').html('₹ <span class="total_fee"></span> /-');
	$('.subbtn').text('Pay Now');
	$("form[name='appointmentForm']").find('input[name=isFree]').val(btoa(0));
  }
  $("form[name='appointmentForm']").find('input[name=total_fee]').val(btoa(total_fee));
  $("form[name='appointmentForm']").find('.total_fee').text(total_fee.toFixed(2));
  $("form[name='appointmentForm']").find('input[name=coupon_discount]').val("");
  $("form[name='appointmentForm']").find('.coupon_discount').text('0.00');
}
$('input[type="checkbox"]').on('change', function() {
  if($(this).is(":checked")) {

	if($(this).val()=='in_clinic'){

		alert("Wallet discount is applicable for In Clinc Appointment");
       return false;
	}
	
   var wallet_amount='{{$wLtamnt}}';
   ajaxReq = jQuery.ajax({
   type: "POST",
   dataType : "JSON",
   url: "{!! route('applyWalletAmt') !!}",
   data: {'type':1,'wallet_amount':wallet_amount},
   success: function(data){
	  if(data.success){
		  //verifyCode(data);
		  var total_fee=$('.final_total_fee').text();
		  var final_amount=total_fee-data.success.availAmount;
		  $('.final_total_fee').text(final_amount);
		  $('.plnCs').text(final_amount);
		  $('.walletMessage').html('<b style="color:green">Health Gennie Cash &#x20b9;'+data.success.availAmount+' Applied On This Order</b>');
		  $('.walletDiscountAmount').val(data.success.availAmount);
	  }
	  else if(data.status == '0'){
		$(".ArrowClass").text(data.msg);
		$(".ArrowClass").show();
	  }
    },
    error: function(error){
      if(error.status == 401){
          location.reload();
      }
      else{
        jQuery('.loading-all').hide();
      }
    }
  });
    }else{
          var coupon_discount=$('#coupon_discount').val();
		var consultaion_fee = $("#consultation_fees").val();
		var convenience_fee = $("#service_charge").val();
	     if(atob(coupon_discount)){
			var total=parseInt(atob(consultaion_fee)) + parseInt(atob(convenience_fee))-parseInt(atob(coupon_discount));
		 }else{
			var total=parseInt(atob(consultaion_fee)) + parseInt(atob(convenience_fee));
		 }
		  
		  $('.final_total_fee').text(total);
		  $('.plnCs').text(total);
		  $('.walletDiscountAmount').val(0);
		  $('.walletMessage').html('').delay("slow").fadeIn();;
	}
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