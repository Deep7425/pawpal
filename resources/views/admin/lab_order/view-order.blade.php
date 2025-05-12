<div class="modal-dialog modal-lg">
    <!-- Modal content-->
<div class="modal-content ">
 <?php $userModule = checkAdminUserModulePermission(52); ?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">View Order</h4>
		</div>
		<div class="modal-body">
		<div class="panel panel-bd lobidrag">
		<div class="HealthGennieOrder">
			<a class="btn btn-primary" href="javascript:void(0);">Health Gennie Order No : <strong>{{$order->orderId}}</strong> </a>
			@if($order->type == 0)<a class="btn btn-primary" href="javascript:void(0);">Order No : <strong>{{$order->ref_orderId}}</strong> </a>@endif
		
		
			@if($order->type != 0 && $order->status == 1)
			<?php $orderStatus =  array("YET TO ASSIGN","YET TO CONFIRM","ASSIGNED","CANCELLED","DONE","ARRIVED","SERVICED","RELEASED","STARTED","ACCEPTED","COLLECTED","RESCHEDULED","Cancllation request","REQUEST TO RELEASE","NON SERVICEABLE",
                    "LEAD","DISPATCHED","CALLBACK","REPORTED","LAB","HUB","FIX APPOINTMENT","PERSUASION","COMPLAINT","REQUEST TO RELEASE");?>	
			<select class="changeSts" orderId="{{$order->id}}">
				@foreach($orderStatus as $status)
				<option value="{{$status}}" @if($order->order_status == $status) selected @endif >{{$status}}</option>
				@endforeach
			</select>
			@else
			<a class="btn btn-primary status" href="javascript:void(0);">Order Status : <strong>{{$order->order_status}}</strong>	
			@endif
			</a>
			@if($orderAPI)
            <a class="btn btn-primary status HealthGennieOrder1" href="javascript:void(0);"> Schedule : <strong>@if($order->type == 0){{date('F d Y',strtotime(@$orderAPI['leadHistoryMaster'][0]['appointOn'][0]['date']))}}, {{date('h:i A',strtotime(@$orderAPI['leadHistoryMaster'][0]['appointOn'][0]['date']))}} : {{date('h:i A',strtotime(@$orderAPI['leadHistoryMaster'][0]['appointOn'][0]['date'])+3600 )}}
			@else {{date('jS M Y',$order->appt_date)}}
			@endif</strong></a>
			@endif
			

			@if($order->type == 0)<a class="btn btn-primary" href="javascript:void(0);">Order No : <strong>{{$order->ref_orderId}}</strong> </a>@endif
			
			@if($userModule)
			@if($order->payment_mode_type != 0)
       <?php
    //   $payment_mode = array("Cash", "Online", "Cheque", "Free", "Payment Link", "Bank/NEFT/RTGS/IMPS", "Credit to CCL", "Credit to RELIABLE", "Credit to LIVING ROOT");
    $payment_mode = array('Cheque' => 2,'Cash' => 3,'Admin Online' => 4, 'Free' => 5 , 'Payment Link online' => 6, 'Bank/NEFT/RTGS/IMPS' => 7, 'Credit to CCL' => 8, 'Credit to RELIABLE' => 9, 'Credit to LIVING ROOT' => 10 ); 
	
	// Cheque = 2,Cash = 3, Admin Online = 4, Free = 5 , Payment Link online = 6, Bank/NEFT/RTGS/IMPS = 7, Credit to CCL = 8, Credit to RELIABLE = 9, Credit to LIVING ROOT = 10 
	 ?>
    <select class="payment_mode_type pay-mode"  name="pay_type" orderId="{{$order->id}}">
        @foreach($payment_mode as $key => $payment)
            <option value="{{$payment}}" @if($order->payment_mode_type == $payment ) selected @endif> {{$key}}</option>
        @endforeach
    </select>
    <span class="help-block payment_mode_typeError"></span>
 @endif  

 @endif



 <div class="form-group col-sm-4 trackBlock" style="display:none;">
					<label>Txn Id:</label>
					<input type="text" class="form-control" id = "tracking_id" placeholder="Txn Id received from paytm payments" value = "{{@$order->LabOrderTxn->tracking_id}}" name="tracking_id" required>
					
						<span class="help-block">
						</span>
					</div>
	
					<div class="form-group col-sm-3 form-groupDate txnDate" style = "display:none;">
										<label>Transaction Date<i class="required_star">*</i></label>
										<div class="date-formet-section">
											<input type="text" id = "trans_date" class="transactionDate form-control" name="trans_date" placeholder="yyyy-mm-dd" value = "{{@$order->LabOrderTxn->trans_date}}"  required/><i  style = "position:absolute; top:35px; right:25px;" class="fa fa-calendar" aria-hidden="true" readonly></i>
											
										</div>
										<span class="help-block"></span>
									</div>

		<button type="submit" class="btn btn-default  submit" style = " display:none;  position: absolute; bottom: 8px; right: 16px;" data-dismiss="modal">Submit</button>
</div>

                 								 
							
				<!-- Your HTML code with the select element -->



		
		 
		<div class="panel-body">
          <h3>Products</h3>
          <table class="table table-bordered table-hover">
              <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Offer Price</th>
                <th>Included Tests</th>
              </tr>
            @php $data =  json_decode($order->meta_data, true); $i = 0; @endphp
            @foreach($order->LabOrderedItems as $product)
              <tr>
                <td>{{$product->product_name}}</td>
                <td>₹ @if(isset($data['items'][$i]['rate']['b2C'])) {{@$data['items'][$i]['rate']['b2C']}} @else {{$product->cost}} @endif</td>
                <td>₹ @if(isset($data['items'][$i]['rate']['offerRate'])) {{@$data['items'][$i]['rate']['offerRate']}} @else {{$product->discount_amt}} @endif</td>
                <td>@if(isset($data['items'][$i]['testCount'])){{@$data['items'][$i]['testCount']}}@endif</td>
              </tr>
              @php $i++; @endphp
              @endforeach
          </table>
          <?php
		  $user = getUserDetails($order->user_id);
                   // dd($data);
                   // $xml = simplexml_load_string($data['bendataxml']);
                   // $json = json_encode($xml);
                   // $patient = json_decode($json,TRUE);
          ?>
          <h3>Patient Details</h3>
          <table class="table table-bordered table-hover">
              <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>E-Mail</th>
                <th>Mobile No.</th>
                <th>Address</th>
              </tr>
              <tr>
                <td>{{$order->order_by}}</td>
                <td>{{@$data['age']}}</td>
                <td>{{@$data['Gender']}}</td>
                <td>{{@$data['email']}}</td>
                <td>{{@$data['mobile']}}</td>
                <td>{{@$data['address']}}</td>
              </tr>
          </table>
		</div>

        <div class="panel-heading PriceDiv">
          <div class="priceBox section-1">
		  <table class="table">
		   <thead>
		   <tr>
		   <th>Date</th>
		   <th>Payment Mode </th>
		   <th>Transaction Id </th>
		   <th>Report Hard Copy </th>
		   <th>Applied Coupon</th>
		   <th style="text-align:center;">Total Amount</th>
		   </tr>
		   </thead>
		   <tbody>
		   <tr>
		   <!-- <td>@if($order->type == 0) @if(isset($orderAPI['leadHistoryMaster'][0]['bookedOn'][0]['date'])){{date('jS M, y',strtotime($orderAPI['leadHistoryMaster'][0]['bookedOn'][0]['date']))}} @endif @else {{date('jS M Y',$order->appt_date)}} @endif</td> -->
		   <td>{{ @$order->LabOrderTxn->trans_date ? date('d-m-y', strtotime($order->LabOrderTxn->trans_date)) : '' }}</td>

		   <td>{{$order->pay_type}}</td>
		   <td>@if(@$order->LabOrderTxn->tracking_id!='null'){{@$order->LabOrderTxn->tracking_id}}@endif</td>
		   <td>{{$order->report_type}}</td>
		   <td>@if(!empty($coupanDetails)) {{@$coupanDetails->coupon_code}} @else - @endif</td>
		   <td style="text-align:center;">₹ {{$order->payable_amt}}</td>
		   </tr>
		   <tr>
		   <td style="text-align:right;" colspan="5"><strong>Price Discount</strong></td>
		   <td style="text-align:center;"><strong>- ₹ @if(!empty($order->discount_amt)) {{$order->discount_amt}} @else 0.00  @endif</strong></td>
		   </tr>
		   <tr>
		   <td style="text-align:right;" colspan="5"><strong>Coupon Discount</strong></td>
		   <td style="text-align:center;"><strong>- ₹ @if(!empty($order->coupon_amt)) {{$order->coupon_amt}} @else 0.00  @endif</strong></td>
		   </tr>
		   <tr>
		   <td style="text-align:right;" colspan="5"><strong>Hard Copy Charges</strong></td>
		   <td style="text-align:center;"><strong>+ ₹ @if($order->report_type == 'yes') 75 @else 0.00  @endif</strong></td>
		   </tr>
		   <tr>
		   <td style="text-align:right;" colspan="5"><strong>Service Charges</strong></td>
		   <td style="text-align:center;"><strong>+ ₹  {{$order->service_charge}} </strong></td>
		   </tr>
		   <tr>
		   <td style="text-align:right;" colspan="5"><strong>Paid Total</strong></td>
		   <td style="text-align:center;"><strong>₹ {{$order->payable_amt}}</strong></td>
		   </tr>
		   </tbody>
		  </table>
			</div>
			</div>
			</div>
			@if(!empty($order->LabReports))
			@if($order->LabReports->company_id == 2)
			<a target="_blank" href="{{@$order->LabReports->report_pdf_name}}"><span class="label-default label label-primary" title="Download Report">Download Report</span></a>
			@else
			
		
			@endif	
			@endif
			@if($order->type != 0 && $order->status == 1)
			<div class="uploadConsultationTop">
				<form action="{{route('admin.uploadReport')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="uploadConsultation123">
					<input type="hidden" name="id" value="{{@$order->LabReports->id}}"/>
					<input type="hidden" name="company_id" @if($order->type == '0') value="2" @else value="{{$order->type}}" @endif/>
					<input type="hidden" name="user_id" value="{{$order->user_id}}"/>
					<input type="hidden" name="order_id" value="{{$order->orderId}}"/>
					<input type="hidden" name="old_report" value="{{@$order->LabReports->report_pdf_name}}" />
					<input type="file" name="new_report" required  accept="image/jpeg,image/png,image/jpg,application/pdf" />
					</div>
					<div class="uploadConsultation">
					<button type="submit">Upload Lab Report</button>
					<div class="DownloadReportP">
			<a target="_blank" href="<?=url("/")."/public/others/lab-reports/".@$order->LabReports->report_pdf_name?>"><span class="label-default label label-primary" title="Download Report">Download Report</span></a>	
			</div>
					</div>
				</form>
				@if(@$order->LabReports->report_pdf_name)<p>Note : If you upload new lab Report, Old file will be deleted.</p>@endif
				
			</div>
			@endif
			@if($order->type != 0 && $order->status == 1)
			<div class="uploadConsultationTop">
				<form action="{{route('admin.uploadoriginReport')}}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="uploadConsultation123">
					<input type="hidden" name="id" value="{{@$order->LabReports->id}}"/>
					<input type="hidden" name="company_id" @if($order->type == '0') value="2" @else value="{{$order->type}}" @endif/>
					<input type="hidden" name="user_id" value="{{$order->user_id}}"/>
					<input type="hidden" name="order_id" value="{{$order->orderId}}"/>
					<input type="hidden" name="old_origin_report" value="{{@$order->LabReports->origin_lab_report}}" />
					<input type="file" id="image-file" name="new_origin_report" required  accept="image/jpeg,image/png,image/jpg,application/pdf" />
					</div>
					<div class="uploadConsultation">
					<button type="submit">Upload Lab Original Report</button>
					<div class="DownloadReportP666">
			<a target="_blank" href="<?=url("/")."/public/others/lab-origin-reports/".@$order->LabReports->origin_lab_report?>"><span class="label-default label label-primary" title="Download Report">Download Original Report</span></a>	
			</div>
					</div>
				</form>
				@if(@$order->LabReports->origin_lab_report)<p>Note : If you upload new lab Report, Old file will be deleted.</p>@endif
				
			</div>
			
			@endif
		</div>
		<div class="modal-footer">
		
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>



		<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>


<script>
$('#image-file').on('change', function() {
var fileSize= this.files[0].size / 1024;
if(fileSize>2024){
	alert("File size should not be greater than 2 mb");
	$('#image-file').val('');
	return false;
}

});
</script>

<script>
$(document).ready(function() {
    // Handle click event of the update button

	var initialValue = $('.payment_mode_type').val();
	   $(".trackBlock").hide();
		$(".txnDate").hide();
		$(".submit").hide();

	if(initialValue == 4 || initialValue == 7){
		$(".trackBlock").show();
		$(".txnDate").show();
		$(".submit").show();
	}
	
  
});

// Payment Mode---------------------------------------------------------------------------------------------------------------------------------


// Online = 1, Cheque = 2,Cash = 3, Admin Online = 4, Free = 5 , Payment Link online = 6 

jQuery(document).on("change", ".payment_mode_type", function () {
		var type = $(this).find('option:selected').val();
		console.log("type" , type);
		$(".trackBlock").hide();
		$(".txnDate").hide();
		$(".submit").hide();
	
		// if(type == '2'){
		// 	$(".payment_mode_cheque").show();
		// }
		    if(type == '4') {
			$(".trackBlock").show();
			$(".txnDate").show();
			$(".submit").show();
			$("#tracking_id").val(""); // Empty the tracking ID input
            $("#trans_date").val(""); 
		}
		else if(type == '7') {
			$(".trackBlock").show();
			$(".txnDate").show();
			$(".submit").show();
		 	$("#tracking_id").val(""); // Empty the tracking ID input
            $("#trans_date").val(""); 
		}
		else{
			alert("Payment Mode Changed Successfully");
			location.reload();
		}
	});
	function copyText(text) {
  var input = document.body.appendChild(document.createElement("input"));
  input.value = text;
  input.select();
  document.execCommand('copy');
  input.parentNode.removeChild(input);


}

// $(".submit").on("click", function() {
// 	alert("Payment Mode Changed Successfully");
//     location.reload();
// });

$( ".transactionDate" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true,
});



</script>


