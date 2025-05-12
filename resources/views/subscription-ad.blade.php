@extends('layouts.Masters.Master')
@section('title', 'Subscription | Health Gennie')
@section('description', "Have questions about Health Gennie's products, support services, or anything else? Let us know, we would be happy to answer your questions & set up a meeting with you.")	
@section('content') 
<link rel="preload" as="style" href="css/assets/bootstrap/css/bootstrap.min.css" media="all" type="text/css" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/assets/bootstrap/css/bootstrap.min.css"></noscript>
<link rel="preload" as="style" href="css/homestyle.css" type="text/css" media="all" onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/homestyle.css"></noscript>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WCSD8NM');</script>
<!-- End Google Tag Manager -->
<link rel="preload" as="style" href="css/common.css?v=12" type="text/css" media="all"  onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/common.css?v=12"></noscript>
<link rel="preload" as="style" href="css/fonts/font-awesome.min.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/fonts/font-awesome.min.css"></noscript>
<link rel="preload" as="style" href="css/fonts/font_google.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/fonts/font_google.css"></noscript>
<!--
<link href="https://fonts.googleapis.com/css2?family=Yantramanav:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="preload" as="style" href="css/fonts/font_raleway.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/fonts/font_raleway.css"></noscript>
<link rel="preload" as="style" href="css/fonts/font_kerala.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/fonts/font_kerala.css"></noscript>
-->
<link rel="preload" as="style" href="css/fonts/font_family.css" media="all" defer async onload="this.onload=null;this.rel='stylesheet'"/>
<noscript><link rel="stylesheet" href="css/fonts/font_family.css"></noscript>
<script src="css/assets/plugins/jQuery/jquery-1.12.4.min.js" type="text/javascript" rel="preload"></script>
<script src="js/bootstrap.min.js" type="text/javascript" async ></script>
<script src="js/jquery.validate.js" type="text/javascript" ></script>
<script src="js/cookieMin.js" type="text/javascript" ></script>


<div class="container"> @if (Session::has('message'))
  <div class="alert alert-info sessionMsg">{{ Session::get('message') }}</div>
  @endif
  <div class="container-inner contact-wrapper contact-us">
  	<div class="BookYourLabNewImage col-lg-8">
        <div class="contact-detail">
          <img src="img/LabOfferNew.jpg" alt="">
        </div>
      </div>
      <div class="col-lg-4">
      	<div class="BookYourLabNew">
    <h2>Book Your Lab</h2>
    <p>Our support team will catch back to you soon!!!</p>
    
    {!! Form::open(array('route' => 'contactUs','method' => 'POST', 'id' => 'contact-form')) !!}
    
        <div class="form-fields">
          <label>Your Name<i class="required_star">*</i></label>
          <input type="text" value="" name="name" placeholder="Enter Full Name" />
          <span class="help-block">
			@if($errors->has('interest_in'))
			<label for="interest_in" generated="true" class="error">
				 {{ $errors->first('interest_in') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>Phone Number<i class="required_star">*</i></label>
          <input type="text" value="" name="mobile" class="NumericFeild" placeholder="Your Phone Number" />
          <span class="help-block">
			@if($errors->has('mobile'))
			<label for="mobile" generated="true" class="error">
				 {{ $errors->first('mobile') }}
			</label>
			@endif
		  </span>
        </div>
        <div class="form-fields">
          <label>City<i class="required_star">*</i></label>
          <input type="text" value="" name="city" placeholder="Your City" />
          <span class="help-block">
			@if($errors->has('city'))
			<label for="email" generated="true" class="error">
				 {{ $errors->first('city') }}
			</label>
			@endif
		  </span>
        </div>
        
        
       <div class="form-fieldsButton">
       	<button type="button" class="btn btn-default email_subcription_btn">Submit</button>
       </div>

      {!! Form::close() !!}
       
    </div>
    </div>
  </div>
  
</div>
<div class="container-fluid">
  <div class="container"> </div>
</div>
<section class="cta-section ">
	<h2 class="jumbotron text-center JumbotronTextCenter">
<span class="subtitle">HEALTH GENNIE</span>Our Satisfied Customers </h2>
<div class="cta position-relative">
	<div class="container">
		
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="counter-stat">
						<i class="fa fa-smile-o" aria-hidden="true"></i>
						<span class="h3 counter" data-count="200000">515525 </span><strong> +</strong>
						<p>Happy Customers</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="counter-stat">
						<i class="fa fa-user-md" aria-hidden="true"></i>
						<span class="h3 counter" data-count="30000">30000 </span><strong>+</strong> 
						<p>Doctors</p>
					</div>
				</div>
				
				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="counter-stat">
						<i class="fa fa-download" aria-hidden="true"></i>
						<span class="h3 counter" data-count="400000">180236 </span><strong> +</strong> 
						<p>App Downloads</p>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>
<section class="cta-section no-bg">
<div class="blog-content"><h3><span class="subtitle">Your Passion is our Satisfaction</span>Our Clients</h3> <p>We believe in turning clients into family and your Passion is our Satisfaction.</p><a href="{{route('partners')}}"><button type="button" class="btn btn-default SeeAllClients">See All Clients</button></a></div>
<div class="container">
   <div class="customer-logos slider">
      <div class="slide"><img src="img/clogo-1.png"></div>
      <div class="slide"><img src="img/clogo-2.png"></div>
      <div class="slide"><img src="img/clogo-3.png"></div>
      <div class="slide"><img src="img/clogo-4.png"></div>
      <div class="slide"><img src="img/clogo-5.png"></div>
      <div class="slide"><img src="img/clogo-6.png"></div>
      <div class="slide"><img src="img/clogo-7.png"></div>
      <div class="slide"><img src="img/clogo-8.png"></div>
      <div class="slide"><img src="img/clogo-9.png"></div>
   </div>
</div>


</section>
<script>


	jQuery("#contact-form").validate({
		rules: {
			name:  {required:true,minlength:2,maxlength:30},
			mobile:{required:true,minlength:10,maxlength:10,number: true},
			email: {required: true,email: true,maxlength:30},
			message: {required: true,maxlength:255},
			subject: {required: true,maxlength:50},
			interest_in: {required:true},
		},
		messages: {
		},
		errorPlacement: function(error, element) {
			 error.appendTo(element.next());
		},ignore: ":hidden",
		submitHandler: function(form) {
			var response = grecaptcha.getResponse();
			if(response.length == 0) {
				alert("Robot verification failed, please try again.");
			}
			else {
				jQuery('.loading-all').show();
				form.submit();
			}
		}
	});

	$('.counter').each(function () {
	$(this).prop('Counter',0).animate({
		Counter: $(this).text()
	}, {
		duration: 3000,
		easing: 'swing',
		step: function (now) {
			$(this).text(Math.ceil(now));
		}
	});
});

	</script> 
@endsection 