@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Patient Portal')
@section('content')
<div class="container doctor-detail-wrapper">
  <div class="container-inner">
    <div class="filer-bar">
      <div class="breadcrume">
        <ul>
          <li><a href="{{route('index')}}">Home</a> /</li>
          <li><a href="{{route('index')}}">{{ Session::get('search_from_city_name') }}</a> /</li>
        </ul>
      </div>
    </div>
    <div class="searching-data-wrapper">
      <div class="searching-data">
        <h1>Find the nearest Healthcare providers in Jaipur.</h1>
        <div class="search-by-wrapper">
          <p>Search By</p>
          <ul>
            <li class="active"><a href="#"> <span class="round-tabs"><i class="sprite idoctor">&nbsp;</i></span>
              <h2>Doctors(1990)</h2>
              </a></li>
            <li><a href="#"> <span class="round-tabs"><i class="sprite iclinic">&nbsp;</i></span>
              <h2>Clinics(1500)</h2>
              </a></li>
            <li><a href="#"> <span class="round-tabs"><i class="sprite ihospital">&nbsp;</i></span>
              <h2>Hospitals(118)</h2>
              </a></li>
            <li><a href="#"> <span class="round-tabs"><i class="sprite itreatments">&nbsp;</i></span>
              <h2>Treatments(10)</h2>
              </a></li>
          </ul>
        </div>
        <div class="top-treatment-wrapper">
          <h3>Top Clinic Specialities</h3>
          <ul>
            <li><a href="#">Premature ejaculation</a></li>
            <li><a href="#">Urinary tract infection</a></li>
            <li><a href="#">Ankylosing spondylitis</a></li>
            <li><a href="#">Diabetes</a></li>
            <li><a href="#">Dengue</a></li>
            <li><a href="#">Lower Back Pain</a></li>
            <li><a href="#">Hair Loss</a></li>
            <li><a href="#">Asthma</a></li>
            <li><a href="#">Chickenpox</a></li>
            <li><a href="#">Dandruff</a></li>
          </ul>
        </div>
        <div class="recent-feedback">
        <h3>Recent Feedback</h3>
          <div class="review-block">
            <div class="review-img-wrap"> <a href="#"><img src="img/thumbnail.jpg" /></a>
              <div class="reviewer">Chelani's Dental & Urology Clinic</div>
            </div>
            <div class="review-text"> Clinic is well maintained and most important thing that we have seen that they have taken care of hygiene at every step.
              Attendants with patients can view what procedure going on inside. Staff also supportive. </div>
          </div>
          <div class="review-block">
            <div class="review-img-wrap"> <a href="#"><img src="img/thumbnail.jpg" /></a>
              <div class="reviewer">Chelani's Dental & Urology Clinic</div>
            </div>
            <div class="review-text"> Clinic is well maintained and most important thing that we have seen that they have taken care of hygiene at every step.
              Attendants with patients can view what procedure going on inside. Staff also supportive. </div>
          </div>
        </div>
        
        
        <div class="queries">
          <h2>Recent Queries on Health Gennie Consult</h2>
          <div class="consult-item"> <i class="spl-sprite idietitian-nutritionist"></i>
            <div class="consult-msg">
            <a href="#" target="_blank" class="subject"> Weigh loss </a>
            <a href="#" target="_blank" class="text">
            <span class="less-text">I wanna lose my 20 kg of weight in just one month and that is not water weight bcz i lose that befor...</span>
            <span class="more-text">I wanna lose my 20 kg of weight in just one month and that is not water weight bcz i lose that before so plz help me</span>	 			<span class="read-more toggle-text">Read More</span>
           </a>
            
              <div class="time-stamp">
                <label class="created-time" datetime="2019-12-26 16:24:32">a day ago</label>
                <span>23 views </span>
              </div>
            </div>
          </div> 
          <div class="consult-item"> <i class="spl-sprite idietitian-nutritionist"></i>
            <div class="consult-msg"> <a href="#" target="_blank" class="subject"> Weigh loss </a> <a href="#" target="_blank" class="text"> <span class="less-text">I wanna lose my 20 kg of weight in just one month and that is not water weight bcz i lose that befor
              ...</span> <span class="more-text">I wanna lose my 20 kg of weight in just one month and that is not water weight bcz i lose that before so plz help me</span> <span class="read-more toggle-text">Read More</span> </a>
              <div class="time-stamp">
                <label class="created-time" datetime="2019-12-26 16:24:32">a day ago</label>
                <span>23 views </span> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 