@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
	<div class="searching-keyword">
		<div class="container">
			<h1>SEARCH RESULTS FOR: <strong>"{{ Session::get('search_from_search_bar') }}"</strong></h1>
			<div class="searhc-result"> @if($infoData) {{$infoData->total()}} @endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
		</div>
    </div>
	<div class="container">
		  <div class="container-inner">
				<div class="filer-bar">
			 		<div class="breadcrume">
							<ul>
							  <li><a href="#">Home</a> /</li>
							  <li><a href="{{route('index')}}">{{ Session::get('search_from_city_name') }}</a>/</li>
							  <li><a href="javascript:window.history.back();">{{ Session::get('search_from_search_bar') }}</a></li>
							</ul>
					 </div>
					<div class="sorting">
                                         
					<!--<label>Showing all 4 results</label>-->
					<!--<div class="select">
					 <select>
						<option>SORT BY LATEST</option>
					 </select>
					</div>-->
				   </div>
			</div> 
			<div class="Symptoms_section">
			@if(count($infoData) > 0)
				<div class="doctor_heading">
					<h2>Symptoms</h2>
				</div>
				@foreach($infoData as $dtta)
				<div class="listing">
					<div class="Symptoms_block">
						<a href="javascript:void(0);" class="view_information" search_type="1" data_id="{{$dtta->id}}" info_type="symptoms"><h2 class="text">{{@$dtta->symptom}}</h2></a>
						<p>{!!@$dtta->description!!}</p>
						@if(count($dtta->SymptomTags) > 0)
						<h4>Symptoms synonyms</h4>
						<p>
							@foreach($dtta->SymptomTags as $i => $tag)
								@if(!empty($tag))
									<span>{{$i+1}}. {{@$tag->text}}</span></br>
								@endif
							@endforeach		
						</p>
						@endif
					</div>
				</div>
			 @endforeach
			@else
			<div class="right-content no-result-found">
				
				<img src="img/search-result.png" alt="icon"  />
				<h2><strong>We're Sorry!</strong><br /> We couldn't find what you were looking for!</h2>
				<p>Go back to <a href="javascript:window.history.back();">home</a>.</p>
                <div class="doctorRegistrationDiv"><a href='{{route("addDoc")}}'>Doctor Registration</a></div>
                
			</div>
			@endif	
			<div class="pages-section">
			{{ $infoData->appends($_REQUEST)->links() }}
			</div>
			</div>
	</div>
	</div>
	<div class="container-fluid">
		<div class="container"> </div>
    </div>
@endsection