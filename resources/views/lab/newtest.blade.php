@extends('layouts.Masters.Master')
@section('title', 'Cart')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<style media="screen">
.wizard,
.tabcontrol
{
    display: block;
    width: 100%;
    overflow: hidden;
}

.wizard a,
.tabcontrol a
{
    outline: 0;
}

.wizard ul,
.tabcontrol ul
{
    list-style: none !important;
    padding: 0;
    margin: 0;
}

.wizard ul > li,
.tabcontrol ul > li
{
    display: block;
    padding: 0;
}

/* Accessibility */
.wizard > .steps .current-info,
.tabcontrol > .steps .current-info
{
    position: absolute;
    left: -999em;
}

.wizard > .content > .title,
.tabcontrol > .content > .title
{
    position: absolute;
    left: -999em;
}



/*
    Wizard
*/

.wizard > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.wizard.vertical > .steps
{
    display: inline;
    float: left;
    width: 30%;
}

.wizard > .steps .number
{
    font-size: 1.429em;
}

.wizard > .steps > ul > li
{
    width: 25%;
}

.wizard > .steps > ul > li,
.wizard > .actions > ul > li
{
    float: left;
}

body .wizard .steps ul li {
    width: 20%;
    float: left;
}
.order-overview .left {
    float: left;
    width: 100%;
    margin-top: 15px;
}
.wizard.vertical > .steps > ul > li
{
    float: none;
    width: 100%;
}

.wizard > .steps a,
.wizard > .steps a:hover,
.wizard > .steps a:active
{
    display: block;
    width: auto;
    margin: 0 0.5em 0.5em;
    padding: 1em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .steps .disabled a,
.wizard > .steps .disabled a:hover,
.wizard > .steps .disabled a:active
{
    background: #eee;
    color: #aaa;
    cursor: default;
}

.wizard > .steps .current a,
.wizard > .steps .current a:hover,
.wizard > .steps .current a:active
{
    background: #fcfcfc;
    color: #111;
    cursor: default;
}

.wizard > .steps .done a,
.wizard > .steps .done a:hover,
.wizard > .steps .done a:active
{
    background: #4CAF50;
    color: #fff;
}

.wizard > .steps .error a,
.wizard > .steps .error a:hover,
.wizard > .steps .error a:active
{
    background: #ff3111;
    color: #fff;
}

.wizard > .content
{
    background: #eee;
    display: block;
    margin: 0.5em;
    min-height: 35em;
    overflow: hidden;
    position: relative;
    width: auto;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard.vertical > .content
{
    display: inline;
    float: left;
    margin: 0 2.5% 0.5em 2.5%;
    width: 65%;
}

.wizard > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.wizard > .content > .body ul
{
    list-style: disc !important;
}

.wizard > .content > .body ul > li
{
    display: list-item;
}

.wizard > .content > .body > iframe
{
    border: 0 none;
    width: 100%;
    height: 100%;
}

.wizard > .content > .body input
{
    display: block;
    border: 1px solid #ccc;
}

.wizard > .content > .body input[type="checkbox"]
{
    display: inline-block;
	height:inherit;
	border-radius: inherit;
	border:0px;
	float:none;
	width:inherit;
	line-height:inherit;
}

.wizard > .content > .body input.error
{
    background: rgb(251, 227, 228);
    border: 1px solid #fbc2c4;
    color: #8a1f11;
}

.wizard > .content > .body label
{
    display: inline-block;
    margin-bottom: 0.5em;
}

.wizard > .content > .body label.error
{
    color: #8a1f11;
    display: inline-block;
    margin-left: 1.5em;
}

.wizard > .actions
{
    position: relative;
    display: block;
    text-align: right;
    width: 100%;
}

.wizard.vertical > .actions
{
    display: inline;
    float: right;
    margin: 0 2.5%;
    width: 95%;
}

.wizard > .actions > ul
{
    display: inline-block;
    text-align: right;
}

.wizard > .actions > ul > li
{
    margin: 0 0.5em;
}

.wizard.vertical > .actions > ul > li
{
    margin: 0 0 0 1em;
}

.wizard > .actions a,
.wizard > .actions a:hover,
.wizard > .actions a:active
{
    background: #2184be;
    color: #fff;
    display: block;
    padding: 0.5em 1em;
    text-decoration: none;

    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
}

.wizard > .actions .disabled a,
.wizard > .actions .disabled a:hover,
.wizard > .actions .disabled a:active
{
    background: #eee;
    color: #aaa;
}

.wizard > .loading
{
}

.wizard > .loading .spinner
{
}



/*
    Tabcontrol
*/

.tabcontrol > .steps
{
    position: relative;
    display: block;
    width: 100%;
}

.tabcontrol > .steps > ul
{
    position: relative;
    margin: 6px 0 0 0;
    top: 1px;
    z-index: 1;
}

.tabcontrol > .steps > ul > li
{
    float: left;
    margin: 5px 2px 0 0;
    padding: 1px;

    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
    -moz-border-radius-topleft: 5px;
    -moz-border-radius-topright: 5px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.tabcontrol > .steps > ul > li:hover
{
    background: #edecec;
    border: 1px solid #bbb;
    padding: 0;
}

.tabcontrol > .steps > ul > li.current
{
    background: #fff;
    border: 1px solid #bbb;
    border-bottom: 0 none;
    padding: 0 0 1px 0;
    margin-top: 0;
}

.tabcontrol > .steps > ul > li > a
{
    color: #5f5f5f;
    display: inline-block;
    border: 0 none;
    margin: 0;
    padding: 10px 30px;
    text-decoration: none;
}

.tabcontrol > .steps > ul > li > a:hover
{
    text-decoration: none;
}

.tabcontrol > .steps > ul > li.current > a
{
    padding: 15px 30px 10px 30px;
}

.tabcontrol > .content
{
    position: relative;
    display: inline-block;
    width: 100%;
    height: 35em;
    overflow: hidden;
    border-top: 1px solid #bbb;
    padding-top: 20px;
}

.tabcontrol > .content > .body
{
    float: left;
    position: absolute;
    width: 95%;
    height: 95%;
    padding: 2.5%;
}

.tabcontrol > .content > .body ul
{
    list-style: disc !important;
}

.tabcontrol > .content > .body ul > li
{
    display: list-item;
}

#MyCartPage input[type="text"],
#MyCartPage input[type="email"],
#MyCartPage input[type="tel"],
#MyCartPage input[type="url"],
#MyCartPage textarea,
#MyCartPage button[type="submit"] {
  font: 400 12px/16px "Titillium Web", Helvetica, Arial, sans-serif;
}

#MyCartPage {
  background: #F9F9F9;
  padding: 25px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}

#MyCartPage h3 {
  display: block;
  font-size: 30px;
  font-weight: 300;
  margin-bottom: 10px;
}

#MyCartPage h4 {
  margin: 5px 0 15px;
  display: block;
  font-size: 13px;
  font-weight: 400;
}

#MyCartPage input[type="text"],
#MyCartPage input[type="email"],
#MyCartPage input[type="tel"],
#MyCartPage input[type="url"],
#MyCartPage textarea {
  width: 100%;
  border: 1px solid #ccc;
  background: #FFF;
  margin: 0 0 5px;
  padding: 10px;
}

#MyCartPage input[type="text"]:hover,
#MyCartPage input[type="email"]:hover,
#MyCartPage input[type="tel"]:hover,
#MyCartPage input[type="url"]:hover,
#MyCartPage textarea:hover {
  -webkit-transition: border-color 0.3s ease-in-out;
  -moz-transition: border-color 0.3s ease-in-out;
  transition: border-color 0.3s ease-in-out;
  border: 1px solid #aaa;
}

#MyCartPage textarea {
  height: 100px;
  max-width: 100%;
  resize: none;
}

#MyCartPage button[type="submit"] {
 height: 40px;
    background: #0f5b92;
    color: #fff;
    border: 0px;
    padding: 0px 20px;
    border-radius: 4px;
    font-size: 16px;
    margin-right: 10px;
}

#MyCartPage button[type="submit"]:hover {
  background: #43A047;
  -webkit-transition: background 0.3s ease-in-out;
  -moz-transition: background 0.3s ease-in-out;
  transition: background-color 0.3s ease-in-out;
}

#MyCartPage button[type="submit"]:active {
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
}



#MyCartPage input:focus,
#MyCartPage textarea:focus {
  outline: 0;
  border: 1px solid #aaa;
}

.steps > ul > li > a,
.actions li a {
    padding: 10px;
    text-decoration: none;
    margin: 1px;
    display: block;
    color: #777;
}
.steps > ul > li,
.actions li {
    list-style:none;
}
</style>
<?php
      $increment = 900;
      $day_in_increments = range( 0, (86400 - $increment), $increment);
      $appt_durations  = getAppoimentDurations();
 ?>
<div class="lab-test lab-test-profile">
  <div class="container-fluid">
    <div class="container">
      <div class="tab-cart-wrapper">
        <div class="alert alert-danger serverError" style="display:none;">
          <strong>Alert!</strong><span class="msg">Something Missing Please Try Again Later</span>
        </div>
        <?php $year = range(1950,date("Y")); ?>

        <div class="tabs-cart">
          <form id="PersonalInfo" >
              <div class="order-overview startdiv">
                <div class="left">
                  <div id="MyCartPage">
                      <div>
                          <h3>Personal Info</h3>
                          <section>
                              <div class="patient-details">
                                <input type="hidden" name="status" value="0">
                                <input type="hidden" name="order_status" value="0">
                            <input type="hidden" name="Margin" class="tMargin" valuenewtest=""/>
                                <div class="input-wrapper">
                                <label>Full Name<i class="required_star">*</i></label>
                                <input type="text" placeholder="Name" name="name" value="{{$user->first_name.' '.$user->last_name}}" />
                                <span class="help-block"></span>
                                </div>
                                <?php
                                  //date in mm/dd/yyyy format; or it can be in other formats as well
                                  $birthDate = date('m/d/Y', $user->dob);
                                  //explode the date to get month, day and year
                                  $birthDate = explode("/", $birthDate);
                                  //get age from date or birthdate
                                  $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
                                    ? ((date("Y") - $birthDate[2]) - 1)
                                    : (date("Y") - $birthDate[2]));
                                ?>
                            <div class="input-wrapper">
                            <label>Age (Year)<i class="required_star">*</i></label>
                            <input type="text" placeholder="Age" name="age" class="nummber NumericFeild"  />
                            <!-- <select name="age_type" class="form-control age-wrapper">
                            <option value="Y">Year</option>
                            <option value="M">Month</option>
                            <option value="D">Days</option>
                            </select> -->
                            <span class="help-block"></span>
                            </div>
                            <div class="radio-wrapper waitingTime top">
                            <label>Gender<i class="required_star">*</i></label>
                            <p><input id="gender1" type="radio" name="gender" id="test8" value="M" @if($user->gender == "Male") checked @endif><label for="gender1">Male</label><span class="help-block"></span></p>
                            <p><input id="gender2" type="radio" name="gender" id="test9" value="F" @if($user->gender == "Female") checked @endif ><label for="gender2">Female</label></p>
                            <!-- <p><input id="gender3" type="radio" name="gender" id="test10" value="3"><label for="gender3">Other</label></p> -->
                            <span class="help-block"></span>
                            </div>
                                <div class="input-wrapper">
                                  <label>E-Mail<i class="required_star">*</i></label>
                                <input type="text" placeholder="E-mail" class="checkemail"  name="email"  />
                                <span class="help-block"></span>
                                </div>

                                  <div class="input-wrapper ">
						             	<label>&nbsp;</label>
                            <select style="margin-top: 27px;" class="countryCode" name="mobile_code" esabled>
                            <option class="countryCode"  id="country" selected="selected" value="IN">+91(IND)</option>
                            </select>
                            <input name="mobile_no" v_type="1" class="s-input verifyDocData NumericFeild" type="text" placeholder="Mobile Number" autocomplete="off" value="{{ old('mobile_no') }}" />

                            </div>
                            <div class="doctor-img form-fields form-field-mid">
                              <label>Profile Image</label>
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
                            <div class="input-wrapper">
                              <label>About<i class="required_star">*</i></label>
                            <!-- <input type="text" placeholder="E-mail" name="email" value="{{$user->email}}" /> -->
                            <textarea type="text" placeholder="About You" name="email" value=""></textarea>
                            <span class="help-block"></span>
                            </div>

                              </div>

                          </section>
                          <h3>Education Details</h3>
                          <section>
                            <div class="form-fields start">
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
            </section>
            <h3>Payment Delaits</h3>
<section>
<div class="address-wrapper">
  <div class="Account-Daitale">
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
            </section>

            <h3>Clinic Delaits</h3>
            <section>
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
  </section>
            <h3>OPD Time Schedule</h3>
            <section>
                <div class="container">
                  <div class="right-section new-tabs-section">
                     <div class="right-block">
                          <div class="right-box">
                              <div class="aad-inventory-section">
                                  <div class="add-staff">
                                      <h2> OPD Time Schedule</h2>
                                  </div>
                                  <div class="delete-top opd_back">

                            </div>
                              </div>
                              <div class="opd-sch profile-1">
                                  <div class="add-doctor-block">
                                      <div class="add-doctor-left">
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
                                                                      @foreach($appt_durations ?? '' as $idx => $dur)
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
                                                                    @foreach($appt_durations ?? '' as $index => $dur)
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
    <!-- sher khan deshwali -->

                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
            </section>


                      </div>
                  </div>
                </div>

              </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{ URL::asset('js/jquery.steps.js') }}"></script>
<script type="text/javascript">



var mainDiv = $("#MyCartPage");
var form = $("#PersonalInfo");
  // form.validate({
  //   rules: {
  //     name: "required",
  //     gender: "required",
  //     age: {
  //       required: true,
  //       min: 1,
  //       max: 100,
  //     },
  //     email: {
  //       required: true,
  //       minlength: 10,
  //       maxlength: 50,
  //     },
  //     address_id: "required",
  //     appt_time: "required",
  //     mobile: {
  //       required: true,
  //       minlength: 10,
  //       maxlength: 10,
  //     },
  //   },
    // Specify the validation error messages
    // messages: {
       // name: "Please Type Name.",
       //  gender: "Please Select Product Type",
       //  age: "Please  Enter Age",
       //  strength:{"required": "Please enter Strength","number": "Please enter Strength in Numeric."},
       //  item_name: "Please enter Drug Name",
       //  hsn: "Please enter HSN Code",
       //  gst: "Please Select GST.",
       //  standards: "Please Select Standards"
    // },
    // errorPlacement: function(error, element) {
    //    // $(element).css({"color": "red", "border": "1px solid red"});
    //   error.appendTo($(element).parent().find('.help-block'));
    // },
    //  function PersonalInfo() {
    //         jQuery('.loading-all').show();
    //         // jQuery('#PersonalInfo').attr('disabled',true);
    //         jQuery.ajax({
    //         type: "POST",
    //         url: "{!! route('newsave') !!}",
    //         data:  new FormData(form_data),
		// 	     contentType: false,
    //         processData:false,
    //         success: function(data) {
		// 			if(data.status == '0') {
		// 				jQuery('.loading-all').hide();
		// 				 $.alert({
		// 					title: 'Alert!',
		// 					draggable: false,
		// 					content: data.output.response,
		// 				});
		// 			}
		// 			else if(data.status == '1') {
		// 				 // console.log(data);
		// 				 var url = '{!! url("/labCheckoutOrder?tid='+data.tid+'&order_id='+data.order_id+'&amount='+data.amount+'&merchant_param1='+data.merchant_param1+'&merchant_param2='+data.merchant_param2+'&merchant_param3='+data.merchant_param3+'&merchant_param4='+data.merchant_param4+'&order_by='+data.order_by+'") !!}';
		// 				 //window.location.href = '{{route("labCheckoutOrder",'+data+')}}'; //using a named route
		// 				 window.location = url; //using a named route
		// 			}
		// 		  else if (data == '4') {
		// 			jQuery('.loading-all').hide();
		// 			$('.serverError').show();
		// 			$('.serverError').find('.msg').text('Something Missing Please Try Again Later');
		// 		  }
		// 		  else{
		// 			  console.log(data);
		// 			  window.location.href = '{{route("newtest")}}';
		// 			}
    //            },
    //             error: function(error)
    //             {
    //               if(error.status == 401)
    //               {
    //                  // alert("Session Expired,Please logged in..");
    //                   location.reload();
    //               }
    //               else
    //               {
    //                 jQuery('.loading-all').hide();
    //                 alert("Oops Something goes Wrong.");
    //                 jQuery('#PersonalInfo').attr('disabled',false);
    //               }
    //             }
    //          });
    // }
// });

$("#PersonalInfo").click(function () {
      $.ajax({
          url: "{{ route('newsave') }}",
          method: "POST",

              processData:false,
            dataType: "json",
          success: function (data) {
              console.log(data);
          }
      });
  });


jQuery(document).on("change", ".select_reg_council", function () {
if($(this).val() == '0'){
  $(".reg_council_other_div").show();

}
else {
  $(".reg_council_other_div").hide();
}
});

mainDiv.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex){
      // alert(currentIndex);
      if (currentIndex > newIndex){
           return true;
       }
      form.validate().settings.ignore = ":hidden";
      return form.valid();
    },
    onFinishing: function (event, currentIndex) {
      var pay_type = $('.pay_type:checked').length;
       if(!pay_type){
         $('.payModeError').show();
           return false;
       }else {
         return true;
       }
    },
    onFinished: function (event, currentIndex){
        $("#createLabOrder").submit();
    }
});

//sher khan  ------By Schedule code Start ------------//
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
                    row += '<div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+cnt+'][timings][1][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations ?? '' as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div>';
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
                   row += '<div class="teleconsult_duration set_error" style="display:none;"> <select name="schedule['+scheduleCnt+'][timings]['+cnt+'][tele_appt_duration]" class="slots"> <option value="">Tele Appointment Duration</option> @foreach($appt_durations ?? '' as $index => $dur) <option value="{{$dur->time}}">{{$dur->title}}</option> @endforeach </select> </div>';
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

});


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

function viewCart(reportType) {
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ViewCartAPI') !!}",
  data: {'report_type':reportType},
  success: function(data)
      {
        console.log(data);
        var products = data.product;
        $('#finalProducts').val(data.product);
        var alreadyAdded = false;
        var Includedtest = [];
          $(".cartProduct").each(function(){
             currentAttr = $(this).attr('product');
              if(jQuery.inArray(currentAttr, products) == -1){
                Includedtest.push(currentAttr);
                alreadyAdded = true;
              }
          });
          if (alreadyAdded == true) {
            $('.alreadyAdded').show();
          }

          Includedtest = Includedtest.toString();

        $('.IncludedTest').text(Includedtest.replace(/,/g, ", "));
        $('.IncludedInTest').text(data.product);

        $('#paidAmount').val(btoa(data.payable));
        $('#paidAmount2').val(btoa(data.payable));

        var totalAmount = atob($('#totalAmount').val());
        var reportHardCopy = $(".report_type:checked").val();
        if(reportHardCopy != "yes") {
			if(data.chcCharges != 0) {
				$('.totalAmount').text(totalAmount);
				$('#priceDiscount').val(btoa(totalAmount - data.rates));
				$('.priceDiscount').text(totalAmount - data.rates);
			}
			else{
				$('.totalAmount').text(totalAmount);
				$('#priceDiscount').val(btoa(totalAmount - data.payable));
				$('.priceDiscount').text(totalAmount - data.payable);
			}
        }
		else{
			$('.totalAmount').text(parseInt(totalAmount) + 75);
		}

        $('#paidAmount2').val(btoa(data.payable));
        $('.tMargin').val(data.margin);
        $('#serviceCharge').val(data.chcCharges);
        $('.serviceCharge').text(data.chcCharges);
        $('.paidAmount').text();
        jQuery('.loading-all').hide();
        paymentCalculate(4);
     },
      error: function(error)
      {
        if(error.status == 401)
        {
         //   alert("Session Expired,Please logged in..");
            location.reload();
        }
        else
        {
          jQuery('.loading-all').hide();
        //  alert("Oops Something goes Wrong.");
          jQuery('#saveAddress').attr('disabled',false);
        }
      }
   });
}

function customLabViewCart(reportType) {
	var totalAmount = atob($('#totalAmount').val());
	var totalSaving = atob($('#totalSaving').val());
	var priceDiscount = atob($('#priceDiscount').val());
	var reportHardCopy = $(".report_type:checked").val();
	if(reportHardCopy == "yes") {
		totalAmount = parseFloat(totalAmount) + 75;
	}
	priceDiscount = priceDiscount > 0 ? priceDiscount : 0;
	$('.totalAmount').text(totalAmount);
	$('#priceDiscount').val(btoa(totalSaving));
	$('.priceDiscount').text(totalSaving);
	// console.log(totalAmount);
	$('#paidAmount2').val(btoa(totalAmount));
	$('#paidAmount').val(btoa(totalAmount));
	$('.paidAmount').text(totalAmount);
	$('.tMargin').val(0);
}

jQuery(document).ready(function () {
  $(window).load(function(){
	var gLabCmptp = $(".gLabCmptp").val();
    if ($('#cartItems tr').length > '0') {
	  if(gLabCmptp != '0') {
		  customLabViewCart();
	  }
	  else{
		  viewCart('N');
	  }
    }
  });
});

jQuery(document).ready(function () {
  jQuery(document).on("click", ".payMode", function () {
    $('.payMode').removeClass('active');
    $('.payMode').parent().parent().find('.pay_type').prop('checked',false);
    $(this).parent().find('.pay_type').prop('checked',true);
    $('.payModeError').hide();
    $(this).addClass('active');
  });

    $("#wizard").steps({
        headerTag: "h2",
        bodyTag: "section",
        transitionEffect: "none",
        enableFinishButton: false,
        enablePagination: false,
        enableAllSteps: true,
        titleTemplate: "#title#",
        cssClass: "tabcontrol"
    });

    // $("#checkPincode").keyup(function(){
    //   alert('test');
    // });
    $("#checkPincode").on("keyup paste", function(){
        var pincode = $(this).val();
        var current = this;
        if (pincode.length != 6) {
          $(current).parent().find('.help-block').find('label').hide();
        }
        if (pincode.length == 6) {
		 jQuery.ajax({
		  type: "POST",
		  dataType: 'json',
		  url: "{!! route('checkPincodeAvailability') !!}",
		  data: {'pincode':pincode},
		  success: function(data) {
			  if(data==1) {
				jQuery('#saveAddress').attr('disabled',false);
				$(current).parent().find('.help-block').find('label').hide();
				$(current).parent().find('.help-block').append('<label class="error" style="display:block; color:green;">Service Available</label>');
				$('.inputBoxLoader').hide();
			  }
			  else {
				jQuery('#saveAddress').attr('disabled',true);
				$(current).parent().find('.help-block').find('label').hide();
				$(current).parent().find('.help-block').append('<label class="error" style="display:block; color:red;">Service Not Available</label>');
				$(current).val('');
				$('.inputBoxLoader').hide();
			  }
			}
		  });
        }
        else{
          $(current).parent().find('.help-block').append('<label class="error" style="display:block; color:red;">This field is required</label>');
        }
    });

	function checkPincodeAvailability(pincode) {
	  $('.inputBoxLoader').show();
	  jQuery('#saveAddress').attr('disabled',true);
	  jQuery.ajax({
	  type: "POST",
	  dataType: 'json',
	  async: false,
	  global: false,
	  url: "{!! route('checkPincodeAvailability') !!}",
	  data: {'pincode':pincode},
	  success: function(data) {
		  if(data==1) {
			return true;
		  }
		  else return false;
		}
	  });
	}
});


jQuery(document).ready(function () {
$( ".scheduleDate" ).datepicker({
	dateFormat: 'dd-MM-yy',
	minDate: 0,
	changeMonth: true,
    changeYear: true,
 });
$('.scheduleDate').datepicker('setDate', 'today');
});

function cuteHide(el) {
  el.animate({opacity: '0'}, 150, function(){
    el.animate({height: '0px'}, 150, function(){
      el.remove();
    });
  });
}
function switchButton() {
  var tab1 = $('#wizard-t-0').parent().attr('aria-selected');
  var tab2 = $('#wizard-t-1').parent().attr('aria-selected');
  var tab3 = $('#wizard-t-2').parent().attr('aria-selected');
  if (tab1 == 'true') {
    $('.scheduleButton').find('strong').text('Schedule Order')
    $('.scheduleButton').removeClass('payNow')
    $('#payment_tab').val(0);
  }
  else if (tab2 == 'true') {
    $('.scheduleButton').find('strong').text('Proceed Payment')
    $('.scheduleButton').addClass('payNow')
    $('#payment_tab').val(0);
  }

  if (tab3 == 'true') {
    $('#payment_tab').val(1);
  }
}
function billCalculate(currentRow) {


}
function coupanDiscountFun(coupanDiscount, type) {
  var paidAmount = atob($('#paidAmount2').val());
  var priceDiscount = atob($('#priceDiscount').val());
  var coupanDiscountAmount = paidAmount * coupanDiscount / 100;
  var totalSaving = atob($('#totalSaving').val());
  var reportHardCopy = atob($('#reportHardCopy').val());


  if (type == 1) {
    if (coupanDiscountAmount != "") {
      $('#coupanDiscountAmount').val(btoa(coupanDiscountAmount.toFixed(2)));
      $('.coupanDiscountAmount').text(coupanDiscountAmount.toFixed(2));

    }
    else {
      $('.coupanDiscountAmount').text('0.00');
    }

    paidAmount = paidAmount - coupanDiscountAmount;
    // if (reportHardCopy != "") {
    //   paidAmount = parseInt(paidAmount) + parseInt(reportHardCopy);
    // }
    if (priceDiscount != "" && priceDiscount > "0") {
      $('#totalSaving').val(btoa(parseInt(totalSaving) + parseInt(coupanDiscountAmount.toFixed(2))));
      $('.totalSaving').text(parseInt(totalSaving) + parseInt(coupanDiscountAmount.toFixed(2)));
    }
    else{
      $('#totalSaving').val(btoa(coupanDiscountAmount.toFixed(2)));
      $('.totalSaving').text(coupanDiscountAmount.toFixed(2));
    }
    $('.coupanApplyedBox').find('.save-icon').find('.applyCouponAmount').text(coupanDiscountAmount.toFixed(2));
  }
  if (type == 2) {
      paidAmount = parseInt(paidAmount);
      // if (reportHardCopy != "") {
      //   paidAmount = parseInt(paidAmount) + parseInt(reportHardCopy);
      // }
      $('#totalSaving').val(btoa(parseInt(totalSaving) - parseInt(coupanDiscountAmount.toFixed(2))));
      $('.totalSaving').text(parseInt(totalSaving) - parseInt(coupanDiscountAmount.toFixed(2)));
  }

  return paidAmount;
}

function paymentCalculate(type, currentRow) {
var reportHardCopy = $(".report_type:checked").val();
var totalAmount = atob($('#totalAmount').val());
var priceDiscount = atob($('#priceDiscount').val());
var paidAmount = atob($('#paidAmount').val());
var paidAmount2 = atob($('#paidAmount2').val());
var coupanDiscount = atob($('#coupanDiscount').val());
var coupanDiscountAmount = atob($('#coupanDiscountAmount').val());
var totalSaving = atob($('#totalSaving').val());
if (type == 1) {
var thisOfferPrice = $(currentRow).parent().find('.offerPrice').val();
var thisPackagePrice = $(currentRow).parent().find('.packagePrice').val();
if (thisOfferPrice > 0 || thisOfferPrice != "" ) {
  var thistotalAmount = thisPackagePrice - thisOfferPrice;
}
else {
  var thistotalAmount = thisPackagePrice;
}
$('#totalAmount').val(btoa(totalAmount-thistotalAmount));
// $('#priceDiscount').val(btoa(priceDiscount-thisOfferPrice));
paidAmount = parseInt(paidAmount2) - (parseInt(thisPackagePrice) - parseInt(thisOfferPrice));

$('#paidAmount2').val(btoa(paidAmount.toFixed(2)));
$('#totalSaving').val(btoa((totalSaving) -(thisOfferPrice)));
$('.totalAmount').text(totalAmount-thistotalAmount);
// $('.priceDiscount').text(priceDiscount-thisOfferPrice);
$('.paidAmount').text(paidAmount.toFixed(2));
$('.totalSaving').text((totalSaving) -(thisOfferPrice));
if (coupanDiscount != "") {
	paidAmount = coupanDiscountFun(coupanDiscount, 1);
}
}
if (type == 2) {
  paidAmount = coupanDiscountFun(coupanDiscount, 1);
}
if (type == 3) {
  paidAmount = coupanDiscountFun(coupanDiscount, 2);
}
if (type == 4) {
  if (reportHardCopy == 'yes') {
    saveAmount = (paidAmount) * (coupanDiscount) / 100;
    paidAmount = (paidAmount) - (saveAmount);
    coupanDiscountFun(coupanDiscount, 1)
  }
  else {
    saveAmount = (paidAmount) * (coupanDiscount) / 100;
    paidAmount = (paidAmount) - (saveAmount);
    coupanDiscountFun(coupanDiscount, 1)
  }
}
$('#paidAmount').val(btoa(paidAmount.toFixed(2)));
$('.paidAmount').text(paidAmount.toFixed(2));
}

function GetAppointmentSlots(pincode, schedule_date) {
  var scheduleTime = $('#scheduleTime');
  scheduleTime.empty();
  jQuery("#scheduleTime").prepend($('<option value=""></option>').html('Loading...'));
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('GetAppointmentSlots') !!}",
  data: {'pincode':pincode, schedule_date:schedule_date},
  success: function(data){
      if (data.lSlotDataRes > '0') {
        jQuery("#scheduleTime").html('<option value="">Select Schedule Time</option>');
        jQuery.each(data.lSlotDataRes,function(index, element) {
			   let slot_time = element.slot.split("-");
			scheduleTime.append(jQuery('<option>', {
             value: slot_time[0],
             text : element.slot,
             data_id : JSON.stringify(element)
          }));
        });
      }
      else{
		alert(data.response);
		jQuery("#scheduleTime").html('<option value="">All Time Slots have been booked for the day</option>');
      }
    },
    error: function(error){
      if(error.status == 401) {
         // alert("Session Expired,Please logged in..");
          location.reload();
      }
      else {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });
}
function LabCart(current, product_array, action_type) {
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  url: "{!! route('CartUpdate') !!}",
  data: {'product_array':product_array,'action_type':action_type},
  success: function(data){
      if(data.status==1){
		 jQuery(".gLabCmptp").val(data.lab_company_type);
        jQuery('.loading-all').hide();
        $(current).closest('tr').remove();
        if ($('#cartItems tr').length != '0') {
          var reportHardCopy = $(".report_type:checked").val();
		  var gLabCmptp = $(".gLabCmptp").val();
		  if(gLabCmptp != '0') {
			  location.reload();
		  }
		  else {
			  viewCart(reportHardCopy);
		  }
        }
        if ($('#cartItems tr').length == '0') {
          setTimeout(function(){
            window.location = "{{ route('LabCart') }}";
          }, 300);
        }
        // alert("Package Remove Successfully");
      }
      else
      { jQuery('.loading-all').hide();
        alert("Problem into Cart");
      }
    }
  });
}

function ApplyCoupon(couponcode) {
  jQuery('#coupanApply').attr('disabled',true);
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('ApplyCoupon') !!}",
  data: {'couponcode':couponcode,'isDirect':'1'},
  success: function(data){
		if (data.status == '1') {
		  $('#coupanDiscount').val(btoa(data.coupon_rate))
		  $('#couponCode').val(data.coupon_code)
		  paymentCalculate(2);
			$('.coupanApplyedBox').find('.applyCouponCode').text(data.coupon_code);
			if(data.other_text != null) {
				$('.coupanApplyedBox').find('.applyCouponText').text(data.other_text);
			}
			$('.divForHide').slideUp();
			$('.coupanApplyedBox').slideDown();
		}
		else{
			$('.CouponAvailableMsg').text('Invalid Or Expired Coupan Code');
			$('.CouponAvailableMsg').css("color", "red");
			$('.CouponAvailableMsg').slideDown();
		}
      jQuery('#coupanApply').attr('disabled',false);
    },
    error: function(error)
    {
      if(error.status == 401)
      {
        //  alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
       // alert("Oops Something goes Wrong.");
        jQuery('#coupanApply').attr('disabled',false);
      }
    }
  });
}
$("#couponInputCode").on("keyup", function(){
	$('.CouponAvailableMsg').slideUp();
	$('.CouponAvailableMsg').text('');
	$('.CouponAvailableMsg').css("color", "");
});
jQuery(document).on("click", "#coupanApply", function () {
	var couponCode = $('#couponInputCode').val();
	if (couponCode != "") {
		ApplyCoupon(couponCode);
	}
	else{
		$('.CouponAvailableMsg').text('please enter Coupon Code');
		$('.CouponAvailableMsg').css("color", "red");
		$('.CouponAvailableMsg').slideDown();
	}
});

jQuery(document).on("click", ".removeCoupan", function () {
  var  coupanDiscount =  parseInt($('#coupanDiscountAmount').val());
  paymentCalculate(3);
  $('#couponInputCode').val('');
  $('#coupanDiscount').val('');
  $('#coupanDiscountAmount').val('');
  $('#coupanId').val('')
  $('.coupanDiscountAmount').text('0.00')

});

jQuery(document).on("click", ".scheduleButton", function () {
  var tab3 = $('#payment_tab').val();
  if (tab3 == 0) {
    $('#wizard-t-1').click();
    switchButton();
  }
});
jQuery(document).on("click", "#wizard .steps ul li", function () {

  switchButton();
});
jQuery(document).on("change", ".labelType", function () {
  if (this.value == 3) {
      $('.labelName').slideDown();
      $("input[name~='label_name']").addClass('inputvalidation')
  }
  else{
      $('.labelName').slideUp();
      $("input[name~='label_name']").removeClass('inputvalidation')
  }
});
jQuery(document).on("click", ".addNewAddress", function () {
  $('#label_type_1').prop('checked', true);
  $('.labelName').slideUp();
  $('.addAddressDiv').slideToggle();

});
jQuery(document).on("click", ".applyThisCoupan", function () {
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
});
jQuery(document).on("click", ".removeCoupan", function () {
  $('.divForHide').slideToggle();
  $('.coupanApplyedBox').slideToggle();
  $('.applyThisCoupan').prop('checked', false);
});
jQuery(document).on("click", ".deleteFromCart", function () {
    var selectPackage = [];
    var pname = $(this).attr('Pname');
    var pcode = $(this).attr('Pcode');
    selectPackage.push({pname:pname,pcode:pcode});
    LabCart(this, selectPackage, 'remove_item');
	current = $(this);
	paymentCalculate(1, current);
	var el =   $(this).closest('tr');
	cuteHide(el);
	// $(current).closest('tr').remove();
	var cartTotal = jQuery('#cartTotal').text();
	jQuery('#cartTotal').text(parseFloat(cartTotal)-1);
	jQuery('.totalTest').text(parseFloat(cartTotal)-1);
	var data_name = $(this).attr('Pname');
	$("#miniCartList .list").each(function(){
	  if ($(this).attr("data-name") == data_name) {
		$(this).remove();
	  }
	});
	if ($("#miniCartList .list").length == '0') {
	  $("#miniCart").css("display", "none");
	}
});
jQuery(document).on("change", ".scheduleDate", function () {
  var pincode = $(".selectAddress:checked").attr('code');
  var schedule_date = $(this).val();
  var addressCount = $('.AddressBox .address-box').length;
  if ($('.AddressBox .address-box').length > 0) {
    GetAppointmentSlots(pincode, schedule_date);
    $('.addressEmptyMsg').hide();
    $('.addressEmptyMsg').text('');
  }
  else{
      $('.addressEmptyMsg').show();
      $('.addressEmptyMsg').text("Please Add Address First");
    $('.addressEmptyMsg').css("color", 'red');
  }
});
// jQuery(document).on("change", ".selectAddress", function () {
//   var pincode = $(this).attr('code');
//   var schedule_date = $('.scheduleDate').val();
//
//   GetAppointmentSlots(pincode, schedule_date)
// });
jQuery(document).ready(function () {
  $(window).load(function(){
    var schedule_date = $('.scheduleDate').val();
    var pincode = $(".selectAddress:checked").attr('code');
    setTimeout(function(){
      if ($('.AddressBox .address-box').length > 0) {
          GetAppointmentSlots(pincode, schedule_date);
        }
    }, 3000);
  });
});

jQuery(document).on("click", ".address-box", function () {
  var clickDelete = $(this).find('.delete-address').find('.addressDeleteNow').attr('click-delete');
  if (clickDelete == "0") {
    $('.address-box').removeClass('active');
    $(this).addClass('active');
    $(this).find('.coupon-wrapper').find('.selectAddress').prop("checked", true);
    var pincode = $(this).find('.coupon-wrapper').find('.selectAddress').attr('code');
    var schedule_date = $('.scheduleDate').val();
    GetAppointmentSlots(pincode, schedule_date)
  }
});
jQuery(document).on("change", ".report_type", function () {
  var reportType = $(".report_type:checked").val();
    var gLabCmptp = $(".gLabCmptp").val();
	if(gLabCmptp != '0') {
	  customLabViewCart();
	}
	else{
	  viewCart(reportType);
	}
});
jQuery(document).ready(function () {
  $(".inputvalidation").on("keyup paste", function(){
    if (this.value != "") {
      $(this).parent().find('.help-block').find('label').remove();
    }
  });
});
jQuery(document).on("click", "#saveAddress", function () {
  // alert('test');
  var value = $('#addAddressForm').find('.inputvalidation').val();
  var flag = true;

    $(".inputvalidation").each(function(){
      var value = $(this).val();
      if (value == "" ) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">This field is required.</label>');
        flag = false;
      }
      if ($(this).attr('name') == "address" && value != "" && value.length < 10) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter at least 10 characters.</label>');
          flag = false;
      }
      if ($(this).attr('name') == "address" && value != "" && value.length > 100) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 100 characters.</label>');
          flag = false;
      }

      if ($(this).attr('name') == "locality" && value != "" && value.length > 50) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 50 characters.</label>');
          flag = false;
      }

      if ($(this).attr('name') == "landmark" && value != "" && value.length > 40) {
          $(this).parent().find('.help-block').find('label').remove();
          $(this).parent().find('.help-block').append('<label class="error" style="display:block">Please enter no more than 40 characters.</label>');
          flag = false;
      }


    });
    var form = $('#addAddressForm :input').serialize();

    console.log(form);

  if (flag == true) {
    jQuery('.loading-all').show();
    jQuery('#saveAddress').attr('disabled',true);
    jQuery.ajax({
    type: "POST",
    dataType : "JSON",
    url: "{!! route('createLaborderAddresses') !!}",
    data:  form,
    // contentType: false,
    // cache: false,
    // processData:false,
    success: function(data)
        {
          console.log(data);
          if (data.label_type == 1) {
            var labelName = "Home";
          }
          else if (data.label_type == 2) {
            var labelName = "Office";
          }
          else{
            var labelName = data.label_name;
          }

          addressDiv = '<div class="address-box active" lable-type="'+data.label_type+'"><p class="coupon-wrapper"><input type="radio" id="address_radio_'+data.id+'" value="'+data.id+'" name="address_id" class="selectAddress" code="'+data.pincode+'" checked  /><label for="address_radio_'+data.id+'"></label></p><div class="delete-address"><a href="javascript:void(0)" title="Delete Address" data-id="'+data.id+'" class="addressDeleteNow"><i class="fa fa-trash" aria-hidden="true"></i></a></div><div class="float"><div class="label-name">'+labelName+'</div><div class="address-area">'+data.address+', '+data.locality+', '+data.landmark+', '+data.pincode+'</div></div></div>';

          var labeTypes  = [];
          $('.address-box').each(function(){
              labeTypes.push($(this).attr('lable-type'));
          });
          if ((labeTypes.includes(data.label_type.toString()) == true) && (data.label_type == 1 || data.label_type == 2)) {
            $('.address-box').each(function(){
                var label_type = $(this).attr('lable-type');
                if (label_type == data.label_type) {
                  $('.address-box').removeClass('active');
                    $(this).replaceWith(addressDiv);
                }
            });
          }
          else{
            $('.address-box').removeClass('active');
            $(".AddressBox").append(addressDiv);
          }

          $('.emptyAddress').hide();
          $('.addressEmptyMsg').hide();
          $('.addressEmptyMsg').text("");
         $('.addressEmptyMsg').css("color", '');
          $('.availableMsg').hide();
          $("#addAddressForm input:text").each(function(){
              $(this).parent().find('.help-block').find('label').hide();
              $(this).parent().find('.help-block').append('<label class="error" style="display:block"></label>');
          });
          jQuery('.loading-all').hide();
          $('.addAddressDiv').slideUp();
          $('.labelName').slideUp();
          $('#addAddressForm').find('input:text').val('');
          jQuery('#saveAddress').attr('disabled',false);
          //location.reload();
       },
        error: function(error)
        {
          if(error.status == 401)
          {
              //alert("Session Expired,Please logged in..");
              location.reload();
          }
          else
          {
            jQuery('.loading-all').hide();
            //alert("Oops Something goes Wrong.");
            jQuery('#saveAddress').attr('disabled',false);
          }
        }
     });
  }
});
// jQuery(document).on("click", "#payNow", function () {
//
//   var appt_time = $('#scheduleTime').val();
//   if (!appt_time) {
//     $('#wizard-t-1').click();
//   }
//
// jQuery("#createLabOrder").validate({
//     // Specify the validation rules
//     rules: {
//
//       name	: "required",
//       gender: "required",
//       age: {
//         required: true,
//         min: 1,
//         max: 100,
//       },
//       email: {
//         required: true,
//         minlength: 10,
//         maxlength: 50,
//       },
//       appt_time: "required",
//       mobile: {
//         required: true,
//         minlength: 10,
//         maxlength: 10,
//       },
//     },
//     // Specify the validation error messages
//     messages: {
//      //   company_id: "Please Select a Company.",
//         // item_type: "Please Select Product Type",
//         // unit: "Please Select Unit",
//         // strength:{"required": "Please enter Strength","number": "Please enter Strength in Numeric."},
//         // item_name: "Please enter Drug Name",
//         // hsn: "Please enter HSN Code",
//         // gst: "Please Select GST.",
//         //standards: "Please Select Standards"
//     },
//     errorPlacement: function(error, element) {
//        // $(element).css({"color": "red", "border": "1px solid red"});
//       error.appendTo($(element).parent().find('.help-block'));
//
//
//
//
//     },
//     // ignore: ":hidden",
//     submitHandler: function(form) {
//       var flag = true;
//           var appt_time = $('#scheduleTime').val();
//
//           var payment_tab = $('#payment_tab').val();
//           if (payment_tab == '0') {
//             $('#wizard-t-2').click();
//             flag = false;
//             return false;
//           }
//
//           var pay_type = $('.pay_type:checked').length;
//
//           if(!pay_type){
//             $('.payModeError').show();
//               flag = false;
//               return false;
//           }
//
//           var addressCount = $('.AddressBox .address-box').length;
//
//           if ($('.AddressBox .address-box').length == 0) {
//             $('.addressEmptyMsg').show();
//             $('.addressEmptyMsg').text("Please Add Address First");
//             $('.addressEmptyMsg').css("color", 'red');
//               flag = false;
//
//           }
//
//
//           if (flag == true) {
//
//             jQuery('.loading-all').show();
//             jQuery('#saveAddress').attr('disabled',true);
//             jQuery.ajax({
//             type: "POST",
//             dataType : "JSON",
//             url: $(form).attr('action'),
//             data:  new FormData(form),
//             contentType: false,
//             cache: false,
//             processData:false,
//             success: function(data)
//                 {
//                   console.log(data);
//
//                   window.location.href = '{{route("orderSuccess")}}'; //using a named route
//                },
//                 error: function(error)
//                 {
//                   if(error.status == 401)
//                   {
//                       alert("Session Expired,Please logged in..");
//                       location.reload();
//                   }
//                   else
//                   {
//                     jQuery('.loading-all').hide();
//                     alert("Oops Something goes Wrong.");
//                     jQuery('#saveAddress').attr('disabled',false);
//                   }
//                 }
//              });
//           }
//
//     },
// });
// });
jQuery(document).on("click", ".addressDeleteNow", function () {
  $(this).attr('click-delete','1');
  var id = $(this).attr('data-id');
  var current = $(this);
  jQuery.ajax({
  type: "POST",
  url: "{!! route('deletelaborderAddress') !!}",
  data: {'id':id},
  success: function(data){
      var el = $(current).closest('.address-box');
      cuteHide(el);
         $(current).closest('.address-box').remove();
         if ($('.AddressBox .address-box').length == 0) {
           $('.emptyAddress').css({"display": "block"});
         }
    },
    error: function(error)
    {
      if(error.status == 401)
      {
        //  alert("Session Expired,Please logged in..");
          location.reload();
      }
      else
      {
        jQuery('.loading-all').hide();
      //  alert("Oops Something goes Wrong.");
        jQuery('#saveAddress').attr('disabled',false);
      }
    }
  });
});
jQuery(document).ready(function () {
setTimeout(location.reload.bind(location), 600000);
});
</script>
@endsection
