@extends('amp.layouts.Masters.Master')
@section('title', 'Reschedule User Appointment | Health Gennie')
@section('description', "Reschedule Appointment with doctors by Health Gennie. Order Medicine and lab from the comfort of your home. Read about health issues and get solutions.")
@section('content') 
<div class="container">

    <div id="RescheduleWreppar" class="container-inner slot-details">
    @if(isset($doctor) && !empty($doctor))
		<?php $appTImeSlot =  strtotime(date('h:i A',strtotime($appointment->start))); ?>
		<input type="hidden" class="appTImeSlot" readonly value="{{$appTImeSlot}}" />
		<input type="hidden" class="appTInM" readonly value="{{$appointment->time}}" />
		 <input class="doc_id_app_book" type="hidden" value="{{base64_encode($doctor->id)}}"/>
		 <div class="doctor-listtop doctor-listtop2">
			 <div class="doctor-listtop-img">
				<img src="@if(!empty($doctor->profile_pic)){{$doctor->profile_pic}}@else{{url('/img')}}/doc-img.png @endif" width="100" height="100"/>
			 </div>
			 <div class="profile-detail auto doctor-profile">
             	<div class="doctor-listtop-content doctor-listtop-content2">
					<h2>Dr. {{@$doctor->first_name}} {{@$doctor->last_name}},&nbsp; {{@$doctor->qualification}}</h2>
					<p>
					  {{$doctor->content}}</p>
					  @if(!empty($doctor->speciality))
						<span class="pesehwar">{{getSpecialityName($doctor->speciality )}}</span>
					  @endif
			</div>
			 	<div class="doctor-listtop doctor-listtop3">
				@if(!empty($doctor->clinic_name))
					<p><strong>Clinic</strong> : <span>{{@$doctor->clinic_name}}</span></p>	
					<span class="address-doc">
						{{@$doctor->address_1}}
						@if(!empty($doctor->getCityName)) {{@$doctor->getCityName->name}}, {{@$doctor->getStateName->name}} @endif
						<div class="location"><a @if(!empty($doctor->lat) && !empty($doctor->lng)) onClick="window.open('https://maps.google.com/?q={{@$doctor->lat}},{{@$doctor->lng}}');" @elseif(!empty($doctor->address_1)) onClick="window.open('https://maps.apple.com/maps?q={{$doctor->address_1}}')" @else onClick="NoShowMap();"  @endif href="javascript:void(0);">( Get Direction )</a></div>
					</span>
					
				@endif
			</div>
             </div>
		 </div>
<div class="tab-title">
			  <h2>Reschedule Your Appointment</h2>
			   <div class="date-formet-top">
				  <div class="date-formet-block">
				  <label>Appointment Date</label>
				  <div class="date-formet-section">
					<input type="text" class="widget_appointCalender form-control" name="widget_appoint_date" readonly  placeholder="yyyy-mm-dd" autocomplete="off" />
					<i class="fa fa-calendar widget_appoint_cal" aria-hidden="true"></i>
				  </div>
				  </div>
				</div>
				</div>
		<div class="from-widget-top reschedule-app-page">
				@if(isset($doctor) && !empty($doctor) && !(empty($doctor->opd_timings)))
				  <ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" class="tab_class_time_slot" id="1" ><img width="50" height="50" src="img/morning.png" />Morning</a></li>
					<li><a data-toggle="tab" id="2" class="tab_class_time_slot"><img width="50" height="50" src="img/afternoon.png" />Afternoon</a></li>
					<li><a data-toggle="tab" id="3" class="tab_class_time_slot"><img width="50" height="50" src="img/evening.png" />Evening</a></li>
				  </ul>
				  <div class="slotsMainDiv tab-content"> 
					<?php 
					  $mrngTime= [];
					  $afterTime= [];
					  $eveTime= [];
					  $totSlots = [];
					 // echo "<pre>"; print_r($doctor);die;
					  if(!empty($doctor->slot_duration)){
						  $duration = $doctor->slot_duration * 60;
					  }
					  else{
						  $duration = 300;
					  }
					  
					  foreach(json_decode($doctor->opd_timings) as $key=>$schedule){
						  if(!empty($schedule->days)){
								if(in_array(date('w'),$schedule->days)) {
								foreach($schedule->timings as $k=>$v){
									  //$duration
									  $startTime = strtotime($v->start_time);
									  while($startTime <= strtotime($v->end_time)) {
										 // $totSlots[] = date ("H:i", $startTime);
										  $totSlots[] = $startTime;
										  $startTime += $duration;
									   } 
								  }
								}
						  }
					  }
					  foreach($totSlots as $k=>$val){
						  //print_r($totSlots); 
						  if($val < strtotime('12:00') && $val > strtotime(date("h:i A"))){
							$mrngTime[] = $val;
						  }
						  if((($val >= strtotime('12:00')) && ($val < strtotime('16:00'))) && $val > strtotime(date("h:i A"))){
							$afterTime[] = $val;
						  }
						  if((($val >= strtotime('16:00')) && ($val < strtotime('22:00'))) && $val > strtotime(date("h:i A"))){
							$eveTime[] = $val;
						  }
					  }
					?>
					<div class="widget-time-slot tab-pane fade in active" id="docMorning_time_slot">
						<table class="table">
							
							<tbody>
								<tr>
							 @if(count($mrngTime) > 0) 	
							 @foreach($mrngTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)))
								   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="javascript:void(0);" class="chooseSlot" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td> 
								@endif
							  @endforeach
							  @else
								  <p class="no-found-slot-app">No available slots</p>
							  @endif 	  
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-time-slot tab-pane fade" id="docAfternoon_time_slot">
					  <table class="table">
						
						<tbody>
						  <tr>
							@if(count($afterTime) > 0) 	
							  @foreach($afterTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)))
								   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="javascript:void(0);" class="chooseSlot" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td> 
								@endif
							  @endforeach
							  @else
								  <p class="no-found-slot-app">No available slots</p>
							  @endif 
						  </tr>
						</tbody>
					  </table>
					</div>
					<div class="widget-time-slot tab-pane fade" id="docEvening_time_slot">
					  <table class="table">
						
						<tbody>
						  <tr>
						  @if(count($eveTime) > 0) 	
							@foreach($eveTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)))
								   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="javascript:void(0);" class="chooseSlot" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td> 
								@endif
							  @endforeach
							  @else
								  <p class="no-found-slot-app">No available slots</p>
							  @endif 
						  </tr>
						</tbody>
					  </table>
					</div>
				  </div>
			  @else
				  <div class="slotsMainDiv"> 
						<p class="no-found-slot-app">No available slots for this day</p>
				  </div>
			  @endif
			  {!! Form::open(array('route' => 'changeAppointmentSlot','method' => 'POST', 'id' => 'reschedule-app-form')) !!}
				<input type="hidden" name="doc_id" value="{{@$doctor->id}}"/>
				<input type="hidden" name="app_id" value="{{@$appointment->id}}"/>
				<input type="hidden" name="date" />
				<input type="hidden" name="time" />
				<div class="appointment-reschedule-btn-div app_confirm_div" style="display:none;"><button type="submit" class="btn btn-info form-control app_confirm_btn">Confirm Appointment</button></div>
				{!! Form::close() !!}
	</div>
	@endif
	</div>  

	<div id="LabBlankDivLoader" style="display:none;">
		<div class="LabBlankDivLoader labDiv_1">
			          <div class="blankdivSlot" >
    <table class="table blankdivTable">
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
		</div>
      </div>	
</div>
<script>
$(document).ready(function() {
	loadTImeSlot();
	$(".widget_appointCalender" ).datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: 0,
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateText, inst) {
			//jQuery('.loading-all').show();
			$(".reschedule-app-page").find(".app_confirm_div").hide();
			$(".slotsMainDiv").html($("#LabBlankDivLoader").html());
			$(".slotsMainDiv").find("#LabBlankDivLoader").show();
			if(dateText== '{{date("Y-m-d",strtotime($appointment->start))}}') {
				loadTImeSlot();
			}
			else {
			 $.ajax({
				type: "GET",
				url: "{!! route('doctor.loadSlots') !!}",
				data: "date="+dateText+'&doctor={{$doctor->id}}',
				success: function(result) {  
					$(".slotsMainDiv" ).html(result);
					$(".slotsMainDiv").find("#LabBlankDivLoader").hide();
					jQuery('.loading-all').hide();
					$('.tab_class_time_slot').each(function() {
						$(this).closest("li").removeClass("active");
						if($(this).attr('id') == 1) {
							$(this).closest("li").addClass("active");
						}
					});
				   $(result).find("#docMorning_time_slot").addClass('in active');
				   $(result).find("#docAfternoon_time_slot").removeClass('in active');
				   $(result).find("#docEvening_time_slot").removeClass('in active');	
				}
			  });
			}			  
		}
	});
	$('.widget_appointCalender').datepicker('setDate', '{{date("Y-m-d",strtotime($appointment->start))}}');
	var appTInM =  $(".appTInM").val();
	function loadTImeSlot() {
		$(".slotsMainDiv").html($("#LabBlankDivLoader").html());
		$(".slotsMainDiv").find("#LabBlankDivLoader").show();
		$.ajax({
			type: 'GET',
			url: "{!! route('doctor.loadSlots') !!}",
			data: 'date={{date("Y-m-d",strtotime($appointment->start))}}&doctor={{$doctor->id}}',
			success: function(result) {  
				$( ".slotsMainDiv" ).html(result);
				jQuery('.loading-all').hide();
				$(".slotsMainDiv").find("#LabBlankDivLoader").hide();
				$('.tab_class_time_slot').each(function() {
					$(this).closest("li").removeClass("active");
					if($(this).attr('id') == appTInM) {
						$(this).closest("li").addClass("active");
					}
				});
				$(".slotsMainDiv").find("#docMorning_time_slot").removeClass('in active');
				$(".slotsMainDiv").find("#docAfternoon_time_slot").removeClass('in active');
				$(".slotsMainDiv").find("#docEvening_time_slot").removeClass('in active'); 
				
				$(".slotsMainDiv").find(".widget-time-slot").each(function() {
					if(appTInM == 1) {
						$(".slotsMainDiv").find("#docMorning_time_slot").addClass('in active');
					}
					else if(appTInM == 2) { 
						$(".slotsMainDiv").find("#docAfternoon_time_slot").addClass('in active');
					}
					else if(appTInM == 3) {
						$(".slotsMainDiv").find("#docEvening_time_slot").addClass('in active');
					}
				});
				loadTimeSet();
			}
		});
	}
});

	function loadTimeSet(){
		var appTImeSlot = $(".appTImeSlot").val();
		$(".slotsMainDiv .active").find("td").each(function() {
			if(appTImeSlot == $(this).find("a").attr("slot")) {
				$(this).find("a").addClass("todayAppTime");
			}
		});
	}
	jQuery(document).on("click", ".chooseSlot", function (e) {
		$(".reschedule-app-page").find(".app_confirm_div").show();
		$(".reschedule-app-page").find(".todayAppTime").addClass("chooseSlot");
		$(".reschedule-app-page").find(".chooseSlot").each(function (e) {
			//if($(this).attr("slot")) {
				$(this).removeClass("hidentop");
				$(this).removeClass("todayAppTime");
			//}
		});
		$(this).addClass("todayAppTime");
		var slot_time = $(this).attr("slot");
		var doc_id = $(".slot-details").find(".doc_id_app_book").val();
		var date = $(".slot-details").find(".widget_appointCalender").val();
		$("#reschedule-app-form").find('input[name="date"]').val(date);
		$("#reschedule-app-form").find('input[name="time"]').val(slot_time);
		$("html, body").animate({
		   scrollTop: 150
		}, 600);
	});
	jQuery(document).on("click", ".app_confirm_btn", function (e) {
		$("#reschedule-app-form").submit();
	});
	</script>
@endsection