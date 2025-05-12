@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')
<link href="{{ URL::asset('css/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/owlcarousel/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/jquery.notify.css') }}" rel="stylesheet" type="text/css"/>
<?php
if(Auth::user() != null){
$LabCart = getLabCart();
}
else{
$LabCart = Session::get("CartPackages");
}
// dd($LabCart);
?>
<div class="lab-test">
<div class="main-banner-slider"><img src=" {{ URL::asset('img/healthcare.jpg') }}" alt="banner"/></div>
<div class="main-banner-slider mobile"><img src=" {{ URL::asset('img/lab-banner-small.jpg') }}" alt="banner"/></div>
<div class="container-fluid all-offers">
<div class="container">
<h2>Complete Body Health Checkup Package</h2>
<p>Know your health status with our reliable full-body health checkup packages, which test you for any disease that might infect your body.</p>
<div class="checkup-package allOffers">
@if(count($packages) > 0)
@if($cmp_id != 2)
@foreach($packages as $offer)
<div class="checkup-detail item">
  <a href="{{route('LabDetails', ['id'=> base64_encode($offer->title), 'type' => base64_encode('PACKAGE')])}}">
  <img src="{{ URL::asset('public/lab-package-icon') }}/{{$offer->image}}" alt="banner" />
  <div class="certification">NABL, ISO</div>
	<h3>{{$offer->title}}</h3>
  </a>
  <div class="blog-wrapper-content">
	<div class="price-offer">
    
	  @if(!empty($offer->discount_price)) ₹{{$offer->discount_price}} @else ₹{{$offer->price}} @endif
	  @if(!empty($offer->discount_price))
		<div class="discount-price">
		   ₹ {{$offer->price}}
		</div>
		<div class="discount">
		  <?php
			$main = $offer->price;
			$offerRate = $offer->discount_price;
			$diff = $main-$offerRate;
			$percent = ($diff*100)/$offer->price
		  ?>
		  {{(floor($percent))}}%  off
		</div>
	  @endif
	</div>
	<div class="book-now"><input type="hidden" class="selectPackage" isPack="1" name="" lab_type="1" lab_company_type="1" value="{{json_encode($offer)}}"/><a href="javascript:void(0)" url="{{route('LabDetails', ['id'=> base64_encode($offer->title), 'type' => base64_encode('PACKAGE')])}}" class="add_to_cart @if(!empty($LabCart) && array_search($offer->title, array_column($LabCart, 'title')) !== false) removeCart @endif" data-name="{{$offer->title}}" Pcode="{{$offer->id}}" Pname="{{$offer->title}}"  data-type="@if(!empty($LabCart) && array_search($offer->title, array_column($LabCart, 'title')) !== false) 2 @else 1 @endif">@if(!empty($LabCart) && array_search($offer->title, array_column($LabCart, 'title')) !== false) Remove @else Book Now @endif</a></div>
	</div>
  </div>
@endforeach
@else
@foreach($packages as $offer)
<div class="checkup-detail item">
  <a href="{{route('LabDetails', ['id'=> base64_encode($offer->name), 'type' => base64_encode($offer->type)])}}">
  <img src="{{ URL::asset('img/thayrocare-logo-dashboard.jpg') }}" alt="banner"  />
  <div class="certification">NABL, ISO</div>
	<h3>{{$offer->common_name}}</h3>
  </a>
  <div class="blog-wrapper-content">
	<div class="test-included">
		<a href="{{route('LabDetails', ['id'=> base64_encode($offer->common_name), 'type' => base64_encode('OFFER')])}}">({{$offer->testCount}}) Tests Included</a>
	</div>
	<div class="price-offer">
	  ₹{{$offer['rate']['offerRate']}}
	  @if($offer['rate']['b2C'] > $offer['rate']['offerRate'])
		<div class="discount-price">
		   ₹{{$offer['rate']['b2C']}}
		</div>
		<div class="discount">
		  <?php
				$main = $offer['rate']['b2C'];
				$offerRate = $offer['rate']['offerRate'];
				$diff = $main-$offerRate;
				$percent = ($diff*100)/$offer['rate']['b2C']
		  ?>
		  {{(floor($percent))}}%  off
		</div>
	  @endif
	</div>
	<div class="book-now"> <input type="hidden" class="selectPackage" isPack="1" name="" value="{{json_encode($offer)}}" lab_type="0" lab_company_type="0"><a href="javascript:void(0)" url="{{route('LabDetails', ['id'=> base64_encode($offer->name), 'type' => base64_encode($offer->type)])}}" class="add_to_cart @if(!empty($LabCart) && array_search($offer->name, array_column($LabCart, 'name')) !== false) removeCart @endif" data-name="{{$offer->name}}" Pcode="{{$offer->code}}" Pname="{{$offer->name}}"  data-type="@if(!empty($LabCart) && array_search($offer->name, array_column($LabCart, 'name')) !== false) 2 @else 1 @endif">@if(!empty($LabCart) && array_search($offer->name, array_column($LabCart, 'name')) !== false) Remove @else Book Now @endif</a></div>
	</div>
  </div>
@endforeach
@endif
@endif
  </div>
  </div>
  </div>
</div>
<script src='{{ URL::asset("js/owl.carousel.js") }}'></script>
<script src='{{ URL::asset("js/jquery.notify.min.js") }}'></script>
<script>
// all icons shake when button is clicked
function successMessage() {
  notify({
      type: "success", //alert | success | error | warning | info
      // title: "Cart Added",
      // message: "Product Added Successfully.",
        message: "Test package added successfully.",
      position: {
          x: "right", //right | left | center
          y: "top" //top | bottom | center
      },
        icon: '<img src="{{ URL::asset("img/paper_plane.png") }}" />',
      size: "normal", //normal | full | small
      overlay: false, //true | false
      closeBtn: true, //true | false
      overflowHide: false, //true | false
      spacing: 20, //number px
      theme: "default", //default | dark-theme
      autoHide: true, //true | false
      delay: 800, //number ms
      onShow: null, //function
      onClick: null, //function
      onHide: null, //function
      template: '<div class="notify"><div class="notify-text"></div></div>'
  });
}

function CartAnimate(current) {
	$("html, body").animate({
		   scrollTop: 0
	  }, 600);
  shake($(current).closest('.checkup-detail'));

  var cart = $('.cart-wrapper');
  var imgtodrag = $(current).closest('.checkup-detail').find("img").eq(0);
  if (imgtodrag) {
      var imgclone = imgtodrag.clone()
          .offset({
          top: imgtodrag.offset().top,
          left: imgtodrag.offset().left
      })
          .css({
          'opacity': '1',
              'position': 'absolute',
              'height': '150px',
              'width': '150px',
              'z-index': '100'
      })
          .appendTo($('body'))
          .animate({
          'top': cart.offset().top + 10,
              'left': cart.offset().left + 10,
              'width': 75,
              'height': 75
      }, 2000, 'easeInOutExpo');

      setTimeout(function () {
          cart.effect("shake", {
              times: 2
          }, 200);
      }, 1500);

      imgclone.animate({
          'width': 0,
              'height': 0
      }, function () {
          $(current).detach()
      });
  }
}
// adaptable SHAKE function, from
// https://bradleyhamilton.com/projects/shake/index.html
function shake(thing) {
  var interval = 100;
  var distance = 10;
  var times = 6;

  for (var i = 0; i < (times + 1); i++) {
    $(thing).animate({
      left:
        (i % 2 == 0 ? distance : distance * -1)
    }, interval);
  }
  $(thing).animate({
    left: 0,
    top: 0
  }, interval);
}
function LabCart(product_array, action_type,replace_itm = null,lab_type) {
	var returnValue;
	  jQuery.ajax({
	  type: "POST",
	  async: false,
	  dataType: 'json',
	  url: "{!! route('CartUpdate') !!}",
	  data: {'product_array':product_array,'action_type':action_type,"replace_itm":replace_itm,'lab_type':lab_type},
		success: function(data) {
		  returnValue =  data;
		}
	  });
   return returnValue;
}
$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
      items: 5,
      rewind:false,
      dots:false,
      margin: 10,
      // autoplay:true,
      // autoplayTimeout:2000,
      // autoplaySpeed:700,
      // autoplayHoverPause:true,
      responsiveClass: true,
      responsive: {
        0: {
          items: 2,
          nav: true,
          mergeFit:true
        },
        600: {
          items: 3,
          nav: false
        },
        1000: {
          items: 5,
          nav: true,
          margin: 20
        }
      }
    });

  $(".cart-wrapper").hover(function(){
    if ($(".cart-wrapper").hasClass("cart-open") == true) {
        $(".cart-wrapper").removeClass('cart-open');
    }
  });
  jQuery(document).on("click", ".add_to_cart", function () {
    // var product_id = jQuery(this).prev("input").val();
    // var btn = this;
	$('.loading-all').show();
    var cartTotal = jQuery('#cartTotal').text();
    var type = jQuery(this).attr("data-type");
    var selectPackage = jQuery(this).parent().find('.selectPackage').val();
    var pkg = JSON.parse(selectPackage);
    var isPack = jQuery(this).parent().find('.selectPackage').attr('isPack');
	var lab_type = jQuery(this).parent().find('.selectPackage').attr("lab_type");
    var lab_company_type = jQuery(this).parent().find('.selectPackage').attr("lab_company_type");
    var gLabCmptp = jQuery(".gLabCmptp").val();
    var url = $(this).attr('url');
    if (type == 1) {
		if((gLabCmptp == '0' || gLabCmptp > '0') && gLabCmptp != lab_company_type) {
			jQuery('.loading-all').hide();
			if(confirm('Are you sure to change your Labs Comapany?')) {
				LabCart(selectPackage,'add_item','2',lab_type);
				location.reload();
			}
		}
		else {
			var response =  LabCart(selectPackage, 'add_item','',lab_type);
			if(response.status == '3') {
				jQuery('.loading-all').hide();
				if(confirm('Are you sure to change your offer?')) {
					LabCart(selectPackage,'add_item','1',lab_type);
					location.reload();
				}
			}
			else if(response.status == '1') {
				if(lab_type != 0 ) {
					if(isPack == 1){
						jQuery('.loading-all').hide();
						 CartAnimate(this);
						  if ($(".cart-wrapper").hasClass("cart-open") == false) {
							  $(".cart-wrapper").addClass('cart-open');
						  }
						  $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart removeCart" data-name="'+pkg.title+'" Pcode="'+pkg.id+'" Pname="'+pkg.title+'" data-type="2">Remove</a>');
						  var cartTotal = jQuery('#cartTotal').text();
						  successMessage();
						  if (parseFloat(cartTotal) == 0) {
							jQuery('#cartTotal').text(1);
							jQuery('.totalTest').text(1);
						  }
						  else{
							jQuery('#cartTotal').text(parseFloat(cartTotal)+1);
							jQuery('.totalTest').text(parseFloat(cartTotal)+1);
						  }
						  if (pkg.discount_price != null) {
							var price = pkg.discount_price;
						  }
						  else {
							var price = pkg.price;
						  }
						  var Div = '<div class="list" title="'+pkg.title+'" data-name="'+pkg.title+'"><img src="{{asset("img/OurStore-icon.png")}}"><h5><a href="'+url+'">'+pkg.title+'</a></h5> <span><strong>1 x ₹'+price+'</strong></span><a class="close deleteFromMiniCart" href="javascript:void(0);"  Pcode="'+pkg.id+'" Pname="'+pkg.title+'"><i class="fa fa-times-circle" aria-hidden="true"></i></a> </div>';
						  $("#miniCartList").append(Div);
						  setTimeout(function(){
							$("#miniCart").css("display", "");
							$(".cart-wrapper").attr('title','Cart');
						}, 1000);
						jQuery(".gLabCmptp").val(response.lab_company_type);
					}
					else{
						jQuery('.loading-all').hide();
						 CartAnimate(this);
						  if ($(".cart-wrapper").hasClass("cart-open") == false) {
							  $(".cart-wrapper").addClass('cart-open');
						  }
						  $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart removeCart" data-name="'+pkg.default_labs.title+'" Pcode="'+pkg.id+'" Pname="'+pkg.default_labs.title+'" data-type="2"> Remove</a>');
						  var cartTotal = jQuery('#cartTotal').text();
						  successMessage();
						  if (parseFloat(cartTotal) == 0) {
							jQuery('#cartTotal').text(1);
							jQuery('.totalTest').text(1);
						  }
						  else{
							jQuery('#cartTotal').text(parseFloat(cartTotal)+1);
							jQuery('.totalTest').text(parseFloat(cartTotal)+1);
						  }
						  if(pkg.offer_rate != null) {
							var price = pkg.offer_rate;
						  }
						  else {
							var price = pkg.cost;
						  }
						  var Div = '<div class="list" title="'+pkg.default_labs.title+'" data-name="'+pkg.default_labs.title+'"><img src="{{asset("img/OurStore-icon.png")}}"><h5><a href="'+url+'">'+pkg.default_labs.title+'</a></h5> <span><strong>1 x ₹'+price+'</strong></span><a class="close deleteFromMiniCart" href="javascript:void(0);"  Pcode="'+pkg.id+'" Pname="'+pkg.default_labs.title+'"><i class="fa fa-times-circle" aria-hidden="true"></i></a> </div>';
						  $("#miniCartList").append(Div);
						  setTimeout(function(){
							$("#miniCart").css("display", "");
							$(".cart-wrapper").attr('title','Cart');
						}, 1000);
						jQuery(".gLabCmptp").val(response.lab_company_type);
					}
				}
				else {
					jQuery('.loading-all').hide();
					 CartAnimate(this);
					  if ($(".cart-wrapper").hasClass("cart-open") == false) {
						  $(".cart-wrapper").addClass('cart-open');
					  }
					  $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart removeCart" data-name="'+pkg.name+'" Pcode="'+pkg.code+'" Pname="'+pkg.name+'" data-type="2"> Remove</a>');
					  var cartTotal = jQuery('#cartTotal').text();
					  successMessage();
					  if (parseFloat(cartTotal) == 0) {
						jQuery('#cartTotal').text(1);
						jQuery('.totalTest').text(1);
					  }
					  else{
						jQuery('#cartTotal').text(parseFloat(cartTotal)+1);
						jQuery('.totalTest').text(parseFloat(cartTotal)+1);
					  }
					  if (pkg.rate.offerRate != null) {
						var price = pkg.rate.offerRate;
					  }
					  else {
						var price = pkg.rate.b2C;
					  }
					  var Div = '<div class="list" title="'+pkg.name+'" data-name="'+pkg.name+'"><img src="{{asset("img/OurStore-icon.png")}}"><h5><a href="'+url+'">'+pkg.name+'</a></h5> <span><strong>1 x ₹'+price+'</strong></span><a class="close deleteFromMiniCart" href="javascript:void(0);"  Pcode="'+pkg.code+'" Pname="'+pkg.name+'"><i class="fa fa-times-circle" aria-hidden="true"></i></a> </div>';
					  $("#miniCartList").append(Div);
					  setTimeout(function(){
						$("#miniCart").css("display", "");
						$(".cart-wrapper").attr('title','Cart');
					}, 1000);
					jQuery(".gLabCmptp").val(response.lab_company_type);
				}
			}
		}
    }
    else if (type == 2) {
      var selectPackage = [];
      var pname = $(this).attr('Pname');
      var pcode = $(this).attr('Pcode');
      selectPackage.push({pname:pname,pcode:pcode});
	  jQuery('.loading-all').hide();
      var response = LabCart(selectPackage, 'remove_item','',lab_type);
	  if(response.status == '1'){
      $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart" data-name="'+pkg.name+'" Pcode="'+pkg.code+'" Pname="'+pkg.name+'" data-type="1"> Book Now</a>');
      jQuery('#cartTotal').text(parseFloat(cartTotal)-1);
      jQuery('.totalTest').text(parseFloat(cartTotal)-1);
      var data_name = $(this).attr("data-name");
      $("#miniCartList .list").each(function(){
        if ($(this).attr("data-name") == data_name) {
          $(this).remove();
        }
      });
      if ($("#miniCartList .list").length == '0') {
        $("#miniCart").css("display", "none");
        $(".cart-wrapper").removeClass('cart-open');
        $(".cart-wrapper").attr('title','Cart is Empty!');
      }
	  jQuery(".gLabCmptp").val(response.lab_company_type);
    }
	}
  });
});
  // jQuery(document).on("click", ".deleteFromMiniCart", function () {
  //     $(this).parent().slideUp("slow");
  //     var selectPackage = $(this).parent().find('.selectPackage').val();
  //     LabCart(selectPackage, 'remove_item');
  //     // setTimeout(function(){ $(this).parent().remove(); }, 3000);
  // });
</script>
@endsection