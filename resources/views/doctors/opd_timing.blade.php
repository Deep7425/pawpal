<?php 
$row = 1;
$types = [];

?>
 
 {{-- //@dd($schdule); --}}
   
 @if($slot_duration)

 <div class="opd-timings-slot">
   <label>Appointment Duration</label>
   <select name="slot_duration" class="slots-data">
   <option value="">Select</option>
  
     <option value="5" @isset($slot_duration) @if($slot_duration==5) selected="selected" @endif @endisset >5 Minuts</option>
     <option value="10" @isset($slot_duration) @if($slot_duration==10) selected="selected" @endif @endisset >10 Minuts</option>
     <option value="15" @isset($slot_duration) @if($slot_duration==15) selected="selected" @endif @endisset >15 Minuts</option>
     <option value="20" @isset($slot_duration) @if($slot_duration==20) selected="selected" @endif @endisset > 20 Minuts</option>
     <option value="25" @isset($slot_duration) @if($slot_duration==25) selected="selected" @endif @endisset >25 Minuts</option>
     <option value="30"  @isset($slot_duration) @if($slot_duration==30) selected="selected" @endif @endisset>30 Minuts</option>
     <option value="35" @isset($slot_duration) @if($slot_duration==35) selected="selected" @endif @endisset >35 Minuts</option>
     <option value="40" @isset($slot_duration) @if($slot_duration==40) selected="selected" @endif @endisset >40 Minuts</option>
     <option value="45" @isset($slot_duration) @if($slot_duration==45) selected="selected" @endif @endisset >45 Minuts</option>
     <option value="50" @isset($slot_duration) @if($slot_duration==50) selected="selected" @endif @endisset  >50 Minuts</option>
     <option value="55" @isset($slot_duration) @if($slot_duration==55) selected="selected" @endif @endisset >55 Minuts</option>

   </select>
   <span class="help-block"></span>
 </div>
 @endif
   @foreach($schdules as $index => $schdule)
  <div class="main-div-schedule">
  

       <div class="checkbox-div">


        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="1" @isset($schdule['days']) @if(in_array('1',$schdule['days'])) checked="checked" @endif @endisset>Monday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="2" @isset($schdule['days']) @if(in_array('2',$schdule['days'])) checked="checked" @endif @endisset>Tuesday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="3" @isset($schdule['days']) @if(in_array('3',$schdule['days'])) checked="checked" @endif @endisset>Wednesday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="4" @isset($schdule['days']) @if(in_array('4',$schdule['days'])) checked="checked" @endif @endisset>Thursday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="5" @isset($schdule['days']) @if(in_array('5',$schdule['days'])) checked="checked" @endif @endisset>Friday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="6" @isset($schdule['days']) @if(in_array('6',$schdule['days'])) checked="checked" @endif @endisset>Saturday</label>
        <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="0" @isset($schdule['days']) @if(in_array('0',$schdule['days'])) checked="checked" @endif @endisset>Sunday</label>

       </div>
      <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>

       <div class="sessions-str">
           <?php $timecnt = 1; ?>
           @isset($schdule['timings'])
        @foreach($schdule['timings'] as $rowss => $timeval)

      
          <div class="sessions-div" scheduleCnt="{{$index}}">
            <label> Session {{$timecnt}} :</label>
            <div class="teleconsult_section">
              <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1" @if(isset($timeval['teleconsultation']) && !empty($timeval['teleconsultation'])) checked @endif>Tele-consultation</label>
              <input type="hidden" class="teleconsult" name="schedule[{{$index}}][timings][{{$timecnt}}][teleconsultation]" value="@if(isset($timeval['teleconsultation']) && !empty($timeval['teleconsultation'])) 1 @else 0 @endif">
            </div>
            <?php
					$appt_durations = getAppoimentDurations(1);
					$increment = 900;
					$day_in_increments = range( 0, (86400 - $increment), $increment);
				 ?>
            <div class="teleconsult_duration set_error" style="display:@if(isset($timeval['tele_appt_duration']) && !empty($timeval['tele_appt_duration'])) block @else none @endif;">
                <select name="schedule[{{$index}}][timings][{{$timecnt}}][tele_appt_duration]" class="slots">
                    <option value="">Select</option>
                  @foreach($appt_durations ?? '' as $idx => $dur)
                    <option value="{{$dur->time}}" @if(isset($timeval['tele_appt_duration']) && $timeval['tele_appt_duration'] == $dur->time) selected="selected" @endif>{{$dur->title}}</option>
                   @endforeach
                </select>
          </div>

            <div class="set_error">
               <select name="schedule[{{$index}}][timings][{{$timecnt}}][start_time]" class="session_time_up given_time" >
                     <option value="">Select Start Time</option>
                     @foreach($day_in_increments as $time)
                        <option value="{{date( 'H:i', $time )}}"  @if($timeval['start_time'] == date( 'H:i', $time )) selected="selected" @endif>{{date( 'g:i A', $time )}}</option>
                     @endforeach
                </select>
            </div>
            <div class="set_error">
                <select name="schedule[{{$index}}][timings][{{$timecnt}}][end_time]" class="session_time_down given_time" >
                     <option value="">Select End Time</option>
                     @foreach($day_in_increments as $time)
                        <option value="{{date( 'H:i', $time )}}"  @if($timeval['end_time'] == date( 'H:i', $time )) selected="selected" @endif>{{date( 'g:i A', $time )}}</option>
                     @endforeach
                </select>
            </div>
             @if($timecnt > 1)
             <button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
             @endif
          </div>
          <?php $timecnt++; ?>
          @endforeach
          @endisset
       </div>
       <div class="opd-timings-schedule-top">

       <button type="button" class="addSession" >Add More Session</button>

       </div>
       <div class="opd-timings-schedule">

         
  </div>
  @if($row > 1)
  <button class="btn btn-default remove"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
    @endif
  </div>
  <?php $row++; ?>
  @endforeach