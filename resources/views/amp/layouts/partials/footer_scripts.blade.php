<div class="is_mobile">
<div class="modal fade" id="searchDoctorModalArea" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="container">
		<div class="location-top">
			<div class="location-top1">
			<h2>Search Location</h2>
			<button data-dismiss="modal" type="button" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
		</div>
		<div class="navbaar-bottom-section">
			<div class="navbaar-bottom-block local-area-search">
				<i class="fa fa-map-marker" aria-hidden="true"></i>
				<input class="form-control pac-input" id="pac-input" autocomplete="off" type="text" placeholder="city" name="locality" value='{{ Session::get("search_from_locality_name") }}'/>
				<div class="location-div-detect">
				<button type="button" class="btn btn-default search_close_locality" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
				<span class="location-div"><span data-qa-id="current_location" class="btn-detect detect_location"><img width="15" height="13" src="{{ URL::asset('img/loc-detect.png') }}" /><i class="icon-ic_gps_system"></i></span></span></div>
				<div class="dd-wrapper localAreaSearchList" style="display:none;"></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="searchDoctorModalDoctor" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="container">
		<div class="location-top">
			<div class="location-top1">
			<h2>Search Doctors</h2>
			<button data-dismiss="modal" type="button" class="btn btn-primary"><i class="fa fa-times" aria-hidden="true"></i></button>
		</div>
		</div>
		<div class="navbaar-bottom-section">
		<div class="navbaar-bottom-box2">
			<div class="navbaar-bottom-box"> 
				<input type="search" class="docSearching" placeholder="Search by Name and Specialities" value="{{ Session::get('search_from_search_bar') }}" name="data_search" autocomplete="off" />
				<button type="button" class="btn btn-default search_close" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
				<div class="dd-wrapper doctorSearchByInput" style="display:none;"></div>
			</div>
		</div>
		</div>
	</div>
</div>
</div>
<link rel="preload" as="style"  href="/css/jqueryUi.css" media="all" type="text/css" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="/css/jqueryUi.css"></noscript>
<script type="text/javascript" src="/js/jquery.cardtabs.js" ></script>
<script type="text/javascript" src="/js/jquery.easing.min.js" async></script>
<script type="text/javascript" src="/js/custom.js" ></script>
<script src="/js/shopifyWidgetJs.js" type="text/javascript"></script>
<script>
if (window.matchMedia("(max-width: 639px)").matches)  {
	$.cookie('in_mobile','1');
	$(".is_mobile").show();
	$(".is_website").hide();
}
else{
	$.cookie('in_mobile','0');
	$(".is_website").show();
	$(".is_mobile").hide();
}
jQuery(document).on("click", ".logoutUser", function (e) {
	document.getElementById('logout-form').submit();
});
/** All Pages For Data**/
@if($controller != "LabController")
var  filtered_div = "filteredDivDesktop";
if (window.matchMedia("(max-width: 639px)").matches)  {
	filtered_div = "filteredDivMobile";
}

	var lat = ""; var lng = ""; var city = ""; var state = ""; var sub_locality = "";
	function initialize() {
		 if($.cookie('is_app_open') == '1') {
			$(".top-navbaar").find(".top-strip-wrtapper").hide();
			$('body').addClass('main-body-w-app');
		 }
		 if($("#profileStatusSess").val() != '' && $("#profileStatusSess").val() != '1'){
			$("#profileAlertModel").modal("show");
		 }
		if($("#session_lat").val()){
			lat = $("#session_lat").val();
		}
		if($("#session_lng").val()){
			lng = $("#session_lng").val();
		}
		if(!lat && !lng){
			getLatLngLocation(0);
		}
		if(!isPaytmTab){
		jQuery(document).on("click", ".closePopup", function (e) {
			$.cookie('enquiryModal','1');
		});
		if(!$.cookie('enquiryModal')){
			// $("#enquiryModal").modal("show");
		}
	    }
	}
	function getLatLngLocation(x) {
		geocoder = new google.maps.Geocoder();
		if (navigator.geolocation) {
			setTimeout(function(){
				if(x == 0){
					navigator.geolocation.getCurrentPosition(successFunctionOn,errorFunction);
				}
				else {
					navigator.geolocation.getCurrentPosition(successFunction,errorFunction);
				}
			}, 100);
		}
	}


		jQuery(document).on("keyup paste click", ".pac-input", function (e) {
			var localAreaSearchListDiv = jQuery('.localAreaSearchList').find('.search-data div').length;
			$(".doctorSearchByInput").hide();
			$(".doctorSearchByInput .search-data").remove();
			$(this).addClass('loder-show-search');
			if(e.originalEvent.detail == 1) {
				if(jQuery(this).val().length < 3) {
						if(localAreaSearchListDiv <= 0 ){
							getCurrentLocality(this,'');
						}
				}
				else{
					if(jQuery(this).val().length >= 3) {
						var currSearchh = jQuery(this).val();
						if(localAreaSearchListDiv <= 0 ){
							getCurrentLocality(this,currSearchh);
						}
						$(".search_close_locality").show();
					}
				}
			}
			else {
				if(jQuery(this).val().length >= 3) {
					var currSearchh = jQuery(this).val();
					getCurrentLocality(this,currSearchh);
					$(".search_close_locality").show();
				}
				else{
					getCurrentLocality(this,'');
				}
			}
		});
		var currentRequestCity ;
		function getCurrentLocality(current,cur_search) {
			var city_name_1 = $("#searchDocInfo").find(".locality_city_area").val();
			var state_name_1 = $("#searchDocInfo").find(".locality_state_area").val();
			if(currentRequestCity) {
				currentRequestCity.abort();
			}
			currentRequestCity = jQuery.ajax({
				type: "POST",
				url: "{!! route('getCurrentLocality') !!}",
				data: {'city_name':city_name_1,'state_name':state_name_1,'locality_area':cur_search},
				beforeSend: function() {
					$(current).css("background","#FFF url('https://healthgennie.com/img/LoaderIcon.gif') no-repeat right");
				},
				success: function(data) {
					$(current).removeClass('loder-show-search');
					$(current).css("background","");
					var rowToAppend = "";
					var city_name_2 = $("#searchDocInfo").find(".locality_city_area").val();
					var city_slug_2 = $("#searchDocInfo").find(".locality_city_slug").val();
					var state_name = $("#searchDocInfo").find(".locality_state_area").val();

					var city_id_1 = $('#searchDocInfo input[name="city_id"]').val();
					var state_id_1 = $('#searchDocInfo input[name="state_id"]').val();

					if(city_name_2 != '') {
						rowToAppend += '<div data_type="2" slug="'+city_slug_2+'" data_id="'+city_id_1+'" data_city_name="'+city_name_2+'" data_state_name="'+state_name+'" state_id="'+state_id_1+'" class="dd select_area_by"><i class="icon-ic_gps_system"><img width="15" height="12" src="{{ URL::asset("img/search-dd.png") }}" /></i><div class="entire_div_search detail"><span class="text">Search In entire '+city_name_2+'</span></div></div>';
					}
					if(data['city'].length > 0) {
						jQuery.each(data['city'],function(k,v) {
								var search_pic = '{{ URL::asset("img/search-dd.png") }}';
								state_name = ''; state_id = '';
								_name = '';
								var pic_width  = 15;
								var pic_height = 12;
								if(v.name != null) {
									_name = v.name;
								}
								if(v.state != null) {
									state_name = v.state.name;
									state_id = v.state.id;
								}
								rowToAppend += '<div slug="'+v.slug+'" data_type="2" data_id="'+v.id+'" data_city_name="'+_name+'" data_state_name="'+state_name+'" state_id="'+state_id+'" class="dd select_area_by"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+_name+'</span><span class="spec">'+state_name+'</span><div class="city-name-div"><span class="city-name-span">City</span></div></div></div>';
						});
					}
					if(data['locality'].length > 0) {
						jQuery.each(data['locality'],function(k,v) {
								var search_pic = '{{ URL::asset("img/search-dd.png") }}';
								city_name = '';
								state_name = '';
								slug = '';
								city_slug = '';
								_name = '';city_id = ''; state_id = '';
								var pic_width  = 15;
								var pic_height = 12;
								if(v.name != null) {
									_name = v.name;
								}
								if(v.city != null) {
									city_name = v.city.name;
									city_id = v.city.id;
									city_slug = v.city.slug;
								}
								if(v.state != null) {
									state_name = v.state.name;
									state_id = v.state.id;
								}
								rowToAppend += '<div slug="'+v.slug+'" city_slug="'+city_slug+'" data_type="1" data_id="'+v.id+'" data_name="'+_name+'" data_city_name="'+city_name+'" data_state_name="'+state_name+'" city_id="'+city_id+'" state_id="'+state_id+'" class="dd select_area_by"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+_name+'</span><span class="spec">'+city_name+'</span><div class="city-name-div"><span class="city-name-span">Locality</span></div></div></div>';
						});
					}

					if(data['state'].length > 0) {
						jQuery.each(data['state'],function(k,v) {
								var search_pic = '{{ URL::asset("img/search-dd.png") }}';
								con_name = '';
								_name = '';
								var pic_width  = 15;
								var pic_height = 12;
								if(v.name != null) {
									_name = v.name;
								}
								if(v.country != null) {
									con_name = v.country.name;
								}
								rowToAppend += '<div slug="'+v.slug+'" data_type="3" data_id="'+v.id+'" data_state_name="'+_name+'" class="dd select_area_by"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+_name+'</span><span class="spec">'+con_name+'</span><div class="city-name-div"><span class="city-name-span">State</span></div></div></div>';
						});
					}
					jQuery('.navbaar-bottom-block').find('.localAreaSearchList').css('display','block');
					jQuery('.navbaar-bottom-block').find('.localAreaSearchList').html('<div class="search-data">'+rowToAppend+'</div>');
				},
				error: function(error) {
					if(error.status == 401 || error.status == 419 || error.status == 500) {
						location.reload();
					}
				}
			});
		}

	jQuery(document).on("click", ".detect_location", function (e) {
		$(this).find("span").text('');
		$(this).css("width","25px");
		$(this).find("img").css('visibility','hidden');
		$(this).css("background","#FFF url('https://healthgennie.com/img/LoaderIcon.gif') no-repeat left");
		var current = this;
		getLatLngLocation(1);
		setTimeout(function() {
			$(current).find("img").css('visibility','visible');
			$(current).css("background","");
			$(".search_close").click();
			openDoctorSearchWithArea();
		},1500);
	});
	function setSessionLocality(lat,lng) {
		var locality = $(".pac-input").val();
		var city_name = $("#searchDocInfo").find(".locality_city_area").val();
		var state_name = $("#searchDocInfo").find(".locality_state_area").val();

		var locality_id = $('#searchDocInfo input[name="locality_id"]').val();
		var city_id = $('#searchDocInfo input[name="city_id"]').val();
		var state_id = $('#searchDocInfo input[name="state_id"]').val();

		var city_slug = $('#searchDocInfo input[name="city_slug"]').val();
		var locality_slug =	$('#searchDocInfo input[name="locality_slug"]').val();

		jQuery.ajax({
			type: "POST",
			url: "{!! route('setSessionLocality') !!}",
			cache: false,
			data: {'lat':lat,'lng':lng,'state_name':state_name,'city_name':city_name,'locality':locality,'locality_id':locality_id,'city_id':city_id,'state_id':state_id,'city_slug':city_slug,'locality_slug':locality_slug},
			beforeSend: function() {
			},
			success: function(data){
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					// location.reload();
				}
			}
		});
	}
	function successFunctionOn(position) {
		lat = position.coords.latitude;
		lng = position.coords.longitude;
		var latLng = new google.maps.LatLng(lat,lng);
		setTimeout(function(){
			if(geocoder) {
				geocoder.geocode({ 'latLng': latLng}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						console.log(results);
						if (results[1]) {
							for (var i = 0; i < results.length; i++) {
								if (results[i].types[0] === "locality") {
									city = results[i].address_components[0].long_name;
									state = results[i].address_components[2].long_name;
									console.log(results[i].address_components[1].short_name);
								}
							}
						}
						var localAreaName = results[3].address_components[1].long_name;
						$(".mobile-search").find(".searchDoctorModalArea .area_name").text(city);
						$(".pac-input").val(city);
						$("#searchDocInfo").find(".locality_city_area").val(city);
						$("#searchDocInfo").find(".locality_state_area").val(state);
						setTimeout(function() {
							setCityStateIds();
						},500);
						setTimeout(function() {
							setSessionLocality(lat,lng);
						},1000);
					}
					else {
						console.log("Geocoding failed: " + status);
					}
				});
			}
		},500);
	}

	function successFunction(position) {
		lat = position.coords.latitude;
		lng = position.coords.longitude;
		var latLng = new google.maps.LatLng(lat,lng);
		setTimeout(function(){
			if(geocoder) {
				geocoder.geocode({ 'latLng': latLng}, function (results, status) { console.log(results);
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[1]) {
							for (var i = 0; i < results.length; i++) {
								if (results[i].types[0] === "locality") {
									city = results[i].address_components[0].long_name;
									state = results[i].address_components[2].long_name;
									console.log(results[i].address_components[1].short_name);
								}
							}
						}
						var localAreaName = results[3].address_components[1].long_name;
						$(".mobile-search").find(".searchDoctorModalArea .area_name").text(localAreaName);
						$(".pac-input").val(localAreaName);
						$("#searchDocInfo").find(".locality_city_area").val(city);
						$("#searchDocInfo").find(".locality_state_area").val(state);
						setTimeout(function() {
							setCityStateIds();
						},500);
						setTimeout(function() {
							setSessionLocality(lat,lng);
						},1000);
					}
					else {
						console.log("Geocoding failed: " + status);
					}
				});
			}
		},500);
	}
	function errorFunction() {
		if(!$("#searchDocInfo").find(".locality_city_area").val()) {
			$(".mobile-search").find(".searchDoctorModalArea .area_name").text("Jaipur");
			$(".pac-input").val("Jaipur");
			$("#searchDocInfo").find(".locality_city_area").val("Jaipur");
			$("#searchDocInfo").find(".locality_state_area").val("Rajasthan");
			setCityStateIds();
		}
	}

	function setCityStateIds() {
		var locality = $(".pac-input").val();
		var city_name = $("#searchDocInfo").find(".locality_city_area").val();
		var state_name = $("#searchDocInfo").find(".locality_state_area").val();
		jQuery.ajax({
			type: "POST",
			url: "{!! route('setCityStateIds') !!}",
			data: {'state_name':state_name,'city_name':city_name,'locality':locality},
			success: function(data){
				if(data) {
					if(data.locality_id){
						$('#searchDocInfo input[name="locality_id"]').val(data.locality_id);
					}
					else{
						$('#searchDocInfo input[name="locality_id"]').val('');
					}
					$('#searchDocInfo input[name="city_id"]').val(data.city_id);
					$('#searchDocInfo input[name="state_id"]').val(data.state_id);
					$('#searchDocInfo input[name="city_slug"]').val(data.city_slug);
					$('#searchDocInfo input[name="locality_slug"]').val(data.locality_slug);
				}
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					// location.reload();
				}
			}
		});
	}

	$('.tabsholder2').cardTabs({theme: 'inset'});
	$('.tabsholder3').cardTabs({theme: 'graygreen'});
	$('.tabsholder4').cardTabs({theme: 'wiki'});

	jQuery(document).ready(function() {
		var modal = document.getElementById("myModal");
		$("#myBtn").onclick = function() {
		  modal.style.display = "block";
		}
		$('.close').onclick = function() {
		  modal.style.display = "none";
		}
		window.onclick = function(event) {
		  if (event.target == modal) {
			modal.style.display = "none";
		  }
		}
	});
	jQuery(document).on("keyup paste click", ".docSearching", function (e) {
		var doctorSearchByInputDiv = jQuery('.doctorSearchByInput').find('.search-data div').length;
		$(".localAreaSearchList").hide();
		$(".localAreaSearchList .search-data").remove();
		if(e.originalEvent.detail == 1) {
			if(jQuery(this).val().length < 3){
				if(doctorSearchByInputDiv <= 0){
					getSpeciality(this);
				}
			}
			else{
				if(jQuery(this).val().length >= 3) {
					var currSearch = jQuery(this).val();
					if(doctorSearchByInputDiv <= 0){
						searchDoctor(currSearch,this);
					}
					$(".search_close").show();
				}
			}
		}
		else {
			if(jQuery(this).val().length >= 3) {
				var currSearch = jQuery(this).val();
				searchDoctor(currSearch,this);
				$(".search_close").show();
			}
			else{
				getSpeciality(this);
			}
		}
	});
    var currentRequest;
	function searchDoctor(currSearch,current) {
		var locality_ids = $('#searchDocInfo input[name="locality_id"]').val();
		var city_ids = $('#searchDocInfo input[name="city_id"]').val();
		var state_ids = $('#searchDocInfo input[name="state_id"]').val();
		var rowToAppend = "";

        if(currentRequest){
            currentRequest.abort();
        }
		 currentRequest = jQuery.ajax({
			type: "POST",
			url: "{!! route('searchDoctorsWeb') !!}",
			data: {'search_key':currSearch,'lat':lat,'lng':lng,'locality_id':locality_ids,'city_id':city_ids,'state_id':state_ids},
			beforeSend: function() {
				$(current).css("background","#FFF url('https://healthgennie.com/img/LoaderIcon.gif') no-repeat right");
			},
			success: function(data){
				$(current).css("background","");
				// if(currSearch.match(/doc/gi) || currSearch.match(/cli/gi) || currSearch.match(/hos/gi)) {
				// 	rowToAppend += '<h3>Type</h3>';
				// 	if(currSearch.match(/doc/gi)) {
				// 		rowToAppend += '<div search_type="1" data_id="0" info_type="doctor_all" class="dd view_information"><i class="icon-ic_gps_system"><img width="15" height="12" src="{{ URL::asset("img/search-dd.png") }}" /></i><div class="detail"><span class="text">Doctor</span><span class="spec">Doctor</span></div></div>';
				// 	}
				// 	if(currSearch.match(/cli/gi)) {
				// 		rowToAppend += '<div search_type="1" data_id="0" info_type="clinic_all" class="dd view_information"><i class="icon-ic_gps_system"><img width="15" height="12" src="{{ URL::asset("img/search-dd.png") }}" /></i><div class="detail"><span class="text">Clinic</span><span class="spec"></span></div></div>';
				// 	}
				// 	if(currSearch.match(/hos/gi)) {
				// 		rowToAppend += '<div search_type="1" data_id="0" info_type="hos_all" class="dd view_information"><i class="icon-ic_gps_system"><img width="15" height="12" src="{{ URL::asset("img/search-dd.png") }}" /></i><div class="detail"><span class="text">Hospital</span><span class="spec"></span></div></div>';
				// 	}
				// }
				if(data['Speciality'].length > 0) {
					rowToAppend += '<h3>Speciality</h3>';
					jQuery.each(data['Speciality'],function(k,v) {
						var pic_width  = 15;
						var pic_height = 12;
						var search_pic = '{{ URL::asset("img/search-dd.png") }}';
						var search_pic_class = 'search-data-icon';
						if(v.speciality_icon != null){
							search_pic = v.speciality_icon;
							pic_width  = 30;
							pic_height  = 30;
							search_pic_class  = '';
						}
						rowToAppend += '<div slug="'+v.slug+'" group_id="'+v.group_id+'" search_type="1" data_id="'+v.id+'" info_type="Speciality" class="dd view_information '+search_pic_class+'"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+v.spaciality+'</span><span class="spec">Speciality</span></div></div>';
					});
				}
				if(data['Doctors'].length > 0) {
					rowToAppend += '<div search_type="1" data_id="0" info_type="doctorsIn" class="dd view_information seeAllInfo"><h3>Doctors</h3><span>See All</span><p class="text" style="display:none;">'+currSearch+'</p></div>';
					jQuery.each(data['Doctors'],function(k,v) {
						var pic_width  = 15;
						var pic_height = 12;
						var locality = '';
						var city = '';
						var city_slug = '';
						var fName = '';
						var lName = '';
						var rating = 0;
						var rating_div = '';
						var search_pic_class = 'search-data-icon';
						var search_pic = '{{ URL::asset("img/search-dd.png") }}';
						if(v.profile_pic != null) {
							search_pic = v.profile_pic;
							pic_width  = 30;
							pic_height  = 30;
							search_pic_class  = '';
						}
						if(v.first_name != null){
							fName = v.first_name;
						}
						if(v.last_name != null){
							lName = v.last_name;
						}
						if(v.doc_rating){
							rating = v.doc_rating;
						}
						if(v.locality_id){
							locality = v.locality_id.name;
						}
						if(v.city_id){
							city_slug = v.city_id.slug;
						}
						if(v.city_id){
							slug = v.city_id.slug;
						}
						if(rating != 0) {
							console.log(rating);
							for(x=1;x<=rating;x++) {
									rating_div +=  '<span class="doc-star-rating fa fa-star checked"></span>';
							}
							if(rating % 1 != 0){
								rating_div += '<span class="doc-star-rating fa fa-star-half-full checked"></span>';
								x++;
							}
							while (x<=5) {
									rating_div += '<span class="doc-star-rating fa fa-star"></span>';
									x++;
							}

						}
						else{
							for(x=1;x<=5;x++) {
									rating_div += '<span class="doc-star-rating fa fa-star"></span>';
							}
						}
						rowToAppend += '<div slug="'+v.doctor_slug.name_slug+'" city_slug="'+city_slug+'" search_type="1" data_id="'+v.id+'" info_type="Doctors" class="dd view_information '+search_pic_class+'"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+fName+' '+lName+'</span><span class="spec">'+v.speciality.name+'</span><div class="star-rating-div">'+rating_div+'</div></div></div>';
					});
				}
				// if(data['Clinic'].length > 0) {
				// 	rowToAppend += '<div data-show="clinic_all" search_type="1" data_id="0" info_type="clinicIn" class="dd view_information seeAllInfo"><h3>Clinic</h3><span>See All</span><p class="text" style="display:none;">'+currSearch+'</p></div>';
				// 	jQuery.each(data['Clinic'],function(k,v) {
				// 			var search_pic = '{{ URL::asset("img/search-dd.png") }}';
				// 			var pic_width  = 15;
				// 			var pic_height = 12;
				// 			var clinic_name = "";
				// 			var locality = '';
				// 			var city = '';
				// 			var rating = 0;
				// 			var rating_div = '';
				// 			var practice_id = '';
				// 			var search_pic_class = 'search-data-icon';
				// 			if(v.clinic_image != null){
				// 				search_pic = v.clinic_image;
				// 				pic_width  = 30;
				// 				pic_height  = 30;
				// 				search_pic_class  = '';
				// 			}
				// 			if(v.clinic_name != null){
				// 				clinic_name = v.clinic_name;
				// 			}

				// 			if(v.practice_id){
				// 				practice_id = v.practice_id;
				// 			}
				// 			if(v.doc_rating){
				// 				rating = v.doc_rating;
				// 			}
				// 			if(v.locality_id){
				// 				locality = v.locality_id.name;
				// 			}
				// 			if(v.city_id){
				// 				city = v.city_id.name;
				// 			}
				// 			if(rating != 0) {
				// 				for(x=1;x<=rating;x++) {
				// 						rating_div +=  '<span class="doc-star-rating fa fa-star checked"></span>';
				// 				}

				// 				if(rating % 1 != 0){
				// 					rating_div += '<span class="doc-star-rating fa fa-star-half-full checked"></span>';
				// 					x++;
				// 				}
				// 				while (x<=5) {
				// 						rating_div += '<span class="doc-star-rating fa fa-star"></span>';
				// 						x++;
				// 				}

				// 			}
				// 			else{
				// 				for(x=1;x<=5;x++) {
				// 						rating_div += '<span class="doc-star-rating fa fa-star"></span>';
				// 				}
				// 			}

				// 			rowToAppend += '<div slug="'+v.doctor_slug.clinic_name_slug+'" search_type="1" data_id="'+practice_id+'" info_type="Clinic" class="dd view_information '+search_pic_class+'"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+clinic_name+'</span><span class="spec">'+locality+' '+city+'</span><div class="star-rating-div">'+rating_div+'</div></div></div>';
				// 	});
				// }

				// if(data['Hospital'].length > 0) {
				// 	rowToAppend += '<div search_type="1" data_id="0" info_type="hospitalIn" class="dd view_information seeAllInfo"><h3>Hospital</h3><span>See All</span><p class="text" style="display:none;">'+currSearch+'</p></div>';
				// 	jQuery.each(data['Hospital'],function(k,v) {
				// 			var search_pic = '{{ URL::asset("img/search-dd.png") }}';
				// 			var pic_width  = 15;
				// 			var pic_height = 12;
				// 			var clinic_name = "";
				// 			var locality = '';
				// 			var city = '';
				// 			var rating = 0;
				// 			var rating_div = '';
				// 			var practice_id = '';
				// 			var search_pic_class = 'search-data-icon';
				// 			if(v.clinic_image != null){
				// 				search_pic = v.clinic_image;
				// 				pic_width  = 30;
				// 				pic_height  = 30;
				// 				search_pic_class  = '';
				// 			}
				// 			if(v.clinic_name != null){
				// 				clinic_name = v.clinic_name;
				// 			}

				// 			if(v.practice_id){
				// 				practice_id = v.practice_id;
				// 			}
				// 			if(v.doc_rating){
				// 				rating = v.doc_rating;
				// 			}
				// 			if(v.locality_id){
				// 				locality = v.locality_id.name;
				// 			}
				// 			if(v.city_id){
				// 				city = v.city_id.name;
				// 			}
				// 			if(rating != 0) {
				// 				for(x=1;x<=rating;x++) {
				// 						rating_div +=  '<span class="doc-star-rating fa fa-star checked"></span>';
				// 				}

				// 				if(rating % 1 != 0){
				// 					rating_div += '<span class="doc-star-rating fa fa-star-half-full checked"></span>';
				// 					x++;
				// 				}
				// 				while (x<=5) {
				// 						rating_div += '<span class="doc-star-rating fa fa-star"></span>';
				// 						x++;
				// 				}

				// 			}
				// 			else{
				// 				for(x=1;x<=5;x++) {
				// 						rating_div += '<span class="doc-star-rating fa fa-star"></span>';
				// 				}
				// 			}

				// 			rowToAppend += '<div slug="'+v.doctor_slug.clinic_name_slug+'" search_type="1" data_id="'+practice_id+'" info_type="Clinic" class="dd view_information '+search_pic_class+'"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+clinic_name+'</span><span class="spec">'+locality+' '+city+'</span><div class="star-rating-div">'+rating_div+'</div></div></div>';
				// 	});
				// }

				// if(data['symptoms'].length > 0) {
				// 	rowToAppend += '<div search_type="1" data_id="0" info_type="symptoms" class="dd view_information seeAllInfo"><h3>symptoms</h3><span>See All</span><p class="text" style="display:none;">'+currSearch+'</p></div>';
				// 	jQuery.each(data['symptoms'],function(k,v) {
				// 		var pic_width  = 15;
				// 		var pic_height = 12;
				// 		var search_pic = '{{ URL::asset("img/search-dd.png") }}';
				// 			rowToAppend += '<div search_type="1" data_id="'+v.id+'" info_type="symptoms" class="dd view_information search-data-icon"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+v.symptom+'</span><span class="spec">Symptoms</span></div></div>';
				// 	});
				// }
				// rowToAppend += '<h3>Search In Symptoms</h3><div search_type="1" data_id="0" info_type="symptoms" class="dd view_information search-data-icon"><i class="icon-ic_gps_system"><img width="15" height="12" src="{{ URL::asset("img/search-dd.png") }}" /></i><div class="detail"><span class="text"> '+currSearch+'</span><span class="spec">Symptoms</span></div></div>';

				if(data['Doctors'].length <= 0 && data['Speciality'].length <= 0){
					var pic_width  = 15;
					var pic_height = 12;
					var search_pic = '{{ URL::asset("img/search-dd.png") }}';
					rowToAppend += '<div class="dd add_doc_claim search-data-icon"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text"><b>'+currSearch+'</b> Not Found...</span></div></div>';
				}
				jQuery('.doctorSearchByInput').css('display','block');
				jQuery('.doctorSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419 || error.status == 500){
					//location.reload();
				}
			}
		});
	}
	function getSpeciality(current) {
		jQuery.ajax({
			type: "POST",
			url: "{!! route('getSpecialityList') !!}",
			beforeSend: function() {
				$(current).css("background","#FFF url('https://healthgennie.com/img/LoaderIcon.gif') no-repeat right");
			},
			success: function(data){
				$(current).css("background","");
				var rowToAppend = "";
				if(data.length > 0) {
					var search_pic = '{{ URL::asset("img/search-dd.png") }}';
					var pic_width  = 15;
					var pic_height = 12;
					jQuery.each(data,function(k,v) {
						if(v.speciality_icon != null){
							search_pic = v.speciality_icon;
							pic_width  = 30;
							pic_height  = 30;
						}
						rowToAppend += '<div slug="'+v.slug+'" group_id="'+v.group_id+'" search_type="1" data_id="'+v.id+'" info_type="Speciality" class="dd view_information"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+v.spaciality+'</span><span class="spec">Speciality</span></div></div>';
					});
				}
				jQuery('.doctorSearchByInput').css('display','block');
				jQuery('.doctorSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					location.reload();
				}
			}
		});
	}

	jQuery(document).on("click", ".add_doc_claim", function (e) {
		location.href='{{route("addDoc")}}';
	});
	jQuery(document).on("click", ".view_information", function (e) {
		$("#searchDocInfo").find("input[name='lat']").val(lat);
		$("#searchDocInfo").find("input[name='lng']").val(lng);
		var data_search = $(this).find('.text').text();
		var data_info_type = $(this).attr('info_type');
		var data_info_id = $(this).attr('data_id');
		$("#searchDocInfo").find("input[name='bySpacialityId']").val("");
		$("#searchDocInfo").find("input[name='data_search']").val(data_search);
		$("#searchDocInfo").find("input[name='info_type']").val(data_info_type);
		$("#searchDocInfo").find("input[name='id']").val(data_info_id);

		var city = $("#searchDocInfo").find(".locality_city_slug").val();
		var locality = $('#searchDocInfo input[name="locality_slug"]').val();
		var locality_id = $('#searchDocInfo input[name="locality_id"]').val();
		if(!city){
			city = "jaipur";
		}
		var url = "";
		if(data_info_type == "Speciality") {
			var slug = $(this).attr('slug');
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
		else if(data_info_type == "doctor_all") {
			if(locality_id) {
				url = '{{ route("findDoctorLocalityByType", ":city/:doctors/:locality") }}';
				url = url.replace(':locality', locality);
				url = url.replace(':doctors', "doctors");
			}
			else {
				url = '{{ route("findAllDoctorsByCity", ":city") }}';
			}
			url = url.replace(':city', city);
			window.location = url;
		}
		else if(data_info_type == "clinic_all") {
			if(locality_id) {
				url = '{{ route("findDoctorLocalityByType", ":city/:clinics/:locality") }}';
				url = url.replace(':locality', locality);
				url = url.replace(':clinics', "clinics");
			}
			else {
				url = '{{ route("findAllClinicsByCity", ":city") }}';
			}
			url = url.replace(':city', city);
			window.location = url;
		}
		else if(data_info_type == "hos_all") {
			if(locality_id) {
				url = '{{ route("findDoctorLocalityByType", ":city/:hospitals/:locality") }}';
				url = url.replace(':locality', locality);
				url = url.replace(':hospitals', "hospitals");
			}
			else {
				url = '{{ route("findAllHospitalsByCity", ":city") }}';
			}
			url = url.replace(':city', city);
			window.location = url;
		}
		else if(data_info_type == "Clinic") {
			var slug = $(this).attr('slug');
			var url = '{{ route("findDoctorLocalityByType", ":city/:clinic/:slug") }}';
			url = url.replace(':slug', slug);
			url = url.replace(':clinic', "clinic");
			url = url.replace(':city', city);
			window.location = url;
		}
		else if(data_info_type == "Hospital") {
			var slug = $(this).attr('slug');
			var url = '{{ route("findDoctorLocalityByType", ":city/:hospital/:slug") }}';
			url = url.replace(':slug', slug);
			url = url.replace(':hospital', "hospital");
			url = url.replace(':city', city);
			window.location = url;
		}
		else if(data_info_type == "Doctors") {
			var slug = $(this).attr('slug');
			var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}'
			var city = $(this).attr('city_slug');
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'doctor');
			url = url.replace(':name', slug);
			window.location = url;
		}
		else if(data_info_type == "doctorsIn") {
			var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}';
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'doctorsIn');
			if(data_search) {
				data_search = data_search.replace(/ /g, "-").toLowerCase();
			}
			url = url.replace(':name', data_search);
			window.location = url;
		}
		else if(data_info_type == "clinicIn") {
			var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}';
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'clinicIn');
			if(data_search) {
				data_search = data_search.replace(/ /g, "-").toLowerCase();
			}
			url = url.replace(':name', data_search);
			window.location = url;
		}
		else if(data_info_type == "hospitalIn") {
			var	url = '{{ route("findDoctorLocalityByType", ":city/:doctor/:name") }}';
			url = url.replace(':city', city);
			url = url.replace(':doctor', 'hospitalIn');
			if(data_search) {
				data_search = data_search.replace(/ /g, "-").toLowerCase();
			}
			url = url.replace(':name', data_search);
			window.location = url;
		}
		else if(data_info_type == "symptoms") {
			$("#searchDocInfo").submit();
		}
	});
	jQuery(document).on("click", ".homePageDoctors", function (e) {
			// var city = $("#searchDocInfo").find(".locality_city_slug").val();
			// if(!city){
				// city = "jaipur";
			// }
			// url = '{{ route("healthgenniePatientApp") }}';
			// url = '{{ route("findDoctorLocalityByType", ":city/:speciality") }}';
			// url = url.replace(':city', city);
			// url = url.replace(':speciality', 'general-physician');
			// url = url+''+'?type=available'
			// window.location = url;
	});

	jQuery(document).on("click", ".homePageDoctorsforPaytm", function (e) {
			var city = $("#searchDocInfo").find(".locality_city_slug").val();
			if(!city){
				city = "jaipur";
			}
			url = '{{ route("findDoctorLocalityByType", ":city/:speciality") }}';
			url = url.replace(':city', city);
			url = url.replace(':speciality', 'general-physician');
			url = url+''+'?type=available'
			window.location = url;
	});
	function getDoctorInfobyId(id){
		jQuery('.loading-all').show();
		var url = '{!! url("/doctor-detail?id='+btoa(id)+'") !!}';
        window.location = url;
	}
	function getHospitalInfobyId(id,data_search){
		jQuery('.loading-all').show();
		var url = '{!! url("/hospital-detail?id='+btoa(id)+'&data_search='+btoa(data_search)+'") !!}';
        window.location = url;
	}
	jQuery(document).on("click", ".search_close", function (e) {
		$("input[name='data_search']").val('');
		$(this).hide();
		$(".docSearching").focus();
		getSpeciality(this);
	});
	jQuery(document).on("click", ".search_close_locality", function (e) {
		$(".pac-input").val('');
		$(this).hide();
		$(".pac-input").focus();
		getCurrentLocality(this,'');
	});
	jQuery(document).on("click", ".select_area_by", function (e) {
		var data_type = $(this).attr('data_type');
		var data_id = $(this).attr('data_id');
		var data_name = '';var city = '';var state = '';var state_id = '';
		var city_id = '';
		if(data_type == "1") {
			var slug = $(this).attr('slug');
			city_id = $(this).attr('city_id');
			state_id = $(this).attr('state_id');
			locality = $(this).attr('data_name');
			city = $(this).attr('data_city_name');
			state = $(this).attr('data_state_name');
			city_slug = $(this).attr('city_slug');
			$(".mobile-search").find(".searchDoctorModalArea .area_name").text(locality);
			$(".pac-input").val(locality);
			$("#searchDocInfo").find(".locality_city_area").val(city);
			$("#searchDocInfo").find(".locality_city_slug").val(city_slug);
			$("#searchDocInfo").find(".locality_slug").val(slug);
			$("#searchDocInfo").find(".locality_state_area").val(state);

			$('#searchDocInfo input[name="locality_id"]').val(data_id);
			$('#searchDocInfo input[name="city_id"]').val(city_id);
			$('#searchDocInfo input[name="state_id"]').val(state_id);
		}
		if(data_type == "2") {
			var slug = $(this).attr('slug');
			state_id = $(this).attr('state_id');
			locality = $(this).attr('data_city_name');
			state = $(this).attr('data_state_name');
			$(".mobile-search").find(".searchDoctorModalArea .area_name").text(locality);
			$(".pac-input").val(locality);
			$("#searchDocInfo").find(".locality_city_area").val(locality);
			$("#searchDocInfo").find(".locality_city_slug").val(slug);
			$("#searchDocInfo").find(".locality_slug").val("");
			$("#searchDocInfo").find(".locality_state_area").val(state);

			$('#searchDocInfo input[name="locality_id"]').val('');
			$('#searchDocInfo input[name="city_id"]').val(data_id);
			$('#searchDocInfo input[name="state_id"]').val(state_id);
		}
		if(data_type == "3") {
			locality = $(this).attr('data_state_name');
			$(".mobile-search").find(".searchDoctorModalArea .area_name").text(locality);
			$(".pac-input").val(locality);
			$("#searchDocInfo").find(".locality_city_area").val(locality);
			$("#searchDocInfo").find(".locality_state_area").val(locality);

			$('#searchDocInfo input[name="locality_id"]').val('');
			$('#searchDocInfo input[name="city_id"]').val('');
			$('#searchDocInfo input[name="state_id"]').val(data_id);
		}
		setTimeout(function(){
			setSessionLocality(lat,lng);
			$(".search_close").click();
			$(".docSearching").focus();
		}, 500);
		openDoctorSearchWithArea();
	});



	$(document).ready(function() {
		$(window).scroll(function() {
			if($("body").find(".top-navbaar").hasClass("fixed")) {
				$("body").addClass('main-body-banner-section');
				jQuery(".container").find(".dd-wrapper").hide();
				jQuery(".container").find(".dd-wrapper .search-data").remove();
			}
			else{
				$("body").removeClass('main-body-banner-section');
			}
		});
	});

	
	jQuery(document).on("click", ".searchDoctorModalDoctor", function (e) {
		if($.cookie('in_mobile') == '1') {
			$('#searchDoctorModalDoctor').modal('show');
			setTimeout(function(){
				$(".search_close").click();
			}, 500);
		}
		else{
			setTimeout(function(){
				$(".search_close").click();
			}, 500);
		}
	});

	jQuery(document).on("click", ".searchDoctorModalArea", function (e) {
		$('#searchDoctorModalArea').modal('show');
		setTimeout(function() {
			$(".search_close_locality").click();
		}, 500);
	});
	function openDoctorSearchWithArea(){
		if($.cookie('in_mobile') == '1') {
			$('#searchDoctorModalArea').modal('hide');
			$('#searchDoctorModalDoctor').modal('show');
			$(".search_close").click();
		}
	}
	@endif

	@if($controller != "HomeController" && $controller != "LabController")
	/** Doctor Pages Data**/
	function showSlot(id, type) {
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "HTML",
		url: "{!! route('doctor.showSlot')!!}",
		data:{'id':id,'type':type},
		success: function(data){
		  jQuery('.loading-all').hide();
		  jQuery("#doctorAppointmentSlot").html(data);
		  jQuery('#doctorAppointmentSlot').modal('show');
		},
		error: function(error){
			jQuery('.loading-all').hide();
			alert("Oops Something goes Wrong.");
		}
	  });
	}

	jQuery(document).on("click", ".tab_class_time_slot", function () { console.log("testt");
		$('.tab_class_time_slot').each(function() {
			$(this).closest("li").removeClass("active");
			// if($(this).attr('id') == 1) {
				// $(this).closest("li").addClass("active");
			// }
		});
		if($(this).attr('id')== '1' ) {
			$(this).closest("li").addClass("active");
			$("#docMorning_time_slot").addClass('in active');
			$("#docAfternoon_time_slot").removeClass('in active');
			$("#docEvening_time_slot").removeClass('in active');
		}
		else if($(this).attr('id')== '2' ) {
			$(this).closest("li").addClass("active");
			$("#docMorning_time_slot").removeClass('in active');
			$("#docEvening_time_slot").removeClass('in active');
			$("#docAfternoon_time_slot").addClass('in active');
		}
		else if($(this).attr('id')== '3' ) {
			$(this).closest("li").addClass("active");
			$("#docMorning_time_slot").removeClass('in active');
			$("#docAfternoon_time_slot").removeClass('in active');
			$("#docEvening_time_slot").addClass('in active');
		}
	});

	function NoShowMap() {
		alert('Address is not available');
	}

	jQuery("."+filtered_div).find(".price-range-submit").on("click", function() {
		$.cookie('by_price','1');
		filteredFormSubmit(this);
	});

	jQuery("."+filtered_div).find(".filter_by_gender_all").on("click", function() {
		 $.cookie('by_gender','1');
		  var all = $(this);
		  jQuery("."+filtered_div).find('input[name="filter_by_gender"]').each(function() {
			   $(this).prop("checked", all.prop("checked"));
		  });
		 filteredFormSubmit(this);
	});

	jQuery("."+filtered_div).find('input[name="filter_by_gender"]').click(function(){
		$.cookie('by_gender','1');
	  jQuery("."+filtered_div).find('.filter_by_gender_all').prop('checked', false);
	  filteredFormSubmit(this);
	});
	jQuery(".left-content").find('.filter_by_consult').change(function() {
		filteredFormSubmit(this);
	});

	jQuery(".left-content").find('input[name="filter_by_locality"]').click(function() {
	  jQuery(".left-content").find('.filter_by_locality_all').prop('checked', false);
	   $.cookie('by_locality','1');
		filteredFormSubmit(this);
	});
	jQuery(".left-content").find(".filter_by_locality_all").on("click", function() {
	  var all = $(this);
		if ($(this).is(":checked")) {
			jQuery(".left-content").find(".filter_by_locality_all").prop("checked", true);
		}
		else {
			jQuery(".left-content").find(".filter_by_locality_all").prop("checked", false);
		}
	  jQuery(".left-content").find('input[name="filter_by_locality"]').each(function() {
		   $(this).prop("checked", all.prop("checked"));
	  });
	  $.cookie('by_locality','1');
	   filteredFormSubmit(this);
	});

	jQuery("."+filtered_div).find('input[name="filter_by_exp"]').click(function() {
	  $.cookie('by_exp','1');
	  if($(this).prop("checked") == true) {
		  jQuery("."+filtered_div).find('input[name="filter_by_exp"]').prop('checked', false);
		  $(this).prop('checked', true);
		  filteredFormSubmit(this);
	  }
	  else{
		  filteredFormSubmit(this);
	  }
	});

	jQuery("."+filtered_div).find('input[name="filter_by_rating"]').click(function() {
	  if($(this).prop("checked") == true) {
		  jQuery("."+filtered_div).find('input[name="filter_by_rating"]').prop('checked', false);
		  $(this).prop('checked', true);
		  filteredFormSubmit(this);
	  }
	  else{
		  filteredFormSubmit(this);
	  }
	});

	function getGenderFilter() {
	  var genderObj = {};
	  jQuery("."+filtered_div).find("input[name='filter_by_gender']").each(function(index, elem) {
		  if($(this).prop("checked") == true){
			var key = $(elem).val();
			genderObj[key] = 1;
		   }else{
			 var key = $(elem).val();
			 genderObj[key] = 0;
		  }
	  });
	  return genderObj;
	}
	function getLocalityFilter() {
		var localityObj = [];
			jQuery("."+filtered_div).find("input[name='filter_by_locality']").each(function(index, elem) {
				if($(this).prop("checked") == true) {
					var valuee = $(elem).val();
					localityObj.push(valuee);
				}
			});
	  return localityObj;
	}
	function getExpFilter() {
	  var expObj = "";
	  jQuery("."+filtered_div).find("input[name='filter_by_exp']").each(function(index, elem) {
		if($(this).prop("checked") == true) {
			expObj = $(elem).val();
		}
	  });
	  return expObj;
	}
	function getRatingFilter() {
	  var rateObj = "";
	  jQuery("."+filtered_div).find("input[name='filter_by_rating']").each(function(index, elem) {
		if($(this).prop("checked") == true) {
			rateObj = $(elem).val();
		}
	  });
	  return rateObj;
	}

	$('.DataFilterBySorting').change(function() {
		filteredFormSubmit(this);
	});

	function replaceUrlParam(url, paramName, paramValue){
		if(paramValue == null || paramValue == "")
			return url
			.replace(new RegExp('[?&]' + paramValue + '=[^&#]*(#.*)?$'), '$1')
			.replace(new RegExp('([?&])' + paramValue + '=[^&]*&'), '$1');
		url = url.replace(/\?$/,'');
		var pattern = new RegExp('\\b('+paramName+'=).*?(&|$)')
		if(url.search(pattern)>=0){
			return url.replace(pattern,'$1' + paramValue + '$2');
		}
		return url + (url.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue
	}
	function delete_cookie(name) {
		document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	}
	function filteredFormSubmit(current) {
		var url = window.location.href;
		if($.cookie('by_gender') == '1') {
			var genderObjData =  getGenderFilter();
		}
		var sort_val = $('.DataFilterBySorting').find(":selected").val();
		// $('#searchDocInfo input[name="sort_by"]').val(sort_val);
		// $('#searchDocInfo input[name="search_type"]').val('1');
		if($("#searchDocInfo").find("input[name='data_search']").val() == ""){
			$("#searchDocInfo").find("input[name='data_search']").val($("#search_data_by_search_id").val());
		}
		if ($(current).attr('class') == 'filter_by_consult') {
			var conType = $(current).closest('.ConsultationFilter').find(".filter_by_consult:checked").val();
				if (url.indexOf('ctype') == -1) {
					if (url.indexOf('?') > -1) {
						url += '&ctype='+btoa(conType);
					}
					else {
						url += '?ctype='+btoa(conType);
					}
				}
				else{
					url = replaceUrlParam(url,'ctype',btoa(conType));
				}
			url = url;
		}

		if ($(current).attr('name') == 'filter_by_locality' || $(current).attr('class') == 'filter_by_locality_all') {
			//var localityObjData = getLocalityFilter();
			//var url = window.location.href;
			console.log(getLocalityFilter());
			if(getLocalityFilter().length > 0 ) {
				var expDate = new Date();
				expDate.setTime(expDate.getTime() + (5 * 60 * 1000));
				if ($.cookie('locality') != null ) {
					delete_cookie('locality');
				}
				$.cookie("locality", JSON.stringify(getLocalityFilter()), { path: '/', expires: expDate });
				if (url.indexOf('locality') == -1) {
					if (url.indexOf('?') > -1) {
					   url += '&locality=true';
					}
					else {
					   url += '?locality=true';
					}
				}
				url = url;
			}
			else{
				if ($.cookie('locality') != null ) {
					delete_cookie('locality');
				}
			}
		}
		if ($(current).hasClass("price-range-search") == true) {
			var min_price = $(current).closest('.price-range-block').find('.min_price').val();
			var max_price = $(current).closest('.price-range-block').find('.max_price').val();
			if(min_price && max_price){
				if (url.indexOf('min') == -1 && url.indexOf('max') == -1) {
					if (url.indexOf('?') > -1) {
					   url += '&fmin='+btoa(min_price)+'&fmax='+btoa(max_price);
					}
					else {
					   url += '?fmin='+btoa(min_price)+'&fmax='+btoa(max_price);
					}
				}
				else{
					url = replaceUrlParam(url,'fmin',btoa(min_price));
					url = replaceUrlParam(url,'fmax',btoa(max_price));
				}
				url = url;
			}
		}
		if ($(current).attr('name') == 'filter_by_exp') {
			var expObjData =  getExpFilter();
			console.log(expObjData);
			if(expObjData){
				if(url.indexOf('exp') == -1){
					if (url.indexOf('?') > -1) {
					   url += '&dexp='+btoa(expObjData);
					}
					else {
					   url += '?dexp='+btoa(expObjData);
					}
				}
				else{
					url = replaceUrlParam(url,'dexp',btoa(expObjData));
				}
			}
		}
		setTimeout(function() {
			jQuery('.loading-all').show();
			window.location.href = url;
			console.log(url);
		//	$("#searchDocInfo").submit();
		}, 300);
	}


	jQuery(document).on("keyup", ".searchLocalityFromList", function (e) {
		var parent_div = this;
		var c_val = $(parent_div).val();
		if(c_val.length > 1){
			$(parent_div).closest(".block-filter").find(".find-locality-div .chck-container").hide();
			$(parent_div).closest(".block-filter").find(".chck-container").each(function(e) {
				if($.trim($(this).text()) == "All Places"){
					$(this).hide();
				}
			});
		}
		else{
			$(parent_div).closest(".block-filter").find(".find-locality-div .chck-container").show();
			$(parent_div).closest(".block-filter").find(".chck-container").each(function(e) {
				if($.trim($(this).text()) == "All Places"){
					$(this).show();
				}
			});
		}
		$(parent_div).closest(".block-filter").find(".localty-not-found").hide();
		var flag = 0;
		$(parent_div).closest(".block-filter").find(".find-locality-div .chck-container").each(function(e) {
			var txt = $(this).text();
			if(txt.toLowerCase().indexOf($.trim(c_val.toLowerCase())) != -1){
				flag = 1;
				$(this).show();
			}
		});
		if(!flag) {
		flag = 1;
		$(parent_div).closest(".block-filter").find(".localty-not-found").show();
		}
	});

	$(document).ready( function() {
		$(".call_now").click(function() {
			$(".call_now_section").slideToggle();
		});
	});
	/** End Doctor Data**/
	@endif
	@if($controller == "LabController")
	/** Lab Data**/
	jQuery(document).on("keyup paste click", ".labSearching", function (e) {
		var labSearchByInputDiv = jQuery('.labSearchByInput').find('.search-data div').length;
		$(".localAreaSearchList").hide();
		$(".localAreaSearchList .search-data").remove();
		if(e.originalEvent.detail == 1) {
			if(jQuery(this).val().length < 2){
				if(labSearchByInputDiv <= 0){
					getFavLabs(this);
				}
			}
			else{
				if(jQuery(this).val().length >= 2) {
					var currSearch = jQuery(this).val();
					if(labSearchByInputDiv <= 0){
						searchLab(currSearch,this);
					}
					$(".search_close_lab").show();
				}
			}
		}
		else {
			if(jQuery(this).val().length >= 2) {
				var currSearch = jQuery(this).val();
				searchLab(currSearch,this);
				$(".search_close_lab").show();
			}
			else{
				getFavLabs(this);
			}
		}
	});
	var currentFavLabRequest ;
	function getFavLabs(current) {
		if(currentFavLabRequest){
			currentFavLabRequest.abort();
		}
		currentFavLabRequest = jQuery.ajax({
			type: "POST",
			url: "{!! route('getFavLabList') !!}",
			beforeSend: function() {
			},
			success: function(data){
				var rowToAppend = "";
				if(data.length > 0) {
					var search_pic = '{{ URL::asset("img/thayrocare-logo-dashboard.jpg") }}';
					var pic_width  = 15;
					var pic_height = 12;
					jQuery.each(data,function(k,v) {
						rowToAppend += '<div data_id="'+v.name+'" info_type="PROFILE" class="dd view_lab_info"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+v.name+'</span></div></div>';
					});
				}
				jQuery('.labSearchByInput').css('display','block');
				jQuery('.labSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					location.reload();
				}
			}
		});
	}
	var currentLabRequest ;
	function searchLab(currSearch,current) {
		if(currentLabRequest){
			currentLabRequest.abort();
		}
		currentLabRequest = jQuery.ajax({
			type: "POST",
			url: "{!! route('searchLabWeb') !!}",
			data: {'search_key':currSearch},
			beforeSend: function() {
			},
			success: function(data){
				var rowToAppend = "";
				if(data['TESTS'].length > 0) {
					jQuery.each(data['TESTS'],function(k,v) {
						var pic_width  = 15;
						var pic_height = 12;
						var search_pic_class = 'search-data-icon';
						var search_pic = '{{ URL::asset("img/thayrocare-logo-dashboard.jpg") }}';
						var name = v.name;
						if(v.name == "HEMOGRAM - 6 PART (DIFF)"){
							name = v.name+"(CBC)";
						}
						rowToAppend += '<div data_id="'+v.name+'" info_type="TESTS" class="dd view_lab_info"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+name+'</span></div></div>';
					});
				}
				if(data['PROFILE'].length > 0) {
					jQuery.each(data['PROFILE'],function(k,v) {
						var pic_width  = 15;
						var pic_height = 12;
						var search_pic_class = 'search-data-icon';
						var search_pic = '{{ URL::asset("img/thayrocare-logo-dashboard.jpg") }}';
						var name = v.name;
						if(v.name == "HEMOGRAM - 6 PART (DIFF)"){
							name = v.name+"(CBC)";
						}
						rowToAppend += '<div data_id="'+v.name+'" info_type="PROFILE" class="dd view_lab_info"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+name+'</span></div></div>';
					});
				}
				if(data['OFFER'].length > 0) {
					jQuery.each(data['OFFER'],function(k,v) {
						var pic_width  = 15;
						var pic_height = 12;
						var search_pic_class = 'search-data-icon';
						var search_pic = '{{ URL::asset("img/thayrocare-logo-dashboard.jpg") }}';
						var name = v.name;
						if(v.name == "HEMOGRAM - 6 PART (DIFF)"){
							name = v.name+"(CBC)";
						}
						rowToAppend += '<div data_id="'+v.name+'" info_type="OFFER" class="dd view_lab_info"><i class="icon-ic_gps_system"><img width="'+pic_width+'" height="'+pic_height+'" src="'+search_pic+'" /></i><div class="detail"><span class="text">'+name+'</span></div></div>';
					});
				}
				jQuery('.labSearchByInput').css('display','block');
				jQuery('.labSearchByInput').html('<div class="search-data">'+rowToAppend+'</div>');
			},
			error: function(error) {
				if(error.status == 401 || error.status == 419){
					location.reload();
				}
			}
		});
	}

	jQuery(document).on("click", ".search_close_lab", function (e) {
		$(".labSearching").val('');
		$(this).hide();
		$(".labSearching").focus();
		getFavLabs(this);
	});
	jQuery(document).on("click", ".view_lab_info", function (e) {
		var info_type = $(this).attr('info_type');
		var data_id = $(this).attr('data_id');
		var url = '{!! url("/lab-details/'+btoa(data_id)+'/'+btoa(info_type)+'") !!}';
		console.log(url);
		window.location = url;
	});
	jQuery(document).on("click", ".open-tab-mobile-lab", function (e) {
		$(".main-nav-mobile-lab").toggle();
	});
	jQuery(document).on("click", ".searchLabModalDoctor", function (e) {
		$('#searchLabModalDoctor').modal('show');
		setTimeout(function(){
			$(".search_close_lab").click();
		}, 500);
	});
	/** End Lab **/
	@endif
	$(document.body).click( function(e) {
		var target = $(e.target);
		if(!target.is(".navbaar-bottom-section .pac-input") && !target.is(".navbaar-bottom-section .docSearching")) {
			jQuery(this).find(".dd-wrapper").hide();
			jQuery(this).find(".dd-wrapper .search-data").remove();
			$(".search_close").hide();
			$(".search_close_locality").hide();
		}
	});

	function DeleteMiniCart(product_array, action_type,replace_itm = null) {
		var returnValue;
		  jQuery.ajax({
		  type: "POST",
		  async: false,
		  dataType: 'json',
		  url: "{!! route('CartUpdate') !!}",
		  data: {'product_array':product_array,'action_type':action_type,"replace_itm":replace_itm},
			success: function(data) {
			  returnValue =  data;
			}
		  });
	   return returnValue;
	}
	jQuery(document).on("click", ".deleteFromMiniCart", function () {
			jQuery('.loading-all').show();
			$(this).parent().slideUp("slow");
			var current = this;
			var selectPackage = [];
			var cartTotal = jQuery('#cartTotal').text();
			var pname = $(this).attr('Pname');
			var pcode = $(this).attr('Pcode');
			selectPackage.push({pname:pname,pcode:pcode});
			DeleteMiniCart(selectPackage, 'remove_item');
			setTimeout(function(){ $(current).parent().remove();
				if ($("#miniCartList .list").length == '0') {
					$("#miniCart").css("display", "none");
					$(".cart-wrapper").removeClass('cart-open');
					$(".cart-wrapper").attr('title','Cart is Empty!');
				}
				location.reload();
				//jQuery('.loading-all').hide();
			}, 300);
			jQuery('#cartTotal').text(parseFloat(cartTotal)-1);
      jQuery('.totalTest').text(parseFloat(cartTotal)-1);
	});
	jQuery(document).on("click", ".email_subcription_btn", function () {
		var email = $(".Get_company_search").find(".email_subcription").val();
		if(email != "") {
			if (ValidateEmail(email)) {
				$(".Get_company_search").find(".email_subcription").css('border','1px solid red');
				jQuery('.loading-all').show();
				jQuery.ajax({
					type: "POST",
					dataType : "JSON",
					url: "{!! route('subcribedEmail')!!}",
					data:{'email':email},
					success: function(data) {
						jQuery('.loading-all').hide();
						if(data == 1) {
							$(".Get_company_search").html('<p class="form-control bg-success">Thanks For your Subcription..</p>');
							$(".Get_company_search").slideUp("slow");
							$(".Get_company_search").slideDown("slow");
						}
						else {
							$(".EmailSubcriptionMsg").text(data[0]);
						}
					},
					error: function(error) {
						if(error.status == 401 || error.status == 419) {
							location.reload();
						}
						jQuery('.loading-all').hide();
					}
				});
			}
			else {
				$(".Get_company_search").find(".email_subcription").css('border','1px solid red');
			}
		}
		else{
			$(".Get_company_search").find(".email_subcription").css('border','1px solid red');
		}
	});
	function ValidateEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	};
	function FeedbackFormForLatestAppt(id, appointment_id) {
		jQuery('.loading-all').show();
		jQuery.ajax({
		type: "POST",
		dataType : "HTML",
		url: "{!! route('patients.showFeedbackForm')!!}",
		data:{'id':id,'appointment_id':appointment_id},
		success: function(data) {
		  jQuery('.loading-all').hide();
		  jQuery("#patientFeedBackForm").html(data);
		  jQuery('#patientFeedBackForm').modal('show');
		},
		error: function(error) {
			jQuery('.loading-all').hide();
			alert('Oops Something goes Wrong.');
		}
	  });
}
	$(document).ready(function(){
		if($("#userLoginStatus").val()) {
		  var latestApptFeedback = $('input[name="latestApptFeedback"]').val();
		  var closeFeedbackModal =  '{{Session::get("closeFeedbackModal")}}';
		  if (latestApptFeedback != "" && closeFeedbackModal == "") {
		  	latestApptFeedback =	JSON.parse(latestApptFeedback);
		  	setTimeout(function(){
		  		FeedbackFormForLatestAppt(latestApptFeedback.doc_id, latestApptFeedback.appointment_id);
		  }, 15000);

		  }
		}
	});

</script>

@if($controller != "HomeController" && $controller != "LabController")
<script type="text/javascript" src="/js/price_range_script.js" ></script>
<link rel="stylesheet" rel="preload" as="style"  href="/css/price_range_style.css" media="all" type="text/css" defer async />
@endif
<div id="modalpaytmPermissions" class="modal Granting-consent"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Granting consent is mandatory for proceeding with the flow.</p>
                <div class="modal-body-btn">
                <button class="btn fist-btn" onclick="closemini()">Exit</button><button class="btn last-btn" onclick="trymini()">Retry</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modalpaytmPermissionsForLogin" class="modal Granting-consent"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Login is mandatory to proceed.</p>
                <div class="modal-body-btn">
                <button class="btn fist-btn" onclick="closemini()">Exit</button><button class="btn last-btn" onclick="tryminiForLogin()">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
var options = {
"enabled":true,
	"chatButtonSetting":{
	  "backgroundColor":"#4dc247",
	  "ctaText":"",
	  "borderRadius":"25",
	  "marginLeft":"0",
	  "marginBottom":"20",
	  "marginRight":"15",
	  "position":"right"
	},
	"brandSetting":{
	  "brandName":"Health Gennie",
	  "brandSubTitle":"Typically replies within a day",
	  "brandImg":"https://www.healthgennie.com/img/wpp_lgimg.jpg",
	  "welcomeText":"Hi there!\nHow can I help you?",
	  "messageText":"",
	  "backgroundColor":"#0a5f54",
	  "ctaText":"Start Chat",
	  "borderRadius":"25",
	  "autoShow":false,
	  "phoneNumber":"918690006254"
	}
};
if(!isPaytmTab){
CreateWhatsappChatWidget(options);
}
});
</script>