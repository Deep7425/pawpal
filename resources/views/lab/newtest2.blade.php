@extends('layouts.Masters.Master')
@section('title', 'Cart')
@section('content')
<div class="right-section new-tabs-section">
   <div class="right-block">
        <div class="right-box">
            <div class="aad-inventory-section">
                <div class="add-staff">
                    <h2>Opd Timings Schedule</h2>
                </div>
                <div class="delete-top opd_back">
                    <a href="#">
                     <i title="Back button" class="fa fa-arrow-left" aria-hidden="true"></i></a>
        	</div>
            </div>
            <div class="opd-sch profile-1">
                <div class="add-doctor-block">
                    <div class="add-doctor-left">
                      <?php
                            $increment = 900;
                            $day_in_increments = range( 0, (86400 - $increment), $increment);
                            $appt_durations = getAppoimentDurations();
                       ?>

                        <input type="hidden" name="scheduleId" value="">
						<input type="hidden" name="id" value="">
                            @if(!empty($opdSchedule) > 0)
                                <div class="module-access-section module-access-section-border complete-str">
                                    <?php $schdules = json_decode($opdSchedule->schedule);
                                        $row = 1;
                                    ?>
                                    @foreach($schdules as $index => $schdule)

                                    <div class="main-div-schedule">
                                         <div class="checkbox-div">
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="1" @if(in_array('1',$schdule->days)) checked="checked" @endif>Monday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="2" @if(in_array('2',$schdule->days)) checked="checked" @endif>Tuesday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="3" @if(in_array('3',$schdule->days)) checked="checked" @endif>Wednesday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="4" @if(in_array('4',$schdule->days)) checked="checked" @endif>Thursday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="5" @if(in_array('5',$schdule->days)) checked="checked" @endif>Friday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="6" @if(in_array('6',$schdule->days)) checked="checked" @endif>Saturday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[{{$index}}][days][]" value="0" @if(in_array('0',$schdule->days)) checked="checked" @endif>Sunday</label>
                                         </div>
                                        <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
                                         <div class="sessions-str">
                                             <?php $timecnt = 1; ?>
											@foreach($schdule->timings as $rowss => $timeval)
                                            <div class="sessions-div" scheduleCnt="{{$index}}">
                    							<label> Session {{$timecnt}} :</label>
												<div class="teleconsult_section">
												  <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1" @if(isset($timeval->teleconsultation) && !empty($timeval->teleconsultation)) checked @endif>Tele-consultation</label>
												  <input type="hidden" class="teleconsult" name="schedule[{{$index}}][timings][{{$timecnt}}][teleconsultation]" value="@if(isset($timeval->teleconsultation) && !empty($timeval->teleconsultation)) 1 @else 0 @endif">
												</div>
												<div class="teleconsult_duration set_error" style="display:@if(isset($timeval->tele_appt_duration) && !empty($timeval->tele_appt_duration)) block @else none @endif;">
													<select name="schedule[{{$index}}][timings][{{$timecnt}}][tele_appt_duration]" class="slots">
														<option value="">Select</option>
													  @foreach($appt_durations as $idx => $dur)
														<option value="{{$dur->time}}" @if(isset($timeval->tele_appt_duration) && $timeval->tele_appt_duration == $dur->time) selected="selected" @endif>{{$dur->title}}</option>
													   @endforeach
													</select>
												</div>
												<div class="set_error">
												   <select name="schedule[{{$index}}][timings][{{$timecnt}}][start_time]" class="session_time_up given_time" >
														 <option value="">Select Start Time</option>
														 @foreach($day_in_increments as $time)
															<option value="{{date( 'H:i', $time )}}"  @if($timeval->start_time == date( 'H:i', $time )) selected="selected" @endif>{{date( 'g:i A', $time )}}</option>
														 @endforeach
													</select>
												</div>
												<div class="set_error">
													<select name="schedule[{{$index}}][timings][{{$timecnt}}][end_time]" class="session_time_down given_time" >
														 <option value="">Select End Time</option>
														 @foreach($day_in_increments as $time)
															<option value="{{date( 'H:i', $time )}}"  @if($timeval->end_time == date( 'H:i', $time )) selected="selected" @endif>{{date( 'g:i A', $time )}}</option>
														 @endforeach
													</select>
												</div>
												 @if($timecnt > 1)
												 <button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
												 @endif
                                            </div>
                                            <?php $timecnt++; ?>
                                            @endforeach
                                         </div>
                                         <div class="opd-timings-schedule-top">

                                         <button type="button" class="addSession" >Add More Session</button>

                                         </div>
                                         <div class="opd-timings-schedule">

                                            @if($row > 1)
                                         <button class="btn btn-default remove"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                                           @endif
                                    </div>
                                    </div>
                                    <?php $row++; ?>
                                    @endforeach
                                </div>
                            @else
                                <div class="complete-str">
                                    <div class="main-div-schedule">
                                         <div class="checkbox-div">
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="1">Monday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check"name="schedule[1][days][]" value="2">Tuesday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check"name="schedule[1][days][]" value="3">Wednesday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="4">Thursday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="5">Friday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="6">Saturday</label>
                                               <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="0">Sunday</label>
                                         </div><div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
                                         <div class="sessions-str">
                                            <div class="sessions-div" scheduleCnt="1">
											<label> Session 1 :</label>
                                            <div class="teleconsult_section">
                                              <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check"  value="1">Tele-consultation</label>
                                              <input type="hidden" class="teleconsult" name="schedule[1][timings][1][teleconsultation]" value="0">
                                            </div>
                                            <div class="teleconsult_duration set_error" style="display:none;">
                                                <select name="schedule[1][timings][1][tele_appt_duration]" class="slots">
                                                    <option value="">Tele Appointment Duration</option>
                                                  @foreach($appt_durations as $index => $dur)
													<option value="{{$dur->time}}">{{$dur->title}}</option>
                                                   @endforeach
                                                </select>
                                            </div>
                                            <div class="set_error">
                                                 <select name="schedule[1][timings][1][start_time]" class="session_time_up given_time">
                                                     <option value="">Select Start Time</option>
                                                     @foreach($day_in_increments as $time)
                                                     <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
                                                     @endforeach
                                                 </select>
											</div>
											<div class="set_error">
                                                 <select name="schedule[1][timings][1][end_time]" class="session_time_down given_time">
                                                     <option value="">Select End Time</option>
                                                     @foreach($day_in_increments as $time)
                                                        <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
                                                     @endforeach
                                                 </select>
											</div>
                                             </div>
                                         </div>
                                         <div class="opd-timings-schedule-top">
                                         <button type="button" class="addSession" >Add More Session</button>
                                         </div>
                                    </div>
                                </div>
                            @endif
                           <div class="addSchedule-btn"><button type="button" class="addSchedule">Add More Schedule</button>
                             <button type="submit" id='' class="btn btn-default submit">Save</button>
                           </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script type="text/javascript">
// When the browser is ready...
jQuery(document).ready(function () {
  jQuery(".addSchedule").click(function(){ //alert("f");
  	         var cnt = jQuery('.main-div-schedule').length+1;
             //alert(cnt);
             if(cnt <= 7){
                 var row = '<div class="main-div-schedule"><div class="checkbox-div">';
                    row += '<label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="1">Monday</label><label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="2">Tuesday</label>';
                    row += '<label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="3">Wednesday</label><label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="4">Thursday</label>';
                    row += '<label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="5">Friday</label><label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="6">Saturday</label>';
                    row += '<label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="0">Sunday</label></div><div id="msg" class="success-data alert alert-danger" style="display: none;"></div>';
                    row += '<div class="sessions-str"><div class="sessions-div" scheduleCnt="'+cnt+'"><label> Session 1 :</label>';
                    row += '<div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tele-consultation</label> <input type="hidden" class="teleconsult" name="schedule['+cnt+'][timings][1][teleconsultation]" value="0"></div>';
                    row += '<div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+cnt+'][timings][1][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div>';
                    row += '<div class="set_error"><select name="schedule['+cnt+'][timings][1][start_time]" class="session_time_up given_time" ><option value="">Select Start Time</option>@foreach($day_in_increments as $time)<option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>@endforeach</select></div>';
                    row += '<div class="set_error"><select name="schedule['+cnt+'][timings][1][end_time]" class="session_time_down given_time" ><option value="">Select End Time</option>@foreach($day_in_increments as $time)<option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>@endforeach</select></div></div>';
                    row += '</div><div class="opd-timings-schedule-top"><button type="button" class="addSession" >Add More Session</button></div><div class="opd-timings-schedule"><button class="btn btn-default remove"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
                // var row = jQuery('<tr><td class="sNo">'+cnt+'.</td><td><input type="text" class="form-control"  name="meta_data['+cnt+'][complaint_name]" required></td><td style="text-align: right;"><button class="btn btn-default remove"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
                jQuery('.complete-str').append(row);
              }else{
                  alert("Doctor Can't be Schedule More Then 7 Days.");
              }
  });
jQuery(document).on("click", ".remove", function () {
       jQuery(this).parents(".main-div-schedule").remove();
      // var i=1;
      // $('.main-div-schedule').each(function() { //alert(i);
      //  jQuery(this).find(".sNo").html(i);
      // i++;
      //});
});
jQuery(document).on("click", ".addSession", function () {
           var cnt = jQuery(this).parents(".main-div-schedule").find('.sessions-str .sessions-div').length+1;
           var scheduleCnt = jQuery(this).parents(".main-div-schedule").find('.sessions-str .sessions-div').attr('scheduleCnt');
           //alert(scheduleCnt);
           if(cnt <= 8){
               var row = '<div class="sessions-div"><label> Session '+cnt+' :</label>';
                  row += '<div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tele-consultation</label><input type="hidden" class="teleconsult" name="schedule['+scheduleCnt+'][timings]['+cnt+'][teleconsultation]" value="0"> </div>';
                   row += '<div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div>';
                   row += '<div class="set_error"><select name="schedule['+scheduleCnt+'][timings]['+cnt+'][start_time]" class="session_time_up  given_time"><option value="">Select Start Time</option>@foreach($day_in_increments as $time)<option value="{{date( 'H:i', $time )}}" >{{date( 'g:i A', $time )}}</option>@endforeach</select></div>';
                   row += '<div class="set_error"><select name="schedule['+scheduleCnt+'][timings]['+cnt+'][end_time]" class="session_time_down given_time"><option value="">Select End Time</option>@foreach($day_in_increments as $time)<option value="{{date( 'H:i', $time )}}" >{{date( 'g:i A', $time )}}</option>@endforeach</select></div>';
                   row += '<button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>';
              // var row = jQuery('<tr><td class="sNo">'+cnt+'.</td><td><input type="text" class="form-control"  name="meta_data['+cnt+'][complaint_name]" required></td><td style="text-align: right;"><button class="btn btn-default remove"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>');
                  jQuery(this).parents(".main-div-schedule").find('.sessions-str').append(row);
            }else{
                 alert("Sessions Not Available More Then 8 Times.");
            }
});
jQuery(document).on("click", ".removeSess", function () {
       jQuery(this).parents(".main-div-schedule .sessions-str  .sessions-div").remove();
});
jQuery(document).on("change", ".day_check", function () {
 var th = $(this), val = th.prop('value');
 if(th.is(':checked')){
     $(".checkbox-div").find(':checkbox[value="'  + val + '"]').not($(this)).prop('checked',false);
  }

});

            jQuery(document).on("click", ".submit", function () {
                jQuery("#updatetimings").validate({
                // Specify the validation rules
                    rules: {
                    },
                    messages: {
                    },
                    errorPlacement: function(error, element) {
                         error.appendTo(element.next());
                      },
                    submitHandler: function(form){
						var flag = true;
                        $('.main-div-schedule').each(function(){
                            $(this).find(".success-data").html('');
							$(this).find("#msg").hide();
                            if($(this).find('.day_check:checked').length < 1){
                                $(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
                                $(this).find(".success-data").slideDown();
                                flag = false;
                               //return false;
                             }
                        });
						$('.given_time').each(function (){
							if($(this).val()==''){
								$(this).next(".help-block").remove();
							    $(this).after('<span style="width:100%" class="help-block">This field is required</span>');
							   	flag = false;
							   //return false;
							}
							else{
								$(this).next(".help-block").remove();
							}
						});
            $('.teleconsult_section .teleconsult_check').each(function (){
               var teleconsult_duration = $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val();
              if($(this).is(':checked') && teleconsult_duration == ""){
                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').after('<span style="width:100%" class="help-block">This field is required</span>');
                  flag = false;
                 //return false;
              }
              else{
                $(this).closest('.sessions-div').find('.teleconsult_duration .slots').next(".help-block").remove();
              }
            });
                        if(flag == true){
                            jQuery('.loading-all').show();
                          jQuery('#submit').attr('disabled',true);
                             jQuery.ajax({
                                    type: "POST",
                                    url: $(form).attr('action'),
                                    data: new FormData(form),
                                    success: function(data){
										  jQuery('.loading-all').hide();
										  jQuery('#submit').attr('disabled',false);
                                    },
                                    error: function(error){
                                        jQuery('.loading-all').hide();
                                        alert("Oops Something goes Wrong.");
									   jQuery('#submit').attr('disabled',false);

                                    }
                                  });
                            }
                        }
                    });
                });
		jQuery(document).on("change", ".given_time", function (){
			if($(this).val()==''){
					$(this).next(".help-block").remove();
					$(this).after('<span style="width:100%" class="help-block">This field is required</span>');
				}
				else{
					$(this).next(".help-block").remove();
				}
		});

   /* jQuery(document).on("change", ".session_time_up", function (){
            var currevent = this;
            var selectedvar = $(currevent).val();
            //var gg =  selectedvar.unix();
            //alert(selectedvar.unix());

            arr = [];
            $(this).find('option').each(function(){
                //if($(this).val() != ''){
                  // arr[$(this).val()] = $(this).text();
                  arr.push($(this).val());
              //  }
            });
            //console.log(arr);
            preArr = arr.splice(0, arr.indexOf(selectedvar));
            //console.log(preArr);
            arr.splice(0,1);
            var row = '';
            if(preArr.length == 0){
              row += '<option value="" >Select End Time</option>';
            }
            $.each( arr, function( key, value ) {
                var str = value;
                var time = new moment(str, 'HH:mm:ss');
                row += '<option value="'+value+'" >'+moment(time).format('hh:mm A')+'</option>';
            });
            $(currevent).parents('.sessions-div').find('.session_time_down').empty();
            $(currevent).parents('.sessions-div').find('.session_time_down').html(row);

            }); */
      jQuery(document).on("change", ".session_time_up", function (){
            var currevent = this;
            var apostart_time = $(currevent).val();
            var practimeslot = 15;
            var updatedEndTime =  moment(apostart_time, "HH:mm:ss").add(practimeslot, 'minutes');
            selectedvar = moment(updatedEndTime).format('HH:mm:ss');
            var stDatetimestamp = moment(selectedvar, "HH:mm:ss").format('X');
            arrsloatEnd = [];
            $(this).find('option').each(function(){
                if($(this).val() != ''){
                    var endTimestamp = moment($(this).val(), "HH:mm:ss").format('X');
                    if(endTimestamp >= stDatetimestamp){
                        arrsloatEnd.push($(this).val());
                    }
                }
            });
            var row = '';
            $.each( arrsloatEnd, function( key, value ) {
                var str = value;
                var time = new moment(str, 'HH:mm:ss');
                row += '<option value="'+value+'" >'+moment(time).format('hh:mm A')+'</option>';
            });
            $(currevent).parents('.sessions-div').find('.session_time_down').empty();
            $(currevent).parents('.sessions-div').find('.session_time_down').html(row);
    });
      //  jQuery(document).on("change", ".session_time_up", function (){
              //  var selectedvar = $(this).val();
              //  arr = [];
				//var row  = '';
				//var temp = [];

				//selectArr = [];
            //    $(this).find('option').each(function(){
                //    if($(this).val() == selectedvar){
                  //     selectArr[$(this).val()] = $(this).text();
                //    }
				//	arr[$(this).val()] = $(this).text();
              //  });




				//for (var key in arr){
				//	if(arr.hasOwnProperty(key)) {
					//	temp = arr[key].splice(selectedvar, selectedvar);
						//temp[selectedvar] = temp[arr[key]];
						//console.log(key + " -> " + arr[key]);
				//	}
				//}
				//console.log(arr);
				//console.log(temp);
				//alert(selectedvar);
				//arr.move(selectedvar);

				//arr = arr.splice(selectedvar,selectedvar);
				//alert(arr);
				//console.log(arr);
				//var sum = "";
				//for( var i = 0; i < arr.length; i++ ) {
					//sum += arr[i].splice(selectedvar,selectedvar);
					//alert(arr[i].splice(selectedvar,selectedvar));
					//row += '<option value="'+arr[i]+'" >'+arr[i]+'</option>';
				//}
				//console.log(sum);
				//jQuery('.session_time_down').find('option').remove().end().append(row);
        //    });
              //  $(this).next().find('option').each(function(){
              //       alert($(this).val());
                  // });
          //      var row  = '';
            //    var selected_row  = '';
				//var  vals = $('.session_time_up').prop('value');
				//var parent_val = $('.session_time_up').parents();

				//	$(this).find('option').each(function(){
						//if(vals<$(this).val()){
							//alert($(this).val());
							//row += '<option selected value="'+$(this).val()+'" >'+$(this).text()+'</option>';
							//return false;
						//}
					//	   row += '<option value="'+$(this).val()+'" >'+$(this).text()+'</option>';
				//});
				 //jQuery('.session_time_down').find('option').remove().end().append(row);

});

/*jQuery(document).on("change", ".slots", function (){
	if($(this).val() > 0){
		var currevent = this;
		var increment = $(this).val() * 60;
		var result = range(0, (86400-increment),increment);
		var row = '';
		$.each(result, function( key, value ) {
			var time = moment.unix(value).utc().format('hh:mm A');
			var tvalue = moment.unix(value).utc().format('HH:mm');
			row += '<option value="'+tvalue+'" >'+time+'</option>';
		});
		$(currevent).parents('.sessions-div').find('.session_time_down').empty();
		$(currevent).parents('.sessions-div').find('.session_time_up').empty();
		$(currevent).parents('.sessions-div').find('.session_time_up').html(row);
		$(currevent).parents('.sessions-div').find('.session_time_down').html(row);
	}
});*/

function range(start, end, step = 1) {
  const len = Math.floor((end - start) / step) + 1
  return Array(len).fill().map((_, idx) => start + (idx * step))
}

jQuery(document).on("change", ".teleconsult_check", function (){
 if($(this).is(':checked')){
    $(this).closest('.teleconsult_section').find('.teleconsult').val(1);
    $(this).closest('.sessions-div').find('.teleconsult_duration').show();
    var slot_duration = '';
    $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val(slot_duration).trigger('change');
  }
  else {
     $(this).closest('.teleconsult_section').find('.teleconsult').val(0);
     $(this).closest('.sessions-div').find('.teleconsult_duration').hide();
     $(this).closest('.sessions-div').find('.teleconsult_duration .slots').prop('selectedIndex',0);
    // $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val(slot_duration).trigger('change');
  }
});

</script>
@endsection
