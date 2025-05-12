@extends('layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@section('content')
<div class="container">
    <div class="container-inner">
	 @if ($errors->any())
		<div class="alert alert-danger sessionMsg">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<?php $year = range(1950,date("Y")); ?>
    <div class="">
        <div class='example profile-detil doctor-claim-profile'>
		  {!! Form::open(array('route' => 'addDoc','method' => 'POST', 'id' => 'register-form', 'class' => 'clinic-details', 'enctype' => 'multipart/form-data')) !!}
		  <input type="hidden" name="tab_type"/>
		  <input type="hidden" name="clinic_id"/>
		  <input type="hidden" name="tele_status" id="tele_status"/>
			<div class='tabsholder1'>
			  <ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" status="1" class="tab_class_main_ul" id="1" >Info</a></li>
				<li><a data-toggle="tab" id="2" status="0" class="tab_class_main_ul">Clinic Details</a></li>
				<li><a data-toggle="tab" id="3" status="0" class="tab_class_main_ul">OPD Timing</a></li>
			  </ul>
			   <div class="tab-content">
					<div id="info_tab" class="doc_info tab-pane fade in active">
						<div class="doctor-info">
							<div class="registration-wrap doc-register">
								<div class="form-title"><h2>Doctor's Info</h2></div>
								<div class="form-fields form-field-mid pad-r0 f-name-field">
								  <label>First Name<i class="required_star">*</i></label>
								  <select class="countryCode" disabled>
									<option selected="selected" value="Dr.">Dr.</option>
								  </select>
								  <input type="text" name="first_name" placeholder="First name" autocomplete="off" value="{{ old('first_name') }}" />
								  <span class="help-block">
									@if($errors->has('first_name'))
									<label for="first_name" generated="true" class="error">
										 {{ $errors->first('first_name') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid pad-r0">
								  <label>Last Name<i class="required_star">*</i></label>
								  <input type="text" name="last_name" placeholder="Last name" autocomplete="off" value="{{ old('last_name') }}" />
								  <span class="help-block">
									@if($errors->has('last_name'))
									<label for="last_name" generated="true" class="error">
										 {{ $errors->first('last_name') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid pad-r0 gender">
								  <label>Gender<i class="required_star">*</i></label>
								  <div class="radio-wrap">
									<input type="radio" name="gender" id="male" value="Male" checked >
									<label for="male">Male</label>
								  </div>
								  <div class="radio-wrap">
									<input type="radio" name="gender" id="female" value="Female" >
									<label for="female">Female</label>
								  </div>
								</div>
								<div class="form-fields">
								  <label>Mobile Number<i class="required_star">*</i></label>
								  <select class="countryCode" id="country" name="mobile_code" disabled>
									<option selected="selected" value="IN">+91(IND)</option>
								  </select>
								  <input name="mobile_no" v_type="1" class="s-input verifyDocData NumericFeild" type="text" placeholder="Mobile Number" autocomplete="off" value="{{ old('mobile_no') }}" />
								  <span class="help-block">
									@if($errors->has('mobile_no'))
									<label for="mobile_no" generated="true" class="error">
										 {{ $errors->first('mobile_no') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields">
								  <label>Email ID(email@mail.com)<i class="required_star">*</i></label>
								  <input type="text" v_type="0" name="email" placeholder="Enter Email" class="verifyDocData" autocomplete="off" value="{{ old('email') }}" />
								  <span class="help-block">
									@if($errors->has('email'))
									<label for="email" generated="true" class="error">
										 {{ $errors->first('email') }}
									</label>
									@endif
								  </span>
								</div>
							   <div class="form-fields  form-field-mid pad-r0 specialization">
								  <label>Specialization<i class="required_star">*</i></label>
								  <select name="speciality" class="speciality-select_doctor searchDropDown">
									<option value="">Select Speciality</option>
									@foreach (getSpecialityList() as $specialities)
									<option value="{{ $specialities->id }}" @if(old("speciality") == $specialities->id) selected @endif >{{ $specialities->specialities }}</option>
									@endforeach
								  </select>
								  <span class="help-block"><label for="speciality" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('speciality'))
									<label for="speciality" generated="true" class="error">
										 {{ $errors->first('speciality') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields">
								  <label>Experience(Only In Number)<i class="required_star">*</i></label>
								  <input type="text" name="experience" placeholder="Experience" class="NumericFeild" autocomplete="off" value="{{ old('experience') }}" />
								  <span class="help-block">
									@if($errors->has('experience'))
									<label for="experience" generated="true" class="error">
										 {{ $errors->first('experience') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields  form-field-mid specialization">
									<label>Qualification<i class="required_star">*</i></label>
									<input type="text" name="qualification" placeholder="Qualification" autocomplete="off" value="{{ old('qualification') }}"/>
									<span class="help-block">
										@if($errors->has('qualification'))
										<label for="qualification" generated="true" class="error">
											 {{ $errors->first('qualification') }}
										</label>
										@endif
									</span>
								</div>
								<div class="form-fields  form-field-mid specialization">
								  <label>Registration No.<i class="required_star">*</i></label>
								  <input type="text" name="reg_no" placeholder="Registration No" autocomplete="off" value="{{ old('reg_no') }}"/>
								  <span class="help-block">
									@if($errors->has('reg_no'))
									<label for="reg_no" generated="true" class="error">
										 {{ $errors->first('reg_no') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields  form-field-mid specialization">
								  <label>Registration Year<i class="required_star">*</i></label>
									<select name="reg_year" class="form-control">
										<option value="">Select Registration Year</option>
										@foreach($year as $raw)
										<option value="{{$raw}}">{{$raw}}</option>
										@endforeach
									</select>
								  <span class="help-block">
									@if($errors->has('reg_year'))
									<label for="reg_year" generated="true" class="error">
										 {{ $errors->first('reg_year') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid pad-r0 specialization select_reg_council_div">
								  <label>Registered Council<i class="required_star">*</i></label>
								  <select name="reg_council" class="searchDropDown select_reg_council">
									<option value="">Select Registered Council</option>
									@foreach (getCouncilingData() as $council)
									<option value="{{ $council->id }}" @if(old("reg_council") == $council->id) selected @endif>{{ $council->council_name }}</option>
									@endforeach
									<option value="0">Other</option>
								  </select>
								  <span class="help-block"><label for="reg_council" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('reg_council'))
									<label for="reg_council" generated="true" class="error">
										 {{ $errors->first('reg_council') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid specialization reg_council_other_div" style="display:none;">
								  <label>Registered Council Other<i class="required_star">*</i></label>
								  <input type="text" name="reg_council_other" placeholder="Other" autocomplete="off" value="{{ old('reg_council_other') }}"/>
								  <span class="help-block">
									@if($errors->has('reg_council_other'))
									<label for="reg_council_other" generated="true" class="error">
										 {{ $errors->first('reg_council_other') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields  form-field-mid specialization">
								  <label>Last Obtained Degree</label>
								  <input type="text" name="last_obtained_degree" placeholder="Last Obtained Degree" autocomplete="off" value="{{ old('last_obtained_degree') }}"/>
								  <span class="help-block">
									@if($errors->has('last_obtained_degree'))
									<label for="last_obtained_degree" generated="true" class="error">
										 {{ $errors->first('last_obtained_degree') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields  form-field-mid specialization">
								  <label>Degree Year</label>
								  <select name="degree_year" class="form-control">
										<option value="">Select Degree Year</option>
										@foreach($year as $raw)
										<option value="{{$raw}}">{{$raw}}</option>
										@endforeach
								 </select>
								  <!--<input class="yearPick NumericFeild" type="text" name="degree_year" placeholder="Degree Year" autocomplete="off" value="{{ old('degree_year') }}"/>-->
								  <span class="help-block">
									@if($errors->has('degree_year'))
									<label for="degree_year" generated="true" class="error">
										 {{ $errors->first('degree_year') }}
									</label>
									@endif
								  </span>
								</div>

								<div class="form-fields  form-field-mid pad-r0 specialization university_div">
								  <label>College/University</label>
								  <select name="university" class="searchDropDown select_university">
									<option value="">Select College/University</option>
									@foreach (getUniversityList() as $university)
									<option value="{{ $university->id }}" @if(old("university") == $university->id) selected @endif>{{$university->name}}</option>
									@endforeach
									<option value="0">Other</option>
								  </select>
								  <span class="help-block"><label for="university" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('university'))
									<label for="university" generated="true" class="error">
										 {{ $errors->first('university') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid specialization select_other_university_div" style="display:none;">
								  <label>Other College/University<i class="required_star">*</i></label>
								  <input type="text" name="other_university" placeholder="Other" autocomplete="off" value="{{ old('other_university') }}"/>
								  <span class="help-block">
									@if($errors->has('other_university'))
									<label for="other_university" generated="true" class="error">
										 {{ $errors->first('other_university') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="add-doctor-left-box OncallStatus ConsultationType">
									<label>Consultation Type<i class="required_star">*</i></label>
									<div class="ConsultationType">
										<div class="Consultation11">
										  <label class="radio-inline"><input type="checkbox" class="oncall_status"  name="oncall_status[]" value="2">In Clinic</label>
										  <p class="form-fields  form-field-mid specialization inclinic_fees" style="display:none;">
												<input class="NumericFeild" type="text" placeholder="Consultation Fee" name="consultation_fees" autocomplete="off" value="{{ old('consultation_fees') }}"/>
												<span class="help-block">
													@if($errors->has('consultation_fees'))
													<label for="consultation_fees" generated="true" class="error">
														 {{ $errors->first('consultation_fees') }}
													</label>
													@endif
												</span>
											</p>
										</div>
										<div class="Consultation11">
										  <label class="radio-inline"><input type="checkbox" class="oncall_status" name="oncall_status[]" value="1">Tele</label>
											<p class="oncall_fee" style="display:none;">
											  <input class="form-control NumericFeild" placeholder="Tele Consultation Fee" type="text" name="oncall_fee" value="{{ old('oncall_fee') }}"/>
											  <span class="help-block">
												@if($errors->has('oncall_fee'))
												<label for="oncall_fee" generated="true" class="error">
													 {{ $errors->first('oncall_fee') }}
												</label>
												@endif
											  </span>
											</p>
										</div>
										</div>
                    <span id="oncall_status" class="help-block"></span>
								</div>
								<div class="form-fields  form-field-mid specialization">
								  <label>About Doctor (Max: 1500 Character)</label>
								  <textarea name="content" placeholder="Doctor Content" autocomplete="off" value="{{ old('content') }}"> </textarea>
								  <span class="help-block">
									@if($errors->has('content'))
									<label for="content" generated="true" class="error">
										 {{ $errors->first('content') }}
									</label>
									@endif
								  </span>
								</div>
								<!--<div class="form-fields form-field-mid pad-r0 gender">
								  <label>Tele Consultation<i class="required_star">*</i></label>
								  <div class="radio-wrap">
									<input type="radio" name="oncall_status" id="yes" value="1" class="oncall_status" />
									<label for="yes">Yes</label>
								  </div>
								  <div class="radio-wrap">
									<input type="radio" name="oncall_status" id="no" value="0" class="oncall_status" checked />
									<label for="no">No</label>
								  </div>
								</div>-->
								<div class="Account-Daitale1">
								<h2>Payment Details</h2>
								<div class="Account-Daitale2">
								<div class="form-fields form-field-mid">
									<label class="oncall_fee123">Account Number</label>
									<input class="form-control NumericFeild" placeholder="Account Number" type="text" name="acc_no" value="{{ old('acc_no') }}"/>
									<span class="help-block">
									@if($errors->has('acc_no'))
									<label for="acc_no" generated="true" class="error">
										 {{ $errors->first('acc_no') }}
									</label>
									@endif
								  </span>
								</div>
								<div class="form-fields form-field-mid">
									<div class="middle-information-box">
									  <label class="oncall_fee123">Account Name</label>
									  <input class="form-control" placeholder="Enter Account Name" type="text" name="acc_name" />
									  <span class="help-block">
										@if($errors->has('acc_name'))
										<label for="acc_name" generated="true" class="error">
											 {{ $errors->first('acc_name') }}
										</label>
										@endif
									  </span>
									</div>
								</div>
								<div class="form-fields form-field-mid">
									<label class="oncall_fee123">IFSC Code</label><input class="form-control" placeholder="Enter IFSC Code" type="text" name="ifsc_no" value="{{ old('ifsc_no') }}"/>
									  <span class="help-block">
										@if($errors->has('ifsc_no'))
										<label for="ifsc_no" generated="true" class="error">
											 {{ $errors->first('ifsc_no') }}
										</label>
										@endif
									  </span>
								</div>
								<div class="form-fields form-field-mid">
									 <label class="oncall_fee123">Bank Name</label><input class="form-control" placeholder="Enter Bank Name" type="text" name="bank_name" value="{{ old('bank_name') }}"/>
									  <span class="help-block">
										@if($errors->has('bank_name'))
										<label for="bank_name" generated="true" class="error">
											 {{ $errors->first('bank_name') }}
										</label>
										@endif
									  </span>
								</div>
								<div class="form-fields form-field-mid">
									 <label class="oncall_fee123">Paytm Number</label><input class="form-control NumericFeild" placeholder="Enter Paytm Number" type="text" name="paytm_no" value="{{ old('paytm_no') }}"/>
									  <span class="help-block">
										@if($errors->has('paytm_no'))
										<label for="paytm_no" generated="true" class="error">
											 {{ $errors->first('paytm_no') }}
										</label>
										@endif
									  </span>
								</div>
								</div>
								</div>
								<div class="doctor-img form-fields form-field-mid pad-r0">
								  <label>Profile Image</label>
									<div class="image_apload22 doc_profile_image_browse">
										<div class="image_apload">
											<img style="background-size: cover;" id="docProfileImage" class="img-update img-responsive" src="{{ URL::asset('img/camera-icon.jpg') }}" alt="icon" />
											<!--<input type="hidden" name="image_cam" value="" id="image_cam" />-->
										</div>
										<span id="fileselector">
										 <label class="btn btn-default" for="upload-file-selector">
										 	<input type="hidden" name="profile_picBlob"  class="profile_picBlob" />

											<input id="upload-file-selector" type="file" name="profile_pic" class="mylogo" onchange="openFile(event)"/>
												BROWSE
										 </label>
										</span>
									</div>

									<!--<div class="type_btn_main" style="display:none;">
										<div id="my_camera" style="border: 1px solid rgb(24, 154, 212);padding: 0 !important;overflow: hidden;display: inline-block;border-radius: 4px;"></div>

										<div class="button-wrapper"><button class="btn btn-default take_snap" type="button" onClick="take_snapshot()">Take Snapshot</button>
										<button class="btn btn-default load_cam" type="button" onClick="loadcam()">LoadCam</button></div>
									</div>-->
								</div>
								<div class="form-fields send-button doc-profile">
								  <button id="form_clinic_details" tab-name="2" type="submit" class="formSubmit">Next</button>
								</div>
        					</div>
						</div>
					</div>

                <div id="clinic_details_tab" class="clinic_details tab-pane fade">
						<div class="registration-wrap doc-register">
            <div class="form-title"><h2>Clinic Details</h2></div>
				     <div class="form-fields">
							  <label>Clinic Name(If found select from list)<i class="required_star">*</i></label>
							  <input class="clinic_nameBySearech" type="text" name="clinic_name" placeholder="Clinic Name" autocomplete="off" value="{{ old('clinic_name') }}"/>
							  <span class="help-block">
								@if($errors->has('clinic_name'))
								<label for="clinic_name" generated="true" class="error">
									 {{ $errors->first('clinic_name') }}
								</label>
								@endif
							  </span>
							   <i class="btn-reset-clinic" style="display:none;"><button type="button" class"btn btn-default">Reset</button></i>
							   <div class="suggesstion-box" style="display:none;"></div>
							</div>
              <div class="form-fields typeField">
                <label>Type<i class="required_star">*</i></label>
                <div class="radio-wrap clinicRadio">
                <input type="radio" name="practice_type" id="clinic" value="1">
                <label for="clinic">Clinic</label>
                </div>
                <div class="radio-wrap hospitalRadio">
                <input type="radio" name="practice_type" id="hospital" value="2" >
                <label for="hospital  ">Hospital</label>
                </div>
                <span class="help-block"><label for="practice_type" generated="true" class="error" style="display:none;">This field is required.</label>
					@if($errors->has('practice_type'))
					<label for="practice_type" generated="true" class="error">
						 {{ $errors->first('practice_type') }}
					</label>
					@endif
				</span>
              </div>
						    <div class="form-fields">
							  <label>Contact Number<!--<i class="required_star">*</i>--></label>
							  <input class="NumericFeild" type="text" placeholder="Contact No." name="clinic_mobile"  autocomplete="off" value="{{ old('clinic_mobile') }}"/>
							  <span class="help-block">
								@if($errors->has('clinic_mobile'))
								<label for="clinic_mobile" generated="true" class="error">
									 {{ $errors->first('clinic_mobile') }}
								</label>
								@endif
							  </span>
							</div>
							<div class="form-fields">
							  <label>Email</label>
							  <input type="text" placeholder="Email Address" name="clinic_email" autocomplete="off" value="{{ old('clinic_email') }}"/>
							  <span class="help-block">
								@if($errors->has('clinic_email'))
								<label for="clinic_email" generated="true" class="error">
									 {{ $errors->first('clinic_email') }}
								</label>
								@endif
							  </span>
							</div>
							<div class="form-fields">
							  <label>Speciality<i class="required_star">*</i></label>
							  <select name="clinic_speciality" class="searchDropDown clinic_speciality" id="clinic_speciality">
								<option value="">Select Speciality</option>
								@foreach (getSpecialityList() as $specialities)
								<option value="{{ $specialities->id }}" @if(old("clinic_speciality") == $specialities->id) selected @endif>{{ $specialities->specialities }}</option>
								@endforeach
							  </select>
							  <span class="help-block"><label for="clinic_speciality" generated="true" class="error" style="display:none;">This field is required.</label>
								@if($errors->has('clinic_speciality'))
								<label for="clinic_speciality" generated="true" class="error">
									 {{ $errors->first('clinic_speciality') }}
								</label>
								@endif
							  </span>
							</div>
            				<div class="form-fields form-field-mid specialization">
							  <label>Recommendations</label>
							  <input type="text" placeholder="Recommend" name="recommend" autocomplete="off" value="{{ old('recommend') }}"/>
							 <span class="help-block">
								@if($errors->has('recommend'))
								<label for="recommend" generated="true" class="error">
									 {{ $errors->first('recommend') }}
								</label>
								@endif
							 </span>
							</div>
            				<div class="form-fields  form-field-mid specialization">
							  <label>Website (Max: 100 Character)</label>
								<input type="text" placeholder="Website" name="website" autocomplete="off" value="{{ old('website') }}"/>
								<span class="help-block">
									@if($errors->has('website'))
									<label for="website" generated="true" class="error">
										 {{ $errors->first('website') }}
									</label>
									@endif
								</span>
							</div>

							<!--<div class="form-fields  form-field-mid specialization">
								<label>Consultation Discounted Fee (In Digits Only)</label>
								<input class="NumericFeild" type="text" placeholder="Consultation Discount Fee" name="consultation_discount" value="{{ old('consultation_discount') }}" />
								<span class="help-block">
									@if($errors->has('consultation_discount'))
									<label for="consultation_discount" generated="true" class="error">
										 {{ $errors->first('consultation_discount') }}
									</label>
									@endif
								</span>
							</div>-->
            				<div class="form-fields form-field-mid specialization">
							  <label>Note(If u give extra information)</label>
							  <textarea name="note" placeholder="Note(*If u give extra information)" value="{{ old('note') }}" autocomplete="off"></textarea>
								<span class="help-block">
									@if($errors->has('note'))
									<label for="note" generated="true" class="error">
										 {{ $errors->first('note') }}
									</label>
									@endif
								</span>
							</div>
                 			<h3>Address</h3>
                            <div class="form-fields">
							  <label>Address<i class="required_star">*</i></label>
							  <input type="text" placeholder="Address" name="address_1" autocomplete="off" value="{{ old('address_1') }}"/>
							  <span class="help-block">
								@if($errors->has('address_1'))
								<label for="address_1" generated="true" class="error">
									 {{ $errors->first('address_1') }}
								</label>
								@endif
							  </span>
							</div>
							<div></div>
							<div class="form-fields form-field-mid specialization">
							  <label>Country<i class="required_star">*</i></label>
							 <select class="country_id searchDropDown" name="country_id">
								<option value="">Select country</option>
								@foreach(getCountriesList() as $country)
									<option value="{{$country->id}}" @if($country->id == '101') selected @endif >{{$country->name}}</option>
								@endforeach
							 </select>
								<span class="help-block"><label for="country_id" generated="true" class="error" style="display:none;"></label>
								@if($errors->has('country_id'))
								<label for="country_id" generated="true" class="error">
									 {{ $errors->first('country_id') }}
								</label>
								@endif
								</span>
							</div>
							<div class="form-fields  form-field-mid specialization">
							  <label>State<i class="required_star">*</i></label>
								<select class="state_id searchDropDown" name="state_id">
								 <option value="">Select State</option>
									@foreach (getStateList(101) as $state)
										<option value="{{ $state->id }}" >{{ $state->name }}</option>
									@endforeach
								</select>
								 <span class="help-block"><label for="state_id" generated="true" class="error" style="display:none;"></label>
									@if($errors->has('state_id'))
									<label for="state_id" generated="true" class="error">
										 {{ $errors->first('state_id') }}
									</label>
									@endif
								 </span>
							</div>
							<div class="form-fields  form-field-mid specialization">
							  <label>City<i class="required_star">*</i></label>
							 <select class="city_id searchDropDown" name="city_id">
								<option value="">Select City</option>
							 </select>
							  <span class="help-block"><label for="city_id" generated="true" class="error" style="display:none;"></label>
								@if($errors->has('city_id'))
								<label for="city_id" generated="true" class="error">
									 {{ $errors->first('city_id') }}
								</label>
								@endif
							  </span>
							</div>
							<div class="form-fields  form-field-mid specialization">
							  <label>Locality</label>
							 <select class="locality_id searchDropDown" name="locality_id">
								<option value="">Select Locality</option>
							 </select>
							  <span class="help-block"><label for="locality_id" generated="true" class="error" style="display:none;"></label>
								@if($errors->has('locality_id'))
								<label for="locality_id" generated="true" class="error">
									 {{ $errors->first('locality_id') }}
								</label>
								@endif
							  </span>
							</div>
							<div class="form-fields  form-field-mid specialization">
							  <label>Zipcode</label>
								<input type="text" placeholder="Zipcode" name="zipcode" value="{{ old('zipcode') }}"/>
								<span class="help-block">
									@if($errors->has('zipcode'))
									<label for="zipcode" generated="true" class="error">
										 {{ $errors->first('zipcode') }}
									</label>
									@endif
								</span>
							</div>

							<div class="doctor-img form-fields form-field-mid">
								<label>Clinic Image</label>
								<div class="image_apload22">
								<div class="image_apload">
									<p><img style="background-size: cover;" id="docClinicImage" class="img-update img-responsive" src="{{ URL::asset('img/camera-icon.jpg') }}" alt="icon" /></p>
								</div>
								<span id="fileselector" class="clinicFIleDIv">
									<label class="btn btn-default" for="upload-clinic-file">
									<input  type="hidden" name="clinic_imageBlob" class="clinic_imageBlob" />
									<input id="upload-clinic-file" type="file" name="clinic_image" class="mylogoClinic" onchange="openFileClinic(event)"/>BROWSE
									</label>
								</span>
								</div>
							</div>

							<div class="form-fields send-button doc-profile">
							  <button id="form_opd_details" tab-name="3" class="formSubmit opd_submit" type="submit">Next</button>
							</div>
          			  </div>
                </div>
             <?php
				$increment = 900;
				$day_in_increments = range( 0, (86400 - $increment), $increment);
				$appt_durations = getAppoimentDurations();
			?>
            <div id="opd_timing_tab" class="opd_timing_tab tab-pane fade txt-center">
                 <div class="registration-wrap doc-register">
					<div class="checkbox-div complete-str">
						<h3 class="checkbox-divOpd">Opd Timings Schedule</h3>
						<div class="opd-timings-slot">
							<label>Appointment Duration</label>
							<select name="slot_duration" class="slots-data">
							@foreach($appt_durations as $idx => $dur)
								<option value="{{$dur->time}}">{{$dur->title}}</option>
							@endforeach
							</select>
							<span class="help-block"></span>
						</div>
						<div class="main-div-schedule">
                        <div class="check-wrapper checkbox-div">
							<label class="chck-container">Monday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="1">
								<span class="checkmark"></span>
							</label>
							<label class="chck-container">Tuesday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="2">
								<span class="checkmark"></span>
							</label>

							<label class="chck-container">Wednesday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="3">
								<span class="checkmark"></span>
							</label>

							<label class="chck-container">Thursday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="4">
								<span class="checkmark"></span>
							</label>

							<label class="chck-container">Friday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="5">
								<span class="checkmark"></span>
							</label>

							 <label class="chck-container">Saturday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="6">
								<span class="checkmark"></span>
							</label>

							 <label class="chck-container">Sunday
								<input type="checkbox" class="day_check" name="schedule[1][days][]" value="0">
								<span class="checkmark"></span>
							</label>
                        </div>

                        <div class="pop-up-detail">
						 <div class="sessions-div" scheduleCnt="1">
                            <label>Session 1:</label>
                            <div class="schedulingTop">
	                        	<div class="teleconsult_section">
	                              <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check"  value="1">Tel- Consultation</label>
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
                            <select name="schedule[1][timings][1][start_time]" class="session_time_up given_time active">
                                 <option value="">Start Time</option>
								 @foreach($day_in_increments as $time)
								 <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
								 @endforeach
                            </select>
							</div>
							<div class="set_error">
                             <select name="schedule[1][timings][1][end_time]" class="session_time_down given_time active">
                                <option value="">End Time</option>
								@foreach($day_in_increments as $time)
									<option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option>
								@endforeach
                            </select>
							</div>
                      </div>
						 </div>
                        </div>
                        <div class="add-more-session"><a class="addSession" href="javascript:void();">Add More Session</a></div>
						 <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
						</div>
                    </div>
					<div class="add-more-session schedule"><a href="javascript:void(0);" class="addMoreSchedule">Add More Schedule</a></div>
					<div class="tc-checkbox">
						<!--<label><input type="checkbox" name="termcondition" value="1"/>I agree with Term & Conditions<a target="_blank" href="{{route('claimTermsConditions')}}"> Term & Conditions</a></label>-->
						<span class="help-block"></span>
					</div>
					<div class="form-fields send-button doc-profile final-form-submit">
						<button type="submit" class="formSubmit ">Save</button>
					</div>
				</div>
            </div>
          {!! Form::close() !!}
		  </div>
		  </div>
		</div>
        </div>
    </div>

	<div class="modal fade" id="claimOtpModal" role="dialog" data-backdrop="static" data-keyboard="false">
	  {!! Form::open(array('route' => 'verifyClaimOtp', 'id' => 'verifyClaimOtp', 'enctype' => 'multipart/form-data')) !!}
        <input type="hidden" class="form-control" name="id"/>
		<input type="hidden" class="form-control doc_iddEdit" value=""/>
		<div class="modal-dialog">
		<div class="modal-content">

		<div class="modal-header">
          <p>Please enter otp to verification.</p>
          <span>One time password has been sent via SMS</span>
		</div>
		 <div class="modal-body">
			<div class="aad-items-billable">
				<div class="aad-items-billable-section">
					<input type="number" class="form-control" name="mobile" autocomplete="off" placeholder="change mobile" readonly />
					<input type="text" class="form-control" name="otp" autocomplete="off" placeholder="Enter Otp" />
					<span class="help-block"></span>
				</div>
			</div>
			<div class="timer_otp"><span id="timer"></span></div>
		  </div>
		  <div class="modal-footer">
			<button name="submit" type="submit" class="btn btn-default submit">Verify it</button>
			<button type="button" class="btn btn-default resendOtpSubmit" disabled>Resend</button>
		  </div>
	    </div>
		</div>
		{!! Form::close() !!}
	</div>
</div>

	<script src="{{ URL::asset('js/moment.min.js') }}"></script>
	<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>
	<script src="{{ URL::asset('js/webcam.min.js') }}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet"/>

	<script>
		var oncallstatus = [];
		jQuery.validator.addMethod("letterswithspace", function(value, element) {
			return this.optional(element) || /^[a-z][a-z\s]*$/i.test(value);
		}, "This feild should be in alphabets only.");

	  jQuery(document).on("click", ".oncall_status", function () {
		if($(this).val() == '1' && $(this).prop("checked") == true) {
		  $(".oncall_fee").show();
		  oncallstatus.push($(this).val());
		}
		else if($(this).val() == '2' && $(this).prop("checked") == true) {
			$(".inclinic_fees").show();
			oncallstatus.push($(this).val());
		}
		else if($(this).val() == '1'){
			$(".oncall_fee").hide();
		}
		else if($(this).val() == '2'){
			$(".inclinic_fees").hide();
		}
		else if($(this).val() == '1' && $(this).prop("checked") == false){
			jQuery.grep(oncallstatus, function(value) {
			return value != $(this).val();
			});
		}
		else if($(this).val() == '2' && $(this).prop("checked") == false){
			jQuery.grep(oncallstatus, function(value) {
			return value != $(this).val();
			});
		}
	});

	jQuery(document).ready(function () {
		jQuery('.loading-all').show();
			$(".yearPick").datepicker({
					dateFormat: "yy",
					// viewMode: "years",
					changeYear: true,
					// showButtonPanel: true,
					// minViewMode: "years",
					// viewSelect: 'years',
					autoclose: true,
					minDate: new Date('1900'),
					maxDate: new Date('2030')
			});
		$(".searchDropDown").select2();
    jQuery('.searchDropDown').on('change', function() {
      if (this.value != "") {
        $(this).parent('.form-fields').find('.help-block .error').hide();
      }
    });

		jQuery(document).on("click", ".addSession", function () {
           var cnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').length+1;
           var scheduleCnt = jQuery(this).parents(".main-div-schedule").find('.pop-up-detail .sessions-div').attr('scheduleCnt');
           if(cnt <= 8){
				var row =  '<div class="sessions-div" scheduleCnt="1"> <label>Session '+cnt+':</label><div class="schedulingTop"><div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label><input type="hidden" class="teleconsult" name="schedule['+scheduleCnt+'][timings]['+cnt+'][teleconsultation]" value="0"> </div><div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div></div>';
				row += '<button class="btn btn-default removeSess"  type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div>';

                jQuery(this).parents(".main-div-schedule").find('.pop-up-detail').append(row);
            }else{
                 alert("You can not add more than 8 sessions.");
            }
		});

		jQuery(document).on("click", ".removeSess", function () {
			   jQuery(this).parents(".main-div-schedule .pop-up-detail  .sessions-div").remove();
		});


		jQuery(".addMoreSchedule").click(function(){
  	        var cnt = jQuery('.main-div-schedule').length+1;
            if(cnt <= 7){
                 var row = '<div class="main-div-schedule"> <div class="check-wrapper checkbox-div"> <label class="chck-container">Monday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="1"> <span class="checkmark"></span> </label> <label class="chck-container">Tuesday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="2"> <span class="checkmark"></span> </label>  <label class="chck-container">Wednesday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="3"> <span class="checkmark"></span> </label>  <label class="chck-container">Thursday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="4"> <span class="checkmark"></span> </label>  <label class="chck-container">Friday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="5"> <span class="checkmark"></span> </label>  <label class="chck-container">Saturday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="6"> <span class="checkmark"></span> </label>  <label class="chck-container">Sunday <input type="checkbox" class="day_check" name="schedule['+cnt+'][days][]" value="0"> <span class="checkmark"></span> </label> </div> <div class="pop-up-detail"> <div class="sessions-div" scheduleCnt="1"><label>Session 1:</label><div class="schedulingTop"><div class="teleconsult_section"> <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check" value="1">Tel- Consultation</label> <input type="hidden" class="teleconsult" name="schedule['+cnt+'][timings][1][teleconsultation]" value="0"></div> <div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+cnt+'][timings][1][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div>  <div class="set_error"> <select name="schedule['+cnt+'][timings][1][start_time]" class="session_time_up given_time"> <option value="">Start Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> <div class="set_error"> <select name="schedule['+cnt+'][timings][1][end_time]" class="session_time_down given_time"> <option value="">End Time</option> @foreach($day_in_increments as $time) <option value="{{date( 'H:i', $time )}}"  >{{date( 'g:i A', $time )}}</option> @endforeach </select> </div> </div> </div> </div> <div class="add-more-session"><a class="addSession" href="javascript:void();">Add More Session</a></div><div id="msg" class="success-data alert alert-danger" style="display: none;"></div> <div class="opd-timings-schedule"><button class="btn btn-default remove" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></div></div>';
				jQuery('.complete-str').append(row);
			}
			else{
                  alert("Doctor Can not Schedule More Than 7 Days.");
            }
		});
		jQuery(document).on("click", ".remove", function () {
		   jQuery(this).parents(".main-div-schedule").remove();
		});

		jQuery(document).on("change", ".day_check", function () {
			var th = $(this), val = th.prop('value');

			if(th.is(':checked')){
				$(':checkbox[value="'+val+'"]').not($(this)).prop('checked',false);
				$(this).closest(".main-div-schedule").each(function() {
				$(this).find(".success-data").html('');
				$(this).find("#msg").hide();
					if($(this).find('.day_check:checked').length < 1) {
						$(this).find(".success-data").html('');
						$(this).find(".success-data").find("#msg").hide();
					}
				});
			}
			else{
				$(this).closest(".main-div-schedule").find(".success-data").html('');
				$(this).closest(".main-div-schedule").find("#msg").hide();
				$(this).closest(".main-div-schedule").each(function() {
				$(this).find(".success-data").html('');
				$(this).find("#msg").hide();
					if($(this).find('.day_check:checked').length < 1) {
						$(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
						$(this).find(".success-data").slideDown();
					}
				});
			}
		});


		jQuery(document).on("change", ".session_time_up", function (){
            var currevent = this;
            var apostart_time = $(currevent).val();
            var practimeslot = 15;
            var updatedEndTime =  moment(apostart_time, "HH:mm:ss").add(practimeslot, 'minutes');
            selectedvar = moment(updatedEndTime).format('HH:mm:ss');
            console.log(selectedvar);
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
			jQuery('.country_id').on('change', function() {
			  var cid = this.value;
			  var $el = $('.state_id');
			  $el.empty();
			  //jQuery('.loading-all').show();
			   jQuery("#register-form").find(".state_id").prepend($('<option></option>').html('Loading...'));
			  jQuery.ajax({
				  url: "{!! route('getStateList') !!}",
				 // type : "POST",
				dataType : "JSON",
				data:{'id':cid},
				success: function(result) {
					jQuery("#register-form").find(".city_id").html('<option value="">Select City</option>');
					jQuery("#register-form").find(".state_id").html('<option value="">Select State</option>');
					jQuery("#register-form").find(".locality_id").html('<option value="">Select Locality</option>');
					 jQuery.each(result,function(index, element) {
						   $el.append(jQuery('<option>', {
							   value: element.id,
							   text : element.name
						  }));
					  });
				  //jQuery('.loading-all').hide();
				},
				error: function(error) {
					if(error.status == 401){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
					//jQuery('.loading-all').hide();
				}
				}
			  );
			})
			jQuery(document).on("change", ".state_id", function (e) {
			  var cid = this.value;
			  var $el = jQuery('.city_id');
			  $el.empty();
			  //jQuery('.loading-all').show();
			  jQuery("#register-form").find(".city_id").prepend($('<option></option>').html('Loading...'));
			  jQuery.ajax({
				  url: "{!! route('getCityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
				  jQuery("#register-form").find(".city_id").html('<option value="">Select City</option>');
          jQuery("#register-form").find(".locality_id").html('<option value="">Select Locality</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
				  //jQuery('.loading-all').hide();
				},
				error: function(error) {
					if(error.status == 401){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
					//jQuery('.loading-all').hide();
				}
				}
			  );
			});

			jQuery(document).on("change", ".city_id", function (e) {
			  var cid = this.value;
			  var $el = jQuery('.locality_id');
			  $el.empty();
			  //jQuery('.loading-all').show();
			  jQuery("#register-form").find(".locality_id").prepend($('<option></option>').html('Loading...'));
			  jQuery.ajax({
				  url: "{!! route('getLocalityList') !!}",
				  // type : "POST",
				  dataType : "JSON",
				  data:{'id':cid},
				success: function(result){
				  jQuery("#register-form").find(".locality_id").html('<option value="">Select Locality</option>');
				  jQuery.each(result,function(index, element) {
					  $el.append(jQuery('<option>', {
						 value: element.id,
						 text : element.name
					  }));
				  });
				  //jQuery('.loading-all').hide();
				},
				error: function(error) {
					if(error.status == 401){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
					//jQuery('.loading-all').hide();
				}
				}
			  );
			});

            jQuery(document).on("click", ".tab_class_main_ul", function () {
				if($(this).attr('status') == 1) {
					if($(this).attr('id')== 1 ) {
						$(this).closest("li").addClass("active");
						$("#opd_timing_tab").removeClass('in active');
						$("#clinic_details_tab").removeClass('in active');
						$("#info_tab").addClass('in active');
					}
					else if($(this).attr('id')== 2 ) {
						$(this).closest("li").addClass("active");
						$("#info_tab").removeClass('in active');
						$("#opd_timing_tab").removeClass('in active');
						$("#clinic_details_tab").addClass('in active');
					}
					else if($(this).attr('id')== 3 ) {
						$(this).closest("li").addClass("active");
						$("#info_tab").removeClass('in active');
						$("#clinic_details_tab").removeClass('in active');
						$("#opd_timing_tab").addClass('in active');
					}
				}
				else{
					$(this).closest("li").removeClass("active");
					$('.tab_class_main_ul').each(function() {
						if($(this).attr('id') == 2) {
							$(this).closest("li").removeClass("active");
							if($(this).attr('status') == 1) {
								$(this).closest("li").addClass("active");
							}
						}
						else if($(this).attr('id') == 1) {
							if($(this).attr('status') == 1 ) {
								$(this).closest("li").addClass("active");
							}
						}
					});
					alert("Please Fill all informations and click next button");
				}
			});


            jQuery(document).on("click", ".formSubmit", function () {
				$('input[name="tab_type"]').val($(this).attr('tab-name'));
				formValidate();
            });


			function formValidate(){
				jQuery("#register-form").validate({
                   /* rules: {
						clinic_name:  {required:true,maxlength:255},
						first_name: {required:true,maxlength:30},

						last_name: {required:true,maxlength:30},
						mobile_no:{required:true,minlength:10,maxlength:10,number: true},
						clinic_mobile:{minlength:10,maxlength:10,number: true},
						email: {required: true,email: true,maxlength:50},
						clinic_email: {email: true,maxlength:30},
						practice_type: {required: true  },
						consultation_fees: {required:true,maxlength:6,number: true},
						// consultation_discount: {maxlength:6,number: true},
						address_1: {required:true,maxlength:255},
						recommend: {maxlength:150},
						website: {maxlength:100},
						qualification: {required:true,maxlength:150},
						// visit_type: {required: true},
						speciality: "required",
						clinic_speciality: "required",
						experience: {required: true,maxlength:2},
						reg_no: {required: true,maxlength:50},
						reg_year: {required: true,maxlength:4},
						reg_council: {required: true,maxlength:200},
						reg_council_other: {required: true,maxlength:200},
						other_university: {required: true,maxlength:200},
						oncall_fee: {required: true,maxlength:6,number: true},
						content: {maxlength:1500},
						last_obtained_degree: {maxlength:100},
						degree_year: {maxlength:4},
						university: {maxlength:200},
						country_id: "required",
						state_id: "required",
						city_id: "required",
						"oncall_status[]": "required",
						zipcode: {minlength:6,maxlength:6,number: true},
						//locality_id: "required",
						slot_duration: "required",
						acc_no: {
							  required: function(element) {
								  if ($('input[name=ifsc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  maxlength:16,
							  number: true
						  },
						   acc_name: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != "" ||  $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  maxlength:50,
						  },
						  ifsc_no: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != ""  || $('input[name=bank_name]').val() != "") {
									  return true;
								  } else {
									  return false;
								  }
							  },
							  minlength:6,
							  maxlength:14
						  },
						  paytm_no: {
							  minlength:10,
							  maxlength:10,
							  number: true
						  },
						  bank_name: {
							  required: function(element) {
								  if ($('input[name=acc_no]').val() != "" || $('input[name=ifsc_no]').val() != "" ) {
									  return true;
								  } else {
									  return false;
								  }
							  }
						  },
            },*/
            messages: {
            	//termcondition: "Please mention that you have read and agree to the terms and conditions.",
				acc_name: {
					required:"Please Enter Bank Account Name",
				},
				 acc_no: {
				required:"Please Enter Bank Account No.",
				maxlength:"Bank account number should not exceed 14 characters"
				},
				bank_name: "Please Enter Name of the Bank",
				ifsc_no: "Please Enter IFSC code",
				paytm_no: {
				  maxlength:"Paytm number should not exceed 10 characters"
				},
            },
            errorPlacement: function(error, element) {
                if (element.attr('name') == 'termcondition') {
					$(element).closest('.tc-checkbox').find('.help-block').append(error);
            	}
				else if (element.attr("name") == 'oncall_status[]') {
					$("#oncall_status").html(error);
				}
            	else {
					error.appendTo(element.next());
            	}
              },ignore: ":hidden",
            submitHandler: function(form) {
						var flag = true;
						var tab_number =  $('input[name="tab_type"]').val();
						$('.tab_class_main_ul').each(function() {
							 $(this).closest("li").removeClass("active");
							 if($(this).attr('id') == tab_number) {
								 $(this).attr('status',1);
								 $(this).closest("li").addClass("active");
							 }
						});
						if(tab_number == 2){

							$("#info_tab").removeClass('in active');
							$("#opd_timing_tab").removeClass('in active');
							$("#clinic_details_tab").addClass('in active');
              $("html, body").animate({
                   scrollTop: 0
               }, 600);
							flag =  false;
						}
						else if(tab_number == 3 ){
							$("#info_tab").removeClass('in active');
							$("#clinic_details_tab").removeClass('in active');
							$("#opd_timing_tab").addClass('in active');
              $("html, body").animate({
                   scrollTop: 0
               }, 600);
							flag =  false;
						}
                        $('.main-div-schedule').each(function() {
                            $(this).find(".success-data").html('');
							$(this).find("#msg").hide();
                            if($(this).find('.day_check:checked').length < 1){
                                $(this).find(".success-data").append('<p>Please select at least one Schedule.</p>');
                                $(this).find(".success-data").slideDown();
                                flag = false;
                            }
                        });
						$('.given_time').each(function (){
							if($(this).val()==''){
								$(this).next(".help-block").remove();
							    $(this).after('<span style="width:100%" class="help-block">This field is required</span>');
							   	flag = false;
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
						if(flag == true) {
							var otp_id = jQuery("#claimOtpModal").find('input[name="id"]').val();
							var mobile_number = jQuery("#register-form").find('input[name="mobile_no"]').val();
							if(otp_id){
								form.submit();
							}
							else{
								sendOtp(mobile_number,otp_id);
							}
						}
						else {
							return false;
						}
                    }
                });
			}
		});
		jQuery(document).on("change", ".given_time", function () {
			if($(this).val()=='') {
				$(this).next(".help-block").remove();
				$(this).after('<span style="width:100%" class="help-block">This field is required</span>');
			}
			else{
				$(this).next(".help-block").remove();
			}
		});
		jQuery(document).on("click", ".resendOtpSubmit", function () {
			$(this).attr('disabled',true);
			jQuery("#claimOtpModal").find('input[name="otp"]').val("");
			var mobile_number  = jQuery("#claimOtpModal").find('input[name="mobile"]').val();
			var otp_id  = jQuery("#claimOtpModal").find('input[name="id"]').val();
			sendOtp(mobile_number,otp_id);
		});
		function sendOtp(mobile_number,otp_id) {
			jQuery('.loading-all').show();
			jQuery("#claimOtpModal").find('input[name="mobile"]').val(mobile_number);
			jQuery.ajax({
				type: "POST",
				dataType : "HTML",
				url: "{!! route('sendClaimOtp')!!}",
				data:{'mobile_number':mobile_number,'id':otp_id},
				success: function(data) {
					jQuery('.loading-all').hide();
					if(data){
						jQuery("#claimOtpModal").find('input[name="id"]').val(data);
						jQuery("#claimOtpModal").find('input[name="mobile"]').attr('readonly',true);
						jQuery("#claimOtpModal").find('input[name="otp"]').val("");
						jQuery("#claimOtpModal").find('.submit').attr('disabled',false);
						jQuery("#claimOtpModal").find('input[name="otp"]').focus();
						jQuery("#claimOtpModal").modal("show");

						timer(120);
					}
				},
				error: function(error) {
					if(error.status == 401) {
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
						//alert("Oops Something goes Wrong.");
					}
				}
			});
		}
		jQuery("#verifyClaimOtp").validate({
			rules: {
				otp:{required:true,minlength:6,maxlength:6,number: true},
			},
			messages: {
			},
			errorPlacement: function(error, element) {
				 error.appendTo(element.next());
			},
			submitHandler: function(form){
				$(form).find('.submit').attr('disabled',true);
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('verifyClaimOtp')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						if(data==1) {
							jQuery('.loading-all').hide();
							jQuery("#register-form").find('input[name="mobile_no"]').val(jQuery("#claimOtpModal").find('input[name="mobile"]').val());
							var docId = jQuery("#claimOtpModal").find('.doc_iddEdit').val();
							jQuery("#verifyClaimOtp").trigger('reset');
							$(form).find('.submit').attr('disabled',false);
							if(docId) {
								jQuery('#claimOtpModal').modal('hide');
								jQuery('.loading-all').show();
								var url = '{!! url("/claim-doctor?id='+btoa(docId)+'&mbl=true") !!}';
								window.location = url;
							}
							else {
								jQuery('#tele_status').val(oncallstatus.toString());
								finalFormSubmit();
								jQuery('#claimOtpModal').modal('hide');
							}
						}
						else if(data==2) {
							jQuery('.loading-all').hide();
							$(form).find('.submit').attr('disabled',false);
							alert("Wrong Otp");
						}
						else {
							jQuery('.loading-all').hide();
							$(form).find('.submit').attr('disabled',false);
							alert("System Problem");
						}
					},
					error: function(error){
						jQuery('.loading-all').hide();
						if(error.status == 401){
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
		function finalFormSubmit(form) {
			jQuery('.loading-all').show();
			jQuery('.formSubmit').attr('disabled',true);
			jQuery("#register-form").submit();
		}


		let timerOn = true;
		function timer(remaining) {
		  var m = Math.floor(remaining / 60);
		  var s = remaining % 60;
		  m = m < 10 ? '0' + m : m;
		  s = s < 10 ? '0' + s : s;
		  document.getElementById('timer').innerHTML = m + ':' + s;
		  remaining -= 1;
		  if(remaining >= 0 && timerOn) {
			setTimeout(function() {
				timer(remaining);
			}, 1000);
			return;
		  }
		  if(!timerOn) {
			return;
		  }
		  else{
			$("#claimOtpModal").find("#timer").html('');
			jQuery("#claimOtpModal").find('input[name="mobile"]').attr('readonly',false);
			jQuery("#claimOtpModal").find('.resendOtpSubmit').attr('disabled',false);
		  }
		}
		var otpSendRequest;
		jQuery(document).on("keyup", ".verifyDocData", function () {
			var docInfo  = $(this).val();
			var v_type  = $(this).attr('v_type');
			if(v_type == 1){
				if(docInfo.length > 6) {
					verifyDocDetail(docInfo,v_type);
				}
			}
			else{
				verifyDocDetail(docInfo,v_type);
			}
		});
		function verifyDocDetail(docInfo,v_type) {
			if(otpSendRequest) {
				otpSendRequest.abort();
			}
			otpSendRequest = jQuery.ajax({
				type: "POST",
				dataType : "JSON",
				url: "{!! route('verifyDocDetails')!!}",
				data:{'docInfo':docInfo,'v_type':v_type},
				success: function(data) {
					if(data.status == 1) {
						if(v_type == 1) {
							if(data.varify_status == '0') {
								if(confirm("This mobile no. already exists, please enter the OTP & Click OK to edit your profile")) {
									var otp_id = jQuery("#claimOtpModal").find('input[name="id"]').val();
									jQuery("#claimOtpModal").find('.doc_iddEdit').val(data.id);
									jQuery("#claimOtpModal").find('input[name="mobile"]').hide();
									jQuery("#register-form").find('input[name="mobile_no"]').val('');
									sendOtp(data.mobile_no,otp_id);
								}
								else{
									jQuery("#register-form").find('input[name="mobile_no"]').val('');
								}
							}
							else{
								alert("Dr. "+data.name+" is already health gennie verified doctor with this mobile number.");
								jQuery("#register-form").find('input[name="mobile_no"]').val('');
							}
						}
						else{
							if(data.varify_status == '0') {
								if(confirm("This email already exists, please enter the OTP & Click OK to edit your profile")) {
									var otp_id = jQuery("#claimOtpModal").find('input[name="id"]').val();
									jQuery("#claimOtpModal").find('.doc_iddEdit').val(data.id);
									jQuery("#claimOtpModal").find('input[name="mobile"]').hide();
									jQuery("#register-form").find('input[name="email"]').val('');
									sendOtp(data.mobile_no,otp_id);
								}
								else{
									jQuery("#register-form").find('input[name="email"]').val('');
								}
							}
							else{
								alert("Dr. "+data.name+" is already health gennie verified doctor with this email.");
								jQuery("#register-form").find('input[name="email"]').val('');
							}
						}
					}
				  jQuery('.loading-all').hide();
				},
				error: function(error) {
					jQuery('.loading-all').hide();
					if(error.status == 401){
						//alert("Session Expired,Please logged in..");
						location.reload();
					}
					else{
					//	alert("Oops Something goes Wrong.");
					}
				}
			});
		}

		function openFile(event) {
			$("#form_clinic_details").attr("disabled",false);
			var input = event.target;
            var FileSize = input.files[0].size / 1024 / 1024;
            var type = input.files[0].type;
            var ext = input.files[0].name.split('.').pop().toLowerCase();
			var reader = new FileReader();

      if(FileSize > 10) {
          $('#docProfileImage').next(".help-block").remove();
          $('#docProfileImage').after('<span style="width:100%" class="help-block">Allowed file size exceeded. (Max. 3 MB)</span>');
          $("#form_clinic_details").attr("disabled",true);
			}
		    else if($.inArray(ext, ['png','jpg','jpeg']) >= 0) {
				$("#form_clinic_details").attr("disabled",false);
				$('#docProfileImage').next(".help-block").remove();
      			reader.addEventListener("load", function () {
					$('#docProfileImage').attr('src',reader.result);
				},false);
      			reader.readAsDataURL(input.files[0]);
      			// CANVAS RESIZING
				canvasResize(input.files[0], {
					width: 300,
					height: 300,
					crop: false,
					quality: 80,
					rotate: 0,
					callback: function(data, width, height) {
		                // SHOW AS AN IMAGE
						// =================================================
						//var img = new Image();
						//$(img).attr('src', data);
						console.log(data);
						var raw_image_data = data.replace(/^data\:image\/\w+\;base64\,/, '');
						$('.profile_picBlob').val(raw_image_data);
						$('#register-form').find('input[name="profile_pic"]').val('');
	                }
				});
            }
            else {
                $('#register-form').find('input[name="profile_pic"]').val('');
                $('#docProfileImage').next(".help-block").remove();
                $('#docProfileImage').after('<span style="width:100%" class="help-block">Only formats are allowed : (jpeg,jpg,png)</span>');
			    $("#form_clinic_details").attr("disabled",true);
		    }
		}

		function openFileClinic(event) {
			$("#form_opd_details").attr("disabled",false);
			var input = event.target;
            var FileSize = input.files[0].size / 1024 / 1024;
            var type = input.files[0].type;
            var ext = input.files[0].name.split('.').pop().toLowerCase();
			var reader = new FileReader();

            if(FileSize > 10) {
                $('#docClinicImage').next(".help-block").remove();
                $('#docClinicImage').after('<span style="width:100%" class="help-block">Allowed file size exceeded. (Max. 3 MB)</span>');
                $("#form_opd_details").attr("disabled",true);
			}
		    else if($.inArray(ext, ['png','jpg','jpeg']) >= 0) {
				$("#form_opd_details").attr("disabled",false);
				$('#docClinicImage').next(".help-block").remove();
      			reader.addEventListener("load", function () {
					$('#docClinicImage').attr('src',reader.result);
				},false);
      			reader.readAsDataURL(input.files[0]);
      			// CANVAS RESIZING
				canvasResize(input.files[0], {
					width: 300,
					height: 300,
					crop: false,
					quality: 80,
					rotate: 0,
					callback: function(data, width, height) {
		                // SHOW AS AN IMAGE
						// =================================================
						//var img = new Image();
						//$(img).attr('src', data);
						console.log(data);
						var raw_image_data = data.replace(/^data\:image\/\w+\;base64\,/, '');
						$('.clinic_imageBlob').val(raw_image_data);
						$('#register-form').find('input[name="clinic_image"]').val('');
	                }
				});
            }
            else {
                $('#register-form').find('input[name="clinic_image"]').val('');
                $('#docClinicImage').next(".help-block").remove();
                $('#docClinicImage').after('<span style="width:100%" class="help-block">Only formats are allowed : (jpeg,jpg,png)</span>');
			    $("#form_opd_details").attr("disabled",true);
		    }
		}

		/*	jQuery(document).ready(function () {
				Webcam.set({
						width: 292,
						height: 224,
						dest_width: 292,
						dest_height: 224,
						image_format: 'jpg',
						jpeg_quality:100,
						force_flash: false,
						flip_horiz: false,
						fps: 45
				});
				setTimeout(function(){
					Webcam.attach('#my_camera');
				},1000);
				Webcam.snap( function(data_uri) {
					if( /iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
						var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
						document.getElementById('image_cam').value = raw_image_data;
						document.getElementById('my_camera').innerHTML = '<img src="'+data_uri+'"/>';
					}
				});
				Webcam.on('error', function(err) {
					$('.type_btn_main').hide();
					$('.doc_profile_image_browse').show();
					$('#image_cam').val('');
				});
				$('.doc_profile_image_browse').hide();
				$('.type_btn_main').show();
			});

			function take_snapshot() {
				Webcam.snap( function(data_uri) {
						var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
						document.getElementById('image_cam').value = raw_image_data;
						document.getElementById('my_camera').innerHTML = '<img src="'+data_uri+'"/>';
				});
			}

			function loadcam(){
				  Webcam.attach( '#my_camera' );
			}  */

      function addDupHiddenField(field, type){
        var name = field.prop('name');
        var parent = field.parent();

        if (type == 1) {

          if (field.prop('disabled')) {

            var name = field.attr('original-name');
            parent.find('input[type="hidden"][name='+name+']').val(field.val());
          }
          else {
            field.attr('original-name', name);
            var $hiddenInput = $('<input/>',
                      {   type  : 'hidden',
                        name  : name,
                        value : field.val()
                      });
            parent.append( $hiddenInput );
            field.prop({ name : name + "_1",
                     disabled : true });
          }

        }
        else if (type == 2 ) {
          if(field.prop('disabled')){
            var name = field.attr('original-name');
            parent.find('input[type="hidden"][name='+name+']')
               .remove();
            field.prop({name : name,
                    disabled : false});
            field.removeAttr('original-name');
          }
        }



      }


			jQuery(document).on("click", ".container", function () {
				 jQuery(this).find(".suggesstion-box").hide();
				 jQuery(this).find(".suggesstion-box ul").remove();
			});
		var clinicSearchRequest;
  		jQuery(document).on("keyup paste", ".clinic_nameBySearech", function () {
  		  var currSearch = this;
			if(jQuery(this).val().length <= 0) {
				$('#register-form').find('input[name="clinic_id"]').val('');
				 jQuery(".container").find(".suggesstion-box").hide();
				 jQuery(".container").find(".suggesstion-box ul").remove();
			}
			if(clinicSearchRequest) {
				clinicSearchRequest.abort();
			}
			if(jQuery(this).val().length >= 3) {
			  clinicSearchRequest = jQuery.ajax({
			  type: "POST",
			  url: "{!! route('getClinics') !!}",
			  data: {'searchText':jQuery(this).val()},
			  beforeSend: function(){
				jQuery(currSearch).css("background","#FFF url(/img/LoaderIcon.gif) no-repeat rigt");
			  },
			  success: function(data){
			  console.log(data);
				  var liToAppend = "";
					if(data.length > 0){
					  jQuery.each(data,function(k,v) {
						 var city_id = null;
						 var locality_id = null;
						 var city = '';
				         var clinic_name = '';
						 var practice_type = null;
						 var locality = '';
						 var clinic_speciality = null;
						 var pic_width  = 15;
						 var pic_height = 12;
						 var search_pic = '{{ URL::asset("img/search-dd.png") }}';
						 if(v.locality_id){
							locality = v.locality_id.name;
							locality_id = v.locality_id.id;
						 }
						 if(v.clinic_speciality){
							clinic_speciality = v.clinic_speciality;
						 }
						 if(v.city_id){
							city = v.city_id.name;
							city_id = v.city_id.id;
						 }
						 if(v.clinic_name){
							clinic_name = v.clinic_name;
						 }
				 if(v.practice_type){
							practice_type = v.practice_type;
						 }
						if(v.clinic_image_url != null){
							search_pic = v.clinic_image_url;
							pic_width  = 30;
							pic_height  = 30;
						}
						liToAppend += '<li value="'+v.id+'" p_id="'+v.practice_id+'" clinic_name="'+v.clinic_name+'" practice_type="'+practice_type+'" city_id="'+city_id+'" locality_id="'+locality_id+'" clinic_speciality="'+clinic_speciality+'" clinic_image="'+v.clinic_image+'"  clinic_image_url="'+v.clinic_image_url+'" clinic_mobile="'+v.clinic_mobile+'" clinic_email="'+v.clinic_email+'" website="'+v.website+'" address_1="'+v.address_1+'" country_id="'+v.country_id+'" state_id="'+v.state_id+'" zipcode="'+v.zipcode+'" class="dataListClinics"><i class="icon-clinicImage-pic"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail-clinic"><span class="txt">'+v.clinic_name+'</span><span class="spec">' +locality+' '+city+'</span></div></li>';
					  });
					}else{
						//liToAppend += '<li value="0">"'+jQuery(currSearch).val()+'" Clinic Not Found.</li>';
				  }
				  jQuery(currSearch).closest(".form-fields").find(".suggesstion-box").show();
				  jQuery(currSearch).closest(".form-fields").find(".suggesstion-box").html('<ul>'+liToAppend+'</ul>');
			  }
			  });
		}
  		});

  		jQuery(document).on("click", ".dataListClinics", function () {
			alert('Your profile has been created as a Visiting doctor under this "'+jQuery(this).find('.txt').text()+'"');
  			$('#register-form').find('input[name="clinic_id"]').val(jQuery(this).attr("p_id"));
  			jQuery(this).closest(".form-fields").find(".clinic_nameBySearech").val(jQuery(this).find('.txt').text());
			jQuery(this).closest(".form-fields").find(".clinic_nameBySearech").attr('readonly',true);

        if(jQuery(this).attr("practice_type") != "null") {
            practice_type = jQuery(this).attr("practice_type");
            if (practice_type == 2) {
              $('#register-form').find('#hospital').prop('checked',true);
              $('#register-form').find('.clinicRadio').hide();

            }
            else {
              $('#register-form').find('#clinic ').prop('checked',true);
              $('#register-form').find('.hospitalRadio').hide();
            }
        }


  			if(jQuery(this).attr("country_id") != "null") {
          country_data = jQuery(this).attr("country_id");
          $('#register-form').find('.country_id option[value="'+country_data+'"]').prop('selected',true);
  				var countryField = $('#register-form').find('.country_id');
			addDupHiddenField(countryField, 1);

        }
        else {
          var countryField = $('#register-form').find('.country_id');
          addDupHiddenField(countryField, 1);
        }
  			if(jQuery(this).attr("state_id") != "null") {
			  var state_data = jQuery(this).attr("state_id");
			  $('#register-form').find('.state_id').val(state_data).trigger('change');
			  var stateField = $('#register-form').find('.state_id');
			  addDupHiddenField(stateField, 1);
              }
              else {
                var stateField = $('#register-form').find('.state_id');
                addDupHiddenField(stateField, 1);
              }
  			if(jQuery(this).attr("city_id") != "null") {
          var city_data = jQuery(this).attr("city_id");
        setTimeout(function(){
            $('#register-form').find('.city_id').val(city_data).trigger('change');
            var cityField = $('#register-form').find('.city_id');
            addDupHiddenField(cityField, 1);
          },1000);
            }
              else {
                var cityField = $('#register-form').find('.city_id');
                addDupHiddenField(cityField, 1);
              }

  			if(jQuery(this).attr("locality_id") != "null") {
          var locality_data = jQuery(this).attr("locality_id");
          setTimeout(function(){
            $('#register-form').find('.locality_id').val(locality_data).trigger('change');
            var localityField = $('#register-form').find('.locality_id');
            addDupHiddenField(localityField, 1);
          },1500);
            }
            else {
                var localityField = $('#register-form').find('.locality_id');
                addDupHiddenField(localityField, 1);
            }

			if(jQuery(this).attr("clinic_speciality") != "null") {
			  var clinic_speciality = jQuery(this).attr("clinic_speciality");
			  setTimeout(function(){
				$('#register-form').find('.clinic_speciality').val(clinic_speciality).trigger('change');
				var clinicSpeField = $('#register-form').find('.clinic_speciality');
				addDupHiddenField(clinicSpeField, 1);
			  },1500);
            }
		  else {
			var clinicSpeField = $('#register-form').find('.clinic_speciality');
			addDupHiddenField(clinicSpeField, 1);
		  }
  			if(jQuery(this).attr("clinic_mobile") != "null") {
  				$('#register-form').find('input[name="clinic_mobile"]').val(jQuery(this).attr("clinic_mobile"));
  			}
  			if(jQuery(this).attr("clinic_email") != "null") {
  				$('#register-form').find('input[name="clinic_email"]').val(jQuery(this).attr("clinic_email"));
  			}
  			if(jQuery(this).attr("website") != "null") {
  				$('#register-form').find('input[name="website"]').val(jQuery(this).attr("website"));
  			}
  			if(jQuery(this).attr("address_1") != "null") {
  				$('#register-form').find('input[name="address_1"]').val(jQuery(this).attr("address_1"));
  				$('#register-form').find('input[name="address_1"]').attr('readonly',true);
  			}
  			if(jQuery(this).attr("zipcode") != "null") {
  				$('#register-form').find('input[name="zipcode"]').val(jQuery(this).attr("zipcode"));
  			}
  			if(jQuery(this).attr("clinic_image") != "null" && jQuery(this).attr("clinic_image_url") != "null") {
  				$('#register-form').find('#docClinicImage').attr('src',jQuery(this).attr("clinic_image_url"));
  				$('#register-form').find('.clinicFIleDIv').hide();
  			}
			else{
				$('#register-form').find('#docClinicImage').attr('src',"{{ URL::asset('img/camera-icon.jpg') }}");
  				$('#register-form').find('.clinicFIleDIv').show();
			}
  			$('#register-form').find('input[name="clinic_mobile"]').attr('readonly',true);
  			$('#register-form').find('input[name="clinic_email"]').attr('readonly',true);
  			$('#register-form').find('input[name="website"]').attr('readonly',true);
  			$('#register-form').find('input[name="zipcode"]').attr('readonly',true);

  			$('#register-form').find(".btn-reset-clinic").show();
  			jQuery(this).closest(".suggesstion-box").hide();
  			jQuery(this).closest(".suggesstion-box ul").remove();
  		});

  		jQuery(document).on("change", ".select_reg_council", function () {
			if($(this).val() == '0'){
				$(".reg_council_other_div").show();
			}
			else {
				$(".reg_council_other_div").hide();
			}
		});

		jQuery(document).on("change", ".select_university", function () {
			if($(this).val() == '0') {
				$(".select_other_university_div").show();
			}
			else {
				$(".select_other_university_div").hide();
			}
		});
  		jQuery(document).on("click", ".btn-reset-clinic", function () {
		jQuery(".clinic_nameBySearech").attr('readonly',false);
        $('#register-form').find('input[name="clinic_id"]').val('');
  		jQuery(".clinic_nameBySearech").val('');


        var countryField = $('#register-form').find('.country_id');
        addDupHiddenField(countryField, 2);

        var stateField = $('#register-form').find('.state_id');
        addDupHiddenField(stateField, 2);

        var cityField = $('#register-form').find('.city_id');
        addDupHiddenField(cityField, 2);

        var localityField = $('#register-form').find('.locality_id');
        addDupHiddenField(localityField, 2);

		var clinicSpeField = $('#register-form').find('.clinic_speciality');
        addDupHiddenField(clinicSpeField, 2);

        $('#register-form').find('.clinicRadio').show();
        $('#register-form').find('.hospitalRadio').show();
        $('#register-form').find('#clinic').prop('checked', false);
        $('#register-form').find('#hospital').prop('checked', false);



        $('#register-form').find('.country_id').val('101').trigger('change');
        $('#register-form').find('.state_id').val('33').trigger('change');
        $('#register-form').find('.city_id').val('').trigger('change');
		$('#register-form').find('.locality_id').val('').trigger('change');
        $('#register-form').find('.clinic_speciality').val('').trigger('change');

  				$('#register-form').find('input[name="clinic_mobile"]').val('');
  				$('#register-form').find('input[name="clinic_email"]').val('');
  				$('#register-form').find('input[name="website"]').val('');
  				$('#register-form').find('input[name="address_1"]').val('');
  				$('#register-form').find('input[name="address_1"]').attr('readonly',false);
  				$('#register-form').find('input[name="zipcode"]').val('');
  				$('#register-form').find('#docClinicImage').attr('src','{{ URL::asset('img/camera-icon.jpg') }}');
  				$('#register-form').find('.clinicFIleDIv').show();

  			$('#register-form').find('input[name="clinic_mobile"]').attr('readonly',false);
  			$('#register-form').find('input[name="clinic_email"]').attr('readonly',false);
  			$('#register-form').find('input[name="website"]').attr('readonly',false);
  			$('#register-form').find('input[name="zipcode"]').attr('readonly',false);
			$('#register-form').find('input[name="city_id"]').attr('disabled',false);
  			$('#register-form').find('input[name="locality_id"]').attr('disabled',false);
  			$('#register-form').find('input[name="clinic_speciality"]').attr('disabled',false);
  			$('#register-form').find(".btn-reset-clinic").hide();
  		});
  		$(document).ready(function(){
	  	 $(".teleconsult_check").each(function(){
		    if($(this).is(':checked')){
			    $(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');
			}
		 });
	  });
  		jQuery(document).on("change", ".teleconsult_check", function (){
		 if($(this).is(':checked')){
		    $(this).closest('.teleconsult_section').find('.teleconsult').val(1);
		    $(this).closest('.sessions-div').find('.teleconsult_duration').show();
		    var slot_duration = '5';
		    $(this).closest('.sessions-div').find('.teleconsult_duration .slots').val(slot_duration).trigger('change');
		    $(this).closest('.sessions-div').find('.set_error').addClass('CommonWidth');

		  }
		  else {
		     $(this).closest('.teleconsult_section').find('.teleconsult').val(0);
		     $(this).closest('.sessions-div').find('.teleconsult_duration').hide();
		     $(this).closest('.sessions-div').find('.teleconsult_duration .slots').prop('selectedIndex',0);
		     $(this).closest('.sessions-div').find('.set_error').removeClass('CommonWidth');
		  }
		});
		$(window).on('load', function() {
		  jQuery('.loading-all').hide();
		});
	</script>
    <script src="{{ URL::asset('js/zepto.min.js') }}"></script>
	<script src="{{ URL::asset('js/binaryajax.js') }}"></script>
	<script src="{{ URL::asset('js/exif.js') }}"></script>
	<script src="{{ URL::asset('js/canvasResize.js') }}"></script>
@endsection
