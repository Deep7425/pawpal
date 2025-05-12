<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Health Gennie Appointment Report</title>
  </head>
  <body>
  	<div style="width: 100%; float:left;">
  	<div style="width:765px; margin: 0px auto; max-width:765px;">
  	<div style="width: 100%; float:left; padding: 0px;">
  		<div style="width:765px; margin-left: -20px; margin-top: -30px;padding-bottom: 8px;">
    		<div style="width: 365px; float: left;"><p style="color: #000; font-size: 14px;"><strong><span style="color: #189ad4;"><img style="width:80px;" src="{{ URL::asset('img/logo.png') }}" /></span></strong> </p></div>
    		<div style="width: 365px; margin-left: 430px;"><p style="color: #000; font-size: 14px;"><strong>Report Generation Date : <span style="color: #189ad4; padding-right: 10px;">{{ date('d-m-Y') }}</span>Time : <span style="color: #189ad4;">{{date('h:m a')}}</span></strong> </p></div>
    	</div>
    	<div style="width:765px; margin-left: -20px; margin-top:10px; border-top: 1px solid #189ad4; ">
    	<h2 style="color: #189ad4; font-size: 18px;">Health Gennie Appointment Report</h2>
    	<table style="width: 765px;">
    	 <tbody style="width: 765px;">
    	 	<tr style="width:765px;">
    	 		<td style="font-size: 13px;"><strong>From Date</strong><span style="color: #189ad4; margin-right: 10px; margin-left: 10px;"> :</span>@if((app('request')->input('start_date'))!=''){{ base64_decode(app('request')->input('start_date')) }}@endif<span style=" margin-left: 10px;"> <strong> To</strong></span> <span style="color: #189ad4; margin-right: 10px; margin-left: 10px;"> :</span>@if((app('request')->input('end_date'))!=''){{ base64_decode(app('request')->input('end_date')) }}@endif</td>
    	 	</tr>
        <tr>
          <td>Total Appointments : {{count($appointments)}}</td>
        </tr>
    	 </tbody>
    	</table>
    	</div>
    <div style="width:765px; margin-top:30px; margin-left: -20px;">
    	<div style="width:765px; margin-left: 0px; margin-right: 0px;">
        <table style="border: 1px solid #ccc; width: 765px; table-layout:fixed;" cellpadding="0" cellspacing="0"> 
          <thead style="background:#189ad4; color: #fff; width: 765px;">
            <tr style="width: 765px;">
              <th style="width:10px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">S.No.</th>
              <th style="width:100px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Name</th>
              <th style="width:44px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Gender</th>
              <th style="width:70px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">DOB</th>
              <th style="width:70px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Phone Number</th>
              <th style="width:60px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Type</th>
              <th style="width:80px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Doctor</th>
              <th style="width:100px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Appointment Time</th>
              <th style="width:60px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Consultation Fee (Rs.)</th>
              <th style="width:60px; color: #fff; text-align:left; padding: 4px 4px; border: 0px; font-size: 12px;">Total Pay (Rs.)</th>
            </tr>
          </thead>
          <tbody style="width: 765px;">
            @if(count($appointments) > 0)
            <?php $i=1; $total_amount=0;$tot_pay=0;?>
            @foreach($appointments as $index => $appointment)
            <tr style="width: 765px;">
              <td style="width:10px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;"  class="service"><?php echo $i++; ?>.</td>
              <td style="width:100px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">{{@$appointment->patient->first_name.' '.@$appointment->patient->last_name}}</td>
              <td style="width:44px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">{{@$appointment->patient->gender}}</td>
              <td style="width:70px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">{{date('d-m-Y', @$appointment->patient->dob)}}</td>
              <td style="width:70px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">{{@$appointment->patient->mobile_no}}</td>
              <td style="width:60px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">@if($appointment->type == "3") Tele Consult @else In Clinic @endif</td>
              <td style="width:80px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">Dr.{{getDoctorName($appointment->doc_id)}}</td>
              <td style="width:100px;text-align: left; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">{{date('d-m-Y h:i A', strtotime($appointment->start))}}</td>
              <td style="width:60px;text-align: right; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">@if(!empty($appointment->AppointmentOrder)){{number_format(@$appointment->AppointmentOrder->order_subtotal,2)}}@else {{number_format($appointment->consultation_fees,2)}}@endif</td>
              @if(@$appointment->AppointmentOrder->type == 0)  
              <td style="width:60px;text-align: right; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">
				@if(!empty($appointment->AppointmentOrder))
					0.00
				@else
					{{number_format($appointment->consultation_fees,2)}}
				@endif	
                </td>
              @else
                <td style="width:60px;text-align: right; padding: 4px 4px; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc; font-size: 12px;" class="desc">
				@if(!empty($appointment->AppointmentOrder))
                {{number_format(@$appointment->AppointmentOrder->order_total,2)}}
				@else
				{{number_format($appointment->consultation_fees,2)}}	
				@endif	
				</td>
              @endif  
            </tr>
			      <?php 
				  $total_amount+=@$appointment->AppointmentOrder->order_subtotal;
				  if(!empty($appointment->AppointmentOrder) && $appointment->AppointmentOrder->type != 0) {
					 $tot_pay+=$appointment->AppointmentOrder->order_total;
				  }
              
            ?>
            @endforeach
			      <tr>
               <td colspan="8" style="text-align:right; border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;padding: 4px; font-size: 12px;"><strong>Total Fees Collected Rs.  </strong></td>
                <td style="text-align:right;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;padding: 4px; font-size: 12px;"><strong>{{number_format($total_amount,2)}}</strong><span>/-</span></td>
                <td style="text-align:right;border-bottom: 1px solid #ccc;padding: 4px; font-size: 12px;"><strong>{{number_format($tot_pay,2)}}</strong><span>/-</span></td>
            </tr>
             
           
            @else
              <tr><td colspan="9">No Record Found </td></tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
    <footer style="width:1000px; float:left; position: absolute; margin-left:-50px;margin-top:0px; bottom: -42px; left:0px; right:0px; color: #000; padding-right: 50px; padding-top: 8px; padding-bottom: 8px; background: #f1f1f1; height: 45px;">
    <div style="width:100px; float:left; padding: 0px 0px 0px 36px;"><img style="width:80px;" src="{{ URL::asset('img/logo.png') }}" /></div>
    <div style="width:680px; float:left; padding: 13px 0px 0px 100px; text-align:right;">Powered By Healthgennie</div>
    </footer>
    </div>
    </div>
    </div>
  </body>
</html>
