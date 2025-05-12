<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Health Gennie</title>
<!-- Bootstrap -->
<link href="{{ URL::asset('subcription-asset/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('subcription-asset/style.css') }}" rel="stylesheet">
<link href="{{ URL::asset('subcription-asset/css/scrolling-nav.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<header class="top-navbaar">
  <div class="container">
    <div class="offer-image"> <a href="{{route('index')}}"> <img src="{{ URL::asset('subcription-asset/img/logo.png') }}" /></a>
      <h2><span class="health-text">HEALTH </span><span class="gennie-text">GENNIE</span> FESTIVE OFFER</h2>
      <h4>Digitize your practice with <strong>Health Gennie</strong>.</h4>
    </div>
  </div>
</header>
<div class="container-fluid banner"> <img class="img-top12" src="{{ URL::asset('subcription-asset/img/banner-2.jpg') }}" /> </div>
<div class="container-fluid banner-mob"> <img class="img-top12" src="{{ URL::asset('subcription-asset/img/banner-mob-offer.jpg') }}" /> </div>
<div class="container-fluid Patient_Portal">
  <div class="container">
    <div class="benifit-content">
        <h3>Create your free profile on <span>Health Gennie</span> & get assured gift <img class="img-top12" src="{{ URL::asset('subcription-asset/img/Pen-Drive.png') }}" width="50" /></h3>
        	<div class="button-subscribe mobile"><a href='{{route("addDoc")}}'>Create Your Profile Now</a></div>
        </div> 
    <div class="profile-creation top-row">
    <div class="offer-wrapper">
    <div class="profiling-section  profiling-heading1222">
      <div class="profiling-heading12">
        <div class="top-heading">
          <h2>Health Gennie <span>Platinum</span></h2>
        </div>
		<?php $plan_detail_5 = getSubcriptionPlanid(5); ?>
        <div class="profiling-heading2">
          <div class="profiling-heading2">
            <h4>5 Year Subscription</h4>
            <div class="btn-offer"> Cost <span> {{@$plan_detail_5->plan_price}}</span>&nbsp;&nbsp; Offer Price {{$plan_detail_5->plan_price - $plan_detail_5->discount_price}} + <span class="gst">GST</span></div>
            <span class="flat-offer">FLAT "80%" Off  (Save {{@$plan_detail_5->discount_price}}/-) </span>
            <p class="offer"><strong>Entry in Raffle to Win Exciting prizes</strong></p>
          </div>
        </div>
        <div class="produt_block">
          <div class="produt-mall0">
            <div class="img-no"><img src="{{ URL::asset('subcription-asset/img/laptop-image.png') }}" />
              <p> Value 30,000.00 </p>
            </div>
            <div class="row-content">
              <h2> HP Laptop </h2>
              <p>HP Laptop (4GB/256GB SSD/ Windows 10/1.47 kg)</p>
            </div>     
          </div>
        </div>
        <div class="produt_block">
			  <div class="produt-mall0">
				<div class="img-no"> <img class="img-top" src="{{ URL::asset('subcription-asset/img/Amazon-Echo-1.png') }}" />
				  <p>Value 4,499.00 </p>
				</div>
				<div class="row-content">
				<h2>Amazon Echo Dot </h2>
				<p>Echo Dot (3rd Gen) – New and improved smart speaker</p>
				</div>
			  </div>
			</div>
        <div class="produt_block">
			  <div class="produt-mall0">
				<div class="img-no"> <img class="img-top1" src="{{ URL::asset('subcription-asset/img/earphone-1.png') }}" />
				  <p>Value 1999.00</p>
				</div>
				<div class="row-content">
				  <h2>Bluetooth Earphone</h2>
				  <p>Bluetooth Earphone</p>
				</div>
			  </div>
			</div>
        <div class="produt_block last">
          <div class="produt-mall0">
            <div class="img-no"> <img class="img-top12" src="{{ URL::asset('subcription-asset/img/Pen-Drive.png') }}" />
              <p> Value 499.00 </p>
            </div>
            <div class="row-content">
              <h2> Pen Drive </h2>
              <p>8GB USB  Flash Drive <span class="assured-gift">Assured Gift</span></p>
            </div>
          </div>
        </div>
        
        <div class="button-profile box-wrapper"><a href='{{route("addDoc")}}'>Create Your Profile Now</a></div>
      </div>
    </div>
    <div class="profiling-section cccss_top">
	<?php $plan_detail_3 = getSubcriptionPlanid(3); ?>
      <div class="profiling-heading12">
        <div class="top-heading">
          <h2>Health Gennie <span>Gold</span> </h2>
        </div>
        <div class="profiling-heading2">
          <h4>3 Year Subscription</h4>
          <div class="btn-offer"> Cost <span> {{@$plan_detail_3->plan_price}}</span>&nbsp;&nbsp; Offer Price {{$plan_detail_3->plan_price - $plan_detail_3->discount_price}} + <span class="gst">GST</span></div>
          <span class="flat-offer">FLAT "78%" Off  (Save {{@$plan_detail_3->discount_price}}/-) </span>
        </div>
       
        <div class="produt_block last">
          <div class="produt-mall0">
            <div class="img-no"> <img class="img-top12" src="{{ URL::asset('subcription-asset/img/Pen-Drive.png') }}" />
              <p>Value 499.00 </p>
            </div>
            <div class="row-content">
              <h2>Pen Drive </h2>
              <p>8GB USB  Flash Drive<span class="assured-gift">Assured Gift</span></p>
            </div>
          </div>
          <div class="button-profile box-wrapper"><a href='{{route("addDoc")}}'>Create Your Profile Now</a></div>
        </div>
        
      </div>
    </div>
    <div class="profiling-section  cccss_top">
	<?php $plan_detail_1 = getSubcriptionPlanid(1); ?>
      <div class="profiling-heading12">
        <div class="top-heading">
          <h2>Health Gennie <span>Silver </span></h2>
        </div>
        <div class="profiling-heading2">
          <h4>1 Year Subscription</h4>
          <div class="btn-offer">Cost <span> {{@$plan_detail_1->plan_price}}</span>&nbsp;&nbsp; Offer Price {{$plan_detail_1->plan_price - $plan_detail_1->discount_price}} + <span class="gst">GST</span></div>
          <span class="flat-offer">FLAT "75%" Off  (Save {{@$plan_detail_1->discount_price}}/-) </span>
        </div>
         <div class="produt_block last">
          <div class="produt-mall0">
            <div class="img-no"> <img class="img-top12" src="{{ URL::asset('subcription-asset/img/Pen-Drive.png') }}" />
              <p>Value 499.00 </p>
            </div>
            <div class="row-content">
              <h2> Pen Drive </h2>
              <p>8GB USB  Flash Drive <span class="assured-gift">Assured Gift</span></p>
            </div>
          </div>
          <div class="button-profile box-wrapper"><a href='{{route("addDoc")}}'>Create Your Profile Now</a></div>
        </div>
      </div>
      
    </div>
  <!--  <div class="profiling-section">
          <div class="profiling-heading12">
            <div class="top-heading">
              <h2>Health Gennie <span>Bronze</span></h2>
            </div>
            <div class="profiling-heading2">
              <div class="btn-offer">Profiling ( Free )</div>
            </div>
            <div class="produt_block">
              <div class="produt-mall0">
                <div class="img-no"> <img class="img-top12" src="img/Pen-Drive.png" />
                  <p> 499.00 </p>
                </div>
                <div class="row-content">
                  <h2>Pen Drive </h2>
                  <p>4GB USB  Flash Drive <span class="assured-gift">Assured Gift</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>-->
  </div>
    
    
      
    </div>
    
  </div>
  
</div>
<div class="container-fluid profile-creation bottom-row">
	<div class="container">
    	<div class="">
  <div class="benifit-content">
  	      <h2>Benefits of profile on<br><span>Health Gennie.</span></h2>
        <ol>
          <li>Your online presence. </li>
          <li>Get online appointments at no cost.</li>
          <li>Grow your patient base.</li>
          <li>Your personalized QR code for appointments and reviews.</li>
          <li>Patients can know about you in detail. </li>
          <li>Connect with patients more easily.</li>
          <li>Review, Ratings and Feedback from Patients. </li>
          <li>Patient gets appointments record on Health Gennie app. </li>
          <li>Option to advertise on Health Gennie.</li>
          <li>Easy Health care management for patients digitally at no cost. </li>
        </ol>
        
          


        
      </div>
    <div class="benifit-content">
      <h2>Benefits of Health Gennie <br><span> Software.</span></h2>
      <ol>
      	<li style="list-style:none; font-size:14px;"><strong>All benefits of profile plus...</strong></li>
        <li>No Limit on number of doctors</li>
        <li>Complete Clinic management digitally.</li>
        <li>Patient registration and appointments directly in the software</li>
        <li>Very easy Digital Prescriptions creation and sharing.</li>
        <li>Follow up reminders to patients. Helps in increasing follow-ups.</li>
        <li>Easy Billing and receipt sharing digitally.</li>
        <li>Inventory and lab management</li>
        <li>Top Listing in the search results on Health Gennie doctor search</li>
        <li>Mobile app to manage the clinic from anywhere.</li>
        <li>SMS and email campaign system to broadcast messages to patients.</li>
        <li>Personalized QR code, widgets and website.</li>
        <li>Auto data backups and management. No hassle for managing servers.</li>
        <li>Smart Reports</li>
        <li>Complete Health care management for your patients digitally</li>
      </ol>
    </div>
  </div>
    </div>
</div>
<div class="container-fluid footer-offer">
  <div class="container">
    <ul>
      <li><strong>Website: </strong><a href="https://www.healthgennie.com/" target="_blank">www.healthgennie.com</a></li>
      <li><strong>Email: </strong><a href="mailto:info@healthgennie.com">info@healthgennie.com</a></li>
      <li><strong>Contact: </strong> +91 8302072136, 8302053965</li>
    </ul>
  </div>
</div>
<div class="button-subscribe"><a href='{{route("addDoc")}}'>Create Your Profile Now</a></div>
</body>
</html>