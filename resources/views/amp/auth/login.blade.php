@extends('amp.layouts.Masters.Master')
@if(Session::get('loginFrom') == '1')
@section('title', 'Book Diagnostic Tests from Home at Best Prices | Health Gennie')
@elseif (Session::get('loginFrom') == '2')
@section('title', 'Buy Medicines | Health Gennie')
@else
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@endif
@section('content')

<div class="container-inner login-wrapper">
  <div class='example'>
    <div class='tabsholder1'>
	 <div class="tab-content">
      <div id="login_tab">
			<div id="myCarousel" class="carousel slide contnet-sec" data-ride="carousel">
			<h2>MANAGE YOUR <span>HEALTH</span> <br />ALL AT ONE PLACE</h2>
			<div class="carousel-inner">
			  <div class="item active">
				<img src="{{ URL::asset('img/dr-appointment.png') }}" alt="New york" >
				 <h5>Why wait to see a doctor, when there's a doctor<br />waiting to see you? <!--Get doctor on call instantly by booking Health Gennie User App.--></h5>
			  </div>
			</div>
		  </div>

          <div class="registration-wrap login-div-mobile">
		  {!! Form::open(array('route' => 'login','method' => 'POST', 'id' => 'login-form','enctype' => 'multipart/form-data')) !!}
            <h1>Health Gennie</h1>
            <div class="form-fields">
            	<label>Mobile No.</label>
              <input name="mobile_no"  class="checkEmail" type="text" value="" placeholder="Mobile No." />
			  <span class="help-block"></span>
            </div>
			<div class="form-fields password-feild-div" style="display:none;">
				  <label>Password</label>
				  <input name="password"  type="password" value="" placeholder="Password" />
				  @if(isset($errors)) {{$errors->login->first('password')}}@endif
				  <span class="help-block"></span>
            </div>
            <div class="form-bot-field">
              <div class="form-fields">
                <label class="chck-container">Remember Me
                  <input type="checkbox"/>
                  <span class="checkmark"></span></label>
              </div>
              <div class="form-fields send-button">
                <button type="submit" class="login_btn">Login</button>
              <!--   <div class="form-fields send-button btn-forget-pass">
                <a href="{{ route('password.request') }}" class="btn forget_btn">Forgot password?</a>
              </div> -->
              </div>
			  <!-- <p class="Signup-section">New on Health Gennie? <a href="{{ route('register')}}">Sign Up</a></p> -->

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
<div class="container-fluid">
  <div class="container">
  </div>
</div>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script>
// jQuery(document).on("keyup paste keypress", ".checkEmail", function (e) {
// 	jQuery(this).closest(".form-fields").find(".help-block .error").hide();
// 	var letters = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//     if($(this).val().match(letters)) {
// 		//$(this).removeClass("NumericFeildMobile");
// 		$(".password-feild-div").show();
// 		$(this).attr("name",'email');
// 	}
// 	else if($(this).val().match(/^[0-9]+$/)) {
// 		$(".password-feild-div").hide();
// 		$(this).attr("name",'mobile_no');
// 		//$(this).addClass("NumericFeildMobile");
// 	}
// });
jQuery(document).on("keyup paste", ".NumericFeildMobile", function (e) {
      if (e.which != 8 && e.which != 0 && e.which != 10 && (e.which < 48 || e.which > 57)) {
		  $(this).css('border','1px solid red');
		  return false;
      }
      else {
		   $(this).css('border','');
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
					alert("User Id Does't Exists");
				}
			},
			error: function(error) {
				if(error.status == 401) {
					alert("Session Expired,Please logged in..");
					location.reload();
				}
				else{
					alert("Oops Something goes Wrong.");
				}
			}
		});

	});
	  	 jQuery("#login-form").validate({
				rules: {
					mobile_no:{required:true,minlength:10,maxlength:10,number: true},
					password:{required:true},
					email:{required:true,maxlength:100,email: true},
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
					url: "{!! route('login')!!}",
					data:  new FormData(form),
					contentType: false,
					cache: false,
					processData:false,
					success: function(data) {
							if(data.status == '1' && data.type == "mobile") {
								$(".login-div-mobile").slideUp("slow");
								$(".otp-div-login").slideDown("slow");
								jQuery(".otp-div-login").find('input[name="user_id"]').val(data.user_id);
								timer(60);
							}
							else if(data.status == '0' && data.type == "mobile") {
								$.alert({
									title: 'health Gennie Login',
									content: data.msg,
									draggable: false,
									type: 'green',
									typeAnimated: true,
									buttons: {ok: function() {},}
								 });
							}
							else if(data.status == '0' && data.type == "email") {
								$.alert({
									title: 'health Gennie Login',
									content: data.msg,
									draggable: false,
									type: 'green',
									typeAnimated: true,
									buttons: {ok: function() {},}
								 });
							}
							else if(data.status == '1' && data.type == "email") {
								location.href = "{{route('drive')}}";
							}
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
					otp: {number:"Please Enter Valid OTP"},
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
						 else if(data == 2) {
							alert("Invalid OTP");
						 }
						 else if(data == 3) {
							alert("OTP is Expired");
						 }
						 else {
							$(form).find('.help-block').append('<label for="otp" generated="true" class="error">'+data[0]+'</label>')
							$(form).find('.help-block').show();
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
					// alert('Timeout for otp');
				  }
				}

</script>
@endsection
