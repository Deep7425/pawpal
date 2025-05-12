@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')
<link href="{{ URL::asset('css/owlcarousel/owl.carousel.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/owlcarousel/owl.theme.default.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('css/jquery.notify.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="lab-test">
<div class="main-banner-slider"><img src=" {{ URL::asset('img/healthcare.jpg') }}" alt="banner"/></div>
<div class="main-banner-slider mobile"><img src=" {{ URL::asset('img/lab-mobile-banner.jpg') }}" alt="banner"/></div>
<div class="container-fluid LabDashboardSection">
<div class="container">

<h2>Schedule Your Prescribed Lab Tests</h2>
<p>Upload your prescription to book diagnostic tests online with medical benefits. We will suggest the best diagnostic test packages at best prices.</p>

@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
@endif
<div class="comPkg comPkg123">
<div class="PriscriptionUploadImage">
	<img src="img/Priscriptionbook.png" />
</div>
<div class="PriscriptionUploadedit">
<h2>Book Lab test with Priscription</h2>
<p>Upload prescription and we will do the rest!</p>
</div>
<button class="btn btn-info showUploadPresDiv" data-toggle="modal" data-backdrop="static" data-keyboard="false" isLogin="{{Auth::user()}}">Upload Prescription</button>
</div>
<?php
if(Auth::user() != null){
  $LabCart = getLabCart();
}
else{
  $LabCart = Session::get("CartPackages");
}
?>
<div class="crousal-wrapper comPkg">
<h2>Your Health Care Partner</h2>
<p>An online doctor consultation app, where you can find top specialists to consult you about your health problems.</p>

<div class="screening owl-carousel owl-theme">
@forelse(getLabCompanies() as $group)
<div class="small-blogs item">
  <a href="{{route('showLabsPackage', base64_encode($group->id))}}">
  <img src="{{ URL::asset('public/others/company_logos') }}/{{$group->icon}}" alt="img"/>
	<h4>{{$group->title}}</h4>
  </a>
</div>
@empty	
@endforelse 
</div>
</div>

<div class="crousal-wrapper health-checkups">
<h2>Recommended Health Check-up Packages</h2>
<p>To know your health status, select our full body health checkup packages. </p>
<p class="see-all"> <a href="{{route('allPackages', 'groups')}}"> See All</a> </p>
<div class="screening owl-carousel owl-theme">

  <?php $offer = 0;?>
  @if($groups->count()>0)
  @foreach($groups as $group)
	<div class="small-blogs item">
	  <a href="{{route('LabProfile', base64_encode($group->group_name))}}">
	  <img src="@if(!empty($group->image)) {{ URL::asset('public/thyrocarePackageFiles/'.$group->image) }} @else {{ URL::asset('img/diabeties.png') }} @endif" alt="img"  />
		<h4>{{$group->group_name}}</h4>
	  </a>
	</div>
	<?php $offer++; ?>
  @endforeach
  @endif	
</div>
</div>

<div class="top-booked-wrapper">
	<div class="title">
    	<h2>Our Best Medical Tests</h2>
        <p>Below are some of the top medical tests that our customers highly book for their health care check-up.</p>
    </div>
	@if(count($plans) > 0)
		@foreach($plans as $i => $plan)
		<div class="category-wrapper">
			<h3><img class="" src="img/lipid-profile.png" /> {!!$plan->title_head!!}</h3>
			<div class="actual-price-wrapper">@if($plan->discount_price != "" && $plan->discount_price != 0)<strike><strong>₹{{$plan->price}}</strong></strike>@endif <strong>₹{{$plan->price - $plan->discount_price}}</strong></div>
			<div class="plan-content">{!!$plan->content!!}</div>
			 <div class="btn-labbook"><a href='{{route("planDetails",["id" => base64_encode($plan->id)])}}'>Book Now</a></div>
		</div>
		@endforeach
	@endif
    
   <!-- <div class="category-wrapper">
    	<h3><img class="" src="img/liver-profile.png" /> Liver Profile</h3>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
         <div class="btn-labbook"><a href="#">Book Now</a></div>
    </div>
    
    <div class="category-wrapper">
    	<h3><img class="" src="img/blood-sugar.png" /> Blood Sugar</h3>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        <div class="test-category">
        	<strong>Ldl Cholesterol Test</strong>
            <p>Cholesterol Ldl Enzymatic Colorimetric Method Blood</p>
        </div>
        
        <div class="btn-labbook"><a href="#">Book Now</a></div>
    </div>-->
</div>


<!--<h2>Top Booked Packages</h2>
<p class="see-all"> <a href="{{route('allPackages', 'profiles')}}"> See All</a> </p>
<div class="owl-carousel owl-theme last-crowusal">
<?php $profiles = getThyrocareData("PROFILE");?>
@if(count($profiles) > 0) 
@foreach($profiles as $offer) 
<div class="package-box item">
  <img src="{{ URL::asset('img/thayrocare-logo-dashboard.jpg') }}" alt="banner"  />
  <h3>{{$offer['common_name']}}</h3>
  <?php  
		$totalPChild = array();
		foreach($offer['childs'] as $element) {
		   if($element['groupName'] != "SUBSET") {
				$totalPChild[] = $element;
		   }
		}
	?>
	<span class="total_count">Tests Included</span> <b>({{count($totalPChild)}})</b>
	<div class="price">₹ {{$offer['rate']['payAmt1']}}</div>
	<a href="{{route('LabDetails', ['id'=> base64_encode($offer->common_name), 'type' => base64_encode($offer->type)]) }}">View Package</a>
</div>
@endforeach
@endif
</div>

<div class="lab-content">
{!!getTermsBySLug('lab-dashboard-page-content')!!}
</div>-->
<div class="modal fade upload-pres-modal" id="presModelDiv" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
</div>


</div>
<div class="why-book-wrapper">
	<div class="container">
		<div class="why-book">
	<h2>Highlight of our services</h2>
    <div class="package-page">
    	<div class="item-wrapper">
    		<div class="u-d__inline"><img class="" src="img/lab-kit-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">NABL and ISO Approved Lab</div>
                <div class="para-text">A certified professional will collect your sample from your preferred location</div>
            </div>
        </div>
        
        <div class="item-wrapper">
    		<div class="u-d__inline"><img src="img/lab-report-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">Only Certified Professional will collect the sample from your location.</div>
                <div class="para-text">Our labs ensure turn-around-time of 24 hours from specimen pickup</div>
            </div>
        </div>
        
        <div class="item-wrapper">
    		<div class="u-d__inline"><img src="img/lab-offer-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">Best price with extra savings.</div>
                <div class="para-text">Get great discounts and offers on tests and packages.</div>
            </div>
        </div>
        
        
            	<div class="item-wrapper">
    		<div class="u-d__inline"><img class="" src="img/lab-kit-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">Report available directly in Health Gennie app.</div>
                <div class="para-text">A certified professional will collect your sample from your preferred location</div>
            </div>
        </div>
        
        <div class="item-wrapper">
    		<div class="u-d__inline"><img src="img/lab-report-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">Share report with anyone directly from Health Gennie app.</div>
                <div class="para-text">Our labs ensure turn-around-time of 24 hours from specimen pickup</div>
            </div>
        </div>
        
        <div class="item-wrapper">
    		<div class="u-d__inline"><img src="img/lab-offer-icon.png" /></div>
            <div class="u-carousal__item">
                <div class="u-font-bold">Everything from the comfort of your home.</div>
                <div class="para-text">Get great discounts and offers on tests and packages.</div>
            </div>
        </div>
    </div>
</div>
	</div>
</div>


<div class="how-it-work">
	<div class="container">

	<h2>How it works?</h2>
    <ul>
        <li><img class="" src="img/lab-black-icon.png" /><p>Search for tests and packages and seamlessly book a home sample collection.</p></li>
        <li><img class="" src="img/user-black-icon.png" /><p>We will send a certified professional to your place to assist you with the sample collection.</p></li>
        <li><img class="" src="img/report-black-icon.png" /><p>We will email you the reports. You can also access your reports within your account on the Practo app.</p></li>
    </ul>
</div>
</div>
<div class="container-fluid download-app-wrapper">
        <div class="container download-app-sec">
        <div class="col-md-12">
            <div class="col-md-6 col-sm-6 mobile-img"><img style=" margin-top:20px;" src="img/dx.png" alt="doctor"/>
            
            
            
            </div>
            <div class="col-md-6 col-sm-6">
                <h2>Download The<br>Health Gennie App</h2>
                <p><strong>Access to health care in one tap.</strong><br />Book appointments, consult with doctor online, book health check-ups, retrieve health records & read health tips.</p>
                <div class="store-btns">
                    <a href="https://play.google.com/store/apps/details?id=io.Hgpp.app" target="_blank"><img width="125" src="img/play-store-home.png" alt="play store"/></a>
                    <a href="https://apps.apple.com/in/app/health-gennie-care-at-home/id1492557472" target="_blank"><img width="125" src="img/app-store-home.png" alt="app store"/></a>
                </div>
            </div>
        </div>
     </div>
    </div>






</div>
<div class="viewPresFileModal modal" data-keyboard="false" data-backdrop="static">
	<div class="view-file-full">
	  <span class="close" data-dismiss="modal">&times;</span>
	  <img class="modal-content img01" width="150" height="100%" src="" />
	</div>
</div>
<div class="viewPdfFileModal modal" data-keyboard="false" data-backdrop="static">
	<div class="view-file-full">
	  <span class="close" data-dismiss="modal">&times;</span>
	  <iframe class="modal-content pdf01" style="width:100%; height:450px;" src="" ></iframe>
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
jQuery(document).on("click", ".showUploadPresDiv", function () {
 if($(this).attr('isLogin')){
 getUploadedPres();
 }
 else {
	$.alert({
		title: 'Prescription !',
		content: 'Please Login To Share Prescription.',
		draggable: false,
		type: 'green',
		typeAnimated: true,
		buttons: {
			Cancel: function(){
				 // $.alert('Canceled!');
			},
			Login: function(){
				var url = '{!! url("/login") !!}';
				window.location = url;
			},
		}
	  });
 }
});
function getUploadedPres() {
jQuery('.loading-all').show();
jQuery.ajax({
type: "POST",
dataType : "HTML",
url: "{!! route('getPrescriptions')!!}",
// data:{'user_id':user_id},
success: function(data){
  jQuery('.loading-all').hide();
  jQuery("#presModelDiv").html(data);
  jQuery('#presModelDiv').modal('show');
  jQuery("#presModelDiv").scrollTop(0);
  $(window).scrollTop();
},
error: function(error){
	jQuery('.loading-all').hide();
	alert("Oops Something goes Wrong.");
}
});
}
  jQuery(document).on("click", ".add_to_cart", function () {
    // var product_id = jQuery(this).prev("input").val();
    // var btn = this;
	$('.loading-all').show();
    var cartTotal = jQuery('#cartTotal').text();
    var type = jQuery(this).attr("data-type");
    var selectPackage = jQuery(this).parent().find('.selectPackage').val();
    var pkg = JSON.parse(selectPackage);
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
					  if (pkg.offer_rate != 'null') {
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
					  if (pkg.rate.offerRate != 'null') {
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
      LabCart(selectPackage, 'remove_item','',lab_type);
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
	  if(response.isCartEmpty){
		jQuery(".gLabCmptp").val('');
	  }
    }
  });
});
jQuery(document).on("click", ".removePres", function () {
    jQuery('.loading-all').show();
	var rawId = $(this).attr('rawId');
	var current = this;
	jQuery.ajax({
	type: "POST",
	dataType : "JSON",
	url: "{!! route('removePrescription')!!}",
	data:{'rawId':rawId},
	success: function(data){
	  jQuery('.loading-all').hide();
	  $(current).parent().remove();
	},
	error: function(error){
		jQuery('.loading-all').hide();
		alert("Oops Something goes Wrong.");
	}
	});
});
  // jQuery(document).on("click", ".deleteFromMiniCart", function () {
  //     $(this).parent().slideUp("slow");
  //     var selectPackage = $(this).parent().find('.selectPackage').val();
  //     LabCart(selectPackage, 'remove_item');
  //     // setTimeout(function(){ $(this).parent().remove(); }, 3000);
  // });
jQuery(document).on("click", ".viewPresFile", function (e) {
	jQuery('.loading-all').show();
	var image = $(this).attr('src');
	$(".viewPresFileModal").find(".img01").attr("src",image);
	setTimeout(function() {
		jQuery('.loading-all').hide();
		$(".viewPresFileModal").modal("show");
	},1000);
});

jQuery(document).on("click", ".viewPDFPresOwn", function (e) {
	// jQuery('.loading-all').show();
	var pdfF = $(this).attr('pdfSrc');
	window.open(pdfF, '_blank');
	// $(".viewPdfFileModal").find(".pdf01").attr("src",pdfF);
	// setTimeout(function() {
		// jQuery('.loading-all').hide();
		// $(".viewPdfFileModal").modal("show");
	// },1000);
});
function openFileProfile(event) {
	var input = event.target;
	var FileSize = input.files[0].size / 1024 / 1024; // 1in MB
	var type = input.files[0].type;
	var ext = input.files[0].name.split('.').pop().toLowerCase();
	if(FileSize > 3) {
		$('.FileBtn').next(".help-block").remove();
		$('.FileBtn').after('<span style="width:100%" class="help-block text-danger">Allowed file size exceeded. (Max. 3 MB)</span>');
		$(".SaveFile").attr("disabled",true);
	}
	else if($.inArray(ext, ['png','jpg','jpeg','pdf']) >= 0) {
		$('.FileBtn').next(".help-block").remove();
		$('.FileBtn').after('<span style="width:100%" class="help-block text-success">File Fetch Successfully</span>');
		$(".SaveFile").attr("disabled",false);
	}
	else {
		$('.FileBtn').next(".help-block").remove();
		$('.FileBtn').after('<span style="width:100%" class="help-block text-danger">Only formats are allowed : (jpeg,jpg,png,pdf)</span>');
	}
}
</script>
@endsection
