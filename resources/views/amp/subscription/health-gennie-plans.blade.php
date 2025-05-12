@extends('amp.layouts.Masters.Master')
@section('title', 'Subscription health gennie')
@section('content') 
 <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Health Gennie</title>
<!-- Bootstrap -->
<link href="{{ URL::asset('subcription-asset/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('subcription-asset/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('subcription-asset/css/scrolling-nav.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
 
<div class="container-fluid banner">
	<!--<h3>Digitize Your<span> Practice </span> Today</h3>-->
	<img class="img-top12" src="{{ URL::asset('subcription-asset/img/practice-digitize.jpg') }}" />
</div>

<div class="container-fluid Patient_Portal wrapper-portal plan-wrapper">
<div class="container">
<div class="benifit-content">
	<img width="150" src="../img/suss-right.gif" />
	<h3>Thanks for creating your profile on <span>Health Gennie</span></h3>
		<div class="button-subscribe mobile"><a href='{{route("home")}}'>Home</a></div>
	</div> 
</div>
</div>
@endsection