@if(count($orders) > 0)
  @foreach($orders as $order)
  
  <?php $data =  json_decode($order->meta_data, true);
  
   $xml = simplexml_load_string($data['bendataxml']);
   $json = json_encode($xml);
   $patient = json_decode($json,TRUE);
   $coupon_code = "";
   if(!empty($order->Coupons)){
		$coupon_code = $order->Coupons->coupon_code;
   }
   ?>
    <div class="orderDiv LabOrderList" lastId="{{$order->id}}">
		@if(!empty($order->plan_id))
		<div class="elite-member lab-order-time-elite">
			<div class="bg">&nbsp;</div>
			<div class="text">Elite</div>
		</div>
		@endif
	  <div class="lab-order-id-section">
          <div class="order-id">
            <h3>Order ID : {{$order->orderId}}</h3> 
          </div>
          <div class="order-track">
            <a href="{{route('labOrderDetails', ['orderid '=> base64_encode($order->orderId)])}}">View Order</a>
          </div>
      </div>
      <div class="product-details">
        <div class="product">
          <div class="items12">
            @php $i = 0; @endphp
          @foreach($order->LabOrderedItems as $product)
            <div class="items">
              <div class="section-1">
				
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
                <p>Ideal for individuals aged 41-60 years</p>
				@else 
					<p class="product-name">  <a href="{{route('LabDetails', ['id'=> base64_encode($product->product_name), 'type' => base64_encode($product->item_type)])}} ">{{$product->product_name}}</a> </p>
					<p>Ideal for individuals aged 41-60 years</p>
				@endif	
                <p class="tets">Tests Included : {{@$data['items'][$i]['test_count']}}</p>


              </div>
              <div class="section-2">
                <h4 class="product-cost">@if($coupon_code == "HGSUBSCRIBED") ₹ 0 @else ₹ {{$product->cost}} @endif</h4>
              </div>
            </div>
            @php $i++; @endphp
            @endforeach
            </div>
            <div class="section-top-1">
            <table cellpadding="0" cellspacing="0">
            <thead>
              <tr>
                <td>Order Status</td>
                  <td>View Details</td>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td><strong> {{$order->order_status}}</strong></td>
                  <td style="line-height:24px;">
                  <i class="fa fa-user" aria-hidden="true"></i> {{$patient['Ben_details']['Name']}}<br />
                  <i class="fa fa-home" aria-hidden="true"></i> {{$data['address']}}<br />
                  <i class="fa fa-calendar" aria-hidden="true"></i> {{date('F d Y',$order->appt_date)}}<br /> <i class="fa fa-clock-o" aria-hidden="true"></i> {{date('h:i A',$order->appt_date)}} : {{date('h:i A',$order->appt_date+3600 )}}
                  </td>
              </tr>
            </tbody>
            </table>



            </div>
        </div>
        <div class="order-timing-details">
            <div class="order-date">
              <span>Ordered On </span> <strong>{{date('jS M, y',strtotime($order->created_at))}}</strong>
            </div>
            <div class="order-amount">
              <span>Order Total </span> <strong>@if($coupon_code == "HGSUBSCRIBED") ₹ 0 @else  ₹ {{$order->payable_amt}} @endif</strong>
            </div>
        </div>
      </div>
    </div>

    @endforeach

    @endif
