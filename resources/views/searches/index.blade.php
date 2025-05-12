@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content')
<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<div class="collapse navbar-collapse main-nav-mobile container-fluid">
        <div class="container">
            <ul class="nav navbar-nav navbar-right">
            <li class="active apoointment"><a search_type="1" data_id="0" info_type="doctor_all" class="dd @if(isset($_COOKIE['in_mobile']) && $_COOKIE['in_mobile'] == '1') searchDoctorModalDoctor @else view_information @endif" href="javascript:void(0);"><span class="text" style="display:none;">Doctor</span><img src="{{ URL::asset('img/appointment-ico.png') }}" /><div class="nav-section"><h2>Appointment</h2><p>Find Doctors</p></div></a></li>                                  
            <li class="medicine"><a @if(Auth::user() && Auth::user()->profile_status == '1') href="{{route('oneMgOpen')}}" @else href="{{ route('login') }}" @endif ><img src="{{ URL::asset('img/OurStore-icon.png') }}" /><div class="nav-section"><h2>Buy Medicine</h2><p>Medicines</p></div></a></li>
            
            <li class="lab-test"><a href="javascript:void(0);" class="showOrderLabBanner"><img src="{{ URL::asset('img/lab-icon.png') }}" /><div class="nav-section"><h2>Lab Test</h2><p>Book Health Packages</p></div></a></li>
            
			 <li class="blogs-details"><a href="{{ route('blogList') }}"><img src="{{ URL::asset('img/blogs.png') }}" /><div class="nav-section"><h2>Blogs</h2><p>Articals & Activity</p></div></a></li>
            </ul>
        </div>
    </div>

<div class="banner-section"> 
  <div class="banner-content">
      <div class="slider home-slider-ss">
        <ul class="slides">
          <li>
            <img src="{{ URL::asset('img/banner.png') }}" /><!-- random image -->
            <div class="caption left-align">
              <h3>MANAGE YOUR <span>HEALTH</span><br />ALL AT ONE PLACE</h3>
             <h5 class="light grey-text text-lighten-3">Extra savings when you book through health gennie.</h5>
            </div>
          </li>
    
          <li>
            <img src="{{ URL::asset('img/banner.png') }}" /> <!-- random image -->
            <div class="caption left-align">
              <h3>YOUR <span>HEALTH</span> <BR /> AT YOUR FINGERTIPS</h3>
              <h5 class="light grey-text text-lighten-3">Extra savings when you book through health gennie.</h5>
            </div>
          </li>
    
          <li>
           <img src="{{ URL::asset('img/banner.png') }}" /> <!-- random image -->
            <div class="caption left-align">
              <h3>GET BEST DOCTORS <BR /><SPAN>APPOINTMENT</SPAN> WITHIN MINUTE</h3>
             <h5 class="light grey-text text-lighten-3">Extra savings when you book through health gennie.</h5>
            </div>
          </li>
          <li>
           <img src="{{ URL::asset('img/banner.png') }}" /><!-- random image -->
            <div class="caption left-align">
              <h3>SPEND YOUR SAVINGS FROM <SPAN>MEDICINES & LABS</SPAN> ON WHAT YOU LIKE</h3>
              <h5 class="light grey-text text-lighten-3">Extra savings when you book through health gennie.</h5>
            </div>
          </li>
        </ul>
	  </div>
  </div>
</div>

<div class="container-fluid banner-service-section">
  <div class="container">
    <div class="product-section">
      <div class="col-md-12"> <a href="javascript:void(0);"><span class="text" style="display:none;">Doctor</span>
        <div class="product-block">
          <div class="product-image">
		  <div class="navbaar-bottom-block local-area-search">
				<i class="fa fa-map-marker" aria-hidden="true"></i>
				<input id="pac-input" autocomplete="off" type="text" placeholder="city" name="locality" value='{{ Session::get("search_from_locality_name") }}'/>
				<div class="location-div-detect">
				<button type="button" class="btn btn-default search_close_locality" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
				<span class="location-div"><span data-qa-id="current_location" class="btn-detect detect_location"><img src="{{ URL::asset('img/loc-detect.png') }}" /><i class="icon-ic_gps_system"></i><span>Detect</span></span></span></div>
				<div class="dd-wrapper localAreaSearchList" style="display:none;"></div>
			</div>
			<div class="navbaar-bottom-box2">
				<div class="navbaar-bottom-box">
					<input type="search" class="docSearching" placeholder="Search Doctors, Clinics & Hospitals, Symptoms & Speciality etc." value="{{ Session::get('search_from_search_bar') }}" name="data_search" autocomplete="off" />
					<button type="button" class="btn btn-default search_close" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
					<div class="dd-wrapper doctorSearchByInput" style="display:none;"></div>
				</div>
			</div>
			</div>
        </div>
        </a> </div>
    </div>
  </div>
</div>

<div class="container-fluid most-visited-profile">
  <div class="container">
    <div class="FAQ">
      <div class="col-md-12">
        <div class="Find_Doctors_heading heading2">
          <h2>Find doctors in top specialities</h2>
        </div>
          <div class="container">
	        <div class="col-md-3 dr-profile view_information" search_type="1" data_id="49" info_type="Speciality"><span style="display:none" class="text">Dentistry</span>
          <h3>Experience the total <br /><strong>dental solution</strong></h3> <img src="img/dentist.png" />
          <p class="degree">Dentist</p>
</div>
          
          
            <div class="col-md-3 dr-profile view_information" search_type="1" data_id="7" info_type="Speciality"><span style="display:none" class="text">Dermatologist</span>
          <h3>Choose a <br /><strong>trusted name</strong></h3> <img src="img/dermatologist.png" />
          <p class="degree">Dermatologist</p>
          
          </div>
          
          
            <div class="col-md-3 dr-profile view_information" search_type="1" data_id="48" info_type="Speciality"><span style="display:none" class="text">Physiotherapist</span>
          <h3>Dedicated and <strong>premier care</strong><br /> you deserve</h3> <img src="img/physiotherapist.png" />
          <p class="degree">Physiotherapist</p>
          </div>

<div class="col-md-3 dr-profile view_information" search_type="1" data_id="32" info_type="Speciality"><span style="display:none" class="text">Pediatrician</span>
          <h3>Ask the <br /><strong>Pediatrician </strong></h3> <img src="img/Pediatrician.png" />
          <p class="degree">Pediatrician</p>
          </div>
    		</div>
    </div>
  </div>
</div>
</div>
<div class="container bmi-wrapper">
    <div class="row">
        <div class="container" ng-app="bmiApp">
		    <div class="row" data-ng-controller="bmiController">
		        <div class="col-md-12">
					<h3 class="jumbotron text-center">BMI Calculator</h3>
		                 <form role="form"> 
						  <div class="form-group wrapper">
							<div class="radio radioTab active">
							  <label>
								<input type="radio" ng-model="units" value="imperial">
								<span class="label label-danger">Imperial</span> 
							  </label>
							</div> 
							
							<div class="radio radioTab">
							  <label>
								<input type="radio" ng-model="units" value="metric">
								<span class="label label-success">Metric</span>
							  </label>
							</div>
							
						  </div>
						  
						  <div id="metric" ng-show="units == 'imperial'">
							<div class="form-group form-field-bmi">
							  <label for="weight">Weight (lb):</label>
							  <input type="number" ng-model="weight_lb" 
							  id="weight"
							  placeholder="weight in lb" class="form-control">    
							</div>
							
							<div class="form-group form-field-bmi">
							  <label for="weight_foot">Height (foot):</label>
							  <input type="number" ng-model="height_foot" 
							  id="weight_foot"
							  placeholder="height in foot" class="form-control">    
							</div>
							
							<div class="form-group form-field-bmi">
							  <label for="height_inch">Height (inch):</label>
							  <input type="number" ng-model="height_inch" 
							  id="height_inch"
							  placeholder="height in inch" class="form-control">    
							</div>
						  </div>
						  
						  <div id="metric" ng-show="units == 'metric'">
							<div class="form-group form-field-bmi">
							  <label for="weight_kg">Weight (kg):</label>
							  <input type="number" ng-model="weight_kg" 
							  id="weight_kg"
							  placeholder="weight in kg" class="form-control">    
							</div>
							
							<div class="form-group form-field-bmi">
							  <label for="height_cm">Height (cm):</label>
							  <input type="number" ng-model="height_cm" 
							  id="height_cm"
							  placeholder="height in cm" class="form-control">    
							</div>
							
						  </div>
						</form>
						
						 <div class="well text-center calculate_bmi_report_data">
							<!-- Calculated BMI is shown here -->
								<div class="well text-center">
								  <h3 class="text-muted">Calculated BMI</h3>
								  <h2 class="cat_class_change"><span class="cal_bmi label"></span></h2>
								  <h4 class="text-muted text-muted-change"></h4>
								</div>
						</div>
		        </div>
		    </div>
		</div>
    </div>
</div>
<!-- <div class="container-fluid">
  <div class="container">
    <div class="Find_Doctors2">
      <div class="Find_Doctors_section2">
        <div class="col-md-12">
          <div class="Find_Doctors_heading">
            <h2>Steps for better health</h2>
            <p>Free home sample collection for all health checkups</p>
          </div>
          <div class="Find_Doctors_btn">
            <button type="button" class="btn btn-default">VIEW ALL</button>
          </div>
        </div>
      </div>
      <div class="better_health_section">

        <div class="col-md-3">
          <div class="better_health_block"> <img src="{{ URL::asset('img/better_health_image.png') }}" />
            <div class="better_health_top">
              <p>Full Body</p>
              <h2>Checkup</h2>
            </div>
            <div class="better_health_content">
              <h2>Starting from 999</h2>
              <p>Free sample collection at home,</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="better_health_block"> <img src="{{ URL::asset('img/better_health_image-2.png') }}" />
            <div class="better_health_top">
              <p>Connect with a</p>
              <h2>DOCTOR</h2>
            </div>
            <div class="better_health_content">
              <h2>find right doctor</h2>
              <p>Visit, Chat, or Call verified doctor</p>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="better_health_block"> <img src="{{ URL::asset('img/better_health_image-3.png') }}" />
            <div class="better_health_top">
              <p>Order your</p>
              <h2>medicine</h2>
            </div>
            <div class="better_health_content">
              <h2>over 15,000 medicines</h2>
              <p>Free home delivery over 200rs.</p>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="better_health_block"> <img src="{{ URL::asset('img/better_health_image-4.png') }}" />
            <div class="better_health_top">
              <p>healthcare</p>
              <h2>devices</h2>
            </div>
            <div class="better_health_content">
              <h2>HEALTHCARE PRODUCTS</h2>
              <p>Free home delivery over 200rs.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> -->

<div class="container-fluid blog-wrapper">
  <div class="container blog-wrapper-section">
    <div class="blog-content">
    	<h3>Read top blogs from health experts</h3>
        <p>Health blogs that keep you informed about good health practices and achieve your goals.</p>
        <a href="{{route('blogList')}}" ><button type="button" class="btn btn-default">See All Blogs</button></a>
    </div>
    <div class="blog-crasuseal">
	@if(count(getBolgLastUpdated())>0)
		@foreach(getBolgLastUpdated() as $blog)
    	<div class="blog-list">  <a href="{{route('blogInfo',['id'=>base64_encode($blog->id)])}}">
        	<img src="@if(!empty($blog->image))<?php echo url("/")."/public/newsFeedFiles/".$blog->image;?> @else @endif" />
	        <h6>{{@$blog->slug}}</h6>
	        <div class="date-post">@if(!empty($blog->created_at)) {{date('F j , Y',strtotime($blog->created_at))}} @endif</div>
      	    <h2>{{@$blog->title}}</h2>
            <span>Health Gennie</span></a>
		</div>
		@endforeach
    @endif   
	</div>
  </div>
</div>

<!--<div class="container testimonials-wrapper">
    <div class="row">
        <div class="container">
    <div class="row">
        <div class="col-md-12">
<h2>What people are saying?</h2>
            <div id="testimonial-slider" class="owl-carousel">
                <div class="testimonial">
                    <div class="testimonial-profile">
                        <a href="#"><img src="img/img-1.jpg" alt=""></a>
                        <span class="testimonial-date">5 Aug 2015</span>
                    </div>
                    <h3 class="testimonial-title"><a href="#">Web Designer</a></h3>
                    <p class="testimonial-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer consectetur cursus nulla in sagittis. Proin ut.</p>
                </div>
 
                <div class="testimonial">
                    <div class="testimonial-profile">
                        <a href="#"><img src="img/img-2.jpg" alt=""></a>
                        <span class="testimonial-date">7 Aug 2015</span>
                    </div>
                    <h3 class="testimonial-title"><a href="#">Web Developer</a></h3>
                    <p class="testimonial-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer consectetur cursus nulla in sagittis. Proin ut.</p>
                </div>
 
                <div class="testimonial">
                    <div class="testimonial-profile">
                        <a href="#"><img src="img/img-1.jpg" alt=""></a>
                        <span class="testimonial-date">9 Aug 2015</span>
                    </div>
                    <h3 class="testimonial-title"><a href="#">Web Designer</a></h3>
                    <p class="testimonial-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer consectetur cursus nulla in sagittis. Proin ut.</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>-->




<!-- <div class="container-fluid">   
<div class="container">
<div class="FAQ">
<div class="col-md-6">
    <div class="Find_Doctors_heading heading2">
<h2>FAQ</h2>
<p>FREQUENTLY ASKED QUESTIONS</p>
</div>
    <div class="FAQ_section">
<div class="container">
    <div class="panel-group" id="faqAccordion">
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                 <h4 class="panel-title">
                    <a href="" class="ing"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-minus" aria-hidden="true"></i>What is Lorem Ipsum?</a>
              </h4>

            </div>
            <div id="question0" class="panel-collapse collapse in" style="height: 0px;">
                <div class="panel-body">
                     <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        </p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question1">
                 <h4 class="panel-title">
                    <a href="" class="ing"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-minus" aria-hidden="true"></i>Why do we use it?</a>
              </h4>

            </div>
            <div id="question1" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question2">
                 <h4 class="panel-title">
                    <a href="" class="ing"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-minus" aria-hidden="true"></i>Where does it come from?</a>
              </h4>

            </div>
            <div id="question2" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                   <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur,</p>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle collapsed question-toggle" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question3">
                 <h4 class="panel-title">
                    <a href="" class="ing"><i class="fa fa-plus" aria-hidden="true"></i><i class="fa fa-minus" aria-hidden="true"></i>Where can I get some?</a>
              </h4>

            </div>
            <div id="question3" class="panel-collapse collapse" style="height: 0px;">
                <div class="panel-body">
                     <h5><span class="label label-primary">Answer</span></h5>

                    <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. </p>
                </div>
            </div>
        </div>
        
    </div>
    <!--/panel-group--> 
<!--</div>
</div>
</div>
<div class="col-md-6">
<div class="Find_Doctors_heading heading2">
<h2>Quote</h2>
<p>REQUEST FOR QUOTE</p>
</div>
<div class="contact_section_top">
<div class="contact-section">
<input type="text" class="form-control" placeholder="Your Name" />
</div>
<div class="contact-section">
<input type="text" class="form-control" placeholder="Your Name" />
</div>
<div class="contact-section">
<input type="text" class="form-control" placeholder="Your Name" />
</div>
<div class="contact-section">
<input type="text" class="form-control" placeholder="Your Name" />
</div>
<div class="contact-section2">
<textarea class="form-control" rows="5" id="comment"></textarea>    
</div>
<div class="contact-section1">
<button type="button" class="btn btn-default">SUBMIT A QUOTE</button>
</div>
</div>
</div>
</div>
</div>
</div> 
-->

<div class="container-fluid Get_company_top">
  <div class="container">
    <div class="Get">
      <div class="col-md-6">
        <div class="Get_company">
          <div class="Get_company_img"> <img src="{{ URL::asset('img/msg-icon.png') }}" /> </div>
          <div class="Get_company_content">
            <h2>Subscribe for more updates.</h2>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="Get_company_search">
          <input class="email_subcription" type="email" placeholder="Enter Your Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" />
          <button type="button" class="btn btn-default email_subcription_btn">Subscribe</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="{{ URL::asset('js/angular-file.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
<script type="text/javascript">  
$(document).ready(function(){
  $('.radioTab').click(function(){
    $('.radioTab').removeClass('active');
     $(this).addClass('active');
    });
})

	$(document).ready(function(){
	    $("#testimonial-slider").owlCarousel({
	        items:2,
	        itemsDesktop:[1000,2],
	        itemsDesktopSmall:[979,2],
	        itemsTablet:[767,1],
	        pagination: true,
	        autoPlay:true
	    });
	});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>
<script>
	$(document).ready(function(){
		$('.home-slider-ss').slider();
	});
</script>
	
<script>
		var app = angular.module('bmiApp',[]);
		app.controller('bmiController', function ($scope) {
			$scope.units = "imperial";
			$scope.catClass = "default";
			$scope.catTitle = "Unknown";
			$scope.bmi = 0;
			
			$scope.$watch('weight_lb', function (newVal, oldVal, scope) {
			  if(newVal) { 
				var w_kg = (newVal * 0.453592);
				var h_m = (scope.height_foot * 0.3048) + (scope.height_inch * 0.0254);
				
				scope.bmi = (h_m) ? (w_kg/(h_m * h_m)) : 0.0;
			  } else {
				scope.bmi = 0;
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
			$scope.$watch('height_foot', function (newVal, oldVal, scope) {
			  if(newVal) { 
				var w_kg = (scope.weight_lb * 0.453592);
				var h_m = (newVal * 0.3048) + (scope.height_inch * 0.0254);
				scope.bmi = (h_m) ? (w_kg / (h_m * h_m)) : 0.0;
			  } else {
				scope.bmi = 0;
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
			$scope.$watch('height_inch', function (newVal, oldVal, scope) {
			  if(newVal) { 
				var w_kg = (scope.weight_lb * 0.453592);
				var h_m = (scope.height_foot * 0.3048) + (newVal * 0.0254);
				scope.bmi = (h_m) ? (w_kg / (h_m * h_m)) : 0.0;
			  } else {
				scope.bmi = 0;
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
			
			$scope.$watch('weight_kg', function (newVal, oldVal, scope) {
			  if(newVal) { 
				//scope.bmi = newVal;
				scope.bmi = (!!scope.height_cm) ? ((newVal * 10000)/(scope.height_cm * scope.height_cm)) : 0.0;
			  } else {
				scope.bmi = 0;
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
			$scope.$watch('height_cm', function (newVal, oldVal, scope) {
			  if(newVal) { 
				//scope.bmi = newVal;
				scope.bmi = (!!newVal) ? ((scope.weight_kg * 10000) / (newVal * newVal)) : 0.0;
			  } else {
				scope.bmi = 0;
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
			
			$scope.$watch('bmi', function (newVal, oldVal, scope) {
			  if(newVal) { 
				if((newVal <= 24) && (newVal >= 19)) {
				  scope.catClass = "success";
				  scope.catTitle = "Normal";
				} else if((newVal < 19) && (newVal > 0)) {
				  scope.catClass = "danger";
				  scope.catTitle = "Underweight";
				} else if(newVal > 24) {
				  scope.catClass = "danger";
				  scope.catTitle = "Overweight";
				} else {
				  scope.catClass = "default";
				  scope.catTitle = "Unknown";
				}
			  } else {
				scope.catClass = "default";
				scope.catTitle = "Unknown";
			  }
				setBmiCalc($scope.bmi,$scope.catClass,$scope.catTitle);
			});
			
		});
		
		function setBmiCalc(bmi,catClass,catTitle){
			$(".calculate_bmi_report_data").find(".cal_bmi").text(bmi.toFixed(1));
			$(".calculate_bmi_report_data").find(".cal_bmi").addClass('label-'+catClass);
			$(".calculate_bmi_report_data").find(".cal_bmi").attr('title',catTitle);
			$(".calculate_bmi_report_data").find(".text-muted-change").text(catTitle);
		}
		
</script>
<script type="text/javascript">
	  $(document).ready(function() {
		  window.history.pushState(null, "", window.location.href);        
		  window.onpopstate = function() {
			  window.history.pushState(null, "", window.location.href);
		  };
	  });
</script>
@endsection