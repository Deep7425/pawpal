@extends('amp.layouts.Masters.Master')
@section('title', 'Conditions | Health Gennie')
@section('description', "This is the official Terms of Use for Health Gennie website, by accessing our website you agree to be bound by the terms and conditions of this user agreement.")
@section('content') 

	<div class="container">
        <div class="container-inner">
        	<div class="company-policy">
          {!!$page->description !!}
          </div>
        </div>
	</div>
    
	<div class="container-fluid">
		<div class="container"> </div>
    </div>
@endsection