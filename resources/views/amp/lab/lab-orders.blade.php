@extends('amp.layouts.Masters.Master')
@section('title', 'Lab Order | Health Gennie')
@section('description', "Register today on Health Gennie Elite portal to get tested by NABL or ISO certified lab at affordable prices.")
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
<div class="lab-test lab-test-profile">
  <div class="container-fluid">
      <div class="filter-section">
          <div class="filters">
            <a href="{{route('labOrders', ['filter '=> base64_encode(0)])}}" class="filter-1 @if(empty($filter)) active @endif" filter="null">All    <span> ({{getLabOrdersCount(1)}})</span> </a>
            <a href="{{route('labOrders', ['filter '=> base64_encode(1)])}}" class="filter-1 @if($filter == 1) active @endif" filter="1">Upcoming <span> ({{getLabOrdersCount(2)}})</span></a>
            <a href="{{route('labOrders', ['filter '=> base64_encode(3)])}}" class="filter-1 @if($filter == 3) active @endif" filter="3">Completed <span> ({{getLabOrdersCount(3)}})</span></a>
            <a href="{{route('labOrders', ['filter '=> base64_encode(4)])}}" class="filter-1 @if($filter == 4) active @endif" filter="4">Cancelled  <span>({{getLabOrdersCount(4)}})</span></a>
          </div>
      </div>
      <div class="lab-order-section" id="LoadLabOrders">

          <div class="emptylabOrders cartEmpty" style="display:none;">
          	<div class="cartEmpty1234">
              <h3>Lab Orders Not Found</h3>
              <div class="order-track book-test-now">
                  <a href="{{route('LabDashboard')}}">Book Test Now</a>
              </div>
              </div>
          </div>
      </div>
      <div class="orderDiv LabBlankDivLoader labDiv_1" lastid="4" style="display:none;">
          <div class="lab-order-id-section labDiv_2">
              <div class="order-id labDiv_3">
                  <h3></h3>
              </div>
              <div class="order-track labDiv_4">
                  <a href=""></a>
              </div>
          </div>
          <div class="product-details labDiv_5">
              <div class="product labDiv_6">
                  <div class="items12 labDiv_7">
                      <div class="items labDiv_8">
                          <div class="section-1 labDiv_9">

                              <p class="product-name labDiv_10">
                                  <a href=""></a>
                              </p>
                              <div class="labDiv2_10">
                                  <p class="labDiv_11"></p>
                                  <p class="tets labDiv_12"></p>
                              </div>

                          </div>
                          <div class="section-2 labDiv_13">
                              <h4 class="product-cost labDiv_14"></h4>
                          </div>
                      </div>
                  </div>
                  <div class="section-top-1 labDiv_15">
                      <table cellpadding="0" cellspacing="0" class="labDiv_16">
                          <thead>
                              <tr>
                                  <td><span class="labDiv2_15"></span></td>
                                  <td><span class="labDiv2_15"></span></td>
                              </tr>
                          </thead>

                          <tbody>
                              <tr class="labDiv_18">
                                  <td><strong class="labDiv2_18"> </strong></td>
                                  <td style="line-height:24px;">
                                      <span class="labDiv3_18"></span>
                                      <br>
                                      <span class="labDiv4_18"></span>
                                      <br>
                                      <span class="labDiv5_18"></span>
                                      <br> <span class="labDiv6_18"></span>
                                  </td>
                              </tr>
                          </tbody>
                      </table>

                  </div>
              </div>
              <div class="order-timing-details labDiv_19">
                  <div class="order-date labDiv_20">
                      <span></span> <strong></strong>
                  </div>
                  <div class="order-amount labDiv_21">
                      <span></span> <strong></strong>
                  </div>
              </div>
          </div>
      </div>
      <div class="noMoreRecordFound" style="display:none;">
        <h3>No More Records Found !</h3>
      </div>
  </div>
</div>
</div>
</div>
<script src=" {{ URL::asset('js/jquery.steps.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function(){
     var action = 'inactive';
    // // alert($(window).scrollTop());
    // alert($(window).height());
    // // alert($(window).scrollTop() + $(window).height());
    // alert($("#LoadLabOrders").height());

  // var action = 'inactive';
  function LoadLabOrders(lastId) {
    $('.LabBlankDivLoader').show();
    var filter = $('.filters').find('.active').attr('filter')
    action = 'active';
    var token = $('meta[name="csrf-token"]').attr('content');
    jQuery.ajax({
    type: "POST",
    dataType : "HTML",
    url: "{!! route('labOrders') !!}",
    data: {'_token':token,'lastId':lastId, 'filter':filter},
    success:function(data)
    {
      $('.LabBlankDivLoader').hide();
     $('#LoadLabOrders').append(data);
     if(data == '')
     {
      action = 'active';
      if ($(".LabOrderList").length == '0') {
        $('.cartEmpty').show();
      }
      else {
        $('.noMoreRecordFound').show();

      }
      clearInterval();
     }
     else
     {
      action = "inactive";
     }
   },
      error: function(error)
      {
        if(error.status == 401)
        {
            alert("Session Expired,Please logged in..");
            location.reload();
        }
        else
        {
          jQuery('.loading-all').hide();
          alert("Oops Something goes Wrong.");
          jQuery('#saveAddress').attr('disabled',false);
        }
      }
   });
  }

if(action == 'inactive')
{
 action = 'active';
 var lastId = null;
 LoadLabOrders(lastId);
}

    $(window).scroll(function(){

      if($(window).scrollTop() + $(window).height() > $("#LoadLabOrders").height() && action == 'inactive')
      {
       action = 'active';
       var lastId = $(".LabOrderList").last().attr('lastId');
       setTimeout(function(){
        LoadLabOrders(lastId);
       }, 1000);
      }
     });
  //
  // window.setInterval(function(){
  //   if(action == 'inactive')
  //   {
  //    action = 'active';
  //    var lastId = $(".orderDiv").last().attr('lastId');
  //    setTimeout(function(){
  //     LoadLabOrders(lastId);
  //    }, 1000);
  //   }
  // }, 1000);



});
</script>
@endsection
