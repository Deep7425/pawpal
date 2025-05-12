<?php header("Location: ".url("/")."/login"); die;?>
@extends('amp.layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@section('content')

<div class="container-inner login-wrapper register-form-wrapper">
  <div class='example'>
    <div class='tabsholder1'>
		<!--<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" class="tab_class_login" id="1" >Login</a></li>
			<li><a data-toggle="tab" id="2" class="tab_class_login">Signup</a></li>
		</ul>-->
	 <div class="tab-content">
      <div id="login_tab">
		
			@if(Session::get('loginFrom') == '1')
			  <div id="myCarousel" class="carousel slide contnet-sec" data-ride="carousel">
				<h2>MANAGE YOUR <span>HEALTH</span> <br />ALL AT ONE PLACE</h2>
				<div class="carousel-inner">
				  <div class="item active">
					 <img src="{{ URL::asset('img/thyrocareLoginImg.png') }}" alt="Chicago">
					 <h5>Get full body check up and hassle free sample collection<br />at comfort of your home.</h5>
				  </div>
				</div>
			  </div>
		@elseif (Session::get('loginFrom') == '2')
			<div id="myCarousel" class="carousel slide contnet-sec" data-ride="carousel">
			<h2>MANAGE YOUR <span>HEALTH</span> <br />ALL AT ONE PLACE</h2>
			<div class="carousel-inner">
			  <div class="item active">
				 <img src="{{ URL::asset('img/1mgloginimage.png') }}" alt="Chicago">
				 <h5>Order online and have your medicines conveniently<br /> delivered at your home..</h5>
			  </div>
			</div>
		  </div>
		@else	
			<div id="myCarousel" class="carousel slide contnet-sec" data-ride="carousel">
			<ol class="carousel-indicators">
			  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			  <li data-target="#myCarousel" data-slide-to="1"></li>
			  <li data-target="#myCarousel" data-slide-to="2"></li>
			</ol>
			<h2>MANAGE YOUR <span>HEALTH</span> <br />ALL AT ONE PLACE</h2>
			<div class="carousel-inner">
			  <div class="item active">
				<img src="{{ URL::asset('img/dr-appointment.png') }}" alt="New york" >
				 <h5>Why wait to see a doctor, when there's a doctor<br />waiting to see you? <!--Get doctor on call instantly by booking Health Gennie User App.--></h5>
			  </div>
			  <div class="item">
				<img src="{{ URL::asset('img/thyrocareLoginImg.png') }}" alt="Los Angeles">
				<h5>Get full body check up and hassle free sample collection<br />at comfort of your home.</h5>
			  </div>
			  <div class="item">
				 <img src="{{ URL::asset('img/1mgloginimage.png') }}" alt="Chicago">
				 <h5>Order online and have your medicines conveniently<br /> delivered at your home..</h5>
			  </div>
			</div>
		  </div>	
		@endif
			  
          <div class="registration-wrap login-div-mobile">
		  {!! Form::open(array('route' => 'register','method' => 'POST', 'id' => 'register-form','enctype' => 'multipart/form-data')) !!}
            <h1>Health Gennie</h1>
            <div class="form-fields">
            	<label>First Name <i class="required_star">*</i></label>
              <input name="full_name"  type="text" value="" placeholder="Enter Your Full Name" />
			  @if(isset($errors)) {{$errors->login->first('first_name')}}@endif
			  <span class="help-block"></span>
            </div>
			<?php /* <div class="form-fields">
            	<label>Last Name</label>
              <input name="last_name"  type="text" value="" placeholder="Enter Your Last Name" />
			  @if(isset($errors)) {{$errors->login->first('last_name')}}@endif
			  <span class="help-block"></span>
            </div> */ ?>
			<div class="form-fields">
            	<label>Mobile Number <i class="required_star">*</i></label>
              <input name="mobile_no"  class="NumericFeildMobile" type="text" value="" placeholder="Enter Your Mobile Number" />
			  @if(isset($errors)) {{$errors->login->first('mobile_no')}}@endif
			  <span class="help-block"></span>
            </div>
			<div class="form-fields">
            	<label>Email</label>
              <input name="email" class="regEmail" type="text" value="" placeholder="Enter Your Email" />
			  @if(isset($errors)) {{$errors->login->first('email')}}@endif
			  <span class="help-block"></span>
            </div>
            <div class="password-section PwdSection" id="PwdSection" style="display:none;">
            	<div class="form-fields">
	            	<label>Password <i class="required_star">*</i></label>
	              <input name="password" type="password" value="" placeholder="Password" id="new_password" />
				  @if(isset($errors)) {{$errors->login->first('password')}}@endif
				  <span class="help-block"></span>
	            </div>
				<div class="form-fields">
	            	<label>Confirm Password <i class="required_star">*</i></label>
	              <input name="cPassword" type="password" value="" placeholder="Confirm Password" autocomplete="off" />
				  <span class="help-block"></span>
	            </div>
            </div>
			
            <div class="form-bot-field">
              <div class="form-fields send-button">
                <button type="submit" class="login_btn">Register</button>
              </div>
            </div>
          {!! Form::close() !!}
        </div>

		<div class="registration-wrap otp-div-login" style="display:none;">
		  {!! Form::open(array('method' => 'POST', 'id' => 'otp-form')) !!}
		  <input type="hidden" name="user_id"/>
            <h1>Health Gennie</h1>
            <div class="form-fields">
              <label>Mobile OTP</label>
              <input name="otp" type="text" name="text" class="" value="" placeholder="Enter Mobile OTP" />
			  <span class="help-block"></span>
            </div>
            <div class="form-bot-field">
              <div class="form-fields send-button">
                <button type="submit" class="confirm_otp_btn">Confirm OTP</button>
              </div>
			  <div class="form-fields send-button btn-otp">
                <button type="button" class="otp_btn" disabled>resend OTP</button>
              </div>
			  <div class="timer_otp"><span id="logTimer"></span></div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
$(".NumericFeildMobile").on("keyup paste", function(e){
      //alert($(this).val());
       if (e.which != 8 && e.which != 0 && e.which != 10 && (e.which < 48 || e.which > 57)) {
      //display error message
        $(this).css('border','1px solid red');
        return false;
      }
      else {
        $(this).css('border','');
      }
});
$(".regEmail").on("keyup paste", function(e){
   	if (this.value.length > 0) {
   		$('.PwdSection').slideDown();
   	}
   	else {
   		$('.PwdSection').slideUp();
   		$('.PwdSection').find('input').val('');
   	}
});

	jQuery(document).on("click", ".otp_btn", function () {
		jQuery('.loading-all').show();
		var userId = jQuery(".otp-div-login").find('input[name="user_id"]').val();
		jQuery.ajax({
			type: "POST",
			dataType : "JSON",
			url: "{!! route('sendUserOtp')!!}",
			data:{'user_id':userId},
			success: function(data) {
				jQuery('.loading-all').hide();
				if(data == 1) {
					jQuery(".otp-div-login").find('.otp_btn').attr('disabled',true);
					timer(30);
				}
				else{
					$.alert({
						title: 'Invalid User',
						content: 'User Id does not exists.',
						draggable: false,
						type: 'green',
						typeAnimated: true,
						buttons: {ok: function(){},}
					  });
					alert("");
				}
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419) {
					location.reload();
				}
			}
		});

	});

	  	 jQuery("#register-form").validate({
				rules: {
					mobile_no:{required:true,minlength:10,maxlength:10,number: true},
					email:{email: true},
					full_name:{required:true,maxlength:100},
					password: {
		              required: function(element) {
		                  if ($('#PwdSection').css('display') != 'none') {
		                      return true;
		                  } else {
		                      return false;
		                  }
		              },
		              minlength : 6,
					  maxlength : 20
			        },
			        cPassword: {
		              required: function(element) {
		                  if ($('#PwdSection').css('display') != 'none') {
		                      return true;
		                  } else {
		                      return false;
		                  }
		              },
		              minlength : 6,
						maxlength : 20,
						equalTo : "#new_password"
			        },
				},
				messages: {
				},
				errorPlacement: function(error, element) {
					 error.appendTo(element.next());
				  },ignore: ":hidden",
				submitHandler: function(form) {
					jQuery('.loading-all').show();
					jQuery('.login_btn').attr('disabled',true);
					jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('register')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
							if(data.status == '0') {
								  $.alert({
									title: 'Account already exists',
									content: 'Please Enter your Mobile No./Email Id to access your account',
									draggable: false,
									type: 'green',
									typeAnimated: true,
									buttons: {
										ok: function() {
											// $(".login-div-mobile").slideUp("slow");
											// $(".otp-div-login").slideDown("slow");
											// jQuery(".otp-div-login").find('input[name="user_id"]').val(data.user_id);
											// timer(30);
											// location.reload();
											window.location = "{{ route('login') }}";
										},
									}
								  });
							}
							else{
								$(".login-div-mobile").slideUp("slow");
								$(".otp-div-login").slideDown("slow");
								jQuery(".otp-div-login").find('input[name="user_id"]').val(data.user_id);
								timer(30);
							}
						 jQuery("#register-form").trigger('reset');
						 jQuery('.loading-all').hide();
						 jQuery('.login_btn').attr('disabled',false);

					   },
					   error: function(error) {
							if(error.status == 401 || error.status == 419) {
								location.reload();
							}
							jQuery('.loading-all').hide();
							jQuery('.login_btn').attr('disabled',false);
						}
				   });
				}
			});

			jQuery("#otp-form").validate({
				rules: {
					otp:{required:true,minlength:6,maxlength:6,number: true},
				},
				messages: {
				},
				errorPlacement: function(error, element) {
					 error.appendTo(element.next());
				  },ignore: ":hidden",
				submitHandler: function(form) {
					jQuery('.loading-all').show();
					jQuery('.confirm_otp_btn').attr('disabled',true);
					jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('confirmOtp')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
						 if( data == 1) {
							location.href = "{{route('drive')}}";
						 }
						 else if( data == 2) {
							$.alert({
								title: 'Invalid Otp',
								content: 'Please try again.',
								draggable: false,
								type: 'green',
								typeAnimated: true,
								buttons: {ok: function(){},}
							  });
						 }
						 jQuery("#otp-form").trigger('reset');
						 jQuery('.loading-all').hide();
						 jQuery('.confirm_otp_btn').attr('disabled',false);
					   }
				   });
				}
			});

			let timerOn = true;
				function timer(remaining) {
				  var m = Math.floor(remaining / 60);
				  var s = remaining % 60;
				  m = m < 10 ? '0' + m : m;
				  s = s < 10 ? '0' + s : s;
				  document.getElementById('logTimer').innerHTML = m + ':' + s;
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
					$(".otp-div-login").find("#logTimer").html('');
					jQuery(".otp-div-login").find('.otp_btn').attr('disabled',false);
				  }
				}

</script>
<script src="{{ URL::asset('js/materialize.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/owlCarousel.js') }}"></script>
<script>
	$(document).ready(function(){
		// $('.register-page-slider').slider();
		  $(".register-page-slider").owlCarousel({
	        items:2,
	        itemsDesktop:[1000,2],
	        itemsDesktopSmall:[979,2],
	        itemsTablet:[767,1],
	        pagination: true,
	        autoPlay:true
	    });
	});
</script>
@endsection
