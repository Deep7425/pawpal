@extends('amp.layouts.Masters.Master')
@section('title', 'Contact Us | Health Gennie')
@section('description', "Have questions about Health Gennie's products, support services, or anything else? Let us know, we would be happy to answer your questions & set up a meeting with you.")	
@section('content') 
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container"> @if (Session::has('message'))
  <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
  @endif
  <div class="container-inner contact-wrapper">
    <h2>Contact us</h2>
    {!! Form::open(array('route' => 'contactUs','method' => 'POST', 'id' => 'contact-form')) !!}
    <div class="form-fields">
      <label>Intrested in <i class="required_star">*</i></label>
      <select name="interest_in">
        <option selected="selected" value="">Please Select</option>
        <option>Create free profile on healthgennie.com</option>
        <option>Free trial for software to manage clinic</option>
        <option>Software to manage my hospital(s) and Clinic</option>
        <option>Advertising my clinic/hospital on healthgennie.com</option>
        <option>Channel partnerships for clinic management software sales</option>
        <option>Support for an existing product/subscription</option>
        <option>Career opportunities</option>
      </select>
      <span class="help-block">
		@if($errors->has('interest_in'))
		<label for="interest_in" generated="true" class="error">
			 {{ $errors->first('interest_in') }}
		</label>
		@endif
	  </span> </div>
        <div class="form-fields">
          <label>Your Name<i class="required_star">*</i></label>
          <input type="text" value="" name="name" placeholder="Enter Full Name" />
          <span class="help-block">
			@if($errors->has('interest_in'))
			<label for="interest_in" generated="true" class="error">
				 {{ $errors->first('interest_in') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Phone Number<i class="required_star">*</i></label>
          <input type="text" value="" name="mobile" class="NumericFeild" placeholder="Your Phone Number" />
          <span class="help-block">
			@if($errors->has('mobile'))
			<label for="mobile" generated="true" class="error">
				 {{ $errors->first('mobile') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Your Email<i class="required_star">*</i></label>
          <input type="text" value="" name="email" placeholder="Your Email ID" />
          <span class="help-block">
			@if($errors->has('email'))
			<label for="email" generated="true" class="error">
				 {{ $errors->first('email') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Subject(Max: 50 Character)<i class="required_star">*</i></label>
          <input type="text" value="" name="subject" placeholder="Subject" />
          <span class="help-block">
			@if($errors->has('subject'))
			<label for="subject" generated="true" class="error">
				 {{ $errors->first('subject') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Your Message(Max: 255 Character)<i class="required_star">*</i></label>
          <textarea name="message" placeholder="Enter Your Message"></textarea>
          <span class="help-block">
			@if($errors->has('message'))
			<label for="message" generated="true" class="error">
				 {{ $errors->first('message') }}
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
      <div class="contact-detail-wrapper">
        <div class="contact-detail">
          <h3><strong>Health Gennie Office</strong></h3>
          <span>Contact us with any questions or inquiries. We would be happy to answer your questions and set up a meeting with you. Health Gennie can help set you apart from the flock!</span>
          <p>C - 94, Satya Vihar, Lal Kothi Scheme, Lalkothi,<br />
            Jaipur, Rajasthan, India 302015</p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="container"> </div>
</div>
<script>


	jQuery("#contact-form").validate({
		rules: {
			name:  {required:true,minlength:2,maxlength:30},
			mobile:{required:true,minlength:10,maxlength:10,number: true},
			email: {required: true,email: true,maxlength:30},
			message: {required: true,maxlength:255},
			subject: {required: true,maxlength:50},
			interest_in: {required:true},
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