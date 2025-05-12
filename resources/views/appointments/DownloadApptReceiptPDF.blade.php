<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Health gennie Appointment</title>

  </head>
  <body style="font-family: 'Open Sans', sans-serif !important;">
  	<table cellpadding="0" cellspacing="0" style="width: 730px;">
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
  				<td colspan="2" style=" border: 1px solid #ccc; padding: 0px 50px 20px;">
  					<table cellpadding="0" cellspacing="0" style="width: 100%;">
  						<tbody>
  							<tr>
  								<td colspan="2" style="text-align:center; color: #189ad4; font-size:24px; padding: 5px 10px;"> Appointment Details </td>
  								
  							</tr>
  							<!--<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Appointment No.</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{$appointment->id}}</td>
  							</tr>-->
  							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Patient Name</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{$appointment->patient->first_name}} {{$appointment->patient->last_name}}  ({{strtoupper(trim(get_patient_age($appointment->patient->dob)))}} / {{$appointment->patient->gender }})</td>
  							</tr>
  							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Date/Time</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{ date('d-m-Y g:i A', strtotime($appointment->start)) }}</td>
  							</tr>
							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Consultation Fee (Rs.)</td>
								@if(isset($appointment->AppointmentOrder) && $appointment->AppointmentOrder->type == "0")
									<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;"><strike>{{$appointment->AppointmentOrder->order_total}}</strike> FREE</td>
								@else	
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{ $appointment->consultation_fees }}</td>
								@endif
  							</tr>
							@if(!empty($appointment->AppointmentOrder))
							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Convenience Fee (Rs.)</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{ @$appointment->AppointmentOrder->service_charge }}</td>
  							</tr>
							@endif
							
							@if(isset($appointment->AppointmentOrder) && !empty($appointment->AppointmentOrder->coupon_id))
							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Coupon Discount (Rs.)</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{ @$appointment->AppointmentOrder->coupon_discount }}</td>
  							</tr>
							@endif
							@if(!empty($appointment->AppointmentOrder) && $appointment->AppointmentOrder->type != "0")
							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Paid Amount (Rs.)</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{ @$appointment->AppointmentOrder->order_total }}</td>
  							</tr>
							@endif
							@if($appointment->type != "3")
  							<tr>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #000; padding: 5px 10px;">Clinic Address</td>
  								<td style="border: 1px solid #ccc; font-size: 14px; color: #333; padding: 5px 10px;">{{$doctor->clinic_name}}, {{$doctor->address_1}}, {{getCityName($doctor->city_id)}}</td>
  							</tr>
  							@endif
  						</tbody>
  					</table>
  				</td>
  			</tr>
  			<tr>
  				<td style="font-size:12px; color: #000;border: 1px solid #ccc; padding: 5px 10px; border-right:0px;">Powered By Health Gennie</td>
  				<td style="text-align:right; font-size: 13px;border: 1px solid #ccc; padding: 5px 10px; border-left:0px;"><strong>Help Line Number :</strong>{{getSetting("helpline_number")[0]}}</td>
  			</tr>
  		</tbody>
  	</table>
   
  </body>
</html>
