@extends('layouts.Masters.Master')
@section('title', 'Donation | Health Gennie')
@section('description', "Have questions about Health Gennie's products, support services, or anything else? Let us know, we would be happy to answer your questions & set up a meeting with you.")	
@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container"> @if (Session::has('message'))
  <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
  @endif
  <div class="container-inner contact-wrapper registration-wrapper EachOneHelpOne">
	<h2>HELP FAMILIES GET THE HEALTH CARE THEY DESERVE</h2>
	<p class="gray">The same situation has come again where beds are not available in the Hospitals, Covid patients are increasing at an exorbitant pace, businesses are getting closed and people again are struggling for their survival.
People are looking for medical supplies such oxygen cylinders, medicines, Pulse oximeter and much more. Even consulting doctors for Covid treatment is becoming a big challenge in India.</p>

	<p class="gray">Daily wages workers, dependents, people in tier3, tier 4 and villages are not able to travel to cities for treatments of covid and non covid problems.We think its time to think about the survival and help mankind.</p>

	<p>We at Health Gennie has taken this initiative to help needy persons who are getting deprived on the Health care they need no matter where they are. With our slogan of <strong>EACH ONE HELP ONE</strong>, we are helping the needy ones in getting the doctor consultations, medicines, medical equipments etc. at NO COST to them.</p>
	<p>With your small help, a family may survive this pandemic and take care of their children. We want to help 10000 such families and each of your contribution can make a big difference.</p>

	<p>To help, please fill the information below and submit it. You can make the payment online which will then be used to help needy people get the healthcare in this difficult times. For more information, you can email us at <a href="mailto:info@healthgennie.com">info@healthgennie.com</a> or call us at <a href="tel:+91-9414061829">+91-9414061829</a>.</p>

	<?php $user = Auth::user(); ?>
    {!! Form::open(array('route' => 'donation','method' => 'POST', 'id' => 'donation-form')) !!}
        <div class="form-fields">
          <label>Your Name<i class="required_star">*</i></label>
          <input type="text" value="@if(Auth::user()){{@$user->first_name}} {{@$user->last_name}}@endif" name="name" placeholder="Enter Full Name" />
          <span class="help-block">
			@if($errors->has('name'))
			<label for="name" generated="true" class="error">
				 {{ $errors->first('name') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Phone Number<i class="required_star">*</i></label>
          <input type="text" value="{{@$user->mobile_no}}" name="mobile_no" class="NumericFeild" placeholder="Your Phone Number" />
          <span class="help-block">
			@if($errors->has('mobile_no'))
			<label for="mobile_no" generated="true" class="error">
				 {{ $errors->first('mobile_no') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Your Email</label>
          <input type="text" value="{{@$user->email}}" name="email" placeholder="Your Email ID" />
          <span class="help-block">
			@if($errors->has('email'))
			<label for="email" generated="true" class="error">
				 {{ $errors->first('email') }}
			</label>
			@endif
		  </span>
        </div>
		<div class="form-fields">
          <label>Donation Amount (INR)<i class="required_star">*</i></label>
          <input type="text" value="" name="amount" class="NumericFeild" placeholder="Donation Amount (INR)" />
          <span class="help-block">
			@if($errors->has('amount'))
			<label for="amount" generated="true" class="error">
				 {{ $errors->first('amount') }}
			</label>
			@endif
		  </span>
        </div>
		<div class="col-sm-12">
			   <div class="write-us-submit">
				<div class="g-recaptcha" data-sitekey="6Le6KYoUAAAAAOBx_xpvhxYYH2qE3HN92bjSz6IR"></div>
				<div class="loding" style="display:none;"><img src="{{ URL::asset('img/turningArrow.gif') }}" /></div>
			  </div>

			  <div class="button-contact text-right">
				<input type="submit" id="submit" value="Submit" />
				<div class="success-data" style="display:none;"></div>
			  </div>
		  {!! Form::close() !!}
		</div>
	</div>
</div>
<div class="container-fluid">
  <div class="container"> </div>
</div>
<script>
	jQuery("#donation-form").validate({
		rules: {
			name:  {required:true,minlength:2,maxlength:30},
			mobile_no:{required:true,minlength:10,maxlength:10,number: true},
			email: {email: true,maxlength:30},
			amount: {required: true,number: true,maxlength:5},
		},
		messages: {
		},
		errorPlacement: function(error, element) {
			 error.appendTo(element.next());
		},ignore: ":hidden",
		submitHandler: function(form) {
			var response = grecaptcha.getResponse();
			if(response.length == 0) {
				alert("Robot verification failed, please try again.");
			}
			else {
				jQuery('.loading-all').show();
				form.submit();
			}
		}
	});
	</script> 
@endsection 