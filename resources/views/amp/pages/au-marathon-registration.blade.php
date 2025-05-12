@extends('amp.layouts.Masters.Master')
@section('title', 'AU Marathon Registration')
@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="au-banner">
    	<img src="{{ URL::asset('img/jaipur-marathon.jpg') }}" />
    </div>
<div class="container">

  <div class="container-inner contact-wrapper registration-wrapper">
    <div class="errors-section">
      <ul class="ul-error">

      </ul>
    </div>
    <h2>AU Bank Jaipur Marathon Registration</h2>

    {!! Form::open(array('route' => 'AuMarathonReg', 'action' => route('AuMarathonReg'), 'method' => 'POST', 'id' => 'AuMarathonRegForm')) !!}

        <div class="form-fields">
          <label>Name<i class="required_star">*</i></label>
          <input type="text" value="" name="name" placeholder="Name" />
          <span class="help-block"></span>
        </div>
        <div class="form-fields">
          <label>Email<i class="required_star">*</i></label>
          <input type="text" value="" name="email"  placeholder="Email" />
          <span class="help-block"></span>
        </div>
        <div class="form-fields">
          <label>Mobile Number<i class="required_star">*</i></label>
          <input type="text" value="" class="NumericFeild" name="mobile_no" placeholder="Mobile Number" />
          <span class="help-block"></span>
        </div>
        <div class="form-fields">
          <label>Date of Birth<i class="required_star">*</i></label>
          <div class="date-formet-section">
          <input type="text" class="form-control DOB" name="dob"  placeholder="Date of Birth" readonly />
          <i class="fa fa-calendar" aria-hidden="true"></i>
          </div>
          <span class="help-block"></span>
        </div>
        <div class="form-fields">
          <label>Gender<i class="required_star">*</i></label>
          <div class="radio-wrapper waitingTime top">
            <p><input id="gender1" type="radio" name="gender" id="test8" value="Male" checked><label for="gender1">Male</label></p>
            <p><input id="gender2" type="radio" name="gender" id="test9" value="Female"><label for="gender2">Female</label></p>
          </div>
        <span class="help-block"></span>
        </div>
        <div class="form-fields">
          <label>T-shirt Size <i class="required_star">*</i></label>
          <select name="t_shirt_size">
            <option selected="selected" value="">Please Select</option>
            <option value="S">S</option>
            <option value="M">M</option>
            <option value="L">L</option>
            <option value="XL">XL</option>
            <option value="XXL">XXL</option>
          </select>
          <span class="help-block"></span>
        </div>
        <div class="col-sm-12">
          <div class="write-us-submit">
            <div class="g-recaptcha" data-sitekey="6Le6KYoUAAAAAOBx_xpvhxYYH2qE3HN92bjSz6IR"></div>
            <div class="loding" style="display:none;"><img src="{{ URL::asset('img/turningArrow.gif') }}" /></div>
          </div>
          <div class="btn-register">
                <div class="button-contact text-right">
                  <input type="submit" id="submit" value="Register Now" />
                  <div class="success-data" style="display:none;"></div>
                </div>
          </div>
			  <div class="broucher_dwn">
				 <p class="broucher_txt">Race Day Guide  AU Bank Jaipur Marathon</p><a download href="{{ URL::asset('img/Race-Day-Guide-AU-Bank-Jaipur-Marathon.pdf') }}" target="_blank">Download Brochure</a>
			 </div>
        </div>

        {!! Form::close() !!}
        <div class="col-sm-12">
          <div class="au-terms-condi-section">
              <h3><strong>Terms & Conditions</strong></h3>
            <div class="au-terms-conditions">
              <p>1.	Health Gennie will provide free entry and kit for only Dream Run category (6 K.M).</p>
              <p>2. Dream Run will start from Albert Hall at 6:30 am and will end at WTP.</p>
              <p>3.	You must show the Health Gennie app on your phone while collecting the Kit.</p>
              <p>4.	You must collect the Kit from Diggi Palace, Jaipur on 31st  Jan or 1st Feb 2020.</p>              
              <p>5.	First 500 entries will get the free kit. </p>
              <p>6.	To download the Health Gennie app visit healthgennie.com/download or search <strong>Health Gennie – Healthcare at Home</strong> on Play Store and App Store. </p>
              <p>7.	Health Gennie holds the right to cancel any registration entry based on situations. </p>
              <p>8.	All rights regarding registration through health gennie are reserved by Health Gennie. </p>
            </div>
          </div>
        </div>

  </div>
</div>
<div class="container-fluid">
  <div class="container"> </div>
</div>
<script>
jQuery(document).ready(function () {
  $( ".DOB" ).datepicker({
  	dateFormat: 'dd-MM-yy',
  	maxDate: 0,
  	changeMonth: true,
    changeYear: true,
    yearRange: '1950:2020',
   });
});
	jQuery("#AuMarathonRegForm").validate({
		rules: {
			name:  {required:true,minlength:3,maxlength:50},
			mobile_no:{required:true,minlength:10,maxlength:10,number: true},
			email: {required: true,email: true,maxlength:80},
			dob: {required: true},
			gender: {required: true},
			t_shirt_size: {required:true},
		},
		messages: {
		},
		errorPlacement: function(error, element) {
			 error.appendTo(element.closest('.form-fields').find('.help-block '));
		  },ignore: ":hidden",
		submitHandler: function(form) {
      var response = grecaptcha.getResponse();
      var test = 1;
      if(response.length == 0) {
			// if(test == 0) {
				alert("Robot verification failed, please try again.");
			 }
			else {
				jQuery('.loading-all').show();
        // jQuery('#submit').attr('disabled',true);
        jQuery.ajax({
        type: "POST",
        dataType : "JSON",
        url: $(form).attr('action'),
        data:  new FormData(form),
        contentType: false,
        cache: false,
        processData:false,
        success: function(data)
            {
              // console.log(data);
              jQuery('.loading-all').hide();
              if (data.status == 0) {
                  $('.errors-section .ul-error li').remove()
                $.each(data.error, function(k, v) {
                  var li = "<li>"+v+"</li>"
                  $('.errors-section .ul-error').append(li)
              });
              }
              else{
                if (data == 1) {
                  $("#AuMarathonRegForm")[0].reset();
                  $.alert({
                    title: 'Success !',
                    content: 'Thank you for registering for AU Jaipur Marathon Dream run category' +
                        '<div class="form-group"> <a class="btn btn-default" href="https://www.healthgennie.com/download"> Download App <i class="fa fa-download"></i></a> </div>',
                    draggable: false,
                    type: 'green',
                    typeAnimated: true,
                    buttons: {
                        ok: function(){
                          window.location = "{{ route('index') }}";
                        },
                    }
                  });
                }
                else {
                  $.alert({
                    title: 'Alert !',
                    content: 'You Are Already Registered',
                    draggable: false,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        ok: function(){
                        // location.reload();
                        },
                    }
                  });
                }
              }

           },
            error: function(error)
            {
              if(error.status == 401)
              {
                  alert("Session Expired,Please logged in..");
                  location.reload();
              }
              else
              {
                jQuery('.loading-all').hide();
                alert("Oops Something goes Wrong.");
                jQuery('#submit').attr('disabled',false);
              }
            }
         });

			}
		}
	});
	</script>
@endsection
