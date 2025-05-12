@if(count($orderss) > 0)
	@foreach($orderss as $order)
    <div class="orderDiv LabOrderList" lastId="{{$order->id}}">
	  <div class="lab-order-id-section">
          <div class="order-id">
            <h3>Order ID : {{$order->thy_ref_order_no}}</h3>
          </div>
          <div class="order-track">
			  @if(!empty($order->report_url))
			  <p class="report-download"><a download href="{{$order->report_url}}"> <img src="{{ URL::asset('img/report-pdf.png') }}" alt="">  Download Report</a> </p>
				@endif
          </div>
      </div>
      <div class="product-details">
        <div class="product">
          <div class="items12">
            @php $i = 0; @endphp
		@if(!empty($order->thy_order_data) && !empty($order->thy_order_data->ORDER_MASTER))	
          @foreach($order->thy_order_data->ORDER_MASTER as $product)
            <div class="items">
              <div class="section-1">
				<p class="product-name"><a href="javascript:void(0);">{{@$product->PRODUCTS}}</a></p>
				<p>Ideal for individuals aged 41-60 years</p>
              </div>
              <div class="section-2">
              <!--  <h4 class="product-cost">₹ {{@$product->RATE}} </h4>-->
              </div>
            </div>
            @php $i++; @endphp
            @endforeach
		@endif
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
                <td><strong> {{@$order->thy_order_data->BEN_MASTER[0]->STATUS}}</strong></td>
                  <td style="line-height:24px;">
                  <i class="fa fa-user" aria-hidden="true"></i> {{@$order->thy_order_data->BEN_MASTER[0]->NAME}}<br />
                  <i class="fa fa-home" aria-hidden="true"></i> {{@$order->thy_order_data->ORDER_MASTER[0]->ADDRESS}}<br />
                  <i class="fa fa-calendar" aria-hidden="true"></i> {{date('F d Y',strtotime(@$order->thy_order_data->LEADHISORY_MASTER[0]->BOOKED_ON[0]->DATE))}}<br /> 
                  </td>
              </tr>
            </tbody>
            </table>
            </div>
        </div>
		
		<div class="order-timing-details">
            <div class="order-date">
               <span>Ordered On </span> <strong>{{date('jS M, y',strtotime(@$order->thy_order_data->LEADHISORY_MASTER[0]->BOOKED_ON[0]->DATE))}}</strong>
            </div>
			<div class="order-amount">
				<span>Booked By </span> <strong>Health Gennie</strong>
            </div>
        </div>
		
      </div>
    </div>
	@endforeach
@endif