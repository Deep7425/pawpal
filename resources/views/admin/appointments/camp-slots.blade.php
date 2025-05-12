@if(isset($doctor) && !empty($doctor) && !(empty($doctor->opd_timings)))
<?php
$mrngTime= [];
$afterTime= [];
$eveTime= [];
$totSlots = [];
if(!empty($doctor->slot_duration)){
  $duration = $doctor->slot_duration * 60;
}
else{
  $duration = 300;
}
foreach(json_decode($doctor->opd_timings) as $key=>$schedule){
if(count($schedule->days)>0) { 
	$unixTimestamp = strtotime($date);
	$dayOfWeek = date("w", $unixTimestamp);
	if(in_array($dayOfWeek,$schedule->days)) {
		foreach($schedule->timings as $k=>$v){
			$startTime = strtotime($v->start_time);
			if($type == '2' && isset($v->teleconsultation) && $v->teleconsultation == "0") {
				while($startTime <= strtotime($v->end_time)) {
				  $totSlots[] = $startTime;
				  $startTime += $duration;
				}
			}
			else if($type == '1' && isset($v->teleconsultation) && $v->teleconsultation == "1") {
				while($startTime <= strtotime($v->end_time)) {
				if (!empty($v->tele_appt_duration)) {
					$duration = $v->tele_appt_duration * 60;
				}
				  $totSlots[] = $startTime;
				  $startTime += $duration;
				}
			}
		}
	}
}}
foreach($totSlots as $k=>$val){
if($val < strtotime('12:00')) {
	$mrngTime[] = $val;
}
if($val >= strtotime('12:00') && $val < strtotime('16:00')){
	$afterTime[] = $val;
}
if($val >= strtotime('16:00') && $val < strtotime('24:00')){
	$eveTime[] = $val;
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
		<?php $tym =  date("Y-m-d",strtotime($date)).' '.date("H:i:s",$v); ?>
		@if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
		   <td><a href="javascript:void(0);" class="hidentop" title="Not Available" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
		@else
		   <td><a href="javascript:void(0);" class="chooseSlotCamp" title="Choose Slot" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
		<?php $tym =  date("Y-m-d",strtotime($date)).' '.date("H:i:s",$v); ?>
		@if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
		   <td><a href="javascript:void(0);" class="hidentop" title="Not Available" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
		@else
		   <td><a href="javascript:void(0);" class="chooseSlotCamp" title="Choose Slot" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
		<?php $tym =  date("Y-m-d",strtotime($date)).' '.date("H:i:s",$v); ?>
		@if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
		   <td><a href="javascript:void(0);" class="hidentop" title="Not Available" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
		@else
		   <td><a href="javascript:void(0);" class="chooseSlotCamp" title="Choose Slot" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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