@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
<div class="container">
    <div class="container-inner">
    @if(isset($appointment) && !empty($appointment))
		<div class="appoint-confirm-wrapper no-bg confirmation_div" style="@if($appointment->appointment_confirmation == '0' && $appointment->status == '1') display:block; @else  display:none @endif">
			<h1>Appointment Confirmation</h1>
			<div class="text-wrapper">
			<p>Patient appointment ID : {{@$appointment->id}}</p>
			<p>We shall send you email and sms with details after your confirmation.</p>
			@if(!empty($appointment->start))<p>Appointment time <b> {{date('d-m-Y',strtotime(@$appointment->start))}} At {{date('g:i A',strtotime(@$appointment->start))}}</b></p>@endif
			</div>
			<div class="patient-name">
				<label>Patient Name :</label>
				<h3>{{ucfirst(@$appointment->Patient->first_name)}} {{@$appointment->Patient->last_name}}</h3>
			</div>
			@if(!empty(@$appointment->Patient->gender))
			<div class="patient-name">
				<label>Gender :</label>
				<h3>{{@$appointment->Patient->gender}}</h3>
			</div>
			@endif
			@if(!empty(@$appointment->Patient->email))
			<div class="patient-name">
				<label>Email :</label>
				<h3>{{@$appointment->Patient->email}}</h3>
			</div>
			@endif
			
			<div class="patient-name">
				<label>Mobile :</label>
				<h3>{{@$appointment->Patient->mobile_no}}</h3>
			</div>
			<div class="buttons-wrapper">
				<button type="button" class="accept_appointment" appId="{{base64_encode(@$appointment->id)}}" >Accept</button>
				<button type="button" class="cancel_appointment" appId="{{base64_encode(@$appointment->id)}}">Decline</button>
			</div>
		</div>
		<div class="appoint-confirm-wrapper confirmed_div" style="@if($appointment->appointment_confirmation == '1') display:block; @else display:none @endif">
			<h1>Appointment Confirmed</h1>
			<div class="text-wrapper">
				<p>Dear <a href="javascript:void(0);">Dr. {{ucfirst(@$appointment->User->DoctorInfo->first_name)}} {{@$appointment->User->DoctorInfo->last_name}}</a> Appointment of {{ucfirst(@$appointment->Patient->first_name)}} {{@$appointment->Patient->last_name}} ({{@$appointment->Patient->PatientRagistrationNumbers->reg_no}}) with you on  @if(!empty($appointment->start)){{date('d-m-Y',strtotime(@$appointment->start))}} at {{date('g:i A',strtotime(@$appointment->start))}}@endif has been confirmed.
				</p>
			</div>    
		</div>
		<div class="decline-wrapper cancelled_div" style="@if($appointment->status == '0') display:block; @else display:none @endif">
		@if($appointment->cancel_reason == "cancelbypatient")
			<h1>Appointment has been cancelled by user {{ucfirst(@$appointment->Patient->first_name)}} {{@$appointment->Patient->last_name}}</h1>
			<div class="text-wrapper">
				 <p>Dear <a href="javascript:void(0);">Dr. {{ucfirst(@$appointment->User->DoctorInfo->first_name)}} {{@$appointment->User->DoctorInfo->last_name}}</a> Appointment of {{ucfirst(@$appointment->Patient->first_name)}} {{@$appointment->Patient->last_name}} ({{@$appointment->Patient->PatientRagistrationNumbers->reg_no}}) with you on  @if(!empty($appointment->start)){{date('d-m-Y',strtotime(@$appointment->start))}} At {{date('g:i A',strtotime(@$appointment->start))}}@endif has been cancelled.
				</p>
			</div>
		@else
			<h1>Appointment has been cancelled by Dr. {{ucfirst(@$appointment->User->DoctorInfo->first_name)}} {{@$appointment->User->DoctorInfo->last_name}}</h1>
			<div class="text-wrapper">
				 <p>Dear <a href="javascript:void(0);">Dr. {{ucfirst(@$appointment->User->DoctorInfo->first_name)}} {{@$appointment->User->DoctorInfo->last_name}}</a> Appointment of {{ucfirst(@$appointment->Patient->first_name)}} {{@$appointment->Patient->last_name}} ({{@$appointment->Patient->PatientRagistrationNumbers->reg_no}}) with you on  @if(!empty($appointment->start)){{date('d-m-Y',strtotime(@$appointment->start))}} At {{date('g:i A',strtotime(@$appointment->start))}}@endif has been cancelled.
				</p>
			</div>
		@endif
		</div>
	@else
		<div class="appoint-confirm-wrapper no-bg confirmation_div">
			<h1>Appointment Not Found</h1>
			<div class="text-wrapper">
				 <p>Dear doctor you have no appointment today.</p>
			</div>
		</div>
	@endif
	
	</div>    
</div>
	<script>
	jQuery(document).ready(function () { 
		jQuery(document).on("click", ".accept_appointment", function () {
			if(confirm('Are you sure want to confirm this appointment.')) {
				var app_id  = $(this).attr('appId');
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('appointmentConfirm')!!}",
					data:{'id':app_id},
					success: function(data) {
						if(data == 1){
							$(".confirmation_div").slideUp("slow");
							$(".confirmed_div").slideDown("slow");
							// setTimeout(function () {
							// }, 500);
							// alert('Thanks for your confirmation.');
						}	
						else if(data == 2) {
							alert('Appointment Already Confirmed.');
						}
						else if(data == 3) {
							alert('Appointment Not Found.');
						}
						else if(data == 4) {
							alert('Appointment Already Cancel.');
						}
					  jQuery('.loading-all').hide();
					},
					error: function(error) {
						jQuery('.loading-all').hide();
						if(error.status == 401 || error.status == 419){
							//alert("Session Expired,Please logged in..");
							location.reload();
						}
						else{
							//alert("Oops Something goes Wrong.");
						}
					}
				});
			}
		});
		
		jQuery(document).on("click", ".cancel_appointment", function () {
			if(confirm('Are you sure want to cancel this appointment.')) {
				var app_id  = $(this).attr('appId');
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('appointmentCancel')!!}",
					data:{'id':app_id},
					success: function(data) {
						if(data == 1){
							$(".confirmation_div").slideUp("slow");
							$(".cancelled_div").slideDown("slow");
							// alert('Thanks for your confirmation.');
						}	
						else if(data == 2) { 
							alert('Appointment Already Cancel.');
						}
						else if(data == 3) { 
							alert('Appointment Not Found.');
						}
						else if(data == 4) { 
							alert('Appointment Already Confirmed.');
						}
					  jQuery('.loading-all').hide();
					},
					error: function(error) {
						jQuery('.loading-all').hide();
						if(error.status == 401 || error.status == 419){
							//alert("Session Expired,Please logged in..");
							location.reload();
						}
						else{
							//alert("Oops Something goes Wrong.");
						}
					}
				});
			}
		});
	});
	
	</script>
@endsection