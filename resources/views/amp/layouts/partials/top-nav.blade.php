<script>
$(window).scroll(function() {
if ($(this).scrollTop() > 1){  
    $('header').addClass("sticky");
  }
  else{
    $('header').removeClass("sticky");
  }
});
</script>
<header class="top-navbaar float-panel ">
<input id="session_lat" type="hidden" value='{{@Session::get("session_lat")}}'/>
<input id="session_lng" type="hidden" value='{{@Session::get("session_lng")}}'/>
<input id="search_data_by_search_id" type="hidden" value="{{ Session::get('search_from_search_bar') }}"/>
<input id="profileStatusSess" type="hidden" @if(Auth::user() != null && Request::path() != "user-profile") value="{{ Session::get('profile_status') }}" @else value="" @endif/>
<input id="userLoginStatus" type="hidden" @if(Auth::user() != null) value="1" @else value="" @endif />
<style>
.btn1InstallApp{ margin-top:30px;}
</style>
<div class="download-app-paytmchangeDiv">
<div class="download-app download-app-paytm">
<div class="download-app-inner">
<p>Download The App</p>
<a href="https://www.healthgennie.com/download">
	<img width="115" height="36" src="{{ URL::asset('img/download-app.png') }}" alt="health-gennie-app" />
</a>
</div>
</div>
<div class="call_btn_div remove_btn download-app-paytm">
<p><a href='tel:{{getSetting("helpline_number")[0]}}'><img width="100" height="82" src="{{ URL::asset('img/call-btn.png') }}" alt="call-icon" /></a></p>
</div>
<div class="top-bar">
	<div class="container"> 
    	<div class="top-order-list">
<li class="login paytmDivforreplace covid-info"><a class="login-btn-top" href="{{route('covidGuide')}}"><button type="button" class="btn btn-default"><i class="" aria-hidden="true"><img width="22" height="19" src="{{ URL::asset('img/covid-icon.webp') }}" alt="covid-icon" /></i>Covid Guide</button></a></li>
@if(Auth::user())
		<?php $user = Auth::user(); ?>
		<?php
		if(!empty($user->image)) {
			$image_url = getEhrUrl()."/public/patients_pics/".$user->image;
			if(does_url_exists($image_url)) {
				$image_url = $image_url;
			}
			else{
				$image_url = null;
			}
		}
		else{
			$image_url = null;
		} ?>
	<li class="dropdown sub-nev-tool">
	  <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="javascript:void(0);"><img  class="top-user-img" @if($image_url != null) src="{{$image_url}}" @else src="{{ URL::asset('img/avatar_2x.png') }}" @endif/><span class="user-nametop">@if(!empty($user->first_name)) {{@$user->first_name}} {{@$user->last_name}} @else {{@$user->mobile_no}} @endif</span><span class="caret"></span>
	  </a>
	  <ul id="g-account-menu" class="dropdown-menu" role="menu">
	  @if(Auth::id() != null && checkUserSubcriptionStatus(Auth::id()))
		<div class="elite-member">
			<div class="bg">&nbsp;</div>
			<div class="text">Elite</div>
		</div>
		@endif
		<li><a href="{{ route('drive') }}">
			<img width="50" height="50" @if($image_url != null) src="{{$image_url}}" @else src="{{ URL::asset('img/avatar_2x.png') }}" @endif/>
				@if(!empty($user->first_name)) {{@$user->first_name}} {{@$user->last_name}} @else {{@$user->mobile_no}} @endif
		</a></li>
		<!--<li><a href="{{route('changePassword',['id'=>base64_encode(Auth::id())])}}"><i class="fa fa-lock"></i>Change Password</a></li>-->
		<li class="hideforPaytm"><a href="javascript:void(0);" class="logoutUser" ><i class="fa fa-sign-out"></i>Logout</a></li>
	  </ul>
	</li>
	@else
		@if($controller == "DocController" && $action == "addDoc")
			<li class="login paytmDivforreplace"><a class="login-btn-top" href="https://doc.healthgennie.com/login"><button type="button" class="btn btn-default"><i class="" aria-hidden="true"><img width="15" height="19" src="{{ URL::asset('img/login.png') }}" /></i> Login</button></a></li>
		@else 
			<li class="login paytmDivforreplace"><a class="login-btn-top" href="{{ route('login') }}"><button type="button" class="btn btn-default"><i class="" aria-hidden="true"><img width="15" height="19" src="{{ URL::asset('img/login.png') }}" /></i> Login</button></a></li>
		@endif
		
		
	@endif
		<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
			{{ csrf_field() }}
		</form>
		@if($controller == "DocController" && $action == "addDoc")
		@else	
		<li class="doctor hideforPaytm"><a target="_blank" href='https://doc.healthgennie.com'><button type="button" class="btn btn-warning doctor_btn_Hg"><i class="" aria-hidden="true"><img width="15" height="17" src="{{ URL::asset('img/doctor.webp') }}" /></i>For Doctors</button></a></li>
		@endif
		<?php
		if(Auth::user() != null){
		  $LabCart = getLabCart();

		}
		else{
		  $LabCart = Session::get("CartPackages");
		}
		?>
        </div>
    </div>
</div>

<nav class="navbar navbar-default">
<div class="container">
<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="{{route('index')}}"><img width="144" height="60" src="{{ URL::asset('img/logo.png') }}" alt="short-logo" /></a>
  <a  class="navbar-brand mobile-vaccination" href="{{route('vaccinationDrive')}}"><div class="icon-top" style=" width:28px;"><img src="{{ URL::asset('img/vaccinationDriveIcon.webp') }}" alt="vaccine-drive" width="46" height="44" /></div><div class="nav-section"><h3>Vaccination Drive</h3></div></a>
</div>

<div class="navbaar-bottom  mobile-search">
	@if($controller == "LabController")
		<div class="mob-nav-ico open-tab-mobile-lab">
			<a href="javascript:void(0);"><img width="20" height="19" src="{{ URL::asset('img/mob-ico.png') }}" alt="mob-ico" /></a>
		</div>
		<button type="button" class="btn btn-default searchLabModalDoctor" data-toggle="modal" ><i class="" aria-hidden="true"><img width="20" src="{{ URL::asset('img/doctor-mail.png') }}" alt="doctor-mail" /></i>Search Lab</button>
	@else
	<button type="button" class="btn btn-default searchDoctorModalArea" data-toggle="modal" ><i class="fa fa-map-marker" aria-hidden="true"></i><span class="area_name">@if(!empty(Session::get("search_from_locality_name"))) {{ Session::get("search_from_locality_name") }} @else Location @endif</span></button>
	<button type="button" class="btn btn-default searchDoctorModalDoctor" data-toggle="modal" ><i class="" aria-hidden="true"><img width="20" height="19" src="{{ URL::asset('img/doctor-mail.png') }}" alt="doctor-mail" /></i>Search Doctors</button>
	@endif
</div>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
  </ul>
  <ul class="nav navbar-nav navbar-right">
	<li class="active apoointment main-nav-desktop">
      <a  class="dd" href="{{route('vaccinationDrive')}}"><span class="text" style="display:none;">Vaccination Drive</span><div class="icon-top" style=" width:46px;"><img src="{{ URL::asset('img/vaccinationDriveIcon.webp') }}" alt="vaccine-drive" width="46" height="44" /></div><div class="nav-section"><h3>Vaccination Drive</h3><p>Information Center</p></div></a>
    </li>
  	<li class="active apoointment main-nav-desktop" style=" width: 175px;">
		<a  class="dd" href="{{route('covidGuide')}}"><span class="text" style="display:none;">COVID</span><div class="icon-top" style=" width:46px;"><img src="{{ URL::asset('img/covid-icon.png') }}" style=" margin-top:-3px" width="46" height="43" alt="covid-icon" /></div><div class="nav-section"><h3>Covid Guide</h3><p>Information Center</p></div></a>
	</li>
	<li class="active apoointment main-nav-desktop lab-test">
		<a search_type="1" data_id="0" info_type="doctor_all" class="dd searchDoctorModalDoctor" @if($controller == "LabController") href="{{route('index')}}" @else href="javascript:void(0);" @endif ><span class="text" style="display:none;">Doctor</span>
        <div class="icon-top"><img width="35" height="35"  src="{{ URL::asset('img/appointment-ico-top.png') }}" alt="appointment-icon" /></div><div class="nav-section"><h3>Appointment</h3><p>Find Doctors</p></div></a>
	</li>
	<!--<li class="lab-test main-nav-desktop"><a href="{{route('LabDashboard')}}" ><div class="icon-top"><img width="35" height="35" src="{{ URL::asset('img/lab-icon-top.png') }}" alt="lab-icon" /></div><div class="nav-section"><h3>Lab Test</h3><p>Book Health Packages</p></div></a>
	</li>-->
	
		<li class="medicine main-nav-desktop cart-wrapper hideforPaytmCart"  data-toggle="tooltip" title="@if(!empty($LabCart)) Cart @else Cart is Empty! @endif">
			<a href="{{ route('LabCart') }}"><img src="{{ URL::asset('img/cart.webp') }}" width="25" height="25" alt="cart-icon" /><div class="nav-section"><p> <span class="cartTotal" id="cartTotal">@if(!empty($LabCart)) {{count($LabCart)}} @else 0 @endif</span> </p></div></a>
			<ul class="cart-dd" id="miniCart"
			style='display:@if(empty($LabCart) || $LabCart == null || Request::route()->getName() == "LabCart") none @endif;'>
				<li>
					<div class="dd-title">
						<h4>Order Summary</h4>
						<!-- <h3 class="totalTest">@if(!empty($LabCart)) {{count($LabCart)}} @else 0 @endif Test</h3> -->
					</div>
					<div id="miniCartList">
						@if(!empty($LabCart))
							@foreach($LabCart as $package)
							<div class="list" title="{{$package['name']}}" data-name="{{$package['name']}}"><img width="15" height="15" src="{{asset('img/OurStore-icon.png')}}" alt="store-icon" /><h5> <a href="{{route('LabDetails', ['id'=> base64_encode($package['name']), 'type' => base64_encode($package['type'])])}}">{{$package['name']}}</a></h5> <span><strong>1 x ₹ @if($package['rate']['offer_rate'] == "null") {{$package['rate']['b2c']}} @else {{$package['rate']['offer_rate']}} @endif </strong></span>
			 <a class="close deleteFromMiniCart" href="javascript:void(0);"  Pcode="{{$package['code']}}" Pname="{{$package['name']}}"><i class="fa fa-times-circle" aria-hidden="true"></i></a>
							</div>
							@endforeach
						@endif
					</div>
					<div class="cartButtons">
						<a  href="{{ route('LabCart') }}" class="cart">View Cart</a>
					</div>
				</li>
			</ul>
		</li>
	</ul>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
</div>
<?php 
$latestApptFeedback = latestAppointmentFeedback();
?>
<input name="latestApptFeedback" type="hidden" class="latestApptFeedback" value='@if(!empty($latestApptFeedback)) {{ json_encode($latestApptFeedback) }} @endif'/>
@if($controller == "LabController")
	<div class="collapse main-nav-mobile-lab container-fluid">
		<div class="container">
			<ul class="nav navbar-nav navbar-right">
			<li class="apoointment">
				<a class="dd searchDoctorModalDoctor" @if($controller == "LabController") href="{{route('index')}}" @else href="javascript:void(0);" @endif ><span class="text" style="display:none;">Doctor</span><img width="25" height="25" src="{{ URL::asset('img/appointment-ico.png') }}" alt="appointment-icon" /><div class="nav-section"><h3>Appointment</h3><p>Find Doctors</p></div></a>
			</li>
			<li class="lab-test"><a href="{{route('LabDashboard')}}" class=""><img width="25" height="25" src="{{ URL::asset('img/lab-icon.png') }}" /><div class="nav-section"><h3>Lab Test</h3><p>Book Health Packages</p></div></a></li>
			 <li class="blogs-details"><a href="{{ route('blogList') }}"><img width="25" height="25" src="{{ URL::asset('img/blogs.png') }}" alt="blogs" /><div class="nav-section"><h3>Blogs</h3><p>Articals & Activity</p></div></a></li>
			</ul>
		</div>
	</div>
<div class="navbaar-bottom desktop-search lab-search-div is_website" @if(isset($_COOKIE['in_mobile']) && $_COOKIE['in_mobile'] == '0') style="display: block;" @else style="display: none;" @endif>
	<div class="container">
		<div class="navbaar-bottom-section">
			<div class="navbaar-bottom-box2">
				<div class="navbaar-bottom-box">
					<input type="search" class="labSearching" placeholder="Search Lab Test" value="{{ Session::get('search_from_lab') }}" name="lab_search" autocomplete="off" />
					<button type="button" class="btn btn-default search_close_lab" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
					<div class="dd-wrapper labSearchByInput" style="display:none;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="searchLabModalDoctor" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="container">
		<div class="location-top">
			<div class="location-top1">
			<h2>Search Lab</h2>
			<button data-dismiss="modal" type="button" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
		</div>
		<div class="navbaar-bottom-section">
		<div class="navbaar-bottom-box2">
			<div class="navbaar-bottom-box">
				<input type="search" class="labSearching" placeholder="Search Lab Test" value="{{ Session::get('search_from_search_bar') }}" name="lab_search" autocomplete="off" />
				<button type="button" class="btn btn-default search_close_lab" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
				<div class="dd-wrapper labSearchByInput" style="display:none;"></div>
			</div>
		</div>
		</div>
	</div>
</div>
@else
{!! Form::open(array('route' => 'doctorInfo', 'id' => 'searchDocInfo', 'method' => 'POST')) !!}
	<div class="navbaar-bottom desktop-search">
		<input name="info_type" type="hidden" value="@if(isset($_GET['info_type'])){{base64_decode($_GET['info_type'])}}@else{{@Session::get('info_type')}}@endif"/>
		<input name="id" type="hidden" value="@if(isset($_GET['id'])){{base64_decode($_GET['id'])}}@else{{@Session::get('search_id')}}@endif"/>
		<input name="grp_id" type="hidden" value="@if(isset($_GET['grp_id'])){{base64_decode($_GET['grp_id'])}}@else{{@Session::get('grp_id')}}@endif" />
		<input name="lat" type="hidden" value='{{@Session::get("session_lat")}}'/>
		<input name="lng" type="hidden" value='{{@Session::get("session_lng")}}'/>
		<input name="state_id" type="hidden" value='{{ Session::get("state_id") }}'/>
		<input name="city_id" type="hidden" value='{{ Session::get("city_id") }}'/>
		<input name="locality_id" type="hidden" value='{{ Session::get("locality_id") }}'/>

		<input name="state_name" type="hidden" class="locality_state_area" value='{{ Session::get("search_from_state_name") }}'/>
		<input name="city_name" type="hidden" class="locality_city_area" value='{{ Session::get("search_from_city_name") }}'/>
		<input name="city_slug" type="hidden" class="locality_city_slug" value='{{ Session::get("search_from_city_slug") }}'/>
		<input name="locality_slug" type="hidden" class="locality_slug" value='{{ Session::get("locality_slug") }}'/>
		<div class="container">
			<div class="navbaar-bottom-section is_website" @if(isset($_COOKIE['in_mobile']) && $_COOKIE['in_mobile'] == '0') style="display: block;" @else style="display: none;" @endif>
			<div class="navbaar-bottom-block local-area-search">
				<i class="fa fa-map-marker" aria-hidden="true"></i>
				<input id="pac-input" class="pac-input" autocomplete="off" type="text" placeholder="city" name="locality" value='{{ Session::get("search_from_locality_name") }}'/>
				<div class="location-div-detect">
				<button type="button" class="btn btn-default search_close_locality" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
				<span class="location-div"><span data-qa-id="current_location" class="btn-detect detect_location"><img width="15" height="13" src="{{ URL::asset('img/loc-detect.png') }}" alt="location-detect-icon" /><i class="icon-ic_gps_system"></i><span>Detect</span></span></span></div>
				<div class="dd-wrapper localAreaSearchList" style="display:none;"></div>
			</div>
			<div class="navbaar-bottom-box2">
				<div class="navbaar-bottom-box">
					<input type="search" class="docSearching" placeholder="Search by Name and Specialities" value="{{ Session::get('search_from_search_bar') }}" name="data_search" autocomplete="off" />
					<button type="button" class="btn btn-default search_close" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
					<div class="dd-wrapper doctorSearchByInput" style="display:none;"></div>
				</div>
			</div>
			</div>
		</div>
	</div>
{!! Form::close() !!}
@endif
</header>