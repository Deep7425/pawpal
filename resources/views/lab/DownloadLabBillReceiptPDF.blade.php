<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Health gennie Lab</title>

  </head>
  <body style="font-family: 'Open Sans', sans-serif !important;">
  	<table cellpadding="0" cellspacing="0" style="width: 100%; margin-left:0px;">
  		<thead>
  			<tr>
  				<th colspan="2" style="color: #000; font-size:15px; padding-bottom: 20px;"><img style="width:80px;" src="{{ URL::asset('img/logo-section-top.png') }}" /></th>
  			</tr>
  			<tr>
  				<th colspan="2" style="color: #000; font-size:20px; padding-bottom: 10px;"></th>
  			</tr>
  		</thead>
  		<tbody>
  			<tr>
  				<td colspan="2" style="padding: 0px 0px 20px;">
  					<table cellpadding="0" cellspacing="0" style="width: 100%;">
  						<tbody>
  							<tr>
  								<td colspan="2" style="text-align:left; color: #189ad4; font-size:18px; padding: 5px 10px;background: #f1f1f1;">Lab Details </td>
  							</tr>
							<tr>
							<td colspan="2">
							<table cellpadding="0" cellspacing="0" style="width:100%;">
							<tbody>
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">Order ID</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">{{$order->orderId}}</td>
							</tr>
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">Date</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">@if($order->type == 0) @if(isset($orderAPI['leadHistoryMaster'][0]['bookedOn'][0]['date'])){{date('jS M, y',strtotime($orderAPI['leadHistoryMaster'][0]['bookedOn'][0]['date']))}} @endif @else {{date('jS M Y',$order->appt_date)}} @endif</td>
							</tr>
							@if(!empty($order->user->first_name))
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">Name</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">{{$order->user->first_name}} {{$order->user->last_name}}  ({{strtoupper(trim(get_patient_age($order->user->dob)))}} / {{$order->user->gender }})</td>
							</tr>
							@endif
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">Mobile No.</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">{{$order->user->mobile_no}}</td>
							</tr>
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">Address</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">{{$order->user->address}}</td>
							</tr>
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">Report Hard Copy</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">{{$order->report_type}}</td>
							</tr>
							</tbody>
							</table>
							</td>
							</tr>
							<tr>
							<td colspan="2">
							<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<thead>
							<tr>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Lab Name</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px; width:60px;">Price</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px; width:60px;">Discount</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Amount (Rs.)</th>
							</tr>
							</thead>
							<tbody>
							@php $data =  json_decode($order->meta_data, true); $i = 0; @endphp
							@if(count($order->LabOrderedItems)>0)
								 @foreach($order->LabOrderedItems as $raw)
									<tr>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$raw->product_name}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">@if(isset($data['items'][$i]['rate']['b2C'])) {{@$data['items'][$i]['rate']['b2C']}} @else {{$raw->cost + $raw->discount_amt}} @endif</td>
									<td style="text-align:center; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">
									@if(isset($data['items'][$i]['rate']['b2C'])) {{@$data['items'][$i]['rate']['b2C'] - $data['items'][$i]['rate']['offerRate']}} @else {{$raw->cost}}@endif</td>
									<td style="text-align:center; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">
									@if(isset($data['items'][$i]['rate']['offerRate'])) {{@$data['items'][$i]['rate']['offerRate']}} @else {{$raw->discount_amt}} @endif</td>
									
							
									</tr>
								@endforeach
							@endif
							<tr>
							<th colspan="3" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px; border-top:1px solid #ccc;">Payment Mode</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;border-top:1px solid #ccc;">{{$order->pay_type}}</th>
							</tr>
							<tr>
							<th colspan="3" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px; border-top:1px solid #ccc;">Tax (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;border-top:1px solid #ccc;">0.0</th>
							</tr>
							<tr>
							<th colspan="3" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">Total Price Discount (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">{{$order->discount_amt}}</th>
							</tr>
							<tr>
							<th colspan="3" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">Coupon Discount (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">@if(!empty($order->coupon_amt)) {{$order->coupon_amt}} @else 0.00  @endif</th>
							</tr>
							<tr>
							<th colspan="3" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px; border-top:1px solid #ccc;border-bottom:1px solid #ccc;">Payed Amount (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding:2px 6px; font-size:11px; border-bottom:1px solid #ccc;border-top:1px solid #ccc;">{{$order->payable_amt}}</th>
							</tr>
							</tbody>
							</table>
							</td>
							</tr>
						</tbody>
  					</table>
  				</td>
  			</tr>
  			<tr>
  				<td style="font-size:11px; color: #000; padding: 5px 10px; border-right:0px;background: #f1f1f1;">Powered By Health Gennie</td>
  				<td style="text-align:right; font-size: 13px; padding: 5px 10px; border-left:0px;background: #f1f1f1;"><strong>Help Line Number :</strong> {{getSetting("helpline_number")[0]}}</td>
  			</tr>
  		</tbody>
  	</table>
   
  </body>
</html>
