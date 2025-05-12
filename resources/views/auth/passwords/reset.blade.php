@extends('layouts.Masters.Master')
@section('title', 'Health Gennie | Book Doctor Appointments, Order Medicine, Diagnostic Tests')
@section('content')
<div class="container-inner login-wrapper reset-form-wrapper">
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
			  
          <div class="registration-wrap login-div-mobile">
			<form method="POST" action="{{ route('password.update') }}">
                        @csrf
						<input type="hidden" name="token" value="{{ $token }}">
            <h1>Health Gennie Reset Password</h1>
            <div class="form-fields half-field" style=" padding-right:0px !important; padding-left:2% !important;">
            	<label for="email">Email</label>
				<input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
			    @if ($errors->has('email'))
					<span class="help-block" role="alert">
						{{ $errors->first('email') }}
					</span>
				@endif
            </div>
			<div class="form-fields half-field" style=" padding-right:0px !important; padding-left:0% !important;">
            	 <label for="password">{{ __('Password') }}</label>
				 <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required />
					@if ($errors->has('password'))
						<span class="help-block" role="alert">
							{{ $errors->first('password') }}
						</span>
					@endif
            </div>
			
			<div class="form-fields">
            	 <label for="password-confirm">{{ __('Confirm Password') }}</label>
				 <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
			 <div class="form-bot-field">
              <div class="form-fields send-button">
                <button type="submit" class="reset_btn">Reset Password</button>
              </div>
             </div>
          </form>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection
