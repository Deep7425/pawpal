@extends('amp.layouts.Masters.Master')
@section('title', 'Lab Order detail | Health Gennie')
@section('description', "Health Gennie portal to get your lab details in pdf format tested by NABL or ISO certified lab.")
@section('content')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<div class="dashboard-wrapper dashboard-plan-wrapper @if(isset($_COOKIE["in_mobile"]) && $_COOKIE["in_mobile"] == '0') sideband-menu-bar @endif">
@include('users.sidebar')
<div class="dashboard-right">
<div class="lab-test lab-test-profile">
  <div class="container-fluid">
    <div class="container">
      <div class="order-overview">
        <div class="left">
          <div class="lab-order-section">
            <?php
            $checkTime =  strtotime($order->created_at);
            $loginTime =  time();
            $diff =  $loginTime - $checkTime;
            $minutes = floor($diff / 60);

				$coupon_code = "";
			   if(!empty($coupanDetails)){
					$coupon_code = $coupanDetails->coupon_code;
			   }
             ?>
            <input type="hidden" name="diff" id="orderTime" value="{{$minutes}}"/>
              <div class="orderDiv">
                <div class="lab-order-id-section">
                    <div class="order-id">
                      <h3>Order ID : {{$order->orderId}}</h3>
                    </div>


                    @if($order->order_status == 'ASSIGNED' || $order->order_status == 'ACCEPTED' || $order->order_status == 'YET TO ASSIGN' || $order->order_status == 'YET TO CONFIRM' || $order->order_status == 'Y')
                      <div class="order-track">
                        <a href="javascript:void(0)" class="cancelOrder" order="{{$order->orderId}}">Cancel</a>
                      </div>
                     @endif


                </div>
                <div class="product-details inner">

                <table cellpadding="0" cellspacing="0">
                	<tbody>
                      @php $data =  json_decode($order->meta_data, true); $i = 0; @endphp
                    @foreach($order->LabOrderedItems as $product)
                    	<tr>
                        	<td>
							@if($coupon_code == "HGSUBSCRIBED")
								<?php 
									$product_name = "";
									$meta_data = @$order->PlanPeriods->UserSubscribedPlans->meta_data;
									if(!empty($meta_data)){
										$plan_data = json_decode($meta_data);
										$product_name =  $plan_data->lab_pkg_title; 
									}
								?>	
								<p class="product-name">  <a href="javascript:void(0);">{{$product_name}}</a> </p>
							@else
								<p class="product-name"> <a href="{{route('LabDetails', ['id'=> base64_encode($product->product_name), 'type' => base64_encode($product->item_type)])}} ">{{$product->product_name}}</a> </p>
							@endif
                            </td>
                            <td><p>Ideal for individuals aged 41-60 years</p><p class="tets">Tests Included : {{@$data['items'][$i]['test_count']}}</p></td>
                            @if($coupon_code == "HGSUBSCRIBED")
								<td> ₹ 0</td>
							@else
								<td> ₹ {{$product->cost}}</td>
							@endif	
                        </tr>
                          @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>

                 <?php $data =  json_decode($order->meta_data, true);
                          // dd($data);
                          $xml = simplexml_load_string($data['bendataxml']);
                          $json = json_encode($xml);
                          $patient = json_decode($json,TRUE);
                           ?>
                <table cellpadding="0" cellspacing="0" class="tab-order-detail">
                	<tr>
                    	<td>
                        	<h3>User Details</h3>
                            <p class="info-row">
                              <i class="fa fa-user" aria-hidden="true"></i>
                              {{$patient['Ben_details']['Name']}}<br /></p>
                         <p class="info-row">
                              <i class="fa fa-calendar" aria-hidden="true"></i>
                              {{$patient['Ben_details']['Age']}}<br /></p>
<p class="info-row">
                              <i class="fa fa-transgender" aria-hidden="true"></i>
                              @if($patient['Ben_details']['Gender'] == 'M')  Male @else Female @endif<br /></p>
<p class="info-row">
                              <i class="fa fa-envelope" aria-hidden="true"></i>
                              {{$data['email']}}<br /></p>
<p class="info-row">
                              <i class="fa fa-phone" aria-hidden="true"></i>
                              {{$data['mobile']}}<br /></p>
<p class="info-row">
                              <i class="fa fa-home" aria-hidden="true"></i>
                              <span class="order-address">{{$data['address']}}</span><br /></p>
<p class="info-row">
                             <i class="fa fa-calendar" aria-hidden="true"></i>
                             {{date('F d Y',$order->appt_date)}}<br /></p>
<p class="info-row">
                              <i class="fa fa-clock-o" aria-hidden="true"></i>
                              {{date('h:i A',$order->appt_date)}} : {{date('h:i A',$order->appt_date+3600 )}}</p>


                        </td>
                    </tr>
                </table>



                  <div class="orders-track  @if($order->order_status == 'CANCELLED') orderCancel @endif">
                    <div class="order-confirm @if($order->order_status == 'YET TO ASSIGN' || $order->order_status == 'YET TO CONFIRM' || trim($order->order_status) == 'Y' ||  $order->order_status == 'COLLECTED' || $order->order_status == 'REPORTED' ||  $order->order_status == 'CANCELLED') completed @endif">
                        <span class="bubble"><div>1</div></span>
                        <label for="">ORDER CONFIRMATION</label>
                    </div>
                    <div class="sample-collection @if($order->order_status == 'COLLECTED' || $order->order_status == 'REPORTED') completed @endif">
                    <span class="bubble"><div>2</div></span>
                      <label for="">HOME SAMPLE COLLECTED</label>
                    </div>
                    <div class="report-generate @if($order->order_status == 'REPORTED') completed @endif">
                    	<span class="bubble"><div>3</div></span>
                        <label for="">REPORT GENERATED</label>
                    </div>
                    <!-- <div class="report-generate @if($order->order_status == 'CANCELLED') completed @endif"> -->
                  @if($order->order_status == 'CANCELLED')
                    <div class="report-generate completed">
                    	<span class="bubble"><div>2</div></span>
                        <label for="">Order Cancelled</label>
                    </div>
                    @endif
                  </div>
					@if($order->order_status == 'CANCELLED' && $order->pay_type == 'Prepaid' && $order->status == '1')
						<div class="report-section @if($order->order_status == 'REPORTED') reportSection @endif">
						  <h4>Your order has been cancelled successfully. Your refund will be processed within 10 working days and will be credited to your original payment method.</h4>
						</div>
					@else
					  <div class="report-section @if($order->order_status == 'REPORTED') reportSection @endif">
						  <h4>Test Report will be Available Via</h4>
						  <div class="email">
							  <label>E-Mail</label>
							  <p>{{$data['email']}}</p>
						  </div>
						  <div class="mobile">
							<label for="">SMS</label>
							<p>{{$data['mobile']}}</p>
						  </div>
						  @if($order->order_status == 'REPORTED' || $order->order_status == 'DONE')
							<div class="repor-download">
							  <label for="">Reprt Download</label>
							  <p class="report-download"><a href="{{getLabReportById($order->orderId)}}" download> <img src="{{ URL::asset('img/report-pdf.png') }}" alt=""></a> </p>
							</div>
						  @endif
					  </div>
					@endif  
					<div class="order-timing-details">
                      <div class="order-date">
                        <span>Ordered On </span> <strong>{{date('jS M, Y, g : i A',strtotime($order->created_at))}}</strong>
                      </div>
                      <div class="order-amount">
                        <span>Order Total </span>@if($coupon_code == "HGSUBSCRIBED") <strong>₹ 0</strong>  @else <strong>₹ {{$order->payable_amt}}</strong> @endif
                      </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="right">
          
		
		@if(!empty($coupanDetails) && $coupanDetails->coupon_code != "HGSUBSCRIBED")
        <div class="right-block save-block">
            <div class="coupanApplyedBox">
                <div class="save-icon"><img width="13" height="14" src="{{asset('img/right-icon.png')}}"/> You saved <strong class="applyCouponAmount">₹{{$order->coupon_amt}}</strong></div>
                <p><span class="applyCouponText">Applied Coupon</span> <strong class="applyCouponCode">{{$coupanDetails->coupon_code}}</strong></p>
			</div>
        </div>
		@endif

        <div class="right-block">
            <ul>
                <li>Total
                    <div class="price-tag-wrap new_nir"><span>₹ </span><span class="totalAmount">@if($coupon_code == "HGSUBSCRIBED") 0 @else {{$order->total_amt}}@endif</span></div>
                </li>
				@if($order->report_type == 'yes')
				<li>Hard Copy + Soft Copy
                    <div class="price-tag-wrap new_nir">+ ₹ 75</div>
                </li>
				@endif
				@if($coupon_code != "HGSUBSCRIBED")
					@if(!empty($order->discount_amt))
					<li>Price Discount
						<div class="price-tag-wrap new_nir"><span>- ₹</span><span class="priceDiscount"> {{$order->discount_amt}}</span></div>
					</li>
					@endif
					<li>Coupon Discount
						<div class="price-tag-wrap new_nir"><span>- ₹</span><span class="coupanDiscountAmount">@if(!empty($order->coupon_amt)) {{$order->coupon_amt}} @else 0.00  @endif</span></div>
					</li>
				@endif	
                <li class="total-bill"> Paid Total
                    <div class="price-tag-wrap new_nir"> <span>₹</span><span class="paidAmount">@if($coupon_code == "HGSUBSCRIBED") 0 @else {{$order->payable_amt}} @endif</span></div>
                </li>
            </ul>
            <div class="total-save" style="display:none;">Total Savings
                <div class="total-price new_nir"><strong>₹</strong><strong class="totalSaving"> @if(!empty($order->discount_amt)) {{$order->discount_amt}} @else  0.00  @endif</strong></div>
            </div>

        </div>
      </div>

    </div>
  </div>
</div>
</div>
</div>
</div>

<script type="text/javascript">

function cancelOrder(orderId, cancel_reason) {
  jQuery('.loading-all').show();
  jQuery.ajax({
  type: "POST",
  dataType : "JSON",
  url: "{!! route('cancelOrder') !!}",
  data: {'orderId':orderId, 'cancel_reason':cancel_reason},
  success: function(data){
    jQuery('.loading-all').hide();
      if(data.status == '0') {
					jQuery('.loading-all').hide();
					console.log(data.output.RESPONSE);
				}
        if(data.status == '1') {
          $.alert({
            title: 'Order Cancelled Successfully !',
            content: 'Your Order has been cancelled',
            draggable: false,
            type: 'green',
            typeAnimated: true,
            buttons: {
                ok: function(){
                // location.reload();
				window.location = "{{ route('labOrders') }}";
                },
            }
          });
        }
        else {
          $.alert({
            title: 'oops !',
            content: 'Order has not  been Cancelled ! <br> please contact Health Gennie customer care',
            draggable: false,
            type: 'red',
            typeAnimated: true,
            buttons: {
                ok: function(){
                location.reload();
                },
            }
          });
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
function cancel_reason(current) {

      if ($(current).val().length != "") {
        $('.cancelFieldError').hide();
      }
      else {
        $('.cancelFieldError').show();
      }

      if ($(current).val().length == 200) {
        $('.cancelFieldError').text('Text exceeded 200 characters');
        $('.cancelFieldError').show();
      }
      else {
        $(current).val($(current).val().substr(0, 200));
        $('.cancelFieldError').hide();

      }
}


    $('.cancelOrder').on('click', function(){

      var orderTime = $('#orderTime').val();

      if (orderTime < 10) {
        $.alert({
            title: 'Alert!',
            draggable: false,
            content: 'Please Cancel Order After 10 Minutes!',
        });
        return false;
      }

      var orderid = $(this).attr('order');
        $.confirm({
            title: 'Cancel!',
            draggable: false,
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter Cancellation Reason</label>' +
                '<textarea  placeholder="Write Cancel Reason..." class="cancel_reason form-control" onkeyup="cancel_reason(this)" maxlength="200" required /> </textarea>' +
                '<span  class="cancelFieldError" style="display:none; color:red;"></span>' +
                '</div>' +
                '</form>',
            buttons: {
                formSubmit: {
                    text: 'Submit',
                    btnClass: 'btn-blue',
                    action: function(){
                        var cancel_reason = this.$content.find('.cancel_reason').val();
                        if(!cancel_reason){
                          $('.cancelFieldError').text('Field is Required');
                          $('.cancelFieldError').show();
                            return false;
                        }

                        else if (cancel_reason.length > 200) {
                          $('.cancelFieldError').text('Text exceeded 200 characters');
                          $('.cancelFieldError').show();
                          return false;
                        }

                        cancelOrder(orderid, cancel_reason);
                    }
                },
                cancel: function(){
                    //close
                },
            },
            onContentReady: function(){
                // you can bind to the form
                var jc = this;
                this.$content.find('form').on('submit', function(e){ // if the user submits the form by pressing enter in the field.
                    e.preventDefault();
                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                });
            }
        });
    });
</script>
@endsection
