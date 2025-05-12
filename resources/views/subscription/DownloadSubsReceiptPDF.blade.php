<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Health gennie Subscription</title>

  </head>
  <body style="font-family: 'Open Sans', sans-serif !important;">
  	<table cellpadding="0" cellspacing="0" style="width: 760px; margin-left:-23px;">
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
  								<td colspan="2" style="text-align:left; color: #189ad4; font-size:18px; padding: 5px 10px;background: #f1f1f1;"> Subscription Details </td>
  							</tr>
							<tr>
							<td colspan="2">
							<table cellpadding="0" cellspacing="0" style="width:100%;">
							<tbody>
							@if(!empty($subscription->User->first_name))
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">Name</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 6px 8px 2px; font-size:12px;">{{$subscription->User->first_name}} {{$subscription->User->last_name}}  ({{strtoupper(trim(get_patient_age($subscription->User->dob)))}} / {{$subscription->User->gender }})</td>
							</tr>
							@endif
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px; font-size:12px;">Transaction Id</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px; font-size:12px;">{{$subscription->UserSubscriptionsTxn->tracking_id}} </td>
							</tr>
							<tr>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">Mobile No.</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">:</td>
							<td style="text-align:left; color: #000; font-size:18px; padding: 2px 8px 6px; font-size:12px;">{{$subscription->User->mobile_no}}</td>
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
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Plan Name</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px; width:60px;">Start</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px; width:60px;">End</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Free Appointments</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Available Appointments</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px; width:35px;">Status</th>
							<th style="text-align:left; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Payment Mode</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 5px 6px;background: #f1f1f1; font-size:11px;">Amount (Rs.)</th>
							</tr>
							</thead>
							<tbody>
							@if(count($subscription->UserSubscribedPlans)>0)
								@foreach($subscription->UserSubscribedPlans as $plan)
									<?php $meta_data = $plan->meta_data;
										$meta_data = json_decode($plan->meta_data);
										$plan_title = $meta_data->plan_title;
									?>
									<tr>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$plan_title}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{date("d-m-Y",strtotime(@$subscription->PlanPeriods->start_trail))}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{date("d-m-Y",strtotime(@$subscription->PlanPeriods->end_trail))}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$plan->appointment_cnt}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$subscription->PlanPeriods->remaining_appointment}}</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">
										@if($subscription->PlanPeriods->status == '1')
											Active
										@else
											Deactive	
										@endif	
									</td>
									<td style="text-align:left; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$subscription->UserSubscriptionsTxn->card_name}}</td>
									<td style="text-align:center; color: #000; font-size:18px; padding: 5px 6px; font-size:11px;">{{$subscription->UserSubscriptionsTxn->payed_amount}}</td>
									</tr>
								@endforeach
							@endif
							<tr>
							<th colspan="7" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px; border-top:1px solid #ccc;">Tax (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;border-top:1px solid #ccc;">{{$subscription->tax}}</th>
							</tr>
							<tr>
							<th colspan="7" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">Discount (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding: 2px 6px; font-size:11px;">{{$subscription->coupon_discount}}</th>
							</tr>
							<tr>
							<th colspan="7" style="text-align:right; color: #000; font-size:18px; padding: 2px 6px; font-size:11px; border-top:1px solid #ccc;border-bottom:1px solid #ccc;">Payed Amount (Rs.)</th>
							<th style="text-align:center; color: #000; font-size:18px; padding:2px 6px; font-size:11px; border-bottom:1px solid #ccc;border-top:1px solid #ccc;">{{$subscription->order_total}}</th>
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
