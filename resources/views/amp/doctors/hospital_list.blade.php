@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
<div class="searching-keyword">
      <div class="container">
    <div class="searhc-result">@if(isset($infoData)){{$infoData->total()}}@endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
  </div>
</div>
<?php $stype = 'clinic'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');}
if(Session::get('info_type') == "hos_all"){
	$dType = "hospitals";
	$pType = "hospital";
}
else{
	$dType = 'clinics';
	$pType = 'clinic';
}
$cName = Session::get('search_from_city_name');
if(!empty(Session::get('search_from_locality_name'))){
	if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
		$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
	}
}
 ?>
<div class="container">
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
				@if($dType != "clinics" && $dType != "hospitals")
					<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,$dType])}}">{{$dType}}</a> ></li>
				@endif
				<li class="breadcrume-li-tag">{{Session::get('search_from_search_bar')}}</li>
					@php
					$tag = Session::get('search_from_search_bar');
					if(Session::get('search_from_search_bar')) {
						$tag = Session::get('search_from_search_bar');
						if(Session::get('search_from_locality_name') && Session::get('search_from_city_name') && Session::get('locality_id')) {
							$tag = $tag." in ".Session::get('search_from_locality_name')." ".Session::get('search_from_city_name');	
						}
						else if(Session::get('search_from_city_name')) {
							$tag = $tag." in ".Session::get('search_from_city_name');
						}
					}
					@endphp
				</ul>
			</div>
			<div class="sorting">
				@if($_COOKIE["in_mobile"] != '1')
				<label>@if(isset($infoData) && $infoData->total() != '0') {{$infoData->total()}} results found @else no result found @endif </label>
				@endif	
				<div class="select" style="display:none;">
					<select class="DataFilterBySorting" search_type="clinics">
						<option value="">SORT BY</option>
						<option  value="name_asc" @if(isset($_GET['sort_by'])) @if(base64_decode($_GET['sort_by']) == 'name_asc') selected @endif @endif>Name In Ascending Order</option>
					 	<option value="name_dsc" @if(isset($_GET['sort_by'])) @if(base64_decode($_GET['sort_by']) == 'name_dsc') selected @endif @endif>Name In Descending Order</option>
					</select>
				</div>
			</div>
        </div>
        <?php $filteredLocality = ""; $filteredGenderKey = 0; $filteredGender = "";
			  if(isset($_GET['filter_by_locality_put'])) {
				$filteredLocality = json_decode($_GET['filter_by_locality_put'],true);
			  }
		?> 
		<div class="left-content desktop filteredDivDesktop">
			<div class="block-filter">
				<h2>LOCALITY</h2>
				<div class="search-list"><input type="text" class="searchLocalityFromList" placeholder="Find Locality" /></div>
                <div class="content-wrapper">
				<label class="chck-container">All Places
					<input type="checkbox" class="filter_by_locality_all" @if(!empty($filteredLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($filteredLocality) )  checked check-type="1" @else check-type="0" @endif @endif/>
					<span class="checkmark"></span>
				</label>
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
					<div class="find-locality-div">
						{!!$list_menu!!}
					</div>
				@endif
				</div>
			</div>
			<div class="block-filter">
			<h2>EXPERIENCE</h2>
			<div class="filter-wrap">
				<label class="chck-container">1-5 Years
					<input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '5') checked @endif @endif /><span class="checkmark"></span>
				</label>
			</div>
			<div class="filter-wrap">
				<label class="chck-container">5-10 Years
					<input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '10') checked @endif @endif /><span class="checkmark"></span>
				</label>
			</div>
			<div class="filter-wrap">
				<label class="chck-container">10-15 Years
					<input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '15') checked @endif @endif/><span class="checkmark"></span>
				</label>
			</div>
			<div class="filter-wrap">
				<label class="chck-container">15-20 Years
					<input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '20') checked @endif @endif/><span class="checkmark"></span>
				</label>
			</div>
			<div class="filter-wrap">
				<label class="chck-container">More than 20 Years
					<input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '1') checked @endif @endif/><span class="checkmark"></span>
				</label>
			</div>
			</div>
			
<!--			 <div class="block-filter">
			<h2>Top Doctors By Location</h2>
			<div class="filter-wrapConsultation">
				<ul class="list-unstyled">
				 @foreach(getTopLocality(Session::get("city_id")) as $locality)
					<li><a href="{{route('findDoctorLocalityByType',[$cityName,$pType,$locality->slug])}}">Best {{$dType}} in {{$locality->name}}</a></li>
				  @endforeach
				</ul>
			</div>
			</div>-->
		</div>
        
        
        <div class="left-wrapper filteredDivMobile">
        	<h2 class="accordion">Filters</h2>
        	<div class="left-content" style="display:none;">
            	<div class="panel">
					<div class="block-filter">
						<h3>LOCALITY</h3>
						<div class="search-list"><input type="text" class="searchLocalityFromList" placeholder="Find Locality" /></div>
						<div class="content-wrapper">
						<label class="chck-container">All Places
							<input type="checkbox" class="filter_by_locality_all" @if(!empty($filteredLocality)) @if(count(getLocalityByCityId(Session::get('city_id'))) == count($filteredLocality) ) checked @endif @endif/>
							<span class="checkmark"></span>
						</label>
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
							<div class="find-locality-div">
							{!!$list_menu!!}
							</div>
						@endif
						</div>
					</div>
					<div class="block-filter">
						<h3>EXPERIENCE</h3>
						<div class="filter-wrap">
							<label class="chck-container">1-5 Years
								<input type="checkbox" name="filter_by_exp" value="5" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '5') checked @endif @endif /><span class="checkmark"></span>
							</label>
						</div>
						<div class="filter-wrap">
							<label class="chck-container">5-10 Years
								<input type="checkbox" name="filter_by_exp" value="10" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '10') checked @endif @endif /><span class="checkmark"></span>
							</label>
						</div>
						<div class="filter-wrap">
							<label class="chck-container">10-15 Years
								<input type="checkbox" name="filter_by_exp" value="15" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '15') checked @endif @endif/><span class="checkmark"></span>
							</label>
						</div>
						<div class="filter-wrap">
							<label class="chck-container">15-20 Years
								<input type="checkbox" name="filter_by_exp" value="20" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '20') checked @endif @endif/><span class="checkmark"></span>
							</label>
						</div>
						<div class="filter-wrap">
							<label class="chck-container">More than 20 Years
								<input type="checkbox" name="filter_by_exp" value="1" @if(isset($_GET['filter_by_exp'])) @if(base64_decode($_GET['filter_by_exp']) == '1') checked @endif @endif/><span class="checkmark"></span>
							</label>
						</div>
					</div>
				</div>
        	</div>
        </div>
        
		
		<div class="right-content">
		@if(isset($infoData))
		@if(count($infoData) > 0)
			@foreach($infoData as $doc)
			@php 
				if($doc->practice_type == "2") { $stype = 'hospital'; }
			@endphp
			<div class="listing">
				
					<div class="doc-img"><img @if(@$doc->clinic_image != null) src="{{@$doc->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  /> 
					</div>
				<div class="right-con-wrapper">
                <div class="profile-detail">
					<a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}" ><h3 class="text"> {{ucfirst(@$doc->clinic_name)}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3></a>
					<ul>
						@if(!empty($doc->clinic_speciality))<li><img src="{{ URL::asset('img/doctor-ico.png')}}" alt="icon"  />{{getSpecialistName($doc->clinic_speciality)}} @if($doc->practice_type == "1") Clinic @else Hospital @endif</li>@endif 
					</ul>
					<h4><span>{{getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name)}}</span>@if(getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name) == '1') Doctor @else Doctors @endif</h4>
					</div>
				<div class="doctor-address">
					<div class="rating_doctor-div">
						@if(count($doc->DoctorRatingReviews)>0)
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
							echo $rating_div;
						?>
						@endif
					</div>
					<div class="location">{{@$doc->address_1}},@if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif <a target="_blank" @if(!empty($doc->address_1)) href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();" @endif>( Get On Map )</a></div>
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
				</div>
				<div class="list-bottom hospital-wrap">
					<div class="view-profile"><a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}">View Information</a></div>
				</div>
                </div>
			</div>
			@endforeach	
		@else
			<div class="right-content no-result-found suggestion-wrapper ">
			   <img src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
			   <h2><strong>No Result were found. Please try modifying your search term!</strong><br /> </h2>
			  <!-- <p style="display:none;">Browse other Doctor <a class="btn btn-success view_information" search_type="1" data_id="0" info_type="clinic_all" href="javascript:void(0);"><span class="text" style="display:none;">Hospital & Clinic</span>Click Here</a></p>-->
               <div class="doctorRegistrationDiv"><a href='{{route("addDoc")}}'>Doctor Registration</a></div>	
			</div>
           
			@if(count(getHospitalByOtherSpaciality())>0)
				<div class="title-wrapper-suggestion">
					<h2>Explore More</h2>
					<p>Following search results are based on your search.</p>
				</div>
				@foreach(getHospitalByOtherSpaciality() as $doc)
					@php 
						if($doc->practice_type == "2") { $stype = 'hospital'; }
					@endphp
					<div class="listing">
						<div class="doc-img"><img @if(@$doc->clinic_image != null) src="{{@$doc->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  /> 
						
						</div>
			<div class="right-con-wrapper">
					<div class="profile-detail">
						<a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}"><h3 class="text"> {{ucfirst(@$doc->clinic_name)}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/>@endif</h3></a>
						<ul>
							@if(!empty($doc->clinic_speciality))<li><img src="{{ URL::asset('img/doctor-ico.png')}}" alt="icon"  />{{getSpecialistName($doc->clinic_speciality)}} @if($doc->practice_type == "1") Clinic @else Hospital @endif</li>@endif 
						</ul>
						<h4><span>{{getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name)}}</span>@if(getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name) == '1') Doctor @else Doctors @endif</h4>
						</div>
					<div class="doctor-address">
						<div class="rating_doctor-div">
							@if(count($doc->DoctorRatingReviews)>0)
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
								echo $rating_div;
							?>
							@endif
						</div>
						<div class="location">{{@$doc->address_1}},@if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif 
						<a @if(!empty($doc->address_1)) href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();" @endif>( Get On Map )</a></div>
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
					</div>
					<div class="list-bottom hospital-wrap">
						<div class="view-profile"><a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}"><span class="text" style="display:none">{{@$doc->clinic_name}}</span>View Information</a></div>
					</div>
                    </div>
				</div>
				@endforeach
			@endif
		@endif
		@else
			<div class="right-content no-result-found suggestion-wrapper ">
			   <img src="{{ URL::asset('img/search-result.png')}}" alt="icon"/>
			   <h2><strong>No Result were found. Please try modifying your search term!</strong><br /> </h2>
			  <!-- <p style="display:none;">Browse other Doctor <a class="btn btn-success view_information" search_type="1" data_id="0" info_type="clinic_all" href="javascript:void(0);"><span class="text" style="display:none;">Hospital & Clinic</span>Click Here</a></p>-->
               <div class="doctorRegistrationDiv"><a href='{{route("addDoc")}}'>Doctor Registration</a></div>	
			</div>
            
			@if(count(getHospitalByOtherSpaciality())>0)
				<div class="title-wrapper-suggestion">
					<h2>Explore More</h2>
					<p>Following search results are based on your search.</p>
				</div>
				@foreach(getHospitalByOtherSpaciality() as $doc)
					@php 
						if($doc->practice_type == "2") { $stype = 'hospital'; }
					@endphp
					<div class="listing">
					<div class="right-con-wrapper">
                    	<div class="doc-img"><img @if(@$doc->clinic_image != null) src="{{@$doc->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon"  /> 
						
						</div>
						<div class="profile-detail">
						<a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}"><h3 class="text"> {{ucfirst(@$doc->clinic_name)}} @if($doc->is_prime == '1') <img title="Subcribed" src="{{ URL::asset('img/verification-icon.png')}}" alt="icon"/> @endif</h3></a>
						<ul>
							@if(!empty($doc->clinic_speciality))<li><img src="img/doctor-ico.png" alt="icon"  />{{getSpecialistName($doc->clinic_speciality)}} @if($doc->practice_type == "1") Clinic @else Hospital @endif</li>@endif 
						</ul>
						<h4><span>{{getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name)}}</span>@if(getAllDoctorsUnderPractice($doc->practice_id,$doc->clinic_name) == '1') Doctor @else Doctors @endif</h4>
						</div>
						<div class="doctor-address">
						<div class="rating_doctor-div">
							@if(count($doc->DoctorRatingReviews)>0)
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
								echo $rating_div;
							?>
							@endif
						</div>
						<div class="location">{{@$doc->address_1}},@if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif @if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif 
						<a target="_blank" @if(!empty($doc->address_1))  href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get On Map )</a>
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
					</div>
                    <div class="list-bottom hospital-wrap">
						<div class="view-profile"><a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->clinic_name_slug])}}" >View Information</a></div>
					</div>
                    </div>
					
				</div>
				@endforeach
			@endif
		@endif
		
		@if(isset($infoData))
		<div class="pages-section">
		{{ $infoData->appends($_REQUEST)->links() }}
		</div>		
		@endif
		</div>
	</div>
	<div class="container-fluid">
      <div class="container"> </div>
    </div> 
     </div>
	 
	<script>
		jQuery(document).on("click", ".show_hospital_info", function (e) {
			var hos_name = $(this).attr('info_name');
			var htype = "hospital";
			htype = $(this).attr('htype');
			htype = htype.trim();
			jQuery('.loading-all').show();
			var url = '{{ route("findDoctorLocalityByType", ":city/:hospital/:hos_name") }}';
			url = url.replace(':hos_name', hos_name);
			url = url.replace(':hospital', htype);
			var city = "<?php if(Session::get('search_from_city_slug')){ echo Session::get('search_from_city_slug');}else { echo 'jaipur'; } ?>";
			url = url.replace(':city', city);
			window.location = url;
		});
	</script> 
@endsection