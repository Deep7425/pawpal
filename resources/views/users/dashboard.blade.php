@extends('layouts.Masters.Master')
@section('title', 'Elite | Health Gennie')
@section('description', "Welcome to HEALTH GENNIE ELITE where we offer a complete healthcare plan for you & your family. Choose one that suits your needs and enjoy the benefits.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper container">
      @if(session()->get('message'))
	  <div class="alert alert-success">
		<strong>Success!</strong> {{ session()->get('message') }}
	  </div>
	  @endif
      <div class="HG_plan_Section">
        <div class="HG_plan_Block">
        </div>
	  </div>
     <div class="container"> <h2 class="hg-plan-title">Select Online Doctor Consultation Packages for Your Family</h2>
     <p class="page-breif">Show your care for family with general healthcare plan. In preventive care plan you will experience wide range of health care benefits. Our premium healthcare plan includes health care package for you and your family. Make plans with your family for affordable online doctor consultation packages to get doctor's advice according to your medical status from the comfort of your home.</p>
     
	 <div class="HG_plan">
        <!--<p>HEALTH GENNIE ELITE offers different plans. Choose one that suits your needs.</p>-->
		@if(count($plans) > 0)
		@foreach($plans as $i => $plan)
		<div class="hg-plans-wrapper">
				<div class="tab-header-first">
					<strong>Health Gennie Plus Benefits:</strong>
					<span style="text-align:left;">Experience continuous care with unlimited consultations</span>
				</div>    
				<div class="tab-header-second">
					<h3>{{$plan->plan_title}}</h3>
					<span class="">Covers 1 Adult</span>
				</div>    
		{!!$plan->content!!}
				<div align="center" class="price-plan">
					<h3><em>₹{{$plan->price}}</em> ₹{{$plan->price - $plan->discount_price}}</h3>
					<span class="save-amt">Save up to ₹{{$plan->discount_price}}</span>
				   <p> Billed every 365 days</p>
					<a href='{{route("choosePlan",["id" => base64_encode($plan->id)])}}'>Buy Now</a>
				</div>
		</div>
		@endforeach
		@endif
      </div>
      <div class="hg-club">
      	{!!getTermsBySLug('subscription-plan-page-content-bottomapp','en')!!}
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