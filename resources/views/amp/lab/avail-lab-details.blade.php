@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')
<link href="{{ URL::asset('css/jquery.notify.css') }}" rel="stylesheet" type="text/css"/>
<div class="lab-test Lab_Test_Details single-lab-detail">
  <div class="container-fluid avail-pkg">
		@if(!empty($item))
			<div class="container LabDetailsDiv">
			<h2>{{$item->name}}</h2>
			  <div class="package-box_lab">
				   <?php
					$groups = array();
					  foreach ($item->childs as $element) {
					  $groups[$element->group_name][] = $element;
					  }
					?>
				  @foreach($groups as $group => $tests)

				  <h4>{{$group}}</h4>
				  @foreach($tests as $child)
					<div class="package-box">
						<div class="lab-test-block-img">
							<img src="{{ URL::asset('img/lab2-icon.png') }}" />
						</div>
						<div class="lab-test-block">
					  <h3>{{$child->name}}</h3>
						</div>
					</div>
				  @endforeach
				@endforeach
			</div>
		  </div>
		@else
		<div class="container LabDetailsDiv not-found-details">
			<h1>Lab Is Not Available</h1>
		</div>
		@endif
  </div>
</div>
<script src='{{ URL::asset("js/jquery.notify.min.js") }}'></script>
<script>
</script>
@endsection
