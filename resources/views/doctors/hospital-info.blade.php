@extends('layouts.Masters.Master')
@section('title', '')
@section('content')
<div class="container listing-right-wrapper hospital-detail-page">
  <div class="container-inner"> @if (Session::has('message'))
    <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
    @endif
	<?php $stype = 'doctor'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');} 
	if(Session::get('info_type') == "hos_all"){
		$dType = "hospitals";
	}
	else{
		$dType = 'clinics';
	}
	?>
    <div class="filer-bar">
      <div class="breadcrume">
        <ul>
			<li><a href="{{route('index')}}">Health Gennie</a> ></li>
			<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,$dType])}}">{{$dType}}</a> ></li>
			<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,@$infoData->docSpeciality->slug])}}">{{@$infoData->docSpeciality->spaciality}}</a> ></li>
			<li class="breadcrume-li-tag">{{Session::get('search_from_search_bar')}}</li>
        </ul>
      </div>
      <div class="sorting">
	  <h1 style="display:none;" title="{{Session::get('search_from_search_bar')}}">{{Session::get('search_from_search_bar')}}</h1>
	  @if($_COOKIE["in_mobile"] != '1')
		@if(isset($infoData))
        @if($infoData->practice_type == "1")
			<label>Clinic Information</label>
		@else
			<label>Hospital Information</label>
		@endif
		@else
			<label>Hospital Information</label>
		@endif	
	 @endif	
      </div>
    </div>
    @if(isset($infoData))
    @if(!empty($infoData))
    <div class="right-content-hospital">
      <div class="listing detail-page ">
        <div class="doc-img"> <img @if(@$infoData->clinic_image != null) src="{{@$infoData->clinic_image}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="icon" /> </div>
        <div class="profile-detail auto">
          <div class="title-wrap">
            <h3>{{ucfirst(@$infoData->clinic_name)}}</h3>
            @if($infoData->claim_status == '1')
            <!--<p class="completePclaimed">Profile is claimed</p>-->
            @endif
            </div>
          @if($infoData->varify_status == '1')
          <div class="verification"><img src="{{ URL::asset('img/verification-icon.png')}}" alt="icon">Verified By Health Gennie</div>
          @endif
          <div class="address">{{@$infoData->address_1}}</div>
          <div class="multi-specility">
            <ul>
              @if(!empty($infoData->clinic_speciality))<li>{{getSpecialistName($infoData->clinic_speciality)}}</li>@endif
              @if($infoData->practice_type == "1")
				  <li>Clinic</li>
			  @else
				  <li>Hospital</li>
			  @endif
              <!-- <li>Established 2007</li> -->
              <!-- <li>250 Beds</li> -->
              <!-- <li>67 Doctors</li> -->
            </ul>
          </div>
          <!-- <div class="list-bottom"> @if($infoData->claim_status == '1')
            <div class="cal-doctor"> <a class="btn call_now" href="javascript:void(0);"><img src="img/cal-ico.png" alt="icon"  />Call Now</a> </div>
            @endif </div> -->
        </div>
        <div class="call_now_section" style="display:none">Contact Number <span class="number">{{$infoData->clinic_mobile}}</span> </div>
      </div>
      <div class='example profile-detil'>
        <div class='tabsholder2'>
          <div data-tab="Info">
            <div class="doctor-info"> @if(count($infoData->DoctorRatingReviews)>0)
              <?php
                        $rating_val = 0;
                        $rating_count = 0;
                        foreach($infoData->DoctorRatingReviews as $rating) {
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
              <div class="rating">
                <div class="rating_doctor-div" title="{{@$rating_val}} Rating">
                  <?php if(isset($rating_div)){ echo $rating_div; } ?>
                </div>
              </div>
              
              
              <div class="doctor-col">
                <p>
                  <strong>Address:</strong> {{@$infoData->address_1}}, @if(!empty($infoData->locality_id)){{@$infoData->locality_id['name']}},@endif @if(!empty($infoData->getCityName)) {{@$infoData->getCityName->name}}, @endif {{@$infoData->getStateName->name}}</p>
				<div class="location">
				<a target="_blank" 
				@if(!empty($infoData->address_1)) href="https://maps.google.com/maps?q={{$infoData->clinic_name}} {{$infoData->address_1}} {{@$infoData->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get On Map )</a>
				</div>
              </div>
			  <?php
				function arrayChangeSequance($array)
				{ 	if (array_key_exists("teleconsultation",$array)){
						$teleconsultation= array_column($array, 'teleconsultation');
						array_multisort($teleconsultation, SORT_ASC, $array); 
					}
					foreach ($array as $key => $value) {
						((isset($value['teleconsultation']) && isset($value['teleconsultation']) == 1) ? "Tele" : "Inclinic");
					}
					return $array;
				}
				?>
			  <div class="doctor-co2 timing">
				  <h5>OPD Timings</h5>
				  @if(!empty($infoData->opd_timings) && count($infoData->opd_timings)>0)
						@if(isset($infoData->opd_timings["MON"]) && count($infoData->opd_timings["MON"])>0)
							<p>
							  <span class="time-title">Monday </span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["MON"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>

									<?php $currentType = $type; ?>
								@endforeach
								</span>
							</p>
						  @endif
						  @if(isset($infoData->opd_timings["TUE"]) && count($infoData->opd_timings["TUE"])>0)
						   <p>
							  <span class="time-title">Tuesday </span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["TUE"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
							</p>
						  @endif

						  @if(isset($infoData->opd_timings["WED"]) && count($infoData->opd_timings["WED"])>0)
							  <p>
							  <span class="time-title">Wednesday </span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["WED"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
							</p>
						  @endif

						  @if(isset($infoData->opd_timings["THU"]) && count($infoData->opd_timings["THU"])>0)
							  <p>
							  <span class="time-title">Thursday </span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["THU"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
								</p>
						  @endif

						  @if(isset($infoData->opd_timings["FRI"]) && count($infoData->opd_timings["FRI"])>0)
								<p>
							  <span class="time-title">Friday</span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timingMon =  arrayChangeSequance($infoData->opd_timings["FRI"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
								 </p>
						  @endif


						  @if(isset($infoData->opd_timings["SAT"]) && count($infoData->opd_timings["SAT"])>0)
							   <p>
							  <span class="time-title">Saturday </span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["SAT"]); ?>
								@foreach($timings as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
								  </p>
						  @endif


						  @if(isset($infoData->opd_timings["SUN"]) && count($infoData->opd_timings["SUN"])>0)
							  <p>
							  <span class="time-title">Sunday</span>
								<span class="time-opdSc">
									<?php $currentType = ""; $timingMon =  arrayChangeSequance($infoData->opd_timings["SUN"]); ?>
								@foreach($infoData->opd_timings["SUN"] as $opd_t)
								<?php $type = ((isset($opd_t['teleconsultation']) && $opd_t['teleconsultation'] == 1) ? "Tele" : "Inclinic");?>
									@if($type != $currentType)
										<span class="time-title">{{$type}} : </span>
									@endif
									({{$opd_t['start_time']}} - {{$opd_t['end_time']}})<span class="opdTiming">({{$opd_t['start_time']}} - {{$opd_t['end_time']}})</span>
									<?php $currentType = $type; ?>
								@endforeach
								</span>
								</p>
						  @endif

					@else
						Timings Not Updated
					@endif
			</div>
          <div class="doctor-co3 payment-mode"> <strong>Payment Mode</strong>
            <ul class="Creadit_Section">
              <li><img width="20" src="{{ URL::asset('img/credit-card.png')}}" /> Credit Card</li>
              <li><img width="20" src="{{ URL::asset('img/cash.png')}}" /> Cash</li>
              <li><img width="20" src="{{ URL::asset('img/online-payment.png')}}" /> Online Payment</li>
              <li><img width="20" src="{{ URL::asset('img/debit-card.png')}}" /> Debit Card</li>
              <li><img width="20" src="{{ URL::asset('img/insurance-policy.png')}}" /> Insurance</li>
            </ul>
          </div>
          <!--<button class="btn-book-appoiontment">Book Appointment</button>-->
          </div>
          </div>
          <div data-tab='@if($infoData->practice_type == "1") Specialities @else Services with Facilities @endif'>

                        <div class="tab-bot-boxes procedures">
                          <!-- <h3>Specialists in {{ucfirst(@$infoData->clinic_name)}} - {{ucfirst(getCityName($infoData->city_id))}}</h3> -->
                          <ul>
							@if(!empty($infoData->practice_id))
								@if(count(getAllSpecialityByHospital($infoData->practice_id,$infoData->clinic_name)) > 0)
									@foreach(getAllSpecialityByHospital($infoData->practice_id,$infoData->clinic_name) as $spes)
										<li><a href="javascript:void(0);">{{$spes}}</a></li>
									@endforeach
								@endif
							@else
								@if(count(getAllSpecialityByHospital($infoData->practice_id,$infoData->clinic_name)) > 0)
									@foreach(getAllSpecialityByHospital($infoData->practice_id,$infoData->clinic_name) as $spes)
										<li><a href="#">{{$spes}}</a></li>
									@endforeach
								@endif
							@endif
                          </ul>
                        </div>
                       </div>
          <div data-tab="Doctors @if(isset($infoDoctors))({{count($infoDoctors)}}) @endif" class="doctors-list">

            <div class="tab-bot-boxes procedures">
              <h3 style="margin-top:0px;">Doctors in {{ucfirst(@$infoData->clinic_name)}} - {{ucfirst(getCityName($infoData->city_id))}}</h3>
              @foreach($infoDoctors as $doc)
                <div class="listing">
					@php
						if(!empty($doc->getCityName)){
							$cityName = $doc->getCityName->slug;
						}
					@endphp
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
                     ?>
                   @endif
                  <div class="doc-img" style=" margin-right:1%;"><img @if($doc->profile_pic != null) src="{{$doc->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}} @endif"  alt="icon">
                    
                  </div>
                  <div class="profile-detail"> <a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">
                    <h3>Dr. {{ucfirst(@$doc->first_name).' '.ucfirst(@$doc->last_name)}} </h3>
                    </a>
                    <ul>
                      @if(!empty($infoData->clinic_speciality))<li><img src="{{getSpecialityIconById($infoData->clinic_speciality)}}" alt="icon">{{getSpecialistName($infoData->clinic_speciality)}} </li>@endif
                     @if(!empty($doc->qualification)) <li><img src="{{ URL::asset('img/degree-ico.png')}}" alt="icon"/>{{@$doc->qualification}}</li>@endif
                    </ul>
                    <h4 style=" margin-left:0px;">Experience : <span> @if(!empty($doc->experience)){{@$doc->experience}} Years @else Not Updated @endif </span></h4>
					<div class="doc_available">
					@if($doc->available_now == 1)
					<p class="now">Available Today</p>
					@endif
				  </div>
					<?php $consult_type = []; if(!empty($doc->oncall_status)){ $consult_type = explode(',',$doc->oncall_status); } ?>
                    <div class="list-bottom">
                      <div class="cal-doctor">
						@if(checkCountry() && in_array(1,$consult_type) || in_array(2,$consult_type))
							@if(in_array(1,$consult_type))
								<a onclick="showSlot({{$doc->id}}, '{{base64_encode(1)}}');" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Tele Appointment</a>
							@endif
							@if(in_array(2,$consult_type))
								<a onclick="showSlot({{$doc->id}}, '{{base64_encode(2)}}');" class="btn in-clinic-btn" href="javascript:void(0);">In-clinic Appointment</a>
							@endif
						@else
							<div>
								<p class="bg-warning text-danger">Not Available For Consultation</p>
							</div>
						@endif
					  </div>
                      <div class="view-profile"><a href="{{route('findDoctorLocalityByType',[$cityName,$stype,@$doc->DoctorSlug->name_slug])}}">View Profile</a></div>
                    </div>
                  </div>
                  <div class="doctor-address" style=" padding-top:0px;">
                    <div class="rating_doctor-div" title="{{@$rating_val}} Rating">
                        <?php if(isset($rating_div)){ echo $rating_div; } ?>
                       </div>
						@if(in_array(2,$consult_type))
						<div class="location">{{@$doc->address_1}}, 			@if(!empty($doc->locality_id)){{@$doc->locality_id['name']}},@endif
						@if(!empty($doc->getCityName)) {{@$doc->getCityName->name}} @endif <a target="_blank" @if(!empty($doc->address_1))
						href="https://maps.google.com/maps?q={{$doc->clinic_name}} {{$doc->address_1}} {{@$doc->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get On Map )</a>
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
					  @if(checkCountry() && in_array(1,$consult_type) && !empty($doc->oncall_fee))
						<div class="cons-fees">
							Tele-consultation Fees(₹) : <strong>{{$doc->oncall_fee}}</strong>
						</div>
						@endif
						@if(checkCountry() && in_array(2,$consult_type))
						<div class="cons-fees">
						  @if(!empty($doc->consultation_fees)) In-clinic Fees(₹) : <strong>{{$doc->consultation_fees}}</strong>
						  @else Fee Not Updated @endif
						</div>
						@endif
					</div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
			<?php
				$total_feedback = 0;
				foreach($infoData->DoctorRatingReviews as $feedback_dd){
					if($feedback_dd->user_id != ""){
						$total_feedback++;
					}
				}
			  ?>
			 
          <div data-tab="user experience @if(count(getDoctorRatingByHospital($infoData->practice_id,$infoData->clinic_name))> 0) ({{count(getDoctorRatingByHospital($infoData->practice_id,$infoData->clinic_name))}}) @endif ">
          @if(count(getDoctorRatingByHospital($infoData->practice_id,$infoData->clinic_name))> 0)
            <h3>User Experience for  {{@$infoData->clinic_name}}</h3>
			@if(count(getDoctorRatingByHospital($infoData->practice_id,$infoData->clinic_name))> 0)
                    @foreach(getDoctorRatingByHospital($infoData->practice_id,$infoData->clinic_name) as $feedback)
                      @if($feedback->user_id != "")
                    <div class="feedbackDiv">
                      <div class="latest-prod-details">
                          <div class="review-prod-name">
                              Review for:
                              <span class="fontBig">
                                    <i class="fa fa-user-md" aria-hidden="true"></i> <a href="javascript:void(0);">Dr. {{@$feedback->doc_name}}</a>
                              </span>
                          </div>

                          <div class="review-info">
							<?php  
							$rating_div = "";
							for($x=1;$x<=$feedback->rating;$x++) {
							  $rating_div .=  '<span class="doc-star-rating fa fa-star checked"></span>';
							}
							while($x<=5) {
								  $rating_div .= '<span class="doc-star-rating fa fa-star"></span>';
								  $x++;
							}
							?>
                            <div class="rating_doctor-div" title="{{@$feedback->rating}} Rating">
								{!!$rating_div!!}
                            </div>
							<div class="latest-review-content">
                            <i class="fa fa-comments-o" aria-hidden="true"></i>  {{@$feedback->experience}}
							</div>
							<div class="review-basic-info">
                              <span class="rating-by">Reviewed By: <i class="fa fa-user-o" aria-hidden="true"></i>@if($feedback->publish_status == '1') <a href="javascript:void(0);">{{ getUserName($feedback->user_id) }}</a> @else <a href="javascript:void(0);">########</a> @endif
                              </span>
                              <span class="grey-text"><i class="fa fa-clock-o" aria-hidden="true"></i> {{getTimeElapsedString($feedback->created_at)}}</span>
							</div>  
                          </div>
                          <div class="recommend-review">
                            <p>@if($feedback->recommendation == 1)<i class="fa fa-thumbs-up" aria-hidden="true"></i> I recommend this professional @else <i class="fa fa-thumbs-down" aria-hidden="true"></i> I don't recommend this professional @endif</p>
                          </div>
                          <div class="visit-review">
                              <p><i class="fa fa-handshake-o" aria-hidden="true"></i> Visited For  <strong> @if($feedback->visit_type == 1) Consultation @endif
                                @if($feedback->visit_type == 2) Procedure @endif
                                @if($feedback->visit_type == 3) Follow up @endif
                                </strong>
                              </p>
                          </div>
                          <div class="tab-bot-boxes procedures">
								@php $suggestions =  explode(",", $feedback->suggestions); @endphp
								@if(!empty($feedback->suggestions))
								<p>  Compliment For <i class="fa fa-user-md" aria-hidden="true"></i></p>
                                <ul>
                                  @foreach ($suggestions as $value)
                                    <li><a href="javascript:void(0);">{{getCompliments($value)}}</a></li>
                                  @endforeach
                                </ul>
								@endif
                            </div>
                      </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                @else
                      <h3>No User experience</h3>
                @endif
          </div>

        </div>
      </div>
    </div>
    @endif
    @endif </div>
</div>
<script>
  $(document).ready(function() {
   var currentHasVal = window.location.hash.substr(1);
    currentHasVal = currentHasVal.replace(/-/g, ' ');
   if (currentHasVal != "") {
        $(".tabsholder2 .card-tabs-bar a").each(function(){
        if ($(this).text().trim() == currentHasVal) {
            $(this).click();
            return false;
        }
     });
     }
});
  $(document).on("click", ".tabsholder2 .card-tabs-bar a", function () {
    var text = $(this).text().trim();
    text = text.replace(/\s+/g, '-');
     window.location.hash=text;
});
		jQuery(document).on("click", ".show_doctor_info", function (e) {
			var data_info_id = $(this).attr('data_id');
			jQuery('.loading-all').show();
			var url = '{!! url("/doctor-detail?id='+btoa(data_info_id)+'") !!}';
			window.location = url;
			/*
			if($("#searchDocInfo").find("input[name='data_search']").val() == ""){
				$("#searchDocInfo").find("input[name='data_search']").val($("#search_data_by_search_id").val());
			}
			var search_type = $(this).attr('search_type');
			var info_type = $(this).attr('info_type');
			var data_info_id = $(this).attr('data_id');
			$("#searchDocInfo").find("input[name='search_type']").val(search_type);
			$("#searchDocInfo").find("input[name='info_type']").val(info_type);
			$("#searchDocInfo").find("input[name='id']").val(data_info_id);
			setTimeout(function(){
				$("#searchDocInfo").submit();
			}, 500);*/
		});
		
		jQuery(document).on("click", ".show_doctor_info", function (e) {
			var doc_name = $(this).attr('info_name');
			jQuery('.loading-all').show();
			var city = "<?php echo Session::get('search_from_city_name'); ?>";
			var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}';
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'doctor');
			url = url.replace(':name', doc_name);
			window.location = url;
		});
	</script>
@endsection
