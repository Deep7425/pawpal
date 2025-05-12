@extends('layouts.Masters.Master')
@section('title', 'Health Gennie | Tele Consultation Doctors Book Doctor Appointments')
@section('description', 'Find the right doctor with Health Gennie. Order Medicine and lab from the comfort of your home. Read about health issues and get solutions.')
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="container doctor-detail-wrapper">
	<input class="dbaseUrl" type="hidden" value="{{$_SERVER['REQUEST_URI']}}"/>
<?php $dType = 'doctors';  $stype = 'doctor'; $cityName = 'jaipur'; if(Session::get('search_from_city_slug')) { $cityName =  Session::get('search_from_city_slug');} ?>	
    <div class="container-inner">
    @if (Session::has('message'))
   	 <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
	@endif
    <div class="filer-bar">
        <div class="breadcrume">
            <ul>
				<li><a href="{{route('index')}}">Health Gennie</a> ></li>
				<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,$dType])}}">Doctors</a> ></li>
				<li class="breadcrume-li-tag"><a href="{{route('findDoctorLocalityByType',[$cityName,@$infoData->docSpeciality->slug])}}">{{@$infoData->docSpeciality->spaciality}}</a> ></li>
                <li class="breadcrume-li-tag">
					@if(isset($onCall) && $onCall == "1") Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}
					@else
						Dr. {{Session::get('search_from_search_bar')}}
					@endif
				</li>
            </ul>
        </div>
        <div class="sorting">
		@if(isset($onCall) && $onCall == "1") <h1 style="display:none" title="Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}">Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}</h1>
		@else
			<h1 style="display:none" title="Dr. {{Session::get('search_from_search_bar')}}">Dr. {{Session::get('search_from_search_bar')}}</h1>
		@endif
           <!--<label>Show Doctor Information</label>-->
        </div>
    </div>

	@if(isset($infoData))
		@if(!empty($infoData))
         <div class="listing detail-page">
            <div class="doc-img">
            <img @if(@$infoData->profile_pic != null) src="{{@$infoData->profile_pic}}" @else src="{{ URL::asset('img/doc-img.png')}}"@endif alt="Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}" />                </div>
                <div class="profile-detail auto doctor-profile">
                    <div class="title-wrap">
                    <h3>Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}</h3>
					<!-- @if($infoData->claim_status == '1')<p class="completePclaimed">Profile is claimed</p>@endif
					@if($infoData->claim_status == '0')<a href="{{route('addDoc',['id'=>base64_encode($infoData->id)])}}">Claim this profile</a>@endif -->
                    </div>
                    <ul>
                        @if(!empty($infoData->docSpeciality))<li><img src="<?php echo url("/")."/public/speciality-icon/".$infoData->docSpeciality->speciality_icon; ?>" alt="icon" />{{@$infoData->docSpeciality->spaciality}} </li>@endif
                        @if(!empty($infoData->qualification))<li><img src="{{ URL::asset('img/degree-ico.png')}}" alt="icon" />{{@$infoData->qualification}}</li>@endif
                    </ul>
					<p>Experience : <span>@if(!empty($infoData->experience)){{@$infoData->experience}} Years @else Not Updated @endif</span></p>
                    @if($infoData->varify_status == '1')<div class="verification"><img src="{{ URL::asset('img/verification-icon.png')}}" alt="icon">Verified By Health Gennie</div>@endif
                    <p>Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}} @if(!empty($infoData->docSpeciality)) is a {{@$infoData->docSpeciality->spaciality}}@endif in @if(!empty($infoData->getCityName)) {{@$infoData->getCityName->name}}, {{@$infoData->getStateName->name}} @endif @if(!empty($infoData->experience)) and has an experience of {{@$infoData->experience}} years in this field.@endif </p>
					<div class="doc_available">
					@if($infoData->available_now == 1)
					<p class="now">Available Today</p>
					@endif
					</div>
					<?php $consult_type = []; if(!empty($infoData->oncall_status)){ $consult_type = explode(',',$infoData->oncall_status); } ?>
					<div class="fee-box">
							<div class="doctor-co3">
								<!--<div class="cons-fees">
									@if(!empty($infoData->consultation_discount) && $infoData->consultation_discount != '0' && $infoData->consultation_fees > $infoData->consultation_discount ) In-Clinic Fee: <strong>₹{{$infoData->consultation_discount}}</strong>
									<strike style="text-decoration:line-through;">₹{{$infoData->consultation_fees}}</strike>
									@elseif($infoData->consultation_discount == '0') In-Clinic Fee:<strong> Free</strong>
									<strike style="text-decoration:line-through;">₹{{$infoData->consultation_fees}}</strike>
									@elseif(!empty($infoData->consultation_fees)) In-Clinic Fee:<strong> ₹{{$infoData->consultation_fees}}</strong>
									@else Fee Not Updated @endif
								</div>-->
								
								@if(checkCountry() && in_array(1,$consult_type) && !empty($infoData->oncall_fee))
								<div class="cons-fees">
									Tele-consultation Fees(₹) : <strong>{{$infoData->oncall_fee}}</strong>
						        </div>
								@endif
								@if(checkCountry() && in_array(2,$consult_type))
								@if($infoData->fees_show == '1')	
								<div class="cons-fees">
								  @if(!empty($infoData->consultation_fees)) In-clinic Fees(₹) : <strong>{{$infoData->consultation_fees}}</strong>
								  @else Fee Not Updated @endif
								</div>
								@endif
								@endif
							</div>
					</div>
                 <div class="list-bottom">
					<div class="cal-doctor">
					    @if(checkCountry() && in_array(1,$consult_type) || in_array(2,$consult_type))
							@if(in_array(1,$consult_type))
								<a onclick="showSlot({{$infoData->id}}, '{{base64_encode(1)}}');" class="btn" href="javascript:void(0);"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Tele Appointment</a>
							@endif
							@if(in_array(2,$consult_type))
								<a onclick="showSlot({{$infoData->id}}, '{{base64_encode(2)}}');" class="btn in-clinic-btn" href="javascript:void(0);">In-clinic Appointment</a>
							@endif
						@else
							<div>
								<p class="bg-warning text-danger">Not Available For Consultation</p>
							</div>
						@endif
					</div>
					<div class="cal-doctor-feedback-pat"><a onclick="patientFeedbackForm({{$infoData->user_id}});" href="javascript:void(0);" class="btn btn-default"><img src="{{ URL::asset('img/cal-ico.png')}}" alt="icon"  />Share Feedback</a></div>
				</div>

                 </div>

			</div>

          <div class='example profile-detil'>
            <div class='tabsholder2'>
                  <div data-tab="Info">
                    <div class="doctor-info">
						@if(!empty($infoData->content))
                    	<p class="doc-description">
							<?php 
								$string = $infoData->content;
								$totalString = 0;
								$string = strip_tags($string);
								if (strlen($string) > 100) {
									$stringCut = substr($string, 0, 100);
									$endPoint = strrpos($stringCut, ' ');
									$string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
									$totalString = strlen($string);
									$string .= '<a class="showcntnt">Read More</a>';
								}
								echo '<div>'.$string.'</div>';
							?>
							<div class="fullcntnt" style="display:none;">
								@if(strlen($string) > 100) 
									{{substr($infoData->content,$totalString)}}<a class="hidecntnt">Read Less</a>
								@else
									{{$infoData->content}}
								@endif
							</div>
						</p>
						@endif
						@if(count($infoData->DoctorRatingReviews)>0)
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

                        <div class="doctor-col">
                            <div class="rating">
                                <div class="rating_doctor-div" title="{{@$rating_val}} Rating">
                                    <?php if(isset($rating_div)){ echo $rating_div; } ?>
                                </div>
                            </div>
                             @if(in_array(2,$consult_type))
								<strong>{{@$infoData->clinic_name}}, {{@$infoData->getCityName->name}}</strong>
								<div class="location hideforPaytm">
									<a target="_blank" @if(!empty($infoData->address_1))
									href="https://maps.google.com/maps?q={{@$infoData->clinic_name}} {{$infoData->address_1}} {{@$infoData->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get On Map )</a>
								</div>
								<p><strong>Address:</strong> {{@$infoData->address_1}}, @if(!empty($infoData->locality_id)){{@$infoData->locality_id['name']}},@endif @if(!empty($infoData->getCityName)) {{@$infoData->getCityName->name}}, @endif {{@$infoData->getStateName->name}}</p>

								<?php
										$alternate_address = json_decode(@$infoData->DoctorData->alternate_address);
									?>
									@if(!empty(@$infoData->DoctorData->alternate_address) && count($alternate_address) > 0)
									@foreach ($alternate_address as $keyy => $add)
									@if($keyy==0)<p><strong class="AlternateAddress">Alternate Address:</strong></p><br>@endif
									<div class="location hideforPaytm">
										<a target="_blank" @if(!empty($add))
										href="https://maps.google.com/maps?q={{@$add}} {{@$infoData->getCityName->name}}" @else onClick="NoShowMap();" @endif >( Get On Map )</a>
									</div>
									<p>{{$add}}</p>

									@endforeach
									@endif
							@endif
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
												<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["FRI"]); ?>
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
												<?php $currentType = ""; $timings =  arrayChangeSequance($infoData->opd_timings["SUN"]); ?>
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

								@else
									Timings Not Updated
								@endif
						</div>

						<!--   <button class="btn-book-appoiontment">Book Appointment</button>-->
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
				  <div data-tab="user experience @if(!empty($total_feedback)) ({{$total_feedback}}) @endif" >
				  	@if(!empty($total_feedback))
                    <h3>User Experience for Dr. {{ucfirst(@$infoData->first_name)}} {{@$infoData->last_name}}</h3>
                    @foreach(@$infoData->DoctorRatingReviews as $feedback)
                      @if($feedback->user_id != "")
                    <div class="feedbackDiv">
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
                      <div class="latest-prod-details">
                          <div class="review-info">

							<div class="latest-review-content">
								<i class="fa fa-comments-o" aria-hidden="true"></i> <p> {{@$feedback->experience}}</p>
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
                    @else
                    	<h3>No User experience</h3>
                    @endif
                  </div>
              <!--
                  <div data-tab="Consult" class="txt-center">
                    <p>No query answered by this doctor. <br>Get answers to your health queries now</p>
                    <a href="#">Ask Question</a>
                  </div>
              -->
                  <div data-tab="Articles" class="txt-center">
                    <p>No articles written by this doctor.</p>
                    <a href="{{route('blogList')}}" class="Articles-btn">Read Articles</a>
                  </div>
            </div>
          </div>
		@endif
	@endif
    </div>
</div>
<script>
$(document).ready(function() {
	var href = 'javascript:void(0);';
	$(".tabsholder2 .card-tabs-bar a").each(function(){
		$(this).attr('href',href);

     });
	 var currentHasVal = window.location.hash.substr(1);
	 currentHasVal = currentHasVal.replace(/-/g, ' ');
	 if (currentHasVal != "") {
        $(".tabsholder2 .card-tabs-bar a").each(function(){
		    if ($(this).text().trim() == currentHasVal) {
		    	// $(this).closest('.card-tabs-bar').find("a").removeClass("active");
      			$(this).click();
      			return false;
		    }
		 });
   //      $(".tabsholder2 .card-tabs-stack .data-tab").each(function(){
   //      	alert($(this).attr('data-tab'));
   //      	alert(currentHasVal);
		 //    if ($(this).attr('data-tab') == currentHasVal) {
		 //    	$(this).closest('.card-tabs-bar').find("div").hide();
   //    			$(this).show();
   //    			return false;
		 //    }
		 // });
     }
});
$(document).on("click", ".showcntnt", function (e) {
	$(this).hide();
	$(".fullcntnt").slideDown();
	$(".hidecntnt").show();
});
$(document).on("click", ".hidecntnt", function (e) {
	$(this).hide();
	$(".fullcntnt").slideUp();
	$(".showcntnt").show();
});
$(document).on("click", ".tabsholder2 .card-tabs-bar a", function (e) {
	e.preventDefault();
		var text = $(this).text().trim();
		text = text.replace(/\s+/g, '-');
	   window.location.hash=text;
});
function patientFeedbackForm(id) {
	if($("#userLoginStatus").val()) {
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "HTML",
		url: "{!! route('patients.showFeedbackForm')!!}",
		data:{'id':id},
		success: function(data) {
		  jQuery('.loading-all').hide();
		  jQuery("#patientFeedBackForm").html(data);
		  jQuery('#patientFeedBackForm').modal('show');
		},
		error: function(error) {
			jQuery('.loading-all').hide();
			$.alert('Oops Something goes Wrong.');
		}
	  });
	}
	else{
		$.alert({
		title: 'user Feedback !',
		content: 'Please Login To Share Feedback.',
		draggable: false,
		type: 'green',
		typeAnimated: true,
		buttons: {
			Cancel: function(){
				 // $.alert('Canceled!');
			},
			Login: function(){
				dbaseUrl = $(".dbaseUrl").val();
				var url = '{!! url("/login-feedback?url='+dbaseUrl+'") !!}';
				window.location = url;
			},
		}
	  });
	}
}
</script>
@endsection
