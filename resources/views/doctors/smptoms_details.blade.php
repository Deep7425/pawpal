@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
	<div class="searching-keyword">
		<div class="container">
			<h1>SEARCH RESULTS FOR: <strong>"{{ Session::get('search_from_search_bar') }}"</strong></h1>
			<div class="searhc-result"> @if(!empty($infoData)) {{count($infoData->SymptomsSpeciality)}} @endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
		</div>
    </div>
	<div class="container">
		  <div class="container-inner">
				<div class="filer-bar">
			 		<div class="breadcrume">
							<ul>
							  <li><a href="#">Home</a> /</li>
							  <li><a href="#">{{ Session::get('search_from_city_name') }}</a> /</li>
							  <li>Search result for <strong>“{{ Session::get('search_from_search_bar') }}”</strong></li>
							</ul>
					 </div>
						<?php $spaciality_ids = []; $spaciality_slugs= []; $SymptomsSpeciality = []; ?>
                        	@if(count($infoData->SymptomsSpeciality)>0)
							@foreach($infoData->SymptomsSpeciality as $spa)
								<?php $spaciality_ids[] = $spa->Speciality->id;
									  $spaciality_slugs[] = $spa->Speciality->slug;
									  $SymptomsSpeciality[] = ["id"=>$spa->Speciality->id,"slug"=>$spa->Speciality->slug,"group_id"=>$spa->Speciality->group_id,"specialities"=>$spa->Speciality->specialities];
								?>
							@endforeach
							<?php $spaciality_ids = array_unique($spaciality_ids); 
							$spaciality_slugs = array_unique($spaciality_slugs); 
							$SymptomsSpeciality = array_map("unserialize", array_unique(array_map("serialize", $SymptomsSpeciality)));?>
                            @endif
					<div class="sorting">
                                         
					<!--<label>Showing all 4 results</label>-->
					<!--<div class="select">
					 <select>
						<option>SORT BY LATEST</option>
					 </select>
					</div>-->
				   </div>
			</div>
			<div class="Symptoms_section symptons-detail">
            
				<div class="doctor_heading">
					<h2>Symptoms Details</h2>
				</div>
                <button data_id="{{json_encode($spaciality_ids)}}" slug="{{json_encode($spaciality_slugs)}}" info_type="Speciality" search_type="1" s_type="2" class="btn-book-appoiontment view_Alldoc_speciality" type="button">Book Appointment</button>
				<div class="Symptoms_block">
					<h2>{{@$infoData->symptom}}</h2>
					<p>{!!@$infoData->description!!}</p>
				</div>
				@if(!empty($infoData->cause))
				<div class="doctor_heading">
					<h2>Cause</h2>
				</div>
				<div class="Symptoms_block">
					<p>{!!@$infoData->cause!!}</p>
				</div>
				@endif
				@if(!empty($infoData->treatment))
				<div class="doctor_heading">
					<h2>Treatment</h2>
				</div>
				<div class="Symptoms_block">
					<p>{!!@$infoData->treatment!!}</p>
				</div>
				@endif
                
                <div class="disclaimer">
                	<p><strong>Note:</strong> The information here is solely for understanding purpose only. For any medical issues or emergencies, visit a doctor.</p>
                </div>
			</div>
			
			<div class='example profile-detil'>
				<div class='tabsholder2'>
				
				@if(count($SymptomsSpeciality)>0)
                    <div class="doctor-info">
                        <h2>Matches Top Speciality</h2>
						<div class="spaciality-off">
                        	@if(count($SymptomsSpeciality) > 1)<div  slug="{{json_encode($spaciality_slugs)}}" class="spaciality-offblog all view_Alldoc_speciality" info_type="Speciality" search_type="1" s_type="2" data_id="{{json_encode($spaciality_ids)}}">All ({{count($SymptomsSpeciality)}})</div>@endif
							@foreach($SymptomsSpeciality as $spa)
								<div class="spaciality-offblog view_Alldoc_speciality" slug="{{$spa['slug']}}" group_id="{{$spa['group_id']}}" search_type="1" data_id="{{$spa['id']}}" info_type="Speciality" s_type="1" >{{@$spa['specialities']}}</div>
							@endforeach	
                        </div>
                        <div class="doctor-col">
                        </div>
						<button data_id="{{json_encode($spaciality_ids)}}" slug="{{json_encode($spaciality_slugs)}}" info_type="Speciality"  search_type="1" s_type="2"  class="btn-book-appoiontment view_Alldoc_speciality low-top-btn" type="button">Book Appointment</button>	
                    </div>
				@endif
				</div>
          </div>
	</div>
	</div>
	<div class="container-fluid">
		<div class="container"> </div>
    </div>
	<script>
		jQuery(document).on("click", ".view_Alldoc_speciality", function (e) {
			var slug = $(this).attr('slug');
			var city = $("#searchDocInfo").find(".locality_city_slug").val();
			if(!city){
				city = "jaipur";
			}
			var locality = $('#searchDocInfo input[name="locality_slug"]').val();
			var locality_id = $('#searchDocInfo input[name="locality_id"]').val();
			if($(this).attr('s_type') == '1') {
				if(locality_id) {
					url = '{{ route("findDoctorLocalityByType", ":city/:speciality/:locality") }}';
					url = url.replace(':locality', locality);
				}
				else {
					url = '{{ route("findDoctorLocalityByType", ":city/:speciality") }}';
				}
				url = url.replace(':city', city);
				url = url.replace(':speciality', slug);
				window.location = url;
			}
			else{
				if(locality_id) {
					url = '{{ route("findDoctorLocalityByType", ":city/:speciality/:locality?speciality=:slug") }}';
					url = url.replace(':locality', locality);
				}
				else {
					url = '{{ route("findDoctorLocalityByType", ":city/:speciality?speciality=:slug") }}';
				}
				slug = jQuery.parseJSON(slug);
				url = url.replace(':city', city);
				url = url.replace(':speciality',"speciality");
				url = url.replace(':slug',slug);
				window.location = url;
			}
			
		});
		
		
	</script>
@endsection