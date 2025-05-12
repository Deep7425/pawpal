@if(isset($doctor) && !empty($doctor) && !(empty($doctor->opd_timings)))
<?php
      $mrngTime= [];
      $afterTime= [];
      $eveTime= [];
      $totSlots = [];
      foreach(json_decode($doctor->opd_timings) as $key=>$schedule){
          if(!empty($schedule->days)){
                if(in_array($dayOfWeek,$schedule->days))
                {
                  $duration = $doctor->slot_duration * 60;
                  foreach($schedule->timings as $k=>$v){
                      //$duration
                    $startTime = strtotime($v->start_time);
                    if(base64_decode($type) == 2 && isset($v->teleconsultation) && $v->teleconsultation == "0") {
						while($startTime <= strtotime($v->end_time)) {
						  $totSlots[] = $startTime;
						  $startTime += $duration;
						}
					}
					else if (base64_decode($type) == 1 && isset($v->teleconsultation) && $v->teleconsultation == "1") {
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
          }
      }
      foreach($totSlots as $k=>$val){
          //print_r($totSlots);
          // if($date != date("d-m-Y"))
          // {
          //   if($val < strtotime('12:00') ){
          //   $mrngTime[] = $val;
          //   }
          //   if(($val >= strtotime('12:00')) && ($val < strtotime('16:00'))){
          //   $afterTime[] = $val;
          //   }
          //   if(($val >= strtotime('16:00')) && ($val < strtotime('24:00'))){
          //   $eveTime[] = $val;
          //   }
          // }else{
            if($val < strtotime('12:00')){
            $mrngTime[] = $val;
          }
          if((($val >= strtotime('12:00')) && ($val < strtotime('16:00')))){
            $afterTime[] = $val;
          }
          if((($val >= strtotime('16:00')) && ($val < strtotime('24:00')))){
            $eveTime[] = $val;
          }
          // }

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
	<div class="widget-time-slot tab-pane fade @if($active == 1) in active @endif" id="docMorning_time_slot">
    	<table class="table">
    		<tbody>
    			<tr>
			@if(count($mrngTime) > 0)
             @foreach($mrngTime as $k=>$v)
			    <?php $tym =  date("Y-m-d",strtotime(Request::query('date'))).' '.date("H:i:s",$v); ?>
                @if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
                   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
                @elseif($date == date("d-m-Y") && $v < strtotime(date("h:i A")))
                  <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
    <div class="widget-time-slot tab-pane fade @if($active == 2) in active @endif" id="docAfternoon_time_slot">
      <table class="table">
        <tbody>
          <tr>
			@if(count($afterTime) > 0)
              @foreach($afterTime as $k=>$v)
			    <?php $tym =  date("Y-m-d",strtotime(Request::query('date'))).' '.date("H:i:s",$v); ?>
                @if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
                   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
                @elseif($date == date("d-m-Y") && $v < strtotime(date("h:i A")))
                  <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
    <div class="widget-time-slot tab-pane fade @if($active == 3) in active @endif" id="docEvening_time_slot">
      <table class="table">
        <tbody>
          <tr>
			@if(count($eveTime) > 0)
            @foreach($eveTime as $k=>$v)
				<?php $tym =  date("Y-m-d",strtotime(Request::query('date'))).' '.date("H:i:s",$v); ?>
                @if(checkAppointmentAvailable($tym,base64_encode($doctor->user_id)))
                   <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
                @elseif($date == date("d-m-Y") && $v < strtotime(date("h:i A")))
                  <td><a href="javascript:void(0);" class="hidentop" slot='{{$v}}'>{{date ("h:i A",$v)}}</a></td>
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
	@else
		<p class="no-found-slot-app">No available slots for this day</p>
@endif

<script type="text/javascript">
  $(document).ready(function(){
    $(".nav-tabs li").each(function(){
      var active = '{{@$active}}';

      if ($(this).attr('data') == active) {
        $('.nav-tabs li').removeClass('active');
        $(this).addClass('active');
      }
    });
  });
</script>
