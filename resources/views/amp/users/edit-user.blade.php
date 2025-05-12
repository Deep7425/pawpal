@extends('amp.layouts.Masters.Master')
@section('title', 'User Portal | Health Gennie')
@section('description', "Welcome to the user portal of Health Gennie Elite where you can manage your profile and you can see your membership details.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
    <div class="container-inner">
        <div class='user-profile-detail' id="userProfileInfoData">
		  {!! Form::open(array('route' => 'profile','method' => 'POST', 'id' => 'profile-form', 'enctype' => 'multipart/form-data')) !!}
		  <input type="hidden" name="tab_type"/>
		  <input type="hidden" name="id" value="{{@$user->id}}"/>
			   <div class="tab-content user-claim-profile">
						<div class="registration-wrap user-info profile-exam">
                        	<div class="user-update-data">
								<div class="user-profile-img form-fields pad-r0">
								  <div class="profile-pic-wrap">
                                  	<label>Profile Image</label>
									<div class="image_apload22 user_profile_image_browse">
										<div class="image_apload">
											<img style="background-size: cover;" id="userProfileImage" class="img-update img-responsive" src="@if(!empty($user->image_url) && $user->image_url != null) {{$user->image_url}} @else {{ URL::asset('img/camera-icon.jpg') }} @endif" alt="icon" />
											<input type="hidden" name="old_profile_pic" value="{{ @$user->image }}"/>
											<input type="hidden" name="profile_image_cam" value="" id="profile_image_cam" />
										</div>
										<span id="fileselector">
											<label class="btn btn-default" for="upload-file-selector-user">
											<input id="upload-file-selector-user" type="file" name="image" class="mylogo" onchange="openFileProfile(event)"/>
												BROWSE
											</label>
										</span>
                                    </div>
									 <!--<div class="type_btn_main" style="display:none;">
										<div id="user_my_camera" style="border: 1px solid rgb(24, 154, 212);padding: 0 !important;overflow: hidden;display: inline-block;border-radius: 4px;"></div>
										<div class="button-wrapper">
										<button class="btn btn-default take_snap" type="button" onClick="take_snapshot()">Capture</button>
										<button class="btn btn-default load_cam" type="button" onClick="loadcam()">Reload</button>
										</div>
									</div>-->
                                  </div>
                                    <!-- <div class="dp-exiting">
                                    	@if(!empty($user->image_url) && $user->image_url != null)
								<div class="form-fields  form-field-mid specialization">
								  <label class="destop-top">Existing DP</label>
								  <div class="image_apload">
									<img style="background-size: cover;" class="img-update img-responsive" src="{{$user->image_url}}" alt="icon" />
								  </div>
								  <label class="mobile-top">Existing DP</label>
								</div>
								@endif
                                    </div> -->
								</div>
                                <div class="form-fields pad-r0 gender">
								  <label>Gender<i class="required_star">*</i></label>
								  <div class="radio-wrap">
									<input type="radio" name="gender" id="male" value="Male" @if($user->gender == '') checked @endif @if($user->gender == 'Male') checked @endif >
									<label for="male">Male</label>
								  </div>
								  <div class="radio-wrap">
									<input type="radio" name="gender" id="female" value="Female" @if($user->gender == 'Female') checked @endif>
									<label for="female">Female</label>
								  </div>
								  <div class="radio-wrap">
									<input type="radio" name="gender" id="other" value="Other" @if($user->gender == 'Other') checked @endif>
									<label for="other">Other</label>
								  </div>
								    <span class="help-block"><label for="gender" generated="true" class="error" style="display:none;">This field is required.</label></span>
								</div>

								<div class="form-fields pad-r0">
								  <label>Full Name<i class="required_star">*</i></label>
								  <input type="text" name="full_name" placeholder="Full name" value="{{@$user->first_name}}"  />
								  <span class="help-block">
									@if($errors->has('full_name'))
									<label for="full_name" generated="true" class="error">
										 {{ $errors->first('full_name') }}
									</label>
									@endif
								  </span>
								</div>
								<!--<div class="form-fields pad-r0">
								  <label>Last Name<i class="required_star">*</i></label>
								  <input type="text" name="last_name" placeholder="Last name" value="{{@$user->last_name}}"/>
								  <span class="help-block">
									@if ($errors->has('last_name'))
									<label for="last_name" generated="true" class="error">
										 {{ $errors->first('last_name') }} 
									</label>
									@endif
								  </span>
								</div>-->

								<div class="form-fields">
								  <label>Mobile Number<i class="required_star">*</i></label>
								  <select class="countryCode" id="country" name="mobile_code">
									<option selected="selected" value="IN">+91(IND)</option>
								  </select>
								  <input v_type="1" name="mobile_no" pat_id="{{@$user->id}}" class="s-input verifyPatData NumericFeild readOnlyforPaytm" type="text" placeholder="Mobile Number"  value="{{@$user->mobile_no}}" autocomplete="off" />
								  <span class="help-block">
									@if($errors->has('mobile_no'))
									<label for="mobile_no" generated="true" class="error">
										 {{ $errors->first('mobile_no') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields email-wrapper">
								  <label>Email ID<i class="required_star">*</i></label>
								  <input type="text" pat_id="{{@$user->id}}" v_type="0" name="email" placeholder="Email ID" value="{{@$user->email}}" class="verifyPatData readOnlyforPaytm" autocomplete="off" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}"/>
								  <span class="help-block">
									@if($errors->has('email'))
									<label for="email" generated="true" class="error">
										 {{ $errors->first('email') }}
									</label>
									@endif
								  </span>
								</div>
								<!--<div class="form-fields">
								  <label>Organization</label>
								 <select class="organizations searchDropDown" name="organization">
									<option value="">Select Organization</option>
									@foreach(getOrganizations() as $organization)
										<option value="{{$organization->id}}" @if(isset($user->organization)) {{$user->organization == $organization->id ? 'selected="selected"' : ''}} @endif>{{$organization->title}}</option>
									@endforeach
								 </select>
								 <span class="help-block">
									<label for="country_id" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('organization'))
									<label for="organization" generated="true" class="error">
										 {{ $errors->first('organization') }}
									</label>
									@endif
								 </span>
								</div> 
								<div class="form-fields">
								  <label>Aadhar Number(In Digits Only)</label>
								  <input type="text" class="NumericFeild" name="aadhar_no" placeholder="Aadhar Number" value="{{@$user->aadhar_no}}"/>
								  <span class="help-block">
									@if($errors->has('aadhar_no'))
									<label for="aadhar_no" generated="true" class="error">
										 {{ $errors->first('aadhar_no') }}
									</label>
									@endif
								  </span>
								</div>
								-->
								  <div class="appointment-popup-block">
									<label>DOB<i class="required_star">*</i></label>
									<div class="input-group date">
									  <input type="text" class="form-control dob_feild ageFormDobCalculate" name="dob" readonly  placeholder="dd-mm-yyyy" autocomplete="off"  @if($user->dob != null) value="{{ date('d-m-Y',$user->dob)}}"@endif />
									  <span class="input-group-addon patient_age_cal"> <i class="fa fa-calendar" aria-hidden="true"></i> </span>
									   <p class="dob_newP_error help-block">
										@if($errors->has('dob'))
										<label for="dob" generated="true" class="error">
											 {{ $errors->first('dob') }}
										</label>
										@endif
									   </p>
									</div>
									<div class="input-age-group fromDateModal">
									  <input type="text" class="form-control age_in_number NumericFeild" placeholder="Age"/>
									  <select name="age_in_type" class="age_in_type">
										<option value="y">Y</option>
										<option value="m">M</option>
										<option value="d">D</option>
									  </select>
									</div>
								  </div>
								<div class="form-fields">
								  <label style=" margin-bottom:0px;">Address<i class="required_star">*</i></label>
								  <input type="text" placeholder="Address" name="address" value="{{@$user->address}}" />
								  <span class="help-block">
									@if($errors->has('address'))
									<label for="address" generated="true" class="error">
										 {{ $errors->first('address') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid specialization">
								  <label>Country<i class="required_star">*</i></label>
								 <select class="country_id searchDropDown" name="country_id">
									<option value="">Select country</option>
									@foreach(getCountriesList() as $country)
										<option value="{{$country->id}}" @if(isset($user->country_id)) {{$user->country_id == $country->id ? 'selected="selected"' : ''}} @else @if($country->id=='101') selected @endif @endif >{{$country->name}}</option>
									@endforeach
								 </select>
								 <span class="help-block">
									<label for="country_id" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('country_id'))
									<label for="country_id" generated="true" class="error">
										 {{ $errors->first('country_id') }}
									</label>
									@endif
								 </span>
								 </div>
								<div class="form-fields specialization">
								  <label>State<i class="required_star">*</i></label>
									<select class="state_id searchDropDown" name="state_id">
									 <option value="">Select State</option>
										 @foreach($stateList as $state)
											<option value="{{ $state->id }}" {{ $user->state_id == $state->id ? 'selected="selected"' : ''}} >{{ $state->name }}</option>
										@endforeach
									</select>
									<span class="help-block">
										<label for="state_id" generated="true" class="error" style="display:none;"></label>
										@if($errors->has('state_id'))
										<label for="state_id" generated="true" class="error">
											 {{ $errors->first('state_id') }}
										</label>
										@endif
									 </span>
								</div>
								<div class="form-fields specialization">
								  <label>City<i class="required_star">*</i></label>
								 <select class="city_id searchDropDown" name="city_id" >
								 <option value="">Select City</option>
								  @foreach ($cityList as $city)
									<option value="{{$city->id}}" {{ $user->city_id == $city->id ? 'selected="selected"' : ''}}>{{$city->name}}</option>
									@endforeach
								 </select>
								 <span class="help-block">
									<label for="city_id" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('city_id'))
									<label for="city_id" generated="true" class="error">
										 {{ $errors->first('city_id') }}
									</label>
									@endif
								 </span>
								</div>
								<!--<div class="form-fields  specialization">
								  <label>Locality</label>
								 <select class="locality_id searchDropDown" name="locality_id" >
								 <option value="">Select Locality</option>
								 @if(count($localityList) > 0)
								   @foreach ($localityList as $locality)
									<option value="{{$locality->id}}" {{ $user->locality_id == $locality->id ? 'selected="selected"' : ''}}>{{$locality->name}}</option>
									@endforeach
								 @endif
								 </select>
								 <span class="help-block">
									<label for="locality_id" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('locality_id'))
									<label for="locality_id" generated="true" class="error">
										 {{ $errors->first('locality_id') }}
									</label>
									@endif
								 </span>
								</div>-->
								<div class="form-fields specialization">
								  <label>Zipcode(In Digits Only)</label>
									<input class="NumericFeild" type="text" placeholder="Zipcode" name="zipcode" value="{{@$user->zipcode}}" />
									<span class="help-block">
										@if($errors->has('zipcode'))
										<label for="zipcode" generated="true" class="error">
											 {{ $errors->first('zipcode') }}
										</label>
										@endif
									</span>
								</div>
								<div class="form-fields specialization" style="display:none;">
								  <label>About Me(Max: 250 Character)</label>
								  <textarea name="content" placeholder="Write about yourself" value="{{@$user->content}}">{{@$user->content}}</textarea>
								 	<span class="help-block">
										@if($errors->has('content'))
										<label for="content" generated="true" class="error">
											 {{ $errors->first('content') }}
										</label>
										@endif
									</span>
								</div>

								<div class="form-fields send-button doc-profile">
								  <button type="submit" class="profileFormSubmit form-control">Save Profile</button>
								</div>
        					</div>
						</div>
          {!! Form::close() !!}
		  </div>
		</div>
    </div>
    </div>
</div>
<script src="{{ URL::asset('js/moment.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('js/webcam.min.js') }}"></script>
<script>
jQuery(document).ready(function () {
    $(".searchDropDown").select2();
  });
jQuery('.searchDropDown').on('change', function() {
  if (this.value != "") {
    $(this).parent('.form-fields').find('.help-block .error').hide();
  }
});

		jQuery('.country_id').on('change', function() {
			  var cid = this.value;
			  var $el = $('.state_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getStateList') !!}",
				 // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
					jQuery("#profile-form").find("select[name='city_id']").html('<option value="">Select City</option>');
					jQuery("#profile-form").find("select[name='state_id']").html('<option value="">Select State</option>');
					jQuery.each(result,function(index, element) {
					   $el.append(jQuery('<option>', {
						   value: element.id,
						   text : element.name
					  }));
				  });
				},
				error: function(error) {
					if(error.status == 401){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						alert("Oops Something goes Wrong.");
					}
				}
				}
			  );
			})
			jQuery(document).on("change", ".state_id", function (e) {
			//jQuery('.state_id').on('change', function() {
			  var cid = this.value;
			  var $el = jQuery('.city_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getCityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
				  jQuery("#profile-form").find("select[name='city_id']").html('<option value="">Select City</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
				},
				error: function(error) {
					if(error.status == 401){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						alert("Oops Something goes Wrong.");
					}
				}
				}
			  );
			});


			jQuery(document).on("change", ".city_id", function (e) {
			  var cid = this.value;
			  var $el = jQuery('.locality_id');
			  $el.empty();
			  jQuery.ajax({
				  url: "{!! route('getLocalityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
				  jQuery("#profile-form").find("select[name='locality_id']").html('<option value="">Select Locality</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
				},
				error: function(error) {
					if(error.status == 401){
						alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						alert("Oops Something goes Wrong.");
					}
				}
				}
			  );
			});


		jQuery("#profile-form").validate({
			rules: {
				full_name: {required:true,maxlength:20},
				// last_name: {required:true,maxlength:20},
				mobile_no:{required:true,minlength:10,maxlength:10,number: true},
				// aadhar_no:{minlength:12,maxlength:12,number: true},
				email: {required: true,email: true },
				dob: "required",
				gender: "required",
				address: {required:true,maxlength:150},
				country_id: "required",
				state_id: "required",
				city_id: "required",
				content:  {maxlength:250},
				zipcode:  {maxlength:6},
			},
			messages: {
			},
			errorPlacement: function(error, element) {
				 error.appendTo(element.next());
			},ignore: ":hidden",
			submitHandler: function(form) {
				jQuery('.loading-all').hide();
				form.submit();
			}
		});

		function openFileProfile(event) {
			$("#profileFormSubmit").attr("disabled",false);
			var input = event.target;
            var FileSize = input.files[0].size / 1024 / 1024; // 1in MB
            var type = input.files[0].type;
            var ext = input.files[0].name.split('.').pop().toLowerCase();
			//alert($.inArray(ext, ['gif','png','jpg','jpeg']));
			var reader = new FileReader();
            if(FileSize > 3) {
				$('.mylogo').val('');
                $('#userProfileImage').next(".help-block").remove();
                $('#userProfileImage').after('<span style="width:100%" class="help-block">Allowed file size exceeded. (Max. 3 MB)</span>');
                $("#profileFormSubmit").attr("disabled",true);
			}
		    else if($.inArray(ext, ['png','jpg','jpeg']) >= 0) {
				$("#profileFormSubmit").attr("disabled",false);
				$('#userProfileImage').next(".help-block").remove();
      			reader.addEventListener("load", function () {
					$('#userProfileImage').attr('src',reader.result);
				},false);
      			reader.readAsDataURL(input.files[0]);
            }
            else {
                $('.mylogo').val('');
                $('#userProfileImage').next(".help-block").remove();
                $('#userProfileImage').after('<span style="width:100%" class="help-block">Only formats are allowed : (jpeg,jpg,png)</span>');
			    $("#profileFormSubmit").attr("disabled",true);
		    }
		}

		//for report section refreshed data
		$(document).ready(function(){

		 var p_age = $('.dob_feild').val();
			if(p_age !=""){getPatientAgeAdd(p_age);}
			$( ".dob_feild" ).datepicker({
			  dateFormat: 'dd-mm-yy',
			  changeMonth: true,
			  changeYear: true,
			  yearRange: "1900:+0",
			  endDate: "today",
			  maxDate: new Date(),
			  onSelect: function () {
			   getPatientAgeAdd(this.value);
			   $('.dob_feild').val(this.value);
			   }
			});
			jQuery('.patient_age_cal').click(function () {
			   jQuery('.dob_feild').datepicker('show');
			});

		  function getPatientAgeAdd(dob) {
			var p_age = showAge(dob);
			if(p_age != "") {
			  var age_number = p_age.split(',');
			  $("#userProfileInfoData").find(".fromDateModal .age_in_number").val(age_number[0]);
			  if(age_number[1] == "d"){
				$("#userProfileInfoData").find('.fromDateModal select option[value="d"]').prop("selected", true);
			  }
			  else if(age_number[1] == "m"){
				$("#userProfileInfoData").find('.fromDateModal select option[value="m"]').prop("selected", true);
			  }
			  else if(age_number[1] == "y"){
				$("#userProfileInfoData").find('.fromDateModal select option[value="y"]').prop("selected", true);
			  }
			 }
		  }

			//Age To Dob Functionalities
		  jQuery(document).on("change", ".age_in_type", function () {
			var age_in_number = $("#userProfileInfoData").find(".age_in_number").val();
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
			  $(".fromDateModal").find(".age_in_number").val("");
			}
			if(age_in_number){
			  $("#userProfileInfoData").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
			}
			else{
			  $("#userProfileInfoData").find(".ageFormDobCalculate").val("");
			}
		  });
		});

		  jQuery(document).on("keypress keyup keydown", ".age_in_number", function () {
			if ($(this).val() > 150) {
			  $(this).css('border','1px solid red');
			  alert("Invalid value");
			  $(this).val("");
			}
			else{
			  $(this).css('border','');

			  var type = $("#userProfileInfoData").find(".fromDateModal .age_in_type").val();
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
				// $("#userProfileInfoData").find(".fromDateModal .age_in_number").val("");
			  }
			  if(age_in_number){
				$("#userProfileInfoData").find(".ageFormDobCalculate").val($.datepicker.formatDate('dd-mm-yy', now));
			  }
			  else{
				$("#userProfileInfoData").find(".ageFormDobCalculate").val("");
			  }
			}
		  });
/*
		jQuery(document).ready(function () {
			Webcam.set({
					width: 292,
					height: 224,
					dest_width: 292,
					dest_height: 224,
					image_format: 'png',
					jpeg_quality: 90,
					force_flash: false,
					flip_horiz: true,
					fps: 45
			});
			setTimeout(function(){
				Webcam.attach('#user_my_camera');
			},1000);
			Webcam.snap( function(data_uri) {
				if( /iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
					var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
					document.getElementById('profile_image_cam').value = raw_image_data;
					document.getElementById('user_my_camera').innerHTML = '<img src="'+data_uri+'"/>';
				}
			});
			Webcam.on('error', function(err) {
				$(".user-claim-profile").find('.type_btn_main').hide();
				// $('.user_profile_image_browse').show();
				$(".user-claim-profile").find('.user_profile_image_browse').show();
				$('#profile_image_cam').val('');
			});
			// $('.user_profile_image_browse').hide();
			$(".user-claim-profile").find('.type_btn_main').show();
			$(".user-claim-profile").find('.user_profile_image_browse').hide();
		});

		function take_snapshot() {
			// take snapshot and get image data
			Webcam.snap( function(data_uri) {
					var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
					document.getElementById('profile_image_cam').value = raw_image_data;
					document.getElementById('user_my_camera').innerHTML = '<img src="'+data_uri+'"/>';
			});
		}

		function loadcam(){
			  Webcam.attach('#user_my_camera');
		}*/
</script>
@endsection
