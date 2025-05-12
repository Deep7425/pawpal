@extends('layouts.Masters.Master')
@section('title', 'User Appointment | Health Gennie')
@section('description', "To schedule an appointment use book an appointment button on the left menu. You can also rebook an appointment with the same doctor.")
@section('content')

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="dashboard-wrapper user-appoint dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
		<?php $stype = 'doctor'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');} ?>
     
     <div class="container">  
     	<div class="container-inner appointment-data-div appointmetn-list">
      @if (Session::has('message'))
		<div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
	  @endif
		@if(isset($appointments))
		@if(count($appointments) > 0)
			@foreach($appointments as $app)
			<?php 
				if(!empty($app->user->doctorInfo)) {
					$cityName = $app->user->doctorInfo->getCityName->slug;
				}
			?>
			<div class="listing">
				@if(checkAppointmentIsElite($app->id) == 1)
					<div class="elite-member" title="Elite Member Appointment">
						<div class="bg">&nbsp;</div>
						<div class="text">Elite</div>
					</div>
				@elseif($app->type == '3')
					<div class="elite-member tele-ico" title="Tele Consultation Appointment">
						<div class="bg">&nbsp;</div>
						<div class="text"><img src="img/ico.png" alt="icon"  /></div>
					</div>
				@endif
                <div class="profile-detail">
					@if($app->user->id != '2219')
						<a href="{{ route('appointmentDetails',['aPiD' => base64_encode($app->id) ]) }}"><span class="text" style="display:none;">Doctor</span><h3>Dr. {{ucfirst(@$app->user->doctorInfo->first_name)}} {{@$app->user->doctorInfo->last_name}}</h3></a>
					@endif
						<ul>
							@if(!empty($app->user->doctorInfo->docSpeciality))<li><img src="img/doctor-ico.png" alt="icon"  />{{@$app->user->doctorInfo->docSpeciality->spacialies}}  ({{@$app->user->doctorInfo->docSpeciality->spaciality}})</li>@endif
						</ul>
						<?php 
							$order_by = "";
							if(isset($app->AppointmentOrder) && !empty($app->AppointmentOrder->meta_data)){
							$meta_data = json_decode($app->AppointmentOrder->meta_data);
							$order_by = $meta_data->order_by;
							}
						?>
						@if($app->followup_count > 0 && $app->visit_type != '6')<div class="DaysFollowup"><div class="DaysFollowup12">{{$app->followup_count}} days followup @if($app->followupDone == '1')<span class="AvailableDiv">Done.</span> @elseif($app->is_followup == '1')<span class="AvailableDiv">Available.<button class="btn-follow followUp" order_by="{{$order_by}}" p_id="{{$order_by}}" patient_id="{{$app->pId}}" doc_id="{{$app->user->id}}" app_id="{{$app->id}}" gender="{{$app->patient->gender}}" patient_name="{{$app->patient->first_name}} {{$app->patient->last_name}}" dob="{{$app->patient->dob}}" mobile_no="{{$app->patient->mobile_no}}">Book Now</button></span>@else <span class="ExpiredDiv">Expired.</span> @endif</div></div>
						@endif
					</div>
					<div class="date-wrapper">
					
					<p  class="date-highlight"><a @if($app->user->id != '2219') href="{{ route('appointmentDetails',['aPiD' => base64_encode($app->id) ]) }}" @endif ><span class="app-time-highlighted">@if(!empty($app->start)) {{date('j',strtotime($app->start))}} @endif</span><div class="schedule-date">@if(!empty($app->start)){{date('F , Y',strtotime($app->start))}}
					<strong>{{date('g:i A',strtotime($app->start))}}</strong>
					@endif</p></a></div>
					</div>
                    
                    
					
                    
                    <div class="rating_doctor-div">
					@if($app->user->id != '2219')
						<p>@if($app->status == '0') <span class="cancel-app">Cancelled</span> @elseif($app->appointment_confirmation == '0') <span class="pending-app">Pending</span> @elseif($app->status == '1' && $app->appointment_confirmation == '1') <span class="confirm-app">Confirmed</span> @endif<br>@if($app->visit_type == '6') <span class="FUpAppointment">Follow Up</span>@endif</p>
					@else
						<p><span class="pending-app">Pending <!--(Doctor assigning you shortly)--></span></p>
					@endif	
					</div>
					<?php
						$app_status = 0;
						if(!empty($app->start)) {
							if(strtotime($app->start) < strtotime(date("Y-m-d h:i A"))) {
								$app_status = 1;
							}
							else{
								$app_status = 0;
							}
						}
						else{
							$app_status = 0;
						}
					?>
					<div class="list-bottom btn-profile-wrapper">
						<div class="view-profile">
						
						@if($app->visit_status == '1')
							<a src="{{$app->prescription}}" href="javascript:void(0);" app_id="{{@$app->id}}" class="viewPDFPres btn btn-info downloadReceipt" ><i class="fa fa-paperclip" aria-hidden="true"></i>View prescription</a>
						@endif
						  @if($app->status != '0' && !empty($app->AppointmentTxn))
							<a href="{{ route('showAppointmentTxn',['aPiD' => base64_encode($app->id) ]) }}" class="btn btn-info" href="javascript:void(0);">Payment Details</a>
						  @endif
							@if($app_status == '0' && $app->status == '1' && $app->appointment_confirmation == '0')
							<a class="btn btn-warning cancelByPatientApp" user_id="{{$app->user->id}}" app_id="{{$app->id}}" href="javascript:void(0);">Cancel Appointment</a>
							<a class="btn btn-primary rescheduleAppByPatientApp" data_id="{{getDocIdByDoctor($app->user->id)}}" info_type="Doctors" search_type="1" href_old="javascript:void(0);"
							href="{{ route('changeAppointment',['docU_id' => base64_encode($app->user->id) ,'aPiD' => base64_encode($app->id) ]) }}"><span class="text" style="display:none;">Doctor</span>Reschedule Appointment</a>
							@else
							<!--<a class="btn btn-info" href="{{route('findDoctorLocalityByType',[$cityName,$stype,@getDoctorSulgById(getDocIdByDoctor(@$app->user->id))])}}"><span class="text" style="display:none;">Doctor</span>Rebook Appointment</a>-->
							@endif
							@if($app->user->id != '2219')
							<a href="{{ route('downloadReceipt',['aPiD' => base64_encode($app->id) ]) }}" class="downloadReceipt btn btn-info hideforPaytm" href="javascript:void(0);">Download Details</a>
							@endif
							<a href="{{ route('downloadApptReceipt',['aPiD' => base64_encode($app->id) ]) }}" class="btn btn-info" href="javascript:void(0);">Download Receipt</a>
							<!--<a href="{{ route('appointmentDetails',['aPiD' => base64_encode($app->id) ]) }}" class="btn btn-info">View Details</a>-->
						</div>
					</div>
			</div>
			@endforeach
		@else
			<div class="right-content no-result-found">
			   <img src="img/search-result.png" alt="icon"  />
			   <h2><!--<strong>We're Sorry!</strong>--><br/> You have no any past/future Appointment!</h2>
			   <p class="btn-book-appointment"><a search_type="1" data_id="0" info_type="doctor_all" class="dd searchDoctorModalDoctor" href="javascript:void(0);">Book Appointment<span class="text" style="display:none;">Doctor</span></a></p>
			</div>
		@endif
		@else
			<div class="right-content no-result-found">
			   <img src="img/search-result.png" alt="icon"  />
			   <h2><!--<strong>We're Sorry!</strong>--><br/> You have no any past/future Appointment!</h2>
			   <p class="btn-book-appointment"><a search_type="1" data_id="0" info_type="doctor_all" class="dd searchDoctorModalDoctor" href="javascript:void(0);">Book Appointment<span class="text" style="display:none;">Doctor</span></a></p>
			</div>
		@endif
		@if(isset($appointments) && !empty($appointments))
		<div class="pages-section">
		{{ $appointments->appends($_REQUEST)->links() }}
		</div>
		@endif
	</div>
	 </div>
     
     </div>
</div>
	<script>
		jQuery(document).on("click", ".followUp", function (e) {
			if(confirm("Are you sure?")) {
				var gender = $(this).attr('gender');
				var patient_name = $(this).attr('patient_name');
				var dob = $(this).attr('dob');
				var app_id = $(this).attr('app_id');
				var mobile_no = $(this).attr('mobile_no');
				var patient_id = $(this).attr('patient_id');
				var doc_id = $(this).attr('doc_id');
				var p_id = $(this).attr('p_id');
				var order_by = $(this).attr('order_by');
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('followupAppointment')!!}",
					data:{'appId':app_id,'patient_id':patient_id,'doc_id':doc_id,'gender':gender,'patient_name':patient_name,'dob':dob,'mobile_no':mobile_no,'p_id':p_id,'order_by':order_by},
					success: function(data) {
						if(data != 1){
							alert("Oops Something goes Wrong.");
						}
						location.reload();
						jQuery('.loading-all').hide();
					},
					error: function(error) {
						jQuery('.loading-all').hide();
						if(error.status == 401){
							alert("Session Expired,Please logged in..");
							location.reload();
						}
						else{
							alert("Oops Something goes Wrong.");
						}
					}
				});
			}
		});
		jQuery(document).on("click", ".cancelByPatientApp", function (e) {
			if(confirm("Are you sure want to cancel this appointment")) {
			var app_id = $(this).attr('app_id');
			var user_id = $(this).attr('user_id');
			jQuery('.loading-all').show();
			jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('cancelAppointment')!!}",
				data:{'appId':app_id,'userId':user_id},
				success: function(data) {
					if(data != 1){
						alert("Oops Something goes Wrong.");
					}
					location.reload();
					jQuery('.loading-all').hide();
				},
				error: function(error) {
					jQuery('.loading-all').hide();
					if(error.status == 401){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						alert("Oops Something goes Wrong.");
					}
				}
			});
			}
		});

		jQuery(document).on("click", ".show_doctor_info", function (e) {
			if($("#searchDocInfo").find("input[name='data_search']").val() == "") {
				if($("#search_data_by_search_id").val() != ""){
					$("#searchDocInfo").find("input[name='data_search']").val($("#search_data_by_search_id").val());
				}
				else{
					$("#searchDocInfo").find("input[name='data_search']").val($(this).find('.text').text());
				}
			}
			var search_type = $(this).attr('search_type');
			var info_type = $(this).attr('info_type');
			var data_info_id = $(this).attr('data_id');
			$("#searchDocInfo").find("input[name='search_type']").val(search_type);
			$("#searchDocInfo").find("input[name='info_type']").val(info_type);
			$("#searchDocInfo").find("input[name='id']").val(data_info_id);
			jQuery('.loading-all').show();
			setTimeout(function(){
				$("#searchDocInfo").submit();
			}, 500);
		});
		
		$('.viewPDFPres').click(function() {
			$('.loading-all').show();
			var app_id = $(this).attr('app_id');
			var pdfF = $(this).attr('src');
			$.ajax({
			type: "POST",
			dataType : "HTML",
			url: "{!! route('getNotePrintOfWeb') !!}",
			data:{'appointment_id':app_id},
			success: function(data) {
				if(data == "1") {
					var link = document.createElement('a');
					link.href = pdfF;
					link.download = "prescription.pdf";
					link.click();
				}
				else{
					$.alert('Prescription Not Found..');
				}
				jQuery('.loading-all').hide();	
			 }
			});
		});
	</script>

@endsection
