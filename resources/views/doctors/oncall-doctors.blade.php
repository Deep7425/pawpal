@extends('layouts.Masters.Master')
@section('title', 'Health Gennie | Tele Consultation Doctors Book Doctor Appointments')
@section('description', 'Find the right Tele Consultation Doctors with Health Gennie. Read about health issues and get solutions.')
@section('content')
<div class="container listing-right-wrapper live_doctors">
  <div class="container-inner">
    <div class="filer-bar">
      <div class="breadcrume">
        <ul>
          <li><a href="{{route('index')}}">Home</a> /</li>
		   <li class="breadcrume-li-tag">
			<h1 title="Tele Consultation Doctors">Tele Consultation Doctors</h1>
		  </li>
        </ul>
      </div>
		<div class="sorting sorting_fillterTop">
			{!! Form::open(array('route' => 'liveDoctors', 'method'=>'POST')) !!}
			<div class="form-group">
				<div class="teleConsolDocsearch">
					<input type="search" class="TeleDocSearching" placeholder="Search Tele-Consultation Doctors..." 
					value="@if(app('request')->input('search') != "") {{base64_decode(app('request')->input('search'))}} @endif" name="search" autocomplete="off" style="">
				</div>
				<select class="form-control selectSpecialityDiv" name="speciality">
					<option value="">Select Speciality</option>
					@foreach(getSpecialityList() as $index => $speciality)
						<option @if(app('request')->input('speciality') != "" && base64_decode(app('request')->input('speciality')) == $speciality->id) selected @endif value="{{$speciality->id}}">{{$speciality->specialities}}</option>
					@endforeach
				</select>
				<span class="input-group-btn">
				  <button class="btn btn-primary" type="submit">
					  <span class="fa-fa-search"> Search </span>
				  </button>
				</span>
			</div>
			{!! Form::close() !!}
        </div>
    </div>
    <div class="right-content">
      @if(isset($infoData) && count($infoData) > 0)
      @foreach($infoData as $doc)
      <div class="listing"> @if(count($doc->DoctorRatingReviews)>0)
        <?php
					$rating_val = 0;
					$rating_count = 0;
					foreach($doc->DoctorRatingReviews as $rating) {
						$rating_val += $rating->rating;
						$rating_count++;
					}
					if($rating_val > 0){
						$rating_val = round($rating_val/$rating_count,1);
					}
						$rating_div = "";
						$total_rate = 0;
					for($x=1;$x<=$rating_val;$x++) {
						$rating_div .=  '<span class="doc-star-rating fa fa-star checked"></span>';
					}
					if (strpos($rating_val,'.')) {
						$rating_div .= '<span class="doc-star-rating fa fa-star-half-full checked"></span>';
						$x++;
					}
					while ($x<=5) {
						$rating_div .= '<span class="doc-star-rating fa fa-star"></span>';
						$x++;
					}
					?>
        @endif
        <div class="doc-img"><img @if(@$doc->profile_pic != null) src="{{@$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  />

        </div>
        <div class="right-con-wrapper">
        <div class="profile-detail"> <a href='{{route("onCallDoctorDetalis",["id"=>base64_encode($doc->id)])}}'>
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3>
          </a>
		  @if(!empty($doc->clinic_name)) 
            <div class="clinic_name"><img src="{{ URL::asset('img/home-ico.png')}}" alt="icon"  />{{@$doc->clinic_name}} </div>
            @endif
          <ul class="dgree-top">
          	
            
            @if(!empty($doc->docSpeciality))<li><img src="<?php echo url("/")."/public/speciality-icon/".$doc->docSpeciality->speciality_icon; ?>" alt="icon" />{{@$doc->docSpeciality->spaciality}} </li>@endif	
            @if(!empty($doc->qualification))
            <li class="Dgree-section"><p><strong><img src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  /></strong><span>{{@$doc->qualification}}</span></p></li>
            @endif
          </ul>
          <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
          
        </div>
        <div class="doctor-address">
          <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
            <?php if(isset($rating_div)){ echo $rating_div; } ?>
          </div>
        <!--  <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
            @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
			<a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
			</div>-->
           <div class="timing"><span>
		  @if(!empty($doc->opd_timings) && count($doc->opd_timings)>0 && isset($doc->opd_timings["today"]) && count($doc->opd_timings["today"]))
				<span class="time-title">Today Timing</span> 
				@foreach($doc->opd_timings["today"] as $opd_t)
					({{$opd_t['start_time']}}-{{$opd_t['end_time']}})
				@endforeach
			@else
            Not Available For Today
            @endif </span> 
		  </div>
         
			<div class="fees">
				Consultation Fee: <strong>₹{{$doc->oncall_fee}}</strong>
			</div>
		  
        </div>
        <div class="list-bottom">
          <div class="cal-doctor"> <a onclick="showSlot({{$doc->id}});" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Book Appointment</a> </div>
          <div class="view-profile"><a href='{{route("onCallDoctorDetalis",["id"=>base64_encode($doc->id)])}}'>View Profile</a></div>
        </div>
        </div>

      </div>
      @endforeach
      @else
      <div class="right-content no-result-found suggestion-wrapper @if(count(getSuggestedDoctors())>0) suggested-width @endif"> <img src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
        
		<h2 style=" width:100%; text-align:center; float:left;">
			 <img src="{{ URL::asset('img/no-resukt.jpg')}}" alt="icon" style="display:block;"  />
		<strong>No Result Found </strong><br />
		
        </h2>
       </div>
      @endif
      @if(isset($infoData))
      <div class="pages-section">{{ $infoData->appends($_REQUEST)->links() }} </div>
      @endif </div>
  </div>
  <div class="container-fluid">
    <div class="container"></div>
  </div>
</div>
<script src="{{ URL::asset('js/form_multiselect.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap_multiselect.js') }}"></script>
<script>
		$(document).ready(function() {
			$('.selectSpecialityDiv').multiselect({
				includeSelectAllOption: true,
				enableFiltering: true,
				enableCaseInsensitiveFiltering: true,
			});
		});
		jQuery(document).on("click", ".show_doctor_info", function (e) {
			var data_info_id = $(this).attr('data_id');
			var doc_name = $(this).attr('info_name');
			jQuery('.loading-all').show();
			var city = "<?php if(Session::get('search_from_city_slug')){ echo Session::get('search_from_city_slug');}else { echo 'jaipur'; } ?>";
			var	url = '{{ route("onCallDoctorDetalis") }}';
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'doctor');
			url = url.replace(':name', doc_name);
			window.location = url;
		});
	</script>
@endsection
