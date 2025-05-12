@extends('layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')
<link href="{{ URL::asset('css/jquery.notify.css') }}" rel="stylesheet" type="text/css"/>
<?php
if(Auth::user() != null){
	$LabCart = getLabCart();
}
else{
	$LabCart = Session::get("CartPackages");
}
if(Session::get("lab_company_type") !== null) {
	$lab_company_type = Session::get("lab_company_type");
}
else {
	$lab_company_type = null;
}
$groups = array();
?>
<div class="lab-test Lab_Test_Details single-lab-detail">
<div class="container-fluid">
@if(!empty($item))
<div class="container LabDetailsDiv thyroidprofile">
@if($type == "PACKAGE")
<h2>{{$item->title}}</h2>
<div class="checkup-package">
<div class="checkup-detail">
  <div class="Lab_Details_Image">
  <img src="{{ URL::asset('public/lab-package-icon') }}/{{$item->image}}" alt="banner"/>
  </div>
   <div class="blog-wrapper-content">
	<h3>{{$item->title}}</h3>
	<div class="test-included">
	  <a href="javascript:void(0);" title='({{substr_count($item->lab_id,",")+1}}) Tests Included'>({{substr_count($item->lab_id,",")+1}}) Tests Included</a>
	</div>
	  <div class="price-offer">
		@if(!empty($item->discount_price)) ₹ {{$item->discount_price}} @else ₹ {{$item->price}} @endif
		@if(!empty($item->discount_price))
		  <div class="discount-price">
			  {{$item->price}}
		  </div>
		  <div class="discount">
			<?php
				  $main = $item->price;
				  if ($item->discount_price != "null") {
					$offerRate = $item->discount_price;
				  }
				  else {
					$offerRate = $item->price;
				  }
				  $diff = $main-$offerRate;
				  $percent = ($diff*100)/$item->price
			?>
			{{(floor($percent))}}%  off
		  </div>
		  @endif
	</div>
	<div class="book-now"><input type="hidden" class="selectPackage" isPack="1" name="" lab_type="1" lab_company_type="1" value="{{json_encode($item)}}"/><a href="javascript:void(0)" url="{{route('LabDetails', ['id'=> base64_encode($item->title), 'type' => base64_encode('PACKAGE')])}}" class="add_to_cart @if(!empty($LabCart) && array_search($item->title, array_column($LabCart, 'title')) !== false) removeCart @endif" data-name="{{$item->title}}" Pcode="{{$item->id}}" Pname="{{$item->title}}"  data-type="@if(!empty($LabCart) && array_search($item->title, array_column($LabCart, 'title')) !== false) 2 @else 1 @endif">@if(!empty($LabCart) && array_search($item->title, array_column($LabCart, 'title')) !== false) Remove @else Book Now @endif</a></div>
	</div>
</div>
</div>	
@else
<h2>{{$item->common_name}}</h2>

<div class="checkup-package">
<div class="checkup-detail">
  <div class="Lab_Details_Image">
  <img src="{{ URL::asset('public/others/company_logos/thyrocare-logo.png') }}" alt="banner"/>
  </div>
   <div class="blog-wrapper-content">
	<h3>{{$item->common_name}}</h3>
	<div class="test-included">
	<?php
	$groups = array();
	// dd($item->childs);
	$totalChild = array();
	// if(count($item->childs)>0){
	  foreach($item->childs as $element) { //pr($element);
		   if($element['groupName'] != "SUBSET"){
				$groups[$element['groupName']][] = $element;
				$totalChild[] = $element;
		   }
	  }
	// }
	?>
	  <a href="javascript:void(0);" title="({{$item->testCount}}) Tests Included">({{count($totalChild)}}) Tests Included</a>
	</div>
	  <div class="price-offer">
	  @if($item['rate']['offerRate'] != "null") ₹{{$item['rate']['offerRate'] }} @else ₹{{$item['rate']['b2C'] }} @endif
		@if($item['rate']['b2C'] > $item['rate']['offerRate'])
		  <div class="discount-price">
			  {{$item['rate']['b2C']}}
		  </div>

		  <div class="discount">
			<?php
				  $main = $item['rate']['b2C'];
				  if ($item['rate']['offerRate'] != "null") {
					$offerRate = $item['rate']['offerRate'];
				  }
				  else {
					$offerRate = $item['rate']['b2C'];
				  }
				  $diff = $main-$offerRate;
				  $percent = ($diff*100)/$item['rate']['b2C']
			?>
			{{(floor($percent))}}%  off
		  </div>
		  @endif
		  <!-- <p>+ 10% Health cashback <span class="mandatory">*</span></p> -->
	</div>
	  <div class="book-now"><input type="hidden" isPack="0" class="selectPackage" lab_type="0" lab_company_type="0" name="" value="{{json_encode($item)}}"/><a href="javascript:void(0)"  url="{{route('LabDetails', ['id'=> base64_encode($item->name), 'type' => base64_encode($item->type)]) }}" class="add_to_cart @if(!empty($LabCart) && array_search($item->name, array_column($LabCart, 'name')) !== false) removeCart @endif" data-name="{{$item->name}}" Pcode="{{$item->code}}" Pname="{{$item->name}}"  data-type="@if(!empty($LabCart) && array_search($item->name, array_column($LabCart, 'name')) !== false) 2 @else 1 @endif">@if(!empty($LabCart) && array_search($item->name, array_column($LabCart, 'name')) !== false) Remove @else Book Now @endif</a></div>
	</div>
</div>
</div>
@endif  
@if(!empty($other_item))
@php $isCart = checkThisItemInCart($other_item->DefaultLabs->id); @endphp
<div class="checkup-package">
<div class="checkup-detail">
  <div class="Lab_Details_Image">
  <img src="{{ URL::asset('public/others/company_logos') }}/{{$other_item->LabCompany->icon}}" alt="banner"/>
  </div>
   <div class="blog-wrapper-content">
	<h3>{{strtoupper($other_item->DefaultLabs->title)}}</h3>
	<div class="test-included">
	  <a href="javascript:void(0);">({{count($totalChild)}}) Tests Included</a>
	</div>
	  <div class="price-offer">
		@if(!empty($other_item->offer_rate)) ₹ {{$other_item->offer_rate}} @else ₹ {{$other_item->cost}} @endif
		  <!-- <p>+ 10% Health cashback <span class="mandatory">*</span></p> -->
	  </div>
	  <div class="book-now"><input type="hidden" isPack="0" class="selectPackage" name="" lab_type="1" lab_company_type="1" value="{{json_encode($other_item)}}">
	  <a href="javascript:void(0)"  url="{{route('LabDetails', ['id'=> base64_encode($other_item->DefaultLabs->title), 'type' => base64_encode($other_item->type)]) }}" class="add_to_cart @if($isCart) removeCart @endif" data-name="{{$other_item->DefaultLabs->title}}"  Pcode="{{$other_item->DefaultLabs->id}}" Pname="{{$other_item->DefaultLabs->title}}" data-type="@if($isCart) 2 @else 1 @endif">@if($isCart) Remove @else Book Now @endif</a></div>
	</div>
</div>
</div>

  
@endif
@if($type == "PACKAGE" && count($item->labs)>0)
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  @foreach($item->labs as $i => $raw)

      <div class="head" >
        {{$raw->DefaultLabs->title}}
        <i class="arrow"></i>
      </div>
      
      <div class="content">
        <h4>{{$raw->information}}</h4>
        @if(count($raw->sub_labs)>0)
            @foreach($raw->sub_labs as $val)
                <p>{{$val->title}}</p>
            @endforeach
        @endif
      </div>
	@endforeach
  </div>

@elseif(count($groups)>0)
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

<div class="package-box_lab">
		<?php $ii = 0;?>
	  @foreach($groups as $group => $tests)
      <?php $ii++; ?>
       <div class="panel panel-default">
      <div class="head">
           {{$group}}
        <i class="arrow"></i>
      </div>
      <div class="content">
            @foreach($tests as $child)
                <h4>{{$child['name']}}</h4>
          @endforeach
          </div>
        </div>
	@endforeach
</div>
   
    
    
  </div>
@endif
</div>
@else
<div class="container LabDetailsDiv not-found-details">
	<h2>{{$id}}</h2>
	<span>Lab Is Not Available</span>
</div>	
@endif
</div>
</div>
<script>
	$('.head').click(function(){
  $(this).toggleClass('active');
  $(this).parent().find('.arrow').toggleClass('arrow-animate');
  $(this).parent().find('.content').slideToggle(280);
});
</script>

<script src='{{ URL::asset("js/jquery.notify.min.js") }}'></script>
<script>
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
    var type = jQuery(this).attr("data-type").trim();
    var lab_type = jQuery(this).parent().find('.selectPackage').attr("lab_type");
	var isPack = jQuery(this).parent().find('.selectPackage').attr('isPack');
    var lab_company_type = jQuery(this).parent().find('.selectPackage').attr("lab_company_type");
    var gLabCmptp = jQuery(".gLabCmptp").val();
	
    var selectPackage = jQuery(this).parent().find('.selectPackage').val();
    var pkg = JSON.parse(selectPackage);
    var url = $(this).attr('url');
    if(type == 1) {
		var response;
		if((gLabCmptp == '0' || gLabCmptp > '0') && gLabCmptp != lab_company_type) {
			jQuery('.loading-all').hide();
			if(confirm('Are you sure to change your Labs Comapany?')) {
				LabCart(selectPackage,'add_item','2',lab_type);
				location.reload();
			}
		}
		else{
			response =  LabCart(selectPackage, 'add_item','',lab_type);
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
						  if (pkg.offer_rate != null) {
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
					  if (pkg.rate.offerpaymentRate != null) {
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
// jQuery(document).on("click", ".deleteFromMiniCart", function () {
  // $(this).parent().slideUp("slow");
  // var selectPackage = $(this).parent().find('.selectPackage').val();
  // LabCart(selectPackage, 'remove_item','',lab_type);
  // setTimeout(function(){ $(this).parent().remove(); }, 3000);
// });
</script>
@endsection