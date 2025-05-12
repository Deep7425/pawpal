@extends('amp.layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@section('content')

<div class="container-inner login-wrapper email-form-wrapper">
  <div class='example'>
    <div class='tabsholder1'>
	 <div class="tab-content">
      <div id="login_tab">
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
		   @if (session('status'))
			<div class="alert alert-success" role="alert">
				{{ session('status') }}
			</div>
			@endif
          <div class="registration-wrap login-div-mobile">
		  <form method="POST" action="{{ route('forgotEmail') }}">
		    @csrf
            <h1>Health Gennie Reset Password</h1>
            <div class="form-fields">
				<label for="email">{{ __('E-Mail Address') }}</label>
				<input id="email" name="email"  class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" value="{{ old('email') }}" required placeholder="Enter Your Registered Email" />
				@if ($errors->has('email'))
					<span class="invalid-feedback" role="alert">
						{{ $errors->first('email') }}
					</span>
				@endif	
            </div>
			 <div class="form-fields btn btn-primary reset-password">
                <button type="submit" class="reset_btn">{{ __('Send Link') }}</button>
              </div>
           </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
<script>
	$(document).ready(function(){
		jQuery(document).on("click", ".reset_btn", function () {
			if($("#email").val()){
				jQuery('.loading-all').show();
			}
		});
	});
</script>
@endsection
