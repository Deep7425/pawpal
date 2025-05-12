@extends('amp.layouts.Masters.Master')
@section('title', 'HealthGennie Labs')
@section('content')
<div class="searching-keyword">
  <div class="container">
    <h1>SEARCH RESULTS FOR: <strong>"{{ Session::get('search_from_search_bar') }}"</strong></h1>
    <div class="searhc-result">@if(isset($infoData)){{$infoData->total()}}@endif matches found for:<strong>{{ Session::get('search_from_search_bar') }} In {{ Session::get('search_from_city_name') }}</strong> </div>
  </div>
</div>
<div class="lab-test Lab_Test_Details">
  <div class="container-fluid">
  	<div class="container LabDetailsDiv">
      <div class="breadcrume">
        <ul>
          <li><a href="{{route('LabDashboard')}}">Labs</a> /</li>
          <li><a href="{{route('LabProfile', base64_encode($item->group_name))}}">Lab Profile</a> /</li>
          <li><a href="#">Details of "{{$item->name}}"</a></li>
        </ul>
      </div>
      <div class="checkup-package">
      	<h2>{{$item->name}}</h2>
        <div class="checkup-detail">
        	<div class="Lab_Details_Image">
            <img src="@if(!empty($item->image_location))  @if(does_url_exists($item->image_location))  {{$item->image_location}} @else {{ URL::asset('img/health-package1.png') }} @endif @else {{ URL::asset('img/health-package1.png') }} @endif " alt="banner"/>
          </div>
           <div class="blog-wrapper-content">
            <h3>{{$item->name}}</h3>

            <div class="test-included">
              <a href="javascript:void(0);" title="({{$item->test_count}}) Tests Included">({{$item->test_count}}) Test included</a>
            </div>
            <div class="price-offer">
              {{$item->rate->pay_amt}}
                <div class="discount-price">
                    {{$item->rate->b2c}}
                </div>
                <div class="discount">
                  <?php
                        $main = $item->rate->b2c;
                        $pay_amt = $item->rate->pay_amt;
                        $diff = $main-$pay_amt;
                        $percent = ($diff*100)/$item->rate->b2c
                  ?>
                  {{(floor($percent))}}%  off
                </div>
                <p>+ 10% Health cashback <span class="mandatory">*</span></p>
            </div>
            <div class="book-now"> <input type="hidden" class="selectPackage" name="" value="{{json_encode($item)}}"><a href="javascript:void(0)"  url="{{route('LabDetails', ['id'=> base64_encode($item->name), 'type' => base64_encode($item->type)]) }}" class="add_to_cart" data-name="{{$item->name}}" Pcode="{{$item->code}}" Pname="{{$item->name}}"  data-type="@if(!empty(@Session::get("CartPackages")) && array_search($item->name, array_column(@Session::get("CartPackages"), 'name')) !== false) 2 @else 1 @endif">@if(!empty(@Session::get("CartPackages")) && array_search($item->name, array_column(@Session::get("CartPackages"), 'name')) !== false) Remove @else Book Now @endif</a></div>



            </div>
        </div>
      </div>
      <!-- <span class="Total_Test_Lab"><h3>Total Test Included :<span> {{$item->test_count}}</span></h3></span> -->
     <div class="package-box_lab">
      @foreach($item->childs as $child)
    <div class="package-box">
    	<div class="lab-test-block-img">
    		<img src="../img/lab2-icon.png" />
    	</div>
    	<div class="lab-test-block">
      <h3>{{$child->name}}</h3>
        <!-- <span class="total_count"><b>({{$child->group_name}})</b></span>
        <div class="price">{{$child->name}}</div> -->
        </div>
        <!-- <a href="#">Type : {{$child->type}}</a> -->
    </div>
    @endforeach
    </div>
   <!--<div class="package-box_lab">
      <div class="panel-group" id="faqAccordion">
      @foreach($item->childs as $child)
      <div class="panel panel-default ">
      	<div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                 <h4 class="panel-title">
                    <a href="#" class="ing">{{$child->code}}</a>
     </h4>
    </div>
     <div id="question0" class="panel-collapse collapse" style="height: 0px;">
     	<div class="panel-body">
        <span class="total_count"><b>({{$child->group_name}})</b></span>
        <div class="price">{{$child->name}}</div>
        </div>
    </div>
    </div>
    @endforeach
    </div>
    </div> -->
  </div>
  </div>
</div>
<script>
function LabCart(product_array, action_type) {
  jQuery.ajax({
  type: "POST",
  url: "{!! route('CartUpdate') !!}",
  data: {'product_array':product_array,'action_type':action_type},
  success: function(data){
      if(data==1)
      {
        // alert("Package Added into Cart Successfully ");
      }
      else if(data==2)
      {
        // alert("Package Remove Successfully");
      }
      else
      {
        alert("Problem into Cart");
      }
    }
  });
}

  jQuery(document).on("click", ".add_to_cart", function () {
    // var product_id = jQuery(this).prev("input").val();
    // var btn = this;
    var cartTotal = jQuery('#cartTotal').text();
    var type = jQuery(this).attr("data-type");
    var selectPackage = jQuery(this).parent().find('.selectPackage').val();
    var pkg = JSON.parse(selectPackage);
    var url = $(this).attr('url');

    if (type == 1) {
      LabCart(selectPackage, 'add_item');

      $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart" data-name="'+pkg.name+'" data-type="2"> Remove</a>');
      var cartTotal = jQuery('#cartTotal').text();

      if (parseFloat(cartTotal) == 0) {
        jQuery('#cartTotal').text(1);
        jQuery('.totalTest').text(1);
      }
      else{
        jQuery('#cartTotal').text(parseFloat(cartTotal)+1);
        jQuery('.totalTest').text(parseFloat(cartTotal)+1);
      }
      if (pkg.rate.offer_rate != 'null') {
        var price = pkg.rate.offer_rate;
      }
      else {
        var price = pkg.rate.b2c;
      }
      var Div = '<div class="list" title="'+pkg.name+'" data-name="'+pkg.name+'"><img src="{{asset("img/OurStore-icon.png")}}"><h5><a href="'+url+'">'+pkg.name+'</a></h5> <span><strong>1 x ₹'+price+'</strong></span><a class="close deleteFromMiniCart" href="javascript:void(0);"  Pcode="'+pkg.code+'" Pname="'+pkg.name+'"><i class="fa fa-times-circle" aria-hidden="true"></i></a> </div>';
      $("#miniCartList").append(Div);
      setTimeout(function(){ $("#miniCart").css("display", ""); }, 1000);
    }
    else if (type == 2) {
      var selectPackage = [];
      var pname = $(this).attr('Pname');
      var pcode = $(this).attr('Pcode');
      selectPackage.push({pname:pname,pcode:pcode});

      LabCart(selectPackage, 'remove_item');
      $(this).replaceWith('<a href="javascript:void(0)" class="add_to_cart" data-name="'+pkg.name+'" data-type="1"> Book Now</a>');
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
      }

    }
  });
</script>
@endsection
