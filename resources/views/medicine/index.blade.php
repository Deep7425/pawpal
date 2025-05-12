@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')

<link href="{{ URL::asset('css/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/owlcarousel/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/jquery.notify.css') }}" rel="stylesheet" type="text/css"/>

<div class="main-content">
        <div class="crousal-wrapper">
          <?php $banners =  getOfferBanner(1,5); ?>
        <div class="owl-carousel owl-theme">
            @if(count($banners) > 0)
              @foreach($banners as $index => $blg)
              <div class="item">
                <a href="#">
                <img src="{{$blg->image}}" alt="img">
                </a>
              </div>
              @endforeach
            @endif
      </div>
      </div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3>Order Medicines through Prescription</h3>
      </div>
      <div class="col-md-4">
        <div class="med-box">
          <div class="med-content">
            <p>Upload Prescription to place order</p>
            <span>Upload only .jpg .png or .pdf files, size limit is 15 MB</span>
            <button type="button" class="btn btn-primary">Order via Prescription</button>
          </div>
          <div class="med-img-box">
            <img src="" alt="img">
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="med-box">
          <div class="med-content">
            <p>Don't have a Prescription?</p>
            <span>Consult Top Doctors Online and order medicines through valid prescriptions</span>
            <button type="button" class="btn btn-primary">Start Consultation</button>
          </div>
          <div class="med-img-box">
            <img src="" alt="img">
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="med-box">
          <div class="med-content">
            <p>Buy Online</p>
            <span>Search and select the medicines you want</span>
            <button type="button" class="btn btn-primary">Search Medicines</button>
          </div>
          <div class="med-img-box">
            <img src="" alt="img">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src='{{ URL::asset("js/owl.carousel.js") }}'></script>
<script src='{{ URL::asset("js/jquery.notify.min.js") }}'></script>
<script>
  jQuery(document).ready(function($) {
    //   $('.owl-carousel').owlCarousel({
    //   items: 5,
    //   rewind:false,
    //   dots:false,
    //   margin: 10,
    //   // autoplay:true,
    //   // autoplayTimeout:2000,
    //   // autoplaySpeed:700,
    //   // autoplayHoverPause:true,
    //   responsiveClass: true,
    //   responsive: {
    //     0: {
    //       items: 2,
    //       nav: true,
    //       mergeFit:true
    //     },
    //     600: {
    //       items: 3,
    //       nav: false
    //     },
    //     1000: {
    //       items: 5,
    //       nav: true,
    //       margin: 20
    //     }
    //   }
    // });
    $('.owl-carousel').owlCarousel({
      loop:true,
      animateOut: 'slideOutDown',
      animateIn: 'flipInX',
      dots:false,
      items:1,
      margin:30,
      smartSpeed:450
    });
  });
</script>
@endsection
