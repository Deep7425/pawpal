@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
<div class="container">
    <div class="container-inner slot-details">
    @if(isset($doctor) && !empty($doctor))
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
					<img src="{{ URL::asset('img/calendar-icon-img.png') }}" /> @if(app("request")->input("date") != "") {{ base64_decode(app("request")->input("date")) }} @endif &nbsp;
					<img src="{{ URL::asset('img/time-icon-img.png') }}" />@if(app("request")->input("time") != "") {{ date ("h:i A",base64_decode(app("request")->input("time"))) }} @endif
			</div>
		 </div>
		 <div class="from-widget-top">
			{!! Form::open(array('name' => 'appointmentForm','url' => route('doctor.bookSlotConfirm'), 'method'=>"POST" )) !!}
				 <input type="hidden" name="doctor" value='{{ base64_decode(app("request")->input("doc")) }}'>
				 <input type="hidden" name="date" value='{{base64_decode(app("request")->input("date"))}}'>
				 <input type="hidden" name="time" value='{{base64_decode(app("request")->input("time"))}}'>
				 <div class="from-widget">
					 <div class="from-widget-section">
						 <label>First Name <span>*</span></label>
						 <input type="text" name="first_name" placeholder="First Name..." />
						 <span class="help-block"></span>
					 </div>
					 <div class="from-widget-section">
						 <label>Last Name <span>*</span></label>
						 <input type="text" name="last_name" placeholder="Last Name..." />
						 <span class="help-block"></span>
					 </div>
				 <div class="from-widget-section">
					 <label>Mobile Number <span>*</span></label>
					 <input type="text" name="mobile_no" placeholder="Mobile Number..." />
					 <span class="help-block"></span>
				 </div>
				 <div class="from-widget-section">
					 <label>Email Address</label>
					 <input type="text" name="email" placeholder="Email Address..." />
					 <span class="help-block"></span>
				 </div>
				 <div class="appointment-popup-block22">
				 <div class="appointment-popup-block2">
				 <div class="appointment-popup-block">
					<label>Age <span>*</span></label>
					<!--<div class="input-group date" id="datetimepicker6">
						<input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off"/>
						<span class="input-group-addon  patient_age_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
						<p class="dob_error help-block"></p>
					</div>-->
					<input type="hidden" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off"/>
					<div class="input-age-group fromDateModal">
						<input type="text" name="age" class="form-control age_in_number TopNavnumericFeild" placeholder="Age"/>
						<span class="help-block"></span>
						<select name="age_in_type" class="age_in_type">
							<!--<option value="0">Type</option>-->
							<option value="y">Y</option>
							<option value="m">M</option>
							<option value="d">D</option>
						</select>
						
					</div>
				</div>
				 <div class="from-widget-section-top2">
					 <label>Relationship<span>*</span></label>
					<select name="relationship" >
						<option value="myself">Myself</option>
						<option value="other">Other</option>
					</select>
					 <span class="help-block"></span>
				 </div>
				 </div>
				 </div>
				 </div>
				<div class="from-widget-btn">
					<button type="submit" class="btn btn-default subbtn">Confirm Appointment</button>
				 </div>
			{!! Form::close() !!} 
			 </div>
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
				if ($(this).val() > 150) {
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
			
			 jQuery("form[name='appointmentForm']").validate({
				// Specify the validation rules
				rules: {
					first_name: "required",
					last_name: "required",
					email: {email: true },
					mobile_no:{required:true,minlength:10,maxlength:12,number: true},
					age: "required",
					relationship: "required",
				},
				messages: {
					first_name: "Please enter First Name",
					last_name: "Please enter Last Name",
					age: "Please enter Age",
					email: "Please enter valid Email ID",
					mobile_no:{"required": "Please enter Mobile No.","number": "Please enter valid Mobile No."},
					relationship: "Please Choose a Relation ",
				},
				errorPlacement: function(error, element) {
					 error.appendTo(element.next());
				},
				submitHandler: function(form) {
					  jQuery('.loading-all').show();
					  jQuery('.subbtn').attr('disabled',true);
					  jQuery.ajax({
					  type: "POST",
					  dataType : "JSON",
					  url: "{!!route('doctor.bookSlotConfirm')!!}",
					  data:  new FormData(form),
					  contentType: false,
					  cache: false,
					  processData:false,
					  success: function(result) {
							console.log(result);
							if(result == 1){
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								jQuery("form[name='appointmentForm']").trigger('reset');
								var url = '{!! url("/'+seg1+'/step4?doc='+doc_id+'&date='+date+'&time='+time+'") !!}'; 
								// console.log(url);
								// window.location = url;
							}else{
								jQuery('.loading-all').hide();
								jQuery('.subbtn').attr('disabled',false);
								jQuery("form[name='appointmentForm']").trigger('reset');
							}
							
						},
						error: function(error)
						{
							jQuery('.loading-all').hide();
							alert("Oops Something goes Wrong.");
							jQuery('.subbtn').attr('disabled',false);
						}
					   });
				}
			});
        });	
	</script>
@endsection