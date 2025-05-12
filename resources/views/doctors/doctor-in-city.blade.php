@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')

@section('content')
<div class="searching-keyword">
  <div class="container">
    <h1>SEARCH RESULTS FOR: <strong>"{{ Session::get('search_from_search_bar') }}"</strong></h1>
    <div class="searhc-result">@if(isset($infoData)){{$infoData->total()}}@endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
  </div>
</div>
<div class="container listing-right-wrapper">
  <div class="container-inner">
    <div class="filer-bar">
      <div class="breadcrume">
        <ul>
          <li><a href="{{route('index')}}">Home</a> /</li>
          <li><a href="{{route('index')}}">{{ Session::get('search_from_city_name') }}</a> /</li>
          <li>Search Results for <strong>“{{ Session::get('search_from_search_bar') }}”</strong></li>
        </ul>
      </div>
      <div class="sorting">
        <label>Showing all @if(isset($infoData)) {{$infoData->total()}} @endif results</label>
        <div class="select" style="display:none;">
          <select class="DataFilterBySorting" search_type="doctors">
            <option value="">SORT BY</option>
            <option  value="name_asc" @if(isset($_GET['sort_by'])) @if(base64_decode($_GET['sort_by']) == 'name_asc') selected @endif @endif>
            Name In Ascending Order
            </option>
            <option value="name_dsc" @if(isset($_GET['sort_by'])) @if(base64_decode($_GET['sort_by']) == 'name_dsc') selected @endif @endif>
            Name In Descending Order
            </option>
          </select>
        </div>
      </div>
    </div>
    <?php $filteredLocality = ""; $filteredGenderKey = 0; $filteredGender = "";
			  if(isset($_GET['filter_by_locality_put'])) {
				$filteredLocality = json_decode($_GET['filter_by_locality_put'],true);
			  }
			 // pr($filteredLocality);
			  if(isset($_GET['filter_by_gender_put'])) {
				$filteredGender = json_decode(base64_decode($_GET['filter_by_gender_put']),true);
				if($filteredGender['Male'] == 1 && $filteredGender['Female'] == 1){
					$filteredGenderKey = 1;
				}
			  }
		?>
    <div class="left-content desktop filteredDivDesktop">
      <div class="block-filter">
        <h2>LOCALITY</h2>
        <div class="search-list">
          <input type="text" class="searchLocalityFromList" placeholder="Find Locality" />
        </div>
        <div class="content-wrapper">
          <label class="chck-container">All Places
            <input type="checkbox" class="filter_by_locality_all" @if(!empty($filteredLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($filteredLocality) )  checked check-type="1" @else check-type="0" @endif @endif/>
            <span class="checkmark"></span> </label>
          <label class="chck-container localty-not-found" style="display:none;">Not Found</label>
          @if(count(getLocalityByCityId(Session::get("city_id"))) > 0 )
          <?php $list_menu = ""; ?>
          @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
          <?php
							if(!empty($filteredLocality) && in_array($locality->id,$filteredLocality)) {
								$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" checked /><span class="checkmark"></span></label>';
							}
							?>
          @endforeach
          @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
          <?php
						if(!empty($filteredLocality)) {
							if(in_array($locality->id,$filteredLocality) == false){
								$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" /><span class="checkmark"></span></label>';
							}
						}
						else{
							$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" /><span class="checkmark"></span></label>';
						}
					?>
          @endforeach
          <div class="find-locality-div"> {!!$list_menu!!} </div>
          @endif </div>
      </div>
      <div class="block-filter">
        <h2>EXPERIENCE</h2>
        <div class="filter-wrap">
          <label class="chck-container">1-5 Years
            <input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '5') checked @endif @endif />
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">5-10 Years
            <input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '10') checked @endif @endif />
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">10-15 Years
            <input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '15') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">15-20 Years
            <input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '20') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
        <div class="filter-wrap">
          <label class="chck-container">More than 20 Years
            <input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '1') checked @endif @endif/>
            <span class="checkmark"></span> </label>
        </div>
      </div>
      <div class="block-filter">
        <h2>Consultation Fees</h2>
        <div class="price-range-block">
          <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
          <div style="float:left; width:100%;">
            <input type="number" min=0 max="9900" oninput="validity.valid;" id="min_price" class="price-range-field" value="@if(isset($_GET['consult_fee_min'])){{base64_decode($_GET['consult_fee_min'])}}@endif" />
            <input type="number" min=0 max="10000" oninput="validity.valid;" id="max_price" class="price-range-field" value="@if(isset($_GET['consult_fee_max'])){{base64_decode($_GET['consult_fee_max'])}}@endif" />
          </div>
          <button class="price-range-search" id="price-range-submit">Search</button>
          <div id="searchResults" class="search-results-block"></div>
        </div>
      </div>
    </div>
    <div class="left-wrapper filteredDivMobile">
      <h2 class="accordion">Filters</h2>
      <div class="left-content" style="display: none;">
        <div class="panel">
          <div class="block-filter">
            <h3>LOCALITY</h3>
            <div class="search-list">
              <input type="text" class="searchLocalityFromList" placeholder="Find Locality" />
            </div>
            <div class="content-wrapper">
              <label class="chck-container">All Places
                <input type="checkbox" class="filter_by_locality_all" @if(!empty($filteredLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($filteredLocality) ) checked @endif @endif/>
                <span class="checkmark"></span> </label>
              <label class="chck-container localty-not-found" style="display:none;">Not Found</label>
              @if(count(getLocalityByCityId(Session::get("city_id"))) > 0 )
              <?php $list_menu = ""; ?>
              @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
              <?php
							if(!empty($filteredLocality) && in_array($locality->id,$filteredLocality)) {
								$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" checked /><span class="checkmark"></span></label>';
							}
							?>
              @endforeach
              @foreach(getLocalityByCityId(Session::get("city_id")) as $locality)
              <?php
						if(!empty($filteredLocality)) {
							if(in_array($locality->id,$filteredLocality) == false){
								$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" /><span class="checkmark"></span></label>';
							}
						}
						else{
							$list_menu .= '<label class="chck-container">'.$locality->name.'<input type="checkbox" name="filter_by_locality" value="'.$locality->id.'" /><span class="checkmark"></span></label>';
						}
					?>
              @endforeach
              <div class="find-locality-div"> {!!$list_menu!!} </div>
              @endif </div>
          </div>
          <div class="block-filter">
            <h3>EXPERIENCE</h3>
            <div class="filter-wrap">
              <label class="chck-container">1-5 Years
                <input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '5') checked @endif @endif />
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">5-10 Years
                <input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '10') checked @endif @endif />
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">10-15 Years
                <input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '15') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">15-20 Years
                <input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '20') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
            <div class="filter-wrap">
              <label class="chck-container">More than 20 Years
                <input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '1') checked @endif @endif/>
                <span class="checkmark"></span> </label>
            </div>
          </div>
          <div class="block-filter">
            <h3>Consultation Fees</h3>
            <div class="price-range-block">
              <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
              <div style="float:left; width:100%;">
                <input type="number" min=0 max="9900" oninput="validity.valid;" id="min_price" class="price-range-field" value="@if(isset($_GET['consult_fee_min'])){{base64_decode($_GET['consult_fee_min'])}}@endif" />
                <input type="number" min=0 max="10000" oninput="validity.valid;" id="max_price" class="price-range-field" value="@if(isset($_GET['consult_fee_max'])){{base64_decode($_GET['consult_fee_max'])}}@endif" />
              </div>
              <button class="price-range-search" id="price-range-submit">Search</button>
              <div id="searchResults" class="search-results-block"></div>
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
          <div class="doc-img"><img @if($sponsoredDoc->clinic_image != null) src="{{$sponsoredDoc->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}" @endif alt="{{$sponsoredDoc->clinic_name}}" />

          </div>
          <div class="right-con-wrapper">
          	<div class="profile-detail"><a data_id="{{$sponsoredDoc->practice_id}}" slug="{{@$sponsoredDoc->DoctorSlug->clinic_name_slug}}" info_type="Clinic" search_type="1" class="view_information" href="javascript:void(0);">

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
            <div class="location">{{$sponsoredDoc->address_1}}, @if(!empty($sponsoredDoc->locality_id)){{$sponsoredDoc->locality_id['name']}},@endif
              @if(!empty($sponsoredDoc->getCityName)) {{$sponsoredDoc->getCityName->name}} @endif
			  <a target="_blank" @if(!empty($sponsoredDoc->address_1))  href="https://maps.google.com/maps?q={{$sponsoredDoc->address_1}} {{@$sponsoredDoc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
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
				@if(!empty($sponsoredDoc->consultation_discount) && $sponsoredDoc->consultation_discount != '0') Consultation Fees: <strong>₹{{$sponsoredDoc->consultation_discount}}</strong>
				<strike style="text-decoration:line-through;">₹{{$sponsoredDoc->consultation_fees}}</strike>
				@elseif($sponsoredDoc->consultation_discount == '0')<strong> Consultation Fees: Free</strong>
				<strike style="text-decoration:line-through;">₹{{$sponsoredDoc->consultation_fees}}</strike>
				@elseif(!empty($sponsoredDoc->consultation_fees))<strong>Consultation Fees: ₹{{$sponsoredDoc->consultation_fees}}</strong> 
				@else Fee Not Updated @endif
			</div>
          </div>
          
          	
          </div>
          
          <div class="list-bottom sponsered-btn">
          	
            <div class="cal-doctor"> <a class="btn call_now" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Call Now</a> </div>
          </div>
          <div class="call_now_section" style="display:none">Contact Number : <span class="number">8302072136</span></div>
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
        <div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  class="show_doctor_info" href="javascript:void(0);">
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3>
          </a>
          <ul class="dgree-top">
            @if(!empty($doc->docSpeciality))
            <li><img src="{{ URL::asset('img/doctor-ico.png')}}" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
            @endif
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
          <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
            @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
			<a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
			</div>
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
				@if(!empty($doc->consultation_discount) && $doc->consultation_discount != '0') Consultation Fees: <strong>₹{{$doc->consultation_discount}}</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif($doc->consultation_discount == '0')<strong>Consultation Fees: Free</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif(!empty($doc->consultation_fees))<strong>Consultation Fees: ₹{{$doc->consultation_fees}}</strong> 
				@else Fee Not Updated @endif
			</div>
		  
        </div>
        <div class="list-bottom">
          <div class="cal-doctor"> <a onclick="showSlot({{$doc->id}});" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Book Appointment</a> </div>
          <div class="view-profile"><a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  class="show_doctor_info" href="javascript:void(0);">View Profile</a></div>
        </div>
        </div>

      </div>
      @endforeach
      @else
      <div class="right-content no-result-found suggestion-wrapper @if(count(getSuggestedDoctors())>0) suggested-width @endif"> <img src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
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

						// for($i = 1; $i <= 5; $i++) {
						// 	if($i <= $rating_val){
						// 		$rating_div .= '<span class="doc-star-rating fa fa-star checked"></span>';
						// 		$total_rate++;
						// 	}
						// 	else{
						// 		$rating_div .= '<span class="doc-star-rating fa fa-star"></span>';
						// 	}
						// }
					?>
        @endif
        <div class="doc-img"><img @if(@$doc->profile_pic != null) src="{{@$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  />
          
        </div>
        <div class="right-con-wrapper">
        <div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  class="show_doctor_info" href="javascript:void(0);">
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}}  @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3>
          </a>
          <ul>
            @if(!empty($doc->docSpeciality))
            <li><img src="{{ URL::asset('img/doctor-ico.png')}}" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
            @endif
            @if(!empty($doc->qualification))
            <li><img src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  />{{@$doc->qualification}}</li>
            @endif
          </ul>
          <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
          
        </div>
        <div class="doctor-address">
         <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
            <?php if(isset($rating_div)){ echo $rating_div; } ?>
          </div>
          <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
            @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif 
			<a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a>
			</div>
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
				@if(!empty($doc->consultation_discount) && $doc->consultation_discount != '0') Consultation Fees: <strong>₹{{$doc->consultation_discount}}</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif($doc->consultation_discount == '0')<strong>Consultation Fees: Free</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif(!empty($doc->consultation_fees))<strong>Consultation Fees: ₹{{$doc->consultation_fees}}</strong> 
				@else Fee Not Updated @endif
			</div>
        </div>
		<div class="list-bottom">
          <div class="cal-doctor"> <a onclick="showSlot({{$doc->id}});" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Book Appointment</a> </div>
          <div class="view-profile"><a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  class="show_doctor_info" href="javascript:void(0);">View Profile</a></div>
        </div>
        </div>
      </div>
      @endforeach
      @endif
      @endif
      @else
      <div class="right-content no-result-found suggestion-wrapper @if(count(getSuggestedDoctors())>0) suggested-width @endif"> <img src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
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
        <div class="profile-detail"> <a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}" class="show_doctor_info" href="javascript:void(0);">
          <h3>Dr. {{ucfirst(@$doc->first_name)}} {{@$doc->last_name}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/> @endif</h3>
          </a>
          <ul>
            @if(!empty($doc->docSpeciality))
            <li><img src="{{ URL::asset('img/doctor-ico.png')}}" alt="icon"  />{{@$doc->docSpeciality->spaciality}} </li>
            @endif
            @if(!empty($doc->qualification))
            <li><img src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"  />{{@$doc->qualification}}</li>
            @endif
          </ul>
          <h4>Experience : <span>@if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif</span></h4>
          
        </div>
        <div class="doctor-address">
         <div class="rating_doctor-div"  title="{{@$rating_val}} Rating">
            <?php if(isset($rating_div)){ echo $rating_div; } ?>
          </div>
          <div class="location">{{@$doc->address_1}}, @if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
            @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif
			<a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();"  @endif >( Get On Map )</a></div>
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
				@if(!empty($doc->consultation_discount) && $doc->consultation_discount != '0') Consultation Fees: <strong>₹{{$doc->consultation_discount}}</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif($doc->consultation_discount == '0')<strong>Consultation Fees:Free</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif(!empty($doc->consultation_fees))<strong>Consultation Fees: ₹{{$doc->consultation_fees}}</strong> 
				@else Fee Not Updated @endif
			</div>
        </div>
        <div class="list-bottom">
          <div class="cal-doctor"> <a onclick="showSlot({{$doc->id}});" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Book Appointment</a> </div>
          <div class="view-profile"><a data_id="{{$doc->id}}" info_name="{{@$doc->DoctorSlug->name_slug}}"  class="show_doctor_info" href="javascript:void(0);">View Profile</a></div>
        </div>
        </div>

      </div>
      @endforeach
      @endif
      @endif
      @if(isset($infoData))
      <div class="pages-section"> {{ $infoData->appends($_REQUEST)->links() }} </div>
      @endif </div>
      @if(!empty(Session::get('city_id')) && count(getSuggestedDoctors())>0)
    <div class="right-small">
      <div class="right-white-box">
        <h2>Suggested Doctors</h2>
        <h5>Popular Doctors in your area</h5>
        <ul>
          @foreach(getSuggestedDoctors() as $prime_doctor)
          <li>
            <a data_id="{{$prime_doctor->id}}" info_name="{{@$prime_doctor->DoctorSlug->name_slug}}" class="show_doctor_info" href="javascript:void(0);" >
              <span class="doctor-img"><img @if(@$prime_doctor->profile_pic != null) src="{{@$prime_doctor->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}" @endif alt="{{$prime_doctor->first_name.' '.$prime_doctor->last_name}}" /></span>
              <div class="list-detail">
                <h3>Dr.{{$prime_doctor->first_name.' '.$prime_doctor->last_name}}</h3>
                <p>{{$prime_doctor->docSpeciality->spaciality}}</p>
                
				<span class="dr-fee">
				@if(!empty($prime_doctor->consultation_discount) && $prime_doctor->consultation_discount != '0') Consultation Fees: <strong>₹{{$prime_doctor->consultation_discount}}</strong> 
				<strike style="text-decoration:line-through;">₹{{$prime_doctor->consultation_fees}}</strike>
				@elseif($prime_doctor->consultation_discount == '0')<strong>Consultation Fees: Free</strong>
				<strike style="text-decoration:line-through;">₹{{$doc->consultation_fees}}</strike>
				@elseif(!empty($prime_doctor->consultation_fees)) <strong>Consultation Fees: ₹{{$prime_doctor->consultation_fees}}</strong> @else Fee Not Updated @endif
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
    <div class="container"> </div>
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
