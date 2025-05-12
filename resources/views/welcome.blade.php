@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content') 
<div class="banner-section">
<img src="{{ URL::asset('img/banner.png') }}" />
<div class="banner-content">
<div class="container">
<div class="banner-content-section">
<h2>Medical <br><span>Excellence</span> Everyday</h2>
<p>We provides always our best services for our clients and  always  try to achieve our client's trust and satisfaction.</p>
</div>
<!-- <div class="li-section">
<ul>
    <li><a href="#"><img src="{{ URL::asset('img/li-icon.png') }}" /></a></li>
    <li><a href="#"><img src="{{ URL::asset('img/li-icon2.png') }}" /></a></li>
    <li><a href="#"><img src="{{ URL::asset('img/li-icon3.png') }}" /></a></li>
</ul>
</div>-->
</div>
</div>
</div> 
<div class="container-fluid">   
<div class="container">
<div class="product-section">
<div class="col-md-4">
<a href="#">
<div class="product-block">
<div class="product-image">
<img src="{{ URL::asset('img/product-image.png') }}" />
</div>
</div>
</a>
</div>
<div class="col-md-4">
<a href="#">
<div class="product-block product-block2">
<div class="product-block3">
<div class="product-image">
<img src="{{ URL::asset('img/product-image-1.png') }}" />
</div>
</div>
</div>
</a>
</div>
<div class="col-md-4">
<a href="#">
<div class="product-block product-block4">
<div class="product-image">
<img src="{{ URL::asset('img/product-image-2.png') }}" />
</div>
</div>
</a>
</div>
</div>
</div>

</div>
<div class="container-fluid Find_Doctors">   
<div class="container">

<div class="Find_Doctors_section">
<div class="col-md-12">
<div class="Find_Doctors_heading">
<h2>Find Doctors</h2>
<p>How we can help you</p>
</div>
<div class="Find_Doctors_btn">
<button type="button" class="btn btn-default">VIEW ALL</button> 
</div>
</div>
<div class="Find_Doctors_block">
<div class="col-md-4">
<a href="#">
<div class="veiw-block">
<div class="product-image">
<img src="{{ URL::asset('img/veiw-image.png') }}" />
</div>
<div class="veiw-content">
<h2>Dentist</h2>
<p>Teething Troubles? Schedule a dental checkup.</p>
</div>
<div class="veiw-icon">
<img src="{{ URL::asset('img/icon-image-2.png') }}" />
</div>
</div>
</a>
</div>
<div class="col-md-4">
<a href="#">
<div class="veiw-section">
<div class="veiw-block">
<div class="product-image">
<img src="{{ URL::asset('img/veiw-image-2.png') }}" />
</div>
<div class="veiw-content">
<h2>Gynecologist/obstetrician</h2>
<p>Explore for women’s health</p>
</div>
<div class="veiw-icon">
<img src="{{ URL::asset('img/icon-image-1.png') }}" />
</div>
</div>
</div>
</a>
</div>
<div class="col-md-4">
<a href="#">
<div class="veiw-block">
<div class="product-image">
<img src="{{ URL::asset('img/veiw-image-3.png') }}" />
</div>
<div class="veiw-content">
<h2>physiotherapist</h2>
<p>Pulled muscle? Treated by a trained  therapist</p>
</div>
<div class="veiw-icon">
<img src="{{ URL::asset('img/icon-image.png') }}" />
</div>
</div>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="container-fluid Patient_Portal">   
<div class="container">
<div class="Patient_Portal_section">
<h3>India’s largest health platform</h3>
<h2>Patient Portal</h2>
<span>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. </span>
<p>The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, making it look like readable English.</p>
<button type="button" class="btn btn-default">MORE ABOUT PORTAL</button>    
</div>  
</div>
</div>
<div class="container-fluid">   
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
<div class="better_health_block">
<img src="{{ URL::asset('img/better_health_image.png') }}" />
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
<div class="better_health_block">
<img src="{{ URL::asset('img/better_health_image-2.png') }}" />
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
<div class="better_health_block">
<img src="{{ URL::asset('img/better_health_image-3.png') }}" />
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
<div class="better_health_block">
<img src="{{ URL::asset('img/better_health_image-4.png') }}" />
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
</div>  
<div class="health_experts">
<div class="health_experts_section">
<div class="health_experts_img">
<img src="{{ URL::asset('img/health_experts_image.png') }}" />
</div>
<div class="health_experts_content">
<div class="health_experts_text">
<h3>Read top articles from</h3>
<h2>health experts</h2>
<span>We care about you.</span>
<p>Health articles that keep you informed about good health practices
and achieve your goals.</p>
</div>
<button type="button" class="btn btn-default">See All Articlesd</button>
</div>
</div>
</div>
<div class="container-fluid">   
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
</div>
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


<div class="container-fluid Get_company_top">   
<div class="container">
<div class="Get">
<div class="col-md-6">
<div class="Get_company">
<div class="Get_company_img">
<img src="{{ URL::asset('img/msg-icon.png') }}" />  
</div>
<div class="Get_company_content">
    <h2>Get daily updates from our company!</h2>
    <p>We don’t share your personal info anyone.</p>
</div>

</div>
</div>
<div class="col-md-6">
<div class="Get_company_search">
<input type="text" placeholder="name@gmail.com" />  
<button type="button" class="btn btn-default">SUBMIT A QUOTE</button>
</div>
</div>
</div>
</div>
</div>
@endsection