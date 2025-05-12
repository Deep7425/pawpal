@extends('layouts.Masters.Master')
@section('title', 'Limited Time Offers on Full Body Checkup')
@section('description', "Keeping you on track with your health with an online full body checkup to detect any illness. Take full body checkup offers with the best health services.")
@section('content')
<meta name="keywords" content="complete health checkup, full health check up packages, best full body checkup in jaipur, online full body checkup in India, complete body checkup test, full body check up package near me, complete body checkup package in India, full body checkup offers, cheapest full body checkup, complete body check up near me, full health checkup packages, full body checkup online booking, best full body health check up, basic full body checkup, cheap full body check up, full body checkup discount, book full body health checkup, best full body check up package, complete body check up online, best health packs available"/>
<div class="HG_plan_Section land">
	<div class="HG_plan_Block HG_plan_Block_Desktop">
		<img src="images/special-offer-lab.jpg" alt="Health Gennie Club Banner" />
	</div>
	<div class="HG_plan_Block HG_plan_Block_Mobile">
		<img src="images/special-offer-lab-1.jpg" alt="Health Gennie Club Banner" />
	</div>
  </div>
<div class="container unlimited-plan">
<div class="row">
	<div class="col-md-12">
		<div class="dashboard-wrapper dashboard-plan-wrapper ">
  @if(session()->get('message'))
  <div class="alert alert-success">
	<strong>Success!</strong> {{ session()->get('message') }}
  </div>
  @endif
	 <div class="HG_plan">
      	<h2>Limited Time on Exquisite <span>Offers</span></h2>
        <p>Pay now with anytime access to full body checkup offers. Presenting our health care packages with limited offers to help you lead a healthy life. Health packages consists of full body checkups, advanced full body checkup, and complete full body check-up with various health benefits.</p>
        <p class="nabl-iso">NABL & ISO Approved Lab</p>
        <div class="hg-plan-wrapper">
			@if(count($plans) > 0)
				@foreach($plans as $i => $plan)
				<div class="healthcare_plan plan-section{{$i+1}}">
					<div class="title-bg">
						<h2>{!!$plan->title_head!!}</h2>
						<div class="actual-price-wrapper">@if($plan->discount_price != "" && $plan->discount_price != 0)<strike><strong>₹{{$plan->price}}</strong></strike>@endif <strong>₹{{$plan->price - $plan->discount_price}}</strong></div>
					</div>
					<!--<h3></h3>-->
					<div class="plan-content">{!!$plan->content!!}</div>
					<a class="btn" href='{{route("planDetails",["id" => base64_encode($plan->id)])}}'>Buy Plan</a>
				</div>
				@endforeach
			@endif
        </div>
      </div>
      <div class="hg-club">
      	<div class="hg-club">
<h3><span>Health</span> Gennie Benefits</h3>
<p>We made health easy, accessible, and affordable across India with smart solutions for healthy living on a budget. We are online for you with the best health care services that will make your health journey simple and secure. We offer one-stop solutions for all your health problems in one place.</p>

<div class="HG-details">
<div class="details-blog"><img alt="Save Money"  src="https://www.healthgennie.com/images/save-money-new.png" width="124" />
<h2>Save Money</h2>

<p>Less than Rs 35/- per Dr consultation</p>
</div>

<div class="details-blog"><img alt="Save Time"  src="https://www.healthgennie.com/images/save-time-new.png" width="124" />
<h2>Save Time</h2>

<p>No more waiting time</p>
</div>

<div class="details-blog"><img alt="Experienced Doctors"  src="https://www.healthgennie.com/images/doctor-ico-new.png" width="124" />
<h2>Experienced Doctors</h2>

<p>All super specialist doctors available</p>
</div>

<div class="details-blog">
<p><img alt="Access Anywhere"  src="https://www.healthgennie.com/images/access-anywhere-new.png" width="124"/></p>

<h2>Access Anywhere</h2>

<p>Get the help whenever you need it</p>
</div>
</div>
</div>

      </div>	
</div>
        </div>
    </div>
</div>

<script>
function myFunction(current) {
	$('.toggle-wrapper').removeClass("chooseEle");
	$(current).closest('.toggle-wrapper').addClass("chooseEle");
	$('.toggle-wrapper').each(function(){
		if(!$(this).hasClass('chooseEle')) {
			$(this).find('.toggle-wrapper-content').slideUp();
		}
	});
	$(current).closest('.toggle-wrapper').find('.toggle-wrapper-content').slideToggle();
}
</script>
@endsection