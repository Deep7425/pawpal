<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h2>Select a time slot to book an appointment</h2>
		</div>
		 <div class="modal-body">
				  <div class="doctor-listtop doctor-listtop2">
					<div class="doctor-listtop-img">
						<img  src="@if(!empty($doctor->profile_pic)){{$doctor->profile_pic}} @else {{  url('/img') }}/doc-img.png @endif" width="70"/>
					</div>

				 <div class="doctor-listtop-content doctor-listtop-content2">
					
                    <div class="dr-detail-left"><h2>Dr. {{@$doctor->first_name}} {{@$doctor->last_name}}</h2>
					<div class="speciality-wrapper">
                    	<span class="specility">{{getSpecialityName($doctor->speciality )}}</span>
						<h4 class="Qualification">{{@$doctor->qualification}}</h4>
                    </div>
                    </div>
					@if(!empty($doctor->content))<p>
					  {{$doctor->content}}</p>@endif
					  @if(!empty($doctor->speciality))

					  @endif
					  <div class="date-formet-top">
				  <div class="date-formet-block">
				  <label>Appointment Date</label>
				  <div class="date-formet-section">
					<input type="text" class="widget_appointCalender" name="widget_appoint_date" readonly  placeholder="dd-mm-yyyy" autocomplete="off"/>
					<i class="fa fa-calendar widget_appoint_cal" aria-hidden="true"></i>
				  </div>
				  </div>
				  </div>
				 </div>

				 </div>

				  @if(isset($doctor) && !empty($doctor) && !(empty($doctor->opd_timings)))
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
										if(base64_decode($type) == 2 && isset($v->teleconsultation) && $v->teleconsultation == "0") {
											while($startTime <= strtotime($v->end_time)) {
											 // $totSlots[] = date ("H:i", $startTime);
											  $totSlots[] = $startTime;
											  $startTime += $duration;
											}
										}
										else if (base64_decode($type) == 1 && isset($v->teleconsultation) && $v->teleconsultation == "1") {
											while($startTime <= strtotime($v->end_time)) {
											 // $totSlots[] = date ("H:i", $startTime);
											if (!empty($v->tele_appt_duration)) {
												$duration = $v->tele_appt_duration * 60;
											}
											  $totSlots[] = $startTime;
											  $startTime += $duration;
											}
										}
									}
								}
							}
						}
						foreach($totSlots as $k=>$val){
						  //print_r($totSlots);
						  if($val < strtotime('12:00')) {
							// if($val > strtotime(date("h:i A"))){
								$mrngTime[] = $val;
							// }
							// else{
								// $mrngTime[] = $val;
							// }
						  }
						  if($val >= strtotime('12:00') && $val < strtotime('16:00')){
							// if($val > strtotime(date("h:i A"))){
								$afterTime[] = $val;
							// }
							// else{
								// $afterTime[] = $val;
							// }
						  }
						  if($val >= strtotime('16:00') && $val < strtotime('24:00')){
							  // if( $val > strtotime(date("h:i A"))){
								$eveTime[] = $val;
							  // }
							  // else{
								 // $eveTime[] = $val;
							  // }
						  }
						}
					  if (count($mrngTime) > 0) {
					  	$active = 1;
					  }
					  else if (count($afterTime) > 0) {
					  	$active = 2;
					  }
					  else if (count($eveTime) > 0) {
					  	$active = 3;
					  }
					  else {
					  	$active = 1;
					  }
					?>
					  <input id="doc_id_app_book" type="hidden" value="{{base64_encode($doctor->id)}}"/>
				  <ul class="nav nav-tabs">
					<li @if($active == 1) class="active" @endif data="1" ><a data-toggle="tab" class="tab_class_time_slot" id="1" ><img src="{{ URL::asset('img/morning.png') }}" />Morning</a></li>
					<li @if($active == 2) class="active" @endif data="2" ><a data-toggle="tab" id="2" class="tab_class_time_slot"><img src="{{ URL::asset('img/afternoon.png') }}" />Afternoon</a></li>
					<li @if($active == 3) class="active" @endif data="3"><a data-toggle="tab" id="3" class="tab_class_time_slot"><img src="{{ URL::asset('img/evening.png') }}" />Evening</a></li>
				  </ul>
				  <div class="slotsMainDiv tab-content">

					<div class="widget-time-slot tab-pane fade @if($active == 1) in active @endif" id="docMorning_time_slot">
						<table class="table">

							<tbody>
								<tr>
							 @if(count($mrngTime) > 0)
							 @foreach($mrngTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)) || $v < strtotime(date("h:i A")))
								   <td><a href="#" class="hidentop" title="Not Available">{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="#" class="chooseSlot" title="Choose Slot" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td>
								@endif
							  @endforeach
							  @else
								  <p class="no-found-slot-app">No available slots</p>
							  @endif
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-time-slot tab-pane fade @if($active == 2) in active @endif" id="docAfternoon_time_slot">
					  <table class="table">

						<tbody>
						  <tr>
							@if(count($afterTime) > 0)
							  @foreach($afterTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)) || $v < strtotime(date("h:i A")))
								   <td><a href="#" class="hidentop" title="Not Available">{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="#" class="chooseSlot" title="Choose Slot" slot='{{$v}}'>{{date("h:i A",$v)}}</a></td>
								@endif
							  @endforeach
							  @else
								  <p class="no-found-slot-app">No available slots</p>
							  @endif
						  </tr>
						</tbody>
					  </table>
					</div>
					<div class="widget-time-slot tab-pane fade @if($active == 3) in active @endif" id="docEvening_time_slot">
					  <table class="table">

						<tbody>
						  <tr>
						  @if(count($eveTime) > 0)
							@foreach($eveTime as $k=>$v)
								@if(checkAppointmentAvailable(date("Y-m-d H:i:s",$v),base64_encode($doctor->user_id)) || $v < strtotime(date("h:i A")))
								   <td><a href="#" class="hidentop" title="Not Available">{{date("h:i A",$v)}}</a></td>
								@else
								   <td><a href="#" class="chooseSlot" title="Choose Slot" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
							<p class="no-found-slot-app">No slots available for this day</p>
					  </div>
				  @endif
		  </div>
		</div>
	</div>
<script>
$( function() {
$( ".widget_appointCalender" ).datepicker({
  dateFormat: 'dd-mm-yy',
  minDate: 0,
  changeMonth: true,
    changeYear: true,
  onSelect: function(dateText, inst) {
	  jQuery('.loading-all').show();
	 $.ajax
	  ({
		type: "Get",
		url: "{!! route('doctor.loadSlots') !!}",
	   data: "date="+dateText+'&doctor={{$doctor->id}}&type={{$type}}',
		success: function(result) {
		    $( ".slotsMainDiv" ).html(result);
			jQuery('.loading-all').hide();
			// $('.tab_class_time_slot').each(function() {
			// 	$(this).closest("li").removeClass("active");
			// 	// if($(this).attr('id') == 1) {
			// 	// 	$(this).closest("li").addClass("active");
			// 	// }
			// });
		   $(result).find("#docMorning_time_slot").addClass('in active');
		   $(result).find("#docAfternoon_time_slot").removeClass('in active');
		   $(result).find("#docEvening_time_slot").removeClass('in active');

		}
	  });

	}
 });
$('.widget_appointCalender').datepicker('setDate', 'today');
});


     $(document).on("click", ".chooseSlot", function () {
			jQuery('.loading-all').show();
            date = $(".widget_appointCalender").val();
            time = $(this).attr('slot');
            doc_id = $("#doc_id_app_book").val();
            var type = '{{$type}}';
			var onlineApp = 0;
			if(jQuery("#doctorAppointmentSlot").hasClass("liveDocSts")){
				onlineApp = 1;
			}
			var url = '{!! url("/doctor/appointment-book?doc='+doc_id+'&date='+btoa(date)+'&time='+btoa(time)+'&conType='+type+'") !!}';
            window.location = url;
        });


</script>
