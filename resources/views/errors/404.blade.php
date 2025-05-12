@extends('layouts.Masters.Master')
@section('title', 'Page Not Found | Health Gennie')
@section('code', '404')
@section('content')
<div class="container listing-right-wrapper box-errors-404">
<?php $stype = 'clinic'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');} ?>
	<div class="block-errors-404">
	<div class="left-errors-404">
		<img width="300" src="{{ URL::asset('img/404-errors.webp')}}"/>
		<h2>Sorry, we couldn't find the page you are looking for</h2>
		<a href="{{route('index')}}" ><button type="button" class="btn btn-primary">Go Home</button></a>
	</div>	
	<div class="bottom-errors-404">
		<div class="container" style="display:none;">
		<div class="bottom-errors-content">
			<h2>Top specialities Doctors</h2>
			<ul>
				@if(count(getTopDocSpeciality()) > 0)
					@foreach(getTopDocSpeciality() as $spe)
					<li><a href="{{route('findDoctorLocalityByType',[$cityName,@$spe->slug])}}" title="{{$spe->spaciality}} in {{$cityName}}" class="view_information" slug="{{$spe->slug}}" info_type="Speciality">{{$spe->spaciality}} in {{$cityName}}</a></li>
					@endforeach
				@endif
			</ul>
		</div>
		<div class="bottom-errors-content">
			<h2>Benefits Health Gennie Doctors</h2>
			<ul>
				@php if(Session::get('city_id')){ $city = Session::get('city_id'); } else { $city = 3378;} @endphp
				@if(count(getPrimeDoctorsByCity($city)) > 0)
					@foreach(getPrimeDoctorsByCity($city) as $doc)
					<li>
						<a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}" ><img width="15px" title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/> {{$doc->clinic_name}} @if(!empty($doc->docSpeciality)) ({{@$doc->docSpeciality->spaciality}} ,{{@$doc->qualification}})@endif
						</a>
					</li>
					@endforeach
				@endif
			</ul>
		</div>
		<div class="bottom-errors-content">
			<h2>Read Articles</h2>
			<ul>
				<div class="artical-btn txt-center">
                    <a href="{{route('blogList')}}" >Blogs</a>
                </div>
			</ul>
		</div>
	</div>
	</div>
	</div>
	
</div>
@endsection