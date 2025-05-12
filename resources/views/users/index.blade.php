@extends('layouts.Masters.Master')
@section('title', 'Elite | Health Gennie')
@section('description', "Welcome to HEALTH GENNIE ELITE where we offer a complete healthcare plan for you & your family. Choose one that suits your needs and enjoy the benefits.")
@section('content')
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')

<div class="dashboard-right dashboard-wrapper-new main-index">
@if(session()->get('message'))
  <div class="alert alert-success">
	<strong>Success!</strong> {{ session()->get('message') }}
  </div>
@endif
<div class="HG_plan_Section">
	<!--<div class="HG_plan_Block">
		<img src="img/HG-club-health-gennie-banner.jpg" />
	</div>-->
</div>
<div class="container">
	<div class="HG_plan">
    
<h2>Select a Health Gennie Plus healthcare plan</h2>
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