@extends('layouts.Masters.Master')
@section('title', 'Cart')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
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
                            
                      
                    <div class="form-fields">
									<label>Mobile Number<i class="required_star">*</i></label>
									<select class="countryCode" id="country" name="mobile_code" disabled>
									  <option selected="selected" value="IN">+91(IND)</option>
									</select>
									<input name="mobile_no" v_type="1" class="s-input verifyDocData NumericFeild  checkdoctor"  type="text" placeholder="Mobile Number" autocomplete="off" value="{{ old('mobile_no') }}" />
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
                    <input type="text" v_type="0"  name="email" placeholder="Enter Email" class="verifyDocData checkdoctor" autocomplete="off" value="{{ old('email') }}" />
                    <span class="help-block">
                   
                    </span>
                    </div>
                            
                            <div class="input-wrapper">
                                <label>First Name<i class="required_star">*</i></label>
								                
                                <input type="text" name="first_name" placeholder="First name" autocomplete="off" value="{{ old('first_name') }}" />

                                <span class="help-block"></span>
                            </div>

                            <div class="input-wrapper">
                              <label>Last Name<i class="required_star"></i></label>
                              <input type="text" name="last_name" placeholder="Last name" autocomplete="off" value="{{ old('last_name') }}" />
              
                              <span class="help-block"></span>
                            </div>
                             
                            <div class="input-wrapper">
                                <label>Age (Year)<i class="required_star">*</i></label>
                                <input type="text" placeholder="Age" name="age" class="nummber NumericFeild"  />
                                <!-- <select name="age_type" class="form-control age-wrapper">
                                <option valuPersonalInfoe="Y">Year</option>
                                <option value="M">Month</option>
                                <option value="D">Days</option>
                                </select> -->
                                <span class="help-block"></span>
                            </div>
                            
                            <div class="doctor-img form-fields form-field-mid">
                              <label>Profile Image</label>
                              
                             
                              
                              <div class="image_apload22">
                                  <p id="image_apload_profile">
                                    <img class="old_profile_pic" style="width:100px; display:none" src="#" alt="your image" />
                                    <input type="hidden" name="old_profile_pic" class="old_profile_pic" id="old_profile_pic">
                                  </p>
                                <div class="image_apload" style="display:none">
                                  <p><img id="blah" src="#" alt="your image" /></p>
                                {{--  <p><img id="image_apload" src="#" alt="your image" /></p> --}}
                                  
                                </div>
                          
                                <span id="fileselector" class="clinicFIleDIv">
                                
                                <input accept="image/*" class="profile_image custom-file-input" name="profile_image" type='file' id="imgInp" />
                                  
                                  <p id="errormessage"></p>
                                </span>
                                </div>
                            </div>
                            
                            

                  
<div class="radio-wrapper waitingTime top">
                            <label>Gender<i class="required_star">*</i></label>
                            <p id="hidethis"><input id="gender1" type="radio" class="gender" name="gender" id="test8" value="M" ><label for="gender1">Male</label><span class="help-block"></span></p>
                            <p id="genderradio"></p>
                            <p id="hidethisf"><input id="gender2" type="radio" class="gender" name="gender" id="test9" value="F" ><label for="gender2">Female</label></p>
                            <p id="genderradiof"></p>
                            <!-- <p><input id="gender3" type="radio" name="gender" id="test10" value="3"><label for="gender3">Other</label></p> -->
                            <span class="help-block"></span>
                            </div>
                            	
                            
                            <div class="input-wrapper">
                              <label>About<i class="required_star"></i></label>
                            <!-- <input type="text" placeholder="E-mail" name="email" value="
								" /> -->
                            <textarea type="text" class="about_us" id="about_us" placeholder="About You" name="about" value=""></textarea>
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
    									<select name="reg_year" id="reg_year" class="form-control">
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
                      <select name="reg_council"  id="reg_council" class="searchDropDown select_reg_council">
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
    								  <input type="text" name="reg_council_other" placeholder="Other" id="reg_council_other" autocomplete="off" value="{{ old('reg_council_other') }}"/>
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
    								  <select name="degree_year" id="degree_year" class="form-control">
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
    								  <select name="university" id="university" class="searchDropDown select_university">
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
            <h3>Payment Details</h3>
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

            <h3>Clinic Details</h3>
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
   							  <select name="clinic_speciality"  class="searchDropDown clinic_speciality" id="clinic_speciality">
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
   							  <textarea name="note" class="note" placeholder="Note(*If u give extra information)" value="{{ old('note') }}" autocomplete="off"></textarea>
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
								<option value="1">Select City</option>
                <option value="2">Allahabad</option>
                <option value="3">Jaipur</option>
                <option value="4">Simla</option>
                <option value="5">Raipur</option>
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
                <option value="2">Adarsh Nagar</option>
                <option value="3">Agra Road</option>
                <option value="4">Ajmer Road</option>
                <option value="5">Alwar Highway</option>
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
								<input type="text" class="zipcode" placeholder="Zipcode" name="zipcode" value="{{ old('zipcode') }}"/>
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
							    <p id="image_apload_profile">
                    <img  class="old_clinic_pic" style="width:100px; display:none" src="#" alt="your image" />
                    <input type="hidden" name="old_clinic_pic" class="old_clinic_pic" id="old_clinic_pic">
                  </p>
                  
                  <div class="image_apload22">
                    <div id="wrapper">
                      <input type="file" class="clinic_image custom-file-input" name="clinic_image"  accept="image/*" onchange="preview_image(event)">
                      <img id="output_image"/>
                     </div>
								{{-- <span id="fileselector" class="clinicFIleDIv">
									<label class="btn btn-default" for="upload-clinic-file">
								 <input  type="hidden" name="clinic_imageBlob" class="clinic_imageBlob" />
									<input id="upload-clinic-file" type="file" class="clinic_image" name="clinic_image" class="mylogoClinic"/>BROWSE
									</label>
								</span> --}}
								</div>
							</div>
  </section>
            <h3>OPD Time Schedule</h3>
            <section>
              
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
                              <div class="opd-timings-slot slot_duration">
                                <label>Appointment Duration</label>
                                <select name="slot_duration" class="slots-data">
                                <option value="">Select</option>
                               
                                  <option value="5"  >5 Mins</option>
                                  <option value="10" >10 Mins</option>
                                  <option value="15" >15 Mins</option>
                                  <option value="20"  > 20 Mins</option>
                                  <option value="25"  >25 Mins</option>
                                  <option value="30"  >30 Mins</option>
                                  <option value="35"  >35 Mins</option>
                                  <option value="40"  >40 Mins</option>
                                  <option value="45"  >45 Mins</option>
                                  <option value="50"  >50 Mins</option>
                                  <option value="55" >55 Mins</option>
                                  <option value="1 Hour" >1 Hour</option>
                                  
                           
                                </select>
                                <span class="help-block"></span>
                              </div>
                        
                              <div class="opd-sch profile-1">
                                  <div class="add-doctor-block">
                                      <div class="add-doctor-left">
                                          <input type="hidden" name="scheduleId" value="">
                                        <input type="hidden" name="id" value="">

                                        <input type="hidden" id="ForcurrentIndex" name="currentIndex"  value="">                                        
                                           
                                                  <div class="module-access-section module-access-section-border complete-str showthisschedule" style="display:none">
                                                  <p id="main-div-scheduleAzax"></p>
                                                  </div>
                                            
                                                  <div class="complete-str hidethisSchedule">



                                                      <div class="main-div-schedule">

                                                        <div class="checkbox-div">
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="1">Monday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check"name="schedule[1][days][]" value="2">Tuesday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check"name="schedule[1][days][]" value="3">Wednesday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="4">Thursday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="5">Friday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="6">Saturday</label>
                                                          <label class="checkbox-inline"><input type="checkbox" class="day_check" name="schedule[1][days][]" value="0">Sunday</label>
                                                    </div>
                                                           
                                                           <div id="msg" class="success-data alert alert-danger" style="display: none;"></div>
                                                           <div class="sessions-str">
                                                              <div class="sessions-div" scheduleCnt="1">
                                                            <label> Session 1 :</label>
                                                              <div class="teleconsult_section">
                                                                <label class="checkbox-inline"><input type="checkbox" class="teleconsult_check"  value="1">Tele-consultation</label>
                                                                <input type="hidden" id="isAgeSelected" class="teleconsult" name="schedule[1][timings][1][teleconsultation]" value="0">
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
                                             
                                             <div class="addSchedule-btn"><button type="button" class="addSchedule">Add More Schedule</button>
                                               {{-- <button type="submit" id='' class="btn btn-default submit">Save</button> --}}
                                             </div>
    <!-- sher khan deshwali -->

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





function preview_image(event) 
{
 var reader = new FileReader();
 reader.onload = function()
 {
  var output = document.getElementById('output_image');
  output.src = reader.result;
 }
 reader.readAsDataURL(event.target.files[0]);
}
  




mainDiv.children("div").steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex){
    
      // formValidate(form);
       
    if (currentIndex > newIndex){
     
           return true;
       }

      form.validate().settings.ignore = ":hidden";

    if(form.valid() == true){
   
		SavedoctorData(currentIndex);

		return form.valid()


	  }

	  //return form.valid();

    },
    onFinishing: function (event, currentIndex) {

      SavedoctorData(currentIndex);

      var pay_type = $('.pay_type:checked').length;
  
       if(!pay_type){
         $('.payModeError').show();
           return false;
       }else {
	
         return true;
       }
    },
    onFinished: function (event, currentIndex){
  
        console.log("asdsd");
        $("#PersonalInfo").submit();
    }
 });

//sher khan  ------By Schedule code Start ------------//
jQuery(document).ready(function () {
  jQuery(".addSchedule").click(function(){ 
	// alert("f");
  	         var cnt = jQuery('.main-div-schedule').length+1;

             //alert(cnt);
             if(cnt <= 7){
                 var row = '<div class="main-div-schedule" id="main-div-schedule"><div class="checkbox-div">';
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

jQuery(document).on("click", ".removeSche", function () {
  jQuery("#main-div-schedule").remove();
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












// jQuery(document).on("change", ".selectAddress", function () {
//   var pincode = $(this).attr('code');
//   var schedule_date = $('.scheduleDate').val();
//
//   GetAppointmentSlots(pincode, schedule_date)
// });


// jQuery(document).ready(function () {
//   $(".inputvalidation").on("keyup paste", function(){
//     if (this.value != "") {
//       $(this).parent().find('.help-block').find('label').remove();
//     }
//   });
// });



// jQuery(document).ready(function () {
// setTimeout(location.reload.bind(location), 600000);
// });

              function formValidate(form){
				jQuery(form).validate({
                    rules: {
						clinic_name:  {required:true,maxlength:255},
						first_name: {required:true,maxlength:30},
						
                        age:{required:true,number:true,maxlength:2},
						gender1:{required:true},
            profile_image: {
							  required: function(element) {
                  var old_profile_pic= $('.old_profile_pic').val();
                  var profile_image = $('.profile_image').prop('files')[0];
                 
                  if(profile_image !='undefined' && old_profile_pic !=''){
                    return false;
                  }else{
                    return true;
                  }
                  
							  },
							 
						  },
						
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
            },
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


//For Image Priview

imgInp.onchange = evt => {



  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  
    $('#image_apload_profile').hide();
     $('.image_apload').show();
    
  }
}


// For Saving Doctors Details	
	
function SavedoctorData(currentIndex){
	var formData = new FormData();
  
  var old_profile_pic= $('.old_profile_pic').val();
 
	if(currentIndex=='0'){
    
	let age = $("input[name=age]").val();

  var gender = $(".gender:checked").val();

  let old_profile_pic = $("input[name=old_profile_pic]").val();
  
	let first_name = $("input[name=first_name]").val();
	let last_name = $("input[name=last_name]").val();
  let about = $(".about_us").val();
  let _token = $('meta[name="csrf-token"]').attr('content');
	var profile_image = $('.profile_image').prop('files')[0];   

	formData.append('profile_image', profile_image);
	formData.append('currentIndex', currentIndex);
	formData.append('age', age);


	formData.append('about', about);
	formData.append('first_name', first_name);
	formData.append('last_name', last_name);
  formData.append('gender', gender);
  
  formData.append('old_profile_pic', old_profile_pic);
  
 
	}

	if(currentIndex=='1'){

	let experience = $("input[name=experience]").val();
	let qualification = $("input[name=qualification]").val();
	let reg_no = $("input[name=reg_no]").val();
	let reg_year = $("#reg_year").val();
	let reg_council = $("#reg_council").val();
  let university=$("#university").val();
  let last_obtained_degree = $("input[name=last_obtained_degree]").val();
  let degree_year = $("#degree_year").val();

	let _token = $('meta[name="csrf-token"]').attr('content');


  formData.append('experience', experience);
	formData.append('qualification', qualification);
	formData.append('reg_no', reg_no);

	formData.append('reg_year', reg_year);
  formData.append('last_obtained_degree', last_obtained_degree);
	formData.append('degree_year', degree_year);
  formData.append('university', university);
  formData.append('reg_council', reg_council);
 
 
	}

  if(currentIndex=='2'){


  let acc_no = $("input[name=acc_no]").val();
  let ifsc_no = $("input[name=ifsc_no]").val();
  let bank_name = $("input[name=bank_name]").val();
  let acc_name = $("input[name=acc_name]").val();
  let paytm_no = $("input[name=paytm_no]").val();


  formData.append('acc_no', acc_no);
  formData.append('ifsc_no', ifsc_no);
  formData.append('bank_name', bank_name);
  formData.append('acc_name', acc_name);
  formData.append('paytm_no', paytm_no);

}

if(currentIndex=='3'){
  var currentIndexs=currentIndex;
  var curindex=parseInt(currentIndexs)+1;
  $('#ForcurrentIndex').val(curindex);
  
  let old_profile_pic = $("input[name=old_clinic_pic]").val();
let clinic_name = $("input[name=clinic_name]").val();
let clinic_mobile = $("input[name=clinic_mobile]").val();
let clinic_email = $("input[name=clinic_email]").val();
let recommend = $("input[name=recommend]").val();
let website = $("input[name=website]").val();
let address_1 = $("input[name=address_1]").val();
let clinic_speciality = $(".clinic_speciality").val();
//alert(clinic_speciality);
let country_id = $(".country_id").val();
let state_id = $(".state_id").val();
let city_id = $(".city_id").val();
let locality_id = $(".locality_id").val();
let zipcode = $(".zipcode").val();
let note = $(".note").val();
var clinic_image = $('.clinic_image').prop('files')[0];  
formData.append('old_clinic_pic', old_profile_pic);
formData.append('clinic_name', clinic_name);
formData.append('clinic_mobile', clinic_mobile);
formData.append('clinic_email', clinic_email);
formData.append('recommend', recommend);
formData.append('website', website);
formData.append('note', note);
formData.append('address_1', address_1);
formData.append('country_id', country_id);
formData.append('state_id', state_id);
formData.append('city_id', city_id);
formData.append('locality_id', locality_id);
formData.append('zipcode', zipcode);
formData.append('clinic_image', clinic_image);
formData.append('clinic_speciality', clinic_speciality);

}

if(currentIndex=='4'){

  var formData = new FormData($(form)[0]);
  jQuery('.loading-all').show();

 $.ajax({
		url: "{{route('addDoc')}}",
		type: 'POST',
		contentType: 'multipart/form-data',
		cache: false,
		contentType: false,
		processData: false,
		data: formData,
		success: (response) => {
			// success
			console.log("success",response);
      if(currentIndex==4){
        sessionStorage.clear();
       if(response.status && response.status==200){
        jQuery('.loading-all').hide();
        var encodedString = btoa(response.doc_id);
        swal("Registerd!", "You are Successfully registerd", "success");
      
             window.location.href = "{{ route('hgOffersPlans')}}"+'?doc_id='+response.doc_id;
       }
      }
      
		},
		error: (response) => {
			console.log("error",response);
		}
	});

return false;

}

  let doctor_email = $("input[name=email]").val();

  if(doctor_email){
    sessionStorage.setItem("email", doctor_email);
  }
  let doctor_mobile_no = $("input[name=mobile_no]").val();
  if(doctor_mobile_no){
 
    sessionStorage.setItem("mobile_no", doctor_mobile_no);
  }

let email=sessionStorage.getItem("email");
let mobile_no=sessionStorage.getItem("mobile_no");
formData.append('mobile_no', mobile_no);

formData.append('email', email);

formData.append('currentIndex', currentIndex);
	

    $.ajax({
		url: "{{route('addDoc')}}",
		type: 'POST',
		contentType: 'multipart/form-data',
		cache: false,
		contentType: false,
		processData: false,
		data: formData,
		success: (response) => {
			// success
			console.log("success",response);
      if(currentIndex==4){
        sessionStorage.clear();
       if(response.status && response.status==200){
      
        var encodedString = btoa(response.doc_id);
        swal("Registerd!", "You are Successfully registerd", "success");
      
             window.location.href = "{{ route('hgOffersPlans')}}"+'?doc_id='+response.doc_id;
       }
      }
      
      if(response.varify_status && response.varify_status==1){
        swal("Already Exist!", "Your are already varified doctor", "success");
        window.location.href = "{{ route('hgOffersPlans')}}"+'?doc_id='+encodedString;
      } 
     
      // jQuery.each(response.errors, function(key, value){
   
      //             			jQuery('.alert-danger').show();
      //             			jQuery('.alert-danger').append('<p>'+value+'</p>');
      // });
		},
		error: (response) => {
			console.log("error",response);
		}
	});


  
}


$('.checkdoctor').keyup(function(event){
 
  if(event.which=='13'){

    return false;

  }

  let value=$(this).val();
  let testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
 
 if(testEmail.test(value) || $.isNumeric(value) && value.length=='10'){
  //   // Do whatever if it passes.
  // console.log("============",value);
	$.ajax({
		url: "{{route('checkDoctor')}}",
		type: 'POST',
	  data: {'data':value},
    success:function(response) {
          if(response.status==200){

           
          if(response.profile_pic){
            $(".old_profile_pic").attr('src',response.profile_pic).show();
          }  
         
         if(response.fileclinic){
          $(".old_clinic_pic").attr('src',response.fileclinic).show();
         }
         
         $(".old_profile_pic").val(response.data.profile_pic);
         $(".old_clinic_pic").val(response.data.clinic_image);
         // $("#image_apload_profile").html('<img src="'+response.profile_pic+'">');
         
       
          
           console.log("================",response.data);
          $("input[name=age]").val(response.data.age);
          $("input[name=first_name]").val(response.data.first_name);
          $("input[name=last_name]").val(response.data.last_name);
          $("input[name=email]").val(response.data.email);
          $("input[name=mobile_no]").val(response.data.mobile_no);
          $("#about_us").val(response.data.about);
          $("input[name=experience]").val(response.data.experience);
          $("input[name=qualification]").val(response.data.qualification);
          $("input[name=reg_no]").val(response.data.reg_no);
          $("input[name=last_obtained_degree]").val(response.data.last_obtained_degree);
          $("#reg_year").val(response.data.reg_year);
          $("#university").val(response.data.university);
          $("#degree_year").val(response.data.degree_year);
          $("#reg_council").val(response.data.reg_council);

          $("input[name=acc_no]").val(response.data.acc_no);
          $("input[name=ifsc_no]").val(response.data.ifsc_no);
          $("input[name=bank_name]").val(response.data.bank_name);
          $("input[name=acc_name]").val(response.data.acc_name);
          $("input[name=paytm_no]").val(response.data.paytm_no);

         
          $("input[name=clinic_name]").val(response.data.clinic_name);
          $("input[name=clinic_mobile]").val(response.data.clinic_mobile);
          $("input[name=clinic_email]").val(response.data.clinic_email);
          $("input[name=recommend]").val(response.data.recommend);
          $("input[name=website]").val(response.data.website);
          $(".note").val(response.data.note);
          $("input[name=address_1]").val(response.data.address_1);
          $(".country_id").val(response.data.country_id);
          $(".state_id").val(response.data.state_id);
          $(".city_id").val(response.data.city_id);
          $(".locality_id").val(response.data.locality_id);
          $(".zipcode").val(response.data.zipcode);
         //alert(response.data.clinic_speciality);
          $("#clinic_speciality").val(response.data.clinic_speciality);

          if(response.data.gender=='M'){
        
            var radioBtn = $('<input id="gender1" type="radio" class="gender" name="gender" id="test8" value="M" checked ><label for="gender1">Male</label><span class="help-block"></span>');
            $('#genderradio').html(radioBtn);
           $('#hidethis').hide();
      

          }else{
            
         
            var radioBtn = $('<input id="gender2" type="radio" class="gender" name="gender" id="test9" value="F" checked ><label for="gender2">Female</label>');
           $('#genderradiof').html(radioBtn);
           $('#hidethisf').hide();
        
          

          }
       
          if(response.opd_timings!=''){
   
          $('#main-div-scheduleAzax').html(response.opd_timings);
          $('.hidethisSchedule').remove();
       
          $('.showthisschedule').show();
          

          }
           swal("Already Exist!", "Your Profile is under process please complete if any details are pending Thanks!", "success");
             
             var encodedString = btoa(response.data.id);
             if(response.data.varify_status && response.data.varify_status==1){
            swal("Already Exist!", "Your are already varified doctor", "success");
            window.location.href = "{{ route('hgOffersPlans')}}"+'?doc_id='+encodedString;
             }

             if(response.slot_duration){
              // alert(response.slot_duration);
              $('.slot_duration').remove();
             }

           
         
          
         // $("input[name=radio][value='M']").prop('checked', true);
         
          }
        },
        error:function (response) {
   
        }
	});

}

})


// $('.checkbynumber').keyup(function(e){

// let value=$(this).val();
// if($.isNumeric(value) && value.length=='10'){
// //   // Do whatever if it passes.
// // console.log("============",value);

// $.ajax({
//   url: "{{route('checkDoctor')}}",
//   type: 'POST',
//   data: {'data':value},
//   success:function(response) {
//         if(response.status==200){

        
//           if(response.profile_pic){
//             $(".old_profile_pic").attr('src',response.profile_pic).show();
//           }  
         
//          if(response.fileclinic){
//           $(".old_clinic_pic").attr('src',response.fileclinic).show();
//          }
//          $(".old_profile_pic").val(response.data.profile_pic);
//          $(".old_clinic_pic").val(response.data.clinic_image);

        

//           $("input[name=age]").val(response.data.age);
//           $("input[name=first_name]").val(response.data.first_name);
//           $("input[name=last_name]").val(response.data.last_name);
//           $("input[name=email]").val(response.data.email);
//           $("input[name=mobile_no]").val(response.data.mobile_no);
//           $("#about_us").val(response.data.about);
//           $("input[name=experience]").val(response.data.experience);
//           $("input[name=qualification]").val(response.data.qualification);
//           $("input[name=reg_no]").val(response.data.reg_no);
//           $("input[name=last_obtained_degree]").val(response.data.last_obtained_degree);
//           $("#reg_year").val(response.data.reg_year);
//           $("#university").val(response.data.university);
//           $("#degree_year").val(response.data.degree_year);
//           $("#reg_council").val(response.data.reg_council);

//           $("input[name=acc_no]").val(response.data.acc_no);
//           $("input[name=ifsc_no]").val(response.data.ifsc_no);
//           $("input[name=bank_name]").val(response.data.bank_name);
//           $("input[name=acc_name]").val(response.data.acc_name);
//           $("input[name=paytm_no]").val(response.data.paytm_no);

         
//           $("input[name=clinic_name]").val(response.data.clinic_name);
//           $("input[name=clinic_mobile]").val(response.data.clinic_mobile);
//           $("input[name=clinic_email]").val(response.data.clinic_email);
//           $("input[name=recommend]").val(response.data.recommend);
//           $("input[name=website]").val(response.data.website);
//           $(".note").val(response.data.note);
//           $("input[name=address_1]").val(response.data.address_1);
//           $(".country_id").val(response.data.country_id);
//           $(".state_id").val(response.data.state_id);
//           $(".city_id").val(response.data.city_id);
//           $(".locality_id").val(response.data.locality_id);
//           $(".zipcode").val(response.data.zipcode);
         
//           $("#clinic_speciality").val(response.data.clinic_speciality);
          
          
//           if(response.data.gender=='M'){
          
//             var radioBtn = $('<input id="gender1" type="radio" class="gender" name="gender" id="test8" value="M" checked ><label for="gender1">Male</label><span class="help-block"></span>');
//             $('#genderradio').html(radioBtn);
//            $('#hidethis').hide();
//            alert(radioBtn.length);

//           }else{
            
         
//             var radioBtn = $('<input id="gender2" type="radio" class="gender" name="gender" id="test9" value="F" checked ><label for="gender2">Female</label>');
//            $('#genderradiof').html(radioBtn);
//            $('#hidethisf').hide();
        
          

//           }
         
         
        

//           if(response.opd_timings!=''){
            
              
//           $('#main-div-scheduleAzax').html(response.opd_timings);
//           $('.hidethisSchedule').remove();
          
          
//           $('.showthisschedule').show();

//           }

//            swal("Already Exist!", "Your are already varified doctor", "success");
             
//              var encodedString = btoa(response.data.id);
//              if(response.data.varify_status && response.data.varify_status==1){
//             swal("Already Exist!", "Your are already varified doctor", "success");
//             window.location.href = "{{ route('hgOffersPlans')}}"+'?doc_id='+encodedString;
//              }

//              if(response.slot_duration){
//               // alert(response.slot_duration);
//               $('.slot_duration').remove();
//              }
             
//         }
//       },
//       error:function (response) {
 
//       }
// });

// }

// })

// $(function()
//     {
//       $('.teleconsult').change(function()
//       {
//         if ($(this).is(':checked')) {
//            // Do something...
//            alert('You can rock now...');
//         };
//       });
// });

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



</script>
@endsection
