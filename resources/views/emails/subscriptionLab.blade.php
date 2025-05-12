<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap" rel="stylesheet">
    <title></title>
  </head>
    <body style=" background:#d1d3d4; padding-top:0; padding-bottom:0; padding-top:0; padding-bottom:0;">
    <style type="text/css">
    	body{ font-family:Arial, Helvetica, sans-serif;}
		td{ font-family:Arial, Helvetica, sans-serif;}
		th{ font-family:Arial, Helvetica, sans-serif; font-size:13px;}
		
    </style>
     <table cellspacing="0" cellpadding="0" width="780" style="margin:auto; background: #fff ;padding: 10px; font-family:Arial, Helvetica, sans-serif;">
     <tbody>
     	<tr>
     		<td style=" width:35%;text-align: left;"><p style="margin: 0px; padding: 0px;"><a href="https://www.healthgennie.com/" target="_blank"><img width="120" src="http://103.66.73.17/patient_portal/img/logosubscription.png"/></a></p></td>
            <td style=" width:50%; padding: 0px 0px 0px 0px; text-align: left; color: #2f2f22f; font-size: 18px;" > <img width="120" src="http://103.66.73.17/patient_portal/img/Congratulations-icon.png"></td>
     	</tr>
     </tbody>
     
     <tbody>
     	<tr>
        	<td colspan="2" style=" padding-top:0px;">
            	<table cellpadding="0" cellspacing="0" width="100%">
                	     	<tr>
     		<td style=" padding: 0px 0px 15px 0px; text-align: center; color: #2f2f22f; font-size: 18px;" ><strong style="color: #f15a23;"> Congratulations !!</strong><br>You are now a Health Gennie Elite member!<br></td>
     	</tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                <th style=" background: #f15a23;font-size: 15px; color: #14bef0;color: #fff; padding: 10px 10px 10px; text-align: left;">
                                    <h2 style="font-family:Arial, Helvetica, sans-serif; padding: 0px; margin: 0px;font-size: 15px;">Subscription Details</h2>
                                </th>
                                <td style=" background: #f15a23;text-align: right; font-size: 13px; color: #fff; padding: 10px 10px 10px;">Date :  {{date('F d Y g:i A',strtotime($UsersSubscriptions->created_at))}}</td>
                            </tr>
                            </tbody>	
                            </table>
                            </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0px 0px;"></td>
                            </tr>
                            <tr>
                            <td>
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <th style="text-align: left; font-size: 13px; color: #333; padding: 10px 10px 10px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; font-family:Arial, Helvetica, sans-serif;">{{$UsersSubscriptions->user->first_name}} {{$UsersSubscriptions->user->last_name}}</th>
                                            <td style="text-align: right; font-size: 13px; color: #333; padding: 10px 10px 10px; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">{{$UsersSubscriptions->user->email}}<br>{{$UsersSubscriptions->user->mobile_no}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0px 0px;"></td>
                            </tr>
                            <tr>
                                <td style="font-size: 12px; font-family:Arial, Helvetica, sans-serif;">
                                    <table cellpadding="0" cellspacing="0" style="width: 100%; font-family:Arial, Helvetica, sans-serif;">
                                        <tbody>
                                            <tr>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left; width:110px;">Name</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left;">Duration</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left; width:110px;">Start Date</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left; width:110px;">End Date</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: center;"> Appointments</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left; width:130px;">Lab Package</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align: left;">Price</th>
                                                <th style="font-family:Arial, Helvetica, sans-serif;font-size: 13px;background: #f1f1f1; padding: 5px 8px; color: #333; text-align:right;">Discount</th>
                                            </tr>
											 @if($UsersSubscriptions->UserSubscribedPlans->count() > 0)
												@foreach($UsersSubscriptions->UserSubscribedPlans as $index => $plan)
													@php $plan_data = json_decode($plan->meta_data); @endphp
													<tr>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; width:110px;">{{@$plan_data->plan_title}}</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px;">{{@$plan_data->plan_duration}}@if($plan_data->plan_duration_type == "d") Day @elseif($plan_data->plan_duration_type == "m") Month @elseif($plan_data->plan_duration_type == "y") Year @endif</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; width:110px;">{{date('d-m-Y',strtotime(@$plan->PlanPeriods->start_trail))}}</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; width:110px;">{{date('d-m-Y',strtotime(@$plan->PlanPeriods->end_trail))}}</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; text-align:center;">{{@$plan->PlanPeriods->remaining_appointment}}</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; ">{{@$plan_data->lab_pkg_title}}</td>
														<td style="padding: 5px 8px; color: #333; text-align: left; font-size:13px; ">₹ {{@$plan_data->price}}</td>
														<td style="padding: 5px 8px; color: #333; text-align:right; font-size:13px;">₹ {{@$plan_data->discount_price}}</td>
													</tr>
													@endforeach
												@else
													<tr><td colspan="9">No Record Found</td></tr>
												@endif
                                            <tr>
                                                <th valign="top" colspan="7" style="font-family:Arial, Helvetica, sans-serif;font-size:13px; padding: 5px 8px 0px; color: #333; text-align:right; border-top: 1px solid #ccc;">Payment Mode :</th>
                                                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; width:200px;font-size:13px;padding: 5px 8px 0px; color: #333; text-align:right; border-top: 1px solid #ccc;">@if($UsersSubscriptions->payment_mode == "1") Online Payment @elseif($UsersSubscriptions->payment_mode == "2") Cheque @elseif($UsersSubscriptions->payment_mode == "3") Cash @endif</td>
                                            </tr>
                                            <tr>
                                                <th colspan="7" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;padding: 0px 8px; color: #333; text-align:right;">Total Amount :</th>
                                                <td style="font-size:13px;padding: 0px 8px; color: #333; text-align:right;">₹ {{$UsersSubscriptions->order_subtotal}}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="7" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;padding: 0px 8px; color: #333; text-align:right;">Price Discount :</th>
                                                <td style="font-size:13px;padding: 0px 8px; color: #333; text-align:right;">- ₹ @if(!empty($UsersSubscriptions->discount_price)) {{$UsersSubscriptions->discount_price}} @else 0.00  @endif</td>
                                            </tr>
                                            <tr>
                                                <th colspan="7" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;padding: 0px 8px; color: #333; text-align:right;">Tax :</th>
                                                <td style="font-size:13px;padding: 0px 8px; color: #333; text-align:right;">₹ {{$UsersSubscriptions->tax}}</td>
                                            </tr>
                                            <tr>
                                                <th colspan="7" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;padding: 0px 8px 5px; color: #333; text-align:right; border-bottom: 1px solid #ccc;">Paid Amount : </th>
                                                <td style="font-size:13px;padding: 0px 8px 5px; color: #333; text-align:right; border-bottom: 1px solid #ccc;"><strong>₹ {{$UsersSubscriptions->order_total}}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th style="font-family:Arial, Helvetica, sans-serif;text-align:center; padding: 15px 0px 5px 0px; color:#14bef0; font-size: 18px;">For Elite membership benefits download the Health Gennie app</th>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" style="width:100%;">
                                        <tbody>
                                            <tr>
                                                <td style="text-align:right; padding: 0px 8px 6px 0px;"><img width="100" src="http://103.66.73.17/patient_portal/img/app-google-icon.png"></td>
                                                <td style="text-align:left; padding: 0px 8px 6px 0px;"><img width="100" src="http://103.66.73.17/patient_portal/img/app-store-icon.png"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" style="width:100%;">
                                        <tbody>
                                            <tr>
                                                <td style=" font-weight:600; text-align:left; padding: 5px 10px 5px 10px; color: #fff; font-size: 13px; background: #333;">Fitkid Health Tech Pvt. Ltd. All Rights Reserved</td>
                                                <td style=" font-weight:600; padding: 5px 10px 5px 10px;text-align:right; color: #fff; font-size: 13px; background: #333;">C-94 Lal Kothi Scheme, Jaipur</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" style="width:100%;">
                                        <tbody>
                                            <tr>
                                                <td style=" font-weight:600; text-align:left; padding: 5px 10px 5px 10px; color: #333; font-size: 14px; background: #fff;">Call us at – +91-8302072136 <br> Email : info@healthgennie.com</td>
                                                <td style="font-weight:600; padding: 15px 10px 5px 10px;text-align:right; color: #333; font-size: 14px; background: #fff;">
                                                    <table cellpadding="0" cellspacing="0" align="right">
                                                        <tr>
                                                            <td style=" padding-right:5px;"><a style="padding-left:5px;" href="#"><img width="30" src="http://103.66.73.17/patient_portal/img/ficon-image.png"></a></td>
                                                            <td style=" padding-right:5px;"><a style="padding-left:5px;" href="#"><img width="30" src="http://103.66.73.17/patient_portal/img/ticon-image.png"></a></td>
                                                            <td style=" padding-right:5px;"><a style="padding-left:5px;" href="#"><img width="30" src="http://103.66.73.17/patient_portal/img/intaicon-image.png"></a></td>
                                                            <td><a style="padding-left:5px;" href="#"><img width="30" src="http://103.66.73.17/patient_portal/img/inicon-image.png"></a></td>
                                                    </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                </table>
            </td>
        </tr>
     </tbody>	
     </table>
</body>
  </html>