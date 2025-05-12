@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content')
<style>
.ConsultationFilter [type="radio"]:checked + label, .waitingTime [type="radio"]:not(:checked) + label{
  float: inherit !important;
}
</style>
<div class="searching-keyword">
  <div class="container">
    <div class="searhc-result">@if(isset($infoData)){{$infoData->total()}}@endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
  </div>
</div>
<?php $dType = 'doctors';  $stype = 'doctor'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');} 
$cName = Session::get('search_from_city_name');
if(!empty(Session::get('search_from_locality_name'))){
	if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
		$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
	}
}
?>
<div class="container listing-right-wrapper">
<div class="topHeadingSecion">
	<div class="container-inner">
	<div class="topHeadingBlock">
	<h1>Best {{Session::get('search_text')}} In {{$cName}}
	<small>Select the right doctor from Health Gennie and get the best care you deserve.</small></h1>
	</div>
	</div>
</div>
  <div class="container-inner">
    <div class="filer-bar">
      <div class="breadcrume">
        <ul>
			<li><a href="{{route('index')}}">Health Gennie</a> ></li>
			@if(Session::get('search_from_search_bar') != "doctors")
				<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,$dType])}}">Doctors</a> ></li>
			@endif
			<li class="breadcrume-li-tag">{{Session::get('search_from_search_bar')}}</li>
			@php
			$tag = "";
			$slug = basename($_SERVER['REQUEST_URI']);
			if(Session::get('info_type')) {
				if(Session::get('locality_id')) {
					$uri =  $_SERVER['REQUEST_URI'];
					if($uri){
						$urlX = explode("/",$uri);
						$slug = @$urlX[2];
					}
				}
				$tag = getH1TagBySlug(Session::get('info_type'),$slug);
			}
			
			if(empty($tag)) {
				$tag = Session::get('search_from_search_bar');
				if(Session::get('search_from_locality_name') && Session::get('search_from_city_name') && Session::get('locality_id')) {
					$tag = $tag." In ".Session::get('search_from_locality_name')." ".Session::get('search_from_city_name');
				}
				else if(Session::get('search_from_city_name')) {
					$tag = $tag." In ".Session::get('search_from_city_name');
				}
			}
			@endphp
        </ul>
      </div>
      <div class="sorting">
		@if($_COOKIE["in_mobile"] != '1')
        <label class="resultFoundLabel">@if(isset($infoData) && $infoData->total() != '0') {{$infoData->total()}} results found @else no result found @endif </label>
        @endif
      </div>
    </div>
    <?php $filteredLocality = ""; $filteredGenderKey = 0; $filteredGender = "";
			  if(isset($_GET['filter_by_locality_put'])) {
				$filteredLocality = json_decode($_GET['filter_by_locality_put'],true);
			  }
			  if(isset($_GET['filter_by_gender_put'])) {
				$filteredGender = json_decode(base64_decode($_GET['filter_by_gender_put']),true);
				if($filteredGender['Male'] == 1 && $filteredGender['Female'] == 1){
					$filteredGenderKey = 1;
				}
			  }
        $selectedLocality = "";
         if(isset($_GET['locality'])) {
          $selectedLocality =  json_decode(@$_COOKIE['locality']);
        }
        $conType = "";
        if(isset($_GET['ctype'])) {
         $conType = base64_decode($_GET['ctype']);
       }
		?>
    <div class="left-content desktop filteredDivDesktop">
      <h2>Consult Type</h2>
      <div class="ConsultationFilter waitingTime">

        <p>
            <input type="radio" id="test1" value="all"class="filter_by_consult"  name="filter_by_consult"  @if(!empty($conType) && $conType == "all") checked @else checked @endif />
            <label for="test1">All</label>
        </p>
        <p>
            <input type="radio" id="test3" value="1" class="filter_by_consult" name="filter_by_consult"  @if(!empty($conType) && $conType == 1) checked @endif />
            <label for="test3">Tele-Consultation</label>
        </p>
        <p>
            <input type="radio" id="test2" value="2" class="filter_by_consult" name="filter_by_consult"  @if(!empty($conType) && $conType == 2) checked @endif />
            <label for="test2">In-Clinic Consultation</label>
        </p>

        <span class="help-block"></span>
      </div>
      <div class="block-filter">
        <h2>LOCALITY</h2>
        <div class="search-list">
          <input type="text" class="searchLocalityFromList" placeholder="Find Locality" />
        </div>
        <div class="content-wrapper">
          <label class="chck-container">All Places
            <input type="checkbox" class="filter_by_locality_all" @if(!empty($selectedLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($selectedLocality) )  checked check-type="1" @else check-type="0" @endif @endif/>
            <span class="checkmark"></span> </label>
          <label class="chck-container localty-not-found" style="display:none;">Not Found</label>
          @if(count(getLocalityByCityId(Session::get("city_id"))) > 0 )
          <?php $list_menu = ""; ?>
          @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
          <label class="chck-container">{{$locality->name}}<input type="checkbox" name="filter_by_locality" value="{{$locality->id}}" @if(!empty($selectedLocality) && in_array($locality->id,$selectedLocality)) checked @endif /><span class="checkmark"></span></label>
          @endforeach
          <div class="find-locality-div"> {!!$list_menu!!} </div>
          @endif </div>
      </div>
      <div class="block-filter">
        <h2>EXPERIENCE</h2>
        <div class="filter-wrap">
          <label class="chck-container">1-5 Years
            <input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '5') checked @endif @endif />
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">5-10 Years
            <input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '10') checked @endif @endif />
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">10-15 Years
            <input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '15') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">15-20 Years
            <input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '20') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">More than 20 Years
            <input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '1') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
      </div>
      <div class="block-filter">
        <h2>Consultation Fee</h2>
        <div class="price-range-block">
          <div id="slider-range" class="price-filter-range slider-range" name="rangeInput"></div>
          <div style="float:left; width:100%;">
            <input type="number" min=0 max="9900" oninput="validity.valid;" id="min_price" class="price-range-field min_price" value="@if(isset($_GET['fmin'])){{base64_decode($_GET['fmin'])}}@endif" />
            <input type="number" min=0 max="10000" oninput="validity.valid;" id="max_price" class="price-range-field max_price" value="@if(isset($_GET['fmax'])){{base64_decode($_GET['fmax'])}}@endif" />
          </div>
          <button class="price-range-search price-range-submit" id="price-range-submit">Search</button>
          <div id="searchResults" class="search-results-block searchResults"></div>
        </div>
      </div>
      <div class="block-filter">
      	<h2>Top Doctors By Location</h2>
      	<div class="filter-wrapConsultation">
      		<ul class="list-unstyled">
			 @foreach(getTopLocality(Session::get("city_id")) as $locality)
				<li><a href="{{route('findDoctorLocalityByType',[$cityName,$slug,$locality->slug])}}">Best Doctors in {{$locality->name}}</a></li>
              @endforeach
			</ul>
      	</div>
      	</div>
    </div>
    <div class="left-wrapper filteredDivMobile">
      <h2 class="accordion">Filters</h2>
      <div class="left-content" style="display: none;">
        <h2>Consult Type</h2>
        <div class="ConsultationFilter waitingTime">

          <p>
              <input type="radio" id="test4" value="all" class="filter_by_consult" name="filter_by_consult2"  @if(!empty($conType) && $conType == "all") checked @else checked @endif />
              <label for="test4">All</label>
          </p>
          <p>
              <input type="radio" id="test6" value="1" class="filter_by_consult" name="filter_by_consult2"  @if(!empty($conType) && $conType == 1) checked @endif />
              <label for="test6">Tele-Consultation</label>
          </p>
          <p>
              <input type="radio" id="test5" value="2" class="filter_by_consult" name="filter_by_consult2"  @if(!empty($conType) && $conType == 2) checked @endif />
              <label for="test5">In-Clinic Consultation</label>
          </p>

          <span class="help-block"></span>
        </div>
        <div class="panel">
          <div class="block-filter">
            <h3>LOCALITY</h3>
            <div class="search-list">
              <input type="text" class="searchLocalityFromList" placeholder="Find Locality" />
            </div>
            <div class="content-wrapper">
              <label class="chck-container">All Places
                <input type="checkbox" class="filter_by_locality_all" @if(!empty($selectedLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($selectedLocality) ) checked @endif @endif/>
                <span class="checkmark"></span> </label>
              <label class="chck-container localty-not-found" style="display:none;">Not Found</label>
              @if(count(getLocalityByCityId(Session::get("city_id"))) > 0 )
              <?php $list_menu = ""; ?>
              @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
                <label class="chck-container">{{$locality->name}}<input type="checkbox" name="filter_by_locality" value="{{$locality->id}}" @if(!empty($selectedLocality) && in_array($locality->id,$selectedLocality)) checked @endif /><span class="checkmark"></span></label>
              @endforeach

              <div class="find-locality-div"> {!!$list_menu!!} </div>
              @endif </div>
          </div>
          <div class="block-filter">
            <h3>EXPERIENCE</h3>
            <div class="filter-wrap">
              <label class="chck-container">1-5 Years
                <input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '5') checked @endif @endif />
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">5-10 Years
                <input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '10') checked @endif @endif />
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">10-15 Years
                <input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '15') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">15-20 Years
                <input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '20') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">More than 20 Years
                <input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['dexp'])) @if(base64_decode($_GET['dexp']) == '1') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
          </div>
          <div class="block-filter">
            <h3>Consultation Fee</h3>
            <div class="price-range-block">
              <div id="slider-range" class="price-filter-range slider-range" name="rangeInput"></div>
              <div style="float:left; width:100%;">
                <input type="number" min=0 max="9900" oninput="validity.valid;" id="min_price" class="price-range-field min_price" value="@if(isset($_GET['fmin'])){{base64_decode($_GET['fmin'])}}@endif" />
                <input type="number" min=0 max="10000" oninput="validity.valid;" id="max_price" class="price-range-field max_price" value="@if(isset($_GET['fmax'])){{base64_decode($_GET['fmax'])}}@endif" />
              </div>
              <button class="price-range-search price-range-submit" id="price-range-submit">Search</button>
              <div id="searchResults" class="search-results-block searchResults"></div>
            </div>
          </div>
          <div class="block-filter">
      	<h2>Top Doctors By Location</h2>
      	<div class="filter-wrapConsultation">
      		<ul class="list-unstyled">
			 @foreach(getTopLocality(Session::get("city_id")) as $locality)
				<li><a href="{{route('findDoctorLocalityByType',[$cityName,$slug,$locality->slug])}}">Best Doctors in {{$locality->name}}</a></li>
              @endforeach
			</ul>
      	</div>
      	</div>
        </div>
      </div>
    </div>

    <div class="right-content @if(count(getSuggestedDoctors())>0) suggested-width @endif">
		@foreach(getSponsoredDoctor() as $sponsoredDoc)
			<div class="listing">
        @if(count($sponsoredDoc->DoctorRatingReviews)>0)
          <?php
  					$rating_val = 0;
  					$rating_count = 0;
  					foreach($sponsoredDoc->DoctorRatingReviews as $rating) {
  						$rating_val += $rating->rating;
  						$rating_count++;
  					}
  					if($rating_val > 0){
  						$rating_val = round($rating_val/$rating_count,1);
  					}
  						$rating_div = "";
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
          <div class="doc-img"><img loading="lazy" width="56" height="56" @if($sponsoredDoc->clinic_image != null) src="{{$sponsoredDoc->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}" @endif alt="{{$sponsoredDoc->clinic_name}}" />

          </div>
          <div class="right-con-wrapper">
          	<div class="profile-detail"><a data_id="{{$sponsoredDoc->practice_id}}"  slug="{{@$sponsoredDoc->DoctorSlug->clinic_name_slug}}" info_type="Clinic" search_type="1" class="view_information" href="javascript:void(0);">

            <h3>{{ucfirst($sponsoredDoc->clinic_name)}}</h3>
            </a>
            <ul>
              <li>{{$sponsoredDoc->docSpeciality->spaciality}}</li>
            </ul>
             <div class="sponsered">SPONSORED</div>

          </div>
          	<div class="doctor-address">
            <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
              <?php if(isset($rating_div)){ echo $rating_div; } ?>
            </div>
              <div class="timing"><span>
				@if(!empty($sponsoredDoc->opd_timings) && count($sponsoredDoc->opd_timings)>0 && isset($sponsoredDoc->opd_timings["today"]) && count($sponsoredDoc->opd_timings["today"]))
					<span class="time-title">Today Timing</span>
					@foreach($sponsoredDoc->opd_timings["today"] as $opd_t)
						({{$opd_t['start_time']}}-{{$opd_t['end_time']}})
					@endforeach
				@else
				Not Available For Today
				@endif </span>
			  </div>
			<div class="fees">
				<?php $consult_type = []; if(!empty($sponsoredDoc->oncall_status)){ $consult_type = explode(',',$sponsoredDoc->oncall_status); } ?>
				@if(checkCountry() && in_array(2,$consult_type))
				<div class="cons-fees">
				  @if(!empty($sponsoredDoc->consultation_fees)) In-clinic Fees(₹) : <strong>{{$sponsoredDoc->consultation_fees}}</strong>
				  @else Fee Not Updated @endif
				</div>
				@endif
				<div class="cons-fees">
					@if(checkCountry() && in_array(1,$consult_type) && !empty($sponsoredDoc->oncall_fee))
						Tele-consultation Fees(₹) : <strong>{{$sponsoredDoc->oncall_fee}}</strong>
					@endif
				</div>
			</div>
          </div>
          <div class="list-bottom sponsered-btn">
          <div class="cal-doctor"> <a class="btn call_now" href="javascript:void(0);"><img loading="lazy" width="12" height="12" src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Call Now</a> </div>
        </div>
        <div class="call_now_section" style="display:none">Contact Number : <span class="number">8302072136</span></div>
          </div>
        
        </div>
        @endforeach
		@if((isset($infoDataNotFound) && count($infoDataNotFound) <= 0) && count($infoData) > 0)
		  <div class="no-result-found suggestion-wrapper">
			<div class="content-wrapper-not-found">
			  <h2><strong>No search results found for "{{ Session::get('search_from_search_bar') }}"</strong></h2>
			</div>
		  </div>
			<div class="title-wrapper-suggestion">
				<h2>You May also check</h2>
				<p>Following results are displayed on the basis of your search.</p>
			</div>
		@endif
		@if(isset($infoData))
		@if(count($infoData) > 0)
			@foreach($infoData as $doc)
			<?php $consult_type = []; if(!empty($doc->oncall_status)){ $consult_type = explode(',',$doc->oncall_status); } ?>
			  @php
				if(!empty($doc->getCityName) && !empty($doc->getCityName->slug)){
					$cityName = $doc->getCityName->slug;
				}
			  @endphp
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
			<a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
			<div class="doc-img"><img loading="lazy" width="56" height="56" @if(@$doc->profile_pic != null) src="{{@$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  />
			</div>
			</a>
			<div class="right-con-wrapper">
			<div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
			  <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}} @if($doc->is_prime == '1') <img loading="lazy" title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3>
			  </a>
			  <ul class="dgree-top">
				@if(!empty($doc->docSpeciality))
				<li><img loading="lazy" width="13" height="13" src="<?php echo url("/")."/public/speciality-icon/".$doc->docSpeciality->speciality_icon; ?>" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
				@endif
				@if(!empty($doc->qualification))
				<li class="Dgree-section"><p><strong><img loading="lazy" width="13" height="10" src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  /></strong><span>{{@$doc->qualification}}</span></p></li>
				@endif
			  </ul>
			  <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
			  <div class="doc_available">
				@if($doc->available_now == 1)
				<p class="now">Available Today</p>
				@endif
			  </div>
			</div>
			<div class="doctor-address">
			  <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
				<?php if(isset($rating_div)){ echo $rating_div; } ?>
			  </div>
				@if(in_array(2,$consult_type))
					<div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
					@if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
					<a class="hideforPaytm" target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
					</div>
				@endif
			   <!--<div class="timing"><span>
			  @if(!empty($doc->opd_timings) && count($doc->opd_timings)>0 && isset($doc->opd_timings["today"]) && count($doc->opd_timings["today"]))
					<span class="time-title">Today Timing</span>
					@foreach($doc->opd_timings["today"] as $opd_t)
						({{$opd_t['start_time']}}-{{$opd_t['end_time']}})
					@endforeach
				@else
				Not Available For Today
				@endif </span>
			  </div>-->
			   <div class="fees">
					@if(checkCountry() && in_array(2,$consult_type))
					<div class="cons-fees">
					  @if(!empty($doc->consultation_fees)) In-clinic Fees(₹) : <strong>{{$doc->consultation_fees}}</strong>
					  @else Fee Not Updated @endif
					</div>
					@endif
					<div class="cons-fees">
						@if(checkCountry() && in_array(1,$consult_type) && !empty($doc->oncall_fee))
							Tele-consultation Fees(₹) : <strong>{{$doc->oncall_fee}}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="list-bottom">
				@if(checkCountry() && in_array(1,$consult_type) || in_array(2,$consult_type))
					@if(in_array(1,$consult_type))
						<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(1)}}');" class="btn" href="javascript:void(0);"><img loading="lazy" width="12" height="12" src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Tele Appointment</a> </div>
					@endif
					@if(in_array(2,$consult_type))
						<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(2)}}');" class="btn in-clinic-btn" href="javascript:void(0);">In-clinic Appointment</a> </div>
					@endif
				@endif
			</div>
			</div>

		  </div>
			@endforeach
		@else
		  <div class="right-content no-result-found suggestion-wrapper @if(count(getSuggestedDoctors())>0) suggested-width @endif"> <img loading="lazy" src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
			<h2><strong>No Result were found. Please try modifying your search term!</strong><br />
			</h2>
			<p style="display:none;">Browse other Doctor <a class="btn btn-success view_information" search_type="1" data_id="0" info_type="doctor_all" href="javascript:void(0);"><span class="text" style="display:none;">Doctor</span>Click Here</a></p>
			<div class="doctorRegistrationDiv"><a href='{{route("addDoc")}}'>Doctor Registration</a></div>
		  </div>

		  @if(count(getDoctorByOtherSpaciality())>0)
			<div class="title-wrapper-suggestion">
				<h2>Explore More</h2>
				<p>Following results are showing based on your search.</p>
			</div>
		  @foreach(getDoctorByOtherSpaciality() as $doc)
		  @php
			if(!empty($doc->getCityName) && !empty($doc->getCityName->slug)){
				$cityName = $doc->getCityName->slug;
			}
		  @endphp
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
		 <a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
        <div class="doc-img"><img loading="lazy" width="56" height="56" @if(@$doc->profile_pic != null) src="{{@$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  />

        </div>
		</a>
        <div class="right-con-wrapper">
        <div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}}  @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3>
          </a>
          <ul>
            @if(!empty($doc->docSpeciality))
            <li><img loading="lazy" width="13" height="13" src="<?php echo url("/")."/public/speciality-icon/".$doc->docSpeciality->speciality_icon; ?>" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
            @endif
            @if(!empty($doc->qualification))
            <li><img loading="lazy" width="13" height="10" src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  />{{@$doc->qualification}}</li>
            @endif
          </ul>
          <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
          <div class="doc_available">
            @if($doc->available_now == 1)
            <p class="now">Available Today</p>
            @endif
          </div>
        </div>
        <div class="doctor-address">
         <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
            <?php if(isset($rating_div)){ echo $rating_div; } ?>
          </div>
          <?php
   //        <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
   //          @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
			// <a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
			// </div>
      ?>
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
				<?php $consult_type = []; if(!empty($doc->oncall_status)){ $consult_type = explode(',',$doc->oncall_status); } ?>
				@if(checkCountry() && in_array(2,$consult_type))
				<div class="cons-fees">
				  @if(!empty($doc->consultation_fees)) In-clinic Fees(₹) : <strong>{{$doc->consultation_fees}}</strong>
				  @else Fee Not Updated @endif
				</div>
				@endif
				<div class="cons-fees">
					@if(checkCountry() && in_array(1,$consult_type) && !empty($doc->oncall_fee))
						Tele-consultation Fees(₹) : <strong>{{$doc->oncall_fee}}</strong>
					@endif
				</div>
			</div>
        </div>
		<div class="list-bottom">
			@if(checkCountry() && in_array(1,$consult_type) || in_array(2,$consult_type))
				@if(in_array(1,$consult_type))
					<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(1)}}');" class="btn" href="javascript:void(0);"><img loading="lazy" width="12" height="12" src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Tele Appointment</a> </div>
				@endif
				@if(in_array(2,$consult_type))
					<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(2)}}');" class="btn in-clinic-btn" href="javascript:void(0);">In-clinic Appointment</a> </div>
				@endif
			@endif
		</div>
        </div>
      </div>
			@endforeach
		@endif
		@endif
		@else
      <div class="right-content no-result-found suggestion-wrapper @if(count(getSuggestedDoctors())>0) suggested-width @endif"> <img loading="lazy" src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
        <h2><strong>No Result were found. Please try modifying your search term!</strong><br />
        </h2>
        <p style="display:none;">Browse other Doctor <a class="btn btn-success view_information" search_type="1" data_id="0" info_type="doctor_all" href="javascript:void(0);"><span class="text" style="display:none;">Doctor</span>Click Here</a></p>
        <div class="doctorRegistrationDiv"><a href='{{route("addDoc")}}'>Doctor Registration</a></div>
      </div>

      @if(count(getDoctorByOtherSpaciality())>0)
		  <div class="title-wrapper-suggestion">
			<h2>Explore More</h2>
			<p>Following results are showing based on your search.</p>
		  </div>
      @foreach(getDoctorByOtherSpaciality() as $doc)
	  @php
		if(!empty($doc->getCityName) && !empty($doc->getCityName->slug)){
			$cityName = $doc->getCityName->slug;
		}
	  @endphp
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
		 <a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
        <div class="doc-img"><img loading="lazy" width="56" height="56" @if(@$doc->profile_pic != null) src="{{@$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  />

        </div>
		</a>
        <div class="right-con-wrapper">
        <div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}" href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}} @if($doc->is_prime == '1') <img loading="lazy" title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/> @endif</h3>
          </a>
          <ul>
            @if(!empty($doc->docSpeciality))
            <li><img loading="lazy" width="13" height="13" src="<?php echo url("/")."/public/speciality-icon/".$doc->docSpeciality->speciality_icon; ?>" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
            @endif
            @if(!empty($doc->qualification))
            <li><img loading="lazy" width="13" height="10" src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  />{{@$doc->qualification}}</li>
            @endif
          </ul>
          <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
          <div class="doc_available">
            @if($doc->available_now == 1)
            <p class="now">Available Today</p>
            @endif
          </div>

        </div>
        <div class="doctor-address">
         <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
            <?php if(isset($rating_div)){ echo $rating_div; } ?>
          </div>
          <?php
   //        <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
   //          @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
			// <a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a></div>
      ?>
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
				<?php $consult_type = []; if(!empty($doc->oncall_status)){ $consult_type = explode(',',$doc->oncall_status); } ?>
				@if(checkCountry() && in_array(2,$consult_type))
				<div class="cons-fees">
				  @if(!empty($doc->consultation_fees)) In-clinic Fees(₹) : <strong>{{$doc->consultation_fees}}</strong>
				  @else Fee Not Updated @endif
				</div>
				@endif
				<div class="cons-fees">
					@if(checkCountry() && in_array(1,$consult_type) && !empty($doc->oncall_fee))
						Tele-consultation Fees(₹) : <strong>{{$doc->oncall_fee}}</strong>
					@endif
				</div>
			</div>
        </div>
		<div class="list-bottom">
			@if(checkCountry() && in_array(1,$consult_type) || in_array(2,$consult_type))
				@if(in_array(1,$consult_type))
					<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(1)}}');" class="btn" href="javascript:void(0);"><img loading="lazy" width="12" height="12" src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Tele Appointment</a> </div>
				@endif
				@if(in_array(2,$consult_type))
					<div class="cal-doctor"> <a onclick="showSlot({{$doc->id}}, '{{base64_encode(2)}}');" class="btn in-clinic-btn" href="javascript:void(0);">In-clinic Appointment</a> </div>
				@endif
			@endif
		</div>
        </div>
      
      </div>
      @endforeach
      @endif
      @endif
      @if(isset($infoData))
      <div class="pages-section"> {{ $infoData->appends($_REQUEST)->links() }} </div>
      @endif 
      </div>
      @if(!empty(Session::get('city_id')) && count(getSuggestedDoctors())>0)
    <div class="right-small">
      <div class="right-white-box">
        <h2>Suggested Doctors</h2>
        <h5>Popular Doctors in your area</h5>
        <ul>
          @foreach(getSuggestedDoctors() as $prime_doctor)
		  @php
			if(!empty($prime_doctor->getCityName) && !empty($prime_doctor->getCityName->slug)){
			$cityName = $prime_doctor->getCityName->slug;
			}
			@endphp
          <li>
            <a data_id="{{$prime_doctor->id}}" info_name="{{@$prime_doctor->DoctorSlug->name_slug}}" href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$prime_doctor->DoctorSlug->name_slug])}}" >
              <span class="doctor-img"><img loading="lazy" width="56" height="56" @if(@$prime_doctor->profile_pic != null) src="{{@$prime_doctor->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}" @endif alt="{{$prime_doctor->first_name.' '.$prime_doctor->last_name}}" /></span>
              <div class="list-detail">
                <h3>Dr.{{$prime_doctor->first_name.' '.$prime_doctor->last_name}}</h3>
                <p>{{$prime_doctor->docSpeciality->spaciality}}</p>

				<span class="dr-fee">
					<?php $consult_type = []; if(!empty($prime_doctor->oncall_status)){ $consult_type = explode(',',$prime_doctor->oncall_status); } ?>
					@if(checkCountry() && in_array(2,$consult_type))
					<div class="cons-fees">
					  @if(!empty($prime_doctor->consultation_fees)) In-clinic Fees(₹) : <strong>{{$prime_doctor->consultation_fees}}</strong>
					  @else Fee Not Updated @endif
					</div>
					@endif
					<div class="cons-fees">
						@if(checkCountry() && in_array(1,$consult_type) && !empty($prime_doctor->oncall_fee))
							Tele-consultation Fees(₹) : <strong>{{$prime_doctor->oncall_fee}}</strong>
						@endif
					</div>
				</span>

                @if(count($prime_doctor->DoctorRatingReviews)>0)
                  <?php
          					$rating_val = 0;
          					$rating_count = 0;
          					foreach($prime_doctor->DoctorRatingReviews as $rating) {
          						$rating_val += $rating->rating;
          						$rating_count++;
          					}
          					if($rating_val > 0){
          						$rating_val = round($rating_val/$rating_count,1);
          					}
          						$rating_div = "";
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
                  <div class="doc-img">
                    <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
                      <?php if(isset($rating_div)){ echo $rating_div; } ?>
                    </div>
                  </div>
              </div>
            </a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif
	
  </div>
  <div class="container-fluid">
    <div class="container"></div>
  </div>
</div>
<script>
jQuery(document).on("click", ".show_doctor_info", function (e) {
	var data_info_id = $(this).attr('data_id');
	var doc_name = $(this).attr('info_name');
	jQuery('.loading-all').show();
	var city = "<?php if(Session::get('search_from_city_slug')){ echo Session::get('search_from_city_slug');}else { echo 'jaipur'; } ?>";
	var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}';
	url = url.replace(':city', city);
	url = url.replace(':doctor', 'doctor');
	url = url.replace(':name', doc_name);
	window.location = url;
});
</script>
@endsection